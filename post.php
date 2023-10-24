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
    //     session_destroy();
    //     header("location:index");
	// endif;
    
    
	$ref=$_GET['pos'];

    if(!isset($_SESSION['viewdopc'])):
        //Atualização de visualização.
        $bVisualizacoes = "SELECT visualizacoes FROM blog WHERE referencia='$ref'";
        $rVisualizacoes = mysqli_query($con, $bVisualizacoes);
            $dVisualizacoes = mysqli_fetch_array($rVisualizacoes);
        
        $TotalDeVisualizacoes = $dVisualizacoes['visualizacoes'];

        //Adicionar a visualizaçao atual

        @$MaisUmaVisu = $TotalDeVisualizacoes + 1;

        $uPv = "UPDATE blog SET visualizacoes='$MaisUmaVisu' WHERE referencia='$ref'";
        if(mysqli_query($con, $uPv)):
            $_SESSION['viewdopc']=true;
        endif;
    endif;

	if(!isset($_GET['pos'])):
		header("Location:index?$linkSeguro");
	endif;

	
	$bMiniaturas="SELECT miniatura FROM blog WHERE NOT (miniatura = '') AND referencia='$ref' GROUP BY miniatura ORDER BY id ASC ";
	$rMini=mysqli_query($con, $bMiniaturas);
	$nMini = mysqli_num_rows($rMini);
	while($dMiniaturas=mysqli_fetch_array($rMini)):
		$multiminiaturas[] = $dMiniaturas['miniatura'];
	endwhile;


	$buscar="SELECT * FROM blog WHERE referencia='$ref'";
	  $result=mysqli_query($con, $buscar);
		$texto=mysqli_fetch_array($result);

	$titulo=$texto['titulo'];
	$categoriarela = $texto['categoria'];

	if($categoriarela == 'Close' OR $categoriarela == 'Artigos' OR $categoriarela == 'Pesquisas' OR $categoriarela == 'Acervo'):
		//Se não existir a sessão CLiente ou Criativo (CLiente e funcionário) quem tentar acessar será redirecionado para index.
		if(!isset($_SESSION["membro"])):
			//Se não existir usuário logado verificar se há colaborador.
			if(!isset($_SESSION["lideranca"])):
				//Se não houver usuário e nem colaborador, direciona para cadastro.
				$_SESSION['cordamensagem']="orange";
				$_SESSION['mensagem']="Crie sua conta <strong>gratuita</strong> ou acesse com seu facebook para ter acesso a este conteúdo.";
				header('location:cadastro');
			endif;
		endif; 
	endif;
?>
<html lang="pt-BR">
<head>
	<meta charset="UTF-8" />
	<meta content="width=device-width,initial-scale=1.0,maximum-scale=1.0" name="viewport">
	<meta property="og:title" content="Igreja Emanuel | <?echo $texto['categoria'].': '.$texto['titulo'];?>" />
	<meta property="og:url" content="https://igrejaemanuel.com.br<?echo $_SERVER['REQUEST_URI'];?>" />
	<meta property="og:type" content="website" />
	<meta property="og:image" content="https://igrejaemanuel.com.br/<?echo $texto['miniatura'];?>" />
    <meta property="og:image:url" content="https://igrejaemanuel.com.br/<?echo $texto['miniatura'];?>">
    <meta property="og:image:secure_url" content="https://igrejaemanuel.com.br/<?echo $texto['miniatura'];?>">
    <meta name="robots" content="index, follow"> 
	<meta property="og:description" content="<?echo $texto['textoancora'];?>" />
	<meta name="og:keywords" content="<?echo $texto['categoria'];?>, Jesus Cristo, Pastor Rafael Vieira, Rafael Vieira, Igreja em Duque de Caxias, Igreja na Vila São Luís, Igreja, Batista, Lagoinha Caxias, lagoinha niterói, Felipe Valadão, Palavra, Casa de Paz, Belém Caxias, Renascer em fé, mevam, Luiz Hermínio, Advec, Praça da Apoteose, Igreja Batista Emanuel, igrejaemanuel, Igreja Emanuel, IBE, Emanuel Duque de Caxias" />
	<meta name="keywords" content="<?echo $texto['categoria'];?>, Jesus Cristo, Pastor Rafael Vieira, Rafael Vieira, Igreja em Duque de Caxias, Igreja na Vila São Luís, Igreja, Batista, Lagoinha Caxias, lagoinha niterói, Felipe Valadão, Palavra, Casa de Paz, Belém Caxias, Renascer em fé, mevam, Luiz Hermínio, Advec, Praça da Apoteose, Igreja Batista Emanuel, igrejaemanuel, Igreja Emanuel, IBE, Emanuel Duque de Caxias" />
    <meta property="article:publisher" content="https://www.facebook.com/emanueligrejabr">

	<!-- Twitter Theme -->
	<meta name="twitter:widgets:theme" content="light">
	
	<!-- Title &amp; Favicon -->
	<title>Emanuel |<?php if(isset($nomedousuario)): echo 'Olá, '.$nomedousuario.' | '; endif; ?> (Igreja Batista Emanuel) - Jesus é o único caminho.</title>
	<link rel="shortcut icon" type="image/x-icon" href="images/favicon.png">

	<!-- Font -->
	<link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700%7CHind+Madurai:400,500&amp;subset=latin-ext" rel="stylesheet">
	
	<!-- Css -->
	<link rel="stylesheet" href="css/core.min.css" />
	<link rel="stylesheet" href="css/skin.css" />
	<link rel="stylesheet" href="mediaelement/skin/mejs-snowplayerskin.css" />

	<!--[if lt IE 9]>
    	<script type="text/javascript" src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
