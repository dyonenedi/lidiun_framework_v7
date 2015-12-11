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
	
	class Link
	{
		public static $_link;

		public static function load() {
			// Set links to your application
			self::$_link['public'] = '/';

			foreach (Conf::$_conf['public_path'] as $key => $value) {
				$value = str_replace(SEPARATOR, ' ', $value);
				$value = trim($value);
				$value = str_replace(' ', '/', $value);
				
				self::$_link[$key] = self::$_link['public'] . $value . '/';
			}
		}
	}