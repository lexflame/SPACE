<?php

namespace App\Controllers;

use App\Models\RouteModel;

class Events extends BaseController
{
    public function routes()
    {
        header('Content-Type: text/event-stream');
        header('Cache-Control: no-cache');
        header('Connection: keep-alive');
        $model = new RouteModel();
        while (true) {
            $routes = $model->where('user_id', auth()->id())->findAll();
            echo "event: routes\n";
            echo "data: " . json_encode($routes) . "\n\n";
            @ob_flush();
            @flush();
            sleep(3);
        }
        exit;
    }
}