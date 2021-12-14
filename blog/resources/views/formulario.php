<html>
<?php
$session = (new \App\Http\Controllers\PagSeguroController())->getSession();
?>

<head>
    <title>Formulario</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
</head>

<body>
    <div class="container">
        <h3>
            Formulario de adesão
        </h3>
        <input type="hidden" id="sessionId" value="<?= $session['session'] ?>">
        <div class="row">
            <h4 style="font-weight: 400;">
                Dados pessoais:
            </h4>
            <div class="form-group col-12">
                <label for="nome_txt">Nome titular do cartão</label>
                <input type="txt" class="form-control" id="nome_txt" placeholder="Nome">
            </div>
            <div class="form-group col-6">
                <label for="email_text">Email</label>
                <input type="email" class="form-control" id="email_text" placeholder="Email">
            </div>
            <div class="form-group col-6">
                <label for="email_text">Telefone</label>
                <input type="tel" class="form-control" id="tel_txt" placeholder="(00) 00000-0000">
            </div>
            <div class="form-group col-3">
                <label for="tipodoc_text">Tipo DOC.</label>
                <select class="form-control" id="tipodoc_text">
                    <option value="cpf">CPF</option>
                    <option value="cnpj">CNPJ</option>
                </select>
            </div>
            <div class="form-group col-9">
                <label for="doc_text">DOC.</label>
                <input type="txt" class="form-control" id="doc_text">
            </div>

            <div class="col-12" style="margin-top: 17px;">
                <hr />
            </div>

            <h4 style="font-weight: 400;">
                Dados do cartão
            </h4>

            <div class="form-group col-8">
                <label for="nuncartao_text">Numero do cartão</label>
                <input type="txt" class="form-control" id="nuncartao_text" placeholder="Numero do cartão">
            </div>
            <div class="form-group col-4">
                <label for="bandeira_txt">Bandeira do cartçao</label>
                <input type="txt" class="form-control" id="bandeira_txt" placeholder="" value="" data-bandeira="" disabled>
            </div>
            <div class="form-group col-4">
                <label for="mesvencimento_txt">Mês vencimento</label>
                <select class="form-control" id="mesvencimento_txt">
                    <option value="">Selecione</option>
                    <option value="01">01</option>
                    <option value="01">02</option>
                    <option value="01">03</option>
                    <option value="01">04</option>
                    <option value="01">05</option>
                    <option value="01">06</option>
                    <option value="01">07</option>
                    <option value="01">08</option>
                    <option value="01">09</option>
                    <option value="01">10</option>
                    <option value="01">11</option>
                    <option value="01">12</option>
                </select>
            </div>
            <div class="form-group col-4">
                <label for="anovencimento_txt">Ano vencimento</label>
                <input type="text" class="form-control" id="anovencimento_txt" placeholder="Ano">
            </div>
            <div class="form-group col-4">
                <label for="cvv_txt">CVV</label>
                <input type="number" class="form-control" id="cvv_txt" placeholder="CVV">
            </div>

            <div class="col-12">
                <br />
                <button type="button" id="salvardados_btn" class="btn btn-primary">Concluir adesão</button>
            </div>
            <div class="col-12">
                <br/>
                <pre class=" language-json" style="background-color: #ededed;">
                    <code class="prettyprint" id="retorno_adesao"></code>
                </pre>
            </div>
        </div>
    </div>
</body>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js" integrity="sha384-QJHtvGhmr9XOIpI6YVutG+2QOK9T+ZnN4kzFN1RtK3zEFEIsxhlmWl5/YESvpZ13" crossorigin="anonymous"></script>
<script type="text/javascript" src="https://stc.sandbox.pagseguro.uol.com.br/pagseguro/api/v2/checkout/pagseguro.directpayment.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.11.2/jquery.mask.min.js" integrity="sha512-Y/GIYsd+LaQm6bGysIClyez2HGCIN1yrs94wUrHoRAD5RSURkqqVQEU6mM51O90hqS80ABFTGtiDpSXd2O05nw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script type="text/javascript" src="../resources/js/script_formulario.js"></script>

</html>