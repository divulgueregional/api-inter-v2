# Error 60: SSL

## cURL error 60: SSL certificate problem
Esse erro é porque quando for verificado o certificado do Banco Inter, não é possível ler ele. Isso pode ocorrer porque o certificado não é válido ou sua máquina não posssui o SSL instalado para fazer essa leitura.

## SOLUÇÃO 1
Coloque no config a variavel 'verify' => false

```php
    $config = [
        'certificate' => '../cert/Inter_API_Certificado.crt',//local do certificado crt
        'certificateKey' => '../cert/Inter_API_Chave.key',//local do certificado key
        'verify' => false
    ];
```

## SOLUÇÃO 2
Usando o arquivo cacert.pem disponível nessa sessão<br>
Salve o arquivo em sua pasta e direciona o caminho no config:

```php
    $config = [
        'certificate' => '../cert/Inter_API_Certificado.crt',//local do certificado crt
        'certificateKey' => '../cert/Inter_API_Chave.key',//local do certificado key
        'verify' => 'C:\cacert.pem',
    ];
```
Download do arquivo cacert.pem: https://curl.se/ca/cacert.pem

## OBSERVAÇÃO
Caso informar verify só poderá informar 3 valores.<br>
1- 'verify' => false<br>
2- 'verify' => true<br>
3- 'verify' => caminho do certificado cacert.pem