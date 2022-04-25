<?php

namespace Divulgueregional\ApiInterV2;

class GerarBoletoInter {

    protected $urlInter = "";
    protected $dadosBanco = [
        'agencia' => "1025",
        'conta' => "1025302",
    ];
    protected $dadosBoleto = [
        "carteira" => "",
        "beneficente" => "",
        "Valor" => ""
    ];

    function MakeBoleto(array $dadosBanco, array $dadosBoleto)
    {

        try {
            

        } catch (\Exception $e) {
            //throw $th;
        }
        
    }
}