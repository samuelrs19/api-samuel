<?php

/**
 * Objeto responsável por efetuar o controle de acesso dos usuários em alguma página e salvar os logs de acesso
 * 
 * @author Samuel Rodrigues 
 * @link https://github.com/samuelrs19
 * @link https://samuel-dev.ml
 */

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PagSeguroController extends Util
{
    private $urlPrincipal;
    private $token;
    private $email;
    private $cabecalho;
    private $objTrataRetorno;
    private $dadosEnvia;
    private $referenciaNew;
    public $objPlano;
    public $objAdesao;

    function __construct($ambiente = false)
    {
        if ($ambiente) {
            $this->set('urlPrincipal', 'https://ws.pagseguro.uol.com.br');
            $this->set('token', '');
        } else {
            $this->set('urlPrincipal', 'https://ws.sandbox.pagseguro.uol.com.br');
            $this->set('token', '44BFAE8AA8DC4766A2191E3102633B43');
        }

        $this->set('email', 'samrs2012@gmail.com');
        $this->set('objTrataRetorno', (new TrataRetorno()));
        $this->set('objPlano', (new Plano()));
        $this->set('objAdesao', (new Adesao()));
    }

    public function set($atributo, $valor)
    {
        $this->$atributo = $valor;
    }

    public function get($atributo)
    {
        return $this->$atributo;
    }

    public function endpoint($tipo, $preApprovalCode = '', $preApprovalRequestCode = '', $paymentOrderCode = '')
    {
        $url = $this->get('urlPrincipal');

        switch ($tipo) {
            case 'request':

                /***
                 * Criação do plano.
                 */

                $url = "{$this->get('urlPrincipal')}/pre-approvals/request/?email={$this->get('email')}&token={$this->get('token')}";
                break;
            case 'sessions':

                /***
                 * Iniciar sessão para aderir um plano.
                 */

                $url = "{$this->get('urlPrincipal')}/v2/sessions?email={$this->get('email')}&token={$this->get('token')}";
                break;
            case 'pre-approvals':

                /***
                 * Adesão do plano.
                 */

                $url = "{$this->get('urlPrincipal')}/pre-approvals?email={$this->get('email')}&token={$this->get('token')}";
                break;
            case 'payment':

                /***
                 * Cobrança plano.
                 */

                $url = "{$this->get('urlPrincipal')}/pre-approvals/payment?email={$this->get('email')}&token={$this->get('token')}";
                break;
            case 'status':

                /***
                 * - Suspender um plano.
                 * - Reativar um plano.
                 */

                $url = "{$this->get('urlPrincipal')}/pre-approvals/{$preApprovalCode}/status?email={$this->get('email')}&token={$this->get('token')}";
                break;
            case 'payment-request':

                /***
                 * Edição de Valor e planos.
                 */

                $url = "{$this->get('urlPrincipal')}/pre-approvals/request/{$preApprovalRequestCode}/payment?email={$this->get('email')}&token={$this->get('token')}";
                break;
            case 'discount':

                /***
                 * Incluir um desconto no pagamento.
                 */

                $url = "{$this->get('urlPrincipal')}/pre-approvals/{$preApprovalCode}/discount?email={$this->get('email')}&token={$this->get('token')}";
                break;
            case 'payment-method':

                /***
                 * Mudar meio de pagamento.
                 */

                $url = "{$this->get('urlPrincipal')}/pre-approvals/{$preApprovalCode}/payment-method?email={$this->get('email')}&token={$this->get('token')}";
                break;
            case 'cancel':

                /***
                 * Cancelamento de Adesão.
                 */

                $url = "{$this->get('urlPrincipal')}/pre-approvals/{$preApprovalCode}/cancel?email={$this->get('email')}&token={$this->get('token')}";
                break;
            case 'payment-orders':

                /***
                 * Retentativa de Pagamento.
                 */

                $url = "{$this->get('urlPrincipal')}/pre-approvals/{$preApprovalCode}/payment-orders/{$paymentOrderCode}/payment?email={$this->get('email')}&token={$this->get('token')}";
                break;
        }

        return $url;
    }

    public function cabecalho($tipo)
    {
        if ($tipo == 'XML') {
            $this->set('cabecalho', array(
                'Accept: application/vnd.pagseguro.com.br.v3+xml;charset=ISO-8859-1',
                'Content-Type: application/xml;charset=ISO-8859-1'
            ));
        } else if ($tipo == 'JSON') {
            $this->set('cabecalho', array(
                'Content-Type: application/json',
                'Accept: application/vnd.pagseguro.com.br.v1+json;charset=ISO-8859-1'
            ));
        }
        return $this->get('cabecalho');
    }

    public function getSession()
    {
        return array(
            'session' => $this->convertXmlSessionArray($this->request($this->endpoint('sessions'), 'POST', '', '', 10000)['response'])
        );
    }

    public function criarPlano(Request $parametros)
    {
        $parametros = $parametros->all();

        $return = array();
        $return['success'] = false;
        $return['msg'] = '';

        if (empty($parametros)) {
            $return['msg'] = 'Parâmetros não enviado.';
        } else {

            $this->objPlano->reference = $this->get('referenciaNew');
            $this->objPlano->preApproval['name'] = isset($parametros['nomePlano']) ? $parametros['nomePlano'] : 'Pagamento recorrente folheto';
            $this->objPlano->preApproval['charge'] = 'AUTO';
            $this->objPlano->preApproval['period'] = 'MONTHLY';
            $this->objPlano->preApproval['amountPerPayment'] = isset($parametros['valor']) ? $parametros['valor'] : 0.00;
            $this->objPlano->preApproval['membershipFee'] = isset($parametros['taxa']) ? $parametros['taxa'] : 0.00;
            $this->objPlano->preApproval['details'] = isset($parametros['detalhes']) ? $parametros['detalhes'] : '';

            $xml = $this->objPlano->tratarObjeto()->convertObjTOxml($this->objPlano);

            $retornoReq = $this->request($this->endpoint('request'), 'POST', $this->cabecalho('XML'), $xml, 30000);
            $return = $this->get('objTrataRetorno')->retornoCriaPlano($retornoReq);
        }

        return response()->json($return);
    }

    public function aderirPlano(Request $parametros)
    {
        $parametros = $parametros->all();

        $return = array();
        $return['success'] = false;
        $return['msg'] = '';

        if (empty($parametros)) {
            $return['msg'] = 'Parâmetros não enviado.';
        } else {

            $this->objAdesao->plan = isset($parametros['plano']) ? $parametros['plano'] : 'E4AC185C2727A69554176F9397AE74BF';
            $this->objAdesao->reference = isset($parametros['reference']) ? $parametros['reference'] : '001';
            $this->objAdesao->sender['name'] = isset($parametros['nome']) ? $parametros['nome'] : '';
            $this->objAdesao->sender['email'] = isset($parametros['email']) ? $parametros['email'] : '';
            $this->objAdesao->sender['hash'] = isset($parametros['hash']) ? $parametros['hash'] : '';
            $this->objAdesao->sender['phone']['areaCode'] = isset($parametros['telefone']) ?
                $this->tratarNUmeroTelefone($parametros['telefone'])['ddd'] : '';
            $this->objAdesao->sender['phone']['number'] = isset($parametros['telefone']) ?
                $this->tratarNUmeroTelefone($parametros['telefone'])['numero'] : '';
            $this->objAdesao->sender['documents'][0] = isset($parametros['cpf_cnpj']) ?
                $this->tratarCpfCnpj($parametros['cpf_cnpj']) : '';
            $this->objAdesao->paymentMethod['type'] = 'CREDITCARD';
            $this->objAdesao->paymentMethod['creditCard']['token'] = isset($parametros['token']) ? $parametros['token'] : '';

            $array = (array) $this->objAdesao->tratarObjeto();

            $retornoReq = $this->request(
                $this->endpoint('pre-approvals'),
                'POST',
                $this->cabecalho('JSON'),
                json_encode($array, JSON_UNESCAPED_UNICODE),
                30000
            );

            // echo "<pre>";
            // print_r($this->objAdesao->tratarObjeto());
            // echo "</pre>";

            $retornoReq = json_decode($retornoReq['response'], true);
            return response()->json($retornoReq);
        }
    }
}
