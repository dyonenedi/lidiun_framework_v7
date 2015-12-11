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

	class Render
	{
		private static $_reply = [];

		public static function load() {
			// Run Global Render
			self::runGlobalRende();

			// Run Controller
			self::runController();

			// Database close conection
			Database::close();	

			// Delivery response
			self::deliveryResponse();
		}

		private static function runGlobalRende(){
			if (file_exists(Path::$_path['controller'] . 'globalController.php')) {
				Autoload::includePath('mvc'. SEPARATOR .'controller');
				$globalController = 'globalController';
				$globalController = new $globalController;
				$globalcontroller = null;
			}
		}
		
		private static function runController(){
			$isAjax = Request::isAjax();
			if ($isAjax) {
				Autoload::includePath('mvc' . SEPARATOR . 'controller' . SEPARATOR . 'ajax');
			} else {
				Autoload::includePath('mvc' . SEPARATOR . 'controller' . SEPARATOR . 'web');
			}

			Layout::loadContent();
			$controller = Request::getController();
			if (class_exists($controller)) {
				$app = new $controller;
			} else {
				Request::redirect('notfound');
			}
			$app = null;
		}
		
		private static function deliveryResponse(){
			$isAjax = Request::isAjax();
			if ($isAjax) {
				if (empty(self::$_reply) || !is_array(self::$_reply)) {
					exit('No reply seted by ajax controller or reply is not an array');
				}

				foreach (self::$_reply as $key => $value) {
					if ($key != 'reply' && !is_numeric($value) && !is_array($value) && $value) {
						self::$_reply[$key] = Language::translation($value);
					}
				}

				Request::responseHeader();
				echo json_encode(self::$_reply);
			} else {
				Layout::MenuFooterAutoload();
				Layout::replaceTag();
				Layout::translation();

				Request::responseHeader();
				echo Layout::getLayout();
			}
		}

		##########################################################################
		############################ PUBLIC METHODS ##############################
		##########################################################################

		public static function setReply($reply){
			self::$_reply = $reply;
		}
	}