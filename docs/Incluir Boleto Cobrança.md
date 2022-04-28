# BOLETO DETALHADO-INTER

## Consultar boleto
Busca todos os dados do boleto<br>
Necessita informar o nossoNumero.

```php
    require '../../../vendor/autoload.php';

    use Divulgueregional\ApiInterV2\InterBanking;

    $config = [
        'certificate' => '../cert/Inter_API_Certificado.crt',
        'certificateKey' => '../cert/Inter_API_Chave.key',
        'token' => 'b32e22ea-402d-4b71-9dec-43697b2e9a2d',//informe o token
    ];
    $dadosBoleto = [
        "seuNumero" => "85401",
        "valorNominal" => 50,
        "valorAbatimento" => 0,
        "dataEmissao" => "2022-04-28",
        "dataVencimento" => "2022-07-15",
        "numDiasAgenda" => 30,
        "pagador" => [
            "cpfCnpj" => "55309496068",
            "nome" => "CARLOS ROSENO MATOS",
            "email" => "",
            "telefone" => "",
            "cep" => "95097660",
            "numero" => "927",
            "complemento" => "",
            "bairro" => "Rio Branco",
            "cidade" => "Caxias do Sul",
            "uf" => "RS",
            "endereco" => "Rua Comendador Silvio Toigo",
            "ddd" => "",
            "tipoPessoa" => "FISICA"
        ],
        "mensagem" => [
            "linha1" => "Teste de evio 1",
            "linha2" => "Teste de evio 2",
            "linha3" => "",
            "linha4" => "",
            "linha5" => ""
        ],
        "desconto1" => [
            "codigoDesconto" => "NAOTEMDESCONTO",
            "taxa" => 0,
            "valor" => 0,
            "data" => ""
        ],
        "desconto2" => [
            "codigoDesconto" => "NAOTEMDESCONTO",
            "taxa" => 0,
            "valor" => 0,
            "data" => ""
        ],
        "desconto3" => [
            "codigoDesconto" => "NAOTEMDESCONTO",
            "taxa" => 0,
            "valor" => 0,
            "data" => ""
        ],
        "multa" => [
            "codigoMulta" => "NAOTEMMULTA",
            "data" => "",
            "taxa" => 0,
            "valor" => 0
        ],
        "mora" => [
            "codigoMora" => "ISENTO",
            "data" => "",
            "taxa" => 0,
            "valor" => 0
        ]
    ];

    try {
        $bankingInter = new InterBanking();

        echo "<pre>";
        $incluirBoletoCobranca = $bankingInter->incluirBoletoCobranca($config, $dadosBoleto);
        // echo $incluirBoletoCobranca->nossoNumero;
        print_r($incluirBoletoCobranca);
    } catch (\Exception $e) {
        echo $e->getMessage();
    }
```