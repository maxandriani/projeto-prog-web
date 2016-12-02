<?php

class Response {

	/**
	* Imprime uma resposta no formato JSON para uma requisição AJAX
	* Essa função recebe dois parâmetros, o status code da requisição e o conteúdo.$_COOKIE
	*
	* $status {int} HTTP Status code (200)
	* $data {array} Array de valores a ser enviado ao cliente
	*/
	function json($data, $status = 200){
		$response = new stdClass();
		$response->errors = [];
		$response->data = $data;
		$this->render($status, $response);
	}

	/**
	* Essa função recebe um objeto de erro e imprime uma resposta de erro no formato JSON
	* 
	* $exception {Exception} Objeto de erro nativo do PHP
	* $code {int} Código de erro HTTP personalizado. Valor padrão (500)
	*/
	function error($exception, $code = 500){
		$response = new stdClass();
		$response->errors = array();
		$response->errors[] = $exception->getMessage(); // $sception->getMessage retorna a mensagem de erro do objeto
		$this->render($code, $response);
	}

	function render( $code, $data ) {
		http_response_code($code);
		echo json_encode($data);
		exit();
	}
}