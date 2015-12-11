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
	
	class Language
	{
		private static $_language;
		private static $_dictionary;

		public static function load() {
			$language = Conf::$_conf['preset']['language_default'];
			if (!empty($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
				$exp = explode(',', $_SERVER['HTTP_ACCEPT_LANGUAGE']);
				if (!empty($exp[1])) {
					$exp = explode(';', $exp[1]);
					if (!empty($exp[0])) {
						$lang = strtolower(trim($exp[0]));
						if (file_exists(Path::$_path['translation'].$lang.'.php')) {
							$language = $lang;
						}
					}
				}
			}
			
			self::setLanguage($language);
		}

		#########################################################################
		####################### GLOBALS LANGUAGE METHODS ########################
		#########################################################################

		public static function setLanguage($language) {
			if (file_exists(Path::$_path['translation'].$language.'.php')) {
				self::$_language = $language;
				self::$_dictionary = require(Path::$_path['translation'].self::$_language.'.php');
			} else {
				throw new \Exception('Language to your application do not exists in translation file: ' . Path::$_path['translation'].$language.'.php');
			}
		}

		public static function getLanguage(){
			return self::$_language;
		}

		public static function translation($content=null) {
			if (isset($content)) {
				if (!is_array(self::$_dictionary)) {
					throw new \Exception('$_dictionary must be an array. In: ' . Path::$_path['translation'].self::$_language.'.php');
				}

				foreach (self::$_dictionary as $tag => $translation) {
					$content = str_replace('<%'.strtoupper($tag).'%>', $translation, $content);
				}

				return $content;
			} else {
				throw new \Exception('$content is required in Language::translation($content);');
			}
		}
	}