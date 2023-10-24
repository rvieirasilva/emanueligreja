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


    if(isset($_GET['exc'])):
        $iddopostexcluir=$_GET['id'];
        $refparaexc=$_GET['ref'];
    
        $bconteudodopost1 = "SELECT audio, video, anexo, capa FROM blog WHERE referencia='$refparaexc'";
          $rc1=mysqli_query($con, $bconteudodopost1);
            $dc1=mysqli_fetch_array($rc1);

        unlink($dc1['audio']);
        unlink($dc1['video']);
        unlink($dc1['anexo']);
        unlink($dc1['capa']);
            
        $bconteudodopost2 = "SELECT miniatura FROM blog WHERE referencia='$refparaexc'";
          $rc2=mysqli_query($con, $bconteudodopost2);
            while($dc2=mysqli_fetch_array($rc2)):
                unlink($dc1['miniatura']);
            endwhile;
            
        $excluir="DELETE FROM blog WHERE referencia='$refparaexc'";
        if(mysqli_query($con, $excluir)):
            $_SESSION['mensagem']="Post excluido.";
            //Dados para registrar o log do cliente.
            $mensagem = ("\n$nomedocolaborador ($matricula) Excluíu o texto de referência $refparaexc. Isto através do IP: $ip, na data $dia às $hora.\n"); //Escreva a mensagem que será gravada no log.
                include "./registrolog.php";
            //Fim do registro do log.
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
                                                        <h1 class="mb-0">Editar <strong>conteúdo para o blog</strong></h1>
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
                                    <table class="table striped non-responsive rounded medium full-width">
                                        <thead>
                                        <tr class="bkg-charcoal color-white">
                                            <th>TITULO</th>
                                            <th class="center">DATA</th>
                                            <th class="center">CATEGORIA</th>
                                            <th></th>
                                            <th></th>
                                        </tr>
                                        </thead>
                                        <tbody>

                                        <?php
                                            // <!--CÓDIGO PARA FAZER PESQUISA DOS HORÁRIOS E CRIAR O LOOPING -->
                                            $pagina = (isset($_GET['pagina']))? $_GET['pagina'] : 1;

                                                $buscarporposts= "SELECT id FROM blog WHERE autor= '$nomedocolaborador' ORDER BY id DESC";
                                                    $resultadobuscarporposts = mysqli_query($con, $buscarporposts);
                                                    
                                                    $totaldeposts = mysqli_num_rows($resultadobuscarporposts);

                                                    //Defini o número de horarios que serão exibidos por página
                                                    $postsporpagina = 30;

                                                    //defini número de páginas necessárias para exibir todos os horarios.
                                                    $totaldepaginas = ceil($totaldeposts / $postsporpagina);

                                                    $inicio = ($postsporpagina * $pagina) - $postsporpagina;

                                            
                                                //AQUI ELE FAZ A PESQUISA PELO CAMPUS E TRAZ O DADO MAIS RECENTE PELO ANO, SE ESTAMOS EM 2010 ELE TRARÁ OS DADOS DESTE ANO OU DO ÚLTIMO ANO EM QUE O CAMPUS APRESENTOU UMA MATÉRIA REGISTRADA, COM BASE NISSO É FEITO UM FILTRO EXIBINDO POR ORDEM O DIA E DEPOIS O HORÁRIO.
                                                //$postsparaeditar= "SELECT * FROM blog WHERE autor= '$nomedocolaborador' ORDER BY datadapostagem DESC LIMIT $inicio, $postsporpagina";
                                                $postsparaeditar= "SELECT * FROM blog group BY referencia ORDER BY datadapostagem DESC LIMIT $inicio, $postsporpagina";
                                                    $resultadopostsparaeditar = mysqli_query($con   , $postsparaeditar);
                                                        while($dadospostsparaeditar = mysqli_fetch_array($resultadopostsparaeditar)):
                                                        
                                                
                                                
                                        ?>
                                            <tr class="">
                                                <!-- O CÓDIGO ABAIXO VERIFICA SE O DIA CORRESPONDE COM A SEGUNDA-FEIRA, PARA COLUNA E TRÁS AS MATÉRIAS PARA DESTES DIAS -->
                                                <td class="color-black text-small" width="60%" valign="medium">
                                                    <?php echo $dadospostsparaeditar['titulo'];?> 
                                                </td>
                                                <td class="color-black text-small center" width="13%" valign="medium">
                                                    <?php $datadapostagem=$dadospostsparaeditar['datadapostagem']; echo date('d/M/Y', strtotime($datadapostagem));?> 
                                                </td>
                                                <td class="color-black text-small center" width="12%" valign="medium">
                                                    <?php echo $dadospostsparaeditar['categoria'];?> 
                                                </td>
                                                <td width="10%" valign="medium">
                                                    <center><a href="blogeditar?m=<?php echo $matricula;?>&exc&id=<?php echo $dadospostsparaeditar['id'];?>&<?php echo uniqid();?>&token=<?php echo $token;?>&ref=<?php echo $dadospostsparaeditar['referencia'];?>&titulo=<?php echo $dadospostsparaeditar['titulo'];?>" class="icon-pencil color-blue-light center small thick"> Editar</a></center>
                                                </td>
                                                <td width="5%" valign="medium">
                                                    <center>
                                                    <a href="bloglistar?m=<?php echo $matricula;?>&exc&id=<?php echo $dadospostsparaeditar['id'];?>&token=<?php echo $token;?>&ref=<?php echo $dadospostsparaeditar['referencia'];?>&titulo=<?php echo $dadospostsparaeditar['titulo'];?>"><span class="color-black">Excluir</span></a>
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
				<div class="section-block pagination-3 bkg-grey-ultralight pt-30">
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