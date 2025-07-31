<?php

namespace App\Controllers;

use App\Models\FileModel;

class Disk extends BaseController
{
    public function index($path = '/data/app/public/yandexdisk/Gallery/')    { 
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
}
