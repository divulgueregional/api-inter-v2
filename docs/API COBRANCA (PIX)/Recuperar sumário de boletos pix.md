# SUMÁRIO DE BOLETOS PIX-INTER

## Sumário de Boletos pix

Utilizado para recuperar o sumário de uma coleção de Cobranças por um período específico, de acordo com os parâmetros informados.

## Escopo

Escopo requerido: boleto-cobranca.read<br>
Rate limit: 120 chamadas por minuto

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
        'dataInicial' => '2022-04-01',//obrigatório
        'dataFinal' => '2022-04-28',//obrigatório
        'filtrarDataPor' => 'VENCIMENTO', //VENCIMENTO - EMISSAO - PAGAMENTO
        'situacao' => 'RECEBIDO', //RECEBIDO - A_RECEBER - MARCADO_RECEBIDO - ATRASADO - CANCELADO - EXPIRADO
        'nome' => '',
        'email' => '',
        'cpfCnpj' => '',
        'nossoNumero' => '',
    ];

    $token = '';//seu token
    try {
        $bankingInter = new InterBanking($config);
        $bankingInter->setToken($token);

        $response = $bankingInter->sumarioBoletosPix($filters);
        print_r($response);
    } catch (\Exception $e) {
        echo $e->getMessage();
    }
```
