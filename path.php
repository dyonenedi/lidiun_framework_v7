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
	
	class Path
	{
		public static $_path;
		
		public static function load() {
			// Set paths to your application
			self::$_path['app'] = PUBLIC_DIRECTORY . SEPARATOR . '..' . SEPARATOR;

			self::$_path['conf']        = self::$_path['app'] . 'conf' . SEPARATOR;
			self::$_path['mvc']         = self::$_path['app'] . 'mvc' . SEPARATOR;
			self::$_path['plugin']      = self::$_path['app'] . 'plugin'  . SEPARATOR;
			self::$_path['translation'] = self::$_path['app'] . 'translation' . SEPARATOR;
			self::$_path['public']      = PUBLIC_DIRECTORY . SEPARATOR;

			foreach (Conf::$_conf['public_path'] as $key => $value) {
				$value = str_replace(SEPARATOR, ' ', $value);
				$value = trim($value);
				$value = str_replace(' ', SEPARATOR, $value);
				
				self::$_path[$key] = self::$_path['public'] . $value . SEPARATOR;
			}
			
			self::$_path['model'] = self::$_path['mvc'] . 'model' . SEPARATOR;

			self::$_path['view']    = self::$_path['mvc'] . 'view' . SEPARATOR;
			self::$_path['layout']  = self::$_path['view'] . 'layout' . SEPARATOR;
			self::$_path['content'] = self::$_path['view'] . 'content' . SEPARATOR;
			self::$_path['segment'] = self::$_path['view'] . 'segment' . SEPARATOR;
			
			self::$_path['controller'] = self::$_path['mvc'] . 'controller' . SEPARATOR;
			self::$_path['ajax']       = self::$_path['controller'] . 'ajax' . SEPARATOR;
			self::$_path['web']        = self::$_path['controller'] . 'web' . SEPARATOR;	
		}
	}