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
                                                        <h1 class="mb-0">Editar <strong>relatório de culto</strong></h1>
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
                                                    <th></th>
                                                <th>DATA</th>
                                                <th>CÓDIGO</th>
                                                <th>PREGADOR</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                        <?php
                                            // <!--CÓDIGO PARA FAZER PESQUISA DOS HORÁRIOS E CRIAR O LOOPING -->
                                            $pagina = (isset($_GET['pagina']))? $_GET['pagina'] : 1;

                                                $buscarporconceitos= "SELECT id FROM relatoriodeculto WHERE NOT (relator = '')  ORDER BY id DESC";
                                                    $resultadobuscarporconceitos = mysqli_query($con, $buscarporconceitos);
                                                    
                                                    $totaldeconceitos = mysqli_num_rows($resultadobuscarporconceitos);

                                                    //Defini o número de horarios que serão exibidos por página
                                                    $conceitosporpagina = 30;

                                                    //defini número de páginas necessárias para exibir todos os horarios.
                                                    $totaldepaginas = ceil($totaldeconceitos / $conceitosporpagina);

                                                    $inicio = ($conceitosporpagina * $pagina) - $conceitosporpagina;

                                            
                                                //AQUI ELE FAZ A PESQUISA PELO CAMPUS E TRAZ O DADO MAIS RECENTE PELO ANO, SE ESTAMOS EM 2010 ELE TRARÁ OS DADOS DESTE ANO OU DO ÚLTIMO ANO EM QUE O CAMPUS APRESENTOU UMA MATÉRIA REGISTRADA, COM BASE NISSO É FEITO UM FILTRO EXIBINDO POR ORDEM O DIA E DEPOIS O HORÁRIO.
                                                $conceitosparaeditar= "SELECT id, datadoculto, codigodorelatorio, pregador FROM relatoriodeculto WHERE NOT (relator = '') ORDER BY datadoculto DESC LIMIT $inicio, $conceitosporpagina";
                                                    $resultadoconceitosparaeditar = mysqli_query($con   , $conceitosparaeditar);
                                                            $linha=0;
                                                        while($dadosconceitosparaeditar = mysqli_fetch_array($resultadoconceitosparaeditar)):
                                                            $linha++;
                                        ?>
                                            <tr >
                                                <!-- O CÓDIGO ABAIXO VERIFICA SE O DIA CORRESPONDE COM A SEGUNDA-FEIRA, PARA COLUNA E TRÁS AS MATÉRIAS PARA DESTES DIAS -->
                                                <td class="color-black" width="3%" valign="medium">
                                                        <?php echo $linha;?> 
                                                </td>
                                                <td class="color-black" width="42%" valign="medium">
                                                        <?php echo $dadosconceitosparaeditar['datadoculto'];?> 
                                                </td>
                                                <td class="color-black" width="15%" valign="medium">
                                                        <?php echo $dadosconceitosparaeditar['codigodorelatorio'];?> 
                                                </td>
                                                <td class="color-black" width="30%" valign="medium">
                                                        <?php echo $dadosconceitosparaeditar['pregador'];?> 
                                                </td>
                                                <td width="20%" valign="medium">
                                                    <center>
                                                        <a href="cultorelatorioedit?m=<?php echo $matricula;?>&id=<?php echo $dadosconceitosparaeditar['id'];?>&token=<?php echo $token;?>" class="icon-pencil color-black color-charcoal center small thick">
                                                            Editar
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
				<div class="section-block pagination-3 bkg-grey-ultralight pt-30">
					<div class="row">
                        <div class="box rounded bkg-white shadow small">
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