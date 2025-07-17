<?php namespace App\Controllers;

use App\Models\TaskModel;
use CodeIgniter\API\ResponseTrait;

/**
 * Контроллер задач.
 * Обрабатывает CRUD-операции через REST API.
 */
class TaskController extends BaseController
{
    use ResponseTrait;

    /**
     * Возвращает HTML-страницу с задачами.
     *
     * @return string
     */
    public function index()
    {
        return view('tasks/index');
    }

    /**
     * Возвращает список всех задач в формате JSON.
     *
     * @return \CodeIgniter\HTTP\ResponseInterface
     */
    public function list()
    {
        $model = new TaskModel();
        return $this->respond($model->orderBy('id', 'DESC')->findAll());
    }

    /**
     * Создаёт новую задачу.
     *
     * @return \CodeIgniter\HTTP\ResponseInterface
     */
    public function create()
    {
        $model = new TaskModel();
        $data = $this->request->getJSON(true);

        $model->insert($data);
        return $this->respondCreated(['id' => $model->insertID()]);
    }

    /**
     * Обновляет задачу по ID.
     *
     * @param int $id
     * @return \CodeIgniter\HTTP\ResponseInterface
     */
    public function update($id)
    {
        $model = new TaskModel();
        $data = $this->request->getJSON(true);

        $model->update($id, $data);
        return $this->respondUpdated();
    }

    /**
     * Удаляет задачу по ID.
     *
     * @param int $id
     * @return \CodeIgniter\HTTP\ResponseInterface
     */
    public function delete($id)
    {
        $model = new TaskModel();
        $model->delete($id);
        return $this->respondDeleted();
    }
}
