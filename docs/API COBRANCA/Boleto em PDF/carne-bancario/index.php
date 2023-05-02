<?php
require_once 'vendor/autoload.php';//dompdf
require_once 'CarneBancario/CarneBancario.php';
require_once 'Util/ConversorDadosCarne.php';

$carneBancario = new CarneBancario();
$conversor = new ConversorDadosCarne();

$parcela1 = new stdClass();
$parcela1->codigoLinhaDigitavel = '00190000090312855700000006000178793870000001000';
$parcela1->codigoBarras = '00191954600000100000000003500053000000004717';
$parcela1->mensagemBloqueto = 'Texto instuções';
$parcela1->nossoNumero = '009825263563';
$parcela1->nomeBanco = 'Banco Inter';
$parcela1->codigoBanco = '077-9';
$parcela1->dataVencimento = '12/05/2034';
$parcela1->valorDocumento = 120;
$parcela1->numeroDocumento = '32232'; //seu número
$parcela1->nomePagador = 'Nome Cliente';
$parcela1->CNPJCPFPagador = '02327558174';
$parcela1->CEPPagador = '23421234';
$parcela1->enderecoPagador = 'Rua teste';
$parcela1->bairroPagador = 'Bairo cliente';
$parcela1->cidadePagador = 'Brasília';
$parcela1->UFPagador = 'DF';
$parcela1->nomeEmpresa = 'Nome Empresa emitente';
$parcela1->CNPJEmpresa = '87654637876547';
$parcela1->agencia = '0001-9';
$parcela1->codigoCedente = '7626264';
$parcela1->codigoAceite = 'NAO';
$parcela1->dataEmissao = '12/09/2023';
$parcela1->carteiraCobranca = '112';
$parcela1->boletoPago = false;

$parcela2 = new stdClass();
$parcela2->codigoLinhaDigitavel = '00190000090312855700000006000178793870000001000';
$parcela2->codigoBarras = '00191954600000100000000003500053000000004717';
$parcela2->mensagemBloqueto = 'Texto instuções';
$parcela2->nossoNumero = '009825263563';
$parcela2->nomeBanco = 'banco do Brasil';
$parcela2->codigoBanco = '001-9';
$parcela2->dataVencimento = '12/05/2034';
$parcela2->valorDocumento = 170;
$parcela2->numeroDocumento = '32232'; //seu número
$parcela2->nomePagador = 'Nome Cliente';
$parcela2->CNPJCPFPagador = '02327558174';
$parcela2->CEPPagador = '23421234';
$parcela2->enderecoPagador = 'Rua teste';
$parcela2->bairroPagador = 'Bairo cliente';
$parcela2->cidadePagador = 'Brasília';
$parcela2->UFPagador = 'DF';
$parcela2->nomeEmpresa = 'Nome Empresa emitente';
$parcela2->CNPJEmpresa = '87654637876547';
$parcela2->agencia = '0001-9';
$parcela2->codigoCedente = '7626264';
$parcela2->codigoAceite = 'S';
$parcela2->dataEmissao = '12/09/2023';
$parcela2->carteiraCobranca = '112';
$parcela2->boletoPago = true;

$carneBancario->adicionarParcela($parcela1);
$carneBancario->adicionarParcela($parcela1);
$carneBancario->adicionarParcela($parcela2);

/*

  OS DADOS PODEM SER PREENCHIDOS MANUALMENTE COMO NO EXEMPLO ACIMA OU USANDO O CONVERSOR DE DADOS.

  //$retornoBanco : Dados retornados pela API do banco
  //$codigoBanco : 001=Banco do Brasil    077=Banco Inter


  //Recebe os dados da API e converte para o padrão do carnê
  $parcelaConvertida = $conversor->getObjetoCarneBancario($retornoBanco, $codigoBanco);
  $carneBancario->adicionarParcela($parcelaConvertida);

 */


$pdf = $carneBancario->getPDF();



header("Content-type:application/pdf");
echo $pdf;