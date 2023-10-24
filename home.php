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

        <!-- Content -->
        <div class="content clearfix">
            <section id="home" class="section-block hero-5 window-height right show-media-column-on-mobile bkg-blue color-white">
                <div class="column width-5 offset-1 window-height background-none">
                    <div class="tm-slider-container content-slider window-height v-align-middle" data-animation="slide" data-nav-arrows="false" data-progress-bar="false" data-speed="1000" data-scale-under="0" data-width="722">
                        <ul class="tms-slides">
                            <?php
                                // $bMais = "SELECT  referencia, titulo, textoancora, miniatura, video, categoria, datadapostagem, capa, visualizacoes FROM blog WHERE datadapostagem <= '$dataHora' AND categoria LIKE '%Videos%' AND categoria NOT LIKE '%Shorts%' AND video!='' ORDER BY id DESC LIMIT 3";
                                $bMais = "SELECT  referencia, titulo, textoancora, miniatura, video, categoria, datadapostagem, capa, visualizacoes FROM blog WHERE datadapostagem <= '$dataHora' AND video!='' ORDER BY id DESC LIMIT 5";
                                  $rMais = mysqli_query($con, $bMais);
                                      while($dVideo=mysqli_fetch_array($rMais)):
                            ?>
                            <li class="tms-slide" data-animation="scaleOut">
                                <?     
                                    $iframevideo=$dVideo['video'];  
                                        $iframevideo	= explode('/', $iframevideo); //https: // youtube.com / embed / w00JkhGoII0
                                        @$videoiframe_URL	 = $iframevideo[2];
                                    if($videoiframe_URL === 'youtube.com'):
                                ?>
                                    <iframe bkg="transparent" width='900' height='481' src="<? echo $dVideo['video']; ?>?showinfo=0&amp;loop=1" poster="<? echo $dVideo['capa']; ?>"></iframe>
                                <? else: ?>
                                    <div class='video-container mejs-container'>
                                        <video loading='lazy' poster="<? echo $dVideo['capa']; ?>">
                                            <source type='video/mp4' src='<? echo $dVideo['video']; ?>'>
                                        </video>
                                    </div>
                                <? endif; ?>
                            </li>
                            <? endwhile; ?>
                        </ul>
                    </div>
                </div>
                <div class="row">
                    <div class="column width-5 offset-1">
                        <div class="hero-content split-hero-content">
                            <div class="hero-content-inner left horizon" data-animate-in="preset:slideInRightShort;duration:1000ms;" data-threshold="0.5">
                                <h1 class="mb-0"><strong>Vem conhecer Jesus.</strong><br></h1>
                                <span class=" title-medium opacity-05">Bem-vindo a um lugar direcionado por Cristo através da bíblia.</span>
                                <p class="pt-10">Estamos felizes por você estar aqui, somos uma igreja que ama servir Jesus impactando nossa sociedade. Estamos aqui para caminharmos juntos e viver ousadamente em Cristo.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <div class="section-block large bkg-white clear-height">
                <div class="row flex two-columns-on-tablet">
                    <? include "./_notificacaomensagem.php"; ?>
                    <div class="column width-7 left v-align-middle">
                        <div>
                            <h1 class="mb-5 color-charcoal">Oferta não tem valor, <strong>tem significado.</strong></h1>
                            <span>(Pastor Rafael Vieira)</span>
                            <h3 class="color-charcoal opacity-09">
                                <strong>Nós construímos a história da igreja</strong>. O trabalho social e as novas
                                igrejas são fruto do amor de Cristo em sua vida e de sua fidelidade. Aqui você pode ver
                                os frutos da sua fideliade e acompanhar com transparência os investimentos realizados.
                            </h3>
                        </div>
                    </div>
                    <div class="column width-5 right left-on-mobile v-align-middle">
                        <div>
                            <a href="doacoes"
                                class="column width-12 pb-10 button rounded large border-blue color-blue bkg-hover-blue color-hover-white mb-0">
                                CLIQUE PARA OFERTAR
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <?php include "./bloginterpages.php"; ?>

            <div class="section-block pb-80 recent-carousel bkg-white">
                <div class="row">
                    <div class="column width-12 center">
                        <h3 class="weight-bold mb-0">Próximos eventos deste mês</h3>
                        <span><strong>Nossos cultos:</strong> Domingo às 18:30h e Quinta-feira às 20h <br> siga-nos nas
                            mídias sociais e visite uma célula</span>
                    </div>
                </div>
                <div class="row full-width collapse">
                    <div class="section-block blog-masonry grid-container pt-20 pb-30 bkg-white"
                        data-layout-mode="masonry" data-grid-ratio="1.5" data-animate-resize data-set-dimensions
                        data-animate-resize-duration="600" data-as-bkg-image>
                        <div class="row">
                            <div class="column width-12">
                                <div class="row grid content-grid-3">
                                    <?php     
											$mesdoevento='-'.date('m').'-';
											if(!isset($_SESSION['membro'])):
												$bMais = "SELECT video, miniatura, evento, codigodoevento, datadoinicio, datadotermino, valor FROM agenda WHERE datadoinicio LIKE '%$mesdoevento%' AND ano='$ano' AND visibilidade='Externo' GROUP BY codigodoevento ORDER BY datadoinicio ASC";
											else:
												$bMais = "SELECT video, miniatura, evento, codigodoevento, datadoinicio, datadotermino, valor FROM agenda WHERE datadoinicio LIKE '%$mesdoevento%' AND ano='$ano' GROUP BY codigodoevento ORDER BY datadoinicio ASC";
											endif;
												$rMais = mysqli_query($con, $bMais);
														$gridItem = 0;
													while($dMais = mysqli_fetch_array($rMais)):
														$gridItem++;											
												$Msgs++;
												
												// $SlideDoPost            = $dMais['slidedeminis'];
												$miniaturasDoPost[]     = $dMais['miniatura'];
												$refDoPost              = $dMais['codigodoevento'];
												$titulodopost           = $dMais['evento'];
												$titulo 	            = str_replace(' ', '-', $titulodopost);
												$datadoinicio=$dMais['datadoinicio'];
												$datadoinicio = date(('d/m/Y'), strtotime($datadoinicio));
												$datadotermino=$dMais['datadotermino'];
												$datadotermino = date(('d/m/Y'), strtotime($datadotermino));
										?>
                                    <div class="grid-item portrait">
                                        <div class="thumbnail tm-slider-container content-slider post-slider rounded overlay-fade-img-scale-in"
                                            data-hover-easing="easeInOut" data-hover-speed="700"
                                            data-hover-bkg-color="#000000" data-gradient data-gradient-spread="70%"
                                            data-hover-bkg-opacity="0.75">
                                            <a class="overlay-link"
                                                href="evento?<?php echo $linkSeguro;?>pos=<?php echo $dMais['codigodoevento'];?>&<?php include "_filtroparalink.php";   $tituloamigavel = strTr($titulo, $filtro); echo $tituloamigavel; ?>">
                                                <?php
														if(!empty($dMais['video'])):
													?>
                                                <div class="grid-item grid-sizer wide large">
                                                    <?php
														elseif(empty($dMais['video'])):
													?>
                                                    <ul class="tms-slides">
                                                        <?php
															if(!isset($_SESSION['membro'])):
																if(isset($_GET['ms']) AND !empty($_GET['ms'])):
																	$bMiniaturas="SELECT miniatura, evento FROM agenda WHERE NOT (miniatura = '') AND datadoinicio LIKE '%$mesdoevento%' AND codigodoevento='$refDoPost' AND visibilidade='Externo' GROUP BY miniatura ORDER BY datadoinicio DESC, id ASC";
																else:
																	$bMiniaturas="SELECT miniatura, evento FROM agenda WHERE NOT (miniatura = '') AND codigodoevento='$refDoPost' AND visibilidade='Externo' GROUP BY miniatura ORDER BY datadoinicio DESC, id ASC";
																endif;
															else:
																if(isset($_GET['ms']) AND !empty($_GET['ms'])):
																	$bMiniaturas="SELECT miniatura, evento FROM agenda WHERE NOT (miniatura = '') AND datadoinicio LIKE '%$mesdoevento%' AND codigodoevento='$refDoPost' GROUP BY miniatura ORDER BY datadoinicio DESC, id ASC";
																else:
																	$bMiniaturas="SELECT miniatura, evento FROM agenda WHERE NOT (miniatura = '') AND codigodoevento='$refDoPost' GROUP BY miniatura ORDER BY datadoinicio DESC, id ASC";
																endif;
															endif;
															$rMini=mysqli_query($con, $bMiniaturas);
															$nMini = mysqli_num_rows($rMini);
															while($dMiniaturas=mysqli_fetch_array($rMini)):
														?>
                                                        <li class="tms-slide" data-image data-force-fit
                                                            data-as-bkg-image>
                                                            <img data-src="<?php echo $dMiniaturas['miniatura']; ?>"
                                                                src="images/blank.png"
                                                                alt="<?php echo $dMiniaturas['evento']; ?>" />
                                                        </li>
                                                        <?php
															endwhile;
														?>
                                                    </ul>
                                                    <?php
														endif;
													?>
                                                    <span class="overlay-info v-align-bottom center">
                                                        <span>
                                                            <span>
                                                                <span class="post-info">
                                                                    <span class="post-tags"><span><span
                                                                                class="post-tag label small rounded bkg-green color-white color-hover-white bkg-hover-green">
                                                                                R$ <?php echo $dMais['valor'];?>
                                                                            </span></span></span>
                                                                </span>
                                                                <span class="post-title">
                                                                    <?php echo $dMais['evento'].' / '.$datadoinicio.' até '.$datadotermino;?>
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
                </div>
            </div>

            <?php
                    include "./_modalevento.php";
                ?>

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