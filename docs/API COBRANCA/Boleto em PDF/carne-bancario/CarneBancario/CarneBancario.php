<?php

use Dompdf\Dompdf;

class CarneBancario {

    private $dompdf;
    public $boletos = [];

    function __construct() {
        $options = [
            'isHtml5ParserEnabled' => true,
            'isRemoteEnabled' => true
        ];
        $this->dompdf = new Dompdf($options);
        $this->dompdf->setPaper('A4', 'portrait');
    }

    public function getPDF() {
        ob_start();
        $this->montarHTML();
        $conteudoPDF = ob_get_clean();
        $this->dompdf->loadHtml($conteudoPDF);
        $this->dompdf->render();
        return $this->dompdf->output();
    }

    public function adicionarParcela($dadosBoleto) {
        $dadosBoleto = $this->formatarDados($dadosBoleto);
        $this->boletos[] = $dadosBoleto;
    }

    private function montarHTML() {
        $boletos = $this->boletos;
        require_once 'HTMLCarne.php';
//        exit();
    }

    private function formatarLinhaDigitavel($linha) {
        $p1 = substr($linha, 0, 4);
        $p2 = substr($linha, 19, 5);
        $p3 = $this->modulo10("$p1$p2");
        $p4 = "$p1$p2$p3";
        $p5 = substr($p4, 0, 5);
        $p6 = substr($p4, 5);
        $campo1 = "$p5.$p6";

        $p1 = substr($linha, 24, 10);
        $p2 = $this->modulo10($p1);
        $p3 = "$p1$p2";
        $p4 = substr($p3, 0, 5);
        $p5 = substr($p3, 5);
        $campo2 = "$p4.$p5";

        $p1 = substr($linha, 34, 10);
        $p2 = $this->modulo10($p1);
        $p3 = "$p1$p2";
        $p4 = substr($p3, 0, 5);
        $p5 = substr($p3, 5);
        $campo3 = "$p4.$p5";

        $campo4 = substr($linha, 4, 1);

        $campo5 = substr($linha, 5, 14);

        return "$campo1 $campo2 $campo3 $campo4 $campo5";
    }

    private function modulo11($num, $base = 9, $r = 0) {
        $soma = 0;
        $fator = 2;
        for ($i = strlen($num); $i > 0; $i--) {
            $numeros[$i] = substr($num, $i - 1, 1);
            $parcial[$i] = $numeros[$i] * $fator;
            $soma += $parcial[$i];
            if ($fator == $base) {
                $fator = 1;
            }
            $fator++;
        }
        if ($r == 0) {
            $soma *= 10;
            $digito = $soma % 11;

            //corrigido
            if ($digito == 10) {
                $digito = "X";
            }


            if (strlen($num) == "43") {
                //ent�o estamos checando a linha digit�vel
                if ($digito == "0" or $digito == "X" or $digito > 9) {
                    $digito = 1;
                }
            }
            return $digito;
        } elseif ($r == 1) {
            $resto = $soma % 11;
            return $resto;
        }
    }

    private function modulo10($num) {
        $numtotal10 = 0;
        $fator = 2;

        for ($i = strlen($num); $i > 0; $i--) {
            $numeros[$i] = substr($num, $i - 1, 1);
            $parcial10[$i] = $numeros[$i] * $fator;
            $numtotal10 .= $parcial10[$i];
            if ($fator == 2) {
                $fator = 1;
            } else {
                $fator = 2;
            }
        }

        $soma = 0;
        for ($i = strlen($numtotal10); $i > 0; $i--) {
            $numeros[$i] = substr($numtotal10, $i - 1, 1);
            $soma += $numeros[$i];
        }
        $resto = $soma % 10;
        $digito = 10 - $resto;
        if ($resto == 0) {
            $digito = 0;
        }

        return $digito;
    }

    private function formatarDados($dadosBoleto) {

        if (isset($dadosBoleto->codigoBarras)) {
            $dadosBoleto->codigoBarrasDigitavel = $this->formatarLinhaDigitavel($dadosBoleto->codigoBarras);
        }

        return $dadosBoleto;
    }

}