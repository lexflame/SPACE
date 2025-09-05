<?php namespace App\Controllers;

use App\Models\TaskModel;
use App\Models\MarkerModel;
use CodeIgniter\API\ResponseTrait;
use App\Libraries\ModelManager;

/**
 * Юнион контроллер для работы с БД.
 * Обрабатывает CRUD-операции через REST API.
 */
class UnionController extends BaseController
{
    use ResponseTrait;
    public $Segment;
    public $uri;

    public function __construct()
    {

    }   

    public function setModel()
    {
        $Manager = new ModelManager();
        return $Manager->getTable($this->request);        
    }

    /**
     * Возвращает HTML-страницу с задачами.
     *
     * @return string
     */
    public function index()
    {

    }

    /**
     * Возвращает список всех задач в формате JSON.
     *
     * @return \CodeIgniter\HTTP\ResponseInterface
     */
    public function list()
    {
        $model = $this->setModel();
        return $this->respond($model->orderBy('id', 'DESC')->findAll());
    }

    /**
     * Создаёт новую задачу.
     *
     * @return \CodeIgniter\HTTP\ResponseInterface
     */
    public function create()
    {
        $model = $this->setModel();
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
        $model = $this->setModel();
        $res = [];
        if($up < 1){
            $data = $this->request->getJSON(true);
            
            $arrSync = [];
            foreach ($data as $key => $task) {
                $arrSync[$task['id']]['obj'] = json_encode($task);
                $arrSync[$task['id']]['item_date'] = $task['date'];
                $arrSync[$task['id']]['sync_id'] = $task['id'];
                $arrSync[$task['id']]['remember'] = 0;
            }

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
        $model = $this->setModel();
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
        $model = $this->setModel();
        $model->delete($id);
        return $this->respondDeleted();
    }
}
