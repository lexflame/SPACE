<?php
namespace App\Controllers;

use App\Models\MapModel;

class Mapmanager extends BaseController
{
    public function index()
    {
        $mapModel = new MapModel();
        $data['maps'] = $mapModel->getAll();
        return view('panel').view('mapeditor/maps_list', $data);
    }

    public function addMap()
    {
        return view('panel').view('mapeditor/map_form');
    }

    public function saveMap()
    {
        $mapModel = new MapModel();
        $mapId = $this->request->getPost('id');
        $data = [
            'name' => $this->request->getPost('name'),
            'projection' => $this->request->getPost('projection'),
            'image_path' => $this->request->getPost('image_path')
        ];
        if ($mapId)
            $mapModel->update($mapId, $data);
        else
            $mapId = $mapModel->insert($data);

        return redirect()->to('/mapmanager');
    }

    public function delete($id)
    {
        $mapModel = new MapModel();
        $mapModel->delete($id);
        return redirect()->to('/mapmanager');
    }
}
