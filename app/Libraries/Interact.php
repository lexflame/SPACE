<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Interact
{

	public function ScandirPage($path)
	{
		$dirData 		= scandir($path);

		array_shift($dirData);

		array_shift($dirData);

		return $dirData;
	}
	public function ScandirLoad()
	{
		$DIR 			= $_SERVER['WORKDIR'].'/load/';

		$dirData 		= scandir($DIR);

		array_shift($dirData);

		array_shift($dirData);

		return $dirData;

	}
	public function SelectFileDir($dir)
	{
		$DIR 			= $_SERVER['WORKDIR'].'/load/'.$dir;

		var_dump($DIR);
	}
	public function GetArc( $link , $str, $section = null )
	{

		if($section != null && !empty($section)){

			$path_to_cat 	= $this->CatFromLink($link).$section;

			$arrLink 		= explode('/', $link);

			$name_file 		= trim(array_pop($arrLink));

			$DIR 			= $_SERVER['WORKDIR'].'/load/';

			$filename 		= $DIR.$path_to_cat.'/'.$name_file.'.html';

			$file_exists 	= file_exists($filename);		

			$arrLink 		= explode('/', $link);

			$LasrarrLink 	= array_pop($arrLink);

			array_push($arrLink, $section);

			array_push($arrLink, $LasrarrLink);

			$link 			= implode('/', $arrLink);

			// var_dump($link);

		}else{

			$DIR 			= $_SERVER['WORKDIR'].'/load/';
			
			$filename 		= $DIR.$link.'.html';

			$file_exists 	= file_exists($filename);

		}
		
		if($file_exists){

			echo '<a target="_blank" href="./load/'.$link.'.html" class="badge badge-success"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M18.546 1h-13.069l-5.477 8.986v13.014h24v-13.014l-5.454-8.986zm-3.796 12h-5.5l-2.25-3h-4.666l4.266-7h10.82l4.249 7h-4.669l-2.25 3zm-9.75-4l.607-1h12.786l.607 1h-14zm12.18-3l.607 1h-11.573l.606-1h10.36zm-1.214-2l.606 1h-9.144l.607-1h7.931z"/></svg></a>';

		}else{

			echo '<a href="./?page=getlist&action=addScheduler&str='.$str.'&link='.$link.'" class="badge badge-warning"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M24 2v22h-24v-22h3v1c0 1.103.897 2 2 2s2-.897 2-2v-1h10v1c0 1.103.897 2 2 2s2-.897 2-2v-1h3zm-2 6h-20v14h20v-14zm-2-7c0-.552-.447-1-1-1s-1 .448-1 1v2c0 .552.447 1 1 1s1-.448 1-1v-2zm-14 2c0 .552-.447 1-1 1s-1-.448-1-1v-2c0-.552.447-1 1-1s1 .448 1 1v2zm1 11.729l.855-.791c1 .484 1.635.852 2.76 1.654 2.113-2.399 3.511-3.616 6.106-5.231l.279.64c-2.141 1.869-3.709 3.949-5.967 7.999-1.393-1.64-2.322-2.686-4.033-4.271z"/></svg></a>';
		}

	}
	public function InteractPage(	$link = '', $once = false )
	{
		$this->CI->load->model('data_model');

		$DIR 			= $_SERVER['WORKDIR'].'/load/';

		$dtaProject 	= $this->CI->graber->GetDataProject($link);

		$nameFileHtml 	= array_pop($dtaProject);

		$nameFileHtml 	= urldecode($nameFileHtml);				

		$nameFileHtml 	= $nameFileHtml.'.html';

		if($once === true){

			$command 		= 'wget --default-page=NAME.html -k -p -E -mk -w 20 -P '.$DIR.' '.$link.'  > /dev/null &';	

		}else{

			$command 		= 'wget --default-page=NAME.html -k -p -E -mk -w 20 -P '.$DIR.' -r '.$link.'  > /dev/null &';

		}

		

		if(exec( $command )){

			return true;

		}else{

			// var_dump($command);

			return true;
		}
	}
	public function WgetMonit(){

		exec('ps aux' ,$op);
		
		$wget = array();
		foreach ($op as $itm) {

			if(stristr($itm,'wget')){

				$wget[] = $itm;

			}

		}
		$wget_str = array();
		foreach ($wget as $itm) {

			$wget_str = explode(' ', $itm);

		}
		$keys = 0;
		$dta = array();
		foreach ($wget_str as $itm) {

			if(empty($itm))
				{
					
				}else{
					$dta[] = $itm;
				}
			$keys++;
		}


		$monolith = array();
		foreach ($op as $itm) {

			if(stristr($itm,'monolith')){

				$monolith[] = $itm;

			}

		}

		$cnt_mn = count($monolith);

		$wget_str = array();
		foreach ($monolith as $itm) {

			$wget_str = explode(' ', $itm);

		}
		$keys = 0;
		$dta = array();
		foreach ($wget_str as $itm) {

			if(empty($itm))
				{
					
				}else{
					$dta[] = $itm;
				}
			$keys++;
		}		

		echo $cnt_mn;
		// echo count($wget)+count($monolith);

	}
	public function AllStop()
	{
		exec('ps aux' ,$op);
		$wget = array();
		foreach ($op as $itm) {

			if(stristr($itm,'wget')){

				$wget[] = $itm;

			}

		}
		$wget_str = array();
		foreach ($wget as $itm) {

			$wget_str = explode(' ', $itm);

		}
		$keys = 0;
		$dta = array();
		foreach ($wget_str as $itm) {

			if(empty($itm))
				{
					
				}else{
					$dta[] = $itm;
				}
			$keys++;
		}
		$proc = $dta[1];
		exec('kill -TERM '.$proc);
	}
	public function InteractSection( $dir )
	{

		$DIR 			= $_SERVER['WORKDIR'].'/load/';

		$url 			= urldecode($url);

		$path			= $DIR.$url.'.html';

		$fxist_arc 		= file_exists($path);

		if($fxist_arc === true){

			$page_arc = file_get_contents($path);

			$category = $this->CI->graber->ulListCat($page_arc);

			// var_dump($category);

			$path_to_cat = $this->CatFromLink($url);

			$mkdir = $DIR.$path_to_cat.$category;

			mkdir($mkdir);

			$command = "mv $path '$mkdir'";

			exec($command);

			$this->CI->load->model('data_model');

			$this->CI->data_model->UpdateTable('link_list','link',urldecode($url),'section',$category);

			// mkdir($DIR.$path_to_cat.$category);

		}else{

			$this->InteractPage($url,true);

			$page_arc = $this->CI->graber->GetPage($url);

			$this->CI->load->model('data_model');

			$this->CI->data_model->UpdateTable('link_list','link',urldecode($url),'section',$page_arc['cat']);

		}

	}
	public function ErrorScheduler()
	{
		
		$DIR = $_SERVER['WORKDIR'].'/load/';
		
		$ArrProjectDir = scandir($DIR);
		
		array_shift($ArrProjectDir);
		
		array_shift($ArrProjectDir);
		
		$validRes = false;
		
		foreach ($ArrProjectDir as $dirProject) {
		
			$ArrFile = scandir($DIR.$dirProject);
		
			foreach ($ArrFile as $itemFile) {
		
				if(stristr($itemFile, 'html')){
		
					$FSize = filesize($DIR.$dirProject.'/'.$itemFile);
		
					if($FSize < 100){
		
						$validRes = true;
		
					}
		
				}
		
			}

		}

		if($validRes === true){

			echo '<a href="./?page=scheduler&action=ErrorListFile">Error List File </a> <a href="./?page=scheduler&action=ErrorListFileStart" class="badge badge-warning" style="transform: scale(0.7) translateY(-2px);""><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M13 2.004c5.046.504 9 4.783 9 9.97 0 1.467-.324 2.856-.892 4.113l1.738 1.005c.732-1.553 1.154-3.284 1.154-5.117 0-6.304-4.842-11.464-11-11.975v2.004zm-10.109 14.083c-.568-1.257-.891-2.646-.891-4.112 0-5.188 3.954-9.466 9-9.97v-2.005c-6.158.511-11 5.671-11 11.975 0 1.833.421 3.563 1.153 5.118l1.738-1.006zm17.213 1.734c-1.817 2.523-4.769 4.174-8.104 4.174s-6.288-1.651-8.105-4.175l-1.746 1.01c2.167 3.123 5.768 5.17 9.851 5.17 4.082 0 7.683-2.047 9.851-5.168l-1.747-1.011zm-8.104-13.863c-4.419 0-8 3.589-8 8.017s3.581 8.017 8 8.017 8-3.589 8-8.017-3.581-8.017-8-8.017zm-2 11.023v-6.013l6 3.152-6 2.861z"/></svg></a> |';
		
		}

		// echo '<pre>'; print_r($ArrProjectDir); echo '</pre>';
	}
	public function startFirstRound()
	{
		$command = 'php '.$_SERVER['WORKDIR'].'/app.php Worker S1 > /dev/null &';
		exec($command);
	}
	public function startSecondRound()
	{
		$command = 'php '.$_SERVER['WORKDIR'].'/app.php Worker S2 > /dev/null &';
		exec($command);
	}
	public function startAllRound()
	{
		$command = 'php '.$_SERVER['WORKDIR'].'/app.php Worker S1 > /dev/null &';
		exec($command);
		$command = 'php '.$_SERVER['WORKDIR'].'/app.php Worker S2 > /dev/null &';
		exec($command);		
	}
	public function CatFromLink( $url )
	{

		$url = explode('/', $url);

		array_pop($url);

		$url = implode($url, '/');

		$path = $url.'/';
		
		return $path;

	}
	public function StreamContext( $url , $context )
	{
		
		$proxy = $this->ErgoProxy();
		
		$opts = array('http' =>
		
		    array(
		
		        'method'  => 'POST',
		
		        'header'  => 'Content-Type: application/x-www-form-urlencoded',
		
		        'content' => $context,
		
		        'proxy'           => 'tcp://'.$proxy['ip'].':'.$proxy['port'].'',
		
		        'request_fulluri' => true,		        
		
		    )
		);

		$context  = stream_context_create($opts);

		$result = file_get_contents($url, false, $context);

		return $result;
	}
	public function GetContentPage 	()
	{
		
		if($_SERVER['init_addr'])
		
			{
		
				$proxy = $this->ErgoProxy();
		
				$opts = array(
		
				    'http' => array(
		
				        'proxy'           => 'tcp://'.$proxy['ip'].':'.$proxy['port'].'',
		
				        'request_fulluri' => true,
		
				    ),
		
				);

				$context  = stream_context_create($opts);

				// print_r($_SERVER['init_addr']);exit;

				$page = file_get_contents($_SERVER['init_addr'],false,$context);

				// $page = file_get_contents($_SERVER['init_addr']);
		
				

				if($page === false){
					$option = array(
						CURLOPT_HEADER => 0,
						CURLOPT_FOLLOWLOCATION => 1,
						CURLOPT_CONNECTTIMEOUT => 30,
						CURLOPT_SSL_VERIFYPEER => false
					);			

					$page = $this->Interaction(
						$this->InterInit($_SERVER['init_addr']),
						true,
						__FUNCTION__,
						false,
						false,
						$option
					);					
				}

				unset($_SERVER['init_addr']);
	
					return $page;
	
			}else{
	
				throw new Exception( 'Is not set URL' );
		
			}
	
	}

	public function InterInit ( $url = '' )
	{

		if( !empty( $url ) && isset( $url ) ){

			$inter = curl_init();
		
			if( $inter != false )
		
				{
		
					$_SERVER['init_addr'] = $url;
		
						return $inter;
		
				}else{
		
					throw new Exception( 'Init is not coorrect' );
		
				}
		
		}else{
		
			throw new Exception( 'Argument URL is empty or not isset' );
		
		}

	}

	public function Interaction		
	(
		$inter, 
		$RETURNTRANSFER = '',
		$COOKIEFILE_SUFIX = '', 
		$POST = false, 
		$POSTFIELD = false,
		$opt
	)
	{

		$proxy = $this->ErgoProxy();

		curl_setopt($inter, CURLOPT_URL, 			$_SERVER['init_addr'] ); 

		curl_setopt($inter, CURLOPT_RETURNTRANSFER, $RETURNTRANSFER);

		curl_setopt($inter, CURLOPT_PROXY, 			$proxy['ip']);

		curl_setopt($inter, CURLOPT_PROXYPORT, 		$proxy['port']);

		curl_setopt($inter, CURLOPT_MAXREDIRS, 		$this->CURLOPT_MAXREDIRS($_SERVER['init_addr']));

		curl_setopt_array($inter, $opt);

		if($POST != false){
			
			curl_setopt($inter, CURLOPT_POST, 		1);

			curl_setopt($inter, CURLOPT_POSTFIELDS, http_build_query($POSTFIELD));		

		}


		$COOKIE = $this->CURLOPT_COOKIEJAR($COOKIEFILE_SUFIX);

		curl_setopt($inter, CURLOPT_COOKIEJAR, 		$COOKIE);

		curl_setopt($inter, CURLOPT_COOKIEFILE,  	$COOKIE);
		
		$response = curl_exec($inter);
		
		return $response;
	}
	public function CURLOPT_MAXREDIRS(	$addr )
	{
		
		$url = parse_url($addr);

		if($url['scheme'] == 'https'){

		   return 3;

		}else{

		   return 4;

		}
	}	
	public function CURLOPT_COOKIEJAR( $COOKIEFILE_SUFIX = '' )
	{
		
		$dirname = $_SERVER['init_addr'];

		$dirname = explode('//', $dirname);
		
		$dirname = explode('/', $dirname[1]);
		
		$host = $dirname[0];
		
		$dir = dirname(__FILE__).'/'.$dirname[0];
		
		mkdir($dir);
		
		return $dir.'/cookies_'.$COOKIEFILE_SUFIX.'_'.$host.'.txt';

	}
	public function CURLOPT_COOKIEFILE( $COOKIEFILE_SUFIX = '' )
	{
		return dirname(__FILE__).'/cookies_'.$COOKIEFILE_SUFIX.'.txt';
	}
}

/* End of file Interact.php */
/* Location: ./application/libraries/Interact.php */
