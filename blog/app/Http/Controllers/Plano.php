<?php
namespace App\Http\Controllers;
/**
 * Objeto responsável por efetuar o controle de acesso dos usuários em alguma página e salvar os logs de acesso
 * 
 * @author Samuel Rodrigues 
 * @link https://github.com/samuelrs19
 * @link https://samuel-dev.ml
 */
class Plano extends Util
{

    public $redirectURL;
    public $reference;
    public $notificationURL;
    public $preApproval = array(
        'notificationURL' => '',
        'name' => '',
        'charge' => '',
        'period' => '',
        'amountPerPayment' => '',
        'membershipFee' => '',
        'trialPeriodDuration' => '',
        'expiration' => array(
            'value' => '',
            'unit' => ''
        ),
        'details' => '',
        'maxAmountPerPeriod' => '',
        'maxAmountPerPayment' => '',
        'maxTotalAmount' => '',
        'maxPaymentsPerPeriod' => '',
        'initialDate' => '',
        'finalDate' => '',
        'dayOfYear' => '',
        'dayOfMonth' => '',
        'dayOfWeek' => '',
        'cancelURL' => ''
    );
    public $reviewURL;
    public $maxUses;
    public $receiver = array(
        'email' => ''
    );

    public function tratarObjeto()
    {
        foreach ($this as $key => $value) {

            if (empty($value)) {

                unset($this->$key);
            } else {

                if (is_array($value)) {

                    foreach ($value as $key1 => $value1) {

                        if (empty($value1)) {

                            unset($this->$key[$key1]);
                        } else {

                            if (is_array($value1)) {

                                foreach ($value1 as $key2 => $value2) {

                                    if (empty($value2)) {

                                        unset($this->$key[$key1][$key2]);
                                    }
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
