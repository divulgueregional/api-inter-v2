# PAGAR DARF-INTER

## Pagar DARF
Método para inclusão de um pagamento imediato de DARF sem código de barras.<br>
Este endpoint está implementado com um rate-limit de 120 chamadas por minuto.

```php
    require '../../../vendor/autoload.php';

    use Divulgueregional\ApiInterV2\InterBanking;

    $config = [
        'certificate' => '../cert/Inter_API_Certificado.crt',//local do certificado crt
        'certificateKey' => '../cert/Inter_API_Chave.key',//local do certificado key
    ];

    $filters = [
        'cnpjCpf' =>  $Post->cnpjCpf,// string
        'codigoReceita' =>  $Post->codigoReceita,// string 0220
        'dataVencimento' =>  $Post->dataVencimento,// string 31/01/2020
        'descricao' =>  $Post->descricao,// string Pagamento DARF
        'nomeEmpresa' =>  $Post->nomeEmpresa,// string Minha empresa
        'telefoneEmpresa' =>  $Post->telefoneEmpresa,// string 
        'periodoApuracao' =>  $Post->periodoApuracao,// string 2020-01-31
        'valorPrincipal' =>  $Post->valorPrincipal,// string 47.14
        'valorMulta' =>  $Post->valorMulta,// string 27.48
        'valorJuros' =>  $Post->valorJuros,// string 10.11
        'dataPagamento' =>  $Post->dataPagamento,// string 2020-01-30
        'referencia' =>  $Post->referencia,// string 13609400849201739
    ];
    $token = '';//seu token
    try {
        $bankingInter = new InterBanking($config);
        $bankingInter->setToken($token);

        echo "<pre>";
        $pagarDARF = $bankingInter->pagarDARF($filters);
        print_r($pagarDARF);
    } catch (\Exception $e) {
        echo $e->getMessage();
    }
```