# CRIAR COBRANÇA VENCIMENTO-INTER

## Criar cobrança com vencimento
Endpoint para criar uma cobrança com vencimento.

Na cobrança com vencimento é possível parametrizar uma data de vencimento e com isso o pagamento pode ser realizado em data futura, pode também incluir outras informações como juros, multas, outros acréscimos, descontos e outros abatimentos, semelhante ao boleto.

## Escopo

Escopo requerido: cobv.write<br>

## Rate limit

120 chamadas por minuto (produção)
10 chamadas por minuto (sandbox)

## Observações

- Você gera o txid.
- O token tem validade de 60 minutos e deverá ser reutilizado nas requisições.
- Header x-conta-corrente é necessário somente quando a aplicação estiver associada a mais de uma conta corrente

## Parâmetros (path)

- txid (obrigatório): string [a-zA-Z0-9]{26,35}

## Responses

- 201 Cobrança com vencimento criada
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

    $txid = '';//gerado por você, min 26 max 35 caracteres
    $filters = [
        "calendario" => [
            "dataDeVencimento" => "2020-12-31",
            "validadeAposVencimento" => 30,
        ],
        // "loc" => [
        //     "id" => 789,
        // ],
        "devedor" => [
            "logradouro" => "Alameda Souza, Numero 80, Bairro Braz",
            "cidade" => "Recife",
            "uf" => "PE",
            "cep" => "70011750",
            "cpf" => "12345678909",
            "nome" => "Francisco da Silva",
            //ou
            // "cnpj" => "12345678000195",
            // "nome" => "Empresa de Serviços SA",
        ],
        "valor" => [
            "original" => "123.45",
            // "multa" => [
            //     "modalidade" => "2",
            //     "valorPerc" => "15.00",
            // ],
            // "juros" => [
            //     "modalidade" => "2",
            //     "valorPerc" => "2.00",
            // ],
            // "desconto" => [
            //     "modalidade" => "1",
            //     "descontoDataFixa" => [
            //         [
            //             "data" => "2020-11-30",
            //             "valorPerc" => "30.00",
            //         ],
            //     ],
            // ],
        ],
        "chave" => "",
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
        $response = $bankingInter->criarCobrancaVencimento($txid, $filters);
        print_r($response);
    } catch (\Exception $e) {
        echo $e->getMessage();
    }
```