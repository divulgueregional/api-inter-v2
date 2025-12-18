# CONSULTAR CALLBACKS-INTER

## Consultar Callbacks

Retorna as requisições de callbacks ordenado pela data de disparo (decrescente)

## Escopo

Escopo requerido: boleto-cobranca.read<br>
Rate limit: 10 chamadas por minuto

## Parâmetros

- dataHoraInicio (obrigatório): yyyy-MM-dd'T'HH:mm[:ss][.SSS]XXX
- dataHoraFim (obrigatório): yyyy-MM-dd'T'HH:mm[:ss][.SSS]XXX
- pagina (opcional): default 0
- tamanhoPagina (opcional): inteiro de 10 a 50 (default 20)
- codigoSolicitacao (opcional): uuid

## Responses

- 200 Sucesso
- 400 Problemas na requisição.
- 500 Serviço não está disponível no momento.

```php
    require '../../../vendor/autoload.php';

    use Divulgueregional\ApiInterV2\InterBanking;

    $config = [
        'certificate' => '../cert/Inter_API_Certificado.crt',//local do certificado crt
        'certificateKey' => '../cert/Inter_API_Chave.key',//local do certificado key
        // 'sandbox' => true, //opcional
        // 'contaCorrente' => '12345678', //opcional (x-conta-corrente)
    ];

    $filters = [
        'dataHoraInicio' => '2023-06-01T00:00Z',//obrigatorio
        'dataHoraFim' => '2023-06-10T00:00Z',//obrigatorio
        'pagina' => 0,
        'tamanhoPagina' => 20,
        'codigoSolicitacao' => null,
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
