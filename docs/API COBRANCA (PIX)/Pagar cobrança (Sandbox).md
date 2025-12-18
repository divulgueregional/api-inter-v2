# PAGAR COBRANÇA (SANDBOX) PIX-INTER

## Pagar cobrança (Sandbox)

Pagamento de uma cobrança com código de barras ou QRCode (Exclusivo para o ambiente Sandbox).

Após o pagamento, será disparado um callback com os dados da cobrança atualizados para seu webhook cadastrado.

## Escopo

Escopo requerido: boleto-cobranca.write<br>
Rate limit: 10 chamadas por minuto

## Exemplo

```php
    require '../../../vendor/autoload.php';

    use Divulgueregional\ApiInterV2\InterBanking;

    $config = [
        'certificate' => '../cert/Inter_API_Certificado.crt',//local do certificado crt
        'certificateKey' => '../cert/Inter_API_Chave.key',//local do certificado key
        'sandbox' => true, //obrigatório para este endpoint
        // 'contaCorrente' => '12345678', //opcional (x-conta-corrente)
    ];

    $token = '';//seu token
    $codigoSolicitacao = '';//codigoSolicitacao (UUID) retornado ao emitir a cobranca

    // BOLETO ou PIX
    $pagarCom = 'BOLETO';

    try {
        $bankingInter = new InterBanking($config);
        $bankingInter->setToken($token);

        echo "<pre>";
        $response = $bankingInter->pagarCobrancaPix($codigoSolicitacao, $pagarCom);
        print_r($response);
    } catch (\Exception $e) {
        echo $e->getMessage();
    }
```
