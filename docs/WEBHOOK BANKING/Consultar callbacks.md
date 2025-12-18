# CONSULTAR CALLBACKS WEBHOOK BANKING-INTER

## Consultar callbacks
Retorna as requisições de callbacks ordenadas pela data de disparo (decrescente). Permite consultar erros no envio de callbacks.

## Escopo

Escopo requerido: webhook-banking.read<br>

## Rate limit

10 chamadas por minuto (produção)
10 chamadas por minuto (sandbox)

## Observações

- O token tem validade de 60 minutos e deverá ser reutilizado nas requisições.
- Header x-conta-corrente é necessário somente quando a aplicação estiver associada a mais de uma conta corrente

## Parâmetros (path)

- tipoWebhook (obrigatório): pix-pagamento, boleto-pagamento

## Parâmetros (query)

- dataHoraInicio (obrigatório): date-time (ex: 2023-06-01T00:00Z)
- dataHoraFim (obrigatório): date-time (ex: 2023-06-10T00:00Z)
- pagina (opcional): integer (default: 0)
- tamanhoPagina (opcional): integer (10..50) (default: 20)
- endToEnd (opcional): string (exclusivo para tipoWebhook=pix-pagamento)
- codigoTransacao (opcional): string (exclusivo para tipoWebhook=boleto-pagamento)

## Header

- x-conta-corrente (opcional): string ^[1-9][0-9]*$

## Responses

- 200 Sucesso
- 400 Requisição com formato inválido.
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

    $token = '';//seu token
    $tipoWebhook = 'pix-pagamento';

    $filters = [
        'dataHoraInicio' => '2023-06-01T00:00Z',
        'dataHoraFim' => '2023-06-10T00:00Z',
        'pagina' => 0,
        'tamanhoPagina' => 20,
        // 'endToEnd' => 'E00416968202309302034aqtneKsYCqs',
        // 'codigoTransacao' => 'c42f0787-02cb-4b31-827e-459ec9d7ece1',
    ];

    try {
        $bankingInter = new InterBanking($config);
        $bankingInter->setToken($token);

        echo "<pre>";
        $response = $bankingInter->consultarCallbacksWebhookBanking($tipoWebhook, $filters);
        print_r($response);
    } catch (\Exception $e) {
        echo $e->getMessage();
    }
```
