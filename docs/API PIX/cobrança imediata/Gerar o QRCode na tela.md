# GERAR QRCODE NA TELA-INTER

## Gerar o QRCode na tela
Você pode usar esse projeto para gerar o qrcode: https://github.com/mpdf/qrcode<br>
Atualmente estou usando chart.googleapis.com<br>
basta colocar o pixCopiaECola do json do retorno do pix criado no src do img.<br>
https://chart.googleapis.com/chart?cht=qr&chs=300x300&chl='+pixCopiaECola
<img id="img_pix_qrcode" src=""/><br>

```js
    var imgPixQRCode = document.getElementById('img_pix_qrcode');

    var copia_e_cola = document.getElementById('chave_copia_e_cola');
    imgPixQRCode.src = 'https://chart.googleapis.com/chart?cht=qr&chs=300x300&chl='+copia_e_cola;
    imgPixQRCode.style.display = 'block';
```