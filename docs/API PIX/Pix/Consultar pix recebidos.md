# CONSULTAR PIX RECEBIDO-INTER

## Consultar pix recebidos
Endpoint para consultar um pix por um período específico, de acordo com os parâmetros informados.<br>
Escopo requerido: pix.read<br>
Rate limit: 120 chamadas por minuto<br>

```php
    require '../../../vendor/autoload.php';

    use Divulgueregional\ApiInterV2\InterBanking;

    $config = [
        'certificate' => '../cert/Inter_API_Certificado.crt',//local do certificado crt
        'certificateKey' => '../cert/Inter_API_Chave.key',//local do certificado key
    ];

    $filters = [
        "inicio" => '2023-06-01T12:09:00Z',
        "fim" => '2023-06-30T12:09:00Z',
        "txId" => null,
        "txIdPresente" => null,
        "devolucaoPresente" => null,
        "cpf" => null,
        "cnpj" => null,
        "paginacao.paginaAtual" => 0,
        "paginacao.itensPorPagina" => 100,
    ];

    $token = '';//seu token
    try {
        $bankingInter = new InterBanking($config);
        $bankingInter->setToken($token);

        echo "<pre>";
        $consultarPixRecebidos = $bankingInter->consultarPixRecebidos($filters);
        print_r($consultarPixRecebidos);
    } catch (\Exception $e) {
        echo $e->getMessage();
    }
```