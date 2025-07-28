<?php namespace App\Controllers;

use App\Models\TacmapMapModel;
use CodeIgniter\RESTful\ResourceController;

class Tacmap extends BaseController
{
    public function index($id = 1)
    {   
        $model = new TacmapMapModel();
        $arrMap = $model->where('id', $id)->findAll()[0];
        $arrMap['dif'] = getimagesize('http://'.$_SERVER['HTTP_HOST'].$arrMap['path']);
        // echo '<pre>'; print_r($arrMap); echo '</pre>'; exit;
        return view('tacmap/index', $arrMap);
    }

    public function data()
    {
        $model = new TacmapModel();
        return $this->response->setJSON($model->getMarkers());
    }
}
