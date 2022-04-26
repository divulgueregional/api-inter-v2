# TOKEN-INTER

## O Token deve ser gerado com certificado

O tokem devem ser gerado com o uso do certificado, que foi obtido na criação da aplicação, caso contrário, o token gerado será inválido e seu sistema não conseguirá consumir os serviços do Inter.

## Certificado
Faça login no site do Banco do Inter.<br>
Clique em conta digital e selecione Gestão de aplicações<br>

- Criar aplicação
- baixe o certificado em seu computador (são 2)
- Clique na seta  esquerda ao lado do nome da aplicação e pegue ClientId e ClientSecret.

## Geração do Token
Essa API gera o token e gerencia o tempo de uso do token, apenas precisa informar o local que estão os certificados e informar client_id e client_secret

```php
    $dd = new stdClass;
    $dd->certificate = '../cert/Inter_API_Certificado.crt';//local do certificado crt
    $dd->certificateKey = '../cert/Inter_API_Chave.key';//local do certificado key
    $dd->client_id = '';//seu client_id
    $dd->client_secret = '';//client_secret

    
    $bankingInter = new InterBanking($dd);

    $token = $bankingInter->getToken();
    print_r($token);
    
```

## Observação
Armazene e gerencie o token da forma que achar mais conveniente para você<br>
Pois para poder usar os recursos da API vai precisar desse token<br>
O Token tem uma válidade de 1 hora após ele ser gerado.
