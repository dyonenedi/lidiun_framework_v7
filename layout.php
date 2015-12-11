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
	
	class Layout
	{
		public static $_content;
		private static $_layout;
		private static $_menu;
		private static $_footer;
		private static $_renderMenu;
		private static $_renderFooter;
		private static $_title;
		private static $_description;

		##########################################################################
		############################ PUBLIC METHODS ##############################
		##########################################################################

		public static function loadContent($content=null) {
			if (empty($content)) {
				$content = Request::getController();
				$content = substr($content, 0, strrpos($content, 'Controller'));
			}		
			
			if (file_exists(Path::$_path['content'] . $content . '.html')) {
				self::$_content = file_get_contents(Path::$_path['content'] . $content . '.html');
			}
		}

		public static function MenuFooterAutoload() {
			if (!empty(self::$_renderMenu)) {
				if (empty(self::$_menu)) {
					if (file_exists(Path::$_path['view'] . 'layout' . SEPARATOR . 'menu.html')) {
						self::$_menu = file_get_contents(Path::$_path['view'] . 'layout' . SEPARATOR . 'menu.html');
					} else {
						throw new \Exception('I can\'t autoload menu.html with: "Layout::layoutAutoload()" File: "' . Path::$_path['view'] . 'layout' . SEPARATOR . 'menu.html" do not exists');
					}
				}
			} else {
				self::$_menu = '';
			}

			if (!empty(self::$_renderFooter)) {
				if (empty(self::$_footer)) {
					if (file_exists( Path::$_path['view'] . 'layout' . SEPARATOR . 'footer.html')) {
						self::$_footer = file_get_contents(Path::$_path['view'].'layout' . SEPARATOR . 'footer.html');
					} else {
						throw new \Exception('I can\'t autoload footer.html with: "Layout::layoutAutoload()" File: "'.Path::$_path['view'].'layout' . SEPARATOR . 'footer.html" do not exists');
					}
				}
			} else {
				self::$_footer = '';
			}
		}

		public static function replaceTag() {
			$content = (!empty(self::$_content)) ? self::$_content : '';
			$menu = (!empty(self::$_menu)) ? self::$_menu : '';
			$footer = (!empty(self::$_footer)) ? self::$_footer : '';

			$commonCss = '';
			$commonJs = '';

			if (!empty(Conf::$_conf['common_files']['css']) && is_array(Conf::$_conf['common_files']['css'])) {
				foreach (Conf::$_conf['common_files']['css'] as $value) {
					if (strpos($value, 'http') !== false) {
						$commonCss .= '<link href="'.$value.'" rel="stylesheet" type="text/css"/>';
					} else {
						$commonCss .= '<link href="'.Link::$_link['css'].$value.'" rel="stylesheet" type="text/css"/>';
					}
				}
			}

			if (!empty(Conf::$_conf['common_files']['js']) && is_array(Conf::$_conf['common_files']['js'])) {
				foreach (Conf::$_conf['common_files']['js'] as $value) {
					if (strpos($value, 'http') !== false) {
						$commonJs .= '<script src="'.$value.'" type="text/javascript"></script>';
					} else {
						$commonJs .= '<script src="'.Link::$_link['js'].$value.'" type="text/javascript"></script>';
					}
				}
			}

			$additionalCss = '';
			$additionalJs = '';

			if (!empty(Conf::$_conf['common_files']['additional_css']) && is_array(Conf::$_conf['common_files']['additional_css'])) {
				foreach (Conf::$_conf['common_files']['additional_css'] as $value) {
					if (strpos($value, 'http') !== false) {
						$additionalCss .= '<link href="'.$value.'" rel="stylesheet" type="text/css"/>';
					} else {
						$additionalCss .= '<link href="'.Link::$_link['css'].$value.'" rel="stylesheet" type="text/css"/>';
					}
				}
			}

			if (!empty(Conf::$_conf['common_files']['additional_js']) && is_array(Conf::$_conf['common_files']['additional_js'])) {
				foreach (Conf::$_conf['common_files']['additional_js'] as $value) {
					if (strpos($value, 'http') !== false) {
						$additionalJs .= '<script src="'.$value.'" type="text/javascript"></script>';
					} else {
						$additionalJs .= '<script src="'.Link::$_link['js'].$value.'" type="text/javascript"></script>';
					}
				}
			}

			$title = (!empty(self::$_title)) ? self::$_title: ucwords(substr(Request::getController() , 0, strrpos(Request::getController(), 'Controller')));
			$description = (!empty(self::$_description)) ? self::$_description: Conf::$_conf['preset']['description'];

			$layout = self::getView('layout' . SEPARATOR . 'layout');
			$layout = str_replace('<%CONTENT%>', $content, $layout);
			$layout = str_replace('<%MENU%>', $menu, $layout);
			$layout = str_replace('<%FOOTER%>', $footer, $layout);
			$layout = str_replace('<%SITE_TAG%>', Url::$_url['base'], $layout);
			$layout = str_replace('<%APP_NAME_TAG%>', Conf::$_conf['preset']['app_name'], $layout);
			$layout = str_replace('<%TITLE_TAG%>', $title, $layout);
			$layout = str_replace('<%AUTHOR_TAG%>', Conf::$_conf['preset']['author'], $layout);
			$layout = str_replace('<%DESCRIPTION_TAG%>', $description, $layout);
			$layout = str_replace('<%KEY_WORD_TAG%>', Conf::$_conf['preset']['key_word'], $layout);
			$layout = str_replace('<%LANGUAGE_TAG%>', Language::getLanguage(), $layout);
			
			$layout = str_replace('<%COMMON_CSS_PATH%>', $commonCss, $layout);
			$layout = str_replace('<%ADDITIONAL_CSS_PATH%>', $additionalCss, $layout);
			
			$layout = str_replace('<%COMMON_JS_PATH%>', $commonJs, $layout);
			$layout = str_replace('<%ADDITIONAL_JS_PATH%>', $additionalJs, $layout);
			
			if (!empty(Link::$_link) && is_array(Link::$_link)) {
				foreach (Link::$_link as $key => $value) {
					$layout = str_replace('<%'.strtoupper($key).'_PATH%>', $value, $layout);
				}
			}

			self::$_layout = $layout;
		}

		public static function translation() {
			self::$_layout = Language::translation(self::$_layout);

		}

		public static function getLayout() {
			return self::$_layout;
		}

		public static function haveContent() {
			if (!empty(self::$_content)) {
				return true;
			} else {
				return false;
			}
		}

		/**********************************************************
		* Methods used by developer to help render layout.
		*
		**********************************************************/

		public static function replaceLayout($search, $replace) {
			self::$_layout = str_replace('<%'.strtoupper($search).'%>', $replace, self::$_layout);
		}

		public static function replaceContent($search, $replace) {
			self::$_content = str_replace('<%'.strtoupper($search).'%>', $replace, self::$_content);
		}

		public static function replaceMenu($search, $replace) {
			if (empty(self::$_menu)) {	
				if (file_exists(Path::$_path['view'].'layout' . SEPARATOR . 'menu.html')) {
					self::$_menu = file_get_contents(Path::$_path['view'].'layout' . SEPARATOR . 'menu.html');
				} else {
					throw new \Exception('I can\'t autoload menu.html with: "Layout::layoutAutoload()" File: "'.Path::$_path['view'].'layout' . SEPARATOR . 'menu.html" do not exists');
				}
			}

			self::$_menu = str_replace('<%'.strtoupper($search).'%>', $replace, self::$_menu);
		}

		public static function replaceFooter($search, $replace) {
			if (empty(self::$_footer)) {
				if (file_exists(Path::$_path['view'].'layout' . SEPARATOR . 'footer.html')) {
					self::$_footer = file_get_contents(Path::$_path['view'].'layout' . SEPARATOR . 'footer.html');
				} else {
					throw new \Exception('I can\'t autoload footer.html with: "Layout::layoutAutoload()" File: "'.Path::$_path['view'].'layout' . SEPARATOR . 'footer.html" do not exists');
				}
			}

			self::$_footer = str_replace('<%'.strtoupper($search).'%>', $replace, self::$_footer);
		}

		public static function replaceView($search, $replace, $view) {
			return str_replace('<%'.strtoupper($search).'%>', $replace, $view);
		}

		public static function getView($view) {
			if (file_exists(Path::$_path['view'].$view.'.html') && ($view = file_get_contents(Path::$_path['view'].$view.'.html'))) {
				return $view; 
			} else {
				throw new \Exception('I can\'t get view with: "Layout::getView()" File: "'.Path::$_path['view'].$view.'.html" do not exists');
			}
		}

		public static function getContent($content) {
			if (file_exists(Path::$_path['content'].$content.'.html') && ($content = file_get_contents(Path::$_path['content'].$content.'.html'))) {
				return $view; 
			} else {
				throw new \Exception('I can\'t get content with: "Layout::getContent()" File: "'.Path::$_path['content'].$content.'.html" do not exists');
			}
		}

		public static function getSegment($segment) {
			if (file_exists(Path::$_path['segment'].$segment.'.html') && ($segment = file_get_contents(Path::$_path['segment'].$segment.'.html'))) {
				return $segment; 
			} else {
				throw new \Exception('I can\'t get segment with: "Layout::getSegment()" File: "'.Path::$_path['segment'].$segment.'.html" do not exists');
			}
		}

		public static function putContent($html) {
			self::$_content = $html;
		}

		public static function renderMenu($render) {
			if ($render) {
				self::$_renderMenu = true;
			} else {
				self::$_renderMenu = false;
			}
		}

		public static function renderFooter($render) {
			if ($render) {
				self::$_renderFooter = true;
			} else {
				self::$_renderFooter = false;
			}
		}

		public static function addCss($file) {
			if (!empty(Conf::$_conf['common_files']['additional_css']) && is_array(Conf::$_conf['common_files']['additional_css'])) {
				array_push(Conf::$_conf['common_files']['additional_css'], $file);
			} else {
				Conf::$_conf['common_files']['additional_css'] = [];
				array_push(Conf::$_conf['common_files']['additional_css'], $file);
			}
		}

		public static function addJs($file) {
			if (!empty(Conf::$_conf['common_files']['additional_js']) && is_array(Conf::$_conf['common_files']['additional_js'])) {
				array_push(Conf::$_conf['common_files']['additional_js'], $file);
			} else {
				Conf::$_conf['common_files']['additional_js'] = [];
				array_push(Conf::$_conf['common_files']['additional_js'], $file);
			}
		}

		public static function removeCss($file) {
			if (is_array(Conf::$_conf['common_files']['css']) && count(Conf::$_conf['common_files']['css']) >= 1) {
				$key = array_search($file, Conf::$_conf['common_files']['css']);
				if ($key !== false) {
					unset(Conf::$_conf['common_files']['css'][$key]);
				} else {
					throw new \Exception('I can\'t remove css, file "'.$file.'", do not exists in define_css_js.php.');
				}
			} else {
				throw new \Exception('I can\'t remove css, file "'.$file.'", common js is empty in define_css_js.php.');
			}
		}
		public static function removeJs($file) {
			if (is_array(Conf::$_conf['common_files']['js']) && count(Conf::$_conf['common_files']['js']) >= 1) {
				$key = array_search($file, Conf::$_conf['common_files']['js']);
				if ($key !== false) {
					unset(Conf::$_conf['common_files']['js'][$key]);
				} else {
					throw new \Exception('I can\'t remove js, file "'.$file.'", do not exists in define_css_js.php.');
				}
			} else {
				throw new \Exception('I can\'t remove js, file "'.$file.'", common js is empty in define_css_js.php.');
			}
		}

		public static function setTitle($title) {
			self::$_title = $title;
		}

		public static function setDescription($description) {
			self::$_description = $description;
		}

		public static function mountSegment($view, $data) {
			$view = self::getSegment($view);
			$block = "";
			foreach ($data as $row) {
				$block .= $view;
				foreach ($row as $col => $value) {
					$tag = "<%" . strtoupper($col) . "%>";
					$block = str_replace($tag, $value, $block);
				}
			}

			return $block;
		}
	}