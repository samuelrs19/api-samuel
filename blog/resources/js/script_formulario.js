$(document).ready(function () {

    PagSeguroDirectPayment.setSessionId($('#sessionId').val());

    $("input#nuncartao_text").mask('9999 9999 9999 9999');
    $("input#cvv_txt").mask('999');
    $("input#mesvencimento_txt").mask('99');
    $("input#anovencimento_txt").mask('9999');
    $('input#docNumber').mask('99999999999');
    $('input#tel_txt').mask('(99)99999-9999');

    var options = {
        onKeyPress: function (cpf, ev, el, op) {
            var masks = ['000.000.000-000', '00.000.000/0000-00'];
            $('#doc_text').mask((cpf.length > 14) ? masks[1] : masks[0], op);
        }
    }

    $('#doc_text').length > 11 ? $('#doc_text').mask('00.000.000/0000-00', options) : $('#doc_text').mask('000.000.000-00#', options);

    $("#nuncartao_text").keyup(function (event) {

        console.log($(this).val());

        $('#bandeira_txt').val('');
        $('#bandeira_txt').attr('data-bandeira', '');

        if ($('#nuncartao_text').val().length >= 16) {

            console.log('qtd: ' + $(this).val().length);
            console.log('numero cartão: ' + $(this).val().replace(/ /g, ""));

            PagSeguroDirectPayment.getBrand({
                cardBin: $(this).val().replace(/ /g, ""),
                success: function (response) {
                    console.log('response:: ', response);
                    $('#bandeira_txt').val(response.brand.name.toUpperCase());
                    $('#bandeira_txt').attr('data-bandeira', response.brand.name);
                    return false;
                },
                error: function (response) {
                    console.log('Erro 1:: ', response);
                    return false;
                }
            });
        }
    });

    $('#nome_txt').keyup(function (e) {

        var reg = /[a-zA-Z]/i

        if (reg.test(this.value)) {
            $(this).val(this.value.toUpperCase());
        }

    });

    $('#salvardados_btn').on('click', function () {

        $('#retorno_adesao').html('<img id="gif" src="../resources/img/loading.gif" />');

        PagSeguroDirectPayment.createCardToken({
            cardNumber: $('#nuncartao_text').val().replace(/ /g, ""),
            brand: $('#bandeira_txt').attr('data-bandeira'),
            cvv: $('#cvv_txt').val(),
            expirationMonth: $('#mesvencimento_txt').val(),
            expirationYear: $('#anovencimento_txt').val(),
            success: function (response) {

                let token = response.card.token;
                console.log('sucesso: ', response);
                //$('#retorno_adesao').html('Pronto :)');
                let hash = PagSeguroDirectPayment.getSenderHash();

                console.log('hash: ' + hash + ' | token: ' + token);
                criarPlano(response.card.token, hash)
            },
            error: function (response) {
                console.log('error 2 :: ', response);
                $('#retorno_adesao').html('Deu ruim');
            }
        });

    });
});


function criarPlano(token, hash) {

    $.post('api/v1/adesao', {
        nome: $('#nome_txt').val(),
        email: $('#email_text').val(),
        tipo_doc: $('#tipodoc_text').val(),
        cpf_cnpj: $('#doc_text').val(),
        telefone: $('#tel_txt').val(),
        plano: $('#plano_txt').val(),
        token: token,
        hash: hash
    }, function (data) {

        

        data = "<pre>" + JSON.stringify(data, undefined, 2) + "</pre>";

        console.log('data: ', data);

        $('#retorno_adesao').html(data);
    });
}
