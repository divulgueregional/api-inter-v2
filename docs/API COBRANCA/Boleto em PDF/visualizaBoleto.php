<?php


date_default_timezone_set('America/Sao_Paulo');

function formatCnpjCpf($value)
{
  $CPF_LENGTH = 11;
  $cnpj_cpf = preg_replace("/\D/", '', $value);

  if (strlen($cnpj_cpf) === $CPF_LENGTH) {
    return preg_replace("/(\d{3})(\d{3})(\d{3})(\d{2})/", "\$1.\$2.\$3-\$4", $cnpj_cpf);
  }

  return preg_replace("/(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/", "\$1.\$2.\$3/\$4-\$5", $cnpj_cpf);
}

function montarLinhaDigitavel($codigoBarras)
{
  // vamos nos concentrar no primeiro campo
  $campo1 = substr($codigoBarras, 0, 3) .
    substr($codigoBarras, 3, 1) . substr($codigoBarras, 19, 5);

  // agora o segundo campo
  $campo2 = substr($codigoBarras, 24, 10);

  // agora o terceiro campo
  $campo3 = substr($codigoBarras, 34, 10);

  // agora o quarto campo
  $campo4 = substr($codigoBarras, 4, 1);

  // finalmente o quinto campo
  $campo5 = substr($codigoBarras, 5, 4) .
    substr($codigoBarras, 9, 10);

  $linhaDigitavel = substr($campo1, 0, 5) . "." . substr($campo1, 5, 5) .
    dvLinhaDigitavel($campo1) . " " . substr($campo2, 0, 5) . "."
    . substr($campo2, 5, 5) . dvLinhaDigitavel($campo2) .
    " " . substr($campo3, 0, 5) . "."  . substr($campo3, 5, 5) .
    dvLinhaDigitavel($campo3) . " " . $campo4 . " " . $campo5;

  return $linhaDigitavel;
}

function dvLinhaDigitavel($valor)
{
  // vamos inverter o valor
  $valor = strrev($valor);
  // vamos definir os índices de múltiplicação
  $indices = "2121212121";
  // e aqui a soma da multiplicação coluna por coluna
  $soma = 0;

  // fazemos a multiplicação coluna por coluna agora
  for ($i = 0; $i < strlen($valor); $i++) {
    $res = ((int)($valor[$i])) *
      ((int)($indices[$i]));

    // Quando o resultado da multiplicação for um número 
    // com 2 dígitos, somar os 2 algarismos.
    // Ex: (10: 1+0 = 1). 
    if ($res > 9) {
      // vamos converter o valor númerico em string
      $res = strval($res);
      $res = ((int)($res[0]) + (int)($res[1]));
    }

    $soma = $soma + $res;
  }

  // obtemos o resto da divisão da soma por 10
  $resto = $soma % 10;

  //  Se o resto da divisão for 0 (zero), o DV 
  // será 0 (zero).
  if ($resto == 0) {
    $digito = 0;
  }
  // Se o Total da Soma for inferior a 10, o DV corresponde
  // à diferença entre 10 e o Total da Soma
  else if ($soma < 10) {
    $digito = 10 - $soma;
  } else {
    // subtraímos onze pelo resto da divisão
    $digito = 10 - $resto;
  }

  return $digito;
}

function Mask($mask, $str)
{

  $str = str_replace(" ", "", $str);

  for ($i = 0; $i < strlen($str); $i++) {
    $mask[strpos($mask, "#")] = $str[$i];
  }

  return $mask;
}

function codigoBarra($linhaDigitavel)
{
  $codigo = $linhaDigitavel;
  $barcodes = array('00110', '10001', '01001', '11000', '00101', '10100', '01100', '00011', '10010', '01010');
  $barraStart = '<div class="barcode"><div class="black thin"></div><div class="white thin"></div><div class="black thin"></div><div class="white thin"></div>';
  $barraStop = '<div class="black large"></div><div class="white thin"></div><div class="black thin"></div></div>';
  $retorno = "";

  for ($a = 9; $a >= 0; $a--) {
    for ($b = 9; $b >= 0; $b--) {
      $ind = ($a * 10) + $b;
      $texto = "";

      for ($c = 1; $c < 6; $c++) {
        $texto .= substr($barcodes[$a], ($c - 1), 1) . substr($barcodes[$b], ($c - 1), 1);
      }
      $barcodes[$ind] = $texto;
    }
  }

  while (strlen($codigo) > 0) {
    $codEsq = round(substr($codigo, 0, 2));
    $codigo = substr($codigo, strlen($codigo) - (strlen($codigo) - 2), strlen($codigo) - 2);
    $binario = $barcodes[$codEsq];

    for ($i = 1; $i < 11; $i += 2) {
      $retorno .= "<div class='black " . (substr($binario, ($i - 1), 1) == "0" ? "thin" : "large") . "'></div>";
      $retorno .= "<div class='white " . (substr($binario, $i, 1) == "0" ? "thin" : "large") . "'></div>";
    }
  }

  return $barraStart . $retorno . $barraStop;
}


