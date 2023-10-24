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
        $tipo               = mysqli_escape_string($con, $_POST['tipo']);
        $fundo              = mysqli_escape_string($con, $_POST['fundo']);

        for($a = 0; $a < count($_FILES['imagemtoposite']['name']); $a++)
        {
            //CAPA
            $extensao = pathinfo($_FILES['imagemtoposite']['name'][$a], PATHINFO_EXTENSION);
                if(in_array($extensao, $formatospermitidosimagens)):
					@mkdir("img/", 0777); //Cria a pasta. @ pra ocultar erro caso ela exista.
					@mkdir("img/site/", 0777); //Cria a pasta. @ pra ocultar erro caso ela exista.
					@mkdir("img/site/topo/", 0777); //Cria a pasta. @ pra ocultar erro caso ela exista.
					$pasta			= "img/site/topo/";
					$temporario	    = $_FILES['imagemtoposite']['tmp_name'][$a];
					$novoNome		= $_FILES['imagemtoposite']['name'][$a];
						$imagem		= $pasta.$novoNome;
				endif;
        
            $duplicado="SELECT * FROM imagenssite WHERE pasta='$imagem'";
                $rduplicado=mysqli_query($con, $duplicado);
                    $nrd=mysqli_num_rows($rduplicado);


            if($nrd > '0'):
				$_SESSION['cordamensagem']="red";
                $_SESSION['mensagem']="Algumas imagens não foram registradas pois existe uma igual atrelada ao sistema.";
			else:
				$inserir="INSERT INTO imagenssite (tipo, fundo, pasta, postadoem, ano, descricao) VALUES ('$tipo', '$fundo', '$imagem', '$dataeng', '$anoatual', '')";

				if(mysqli_query($con, $inserir)):
                move_uploaded_file($temporario, $pasta.$novoNome);
					$_SESSION['cordamensagem']="green";
                    $_SESSION['mensagem']="Imagens inseridas no sistema.";
                    //Dados para registrar o log do cliente.
                        $mensagem = ("\n$nomedocolaborador ($matricula) Adicionou a imagem para o topo do site $imagem. Isto através do IP: $ip, na data $dia às $hora.\n"); //Escreva a mensagem que será gravada no log.
                        include "./registrolog.php";
                    //Fim do registro do log.					
				else:
					$_SESSION['cordamensagem']="red";
                    $_SESSION['mensagem']="Erro ao inserir imagem.";
                endif;
            endif;
        }
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
                                                        <h1 class="mb-0">Adicionar imagem para o <strong>site</strong></h1>
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
                                    <div class="contact-form-container">
                                        <form class="form" action="" method="post" enctype="Multipart/form-data">
                                            <div class="row">
                                                <div class="column width-6">
                                                    <span class="color-black text-xsmall">SELECIONE UMA IMAGEM PARA O TOPO DAS PÁGINAS DO SITE. (2500 x 1200px)</span>
                                                    <div class="field-wrapper">
                                                        <input type="file" multiple="multiple" name="imagemtoposite[]" class="form-aux form-date form-element large" placeholder="portfolio" tabindex="5">
                                                    </div>
                                                </div>
                                                
                                                <div class="column width-3">
                                                    <span	class="color-black">TIPO</span>
                                                    <div class="form-select form-element large color-black">
                                                        <select name="tipo" tabindex="32" class="form-aux" data-label="sexo">
                                                            <option selected="selected" value="TOPO">
                                                                Topo
                                                            </option>
                                                            <option value="MINIATURA">
                                                                Miniatura das frases
                                                            </option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="column width-3">
                                                    <span	class="color-black">Fundo</span>
                                                    <div class="form-select form-element large color-black">
                                                        <select name="fundo" tabindex="32" class="form-aux" data-label="sexo">
                                                            <option selected="selected" value="Escuro">
                                                                Escuro
                                                            </option>
                                                            <option value="Claro">
                                                                Claro
                                                            </option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="column width-12">
                                                    <button type="submit" name="btn-finalizar" class="form-submit button pill small border-theme bkg-hover-theme color-theme color-hover-white">SALVAR</button>
                                                </div>
                                            </div>
                                        </form>
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

		</div>
	</div>
    
	<? include "./_script.php"; ?>
    
</body>
</html>