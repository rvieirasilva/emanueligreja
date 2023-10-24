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
    
    $CategoriaPage = $_GET['c'];
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
                                                        <h1 class="mb-0"><strong><?php
                                                        if(!empty($CategoriaPage)):
                                                            echo $CategoriaPage;
                                                        else:
                                                            echo "Categoria inexistente";
                                                        endif;?></strong>.</h1>
                                                        <p class="text-large color-charcoal mb-0 mb-mobile-20">
                                                            Nossa missão é <strong>Anunciar Jesus e ser culturalmente relevante.</strong>
                                                        </p>
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

                                    $bMensagensV= "SELECT id FROM blog WHERE datadapostagem <= '$dataHora' AND categoria='$CategoriaPage' ORDER BY id DESC";
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
                                ?>    
                                <?php
                                    if($CategoriaPage === 'Vídeos'):
                                        include "./newsvideo.php";
                                    elseif($CategoriaPage === 'PodCasts'):
                                        include "./newspodcast.php";
                                    elseif($CategoriaPage === 'Close'):
                                        include "./newsclose.php";
                                    elseif($CategoriaPage === 'Teologia'):
                                        include "./newsteologia.php";
                                    elseif($CategoriaPage !== 'Close' OR $CategoriaPage !== 'PodCasts' OR $CategoriaPage !== 'Videos'):
                                        include "./newsgeral.php";
                                    else:
                                ?>
                                        <div class="content-inner blog-masonry grid-container pt-20 pb-30 bkg-white" data-layout-mode="masonry" data-grid-ratio="1.5" data-animate-resize data-set-dimensions data-animate-resize-duration="600" data-as-bkg-image>
                                            <div class="row">
                                                <div class="column width-12">
                                                    <h1>
                                                        Não há publicação para essa categoria.
                                                    </h1>
                                                </div>
                                            </div>
                                        </div>
                                <?
                                    endif;
                                    endif;
                                ?>          
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Content End -->
                <div class="printable"></div>
                
                <!-- Pagination Section 3 -->
				<!-- <div class="section-block pagination-3 bkg-grey-ultralight pt-30">
					<div class="row">
                        <div class="box rounded bkg-white border-blue small">
                            <div class="column width-12">
                                <?php
                                    //include "./_paginacao.php";
                                ?>
                            </div>
						</div>
					</div>
				</div> -->
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