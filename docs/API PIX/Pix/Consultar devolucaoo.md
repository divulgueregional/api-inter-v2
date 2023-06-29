# CONSULTAR DEVOLUÇÃO-INTER

## Consultar devolução
Endpoint para consultar uma devolução através de um E2EID do Pix e do ID da devolução.<br>
Escopo requerido: pix.read<br>
Rate limit: 120 chamadas por minuto<br>

```php
    require '../../../vendor/autoload.php';

    use Divulgueregional\ApiInterV2\InterBanking;

    $config = [
        'certificate' => '../cert/Inter_API_Certificado.crt',//local do certificado crt
        'certificateKey' => '../cert/Inter_API_Chave.key',//local do certificado key
    ];

    $e2eId = '111111111111111111111111111111';
    $id = '22222222222222222';

    $token = '';//seu token
    try {
        $bankingInter = new InterBanking($config);
        $bankingInter->setToken($token);

        echo "<pre>";
        $consultarPix = $bankingInter->consultarPix($e2eId, $id);
        print_r($consultarPix);
    } catch (\Exception $e) {
        echo $e->getMessage();
    }
```