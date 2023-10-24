<!DOCTYPE html>
<?php
	session_start();
	if(!empty($_GET['lng'])):
		$idioma = $_GET['lng'];
	elseif(empty($_GET['lng']) OR !isset($_GET['lng'])):
		$idioma = 'pt-BR';
    endif;
    
    include "./_con.php";
    include "./_configuracao.php";

	if(isset($_SESSION["lideranca"])):
        include "protectcolaborador.php";	
    elseif(isset($_SESSION["membro"])):
        include "protectusuario.php";
    else:
        session_destroy();
        header("location:index");
	endif;

    $data = date('d/m/Y');
    
	$v1 = mt_rand(1,10);
    $v2 = rand(1,10);

    $soma = $v1 + $v2;
        //$_SESSION['soma'] = $_POST['soma1'];
       	$s = $_POST['soma1'];

    	//$somado = $_POST['soma'];
        //$_SESSION['somado'] = $_POST['soma'];
        //$s2 = $_SESSION['somado'];
        $s2 = $_POST['soma'];


	if(isset($_POST['btn-cadastrar'])):
        if($s2 === $s):

            $descricaodomembro              = mysqli_escape_string($con, $_POST['descricaodomembro']);
            $nome                           = mysqli_escape_string($con, $_POST['nome']);
            $sobrenome                      = mysqli_escape_string($con, $_POST['sobrenome']);
            $cpf                            = mysqli_escape_string($con, $_POST['cpf']);
            $rg                             = mysqli_escape_string($con, $_POST['rg']);
            $email                          = mysqli_escape_string($con, $_POST['email']);
            $sexo                           = mysqli_escape_string($con, $_POST['sexo']);
            $telefonemovel                  = mysqli_escape_string($con, $_POST['telefonemovel']);
            $whatsapp                       = mysqli_escape_string($con, $_POST['whatsapp']);
            $motivo                         = mysqli_escape_string($con, $_POST['motivo']);
            $endereco                       = mysqli_escape_string($con, $_POST['endereco']);
            $cep                            = mysqli_escape_string($con, $_POST['cep']);
            $estado                         = mysqli_escape_string($con, $_POST['estado']);
            $cidade                         = mysqli_escape_string($con, $_POST['cidade']);
            $nascimento                     = mysqli_escape_string($con, $_POST['nascimento']);
            $pai                            = mysqli_escape_string($con, $_POST['pai']);
            $mae                            = mysqli_escape_string($con, $_POST['mae']);
            $estadocivil                    = mysqli_escape_string($con, $_POST['estadocivil']);
            $grauescolar                    = mysqli_escape_string($con, $_POST['grauescolar']);
            $formadoem                      = mysqli_escape_string($con, $_POST['formadoem']);
            $profissao                      = mysqli_escape_string($con, $_POST['profissao']);
            $pisosalarial                   = mysqli_escape_string($con, $_POST['pisosalarial']);
            $frequentaemanueldesde          = mysqli_escape_string($con, $_POST['frequentaemanueldesde']);
            $ministerioN                    = mysqli_escape_string($con, $_POST['ministerio']);
            $desejaservirnaigreja           = mysqli_escape_string($con, $_POST['desejaservirnaigreja']);
            $serviremqualarea               = mysqli_escape_string($con, $_POST['serviremqualarea']);
            $pertenceaqualcelulaArray       = mysqli_escape_string($con, $_POST['pertenceaqualcelula']);
              $PertenceCelula = explode('~', $pertenceaqualcelulaArray);
              $pertenceaqualcelula = $PertenceCelula[0];
              $matriculadacelulapertencente = $PertenceCelula[1];
            $numerodefamiliaresnaemanuel    = mysqli_escape_string($con, $_POST['numerodefamiliaresnaemanuel']);
            $datadobatismo                  = mysqli_escape_string($con, $_POST['datadobatismo']);
            $igrejadebatismo                = mysqli_escape_string($con, $_POST['igrejadebatismo']);
            $batizadonoespiritosanto        = mysqli_escape_string($con, $_POST['batizadonoespiritosanto']);
            $midiasocial1                   = mysqli_escape_string($con, $_POST['midiasocial1']);
            $link1                          = mysqli_escape_string($con, $_POST['link1']);
            $midiasocial2                   = mysqli_escape_string($con, $_POST['midiasocial2']);
            $link2                          = mysqli_escape_string($con, $_POST['link2']);
            $midiasocial3                   = mysqli_escape_string($con, $_POST['midiasocial3']);
            $link3                          = mysqli_escape_string($con, $_POST['link3']);
            $midiasocial4                   = mysqli_escape_string($con, $_POST['midiasocial4']);
            $link4                          = mysqli_escape_string($con, $_POST['link4']);
            $declaracao                     = mysqli_escape_string($con, $_POST['declaracao']);

            $upMinisterio             = $ministerio.','.$ministerioN;

            include "./_cpfvalida.php";

            if($cpfinvalido):
                $_SESSION['cordamensagem']="red";    
                $_SESSION['mensagem']="<strong>CPF inválido</strong>, por favor insira um CPF válido.";
            else:   
                //Registra o membro na célula.
                $bmembronacelula="SELECT id FROM celulasmembros WHERE solicitante = '$nomecompleto'";
                  $rmcd=mysqli_query($con, $bmembronacelula);
                    $nmcd=mysqli_num_rows($rmcd);
                
                if($nmcd < 1): //Impede duplicados
                    $nomecompleto = $nome.' '.$sobrenome;
                        $nomecompleto = base64_encode($nomecompleto);
                        $nomecompleto = base64_encode($nomecompleto);  
                    $bDadosDaCelula="SELECT rededacelula, estado, municipio, cep, lider, matriculadolider FROM celulas WHERE matricula='$matriculadacelulapertencente' AND celula='$pertenceaqualcelula'";
                    $rDadosDaCelula=mysqli_query($con, $bDadosDaCelula);
                        $dDadosDaCelula=mysqli_fetch_array($rDadosDaCelula);
                    
                    $ref= mt_rand(200, 999);
                    $rededacelula=$dDadosDaCelula['rededacelula'];
                    $estado=$dDadosDaCelula['estado'];
                    $municipio=$dDadosDaCelula['municipio'];
                    $cep=$dDadosDaCelula['cep'];
                    $lider=$dDadosDaCelula['lider'];
                    $matriculadolider=$dDadosDaCelula['matriculadolider'];

                    $inCelula = "INSERT INTO celulasmembros (dia, mes, ano, referencia, solicitante, matriculadomembro, celulaatual, codigodacelula, matriculadolider, lideratual, novacelula, matriculanovacelula, lidernovo, datadasolicitacao, liberacaodolideratual, aprovacaodonovolider, comentarioliderantigo, justificativaparamudanca, redeatual, redenova) VALUES ('$dia', '$mes', '$anoatual', '$ref', '$nomecompleto', '$matricula', '$pertenceaqualcelula', '$matriculadacelulapertencente', '$matriculadolider', '$lider', '', '', '', '', '', '', '', '', '$rededacelula', '')";
                    mysqli_query($con, $inCelula);
                endif;

                //Criptografia de dados sensíveis
                $nome = base64_encode($nome);
                $nome = base64_encode($nome);
                
                $sobrenome = base64_encode($sobrenome);
                $sobrenome = base64_encode($sobrenome);

                $cpf = base64_encode($cpf);
                $cpf = base64_encode($cpf);
                
                $rg = base64_encode($rg);
                $rg = base64_encode($rg);
                
                $endereco = base64_encode($endereco);
                $endereco = base64_encode($endereco);

                $cep = base64_encode($cep);
                $cep = base64_encode($cep);
                
                $telefonemovel = base64_encode($telefonemovel);
                $telefonemovel = base64_encode($telefonemovel);

                $whatsapp = base64_encode($whatsapp);
                $whatsapp = base64_encode($whatsapp);

                $pisosalarial = base64_encode($pisosalarial);
                $pisosalarial = base64_encode($pisosalarial);

                $link1 = base64_encode($link1);
                $link1 = base64_encode($link1);
                
                $link2 = base64_encode($link2);
                $link2 = base64_encode($link2);

                $link3 = base64_encode($link3);
                $link3 = base64_encode($link3);

                $link4 = base64_encode($link4);
                $link4 = base64_encode($link4);
                //Criptografia de dados sensíveis

                $igreja                 = "Emanuel";

                // Imagem de perfil            
                if(!empty($_FILES['fotodoperfil']['name'])):
                    //Buscar foto do perfil antiga
                    $bFPA="SELECT fotodeperfil FROM membros WHERE matricula='$matricula'";
                    $rFPA=mysqli_query($con, $bFPA);
                        $dFPA=mysqli_fetch_array($rFPA);
                    
                    $fotodoperfilAntiga = $dFPA['fotodeperfil'];


                    $extensaofotodeperfil = pathinfo($_FILES['fotodoperfil']['name'], PATHINFO_EXTENSION);
                    if(in_array($extensaofotodeperfil, $formatospermitidosimagens)):
                        @mkdir("arq/", 0777); //Cria a pasta se não houver. 
                        @mkdir("arq/membros/", 0777); //Cria a pasta se não houver. 
                        @mkdir("arq/membros/$matricula/", 0777); //Cria a pasta se não houver. 
                        
                        $pastafotodeperfil			= "arq/membros/$matricula/";
                        $img_fotodeperfil 			= $_FILES["fotodeperfil"]['name'];
                        $temporariofotodeperfil	    = $_FILES['fotodoperfil']['tmp_name'];
                        $tamanhodafotodeperfil	    = $_FILES['fotodoperfil']['size'];
                        $novoNomefotodeperfil		= mt_rand(10,9999).'.'.$extensaofotodeperfil;
                            $fotodeperfil		= $pastafotodeperfil.$novoNomefotodeperfil;
                        
                        //Compacta a imagem da fotodeperfil								
                        $quality_fotodeperfil = 60;	
                        
                            
                        // function compress_image($img_fotodeperfil, $fotodeperfil, $quality_fotodeperfil) {
                        // 	$info = getimagesize($img_fotodeperfil);
                        // 	if ($info["mime"] == "image/jpeg") $image = imagecreatefromjpeg($img_fotodeperfil);
                        // 	elseif ($info["mime"] == "image/gif") $image = imagecreatefromgif($img_fotodeperfil);
                        // 	elseif ($info["mime"] == "image/png") $image = imagecreatefrompng($img_fotodeperfil);
                        // 	imagejpeg($image, $fotodeperfil, $quality_fotodeperfil);
                        // 	return $fotodeperfil;
                        // }
                            
                        // $temporariofotodeperfil = compress_image($_FILES["fotodeperfil"]["tmp_name"], $fotodeperfil, $quality_fotodeperfil); //Compacta a imagem 
                    

                        move_uploaded_file($temporariofotodeperfil, $fotodeperfil);
                    endif;
                endif;
                // Imagem de perfil

                // Qr Code            
                $bQrCode = "SELECT qrcode FROM membros WHERE matricula='$matricula'";
                $rQrCode = mysqli_query($con, $bQrCode);
                    //$nQrCode = mysqli_num_rows($rQrCode);
                    $dQrCode = mysqli_fetch_array($rQrCode);

                    $QrExi = $dQrCode['qrcode'];
                
                if(empty($QrExi)):
                    include "./_qrcodePartner.php";
                    //Registrar QrCode
                    $UpQrCode = "UPDATE membros SET qrcode='$PastaDoQrCode' wHERE matricula='$matricula'";
                    mysqli_query($con, $UpQrCode);
                endif;        
                // Qr Code

                //Atualizar dados do membro
                $upMembro = "UPDATE membros SET fotodeperfil='$fotodeperfil', nome='$nome', sobrenome='$sobrenome', descricaodomembro='$descricaodomembro', nascimento='$nascimento', sexo='$sexo', email='$email', cpf='$cpf', rg='$rg', telefonemovel='$telefonemovel', whatsapp='$whatsapp', endereco='$endereco', cep='$cep', uf='$estado', cidade='$cidade', pai='$pai', mae='$mae', estadocivil='$estadocivil', grauescolar='$grauescolar', formadoem='$formadoem', profissao='$profissao', pisosalarial='$pisosalarial', frequentaaemanueldesde='$frequentaemanueldesde', ministerio='$ministerioN', desejaservirnaigreja='$desejaservirnaigreja', serviremqualarea='$serviremqualarea', pertenceaqualcelula='$pertenceaqualcelula', matriculadacelula='$matriculadacelulapertencente', numerodefamiliaresnaemanuel='$numerodefamiliaresnaemanuel', datadobatismo='$datadobatismo', igrejadebatismo='$igrejadebatismo', batizadonoespiritosanto='$batizadonoespiritosanto', matricula='$matricula', midiasocial1='$midiasocial1', link1='$link1', midiasocial2='$midiasocial2', link2='$link2', midiasocial3='$midiasocial3', link3='$link3', midiasocial4='$midiasocial4', link4='$link4', qrcode='$PastaDoQrCode' WHERE matricula='$matricula' AND sobrenome='$sobrenome'";

                if(mysqli_query($con, $upMembro)):
                    if(!empty($fotodoperfilAntiga)):
                        @unlink($fotodoperfilAntiga); //Apaga a foto antiga.
                    endif;
                    $_SESSION['cordamensagem']="green";
                    $_SESSION['mensagem']="Seus dados foram atualizados com sucesso.";
                else:
                    $_SESSION['cordamensagem']="red";
                    $_SESSION['mensagem']="Erro ao atualizar dados, tente novamente mais tarde ou vá até nossa secretaria.";
                endif;
            endif;
        else:
            //captcha invalido
            $_SESSION['bot'] = true;
            $_SESSION['cordamensagem']='red';
            $_SESSION['mensagem']='Erro no reCAPTCHA. Informe o valor correto da soma.';
        endif;
    endif;    
    
    // Confirma as senhas preenchidas, criptografa e faz a atualização.
    if (isset($_POST['btn-recuperarsenha'])):
        $novasenha      =     mysqli_escape_string($con, $_POST['novasenha1']);
        $novasenhaconf  =     mysqli_escape_string($con, $_POST['novasenha2']);
        //$id       =     mysqli_escape_string($con, $_POST['id']);
        $validaate					= date(('Y-m-d'), strtotime(date('Y-m-d'). ' + 6 months'));
            // mysqli_escape_string($con,
        
        if ($novasenha===$novasenhaconf)
        {
            //AQUI CRIA-SE UMA SENHA SEGURA PADRÃO, PARA O RESPONSÁVEL ALTERAR POSTERIORMENTE.
                $novasenhasegura    = password_hash($novasenha, PASSWORD_BCRYPT);
                    $anoatual       = date('Y');
                //PARA FAZER A INSERÇÃO É PRECISO O MYSQLI_QUERY // COMO O SQL JÁ POSSUÍA ELE USAVA O ANTECESSOR.
                $upnovasenha = "UPDATE membros SET senha='$novasenhasegura' WHERE matricula='$matricula'";
            if(mysqli_query($con, $upnovasenha)):
                //SE OS DADOS FOREM INSERIDOS ELE GRAVA O LOG.
                //conexão
                
                /*  INICIO DA AÇÃO PARA LOG */
                //AQUI SERÃO CAPTADOS OS DADOS PARA MENSAGEM
                //DADOS
                $sqllog = "SELECT email, nome, sobrenome, matricula FROM membros WHERE matricula='$matricula'";
                $resultadolog= mysqli_query($con, $sqllog);
                $dadoslog = mysqli_fetch_array($resultadolog);

                //Email confirmando a troca da senha.
                $email  =   $dadoslog['email']; // Caso não tenha sido resgatado essa informação anteriormente.
                $colaborador  =   $dadoslog['nome']; // Caso não tenha sido resgatado essa informação anteriormente.
                include "_enviaremail.php";
                $mail->addAddress("$email", "$colaborador");     // Add a recipient 
                    //$mail->addAddress("ellen@example.com");               // Name is optional
                    //$mail->addReplyTo("info@example.com", "Information");
                    //$mail->addCC("cc@example.com");
                    //$mail->addBCC("bcc@example.com");

                    //$mail->addAttachment("/var/tmp/file.tar.gz");         // Add attachments
                    //$mail->addAttachment("/tmp/image.jpg", "new.jpg");    // Optional name
                $mail->isHTML(true);                                  // Set email format to HTML

                $mail->Subject = "Recuperação de senha.";
                $mail->Body    = "Olá $colaborador.
                                <p>Parabéns sua senha foi alterada com sucesso.</p>
                                <p>Ela será valida até:".$validaate."</p>
                                <p><strong>Altere sua senha antes desse prazo</p>
                                <h1>Cristo é o Senhor</h1>";
                $mail->AltBody = "Pr. Rafael Vieira"; // Não é visualizado pelo usuário!
                $mail->send();
                /* */
            
                $colaborador    			=   $dadoslog['nome'];
                $data						=	date('d/m/Y');
                $hora						=	date('G:i:s');
                $mesdolog					= 	date('m');
                $matriculalog				=	$dadoslog['matricula'];
                    $ip						=	$_SERVER["REMOTE_ADDR"];

                //Dados para registrar o log do cliente.
                    $mensagem = ("\n$colaborador Alterou sua senha. Isto através do IP: $ip, na data $dia às $hora.\n"); //Escreva a mensagem que será gravada no log.
                    include "./registrolog.php";
                //Fim do registro do log.  
               $_SESSION['cordamensagem'] = "green"; 
               $_SESSION['mensagem'] = "Senha alterada com sucesso."; 
            endif;
        }
            else
            {
               $_SESSION['cordamensagem'] = "red"; 
                $_SESSION['mensagem'] = "<strong>Erro!</strong> <br>As senhas informadas não são identicas.";
            }
    endif;

    if(isset($_POST['btn-migrarcelula'])):
        $celulaselecionada=mysqli_escape_string($con, $_POST['celula']);
        $justificativa=mysqli_escape_string($con, $_POST['justificativaparamudanca']);

        //Buscar dados da célula.
        $bDadosDaCélula = "SELECT lider, matriculadolider, rededacelula FROM celulas WHERE celula='$celuladomembro'";
            $rDC=mysqli_query($con, $bDadosDaCélula);
            $dDC=mysqli_fetch_array($rDC);
                $lideratualC=$dDC['lider']; $matriculadoliderA=$dDC['matriculadolider']; $rededestacelula=$dDC['rededacelula'];
        
        $bDadosNova = "SELECT celula, lider, matriculadolider, rededacelula FROM celulas WHERE matricula='$celulaselecionada'";
            $rDCN=mysqli_query($con, $bDadosNova);
            $dDCN=mysqli_fetch_array($rDCN);
                $celulanova=$dDCN['celula']; $liderNovo=$dDCN['lider']; $MatrLN=$dDCN['matriculadolider']; $RedeNova=$dDCN['rededacelula'];

        $bSPendente = "SELECT id FROM celulasmembros WHERE solicitante='$nomeusuario' AND redeatual !='' AND redenova !='' AND aprovacaodonovolider =''";
          $rSP=mysqli_query($con, $bSPendente);
            $nSP=mysqli_num_rows($rSP);
        if($nSP < 1):
            $referenciaDoPedido=mt_rand(0000, 9999);
            $inSP="INSERT INTO celulasmembros (dia, mes, ano, referencia, solicitante, matriculadomembro, celulaatual, codigodacelula, matriculadolider, lideratual, novacelula, matriculanovacelula, lidernovo, datadasolicitacao, liberacaodolideratual, aprovacaodonovolider, comentarioliderantigo, justificativaparamudanca, redeatual, redenova) VALUES ('$dia', '$mes', '$anoatual', '$referenciaDoPedido', '$nomeusuario', '$matricula', '$celuladomembro', '$matriculadacelula', '$matriculadoliderA', '$lideratualC', '$celulanova', '$celulaselecionada', '$liderNovo', '$data', '', '', '', '$justificativa', '$rededestacelula', '$RedeNova')";
            if(mysqli_query($con, $inSP)):
                $_SESSION['cordamensagem']='green';
                $_SESSION['mensagem']='Solicitação realizada com sucesso.';
            else:
                $_SESSION['cordamensagem']='red';
                $_SESSION['mensagem']='erro ao inserir solicitação, fale com o TI';
            endif;
        else:
            $_SESSION['cordamensagem']='red';
            $_SESSION['mensagem']='Você possuí uma solicitação pendente, o líder da célula escolhida precisa te aprovar seu pedido de migração';
        endif;
    endif;
