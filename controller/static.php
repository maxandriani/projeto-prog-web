<?php
/**
 * Controller responsável por renderizar páginas HTML estáticas
 */

class StaticController {
	public static function public(){
		require_once 'view/public.html.php';
	}

	public static function restrict(){
		require_once 'view/restrict.html.php';
	}
}