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

    if(isset($_POST['btn-slides'])):
		for($nslide=0; $nslide < count($_POST['titulo']); $nslide++){
			if(!empty($_POST['titulo'][$nslide])): //Para não passar os slides em branco
				$campo              = mysqli_escape_string($con, $_POST['campo']);
				@$idatualizar       = mysqli_escape_string($con, $_POST['idatualizar'][$nslide]);
				$ordemdoslide       = mysqli_escape_string($con, $_POST['ordemdoslide'][$nslide]);
				$titulo             = mysqli_escape_string($con, $_POST['titulo'][$nslide]);
				$subtitulo          = mysqli_escape_string($con, $_POST['subtitulo'][$nslide]);
				$texto              = mysqli_escape_string($con, $_POST['texto'][$nslide]);
				$linkslide          = mysqli_escape_string($con, $_POST['linkslide'][$nslide]);
				$cta                = mysqli_escape_string($con, $_POST['cta'][$nslide]);

				// Imagem do Slide
				//if(!empty($_FILES["slide"]['name'])):
					$extensaocapa = pathinfo($_FILES["slide"]['name'][$nslide], PATHINFO_EXTENSION);
					if(in_array($extensaocapa, $formatospermitidosimagens)):
						
						@mkdir("personalizacao/", 0777); //Cria a pasta se não houver. 
						@mkdir("personalizacao/img/", 0777); //Cria a pasta se não houver. 
						@mkdir("personalizacao/img/index/", 0777); //Cria a pasta se não houver. 
						
						$pastacapa			= "personalizacao/img/index/";
						$img_capa 			= $_FILES["slide"]['name'][$nslide];
						$temporariocapa	    = $_FILES["slide"]['tmp_name'][$nslide];
						$tamanhodacapa	    = $_FILES["slide"]['size'][$nslide];
						$novoNomecapa		= mt_rand(10,9999).'.'.$extensaocapa;
							$capa		= $pastacapa.$novoNomecapa;
						
						//Compacta a imagem da capa								
						// if($tamanhodacapa >= 1000000):
						// 	$quality_capa = 40;	
						// elseif($tamanhodacapa >= 5000000 OR $tamanhodacapa < 1000000):
						// 	$quality_capa = 50;
						// else:	
							$quality_capa = 80;	
						// endif;

						// function compress_image($img_capa, $capa, $quality_capa) {
						// 	$info = getimagesize($img_capa);
						// 	if ($info["mime"] == "image/jpeg") $image = imagecreatefromjpeg($img_capa);
						// 	elseif ($info["mime"] == "image/gif") $image = imagecreatefromgif($img_capa);
						// 	elseif ($info["mime"] == "image/png") $image = imagecreatefrompng($img_capa);
						// 	imagejpeg($image, $capa, $quality_capa);
						// 	return $capa;
						// }
							
						// $temporariocapa = compress_image($temporariocapa, $capa, $quality_capa); //Compacta a imagem 
					

						//move_uploaded_file($temporariocapa, $capa);
					endif;
				// endif;
				// Imagem do Slide

				//Insere uma nova imagem, Imagem enviada e Id de atualização inexistente
				if(!empty($_FILES['slide']['name']) AND !isset($_POST['idatualizar'])):
					move_uploaded_file($temporariocapa, $capa);

					$inslide = "INSERT INTO personalizarindex (dia, mes, ano, campo, ordem, titulo, subtitulo, texto, link1, cta1, link2, cta2, link3, cta3, imagem) VALUES ('$dia', '$mesnumero', '$ano', '$campo', '$ordemdoslide', '$titulo', '$subtitulo', '$texto', '$linkslide', '$cta', '', '', '', '', '$capa')";
					
					if(mysqli_query($con, $inslide)):
						move_uploaded_file($temporariocapa, $capa);
						$_SESSION['cordamensagem']="green";
						$_SESSION['mensagem']="Slide inserido com sucesso.";
						
						//Dados para registrar o log do cliente.
						$mensagem = ("\n$nomedocolaborador ($matricula) Adicionou o slide com o título: $titulo. Isto através do IP: $ip, na data $dia às $hora.\n"); //Escreva a mensagem que será gravada no log.
						include "registrolog.php";
						//Fim do registro do log.
					else:
						$_SESSION['cordamensagem']="red";
						$_SESSION['mensagem']="Verifique sua conexão, não foi possível inserir o slide. Caso este erro persista, entre em contato com o gestor do site.";
					endif;
				elseif(isset($_POST['idatualizar']) AND !empty($_FILES['slide']['name'])):
					move_uploaded_file($temporariocapa, $capa);

					//Busca a imagem antiga para exclusão
					$bISA="SELECT imagem FROM personalizarindex WHERE id='$idatualizar'";
					$rISA=mysqli_query($con, $bISA);
						$dISA=mysqli_fetch_array($rISA);

					$dImagemAntiga=$dISA['imagem'];

					$updateSlide="UPDATE personalizarindex SET ordem='$ordemdoslide', titulo='$titulo', subtitulo='$subtitulo', texto='$texto', link1='$linkslide' , cta1='$cta', imagem='$capa' WHERE id='$idatualizar'";
					if(mysqli_query($con, $updateSlide)):
						@unlink($dImagemAntiga); //Excluí a imagem antiga.
						
						$_SESSION['cordamensagem']="green";
						$_SESSION['mensagem']="Slide atualizado com sucesso.";
						
						//Dados para registrar o log do cliente.
						$mensagem = ("\n$nomedocolaborador ($matricula) atualizou o slide com o título: $titulo. Isto através do IP: $ip, na data $dia às $hora.\n"); //Escreva a mensagem que será gravada no log.
						include "registrolog.php";
						//Fim do registro do log.

						header("refresh:2");
					endif;
				else:
					$updateSlide="UPDATE personalizarindex SET ordem='$ordemdoslide', titulo='$titulo', subtitulo='$subtitulo', texto='$texto', link1='$linkslide' , cta1='$cta' WHERE id='$idatualizar'";
					if(mysqli_query($con, $updateSlide)):
						$_SESSION['cordamensagem']="green";
						$_SESSION['mensagem']="Slide atualizado com sucesso.";
						
						//Dados para registrar o log do cliente.
						$mensagem = ("\n$nomedocolaborador ($matricula) atualizou o slide com o título: $titulo. Isto através do IP: $ip, na data $dia às $hora.\n"); //Escreva a mensagem que será gravada no log.
						include "registrolog.php";
						//Fim do registro do log.
					endif;
				endif;
			endif;
		}
    endif;
    
	//Comando para o modal de alterar miniatura.
    if(isset($_POST['btn-alterarimagem'])):
        
        $iddaimagem = mysqli_escape_string($con, $_POST['iddaimagem']);
        
        $extensao = pathinfo($_FILES["imagemslide"]['name'], PATHINFO_EXTENSION);
        if(in_array($extensao, $formatospermitidosimagens)):
			//Pegar capa antiga e excluir 
			$bCapaAntiga = "SELECT imagem FROM personalizarindex WHERE id='$iddaimagem'";
			  $rCA = mysqli_query($con, $bCapaAntiga);
				$dCA = mysqli_fetch_array($rCA);
				
				$capaantiga = $dCA["imagem"];


			//Adiciona a nova compactada
			
				$img_capa 			= $_FILES["imagemslide"]['name'];
				$pastacapa			= "personalizacao/img/index/";
                $temporariocapa	    = $_FILES["imagemslide"]['tmp_name'];
                $tamanhodacapa	    = $_FILES["imagemslide"]['size'];
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
					
				$temporariocapa = compress_image($_FILES["imagemslide"]["tmp_name"], $linkcapa, $quality_capa);
					
		
            $capa = "UPDATE personalizarindex SET imagem='$linkcapa' WHERE id='$iddaimagem'";
			if (mysqli_query($con, $capa)):	

				@unlink($capaantiga); //Excluí a capa antiga. @ Caso não exista uma não aparecerá o erro.

            	move_uploaded_file($temporariocapa, $linkcapa);
            
				$_SESSION['cordamensagem'] = "green";
				$_SESSION['mensagem'] = "Capa atualizada.";
				//header("refresh:3; url=blogeditar?m=$matricula&id=$iddopost&token=$token&ref=$refdopost");                
            else:
				$_SESSION['cordamensagem'] = "red";
                $_SESSION['mensagem'] = "Ocorreu um erro e a capa não foi atualizada.";
                //header("Location:ge_posteditarunico?id=$iddopost&".uniqid()."&tgestor=$token");
            endif;
        endif;
    endif;

    if(isset($_POST['btn-cta'])):
        $campo          = mysqli_escape_string($con, $_POST['campo']);
        $titulo         = mysqli_escape_string($con, $_POST['titulo']);
        $texto          = mysqli_escape_string($con, $_POST['texto']);
        $link1          = mysqli_escape_string($con, $_POST['link1']);
        $cta1           = mysqli_escape_string($con, $_POST['cta1']);
        $link2          = mysqli_escape_string($con, $_POST['link2']);
        $cta2           = mysqli_escape_string($con, $_POST['cta2']);

        $incta = "INSERT INTO personalizarindex (dia, mes, ano, campo, ordem, titulo, subtitulo, texto, link1, cta1, link2, cta2, link3, cta3, imagem) VALUES ('$dia', '$mesnumero', '$ano', '$campo', '', '$titulo', '', '$texto', '$link1', '$cta1', '$link2', '$cta2', '', '', '')";
        
        if(mysqli_query($con, $incta)):
            $_SESSION['cordamensagem']="green";
            $_SESSION['mensagem']="CTA inserido com sucesso.";
            
            //Dados para registrar o log do cliente.
            $mensagem = ("\n$nomedocolaborador ($matricula) Adicionou o CTA da página inicial com o título: $titulo. Isto através do IP: $ip, na data $dia às $hora.\n"); //Escreva a mensagem que será gravada no log.
            include "registrolog.php";
            //Fim do registro do log.
        else:
            $_SESSION['cordamensagem']="red";
            $_SESSION['mensagem']="Verifique sua conexão, não foi possível inserir o CTA.";
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
                                                        <h1 class="mb-0">Editar página <strong>inicial</strong></h1>
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
									<div class="tabs button-nav rounded bordered left">
										<ul class="tab-nav">
											<li class="active">
												<a href="#tabs-slide">Slides</a>
											</li>
											<li>
												<a href="#tabs-cta">CTA</a>
											</li>
										</ul>
										<div class="tab-panes">
											<div id="tabs-slide" class="active animate">
												<div class="tab-content">
													<form class="form" action="" method="post" charset="utf-8" enctype="multipart/form-data">
														<?php
															//Carrega os slides no BD
															$bSlid = "SELECT id, ordem, titulo, subtitulo, texto, link1, cta1, link2, cta2, link3, cta3, imagem FROM personalizarindex WHERE campo='SLIDE' AND titulo!=''";
															$rSlid = mysqli_query($con, $bSlid);
																	$nDiS = 0; //Número de Slides disponíveis para carregar até 5
																while($dSlid=mysqli_fetch_array($rSlid)):
																	$nDiS++;
														?>
														<div class="column width-4">
															<!-- <h3 class="mb-50">Slide 1</h3> -->
															<!-- <a data-content="inline" data-aux-classes="tml-newsletter-modal tml-exit-light" data-toolbar="" data-modal-mode data-modal-width="600" data-lightbox-animation="fadeIn" href="#editarcapadopost<?php echo $nDis;?>" class="lightbox-link"><label class="color-black">(ALTERAR CAPA.)</label></a>
															-->
															<img class="" src="<?php echo $dSlid['imagem'];?>" width="800" height="400">
														</div>
														<div class="column width-8">
															<div class="form-container">
																<div class="row">
																	<input type="hidden" name="campo" value="SLIDE"/>
																	<input type="hidden" name="idatualizar[]" value="<?php echo $dSlid['id'];?>"/>
																	
																	<div class="column width-3">
																		<span class="">Ordem do slide</span>
																		<div class="field-wrapper">
																			<input type="text" name="ordemdoslide[]" value="<?php echo $dSlid['ordem'];?>"class="form-fname form-element rounded medium" placeholder="Ordem" tabindex="0<?php echo $nDiS; ?>1">
																		</div>
																	</div>
																	<div class="column width-9">
																		<span class="">Substituir imagem</span>
																		<div class="field-wrapper">
																			<input type="file" name="slide[]" class="form-fname form-element rounded medium" placeholder="Título" tabindex="0<?php echo $slid;?>2">
																		</div>
																	</div>
																	<div class="column width-12">
																		<div class="field-wrapper">
																			<input type="text" name="titulo[]" value="<?php echo $dSlid['titulo'];?>" class="form-fname form-element rounded medium" placeholder="Título" tabindex="0<?php echo $nDiS; ?>1">
																		</div>
																	</div>
																	<!-- <div class="column width-12">
																		<div class="field-wrapper">
																			<input type="text" name="subtitulo[]" value="<?//php echo $dSlid['subtitulo'];?>" class="form-fname form-element rounded medium" placeholder="Sub-título" tabindex="1">
																		</div>
																	</div> -->
																	<div class="column width-12">
																		<div class="field-wrapper">
																			<textarea type="text" name="subtitulo[]" maxlength="200" class="form-lname form-element rounded medium" placeholder="Seu subtítulo" tabindex="0<?php echo $nDiS; ?>2"><?php echo $dSlid['subtitulo'];?></textarea>
																		</div>
																	</div>
																	<div class="column width-8">
																		<div class="field-wrapper">
																			<input type="text" name="linkslide[]" value="<?php echo $dSlid['link1'];?>" class="form-email form-element rounded medium" placeholder="Link do botão" tabindex="0<?php echo $nDiS; ?>3">
																		</div>
																	</div>
																	<div class="column width-4">
																		<div class="field-wrapper">
																			<input type="text" name="cta[]" value="<?php echo $dSlid['cta1'];?>" class="form-wesite form-element rounded medium" placeholder="CTA para o botão" tabindex="0<?php echo $nDiS; ?>4">
																		</div>
																	</div>
																	<div class="column width-12 pb-20">
																		<a href="" class="column width-12 button rounded medium bkg-red bkg-hover-red color-white color-hover-white mb-0 hard-shadow" tabindex="0<?php echo $nDiS; ?>5"/>
																			Excluir
																		</a>
																	</div>
																</div>
															</div>
														</div>
														<?php
															endwhile;
														?>
														<hr/>
														<?php
															$disSlid = 5 - $nDiS; //Subtraí o número de Slides no BD para ver quantos estão disponíveis.

															$slid = 0 + $nDiS;
															for ($i=0; $i < $disSlid; $i++) {
																$slid++;
														?>
														<div class="column width-4">
															<!-- <h3 class="mb-50">Slide 1</h3> -->
															<img src="" />
														</div>
														<div class="column width-8">
															<div class="contact-form-container">
																<div class="row">
																	<input type="hidden" name="campo" value="SLIDE"/>
																	
																	<div class="column width-3">
																		<div class="field-wrapper">
																			<input type="text" name="ordemdoslide[]" value="<?php echo $slid;?>" class="form-fname form-element rounded medium" placeholder="Ordem do slide" tabindex="0<?php echo $slid;?>1">
																		</div>
																	</div>
																	<div class="column width-9">
																		<div class="field-wrapper">
																			<input type="file" name="slide[]" class="form-fname form-element rounded medium" placeholder="Título" tabindex="0<?php echo $slid;?>2">
																		</div>
																	</div>
																	<div class="column width-12">
																		<div class="field-wrapper">
																			<input type="text" name="titulo[]" class="form-fname form-element rounded medium" placeholder="Título" tabindex="0<?php echo $slid;?>3">
																		</div>
																	</div>
																	<div class="column width-12">
																		<div class="field-wrapper">
																			<textarea type="text" name="subtitulo[]" maxlength="200" class="form-lname form-element rounded medium" placeholder="Seu subtítulo" tabindex="0<?php echo $slid;?>4"></textarea>
																		</div>
																	</div>
																	<div class="column width-8">
																		<div class="field-wrapper">
																			<input type="text" name="linkslide[]" class="form-email form-element rounded medium" placeholder="Link do botão" tabindex="0<?php echo $slid;?>5">
																		</div>
																	</div>
																	<div class="column width-4">
																		<div class="field-wrapper">
																			<input type="text" name="cta[]" class="form-wesite form-element rounded medium" placeholder="CTA para o botão" tabindex="0<?php echo $slid;?>6">
																		</div>
																	</div>
																	<div class="column width-12 pb-20">
																		<a href="" class="column width-12 button rounded medium bkg-red bkg-hover-red color-white color-hover-white mb-0 hard-shadow" tabindex="0<?php echo $slid;?>7"/>
																			Excluir
																		</a>
																	</div>
																</div>
															</div>
														</div>
														<?php
															}
														?>
														<div class="column width-12">
															<!-- <input type="submit" name="btn-slides" value='Salvar' class="column width-12 button rounded medium bkg-blue bkg-hover-blue color-white color-hover-white mb-0 hard-shadow shadow" tabindex="8"/> -->
															<button type="submit" name="btn-slides" class="column width-12 button rounded medium bkg-blue bkg-hover-blue color-white color-hover-white mb-0 hard-shadow shadow" tabindex="8">
																<span class="title-medium">
																	Salvar
																</span>
															</button>
														</div>
													</form>
												</div>
											</div>
											<div id="tabs-cta">
												<div class="tab-content">
													<form class="contact-form" action="" method="post" charset="utf-8" enctype="Mulipart/form-data">
														<div class="contact-form-container">
															<div class="row">
																<input type="hidden" name="campo" value="CTA"/>                                                        
																<!-- <div class="column width-12">
																	<div class="field-wrapper">
																		<input type="file" name="slide" class="form-fname form-element rounded medium" placeholder="Título" tabindex="1">
																	</div>
																</div> -->
																<div class="column width-12">
																	<div class="field-wrapper">
																		<input type="text" name="titulo" class="form-fname form-element rounded medium" placeholder="Título" tabindex="1">
																	</div>
																</div>
																<div class="column width-12">
																	<div class="field-wrapper">
																		<textarea type="text" name="texto" maxlength="200" class="form-lname form-element rounded medium" placeholder="Seu subtítulo" tabindex="2"></textarea>
																	</div>
																</div>
																<div class="column width-8">
																	<div class="field-wrapper">
																		<input type="text" name="link1" class="form-email form-element rounded medium" placeholder="Link do botão" tabindex="3">
																	</div>
																</div>
																<div class="column width-4">
																	<div class="field-wrapper">
																		<input type="text" name="cta1" class="form-wesite form-element rounded medium" placeholder="CTA para o botão" tabindex="4">
																	</div>
																</div>
																<div class="column width-8">
																	<div class="field-wrapper">
																		<input type="text" name="link2" class="form-email form-element rounded medium" placeholder="Link do botão" tabindex="3">
																	</div>
																</div>
																<div class="column width-4">
																	<div class="field-wrapper">
																		<input type="text" name="cta2" class="form-wesite form-element rounded medium" placeholder="CTA para o botão" tabindex="4">
																	</div>
																</div>
																<div class="column width-12">
																	<button type="submit" name="btn-cta" class="column width-12 button rounded medium bkg-blue bkg-hover-blue color-white color-hover-white mb-0 hard-shadow shadow" tabindex="8"/>
																		<span class="title-medium">
																			Salvar
																		</span>
																	</button>
																</div>
															</div>
														</div>
													</form>
												</div>
											</div>
										</div>
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