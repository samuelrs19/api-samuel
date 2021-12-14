<?php
namespace App\Http\Controllers;

class TrataRetorno extends Util
{

    public function retornoCriaPlano($retornoReq)
    {
        $return = array();
        $return['success'] = false;
        $return['codePlano'] = '';
        $return['codeError'] = '';
        $return['status'] = isset($retornoReq['status']) ? $retornoReq['status'] : '';
        $return['msg'] = '';
        $return['dataCriacao'] = '';

        $xml = simplexml_load_string($retornoReq['response']);
        $json = json_encode($xml, JSON_UNESCAPED_UNICODE);
        $array = json_decode($json, TRUE);

        if (isset($array['code'])) {

            $return['codePlano'] = $array['code'];
            $return['dataCriacao'] = $array['date'];
            $return['msg'] = 'Sucesso';
            $return['success'] = true;
        } else {

            if (isset($array['error'])) {

                $return['codeError'] = $array['error']['code'];
                $return['msg'] = $array['error']['message'];
            } else {

                $return['msg'] = "Houve algum erro desconhecido";
            }
        }

        return $return;
    }
}
