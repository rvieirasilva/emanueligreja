<?php
    @$ip		                    = $_SERVER["REMOTE_ADDR"]; //Captura o ip de quem acessou a página.
    @$anoatual                   = date('y');
    @$ano                        = date('Y');
    @$dia                        = date('d');
    @$data                       = date('d/m/Y');
    @$dataeng                    = date('Y-m-d');
    $dataHora                    = date('Y-m-d H:i');
    @$mes                        = date('M');
    @$mesnumero                  = date('m');
    @$hora                       = date('H:i');
    @$horaa                      = date('h');
    @$horab                      = date('i');
    @$rand                       = mt_rand(99,9999);
    $algo = "AES-256-CBC";

    //Defini os formatos permitidos para os formulários que carrega arquivos
    $formatospermitidosaudios = array('',"mp3", "m4a", "mp4");
    $formatospermitidosimagens = array('',"jpg", "png", "jpeg", "svg", "PNG", "JPEG", "JPG", "SVG", "BMP", "ICO");
    $formatospermitidosvideos = array('',"mp4", "avi", "mobi", "MP4", "AVI", "MOBI", "mov", "MOV");
    $formatospermitidosarquivos = array('',"pdf", "PDF", "epub", "ePUB", "doc", "DOC", "docx", "DOCX", "odt", "odp", "ods", "xls", "xlsx", "txt", "pages", "mobi", "rar");
    $formatospermitidos = array('',"jpg", "png", "jpeg", "svg", "PNG", "JPEG", "JPG", "SVG", "BMP", "ICO", "pdf", "PDF", "epub", "ePUB", "doc", "DOC", "docx", "DOCX", "odt", "odp", "ods", "xls", "xlsx", "txt", "pages", "mobi", "rar"); //Aceita imagens e arq.

    //Parametro para todas senhas.
    $psenha         = "minlength='8' maxlength='15'";

    //TOKEN
    $btoken = "SELECT token FROM tokens GROUP BY token ORDER BY rand(), id DESC LIMIT 1";
      $rbtoken = mysqli_query($con, $btoken);
        $dbtoken = mysqli_fetch_array($rbtoken);

    $token = $dbtoken['token'];

    //Se o cliente logar esses dados serão carregados para uso interno.
    if(isset($_SESSION["membro"])):
        $matriculadousuario = $_SESSION['matricula'];
        //DADOS DO >>>>> USUÁRIO <<<<<
        $buscarusuario = "SELECT nome, sobrenome, descricaodomembro, nascimento, email, sexo, igreja, frequentaaemanueldesde, ministerio, funcaoadministrativa, rededepartamento, statusdopagamento, matricula, validaate, statusdomembro, uf, cidade, pai, mae, estadocivil, grauescolar, formadoem, pertenceaqualcelula, matriculadacelula, datadobatismo, motivo, fotodeperfil, telefonemovel, whatsapp, cpf, rg, formadoem, endereco, cep, pisosalarial, midiasocial1, link1, midiasocial2, link2, midiasocial3, link3, midiasocial4, link4, qrcode, membrodocampus, codigodocampus from membros where matricula='$matriculadousuario'";
          $rusuario = mysqli_query($con, $buscarusuario); //resultado da busca
            $dusuario = mysqli_fetch_array($rusuario); //array da busca

        $nomeusuario                    = $dusuario['nome'];
        $sobrenomeusuario               = $dusuario['sobrenome'];
        
        $nomeusuario           = base64_decode($dusuario['nome']);
            $nomeusuario       = base64_decode($nomeusuario);
        
        $sobrenomeusuario           = base64_decode($dusuario['sobrenome']);
            $sobrenomeusuario       = base64_decode($sobrenomeusuario);
            
        $nomedousuario                  = $nomeusuario.' '.$sobrenomeusuario;
        $emailusuario                   = $dusuario['email'];
        $sexo                           = $dusuario['sexo'];
        $nascimento                     = $dusuario['nascimento'];
        $igrejausuario                  = $dusuario['igreja'];
        $cargoministerial               = $dusuario['ministerio'];
        $ministerio                     = $dusuario['ministerio'];
        $formadoem                      = $dusuario['formadoem'];
        $funcaoadministrativa           = $dusuario['funcaoadministrativa'];
        $rededepartamento               = $dusuario['rededepartamento'];
        $statusdopagamentousuario       = $dusuario['statusdopagamento'];
        $statusdomembro                 = $dusuario['statusdomembro'];
        $matricula                      = $dusuario['matricula'];
        $matriculausuario               = $dusuario['matricula'];
        $matriculadousuario             = $dusuario['matricula'];
        $estadodomembro                 = $dusuario['uf'];
        $cidadedomembro                 = $dusuario['cidade'];
        $celuladomembro                 = $dusuario['pertenceaqualcelula'];
        $matriculadacelula              = $dusuario['matriculadacelula'];
        $fotodeperfil                   = $dusuario['fotodeperfil'];
        $qrcode                         = $dusuario['qrcode'];
        $membrodocampus                         = $dusuario['membrodocampus'];
        $codigodocampus                         = $dusuario['codigodocampus'];
        $membrodesde                    = date(('d/m/y'), strtotime($dusuario['frequentaaemanueldesde']));
        $validaate                    = date(('d/m/y'), strtotime($dusuario['validaate']));
        // $validaate                    = $dusuario['validaate'];
        
        
        $telefonemovelusuario           = base64_decode($dusuario['telefonemovel']);
            $telefonemovelusuario       = base64_decode($telefonemovelusuario);
            
        $whatsappdomembro           = base64_decode($dusuario['whatsapp']);
            $whatsappdomembro       = base64_decode($whatsappdomembro);

        $cpfdomembro           = base64_decode($dusuario['cpf']);
            $cpfdomembro       = base64_decode($cpfdomembro);
            
        $rgdomembro            = base64_decode($dusuario['rg']);
            $rgdomembro        = base64_decode($rgdomembro);

        $enderecodomembro            = base64_decode($dusuario['endereco']);
            $enderecodomembro        = base64_decode($enderecodomembro);

        $cepdomembro            = base64_decode($dusuario['cep']);
            $cepdomembro        = base64_decode($cepdomembro);

        $pisosalarialdomembro            = base64_decode($dusuario['pisosalarial']);
            $pisosalarialdomembro        = base64_decode($pisosalarialdomembro);

        $link1domembro            = base64_decode($dusuario['link1']);
            $link1domembro        = base64_decode($link1domembro);

        $link2domembro            = base64_decode($dusuario['link2']);
            $link2domembro        = base64_decode($link2domembro);

        $link3domembro            = base64_decode($dusuario['link3']);
            $link3domembro        = base64_decode($link3domembro);

        $link4domembro            = base64_decode($dusuario['link4']);
            $link4domembro        = base64_decode($link4domembro);

            
        // if(isset($_GET)):
        //     @$page_acessoone = $_SERVER['REQUEST_URI'];
        //     @$page_acessoone = explode('/', $page_acessoone);
        //     @$page_acessoone = $page_acessoone[1];

        //     //Tira os GET's.
        //     @$page_acessoone = explode('?', $page_acessoone);
        //     @$page_acessoone = $page_acessoone[0];
        // else:
        //     @$page_acessoone = $_SERVER['REQUEST_URI'];
        //     @$page_acessoone = explode('/', $page_acessoone);
        //     @$page_acessoone = $page_acessoone[1];
        // endif;
        if(isset($_GET)):
            @$page_acessoone = $_SERVER['REQUEST_URI'];
            @$page_acessoone = explode('/', $page_acessoone);
            @$page_acessoone = $page_acessoone[1];

            //Tira os GET's.
            @$page_acessoone = explode('?', $page_acessoone);
            @$page_acessoone = $page_acessoone[0];
        else:
            @$page_acessoone = $_SERVER['REQUEST_URI'];
            @$page_acessoone = explode('/', $page_acessoone);
            @$page_acessoone = $page_acessoone[1];
        endif;

        if($page_acessoone !== 'configurar'):
            if(empty($cpfdomembro)):
                $_SESSION['cordamensagem']="orange";
                $_SESSION['mensagem']= "Você precisa completar seus dados para ter acesso as demais áreas do site";

                header("location:configurar?token=$token&m=$matricula");
            endif;
        endif;

        //==================================================================================== /////

        //Abaixo mensagem modelo para o log dos clientes

        /*
        //Dados para registrar o log do cliente.
        $mensagem = ("\n$cliente ($matricula) Avaliou o chamado $codigodochamado como: 'SATISFEITO'. Isto através do IP: $ip, .\n"); //Escreva a mensagem que será gravada no log.
            include "cliente_registrodolog.php"; //Arquivo com as configurações de página abertura de arquivo e outros.
        //Fim do registro do log.
        */   
    endif;
   
    if(isset($_SESSION["visitante"])):
        $matriculadousuario = $_SESSION['matricula'];
        //DADOS DO >>>>> USUÁRIO <<<<<
        $buscarusuario = "SELECT nome, sobrenome, nascimento, email, sexo, igreja, frequentaaemanueldesde, ministerio, funcaoadministrativa, rededepartamento, statusdopagamento, matricula, validaate, statusdomembro, uf, cidade, pertenceaqualcelula, matriculadacelula, fotodeperfil, telefonemovel, whatsapp, cpf, rg, formadoem, endereco, cep, pisosalarial, link1, link2, link3, link4, qrcode from visitantes where matricula='$matriculadousuario'";
          $rusuario = mysqli_query($con, $buscarusuario); //resultado da busca
            $dusuario = mysqli_fetch_array($rusuario); //array da busca

        // $nomeusuario                    = $dusuario['nome'];
        // $sobrenomeusuario               = $dusuario['sobrenome'];
        
        $nomeusuario           = base64_decode($dusuario['nome']);
            $nomeusuario       = base64_decode($nomeusuario);
        
        $sobrenomeusuario           = base64_decode($dusuario['sobrenome']);
            $sobrenomeusuario       = base64_decode($sobrenomeusuario);
            
        $nomedousuario                  = $nomeusuario.' '.$sobrenomeusuario;
        $emailusuario                   = $dusuario['email'];
        $sexo                           = $dusuario['sexo'];
        $nascimento                     = $dusuario['nascimento'];
        $igrejausuario                  = $dusuario['igreja'];
        $cargoministerial               = $dusuario['ministerio'];
        $ministerio                     = $dusuario['ministerio'];
        $formadoem                      = $dusuario['formadoem'];
        $funcaoadministrativa           = $dusuario['funcaoadministrativa'];
        $rededepartamento               = $dusuario['rededepartamento'];
        $statusdopagamentousuario       = $dusuario['statusdopagamento'];
        $statusdomembro                 = $dusuario['statusdomembro'];
        $matricula                      = $dusuario['matricula'];
        $matriculausuario               = $dusuario['matricula'];
        $matriculadousuario             = $dusuario['matricula'];
        $estadodomembro                 = $dusuario['uf'];
        $cidadedomembro                 = $dusuario['cidade'];
        $celuladomembro                 = $dusuario['pertenceaqualcelula'];
        $matriculadacelula              = $dusuario['matriculadacelula'];
        $fotodeperfil                   = $dusuario['fotodeperfil'];
        $qrcode                         = $dusuario['qrcode'];
        $membrodesde                    = date(('d/m/y'), strtotime($dusuario['frequentaaemanueldesde']));
        $validaate                      = date(('d/m/y'), strtotime($dusuario['validaate']));
        // $validaate                    = $dusuario['validaate'];
        
        
        $telefonemovelusuario           = base64_decode($dusuario['telefonemovel']);
            $telefonemovelusuario       = base64_decode($telefonemovelusuario);
            
        $whatsappdomembro           = base64_decode($dusuario['whatsapp']);
            $whatsappdomembro       = base64_decode($whatsappdomembro);

        $cpfdomembro           = base64_decode($dusuario['cpf']);
            $cpfdomembro       = base64_decode($cpfdomembro);
            
        $rgdomembro            = base64_decode($dusuario['rg']);
            $rgdomembro        = base64_decode($rgdomembro);

        $enderecodomembro            = base64_decode($dusuario['endereco']);
            $enderecodomembro        = base64_decode($enderecodomembro);

        $cepdomembro            = base64_decode($dusuario['cep']);
            $cepdomembro        = base64_decode($cepdomembro);

        $pisosalarialdomembro            = base64_decode($dusuario['pisosalarial']);
            $pisosalarialdomembro        = base64_decode($pisosalarialdomembro);

        $link1domembro            = base64_decode($dusuario['link1']);
            $link1domembro        = base64_decode($link1domembro);

        $link2domembro            = base64_decode($dusuario['link2']);
            $link2domembro        = base64_decode($link2domembro);

        $link3domembro            = base64_decode($dusuario['link3']);
            $link3domembro        = base64_decode($link3domembro);

        $link4domembro            = base64_decode($dusuario['link4']);
            $link4domembro        = base64_decode($link4domembro);

            
        if(isset($_GET)):
            @$page_acessoone = $_SERVER['REQUEST_URI'];
            @$page_acessoone = explode('/', $page_acessoone);
            @$page_acessoone = $page_acessoone[1];

            //Tira os GET's.
            @$page_acessoone = explode('?', $page_acessoone);
            @$page_acessoone = $page_acessoone[0];
        else:
            @$page_acessoone = $_SERVER['REQUEST_URI'];
            @$page_acessoone = explode('/', $page_acessoone);
            @$page_acessoone = $page_acessoone[1];
        endif;

        if($page_acessoone !== 'configurar'):
            if(empty($cpfdomembro)):
                $_SESSION['cordamensagem']="orange";
                $_SESSION['mensagem']= "Você precisa completar seus dados para ter acesso as demais áreas do site";

                header("location:configurar?token=$token&m=$matricula");
            endif;
        endif;

        //==================================================================================== /////

        //Abaixo mensagem modelo para o log dos clientes

        /*
        //Dados para registrar o log do cliente.
        $mensagem = ("\n$cliente ($matricula) Avaliou o chamado $codigodochamado como: 'SATISFEITO'. Isto através do IP: $ip, .\n"); //Escreva a mensagem que será gravada no log.
            include "cliente_registrodolog.php"; //Arquivo com as configurações de página abertura de arquivo e outros.
        //Fim do registro do log.
        */   
    endif;
   

	$v1 = mt_rand(1,10);
    $v2 = rand(1,10);

    $soma = $v1 + $v2;
        //$_SESSION['soma'] = $_POST['soma1'];
       	$s = $_POST['soma1'];

    	//$somado = $_POST['soma'];
        //$_SESSION['somado'] = $_POST['soma'];
        //$s2 = $_SESSION['somado'];
        $s2 = $_POST['soma'];


    //configura o link de segurança conforme o usário.
    if(isset($_SESSION["membro"]) OR isset($_SESSION["visitante"])):
        $linkSeguro = "m=$matricula&token=$token&";
        
        if(isset($_SESSION["membro"])):
            $pageinicial="emanuel";
        elseif(isset($_SESSION["visitante"])):
            $pageinicial="loja";
        endif;
    // elseif(isset($_SESSION["membro"])):
    //     $linkSeguro = "m=$matriculadousuario&token=$token&";
     else:
        $pageinicial="https://igrejaemanuel.com.br";
        $linkSeguro = '';
    endif;
