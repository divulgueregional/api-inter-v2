# TOKEN-INTER

## O Token deve ser gerado com certificado

O tokem devem ser gerado com o uso do certificado, que foi obtido na criação da aplicação, caso contrário, o token gerado será inválido e seu sistema não conseguirá consumir os serviços do Inter.

## Certificado
Faça login no site do Banco do Inter.<br>
Clique em conta digital e selecione Gestão de aplicações<br>

- Criar aplicação
- baixe o certificado que vai estar zipado e descompacte para obter o certificado .crt e .key
- Clique na seta  esquerda ao lado do nome da aplicação e pegue ClientId e ClientSecret

## Geração do Token
Obter token oAuth

```php
    require '../../../vendor/autoload.php';

    use Divulgueregional\ApiInterV2\InterBanking;

    $config = [
        'certificate' => '../cert/Inter_API_Certificado.crt',//local do certificado crt
        'certificateKey' => '../cert/Inter_API_Chave.key',//local do certificado key
    ];

    $client_id = '',//seu client_id
    $client_secret = '',//client_secret
    try {
        $bankingInter = new InterBanking($config);
        
        $token = $bankingInter->getToken($client_id, $client_secret);
        print_r($token);
    } catch (\Exception $e) {
        echo $e->getMessage();
    }    
```

## Observação
Armazene e controle o token da forma que achar mais conveniente para você<br>
Pois para poder usar as funcionalidades da API vai precisar desse token<br>
O Token tem uma válidade de 1 hora após ele ser gerado.

## Controle do Token
Segue um exemplo simples que auxilia uma forma de verificar o tempo do token gerado através da session, mas você pode obtar e armazenar no banco e trazer as infomações desses dados no exemplo abaixo.

```php
//obter o token
$controlToken = controlToken();//função para analisar tempo do token

if($controlToken=='gerarToken'){
    $config = [
        'certificate' => '../cert/Inter_API_Certificado.crt',
        'certificateKey' => '../cert/Inter_API_Chave.key',
    ];
    
    $bankingInter = new InterBanking($config);

    $client_id = '';//seu client_id
    $client_secret = '';//client_secret
    $result = $bankingInter->getToken( $client_id, $client_secret);
    if (isset($result['access_token'])) {
        $_SESSION['tokenInter'] = [];
        $_SESSION['tokenInter']['data'] = date('Y-m-d');
        $_SESSION['tokenInter']['hora'] = date('H:i:s');
        $_SESSION['tokenInter']['token'] = $result['access_token'];

        $bankingInter->setToken($result['access_token']);
    }

}else{
    $token = $_SESSION['tokenInter']['token'];
    $bankingInter->setToken($token);
}


//funcão verifica válidade do token
function controlToken()
{
    date_default_timezone_set('America/Sao_Paulo');
    if(isset($_SESSION['tokenInter']['token']))
    {
        if ($_SESSION['tokenInter']['token'] != '') 
        {
            //token gerado, conferir validade
            if ($_SESSION['tokenInter']['data'] == date('Y-m-d')) 
            {
                //data válida, verificar horário
                $hora_decorridas = gmdate('H:i:s', strtotime(date('H:i:s')) - strtotime($_SESSION['tokenInter']['hora']));
                $hora_min = explode(":", $hora_decorridas);
                if ($hora_min[0] == '00') 
                {
                    if ($hora_min[1] < '58') 
                    {
                        return $_SESSION['tokenInter']['token'];
                    } else {
                        //passou de 56 min, gerar novo token
                        return 'gerarToken';
                    }
                } else {
                    //passou de 1 hora, gerar token
                    return 'gerarToken';
                }
            } else {
                //data inválida, gerar token
                return 'gerarToken';
            }
        } else {
            return 'gerarToken';
        }
    } else {
        return 'gerarToken';
    }
}

```