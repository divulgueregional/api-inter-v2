# INFORMAÇÃO PAGAMENTO DARF-INTER

## Informacao Pagamento DARF
Endpoint responsável por buscar informações de pagamentos de DARF.

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
    //  Os filtros de data inicial e data final se aplicarão a:
    // INCLUSAO - Filtro de data pela data da operação que foi incluida. (Default)
    // PAGAMENTO - Filtro de data em que foi realizado o pagamento de fato.
    // VENCIMENTO - Filtro de data de vencimento do pagamento da DARF.
    // PERIODO_APURACAO - Filtro de data de período de apuração do pagamento da DARF.
    $filters = [
        'codigoTransacao' => null,
        'codigoReceita' => null,
        'dataInicio' => null,
        'dataFim' => null,
        'filtrarDataPor' => null,
    ];
    try {
        echo "<pre>";
        $respose = $bankingInter->informacaoPagamentoDARF($filters);
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