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
    //var_dump($_SESSION);
    // if(isset($_SESSION["lideranca"])):
    //     include "protectcolaborador.php";	
    // elseif(isset($_SESSION["membro"])):
    //     include "protectusuario.php";
    // else:
    //     header("location:index");
	// endif;
	
	
	//var_dump($_SESSION);
    //Dados para registrar o log do cliente.
    $mensagem = ("\n$nomedocolaborador ($matricula) Acessou as configurações da célula. Isto através do IP: $ip, na data $dia às $hora.\n"); //Escreva a mensagem que será gravada no log.
    include "registrolog.php";

    if(isset($_POST['btn-confirmarmigracao'])):
        $referenciadopedido=mysqli_escape_string($con, $_POST['referenciadopedido']);
        $solicitante=mysqli_escape_string($con, $_POST['solicitante']);
        $matriculasolicitante=mysqli_escape_string($con, $_POST['matricula']);
        $celulaatual=mysqli_escape_string($con, $_POST['celulaatual']);
        $codigodacelula=mysqli_escape_string($con, $_POST['codigodacelula']);
        $novacelula=mysqli_escape_string($con, $_POST['novacelula']);
        $matriculanovacelula=mysqli_escape_string($con, $_POST['matriculanovacelula']);
        $lidernovo=mysqli_escape_string($con, $_POST['lidernovo']);
        $datadasolicitacao=mysqli_escape_string($con, $_POST['datadasolicitacao']);
        $comentarioliderantigo=mysqli_escape_string($con, $_POST['comentarioliderantigo']);

        $upMigra="UPDATE celulasmembros SET comentarioliderantigo='$comentarioliderantigo', liberacaodolideratual='LIBERADO' WHERE referencia='$referenciadopedido' AND solicitante='$solicitante' AND matricula='$matriculasolicitante'";
        if(mysqli_query($con, $upMigra)):
            $upCM="UPDATE membros SET pertenceaqualcelula='$novacelula', matriculadacelula='$matriculanovacelula' WHERE matricula='$matricula'";
            mysqli_query($con, $upCM);

            $_SESSION['cordamensagem']='green';
            $_SESSION['mensagem']='Membro liberado com sucesso.';
        else:
            $_SESSION['cordamensagem']='red';
            $_SESSION['mensagem']='Erro ao registrar liberação do membro, comunique ao TI';
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
                                                        <h1 class="mb-0">Configuração da sua <strong>célula</strong>.</h1>
                                                        <p class="text-large color-charcoal mb-0 mb-mobile-20">
                                                            Nossa missão é <strong>Anunciar Jesus e ser culturalmente relevante.</strong>
                                                        </p>
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
                                <div class="column width-3">
                                    <div class="box rounded small pt-12 pb-10 bkg-white border-blue color-charcoal shadow rounded border-grey-light">
                                        <div class="column width-12 pt-20 pb-20">
                                            <div class="feature-column left"> 
                                                <a href="celulaadd<?echo $linkSeguro?>" target="_blank" class="color-blue color-hover-blue text-medium">Adicionar integrante</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="box rounded rounded shadow border-blue bkg-white">
                                <div class="column width-12">
                                    <h1>Integrantes que solicitarão migração de célula</h1>
                                </div>
                                <?
                                    $bMigra="SELECT referencia, solicitante FROM celulasmembros WHERE celulaatual='$celuladomembro' AND codigodacelula='$matriculadacelula' AND datadasolicitacao!='' AND aprovacaodonovolider !=''";
                                      $rMigr=mysqli_query($con, $bMigra);
                                        while($dMigra=mysqli_fetch_array($rMigr)):
                                ?>
                                <div class="column width-3">
                                    <a data-content="inline" data-aux-classes="height-auto rounded" data-toolbar=""         data-modal-mode data-modal-width="1140" data-modal-animation="scaleIn" data-lightbox-animation="fadeIn" href="#<? echo $dMigra['referencia'];?>" class="lightbox-link rounded medium color-blue color-hover-blue text-medium">
                                        <div class="box rounded small pt-12 pb-10 bkg-white border-blue color-charcoal shadow rounded border-grey-light">
                                            <div class="column width-12 pt-20 pb-20">
                                                <div class="feature-column left"> 
                                                    <? echo base64_decode(base64_decode($dMigra['solicitante']));?>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                
                                <div id="<? echo $dMigra['referencia'];?>" class="pt-70 pb-50 hide">
                                    <div class="row">
                                        <div class="column width-10 offset-1 center">

                                            <h3 class="mb-10">Solicitação do membro <strong><? echo base64_decode(base64_decode($dMigra['solicitante']));?></strong></h3>
                                            

                                            <!-- Contact Form -->
                                            <div class="contact-form-container">
                                                <form class="contact-form" action="" method="post" novalidate>
                                                    <input type="hidden" name="referenciadopedido" value="<? echo $dMigra['referencia'];?>"/>
                                                    <div class="row">
                                                        <div class="column width-6">
                                                            <div class="field-wrapper">
                                                                <input type="text" value="<? echo base64_decode(base64_decode($dMigra['solicitante']));?>" name="solicitante" class="form-fname form-element rounded medium" readonly tabindex="1">
                                                            </div>
                                                        </div>
                                                        <div class="column width-3">
                                                            <div class="field-wrapper">
                                                                <input type="text" name="matricula" value="<? echo $dMigra['matriculadomembro'];?>" class="form-lname form-element rounded medium" readonly tabindex="2">
                                                            </div>
                                                        </div>
                                                        <div class="column width-3">
                                                            <div class="field-wrapper">
                                                                <input type="text" name="celulaatual" value="<? echo $dMigra['celulaatual'];?>" class="form-lname form-element rounded medium" readonly tabindex="3">
                                                            </div>
                                                        </div>
                                                        <div class="column width-3">
                                                            <div class="field-wrapper">
                                                                <input type="text" name="codigodacelula" value="<? echo $dMigra['codigodacelula'];?>" class="form-lname form-element rounded medium" readonly tabindex="4">
                                                            </div>
                                                        </div>
                                                        <div class="column width-3">
                                                            <div class="field-wrapper">
                                                                <input type="text" name="novacelula" value="<? echo $dMigra['novacelula'];?>" class="form-lname form-element rounded medium" readonly tabindex="5">
                                                            </div>
                                                        </div>
                                                        <div class="column width-3">
                                                            <div class="field-wrapper">
                                                                <input type="text" name="matriculanovacelula" value="<? echo $dMigra['matriculanovacelula'];?>" class="form-lname form-element rounded medium" readonly tabindex="6">
                                                            </div>
                                                        </div>
                                                        <div class="column width-3">
                                                            <div class="field-wrapper">
                                                                <input type="text" name="lidernovo" value="<? echo base64_decode(base64_decode($dMigra['lidernovo']));?>" class="form-lname form-element rounded medium" readonly tabindex="6">
                                                            </div>
                                                        </div>
                                                        <div class="column width-3">
                                                            <div class="field-wrapper">
                                                                <input type="text" name="datadasolicitacao" value="<? echo $dMigra['datadasolicitacao'];?>" class="form-lname form-element rounded medium" readonly tabindex="6">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="column width-12">
                                                            <div class="field-wrapper">
                                                                <textarea name="comentarioliderantigo" class="form-message form-element rounded medium" placeholder="Como você avalia o membro atual?" tabindex="7" required></textarea>
                                                            </div>
                                                            <button type="submit" name="btn-confirmarmigracao" class="form-submit button rounded medium bkg-theme bkg-hover-theme color-white color-hover-white">Liberar membro</button>
                                                        </div>
                                                    </div>
                                                </form>
                                                <div class="form-response"></div>
                                            </div>
                                            <!-- Contact Form End -->

                                        </div>
                                    </div>
                                </div>
                                <? endwhile; ?>
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

			<?php
				include "./_modalevento.php";
			?>
		</div>
	</div>
    <div id="modal-novomembro" class="pt-70 pb-50 hide">
        <div class="row">
            <div class="column width-10 offset-1 center">

                <!-- Info -->
                <h3 class="mb-10">Contact Us</h3>
                <p class="mb-30">We respond to all emails and inquieries within 48hrs.</p>
                <!-- Info End -->

                <!-- Contact Form -->
                <div class="contact-form-container">
                    <form class="contact-form" action="php/send-email.php" method="post" novalidate>
                        <div class="row">
                            <div class="column width-6">
                                <div class="field-wrapper">
                                    <input type="text" name="fname" class="form-fname form-element rounded medium" placeholder="First Name*" tabindex="1" required>
                                </div>
                            </div>
                            <div class="column width-6">
                                <div class="field-wrapper">
                                    <input type="text" name="lname" class="form-lname form-element rounded medium" placeholder="Last Name" tabindex="2">
                                </div>
                            </div>
                            <div class="column width-6">
                                <div class="field-wrapper">
                                    <input type="email" name="email" class="form-email form-element rounded medium" placeholder="Email address*" tabindex="3" required>
                                </div>
                            </div>
                            <div class="column width-6">
                                <div class="field-wrapper">
                                    <input type="text" name="website" class="form-wesite form-element rounded medium" placeholder="Website" tabindex="4">
                                </div>
                            </div>
                            <div class="column width-6">
                                <input type="text" name="honeypot" class="form-honeypot form-element rounded medium">
                            </div>
                        </div>
                        <div class="row">
                            <div class="column width-12">
                                <div class="field-wrapper">
                                    <textarea name="message" class="form-message form-element rounded medium" placeholder="Message*" tabindex="7" required></textarea>
                                </div>
                                <input type="submit" value="Send Email" class="form-submit button rounded medium bkg-theme bkg-hover-theme color-white color-hover-white">
                            </div>
                        </div>
                    </form>
                    <div class="form-response"></div>
                </div>
                <!-- Contact Form End -->

            </div>
        </div>
    </div>
	<? include "./_script.php"; ?>
    
</body>
</html>