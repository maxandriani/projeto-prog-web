<?php
require_once 'model/database.php';
require_once 'model/response.php';

/**
 * Gerencia o controle, acesso e registro de usuários
 */
class AccountsController {
	// MÉTODO POST
	// Cadastra um novo usuário
	public static function register(){

		$response = new Response();

		// prepare args
		// isset ( $_POST['arg'] ) => Verifica se existe o argumento 'arg' dentro do array $_POST
		// Se sim, atribui o valor de $_POST['arg'] à variável, se não, preenche a variável com NULL
		$name = (isset($_POST['name'])) ? $_POST['name'] : null;
		$email = (isset($_POST['email'])) ? $_POST['email'] : null;
		$pass = (isset($_POST['pass'])) ? $_POST['pass'] : null;

		try { // se houver algum erro dentro desse bloco, o bloco catch será chamado
			
			// Tudo válido até aqui, então chamamos o model e salvamos o usuário
			$db = new Database();
			$result = $db->insert(
				'usuarios', 
				array( 
					'nome' => $name,
					'email' => $email,
					'senha' => md5( $pass ) // criptografa a senha 
				)
			);

			$user = new stdClass();
			$user->name = $name;
			$user->email = $email;
			$user->id = $result;

			$response->json( $user );
		} catch (Exception $e) {
			$response->error( $e );
		}
	}

	public static function login(){
		$response = new Response();

		$email = (isset($_POST['email'])) ? $_POST['email'] : null;
		$pass = (isset($_POST['pass'])) ? $_POST['pass'] : null;

		try { // se houver algum erro dentro desse bloco, o bloco catch será chamado
			
			// Tudo válido até aqui
			$db = new Database();

			$query = 'Select u.user_id as id From usuarios as u Where u.email = :email And u.senha = :pass Limit 1;';
			$stmt = $db->conn->prepare($query);
			$stmt->execute( array(
				'email' => $email,
				'pass' => md5( $pass )
			));

			// Converte o resultado em um array
			$result = $stmt->fetchAll();

			if (count($result) != 1){
				// Usuário não encontrado ou senha incorreta
				throw new Exception('Acesso não autorizado');
			}

			$user = new stdClass();
			$user->id = $result[0]['id'];
			$user->email = $email;
			$user->pass = $pass;

			$_SESSION['library'] = json_encode($user);

			$response->json( $user );
		} catch (Exception $e) {
			$response->error( $e );
		}
	}

	public static function logout(){
		$response = new Response();

		try {
			session_destroy();
			$response->json(true);
		} catch (Exception $e) {
			$response->error($e);
		}
	}

	public static function is_user_logged_in(){
		return (isset($_SESSION['library']));
	}
}