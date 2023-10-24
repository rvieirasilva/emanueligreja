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

    
	$iddopost=$_GET['id'];
	$refdopost=$_GET['ref'];


	$id_altmini = $_GET['altmini'];	//Get gerado ao alterar miniatura múltiplas	

	
	if(isset($_POST['btn-addcategoria'])):
		$categoria = mysqli_escape_string($con, $_POST['categoria']);
		
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
	
	$buscarpost="SELECT * FROM blog WHERE id='$iddopost' AND referencia='$refdopost'";
		$rbp=mysqli_query($con, $buscarpost);
			$dbp=mysqli_fetch_array($rbp);

    if(isset($_POST['btn-finalizar'])):

		$referencia         = rand();
		$titulo             = mysqli_escape_string($con, $_POST['titulo']);
		$audio       		= mysqli_escape_string($con, $_POST['audio']);
		$videoyoutube       = mysqli_escape_string($con, $_POST['videoyoutube']);
		
		if(!empty($videoyoutube)):
			//Simplificar código retirando ou inserindo embed.
			$videoyoutubeF	= explode('/', $videoyoutube); //https: // youtube.com / embed / w00JkhGoII0
			@$videoyoutube_EMBED = $videoyoutubeF[3];
			@$videoyoutube_URL	 = $videoyoutubeF[2];

			if($videoyoutube_EMBED == 'embed'):
				$videoyoutube	=	$videoyoutube;
			elseif($videoyoutube_URL == 'youtu.be'): //Ver se é https://youtu.be/w00JkhGoII0
				$videoyoutube	= "https://youtube.com/embed/$videoyoutube_EMBED";
			else: //Se for só a URL w00JkhGoII0
				$videoyoutube	= "https://youtube.com/embed/$videoyoutube";
			endif;
		endif;

		$datadapostagem     = mysqli_escape_string($con, $_POST['datadapostagem']);
		$horadapostagem     = mysqli_escape_string($con, $_POST['horadapostagem']);
			$datadapostagemHora = $datadapostagem.' '.$horadapostagem;
			//$datadapostagemHora = strtotime($datadapostagemHora);
		$textoancora        = mysqli_escape_string($con, $_POST['textoancora']);
			//$textoancora1	= mb_substr($textoancora, 0, 280);

		$categoria          = mysqli_escape_string($con, $_POST['categoria']);
		$texto          = mysqli_escape_string($con, $_POST['txtArtigo']);
			//$texto1	= mb_substr($texto, 0, 14980);
		$bibliografia       = mysqli_escape_string($con, $_POST['bibliografia']);
			//$bibliografia1	= mb_substr($bibliografia, 0, 4880);

		//Áudio EXCLUSIVO
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
					@mkdir("arqblog/anexo/$matricula/", 0777); //Cria a pasta se não houver. 
					$pastaanexo			= "arqblog/anexo/$matricula/";
					$temporarioanexo	    = $_FILES['anexo']['tmp_name'];
					$novoNomeanexo		= mt_rand(10, 9999).'.'.$extensaoanexo;
						$anexo		= $pastaanexo.$novoNomeanexo;
				move_uploaded_file($temporarioanexo, $pastaanexo.$novoNomeanexo);
			endif;
		endif;
		
		if($_FILES['video']['name'] != ''):
				$upvideo = $video;
			elseif($videoyoutube != ''):
				$upvideo = $videoyoutube;
			else:
				$upvideo = '';
		endif;

		if($_FILES['anexo']['name'] != ''):
				$novoanexo = $anexo;
			else:
				$novoanexo = '';
		endif;

		if(!empty($audio)):
			//Atualizar áudio
			$bAudioAntigo = "SELECT audio FROM blog WHERE id='$iddopost' AND referencia='$refdopost'";
				$rAA = mysqli_query($con, $bAudioAntigo);
				$dAA = mysqli_fetch_array($rAA);
				
				$audioantigo = $dAA['audio'];
			
			$UpAudio = "UPDATE blog SET audio='$audio' WHERE id='$iddopost' AND referencia='$refdopost'";
			if(mysqli_query($con, $UpAudio)):
				@unlink($audioantigo); //Excluí o arquivo do áudio anterior do sistema
			endif;
			//Atualizar áudio
		endif;

		$inserir="UPDATE blog SET titulo='$titulo', texto='$texto', textoancora='$textoancora', categoria='$categoria',  datadapostagem='$datadapostagemHora', horadapostagem='$horadapostagem', ano='$ano', mes='$mes', bibliografia='$bibliografia' WHERE id='$iddopost' AND referencia='$refdopost'";
		
		if(mysqli_query($con, $inserir)):
			$_SESSION['cordamensagem']="green";
			$_SESSION['mensagem']="Parabéns, texto editado.";


				//Dados para registrar o log do cliente.
					$mensagem = ("\n$nomedocolaborador ($matricula) Editou o post; $titulo ($referencia). Isto através do IP: $ip, na data $dia às $hora.\n"); //Escreva a mensagem que será gravada no log.
					include "./registrolog.php";
				//Fim do registro do log.
			header('refresh:3');

			if(!empty($upvideo)):
				$atualizarvideo="UPDATE blog SET video='$upvideo' WHERE id='$iddopost' AND referencia='$refdopost'";
				if(mysqli_query($con, $atualizarvideo)):
				else:
					unlink($video);
					$_SESSION['cordamensagem']="youtube";
					$_SESSION['mensagem']="Erro ao enviar novo vídeo.";
				endif;
			endif;

			if($novoanexo !== ''):
				$atualizaranexo="UPDATE blog SET anexo='$novoanexo' WHERE id='$iddopost' AND referencia='$refdopost'";
				if(mysqli_query($con, $novoanexo)):
					else:
						unlink($novoanexo);
						$_SESSION['cordamensagem']="green";
						$_SESSION['mensagem']="Erro ao enviar novo anexo.";
				endif;
			endif;
		else:
			$_SESSION['cordamensagem']="youtube";
			$_SESSION['mensagem']="Erro ao atualizar texto.";
		endif;
	endif;

	//Adicionar nova miniatura
	if(isset($_POST['btn-adicionarminiatura'])):


		$referencia         = $refdopost;
		$titulo             = mysqli_escape_string($con, $_POST['titulo']);
		$audio       		= '';
		$videoyoutube       = '';
		
		$datadapostagem     = mysqli_escape_string($con, $_POST['datadapostagem']);
		$horadapostagem     = mysqli_escape_string($con, $_POST['horadapostagem']);
			$datadapostagemHora = $datadapostagem.' '.$horadapostagem;
			//$datadapostagemHora = strtotime($datadapostagemHora);
		$capa     			= mysqli_escape_string($con, $_POST['capanew']);
		$textoancora        = '';
			//$textoancora1	= mb_substr($textoancora, 0, 280);

		$categoria          = '';
		$texto          	= '';
			//$texto1	= mb_substr($texto, 0, 14980);
		$bibliografia       = '';
			//$bibliografia1	= mb_substr($bibliografia, 0, 4880);


		$extensao = pathinfo($_FILES['miniaturanew']['name'], PATHINFO_EXTENSION);
        if(in_array($extensao, $formatospermitidos)):
			//Pegar miniatura antiga e excluir 
			$bMiniAntiga = "SELECT miniatura FROM blog WHERE id='$iddopost'";
			  $rMA = mysqli_query($con, $bMiniAntiga);
				$dMA = mysqli_fetch_array($rMA);
				
				$miniaturaantiga = $dMA['miniaturanew'];


			//Adiciona a nova compactada
			
			$mini_0 				= $_FILES['miniaturanew']['name'];
			$tamanhodaminiatura		= $_FILES['miniaturanew']['size'];
			$pastaminiatura			= "arqblog/miniatura/$matricula/";
			$temporariominiatura	= $_FILES['miniaturanew']['tmp_name'];
			$novoNomeminiatura		= mt_rand(10, 9999).'.'.$extensao;
				$linkminiatura		= $pastaminiatura.$novoNomeminiatura;

			
			if($tamanhodaminiatura >= 1000000):
				$quality = 20;	
			elseif($tamanhodaminiatura >= 5000000 AND $tamanhodaminiatura <= 1000000):
				$quality = 40;
			else:	
				$quality = 50;	
			endif;

			function compress_image($mini_0, $linkminiatura, $quality) {
				$info = getimagesize($mini_0);
				if ($info["mime"] == "image/jpeg") $image = imagecreatefromjpeg($mini_0);
				elseif ($info["mime"] == "image/gif") $image = imagecreatefromgif($mini_0);
				elseif ($info["mime"] == "image/png") $image = imagecreatefrompng($mini_0);
				imagejpeg($image, $linkminiatura, $quality);
				return $linkminiatura;
			}
			
			$temporariominiatura = compress_image($_FILES["miniaturanew"]["tmp_name"], $linkminiatura, $quality);


			$video = '';
			$anexo = '';
			//Fim da compressão
            //$miniatura = "UPDATE blog SET miniatura='$linkminiatura' WHERE id='$id_altmini'";

			$miniatura= "INSERT INTO blog (referencia, titulo, texto, audio, video, textoancora, categoria, autor, datadapostagem, horadapostagem, anexo, publico, ano, mes, miniatura, capa, bibliografia, visualizacoes) VALUES ('$referencia', '$titulo', '$texto', '$audio', '$upvideo', '$textoancora', '$categoria', '$nomedocolaborador', '$datadapostagemHora', '$horadapostagem', '$anexo', '', '$ano', '$mes', '$linkminiatura', '$capa', '$bibliografia', '')";

			if (mysqli_query($con, $miniatura)):
				move_uploaded_file($temporariominiatura, $linkminiatura);
				  $_SESSION['cordamensagem'] = "green";
            		$_SESSION['mensagem'] = "Miniatura adicionada.";
				
					header("refresh:3; url=blogeditar?m=$matricula&id=$iddopost&token=$token&ref=$refdopost");
            else:
				$_SESSION['cordamensagem'] = "red";
            	$_SESSION['mensagem'] = "Erro ao adicionar nova miniatura";
				
					header("refresh:3; url=blogeditar?m=$matricula&id=$iddopost&token=$token&ref=$refdopost");                
			endif;
		else:
			$_SESSION['cordamensagem'] = "red";
			$_SESSION['mensagem'] = "Esse formato de imagem não é permitido. (Formato aceitáveis; JPG, PNG, BITMAP)";
		endif;
	endif;
	
	//Comando para o modal de alterar miniatura.
	if(isset($_POST['btn-alterarcapa'])):
        
        $extensao = pathinfo($_FILES['imagemcapa']['name'], PATHINFO_EXTENSION);
        if(in_array($extensao, $formatospermitidosimagens)):
			//Pegar capa antiga e excluir 
			$bCapaAntiga = "SELECT capa FROM blog WHERE id='$iddopost'";
			  $rCA = mysqli_query($con, $bCapaAntiga);
				$dCA = mysqli_fetch_array($rCA);
				
				$capaantiga = $dCA['capa'];


			//Adiciona a nova compactada
			
				$img_capa 			= $_FILES['imagemcapa']['name'];
				$pastacapa			= "arqblog/capa/$matricula/";
                $temporariocapa	    = $_FILES['imagemcapa']['tmp_name'];
                $tamanhodacapa	    = $_FILES['imagemcapa']['size'];
                $novoNomecapa		= mt_rand(10, 9999).'.'.$extensao;
					$linkcapa		= $pastacapa.$novoNomecapa;
				
				if($tamanhodacapa >= 1000000):
					$quality_capa = 30;	
				elseif($tamanhodacapa >= 5000000 AND $tamanhodacapa <= 1000000):
					$quality_capa = 50;
				else:	
					$quality_capa = 60;	
				endif;

				function compress_image($img_capa, $linkcapa, $quality_capa) {
					$info = getimagesize($img_capa);
					if ($info["mime"] == "image/jpeg") $image = imagecreatefromjpeg($img_capa);
					elseif ($info["mime"] == "image/gif") $image = imagecreatefromgif($img_capa);
					elseif ($info["mime"] == "image/png") $image = imagecreatefrompng($img_capa);
					imagejpeg($image, $linkcapa, $quality_capa);
					return $linkcapa;
				}
					
				$temporariocapa = compress_image($_FILES["imagemcapa"]["tmp_name"], $linkcapa, $quality_capa);
					
		
            $capa = "UPDATE blog SET capa='$linkcapa' WHERE referencia='$refdopost'";
			if (mysqli_query($con, $capa)):	

				@unlink($capaantiga); //Excluí a capa antiga. @ Caso não exista uma não aparecerá o erro.

            	move_uploaded_file($temporariocapa, $linkcapa);
            
				$_SESSION['cordamensagem'] = "green";
				$_SESSION['mensagem'] = "Capa atualizada.";
				header("refresh:3; url=blogeditar?m=$matricula&id=$iddopost&token=$token&ref=$refdopost");                
            else:
				$_SESSION['cordamensagem'] = "red";
                $_SESSION['mensagem'] = "Ocorreu um erro e a capa não foi atualizada.";
                //header("Location:ge_posteditarunico?id=$iddopost&".uniqid()."&tgestor=$token");
            endif;
        endif;
    endif;
	
    //Comando para o modal de alterar miniatura.
	if(isset($_POST['btn-alterarminiatura'])):
		$extensao = pathinfo($_FILES['miniatura']['name'], PATHINFO_EXTENSION);
        if(in_array($extensao, $formatospermitidos)):
			//Pegar miniatura antiga e excluir 
			$bMiniAntiga = "SELECT miniatura FROM blog WHERE id='$iddopost'";
			  $rMA = mysqli_query($con, $bMiniAntiga);
				$dMA = mysqli_fetch_array($rMA);
				
				$miniaturaantiga = $dMA['miniatura'];


			//Adiciona a nova compactada
			
			$mini_0 				= $_FILES['miniatura']['name'];
			$tamanhodaminiatura		= $_FILES['miniatura']['size'];
			$pastaminiatura			= "arqblog/miniatura/$matricula/";
			$temporariominiatura	= $_FILES['miniatura']['tmp_name'];
			$novoNomeminiatura		= mt_rand(10, 9999).'.'.$extensao;
				$linkminiatura		= $pastaminiatura.$novoNomeminiatura;

			
			if($tamanhodaminiatura >= 1000000):
				$quality = 20;	
			elseif($tamanhodaminiatura >= 5000000 AND $tamanhodaminiatura <= 1000000):
				$quality = 40;
			else:	
				$quality = 50;	
			endif;

			function compress_image($mini_0, $linkminiatura, $quality) {
				$info = getimagesize($mini_0);
				if ($info["mime"] == "image/jpeg") $image = imagecreatefromjpeg($mini_0);
				elseif ($info["mime"] == "image/gif") $image = imagecreatefromgif($mini_0);
				elseif ($info["mime"] == "image/png") $image = imagecreatefrompng($mini_0);
				imagejpeg($image, $linkminiatura, $quality);
				return $linkminiatura;
			}
			
			$temporariominiatura = compress_image($_FILES["miniatura"]["tmp_name"], $linkminiatura, $quality);

			//Fim da compressão
            $miniatura = "UPDATE blog SET miniatura='$linkminiatura' WHERE id='$id_altmini'";
			if (mysqli_query($con, $miniatura)):
				@unlink($miniaturaantiga); //Excluí a miniatura antiga. @ Caso não exista uma não aparecerá o erro.
				
				move_uploaded_file($temporariominiatura, $linkminiatura);
					$_SESSION['cordamensagem'] = "green";
            		$_SESSION['mensagem'] = "Miniatura atualizada.";
				
					header("refresh:3; url=blogeditar?m=$matricula&id=$iddopost&token=$token&ref=$refdopost");
            else:
				$_SESSION['cordamensagem'] = "red";
        		$_SESSION['mensagem'] = "Erro ao atualizar miniatura.";
			
				header("refresh:3; url=blogeditar?m=$matricula&id=$iddopost&token=$token&ref=$refdopost");
			endif;
		else:
			$_SESSION['cordamensagem'] = "red";
			$_SESSION['mensagem'] = "Esse formato de imagem não é permitido. (Formato aceitáveis; JPG, PNG, BITMAP)";
		endif;
	endif;

	//Excluir miniatura
	if(isset($_GET['excmini'])):
		$id_MiniExcluir=$_GET['excmini'];
		$refparaexc=$_GET['ref'];
	
		$excluir="DELETE FROM blog WHERE id='$id_MiniExcluir' AND referencia='$refparaexc'";
		if(mysqli_query($con, $excluir)):
			$_SESSION['cordamensagem']="red";
			$_SESSION['mensagem']="Miniatura excluida.";
			//Dados para registrar o log do cliente.
			$mensagem = ("\n$nomedocolaborador ($matricula) Excluíu a miniatura para post de referência: $refparaexc. Isto através do IP: $ip, na data $dia às $hora.\n"); //Escreva a mensagem que será gravada no log.
			include "./registrolog.php";
			//Fim do registro do log.
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
                                                        <h1 class="mb-0">Editar <strong>conteúdo do blog</strong></h1>
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
									<a data-content="inline" data-aux-classes="tml-newsletter-modal height-auto rounded" data-toolbar="" data-modal-mode data-modal-width="600" data-modal-animation="scaleIn" data-lightbox-animation="fadeIn" href="#modal_addcategoria" class="column full-width lightbox-link button rounded medium bkg-theme bkg-hover-theme color-white color-hover-white">Adicionar categoria</a>

									<div id="modal_addcategoria" class="pt-70 pb-50 hide">
										<div class="row">
											<div class="column width-10 offset-1 center">
												<!-- Info -->
												<h3 class="mb-10">Adicionar categoria</h3>
												<!-- Info End -->

												<!-- Signup -->
												<div class="signup-form-container">
													<form class="form merged-form-elements" action="" charset="utf-8" method="post" >
														<div class="row">
															<div class="column width-8">
																<div class="field-wrapper">
																	<input type="text" name="categoria" class="form-email form-element rounded medium left" placeholder="Escreva apenas uma categoria" tabindex="2" required>
																</div>
															</div>
															<div class="column width-4">
																<button type="submit" name="btn-addcategoria" class="form-submit button rounded medium bkg-theme bkg-hover-theme color-white color-hover-white">Adicionar</button>
															</div>
														</div>
													</form>
												</div>
												<!-- Signup End -->

											</div>
										</div>
									</div>
								</div>

								<hr>

                                <?php
                                    //Verificação conforme o número de miniaturas.

                                    $bPostQntd="SELECT id, miniatura FROM blog WHERE referencia='$refdopost'";
                                    $rPQntd=mysqli_query($con, $bPostQntd);
                                        $nPQntd = mysqli_num_rows($rPQntd);
                                        while($dPQntd=mysqli_fetch_array($rPQntd)):
                                            $mini_miniaturas[]  = $dPQntd['miniatura'];
                                            $mini_id[]			= $dPQntd['id'];
                                        endwhile;

                                    if($nPQntd > 1):
                                        $areaCapa = '12';
                                    else:
                                        $areaCapa = '8';
                                    endif;
                                ?>
                                
                                <div class="column width-<?php echo $areaCapa;?>">
                                    <h5 class="color-black"><strong>CAPA ATUAL.</strong>
                                    <a data-content="inline" data-aux-classes="tml-newsletter-modal tml-exit-light" data-toolbar="" data-modal-mode data-modal-width="600" data-lightbox-animation="fadeIn" href="#editarcapadopost" class="lightbox-link"><span class="color-black">(ALTERAR CAPA.)</span></a></h5>


                                    <img class="<center>" src="<?php echo $dbp['capa'];?>" width="800" height="400">
                                </div>

                                <?php
                                    for($editmini=0; $editmini < count($mini_miniaturas); $editmini++) {
                                ?>
                                    <div class="column width-4 pt-10 ">
                                    <h5 class="color-black">
                                        <strong>MINIATURA ATUAL.</strong>
                                        <a href= "blogeditar?m=<?php echo $matricula;?>&token=<?php echo $token;?>&id=<?php echo $iddopost;?>&ref=<?php echo $refdopost;?>&altmini=<?php echo $mini_id[$editmini];?>" data-content="inline" data-aux-classes="tml-newsletter-modal tml-exit-light" data-toolbar="" data-modal-mode data-modal-width="600" data-lightbox-animation="fadeIn" href="#editarminiaturadopost" class="overlay-link"><span class="color-black text-small">(ALTERAR MINIATURA.)</span></a>
                                    </h5>


                                    <a data-content="inline" data-aux-classes="tml-newsletter-modal tml-exit-light" data-toolbar="" data-modal-mode data-modal-width="600" data-lightbox-animation="fadeIn" href="#addminiatura" class="lightbox-link"><span class="color-black">+ Adicionar conteúdo</span></a>

                                    <span class="color-black"> | </span>

                                    <a href= "blogeditar?m=<?php echo $matricula;?>&token=<?php echo $token;?>&id=<?php echo $iddopost;?>&ref=<?php echo $refdopost;?>&excmini=<?php echo $mini_id[$editmini];?>" class="overlay-link"><span class="color-red">Excluir miniatura</span></a>

                                        <img class="<center>" src="<?php echo $mini_miniaturas[$editmini];?>" width="300" height="200">
                                        </div>
                                <?php
                                    }
                                ?>
                            </div>
                            <div class="box rounded rounded shadow border-blue bkg-white">
                                <div class="column width-12">
                                    
                                    <div class="contact-form-container">
                                        <form class="form" action="" charset="UTF-8" method="post" enctype="Multipart/form-data">
                                            <div class="row">
                                            <div class="column width-6">
                                                    <span class="color-black"><strong>Título do post.</strong></span>
                                                    <div class="field-wrapper">
                                                        <input type="text"  name="titulo" maxlength="100" class="form-aux form-date form-element large" value='<?php echo $dbp['titulo'];?>' placeholder="Título do texto" tabindex="5">
                                                    </div>
                                                </div>
                                                <div class="column width-3">
                                                    <span class="color-black"><strong>PROGRAMAR POST PARA.</strong></span>
                                                    <div class="field-wrapper">
                                                        <input type="date"  value="<?php $FilterData = $dbp['datadapostagem']; $FilterData = explode(' ', $FilterData); echo $FilterData[0]; ?>" name="datadapostagem" value="<?php echo date('Y-m-d');?>" class="form-aux form-date form-element large"  tabindex="5">
                                                    </div>
                                                </div>
                                                <div class="column width-3">
                                                    <span class="color-black"><strong>Horário.</strong></span>
                                                    <div class="field-wrapper">
                                                        <input type="time"  value="<?php echo $dbp['horadapostagem'];?>" name="horadapostagem" value="<?php echo date('H:i');?>" class="form-aux form-date form-element large"  tabindex="5">
                                                    </div>
                                                </div>
                                                <div class="column width-12">
                                                    <span class="color-black">ÁUDIO <strong></strong></span>
                                                    <div class="field-wrapper">
                                                        <input type="file" name="audio" class=" color-white color-hover-white small bkg-blue bkg-hover-blue rounded" placeholder="portfolio" tabindex="5">
                                                    </div>
                                                </div>
                                                <div class="column width-5">
                                                    <span class="color-black">VÍDEO <strong>EXCLUSIVO</strong></span>
                                                    <div class="field-wrapper">
                                                        <input type="file" name="video" class=" color-white color-hover-white small bkg-red bkg-hover-red-light rounded" placeholder="portfolio" tabindex="5">
                                                    </div>
                                                </div>
                                                <div class="column width-7">
                                                    <span class="color-black">VÍDEO <strong>DO YOUTUBE</strong></span>
                                                    <div class="field-wrapper">
                                                        <input type="text"  name="videoyoutube" class="form-aux form-date form-element large" placeholder="URL DO VÍDEO DO YOUTUBE NESSE FORMATO: https://youtube.com/embed/7W9dasm09" tabindex="5">
                                                    </div>
                                                </div>
                                            <div class="column width-12">
                                                    <span class="color-black"><strong>Texto de destaque.</strong></span>
                                                    <div class="field-wrapper">
                                                        <textarea type="text" cols="5" rows="10" name="textoancora" maxlength="500" class="form-aux form-date form-element large" placeholder="Digite aqui o texto de destaque deste post. // Máximo de 500 caracteres." tabindex="5"><?php echo $dbp['textoancora'];?></textarea>
                                                    </div>
                                                </div>
                                                <div class="column width-12">
                                                    <span	class="color-black">CATEGORIA</span>
                                                    <div class="form-select form-element large color-black">
                                                        <select name="categoria" tabindex="32" class="form-aux" data-label="sexo">
                                                            <option selected="selected"><?php echo $dbp['categoria']; ?></option>
                                                                <?php
                                                                    $exibircategorias="SELECT * FROM blogcategorias";
                                                                    $rc=mysqli_query($con, $exibircategorias);
                                                                    while($dc=mysqli_fetch_array($rc)):
                                                                ?>
                                                            <option><?php echo $dc['categoria'];?></option>
                                                                    <?php endwhile; ?>	
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="column width-12">
                                                    <span class="color-black text-small">TEXTO</span>
                                                    <div class="field-wrapper">
                                                        <textarea maxlength="10" cols="20" rows="150" name="txtArtigo" id="txtArtigo" class="form-message form-element large" placeholder="Descrição do case. (Até 5.000 caracteres)." tabindex="36" ><?php echo $dbp['texto'];?></textarea>
                                                    </div>
                                                </div>
                                                <br>
                                                <div class="column width-12">
                                                    <span class="color-black">ANEXO</span>
                                                    <div class="field-wrapper">
                                                        <input type="file" name="anexo" class="color-white color-hover-white small bkg-purple bkg-hover-purple-light pill" placeholder="portfolio" tabindex="5">
                                                    </div>
                                                </div>
                                                <div class="column width-12">
                                                    <span class="color-black"><strong>Bibliografia.</strong></span>
                                                    <div class="field-wrapper">
                                                        <textarea type="text" cols="5" rows="10" name="bibliografia" maxlength="4800" class="form-aux form-date form-element large" placeholder="Informe a bibliografia do seu texto" tabindex="5"><?php echo $dbp['bibliografia'];?></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <br>
                                                <div class="column width-12">
                                                    <button type="submit" name="btn-finalizar" class="form-submit button pill small border-theme bkg-hover-theme color-theme color-hover-white">SALVAR</button>
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
    

	<!-- Alterar capa Modal End -->
	<div id="editarcapadopost" class="section-block pt-0 pb-30 background-none hide">
		
		<!-- Intro Title Section 2 -->
		<div class="thumbnail xsmall">
			<img src="<?php echo $dbp['capa'];?>" width="825" height="400" alt="">
		</div>
		<!-- Intro Title Section 2 End -->

		<!-- Signup -->
		<div class="section-block pt-60 pb-0">
			<div class="row">
				<div class="column width-12 left">
					<div class="signup-form-container">
						<div class="row">
							<div class="column width-10 offset-1">
								<p>
									<?php
									
									?>
								</p>
							</div>
						</div>
						<form class="form" action="" method="post" enctype="Multipart/form-data">
							<div class="row">
								<div class="column width-12">
									<input type="file" name="imagemcapa" class="button medium border-charcoal-light color-blue-light color-hover-blue">
								</div>
							</div>
							<div class="row">
								<div class="column width-5 left">
									<button type="submit" value="ALTERAR CAPA" name="btn-alterarcapa" class="button medium border-red color-blue-lght color-hover-blue">ALTERAR CAPA</button>
								</div>
							</div>
							
						</form>
						<!--<div class="form-response show"></div>-->
					</div>
				</div>
			</div>
		</div>
		<!-- Signup End -->

	</div>
	<!-- Fim Alterar capa Modal End -->

	<?php
		$MiniGet="SELECT id, miniatura FROM blog WHERE id='$id_altmini'";
			$rMG=mysqli_query($con, $MiniGet);
			$dMG=mysqli_fetch_array($rMG);
	?>

	<!-- Alterar miniatura Modal End -->
	<div id="editarminiaturadopost" class="section-block pt-0 pb-30 background-none hide">
		
		<!-- Intro Title Section 2 -->
		<div class="thumbnail xsmall">
			<img src="<?php echo $dMG['miniatura'];?>" width="825" height="400" alt="">
		</div>
		<!-- Intro Title Section 2 End -->

		<!-- Signup -->
		<div class="section-block pt-60 pb-0">
			<div class="row">
				<div class="column width-12 left">
					<div class="signup-form-container">
						<div class="row">
							<div class="column width-10 offset-1">
								<p>
									<?php
									
									?>
								</p>
							</div>
						</div>
						<form class="form" action="" method="post" enctype="multipart/form-data">
							<div class="row">
								<div class="column width-12">
									<input type="file" name="miniatura" class="button medium border-charcoal-light color-blue-light color-hover-blue">
								</div>
							</div>
							<div class="row">
								<div class="column width-5 left">
									<input type="submit" value="ALTERAR MINIATURA" name="btn-alterarminiatura" class="button medium border-red color-blue-lght color-hover-blue">
								</div>
							</div>
							
						</form>
						<!--<div class="form-response show"></div>-->
					</div>
				</div>
			</div>
		</div>
		<!-- Signup End -->
	</div>
	<!-- Fim Alterar miniatura Modal End -->

	<!-- Adicionar miniatura modal End -->
	<div id="addminiatura"  class="section-block pt-0 pb-30 background-none hide">
		
		<!-- Intro Title Section 2 -->
		<div class="thumbnail xsmall">
			<div class="row">
				<div class="column width-12">
					<h2 class="center pt-20 pb-10 "> Adicionar miniatura</h2>
				</div>
			</div>
		</div>
		<!-- Intro Title Section 2 End -->

		<!-- Signup -->
		<div class="section-block pt-60 pb-0">
			<div class="row">
				<div class="column width-12 left">
					<div class="signup-form-container">
						<div class="row">
							<div class="column width-10 offset-1">
								<p>
									<?php
									
									?>
								</p>
							</div>
						</div>
						<form class="form" action="" method="post" enctype="multipart/form-data">
							<!-- Campos do post invísiveis -->
							<input type="hidden"  name="titulo" maxlength="100" class="form-aux form-date form-element large" value='<?php echo $dbp['titulo'];?>' placeholder="Título do texto" tabindex="5">
							
							<input type="hidden"  value="<?php echo $dbp['datadapostagem'];?>" name="datadapostagem" class="form-aux form-date form-element large"  tabindex="5">

							<input type="hidden"  value="<?php echo $dbp['capa'];?>" name="capanew" class="form-aux form-date form-element large"  tabindex="5">
							
							<input type="hidden" cols="5" rows="10" name="textoancora" maxlength="500" class="form-aux form-date form-element large" placeholder="Digite aqui o texto de destaque deste post. // Máximo de 500 caracteres." tabindex="5" value="<?php echo $dbp['textoancora'];?>"/>
							
							<input type="hidden" name="categoria" value="<?php echo $dbp['categoria']; ?>"/>

							<input type="hidden" name="txtArtigo" id="txtArtigo" class="form-message form-element large" placeholder="Descrição do case. (Até 5.000 caracteres)." tabindex="36"  value="<?php echo $dbp['texto'];?>"/>

							<input type="hidden" cols="5" rows="10" name="bibliografia" maxlength="4800" class="form-aux form-date form-element large" placeholder="Informe a bibliografia do seu texto" tabindex="5" value="<?php echo $dbp['bibliografia'];?>"/>
							<!-- Fim dos campos invisíveis -->

							<div class="row">
								<div class="column width-12">
									<input type="file" name="miniaturanew" class="button medium border-charcoal-light color-blue-light color-hover-blue">
								</div>
							</div>

							<div class="row">
								<div class="column width-5 left">
									<input type="submit" value="Adicionar miniatura" name="btn-adicionarminiatura" class="button medium border-red color-blue-lght color-hover-blue">
								</div>
							</div>
							
						</form>
						<!--<div class="form-response show"></div>-->
					</div>
				</div>
			</div>
		</div>
		<!-- Signup End -->
	</div>
	<!-- Fim Adicionar miniatura modal End -->


	<?php
		if(isset($_GET['altmini']) AND !empty($_GET['altmini'])):
	?>
		<script>
			window.setTimeout(function(){
				document.getElementById("minialt").click();
			}, 500);
		</script>
		<script>
			window.setTimeout(function(){
				document.getElementById("minialt").click();
			}, 2000);
		</script>
		<a id='minialt' data-content="inline" data-aux-classes="tml-newsletter-modal tml-exit-light" data-toolbar="" data-modal-mode data-modal-width="600" data-lightbox-animation="fadeIn" href="#editarminiaturadopost" class="lightbox-link"><label class="color-black">(ALTERAR MINIATURA.)</label></a>
	<?php
		endif;
	?>

	<? include "./_script.php"; ?>
    
</body>
</html>