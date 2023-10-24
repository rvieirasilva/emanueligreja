<!DOCTYPE html>
<?php
	session_start();
	if(!empty($_GET['lng'])):
		$idioma = $_GET['lng'];
	elseif(empty($_GET['lng']) OR !isset($_GET['lng'])):
		$idioma = 'pt-BR';
    endif;
    
    include "./_con.php";
    // include "./configDoacoes.php";
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
                                                        <h1 class="mb-0"><strong>Comprovante</strong> de oferta</h1>
                                                        <p class="text-large color-charcoal mb-0 mb-mobile-20">
                                                            "Oferta não tem valor, tem significado". (Pr Rafael Vieira), Não se trata do valor e sim do quanto significa para você aquilo que você está contribuindo.</p>
                                                    </div>
                                                </div>
                                                <div class="column width-3 v-align-middle">
                                                    <div>
                                                        <ul class="breadcrumb inline-block mb-0 pull-right clear-float-on-mobile">
                                                            <li>
                                                                <a href="index">Inicio</a>
                                                            </li>
                                                            <li>
                                                                Oferta
                                                            </li>
                                                        </ul>
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
                            <!-- <div class="box rounded rounded shadow border-blue bkg-white"> -->
                                <div class="column width-12 pt-30">
                                    <div class="column width-6 offset-3">
                                        <div class="box large rounded shadow bkg-white border-blue">
                                            <h2 class="left">
                                                Comprovante de <?php echo $_SESSION['comprovantetipodecontribuicao'];?>
                                            </h2>
                                            <label><?php echo $_SESSION['comprovantedatadaoferta'];?></label>
                                            <label class="text-medium">
                                                <?php
                                                    $contribuicao = $_SESSION['comprovantevalordacontribuicao'];
                                                    if($contribuicao >= 1000):
                                                        $contribuicao = explode('.', $contribuicao); // 1 999,35
                                                        $contribuicao = $contribuicao[0].$contribuicao[1]; //1999,35
                                                        $contribuicao = str_replace(',','.', $contribuicao); // 1999,35 >> 1999.35
                                                    elseif($contribuicao < 1000):
                                                        $contribuicao = str_replace(',','.', $contribuicao); // 1999,35 >> 1999.35
                                                    endif;
                                                ?>
                                                VALOR: R$ <?php echo $contribuicao;?>
                                            </label>
                                            <label class="text-medium">NOME: <?php $nomeoferta_enc = $_SESSION['comprovantenomedoofertante']; $nomeoferta_enc = base64_decode($nomeoferta_enc); echo base64_decode($nomeoferta_enc);?></label>

                                            <label class="text-large weight-bold text-uppercase color-blue">
                                                Igreja Batista Emanuel.
                                            </label>
                                        </div>
                                        <span>Se desejar, tire print desta tela e envie o comprovante para nosso e-mail: </span>
                                        <a href="mailto:financas@igrejaemanuel.com.br" target="_blank">
                                            Enviar.
                                        </a>
                                    </div>
                                </div>
                            <!-- </div> -->
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