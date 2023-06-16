/**
 * @package     Gamuza_Autocomplete
 * @copyright   Copyright (c) 2022 Gamuza Technologies (http://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

var autocompleteUF = [];

function autocomplete (object, type)
{
    var postcode = object.value.replace(/\D/, "");

    if (postcode.length != 8)
    {
        return false;
    }

    var maxHeight = Math.max(
        document.body.scrollHeight, document.documentElement.scrollHeight,
        document.body.offsetHeight, document.documentElement.offsetHeight,
        document.body.clientHeight, document.documentElement.clientHeight
    );

    $("autocomplete-overlay").setStyle({ height: maxHeight + "px" });
    $("autocomplete-overlay").show ();
    $("autocomplete-mask").show ();

    new Ajax.Request(autocompleteBaseUrl + "autocomplete/cep?q=" + postcode, {
        'method': 'get',
        onSuccess: function (transport) {
            if (!transport.responseJSON.erro)
            {
                $(type + "region_id").setValue(autocompleteUF [transport.responseJSON.uf]);
                $(type + "city").value = transport.responseJSON.localidade;
                $(type + "street_1").value  = transport.responseJSON.logradouro;
                $(type + "street_2").value  = '';
                $(type + "street_3").value  = transport.responseJSON.complemento;
                $(type + "street_4").value  = transport.responseJSON.bairro;
                $(type + "street_2").focus();
            }
        },
        onComplete: function () {
            $("autocomplete-mask").hide ();
            $("autocomplete-overlay").hide ();
        },
    }, 12000);
}

