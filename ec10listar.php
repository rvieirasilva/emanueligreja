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

    // if(isset($_GET['exc'])):
    //     $iddopostexcluir=$_GET['id'];
    //     $refparaexc=$_GET['ref'];
    
    //     $excluir="DELETE FROM conceitos WHERE id='$iddopostexcluir' AND referencia='$refparaexc'";
    //      if(mysqli_query($con, $excluir)):
    //         $_SESSION['mensagem']="Conceito excluido.";
    //             //Dados para registrar o log do cliente.
    //                 $mensagem = ("\n$nomedousuario ($matricula) Excluíu o conceito $refparaexc. Isto através do IP: $ip, na data $dia às $hora.\n"); //Escreva a mensagem que será gravada no log.
    //                 include "./registrolog.php";
    //             //Fim do registro do log.
    //      endif;
    // endif;
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
                                                        <h1 class="mb-0">Projeto <strong>Eclesiastes 10.10</strong></h1>
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
                                <div class="column width-3">
                                    <div class="column width-12">
                                        <span class="progress-bar-label pull-left text-medium color-red">Ruim (< 30%)</span>
                                        <span class="progress-bar-label pull-right text-medium color-green">Excelente (> 70%)</span>
                                    </div>
                                        <?php
                                            $bnps="SELECT count(nps) as xNPS, sum(nps) as nps FROM ec10";
                                              $rnps=mysqli_query($con, $bnps);
                                                $dnps=mysqli_fetch_array($rnps);

                                            $totaldenps=$dnps['xNPS'];
                                            $somadenps=$dnps['nps'];

                                            @$vbr = $somadenps / $totaldenps; //Valor Barra
                                            $vbr=$vbr*100;
                                            @$vbr = number_format($vbr, 0, ',', '.');
                                            if($vbr >= 100): //vmb Demanda do Produto
                                                $vbr = 100;
                                                $cornps='green';
                                            elseif($vbr > 1 AND $vbr < 10):
                                                $vbr = 10;
                                                $cornps='red';
                                            elseif($vbr > 10 AND $vbr < 20):
                                                $vbr = 20;
                                                $cornps='red';
                                            elseif($vbr > 20 AND $vbr < 30):
                                                $vbr = 30;
                                                $cornps='red';
                                            elseif($vbr > 30 AND $vbr < 40):
                                                $vbr = 40;
                                                $cornps='orange';
                                            elseif($vbr > 40 AND $vbr < 50):
                                                $vbr = 50;
                                                $cornps='orange';
                                            elseif($vbr > 50 AND $vbr < 60):
                                                $vbr = 60;
                                                $cornps='orange';
                                            elseif($vbr > 60 AND $vbr < 70):
                                                $vbr = 70;
                                                $cornps='green';
                                            elseif($vbr > 70 AND $vbr < 80):
                                                $vbr = 80;
                                                $cornps='green';
                                            elseif($vbr > 80 AND $vbr < 100):
                                                $vbr = 90;
                                                $cornps='green';
                                            elseif($vbr >= 98):
                                                $vbr = 100;
                                                $cornps='green';
                                                //$filtro = array('05' => '10', '15' => '20','25' => '30','35' => '40','45' => '50','55' => '60','65' => '70','75' => '80','85' => '90','95' => '100');
                                                //$vbr = strTr($vbr, $filtro);
                                            elseif($vbr = 0 OR $vbr = ''):
                                                $vbr=0;
                                            endif;
                                        ?>
                                    <div class="progress-bar pill small pt-0 pb-0 bkg-grey-ultralight">
                                        <div class="bar bkg-<?echo $cornps;?> color-white percent-<?php echo $vbr; ?> horizon"
                                            data-animate-in="transX:-100%;duration:1000ms;easing:easeIn;">
                                        </div>
                                    </div>
                                        <h5 class="title-small weight-bold">NPS - Evangelismo <?php echo $vbr; ?>%</h5>
                                </div>
                                <div class="column width-3">
                                    <div class="column width-12">
                                        <span class="progress-bar-label pull-left text-medium color-red">Ruim (< 30%)</span>
                                        <span class="progress-bar-label pull-right text-medium color-green">Excelente (> 70%)</span>
                                    </div>
                                        <?php
                                            $bMusicaBoa="SELECT count(musica) as musicaboa FROM ec10 WHERE musica='Boa'";
                                              $rMB=mysqli_query($con, $bMusicaBoa);
                                                $dMB=mysqli_fetch_array($rMB);
                                                
                                            $bMusicaRuim="SELECT count(musica) as musicaRuim FROM ec10 WHERE musica='Ruim'";
                                              $rMR=mysqli_query($con, $bMusicaRuim);
                                                $dMR=mysqli_fetch_array($rMR);


                                            $MusicasBoas=$dMB['musicaboa'];
                                            $MusicasRuins=$dMR['musicaRuim'];

                                            @$vMusic = $MusicaBoas / $MusicasRuins; //Valor Barra
                                            $vMusic=$vMusic*100;
                                            @$vMusic = number_format($vMusic, 0, ',', '.');
                                            if($vMusic >= 100): //vmb Demanda do Produto
                                                $vMusic = 100;
                                                $CorMusic='green';
                                            elseif($vMusic > 1 AND $vMusic < 10):
                                                $vMusic = 10;
                                                $CorMusic='red';
                                            elseif($vMusic > 10 AND $vMusic < 20):
                                                $vMusic = 20;
                                                $CorMusic='red';
                                            elseif($vMusic > 20 AND $vMusic < 30):
                                                $vMusic = 30;
                                                $CorMusic='red';
                                            elseif($vMusic > 30 AND $vMusic < 40):
                                                $vMusic = 40;
                                                $CorMusic='orange';
                                            elseif($vMusic > 40 AND $vMusic < 50):
                                                $vMusic = 50;
                                                $CorMusic='orange';
                                            elseif($vMusic > 50 AND $vMusic < 60):
                                                $vMusic = 60;
                                                $CorMusic='orange';
                                            elseif($vMusic > 60 AND $vMusic < 70):
                                                $vMusic = 70;
                                                $CorMusic='green';
                                            elseif($vMusic > 70 AND $vMusic < 80):
                                                $vMusic = 80;
                                                $CorMusic='green';
                                            elseif($vMusic > 80 AND $vMusic < 100):
                                                $vMusic = 90;
                                                $CorMusic='green';
                                            elseif($vMusic >= 98):
                                                $vMusic = 100;
                                                $CorMusic='green';
                                                //$filtro = array('05' => '10', '15' => '20','25' => '30','35' => '40','45' => '50','55' => '60','65' => '70','75' => '80','85' => '90','95' => '100');
                                                //$vMusic = strTr($vMusic, $filtro);
                                            elseif($vMusic = 0 OR $vMusic = ''):
                                                $vMusic=0;
                                            endif;
                                        ?>
                                    <div class="progress-bar pill small pt-0 pb-0 bkg-grey-ultralight">
                                        <div class="bar bkg-<?echo $CorMusic;?> color-white percent-<?php echo $vMusic; ?> horizon"
                                            data-animate-in="transX:-100%;duration:1000ms;easing:easeIn;">
                                        </div>
                                    </div>
                                    <h5 class="title-small weight-bold">NPS - Música <?php echo $vMusic; ?>%</h5>
                                </div>
                                <div class="column width-3">
                                    <div class="column width-12">
                                        <span class="progress-bar-label pull-left text-medium color-red">Ruim (< 30%)</span>
                                        <span class="progress-bar-label pull-right text-medium color-green">Excelente (> 70%)</span>
                                    </div>
                                        <?php
                                            $bRecepcaoBoa="SELECT count(recepcao) as recepcaoboa FROM ec10 WHERE recepcao='Boa'";
                                              $rRB=mysqli_query($con, $bRecepcaoBoa);
                                                $dRB=mysqli_fetch_array($rRB);
                                                
                                            $bRecepcaoRuim="SELECT count(recepcao) as recepcaoRuim FROM ec10 WHERE recepcao='Ruim'";
                                              $rRR=mysqli_query($con, $bRecepcaoRuim);
                                                $dRR=mysqli_fetch_array($rRR);


                                            $RecepcaoBoa=$dRB['recepcaoboa'];
                                            $RecepcaoRuin=$dRR['recepcaoRuim'];

                                            @$vRecep = $RecepcaoBoa / $RecepcaoRuin; //Valor Barra
                                            $vRecep=$vRecep*100;
                                            @$vRecep = number_format($vRecep, 0, ',', '.');
                                            if($vRecep >= 100): //vmb Demanda do Produto
                                                $vRecep = 100;
                                                $corRecep='green';
                                            elseif($vRecep > 1 AND $vRecep < 10):
                                                $vRecep = 10;
                                                $corRecep='red';
                                            elseif($vRecep > 10 AND $vRecep < 20):
                                                $vRecep = 20;
                                                $corRecep='red';
                                            elseif($vRecep > 20 AND $vRecep < 30):
                                                $vRecep = 30;
                                                $corRecep='red';
                                            elseif($vRecep > 30 AND $vRecep < 40):
                                                $vRecep = 40;
                                                $corRecep='orange';
                                            elseif($vRecep > 40 AND $vRecep < 50):
                                                $vRecep = 50;
                                                $corRecep='orange';
                                            elseif($vRecep > 50 AND $vRecep < 60):
                                                $vRecep = 60;
                                                $corRecep='orange';
                                            elseif($vRecep > 60 AND $vRecep < 70):
                                                $vRecep = 70;
                                                $corRecep='green';
                                            elseif($vRecep > 70 AND $vRecep < 80):
                                                $vRecep = 80;
                                                $corRecep='green';
                                            elseif($vRecep > 80 AND $vRecep < 100):
                                                $vRecep = 90;
                                                $corRecep='green';
                                            elseif($vRecep >= 98):
                                                $vRecep = 100;
                                                $corRecep='green';
                                                //$filtro = array('05' => '10', '15' => '20','25' => '30','35' => '40','45' => '50','55' => '60','65' => '70','75' => '80','85' => '90','95' => '100');
                                                //$vRecep = strTr($vRecep, $filtro);
                                            elseif($vRecep = 0 OR $vRecep = ''):
                                                $vRecep=0;
                                            endif;
                                        ?>
                                    <div class="progress-bar pill small pt-0 pb-0 bkg-grey-ultralight">
                                        <div class="bar bkg-<?echo $corRecep;?> color-white percent-<?php echo $vRecep; ?> horizon"
                                            data-animate-in="transX:-100%;duration:1000ms;easing:easeIn;">
                                        </div>
                                    </div>
                                    <h5 class="title-small weight-bold">NPS - Recepção <?php echo $vRecep; ?>%</h5>
                                </div>
                                <div class="column width-3">
                                    <div class="column width-12">
                                        <span class="progress-bar-label pull-left text-medium color-red">Ruim (< 30%)</span>
                                        <span class="progress-bar-label pull-right text-medium color-green">Excelente (> 70%)</span>
                                    </div>
                                        <?php
                                            $bLimpezaBoa="SELECT count(limpeza) as limpezaboa FROM ec10 WHERE limpeza='Boa'";
                                              $rLB=mysqli_query($con, $bLimpezaBoa);
                                                $dLB=mysqli_fetch_array($rLB);
                                                
                                            $bLimpezaRuim="SELECT count(limpeza) as limpezaRuim FROM ec10 WHERE limpeza='Ruim'";
                                              $rLR=mysqli_query($con, $bLimpezaRuim);
                                                $dLR=mysqli_fetch_array($rLR);


                                            $LimpezaBoa=$dLB['limpezaboa'];
                                            $LimpezaRuin=$dLR['limpezaRuim'];

                                            @$vLimpeza = $LimpezaBoa / $LimpezaRuin; //Valor Barra
                                            $vLimpeza=$vLimpeza*100;
                                            @$vLimpeza = number_format($vLimpeza, 0, ',', '.');
                                            if($vLimpeza >= 100): //vmb Demanda do Produto
                                                $vLimpeza = 100;
                                                $corLimpeza='green';
                                            elseif($vLimpeza > 1 AND $vLimpeza < 10):
                                                $vLimpeza = 10;
                                                $corLimpeza='red';
                                            elseif($vLimpeza > 10 AND $vLimpeza < 20):
                                                $vLimpeza = 20;
                                                $corLimpeza='red';
                                            elseif($vLimpeza > 20 AND $vLimpeza < 30):
                                                $vLimpeza = 30;
                                                $corLimpeza='red';
                                            elseif($vLimpeza > 30 AND $vLimpeza < 40):
                                                $vLimpeza = 40;
                                                $corLimpeza='orange';
                                            elseif($vLimpeza > 40 AND $vLimpeza < 50):
                                                $vLimpeza = 50;
                                                $corLimpeza='orange';
                                            elseif($vLimpeza > 50 AND $vLimpeza < 60):
                                                $vLimpeza = 60;
                                                $corLimpeza='orange';
                                            elseif($vLimpeza > 60 AND $vLimpeza < 70):
                                                $vLimpeza = 70;
                                                $corLimpeza='green';
                                            elseif($vLimpeza > 70 AND $vLimpeza < 80):
                                                $vLimpeza = 80;
                                                $corLimpeza='green';
                                            elseif($vLimpeza > 80 AND $vLimpeza < 100):
                                                $vLimpeza = 90;
                                                $corLimpeza='green';
                                            elseif($vLimpeza >= 98):
                                                $vLimpeza = 100;
                                                $corLimpeza='green';
                                                //$filtro = array('05' => '10', '15' => '20','25' => '30','35' => '40','45' => '50','55' => '60','65' => '70','75' => '80','85' => '90','95' => '100');
                                                //$vLimpeza = strTr($vLimpeza, $filtro);
                                            elseif($vLimpeza = 0 OR $vLimpeza = ''):
                                                $vLimpeza=0;
                                            endif;
                                        ?>
                                    <div class="progress-bar pill small pt-0 pb-0 bkg-grey-ultralight">
                                        <div class="bar bkg-<?echo $corLimpeza;?> color-white percent-<?php echo $vLimpeza; ?> horizon"
                                            data-animate-in="transX:-100%;duration:1000ms;easing:easeIn;">
                                        </div>
                                    </div>
                                    <h5 class="title-small weight-bold">NPS - Limpeza <?php echo $vLimpeza; ?>%</h5>
                                </div>
                            </div>
                            <div class="box rounded rounded shadow border-blue bkg-white">
                                <div class="column width-12">
                                        <table class="table striped non-responsive rounded medium full-width">
                                            <thead>
                                                <tr class="bkg-charcoal color-white">
                                                        <th></th>
                                                    <th>Data</th>
                                                    <th>Motivo</th>
                                                    <th></th>
                                                </tr>
                                            </thead>
                                            <tbody>

                                            <?php
                                                // <!--CÓDIGO PARA FAZER PESQUISA DOS HORÁRIOS E CRIAR O LOOPING -->
                                                $pagina = (isset($_GET['pagina']))? $_GET['pagina'] : 1;

                                                    $buscarporconceitos= "SELECT * FROM ec10 WHERE NOT (descricao = '') ORDER BY id DESC";
                                                        $resultadobuscarporconceitos = mysqli_query($con, $buscarporconceitos);
                                                        
                                                        $totaldeconceitos = mysqli_num_rows($resultadobuscarporconceitos);

                                                        //Defini o número de horarios que serão exibidos por página
                                                        $conceitosporpagina = 30;

                                                        //defini número de páginas necessárias para exibir todos os horarios.
                                                        $totaldepaginas = ceil($totaldeconceitos / $conceitosporpagina);

                                                        $inicio = ($conceitosporpagina * $pagina) - $conceitosporpagina;

                                                
                                                    //AQUI ELE FAZ A PESQUISA PELO CAMPUS E TRAZ O DADO MAIS RECENTE PELO ANO, SE ESTAMOS EM 2010 ELE TRARÁ OS DADOS DESTE ANO OU DO ÚLTIMO ANO EM QUE O CAMPUS APRESENTOU UMA MATÉRIA REGISTRADA, COM BASE NISSO É FEITO UM FILTRO EXIBINDO POR ORDEM O DIA E DEPOIS O HORÁRIO.
                                                    $conceitosparaeditar= "SELECT * FROM ec10 WHERE NOT (descricao = '') ORDER BY id DESC LIMIT $inicio, $conceitosporpagina";
                                                        $resultadoconceitosparaeditar = mysqli_query($con   , $conceitosparaeditar);
                                                                $linha=0;
                                                            while($ec10 = mysqli_fetch_array($resultadoconceitosparaeditar)):
                                                                $linha++;
                                            ?>
                                                <tr >
                                                    <!-- O CÓDIGO ABAIXO VERIFICA SE O DIA CORRESPONDE COM A SEGUNDA-FEIRA, PARA COLUNA E TRÁS AS MATÉRIAS PARA DESTES DIAS -->
                                                    <td class="color-black" width="3%" valign="medium">
                                                        <?php echo $linha;?> 
                                                    </td>
                                                    <td class="color-black" width="56%" valign="medium">
                                                        <?php echo $ec10['dia'].'/'.$ec10['mes'].'/'.$ec10['ano'];?> 
                                                    </td>
                                                    <td class="color-black" width="12%" valign="medium">
                                                        <?php echo $ec10['motivo'];?> 
                                                    </td>
                                                    <td width="10%" valign="medium">
                                                        <center>
                                                            <a href="ec10ver?m=<?php echo $matricula;?>&exc&id=<?php echo $ec10['id'];?>&<?php echo uniqid();?>&token=<?php echo $token;?>" class="icon-pencil color-black color-charcoal center small thick">
                                                            Abrir
                                                            </a>
                                                        </center>
                                                    </td>
                                                </tr>
                                                <?php endwhile; ?>
                                            </tbody>
                                        </table>
                                        
                                        <div class="row">
                                            <div class="column width-12">
                                                <button type="button" onclick="javascript:window.print();" class="form-submit pill button small bkg-blue-light bkg-hover-blue color-white color-hover-white">IMPRIMIR</button>
                                            </div>
                                        </div>
                                
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Content End -->
                <div class="printable"></div>
                
                <!-- Alterar capa Modal End -->
				<div id="editarcapadopost" class="section-block pt-0 pb-30 background-none hide">
					
					<!-- Intro Title Section 2 -->
					<div class="thumbnail xsmall">
						<img src="<?php echo $dte['capa'];?>" width="825" height="400" alt="">
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

				<!-- Alterar miniatura Modal End -->
				<div id="editarminiaturadopost" class="section-block pt-0 pb-30 background-none hide">
					
					<!-- Intro Title Section 2 -->
					<div class="thumbnail xsmall">
						<img src="<?php echo $dte['miniatura'];?>" width="825" height="400" alt="">
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
                
				<!-- Pagination Section 3 -->
				<div class="section-block pagination-3 bkg-white pt-30">
					<div class="row">
                        <div class="box rounded bkg-white small">
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

		</div>
	</div>
    
	<? include "./_script.php"; ?>
    
</body>
</html>