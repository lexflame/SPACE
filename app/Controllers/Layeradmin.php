<?php
namespace App\Controllers;
use App\Models\LayerModel;
use App\Models\MapModel;

class Layeradmin extends BaseController
{
    public function index($map_id = 1)
    {
        $model = new LayerModel();
        $data['layers'] = $model->where('map_id',$map_id)->orderBy('sort_order')->findAll();
        $data['maps'] = (new MapModel())->getAll();
        $data['map_id'] = $map_id;
        return view('mapeditor/layer_admin', $data);
    }
    public function save()
    {
        $model = new LayerModel();
        $id = $this->request->getPost('id');
        $data = [
            'map_id'=>$this->request->getPost('map_id'),
            'name'=>$this->request->getPost('name'),
            'sort_order'=>$this->request->getPost('sort_order',0)
        ];
        if ($id) $model->update($id,$data); else $model->insert($data);
        return redirect()->to('/layeradmin/'.$data['map_id']);
    }
    public function delete($id)
    {
        $model = new LayerModel();
        $layer = $model->find($id);
        $map_id = $layer['map_id'];
        $model->delete($id);
        return redirect()->to('/layeradmin/'.$map_id);
    }
}
