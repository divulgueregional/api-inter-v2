# CANCELAR AGENDAMENTO PAGAMENTO-INTER

## Cancelar agendamento do pagamento
Método utilizado para cancelar o agendamento do pagamento de um boleto.<br>
Escopo requerido: pagamento-boleto.write<br>
Rate limit: 20 chamadas por minuto.

```php
    require '../../../vendor/autoload.php';
    use Divulgueregional\ApiInterV2\InterBanking;

    $config = [
        'certificate' => '../cert/Inter_API_Certificado.crt',//local do certificado crt
        'certificateKey' => '../cert/Inter_API_Chave.key',//local do certificado key
    ];

    $token = '';//seu token
    $codigoTransacao = ''; //informe o nossoNumero
    try {
        $bankingInter = new InterBanking($config);
        $bankingInter->setToken($token);
        echo "<pre>";
        $respose = $bankingInter->cancelarAgendamentoPagamento($codigoTransacao);
        print_r($respose);
    } catch (\Exception $e) {
        echo $e->getMessage();
    }
```