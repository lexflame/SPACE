<?php

namespace App\Libraries;

use App\Models\TaskModel;
use App\Models\MarkerModel;

class ModelManager
{
	public function getTable( $request )
	{
		$Model = false;
		switch ($request->getUri()->getSegment(1)) {
			case 'marker':
				$Model = new MarkerModel();
				break;
			case 'tasks':
				$Model = new TaskModel();
				break;
			case 'union':
				$Model = new MarkerModel();
				break;
			default:
				break;
		}
		if($Model != false){
			$res = $Model;
		}else{
			$res = false;
		}
		return $res;
	}
}