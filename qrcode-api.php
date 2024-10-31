<?php

// Definir o cabeçalho da página como UTF-8
header('Content-Type: text/html; charset=UTF-8');

// Caminho da imagem do QR Code
$caminhoImagemQRCode = 'img/qrcodegab.png';

// Verificar se o arquivo de imagem existe
if (!file_exists($caminhoImagemQRCode)) {
    die("Erro: O arquivo de imagem não foi encontrado no caminho especificado.");
}

// Função para enviar a imagem para o serviço ZXing e obter o conteúdo do QR Code
function lerQRCodeComZXing($caminhoDaImagem) {
    $url = 'https://api.qrserver.com/v1/read-qr-code/';

    // Enviar a imagem para a API usando cURL
    $cfile = new CURLFile($caminhoDaImagem, mime_content_type($caminhoDaImagem), basename($caminhoDaImagem));
    $data = ['file' => $cfile];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

    $response = curl_exec($ch);
    curl_close($ch);

    if ($response === false) {
        return "Erro ao enviar a imagem para o serviço ZXing.";
    }

    // Decodificar a resposta JSON
    $responseData = json_decode($response, true);

    // Verificar o conteúdo do QR Code e forçar a codificação UTF-8
    if (isset($responseData[0]['symbol'][0]['data'])) {
        // Aplicar utf8_encode() para forçar UTF-8
        $conteudo = $responseData[0]['symbol'][0]['data'];
        
        // Tentar forçar UTF-8 e substituir caracteres inválidos
        return iconv(mb_detect_encoding($conteudo, mb_detect_order(), true), "UTF-8//IGNORE", $conteudo);
    } else {
        return "Nenhum QR Code encontrado ou imagem inválida.";
    }
}

// Ler o QR Code usando o serviço ZXing
$resultado = lerQRCodeComZXing($caminhoImagemQRCode);

// Exibir o resultado na tela
echo "<h2>Conteúdo do QR Code:</h2>";
echo "<pre>$resultado</pre>";
?>
