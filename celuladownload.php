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
    
    // $CategoriaPage = $_GET['c'];
    $CategoriaPage = "Esboço de célula";
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
                                                        <h1 class="mb-0">Acervo para suporte das <strong>células</strong></h1>
                                                        <p class="text-large color-charcoal mb-0 mb-mobile-20">Nossa missão é <strong>anunciar Jesus e ser culturalmente relevante</strong></p>
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
                                <?php
                                    // <!--CÓDIGO PARA FAZER PESQUISA DOS HORÁRIOS E CRIAR O LOOPING -->
                                    $pagina = (isset($_GET['pagina']))? $_GET['pagina'] : 1;

                                    $bMensagensV= "SELECT id FROM blog WHERE datadapostagem <= '$dataHora' AND categoria='$CategoriaPage' AND anexo!='' ORDER BY id DESC";
                                        $rMv = mysqli_query($con, $bMensagensV);
                                        
                                        $totaldeposts = mysqli_num_rows($rMv);

                                        //Defini o número de horarios que serão exibidos por página
                                        if($CategoriaPage === "Videos"):
                                            $postsporpagina = 3;
                                        else:
                                            $postsporpagina = 12;
                                        endif;

                                        //defini número de páginas necessárias para exibir todos os horarios.
                                        $totaldepaginas = ceil($totaldeposts / $postsporpagina);

                                        $inicio = ($postsporpagina * $pagina) - $postsporpagina;

                                            //Informação de posts
                                            $Vendo = 0;
                                                while($contPosts=mysqli_fetch_array($rMv)):
                                            $Vendo++;
                                                endwhile;

                                            //if($pagina > 1):
                                            //    $Vendo0 = ($pagina * $postsporpagina) + $Vendo;
                                            //else:
                                            //    $Vendo = $postsporpagina;
                                            //endif;
                                    if($totaldeposts > 0):
                                        if($CategoriaPage === "Esboço de célula"):
                                ?>
                                <div class="content-inner blog-masonry grid-container pt-20 pb-30 bkg-white"
                                    data-layout-mode="masonry" data-grid-ratio="1.5" data-animate-resize data-set-dimensions
                                    data-animate-resize-duration="600" data-as-bkg-image>
                                    <div class="row">
                                        <div class="column width-12">
                                            <div class="row grid content-grid-3">
                                                <?php    
                                                    //AQUI ELE FAZ A PESQUISA PELO CAMPUS E TRAZ O DADO MAIS RECENTE PELO ANO, SE ESTAMOS EM 2010 ELE TRARÁ OS DADOS DESTE ANO OU DO ÚLTIMO ANO EM QUE O CAMPUS APRESENTOU UMA MATÉRIA REGISTRADA, COM BASE NISSO É FEITO UM FILTRO EXIBINDO POR ORDEM O DIA E DEPOIS O HORÁRIO.
                                                    $bMais = "SELECT referencia, titulo, textoancora, miniatura, video, anexo, categoria, datadapostagem, capa, visualizacoes FROM blog WHERE anexo!='' AND datadapostagem <= '$dataHora' AND categoria='$CategoriaPage' ORDER BY datadapostagem DESC LIMIT $inicio, $postsporpagina";
                                                        $rMais = mysqli_query($con, $bMais);
                                                                $gridItem = 0;
                                                            while($dMais = mysqli_fetch_array($rMais)):
                                                                $gridItem++;											
                                                        $Msgs++;
            
                                                        $refDoPost    = $dMais['referencia'];
                                                        $titulodopost = $dMais['titulo'];
                                                        $titulo 	  = str_replace(' ', '-', $titulodopost);
                                                    if($gridItem === 1):
                                                ?>
                                                <?php
                                                                if(!empty($dMais['video'])):
                                                            ?>
                                                <div class="grid-item grid-sizer wide large">
                                                    <?php
                                                                elseif(empty($dMais['video'])):
                                                            ?>
                                                    <div class="grid-item grid-sizer wide large">
                                                        <?php
                                                                endif;
                                                            ?>
                                                        <div class="thumbnail rounded overlay-fade-img-scale-in" data-hover-easing="easeInOut"
                                                            data-hover-speed="700" data-hover-bkg-color="#000000" data-gradient
                                                            data-gradient-spread="70%" data-hover-bkg-opacity="0.75">
                                                            <a class="overlay-link" href="<?php echo $dMais['anexo'];?>" target="_blank">
                                                                <?php
                                                                            if(!empty($dMais['video'])):
                                                                        ?>
                                                                <iframe src="<?php echo $dMais['video'];?>?loop=1" width="500"
                                                                    height="300"></iframe>
                                                                <?php
                                                                            elseif(empty($dMais['video'])):
                                                                        ?>
                                                                <img src="<?php echo $dMais['miniatura'];?>"
                                                                    alt="<?php echo $dMais['titulo'];?>" />
                                                                <?php
                                                                            endif;
                                                                        ?>
                                                                <span class="overlay-info v-align-bottom center">
                                                                    <span>
                                                                        <span>
                                                                            <span class="post-info">
                                                                                <span class="post-tags"><span><span
                                                                                            class="post-tag label small rounded bkg-pink color-white bkg-hover-pink bkg-hover-white">
                                                                                            <?php echo $dMais['categoria'];?>
                                                                                        </span></span></span>
                                                                            </span>
                                                                            <span class="post-title">
                                                                                <?php echo $dMais['titulo'];?>
                                                                            </span>
                                                                            <span class="post-info">
                                                                                <span class="post-date"><?php echo $dMais['visualizacoes'];?>
                                                                                    views</span>
                                                                            </span>
                                                                        </span>
                                                                    </span>
                                                                </span>
                                                            </a>
                                                        </div>
                                                    </div>
                                                    <?php
                                                                elseif($gridItem > 1):
                                                            ?>
                                                    <div class="grid-item">
                                                        <div class="thumbnail rounded overlay-fade-img-scale-in" data-hover-easing="easeInOut"
                                                            data-hover-speed="700" data-hover-bkg-color="#000000" data-gradient
                                                            data-gradient-spread="70%" data-hover-bkg-opacity="0.75">
                                                            <a class="overlay-link" href="<?php echo $dMais['anexo'];?>" target="_blank">
                                                                <?php
                                                                            if(!empty($dMais['video'])):
                                                                        ?>
                                                                <iframe src="<?php echo $dMais['video'];?>?loop=1" width="500"
                                                                    height="300"></iframe>
                                                                <?php
                                                                            elseif(empty($dMais['video'])):
                                                                        ?>
                                                                <img src="<?php echo $dMais['miniatura'];?>"
                                                                    alt="<?php echo $dMais['titulo'];?>" />
                                                                <?php
                                                                            endif;
                                                                        ?>
                                                                <span class="overlay-info v-align-bottom center">
                                                                    <span>
                                                                        <span>
                                                                            <span class="post-info">
                                                                                <span class="post-tags"></span>
                                                                                <span
                                                                                    class="post-tag label small rounded bkg-gradient-royal-garden color-white bkg-hover-pink bkg-hover-white">
                                                                                    <?php echo $dMais['categoria'];?>
                                                                                </span>
                                                                            </span>
                                                                        </span>
                                                                        <span class="post-title">
                                                                            <?php echo $dMais['titulo'];?>
                                                                        </span>
                                                                        <span class="post-info">
                                                                            <span class="post-date"><?php echo $dMais['visualizacoes'];?>
                                                                                views</span>
                                                                        </span>
                                                                    </span>
                                                                </span>
                                                                </span>
                                                            </a>
                                                        </div>
                                                    </div>
                                                    <?php
                                                                endif;
                                                            endwhile;
                                                            ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Masonry Blog Grid End -->
                                    <?php
                                            endif;
                                            else:
                                        ?>
                                    <!-- Masonry Blog Grid -->
                                    <div class="content-inner blog-masonry grid-container pt-20 pb-30 bkg-white"
                                        data-layout-mode="masonry" data-grid-ratio="1.5" data-animate-resize data-set-dimensions
                                        data-animate-resize-duration="600" data-as-bkg-image>
                                        <div class="row">
                                            <div class="column width-12">
                                                <h3>
                                                    Não há downloads disponíveis.
                                                </h3>
                                            </div>
                                        </div>
                                    </div>
                                    <?php
                                            endif;
                                        ?>
                                </div>
                            </div>
                        </div>
                    </div>                    
                </div>
                <!-- Content End -->
                <div class="printable"></div>
                
                <!-- Pagination Section 3 -->
				<div class="section-block pagination-3 bkg-grey-ultralight pt-30">
					<div class="row">
                        <div class="box rounded bkg-white border-blue small shadow">
                            <div class="column width-12">
                                <?php
                                    include "./_paginacao.php";
                                ?>
                            </div>
						</div>
					</div>
				</div>
				<!-- Pagination Section 3 End -->
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