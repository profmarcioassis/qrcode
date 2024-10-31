<?php

require 'vendor/autoload.php';

use Zxing\QrReader;

// Caminho para a imagem com o QR Code
$caminhoImagem = 'img/qrcodegab.png';

// Instancia o leitor de QR Code
$qrcode = new QrReader($caminhoImagem);

// Obtém o texto do QR Code
$textoQRCode = $qrcode->text();

// Exibe o conteúdo do QR Code na tela
if ($textoQRCode) {
    echo "Conteúdo do QR Code: " . htmlspecialchars($textoQRCode);
} else {
    echo "QR Code não encontrado ou imagem inválida.";
}

?>
