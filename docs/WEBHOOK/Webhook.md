# ARQUIVO QUE RECEBE AS NOTIFICAÇÕES

## Como tratar o recebimento
Arquivo webhook.php

```php
    <?php
    $Post_Recebe = trim(file_get_contents('php://input')); //recebe um array em post

    //dados de retorno
    $retorno = json_decode($Post_Recebe);
    $nossoNumero = $retorno->nossoNumero;
    $situacao = $retorno->situacao;

    //gravando na pasta um arquivo log com o retorno
    $dataHora = date('Y-m-d H:s:i');
    $fp = fopen("logWebHookInter-{$dataHora}.log", "a");
    fwrite($fp, $Post_Recebe);
    fclose($fp);

    
```