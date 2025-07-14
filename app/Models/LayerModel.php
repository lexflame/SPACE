<?php namespace App\Models;
use CodeIgniter\Model;
class LayerModel extends Model
{
    protected $table = 'map_layers';
    protected $allowedFields = ['map_id','name','visible','sort_order'];
}
