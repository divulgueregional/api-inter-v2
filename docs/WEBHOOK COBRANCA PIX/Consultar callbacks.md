# CONSULTAR CALLBACKS-INTER

## Consultar Callbacks

Retorna as requisições de callbacks ordenado pela data de disparo (decrescente)

## Escopo

Escopo requerido: boleto-cobranca.read<br>
Rate limit: 10 chamadas por minuto

```php
    require '../../../vendor/autoload.php';

    use Divulgueregional\ApiInterV2\InterBanking;

    $config = [
        'certificate' => '../cert/Inter_API_Certificado.crt',//local do certificado crt
        'certificateKey' => '../cert/Inter_API_Chave.key',//local do certificado key
    ];

    $filters = [
        'dataHoraInicio' => '2023-06-01T00:00Z',//obrigatorio
        'dataHoraFim' => '2023-06-01T00:00Z',//obrigatorio
        'pagina' => null,
        'tamanhoPagina' => null,
        'codigoCobranca' => null,
    ];

    $token = '';//seu token
    try {
        $bankingInter = new InterBanking($config);
        $bankingInter->setToken($token);

        echo "<pre>";
        $response = $bankingInter->callbacksCobPIx($filters);
        // print_r($response);
    } catch (\Exception $e) {
        echo $e->getMessage();
    }
```
