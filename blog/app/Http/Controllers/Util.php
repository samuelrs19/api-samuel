<?php

namespace App\Http\Controllers;

class Util
{
    public $retornoReq;

    public function set($atributo, $valor)
    {
        $this->$atributo = $valor;
    }

    public function get($atributo)
    {
        return $this->$atributo;
    }

    public function request($url, $tipo, $cabecalho, $dados, $time = 40000)
    {
        $ch = curl_init($url);

        if ($tipo == 'POST') {
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $dados);
        }

        if ($tipo == 'PUT') {
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
            curl_setopt($ch, CURLOPT_POSTFIELDS, $dados);
        }

        if ($tipo == 'GET') {
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        }

        if ($tipo == 'DELETE') {
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
        }

        if (!empty($cabecalho)) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $cabecalho);
        }
        curl_setopt($ch, CURLOPT_TIMEOUT_MS, $time);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        $resposta = curl_exec($ch);
        curl_close($ch);

        $return['status'] = $status;
        $return['response'] = $resposta;

        return $this->retornoReq = $return;
    }

    public function convertObjTOxml($array, $rootElement = null, $xml = null)
    {
        $_xml = $xml;

        // If there is no Root Element then insert root 
        if ($_xml === null) {
            $_xml = new \SimpleXMLElement($rootElement !== null ? $rootElement : '<preApprovalRequest/>');
        }

        // Visit all key value pair 
        foreach ($array as $k => $v) {

            // If there is nested array then 
            if (is_array($v)) {

                // Call function for nested array 
                $this->convertObjTOxml($v, $k, $_xml->addChild($k));
            } else {

                // Simply add child element.  
                $_xml->addChild($k, $v);
            }
        }

        return $_xml->asXML();
    }

    public function convertXmlSessionArray($xml)
    {
        $xml = simplexml_load_string($xml);
        $json = json_encode($xml, JSON_UNESCAPED_UNICODE);
        $return = json_decode($json, TRUE);

        return isset($return['id']) ? $return['id'] : '';
    }

    public function tratarNUmeroTelefone($tel)
    {
        $tel = preg_replace('/[^0-9]/', '', $tel);
        $tel = substr($tel, 0, 11);

        return array(
            'ddd' => substr($tel, 0, 2),
            'numero' => substr($tel, 2, 9)
        );
    }

    public function tratarCpfCnpj($cpf_cnpj)
    {
        $cpf_cnpj = preg_replace('/[^0-9]/', '', $cpf_cnpj);
        $cpf_cnpj = substr($cpf_cnpj, 0, 14);

        if (strlen($cpf_cnpj) == 11) {
            return array(
                'type' => 'CPF',
                'value' => $cpf_cnpj
            );
        } elseif (strlen($cpf_cnpj) == 14) {
            return array(
                'type' => 'CNPJ',
                'value' => $cpf_cnpj
            );
        } else {
            return array(
                'type' => '',
                'value' => ''
            );
        }
    }
}
