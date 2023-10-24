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

    if(isset($_POST['btn-finalizar'])):

		$referencia         = rand();
		$conceito             = mysqli_escape_string($con, $_POST['txtArtigo']);
		$autor             	  = mysqli_escape_string($con, $_POST['autor']);
		$autorfavorito        = mysqli_escape_string($con, $_POST['autorfavorito']);

			if(!empty($autorfavorito)):
				$autor		=	$autorfavorito;
			elseif(!empty($autor)):
				$autor 		=	$autor;
			else:
				$autor 		= 'Rafael Vieira';
			endif;
		

		$datadapostagem       = mysqli_escape_string($con, $_POST['datadapostagem']);
		
		$duplicado="SELECT id FROM conceitos WHERE conceito='$conceito' AND autor='$autor'";
		  $rduplicado=mysqli_query($con, $duplicado);
		    $nrd=mysqli_num_rows($rduplicado);

		if($nrd > '0'):
			$_SESSION['mensagem']="Você inseriu este conceito anteriormente, favor alterar o conceito.";
			else:
				$inserir="INSERT INTO conceitos (referencia, conceito, autor, postadoem, postadopor) VALUES ('$referencia', '$conceito', '$autor', '$datadapostagem', '$nomedocolaborador')";

			if(mysqli_query($con, $inserir)):
				$_SESSION['mensagem']="Parabéns, texto adicionado.";
					//Dados para registrar o log do cliente.
						$mensagem = ("\n$nomedocolaborador ($matricula) Adicionou o conceito $conceito. Isto através do IP: $ip, na data $dia às $hora.\n"); //Escreva a mensagem que será gravada no log.
						include "./registrolog.php";
					//Fim do registro do log.
			else:
				$_SESSION['mensagem']="Erro ao adicionar conceito.";
			endif;
		endif;
	endif;

	$ConceitoData="SELECT postadoem FROM conceitos order by postadoem DESC LIMIT 1";
	  $rConceitoData=mysqli_query($con, $ConceitoData);
		$dConceitoData = mysqli_fetch_array($rConceitoData);

	$UConceito = $dConceitoData['postadoem']; //Traz a data do último post para automatizar a data do novo conceito. Pega o último dia e adiciona 1.
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
                                                        <h1 class="mb-0"><strong>Escrever</strong> Frases.</h1>
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
                                <div class="column width-12">
                                    <div class="contact-form-container">
                                        <form class="form" action="" charset="UTF-8" method="post" enctype="Multipart/form-data">
                                            <div class="row">
                                            <div class="column width-4">
                                                    <span class="color-black"><strong>NOVO</strong> AUTOR DA FRASE</span>
                                                    <div class="field-wrapper">
                                                        <input type="text"  name="autor" class="form-aux form-date form-element large"  tabindex="5">
                                                    </div>
                                                </div>
                                                <div class="column width-4">
                                                    <span class="color-black">AUTOR <strong>FAVORITO</strong></span>
                                                    <div class="form-select form-element large">
                                                        <select name="autorfavorito" tabindex="32" class="form-aux" data-label="sexo">
                                                            <option selected="selected" value="">Escolha um autor já mencionado</option>
                                                            <?php
                                                                $bAC = "SELECT autor FROM conceitos GROUP BY autor";
                                                                $rAC = mysqli_query($con, $bAC);
                                                                    while($dAC = mysqli_fetch_array($rAC)):
                                                            ?>
                                                                <option><?php echo $dAC['autor']; ?></option>
                                                            <?php
                                                                endwhile;
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>
                                            <div class="column width-4">
                                                    <span class="color-black"><strong>PROGRAMAR POST PARA.</strong></span>
                                                    <div class="field-wrapper">
                                                        <input type="date"  name="datadapostagem" value="<?php echo date(('Y-m-d'), strtotime($UConceito . '+ 1 days'));?>" class="form-aux form-date form-element large"  tabindex="5">
                                                    </div>
                                                </div>
                                                <div class="column width-12">
                                                    <span class="color-black text-small">CONCEITO</span>
                                                    <div class="field-wrapper">
                                                        <!--<textarea maxlength="10" cols="20" rows="150" name="txtArtigo" id="txtArtigo" class="form-message form-element large" placeholder="Descrição do case. (Até 5.000 caracteres)." tabindex="36" ></textarea>-->
                                                        <textarea name="txtArtigo" class="form-element large" placeholder="Descrição do case. (Até 300 caracteres)." tabindex="36" ></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <br>
                                                <div class="column width-12">
                                                    <button type="submit" name="btn-finalizar" class="form-submit button pill small border-theme bkg-hover-theme color-theme color-hover-white">PUBLICAR</button>
                                                </div>
                                            </div>
                                        </form>
                                        <div class="form-response"></div>
                                    </div>
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