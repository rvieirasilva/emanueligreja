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
		$categoria          = "Esboço de célula";
		$referencia         = mt_rand(00000,9999);
		$titulo             = mysqli_escape_string($con, $_POST['titulo']);
		$datadapostagem     = mysqli_escape_string($con, $_POST['datadapostagem']);
		$horadapostagem     = mysqli_escape_string($con, $_POST['horadapostagem']);
			$datadapostagemHora = $datadapostagem.' '.$horadapostagem;
		$textoancora        = mysqli_escape_string($con, $_POST['textoancora']);
		$texto          	= mysqli_escape_string($con, $_POST['txtArtigo']);
		$bibliografia       = mysqli_escape_string($con, $_POST['bibliografia']);
		
		//CAPA
		if(!empty($_FILES['capa']['name'])):
			$extensaocapa = pathinfo($_FILES['capa']['name'], PATHINFO_EXTENSION);
			if(in_array($extensaocapa, $formatospermitidosimagens)):
				$img_capa 			= $_FILES["capa"]['name'];
				@mkdir("arqblog/capa/$matricula/", 0777); //Cria a pasta se não houver. 
				$pastacapa			= "arqblog/capa/$matricula/";
				$temporariocapa	    = $_FILES['capa']['tmp_name'];
				$tamanhodacapa	    = $_FILES['capa']['size'];
				$novoNomecapa		= mt_rand(10,9999).'.'.$extensaocapa;
					$capa		= $pastacapa.$novoNomecapa;
				
				//Compacta a imagem da capa								
				if($tamanhodacapa >= 1000000):
					$quality_capa = 30;	
				elseif($tamanhodacapa >= 5000000 OR $tamanhodacapa < 1000000):
					$quality_capa = 50;
				else:	
					$quality_capa = 60;	
				endif;

				function compress_image($img_capa, $capa, $quality_capa) {
					$info = getimagesize($img_capa);
					if ($info["mime"] == "image/jpeg") $image = imagecreatefromjpeg($img_capa);
					elseif ($info["mime"] == "image/gif") $image = imagecreatefromgif($img_capa);
					elseif ($info["mime"] == "image/png") $image = imagecreatefrompng($img_capa);
					imagejpeg($image, $capa, $quality_capa);
					return $capa;
				}
					
				$temporariocapa = compress_image($_FILES["capa"]["tmp_name"], $capa, $quality_capa); //Compacta a imagem 
			

				//move_uploaded_file($temporariocapa, $capa);
			endif;
		endif;

		//ANEXO
		if(!empty($_FILES['anexo']['name'])):
			$extensaoanexo = pathinfo($_FILES['anexo']['name'], PATHINFO_EXTENSION);
			if(in_array($extensaoanexo, $formatospermitidos)):
				@mkdir("arqblog/anexo/$matricula/", 0777); //Cria a pasta se não houver. 
				$pastaanexo			= "arqblog/anexo/$matricula/";
				$temporarioanexo	    = $_FILES['anexo']['tmp_name'];
				$novoNomeanexo		= mt_rand(10,9999).'.'.$extensaoanexo;
					$anexo		= $pastaanexo.$novoNomeanexo;
				//move_uploaded_file($temporarioanexo, $pastaanexo.$novoNomeanexo);
			endif;
		endif;	
		
		if(!empty($_FILES['video']['name'])):
			$upvideo = $video;
		elseif(!empty($videoyoutube)):
			$upvideo = $videoyoutube;
		else:
			$upvideo = '';
		endif;

		//Envia várias miniaturas para o mesmo post.
		$contagemdasminiaturas = count($_FILES['miniatura']['name']);
		for($imgmini = 0; $imgmini < $contagemdasminiaturas; $imgmini++) {
			if(!empty($_FILES['miniatura']['name'][$imgmini])):
				$extensaominiatura = pathinfo($_FILES['miniatura']['name'][$imgmini], PATHINFO_EXTENSION);
				if(in_array($extensaominiatura, $formatospermitidosimagens)):
					@mkdir("arqblog/", 0777); //Cria a pasta se não houver. 
					@mkdir("arqblog/miniatura/", 0777); //Cria a pasta se não houver. 
					@mkdir("arqblog/miniatura/$matricula/", 0777); //Cria a pasta se não houver. 

					$pastaminiatura			= "arqblog/miniatura/$matricula/";

					$Img_miniatura 			= $_FILES['miniatura']['name'][$imgmini];
					$tamanhodaminiatura		= $_FILES['miniatura']['size'][$imgmini];
					$temporariominiatura	= $_FILES['miniatura']['tmp_name'][$imgmini];

					$novoNomeminiatura		= mt_rand(10,9999).'.'.$extensaominiatura;
						$miniatura			= $pastaminiatura.$novoNomeminiatura;

					/*
						//Compacta a miniatura
						if($tamanhodaminiatura >= 1000000):
							$quality_miniatura = 20;	
						elseif($tamanhodaminiatura >= 5000000 AND $tamanhodaminiatura <= 1000000):
							$quality_miniatura = 40;
						else:	
							$quality_miniatura = 50;	
						endif;


						function compress_image($Img_miniatura, $miniatura, $quality_miniatura) {
							$info = getimagesize($Img_miniatura);
							if ($info["mime"] == "image/jpeg") $imageMiniatura = imagecreatefromjpeg($Img_miniatura);
							elseif ($info["mime"] == "image/gif") $imageMiniatura = imagecreatefromgif($Img_miniatura);
							elseif ($info["mime"] == "image/png") $imageMiniatura = imagecreatefrompng($Img_miniatura);
							imagejpeg($Img_miniatura, $miniatura, $quality_miniatura);
							return $miniatura;
						}
						$temporariominiatura = compress_image($temporariominiatura, $miniatura, $quality_miniatura);
						//Compacta a miniatura
					*/
					//move_uploaded_file($temporariominiatura, $miniatura);
				endif;

				//Para impedir erro na contagem das categorias, a primeira inserção é completa as demais só adiciona a miniatura e os dados fundamentais: Referencia, Autor, Data, titulo e a miniatura.
				if($contagemdasminiaturas <= 1):
					
					$inserirminiaturas= "INSERT INTO blog (qrcode, referencia, titulo, texto, audio, video, textoancora, categoria, autor, cargo, datadapostagem, horadapostagem, anexo, publico, ano, mes, miniatura, capa, bibliografia, visualizacoes) VALUES ('', '$referencia', '$titulo', '$texto', '', '', '$textoancora', '$categoria', '$matricula', '$cargo', '$datadapostagemHora', '$horadapostagem', '$anexo', '', '$ano', '$mes', '$miniatura', '$capa', '$bibliografia', '')";
				elseif($contagemdasminiaturas > 1):
					$inserirminiaturas= "INSERT INTO blog (qrcode, referencia, titulo, texto, audio, video, textoancora, categoria, autor, cargo, datadapostagem, horadapostagem, anexo, publico, ano, mes, miniatura, capa, bibliografia, visualizacoes) VALUES ('', '$referencia', '$titulo', '', '', '', '', '$categoria', '$matricula', '$cargo', '$datadapostagemHora', '$horadapostagem', '', '', '$ano', '$mes', '$miniatura', '$capa', '', '')";
				endif;
				
				if(mysqli_query($con, $inserirminiaturas)):
					move_uploaded_file($temporarioaudio, $audio);
					move_uploaded_file($temporariovideo, $pastavideo.$novoNomevideo);
					move_uploaded_file($temporariocapa, $capa);
					move_uploaded_file($temporarioanexo, $pastaanexo.$novoNomeanexo);
					move_uploaded_file($temporariominiatura, $miniatura);
					$_SESSION['mensagem']="Parabéns, texto adicionado.";
					//Dados para registrar o log do cliente.
					$mensagem = ("\n$nomedousuario ($matricula) Postou o esboço de célula; $titulo. Isto através do IP: $ip, na data $dia às $hora.\n"); //Escreva a mensagem que será gravada no log.
					include "./registrolog.php";
					//Fim do registro do log.
				else:
					$_SESSION['mensagem']="Erro ao criar publicação.";
					//Excluir arquivos enviados
					if(!empty($audio)):
						unlink($audio);
					endif;
					if(!empty($upvideo)):
						unlink($upvideo);
					endif;
					if(!empty($anexo)):
						unlink($anexo);
					endif;
					if(!empty($miniatura)):
						unlink($miniatura);
					endif;
					if(!empty($capa)):
						unlink($capa);
					endif;
				endif;
			else:
				if(!empty($_FILES['capa']['name']) AND $categoria !== 'Videos'):
					//Se houver uma capa e não for um post de vídeo, se o usuário deixar vazio será utilizado a mesma imagem de capa para o post como miniatura.
					$miniatura=$capa;
				else:
					$miniatura='';	
				endif;
				
				$inserir= "INSERT INTO blog (qrcode, referencia, titulo, texto, audio, video, textoancora, categoria, autor, cargo, datadapostagem, horadapostagem, anexo, publico, ano, mes, miniatura, capa, bibliografia, visualizacoes) VALUES ('', '$referencia', '$titulo', '$texto', '', '', '$textoancora', '$categoria', '$matricula', '$cargo', '$datadapostagemHora', '$horadapostagem', '$anexo', '', '$ano', '$mes', '$miniatura', '$capa', '$bibliografia', '')";

				if(mysqli_query($con, $inserir)):
					move_uploaded_file($temporarioaudio, $audio);
					move_uploaded_file($temporariovideo, $pastavideo.$novoNomevideo);
					move_uploaded_file($temporariocapa, $capa);
					move_uploaded_file($temporarioanexo, $pastaanexo.$novoNomeanexo);
					move_uploaded_file($temporariominiatura, $miniatura);
					
					$_SESSION['mensagem']="Parabéns, texto adicionado.";
					//Dados para registrar o log do cliente.
					$mensagem = ("\n$nomedousuario ($matricula) Postou o esboço de célula; $titulo. Isto através do IP: $ip, na data $dia às $hora.\n"); //Escreva a mensagem que será gravada no log.
					include "./registrolog.php";
					//Fim do registro do log.
				else:
					$_SESSION['mensagem']="Erro ao criar publicação, tente novamente mais tarde.";
					//Excluir arquivos enviados
					if(!empty($audio)):
						@unlink($audio);
					endif;
					if(!empty($upvideo)):
						@unlink($upvideo);
					endif;
					if(!empty($anexo)):
						@unlink($anexo);
					endif;
					if(!empty($miniatura)):
						@unlink($miniatura);
					endif;
					if(!empty($capa)):
						@unlink($capa);
					endif;
				endif;
			endif;
		}
	endif;

	$TextoData="SELECT datadapostagem, horadapostagem FROM blog order by datadapostagem DESC LIMIT 1";
	  $rTextoData=mysqli_query($con, $TextoData);
	    $dTextoData = mysqli_fetch_array($rTextoData);

		$UTexto = $dTextoData['datadapostagem']; //Traz a data do último post para automatizar a data do novo conceito. Pega o último dia e adiciona 1.
		$UTexto = explode(' ', $UTexto);
		$UTexto = $UTexto[0];
		
		$ProgramarPostaParaHorario = $dTextoData['horadapostagem'];
		$ProgramarPostaPara = date(('Y-m-d'), strtotime($UTexto . '+1 day'));

		if($ProgramarPostaPara < $data):
			$ProgramarPostaPara = date('Y-m-d');
		else:
			$ProgramarPostaPara = $ProgramarPostaPara;
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
                                                        <h1 class="mb-0">Publicar esboço de <strong>célula</strong></h1>
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
                                        <form class="form" action="<? echo $_SERVER['REQUEST_URI'];?>" charset="UTF-8" method="post" enctype="Multipart/form-data">
                                            <div class="row">
                                            <div class="column width-6">
                                                    <span class="color-black"><strong>Título esboço.</strong></span>
                                                    <div class="field-wrapper">
                                                        <input type="text"  name="titulo" maxlength="100" class="form-aux form-date form-element large" placeholder="Título do texto" tabindex="5">
                                                    </div>
                                                </div>
                                                <div class="column width-3">
                                                    <span class="color-black"><strong>PROGRAMAR PARA.</strong></span>
                                                    <div class="field-wrapper">
                                                        <input type="date"  name="datadapostagem" value="<?php echo $ProgramarPostaPara;?>" class="form-aux form-date form-element large"  tabindex="5">
                                                    </div>
                                                </div>
                                                <div class="column width-3">
                                                    <span class="color-black"><strong>Horário.</strong></span>
                                                    <div class="field-wrapper">
                                                        <input type="time"  name="horadapostagem" value="<?php echo $ProgramarPostaParaHorario;?>" class="form-aux form-date form-element large"  tabindex="5">
                                                    </div>
                                                </div>
                                                <!-- <div class="column width-12">
                                                    <span class="color-black">ÁUDIO <strong></strong></span>
                                                    <div class="field-wrapper">
                                                        <input type="file" name="audio" class=" color-white color-hover-white small bkg-blue bkg-hover-blue rounded" placeholder="portfolio" tabindex="5">
                                                    </div>
                                                </div>
                                                <div class="column width-6">
                                                    <span class="color-black">VÍDEO <strong>EXCLUSIVO</strong></span>
                                                    <div class="field-wrapper">
                                                        <input type="file" name="video" class=" color-white color-hover-white small bkg-red bkg-hover-red-light rounded" placeholder="portfolio" tabindex="5">
                                                    </div>
                                                </div>
                                                <div class="column width-6">
                                                    <span class="color-black">VÍDEO <strong>DO YOUTUBE</strong></span>
                                                    <div class="field-wrapper">
                                                        <input type="text" name="videoyoutube" class="form-aux form-date form-element large" maxlength="150" placeholder="*** 7xSpHlV3ruY" tabindex="5">
                                                    </div>
                                                </div> -->
                                            <div class="column width-12">
                                                    <span class="color-black"><strong>Texto de destaque.</strong></span>
                                                    <div class="field-wrapper">
                                                        <textarea type="text" cols="5" rows="10" name="textoancora" maxlength="500" class="form-aux form-date form-element large" placeholder="Digite aqui o texto de destaque deste post. // Máximo de 500 caracteres." tabindex="5"></textarea>
                                                    </div>
                                                </div>
                                                <!-- <div class="column width-12">
                                                    <span class="color-black"><strong>Categorias.</strong></span>
                                                    <div class="field-wrapper">
                                                        <iframe src="_categoriaparaposts.php" height="40px" scrolling="no"></iframe>
                                                        
                                                        <div class="field-wrapper pt-10 pb-10">
                                                        <?php
                                                                
                                                                include "_con.php";
                                                                include "_configuracao.php";
                
                                                                    $exibircategorias="SELECT id, categoria FROM blogcategorias";
                                                                    $rc=mysqli_query($con, $exibircategorias);
                                                                    while($dc=mysqli_fetch_array($rc)):
                                                                        if($dc['categoria'] == "Mensagens"):
                                                                ?>
                                                                <input id="radio-4-<?php echo $dc['id']; ?>" class="form-element radio rounded" name="categoria[]" value="<?php echo $dc['categoria']; ?>" type="radio" checked>
                                                                <label for="radio-4-<?php echo $dc['id']; ?>" class="radio-label color-black" tabindex="6"><?php echo $dc['categoria']; ?></label>
                                                            <?php else: ?>
                                                                <input id="radio-4-<?php echo $dc['id']; ?>" class="form-element radio rounded" name="categoria[]" value="<?php echo $dc['categoria']; ?>" type="radio">
                                                                <label for="radio-4-<?php echo $dc['id']; ?>" class="radio-label color-black" tabindex="6"><?php echo $dc['categoria']; ?></label>
                                                            <?php endif; endwhile; ?>
                                                        </div>
                                                    </div>
                                                </div> -->
                                            <div class="column width-6">
                                                    <span class="color-black">CAPA DO POST. (2500 x 1200px)</span>
                                                    <div class="field-wrapper">
                                                        <input type="file" name="capa" class="form-aux form-date form-element large" placeholder="portfolio" tabindex="5">
                                                    </div>
                                                </div>
                                            <div class="column width-6">
                                                    <span class="color-black">MINIATURA</span>
                                                    <div class="field-wrapper">
                                                        <input type="file" name="miniatura[]" multiple="multiple" class="form-aux form-date form-element large" placeholder="portfolio" tabindex="5">
                                                    </div>
                                                </div>
                                                <div class="column width-12">
                                                    <span class="color-black text-small">TEXTO</span>
                                                    <div class="field-wrapper">
                                                        <textarea cols="20" rows="150" name="txtArtigo" id="txtArtigo" class="form-message form-element large" placeholder="Descrição do case. (Até 5.000 caracteres)." tabindex="36" ></textarea>
                                                    </div>
                                                </div>
                                                <br>
                                                <div class="column width-12">
                                                    <span class="color-black">ANEXO</span>
                                                    <div class="field-wrapper">
                                                        <input type="file" name="anexo" class=" color-white color-hover-white small bkg-purple bkg-hover-purple-light pill" placeholder="portfolio" tabindex="5">
                                                    </div>
                                                </div>
                                                <div class="column width-12">
                                                    <span class="color-black"><strong>Bibliografia.</strong></span>
                                                    <div class="field-wrapper">
                                                        <textarea type="text" cols="5" rows="10" name="bibliografia" maxlength="4800" class="form-aux form-date form-element large" placeholder="Informe a bibliografia do seu texto" tabindex="5"></textarea>
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

		</div>
	</div>
    
	<? include "./_script.php"; ?>
    
</body>
</html>