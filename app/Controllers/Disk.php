<?php

namespace App\Controllers;

class Disk extends BaseController
{
    public function index()    { 
		$reader = new \App\Libraries\DirectoryReader();
		$structureDirectory = $reader->getStructure('/data/app/public/yandexdisk/Gallery/');
		echo '<pre>';
		foreach ($structureDirectory as $key => $elm) {
			print_r($elm);
		}
		echo '</pre>';
		exit;
    }
}
