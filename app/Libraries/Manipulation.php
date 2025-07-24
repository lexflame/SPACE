<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Manipulation
{
	protected $CI;

	public function __construct()
	{

        $this->CI =& get_instance();

	}

	public function GetDataPathViaFile( $link )
	{
		$DIR 					= $_SERVER['WORKDIR'].'/load';

		$arrLink 				= explode('/', $link);

		$arrValid['file'] 		= array_pop($arrLink);

		$arrValid['project'] 	= array_shift($arrLink);

		$arrValid['dir_1'] 		= array_shift($arrLink);

		$arrValid['dir_2'] 		= array_shift($arrLink);

		$arrValid['path'] 		= $DIR.'/'.$arrValid['project'];

		$arrValid['path'] 		.= '/'.$arrValid['dir_1'];

		$arrValid['path'] 		.= '/'.$arrValid['dir_2'].'/';

		return $arrValid;
		
	}

	public function ValidFilePath( $arrValid , $arrPath )
	{

		$fdta = array();
		
		foreach ($arrPath as $item_elm) {
			
			$fdta[] = stristr($item_elm, $arrValid['file']);

		}

		$arrValidFile = array();

		foreach ($fdta as $item) {
			
			if(!empty($item)){

				$arrValidFile[] = $item;

			}

		}		

		return $arrValidFile;

	}

	public function DeleteFile($arrFile,$path)
	{
		foreach ($arrFile as $filename) {
			unlink($path.$filename);
		}
	}

}

/* End of file Manipulation.php */
/* Location: ./application/libraries/Manipulation.php */
