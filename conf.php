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
	
	class Conf
	{
		public static $_conf;
		
		public static function load($conf){
			self::$_conf = $conf;

			// Set Application Status
			if (strtolower(self::$_conf['preset']['application_status']) === 'off') {
				Request::redirect('offline');
			}

			// Set host
			self::$_conf['host'] = (isset($_SERVER['SERVER_NAME'])) ? strtolower($_SERVER['SERVER_NAME']) : strtolower($_SERVER['HTTP_HOST']);

			// Set environment
			foreach (self::$_conf['environment'] as $environment => $fullDomain) {
				if (strpos($fullDomain, '*.') === false) {
					if (self::$_conf['host'] == $fullDomain) {
						self::$_conf['preset']['server'] = $environment;
						break;
					}
				} else {
					$domain = str_replace("*.", '', $fullDomain);
					if (strrpos(self::$_conf['host'], $domain) !== false) {
						self::$_conf['preset']['server'] = $environment;
						break;
					}
				}
			}

			if (empty(self::$_conf['preset']['server'])) {
				throw new \Exception('Fatal Error: The host "'.self::$_conf['host'].'" don\'t found in environment. Please check environment in config to fix this problem.');
			}
			
			// Set debug mode
			self::$_conf['preset']['debug_mode'] = (self::$_conf['preset']['server'] == 'production') ? false : true;
			if (self::$_conf['preset']['debug_mode'] || self::$_conf['preset']['force_debug']) {
				ini_set('display_errors',1);
				ini_set('display_startup_erros',1);
				error_reporting(E_ALL);
			} else {
				ini_set('display_errors',0);
				ini_set('display_startup_erros',0);
				error_reporting(0);
			}

			// Set time zone
			date_default_timezone_set(self::$_conf['preset']['timezone']);

			// Start Session
			session_start();
		}
	}