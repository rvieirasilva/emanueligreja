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

    if(isset($_POST['btn-finalizar'])):
        $nomedocampus                 = mysqli_escape_string($con, $_POST['nomedocampus']);
        $enderecodocampus                  = mysqli_escape_string($con, $_POST['endereco']);
        $pastordocampus0                = mysqli_escape_string($con, $_POST['pastordocampus']);
            $pastordocampusarray = explode(' - ', $pastordocampus0);
            $pastordocampus      = $pastordocampusarray[0];
            $matriculadolider = $pastordocampusarray[1];

        $iniciodalideranca          = mysqli_escape_string($con, $_POST['iniciodalideranca']);
        $terminodalideranca         = date(('Y-m-d'), strtotime($iniciodalideranca.'+ 2 years'));
        $segundoliderdarede0         = mysqli_escape_string($con, $_POST['2liderdarede']);
            $segundoliderdaredearray        = explode(' - ', $segundoliderdarede0);
            $segundoliderdarede             = $segundoliderdaredearray[0];
            $matriculasegundoliderdarede    = $segundoliderdaredearray[1];

        $terceiroliderdarede0        = mysqli_escape_string($con, $_POST['3liderdarede']);
            $terceiroliderdaredearray        = explode(' - ', $terceiroliderdarede0);
            $terceiroliderdarede             = $terceiroliderdaredearray[0];
            $matriculaterceiroliderdarede    = $terceiroliderdaredearray[1];

        $secretario0                 = mysqli_escape_string($con, $_POST['secretario']);
            $secretarioarray        = explode(' - ', $secretario0);
            $secretario             = $secretarioarray[0];
            $matriculasecretario    = $secretarioarray[1];

        $segundosecretario0          = mysqli_escape_string($con, $_POST['2secretario']);
            $segundosecretarioarray        = explode(' - ', $segundosecretario0);
            $segundosecretario             = $segundosecretarioarray[0];
            $matriculasegundosecretario    = $segundosecretarioarray[1];

        $tesoureiro0                 = mysqli_escape_string($con, $_POST['tesoureiro']);
            $tesoureiroarray        = explode(' - ', $tesoureiro0);
            $tesoureiro             = $tesoureiroarray[0];
            $matriculatesoureiro    = $tesoureiroarray[1];

        $segundotesoureiro0          = mysqli_escape_string($con, $_POST['2tesoureiro']);
            $segundotesoureiroarray        = explode(' - ', $segundotesoureiro0);
            $segundotesoureiro             = $segundotesoureiroarray[0];
            $matriculasegundotesoureiro    = $segundotesoureiroarray[1];

        $areadarede                 = mysqli_escape_string($con, $_POST['areadarede']);
        $municipio                 = mysqli_escape_string($con, $_POST['municipio']);
        $descricao                  = mysqli_escape_string($con, $_POST['descricao']);

        if($segundoliderdarede0 === $liderdarede0):
            $segundoliderdarede = '';
            $matriculasegundoliderdarede = '';
        endif;

        if($terceiroliderdarede0 === $liderdarede0):
            $terceiroliderdarede = '';
            $matriculaterceiroliderdarede = '';
        endif;

        if($secretario0 === $liderdarede0):
            $secretario = '';
            $matriculasecretario = '';
        endif;

        if($segundosecretario0 === $liderdarede0):
            $segundosecretario = '';
        endif;

        if($segundosecretario0 === $secretario0):
            $segundosecretario = '';
            $matriculasegundosecretario = '';
        endif;

        if($tesoureiro0 === $liderdarede0):
            $tesoureiro = '';
            $matriculatesoureiro = '';
        endif;

        if($segundotesoureiro0 === $liderdarede0):
            $segundotesoureiro = '';
            $matriculasegundotesoureiro = '';
        endif;

        if($segundotesoureiro0 === $tesoureiro0):
            $segundotesoureiro = '';
            $matriculasegundotesoureiro = '';
        endif;

        //Buscar rede
        $bRede="SELECT id FROM campus WHERE nomedocampus='$nomedocampus' AND areadocampus='$areadarede'";
          $rRede=mysqli_query($con, $bRede);
            $nRede=mysqli_num_rows($rRede);
        
        if($nRede < 1):
            //Buscar ciclo
            $bCiclo = "SELECT id, terminodalideranca FROM campus WHERE pastordocampus='$pastordocampus' AND areadocampus='$areadarede'";
            $rCiclo=mysqli_query($con, $bCiclo);
                $dCiclo=mysqli_fetch_array($rCiclo);
                $nCiclo=mysqli_num_rows($rCiclo);

            $ciclosdelideranca = $nCiclo;
            $liberadoparaliderar = date(('Y-m-d'), strtotime($dCiclo['terminodalideranca'] . '+ 2 years'));

            
            $abrirRede="INSERT INTO campus (dia, mes, ano, nomedocampus, enderecodocampus, pastordocampus, matricula, iniciodalideranca, terminodalideranca, ciclo, vicelider, matriculavicelider, segundovp, matriculasegundovp, secretario, matriculasecretario, segundosecretario, matriculasegundosecretario, tesoureiro, matriculatesoureiro, segundotesoureiro, matriculasegundotesoureiro, areadocampus, municipio, descricao) VALUES ('$dia', '$mesnumero', '$ano', '$nomedocampus', '$enderecodocampus', '$pastordocampus', '$matriculadolider', '$iniciodalideranca', '$terminodalideranca', '$ciclosdelideranca', '$segundoliderdarede', '$matriculasegundoliderdarede', '$terceiroliderdarede', '$matriculaterceiroliderdarede', '$secretario', '$matriculasecretario', '$segundosecretario', '$matriculasegundosecretario', '$tesoureiro', '$matriculatesoureiro', '$segundotesoureiro', '$matriculasegundotesoureiro', '$areadarede', '$municipio','$descricao')";
            if(mysqli_query($con, $abrirRede)):
                $_SESSION['mensagem']="Campus aberto com sucesso.";
                $_SESSION['cordamensagem']='green';
            else:
                $_SESSION['mensagem']="Erro ao abrir campus";
                $_SESSION['cordamensagem']='red';
            endif;
        else:
            $_SESSION['mensagem']="$nomedarede foi cadastrado anteriormente.";
            $_SESSION['cordamensagem']='red';
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
                                                        <h1 class="mb-0">Cadastrar <strong>campus</strong></h1>
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
                                        <form class="form" action="" charset="UTF-8" method="post" enctype="Multipart/form-data">
                                            <div class="row">
                                                <div class="column width-3">
                                                    <span class="color-black"><strong>NOME</strong> DO CAMPUS.</span>
                                                    <div class="field-wrapper">
                                                        <input type="text"  name="nomedocampus" class="form-aux form-date form-element large"  tabindex="5">
                                                    </div>
                                                </div>
                                                <div class="column width-3">
                                                    <span class="color-black"><strong>ENDEREÇO</strong> DO CAMPUS.</span>
                                                    <div class="field-wrapper">
                                                        <input type="text"  name="endereco" class="form-aux form-date form-element large"  tabindex="5">
                                                    </div>
                                                </div>
                                                <div class="column width-6">
                                                    <span class="color-black"><strong>PASTOR</strong> DO CAMPUS.</span>
                                                    <div class="form-select form-element large">
                                                        <select name="pastordocampus" tabindex="32" class="form-aux" data-label="sexo">
                                                            <?php
                                                                $bLider="SELECT nome, sobrenome, matricula FROM membros WHERE statusdomembro='ATIVO' AND uf='$estadodomembro' AND cidade='$cidadedomembro'";
                                                                $rLider=mysqli_query($con, $bLider);
                                                                    while($dLider=mysqli_fetch_array($rLider)):
                                                            ?>
                                                            <option><?php echo base64_decode(base64_decode($dLider['nome'])).' '.base64_decode(base64_decode($dLider['sobrenome'])).' - '.$dLider['matricula'];?></option>
                                                            <?php
                                                                endwhile;
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="column width-3">
                                                    <span class="color-black"><strong>INICIO DA LIDERANÇA</strong></span>
                                                    <div class="field-wrapper">
                                                        <input type="date"  name="iniciodalideranca" value="<?php echo date('Y-m-d');?>" class="form-aux form-date form-element large"  tabindex="5">
                                                    </div>
                                                </div>
                                                <div class="column width-4">
                                                    <span class="color-black">2º <strong>LÍDER</strong> DO CAMPUS.</span>
                                                    <div class="form-select form-element large">
                                                        <select name="2liderdarede" tabindex="32" class="form-aux" data-label="sexo">
                                                            <?php
                                                                $bLider="SELECT nome, sobrenome, matricula FROM membros WHERE statusdomembro='ATIVO' AND uf='$estadodomembro' AND cidade='$cidadedomembro'";
                                                                $rLider=mysqli_query($con, $bLider);
                                                                    while($dLider=mysqli_fetch_array($rLider)):
                                                            ?>
                                                            <option><?php echo base64_decode(base64_decode($dLider['nome'])).' '.base64_decode(base64_decode($dLider['sobrenome'])).' - '.$dLider['matricula'];?></option>
                                                            <?php
                                                                endwhile;
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="column width-5">
                                                    <span class="color-black">3º <strong>LÍDER</strong> DO CAMPUS.</span>
                                                    <div class="form-select form-element large">
                                                        <select name="3liderdarede" tabindex="32" class="form-aux" data-label="sexo">
                                                            <?php
                                                                $bLider="SELECT nome, sobrenome, matricula FROM membros WHERE statusdomembro='ATIVO' AND uf='$estadodomembro' AND cidade='$cidadedomembro'";
                                                                $rLider=mysqli_query($con, $bLider);
                                                                    while($dLider=mysqli_fetch_array($rLider)):
                                                            ?>
                                                            <option><?php echo base64_decode(base64_decode($dLider['nome'])).' '.base64_decode(base64_decode($dLider['sobrenome'])).' - '.$dLider['matricula'];?></option>
                                                            <?php
                                                                endwhile;
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="column width-6">
                                                    <span class="color-black"><strong>SECRETÁRIO</strong> DO CAMPUS.</span>
                                                    <div class="form-select form-element large">
                                                        <select name="secretario" tabindex="32" class="form-aux" data-label="sexo">
                                                            <?php
                                                                $bLider="SELECT nome, sobrenome, matricula FROM membros WHERE statusdomembro='ATIVO' AND uf='$estadodomembro' AND cidade='$cidadedomembro'";
                                                                $rLider=mysqli_query($con, $bLider);
                                                                    while($dLider=mysqli_fetch_array($rLider)):
                                                            ?>
                                                            <option><?php echo base64_decode(base64_decode($dLider['nome'])).' '.base64_decode(base64_decode($dLider['sobrenome'])).' - '.$dLider['matricula'];?></option>
                                                            <?php
                                                                endwhile;
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="column width-6">
                                                    <span class="color-black">2º <strong>SECRETÁRIO</strong> DO CAMPUS.</span>
                                                    <div class="form-select form-element large">
                                                        <select name="2secretario" tabindex="32" class="form-aux" data-label="sexo">
                                                            <?php
                                                                $bLider="SELECT nome, sobrenome, matricula FROM membros WHERE statusdomembro='ATIVO' AND uf='$estadodomembro' AND cidade='$cidadedomembro'";
                                                                $rLider=mysqli_query($con, $bLider);
                                                                    while($dLider=mysqli_fetch_array($rLider)):
                                                            ?>
                                                            <option><?php echo base64_decode(base64_decode($dLider['nome'])).' '.base64_decode(base64_decode($dLider['sobrenome'])).' - '.$dLider['matricula'];?></option>
                                                            <?php
                                                                endwhile;
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="column width-6">
                                                    <span class="color-black"><strong>TESOUREIRO</strong> DO CAMPUS.</span>
                                                    <div class="form-select form-element large">
                                                        <select name="tesoureiro" tabindex="32" class="form-aux" data-label="sexo">
                                                            <?php
                                                                $bLider="SELECT nome, sobrenome, matricula FROM membros WHERE statusdomembro='ATIVO' AND uf='$estadodomembro' AND cidade='$cidadedomembro'";
                                                                $rLider=mysqli_query($con, $bLider);
                                                                    while($dLider=mysqli_fetch_array($rLider)):
                                                            ?>
                                                            <option><?php echo base64_decode(base64_decode($dLider['nome'])).' '.base64_decode(base64_decode($dLider['sobrenome'])).' - '.$dLider['matricula'];?></option>
                                                            <?php
                                                                endwhile;
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="column width-6">
                                                    <span class="color-black">2º <strong>TESOUREIRO</strong> DO CAMPUS.</span>
                                                    <div class="form-select form-element large">
                                                        <select name="2tesoureiro" tabindex="32" class="form-aux" data-label="sexo">
                                                            <?php
                                                                $bLider="SELECT nome, sobrenome, matricula FROM membros WHERE statusdomembro='ATIVO' AND uf='$estadodomembro' AND cidade='$cidadedomembro'";
                                                                $rLider=mysqli_query($con, $bLider);
                                                                    while($dLider=mysqli_fetch_array($rLider)):
                                                            ?>
                                                            <option><?php echo base64_decode(base64_decode($dLider['nome'])).' '.base64_decode(base64_decode($dLider['sobrenome'])).' - '.$dLider['matricula'];?></option>
                                                            <?php
                                                                endwhile;
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="column width-6">
                                                    <span class="color-black"><strong>ÁREA</strong> DO CAMPUS</span>
                                                    <div class="form-select form-element large">
                                                        <select name="areadarede" tabindex="32" class="form-aux" data-label="sexo">
                                                            <?
                                                                $bA="SELECT nome FROM estados GROUP BY nome ORDER BY nome ASC";
                                                                  $rA=mysqli_query($con, $bA);
                                                                    while($dA=mysqli_fetch_array($rA)):
                                                            ?>
                                                            <option><? echo $dA['nome'];?></option>
                                                            <?endwhile; ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="column width-6">
                                                    <span class="color-black"><strong>MUNICÍPIO</strong> DO CAMPUS</span>
                                                    <div class="form-select form-element large">
                                                        <select name="municipio" tabindex="32" class="form-aux" data-label="sexo">
                                                            <?
                                                                $bAc="SELECT nome FROM cidades WHERE estado='19' GROUP BY nome ORDER BY nome ASC";
                                                                  $rAc=mysqli_query($con, $bAc);
                                                                    while($dAc=mysqli_fetch_array($rAc)):
                                                            ?>
                                                            <option><? echo $dAc['nome'];?></option>
                                                            <?endwhile; ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="column width-12">
                                                    <span class="color-black"><strong>DESCRIÇÃO</strong> DO CAMPUS</span>
                                                    <div class="field-wrapper">
                                                        <textarea type="text"  name="descricao" class="form-aux form-date form-element large"  tabindex="5"></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <br>
                                                <div class="column width-12">
                                                    <button type="submit" name="btn-finalizar" class="form-submit button pill small border-theme bkg-hover-theme color-theme color-hover-white">PUBLICAR</button>
                                                </div>
                                            </div>
                                        </form>
                                        <div class="form-response"></div>
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