require_once 'inter/vendor/autoload.php';

use Divulgueregional\ApiInterV2\InterBanking;



$config = [
  'certificate' => __DIR__ . '/inter/cert/' . $caminhocert . '.crt', //local do certificado crt
  'certificateKey' => __DIR__ . '/inter/cert/' . $caminhocert . '.key', //local do certificado key
  'verify' => false
];

$bankingInter = new InterBanking($config);


function geraToken($bankingInter, $client_id, $client_secret)
{
  $token = $bankingInter->getToken($client_id, $client_secret);
  return $token;
}




$bankingInter->setToken($token);

function consultaBoletoDetalhado($bankingInter, $numero)
{
  $boletoDetalhado = $bankingInter->boletoDetalhado($numero);
  return ($boletoDetalhado);
}
$retornoBoletoDetalhado = consultaBoletoDetalhado($bankingInter, $nossonumero);

$descBoletoDesconto = '';
$descBoletoMulta = '';
$descBoletoMora = '';

if ($retornoBoletoDetalhado['response']->desconto1->codigo == 'PERCENTUALDATAINFORMADA') {
  $descBoletoDesconto = 'DESCONTO: ' . $retornoBoletoDetalhado['response']->desconto1->taxa . '% ATÉ: ' . date('d/m/Y', strtotime($retornoBoletoDetalhado['response']->desconto1->data)) . '.';
}

if ($retornoBoletoDetalhado['response']->desconto1->codigo == 'VALORFIXODATAINFORMADA') {
  $descBoletoDesconto = 'DESCONTO: R$' . $retornoBoletoDetalhado['response']->desconto1->valor . ' ATÉ: ' . date('d/m/Y', strtotime($retornoBoletoDetalhado['response']->desconto1->data)) . '.';
}

if ($retornoBoletoDetalhado['response']->multa->codigo == 'PERCENTUAL') {
  $descBoletoMulta = ' MULTA DE ' . $retornoBoletoDetalhado['response']->multa->taxa . '% EM ' . date('d/m/Y', strtotime($retornoBoletoDetalhado['response']->multa->data)) . '.';
}
if ($retornoBoletoDetalhado['response']->multa->codigo == 'VALORFIXO') {
  $descBoletoMulta = ' MULTA DE R$' . $retornoBoletoDetalhado['response']->multa->valor . ' EM ' . date('d/m/Y', strtotime($retornoBoletoDetalhado['response']->multa->data)) . '.';
}
if ($retornoBoletoDetalhado['response']->mora->codigo == 'TAXAMENSAL') {
  $descBoletoMora = ' MORA DE ' . $retornoBoletoDetalhado['response']->mora->taxa . '% A PARTIR DE ' . date('d/m/Y', strtotime($retornoBoletoDetalhado['response']->mora->data)) . '.';
}
if ($retornoBoletoDetalhado['response']->mora->codigo == 'VALORDIA') {
  $descBoletoMora = ' MORA DE R$' . $retornoBoletoDetalhado['response']->mora->valor . ' A PARTIR DE ' . date('d/m/Y', strtotime($retornoBoletoDetalhado['response']->mora->data)) . '.';
}

require_once 'plugins/dompdf/vendor/autoload.php';

// reference the Dompdf namespace
use Dompdf\Dompdf;

// instantiate and use the dompdf class
$tmp = sys_get_temp_dir();

$dompdf = new Dompdf([
  'logOutputFile' => '',
  // authorize DomPdf to download fonts and other Internet assets
  'isRemoteEnabled' => true,
  // all directories must exist and not end with /
  'fontDir' => $tmp,
  'fontCache' => $tmp,
  'tempDir' => $tmp,
  'chroot' => $tmp,
]);
$path = 'img/logobol2.png';
$type = pathinfo($path, PATHINFO_EXTENSION);
$data = file_get_contents($path);
$base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);