</head>
<body class="shop blog home-page">

	<?php
        // if(isset($_SESSION["membro"])):
            include "./_menu.php";
        // endif;
	?>

			<!-- Content -->
			<div class="content clearfix">
                <section class="section-block featured-media small page-intro tm-slider-parallax-container">
                    <div class="tm-slider-container full-width-slider" data-parallax data-parallax-fade-out data-animation="slide" data-scale-under="1140" data-scale-min-height="300">
                        <ul class="tms-slides">
                            <li class="tms-slide" data-image data-force-fit data-overlay-bkg-color="#000000" data-overlay-bkg-opacity="0.3">
                                <div class="tms-content">
                                    <div class="tms-content-inner center">
                                        <div class="row">
                                            <div class="column width-12">
                                                <div class="column width-8">
                                                    <h2 class="mb-60 color-white"></h2>
                                                    <h2 class="mb-10 color-white left label bkg-blue rounded"><?php echo $texto['titulo'];?></h2>
                                                </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php
                                    if($texto['capa'] !== ''):
                                ?>
                                <img data-src="<?php echo $texto['capa'];?>" data-retina src="images/blank.png" alt=""/>
                                <?php
                                    else:
                                ?>
                                <img data-src="<?php echo $texto['miniatura'];?>" data-retina src="images/blank.png" alt=""/>
                                <?php
                                    endif;
                                ?>
                            </li>
                        </ul>
                    </div>
                </section>
                
                <div class="section-block clearfix bkg-grey-ultralight">
                    <div class="column width-9 content-inner blog-single-post">
                        <article id="divpublicacao" class="post">
                            <div class="post-content with-background">
                                <div class="column width-3">
                                    <?php
                                        //Busca o Qr Code do artigo.
                                        $bQrExis = "SELECT qrcode FROM blog WHERE qrcode != '' AND referencia='$ref'";
                                          $rQrExis = mysqli_query($con, $bQrExis);
                                        	$nQrExis = mysqli_num_rows($rQrExis);
                                        	  $dQrExist = mysqli_fetch_array($rQrExis);
                                        if($nQrExis > 0):
                                        	//Busca o Qr Code do artigo.
                                        	$qrcodedoartigo = $dQrExist['qrcode'];
                                        else:
                                            // Cria o Qr Code
                                            include "./_qrcodepage.php";
                                            $qrcodedoartigo = "arqblog/qrcodepost/$ref/$ref.png";

                                            //Registra o Qr Code no Bd.
                                            $InserirQRcode = "UPDATE blog SET qrcode='$qrcodedoartigo' WHERE referencia='$ref'";
                                            mysqli_query($con, $InserirQRcode);
                                        endif;
                                    ?>
                                    <label class="center">
                                        <?php echo $texto['cargo'].' '.$texto['autor'].' ['.$texto['categoria'].']';?>
                                    </label>
                                    <img class="center" src="<?php echo $qrcodedoartigo; ?>" width="300"> 
                                </div>
                                <div class="column width-9 right pt-10">
                                    <h3 class="mb-10 pb-0 color-charcoal weight-bold"><?php echo $texto['titulo'];?></h3>
                                    <span class="text-small color-charcoal">Igreja Emanuel (<span class="text-xsmall"> https://igrejaemanuel.com.br/<?php echo $pagina;?> )</span></span>
                                    <br/>

                                    <span class="text-xsmall color-charcoal"><?php echo $texto['visualizacoes'];?> visualizações.</span>
                                    <br/>

                                    <span class="icon-facebook color-charcoal color-hover-charcoal"></span>
                                    <span class="icon-youtube color-charcoal color-hover-charcoal"></span>
                                    <span class="icon-instagram color-charcoal color-hover-charcoal"></span>
                                    <span class="icon-twitter color-charcoal color-hover-charcoal"></span>
                                    <span class="text-xsmall color-charcoal">/emaueligrejabr</span>

                                    <br/>
                                    <button type="button" id="btnPrint" class="column full width pt-10 button small border-blue border-hover-blue color-blue color-hover-blue hard-shadow shadow right">Imprimir</button>
                                </div>
                                <hr class="border-blue">
                                <?php if(!empty($texto['video'])): ?>
                                    <?php  $videoCarregado = $texto['video']; $procurar = "youtube.com/"; $linkVideo = stripos($videoCarregado, $procurar); if(stripos($videoCarregado, $procurar) !== false): ?>
                                        <div class="post-media video-container pt-0"><iframe src="<?php echo $texto['video'];?>" width="350" height="450"></iframe></div> <?php else: ?>
                                        <video poster="<?php echo $texto['capa'];?>" class="bkg-black" controls="" muted="" style="width:100%; height:100%;" height="200"><source type="video/mp4" src="<?php echo $texto['video'];?>" /><source type="video/webm" src="<?php echo $texto['video'];?>" /></video>
                                    <?php endif; ?>
                                <?php endif;  
                                    if($nMini > 1): ?> 
                                    <div class="tm-slider-container recent-slider" data-nav-dark data-carousel-visible-slides="2" data-nav-keyboard="true" data-nav-pagination="false" data-nav-show-on-hover="false" data-carousel-1024="2">
                                        <ul class="tms-slides">	
                                            <?php foreach($multiminiaturas as $miniaturasview) { ?>
                                                <li class="tms-slide">
                                                    <div class="thumbnail rounded">
                                                        <img data-src="<?php echo $miniaturasview; ?>" src="images/blank.png" alt=""/>
                                                    </div>
                                                </li>
                                            <?php
                                                }
                                            ?>
                                        </ul>
                                    </div>
                                <?php
                                    endif; ?> 
                                <?php if(!empty($texto['anexo'])): ?>
                                    <div class="column width-12">
                                        <a href="<?php echo $texto['anexo'];?>" class="column width-12 rounded hard-shadow pt-10 pb-20 button bkg-blue border-hover-blue color-white color-hover-blue small download-link"><span class="icon-download color-white color-hover-blue"></span>Baixar anexo.</a>
                                    </div>
                                <?php endif; ?>
                                <br>
                                <?php if(!empty($texto['texto'])): ?>
                                    <font color="#000000" align="justify"><!--</font> style="font-family:Open Sans, Times New Roman">tms-caption -->
                                        <p class="text-medium"> <?php $textodopost= str_replace('<blockquote>', '<blockquote class="large box xlarge full-width bkg-grey-ultralight">', $texto['texto']); $textodopost=str_replace('font-size:11pt', 'font-size:14pt', $textodopost); echo $textodopost;?> </p>
                                    </font>
                                <?php endif; if(!empty($texto['bibliografia'])): ?> 
                                    <hr/>
                                    <h3 class="text-medium weight-bold"> Referências bibliográficas </h3>
                                    <font color="#000000" align="justify"><p class="text-medium color-black"><?php echo str_replace('<blockquote>', '<blockquote class="large box xlarge full-width bkg-grey-ultralight">', $texto['bibliografia']);?></p>
                                    </font>
                                <?php endif; ?>
                            </div>
                        </article>
                        <div class="column width-12 pt-20 bkg-white"><button type="button" id="btnPrintb" class="column full width button small border-blue border-hover-blue color-charcoal color-hover-charcoal hard-shadow shadow right">Clique para imprimir</button></div>
                        
                        <div class="column width-12 pt-20 bkg-white">
                            <div class="section-block grid-filter-menu center">
                                <div class="row">
                                    <div class="feature-text column width-12 ">
                                        <h4 class="pb-10 color-blue ">Posts relacionados.</h4>
                                    </div>
									<div class="column width-12">
										<!-- Masonry Blog Grid -->
										<div class="section-block content-inner blog-masonry grid-container pt-20 pb-30 bkg-white" data-layout-mode="masonry" data-grid-ratio="1.5" data-animate-resize data-set-dimensions data-animate-resize-duration="600" data-as-bkg-image>
											<div class="row">
												<div class="column width-12">
													<div class="row grid content-grid-2">
														<?php
															//Trazer posts.
															$bMais = "SELECT referencia, titulo, textoancora, miniatura, video, categoria, datadapostagem, capa FROM blog WHERE NOT (datadapostagem > '$dataeng') AND categoria='$categoriarela' ORDER BY rand(), datadapostagem DESC LIMIT 4";
																$rMais = mysqli_query($con, $bMais);
																	while($dMais = mysqli_fetch_array($rMais)):
																		$titulodopost = $dMais['titulo'];
																		$titulo 	  = str_replace(' ', '-', $titulodopost);
														?>	
														<div class="grid-item">
															<div class="thumbnail overlay-fade-img-scale-in" data-hover-easing="easeInOut" data-hover-speed="700" data-hover-bkg-color="#000000" data-gradient data-gradient-spread="90%" data-hover-bkg-opacity="1">
																<a href="post?<?php echo $linkSeguro;?>pos=<?php echo $dMais['referencia'];?>&<?php include "_filtroparalink.php";  $tituloamigavel = strTr($titulo, $filtro); echo $tituloamigavel; ?>">
																	<?php
																		if(!empty($dMais['video'])):
																	?>
                                                                    <?     
                                                                        $iframevideo=$dMais['video'];  
                                                                            $iframevideo	= explode('/', $iframevideo); //https: // youtube.com / embed / w00JkhGoII0
                                                                            @$videoiframe_URL	 = $iframevideo[2];
                                                                        if($videoiframe_URL === 'youtube.com'):
                                                                    ?>
                                                                        <iframe bkg="transparent" width='500' height='300' src="<? echo $dMais['video']; ?>?showinfo=0&amp;loop=1" poster="<? echo $dMais['capa']; ?>"></iframe>
                                                                    <? else: ?>
                                                                        <div class="row flex">
                                                                            <video controls loading='lazy' poster="<? echo $dMais['capa']; ?>">
                                                                                <source type='video/mp4' src='<? echo $dMais['video']; ?>'>
                                                                            </video>
                                                                        </div>
                                                                    <? endif; ?>
																	<!-- <iframe src="<?php echo $dMais['video'];?>?loop=1" width="500" height="300"></iframe> -->
																	<?php
																		elseif(empty($dMais['video'])):
																	?>
																	<img src="<?php echo $dMais['miniatura'];?>" alt="<?php echo $dMais['titulo'];?>"/>
																	<?php
																		endif;
																	?>
																	<span class="overlay-info v-align-bottom center">
																		<span>
																			<span>
																				<span class="post-info">
																					<span class="post-tags"><span><span class="post-tag label small rounded bkg-gradient-royal-garden color-white bkg-hover-pink bkg-hover-white"><?php echo $dMais['categoria'];?></span></span></span>
																				</span>
																				<span class="post-title">
																					<?php echo $dMais['titulo'];?>
																				</span>
																				<span class="post-info">
																					<span class="post-date"><?php echo $dMais['visualizacoes'];?> views</span>
																				</span>
																			</span>
																		</span>
																	</span>
																</a>
															</div>
														</div>
														<?php
															endwhile;
														?>
													</div>
												</div>
											</div>
										</div>
										<!-- Content Inner End -->
									</div>
									<div class="column width-12">
											
										<?php
											$autor=$texto['autor'];
											// $array = explode(' ', $autor,2);
											// $nomeautor=$array[0];
											// $sobrenomeautor=$array[1];
                                            
                                            $buscarsobreautor="SELECT nome, descricaodomembro FROM membros WHERE matricula='$autor'";
                                            $ressobreautor=mysqli_query($con, $buscarsobreautor);
                                                $dsa=mysqli_fetch_array($ressobreautor);
                                            
                                            $nomeautor = $dsa['nome'];
                                        ?>
										<div id="author" class="post-author section-block">
											<div class="post-author-aside">
												<span class="title-small weight-bold color-black"><?php echo base64_decode(base64_decode($nomeautor));?></span>
											</div>
											<article class="author-bio">
												<div class="author-content">
													<div class="row">
														<div class="column width-12">
																
															<h5 class="name">Quem é o autor?</h5>
															<p class="color-black" align="justify"><?php echo $dsa['descricaodomembro'];?></p>
														</div>
													</div>
												</div>
											</article>
										</div>

										<!-- Post Comments
										<div class="bkg-white with-background">
											<?php // echo $dsa['disqus'];?>
										</div> -->
									</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <aside class="column width-3 sidebar right">
                        <div class="sidebar-inner bkg-white border-blue box rounded small freeze">
                            <div class="widget box bkg-white rounded">
                                <h3 class="widget-title">Pesquisar</h3>
                                <div class="search-form-container site-search">
                                    <form action="pesquisa.php" method="get" novalidate>
                                        <div class="row">
                                            <div class="column width-12">
                                                <div class="field-wrapper">
                                                    <input type="text" name="pesquisa" class="form-search form-element" placeholder="Digite e aperte o enter...">
                                                    <span class="border"></span>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                    <div class="form-response"></div>
                                </div>
                            </div>
                            <div class="widget box bkg-white rounded">
                                <h3 class="widget-title">Categorias</h3>
                                <ul>
                                    <?php
                                        $buscarcategorias="SELECT categoria, count(categoria) as categs FROM blog WHERE categoria != '' GROUP BY categoria ORDER BY id DESC";
                                        $rbc=mysqli_query($con, $buscarcategorias);
                                        while($dbc=mysqli_fetch_array($rbc)):
                                    ?>
                                    <li><a href="news?m=<?php echo $matricula;?>&token=<?php echo $token;?>&c=<?php echo $dbc['categoria'];?>"><?php echo $dbc['categoria'].' ('.$dbc['categs'].')';?></a></li>
                                    <?php endwhile;?>
                                </ul>
                            </div>
                            <div class="widget box bkg-white rounded">
                                    <h3 class="widget-title">Sobre o autor.</h3>
                                    <p><?php echo $dsa['sobre'];?><p>
                            </div>
                            <div class="widget box bkg-white rounded">
                                <h3 class="widget-title">Últimos textos.</h3>
                                <ul class="list-group">
                                        <?php
                                            $ulposts="SELECT * FROM blog WHERE titulo='$titulo'";
                                                $rup=mysqli_query($con, $ulposts);
                                                    while($dup=mysqli_fetch_array($rup)):
                                                        $data=$dup['datadapostagem'];
                                                        $datadapostagem=date(('d/n/y'), strtotime($dup['datadapostagem']));
                                        ?>
                                    <li>
                                        <span class="title-post"><?php echo $datadapostagem;?></span>
                                        <br>
                                        <a href="#"><?php echo $titulo;?></a>
                                    </li>
                                                        <?php endwhile;?>
                            </div>
                            <div class="widget box bkg-white rounded">
                                <h3 class="widget-title">Tweets</h3>
                                <!-- twitter -->
                                <a class="twitter-timeline" href="https://twitter.com/<?php echo $dsa['twitter'];?>" data-chrome="noheader nofooter noborders transparent"  data-tweet-limit="2" data-link-color="#0cbacf" data-widget-id="572782546753429504">Tweets por @<?php echo $dsa['twitter'];?></a> 
                                <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+"://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
                            </div>
                        </div>
                    </aside>
			    </div>
			</div>
			<!-- Content End -->

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