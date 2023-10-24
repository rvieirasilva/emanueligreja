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
    
	$v1 = mt_rand(1,5);
    $v2 = rand(0,5);

    $soma = $v1 + $v2;
        //$_SESSION['soma'] = $_POST['soma1'];
       	$s = $_POST['soma1'];

    	//$somado = $_POST['soma'];
        //$_SESSION['somado'] = $_POST['soma'];
        //$s2 = $_SESSION['somado'];
        $s2 = $_POST['soma'];

    $matriculaprocurada = $_GET['ref'];

    if(isset($_POST['btn-excluir'])):
        $delMembro="DELETE FROM membros WHERE matricula='$matriculaprocurada'";
        if(mysqli_query($con, $delMembro)):
            $_SESSION['cordamensagem']='blue';
            $_SESSION['mensagem']="Excluído com sucesso.";
            header("location: equipemembrolistar?$linkSeguro");
        endif;
    endif;
    
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
            $statusdomembro                 = mysqli_escape_string($con, $_POST['statusdomembro']);
            $funcaoadministrativa           = mysqli_escape_string($con, $_POST['funcaoadministrativa']);
            $formadoem                      = mysqli_escape_string($con, $_POST['formadoem']);
            $profissao                      = mysqli_escape_string($con, $_POST['profissao']);
            $pisosalarial                   = mysqli_escape_string($con, $_POST['pisosalarial']);
            $frequentaemanueldesde          = mysqli_escape_string($con, $_POST['frequentaemanueldesde']);
            //$ministerioN                    = mysqli_escape_string($con, $_POST['ministerio']); //Array com opções selecionadas
            
            $TotalMinisterio = count(!empty($_POST['ministerio']));
            for($iM=0; $iM < $TotalMinisterio; $iM++){
                @$ministerioN = implode(',', $_POST['ministerio']);
            }

            $desejaservirnaigreja           = mysqli_escape_string($con, $_POST['desejaservirnaigreja']);
            //$serviremqualarea[]             = mysqli_escape_string($con, $_POST['serviremqualarea']);

            $ServirArea = count(!empty($_POST['serviremqualarea']));
            for($sA=0; $sA < $ServirArea; $sA++){
                @$serviremqualarea = implode(',', $_POST['serviremqualarea']);
            }

            $pertenceAoCampusArray       = mysqli_escape_string($con, $_POST['campus']);
              $PertenceaoCampus = explode('~', $pertenceAoCampusArray);
              $pertenceAoCampus = $PertenceaoCampus[0];
              $matriculadocampus = $PertenceaoCampus[1];

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

            //$upMinisterio             = $ministerio.','.$ministerioN;

            if($sexo === 'Masculino'):
                @$ministerioN = str_replace('Pastor (a)', 'Pastor', $ministerioN); //Caso defina como pastor.
                @$ministerioN = str_replace('Diacono (a)', 'Diacono', $ministerioN); //Caso defina como pastor.
                @$ministerioN = str_replace('Coordenador (a) de rede', 'Coordenador de rede', $ministerioN); //Caso defina como pastor.
                @$ministerioN = str_replace('Supervisor (a) de célula', 'Supervisor de célula', $ministerioN); //Caso defina como pastor.
                @$ministerioN = str_replace('Secretário (a) de célula', 'Secretário de célula', $ministerioN); //Caso defina como pastor.
                @$ministerioN = str_replace('Tesoureiro (a) de célula', 'Tesoureiro de célula', $ministerioN); //Caso defina como pastor.

                @$funcaoadministrativa = str_replace('Tesoureiro (a)', 'Tesoureiro', $funcaoadministrativa);
                @$funcaoadministrativa = str_replace('II TesoureIro (a)', 'II Tesoureiro', $funcaoadministrativa);
                @$funcaoadministrativa = str_replace('Secretário (a)', 'Secretario', $funcaoadministrativa);
                @$funcaoadministrativa = str_replace('II Secretário (a)', 'II Secretario', $funcaoadministrativa);
            elseif($sexo === "Feminino"):
                @$ministerioN = str_replace('Pastor (a)', 'Pastora', $ministerioN); //Caso defina como pastor.
                @$ministerioN = str_replace('Diacono (a)', 'Diáconisa', $ministerioN); //Caso defina como pastor.
                @$ministerioN = str_replace('Coordenador (a) de rede', 'Coordenadora de rede', $ministerioN); //Caso defina como pastor.
                @$ministerioN = str_replace('Supervisor (a) de célula', 'Supervisora de célula', $ministerioN); //Caso defina como pastor.
                @$ministerioN = str_replace('Secretário (a) de célula', 'Secretária de célula', $ministerioN); //Caso defina como pastor.
                @$ministerioN = str_replace('Tesoureiro (a) de célula', 'Tesoureira de célula', $ministerioN); //Caso defina como pastor.

                @$funcaoadministrativa = str_replace('Tesoureiro (a)', 'Tesoureira', $funcaoadministrativa);
                @$funcaoadministrativa = str_replace('II TesoureIro (a)', 'II Tesoureira', $funcaoadministrativa);
                @$funcaoadministrativa = str_replace('Secretário (a)', 'Secretaria', $funcaoadministrativa);
                @$funcaoadministrativa = str_replace('II Secretário (a)', 'II Secretaria', $funcaoadministrativa);
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
                $bFPA="SELECT fotodeperfil FROM membros WHERE matricula='$matriculaprocurada'";
                  $rFPA=mysqli_query($con, $bFPA);
                    $dFPA=mysqli_fetch_array($rFPA);
                
                $fotodoperfilAntiga = $dFPA['fotodeperfil'];


				$extensaofotodeperfil = pathinfo($_FILES['fotodoperfil']['name'], PATHINFO_EXTENSION);
				if(in_array($extensaofotodeperfil, $formatospermitidosimagens)):
					@mkdir("arq/", 0777); //Cria a pasta se não houver. 
					@mkdir("arq/membros/", 0777); //Cria a pasta se não houver. 
                    @mkdir("arq/membros/$matriculaprocurada/", 0777); //Cria a pasta se não houver. 
                    
					$pastafotodeperfil			= "arq/membros/$matriculaprocurada/";
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
            $bQrCode = "SELECT qrcode FROM membros WHERE matricula='$matriculaprocurada'";
            $rQrCode = mysqli_query($con, $bQrCode);
                //$nQrCode = mysqli_num_rows($rQrCode);
                $dQrCode = mysqli_fetch_array($rQrCode);

                $QrExi = $dQrCode['qrcode'];
            
            if(empty($QrExi)):
                include "./_qrcodePartner.php";
                //Registrar QrCode
                $UpQrCode = "UPDATE membros SET qrcode='$PastaDoQrCode' wHERE matricula='$matriculaprocurada'";
                mysqli_query($con, $UpQrCode);
            endif;        
            // Qr Code

            //Atualizar dados do membro
            $upMembro = "UPDATE membros SET nome='$nome', sobrenome='$sobrenome', descricaodomembro='$descricaodomembro', nascimento='$nascimento', sexo='$sexo', email='$email', cpf='$cpf', rg='$rg', telefonemovel='$telefonemovel', whatsapp='$whatsapp', endereco='$endereco', cep='$cep', uf='$estado', cidade='$cidade', pai='$pai', mae='$mae', estadocivil='$estadocivil', grauescolar='$grauescolar', formadoem='$formadoem', profissao='$profissao', pisosalarial='$pisosalarial', igreja='$igreja', frequentaaemanueldesde='$frequentaemanueldesde', funcaoadministrativa = '$funcaoadministrativa' ,ministerio='$ministerioN', desejaservirnaigreja='$desejaservirnaigreja', serviremqualarea='$serviremqualarea', pertenceaqualcelula='$pertenceaqualcelula', matriculadacelula='$matriculadacelulapertencente', declaracaodemembro='$declaracao', motivo='$motivo', numerodefamiliaresnaemanuel='$numerodefamiliaresnaemanuel', datadobatismo='$datadobatismo', igrejadebatismo='$igrejadebatismo', batizadonoespiritosanto='$batizadonoespiritosanto', matricula='$matriculaprocurada', statusdomembro='$statusdomembro', midiasocial1='$midiasocial1', link1='$link1', midiasocial2='$midiasocial2', link2='$link2', midiasocial3='$midiasocial3', link3='$link3', midiasocial4='$midiasocial4', link4='$link4', membrodocampus='$pertenceAoCampus', codigodocampus='$matriculadocampus' WHERE matricula='$matriculaprocurada'";

            if(mysqli_query($con, $upMembro)):
                if(!empty($fotodoperfilAntiga)):
                    @unlink($fotodoperfilAntiga); //Apaga a foto antiga.
                endif;

                $nomeemail=base64_decode(base64_decode($nome));
                if($statusdomembro == 'Ativo'):
                    include "./_enviaremail.php";
                    $mail->addAddress("$email", "$nomeemail - Igreja Emanuel");     // Add a recipient 
                        //$mail->addAddress("ellen@example.com");               // Name is optional
                        //$mail->addReplyTo("info@example.com", "Information");
                        //$mail->addCC("cc@example.com");
                        //$mail->addBCC("grupormd+g5f5dsztwcbzrbnxf0xs@boards.trello.com");

                        //$mail->addAttachment("/var/tmp/file.tar.gz");         // Add attachments
                        //$mail->addAttachment("/tmp/image.jpg", "new.jpg");    // Optional name
                    $mail->isHTML(true);                                  // Set email format to HTML

                    $mail->Subject = "Bem vindo!";
                    $mail->Body    = "Olá $nomeemail. 
                                    <p>Seu acesso ao site da igreja foi liberado, bem-vindo.</p>
                                    <p>Estamos alegres por sua chegada ao nosso ministério, vamos servir a Jesus</p>
                                    <p<h1>Cristo é o Senhor!</p>
                                    ";
                    $mail->AltBody = "Bem vindo."; // Não é visualizado pelo usuário!
                    $mail->send();
                endif;
                    
                $_SESSION['cordamensagem']="green";
                $_SESSION['mensagem']="Dados foram atualizados com sucesso.";
                // header("refresh:3");
                header("location: equipemembrolistar?$linkSeguro");
            else:
                $_SESSION['cordamensagem']="red";
                $_SESSION['mensagem']="Erro ao atualizar dados, tente novamente mais tarde ou vá até nossa secretaria.";
            endif;
		else:
			//captcha invalido
            $_SESSION['bot'] = true;
            $_SESSION['cordamensagem']='red';
            $_SESSION['mensagem']='Erro no reCAPTCHA. Informe o valor correto da soma.';
		endif;
    endif;
    
    //Pegar os dados do membro selecionado para alterar.
    $bMe="SELECT * FROM membros WHERE matricula='$matriculaprocurada'";
      $rMe=mysqli_query($con, $bMe);
        $dMe=mysqli_fetch_array($rMe);

        $ministerioprocurado = explode(',', $dMe['ministerio']);
        $serviremqualareaprocurado = explode(',', $dMe['serviremqualarea']);
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
        <div class="screen">
            <div class="content clearfix">
                <div class="section-block tm-slider-parallax-container pb-30 small bkg-blue">
                    <div class="row">
                        <div class="box rounded small bkg-white shadow">
                            <div class="column width-12">
                                <div class="title-container">
                                    <div class="title-container-inner">
                                        <div class="row flex">
                                            <div class="column width-8 v-align-middle">
                                                <div>
                                                    <h1 class="mb-0"><strong>Cadastro/Aprovação</strong> de membros</h1>
                                                    <p class="text-large color-charcoal mb-0 mb-mobile-20">Por isso não
                                                        corro sem objetivo.</p>
                                                </div>
                                            </div>
                                            <div class="column width-4 v-align-middle">
                                                <div>
                                                    <ul
                                                        class="breadcrumb inline-block mb-0 pull-right clear-float-on-mobile">
                                                        <li>
                                                            <a
                                                                href="equipemembrolistar<?php if(!empty($linkSeguro)): echo '?'.$linkSeguro; endif;?>">Listar
                                                                membros</a>
                                                        </li>
                                                        <li>
                                                            Cadastro/Aprovação de membro.
                                                        </li>
                                                    </ul>
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
                        <?php include "./_notificacaomensagem.php"; ?>
                        <div class="box rounded rounded shadow border-blue bkg-white">
                            <div class="column width-12 pt-30">
                                <form class="form" charset="UTF-8" action="" charset='utf-8' method="post"
                                    enctype="multipart/form-data">
                                    <div class="row">
                                        <div class="column width-12">
                                            <span class="color-charcoal"><strong>Foto do perfil</strong></span>
                                            <input type="file" name="fotodoperfil"
                                                class="form-fname form-element medium"
                                                placeholder="Digite seu primeiro nome (Ele estará em seus certificados e documentos da Emanuel)"
                                                tabindex="01">
                                        </div>
                                        <div class="column width-3">
                                            <span class="color-charcoal"><strong>NOME (COMPLETO)</strong></span>
                                            <input type="text" name="nome"
                                                value="<?php $nomeM=base64_decode($dMe['nome']); echo base64_decode($nomeM); ?>"
                                                class="form-fname form-element medium"
                                                placeholder="Digite seu primeiro nome (Ele estará em seus certificados e documentos da Emanuel)"
                                                tabindex="01" required>
                                        </div>
                                        <div class="column width-3">
                                            <span class="color-charcoal"><strong>SOBRENOME (COMPLETO)</strong></span>
                                            <input type="text" name="sobrenome"
                                                value="<?php $sobrenomeM=base64_decode($dMe['sobrenome']); echo base64_decode($sobrenomeM); ?>"
                                                class="form-fname form-element medium"
                                                placeholder="Digite seu sobrenome completo (Ele estará em seus certificados e documentos da Emanuel)"
                                                tabindex="01" required>
                                        </div>
                                        <div class="column width-3">
                                            <span class="color-charcoal"><strong>CPF</strong></span>
                                            <input type="number" name="cpf"
                                                value="<?php $DocCpf = base64_decode($dMe['cpf']); echo base64_decode($DocCpf); ?>"
                                                class="form-fname form-element medium"
                                                placeholder="Digite seu sobrenome completo (Ele estará em seus certificados e documentos da Emanuel)"
                                                tabindex="01">
                                        </div>
                                        <div class="column width-3">
                                            <span class="color-charcoal"><strong>RG</strong></span>
                                            <input type="number" name="rg"
                                                value="<?php $DocRg = base64_decode($dMe['rg']); echo base64_decode($DocRg); ?>"
                                                class="form-fname form-element medium" placeholder="" tabindex="01">
                                        </div>
                                        <div class="column width-4">
                                            <span class="color-charcoal"><strong>E-MAIL</strong></span>
                                            <input type="email" name="email" value="<?php echo $dMe['email']; ?>"
                                                class="form-fname form-element medium" placeholder="E-mail principal"
                                                tabindex="02" required>
                                        </div>
                                        <div class="column width-3">
                                            <span class=" color-charcoal weight-bold pb-10">Sexo.</span>
                                            <div class="form-select form-element rounded medium">
                                                <select name="sexo" tabindex="6" class="form-aux"
                                                    data-label="Project Budget">
                                                    <option><?php echo $dMe['sexo']; ?></option>
                                                    <?php
                                                            if($dMe['sexo'] === "Feminino"):
                                                        ?>
                                                    <option>Masculino</option>
                                                    <?php
                                                            else:
                                                        ?>
                                                    <option>Feminino</option>
                                                    <?php
                                                            endif;
                                                        ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="column width-2">
                                            <span class="color-charcoal">TEL. <strong>MÓVEL</strong></span>
                                            <input type="tel" name="telefonemovel"
                                                value="<?php $telMov= base64_decode($dMe['telefonemovel']); echo base64_decode($telMov); ?>"
                                                class="form-fname form-element medium" placeholder="21 34098021"
                                                tabindex="02">
                                        </div>
                                        <div class="column width-3">
                                            <span class="color-charcoal"><strong>WHATSAPP</strong></span>
                                            <input type="tel" name="whatsapp"
                                                value="<?php $whats=base64_decode($dMe['whatsapp']); $whats = base64_decode($whats); ?>"
                                                class="form-fname form-element medium" placeholder="21 943098412"
                                                tabindex="02">
                                        </div>
                                        <div class="column width-4">
                                            <span class=" color-charcoal weight-bold pb-10">Motivo de Emanuel?</span>
                                            <div class="form-select form-element rounded medium">
                                                <select name="motivo" tabindex="6" class="form-aux"
                                                    data-label="Project Budget">
                                                    <option selected='selected'><?php echo $dMe['motivo']; ?></option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="column width-8">
                                            <span class="color-charcoal"><strong>Endereço</strong></span>
                                            <input type="text" name="endereco"
                                                value="<?php $endAl = base64_decode($dMe['endereco']); echo base64_decode($endAl); ?>"
                                                class="form-fname form-element medium"
                                                placeholder="Rua Otávio Peixoto, 320" tabindex="02">
                                        </div>
                                        <div class="column width-3">
                                            <span class="color-charcoal"><strong>CEP</strong></span>
                                            <input type="number" name="cep"
                                                value="<?php $endAl = base64_decode($dMe['cep']); echo base64_decode($endAl); ?>"
                                                class="form-fname form-element medium" placeholder="02123453"
                                                tabindex="02">
                                        </div>
                                        <div class="column width-3">
                                            <span class=" color-charcoal weight-bold pb-10">
                                                Estado
                                            </span>
                                            <div class="form-select form-element rounded medium">
                                                <select name="estado" tabindex="6" class="form-aux"
                                                    data-label="Project Budget">
                                                    <option selected='selected'><?php echo $dMe['uf']; ?></option>
                                                    <?php
                                                            $bEt="SELECT nome FROM estados WHERE id='19'";
                                                                $rEt=mysqli_query($con, $bEt);
                                                                while($dEt=mysqli_fetch_array($rEt)):
                                                        ?>
                                                    <option selected='selected'><?php echo $dEt['nome']; ?></option>
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
                                                <select name="cidade" tabindex="6" class="form-aux"
                                                    data-label="Project Budget">
                                                    <option selected='selected'><?php echo $dMe['cidade']; ?></option>
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
                                            <input type="date" name="nascimento"
                                                value="<?php echo $dMe['nascimento']; ?>"
                                                class="form-fname form-element medium" placeholder="" tabindex="02">
                                        </div>
                                        <div class="column width-4">
                                            <span class="color-charcoal"><strong>Pai</strong> (Nome completo)</span>
                                            <input type="text" name="pai" value="<?php echo $dMe['pai']; ?>"
                                                class="form-fname form-element medium" placeholder="Nome do seu pai"
                                                tabindex="02">
                                        </div>
                                        <div class="column width-4">
                                            <span class="color-charcoal"><strong>Mãe</strong> (Nome completo)</span>
                                            <input type="text" name="mae" value="<?php echo $dMe['mae']; ?>"
                                                class="form-fname form-element medium" placeholder="Nome da sua mãe."
                                                tabindex="02">
                                        </div>
                                        <div class="column width-4">
                                            <span class=" color-charcoal weight-bold pb-10">
                                                Estado cívil
                                            </span>
                                            <div class="form-select form-element rounded medium">
                                                <select name="estadocivil" tabindex="6" class="form-aux"
                                                    data-label="Project Budget">
                                                    <option selected='selected'><?php echo $dMe['estadocivil']; ?>
                                                    </option>
                                                    <option>Solteiro (a)</option>
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
                                                <select name="grauescolar" tabindex="6" class="form-aux"
                                                    data-label="Project Budget">
                                                    <option selected='selected'><?php echo $dMe['grauescolar'];?>
                                                    </option>
                                                    <option>Doutor</option>
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
                                            <input type="text" name="formadoem" value="<?php echo $dMe['formadoem']; ?>"
                                                class="form-fname form-element medium" placeholder="Administração"
                                                tabindex="02">
                                        </div>
                                        <div class="column width-3">
                                            <span class="color-charcoal"><strong>Profissão</strong> atual</span>
                                            <input type="text" name="profissao" value="<?php echo $dMe['profissao']; ?>"
                                                class="form-fname form-element medium" placeholder="Administrador"
                                                tabindex="02">
                                        </div>
                                        <div class="column width-3">
                                            <span class="color-charcoal"><strong>Piso</strong> salarial (R$)</span>
                                            <input type="text" name="pisosalarial"
                                                value="<?php echo $pisosalarialdomembro; ?>"
                                                class="form-fname form-element medium" placeholder="1.395,00"
                                                tabindex="02">
                                        </div>
                                        <div class="column width-3">
                                            <span class="color-charcoal"><strong>Frequenta </strong>a Emanuel
                                                desde:</span>
                                            <input type="date" name="frequentaemanueldesde"
                                                value="<?php echo $dMe['frequentaaemanueldesde']; ?>"
                                                class="form-fname form-element medium" placeholder="16/01/2021"
                                                tabindex="02">
                                        </div>
                                        <div class="column width-6">
                                            <span class=" color-charcoal weight-bold pb-10">
                                                Você foi consagrado ao ministério como?
                                            </span>
                                            <div class="column width-12">
                                                <div class="field-wrapper pt-10 pb-10">
                                                    <input id="radio-membro"
                                                        <?php if(in_array("Membro", $ministerioprocurado)): echo "checked"; endif;?>
                                                        class="form-save-card-details form-element radio rounded no-margin-bottom"
                                                        value="Membro" name="ministerio[]" type="radio" tabindex="25">
                                                    <label for="radio-membro" class="radio-label no-margin-bottom">
                                                        Membro
                                                    </label>

                                                    <input id="radio-pastor"
                                                        <?php if(in_array("Pastor", $ministerioprocurado) OR in_array("Pastora", $ministerioprocurado)): echo "checked"; endif;?>
                                                        class="form-save-card-details form-element radio rounded no-margin-bottom"
                                                        value="Pastor (a)" name="ministerio[]" type="radio"
                                                        tabindex="25">
                                                    <label for="radio-pastor" class="radio-label no-margin-bottom">
                                                        Pastor (a)
                                                    </label>

                                                    <input id="radio-presbitero"
                                                        <?php if(in_array("Presbítero", $ministerioprocurado)): echo "checked"; endif;?>
                                                        class="form-save-card-details form-element radio rounded no-margin-bottom"
                                                        value="Presbítero" name="ministerio[]" type="radio"
                                                        tabindex="25">
                                                    <label for="radio-presbitero" class="radio-label no-margin-bottom">
                                                        Presbítero
                                                    </label>

                                                    <input id="radio-evangelista"
                                                        <?php if(in_array("Evangelista", $ministerioprocurado)): echo "checked"; endif;?>
                                                        class="form-save-card-details form-element radio rounded no-margin-bottom"
                                                        value="Evangelista" name="ministerio[]" type="radio"
                                                        tabindex="25">
                                                    <label for="radio-evangelista" class="radio-label no-margin-bottom">
                                                        Evangelista
                                                    </label>

                                                    <input id="radio-diacono"
                                                        <?php if(in_array("Diacono", $ministerioprocurado) OR in_array("Diáconisa", $ministerioprocurado)): echo "checked"; endif;?>
                                                        class="form-save-card-details form-element radio rounded no-margin-bottom"
                                                        value="Diacono (a)" name="ministerio[]" type="radio"
                                                        tabindex="25">
                                                    <label for="radio-diacono" class="radio-label no-margin-bottom">
                                                        Diacono (a)
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="column width-3">
                                            <span class="color-charcoal"><strong>(Outro)</strong> Consagrado
                                                como?</span>
                                            <input type="text" name="ministerio2" value=""
                                                class="form-fname form-element medium" placeholder="Líder de célula"
                                                tabindex="02">
                                        </div>

                                        <div class="column width-12 pb-10">
                                            <span class=" color-charcoal weight-bold pb-10">
                                                É líder de:
                                            </span>
                                            <div class="column width-12">
                                                <div class="field-wrapper pt-10 pb-10">
                                                    <input id="checkbox-coordenadorderede"
                                                        <?php if(in_array("Coordenador de rede", $ministerioprocurado) OR in_array("Coordenadora de rede", $ministerioprocurado)): echo "checked"; endif;?>
                                                        class="form-save-card-details form-element checkbox rounded no-margin-bottom"
                                                        value="Coordenador (a) de rede" name="ministerio[]"
                                                        type="checkbox" tabindex="25">
                                                    <label for="checkbox-coordenadorderede"
                                                        class="checkbox-label no-margin-bottom">
                                                        Coordenador (a) de rede
                                                    </label>

                                                    <input id="checkbox-supervisordecelula"
                                                        <?php if(in_array("Supervisor de célula", $ministerioprocurado) OR in_array("Supervisora de célula", $ministerioprocurado)): echo "checked"; endif;?>
                                                        class="form-save-card-details form-element checkbox rounded no-margin-bottom"
                                                        value="Supervisor (a) de célula" name="ministerio[]"
                                                        type="checkbox" tabindex="25">
                                                    <label for="checkbox-supervisordecelula"
                                                        class="checkbox-label no-margin-bottom">
                                                        Supervisor (a) de célula
                                                    </label>

                                                    <input id="checkbox-liderderede"
                                                        <?php if(in_array("Líder de rede", $ministerioprocurado)): echo "checked"; endif;?>
                                                        class="form-save-card-details form-element checkbox rounded no-margin-bottom"
                                                        value="Líder de rede" name="ministerio[]" type="checkbox"
                                                        tabindex="25">
                                                    <label for="checkbox-liderderede"
                                                        class="checkbox-label no-margin-bottom">
                                                        Líder de rede
                                                    </label>

                                                    <input id="checkbox-liderdecelula"
                                                        <?php if(in_array("Líder de célula", $ministerioprocurado)): echo "checked"; endif;?>
                                                        class="form-save-card-details form-element checkbox rounded no-margin-bottom"
                                                        value="Líder de célula" name="ministerio[]" type="checkbox"
                                                        tabindex="25">
                                                    <label for="checkbox-liderdecelula"
                                                        class="checkbox-label no-margin-bottom">
                                                        Líder de célula
                                                    </label>

                                                    <input id="checkbox-secretariodecelula"
                                                        <?php if(in_array("Secretário de célula", $ministerioprocurado)): echo "checked"; endif;?>
                                                        class="form-save-card-details form-element checkbox rounded no-margin-bottom"
                                                        value="Secretário (a) célula" name="ministerio[]"
                                                        type="checkbox" tabindex="25">
                                                    <label for="checkbox-secretariodecelula"
                                                        class="checkbox-label no-margin-bottom">
                                                        Secretário (a) de célula
                                                    </label>

                                                    <input id="checkbox-Tesoureirodecelula"
                                                        <?php if(in_array("Tesoureiro de célula", $ministerioprocurado)): echo "checked"; endif;?>
                                                        class="form-save-card-details form-element checkbox rounded no-margin-bottom"
                                                        value="Tesoureiro (a) célula" name="ministerio[]"
                                                        type="checkbox" tabindex="25">
                                                    <label for="checkbox-Tesoureirodecelula"
                                                        class="checkbox-label no-margin-bottom">
                                                        Tesoureiro (a) de célula
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="column width-3">
                                            <span class=" color-charcoal weight-bold pb-10">
                                                Quer servir a igreja?
                                            </span>
                                            <div class="form-select form-element rounded medium">
                                                <select name="desejaservirnaigreja" tabindex="6" class="form-aux"
                                                    data-label="Project Budget">
                                                    <option><?php echo $dMe['desejaservirnaigreja'];?></option>
                                                    <?php
                                                            if($dMe['desejaservirnaigreja'] === "Não"):
                                                        ?>
                                                    <option>Sim</option>
                                                    <?php
                                                            else:
                                                        ?>
                                                    <option>Não</option>
                                                    <?php
                                                            endif;
                                                        ?>
                                                </select>
                                            </div>
                                        </div>
                                        <!-- <div class="column width-3">
                                                <span class=" color-charcoal weight-bold pb-10">
                                                    Como?
                                                </span>
                                                <div class="form-select form-element rounded medium">
                                                    <select name="serviremqualarea" tabindex="6" class="form-aux" data-label="Project Budget"> 
                                                        <option selected='selected'>Diaconia</option>
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
                                                    </select>
                                                </div>
                                            </div> -->

                                        <div class="column width-6">
                                            <span class=" color-charcoal weight-bold pb-10">
                                                É batizado com os dons do Espírito Santo?
                                            </span>
                                            <div class="form-select form-element rounded medium">
                                                <select name="batizadonoespiritosanto" tabindex="6" class="form-aux"
                                                    data-label="Project Budget">
                                                    <option><?php echo $dMe['batizadonoespiritosanto'];?></option>
                                                    <?php
                                                            if($dMe['batizadonoespiritosanto'] === "Não"):
                                                        ?>
                                                    <option>Sim</option>
                                                    <?php
                                                            else:
                                                        ?>
                                                    <option>Não</option>
                                                    <?php
                                                            endif;
                                                        ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="column width-3 border-red rounded">
                                            <span class=" color-charcoal weight-bold pb-10">
                                                Status do <strong>membro</strong>.
                                            </span>
                                            <div class="form-select form-element rounded medium">
                                                <select name="statusdomembro" tabindex="6" class="form-aux"
                                                    data-label="Project Budget">
                                                    <option selected='selected'><?php echo $dMe['statusdomembro'];?>
                                                    </option>
                                                    <?php
                                                            if($dMe['statusdomembro'] === "Ativo"):
                                                        ?>
                                                    <option>Advertido</option>
                                                    <option>Excluído</option>
                                                    <?php
                                                            elseif($dMe['statusdomembro'] === "Advertido"):
                                                        ?>
                                                    <option>Ativo</option>
                                                    <option>Excluído</option>
                                                    <?php
                                                            elseif($dMe['statusdomembro'] === "Excluído"):
                                                        ?>
                                                    <option>Ativo</option>
                                                    <option>Advertido</option>
                                                    <?php
                                                            elseif($dMe['statusdomembro'] === "SOLICITADO"):
                                                        ?>
                                                    <option>Ativo</option>
                                                    <option>Advertido</option>
                                                    <?php
                                                            endif;
                                                        ?>
                                                </select>
                                            </div>
                                        </div>
                                        <?php
                                                if($funcaoadministrativa === "Presidente"):
                                            ?>
                                        <div class="column width-12 border-orange rounded">
                                            <span class=" color-charcoal weight-bold pb-10">
                                                Função administrativa do <strong>membro</strong>.
                                            </span>
                                            <div class="form-select form-element rounded medium">
                                                <select name="funcaoadministrativa" tabindex="6" class="form-aux"
                                                    data-label="Project Budget">
                                                    <option selected='selected'>
                                                        <?php echo $dMe['funcaoadministrativa'];?></option>
                                                    <?php
                                                            if($dMe['funcaoadministrativa'] === "Presidente"):
                                                        ?>
                                                    <option>Presidente</option>
                                                    <option>Vice Presidente</option>
                                                    <option>Tesoureiro (a)</option>
                                                    <option>I Secretário (a)</option>
                                                    <option>II Vice Presidente</option>
                                                    <option>II Tesoureiro (a)</option>
                                                    <option>II Secretário (a)</option>
                                                    <?php
                                                            elseif($dMe['funcaoadministrativa'] === "Vice Presidente"):
                                                        ?>
                                                    <option>II Vice Presidente</option>
                                                    <option>II Tesoureiro (a)</option>
                                                    <option>II Secretário (a)</option>
                                                    <?php
                                                            elseif($dMe['funcaoadministrativa'] === "Tesoureiro" OR $dMe['funcaoadministrativa'] === "Tesoureira"):
                                                        ?>
                                                    <option>Presidente</option>
                                                    <option>Vice Presidente</option>
                                                    <option>Tesoureiro (a)</option>
                                                    <option>I Secretário (a)</option>
                                                    <option>II Vice Presidente</option>
                                                    <option>II Tesoureiro (a)</option>
                                                    <option>II Secretário (a)</option>
                                                    <?php
                                                            else:
                                                        ?>
                                                    <option>Presidente</option>
                                                    <option>Vice Presidente</option>
                                                    <option>Tesoureiro (a)</option>
                                                    <option>I Secretário (a)</option>
                                                    <option>II Vice Presidente</option>
                                                    <option>II Tesoureiro (a)</option>
                                                    <option>II Secretário (a)</option>
                                                    <?php
                                                            endif;
                                                        ?>
                                                </select>
                                            </div>
                                        </div>
                                        <?php
                                                endif;
                                            ?>
                                        <div class="column width-12">
                                            <span class=" color-charcoal weight-bold pb-10">
                                                Na rede de?
                                            </span>
                                            <div class="column full-width left">
                                                <div class="field-wrapper pb-10">
                                                    <div class="column width-2">
                                                        <input id="checkbox-missoes"
                                                            <?php if(in_array("Missões e Social", $serviremqualareaprocurado)): echo "checked"; endif;?>
                                                            class="form-save-card-details form-element checkbox rounded no-margin-bottom"
                                                            value="Missões e Social" name="serviremqualarea[]"
                                                            type="checkbox" tabindex="25" />
                                                        <label for="checkbox-missoes"
                                                            class="left checkbox-label no-margin-bottom">
                                                            Missões e Social
                                                        </label>
                                                    </div>
                                                    <div class="column width-2">
                                                        <input id="checkbox-kids"
                                                            <?php if(in_array("IBE Kids", $serviremqualareaprocurado)): echo "checked"; endif;?>
                                                            class="form-save-card-details form-element checkbox rounded no-margin-bottom"
                                                            value="IBE Kids" name="serviremqualarea[]" type="checkbox"
                                                            tabindex="25" />
                                                        <label for="checkbox-kids"
                                                            class="left checkbox-label no-margin-bottom">
                                                            IBE Kids
                                                        </label>
                                                    </div>
                                                    <div class="column width-2">
                                                        <input id="checkbox-homens"
                                                            <?php if(in_array("Homens", $serviremqualareaprocurado)): echo "checked"; endif;?>
                                                            class="form-save-card-details form-element checkbox rounded no-margin-bottom"
                                                            value="Homens" name="serviremqualarea[]" type="checkbox"
                                                            tabindex="25" />
                                                        <label for="checkbox-homens"
                                                            class="left checkbox-label no-margin-bottom">
                                                            Homens
                                                        </label>
                                                    </div>
                                                    <div class="column width-2">
                                                        <input id="checkbox-mulheres"
                                                            <?php if(in_array("Mulheres", $serviremqualareaprocurado)): echo "checked"; endif;?>
                                                            class="form-save-card-details form-element checkbox rounded no-margin-bottom"
                                                            value="Mulheres" name="serviremqualarea[]" type="checkbox"
                                                            tabindex="25" />
                                                        <label for="checkbox-mulheres"
                                                            class="left checkbox-label no-margin-bottom">
                                                            Mulheres
                                                        </label>
                                                    </div>
                                                    <div class="column width-2">
                                                        <input id="checkbox-Jovens"
                                                            <?php if(in_array("Jovens", $serviremqualareaprocurado)): echo "checked"; endif;?>
                                                            class="form-save-card-details form-element checkbox rounded no-margin-bottom"
                                                            value="Jovens" name="serviremqualarea[]" type="checkbox"
                                                            tabindex="25" />
                                                        <label for="checkbox-Jovens"
                                                            class="left checkbox-label no-margin-bottom">
                                                            Jovens
                                                        </label>
                                                    </div>
                                                    <div class="column width-2">
                                                        <input id="checkbox-adolescentes"
                                                            <?php if(in_array("Adolcescentes", $serviremqualareaprocurado)): echo "checked"; endif;?>
                                                            class="form-save-card-details form-element checkbox rounded no-margin-bottom"
                                                            value="Adolescentes" name="serviremqualarea[]"
                                                            type="checkbox" tabindex="25" />
                                                        <label for="checkbox-adolescentes"
                                                            class="left checkbox-label no-margin-bottom">
                                                            Adolescentes
                                                        </label>
                                                    </div>
                                                    <div class="column width-2">
                                                        <input id="checkbox-infraestrutura"
                                                            <?php if(in_array("Infraestrutura", $serviremqualareaprocurado)): echo "checked"; endif;?>
                                                            class="form-save-card-details form-element checkbox rounded no-margin-bottom"
                                                            value="Infraestrutura" name="serviremqualarea[]"
                                                            type="checkbox" tabindex="25" />
                                                        <label for="checkbox-infraestrutura"
                                                            class="left checkbox-label no-margin-bottom">
                                                            Infraestrutura
                                                        </label>
                                                    </div>
                                                    <div class="column width-2">
                                                        <input id="checkbox-midiasecomunicacao"
                                                            <?php if(in_array("Mídias", $serviremqualareaprocurado)): echo "checked"; endif;?>
                                                            class="form-save-card-details form-element checkbox rounded no-margin-bottom"
                                                            value="Mídias" name="serviremqualarea[]" type="checkbox"
                                                            tabindex="25" />
                                                        <label for="checkbox-midiasecomunicacao"
                                                            class="left checkbox-label no-margin-bottom">
                                                            Mídias
                                                        </label>
                                                    </div>
                                                    <div class="column width-2">
                                                        <input id="checkbox-negocioeempreendimentos"
                                                            <?php if(in_array("Business", $serviremqualareaprocurado)): echo "checked"; endif;?>
                                                            class="form-save-card-details form-element checkbox rounded no-margin-bottom"
                                                            value="Business" name="serviremqualarea[]" type="checkbox"
                                                            tabindex="25" />
                                                        <label for="checkbox-negocioeempreendimentos"
                                                            class="left checkbox-label no-margin-bottom">
                                                            Business
                                                        </label>
                                                    </div>

                                                    <div class="column width-2">
                                                        <input id="checkbox-danca"
                                                            <?php if(in_array("Dança", $serviremqualareaprocurado)): echo "checked"; endif;?>
                                                            class="form-save-card-details form-element checkbox rounded no-margin-bottom"
                                                            value="Dança" name="serviremqualarea[]" type="checkbox"
                                                            tabindex="25" />
                                                        <label for="checkbox-danca"
                                                            class="left checkbox-label no-margin-bottom">
                                                            Dança
                                                        </label>
                                                    </div>
                                                    <div class="column width-2">
                                                        <input id="checkbox-celulas"
                                                            <?php if(in_array("Células", $serviremqualareaprocurado)): echo "checked"; endif;?>
                                                            class="form-save-card-details form-element checkbox rounded no-margin-bottom"
                                                            value="Células" name="serviremqualarea[]" type="checkbox"
                                                            tabindex="25" />
                                                        <label for="checkbox-celulas"
                                                            class="left checkbox-label no-margin-bottom">
                                                            Células
                                                        </label>
                                                    </div>
                                                    <div class="column width-2">
                                                        <input id="checkbox-educacao"
                                                            <?php if(in_array("Educação", $serviremqualareaprocurado)): echo "checked"; endif;?>
                                                            class="form-save-card-details form-element checkbox rounded no-margin-bottom"
                                                            value="Educação" name="serviremqualarea[]" type="checkbox"
                                                            tabindex="25" />
                                                        <label for="checkbox-educacao"
                                                            class="left checkbox-label no-margin-bottom">
                                                            Educação
                                                        </label>
                                                    </div>
                                                    <div class="column width-2">
                                                        <input id="checkbox-musica"
                                                            <?php if(in_array("Música", $serviremqualareaprocurado)): echo "checked"; endif;?>
                                                            class="form-save-card-details form-element checkbox rounded no-margin-bottom"
                                                            value="Música" name="serviremqualarea[]" type="checkbox"
                                                            tabindex="25" />
                                                        <label for="checkbox-musica"
                                                            class="left checkbox-label no-margin-bottom">
                                                            Música
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="column width-12">
                                            <span class=" color-charcoal weight-bold pb-10">
                                                Pertence a qual <strong>campus</strong>?
                                            </span>
                                            <div class="form-select form-element rounded medium">
                                                <select name="campus" tabindex="6" class="form-aux"
                                                    data-label="Project Budget">
                                                    <?php
                                                            $bCampus="SELECT nomedocampus, matricula FROM campus WHERE areadocampus='$estadodomembro' GROUP BY matricula ORDER BY nomedocampus ASC, municipio ASC";
                                                                $rCampus=mysqli_query($con, $bCampus);
                                                                while($dCampus=mysqli_fetch_array($rCampus)):
                                                        ?>
                                                    <option
                                                        value="<?php echo $dCampus['nomedocampus'].'~'.$dCampus['matricula'];?>">
                                                        <?php echo $dCampus['nomedocampus'].' ('.$dCampus['matricula'].')';?>
                                                    </option>
                                                    <?php
                                                            endwhile;
                                                        ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="column width-3">
                                            <span class=" color-charcoal weight-bold pb-10">
                                                Pertence a qual célula?
                                            </span>
                                            <div class="form-select form-element rounded medium">
                                                <select name="pertenceaqualcelula" tabindex="6" class="form-aux"
                                                    data-label="Project Budget">
                                                    <?php
                                                            $bCelulas="SELECT celula, matricula FROM celulas WHERE estado='$estadodomembro' GROUP BY matricula ORDER BY celula ASC, municipio ASC";
                                                                $rCelulas=mysqli_query($con, $bCelulas);
                                                                while($dCelulas=mysqli_fetch_array($rCelulas)):
                                                        ?>
                                                    <option
                                                        value="<?php echo $dCelulas['celula'].'~'.$dCelulas['matricula'];?>">
                                                        <?php echo $dCelulas['celula'].' ('.$dCelulas['matricula'].')';?>
                                                    </option>
                                                    <?php
                                                            endwhile;
                                                        ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="column width-3">
                                            <span class="color-charcoal">Nº de <strong>failiares</strong> na
                                                Emanuel</span>
                                            <input type="number" name="numerodefamiliaresnaemanuel"
                                                value="<?php echo $dMe['numerodefamiliaresnaemanuel']; ?>"
                                                class="form-fname form-element medium" placeholder="3" tabindex="02">
                                        </div>
                                        <div class="column width-3">
                                            <span class="color-charcoal">Data do <strong>batismo</strong> </span>
                                            <input type="date" name="datadobatismo"
                                                value="<?php echo $dMe['datadobatismo']; ?>"
                                                class="form-fname form-element medium" placeholder="01/04/2020"
                                                tabindex="02">
                                        </div>
                                        <div class="column width-3">
                                            <span class="color-charcoal">Igreja do <strong>batismo</strong></span>
                                            <input type="text" name="igrejadebatismo"
                                                value="<?php echo $dMe['igrejadebatismo']; ?>"
                                                class="form-fname form-element medium" placeholder="Igreja Emanuel"
                                                tabindex="02">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="column width-3">
                                            <span class=" color-charcoal weight-bold pb-10">
                                                Sua principal <strong>mídia social</strong>.
                                            </span>
                                            <div class="form-select form-element rounded medium">
                                                <select name="midiasocial1" tabindex="6" class="form-aux"
                                                    data-label="Project Budget">
                                                    <option selected='selected'><?php echo $dMe['midiasocial1'];?>
                                                    </option>
                                                    <option value="facebook">Facebook</option>
                                                    <option value="instagram">Instagram</option>
                                                    <option value="youtube">Youtube</option>
                                                    <option value="twitter">Twitter</option>
                                                    <option value="tiktok">TikTok</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="column width-9">
                                            <span class="color-charcoal">Link do seu perfil nessa mídia <strong>social
                                                    </strongspan /label>
                                                    <input type="text" name="link1"
                                                        value="<?php $link1 = base64_decode($dMe['link1']); echo base64_decode($link1); ?>"
                                                        class="form-fname form-element medium"
                                                        placeholder="instagram.com/emanueligrejabr" tabindex="02">
                                        </div>
                                        <div class="column width-3">
                                            <span class=" color-charcoal weight-bold pb-10">
                                                Segunda <strong>Mídia social</strong>.
                                            </span>
                                            <div class="form-select form-element rounded medium">
                                                <select name="midiasocial2" tabindex="6" class="form-aux"
                                                    data-label="Project Budget">
                                                    <option selected='selected'><?php echo $dMe['midiasocial2'];?>
                                                    </option>
                                                    <option value="facebook">Facebook</option>
                                                    <option value="instagram">Instagram</option>
                                                    <option value="youtube">Youtube</option>
                                                    <option value="twitter">Twitter</option>
                                                    <option value="tiktok">TikTok</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="column width-9">
                                            <span class="color-charcoal">Link do seu perfil nessa mídia <strong>social
                                                    </strongspan /label>
                                                    <input type="text" name="link2"
                                                        value="<?php $link2 = base64_decode($dMe['link2']); echo base64_decode($link2); ?>"
                                                        class="form-fname form-element medium"
                                                        placeholder="youtube.com/emanueligrejabr" tabindex="02">
                                        </div>
                                        <div class="column width-3">
                                            <span class=" color-charcoal weight-bold pb-10">
                                                Terceira <strong>Mídia social</strong>.
                                            </span>
                                            <div class="form-select form-element rounded medium">
                                                <select name="midiasocial3" tabindex="6" class="form-aux"
                                                    data-label="Project Budget">
                                                    <option selected='selected'><?php echo $dMe['midiasocial3'];?>
                                                    </option>
                                                    <option value="facebook">Facebook</option>
                                                    <option value="instagram">Instagram</option>
                                                    <option value="youtube">Youtube</option>
                                                    <option value="twitter">Twitter</option>
                                                    <option value="tiktok">TikTok</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="column width-9">
                                            <span class="color-charcoal">Link do seu perfil nessa mídia <strong>social
                                                    </strongspan /label>
                                                    <input type="text" name="link3"
                                                        value="<?php $link3 = base64_decode($dMe['link3']); echo base64_decode($link3); ?>"
                                                        class="form-fname form-element medium"
                                                        placeholder="twitter.com/emanueligrejabr" tabindex="02">
                                        </div>
                                        <div class="column width-3">
                                            <span class=" color-charcoal weight-bold pb-10">
                                                Quarta <strong>Mídia social</strong>.
                                            </span>
                                            <div class="form-select form-element rounded medium">
                                                <select name="midiasocial4" tabindex="6" class="form-aux"
                                                    data-label="Project Budget">
                                                    <option selected='selected'><?php echo $dMe['midiasocial4'];?>
                                                    </option>
                                                    <option value="facebook">Facebook</option>
                                                    <option value="instagram">Instagram</option>
                                                    <option value="youtube">Youtube</option>
                                                    <option value="twitter">Twitter</option>
                                                    <option value="tiktok">TikTok</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="column width-9">
                                            <span class="color-charcoal">Link do seu perfil nessa mídia <strong>social
                                                    </strongspan /label>
                                                    <input type="text" name="link4"
                                                        value="<?php $link4 = base64_decode($dMe['link4']); echo base64_decode($link4); ?>"
                                                        class="form-fname form-element medium"
                                                        placeholder="faecebook.com/emanueligrejabr" tabindex="02">
                                        </div>
                                        <div class="column width-12">
                                            <span class="color-charcoal">Pense que está é sua BIO e nos diga: Quem é
                                                você? <strong>(Esse texto pode ficar vísivel na página ministerial do
                                                    site)</strong><span>
                                                    <textarea type="text" name="descricaodomembro"
                                                        class="form-fname form-element medium"
                                                        placeholder="Pastor, Administrador, Pós graduado em Finanças pela FGV, casado e apaixonado por apresentar a pessoa de Cristo."
                                                        tabindex="02"><?php echo $dMe['descricaodomembro']; ?></textarea>
                                        </div>
                                        <div class="column width-12">
                                            <span class="color-black">
                                                AO FINALIZAR VOCÊ CONFIRMA QUE PREENCHEU ESTE PRÉ CADASTRO DE MEMBRO
                                                ESPONTÂNEAMENTE, QUE QUER SER <strong>DECLARADO MEMBRO DA EMANUEL
                                                    (IGREJA BATISTA EMANUEL)</strong> E QUE ESTÁ CIENTE DE QUE AO
                                                CONFIRMAR ESTE FORMULÁRIO AUTORIZARÁ O USO DA SUA IMAGEM EM TODOS OS
                                                VEÍCULOS DE DIVULGAÇÃO, COMUNICAÇÃO E PUBLICIDADE PELA EMANUEL.
                                            </span>

                                            <div class="field-wrapper pt-10 pb-10">
                                                <input id="radio-sim" class="form-element radio rounded"
                                                    name="declaracao" value="SIM" type="radio" checked required>
                                                <label for="radio-sim" class="radio-label color-black" tabindex="6">
                                                    Sim
                                                </label>
                                            </div>
                                        </div>
                                        <div class="column width-12 border-theme pt-20 pb-20">
                                            <span class="center color-charcoal">PARA FINALIZAR <strong>RESOLVA O CÁLCULO
                                                    ABAIXOspan/strong></label>
                                                    <div class="field-wrapper">
                                                        <div class="column width-3 pt-20 offset-2">
                                                            <h3 class="right">
                                                                <?php echo $v1.' + '.$v2.' = ';?>
                                                            </h3>
                                                        </div>
                                                        <div class="column width-6">
                                                            <div class="field-wrapper">
                                                                <input type="hidden" name="soma1"
                                                                    class="form-name form-element medium"
                                                                    value="<?php $somaa = $v1+$v2; echo $somaa;?>">
                                                                <input type="number" name="soma"
                                                                    class="form-name form-element medium"
                                                                    placeholder="Informar valor" tabindex="03" required>
                                                            </div>
                                                        </div>
                                                    </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="column width-12 pt-20">
                                            <button tabindex="04" type="submit" name="btn-cadastrar"
                                                class="form-submit button small rounded bkg-blue bkg-hover-blue-light color-white color-hover-white hard-shadow text-medium">Finalizar</button>
                                            <button tabindex="05" type="submit" name="btn-excluir"
                                                class="form-submit button small rounded bkg-red bkg-hover-red color-white color-hover-white hard-shadow text-medium">Excluir
                                                cadastro</button>
                                            <!-- <a tabindex="05" href="<?php echo $loginUrl; ?>" class="form-submit button small bkg-facebook bkg-hover-facebook rounded color-white color-hover-white hard-shadow">
                                                    <span class="icon-facebook left"></span>  Entrar com Facebook
                                                </a> -->
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Content End -->
            <div class="printable"></div>
        </div>

        <!-- Footer -->
        <footer class="footer footer-blue">
            <? include "./_footer_top.php"; ?>
        </footer>
        <!-- Footer End -->

        </div>
        </div>
        <? include "./_script.php"; ?>

        <script src="js/printThis.js"></script>
        <?php
        for($print=0; $print < count($prints); $print++){
    ?>
        <script type="text/javascript">
        $(document).ready(function() {
            $("#btnPrint<?php echo $prints[$print];?>").click(function() {
                //get the modal box content and load it into the printable div
                $(".printable").html($("#divpublicacao<?php echo $prints[$print];?>").html());
                $(".printable").printThis();
            });
        });
        </script>
        <?php
        }
    ?>
    </body>

</html>