# Error 60: SSL

## cURL error 60: SSL certificate problem
Esse erro é porque quando for verificado o certificado do Banco Inter, não é possível ler ele. Isso pode ocorrer porque o certificado não é válido ou sua máquina não posssui o SSL instalado para fazer essa leitura.

## SOLUÇÃO
Arquivo: InterBanking.php<br>
No momento de criar o nosso cliente utilizando Guzzle, tente adicionar a opção verify com o valor false dessa forma:

```php
    function __construct(array $config)
    {
        $this->client = new Client([
            'base_uri' => 'https://cdpj.partners.bancointer.com.br',
        ]);

        $this->optionsRequest = [
            'headers' => [
                'Accept' => 'application/json'
            ],
            'cert' => $config['certificate'],
            // 'verify' => $config['certificate'],
            'verify' => false,
            'ssl_key' => $config['certificateKey'],
        ];
    }
```

## OBSERVAÇÃO
Caso fizer um composer update, deverá entrar e mudar novamente.