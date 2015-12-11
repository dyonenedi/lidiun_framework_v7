<?php
	/**********************************************************
	* Lidiun PHP Framework 6.0 - (http://www.lidiun.com)
	*
	* @Created in 26/08/2013
	* @Author  Dyon Enedi <dyonenedi@hotmail.com>
	* @By Dyon Enedi <dyonenedi@hotmail.com>
	* @Contributor Gabriela A. Ayres Garcia <gabriela.ayres.garcia@gmail.com>
	* @License: free
	*
	**********************************************************/
	
	namespace Lidiun_Framework_v6;
	
	class Request
	{
		private static $_parameter;
		private static $_post;
		private static $_get;
		private static $_files;
		private static $_controller;
		private static $_entity;
		private static $_ajax;
		private static $_webHeader;
		private static $_ajaxHeader;
		
		public static function load() {
			// Set Path
			Path::load();

			// Set Link
			Link::load();

			// Set URL
			Url::load();

			// Set Language
			Language::load();	

			// Set parameters
			self::_setParameters();

			// Set Controller
			self::_setController();
			
			// Set Headers
			self::_setDefaultHeader();
		}

		#########################################################################
		############################ PRIVATE METHODS ############################
		#########################################################################
		
		private static function _setParameters(){
			// Treat parameters
			if (!empty(Url::$_url['uri']) && is_array(Url::$_url['uri'])) {
				foreach (Url::$_url['uri'] as $value) {
					self::$_parameter[] = $value;
				}
			}

			if (!empty($_POST) && is_array($_POST)) {
				foreach ($_POST as $key => $value) {
					self::$_post[$key] = $value;
				}
			}

			if (!empty($_GET) && is_array($_GET)) {
				foreach ($_GET as $key => $value) {
					self::$_get[$key] = $value;
				}
			}

			if (!empty($_FILES) && is_array($_FILES)) {
				foreach ($_FILES as $key => $value) {
					self::$_files[$key] = self::organizeArray($value);
				}
			}

			if (empty(self::$_parameter)) {
				self::$_parameter[0] = Conf::$_conf['preset']['controller_default'];
			}

			
		}

		private static function _setController(){
			if (empty(self::$_controller)) {
				// If is an ajax request
				if ((!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest')) {
					$pathController = Path::$_path['ajax'];
					self::$_ajax = true;
				} else {
					$pathController = Path::$_path['web'];
				}

				if (file_exists($pathController.self::$_parameter[0].'_controller.php')) {
					// If exists a controller with first parameter
					self::$_controller = strtolower(self::$_parameter[0]);
					self::unsetParameter(0);
				} else if (!empty(self::$_parameter[1]) && file_exists($pathController.self::$_parameter[1].'_controller.php')) {
					// If exists a controller with second parameter	
					self::$_entity = strtolower(self::$_parameter[0]);
					self::$_controller = strtolower(self::$_parameter[1]);
					self::unsetParameter(0);
					self::unsetParameter(1);
				} else {
					self::redirect('notfound');
				}
			}

			self::orderParameter();
		}

		private static function _setDefaultHeader(){
			self::$_webHeader = [
				'Content-Type: text/html; charset=UTF-8',
				'Access-Control-Allow-Methods: GET, POST',
				'Access-Control-Allow-Headers: *',
			];

			self::$_ajaxHeader = [
				'Content-Type: application/json; charset=UTF-8',
				'Access-Control-Allow-Methods: GET, POST',
				'Access-Control-Allow-Headers: *',
			];
		}

		#########################################################################
		########################### AUX METHODS ##############################
		#########################################################################

		private static function organizeArray($file){
			if (is_array($file['name'])) {
				foreach ($file as $key => $value) {
					foreach ($value as $k => $val) {
						$file[$k][$key] = $val;
						unset($file[$key][$k]);
					}
					unset($file[$key]);
				}
			} else {
				$file[0] = $file;
			}

			return $file;
		}

		private static function orderParameter(){
			$aux = self::$_parameter;
			self::$_parameter = [];
			foreach ($aux as $value) {
				array_push(self::$_parameter, $value);
			}
		}

		#########################################################################
		########################### PUBLIC METHODS ##############################
		#########################################################################

		public static function setController($controller){
			self::$_controller = $controller;
		}

		public static function isAjax(){
			return self::$_ajax;
		}

		public static function responseHeader() {
			$header = (self::$_ajax) ? self::$_ajaxHeader: self::$_webHeader;
			foreach ($header as $headerLine) {
				header($headerLine);
			}
		}

		/*
		* Usefull for developer
		*
		*/

		public static function setAjaxResponseHeader($header){
			self::$_ajaxHeader = $header;
		}

		public static function setWebResponseHeader($header){
			self::$_webHeader = $header;
		}

		public static function setParameter($parameter=false){
			if ($parameter) {
				array_push($_parameter, $parameter);
			}
		}

		public static function unsetParameter($key){
			unset(self::$_parameter[$key]);
		}

		public static function getParameter(){
			return self::$_parameter;
		}

		public static function getGet(){
			return self::$_get;
		}

		public static function getPost(){
			return self::$_post;
		}

		public static function getFiles(){
			return self::$_files;
		}

		public static function getEntity(){
			return self::$_entity;
		}

		public static function getController(){
			return self::$_controller;
		}

		public static function redirect($content){
			Redirect_controller::$_content = $content;
			self::setController("Lidiun_Framework_v6\Redirect");
		}

		public static function redirectTo($url){
			header('Location: ' . Url::$_url['base'] . $url);
			exit;
		}
	}