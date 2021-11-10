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
	use Lidiun_Framework_v7\Conf;
	
	Class Url
	{	
		public static $_url = [];

		public static function load() {	
			$protocol = explode('/', $_SERVER['SERVER_PROTOCOL']);
			$protocol = strtolower($protocol[0]);
			$protocol = $protocol . '://';

			$host = Conf::$_conf['host'];
			$port = (!empty($_SERVER['SERVER_PORT'])) ? ':' . $_SERVER['SERVER_PORT']: '';

			$uri = (!empty($_GET['uri'])) ? $_GET['uri'] : false;
			$uri = str_replace('/', ' ', $uri);
			$uri = trim($uri);
			$uri = str_replace(' ', '/', $uri);
			if ($uri) {
				$url = $protocol . $host . $port . '/' . $uri . '/';
				$uri = explode('/', $uri);
			} else {
				$url = $protocol . $host . '/';
				$uri = [];
			}

			self::$_url['protocol'] = $protocol;
			self::$_url['host']     = $host;
			self::$_url['port']     = $port;
			self::$_url['base']     = $protocol . $host . ((Conf::$_conf['preset']['server'] == 'developer') ? $port : "") . '/';
			self::$_url['uri']      = $uri;
			self::$_url['full']     = $url;
		}
	}
