<?php namespace App\Controllers;

use App\Models\TaskModel;
use App\Models\MarkerModel;
use App\Models\MarkerMapModel;
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
            $addictedData = [];

            foreach ($data as $key => $record) {
                    
                $addicted = false;
                switch ($model->getTable()) {
                    case 'union_task':
                        break;
                    case 'union_marker':
                        $addicted = new MarkerMapModel();
                        $addictedData[$record['id']]['map_id'] = $record['map'];
                        $addictedData[$record['id']]['marker_id'] = false;
                        break;
                    default:
                        break;
                }

                $arrSync[$record['id']]['obj'] = json_encode($record);
                $arrSync[$record['id']]['item_date'] = $record['date'];
                $arrSync[$record['id']]['sync_id'] = $record['id'];
                $arrSync[$record['id']]['remember'] = 0;
            }

            foreach($arrSync as $key => $recordData){
                $error_text = false;
                $isSave = count($model->where('sync_id', $key)->findAll()) > 0;

                if($isSave){
                    $recordData['id'] = intval($model->where('sync_id', $key)->findAll()[0]['id']);
                }

                try {
                    $resUp = $model->save($recordData,false);
                } catch (\Exception $e) {
                    $error_text = $e->getMessage();
                }

                if($resUp === true){
                    $respID = $model->insertID();

                    if(isset($addictedData[$key])){
                        foreach($addicted->allowedFields as $val){
                            $val = trim($val);
                            if($addictedData[$key][$val] != false){
                                $recordAddictedData[$val] = $addictedData[$key][$val];
                            }else{
                                $recordAddictedData[$val] = $respID;
                            }
                        }
                        $addictedRecord = $addicted->save($recordAddictedData,false);
                    }

                    $recordSync[] = [
                        'base_id' => $this->respondCreated(['id' => $respID]),
                        'sync_id' => $key,
                    ];
                }
            }
            $res = [
                'status' => 'success',
                'data' => $recordSync,
                'up' => $up,
            ];
        }else{
            $arrRecord = $model->where('remember', 0)->findAll();
            $arrDataJson = [];
            foreach($arrRecord as $index_key => $record){
                $arrDataJson[] = $record['obj'];
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
