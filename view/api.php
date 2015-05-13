<?PHP
	/**
	 * Glexa for moodle API
	 * 
	 * Customize for e-mon tsukuro at Hiroshima Shudo University
	 * Programmed by A.Ohnishi at VERSION2 Inc, http://ver2.jp/
	 * 
	 * March 2011. 
	 */
	define('DEBUG', false);
	define('SYSTEM_NAME', ' [e問つく朗]');
	 
	// required library
    require_once("../../../config.php");

    require_login();
	
	// parameters
	$action = required_param('action', PARAM_TEXT);
	
	// common variables
	$json  = array();
	
	// action file include
	$actionFile = dirname(__FILE__) . '/api/' . $action . '.php';
	if (file_exists($actionFile)) {
		require_once $actionFile;
	} else {
		error('Invalid action.');
		exit;
	}
	
	msg('---------------result-------------');
	msg($json);
	
	// output
	echo json_encode($json);
	exit;
	
	
	/**
	 * set array
	 * 
	 * @param	mixed	$src
	 * @param	&array	$dest
	 */
	function setArray($src, &$dest) {
		if (is_object($src) || is_array($src)) {
			foreach ($src as $k => $v) {
				if (is_string($v)) {
					$dest[$k] = $v;
				} else {
					setArray($v, $dest[$k]);
				}
			}
		}
	}
	
	/**
	 * view message for debug
	 * 
	 * @param	string	$str
	 * @return	string
	 */
	function msg($str) {
		if (DEBUG) {
			echo '<b>msg: </b>'; 
			if (is_array($str)) {
				echo '<xmp>';
				var_dump($str);
				echo '</xmp>';
			} else {
				echo $str;
			}
			echo '<br />';
		}
	}
?>
