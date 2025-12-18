# PAGAR DARF-INTER

## Pagar DARF
Método para inclusão de um pagamento imediato de DARF sem código de barras.<br>

## Escopo

Escopo requerido: pagamento-darf.write<br>

## Rate limit

10 chamadas por minuto (produção)
10 chamadas por minuto (sandbox)

## Request (JSON)

- cnpjCpf (obrigatório): string (11..20)
- codigoReceita (obrigatório): string (4)
- dataVencimento (obrigatório): YYYY-MM-DD
- descricao (obrigatório): string (até 1000)
- nomeEmpresa (obrigatório): string (até 100)
- telefoneEmpresa (opcional): string (até 50)
- periodoApuracao (obrigatório): YYYY-MM-DD
- valorPrincipal (obrigatório): number
- valorMulta (opcional): number
- valorJuros (opcional): number
- referencia (obrigatório): string (até 30)

## Responses

- 200 Sucesso
- 400 Requisição com formato inválido.
- 401 Requisição não autorizada.
- 403 Requisição de participante autenticado que viola alguma regra de autorização.
- 404 Recurso solicitado não foi encontrado.
- 500 Serviço não está disponível no momento.

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
        'referencia' =>  $Post->referencia,// string 13609400849201739
    ];

    $token = '';//seu token
    try {
        $bankingInter = new InterBanking($config);
        $bankingInter->setToken($token);

        echo "<pre>";
        $pagarDARF = $bankingInter->pagarDARF($filters);
        print_r($pagarDARF['response']);
    } catch (\Exception $e) {
        echo $e->getMessage();
    }
```