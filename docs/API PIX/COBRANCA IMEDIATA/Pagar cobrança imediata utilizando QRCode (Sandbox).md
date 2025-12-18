# PAGAR COBRANÇA IMEDIATA UTILIZANDO QRCODE (SANDBOX)-INTER

## Pagar cobrança imediata utilizando QRCode (Sandbox)
Endpoint para pagar uma cobrança imediata utilizando QRCode.

(Exclusivo para o ambiente Sandbox)

## Escopo

Escopo requerido: pix.write<br>

## Rate limit

10 chamadas por minuto (sandbox)

## Observações

- O token tem validade de 60 minutos e deverá ser reutilizado nas requisições.
- Endpoint exclusivo do ambiente Sandbox (use `sandbox => true` no SDK).

## Request Body

- qrCode (obrigatório): string (URL do pixCopiaECola)
- valor (obrigatório): number
- cpfCnpj (obrigatório): string

## Responses

- 200 pagamento realizado
- 400 Requisição com formato inválido.
- 404 Não encontrado.
- 500 Acesso não permitido.

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

    $filters = [
        'qrCode' => '000201010200661010014BR.GOV.BCB.PIX2009url-exemplo.qrcode.sandbox.co/pj-s/v2/cob/f5c23856e5694ed48607ab0bd0172496520400005309863540550.005802BR5901*6013BELO HORIZONT61089999999962070503***801000014BR.GOV.BCB.PIX2578cdpj-sandbox.partners.uatinter.co/pj-s/v2/rec/60eb10961d744397903cdc376c3ae8a56300288A',
        'valor' => 100,
        'cpfCnpj' => '09008007006',
    ];

    $token = '';//seu token
    try {
        $bankingInter = new InterBanking($config);
        $bankingInter->setToken($token);

        echo "<pre>";
        $response = $bankingInter->pagarCobrancaImediataQRCodeSandbox($filters);
        print_r($response);
    } catch (\Exception $e) {
        echo $e->getMessage();
    }
```
