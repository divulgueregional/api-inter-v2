# ATUALIZAR COBRANÇA VENCIMENTO-INTER

## Revisar cobrança com vencimento
Endpoint para revisar uma cobrança com vencimento.

## Escopo

Escopo requerido: cobv.write<br>

## Rate limit

120 chamadas por minuto (produção)
10 chamadas por minuto (sandbox)

## Observações

- O token tem validade de 60 minutos e deverá ser reutilizado nas requisições.
- Header x-conta-corrente é necessário somente quando a aplicação estiver associada a mais de uma conta corrente

## Parâmetros (path)

- txid (obrigatório): string [a-zA-Z0-9]{26,35}

## Responses

- 200 Cobrança com vencimento revisada. A revisão deve ser incrementada em 1.
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

    $txid = ''; //gerado ao criar o pix  
    $filters = [
        // "loc" => [
        //     "id" => 7768,
        // ],
        "devedor" => [
            "cpf" => "12345678909",
            "nome" => "Francisco da Silva",
        ],
        "valor" => [
            "original" => "123.45",
        ],
        "solicitacaoPagador" => "Cobrança dos serviços prestados.",
        // "infoAdicionais" => [
        //     [
        //         "nome" => '"Campo 1',//required
        //         "valor" => "Informação Adicional1 do PSP-Recebedor",//required
        //     ],
        // ],
    ];

    $token = '';//seu token
    try {
        $bankingInter = new InterBanking($config);
        $bankingInter->setToken($token);

        echo "<pre>";
        $response = $bankingInter->atualizarCobrancaVencimento($txid, $filters);
        print_r($response);
    } catch (\Exception $e) {
        echo $e->getMessage();
    }
```