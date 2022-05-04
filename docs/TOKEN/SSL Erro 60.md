# Error 60: SSL

## cURL error 60: SSL certificate problem
Esse erro é porque quando for verificado o certificado do Banco Inter, não é possível ler ele. Isso pode ocorrer porque o certificado não é válido ou sua máquina não posssui o SSL instalado para fazer essa leitura.

## SOLUÇÃO 1
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

## SOLUÇÃO 2
Usando o arquivo cacert.pem disponível nessa sessão<br>
Salve o arquivo em sua pasta e direciona o caminho:

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
            'verify' => 'C:\cacert.pem',
            'ssl_key' => $config['certificateKey'],
        ];
    }
```
Download do arquivo cacert.pem: https://curl.se/ca/cacert.pem

## OBSERVAÇÃO
Caso fizer um composer update, deverá entrar e mudar novamente.