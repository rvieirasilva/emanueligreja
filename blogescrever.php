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

	if(isset($_POST['btn-addcategoria'])):
		$categoria = mysqli_escape_string($con, $_POST['categorianova']);
		$titulo             = mysqli_escape_string($con, $_POST['titulo']);
		$audio       		= mysqli_escape_string($con, $_POST['audio']);
		$datadapostagem     = mysqli_escape_string($con, $_POST['datadapostagem']);
		$horadapostagem     = mysqli_escape_string($con, $_POST['horadapostagem']);
		$textoancora        = mysqli_escape_string($con, $_POST['textoancora']);
		$texto          	= mysqli_escape_string($con, $_POST['txtArtigo']);
		$bibliografia       = mysqli_escape_string($con, $_POST['bibliografia']);
		$videoyoutube       = mysqli_escape_string($con, $_POST['videoyoutube']);

		$fileaudio = $_FILES['audio'];
		$filevideo = $_FILES['video'];
		$filecapa = $_FILES['capa'];
		$fileanexo = $_FILES['anexo'];
		$fileminiatura = $_FILES['miniatura'];
			
		$duplicado="SELECT id FROM blogcategorias WHERE categoria='$categoria'";
		$rd=mysqli_query($con, $duplicado);
		$nd=mysqli_num_rows($rd);
		if($nd > 0):
			$_SESSION['mensagem']="Já existe está categoria registrada.";
		else:
			$inserir="INSERT INTO blogcategorias (categoria) VALUES ('$categoria')";
			mysqli_query($con, $inserir);
			$_SESSION['mensagem']="Categoria adicionada com sucesso, <strong>atualize a página</strong>.";
			//header("Location:_categoriaparaposts.php");
		endif;
	endif;

	if(isset($_POST['btn-finalizar'])):
		$referencia         = mt_rand(0001,9999);
		if(!empty($_POST['categorianova'])):
			$categoria = mysqli_escape_string($con, $_POST['categorianova']);
				$duplicado="SELECT id FROM blogcategorias WHERE categoria='$categoria'";
				$rd=mysqli_query($con, $duplicado);
				$nd=mysqli_num_rows($rd);
				if($nd < 1):
					$inserir="INSERT INTO blogcategorias (categoria) VALUES ('$categoria')";
					mysqli_query($con, $inserir);
					$_SESSION['mensagem']="Categoria adicionada com sucesso, <strong>atualize a página</strong>.";
					//header("Location:_categoriaparaposts.php");
				endif;
		elseif(empty($_POST['categorianova'])):
			$categoria = mysqli_escape_string($con, $_POST['categoria']);
		endif;

		// $categoria0          = mysqli_escape_string($con, $_POST['categoria']); //Nova Categoria escrita.
		// $ncategorias = count($_POST['categoria']); if($ncategorias > 1): $categoria = implode(",", $_POST['categoria']); else: $categoria = mysqli_escape_string($con, $_POST['categoria']); endif;
		$titulo             = mysqli_escape_string($con, $_POST['titulo']);
		$audio       		= mysqli_escape_string($con, $_POST['audio']);
		$datadapostagem     = mysqli_escape_string($con, $_POST['datadapostagem']);
		$horadapostagem     = mysqli_escape_string($con, $_POST['horadapostagem']);
			if(empty($horadapostagem)): $horadapostagem=date('H:m'); endif;
			$datadapostagemHora = $datadapostagem.' '.$horadapostagem;
		$textoancora        = mysqli_escape_string($con, $_POST['textoancora']);
		$texto          	= mysqli_escape_string($con, $_POST['txtArtigo']);
		$bibliografia       = mysqli_escape_string($con, $_POST['bibliografia']);
		$videoyoutube       = mysqli_escape_string($con, $_POST['videoyoutube']);

		if(!empty($videoyoutube)):
			$videoyoutubeF	= explode('/', $videoyoutube); //https: // youtube.com / embed / w00JkhGoII0
			@$videoyoutube_EMBED = $videoyoutubeF[3];
			@$videoyoutube_URL	 = $videoyoutubeF[2];

			if($videoyoutube_EMBED == 'embed'):
				$videoyoutube	=	$videoyoutube;
			elseif($videoyoutube_URL == 'youtu.be'): //Ver se é https://youtu.be/w00JkhGoII0
				$videoyoutube	= "https://youtube.com/embed/$videoyoutube_EMBED";
			elseif($videoyoutube_URL == 'www.youtube.com'): //https://www.youtube.com/watch?v=_OqnE0OsmnQ
				$videoyoutube = str_replace('watch?v=', '', $videoyoutube_EMBED); //Tira o texto antes do link do video.
				$videoyoutube = "https://youtube.com/embed/$videoyoutube";
			else: //Se for só a URL w00JkhGoII0
				$videoyoutube	= "https://youtube.com/embed/$videoyoutube";
			endif;
		endif;

		//Áudio EXCLUSIVO ~ Podcast
		if(!empty($_FILES['audio']['name'])):
			$extensaoaudio = pathinfo($_FILES['audio']['name'], PATHINFO_EXTENSION);
			if(in_array($extensaoaudio, $formatospermitidosaudios)):
				@mkdir("arqblog/", 0777); //Cria a pasta se não houver. 
				@mkdir("arqblog/audio/", 0777); //Cria a pasta se não houver. 
				@mkdir("arqblog/audio/$matricula/", 0777); //Cria a pasta se não houver. 
				$pastaaudio			= "arqblog/audio/$matricula/";
				$temporarioaudio	    = $_FILES['audio']['tmp_name'];
				$novoNomeaudio		= mt_rand(10, 9999).'.'.$extensaoaudio;
					$audio		= $pastaaudio.$novoNomeaudio;
				move_uploaded_file($temporarioaudio, $audio);
			endif;
		endif;
		
		//VÍDEO EXCLUSIVO
		if(!empty($_FILES['video']['name'])):
			$extensaovideo = pathinfo($_FILES['video']['name'], PATHINFO_EXTENSION);
			if(in_array($extensaovideo, $formatospermitidosvideos)):
				@mkdir("arqblog/", 0777); //Cria a pasta se não houver. 
				@mkdir("arqblog/video/", 0777); //Cria a pasta se não houver. 
				@mkdir("arqblog/video/$matricula/", 0777); //Cria a pasta se não houver. 
				$pastavideo			= "arqblog/video/$matricula/";
				$temporariovideo	    = $_FILES['video']['tmp_name'];
				$novoNomevideo		= mt_rand(10, 9999).'.'.$extensaovideo;
					$video		= $pastavideo.$novoNomevideo;
				move_uploaded_file($temporariovideo, $pastavideo.$novoNomevideo);
			endif;
		endif;
		
		//ANEXO
		if(!empty($_FILES['anexo']['name'])):
			$extensaoanexo = pathinfo($_FILES['anexo']['name'], PATHINFO_EXTENSION);
			if(in_array($extensaoanexo, $formatospermitidos)):
				@mkdir("arqblog/", 0777); //Cria a pasta se não houver. 
				@mkdir("arqblog/anexo/", 0777); //Cria a pasta se não houver. 
				@mkdir("arqblog/anexo/$matricula/", 0777); //Cria a pasta se não houver. 
				$pastaanexo			= "arqblog/anexo/$matricula/";
				$temporarioanexo	    = $_FILES['anexo']['tmp_name'];
				$novoNomeanexo		= mt_rand(10,9999).'.'.$extensaoanexo;
					$anexo		= $pastaanexo.$novoNomeanexo;
				move_uploaded_file($temporarioanexo, $pastaanexo.$novoNomeanexo);
			endif;
		endif;	

		//CAPA
		if(!empty($_FILES['capa']['name'])):
			$extensaocapa = pathinfo($_FILES['capa']['name'], PATHINFO_EXTENSION);
			if(in_array($extensaocapa, $formatospermitidosimagens)):
				$img_capa 			= $_FILES["capa"]['name'];
				@mkdir("arqblog/", 0777); //Cria a pasta se não houver. 
				@mkdir("arqblog/capa/", 0777); //Cria a pasta se não houver. 
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
				move_uploaded_file($temporariocapa, $capa);
			endif;
		endif;

		if(!empty($_FILES['video']['name'])):
			$upvideo = $video;
		elseif(!empty($videoyoutube)):
			$upvideo = $videoyoutube;
		else:
			$upvideo = '';
		endif;
		
		//Envia várias miniaturas >>>> (CARROSSEL) <<<< para o mesmo post.
		$contagemdasminiaturas = count($_FILES['miniatura']['name']);
		for($imgmini = 0; $imgmini < count($_FILES['miniatura']['name']); $imgmini++) {
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
					move_uploaded_file($temporariominiatura, $miniatura);
				endif;
				
				if($contagemdasminiaturas <= 1):
					if(empty($_FILES['capa']['name'])): $capa = $miniatura; endif;
					$inserirminiaturas= "INSERT INTO blog (qrcode, referencia, titulo, texto, audio, video, textoancora, categoria, autor, cargo, datadapostagem, horadapostagem, anexo, publico, ano, mes, miniatura, capa, bibliografia, visualizacoes) VALUES ('', '$referencia', '$titulo', '$texto', '$audio', '$upvideo', '$textoancora', '$categoria', '$matricula', '$cargo', '$datadapostagemHora', '$horadapostagem', '$anexo', '', '$ano', '$mes', '$miniatura', '$capa', '$bibliografia', '')";
				elseif($contagemdasminiaturas > 1):
					$inserirminiaturas= "INSERT INTO blog (qrcode, referencia, titulo, texto, audio, video, textoancora, categoria, autor, cargo, datadapostagem, horadapostagem, anexo, publico, ano, mes, miniatura, capa, bibliografia, visualizacoes) VALUES ('', '$referencia', '$titulo', '', '', '', '', '$categoria', '$matricula', '$cargo', '$datadapostagemHora', '$horadapostagem', '', '', '$ano', '$mes', '$miniatura', '', '', '')";
				endif;

				if(mysqli_query($con, $inserirminiaturas)):
					// move_uploaded_file($temporariominiatura, $miniatura);

					$_SESSION['mensagem']="Parabéns, texto adicionado.";
					//Dados para registrar o log do cliente.
					$mensagem = ("\n$nomedousuario ($matricula) Postou o texto $titulo. Isto através do IP: $ip, na data $dia às $hora.\n"); //Escreva a mensagem que será gravada no log.
					include "./registrolog.php";
					//Fim do registro do log.
				else:
					$erroaoenviarBD=true;
					$_SESSION['mensagem']="Erro ao criar publicação.";
					if(!empty($miniatura)):
						unlink($miniatura);
					endif;
				endif;	
			endif;
		}

		if($erroaoenviarBD):
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
			if(!empty($capa)):
				unlink($capa);
			endif;
			$erroaoenviarBD = false;
		endif;

		//Adicionar nova categoria - QUando podia enviar várias categorias para um post
		// for ($ic=0; $ic < count($categoria0); $ic++) {
		// 	$buscarcategorianova = $categoria0[$ic];
		// 	$bcategoriasregistradas="SELECT id FROM blogcategorias WHERE categoria='$buscarcategorianova'";
		// 	  $rbcr=mysqli_query($con, $bcategoriasregistradas);
		// 	    $nbcr=mysqli_num_rows($rbcr);
		// 	if($nbcr < 1):
		// 		$inCN="INSERT INTO blogcategorias (categoria) VALUES ('$buscarcategorianova')";
		// 		mysqli_query($con, $inCN);
		// 		$_SESSION['mensagem']="Categoria adicionada com sucesso, <strong>atualize a página</strong>.";
		// 	endif;
		// }

		header("refresh:3");
	
		// move_uploaded_file($temporarioaudio, $audio);
		// move_uploaded_file($temporariovideo, $pastavideo.$novoNomevideo);
		// move_uploaded_file($temporariocapa, $capa);
		// move_uploaded_file($temporarioanexo, $pastaanexo.$novoNomeanexo);

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
                                                        <h1 class="mb-0">Escrever/publicar <strong>conteúdo para o blog</strong></h1>
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
                                        <form class="form" action="" charset="UTF-8" method="post" enctype="Multipart/form-data">
                                            <div class="row">
                                                <div class="column width-12">
                                                    <span class="color-black"><strong>Categorias.</strong></span>
												</div>
												<div class="column width-11">
													<div class="field-wrapper">
														<input type="text"  name="categorianova" maxlength="100" class="form-aux form-date form-element large" placeholder="Adicionar nova categoria" tabindex="5">
													</div>
												</div>
												<div class="column width-1 right">
													<div class="field-wrapper">
														<button type="submit"  name="btn-addcategoria" maxlength="100" class=" button pill bkg-hover-theme color-white color-hover-white bkg-theme small" tabindex="5"><span class="icon-plus color-white color-hover-white circled"></span></button>
													</div>
												</div>												
												<div class="column width-12 pt-10 pb-10">
													<div class="box rounded small border-blue">
														<div class="field-wrapper color-black ">
															<?php
																	$exibircategorias="SELECT categoria FROM blogcategorias GROUP BY categoria ORDER BY categoria ASC";
																	$rc=mysqli_query($con, $exibircategorias);
																	while($dc=mysqli_fetch_array($rc)):
																		// if($dc['categoria'] == "Mensagens"): $marcarcat='checked'; else: $marcarcat=''; endif;
															?>
															<span>
																<input id="<?php echo $dc['categoria']; ?>" value="<?php echo $dc['categoria']; ?>" class="form-element radio small v-align-middle" name="categoria" type="radio">
																<label for="<?php echo $dc['categoria']; ?>" class="radio-label"><?php echo $dc['categoria']; ?></label>
															</span>
															<? endwhile; ?>
														</div>
													</div>
												</div>
											</div>
											<div class="row">
                                                <div class="column width-6">
                                                    <span class="color-black"><strong>Título do post.</strong></span>
                                                    <div class="field-wrapper">
                                                        <input type="text" <? if(isset($_POST['btn-addcategoria']) OR isset($_POST['btn-finalizar'])): echo "value='".$titulo."'"; endif;?> name="titulo" maxlength="100" class="form-aux form-date form-element large" placeholder="Título do texto" tabindex="5">
                                                    </div>
                                                </div>
                                                <div class="column width-3">
                                                    <span class="color-black"><strong>PROGRAMAR POST PARA.</strong></span>
                                                    <div class="field-wrapper">
                                                        <input type="date" <? if(isset($_POST['btn-addcategoria']) OR isset($_POST['btn-finalizar'])): echo "value='".$datadapostagem."'"; endif;?> name="datadapostagem" value="<?php echo $ProgramarPostaPara;?>" class="form-aux form-date form-element large"  tabindex="5">
                                                    </div>
                                                </div>
                                                <div class="column width-3">
                                                    <span class="color-black"><strong>Horário.</strong></span>
                                                    <div class="field-wrapper">
                                                        <input type="time" <? if(isset($_POST['btn-addcategoria']) OR isset($_POST['btn-finalizar'])): echo "value='".$horadapostagem."'"; endif;?> name="horadapostagem" value="<?php echo $ProgramarPostaParaHorario;?>" class="form-aux form-date form-element large"  tabindex="5">
                                                    </div>
                                                </div>
                                                <div class="column width-12">
                                                    <span class="color-black">ÁUDIO <strong></strong></span>
                                                    <div class="field-wrapper">
                                                        <input type="file" name="audio" class=" color-white color-hover-white small bkg-blue bkg-hover-blue rounded" placeholder="portfolio" tabindex="5">
                                                    </div>
                                                </div>
                                                <div class="column width-6">
                                                    <span class="color-black">VÍDEO <strong>EXCLUSIVO</strong></span>
                                                    <div class="field-wrapper">
                                                        <input type="file" <? if(isset($_POST['btn-addcategoria']) OR isset($_POST['btn-finalizar'])): echo "value='".$video."'"; endif;?> name="video" class=" color-white color-hover-white small bkg-red bkg-hover-red-light rounded" placeholder="portfolio" tabindex="5">
                                                    </div>
                                                </div>
                                                <div class="column width-6">
                                                    <span class="color-black">VÍDEO <strong>DO YOUTUBE</strong></span>
                                                    <div class="field-wrapper">
                                                        <input type="text" <? if(isset($_POST['btn-addcategoria']) OR isset($_POST['btn-finalizar'])): echo "value='".$_POST['videoyoutube']."'"; endif;?> name="videoyoutube" class="form-aux form-date form-element large" maxlength="150" placeholder="*** 7xSpHlV3ruY" tabindex="5">
                                                    </div>
                                                </div>
                                                <div class="column width-12">
                                                    <span class="color-black"><strong>Texto de destaque.</strong></span>
                                                    <div class="field-wrapper">
                                                        <textarea type="text" cols="5" rows="10" <? if(isset($_POST['btn-addcategoria']) OR isset($_POST['btn-finalizar'])): echo "value='".$textoancora."'"; endif;?> name="textoancora" maxlength="500" class="form-aux form-date form-element large" placeholder="Digite aqui o texto de destaque deste post. // Máximo de 500 caracteres." tabindex="5"></textarea>
                                                    </div>
                                                </div>
                                                <div class="column width-6">
                                                    <span class="color-black">CAPA DO POST. (2500 x 1200px)</span>
                                                    <div class="field-wrapper">
                                                        <input type="file" name="capa" class="form-aux form-date form-element large" placeholder="portfolio" tabindex="5">
                                                    </div>
                                                </div>
                                                <div class="column width-6">
                                                    <span class="color-black">MINIATURA</span>
                                                    <div class="field-wrapper">
                                                        <input type="file" name="miniatura[]" multiple="multiple" class="form-aux form-date form-element large" placeholder="portfolio" tabindex="5 required">
                                                    </div>
                                                </div>
                                                <div class="column width-12">
                                                    <span class="color-black text-small">TEXTO</span>
                                                    <div class="field-wrapper">
                                                        <textarea cols="20" rows="150" name="txtArtigo" id="txtArtigo" class="form-message form-element large" placeholder="Descrição do case. (Até 5.000 caracteres)." tabindex="36" ><? if(isset($_POST['btn-addcategoria']) OR isset($_POST['btn-finalizar'])): echo $texto; endif;?></textarea>
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
                                                        <textarea type="text" cols="5" rows="10" name="bibliografia" maxlength="4800" class="form-aux form-date form-element large" placeholder="Informe a bibliografia do seu texto" tabindex="5"><? if(isset($_POST['btn-addcategoria']) OR isset($_POST['btn-finalizar'])): echo $bibliografia; endif;?></textarea>
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