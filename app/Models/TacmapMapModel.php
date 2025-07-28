<?php namespace App\Models;

use CodeIgniter\Model;

class TacmapMapModel extends Model
{
    protected $table = 'tacmap_map';
    protected $primaryKey = 'id';
    protected $allowedFields = ['label', 'x', 'y'];

    public function getMarkers()
    {
        return $this->findAll();
    }
}
