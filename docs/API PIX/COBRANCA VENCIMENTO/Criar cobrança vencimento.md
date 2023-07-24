# CRIAR COBRANÇA VENCIMENTO-INTER

## Criar cobrança vencimento
Endpoint para criar uma cobrança com vencimento.<br>
Escopo requerido: cobv.write<br>
Você gera o txid.<br>
Rate limit: 120 chamadas por minuto<br>

```php
    require '../../../vendor/autoload.php';

    use Divulgueregional\ApiInterV2\InterBanking;

    $config = [
        'certificate' => '../cert/Inter_API_Certificado.crt',//local do certificado crt
        'certificateKey' => '../cert/Inter_API_Chave.key',//local do certificado key
    ];

    $txid = '';//gerado por você, min 24 max 35 caracteres
    //JURIDICA
    $filters = [
        "calendario" => [
            "dataDeVencimento" => "2023-07-25",//Tempo de vida da cobrança, especificado em segundos a partir da data de criação (Calendario.criacao)
            "validadeAposVencimento" => 30
        ],
        // "loc" => [
        //     "id" => 789,
        // ],
        "devedor" => [
            "logradouro" => 'Rua A, 666. Rio Branco',
            "cidade" => "Caxias do Sul",
            "uf" => 'RS',
            "cep" => '95097660',
            "cnpj" => '99999999999999',
            "nome" => "MINHA EMPRESA",
        ],
        "valor" => [
            "original" => '10.00',//string required- valores monetários referentes à cobrança.
            // "multa" => [
            //     "modalidade" => "2",// 1-Valor Fixo; 2-Percentual
            //     "valorPerc" => "5.00",
            // ],
            // "multa" => [
            //     "modalidade" => "2",//1-Valor (dias corridos); 2-Percentual ao dia (dias corridos);3-Percentual ao mês (dias corridos);4-Percentual ao ano (dias corridos);5-Valor (dias úteis);6-Percentual ao dia (dias úteis);7-Percentual ao mês (dias úteis);8-Percentual ao ano (dias úteis)
            //     "valorPerc" => "1.00",
            // ],
            // "desconto" => [
            //     "modalidade" => "1",// 1-Valor Fixo; 2-Percentual
            //     "descontoDataFixa" => [
            //         "data" => "2020-11-30",
            //         "valorPerc" => "2.00",
            //     ],
            // ],
        ],
        "chave" => "",//string chave Pix do recebedor. Chave podem ser: telefone, e-mail, cpf/cnpj ou EVP.
        "solicitacaoPagador" => "Sobre meus serviços",//O campo solicitacaoPagador determina um texto a ser apresentado ao pagador para que ele possa digitar uma informação correlata, em formato livre, a ser enviada ao recebedor. Esse texto está limitado a 140 caracteres.
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
        $criarCobrancaVencimento = $bankingInter->criarCobrancaVencimento($txid, $filters);
        print_r($criarCobrancaVencimento);
    } catch (\Exception $e) {
        echo $e->getMessage();
    }
```