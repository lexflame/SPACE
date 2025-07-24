<?php
namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\DataModel;

class Edo extends BaseController
{
    protected $dataModel;

    public function __construct()
    {
        $this->dataModel = new DataModel();
    }

    public function index()
    {
        $data = [];
        $data['block']['link_ul_list'] = $this->dataModel->GetLinks('li');

        return view('edo_block', $data);
    }
}
