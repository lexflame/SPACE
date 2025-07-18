<?php namespace App\Models;

use CodeIgniter\Model;

class TacmapModel extends Model
{
    protected $table = 'tacmap_markers';
    protected $primaryKey = 'id';
    protected $allowedFields = ['label', 'x', 'y'];

    public function getMarkers()
    {
        return $this->findAll();
    }
}