$path2 = 'img/inter.png';
$type2 = pathinfo($path2, PATHINFO_EXTENSION);
$data2 = file_get_contents($path2);
$base642 = 'data:image/' . $type2 . ';base64,' . base64_encode($data2);

$path3 = 'img/inter2.jpg';
$type3 = pathinfo($path3, PATHINFO_EXTENSION);
$data3 = file_get_contents($path3);
$base643 = 'data:image/' . $type3 . ';base64,' . base64_encode($data3);


$html = '
<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Inter - Boleto</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@500&display=swap" rel="stylesheet">
  <style>
    body {
      background-color: #fff;
      font-family: Inter;
      font-size: 11px;
    }
  </style>
  <style>
	.barcode .thin.black {
		border-left-width: 1px;
	}

	.barcode .thin.white {
		width: 1px;
	}

	.barcode .large.black {
		border-left-width: 3px;
	}

	.barcode .large.white {
		width: 3px;
	}

	.barcode .black {
		border-color: #000;
		border-left-style: solid;
		width: 0;
	}

	.barcode .white {
		background: none repeat scroll 0 0 #fff;
	}

	.barcode div {
		display: inline-block;
		height: 18%;
    margin-top: 10px
	}
</style>
</head>

<body>
  <table border="0" cellspacing="0" cellpadding="0" style="width: 100%">
    <tr>
      <td style="width:140px"><img src="' . $base64 . '" width="120px"></td>
      <td style="text-align: left; vertical-align:bottom;"><b>NOME EMPRESA</b><br />CNPJ: CNPJ<br />EMAIL</td>
      <td style="text-align: right; vertical-align:bottom">ENDEREÇO<br />CEP</td>
    </tr>
  </table>
  <hr style="border: 0;background: #000;">

  <table border="0" cellspacing="10" cellpadding="5" style="width: 100%; font-size:9px">
    <tr>
      <td style="border: 1px solid #000; border-radius: 6px; padding-left: 20px; width: 70%">
      <b>Condomínio:</b>NOME<br />
      <b>CNPJ:</b>CNPJ<br />
      <b>Endereço:</b>ENDEREÇO</td>
      <td rowspan="2" style="text-align: right; vertical-align:middle"><img src="' . $base642 . '" width="130px"><br /><img src="' . $base643 . '" width="180px"></td>
    </tr>

    <tr>
      <td style="border: 1px solid #000; border-radius: 6px; padding-left: 20px; width: 70%">
      <b>Responsável:</b>NOME<br />
      <b>CPF/CNPJ:</b>CPF<br />
      <b>Endereço:</b>ENDEREÇO</td>
    </tr>
  </table>
  <hr style="border: 0;background: #000;">
  <table border="0" cellspacing="10" cellpadding="5" style="width: 100%; font-size:9px">
    <tr>
      <td style="border: 1px solid #000; border-radius: 6px; padding-left: 20px; width: 70%; vertical-align:top">
        <table border="0" style="width: 100%; font-size:9px">
          <tr>
            <td style="width: 80%"><b>Detalhamento</b></td>
            <td align="right"><b>Valor</b></td>
          </tr>
        </table>
      </td>
      <td style="border: 1px solid #000; border-radius: 6px; text-align: center; vertical-align:middle">Pagamento para a conta<br />' . $nomefantasia . '<br />
      Nº DOCUMENTO: ' . $retornoBoletoDetalhado['response']->seuNumero . '<br />
      VENCIMENTO: ' . date('d/m/Y', strtotime($retornoBoletoDetalhado['response']->dataVencimento)) . '<br />
      VALOR: R$' . number_format($retornoBoletoDetalhado['response']->valorNominal, 2, ',', '.') . '</td>
    </tr>
  </table>
  <hr style="border: 0;background: #000;"><span style="font-size: 8px; padding-left: 450px">Autenticação Mecânica</span>
  <hr style="border: 0;background: #fff;">
  <hr style="border: 0.5 dashed #000; margin-top: 30px">
  <table border="0" cellspacing="0" cellpadding="0" style="width: 100%; font-size:9px;">
    <tr>
      <td style="width: 180px">
        <img src="' . $base642 . '" width="120px">
      </td>
      <td style="text-align: center;border-left: 1px solid black; border-right: 1px solid black; vertical-align:bottom !important;"><span style="font-size:18px;font-weight:bold;">&nbsp;077-9&nbsp;</span></td>
      <td style="font-weight: bold; font-size:18px; vertical-align:bottom;text-align: justify; padding-left: 10px">' . montarLinhaDigitavel($retornoBoletoDetalhado['response']->codigoBarras) . '</td>
    </tr>
  </table>
  <table cellspacing="0" cellpadding="0" border="0" style="width: 100%; font-size: 9px; border-top: 1px solid black">
    <tr>
      <td colspan="5" style="border-bottom: 1px solid black; border-right: 1px solid black;  font-weight: bold">
        <b>Local de Pagamento</b>
        <br />
        PAGÁVEL EM QUALQUER BANCO
      </td>
      <td width="30%" bgcolor="#d2d2d2" style="vertical-align:top; border-bottom: 1px solid black;">
        <span><b>Vencimento</b></span>
        <br />
        <span style="float: right"><b>' . date('d/m/Y', strtotime($retornoBoletoDetalhado['response']->dataVencimento)) . '</b></span>
      </td>
    </tr>
    <tr>
      <td colspan="5" style="border-bottom: 1px solid black; border-right: 1px solid black;">
        <b>Beneficiário</b>
        <br /><b>
        ' . formatCnpjCpf($retornoBoletoDetalhado['response']->cnpjCpfBeneficiario) . ' - ' . $retornoBoletoDetalhado['response']->nomeBeneficiario . '
        </b></td>
      <td width="30%" style="vertical-align:top; border-bottom: 1px solid black;">
        <span><b>Agência / Conta</b></span>
        <br />
        <span style="float: right"><b>00019/' . $retornoBoletoDetalhado['response']->contaCorrente . '</b></span>
      </td>
    </tr>
    <tr>
      <td colspan="5" style="border-bottom: 1px solid black; border-right: 1px solid black;">
        <b>Endereço do Beneficiário
        <br />
        ' . mb_strtoupper($montaendereco) . '</b>
      </td>
      <td width="30%" style="vertical-align:top; border-bottom: 1px solid black;">
        <b>Nosso Número / Cód. do Documento</b>
        <br />
        <span style="float: right"><b>00019/112/' . substr_replace($retornoBoletoDetalhado['response']->nossoNumero, '-', -1, 0) . '</b></span>
      </td>
    </tr>
    <tr>
      <td style="border-bottom: 1px solid black; border-right: 1px solid black;">
        <b>Data do Documento</b>
        <br />
        <b>' . date('d/m/Y', strtotime($retornoBoletoDetalhado['response']->dataEmissao)) . '</b>
      </td>
      <td style="border-bottom: 1px solid black; border-right: 1px solid black;">
        <b>Nº do Documento
        <br />
        ' . $retornoBoletoDetalhado['response']->seuNumero . '</b>
      </td>
      <td style="border-bottom: 1px solid black; border-right: 1px solid black;">
        <b>Espécia Documento
        <br />
        DM</b>
      </td>
      <td style="border-bottom: 1px solid black; border-right: 1px solid black;">
        <b>Aceite
        <br />
        NAO</b>
      </td>
      <td style="border-bottom: 1px solid black; border-right: 1px solid black;">
        <b>Data de Processamento
        <br />
        ' . date('d/m/Y', strtotime($retornoBoletoDetalhado['response']->dataEmissao)) . '</b>
      </td>
      <td width="30%" bgcolor="#d2d2d2" style="vertical-align:top; border-bottom: 1px solid black;">
        <span><b>(=) Valor do Documento</b></span>
        <br />
        <span style="float: right"><b>' . number_format($retornoBoletoDetalhado['response']->valorNominal, 2, ',', '.') . '</b></span>
      </td>
    </tr>
    <tr>
      <td style="border-bottom: 1px solid black; border-right: 1px solid black;">
        <b>Uso do Banco</b>
        <br />
        &nbsp;
      </td>
      <td style="border-bottom: 1px solid black; border-right: 1px solid black;">
        <b>Carteira
        <br />
        112</b>
      </td>
      <td style="border-bottom: 1px solid black; border-right: 1px solid black;">
        <b>Espécia Moeda
        <br />
        REAL</b>
      </td>
      <td style="border-bottom: 1px solid black; border-right: 1px solid black;">
        <b>Quantidade Moeda</b>
        <br />
        &nbsp;
      </td>
      <td style="border-bottom: 1px solid black; border-right: 1px solid black;">
        <b>Valor Moeda</b>
        <br />
        &nbsp;
      </td>
      <td width="30%" style="vertical-align:top; border-bottom: 1px solid black;">
        <span><b>(-) Desconto / Abatimento</b></span>
        <br />
        <span style="float: right"></span>
      </td>
    </tr>
    <tr>
      <td colspan="5" rowspan="4" style="border-bottom: 1px solid black; border-right: 1px solid black;" valign="top">
      <b>Informações de responsabilidade do beneficiário:
      ' . $descBoletoDesconto . $descBoletoMulta . $descBoletoMora . '
      <br/>Data Limite para pagamento: ' . date('d/m/Y', strtotime($retornoBoletoDetalhado['response']->dataLimite)) . '</b>
      </td>
      <td width="30%" style="vertical-align:top; border-bottom: 1px solid black;">
        <span><b>(-) Outras Deduções</b></span>
        <br /><br />
        &nbsp;
      </td>
    </tr>
    <tr>
      <td width="30%" style="vertical-align:top; border-bottom: 1px solid black;">
        <span><b>(+) Mora / Multa</b></span>
        <br /><br />
        &nbsp;
      </td>
    </tr>
    <tr>
      <td width="30%" style="vertical-align:top; border-bottom: 1px solid black;">
        <span><b>(+) Outros Acréscimos</b></span>
        <br /><br />
        &nbsp;
      </td>
    </tr>
    <td width="30%" style="vertical-align:top; border-bottom: 1px solid black;" bgcolor="#d2d2d2">
      <span><b>(=) Valor Cobrado</b></span>
      <br /><br />
      &nbsp;
    </td>
    </tr>
  </table>
  <table cellspacing="0" cellpadding="0" border="0" style="width: 100%; font-size: 9px; border-top: 1px solid black;">
    <tr>
      <td style="width: 15%"></td>
      <td style="width: 65%"></td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td style="width: 15%"><b>Pagador</b></td>
      <td style="width: 65%"><b>' . $retornoBoletoDetalhado['response']->pagador->nome . '</b></td>
      <td><b>CNPJ/CPF: ' . formatCnpjCpf($retornoBoletoDetalhado['response']->pagador->cpfCnpj) . '<b/></td>
    </tr>
    <tr>
      <td></td>
      <td><b>' . mb_strtoupper($retornoBoletoDetalhado['response']->pagador->endereco . ' ' . $retornoBoletoDetalhado['response']->pagador->numero . ' ' . $retornoBoletoDetalhado['response']->pagador->complemento) . '</b></td>
      <td></td>
    </tr>
    <tr>
      <td></td>
      <td><b>' . mb_strtoupper($retornoBoletoDetalhado['response']->pagador->bairro . ' ' . Mask("#####-###", $retornoBoletoDetalhado['response']->pagador->cep) . ' ' . $retornoBoletoDetalhado['response']->pagador->cidade . '/' . $retornoBoletoDetalhado['response']->pagador->uf) . '<b/></td>
      <td></td>
    </tr>
    <tr>
      <td><b>Beneficiário Final</b></td>
      <td><b>' . $retornoBoletoDetalhado['response']->nomeBeneficiario . '</b></td>
      <td><b>CNPJ/CPF: ' . formatCnpjCpf($retornoBoletoDetalhado['response']->cnpjCpfBeneficiario) . '</b></td>
    </tr>
    <tr>
      <td style="width: 15%"></td>
      <td style="width: 65%"></td>
      <td>&nbsp;</td>
    </tr>
  </table>
  <hr style="border: 0;background: #000;"><span style="font-size: 8px; padding-left: 450px">Autenticação Mecânica</span><br /><span style="float: right; font-size: 14px"><b>Ficha de Compensação</b></span>' . codigoBarra($retornoBoletoDetalhado['response']->codigoBarras) . '
  


</body>

</html>';





$dompdf->loadHtml($html);

// (Optional) Setup the paper size and orientation
$dompdf->setPaper('A4', 'portrait');

// Render the HTML as PDF
$dompdf->render();

// Output the generated PDF to Browser
$dompdf->stream($retornoBoletoDetalhado['response']->seuNumero, array("Attachment" => false));
