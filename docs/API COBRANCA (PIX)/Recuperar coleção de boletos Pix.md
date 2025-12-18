# COLEÇÃO DE BOLETOS PIX-INTER

## Coleção de Boletos pix

Recuperar coleção de cobranças

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
        'situacao' => 'PAGO,EMABERTO,VENCIDO', //RECEBIDO - A_RECEBER - MARCADO_RECEBIDO - ATRASADO - CANCELADO - EXPIRADO
        'nome' => '',
        'email' => '',
        'cpfCnpj' => '',
        'nossoNumero' => '',
        'itensPorPagina' => 1000, //maximo 1000
        'paginaAtual' => 0,
        'ordenarPor' => 'PESSOA_PAGADORA', //PESSOA_PAGADORA - TIPO_COBRANCA - CODIGO_COBRANCA - IDENTIFICADOR - DATA_EMISSAO - DATA_VENCIMENTO - VALOR - STATUS
        'tipoOrdenacao' => 'ASC', //ASC - Crescente (Default). DESC - Decrescente
    ];

    $token = '';//seu token
    try {
        $bankingInter = new InterBanking($config);
        $bankingInter->setToken($token);

        $response = $bankingInter->colecaoBoletosPix($filters);
        print_r($response);
    } catch (\Exception $e) {
        echo $e->getMessage();
    }
```
