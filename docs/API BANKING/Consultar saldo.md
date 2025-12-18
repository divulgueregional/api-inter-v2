# CONSULTAR SALDO-INTER

## Consultar Saldo
Para trazer o saldo da conta

## Escopo

Escopo requerido: extrato.read<br>
Rate limit: 10 chamadas por minuto

## Observações

- Em sandbox, os dados retornados são fictícios (apenas para validação da requisição)
- Caso a dataSaldo não seja informada, será utilizada a data atual e os valores retornados serão: saldo atual (disponível), bloqueado em cheque, bloqueado judicialmente, bloqueado administrativo e limite
- Caso seja informada uma data, o retorno será somente o valor do saldo naquele dia (disponível)
- Header x-conta-corrente é necessário somente quando a aplicação estiver associada a mais de uma conta corrente

## Responses

- 200 Sucesso
- 400 Requisição com formato inválido.
- 403 Requisição de participante autenticado que viola alguma regra de autorização.
- 404 Recurso solicitado não foi encontrado.
- 503 Serviço não está disponível no momento.

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
        'dataSaldo' => '',//YYYY-MM-DD caso não informar traz o saldo do dia
    ];

    $token = '';//seu token
    try {
        $bankingInter = new InterBanking($config);
        $bankingInter->setToken($token);

        echo "<pre>";
        $saldo = $bankingInter->checkSaldo($filters);
        print_r($saldo['response']);
    } catch (\Exception $e) {
        echo $e->getMessage();
    }
```