?>
<html lang="pt-BR">
<head>
	<? include "./_head.php"; ?>
</head>
<body class="shop blog home-page">

    <?php
        // if(isset($_SESSION["membro"])):
            include "./_menu.php";
        // endif;
	?>
			<!-- Content -->
			<div class="content clearfix">
                <div class="section-block tm-slider-parallax-container pb-30 small bkg-blue">
					<div class="row">
                        <div class="box rounded small bkg-white shadow">
						    <div class="column width-12">
                                <div class="title-container">
                                    <div class="title-container-inner">
                                        <div class="row flex">
                                            <div class="column width-6 v-align-middle">
                                                <div>
                                                    <h1 class="mb-0">Configurar <strong>minha conta</strong></h1>
                                                    <p class="text-large color-charcoal mb-0 mb-mobile-20">Seja bem-vindo, finalize seu cadastro para ter acesso a todas informações.</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
							</div>
						</div>
					</div>
				</div>
                <div class="section-block team-2 pt-50 bkg-grey-ultralight">
                    <div class="row">
                        <?php
                            // var_dump($_SESSION['membro']);
                            if(isset($_SESSION["membro"])):
                        ?>
                        <div class="row">
                            <?php
                                include "./_notificacaomensagem.php";
                            ?>
                            <div class="box rounded rounded shadow border-blue bkg-white">
                                <div class="column width-3">
                                    <h3 class="mb-10">Sua <br><strong>Conta.</strong></h3>
                                    <?php
                                        //Verificar se há QrCode registrado
                                        // $bQrCode="SELECT qrcode FROM membros WHERE matricula='$matricula'";
                                        //   $rQrCode=mysqli_query($con, $bQrCode);
                                        //     $dQrCode=mysqli_fetch_array($rQrCode);
    
                                        // $bQrCode = "SELECT qrcode FROM membros WHERE matricula='$matricula'";
                                        //   $rQrCode = mysqli_query($con, $bQrCode);
                                        // 	$dQrCode = mysqli_fetch_array($rQrCode);
    
                                            // $QrExi = $dQrCode['qrcode'];
                                        
                                        // if(empty($QrExi)):
                                            include "./_qrcodePartner.php";
                                            //Registrar QrCode
                                            $UpQrCode = "UPDATE membros SET qrcode='$PastaDoQrCode' wHERE matricula='$matricula'";
                                            mysqli_query($con, $UpQrCode);
                                        // endif;
                                    
                                        //Verificar se há QrCode registrado
                                        $bNqr = "SELECT qrcode FROM membros WHERE matricula='$matricula'";
                                        $rNqr = mysqli_query($con, $bNqr);
                                            $dNr = mysqli_fetch_array($rNqr); 
                                        
                                        $Novo_qr = $dNr['qrcode'];
                                    ?>
                                        <span class="color-charcoal text-small ">
                                            Confirmação de membro <strong>https://igrejaemanuel.com.br/carteirinha?m=<?php echo $matricula;?></strong>
                                        </span>
                                        <img class="center" src="<?php echo $Novo_qr; ?>" width='300'>
                                        <a class="overlay-link column full-width button rounded center small bkg-blue bkg-hover-blue-light hard-shadow color-white color-hover-white" target="_blank" href="<?php echo $Novo_qr; ?>">
                                            <span class="text-medium">
                                                Baixar QrCode
                                            </span>
                                        </a>
                                </div>
                                <div class="column width-9">
                                    <!--<form charset="UTF-8" class="form" action="" method="post" enctype="Multipart/form-data">-->
                                        <div class="row center" >
                                            <div class="column width-7">
                                                <h3 class="mb-10 left">Dados <strong>Básicos.</strong></h3>
                                                <p class="left">
                                                    <strong class="color-charcoal">Nome:</strong> <?php echo $nomedousuario;?>
                                                    <br><strong class="color-charcoal">E-mail:</strong> <?php echo $emailusuario;?>
                                                    <br><strong class="color-charcoal">Ministério:</strong> <?php echo $dusuario['ministerio'];?>
                                                    <br><strong class="color-charcoal">Função administrativa:</strong> <?php echo $funcaoadministrativa;?>
                                                    <br><strong class="color-charcoal">Célula:</strong> <?php echo $celuladomembro;?> (<a data-content="inline" data-aux-classes="tml-newsletter-modal height-auto rounded" data-toolbar="" data-modal-mode data-modal-width="600" data-modal-animation="scaleIn" data-lightbox-animation="fadeIn" href="#migrarcelula" class="lightbox-link">Solicitar mudança de célula</a>)
                                                    <br><strong class="color-charcoal">Sua URL:</strong> https://igrejaemanuel.com.br/carteirinha?m=<strong><?php echo $matricula;?></strong>
                                                    <br>
                                                        <!--Alterar url -->
                                                        <a data-content="inline" data-aux-classes="tml-newsletter-modal height-auto rounded" data-toolbar="" data-modal-mode data-modal-width="1140" data-modal-animation="scaleIn" data-lightbox-animation="fadeIn" href="#modal_alterar_url" class="lightbox-link column width-12 button small rounded border-blue border-hover-white color-blue color-hover-blue-light">
                                                            <span class="icon-ccw text-large"></span> Atualizar perfil
                                                        </a>
                                                        <!--Alterar url -->
                                                        <a data-content="inline" data-aux-classes="tml-newsletter-modal height-auto rounded" data-toolbar="" data-modal-mode data-modal-width="1140" data-modal-animation="scaleIn" data-lightbox-animation="fadeIn" class="lightbox-link column full-width button rounded center small bkg-blue bkg-hover-blue-light hard-shadow color-white color-hover-white" target="_blank" href="#atualizarsenha">
                                                            <span class="text-medium color-charcoal">
                                                                Atualizar senha
                                                            </span>
                                                        </a>
                                                </p>
                                            </div>
                                            <div class="column width-5">
                                                <h3 class="mb-10 left"><strong>Foto de perfil.</strong></h3>
                                                <?php
                                                    if(!empty($fotodeperfil)):
                                                ?>
                                                    <img src="<?php echo $fotodeperfil; ?>" width="200" height="200"/>
                                                <?php
                                                    endif;
                                                ?>
                                            </div>
                                            <!-- <div class="column width-12 left">
                                                <h3 class="mb-10 left"><strong>Sua conta.</strong></h3>
                                                <p><a href="#excluirconta" class="lightbox-link data-content="inline" data-aux-classes="tml-form-modal tml-exit-light height-auto with-header rounded" data-toolbar="" data-modal-mode data-modal-width="500" data-modal-animation="scaleIn" data-lightbox-animation="fade">Excluir conta.</a></p>
                                            </div> -->
                                        </div>
                                    <!--</form>-->
                                </div>
                            </div>
                        </div>
                        <?php
                            endif;
                        ?>
                    </div>
                </div>
			</div>
        <!-- Content End -->


			<!-- Footer -->
			<footer class="footer footer-blue">
				<? include "./_footer_top.php"; ?>
			</footer>
			<!-- Footer End -->

		</div>
	</div>

    <div id="migrarcelula" class="pt-70 pb-50 hide">
        <div class="row">
            <div class="column width-10 offset-1">

                <!-- Info -->
                <h3 class="mb-10 center">Mudar de célula</h3>
                <!-- Info End -->

                <!-- Signup -->
                <div class="signup-form-container">
                    <form class="signup-form merged-form-elements" action="<?echo $_SERVER['REQUEST_URI'];?>" method="post">
                        <div class="row">
                            <div class="column width-12">
                                <span class=" color-charcoal weight-bold pb-10 left">Selecione sua nova célula.</span>
                                <div class="form-select form-element rounded medium">
                                    <select name="celula" tabindex="6" class="form-aux" data-label="Project Budget">
                                        <?
                                            $bNC="SELECT celula, matricula, rededacelula, horario, diadeencontro, anfitriao, lider FROM celulas WHERE estado='$estadodomembro' GROUP BY rededacelula ORDER BY municipio ASC";
                                            $rNC=mysqli_query($con, $bNC);
                                                while($dNC=mysqli_fetch_array($rNC)):
                                                    $celulaNOVA=$dNC['celula'];
                                                    $matriculaNOVA=$dNC['matricula'];
                                                    $rededacelulaNOVA=$dNC['rededacelula'];
                                                    $horarioNOVA=$dNC['horario'];
                                                    $diadeencontroNOVA=$dNC['diadeencontro'];
                                                    $anfitriaoNOVA=$dNC['anfitriao'];
                                                    $liderNOVA=$dNC['lider'];
                                        ?>
                                        <option value="<?php echo $matriculaNOVA; ?>"><?php echo 'Rede: '.$rededacelulaNOVA.'; Célula: '.$celulaNOVA.' ('.$diadeencontroNOVA.' ás '.$horarioNOVA.') /'.base64_decode(base64_decode($liderNOVA)); ?></option>
                                        <? endwhile;?>
                                    </select>
                                </div> 
                            </div> 
                            <div class="column width-12">
                                <span class=" color-charcoal weight-bold pb-10 left">Por que você quer migrar? (Está informação será anônima e não será visualizada pelo líder ou equipe da célula)</span>
                                <div class="field-wrapper">
                                    <textarea type="text" name="justificativaparamudanca" class="form-name form-element rounded medium left" placeholder="Justifique seu pedido para mudança de célula, conte a razão de você mudar." required></textarea>
                                </div>
                            </div>
                            <div class="column width-12">
                                <button type="submit" name="btn-migrarcelula" class="form-submit button rounded medium bkg-theme bkg-hover-theme color-white color-hover-white">Confirmar pedido de mudança</button>
                            </div>
                        </div>
                    </form>
                </div>
                <!-- Signup End -->

            </div>
        </div>
    </div>
    
    <div id="modal_alterar_url" class="hide">
        <div class="box rounded medium bkg-white">
            <div class="row">
                <div class="column width-12 center">
                    <h3 class="color-blue mb-10 pt-0 pb-20">Atualizar <strong>perfil</strong>.</h3>
                </div>
                <div class="column width-12 left">
                    <div class="form">
                        <form class="form" charset="UTF-8" action="<? echo $_SERVER['REQUEST_URI']; ?>" charset='utf-8' method="post" enctype="multipart/form-data">
                            <div class="row">
                                <div class="column width-12">
                                    <span class="color-charcoal"><strong>Foto do perfil</strong></span>
                                    <input type="file" name="fotodoperfil" class="form-fname form-element medium" placeholder="Digite seu primeiro nome (Ele estará em seus certificados e documentos da Emanuel)" tabindex="01">
                                </div>
                                <div class="column width-3">
                                    <span class="color-charcoal"><strong>NOME (COMPLETO)</strong></span>
                                    <input type="text" name="nome" value="<?php echo $nomeusuario; ?>" class="form-fname form-element medium" placeholder="Digite seu primeiro nome (Ele estará em seus certificados e documentos da Emanuel)" tabindex="01" required>
                                </div>
                                <div class="column width-3">
                                    <span class="color-charcoal"><strong>SOBRENOME (COMPLETO)</strong></span>
                                    <input type="text" name="sobrenome"  value="<?php echo $sobrenomeusuario; ?>" class="form-fname form-element medium" placeholder="Digite seu sobrenome completo (Ele estará em seus certificados e documentos da Emanuel)" tabindex="01" required>
                                </div>
                                <div class="column width-3">
                                    <span class="color-charcoal"><strong>CPF</strong></span>
                                    <input type="number" name="cpf" minlenght='11' maxlenght='11' value="<?php echo $cpfdomembro; ?>" class="form-fname form-element medium" placeholder="Digite seu sobrenome completo (Ele estará em seus certificados e documentos da Emanuel)" tabindex="01" required>
                                </div>
                                <div class="column width-3">
                                    <span class="color-charcoal"><strong>RG</strong></span>
                                    <input type="number" name="rg" minlenght='9' maxlenght='9' value="<?php echo $rgdomembro; ?>" class="form-fname form-element medium" placeholder="" tabindex="01">
                                </div>
                                <div class="column width-4">
                                    <span class="color-charcoal"><strong>E-MAIL</strong></span>
                                    <input type="email" name="email"  value="<?php echo $emailusuario; ?>" class="form-fname form-element medium" placeholder="E-mail principal" tabindex="02" required>
                                </div>
                                <div class="column width-3">
                                    <span class=" color-charcoal weight-bold pb-10">Sexo.</span>
                                    <div class="form-select form-element rounded medium">
                                        <select name="sexo" tabindex="6" class="form-aux" data-label="Project Budget">
                                            <option><?php echo $sexo; ?></option>
                                            <option>Masculino</option>
                                            <option>Feminino</option>
                                        </select>
                                    </div> 
                                </div> 
                                <div class="column width-2">
                                    <span class="color-charcoal">TEL. <strong>MÓVEL</strong></span>
                                    <input type="tel" name="telefonemovel"  value="<?php echo $telefonemovelusuario; ?>" class="form-fname form-element medium" placeholder="21 34098021" tabindex="02">
                                </div>
                                <div class="column width-3">
                                    <span class="color-charcoal"><strong>WHATSAPP</strong></span>
                                    <input type="tel" name="whatsapp"  value="<?php echo $whatsappdomembro; ?>" class="form-fname form-element medium" placeholder="21 943098412" tabindex="02">
                                </div>
                                <div class="column width-4">
                                    <span class=" color-charcoal weight-bold pb-10">Motivo de estar na Emanuel?</span>
                                    <div class="form-select form-element rounded medium">
                                        <select name="motivo" tabindex="6" class="form-aux" data-label="Project Budget"> 
                                            <option selected='selected'><?php echo $dusuario['motivo']; ?></option>
                                            <option>Conversão</option>
                                            <option>Retorno</option>
                                            <option>Fundador</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="column width-8">
                                    <span class="color-charcoal"><strong>Endereço</strong></span>
                                    <input type="text" name="endereco"  value="<?php echo $enderecodomembro; ?>" class="form-fname form-element medium" placeholder="Rua Otávio Peixoto, 320" tabindex="02">
                                </div>
                                <div class="column width-3">
                                    <span class="color-charcoal"><strong>CEP</strong></span>
                                    <input type="number" name="cep"  value="<?php echo $cepdomembro; ?>" class="form-fname form-element medium" placeholder="02123453" tabindex="02">
                                </div>
                                <div class="column width-3">
                                    <span class=" color-charcoal weight-bold pb-10">
                                        Estado
                                    </span>
                                    <div class="form-select form-element rounded medium">
                                        <select name="estado" tabindex="6" class="form-aux" data-label="Project Budget">
                                            <option selected='selected'><?php echo $dusuario['uf']; ?></option> 
                                            <?php
                                                $bEt="SELECT nome FROM estados WHERE nome='Rio de Janeiro'";
                                                    $rEt=mysqli_query($con, $bEt);
                                                    while($dEt=mysqli_fetch_array($rEt)):
                                            ?>
                                            <option><?php echo $dEt['nome']; ?></option>
                                            <?php
                                                endwhile;
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="column width-3">
                                    <span class=" color-charcoal weight-bold pb-10">
                                        Cidade
                                    </span>
                                    <div class="form-select form-element rounded medium">
                                        <select name="cidade" tabindex="6" class="form-aux" data-label="Project Budget"> 
                                            <option selected='selected'><?php echo $dusuario['cidade']; ?></option>
                                            <?php
                                                $bEt="SELECT nome FROM cidades WHERE estado='19'";
                                                    $rEt=mysqli_query($con, $bEt);
                                                    while($dEt=mysqli_fetch_array($rEt)):
                                            ?>
                                            <option><?php echo $dEt['nome']; ?></option>
                                            <?php
                                                endwhile;
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="column width-3">
                                    <span class="color-charcoal"><strong>Data de Nascimento</strong></span>
                                    <input type="date" name="nascimento"  value="<?php echo $nascimento; ?>" class="form-fname form-element medium" placeholder="" tabindex="02">
                                </div>
                                <div class="column width-4">
                                    <span class="color-charcoal"><strong>Pai</strong> (Nome completo)</span>
                                    <input type="text" name="pai"  value="<?php echo $dusuario['pai']; ?>" class="form-fname form-element medium" placeholder="Nome do seu pai" tabindex="02">
                                </div>
                                <div class="column width-4">
                                    <span class="color-charcoal"><strong>Mãe</strong> (Nome completo)</span>
                                    <input type="text" name="mae"  value="<?php echo $dusuario['mae']; ?>" class="form-fname form-element medium" placeholder="Nome da sua mãe." tabindex="02">
                                </div>
                                <div class="column width-4">
                                    <span class=" color-charcoal weight-bold pb-10">
                                        Estado cívil
                                    </span>
                                    <div class="form-select form-element rounded medium">
                                        <select name="estadocivil" tabindex="6" class="form-aux" data-label="Project Budget"> 
                                            <option selected='selected'><?php echo $dusuario['estadocivil']; ?></option>
                                            <option >Solteiro (a)</option>
                                            <option>Casado (a)</option>
                                            <option>Noivo (a)</option>
                                            <option>Namorando</option>
                                            <option>Divorciado (a)</option>
                                            <option>Morando junto</option>
                                            <option>Outro</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="column width-3">
                                    <span class=" color-charcoal weight-bold pb-10">
                                        Nível escolar
                                    </span>
                                    <div class="form-select form-element rounded medium">
                                        <select name="grauescolar" tabindex="6" class="form-aux" data-label="Project Budget"> 
                                            <option selected='selected'><?php echo $dusuario['grauescolar'];?></option>
                                            <option>Doutorado</option>
                                            <option>Mestre</option>
                                            <option>Pós graduado</option>
                                            <option>Graduado</option>
                                            <option>Ensino técnico</option>
                                            <option>Ensino médio</option>
                                            <option>Ensino fundamental</option>
                                            <option>Educação básica incompleta</option>
                                            <option>Outro</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="column width-3">
                                    <span class="color-charcoal"><strong>Área</strong> de formação</span>
                                    <input type="text" name="formadoem"  value="<?php echo $dusuario['formadoem']; ?>" class="form-fname form-element medium" placeholder="Administração" tabindex="02">
                                </div>
                                <div class="column width-3">
                                    <span class="color-charcoal"><strong>Profissão</strong> atual</span>
                                    <input type="text" name="profissao"  value="<?php echo $dusuario['profissao']; ?>" class="form-fname form-element medium" placeholder="Administrador" tabindex="02">
                                </div>
                                <div class="column width-3">
                                    <span class="color-charcoal"><strong>Piso</strong> salarial (R$)</span>
                                    <input type="text" name="pisosalarial"  value="<?php echo $pisosalarialdomembro; ?>" class="form-fname form-element medium" placeholder="1.395,00" tabindex="02">
                                </div>
                                <div class="column width-3">
                                    <span class="color-charcoal"><strong>Frequenta </strong>a Emanuel desde:</span>
                                    <input type="date" name="frequentaemanueldesde"  value="<?php echo $dusuario['frequentaaemanueldesde']; ?>" class="form-fname form-element medium" placeholder="16/01/2021" tabindex="02">
                                </div>
                                <div class="column width-6">
                                    <span class=" color-charcoal weight-bold pb-10">
                                        Você é consagrado ao ministério?
                                    </span>
                                    <div class="form-select form-element rounded medium">
                                        <select name="ministerio" tabindex="6" class="form-aux" data-label="Project Budget"> 
                                            <option selected='selected' value="<?php echo $dusuario['ministerio']; ?>"><?php echo $dusuario['ministerio']; ?></option>
                                            <option value="">Sou apenas membro</option>
                                            <option value="Pastor">Sou Pastor</option>
                                            <option value="Pastora">Sou Pastora</option>
                                            <option value="Evangelista">Sou Evangelista</option>
                                            <option value="Presbítero">Sou Presbítero</option>
                                            <option value="Diacono">Sou Diacono</option>
                                            <option value="Diáconisa">Sou Diáconisa</option>
                                            <option value="Missionária">Sou Missionária</option>
                                            <option value="Outro">Outro</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="column width-3">
                                    <span class=" color-charcoal weight-bold pb-10">
                                        Quer servir a igreja?
                                    </span>
                                    <div class="form-select form-element rounded medium">
                                        <select name="desejaservirnaigreja" tabindex="6" class="form-aux" data-label="Project Budget"> 
                                            <option selected='selected'>Sim</option>
                                            <option>Não</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="column width-3">
                                    <span class=" color-charcoal weight-bold pb-10">
                                        Como?
                                    </span>
                                    <div class="form-select form-element rounded medium">
                                        <select name="serviremqualarea" tabindex="6" class="form-aux" data-label="Project Budget"> 
                                            <option selected='selected'><? echo $ministerio;?></option>
                                            <option>Diaconia</option>
                                            <option>Ministério</option>
                                            <option>Missões/Social</option>
                                            <option>Kids</option>
                                            <option>Homens</option>
                                            <option>Mulheres</option>
                                            <option>Jovens</option>
                                            <option>Adolescentes</option>
                                            <option>Infraestrutura</option>
                                            <option>Mídias e Comunicação</option>
                                            <option>Negócios e Empreendimentos</option>
                                            <option>Dança</option>
                                            <option>Células</option>
                                            <option>Educação</option>
                                            <option>Música</option>
                                            <option>Outro</option>
                                        </select>
                                    </div>
                                </div>
                                <? if(empty($dusuario['pertenceaqualcelula'])): $tp='3';?>
                                <div class="column width-3">
                                    <span class=" color-charcoal weight-bold pb-10">
                                        Pertence a qual célula?
                                    </span>
                                    <div class="form-select form-element rounded medium">
                                        <select name="pertenceaqualcelula" tabindex="6" class="form-aux" data-label="Project Budget">
                                            <option value="">
                                                Nenhuma
                                            </option>
                                            <?php
                                                $bCelulas="SELECT celula, matricula FROM celulas WHERE estado='$estadodomembro' GROUP BY matricula ORDER BY celula ASC, municipio ASC";
                                                    $rCelulas=mysqli_query($con, $bCelulas);
                                                    while($dCelulas=mysqli_fetch_array($rCelulas)):
                                            ?>
                                            <option value="<?php echo $dCelulas['celula'].'~'.$dCelulas['matricula'];?>">
                                                <?php echo $dCelulas['celula'].' ('.$dCelulas['matricula'].')';?>
                                            </option>
                                            <?php
                                                endwhile;
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <? else: $tp='6'; endif; ?>
                                <div class="column width-<? echo $tp;?>">
                                    <span class="color-charcoal">Nº de <strong>failiares</strong> na Emanuel</span>
                                    <input type="number" name="numerodefamiliaresnaemanuel"  value="<?php echo $dusuario['numerodefamiliaresnaemanuel']; ?>" class="form-fname form-element medium" placeholder="3" tabindex="02">
                                </div>
                                <div class="column width-3">
                                    <span class="color-charcoal">Data do <strong>batismo</strong> </span>
                                    <input type="date" name="datadobatismo"  value="<?php echo $dusuario['datadobatismo']; ?>" class="form-fname form-element medium" placeholder="01/04/2020" tabindex="02">
                                </div>
                                <div class="column width-6">
                                    <span class="color-charcoal">Igreja do <strong>batismo</strong></span>
                                    <input type="text" name="igrejadebatismo"  value="<?php echo $dusuario['igrejadebatismo']; ?>" class="form-fname form-element medium" placeholder="Igreja Emanuel" tabindex="02">
                                </div>
                                <div class="column width-6">
                                    <span class=" color-charcoal weight-bold pb-10">
                                        É batizado com os dons do Espírito Santo?
                                    </span>
                                    <div class="form-select form-element rounded medium">
                                        <select name="batizadonoespiritosanto" tabindex="6" class="form-aux" data-label="Project Budget"> 
                                            <option selected='selected'>Sim</option>
                                            <option>Não</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="column width-3">
                                    <span class=" color-charcoal weight-bold pb-10">
                                        Sua principal <strong>mídia social</strong>.
                                    </span>
                                    <div class="form-select form-element rounded medium">
                                        <select name="midiasocial1" tabindex="6" class="form-aux" data-label="Project Budget"> 
                                            <option selected='selected'><?php echo $dusuario['midiasocial1'];?></option>
                                            <option value="facebook">Facebook</option>
                                            <option value="instagram">Instagram</option>
                                            <option value="youtube">Youtube</option>
                                            <option value="twitter">Twitter</option>
                                            <option value="tiktok">TikTok</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="column width-9">
                                    <span class="color-charcoal">Link do seu perfil nessa mídia <strong>social</strongspan/label>
                                    <input type="text" name="link1"  value="<?php echo $link1domembro; ?>" class="form-fname form-element medium" placeholder="instagram.com/emanueligrejabr" tabindex="02">
                                </div>
                                <div class="column width-3">
                                    <span class=" color-charcoal weight-bold pb-10">
                                        Segunda <strong>Mídia social</strong>.
                                    </span>
                                    <div class="form-select form-element rounded medium">
                                        <select name="midiasocial2" tabindex="6" class="form-aux" data-label="Project Budget"> 
                                            <option selected='selected'><?php echo $dusuario['midiasocial2'];?></option>
                                            <option value="facebook">Facebook</option>
                                            <option value="instagram">Instagram</option>
                                            <option value="youtube">Youtube</option>
                                            <option value="twitter">Twitter</option>
                                            <option value="tiktok">TikTok</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="column width-9">
                                    <span class="color-charcoal">Link do seu perfil nessa mídia <strong>social</strongspan/label>
                                    <input type="text" name="link2"  value="<?php echo $link2domembro; ?>" class="form-fname form-element medium" placeholder="youtube.com/emanueligrejabr" tabindex="02">
                                </div>
                                <div class="column width-3">
                                    <span class=" color-charcoal weight-bold pb-10">
                                        Terceira <strong>Mídia social</strong>.
                                    </span>
                                    <div class="form-select form-element rounded medium">
                                        <select name="midiasocial3" tabindex="6" class="form-aux" data-label="Project Budget"> 
                                            <option selected='selected'><?php echo $dusuario['midiasocial3'];?></option>
                                            <option value="facebook">Facebook</option>
                                            <option value="instagram">Instagram</option>
                                            <option value="youtube">Youtube</option>
                                            <option value="twitter">Twitter</option>
                                            <option value="tiktok">TikTok</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="column width-9">
                                    <span class="color-charcoal">Link do seu perfil nessa mídia <strong>social</strongspan/label>
                                    <input type="text" name="link3"  value="<?php echo $link3domembro; ?>" class="form-fname form-element medium" placeholder="twitter.com/emanueligrejabr" tabindex="02">
                                </div>
                                <div class="column width-3">
                                    <span class=" color-charcoal weight-bold pb-10">
                                        Quarta <strong>Mídia social</strong>.
                                    </span>
                                    <div class="form-select form-element rounded medium">
                                        <select name="midiasocial4" tabindex="6" class="form-aux" data-label="Project Budget"> 
                                            <option selected='selected'><?php echo $dusuario['midiasocial4'];?></option>
                                            <option value="facebook">Facebook</option>
                                            <option value="instagram">Instagram</option>
                                            <option value="youtube">Youtube</option>
                                            <option value="twitter">Twitter</option>
                                            <option value="tiktok">TikTok</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="column width-9">
                                    <span class="color-charcoal">Link do seu perfil nessa mídia <strong>social</strongspan/label>
                                    <input type="text" name="link4"  value="<?php echo $link4domembro; ?>" class="form-fname form-element medium" placeholder="faecebook.com/emanueligrejabr" tabindex="02">
                                </div>
                                <div class="column width-12">
                                    <span class="color-charcoal">Pense que está é sua BIO e nos diga: Quem é você? <strong>(Esse texto pode ficar vísivel na página ministerial do site)</strong><span>
                                    <textarea type="text" name="descricaodomembro"  value="<?php echo $dusuario['descricaodomembro']; ?>" class="form-fname form-element medium" placeholder="Pastor, Administrador, Pós graduado em Finanças pela FGV, casado e apaixonado por apresentar a pessoa de Cristo." tabindex="02"><? echo $dusuario['descricaodomembro'];?></textarea>
                                </div>
                                <div class="column width-12">
                                    <span class="color-black">
                                        AO FINALIZAR VOCÊ CONFIRMA QUE PREENCHEU ESTE PRÉ CADASTRO DE MEMBRO ESPONTÂNEAMENTE, QUE QUER SER <strong>DECLARADO MEMBRO DA EMANUEL (IGREJA BATISTA EMANUEL)</strong> E QUE ESTÁ CIENTE DE QUE AO CONFIRMAR ESTE FORMULÁRIO AUTORIZARÁ O USO DA SUA IMAGEM EM TODOS OS VEÍCULOS DE DIVULGAÇÃO, COMUNICAÇÃO E PUBLICIDADE PELA EMANUEL.
                                    </span>
                                    
                                    <div class="field-wrapper pt-10 pb-10">
                                        <input id="radio-sim" class="form-element radio rounded" name="declaracao" value="SIM" type="radio" checked required>
                                        <label for="radio-sim" class="radio-label color-black" tabindex="6">
                                            Sim
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="column width-12 border-theme pt-20 pb-20">
                                    <span class="center color-charcoal">PARA FINALIZAR <strong>RESOLVA O CÁLCULO ABAIXO</strong></span>
                                    <div class="field-wrapper">
                                        <div class="column width-3 pt-20 offset-2">
                                            <h3 class="right">
                                                <?php echo $v1.' + '.$v2.' = ';?>
                                            </h3>
                                        </div>
                                        <div class="column width-6">
                                            <div class="field-wrapper">
                                                <input type="hidden" name="soma1" class="form-name form-element medium" value="<?php $somaa = $v1+$v2; echo $somaa;?>">
                                                <input type="number" name="soma" class="form-name form-element medium" placeholder="Informar valor" tabindex="03" required>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="column width-12 pt-20">
                                    <button tabindex="04" type="submit" name="btn-cadastrar" class="form-submit button small rounded bkg-blue bkg-hover-blue-light color-white color-hover-white hard-shadow"><span class="text-medium weight-bold">Finalizar</span></button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

	<? include "./_script.php"; ?>
</body>
</html>