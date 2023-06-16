/**
 * @package     Gamuza_Basic
 * @copyright   Copyright (c) 2023 Gamuza Technologies. (https://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

$j(document).ready(function(){
    /**
     * TinySlider
     */
    $j('.tiny-slider').each(function(index, value){
        var slider = tns({
            container: value,
            items: 2,
            gutter: 10,
            controls: false,
            nav: false,
            speed: 1000,
            autoplay: true,
            autoplayButtonOutput: false,
            mouseDrag: true,
            responsive: {
                481: {
                    items: 6,
                }
            }
        });
    });
});

