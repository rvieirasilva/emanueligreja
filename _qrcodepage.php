<?php
    //Namespace usando para nomear as classe e evitar conflito de classe iguais
    namespace chillerlan\QRCodeExamples;

    //Estamos usando a classe QRCode do namespace QRCodeExamples
    use chillerlan\QRCode\{QRCode, QROptions};

    //Incluir Composer
    include './vendor/autoload.php';

    //URL que será utilizada para gerar o QR
    $ref_post = $_GET['pos'];
    $pagina = "post?pos=$ref_post";


    //URL que será utilizada para gerar o QR
    $url = "https://igrejaemanuel.com.br/$pagina";

    /*
    //Gera imagem em svg
    //Configurações do QRCode
    $options = new QROptions([
        'version'    => 5,
        'outputType' => QRCode::OUTPUT_MARKUP_SVG,
        'eccLevel'   => QRCode::ECC_L,
        'scale'        => 5,
    ]);
    */

    //Gera a imagem em png
    
    $options = new QROptions([
        'version'      => 5,
        'outputType'   => QRCode::OUTPUT_IMAGE_PNG,
        'eccLevel'     => QRCode::ECC_L,
        'scale'        => 5,
        'imageBase64'  => false,
        'moduleValues' => [
            // finder
            1536 => '#000000', // dark (true)
            6    => '#FFBFBF', // light (false), white is the transparency color and is enabled by default
            // alignment
            2560 => '#000000',
            10   => '#FFBFBF',
            // timing
            3072 => '#000000',
            12   => '#FFBFBF',
            // format
            3584 => '#000000',
            14   => '#FFBFBF',
            // version
            4096 => '#000000',
            16   => '#FFBFBF',
            // data
            1024 => '#000000',
            4    => '#FFBFBF',
            // darkmodule
            512  => '#000000',
            // separator
            8    => '#FFBFBF',
            // quietzone
            18   => '#FFBFBF',
        ],
    ]);
    
    $atualizaImgQr = mt_rand(0001, 9999);

    //invoca uma nova instância QRCode
    $qrcode = new QRCode($options);

    //Gerar a imagem do QR
    $qrcode->render($url);

    //Gerar a imagem e salvar a imagem do QR no servidor
    @mkdir("arqblog/", 0777); //Cria a pasta se não houver. o @ é pra ocultar erro caso
    @mkdir("arqblog/qrcodepost/", 0777); //Cria a pasta se não houver. o @ é pra ocultar erro caso
    @mkdir("arqblog/qrcodepost/$ref/", 0777); //Cria a pasta se não houver. o @ é pra ocultar erro caso

    //Gerar a imagem e salvar a imagem do QR no servidor
    $qrcode->render($url, "arqblog/qrcodepost/$ref/$ref.png");

    $PastaDoQrCode = "arqblog/qrcodepost/$ref/$ref.png";
    
    //echo '<img src="'.(new QRCode)->render($url).'" alt= "" />';
