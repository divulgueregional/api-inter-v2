<?php

class GerarBoletoInter {

    protected $urlInter = "";
    protected $dadosBanco = [
        'agencia' => "",
        'conta' => "",
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