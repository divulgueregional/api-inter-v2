# PAGAR BOLETO-INTER

## Pagar boleto
Método para inclusão de um pagamento imediato ou agendamento do pagamento de boleto, convênio ou tributo com código de barras.<br>
Este endpoint está implementado com um rate-limit de 10 chamadas por minuto.

```php
    require '../../../vendor/autoload.php';

    use Divulgueregional\ApiInterV2\InterBanking;

    $config = [
        'certificate' => '../cert/Inter_API_Certificado.crt',//local do certificado crt
        'certificateKey' => '../cert/Inter_API_Chave.key',//local do certificado key
    ];

    $filters = [
        'codBarraLinhaDigitavel' =>  $Post->codBarraLinhaDigitavel,// required
        'valorPagar' =>  $Post->valorPagar,// required
        'dataPagamento' =>  $Post->dataPagamento,// Data para efetivar o pagamento. Se não informada, o pagamento será feito no mesmo dia. Formato aceito: YYYY-MM-DD
        'dataVencimento' =>  $Post->dataVencimento,// required Data de vencimento do título. Formato aceito: YYYY-MM-DD
    ];
    $token = '';//seu token
    try {
        $bankingInter = new InterBanking($config);
        $bankingInter->setToken($token);

        echo "<pre>";
        $pagarBoleto = $bankingInter->pagarBoleto($filters);
        print_r($pagarBoleto);
    } catch (\Exception $e) {
        echo $e->getMessage();
    }
```