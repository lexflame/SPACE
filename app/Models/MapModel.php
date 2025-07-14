<?php

namespace App\Models;

use CodeIgniter\Model;

class MapModel extends Model
{
    protected $table = 'maps';
    protected $primaryKey = 'id';

    public function getAll()
    {
        return $this->findAll();
    }
    public function getLayers($map_id)
    {
        return db_connect()->table('map_layers')->where('map_id', $map_id)->orderBy('sort_order')->get()->getResultArray();
    }
    public function getCategories()
    {
        return db_connect()->table('marker_categories')->get()->getResultArray();
    }
}
