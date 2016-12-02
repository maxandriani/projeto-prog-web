<?php
/**
 * Prepara as rotas e chama os controladores
 */
session_start();

require_once 'controller/books.php';
require_once 'controller/accounts.php';
require_once 'controller/static.php';
 
$route = (isset($_GET['q']))? $_GET['q'] :  null; // Recupera o parâmetro GET q=/rota, nele será informado o que se quer fazer
$method = $_SERVER['REQUEST_METHOD'];

if (AccountsController::is_user_logged_in()){ // Verifica se existe um usuário logado
	// Rotas privadas
	switch( $route ){
		case '/books':
			if ($method == 'GET'){
				// GET Recupera lista de livros
				BooksController::search();
			} else if ($method == 'POST'){
				// POST Cria um novo livro
				BooksController::register();
			} else if ($method == 'DELETE'){
				// DELETE Remove um livro
				BooksController::remove();
			} else {
				// Para métodos desconhecidos, responde 404
				StaticController::not_found();
			}
			break;
		case '/books/categories':
			if ($method == 'GET'){
				// GET, Recupera lista de categorias
				BooksController::get_categories();
			} else {
				StaticController::not_found();
			}
		case '/account/logout':
			// Para qualquer método, Destroi a sessão corrente
			AccountsController::logout();
			break;
		default:
			// Se nenhuma das condições for satisfeita, imprime a lista de livros
			StaticController::restrict();
	}
} else {
	// Rotas públicas
	switch( $route ){
		case '/account/login':
			// Se for POST, Autentica o usuário
			if ($method == 'POST'){
				AccountsController::login();
			} else {
				StaticController::not_found();
			}
			break;
		case '/account':
			// Se for POST, Cria um novo usuário
			if ($method == 'POST'){
				AccountsController::register();
			} else {
				StaticController::not_found();
			}
			break;
		default:
			// Se nenhuma das condições for satisfeita, imprime a página de login
			StaticController::public();
	}
}