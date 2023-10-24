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
    
        $bInscr="SELECT nome, email, evento FROM agendainscritos WHERE codigodoevento='$refparaexc'";
            $rIns=mysqli_query($con, $bInscr);
            while($dIn=mysqli_fetch_array($rIns)):
                $inscrito=$dIn['nome'];
                $emailinscrito=$dIn['email'];
                $evento=$dIn['evento'];
        
                //Email teste
                include "./_enviaremailPay.php";
                $mail->addAddress("$emailinscrito", "$inscrito");
                $mail->Subject = "$evento, CANCELADO. Emanuel";
                $mail->Body    = "Olá $inscrito. 
                                <p>O evento: <strong>$evento</strong> foi cancelado. Caso este evento tenha sido pago, nossa equipe entrará em contato para realizar o estorno, <strong>mas você pode fazer isto de imediato e iremos instruir como será realizado e quando. Obrigado por ter se inscrito e nos perdoe por este contratempo.</strong>. Aaa, nossa equipe irá informar a razão do cancelamento.</p>
                                <p<h1>Jesus Cristo é o Senhor!</p>
                                ";
                $mail->AltBody = "Olá $inscrito. 
                                <p>O evento: <strong>$evento</strong> foi cancelado. Caso este evento tenha sido pago, nossa equipe entrará em contato para realizar o estorno, <strong>mas você pode fazer isto de imediato e iremos instruir como será realizado e quando. Obrigado por ter se inscrito e nos perdoe por este contratempo.</strong>. Aaa, nossa equipe irá informar a razão do cancelamento.</p>
                                <p<h1>Jesus Cristo é o Senhor!</p>"; // Não é visualizado pelo usuário!
                $mail->send();
            endwhile;
            
        $excluir="DELETE FROM agenda WHERE id='$iddopostexcluir' AND codigodoevento='$refparaexc'";
         if(mysqli_query($con, $excluir)):
            $_SESSION['mensagem']="Post excluido.";
            //Dados para registrar o log do cliente.
            $mensagem = ("\n$nomedousuario ($matricula) Excluíu o evento de código $refparaexc. Isto através do IP: $ip, na data $dia às $hora.\n"); //Escreva a mensagem que será gravada no log.
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
                                                        <h1 class="mb-0">Editar <strong>Evento</strong></h1>
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
                                            <th>EVENTO</th>
                                            <th class="center">DATA</th>
                                            <th class="center">VALOR</th>
                                            <th></th>
                                            <th></th>
                                        </tr>
                                        </thead>
                                        <tbody>

                                        <?php
                                            // <!--CÓDIGO PARA FAZER PESQUISA DOS HORÁRIOS E CRIAR O LOOPING -->
                                            $pagina = (isset($_GET['pagina']))? $_GET['pagina'] : 1;

                                                $buscarporposts= "SELECT id FROM agenda WHERE ano='$ano' ORDER BY id DESC";
                                                    $resultadobuscarporposts = mysqli_query($con, $buscarporposts);
                                                    
                                                    $totaldeposts = mysqli_num_rows($resultadobuscarporposts);

                                                    //Defini o número de horarios que serão exibidos por página
                                                    $postsporpagina = 30;

                                                    //defini número de páginas necessárias para exibir todos os horarios.
                                                    $totaldepaginas = ceil($totaldeposts / $postsporpagina);

                                                    $inicio = ($postsporpagina * $pagina) - $postsporpagina;

                                            
                                                //AQUI ELE FAZ A PESQUISA PELO CAMPUS E TRAZ O DADO MAIS RECENTE PELO ANO, SE ESTAMOS EM 2010 ELE TRARÁ OS DADOS DESTE ANO OU DO ÚLTIMO ANO EM QUE O CAMPUS APRESENTOU UMA MATÉRIA REGISTRADA, COM BASE NISSO É FEITO UM FILTRO EXIBINDO POR ORDEM O DIA E DEPOIS O HORÁRIO.
                                                //$postsparaeditar= "SELECT * FROM agenda WHERE autor= '$nomedocolaborador' ORDER BY datadapostagem DESC LIMIT $inicio, $postsporpagina";
                                                $postsparaeditar= "SELECT id, evento, datadoinicio, valor, codigodoevento FROM agenda WHERE ano='$ano' group BY codigodoevento ORDER BY datadoinicio ASC LIMIT $inicio, $postsporpagina";
                                                    $resultadopostsparaeditar = mysqli_query($con   , $postsparaeditar);
                                                        while($dadospostsparaeditar = mysqli_fetch_array($resultadopostsparaeditar)):
                                                        
                                                
                                                
                                        ?>
                                            <tr class="">
                                                <!-- O CÓDIGO ABAIXO VERIFICA SE O DIA CORRESPONDE COM A SEGUNDA-FEIRA, PARA COLUNA E TRÁS AS MATÉRIAS PARA DESTES DIAS -->
                                                <td class="color-black text-xsmall" width="60%" valign="medium">
                                                    <?php echo $dadospostsparaeditar['evento'];?> 
                                                </td>
                                                <td class="color-black text-xsmall center" width="13%" valign="medium">
                                                    <?php $datadapostagem=$dadospostsparaeditar['datadoinicio']; echo date('d/M/Y', strtotime($datadapostagem));?> 
                                                </td>
                                                <td class="color-black text-xsmall center" width="12%" valign="medium">
                                                    R$ <?php echo $dadospostsparaeditar['valor'];?> 
                                                </td>
                                                <td width="10%" valign="medium">
                                                    <center><a href="agendaeditar?m=<?php echo $matricula;?>&id=<?php echo $dadospostsparaeditar['id'];?>&<?php echo uniqid();?>&token=<?php echo $token;?>&ref=<?php echo $dadospostsparaeditar['codigodoevento'];?>&evento=<?php echo $dadospostsparaeditar['evento'];?>" class="icon-pencil color-blue-light center small thick"> Editar</a></center>
                                                </td>
                                                <td width="5%" valign="medium">
                                                    <center>
                                                    <a href="agendalistar?m=<?php echo $matricula;?>&exc&id=<?php echo $dadospostsparaeditar['id'];?>&token=<?php echo $token;?>&ref=<?php echo $dadospostsparaeditar['codigodoevento'];?>&evento=<?php echo $dadospostsparaeditar['evento'];?>"><span class="color-black">Excluir</span></a>
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

                    <!-- Search Modal End -->
                    <div id="search-modal" class="hide">
                        <div class="row">
                            <div class="column width-12 center">
                                <div class="search-form-container site-search">
                                    <form action="#" method="get" novalidate>
                                        <div class="row">
                                            <div class="column width-12">
                                                <div class="field-wrapper">
                                                    <input type="text" name="search" class="form-search form-element" placeholder="type &amp; hit enter...">
                                                    <span class="border"></span>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                    <div class="form-response"></div>
                                </div>
                                <a href="#" class="tml-aux-exit">FECHAR PESQUISA</a>
                            </div>
                        </div>
                    </div>
                    <!-- Search Modal End -->
                    
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
                            <div class="column width-12">
                                <?php
                                    include "./_paginacao.php";
                                ?>
                            </div>
                        </div>
                    </div>
                    <!-- Pagination Section 3 End -->
                    
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
    
    <script src="js/printThis.js"></script>
    <?php
        for($print=0; $print < count($prints); $print++){
    ?>
    <script type="text/javascript">
    $(document).ready(function() {
        $("#btnPrint<?php echo $prints[$print];?>").click(function() {
            //get the modal box content and load it into the printable div
            $(".printable").html($("#divpublicacao<?php echo $prints[$print];?>").html());
            $(".printable").printThis();
        });
    });
    </script>
    <?php
        }
    ?>
</body>
</html>