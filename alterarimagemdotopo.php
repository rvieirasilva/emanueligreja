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
        $imagenparaexcluir=$_GET['id'];
    
        $excluir="DELETE FROM imagenssite WHERE id='$imagenparaexcluir'";
        if(mysqli_query($con, $excluir)):
            $_SESSION['cordamensagem']="green";
            $_SESSION['mensagem']="Imagen do topo excluida.";
            header('refresh:2');

            //Dados para registrar o log do cliente.
            $mensagem = ("\n$nomedocolaborador ($matricula) Excluíu uma Imagen do topo. Isto através do IP: $ip, na data $dia às $hora.\n"); //Escreva a mensagem que será gravada no log.
            include "./registrolog.php";
            //Fim do registro do log.
        else:
            $_SESSION['cordamensagem']="red";
            $_SESSION['mensagem']="Erro ao exluir image, envie uma mensagem para nossa equipe de mídia e informe o erro imediatamente.";
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
                                                        <h1 class="mb-0">Alterar imagem do <strong>site</strong></h1>
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
                                    <div class="contact-form-container">
                                        <table class="table striped non-responsive rounded medium full-width">
                                            <thead>
                                                <tr class="bkg-charcoal color-white">
                                                    <th></th>
                                                    <th>IMAGEM</th>
                                                    <th>TIPO</th>
                                                    <th>POSTADO EM</th>
                                                    <th></th>
                                                </tr>
                                            </thead>
                                            <tbody>

                                            <?php
                                                // <!--CÓDIGO PARA FAZER PESQUISA DOS HORÁRIOS E CRIAR O LOOPING -->
                                                $pagina = (isset($_GET['pagina']))? $_GET['pagina'] : 1;

                                                    $buscarporimagens= "SELECT * FROM imagenssite WHERE NOT (pasta = '') ORDER BY id DESC";
                                                        $resultadobuscarporimagens = mysqli_query($con, $buscarporimagens);
                                                        
                                                        $totaldeimagens = mysqli_num_rows($resultadobuscarporimagens);

                                                        //Defini o número de horarios que serão exibidos por página
                                                        $imagensporpagina = 30;

                                                        //defini número de páginas necessárias para exibir todos os horarios.
                                                        $totaldepaginas = ceil($totaldeimagens / $imagensporpagina);

                                                        $inicio = ($imagensporpagina * $pagina) - $imagensporpagina;

                                                
                                                    //AQUI ELE FAZ A PESQUISA PELO CAMPUS E TRAZ O DADO MAIS RECENTE PELO ANO, SE ESTAMOS EM 2010 ELE TRARÁ OS DADOS DESTE ANO OU DO ÚLTIMO ANO EM QUE O CAMPUS APRESENTOU UMA MATÉRIA REGISTRADA, COM BASE NISSO É FEITO UM FILTRO EXIBINDO POR ORDEM O DIA E DEPOIS O HORÁRIO.
                                                    $imagensparalistar= "SELECT pasta, tipo, postadoem, id FROM imagenssite WHERE NOT (pasta = '') ORDER BY id DESC LIMIT $inicio, $imagensporpagina";
                                                        $resultadoimagensparalistar = mysqli_query($con   , $imagensparalistar);
                                                                $linha=0;
                                                            while($dadosimagensparalistar = mysqli_fetch_array($resultadoimagensparalistar)):
                                                                $linha++;
                                            ?>
                                                <tr >
                                                    <!-- O CÓDIGO ABAIXO VERIFICA SE O DIA CORRESPONDE COM A SEGUNDA-FEIRA, PARA COLUNA E TRÁS AS MATÉRIAS PARA DESTES DIAS -->
                                                    <td class="color-black" width="5%" valign="medium"> <?php echo $linha;?> </td>
                                                    <td class="color-black" width="69%" valign="medium"> <?php echo $dadosimagensparalistar['pasta'];?> </td>
                                                    <td class="color-black" width="10%" valign="medium"> <?php echo $dadosimagensparalistar['tipo'];?> </td>
                                                    <td class="color-black" width="10%" valign="medium"> <?php echo $dadosimagensparalistar['postadoem'];?> </td>
                                                    <td width="5%" valign="medium"><center>
                                                        <a href="alterarimagemdotopo?<?php echo $linkSeguro;?>exc&id=<?php echo $dadosimagensparalistar['id'];?>"><span class="color-black">Excluir</span></a>
                                                    </td>
                                                </tr>
                                                <?php endwhile; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
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