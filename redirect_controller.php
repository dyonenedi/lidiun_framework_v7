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
	
	Class Redirect_controller
	{
		public static $_content;

		public function __construct() {
			if (Request::isAjax()) {
				Render::setReply(['reply' => false, 'message' => self::$_content]);
			} else {
				Layout::renderMenu(false);
				Layout::renderFooter(false);
				Layout::loadContent(self::$_content);
				if (!Layout::haveContent()) {
					Layout::putContent(self::$_content);
				}
			}
		}
	}