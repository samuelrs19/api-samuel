<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Lumen\Routing\Controller as BaseController;

class ControllerTeste extends BaseController
{

    public function teste1(Request $request)
    {
        return response()->json(
            [
                'status' => '200',
                'message' => 'Olá você esta usando a estrutura de rotas API Lumen.',
                'data' => $request->getInputSource()
            ]
        );
    }

    public function teste2(Request $request)
    {
        return response()->json(
            [
                'status' => '200',
                'message' => 'Olá mundo!',
                'data' => $request->all()
            ]
        );
    }
}
