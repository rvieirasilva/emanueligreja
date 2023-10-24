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

    if(isset($_POST['btn-quemsomos'])):
        $titulo             = mysqli_escape_string($con, $_POST['titulo']);
        $subtitulo          = mysqli_escape_string($con, $_POST['subtitulo']);
        $texto              = mysqli_escape_string($con, $_POST['txtArtigo']);
        
        $inQS = "INSERT INTO personalizarquemsomos (dia, mes, ano, titulo, subtitulo, texto) VALUES ('$dia', '$mesnumero', '$ano', '$titulo', '$subtitulo', '$texto')";
        if(mysqli_query($con, $inQS)):
            $_SESSION['cordamensagem'] = "green";
            $_SESSION['mensagem'] = "Atualização realizada com sucesso";
        else:
            $_SESSION['cordamensagem'] = "red";
            $_SESSION['mensagem'] = "Verifique sua conexão. Erro ao atualizar página.";
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
                                                        <h1 class="mb-0">Editar página <strong>Quem somos</strong></h1>
                                                        <p class="text-large color-charcoal mb-0 mb-mobile-20">Por isso não corro sem objetivo.</p>
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
                                    <form action="" charset="utf-8" method="post">
                                        <div class="column width-12">
                                            <span class="color-black"><strong>Título</strong></span>
                                            <div class="field-wrapper">
                                                <input type="text"  name="titulo" maxlength="100" class="form-aux form-date form-element large" placeholder="Título da página" tabindex="5">
                                            </div>
                                        </div>                                
                                        <div class="column width-12">
                                            <span class="color-black"><strong>Subtítulo</strong></span>
                                            <div class="field-wrapper">
                                                <input type="text"  name="subtitulo" maxlength="100" class="form-aux form-date form-element large" placeholder="Subtítulo da página" tabindex="5">
                                            </div>
                                        </div>                                
                                        <div class="column width-12">
                                            <span class="color-black text-small">TEXTO</span>
                                            <div class="field-wrapper">
                                                <textarea cols="20" rows="150" name="txtArtigo" id="txtArtigo" class="form-message form-element large" placeholder="Descrição do case. (Até 5.000 caracteres)." tabindex="36" ></textarea>
                                            </div>
                                        </div>
                                        <div class="column width-12 pt-30">
                                            <button type="submit" name="btn-quemsomos" class="form-submit button pill small border-theme bkg-hover-theme color-theme color-hover-white">PUBLICAR</button>
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
    
</body>
</html>