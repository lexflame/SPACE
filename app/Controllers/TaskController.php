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
     * Синхронизация
     *
     * @return \CodeIgniter\HTTP\ResponseInterface
     */
    public function sync(int $up)
    {
        $model = new TaskModel();
        $res = [];
        if($up < 1){
            $data = $this->request->getJSON(true);
            // $data = json_decode('[{"id":1752755002823,"title":"syncWithServer for plugin 14","date":"2025-07-24T18:00","priority":"high","description":"","link":"","tag":"","coords":"","files":[],"completed":false,"_synced":false,"synced":false}]',true);
            
            $arrSync = [];
            foreach ($data as $key => $task) {
                $arrSync[$task['id']]['obj'] = json_encode($task);
                $arrSync[$task['id']]['date_task'] = $task['date'];
                $arrSync[$task['id']]['sync_id'] = $task['id'];
                $arrSync[$task['id']]['remember'] = 0;
            }

            // echo '<pre>'; print_r(); echo '</pre>';

            foreach($arrSync as $key => $taskData){
                $error_text = false;
                $isSave = count($model->where('sync_id', $key)->findAll()) > 0;

                if($isSave){
                    $taskData['id'] = intval($model->where('sync_id', $key)->findAll()[0]['id']);
                }

                try {
                    $resUp = $model->save($taskData,false);
                } catch (\Exception $e) {
                    $error_text = $e->getMessage();
                }

                if($resUp === true){
                    $taskSync[] = [
                        'base_id' => $this->respondCreated(['id' => $model->insertID()]),
                        'sync_id' => $key,
                    ];
                }
            }
            $res = [
                'status' => 'success',
                'data' => $taskSync,
                'up' => $up,
            ];
        }else{
            $arrTasks = $model->where('remember', 0)->findAll();
            $arrDataJson = [];
            foreach($arrTasks as $index_key => $task){
                $arrDataJson[] = $task['obj'];
            } 
            $res['upData'] = $arrDataJson;
        }

        return $this->response->setJSON($res);
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
