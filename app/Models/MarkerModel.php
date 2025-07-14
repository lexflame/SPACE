<?php

namespace App\Models;

use CodeIgniter\Model;

class MarkerModel extends Model
{
    protected $table = 'map_markers';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'map_id','layer_id','category_id','title','description','icon','icon_color','icon_size','x','y','sort_order'
    ];

    public function getMarkers($map_id)
    {
        return $this->select('map_markers.*, marker_categories.name AS category_name, marker_categories.color AS category_color, marker_categories.icon AS category_icon, map_layers.name AS layer_name, map_layers.visible AS layer_visible')
            ->join('marker_categories', 'marker_categories.id = map_markers.category_id', 'left')
            ->join('map_layers', 'map_layers.id = map_markers.layer_id', 'left')
            ->where('map_markers.map_id', $map_id)
            ->orderBy('map_markers.sort_order')
            ->findAll();
    }

    public function getMarker($id)
    {
        return $this->asArray()->find($id);
    }
}
