<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Notifer
{
	protected $CI;

	public function __construct()
	{

        $this->CI =& get_instance();

	}

	public function Init($function_name,$arg)
	{

		$res = $this->$function_name($arg);

		if(is_string($res)){
			return $res;
		}
	}

	public function ScanDataUnlink()
	{
		unset($_SERVER['SCANDIR_DATA']);
	}

	public function ScanDataLink($arrValid)
	{
		if(!isset($_SERVER['SCANDIR_DATA'])){

			$arrPath 					= scandir($arrValid['path']);

			$_SERVER['SCANDIR_DATA'] 	= $arrPath;
			
		}else{


			$arrPath 					= $_SERVER['SCANDIR_DATA'];

		}

		return $arrPath;
	}
	public function ValidFileExist($link)
	{

		$this->CI->load->library('manipulation');
			
		$arrValid 				= $this->CI->manipulation->GetDataPathViaFile($link);

		$arrPath 				= $this->ScanDataLink($arrValid);	

		$arr = $this->CI->manipulation->ValidFilePath($arrValid,$arrPath);

		if(count($arr) > 0){

			$this->CI->load->model('data_model');

			$action = $this->CI->data_model->ArgGetValue('action');

			if($action === 'deleteDuplicate'){

				$resDel			= $this->CI->manipulation->DeleteFile($arr, $arrValid['path']);

				return '<a href="#" class="badge badge-warning" style="float: right;">Deleted file</a>';

			}else{

				return '<a href="#" class="badge badge-danger" style="float: right;">WARNING! Duplicate file in preload dir</a>';

			}
			
		}else{
			return NULL;
		}

	}

}

/* End of file Notifer.php */
/* Location: ./application/libraries/Notifer.php */
