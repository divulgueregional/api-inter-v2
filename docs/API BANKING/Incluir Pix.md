# INCLUIR PIX-INTER

## Incluir Pix
Método para inclusão de um pagamento/transferência Pix utilizando dados bancários ou chave.<br>
Escopo requerido: pagamento-pix.write.<br>
Rate limit: 20 chamadas por minuto<br>
Você pagnado pix para outra pessoa pela chave

```php
    require '../../../vendor/autoload.php';

    use Divulgueregional\ApiInterV2\InterBanking;

    $config = [
        'certificate' => '../cert/Inter_API_Certificado.crt',//local do certificado crt
        'certificateKey' => '../cert/Inter_API_Chave.key',//local do certificado key
    ];

    // Exemplos de tipos:
    // Se email: email do recebedor (ex: fulano.da.silva@example.com)
    // Se CPF/CNPJ: 12345678900 / 00038166000105
    // Se número do telefone celular: +55DD9XXXXXXXX (formato internacional)
    // Se EVP: 123e4567-e12b-12d1-a456-426655440000
    $filters = [
        'valor' => '10.00',
        'dataPagamento' => null,
        'descricao' =>  'Teste',
        'destinatario' => [
            'chave' =>  '55309496068',
            'tipo' =>  'CHAVE',
        ],
    ];

    $token = '';//seu token
    try {
        $bankingInter = new InterBanking($config);
        $bankingInter->setToken($token);

        echo "<pre>";
        $incluirPix = $bankingInter->incluirPix($filters);
        print_r($incluirPix);
    } catch (\Exception $e) {
        echo $e->getMessage();
    }
```