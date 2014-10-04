<?php
/**
 * Adminer for CakePHP
 * ===================
 * 
 * Adminer extension to auto login using CakePHP default database connection.
 * 
 * 
 * from: https://github.com/movableapp/Adminer
 * 
 */





// Just emulate some constants to interact with CakePHP.
define('DS', DIRECTORY_SEPARATOR);
define('HERE', dirname($_SERVER['SCRIPT_FILENAME']) . DS);
define('APP', dirname(dirname(HERE)) . DS);

if (file_exists(HERE.'passwd.txt')) {
	session_start();
	if (isset($_GET['exit'])) {
		$_SESSION['afc'] = false;
	}
	if (!empty($_POST['passwd'])) {
		if (md5($_POST['passwd']) == file_get_contents(HERE.'passwd.txt')) {
			$_SESSION['afc'] = true;
			header('Location: ?');
			exit;
		}
	}
	if (empty($_SESSION['afc'])) {
		echo '<form action="?" method="post">';
		echo '<input type="password" name="passwd" placeholder="Adminer for CakePHP Password" size="30">';
		echo '</form>';
		exit;
	}
}


// database auto-login credentials
define('DB_CONFIG', APP.'Config'.DS.'database.php');
if (@include(DB_CONFIG)) {
	$_GET["username"] = "";
	// bypass database selection bug
	$__db_config__ = new DATABASE_CONFIG;
	$_GET["db"] = $__db_config__->default['database'];

// loading error
} else {
	trigger_error("Adminer is not able to locate database config!");
	exit;
}

// Adminer Extension
function adminer_object() {
	
	class AdminerSoftware extends Adminer {
		
		function __construct() {
			global $__db_config__;
			$this->db_config = $__db_config__;
		}
		
		public function credentials() {
			return array($this->db_config->default['host'], $this->db_config->default['login'], $this->db_config->default['password']);
		}
		
		public function database() {
			return $this->db_config->default['database'];
		}
		
	}
	
	return new AdminerSoftware;
	
}

// Include Adminer Script
include('./adminer.php');
