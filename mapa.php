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
            include "./_menuinterno.php";
        // endif;
	?>

			<!-- Content -->
			<div class="content clearfix">
				<div class="section-block no-padding">
					<div class="row collapse full-width">
						<div class="column width-12 center">
                            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d4374.706571057985!2d-43.29167763386511!3d-22.7774366715717!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x997bb6ae723135%3A0xb6cb4b312fcf2b30!2sIgreja%20Batista%20Emanuel!5e0!3m2!1spt-BR!2sbr!4v1675202942527!5m2!1spt-BR!2sbr" width="100vh" height="600" style="border:0;" allowfullscreen="true" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
						</div>
					</div>
				</div>

				<!-- <div class="section-block large bkg-white clear-height">
					<div class="row flex two-columns-on-tablet">
						<div class="column width-7 left v-align-middle" data-threshold="1">
							<div>
								<h1 class="mb-5 color-charcoal">Oferta não tem valor, <strong>tem significado.</strong></h1> <span>(Pastor Rafael Vieira)</span>
								<h3 class="color-charcoal opacity-09">
									<strong>Nós construímos a história da igreja</strong>. O trabalho social e as novas igrejas são fruto do amor de Cristo em sua vida e de sua fidelidade. Aqui você pode ver os frutos da sua fideliade e acompanhar com transparência os investimentos realizados.
								</h3>
							</div>
						</div>
						<div class="column width-5 right left-on-mobile v-align-middle" data-threshold="1">
							<div>
								<a href="doacoes" class="column width-12 pb-10 button rounded large border-blue color-blue bkg-hover-blue color-hover-white mb-0">
									CLIQUE PARA OFERTAR
								</a>
								<!-- <a href="construindoportodos" class="column width-12 button rounded large bkg-theme color-white bkg-hover-theme color-hover-white no-margin-left mb-0">
									CONSTRUINDO POR TODOS (Campanha)
								</a> ->
							</div>
						</div>
					</div>
				</div> -->

                <?php include "./bloginterpages.php"; ?>
                
				<div class="section-block pb-80 recent-carousel bkg-white">
					<div class="row">
						<div class="column width-12 center">
							<h3 class="weight-bold mb-0">Próximos eventos deste mês</h3>
							<span><strong>Nossos cultos:</strong> Domingo às 18h e Quinta-feira às 20h <br> siga-nos nas mídias sociais (@emanueligrejabr) e visite uma célula</span>
						</div>
					</div>
					<div class="row full-width collapse">
						<div class="section-block blog-masonry grid-container pt-20 pb-30 bkg-white" data-layout-mode="masonry" data-grid-ratio="1.5" data-animate-resize data-set-dimensions data-animate-resize-duration="600" data-as-bkg-image>
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
											<div class="thumbnail tm-slider-container content-slider post-slider rounded overlay-fade-img-scale-in" data-hover-easing="easeInOut" data-hover-speed="700" data-hover-bkg-color="#000000" data-gradient data-gradient-spread="70%" data-hover-bkg-opacity="0.75">
												<a class="overlay-link" href="evento?<?php echo $linkSeguro;?>pos=<?php echo $dMais['codigodoevento'];?>&<?php include "_filtroparalink.php";   $tituloamigavel = strTr($titulo, $filtro); echo $tituloamigavel; ?>">
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
														<li class="tms-slide" data-image data-force-fit data-as-bkg-image>
															<img data-src="<?php echo $dMiniaturas['miniatura']; ?>" src="images/blank.png" alt="<?php echo $dMiniaturas['evento']; ?>"/>
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
																	<span class="post-tags"><span><span class="post-tag label small rounded bkg-green color-white color-hover-white bkg-hover-green">
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