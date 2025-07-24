<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\FileModel;
use CodeIgniter\HTTP\ResponseInterface;

class FileController extends BaseController
{
    protected $fileModel;

    public function __construct()
    {
        $this->fileModel = new FileModel();
    }

    /**
     * Получить список всех файлов
     */
    public function index()
    {
        $files = $this->fileModel->findAll();

        return $this->response->setJSON([
            'status' => 'success',
            'data' => $files,
        ]);
    }

    /**
     * Получить один файл по ID
     */
    public function show($id = null)
    {
        $file = $this->fileModel->find($id);

        if (!$file) {
            return $this->failNotFound("Файл с ID $id не найден");
        }

        return $this->response->setJSON([
            'status' => 'success',
            'data' => $file,
        ]);
    }

    /**
     * Создать новый файл (POST)
     */
    public function create()
    {
        $data = $this->request->getJSON(true);

        if (!$this->fileModel->insert($data)) {
            return $this->failValidationErrors($this->fileModel->errors());
        }

        return $this->respondCreated([
            'status' => 'success',
            'message' => 'Файл успешно создан',
            'id' => $this->fileModel->getInsertID(),
        ]);
    }

    /**
     * Обновить файл по ID (PUT/PATCH)
     */
    public function update($id = null)
    {
        $data = $this->request->getJSON(true);

        if (!$this->fileModel->find($id)) {
            return $this->failNotFound("Файл с ID $id не найден");
        }

        if (!$this->fileModel->update($id, $data)) {
            return $this->failValidationErrors($this->fileModel->errors());
        }

        return $this->response->setJSON([
            'status' => 'success',
            'message' => 'Файл успешно обновлён',
        ]);
    }

    /**
     * Удалить файл по ID (DELETE)
     */
    public function delete($id = null)
    {
        if (!$this->fileModel->find($id)) {
            return $this->failNotFound("Файл с ID $id не найден");
        }

        $this->fileModel->delete($id);

        return $this->response->setJSON([
            'status' => 'success',
            'message' => 'Файл успешно удалён',
        ]);
    }
}
