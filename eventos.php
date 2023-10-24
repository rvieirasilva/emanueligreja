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
	// endif;
    
    $CategoriaPage = $_GET['c'];
    $mesdoevento=$_GET['ms'];
    $mesdoevento='-'.$mesdoevento.'-';
    
    if(isset($_GET['ms']) AND !empty($_GET['ms'])):
        $linke="&ms=".$_GET['ms'].'&';
    else:
        $linke='';
    endif;
    if(isset($_GET['e']) AND !empty($_GET['e'])):
        $linkt="&e=".$_GET['e'];
    else:
        $linkt='';
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
                                                        <h1 class="mb-0">Agenda de <?php echo $ano; ?></h1>
                                                        <p class="text-large color-charcoal mb-0 mb-mobile-20">
                                                            Acesse sua área de membro para ter acesso aos <strong>eventos exclusivos</strong> para os membros da Emanuel.
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

                                    if(!isset($_SESSION['membro'])):
                                        $bMensagensV= "SELECT id FROM agenda WHERE ano='$ano' AND visibilidade='Externo' ORDER BY datadoinicio DESC, id DESC";
                                    else:
                                        $bMensagensV= "SELECT id FROM agenda WHERE ano='$ano' ORDER BY datadoinicio DESC, id DESC";
                                    endif;
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
                                ?> 
                                <div class="column width-12 pb-20 center">
                                    <a href="eventos?<?php echo $linkSeguro.$linkt;?>" class="button small rounded border-blue bkg-hover-blue color-blue color-hover-white">
                                        Todos
                                    </a>
                                    <?php
                                        if($mesnumero === '01' AND !isset($_GET['ms'])):
                                            $colormes01 = 'bkg-green bkg-hover-green color-white color-hover-white shadow';
                                        elseif($mesnumero === '01' AND $_GET['ms'] == '01'):
                                            $colormes01 = 'bkg-green bkg-hover-green color-white color-hover-white shadow';
                                        elseif(!empty($_GET['ms']) AND $_GET['ms'] == '01'):
                                            $colormes01 = 'bkg-green bkg-hover-green color-white color-hover-white shadow';
                                        elseif($mesnumero !== '01' AND !isset($_GET['ms'])):
                                            $colormes01 = 'border-green color-green bkg-hover-green color-hover-white';
                                        elseif($_GET['ms'] !== '01'):
                                            $colormes01 = 'border-green color-green bkg-hover-green color-hover-white';
                                        endif;                                    
                                    ?>
                                    <a href="eventos?<?php echo $linkSeguro."ms=01".$linkt;?>" class="button small rounded <?php echo $colormes01;?>">
                                        Jan
                                    </a>
                                    <?php
                                        if($mesnumero === '02' AND !isset($_GET['ms'])):
                                            $colormes01 = 'bkg-green bkg-hover-green color-white color-hover-white shadow';
                                        elseif($mesnumero === '02' AND $_GET['ms'] == '02'):
                                            $colormes01 = 'bkg-green bkg-hover-green color-white color-hover-white shadow';
                                        elseif(!empty($_GET['ms']) AND $_GET['ms'] == '02'):
                                            $colormes01 = 'bkg-green bkg-hover-green color-white color-hover-white shadow';
                                        elseif($mesnumero !== '02' AND !isset($_GET['ms'])):
                                            $colormes01 = 'border-green color-green bkg-hover-green color-hover-white';
                                        elseif($_GET['ms'] !== '02'):
                                            $colormes01 = 'border-green color-green bkg-hover-green color-hover-white';
                                        endif;
                                    ?>
                                    <a href="eventos?<?php echo $linkSeguro."ms=02".$linkt;?>" class="button small rounded <?php echo $colormes01;?>">
                                        Fev
                                    </a>
                                    <?php
                                        if($mesnumero === '03' AND !isset($_GET['ms'])):
                                            $colormes01 = 'bkg-green bkg-hover-green color-white color-hover-white shadow';
                                        elseif($mesnumero === '03' AND $_GET['ms'] == '03'):
                                            $colormes01 = 'bkg-green bkg-hover-green color-white color-hover-white shadow';
                                        elseif(!empty($_GET['ms']) AND $_GET['ms'] == '03'):
                                            $colormes01 = 'bkg-green bkg-hover-green color-white color-hover-white shadow';
                                        elseif($mesnumero !== '03' AND !isset($_GET['ms'])):
                                            $colormes01 = 'border-green color-green bkg-hover-green color-hover-white';
                                        elseif($_GET['ms'] !== '03'):
                                            $colormes01 = 'border-green color-green bkg-hover-green color-hover-white';
                                        endif;
                                    ?>
                                    <a href="eventos?<?php echo $linkSeguro."ms=03".$linkt;?>" class="button small rounded <?php echo $colormes01;?>">
                                        Mar
                                    </a>
                                    <?php
                                        if($mesnumero === '04' AND !isset($_GET['ms'])):
                                            $colormes01 = 'bkg-green bkg-hover-green color-white color-hover-white shadow';
                                        elseif($mesnumero === '04' AND $_GET['ms'] == '04'):
                                            $colormes01 = 'bkg-green bkg-hover-green color-white color-hover-white shadow';
                                        elseif(!empty($_GET['ms']) AND $_GET['ms'] == '04'):
                                            $colormes01 = 'bkg-green bkg-hover-green color-white color-hover-white shadow';
                                        elseif($mesnumero !== '04' AND !isset($_GET['ms'])):
                                            $colormes01 = 'border-green color-green bkg-hover-green color-hover-white';
                                        elseif($_GET['ms'] !== '04'):
                                            $colormes01 = 'border-green color-green bkg-hover-green color-hover-white';
                                        endif;
                                    ?>
                                    <a href="eventos?<?php echo $linkSeguro."ms=04".$linkt;?>" class="button small rounded <?php echo $colormes01;?>">
                                        Abr
                                    </a>
                                    <?php
                                        if($mesnumero === '05' AND !isset($_GET['ms'])):
                                            $colormes01 = 'bkg-green bkg-hover-green color-white color-hover-white shadow';
                                        elseif($mesnumero === '05' AND $_GET['ms'] == '05'):
                                            $colormes01 = 'bkg-green bkg-hover-green color-white color-hover-white shadow';
                                        elseif(!empty($_GET['ms']) AND $_GET['ms'] == '05'):
                                            $colormes01 = 'bkg-green bkg-hover-green color-white color-hover-white shadow';
                                        elseif($mesnumero !== '05' AND !isset($_GET['ms'])):
                                            $colormes01 = 'border-green color-green bkg-hover-green color-hover-white';
                                        elseif($_GET['ms'] !== '05'):
                                            $colormes01 = 'border-green color-green bkg-hover-green color-hover-white';
                                        endif;
                                    ?>
                                    <a href="eventos?<?php echo $linkSeguro."ms=05".$linkt;?>" class="button small rounded <?php echo $colormes01;?>">
                                        Mai
                                    </a>
                                    <?php
                                        if($mesnumero === '06' AND !isset($_GET['ms'])):
                                            $colormes01 = 'bkg-green bkg-hover-green color-white color-hover-white shadow';
                                        elseif($mesnumero === '06' AND $_GET['ms'] == '06'):
                                            $colormes01 = 'bkg-green bkg-hover-green color-white color-hover-white shadow';
                                        elseif(!empty($_GET['ms']) AND $_GET['ms'] == '06'):
                                            $colormes01 = 'bkg-green bkg-hover-green color-white color-hover-white shadow';
                                        elseif($mesnumero !== '06' AND !isset($_GET['ms'])):
                                            $colormes01 = 'border-green color-green bkg-hover-green color-hover-white';
                                        elseif($_GET['ms'] !== '06'):
                                            $colormes01 = 'border-green color-green bkg-hover-green color-hover-white';
                                        endif;
                                    ?>
                                    <a href="eventos?<?php echo $linkSeguro."ms=06".$linkt;?>" class="button small rounded <?php echo $colormes01;?>">
                                        Jun
                                    </a>
                                    <?php
                                        if($mesnumero === '07' AND !isset($_GET['ms'])):
                                            $colormes01 = 'bkg-green bkg-hover-green color-white color-hover-white shadow';
                                        elseif($mesnumero === '07' AND $_GET['ms'] == '07'):
                                            $colormes01 = 'bkg-green bkg-hover-green color-white color-hover-white shadow';
                                        elseif(!empty($_GET['ms']) AND $_GET['ms'] == '07'):
                                            $colormes01 = 'bkg-green bkg-hover-green color-white color-hover-white shadow';
                                        elseif($mesnumero !== '07' AND !isset($_GET['ms'])):
                                            $colormes01 = 'border-green color-green bkg-hover-green color-hover-white';
                                        elseif($_GET['ms'] !== '07'):
                                            $colormes01 = 'border-green color-green bkg-hover-green color-hover-white';
                                        endif;
                                    ?>
                                    <a href="eventos?<?php echo $linkSeguro."ms=07".$linkt;?>" class="button small rounded <?php echo $colormes01;?>">
                                        Jul
                                    </a>
                                    <?php
                                        if($mesnumero === '08' AND !isset($_GET['ms'])):
                                            $colormes01 = 'bkg-green bkg-hover-green color-white color-hover-white shadow';
                                        elseif($mesnumero === '08' AND $_GET['ms'] == '08'):
                                            $colormes01 = 'bkg-green bkg-hover-green color-white color-hover-white shadow';
                                        elseif(!empty($_GET['ms']) AND $_GET['ms'] == '08'):
                                            $colormes01 = 'bkg-green bkg-hover-green color-white color-hover-white shadow';
                                        elseif($mesnumero !== '08' AND !isset($_GET['ms'])):
                                            $colormes01 = 'border-green color-green bkg-hover-green color-hover-white';
                                        elseif($_GET['ms'] !== '08'):
                                            $colormes01 = 'border-green color-green bkg-hover-green color-hover-white';
                                        endif;
                                    ?>
                                    <a href="eventos?<?php echo $linkSeguro."ms=08".$linkt;?>" class="button small rounded <?php echo $colormes01;?>">
                                        Ago
                                    </a>
                                    <?php
                                        if($mesnumero === '09' AND !isset($_GET['ms'])):
                                            $colormes01 = 'bkg-green bkg-hover-green color-white color-hover-white shadow';
                                        elseif($mesnumero === '09' AND $_GET['ms'] == '09'):
                                            $colormes01 = 'bkg-green bkg-hover-green color-white color-hover-white shadow';
                                        elseif(!empty($_GET['ms']) AND $_GET['ms'] == '09'):
                                            $colormes01 = 'bkg-green bkg-hover-green color-white color-hover-white shadow';
                                        elseif($mesnumero !== '09' AND !isset($_GET['ms'])):
                                            $colormes01 = 'border-green color-green bkg-hover-green color-hover-white';
                                        elseif($_GET['ms'] !== '09'):
                                            $colormes01 = 'border-green color-green bkg-hover-green color-hover-white';
                                        endif;
                                    ?>
                                    <a href="eventos?<?php echo $linkSeguro."ms=09".$linkt;?>" class="button small rounded <?php echo $colormes01;?>">
                                        Set
                                    </a>
                                    <?php
                                        if($mesnumero === '10' AND !isset($_GET['ms'])):
                                            $colormes01 = 'bkg-green bkg-hover-green color-white color-hover-white shadow';
                                        elseif($mesnumero === '10' AND $_GET['ms'] == '10'):
                                            $colormes01 = 'bkg-green bkg-hover-green color-white color-hover-white shadow';
                                        elseif(!empty($_GET['ms']) AND $_GET['ms'] == '10'):
                                            $colormes01 = 'bkg-green bkg-hover-green color-white color-hover-white shadow';
                                        elseif($mesnumero !== '10' AND !isset($_GET['ms'])):
                                            $colormes01 = 'border-green color-green bkg-hover-green color-hover-white';
                                        elseif($_GET['ms'] !== '10'):
                                            $colormes01 = 'border-green color-green bkg-hover-green color-hover-white';
                                        endif;
                                    ?>
                                    <a href="eventos?<?php echo $linkSeguro."ms=10".$linkt;?>" class="button small rounded <?php echo $colormes01;?>">
                                        Out
                                    </a>
                                    <?php
                                        if($mesnumero === '11' AND !isset($_GET['ms'])):
                                            $colormes01 = 'bkg-green bkg-hover-green color-white color-hover-white shadow';
                                        elseif($mesnumero === '11' AND $_GET['ms'] == '11'):
                                            $colormes01 = 'bkg-green bkg-hover-green color-white color-hover-white shadow';
                                        elseif(!empty($_GET['ms']) AND $_GET['ms'] == '11'):
                                            $colormes01 = 'bkg-green bkg-hover-green color-white color-hover-white shadow';
                                        elseif($mesnumero !== '11' AND !isset($_GET['ms'])):
                                            $colormes01 = 'border-green color-green bkg-hover-green color-hover-white';
                                        elseif($_GET['ms'] !== '11'):
                                            $colormes01 = 'border-green color-green bkg-hover-green color-hover-white';
                                        endif;
                                    ?>
                                    <a href="eventos?<?php echo $linkSeguro."ms=11".$linkt;?>" class="button small rounded <?php echo $colormes01;?>">
                                        Nov
                                    </a>
                                    <?php
                                        if($mesnumero === '12' AND !isset($_GET['ms'])):
                                            $colormes01 = 'bkg-green bkg-hover-green color-white color-hover-white shadow';
                                        elseif($mesnumero === '12' AND $_GET['ms'] == '12'):
                                            $colormes01 = 'bkg-green bkg-hover-green color-white color-hover-white shadow';
                                        elseif(!empty($_GET['ms']) AND $_GET['ms'] == '12'):
                                            $colormes01 = 'bkg-green bkg-hover-green color-white color-hover-white shadow';
                                        elseif($mesnumero !== '12' AND !isset($_GET['ms'])):
                                            $colormes01 = 'border-green color-green bkg-hover-green color-hover-white';
                                        elseif($_GET['ms'] !== '12'):
                                            $colormes01 = 'border-green color-green bkg-hover-green color-hover-white';
                                        endif;
                                    ?>
                                    <a href="eventos?<?php echo $linkSeguro."ms=12".$linkt;?>" class="button small rounded <?php echo $colormes01;?>">
                                        Dez
                                    </a>
                                </div>
                                <div class="column width-12 right pb-0">
                                    <a href="eventos?<?php echo $linkSeguro.$linke."e=grid";?>">
                                        <span class="button small rounded border-blue bkg-hover-blue color-blue color-hover-white"><span class="icon-grid small"></span></span>
                                    </a>
                                    <a href="eventos?<?php echo $linkSeguro.$linke."e=table";?>">
                                        <span class="button small rounded border-blue bkg-hover-blue color-blue color-hover-white"><span class="icon-list small"></span></span>
                                    </a>
                                </div>
                            </div>
                                   
                            <div class="box rounded rounded shadow border-blue bkg-white">   
                                <?php
                                    if(!isset($_GET['e']) OR empty($_GET['e'])):
                                        include "./_eventosgrid.php";
                                    elseif($_GET['e'] === 'grid'):
                                        include "./_eventosgrid.php";
                                    elseif($_GET['e'] === 'table'):
                                        include "./_eventostable.php";
                                    endif;
                                ?>  
                            </div>
                        </div>
                    </div>                    
                </div>
                <!-- Content End -->
                <div class="printable"></div>
                
                <!-- Pagination Section 3 -->
				<div class="section-block pagination-3 bkg-grey-ultralight pt-30">
					<div class="row">
                        <div class="box rounded bkg-white border-blue small">
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