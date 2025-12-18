# EDITAR COBRANÇA PIX-INTER

## Editar cobrança (BETA)

Editar os dados da cobrança de acordo com o `codigoSolicitacao` informado.

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
        // 'sandbox' => true, //opcional
        // 'contaCorrente' => '12345678', //opcional (x-conta-corrente)
    ];

    $token = '';//seu token
    $codigoSolicitacao = '';//codigoSolicitacao (UUID) retornado ao emitir a cobranca

    $dadosEdicao = [
        'valorNominal' => 10.00,
        'dataVencimento' => '2025-12-31'
    ];

    try {
        $bankingInter = new InterBanking($config);
        $bankingInter->setToken($token);

        echo "<pre>";
        $response = $bankingInter->editarCobrancaPix($codigoSolicitacao, $dadosEdicao);
        print_r($response);
    } catch (\Exception $e) {
        echo $e->getMessage();
    }
```
