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
    
        $excluir="DELETE FROM membros WHERE id='$iddopostexcluir' AND referencia='$refparaexc'";
         if(mysqli_query($con, $excluir)):
            $_SESSION['mensagem']="Conceito excluido.";
                //Dados para registrar o log do cliente.
                    $mensagem = ("\n$nomedousuario ($matricula) Excluíu o conceito $refparaexc. Isto através do IP: $ip, na data $dia às $hora.\n"); //Escreva a mensagem que será gravada no log.
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
                                            <div class="column width-12 v-align-middle">
                                                <div>
                                                    <h1 class="mb-0">Membros a serem <strong>recebidos</strong></h1>
                                                    <p class="text-large color-charcoal mb-0 mb-mobile-20">Por isso não
                                                        corro sem objetivo.</p>
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
                                            <th>Nome</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                        <?php
                                            // <!--CÓDIGO PARA FAZER PESQUISA DOS HORÁRIOS E CRIAR O LOOPING -->
                                            $pagina = (isset($_GET['pagina']))? $_GET['pagina'] : 1;

                                                $buscarpormembros= "SELECT id FROM membros WHERE NOT (nome = '') ORDER BY nome ASC, statusdomembro DESC";
                                                    $resultadobuscarpormembros = mysqli_query($con, $buscarpormembros);
                                                    
                                                    $totaldemembros = mysqli_num_rows($resultadobuscarpormembros);

                                                    //Defini o número de horarios que serão exibidos por página
                                                    $membrosporpagina = 30;

                                                    //defini número de páginas necessárias para exibir todos os horarios.
                                                    $totaldepaginas = ceil($totaldemembros / $membrosporpagina);

                                                    $inicio = ($membrosporpagina * $pagina) - $membrosporpagina;

                                            
                                                //AQUI ELE FAZ A PESQUISA PELO CAMPUS E TRAZ O DADO MAIS RECENTE PELO ANO, SE ESTAMOS EM 2010 ELE TRARÁ OS DADOS DESTE ANO OU DO ÚLTIMO ANO EM QUE O CAMPUS APRESENTOU UMA MATÉRIA REGISTRADA, COM BASE NISSO É FEITO UM FILTRO EXIBINDO POR ORDEM O DIA E DEPOIS O HORÁRIO.
                                                $membrosparaeditar= "SELECT id, nome, sobrenome, matricula, statusdomembro FROM membros WHERE NOT (nome = '') ORDER BY statusdomembro DESC, nome ASC LIMIT $inicio, $membrosporpagina";
                                                    $resultadomembrosparaeditar = mysqli_query($con   , $membrosparaeditar);
                                                            $linha=0;
                                                        while($dadosmembrosparaeditar = mysqli_fetch_array($resultadomembrosparaeditar)):
                                                            $linha++;

                                                            // $converterdata = $dadosmembrosparaeditar['postadoem'];
                                                            // $converterdata = date("d/m/Y", strtotime($converterdata));
                                        ?>
                                        <tr>
                                            <!-- O CÓDIGO ABAIXO VERIFICA SE O DIA CORRESPONDE COM A SEGUNDA-FEIRA, PARA COLUNA E TRÁS AS MATÉRIAS PARA DESTES DIAS -->
                                            <td class="color-black" width="3%" valign="medium">
                                                <?php echo $linha;?>
                                            </td>
                                            <td class="color-black text-medium" width="56%" valign="medium">
                                                <?php $nomeM=base64_decode($dadosmembrosparaeditar['nome']); $nomeM=base64_decode($nomeM); $sobrenomeM=base64_decode($dadosmembrosparaeditar['sobrenome']); $sobrenomeM=base64_decode($sobrenomeM); echo $nomeM.' '.$sobrenomeM.' ('.$dadosmembrosparaeditar['statusdomembro'].')';?>
                                            </td>
                                            <td width="10%" valign="medium">

                                                <center>
                                                    <!-- <a href="equipemembro?m=<?php echo $matricula;?>&exc&id=<?php echo $dadosmembrosparaeditar['id'];?>&<?php echo uniqid();?>&token=<?php echo $token;?>&ref=<?php echo $dadosmembrosparaeditar['matricula'];?>" class="icon-pencil color-black color-charcoal center small thick"> -->
                                                    <a href="equipemembro?m=<?php echo $matricula;?>&id=<?php echo $dadosmembrosparaeditar['id'];?>&<?php echo uniqid();?>&token=<?php echo $token;?>&ref=<?php echo $dadosmembrosparaeditar['matricula'];?>"
                                                        class="icon-pencil color-black color-charcoal center small thick">
                                                        Editar
                                                    </a>
                                                </center>
                                            </td>
                                            <!-- <td width="8%" valign="medium">
                                                    <center>
                                                    <span class="color-black">
                                                        <a class="color-black" href="fraselistar?m=<?//php echo $matricula;?>&exc&id=<?//php echo $dadosmembrosparaeditar['id'];?>&<?//php echo uniqid();?>&token=<?//php echo $token;?>&ref=<?//php echo $dadosmembrosparaeditar['matricula'];?>">
                                                        Excluir
                                                        </a>
                                                    </span>
                                                </td> -->
                                        </tr>
                                        <?php endwhile; ?>
                                    </tbody>
                                </table>

                                <div class="row">
                                    <div class="column width-12">
                                        <button type="button" onclick="javascript:window.print();"
                                            class="form-submit pill button small bkg-blue-light bkg-hover-blue color-white color-hover-white">IMPRIMIR</button>
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
                                                <input type="text" name="search" class="form-search form-element"
                                                    placeholder="type &amp; hit enter...">
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
                                                <input type="file" name="imagemcapa"
                                                    class="button medium border-charcoal-light color-blue-light color-hover-blue">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="column width-5 left">
                                                <button type="submit" value="ALTERAR CAPA" name="btn-alterarcapa"
                                                    class="button medium border-red color-blue-lght color-hover-blue">ALTERAR
                                                    CAPA</button>
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
                                                <input type="file" name="miniatura"
                                                    class="button medium border-charcoal-light color-blue-light color-hover-blue">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="column width-5 left">
                                                <input type="submit" value="ALTERAR MINIATURA"
                                                    name="btn-alterarminiatura"
                                                    class="button medium border-red color-blue-lght color-hover-blue">
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