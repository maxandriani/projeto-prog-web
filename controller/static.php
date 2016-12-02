<?php
/**
 * Controller responsável por renderizar páginas HTML estáticas
 */

class StaticController {
	public static function public_page(){
		require_once 'view/public.html.php';
	}

	public static function restrict_page(){
		require_once 'view/restrict.html.php';
	}

	public static function not_found(){
		http_response_code(404);
		exit();
	}
}