<?php

namespace App\Controllers;

use App\Models\FileModel;

class Disk extends BaseController
{
    public function __construct()
    {
        $this->db = db_connect();
    }
    public function index($path = '/data/app/public/yandexdisk/Gallery/')
    { 
		$reader = new \App\Libraries\DirectoryReader();
		$structureDirectory = $reader->getStructure($path);
		// print_r($structureDirectory);
		// echo '<pre>';
		// foreach ($structureDirectory as $key => $elm) {
             // echo $error_text."\r"."\n";
		// }
		 // echo '</pre>';
		// exit;
    }
    public function thumd_tacmap()
    {
        $image = \Config\Services::image();
		$builder = $this->db->table("file");
        $builder->select('*');
        $builder->like('full_path','tacmap_','match');
        $builder->having('type', 'file');;
        $query = $builder->get();
        $arrImg = [];
        foreach ($query->getResult() as $key => $value) {
        	$item = (array)$value;
        	if(strpos($item['name'],'png') != false){
	            // print_r($item['full_path']);echo "\r\n";
	            $arrPathToThumb = explode('/', $item['full_path']);
	            $name = 'thumb_'.array_pop($arrPathToThumb);
	            $pathToThumb = implode('/', $arrPathToThumb).'/'.$name;
	            // echo $pathToThumb."\r\n";
	            $image->withFile($item['full_path'])->resize(718.16, 718.91, true, 'box')->save($pathToThumb);
            }
        }    	
    }
}
