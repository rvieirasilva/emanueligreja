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
	
    if(isset($_POST['btn-enviarmensagem'])):
        
        
        if ($s2 === $s):
            // sucesso
            $emaildamensagem    = mysqli_escape_string($con, $_POST['email']);
            $whatsapp           = mysqli_escape_string($con, $_POST['whatsapp']);
            $mensagemvisitante  = mysqli_escape_string($con, $_POST['mensagem']);
            $day = date('Y-m-d');

                //$bduplicado="SELECT * FROM mensagensdosite WHERE mensagem='$mensagemvisitante'";
                //    $rduplicado=mysqli_query($con, $bduplicado);
                //        $dduplicado=mysqli_fetch_array($rduplicado);
                //        $ndupli=mysqli_num_rows($rduplicado);
                //if($ndupli < 1):
                    $registrar="INSERT INTO mensagensdosite (dia, email, whatsapp, mensagem) VALUES ('$day', '$emaildamensagem', '$whatsapp', '$mensagemvisitante')";
                        
                    if(mysqli_query($con, $registrar)):
                        //Inicio do código para enviar e-mail.
                        include "./_enviaremail.php"; //Traz página com as configurações para envio do e-mail.
                        $mail->addAddress("$emaildamensagem", "");     // Add a recipient 
                            //$mail->addAddress("ellen@example.com");               // Name is optional
                            //$mail->addReplyTo("info@example.com", "Information");
                            //$mail->addCC("rafael@faelvieirasilva.com.br");
							// $mail->addBCC("rafael@faelvieirasilva.com.br", "");
							// $mail->addBCC("rafaelsilva246+ivtzgwp6g7quopsdrjoe@boards.trello.com", "");

                            //$mail->addAttachment("/var/tmp/file.tar.gz");         // Add attachments
                            //$mail->addAttachment("/tmp/image.jpg", "new.jpg");    // Optional name
                        $mail->isHTML(true);                                  // Set email format to HTML
                        $mail->Subject = "Igreja Emanuel - Recebemos sua mensagem.";
                        $mail->Body    = "Olá. 
                                        <p>Essa é uma mensagem automática para te dizer que eu recebemos sua mensagem e nossa equipe entrará em contato.</p>
                                        <p></p>
                                        <p>Se você tiver enviado seu WhatsApp te chamaremos por lá.</p>
                                        <p></p>
                                        <p></p>
                                        <p>Seu e-mail: $emaildamensagem</p>
                                        <p>Seu WhatsApp: $whatsapp</p>
                                        <p>Sua mensagem: $mensagemvisitante</p>
                                        <p></p>
                                        <p></p>
                                        <p>Grande abraço, paz do Senhor Jesus e que a Paz do Espírito Santo te alcance neste momento. Seja abençoado!</p>

                                        <p></p>
                                        <p></p>
                                        <p><h5>Pastor Rafael Vieira</h5></p>
                                        <p><h4>Igreja Batista Emanuel</h4></p>
                                        ";
                        $mail->AltBody = "Olá. 
                                        <p>Essa é uma mensagem automática para te dizer que eu recebemos sua mensagem e nossa equipe entrará em contato.</p>
                                        <p></p>
                                        <p>Se você tiver enviado seu WhatsApp te chamaremos por lá.</p>
                                        <p></p>
                                        <p></p>
                                        <p>Seu e-mail: $emaildamensagem</p>
                                        <p>Seu WhatsApp: $whatsapp</p>
                                        <p>Sua mensagem: $mensagemvisitante</p>
                                        <p></p>
                                        <p></p>
                                        <p>Grande abraço, paz do Senhor Jesus e que a Paz do Espírito Santo te alcance neste momento. Seja abençoado!</p>

                                        <p></p>
                                        <p></p>
                                        <p><h5>Pastor Rafael Vieira</h5></p>
                                        <p><h4>Igreja Batista Emanuel</h4></p>
                                        "; // Não é visualizado pelo usuário!
                        if($mail->send()):
            				$_SESSION['cordamensagem']='green';
            				$_SESSION['mensagem']='Mensagem enviada.';
            			else:
            				$_SESSION['cordamensagem']='red';
            				$_SESSION['mensagem']='Erro ao enviar mensagem, verifique sua conexão e o seu e-mail. Se o erro persistir tente me enviar uma mensagem pelo messenger: https://m.me/emanueligrejabr';
                        endif;
                    endif;
                //endif; 
        else:
            //captcha invalido
            $_SESSION['bot'] = true;
            $_SESSION['mensagem']='Erro no reCAPTCHA. Informe o valor correto da soma.';
        endif;

        
    endif;

	$bDpage = "SELECT titulo, subtitulo, texto from personalizarquemsomos ORDER BY id desc";
	  $rDpage = mysqli_query($con, $bDpage);
	    $dPage = mysqli_fetch_array($rDpage);
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
                                                        <h1 class="mb-0">
                                                            <?php
                                                                if(!empty($dPage['titulo'])):
                                                                    echo $dPage['titulo'];
                                                                else:
                                                                    echo "Quem somos?";
                                                                endif;
                                                            ?>
                                                        </h1>
                                                        <p class="text-large color-charcoal mb-0 mb-mobile-20">Nossa missão é <strong>anunciar Jesus e ser culturalmente relevante</strong></p>
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
                                <div class="column width-12 pt-30 color-charcoal">
                                    <font size='4' align="justify">
                                        <p align="justify">
                                            <?php
                                                echo $dPage['texto'];
                                            ?>
                                        </p>
                                    </font>
                                </div>
                                <div class="column width-8 offset-2 center">
                                    <h3><strong>Quero ser membro da Emanuel</strong>.</h3>
                                    <p class="lead mb-50">Quer ser membro da Emanuel? Ficamos muito contentes com sua decisão, clique abaixo e entraremos em contato para te conhecer melhor, responder suas dúvidas e iniciar sua integração. Queremos muito te conhecer.</p>
                                    <a href="#sermembro" data-content="inline" data-aux-classes="tml-newsletter-modal" data-toolbar="" data-modal-mode data-modal-width="600" data-lightbox-animation="fadeIn" class="lightbox-link button rounded medium no-margins bkg-theme bkg-hover-theme color-white color-hover-white mb-0 fade-location">
                                        Entrar na Emanuel
                                    </a>
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

			<?php
				include "./_modalevento.php";
			?>
		</div>
	</div>
    
	<? include "./_script.php"; ?>
    
</body>
</html>