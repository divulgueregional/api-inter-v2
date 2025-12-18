# PAGAR PIX DE COBRANÇA COM VENCIMENTO (SANDBOX)-INTER

## Pagar Pix de cobrança com vencimento (Sandbox)
Endpoint para pagar uma cobrança com vencimento via Pagamento Pix.

(Exclusivo para o ambiente Sandbox)

## Escopo

Escopo requerido: pix.write<br>

## Rate limit

10 chamadas por minuto (sandbox)

## Observações

- O token tem validade de 60 minutos e deverá ser reutilizado nas requisições.
- Endpoint exclusivo do ambiente Sandbox (use `sandbox => true` no SDK).

## Parâmetros (path)

- txid (obrigatório): string [a-zA-Z0-9]{26,35}

## Request Body

- valor (obrigatório): number

## Responses

- 201 Cobrança com vencimento Pix paga com sucesso
- 400 Problemas na requisição.
- 403 Requisição de participante autenticado que viola alguma regra de autorização.
- 503 Serviço não está disponível no momento.

## Exemplo SDK (PHP)

```php
    require '../../../vendor/autoload.php';

    use Divulgueregional\ApiInterV2\InterBanking;

    $config = [
        'certificate' => '../cert/Inter_API_Certificado.crt',//local do certificado crt
        'certificateKey' => '../cert/Inter_API_Chave.key',//local do certificado key
        'sandbox' => true, //obrigatório para este endpoint
        // 'contaCorrente' => '12345678', //opcional (x-conta-corrente)
    ];

    $txid = '';//txid
    $filters = [
        'valor' => 150,
    ];

    $token = '';//seu token
    try {
        $bankingInter = new InterBanking($config);
        $bankingInter->setToken($token);

        echo "<pre>";
        $response = $bankingInter->pagarCobrancaVencimento($txid, $filters);
        print_r($response);
    } catch (\Exception $e) {
        echo $e->getMessage();
    }
```
