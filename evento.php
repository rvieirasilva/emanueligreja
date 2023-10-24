<!DOCTYPE html>
<?php
	session_start();
	if(!empty($_GET['lng'])):
		$idioma = $_GET['lng'];
	elseif(empty($_GET['lng']) OR !isset($_GET['lng'])):
		$idioma = 'pt-BR';
    endif;
    
    include "./_con.php";
    if(!isset($_SESSION['inscricaoevento'])):
        include "./_configuracao.php";
    else:
        include "./configDoacoes.php";
    endif;

    // if(isset($_SESSION["lideranca"])):
    //     include "protectcolaborador.php";	
    // elseif(isset($_SESSION["membro"])):
    //     include "protectusuario.php";
    // else:
    //     session_destroy();
    //     header("location:index");
	// endif;
    
    
    $ref=$_GET['pos'];
    
	
	$bMiniaturas="SELECT miniatura FROM agenda WHERE NOT (miniatura = '') AND codigodoevento='$ref' GROUP BY miniatura ORDER BY id ASC ";
	$rMini=mysqli_query($con, $bMiniaturas);
	$nMini = mysqli_num_rows($rMini);
	while($dMiniaturas=mysqli_fetch_array($rMini)):
		$multiminiaturas[] = $dMiniaturas['miniatura'];
	endwhile;


	$buscar="SELECT codigodoevento, evento, datadoinicio, datadotermino, video, capa, miniatura, anexo, descricaodoevento, valor, emailtrello FROM agenda WHERE codigodoevento='$ref'";
	  $result=mysqli_query($con, $buscar);
		$texto=mysqli_fetch_array($result);

	$titulo=$texto['evento'];
    $evento=$texto['evento'];
    $codigodoevento=$texto['codigodoevento'];
    $emailtrello=$texto['emailtrello'];
    
    if(!isset($_SESSION['valordoevento'])):
        $valordoevento=$texto['valor'];
    else:
        $valordoevento=$_SESSION['valordoevento'];
    endif;

    if($valordoevento >0):
        $statusdopagamento="AGUARDADNDO PAGAMENTO";
    else:
        $statusdopagamento="PAGO (Gratuito)";
    endif;

    if(isset($_POST['btn-cadastrar'])):
        $v1 = mysqli_escape_string($con, $_POST['v1']); //Valor passado pelo formulário.
        $v2 = mysqli_escape_string($con, $_POST['v2']); //Valor passado pelo formulário.
    
        $soma = $v1 + $v2;
        $s = $_POST['soma1'];
        $s2 = $_POST['soma'];
		if($s2 === $s):
            $bCod="SELECT id FROM agendainscritos ORDER BY id DESC LIMIT 1";
              $rCod=mysqli_query($con, $bCod);
                $dCod=mysqli_fetch_array($rCod);

            $codigodainscricao = date('ymd').substr($dCod['id'], 0, 4).mt_rand(0000,9999);
            $quantidadedeingressos=mysqli_escape_string($con, $_POST['quantidadedeingressos']);
            $nome=mysqli_escape_string($con, $_POST['nome']);
            $sobrenome=mysqli_escape_string($con, $_POST['sobrenome']);
             $nomecompletoinscrito=$nome.' '.$sobrenome;
            $email=mysqli_escape_string($con, $_POST['email']);
            $sexo=mysqli_escape_string($con, $_POST['sexo']);
            $conheceu=mysqli_escape_string($con, $_POST['conheceu']);
            $telefonemovel=mysqli_escape_string($con, $_POST['telefonemovel']);
            $whatsapp=mysqli_escape_string($con, $_POST['whatsapp']);
            $declaracao=mysqli_escape_string($con, $_POST['declaracao']);
            $alimento=mysqli_escape_string($con, $_POST['alimento']);
            $soma1=mysqli_escape_string($con, $_POST['soma1']);
            $soma=mysqli_escape_string($con, $_POST['soma']);

            $nInscritos = "SELECT sum(quantidadedeingressos) as totaldeinscritos FROM agendainscritos WHERE evento='$evento' AND codigodoevento='$codigodoevento'";
              $rIns=mysqli_query($con, $nInscritos);
                $nIns=mysqli_num_rows($rIns);
                $dIns=mysqli_fetch_array($rIns);

            $InscritosTotal=$dIns['totaldeinscritos']; if($InscritosTotal < 1): $InscritosTotal=1; endif;

            $bDupl="SELECT id FROM agendainscritos WHERE evento='$evento' AND codigodoevento='$codigodoevento' AND nome='$nome' AND sobrenome='$sobrenome' ORDER BY id DESC LIMIT 1";
              $rDupl=mysqli_query($con, $bDupl);
                $nDupl=mysqli_num_rows($rDupl);

            if($nDupl > 0):
                $_SESSION['cordamensagem']="red";
                $_SESSION['mensagem']="Você já está inscrito neste evento.";
            else:
                $inEvento="INSERT INTO agendainscritos (dia, mes, ano, codigodainscricao, fotodeperfil, nome, sobrenome, quantidadedeingressos, nascimento, sexo, email, cpf, rg, telefoneresidencial, telefonemovel, whatsapp, endereco, cep, uf, cidade, rededepartamento, declaracaodemembro, conheceucomo, senha, validaate, evento, codigodoevento, valorpago, statusdopagamento, formadepagamento, parcelas, qrcode, alimento) VALUES('$dia', '$mesnumero', '$ano', '$codigodainscricao', '', '$nome', '$sobrenome', '$quantidadedeingressos', '', '$sexo', '$email', '', '', '', '$telefonemovel', '$whatsapp', '', '', '', '', '', '', '', '', '', '$evento', '$codigodoevento', '$valordoevento', '$statusdopagamento', '', '', '', '$alimento')";
                if(mysqli_query($con, $inEvento)):
                    if($valordoevento > 0):
                        $_SESSION['codigodainscricao']=$codigodainscricao;
                        $_SESSION['inscricaoevento']=true;
                        $_SESSION['valordoevento']=$valordoevento*$quantidadedeingressos;
                    else:
                        //Email teste
                        include "./_enviaremailPay.php";
                        $mail->addAddress("$email", "$nome");
                        $mail->Subject = "$evento, sua inscrição. Emanuel";
                        $mail->Body    = "Olá $nome. 
                                        <p>Seu cadastro no evento: <strong>$evento</strong> foi realizado com sucesso, caso o evento tenha alguma valor de ingresso finalize a etapa do pagamento. Este é o seu código de inscrição: $codigodainscricao e você se <strong>comprometeu em levar para o evento este alimento; $alimento</strong></p>
                                        <p<h1>Jesus Cristo é o Senhor!</p>
                                        ";
                        $mail->AltBody = "Olá $nome. 
                                        <p>Seu cadastro no evento: $evento foi realizado com sucesso, caso o evento tenha alguma valor de ingresso finalize a etapa do pagamento. Este é o seu código de inscrição: $codigodainscricao e você se <strong>comprometeu em levar para o evento este alimento; $alimento</strong></p>
                                        <p<h1>Jesus Cristo é o Senhor!</p>"; // Não é visualizado pelo usuário!
                        if($mail->send()):
                            include "./_enviaremailPay.php";
                            $mail->addAddress("$emailtrello", "$nIns Inscrito - $nomecompletoinscrito");
                            $mail->Subject = "novo Inscrito: $nomecompletoinscrito (Nº $nIns; Total> $InscritosTotal)";
                            $mail->Body    = "$nomecompletoinscrito, Selecionou $quantidadedeingressos - Telefone móvel: $telefonemovel, WhatsApp: ($whatsapp)";
                            $mail->AltBody = "$nomecompletoinscrito, Selecionou $quantidadedeingressos - Telefone móvel: $telefonemovel, WhatsApp: ($whatsapp)"; // Não é visualizado pelo usuário!
                            $mail->send();
                        endif;

                        $_SESSION['cordamensagem']='green';
                        $_SESSION['mensagem']='Sua inscrição foi realizada com sucesso.';
                        // header("location:./evento?pos=$ref");
                    endif;
                else:
                    $_SESSION['cordamensagem']='red';
                    $_SESSION['mensagem']='Erro ao registrar sua inscrição no evento, <strong>verifique sua conexão e tente novamente mais tarde</strong>.';
                endif;
            endif;
        else:
			//captcha invalido
            $_SESSION['bot'] = true;
            $_SESSION['cordamensagem']='red';
            $_SESSION['mensagem']='Erro no reCAPTCHA. Informe o valor correto da soma.';
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
                                                        <h1 class="mb-0"><?php echo $texto['evento'];?></h1>
                                                        <!-- <p class="text-large color-charcoal mb-0 mb-mobile-20">
                                                            Acesse sua área de membro para ter acesso aos <strong>eventos exclusivos</strong> para os membros da Emanuel.
                                                        </p> -->
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
                                <div class="column width-5 content-inner">
                                    <article id="divpublicacao" class="post">
                                        <div class="post-content with-background">
                                            <?php
                                                if(!empty($texto['video'])):
                                            ?>
                                                <?php
                                                    //Verificar se é um vídeo interno ou do youtube.
                                                    $videoCarregado = $texto['video'];
                                                    $procurar = "youtube.com/";

                                                    $linkVideo = stripos($videoCarregado, $procurar);

                                                    if(stripos($videoCarregado, $procurar) !== false):
                                                ?>
                                                    <div class="post-media video-container pt-0">
                                                        <iframe src="<?php echo $texto['video'];?>" width="350" height="450"></iframe>
                                                    </div>
                                            
                                                <?php
                                                    else:
                                                ?>
                                                    <video poster="<?php echo $texto['capa'];?>" class="bkg-black" controls="" muted="" style="width:100%; height:100%;" height="200">
                                                        <source type="video/mp4" src="<?php echo $texto['video'];?>" />
                                                        <source type="video/webm" src="<?php echo $texto['video'];?>" />
                                                    </video>
                                                <?php
                                                    endif;
                                                ?>	
                                            <?php
                                                endif;
                                            ?>	
                                            
                                            <?php
                                                    if($nMini > 1):
                                            ?> 
                                                <div class="tm-slider-container recent-slider" data-nav-dark data-carousel-visible-slides="2" data-nav-keyboard="true" data-nav-pagination="false" data-nav-show-on-hover="false" data-carousel-1024="2">
                                                    <ul class="tms-slides">	
                                                        <?php
                                                            foreach($multiminiaturas as $miniaturasview) {
                                                        ?>
                                                            <li class="tms-slide">
                                                                <div class="thumbnail rounded">
                                                                    <img data-src="<?php echo $miniaturasview; ?>" src="images/blank.png" alt=""/>
                                                                </div>
                                                            </li>
                                                        <?php
                                                            }
                                                        ?>
                                                    </ul>
                                                </div>
                                            <?php
                                                else:
                                            ?>
                                                <div class="thumbnail full-width center">
                                                    <?php
                                                        if(!empty($texto['capa'])):
                                                    ?> 
                                                        <img src="<?php echo $texto['capa']; ?>" alt="">
                                                    <?php
                                                        elseif(!empty($texto['miniatura'])):
                                                    ?> 
                                                        <img src="<?php echo $texto['miniatura']; ?>" alt="">
                                                    <?php
                                                        endif;
                                                    ?> 
                                                    <div class="caption-below">
                                                        <?php echo $texto['evento'];?>
                                                    </div>
                                                </div>
                                            <?php
                                                //endif;
                                                endif;
                                            ?> 
                                            
                                            <?php
                                                //if(!empty($texto['anexo'])):
                                            ?>
                                                <!-- <div class="column width-6">
                                                    <?php	
                                                        
                                                        // $anexoQRCode = $texto['anexo'];
                                                        // $urlAnexo = $texto['anexo'];
                                                        //     $nomedoarquivoanexo = explode('/', $anexoQRCode);
                                                        //     $nomedoarquivoanexo = $nomedoarquivoanexo[3];					
                                                    ?><!--
                                                    <label class="center">
                                                        Baixar anexo
                                                    </label>->
                                                    
                                                </div> -->
                                                <!-- <div class="column width-12">
                                                    <a href="<?php echo $texto['anexo'];?>" class="column width-12 rounded hard-shadow pt-10 pb-20 button bkg-blue border-hover-blue color-white color-hover-blue small download-link">
                                                        <span class="icon-download color-white color-hover-blue"></span>
                                                        Baixar anexo.
                                                    </a>
                                                </div> -->
                                            <?php
                                                //endif;
                                            ?>
                                            <br>
                                            <?php
                                                if(!empty($texto['descricaodoevento'])):
                                            ?>
                                                <font color="#000000" align="justify"><!--</font> style="font-family:Open Sans, Times New Roman">tms-caption -->
                                                    <p class="text-medium">
                                                        <?php echo str_replace('<blockquote>', '<blockquote class="large box xlarge full-width bkg-grey-ultralight">', $texto['descricaodoevento']);?>
                                                    </p>
                                                </font>
                                            <?php
                                                endif;
                                            ?> 
                                            <div class="full-width pt-20">
                                                <button type="button" id="btnPrintb" class="column full width button small border-blue border-hover-blue color-charcoal color-hover-charcoal hard-shadow shadow right">
                                                    Clique para imprimir
                                                </button>
                                            </div>
                                        </div>
                                            
                                    </article>
                                    
                                </div>
                                <div class="column width-7">
                                    <div>
                                        <div class="box rounded medium shadow bkg-white border-blue">
                                            <h5><strong>Informações</strong>. <span class="text-medium">Este evento termina em: <? echo date(('d/m/Y'), strtotime($texto['datadotermino']));?></span></h5>
                                            <p class="pb-0"><strong>Ingresso: R$ </strong><? echo $texto['valor'];?></p>
                                            <label class="text-medium pt-0">O pagamento acontecerá após sua inscrição no formulário abaixo, na opção; <strong>cartão de crédito</strong></label>
                                        </div>
                                        <? if(!isset($_SESSION['inscricaoevento']) AND $dataeng <= $texto['datadotermino']):?>
                                        <div class="signup-box box rounded medium shadow bkg-white border-blue">
                                            <h3><strong>Inscrever-se</strong> no evento.</h3>
                                            <p class="mb-20"></p>
                                            <div class="form">
                                                <form class="form" charset="UTF-8" action="<? echo $_SERVER['REQUEST_URI'];?>" method="post">
                                                    <div class="row">
                                                        <div class="column width-12">
                                                            <label class="color-charcoal"><strong>QUANTIDADE DE INGRESSOS</strong></label>
                                                            <input type="number" name="quantidadedeingressos" class="form-fname form-element medium" placeholder="1" tabindex="01" min='1' step='1' value="1" required>
                                                        </div>
                                                        <div class="column width-4">
                                                            <label class="color-charcoal"><strong>NOME</strong></label>
                                                            <input type="text" name="nome" class="form-fname form-element medium" placeholder="Digite seu primeiro nome" tabindex="01" required>
                                                        </div>
                                                        <div class="column width-8">
                                                            <label class="color-charcoal"><strong>SOBRENOME (COMPLETO)</strong></label>
                                                            <input type="text" name="sobrenome" class="form-fname form-element medium" placeholder="Digite seu sobrenome completo" tabindex="01" required>
                                                        </div>
                                                        <div class="column width-12">
                                                            <label class="color-charcoal"><strong>E-MAIL</strong> (Enviaremos sua Senha de acesso para este e-mail)</label>
                                                            <input type="email" name="email" class="form-fname form-element medium" placeholder="Seu E-mail principal" tabindex="02" required>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="column width-6">
                                                            <span class=" color-charcoal  weight-bold pb-10">Sexo.</span>
                                                            <div class="form-select form-element rounded medium">
                                                                <select name="sexo" tabindex="6" class="form-aux" data-label="Project Budget">
                                                                    <option>Feminino</option>
                                                                    <option>Masculino</option>
                                                                </select>
                                                            </div> 
                                                        </div> 
                                                        <div class="column width-6">
                                                            <span class=" color-charcoal weight-bold pb-10">Nos conheceu através?</span>
                                                            <div class="form-select form-element rounded medium">
                                                                <select name="conheceu" tabindex="6" class="form-aux" data-label="Project Budget"> 
                                                                    <option>
                                                                        Sou membro
                                                                    </option>
                                                                    <option>
                                                                        Amigo(a)
                                                                    </option>
                                                                    <option>
                                                                        Facebook
                                                                    </option>
                                                                    <option>
                                                                        Instagram
                                                                    </option>
                                                                    <option>
                                                                        YouTube
                                                                    </option>
                                                                    <option>
                                                                        Twitter
                                                                    </option>
                                                                    <option>
                                                                        WhatsApp
                                                                    </option>
                                                                    <option>
                                                                        Célula
                                                                    </option>
                                                                    <option>
                                                                        Panfleto
                                                                    </option>
                                                                    <option>
                                                                        Passei em frente.
                                                                    </option>
                                                                    <option>
                                                                        Outro
                                                                    </option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="column width-6">
                                                            <label class="color-charcoal">TEL. <strong>MÓVEL</strong></label>
                                                            <input type="tel" name="telefonemovel" class="form-fname form-element medium" placeholder="21 00001111" tabindex="02" required>
                                                        </div>
                                                        <div class="column width-6">
                                                            <label class="color-charcoal"><strong>WHATSAPP</strong></label>
                                                            <input type="tel" name="whatsapp" class="form-fname form-element medium" placeholder="21 00001111" tabindex="02">
                                                        </div>
                                                        <?
                                                            $bAl="SELECT id FROM agenda WHERE codigodoevento='$codigodoevento' AND alimentos !=''";
                                                              $rAl=mysqli_query($con, $bAl);
                                                                $nAl=mysqli_num_rows($rAl);
                                                            if($nAl > 0):
                                                        ?>
                                                        <div class="box rounded border-blue">
                                                            <div class="column width-12">
                                                                <span class="color-blue">Escolha um item de <strong>alimento</strong> para levar</span>
                                                                <div class="field-wrapper pt-10 pb-10 left bkg-white small rounded">
                                                                    <?
                                                                        $bALi="SELECT alimentos FROM agenda WHERE codigodoevento='$codigodoevento' AND alimentos !=''";
                                                                        $rALi=mysqli_query($con, $bALi);
                                                                            $dAli=mysqli_fetch_array($rALi);
                                                                                $alim=explode(', ', $dAli['alimentos']);
                                                                        // if(!empty($alim)):
                                                                        for($aL=0; $aL < count($alim); $aL++){
                                                                                // echo $alim[$aL];
                                                                            $quant=explode(' - ', $alim[$aL]);
                                                                            $repeticoes=$quant[1];

                                                                            $alimento=$quant[0];
                                                                            $bescolhidos="SELECT count(alimento) as totalescolhidos FROM agendainscritos WHERE codigodoevento='$codigodoevento' AND alimento='$alimento'";
                                                                            $rEsc=mysqli_query($con, $bescolhidos);
                                                                                $dEsc=mysqli_fetch_array($rEsc);
                                                                            $totalescolhidos=$dEsc['totalescolhidos'];

                                                                            if($repeticoes < 1):
                                                                                $repeticoes=1;
                                                                            endif;
                                                                            if(!empty($totalescolhidos)):
                                                                                $repeticoesdisponiveis = $totalescolhidos - $repeticoes;
                                                                            else:
                                                                                $repeticoesdisponiveis = $repeticoes;
                                                                            endif;
                                                                                $tab=0;
                                                                                for($in=0; $in < $repeticoesdisponiveis; $in++){
                                                                                    $tab++
                                                                    ?>
                                                                    <div class="column width-4">
                                                                        <input tabindex="00<? echo $tab?>" id="<? echo $quant[0].$in; ?>" class="radio" name="alimento" value="<? echo $quant[0]; ?>" type="radio">
                                                                        <label for="<? echo $quant[0].$in; ?>" class="radio-label color-blue"><? echo $quant[0]; ?></label>
                                                                    </div>
                                                                    <?
                                                                            }
                                                                        }
                                                                    ?>
                    
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <? else: ?>
                                                            <input type="hidden" value='' name="alimento">
                                                        <? endif; ?>
                                                        <div class="column width-12">
                                                            <p class="color-black pb-10" align='justify'>
                                                                Você confirma que preencheu este cadastro espontâneamente, que quer participar do evento da <strong>Emanuel (Igreja Batista Emanuel)</strong> e que está ciente de que ao confirmar este formulário autorizará o uso da sua imagem em todos os veículos de divulgação, comunicação e publicidade pela Emanuel por tempo indeterminado <strong>?</strong>
                                                            </p>
                                                        </div>
                                                        
                                                        <div class="column width-12">
                                                            <div class="form-select form-element rounded medium">
                                                                <select name="declaracao" tabindex="6" class="form-aux" data-label="Project Budget"> 
                                                                    <option>
                                                                        Sim
                                                                    </option>
                                                                    <option>
                                                                        Não
                                                                    </option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <input type="hidden" name='v1' value='<?php echo $v1;?>'/>
                                                        <input type="hidden" name='v2' value='<?php echo $v2;?>'/>

                                                        <div class="column width-12 border-theme pt-20 pb-20">
                                                            <label class="center color-charcoal">PARA FINALIZAR <strong>RESOLVA O CÁLCULO ABAIXO.</strong></label>
                                                            <div class="field-wrapper">
                                                                <div class="column width-4 pt-20">
                                                                    <h3 class="right">
                                                                        <?php echo $v1.' + '.$v2.' = ';?>
                                                                    </h3>
                                                                </div>
                                                                <div class="column width-6">
                                                                    <div class="field-wrapper">
                                                                        <input type="hidden" name="soma1" class="form-name form-element medium" value="<?php $somaa = $v1+$v2; echo $somaa;?>">
                                                                        <input type="number" name="soma" class="form-name form-element large" placeholder="Informar valor" tabindex="03" required>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="column width-12 pt-20">
                                                            <button tabindex="04" type="submit" name="btn-cadastrar" class="form-submit button small rounded bkg-blue bkg-hover-blue-light color-white color-hover-white hard-shadow"><span class="text-medium weight-bold">Inscrever-se</span></button>
                                                            <!-- <a tabindex="05" href="<?php echo $loginUrl; ?>" class="form-submit button small bkg-facebook bkg-hover-facebook rounded color-white color-hover-white hard-shadow">
                                                                <span class="icon-facebook left"></span>  Entrar com Facebook
                                                            </a> -->
                                                            <p align="justify" class="text-large color-charcoal mt-20"><small>Ao clicar em "inscrever-se" você declara ter lido e aceito nossos <a href="#">termos</a></small>.</p>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                        <? elseif(isset($_SESSION['inscricaoevento']) AND $valordoevento >=1): ?>
                                        <div class="signup-box box rounded medium shadow bkg-white border-blue">
                                            <form action="controllers/PaymentControllerEventos.php" method="post" id="pay" name="pay">
                                                <div class="billing-details">
                                                    <div class="form-container">
                                                        <!--<form action="/controllers/PaymentControllerProduto.php" method="post" id="pay" name="pay">-->
                                                            <!-- Payment Method -->
                                                            <div class="row">
                                                                <div class="column width-12">
                                                                    <div class="tabs button-nav rounded small bordered left mb-20">
                                                                        <div class="tab-content">
                                                                            <div class="row">
                                                                                <div class="column width-12">
                                                                                    <div class="column width-12">
                                                                                        <span class=" color-charcoal text-medium weight-bold">Seu nome</span>
                                                                                        <div class="field-wrapper pt-10 pb-10">
                                                                                            <input id="membro-1" class="form-element radio" name="membro" type="radio" value="<?php echo $nomecompletoinscrito?>" checked>
                                                                                            <label for="membro-1" class="text-large radio-label"><?php echo $nomecompletoinscrito?></label>
                                                                                                
                                                                                            </label>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="column width-12">
                                                                                        <span class=" color-charcoal text-medium weight-bold">Valor da contribuição.</span>
                                                                                        <div class="field-wrapper pt-10 pb-10">
                                                                                            <input type="hidden" name="amount" id='amount' value='<?php echo $contribuicao; ?>'/>
                                                                                            <span class="text-medium color-blue">
                                                                                                <?php echo 'R$ '.$valordoevento; ?> </span>
                                                                                            </span>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="column width-12">
                                                                                        <span class=" color-charcoal text-medium weight-bold pb-10">Como você deseja contribuir?</span>
                                                                                        <div class="form-select form-element rounded medium">
                                                                                            <select name="tipodecontribuicao" tabindex="6" class="form-aux" data-label="Project Budget">
                                                                                                <option selected="selected" value="<?php echo "Pagamento da inscrição do evento: ".$evento;?>"><?php echo "Pagamento da inscrição do evento: ".$evento;?></option>
                                                                                            </select>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                                    
                                                                                <div class="column width-12">
                                                                                    <div class="column width-12">
                                                                                        <span class=" color-charcoal text-medium weight-bold pt-10">Realizada no Cartão (Crédito)</span>
                                                                                    </div>
                                                                                    <div class="column width-12">
                                                                                        <label>Nome</label>
                                                                                        <input type="text" id="cardholderName" data-checkout="cardholderName" placeholder="Nome" class="form-fname form-element rounded medium"/>
                                                                                    </div>

                                                                                    <div class="column width-4">
                                                                                        <label>Tipo de documento</label>
                                                                                        <div class="row">
                                                                                            <div class="column width-12">
                                                                                                <div class="form-select form-element rounded medium"
                                                                                                    tabindex="2">
                                                                                                    <select id="docType" data-checkout="docType"></select>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="column width-8">
                                                                                        <label>Número</label>
                                                                                        <input type="text" id="docNumber" data-checkout="docNumber" placeholder="19119119100" class="form-element rounded medium"/>
                                                                                    </div>

                                                                                    <div class="column width-12">
                                                                                        <label>Número do cartão</label>
                                                                                        <div class="input-indication">
                                                                                            <input type="text" id="cardNumber" data-checkout="cardNumber" placeholder="Número do cartão." onselectstart="return false" onpaste="return true" onCopy="return false" onCut="return false" onDrag="return false" onDrop="return false" autocomplete=off class="form-fname form-element rounded medium"/>

                                                                                            <div  class="form-element input-icon rounded bkg-grey-ultralight inherit-style"> <span class="icon-credit-card"></span> <div class="brand"></div></div>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="column width-4">
                                                                                        <label>Código de segurança (CVV)</label>
                                                                                        <div class="input-indication">
                                                                                                <input type="text" id="securityCode" data-checkout="securityCode" placeholder="CVV" onselectstart="return false" onpaste="return true" onCopy="return false" onCut="return false" onDrag="return false" onDrop="return false" autocomplete=off class="form-security-code form-element rounded medium center"/>
                                                                                            <div
                                                                                                class="form-element input-icon rounded bkg-grey-ultralight inherit-style">
                                                                                                <a data-content="inline"
                                                                                                    href="#cvv-modal"
                                                                                                    class="lightbox-link icon-info border-orange-light color-orange-light"
                                                                                                    data-aux-classes="tml-cart-modal tml-exit-light no-margins height-auto"
                                                                                                    data-modal-mode data-toolbar=""
                                                                                                    data-modal-width="300"
                                                                                                    data-lightbox-animation="fade"
                                                                                                    data-modal-animation="slideInTop"></a>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="column width-4">
                                                                                        <label>Mês de validade</label>
                                                                                        <div class="input-indication">
                                                                                            <input type="text" id="cardExpirationMonth" data-checkout="cardExpirationMonth" placeholder="11" onselectstart="return false" onpaste="return true" onCopy="return false" onCut="return false" onDrag="return false" onDrop="return false" autocomplete=off class="form-expiration form-element rounded medium"/>
                                                                                            <div
                                                                                                class="form-element input-icon rounded bkg-grey-ultralight inherit-style">
                                                                                                <span class="icon-calendar"></span>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="column width-4">
                                                                                        <label>Ano de validade</label>
                                                                                        <div class="input-indication">
                                                                                            <input type="text" id="cardExpirationYear" data-checkout="cardExpirationYear" placeholder="2025" onselectstart="return false" onpaste="return true" onCopy="return false" onCut="return false" onDrag="return false" onDrop="return false" autocomplete=off class="form-expiration form-element rounded medium"/>
                                                                                            <div
                                                                                                class="form-element input-icon rounded bkg-grey-ultralight inherit-style">
                                                                                                <span class="icon-calendar"></span>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                    
                                                                                    <div class="column width-12">
                                                                                        <label>Parcelas</label>
                                                                                        <div class="row">
                                                                                            <div class="column width-12">
                                                                                                <div class="form-select form-element rounded medium" tabindex="2">
                                                                                                    <select id="installments"  name="installments"></select>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                            
                                                                                    <input type="hidden" id="email" name="codigodainscricao" value="<?php echo $codigodainscricao;?>" placeholder="your email" />
                                                                                    <input type="hidden" id="email" name="email" value="<?php echo $email;?>" placeholder="your email" />
                                                                                    <input type="hidden" name="nomeofertante" value="<?php echo $nomecompletoinscrito;?>" placeholder="your email" />

                                                                                    <input type="hidden" name="amount" id="amount" value="<?php echo $valordoevento;?>"/>
                                                                                    <input type="hidden" name="description" value="<?php echo "Pagamento da inscrição do evento: ".$evento;?>"/>
                                                                                    <input type="hidden" name="paymentMethodId"/>
                                                                                    <!--<input type="submit" value="Pay!" />-->

                                                                                    <!-- Submit Payment -->
                                                                                    <div class="column width-12">
                                                                                        <p align="justify" class="title-small mb-0 color-charcoal pt-10 pb-20">
                                                                                            Sua inscrição será processada financeiramente através do  <strong>MercadoPago</strong>.
                                                                                        </p>
                                                                                    </div>
                                                                                    <div class="column width-9">
                                                                                        <button type="submit" value="Concluir contribuição." class="column width-12 button rounded small bkg-blue bkg-hover-blue color-white color-hover-white mb-0 hard-shadow shadow" tabindex="8"/>
                                                                                            <span class="text-medium">
                                                                                                Concluir contribuição.
                                                                                            </span>
                                                                                        </button>
                                                                                    </div>
                                                                                    <!-- Submit Payment End -->
                                                                                </div>
                                                                            </div>
                                                                        
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        <!--</form>-->
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                        <? endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>                    
                </div>
                <!-- Content End -->
                <div class="printable"></div>
                
                <!-- Pagination Section 3 -->
				<!-- <div class="section-block pagination-3 bkg-grey-ultralight pt-30">
					<div class="row">
                        <div class="box rounded bkg-white border-blue small">
                            <div class="column width-12">
                                <?php
                                    //include "./_paginacao.php";
                                ?>
                            </div>
						</div>
					</div>
				</div> -->
				<!-- Pagination Section 3 End -->
            </div>

			<!-- Footer -->
			<footer class="footer footer-blue">
				<? include "./_footer_top.php"; ?>
			</footer>
			<!-- Footer End -->

			<?php
				include "./_modalevento.php";
			?>
		</div>
	</div>
    
	<? include "./_script.php"; ?>
    
</body>
</html>