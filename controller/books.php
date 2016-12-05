<?php
require_once 'model/database.php';
require_once 'model/response.php';

/**
 * Gerencia o controle, acesso e registro de usuários
 */
class BooksController {
	// MÉTODO POST
	// Cadastra um novo usuário
	public static function register(){

		$response = new Response();

		// prepare args
		// isset ( $_POST['arg'] ) => Verifica se existe o argumento 'arg' dentro do array $_POST
		// Se sim, atribui o valor de $_POST['arg'] à variável, se não, preenche a variável com NULL
		$title = (isset($_POST['title'])) ? $_POST['title'] : null;
		$description = (isset($_POST['description'])) ? $_POST['description'] : null;
		$category_id = (isset($_POST['category_id'])) ? intval($_POST['category_id']) : null; // intval = converte o valor para inteiro
		$author = (isset($_POST['author'])) ? $_POST['author'] : null;
		$year = (isset($_POST['year'])) ? intval($_POST['year']) : null;
		$price = (isset($_POST['price'])) ? floatval($_POST['price']) : null; // floatval = converte o valor para float (numero com virgula)
		$cover = (isset($_FILES['cover'])) ? $_FILES['cover'] : null;

		$db = new Database();
		$db->conn->beginTransaction();

		try { // se houver algum erro dentro desse bloco, o bloco catch será chamado
			
			// Tudo válido até aqui, então verifica se o livro já existe
			$query_check = 'Select liv_id as id From livros as l Where l.titulo = :title And l.autor = :author Limit 1;';
			$stmt = $db->conn->prepare( $query_check );
			$stmt->execute( array(
				'title' => $title,
				'author'=> $author
			));
			$result = $stmt->fetchAll();

			// Se os resultados forem maiores que zero, logo, o livro já existe
			if (count($result) > 0){
				throw new Exception('Este livro já está cadastrado no sistema');
			}

			// Primeiro insere o livro sem a capa, para depois de obter o ID, registrar a capa
			$insert_data = array(
				'titulo' => $title,
				'descricao' => $description,
				'categoria_id' => $category_id,
				'autor' => $author,
				'ano' => $year,
				'preco' => $price
			);
			$db->insert('livros', $insert_data, $db->conn);

			// retrives book_id
			$id = $db->conn->lastInsertId();

			$file_path = 'covers/'.$id.'_'.$_FILES['cover']['name'];
			if (!move_uploaded_file($_FILES['cover']['tmp_name'], $file_path)) {
				throw new Exception("Falha ao salar arquivo");
			}

			// File saved, so lets update book
			$query_update = 'Update livros Set paph = :cover Where liv_id = :book_id Limit 1;';
			$stmt = $db->conn->prepare( $query_update );
			$stmt->execute(array(
				'book_id' => $id,
				'cover' => $file_path
			));

			// Tudo certo...
			$book = new stdClass();
			$book->id = $id;
			$book->title = $title;
			$book->description = $description;
			$book->cover = $cover;
			$book->author = $author;
			$book->price = $price;
			$book->year = $year;

			$db->conn->commit();
			$response->json( $book );
		} catch (Exception $e) {
			$db->conn->rollBack();
			$response->error( $e );
		}
	}

	public static function remove(){
		$response = new Response();

		// Parse request data to get book_id
		$data = file_get_contents("php://input");
		parse_str($data, $_POST);

		$book_id = (isset($_POST['book_id'])) ? $_POST['book_id'] : null;

		$db = new Database();
		$db->conn->beginTransaction();

		try { // se houver algum erro dentro desse bloco, o bloco catch será chamado
			// Pega o endereço da capa
			$cover_query = 'Select paph as cover From livros Where liv_id = :liv_id Limit 1;';
			$stmt = $db->conn->prepare( $cover_query );
			$stmt->execute(array(
				'liv_id' => $book_id
			));
			$data = $stmt->fetchAll();

			@unlink($data[0]->cover);

			// Remove todos os registros do banco de dados onde id = $book_id
			$result = $db->remove('livros', array(
				'liv_id' => $book_id
			), $db->conn);

			$db->conn->commit();
			$response->json( true );
		} catch (Exception $e) {
			$db->conn->rollBack();
			$response->error( $e );
		}
	}

	public static function search(){
		$response = new Response();

		// Verifica se existem strings de busca
		$search = (isset($_GET['search']))? $_GET['search'] : null;

		try {
			$db = new Database();

			// Começa a montar a query de busca
			$values = array();
			$query =  'Select '.
							'l.liv_id as id, '.
							'l.titulo as title, '.
							'l.descricao as description, '.
							'c.descricao as category, '.
							'c.categoria_id as category_id, '.
							'l.autor as author, '.
							'l.ano as year, '.
							'l.preco as price, '.
							'l.paph as cover '.
						'From '.
							'livros as l '.
						'Join '.
							'categorias as c On l.categoria_id = c.cat_id ';
			// Se houver string, então complementa a query
			if ($search){
				$search = str_replace("\s", "%", trim($search));
				$search = '%' . $search . '%';
				$query .= 'Where '.
						'l.titulo LIKE :search '.
						'Or l.autor LIKE :search '.
						'Or l.descricao LIKE :search ';
				$values['search'] = $search;
			}

			$query .= 'Order By '.
							'c.descricao Asc, '.
							'l.titulo Asc ;';

			// Cria o statement
			$stmt = $db->conn->prepare( $query );
			$result = $stmt->execute( $values );

			// Converte os resultados em um vetor de objetos
			$books = $stmt->fetchAll(PDO::FETCH_OBJ);

			$response->json( $books );
		} catch (Exception $e) {
			$response->error($e);
		}
	}

	public static function get_categories(){
		$response = new Response();

		try {
			$query = 'Select cat_id as id, descricao as description From categorias;';
			$db = new Database();
			$stmt = $db->conn->prepare( $query );
			$result = $stmt->execute();

			$categories = $stmt->fetchAll(PDO::FETCH_OBJ);
			
			$response->json($categories);
		} catch(Exception $e) {
			$response->error($e);
		}
	}
}