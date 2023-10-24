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
    //     header("location:index");
	// endif;
	
	if(isset($_POST['btn-adicionarnacelula'])):
        //Buscar dados da célula.
        $bDadosDaCélula = "SELECT lider, matriculadolider, rededacelula FROM celulas WHERE celula='$celuladomembro'";
            $rDC=mysqli_query($con, $bDadosDaCélula);
            $dDC=mysqli_fetch_array($rDC);

        $lideratualC=$dDC['lider'];
        $matriculadoliderA=$dDC['matriculadolider'];
        $rededestacelula=$dDC['rededacelula'];

        for ($nM=0; $nM < count($_POST['membros']) ; $nM++) {
            $membrocadastrado0 = mysqli_escape_string($con, $_POST['membros'][$nM]);
            $membrocadastrado0 = explode('~', $membrocadastrado0);
                $nomemembro = $membrocadastrado0[0];
                $sobrenomemembro = $membrocadastrado0[1];
                $matriculanovomembro = $membrocadastrado0[2];
                $nomenovomembro = $nomemembro.' '.$sobrenomemembro;

            $bID="SELECT id FROM celulasmembros";
              $rID=mysqli_query($con, $bID);
                $dID=mysqli_fetch_array($rID);
            $idatual=$dID['id'];
            $referencianovomembro = $idatual.mt_rand(0000, 9999);

            $bMembroCadastrado="SELECT id FROM celulasmembros WHERE solicitante='$nomenovomembro'";
              $rMC=mysqli_query($con, $bMembroCadastrado);
                $nMC=mysqli_num_rows($rMC);

            if($nMC < 1):
                $inMembro= "INSERT INTO celulasmembros (dia, mes, ano, referencia, solicitante, matriculadomembro, celulaatual, codigodacelula, matriculadolider, lideratual, novacelula, matriculanovacelula, lidernovo, datadasolicitacao, liberacaodolideratual, aprovacaodonovolider, comentarioliderantigo, justificativaparamudanca, redeatual, redenova) VALUES ('$dia', '$mes', '$anoatual', '$referencianovomembro', '$nomenovomembro', '$matriculanovomembro', '$celuladomembro', '$matriculadacelula', '$matriculadoliderA', '$lideratualC', '$celuladomembro', '$matriculadacelula', '$lideratualC', '$data', '', '', '', '', '$rededestacelula', '')";
                if(mysqli_query($con, $inMembro)):
                    $_SESSION['cordamensagem']='green';
                    $_SESSION['mensagem']='Membro cadastrado com sucesso.';
                else:
                    $_SESSION['cordamensagem']='red';
                    $_SESSION['mensagem']='Erro ao cadastrar membro, comunicque ao TI. (Página de erro: celulaadd, L50.)';
                endif;
            else:
                $_SESSION['cordamensagem']='red';
                $_SESSION['mensagem']='Este membro foi cadastrado anteriormente, peça para que ele faça solicitação para mudança de célula';
            endif;
        }
    endif;
	
    //Dados para registrar o log do cliente.
        $mensagem = ("\n$nomedocolaborador ($matricula) Acessou a página de acionar membro na célula. Isto através do IP: $ip, na data $dia às $hora.\n"); //Escreva a mensagem que será gravada no log.
        include "registrolog.php";
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
                                                        <h1 class="mb-0"><strong>Adicionar membro na célula</strong>.</h1>
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
                                <form charset="UTF-8" class="form" action="<?echo $_SERVER['REQUEST_URI'];?>" method="post" enctype="Multipart/form-data">
                                <div class="column width-12">
                                    <button tabindex="04" type="submit" name="btn-adicionarnacelula" class="form-submit button small rounded bkg-blue bkg-hover-blue-light color-white color-hover-white hard-shadow"><span class="text-medium weight-bold">Adicionar selecionados</span></button>
                                </div>
                                <div class="column width-12">
                                    <div class="accordion rounded mb-50">
                                        <ul>
                                            <?
                                                $bMunicipios="SELECT uf, cidade FROM membros WHERE NOT (pertenceaqualcelula != '') AND uf='$estadodomembro' GROUP BY uf ORDER BY uf ASC";
                                                $rMunicipios=mysqli_query($con, $bMunicipios);
                                                    while($dMunicipios=mysqli_fetch_array($rMunicipios)):
                                                        $municipiodosmembros = $dMunicipios['cidade'];
                                                        $estadodosmembros = $dMunicipios['uf'];
                                            ?>
                                            <li>
                                                <a href="#<? echo $estadodosmembros.'-'.$municipiodosmembros;?>"><? echo $estadodosmembros.' - '.$municipiodosmembros; ?></a>
                                                <div id="<? echo $estadodosmembros.'-'.$municipiodosmembros;?>">
                                                    <div class="accordion-content">
                                                        <div class="field-wrapper pt-10 pb-10">
                                                            <?
                                                                $bMembrosMunicipio="SELECT nome, sobrenome, matricula FROM membros WHERE NOT (pertenceaqualcelula != '') AND uf='$estadodosmembros' AND cidade='$municipiodosmembros' ORDER BY nome ASC";
                                                                $rMM=mysqli_query($con, $bMembrosMunicipio);
                                                                    while($dMM=mysqli_fetch_array($rMM)):
                                                            ?>
                                                                <input id="<?echo $dMM['matricula'];?>" value="<?echo base64_decode(base64_decode($dMM['nome'])).'~'.base64_decode(base64_decode($dMM['sobrenome'])).'~'.$dMM['matricula'];?>" class="form-element radio" name="membros[]" type="radio">
                                                                <label for="<?echo $dMM['matricula'];?>" class="radio-label"><?echo base64_decode(base64_decode($dMM['nome'])).' '.base64_decode(base64_decode($dMM['sobrenome'])).' ( '.$dMM['matricula'].')';?></label>
                                                            <? endwhile; ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </li>
                                            <? endwhile; ?>
                                        </ul>
                                    </div>
                                </div>
                                </form>
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

			<?php
				include "./_modalevento.php";
			?>
		</div>
	</div>
    
	<? include "./_script.php"; ?>
    
</body>
</html>