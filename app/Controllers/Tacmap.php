<?php namespace App\Controllers;

use App\Models\TacmapModel;
use CodeIgniter\RESTful\ResourceController;

class Tacmap extends BaseController
{
    public function index()
    {   
        return view('tacmap/index');
    }

    public function data()
    {
        $model = new TacmapModel();
        return $this->response->setJSON($model->getMarkers());
    }
}
