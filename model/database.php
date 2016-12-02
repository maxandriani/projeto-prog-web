<?php
define('DB_HOST', 'localhost');
define('DB_BASE', 'livraria');
define('DB_USER', 'root');
define('DB_PASS', '');

class Database {
	public $conn;

	public function __construct(){ // construtor da classe
		$this->conn = $this->create_connection();
	}

	public function __destruct(){ // destrutor
		$this->conn = null;
	}

	/**
	 * Cria uma nova instância de banco de dados
	 * @return PDO
	 *
	 * O Public significa que a classe, seus dependentes e suas instâncias conhecem esse método
	 * O static significa que o método pode ser usado sem dar NEW, ex: Database::create_connection();
	 */
	public static function create_connection(){ // Cria uma nova conecção com o banco de dados
		$c = new PDO('mysql:host='.DB_HOST.';dbname='.DB_BASE.';charset=utf8', DB_USER, DB_PASS); // Inicia o objeto PDO com os dados do banco
		$c->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Diz que os errors de conexão deverão ser mostrados
		//$c->set_charset("utf8"); // Informa que será usado UTF8 como padrão
		return $c;
	}

	/**
	 * Insere dados no banco de dados
	 * @param $table {string} Nome da tabela
	 * @param $data {array} Um array de chave e valor com os campos a serem inseridos. Ex: array( 'titulo' => 'Meu Livro' )
	 * @param $conn {PDO} (opcional) Se passado, usa um PDO existente, se não, cria um novo
	 * @returns {PDO} 
	 */
	public static function insert($table, $data, $conn = ''){
		if ($conn == ''){
			$conn = Database::create_connection();
		}

		$query_bind = Database::to_query_string( $data );

		// Monta a query final
		$query = 'Insert Into '.$table.' (' . $query_bind['keys'] . ') Values (' . $query_bind['values'] . '); ';

		$stmt = $conn->prepare($query);
    return $stmt->execute( $data );
	}

	/**
	 * Remove um registro do banco de dados
	 * @param $table {string} Nome da tabela
	 * @param $where {array} Array de chave e valor dos parâmetros necessários para remover a linha. ex: array('id' => 1)
	 * @param $conn {PDO} (opcional) Se passado, usa um PDO existente, se não, cria um novo
	 * @returns {PDO}
	 */
	public static function remove($table, $where, $conn = ''){
		if ($conn == ''){
			$conn = Database::create_connection();
		}

		$query_bind = Database::to_query_string( $where );

		// Monta a query final
		$query = 'Delete From '.$table.' Where ' . $query_bind['where'] . ' ; ';

		$stmt = $conn->prepare( $query );
    return $stmt->execute( $where );
	}

	private static function to_query_string($data){
		$query['keys'] = '';
		$query['values'] = '';
		$query['where'] = '';

		foreach( $data as $key => $value ){ // Percorre o array de dados convertendo em $key e $value
			$query['keys'] .= $key . ', '; // 'titulo, '
			$query['values'] .= ':' . $key . ', '; // ':titulo, '
			$query['where'] .= $key . ' = :' . $key . ', ';
		}

		// Limpa o ultimo ', '
		$query['keys'] = rtrim($query['keys'], ', ');
		$query['values'] = rtrim($query['values'], ', ');
		$query['where'] = rtrim($query['where'], ', ');

		return $query;
	}
}