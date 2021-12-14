<?php

namespace App\Http\Controllers;

/**
 * Objeto responsável por efetuar o controle de acesso dos usuários em alguma página e salvar os logs de acesso
 * 
 * @author Samuel Rodrigues 
 * @link https://github.com/samuelrs19
 * @link https://samuel-dev.ml
 */

class Adesao extends Util
{
    /***
     * Código do plano ao qual a assinatura será vinculada.
     * 
     * @var string $plan
     */
    public $plan;

    /***
     * Código de referência da assinatura no seu sistema
     * 
     * @var string $reference
     */
    public $reference;

    /***
     * @var object $sender
     */
    public $sender = array(
        /***
         * Nome completo do consumidor. 
         * Formato: Livre, com no mínimo duas sequências de strings e limite total de 50 caracteres.
         * 
         * @var string $name 
         */
        'name' => '',
        /***
         * E-mail do consumidor. 
         * Formato: Um e-mail válido, com limite de 60 caracteres.
         * 
         * @var string $email
         */
        'email' => '',
        /***
         * Endereço de IP de origem do consumidor. 
         * Obrigatório se hash for nulo. Formato: 4 números, de 0 a 255, separados por ponto.
         * 
         * @var string $ip
         */
        'ip' => '',
        /***
         * Identificador (fingerprint) gerado pelo vendedor por meio do JavaScript do PagSeguro. 
         * Obrigatório se ip for nulo. Formato: Obtido a partir do método Javascript PagseguroDirectPayment.getSenderHash().
         * 
         * @var string $hash
         */
        'hash' => '',
        /***
         * @var object $phone
         */
        'phone' => array(
            /***
             * DDD do comprador. 
             * Formato: Um número de 2 dígitos correspondente a um DDD válido.
             * 
             * @var int $areaCode
             */
            'areaCode' => '',
            /***
             * Número do telefone do comprador. 
             * Formato: Um número entre 7 e 9 dígitos.
             * 
             * @var int $number
             */
            'number' => ''
        ),
        /***
         * @var object $address
         */
        'address' => array(
            /***
             * Nome da rua. 
             * Formato: Livre, com limite de 80 caracteres.
             * 
             * @var string $street
             */
            'street' => '',
            /***
             * Número. 
             * Formato: Livre, com limite de 20 caracteres.
             * 
             * @var int $number
             */
            'number' => '',
            /***
             * Complemento (bloco, apartamento, etc.). 
             * Formato: Livre, com limite de 40 caracteres.
             * 
             * @var string $complement
             */
            'complement' => '',
            /***
             * Bairro. 
             * Formato: Livre, com limite de 60 caracteres
             * 
             * @var string $district
             */
            'district' => '',
            /***
             * Cidade. 
             * Formato: Livre. 
             * Deve ser um nome válido de cidade do Brasil, com no mínimo 2 e no máximo 60 caracteres.
             * 
             * @var string $city
             */
            'city' => '',
            /***
             * Estado. 
             * Formato: Duas letras, representando a sigla do estado brasileiro correspondente.
             * 
             * @var string $state
             */
            'state' => '',
            /***
             * País.
             * 
             * @var string $country
             */
            'country' => '',
            /***
             * CEP. 
             * Formato: Um número de 8 dígitos..
             * 
             * @var int $postalCode
             */
            'postalCode' => ''
        ),
        /***
         * @var object $documents
         */
        'documents' => array(
            array(
                /***
                 * Tipo de documento do comprador.
                 * 
                 * @var string $type
                 */
                'type' => '',
                /***
                 * CPF do comprador. Formato: Um número de 11 dígitos.
                 * 
                 * @var string $value
                 */
                'value' => ''
            )
        )
    );

    /***
     * @var object $paymentMethod
     */
    public $paymentMethod = array(
        /***
         *Tipo do meio de pagamento utilizado na assinatura.
         * 
         * @var string $type 
         */
        'type' => '',
        /***
         * @var object $creditCard
         */
        'creditCard' => array(
            /***
             *Token retornado no método Javascript PagSeguroDirectPayment.createCardToken().
             * 
             * @var string $token 
             */
            'token' => '',
            'holder' =>  ''
        )
    );

    public function tratarObjeto()
    {
        foreach ($this as $key => $value) {

            if (empty($value)) {

                unset($this->$key);
            } else {

                if (is_array($value) && !empty($value)) {

                    foreach ($value as $key1 => $value1) {

                        if (empty($value1)) {
                            unset($this->$key[$key1]);
                        } else {

                            if (is_array($value1)) {

                                $cont = count($value1);
                                $contmaimais = 0;

                                foreach ($value1 as $key2 => $value2) {

                                    if (empty($value2)) {
                                        $contmaimais++;
                                        unset($this->$key[$key1][$key2]);
                                    }
                                }

                                if ($cont == $contmaimais) {
                                    unset($this->$key[$key1]);
                                }
                            }
                        }
                    }
                }
            }
        }
        return $this;
    }
}
