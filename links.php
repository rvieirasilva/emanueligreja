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
<head><? include "./_head.php"; ?></head>
<body class="shop blog home-page">

	<?php
        // if(isset($_SESSION["membro"])):
            include "./_menuinterno.php";
        // endif;
	?>

			<div class="content clearfix">
                <div class="section-block pt-150">
                    <div class="row">
                        <?php
                            include "./_notificacaomensagem.php"; $diadalive=date('D');
                            
                            $sistema = $_SERVER['HTTP_USER_AGENT'];
                            $iphone = strpos($sistema, "iPhone");
                            $ipad = strpos($sistema, "ipad");
                            $Android = strpos($sistema, "Android");

                            // if($diadalive !== 'Thu' AND $diadalive !== 'Sun'):
                            if($diadalive !== 'Thu' AND $diadalive !== 'Sun'):
                            $bConceitoCurto= "SELECT autor, conceito FROM conceitos WHERE NOT (postadoem > '$dataeng') AND character_length(conceito) <= 150 AND conceito != '' ORDER BY rand(), postadoem DESC LIMIT 1";
                            $rConceito = mysqli_query($con, $bConceitoCurto);
                                $dConceito = mysqli_fetch_array($rConceito);
                        ?>
                        <div class="grid-item grid-sizer pb-10" >
                            <article class="post font-alt-3">
                                <div class="post-content box small no-margins rounded border-blue bkg-white color-charcoal">
                                    <blockquote class="left icon pt-10 pb-0">
                                        <span class="icon-quote right"></span>
                                        <p class="font-alt-3 weight-light text-medium color-charcoal left"><?php echo $dConceito['conceito'];?>
                                            <br>
                                            <p class="font-alt-3 weight-light color-charcoal text-medium pt-10">- <?php echo $dConceito['autor'];?></p>
                                        </p>
                                    </blockquote>
                                    <div class="left">
                                    </div>
                                </div>
                            </article>
                        </div>
                        <?php
                            else:
                            if($Android):
                                //fb://page/100665391991986
                        ?>
                                <a href="https://www.facebook.com/pg/emanueligrejabr/live_videos/" class="column hide show-on-mobile show-on-tablet full-width button medium rounded hard-shadow border-hover-facebook bkg-facebook color-hover-facebook color-white">
                                    <span class="icon-facebook small color-facebook color-hover-white"> </span>
                                    <span class="text-large weight-bold color-facebook color-hover-white"> Estamos ao vivo, clique e cultue.</span>
                                </a>
                            <?php
                                // Link para mobile abrindo direto o app do facebook no iOS
                                elseif($iphone OR $ipad):
                                    //fb://page/?id=100665391991986
                            ?>
                                <a href="https://www.facebook.com/pg/emanueligrejabr/live_videos/" class="column hide show-on-mobile show-on-tablet full-width button medium rounded hard-shadow border-hover-facebook bkg-facebook color-hover-facebook color-white">
                                    <span class="icon-facebook small color-facebook color-hover-white"> </span>
                                    <span class="text-large weight-bold color-facebook color-hover-white"> Estamos ao vivo, clique e cultue.</span>
                                </a>
                        <?php
                            else:
                        ?>                        
                            <a href="https://www.facebook.com/emanueligrejabr/live_videos/?ref=page_internal" class="column full-width button medium rounded border-hover-blue bkg-blue color-hover-blue color-white">
                                <span class="icon-facebook small color-hover-blue color-white"> </span>
                                <span class="text-large weight-bold color-hover-blue color-white"> Estamos ao vivo, clique e cultue.</span>
                            </a>
                        <?php
                            endif;
                            endif;
                        ?>

                        <a href="https://goo.gl/maps/YtLuYJCgygJ4KzQ57" target="_blank" class="column full-width button large rounded hard-shadow border-hover-charcoal bkg-charcoal color-white color-hover-charcoal">
                            <span class="icon-location-pin small color-white color-hover-charcoal"> </span>
                            <span class="text-large weight-regular color-white color-hover-charcoal">
                                Abrir no Google Maps | <strong>Cultos: Quinta às 20h e Domingo às 18h.</strong>
                            </span>
                        </a>
                        <!-- <a href="https://forms.gle/S8h9UXbTdtMgMjBV7" target="_blank" class="column full-width button large rounded hard-shadow border-hover-yellow-light bkg-yellow color-white color-hover-yellow">
                            <span class="icon-stopwatch small color-white color-hover-yellow"> </span>
                            <span class="text-large weight-bold color-white color-hover-yellow">
                                <strong>Inscrição:</strong> Café teológico - As cartas de Paulo, como missionário. (Gratuito, <strong>apenas 3 vagas disponíveis</strong>)
                            </span>
                        </a> -->
                        <a href="doacoes" target="_blank" class="column full-width button xlarge rounded hard-shadow border-hover-turquoise bkg-turquoise color-white color-hover-charcoal">
                            <span class="icon-lifebuoy small color-white color-hover-charcoal"> </span>
                            <span class="text-large color-white color-hover-charcoal">
                                Enviar <strong>Oferta & Dizímo</strong>
                            </span>
                        </a>
                        <!-- <a href="https://eupedi.com/igrejaemanuel" target="_blank" class="column full-width button xlarge rounded hard-shadow border-hover-orange bkg-orange color-charcoal color-hover-charcoal">
                            <span class="icon-shop small color-white color-hover-red"> </span>
                            <span class="text-large color-white color-hover-red">
                                <strong>Almoço beneficiente</strong> | Ajude-nos
                            </span>
                        </a> -->
                        <a href="parceirofiel" target="_blank" class="column full-width button xlarge rounded hard-shadow border-hover-yellow-light bkg-yellow-light color-charcoal color-hover-charcoal">
                            <span class="icon-compass small color-white color-hover-red"> </span>
                            <span class="text-large color-white color-hover-red">
                                <strong>SEJA UM PARCEIRO MINISTERIAL</strong> | Ajude-nos na implantação e consolidação da Emanuel, envie uma oferta mensal <strong>automática</strong>.
                            </span>
                        </a>
                        <!-- <a href="https://forms.gle/f4HrBR3Zbk6ULmNu8" target="_blank" class="column full-width button large rounded hard-shadow border-hover-red bkg-red color-white color-hover-youtube">
                            <span class="icon-hour-glass small color-white color-hover-red"> </span>
                            <span class="text-large color-white color-hover-red">
                                <strong>Inscrição até 14/07:</strong> Imersão em escatologia (24/07/2021). (12 vagas disponíveis).
                            </span>
                        </a> -->
                        <hr/>
                        <?php
                            $LinkUltimaPubli= "SELECT categoria, referencia, titulo FROM blog WHERE NOT (datadapostagem > '$dataeng') AND textoancora != '' ORDER BY datadapostagem DESC";
                            $rLinkUltimaPubli = mysqli_query($con, $LinkUltimaPubli);
                                $dLinkUltimaPubli = mysqli_fetch_array($rLinkUltimaPubli);
                        ?>
                        <?php
                            if($dLinkUltimaPubli['categoria'] == 'Artigos' OR $dLinkUltimaPubli['categoria'] == 'Pesquisas' OR $dLinkUltimaPubli['categoria'] == 'Close' OR $dLinkUltimaPubli['categoria'] == 'Teologia'):
                        ?>
                        <?php
                            //Se não for inscrito pede para realizar inscrição.
                            if(!isset($_SESSION['usuario']) AND !isset($_SESSION['colaborador'])):
                        ?>
                            <a data-content="inline" data-aux-classes="tml-newsletter-modal tml-exit-light pill" data-toolbar="" data-modal-mode data-modal-width="600" data-lightbox-animation="fadeIn" href="#inscrever" class="lightbox-link column full-width button medium rounded hard-shadow border-blue-light border-hover-blue-light bkg-hover-blue-light color-blue-light color-hover-white">
                        <?php
                            //Se for inscrito e logado aponta o link.
                            elseif(isset($_SESSION['colaborador']) AND isset($_SESSION['usuario'])):
                        ?>
                            <a href="post?<?php echo $linkSeguro;?>&pos=<?php echo $dLinkUltimaPubli['referencia'];?>" class="column full-width button medium rounded hard-shadow border-blue-light border-hover-blue-light bkg-hover-blue-light color-blue-light color-hover-white">
                        <?php
                            endif;
                            else:
                        ?>
                            <a href="post?<?php echo $linkSeguro;?>&pos=<?php echo $dLinkUltimaPubli['referencia'];?>" class="column full-width button medium rounded hard-shadow border-hover-blue-light bkg-blue-light color-white color-hover-blue">
                        <?php
                            endif;
                        ?>
                            <span class="icon-text small color-blue-light color-hover-white"> </span>
                            <span class="text-large weight-bold color-blue-light color-hover-white"><?php echo '['.$dLinkUltimaPubli['categoria'].']: '.$dLinkUltimaPubli['titulo'];?></span>
                        </a>

                        <a href="https://open.spotify.com/show/1Lqiee1bJ0v1HeLct9UuLL?si=5Y87x0jwRo28F-fG41HTNQ&d_branch=1" class="column full-width button medium rounded hard-shadow border-hover-green-light bkg-green-light color-hover-green color-white">
                            <span class="icon-spotify small color-white color-hover-white"> </span>
                            <span class="text-large weight-bold color-green-light color-hover-white"> Podcast: Café na Emanuel</span>
                        </a>
                        <?php
                            if($diadalive !== 'Thu' AND $diadalive !== 'Sun'):
                            // Link para mobile abrindo direto o app do facebook no Android
                            if($Android):
                        ?>
                            <a href="fb://page/100665391991986" class="column hide show-on-mobile show-on-tablet full-width button medium rounded hard-shadow border-hover-facebook bkg-facebook color-hover-facebook color-white">
                                <span class="icon-facebook small color-facebook color-hover-white"> </span>
                                <span class="text-large weight-bold color-facebook color-hover-white"> Facebook</span>
                            </a>
                        <?php
                            // Link para mobile abrindo direto o app do facebook no iOS
                            elseif($iphone OR $ipad):
                        ?>
                            <a href="fb://page/?id=100665391991986" class="column hide show-on-mobile show-on-tablet full-width button medium rounded hard-shadow border-hover-facebook bkg-facebook color-hover-facebook color-white">
                                <span class="icon-facebook small color-facebook color-hover-white"> </span>
                                <span class="text-large weight-bold color-facebook color-hover-white"> Facebook</span>
                            </a>
                        <?php
                            else:
                            // Link para mobile abrindo direto o site do facebook no computador
                        ?>
                        <a href="https://facebook.com/emanueligrejabr" class="column hide-on-mobile hide-on-tablet full-width button medium rounded hard-shadow border-hover-facebook bkg-facebook color-hover-facebook color-white">
                            <span class="icon-facebook small color-facebook color-hover-white"> </span>
                            <span class="text-large weight-bold color-facebook color-hover-white"> Facebook</span>
                        </a>
                        <?php
                            endif;
                            endif;
                        ?>

                        <a href="https://www.youtube.com/channel/UCnN_PstpRXldHdQeXToS78g" class="column full-width button medium rounded border-hover-youtube bkg-youtube color-hover-youtube color-white">
                            <span class="icon-youtube small color-hover-youtube color-white"> </span>
                            <span class="text-large weight-bold color-hover-youtube color-white"> Youtube</span>
                        </a>
                        <a href="https://instagram.com/emanueligrejabr" class="column full-width button medium rounded hard-shadow border-hover-charcoal bkg-gradient-royal-garden color-white color-hover-white">
                            <span class="icon-instagram small color-hover-white color-white"> </span>
                            <span class="text-medium weight-bold color-hover-white color-white"> Instagram</span>
                        </a>
                        <a href="https://tiktok.com/@igrejaemanuel" class="column full-width button medium rounded hard-shadow  border-hover-black bkg-black color-hover-charcoal color-white">
                            <span class="icon-tumblr small color-hover-charcoal color-white"> </span>
                            <span class="text-medium weight-bold color-hover-charcoal color-white"> TikTok</span>
                        </a>
                    </div>
                </div>
            </div>
            
            
			<?php include "./_modalevento.php"; ?>

			<!-- Footer -->
			<footer class="footer footer-blue">
				<? include "./_footer_top.php"; ?>
			</footer>
			<!-- Footer End -->

		</div>
	</div>

	<? include "./_script.php"; ?>
</body>
</html>