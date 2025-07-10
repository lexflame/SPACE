<?php 
	ini_set('display_errors', '1');
	ini_set('display_startup_errors', '1');
	error_reporting(E_ALL);
?>
<title>loger</title>
<style>
	body {background: black;}
	body, body * {color: white; font-family: sans-serif;}
	.btn_p, .btn_to {margin: 15px;}
</style>
<?
	require_once 'conf/conf.php';
	?><div class="btn_p"><?
	foreach ($OPTION['LOGS'] as $key => $value) {
		echo '<span class="btn_to"><a href="./?view='.$key.'&rand='.rand(1,450).'">'.strtolower($key).'</a></span>';
	}
	?></div><?
	if(isset($_GET['view']) and isset($OPTION['LOGS'][$_GET['view']])){
		$path = $OPTION['LOGS'][$_GET['view']];
		switch ($OPTION['VENDOR']) {
			case 'EXES':
				exec('cat '.$path.' ', $output, $retval);
				break;
			default:
				break;
		}
		// echo '<pre>'; print_r($output); echo '</pre>';
		if(is_array($output) and count($output) > 0){
			krsort($output);
			foreach ($output as $key => $value) {
				echo $key.' :: ';
				echo $value.'</br></br>';
			}
		}
	}
	// require_once 'lib/worker.php';

