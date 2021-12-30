<?php

namespace App\Http\Controllers;

/**
 * Objeto responsável por 
 * receber os dados webhook 
 * do pagseguro
 * 
 * @author Samuel Rodrigues 
 * @link https://github.com/samuelrs19
 * @link https://samuel-dev.ml
 */

use Illuminate\Http\Request;

class WebHook extends Util
{
    public $dados = array(
        'id' => '',
        'descricao' => '',
        'text' => '',
        'datahora' => '',
        'idreference' => '',
        'identificador' => ''
    );

    public function recebePost()
    {
        $json = json_encode(app('request')->all(), JSON_UNESCAPED_UNICODE);
        $datahora = date('Y-m-d H:i:s');

        $results = app('db')->insert(
            "INSERT INTO logs (descricao, text, datahora, idreference, identificador) 
                value ('Recebimento webhook 1', '{$json}' , '{$datahora}', 10101, 'webhook')"
        );

        if ($results) {
            return true;
        } else {
            return false;
        }
    }
}
