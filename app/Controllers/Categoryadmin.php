<?php
namespace App\Controllers;
use App\Models\CategoryModel;

class Categoryadmin extends BaseController
{
    public function index()
    {
        $model = new CategoryModel();
        $data['categories'] = $model->findAll();
        return view('mapeditor/category_admin', $data);
    }

    public function save()
    {
        $model = new CategoryModel();
        $id = $this->request->getPost('id');
        $data = [
            'name' => $this->request->getPost('name'),
            'color' => $this->request->getPost('color'),
            'icon' => $this->request->getPost('icon')
        ];
        if ($id) $model->update($id, $data); else $model->insert($data);
        return redirect()->to('/categoryadmin');
    }

    public function delete($id)
    {
        $model = new CategoryModel();
        $model->delete($id);
        return redirect()->to('/categoryadmin');
    }
}
