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

    $nomedarede = $_GET['r'];

	$bRede = "SELECT * from rede WHERE nomedarede != '' AND nomedarede='$nomedarede' ORDER BY id desc";
	  $rRede = mysqli_query($con, $bRede);
	    $dRede = mysqli_fetch_array($rRede);
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
                                                        <h1 class="mb-0">
                                                            <?php
                                                                if(!empty($dRede['nomedarede'])):
                                                                    echo $dRede['nomedarede'];
                                                                else:
                                                                    echo "Essa rede não existe.";
                                                                endif;
                                                            ?>
                                                        </h1>
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
                                <div class="column width-4 right left-on-mobile">
                                    <div class="pu-10">
                                        <p class="lead text-large weight-bold mb-50">
                                            Rede de <?php echo $dRede['areadarede']; ?>
                                        </p>
                                    </div>
                                </div>
                                <div class="column width-8">
                                    <div class="row flex">
                                        <div class="column width-12">
                                            <?php
                                                if(!empty($dRede['descricao'])):
                                            ?>
                                            <h6 class="mb-30 weight-bold text-uppercase">Descrição da rede.</h6>
                                            <p class="mb-50">
                                                <?php echo $dRede['descricao']; ?>
                                            </p>
                                            <?php
                                                else:
                                            ?>
                                            <h6 class="mb-30 weight-bold text-uppercase">Descrição da rede.</h6>
                                            <p class="mb-50">
                                                Não encontramos os dados para essa rede que você procurou.
                                            </p>
                                            <?php
                                                endif;
                                            ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="box rounded rounded shadow border-blue bkg-white">
                                <div class="column width-6">
                                    <h2 class="mb-50">Líderes da rede.</h2>
                                </div>
                                <div class="column width-12">
                                    <div class="row content-grid-2">
                                        <div class="grid-item horizon" data-animate-in="preset:slideInUpShort;duration:1000ms;" data-threshold="0.3">
                                            <h4 class="color-grey mb-5"><?php $OLidArray = explode(' ', $dRede['liderdarede']); echo $OLidArray[0].' '.$OLidArray[1];?></h4>
                                            <?php
                                                $matriculadolider=$dRede['matricula'];
                                                //Buscar dados do líder informado
                                                $bL="SELECT ministerio, fotodeperfil, descricaodomembro, midiasocial1, link1, midiasocial2, link2, midiasocial3, link3, midiasocial4, link4 FROM membros WHERE matricula='$matriculadolider'";
                                                $rL=mysqli_query($con, $bL);
                                                    $dL=mysqli_fetch_array($rL);
                                            ?>
                                            <h4 class="occupation"><?php echo $dL['ministerio'];?></h4>
                                            <div class="thumbnail no-margin-bottom" data-hover-easing="easeInOut" data-hover-speed="500" data-hover-bkg-color="#ffffff" data-hover-bkg-opacity="0.9">
                                                <img src="<?php echo $dL['fotodeperfil'];?>" style="width:330px; height:500px;" width="760" height="500" alt="<?php echo $dRede['lider'];?>"/>
                                            </div>
                                            <div class="team-content-info">
                                                <p>
                                                    <?php echo $dL['descricaodomembro'];?>
                                                </p>
                                                <ul class="social-list list-horizontal">
                                                    <li>
                                                        <a href="<?php $link1 = base64_decode($dL['link1']); echo base64_decode($link1);?>">
                                                            <span class="icon-<?php echo $dL['midiasocial1'];?>-with-circle medium"></span>
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a href="<?php $link2 = base64_decode($dL['link2']); echo base64_decode($link2);?>">
                                                            <span class="icon-<?php echo $dL['midiasocial2'];?>-with-circle medium"></span>
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a href="<?php $link3 = base64_decode($dL['link3']); echo base64_decode($link3);?>">
                                                            <span class="icon-<?php echo $dL['midiasocial3'];?>-with-circle medium"></span>
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <?php
                                        if(!empty($dRede['vicelider'])):
                                    ?>
                                    <div class="row content-grid-2">
                                        <div class="grid-item horizon" data-animate-in="preset:slideInUpShort;duration:1000ms;" data-threshold="0.3">
                                            <h4 class="color-grey mb-5"><?php $OLidArray = explode(' ', $dRede['vicelider']); echo $OLidArray[0].' '.$OLidArray[1];?></h4>
                                            <?php
                                                $matriculavicelider=$dRede['matriculavicelider'];
                                                //Buscar dados do líder informado
                                                $bL="SELECT ministerio, fotodeperfil, descricaodomembro, midiasocial1, link1, midiasocial2, link2, midiasocial3, link3, midiasocial4, link4 FROM membros WHERE matricula='$matriculavicelider'";
                                                $rL=mysqli_query($con, $bL);
                                                    $dL=mysqli_fetch_array($rL);
                                            ?>
                                            <h4 class="occupation"><?php echo $dL['ministerio'];?></h4>
                                            <div class="thumbnail no-margin-bottom" data-hover-easing="easeInOut" data-hover-speed="500" data-hover-bkg-color="#ffffff" data-hover-bkg-opacity="0.9">
                                                <img src="<?php echo $dL['fotodeperfil'];?>" style="width:330px; height:500px;" width="760" height="500" alt="<?php echo $dRede['lider'];?>"/>
                                            </div>
                                            <div class="team-content-info">
                                                <p>
                                                    <?php echo $dL['descricaodomembro'];?>
                                                </p>
                                                <ul class="social-list list-horizontal">
                                                    <li><a href="<?php $link1 = base64_decode($dL['link1']); echo base64_decode($link1);?>"><span class="icon-<?php echo $dL['midiasocial1'];?>-with-circle medium"></span></a></li>
                                                    <li><a href="<?php $link2 = base64_decode($dL['link2']); echo base64_decode($link2);?>"><span class="icon-<?php echo $dL['midiasocial2'];?>-with-circle medium"></span></a></li>
                                                    <li><a href="<?php $linkl = base64_decode($dL['linkl']); echo base64_decode($linkl);?>"><span class="icon-<?php echo $dL['midiasocial3'];?>-with-circle medium"></span></a></li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <?php
                                        endif;
                                        if(!empty($dRede['segundovp'])):
                                    ?>
                                    <div class="row content-grid-2">
                                        <div class="grid-item horizon" data-animate-in="preset:slideInUpShort;duration:1000ms;" data-threshold="0.3">
                                            <h4 class="color-grey mb-5"><?php $OLidArray = explode(' ', $dRede['segundovp']); echo $OLidArray[0].' '.$OLidArray[1];?></h4>
                                            <?php
                                                $matriculasegundovp=$dRede['matriculasegundovp'];
                                                //Buscar dados do líder informado
                                                $bL="SELECT ministerio, fotodeperfil, descricaodomembro, midiasocial1, link1, midiasocial2, link2, midiasocial3, link3, midiasocial4, link4 FROM membros WHERE matricula='$matriculasegundovp'";
                                                $rL=mysqli_query($con, $bL);
                                                    $dL=mysqli_fetch_array($rL);
                                            ?>
                                            <h4 class="occupation"><?php echo $dL['ministerio'];?></h4>
                                            <div class="thumbnail no-margin-bottom" data-hover-easing="easeInOut" data-hover-speed="500" data-hover-bkg-color="#ffffff" data-hover-bkg-opacity="0.9">
                                                <img src="<?php echo $dL['fotodeperfil'];?>" style="width:330px; height:500px;" width="760" height="500" alt="<?php echo $dRede['lider'];?>"/>
                                            </div>
                                            <div class="team-content-info">
                                                <p>
                                                    <?php echo $dL['descricaodomembro'];?>
                                                </p>
                                                <ul class="social-list list-horizontal">
                                                    <li><a href="<?php $link1 = base64_decode($dL['link1']); echo base64_decode($link1);?>"><span class="icon-<?php echo $dL['midiasocial1'];?>-with-circle medium"></span></a></li>
                                                    <li><a href="<?php $link2 = base64_decode($dL['link2']); echo base64_decode($link2);?>"><span class="icon-<?php echo $dL['midiasocial2'];?>-with-circle medium"></span></a></li>
                                                    <li><a href="<?php $linkl = base64_decode($dL['linkl']); echo base64_decode($linkl);?>"><span class="icon-<?php echo $dL['midiasocial3'];?>-with-circle medium"></span></a></li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <?php
                                        endif;
                                        if(!empty($dRede['secretario'])):
                                    ?>
                                    <div class="row content-grid-2">
                                        <div class="grid-item horizon" data-animate-in="preset:slideInUpShort;duration:1000ms;" data-threshold="0.3">
                                            <h4 class="color-grey mb-5"><?php $OLidArray = explode(' ', $dRede['secretario']); echo $OLidArray[0].' '.$OLidArray[1];?></h4>
                                            <?php
                                                $matriculasecretario=$dRede['matriculasecretario'];
                                                //Buscar dados do líder informado
                                                $bL="SELECT ministerio, fotodeperfil, descricaodomembro, midiasocial1, link1, midiasocial2, link2, midiasocial3, link3, midiasocial4, link4 FROM membros WHERE matricula='$matriculasecretario'";
                                                $rL=mysqli_query($con, $bL);
                                                    $dL=mysqli_fetch_array($rL);
                                            ?>
                                            <h4 class="occupation"><?php echo $dL['ministerio'];?></h4>
                                            <div class="thumbnail no-margin-bottom" data-hover-easing="easeInOut" data-hover-speed="500" data-hover-bkg-color="#ffffff" data-hover-bkg-opacity="0.9">
                                                <img src="<?php echo $dL['fotodeperfil'];?>" style="width:330px; height:500px;" width="760" height="500" alt="<?php echo $dRede['lider'];?>"/>
                                            </div>
                                            <div class="team-content-info">
                                                <p>
                                                    <?php echo $dL['descricaodomembro'];?>
                                                </p>
                                                <ul class="social-list list-horizontal">
                                                    <li><a href="<?php $link1 = base64_decode($dL['link1']); echo base64_decode($link1);?>"><span class="icon-<?php echo $dL['midiasocial1'];?>-with-circle medium"></span></a></li>
                                                    <li><a href="<?php $link2 = base64_decode($dL['link2']); echo base64_decode($link2);?>"><span class="icon-<?php echo $dL['midiasocial2'];?>-with-circle medium"></span></a></li>
                                                    <li><a href="<?php $linkl = base64_decode($dL['linkl']); echo base64_decode($linkl);?>"><span class="icon-<?php echo $dL['midiasocial3'];?>-with-circle medium"></span></a></li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <?php
                                        endif;
                                        if(!empty($dRede['segundosecretario'])):
                                    ?>
                                    <div class="row content-grid-2">
                                        <div class="grid-item horizon" data-animate-in="preset:slideInUpShort;duration:1000ms;" data-threshold="0.3">
                                            <h4 class="color-grey mb-5"><?php $OLidArray = explode(' ', $dRede['segundosecretario']); echo $OLidArray[0].' '.$OLidArray[1];?></h4>
                                            <?php
                                                $matriculasegundosecretario=$dRede['matriculasegundosecretario'];
                                                //Buscar dados do líder informado
                                                $bL="SELECT ministerio, fotodeperfil, descricaodomembro, midiasocial1, link1, midiasocial2, link2, midiasocial3, link3, midiasocial4, link4 FROM membros WHERE matricula='$matriculasegundosecretario'";
                                                $rL=mysqli_query($con, $bL);
                                                    $dL=mysqli_fetch_array($rL);
                                            ?>
                                            <h4 class="occupation"><?php echo $dL['ministerio'];?></h4>
                                            <div class="thumbnail no-margin-bottom" data-hover-easing="easeInOut" data-hover-speed="500" data-hover-bkg-color="#ffffff" data-hover-bkg-opacity="0.9">
                                                <img src="<?php echo $dL['fotodeperfil'];?>" style="width:330px; height:500px;" width="760" height="500" alt="<?php echo $dRede['lider'];?>"/>
                                            </div>
                                            <div class="team-content-info">
                                                <p>
                                                    <?php echo $dL['descricaodomembro'];?>
                                                </p>
                                                <ul class="social-list list-horizontal">
                                                    <li><a href="<?php $link1 = base64_decode($dL['link1']); echo base64_decode($link1);?>"><span class="icon-<?php echo $dL['midiasocial1'];?>-with-circle medium"></span></a></li>
                                                    <li><a href="<?php $link2 = base64_decode($dL['link2']); echo base64_decode($link2);?>"><span class="icon-<?php echo $dL['midiasocial2'];?>-with-circle medium"></span></a></li>
                                                    <li><a href="<?php $linkl = base64_decode($dL['linkl']); echo base64_decode($linkl);?>"><span class="icon-<?php echo $dL['midiasocial3'];?>-with-circle medium"></span></a></li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <?php
                                        endif;
                                        if(!empty($dRede['tesoureiro'])):
                                    ?>
                                    <div class="row content-grid-2">
                                        <div class="grid-item horizon" data-animate-in="preset:slideInUpShort;duration:1000ms;" data-threshold="0.3">
                                            <h4 class="color-grey mb-5"><?php $OLidArray = explode(' ', $dRede['tesoureiro']); echo $OLidArray[0].' '.$OLidArray[1];?></h4>
                                            <?php
                                                $matriculatesoureiro=$dRede['matriculatesoureiro'];
                                                //Buscar dados do líder informado
                                                $bL="SELECT ministerio, fotodeperfil, descricaodomembro, midiasocial1, link1, midiasocial2, link2, midiasocial3, link3, midiasocial4, link4 FROM membros WHERE matricula='$matriculatesoureiro'";
                                                $rL=mysqli_query($con, $bL);
                                                    $dL=mysqli_fetch_array($rL);
                                            ?>
                                            <h4 class="occupation"><?php echo $dL['ministerio'];?></h4>
                                            <div class="thumbnail no-margin-bottom" data-hover-easing="easeInOut" data-hover-speed="500" data-hover-bkg-color="#ffffff" data-hover-bkg-opacity="0.9">
                                                <img src="<?php echo $dL['fotodeperfil'];?>" style="width:330px; height:500px;" width="760" height="500" alt="<?php echo $dRede['lider'];?>"/>
                                            </div>
                                            <div class="team-content-info">
                                                <p>
                                                    <?php echo $dL['descricaodomembro'];?>
                                                </p>
                                                <ul class="social-list list-horizontal">
                                                    <li><a href="<?php $link1 = base64_decode($dL['link1']); echo base64_decode($link1);?>"><span class="icon-<?php echo $dL['midiasocial1'];?>-with-circle medium"></span></a></li>
                                                    <li><a href="<?php $link2 = base64_decode($dL['link2']); echo base64_decode($link2);?>"><span class="icon-<?php echo $dL['midiasocial2'];?>-with-circle medium"></span></a></li>
                                                    <li><a href="<?php $linkl = base64_decode($dL['linkl']); echo base64_decode($linkl);?>"><span class="icon-<?php echo $dL['midiasocial3'];?>-with-circle medium"></span></a></li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <?php
                                        endif;
                                        if(!empty($dRede['segundotesoureiro'])):
                                    ?>
                                    <div class="row content-grid-2">
                                        <div class="grid-item horizon" data-animate-in="preset:slideInUpShort;duration:1000ms;" data-threshold="0.3">
                                            <h4 class="color-grey mb-5"><?php $OLidArray = explode(' ', $dRede['segundotesoureiro']); echo $OLidArray[0].' '.$OLidArray[1];?></h4>
                                            <?php
                                                $matriculasegundotesoureiro=$dRede['matriculasegundotesoureiro'];
                                                //Buscar dados do líder informado
                                                $bL="SELECT ministerio, fotodeperfil, descricaodomembro, midiasocial1, link1, midiasocial2, link2, midiasocial3, link3, midiasocial4, link4 FROM membros WHERE matricula='$matriculasegundotesoureiro'";
                                                $rL=mysqli_query($con, $bL);
                                                    $dL=mysqli_fetch_array($rL);
                                            ?>
                                            <h4 class="occupation"><?php echo $dL['ministerio'];?></h4>
                                            <div class="thumbnail no-margin-bottom" data-hover-easing="easeInOut" data-hover-speed="500" data-hover-bkg-color="#ffffff" data-hover-bkg-opacity="0.9">
                                                <img src="<?php echo $dL['fotodeperfil'];?>" style="width:330px; height:500px;" width="760" height="500" alt="<?php echo $dRede['lider'];?>"/>
                                            </div>
                                            <div class="team-content-info">
                                                <p>
                                                    <?php echo $dL['descricaodomembro'];?>
                                                </p>
                                                <ul class="social-list list-horizontal">
                                                    <li><a href="<?php $link1 = base64_decode($dL['link1']); echo base64_decode($link1);?>"><span class="icon-<?php echo $dL['midiasocial1'];?>-with-circle medium"></span></a></li>
                                                    <li><a href="<?php $link2 = base64_decode($dL['link2']); echo base64_decode($link2);?>"><span class="icon-<?php echo $dL['midiasocial2'];?>-with-circle medium"></span></a></li>
                                                    <li><a href="<?php $linkl = base64_decode($dL['linkl']); echo base64_decode($linkl);?>"><span class="icon-<?php echo $dL['midiasocial3'];?>-with-circle medium"></span></a></li>
                                                </ul>
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