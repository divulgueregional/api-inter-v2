# INFORMAÇÃO PAGAMENTO BOLETO-INTER

## Informacao Pagamento Boleto
Endpoint responsável por buscar informações de pagamentos de boleto.

```php
    require '../../../vendor/autoload.php';
    use Divulgueregional\ApiInterV2\InterBanking;

    $config = [
        'certificate' => '../cert/Inter_API_Certificado.crt',//local do certificado crt
        'certificateKey' => '../cert/Inter_API_Chave.key',//local do certificado key
    ];
    $bankingInter = new InterBanking($config);
    $token = '';//seu token
    $bankingInter->setToken($token);

    // filtrarDataPor
    // Os filtros de data inicial e data final se aplicarão a:
    // INCLUSAO - Data da operação que foi solicitado o pagamento do título. (Default)
    // PAGAMENTO - Data em que foi efetuado o pagamento do título
    // VENCIMENTO - Data do vencimento do título de pagamento.
    $codBarrasLinhaDigitavel = '123400000012345678922012345678964663362129356789';
    $filters = [
        'codBarraLinhaDigitavel' => $codBarrasLinhaDigitavel,
        'codigoTransacao' => null,
        'dataInicio' => null,
        'dataFim' => null,
        'filtrarDataPor' => null,
    ];
    try {
        echo "<pre>";
        $respose = $bankingInter->informacaoPagamentoBoleto($filters);
        // print_r($respose);
        foreach ($respose['response'] as $dadosBoleto) {
            // print_r($dadosBoleto);
            echo $extdadosBoletorato->codigoTransacao.
            ' - '.$dadosBoleto->codigoBarra.
            ' - '.$dadosBoleto->tipo.
            ' - '.$dadosBoleto->dataVencimentoDigitada.
            ' - '.$dadosBoleto->dataVencimentoTitulo.
            ' - '.$dadosBoleto->dataInclusao.
            ' - '.$dadosBoleto->dataPagamento.
            ' - '.$dadosBoleto->valorPago.
            ' - '.$dadosBoleto->valorNominal.
            ' - '.$dadosBoleto->statusPagamento.
            ' - '.$dadosBoleto->nomeBeneficiario.'<br>';
        }
    } catch (\Exception $e) {
        echo $e->getMessage();
    }
```