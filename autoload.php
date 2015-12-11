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
	
	namespace Lidiun;
	
	Class autoload
	{
		private static $init = false;
		private static $publicPath;

		public static function includePath($includePath) {
			if (!empty($includePath)) {
				if (is_array($includePath)) {
					foreach ($includePath as $path) {
				    	$path = str_replace('\\', DIRECTORY_SEPARATOR , $path);
				    	$path = str_replace('/', DIRECTORY_SEPARATOR , $path);

				    	$path = str_replace(DIRECTORY_SEPARATOR, ' ', $path);
						$path = trim($path);
						$path = str_replace(' ', DIRECTORY_SEPARATOR, $path);


						ini_set('include_path',ini_get('include_path') . PATH_SEPARATOR . self::$publicPath . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . $path . DIRECTORY_SEPARATOR);
				    }
				} else {
					$path = $includePath;
					$path = str_replace('\\', DIRECTORY_SEPARATOR , $path);
			    	$path = str_replace('/', DIRECTORY_SEPARATOR , $path);

			    	$path = str_replace(DIRECTORY_SEPARATOR, ' ', $path);
					$path = trim($path);
					$path = str_replace(' ', DIRECTORY_SEPARATOR, $path);
					
					ini_set('include_path',ini_get('include_path') . PATH_SEPARATOR . self::$publicPath . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . $path . DIRECTORY_SEPARATOR);
				}
			}
		}

		public static function init(){
		    if (!self::$init) {
			    self::$init = true;
			    self::$publicPath = PUBLIC_DIRECTORY;
			    
			    ini_set('include_path', PATH_SEPARATOR . self::$publicPath . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR);

			    spl_autoload_register(function ($className) {
				    $className = str_replace('\\', DIRECTORY_SEPARATOR , $className);
				    $className = str_replace('/', DIRECTORY_SEPARATOR , $className);

				    if (!include_once($className . '.php')) {
				    	throw new \Exception('Is not possible include the follow file: '.$className.'.php');
				    }
				});
		    }
		}
	}

