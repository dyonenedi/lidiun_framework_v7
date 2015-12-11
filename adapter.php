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

	use Lidiun_Framework_v6\Autoload;
	use Lidiun_Framework_v6\Framework;

	try {
		#######################################################################################
		################################# Include Configuration ###############################
		#######################################################################################

		$configPath = PUBLIC_DIRECTORY . SEPARATOR . '..' . SEPARATOR . 'config' . SEPARATOR . 'config.php';
		include_once($configPath);

		if (empty($config['environment']) || !is_array($config['environment'])) {
			throw new \Exception('Fatal Error: index environment not found in "config.php".');
		}
		if (empty($config['database']) || !is_array($config['database'])) {
			throw new \Exception('Fatal Error: index database not found in "config.php".');
		}
		if (empty($config['public_path']) || !is_array($config['public_path'])) {
			throw new \Exception('Fatal Error: index public_path not found in "config.php".');
		}
		if (empty($config['common_files'])|| !is_array($config['common_files'])) {
			throw new \Exception('Fatal Error: index common_files not found in "config.php".');
		}
		if (empty($config['preset']) || !is_array($config['preset'])) {
			throw new \Exception('Fatal Error: index preset not found in "config.php".');
		}

		#######################################################################################
		############################## Include Autoload and Framework ############################
		#######################################################################################

		$lidiunPath = PUBLIC_DIRECTORY . SEPARATOR . '..' . SEPARATOR . '..' . SEPARATOR . 'lidiun_framework_v6' . SEPARATOR;
		include_once($lidiunPath . 'autoload.php');
		include_once($lidiunPath . 'framework.php');

		Autoload::init();
		
		$Framework = new Framework($config);
		$Framework = null;
	} catch (Exception $e) {
		echo '
			<body style="background:#F1F2F2;">
				<pre style="font-size: 24px; color: #5895C9;font-weight: bold;">Lidiun Framework Warning!</pre>
				<pre style="font-size: 22px; color: #444;">Message: '.$e->getMessage().'</pre>
				<pre style="font-size: 18px; color: #5888B5;">'.$e->getTraceAsString().'</pre>
			</body>
		';
	}