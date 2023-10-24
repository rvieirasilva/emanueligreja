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
        $celula                     =   mysqli_escape_string($con, $_POST['celula']);
        $diadacelula                =   mysqli_escape_string($con, $_POST['diadacelula']);
        $horariodacelula            =   mysqli_escape_string($con, $_POST['horariodacelula']);
        $nomedarede                 =   mysqli_escape_string($con, $_POST['nomedarede']);
        $estado                     =   mysqli_escape_string($con, $_POST['estado']);
        $cidade                     =   mysqli_escape_string($con, $_POST['cidade']);
        $cep                        =   mysqli_escape_string($con, $_POST['cep']);        
        
        $liderdacelula0                = mysqli_escape_string($con, $_POST['liderdacelula']);
            $liderdacelulaarray = explode(' - ', $liderdacelula0);
            $liderdacelula      = $liderdacelulaarray[0];
            $matriculadolider = $liderdacelulaarray[1];
            $liderdacelula = base64_encode(base64_encode($liderdacelula));

        $secretario0                 = mysqli_escape_string($con, $_POST['secretario']);
            $secretarioarray        = explode(' - ', $secretario0);
            $secretario             = $secretarioarray[0];
            $matriculasecretario    = $secretarioarray[1];
            $secretario = base64_encode(base64_encode($secretario));

        $tesoureiro0                 = mysqli_escape_string($con, $_POST['tesoureiro']);
            $tesoureiroarray        = explode(' - ', $tesoureiro0);
            $tesoureiro             = $tesoureiroarray[0];
            $matriculatesoureiro    = $tesoureiroarray[1];
            $tesoureiro = base64_encode(base64_encode($tesoureiro));

        $anfitriao0                 = mysqli_escape_string($con, $_POST['anfitriao']);
            $anfitriaoarray        = explode(' - ', $anfitriao0);
            $anfitriao             = $anfitriaoarray[0];
            $matriculaanfitriao    = $anfitriaoarray[1];
            $anfitriao = base64_encode(base64_encode($anfitriao));

        $anfitria0                 = mysqli_escape_string($con, $_POST['anfitria']);
            $anfitriaarray        = explode(' - ', $anfitria0);
            $anfitria             = $anfitriaarray[0];
            $matriculaanfitria    = $anfitriaarray[1];
            $anfitria = base64_encode(base64_encode($anfitria));

        if($segundoliderdacelula0 === $liderdacelula0):
            $segundoliderdacelula = '';
            $matriculasegundoliderdacelula = '';
        endif;

        if($secretario0 === $liderdacelula0):
            $secretario = '';
            $matriculasecretario = '';
        endif;

        if($tesoureiro0 === $liderdacelula0):
            $tesoureiro = '';
            $matriculatesoureiro = '';
        endif;

        if($anfitriao0 === $liderdacelula0):
            $anfitriao = '';
            $matriculaanfitriao = '';
        endif;

        if($anfitria0 === $liderdacelula0):
            $anfitria = '';
            $matriculaanfitria = '';
        endif;

        //Buscar id
        $bIDC="SELECT id FROM celulas";
          $rIDC=mysqli_query($con, $bIDC);
            $nIDC=mysqli_num_rows($rIDC);
        
        $matricula=$nIDC.mt_rand(0001,999);

        //Buscar rede
        $bRede="SELECT id FROM celulas WHERE celula='$celula' AND rededacelula='$nomedarede'";
          $rRede=mysqli_query($con, $bRede);
            $nRede=mysqli_num_rows($rRede);
        
        if($nRede < 1):
            $abrirRede="INSERT INTO celulas (dia, mes, ano, celula, matricula, horario, diadeencontro, rededacelula, estado, municipio, cep, anfitriao, matriculaanfitriao, anfitria, matriculaanfitria, lider, matriculadolider, secretario, matriculasecretario, tesoureiro, matriculadotesoureiro) VALUES ('$dia', '$mes', '$ano', '$celula', '$matricula', '$horariodacelula', '$diadacelula', '$nomedarede', '$estado', '$cidade', '$cep', '$anfitriao', '$matriculaanfitriao', '$anfitria', '$matriculaanfitria', '$liderdacelula', '$matriculadolider', '$secretario', '$matriculasecretario', '$tesoureiro', '$matriculatesoureiro')";
            if(mysqli_query($con, $abrirRede)):
                $_SESSION['mensagem']="Célula aberta com sucesso.";
                $_SESSION['cordamensagem']='green';
            else:
                $_SESSION['mensagem']="Erro ao cadastrar a célula: $celula";
                $_SESSION['cordamensagem']='red';
            endif;
        else:
            $_SESSION['mensagem']="$celula foi cadastrada anteriormente.";
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
                                                        <h1 class="mb-0">Cadastrar <strong>célula</strong></h1>
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
                                                    <span class="color-black"><strong>NOME</strong> DA CÉLULA.</span>
                                                    <div class="field-wrapper">
                                                        <input type="text"  name="celula" class="form-aux form-date form-element large"  tabindex="5">
                                                    </div>
                                                </div>
                                                <div class="column width-3">
                                                    <span class="color-black"><strong>DIA</strong> DA CÉLULA.</span>
                                                    <div class="form-select form-element large">
                                                        <select name="diadacelula" tabindex="32" class="form-aux" data-label="sexo">
                                                            <option>Segunda-feira</option>
                                                            <option>Terça-feira</option>
                                                            <option>Quarta-feira</option>
                                                            <option>Quinta-feira</option>
                                                            <option>Sexta-feira</option>
                                                            <option>Sábado</option>
                                                            <option>Domingo</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="column width-2">
                                                    <span class="color-black"><strong>HORÁRIO</strong>.</span>
                                                    <div class="field-wrapper">
                                                        <input type="time" name="horariodacelula" class="form-aux form-date form-element large"  tabindex="5">
                                                    </div>
                                                </div>
                                                <div class="column width-4">
                                                    <span class="color-black"><strong>REDE</strong> DA CÉLULA.</span>
                                                    <div class="form-select form-element large">
                                                        <select name="nomedarede" tabindex="32" class="form-aux" data-label="sexo">
                                                            <?php
                                                                $bLider="SELECT nomedarede FROM rede WHERE areadarede='Células'";
                                                                $rLider=mysqli_query($con, $bLider);
                                                                    while($dLider=mysqli_fetch_array($rLider)):
                                                            ?>
                                                            <option><?php echo $dLider['nomedarede'];?></option>
                                                            <?php
                                                                endwhile;
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="column width-4">
                                                    <span class="color-black"><strong>LÍDER</strong> DA CÉLULA.</span>
                                                    <div class="form-select form-element large">
                                                        <select name="liderdacelula" tabindex="32" class="form-aux" data-label="sexo">
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
                                                <div class="column width-4">
                                                    <span class="color-black"><strong>ANFITRIÃO</strong> DA CÉLULA.</span>
                                                    <div class="form-select form-element large">
                                                        <select name="anfitriao" tabindex="32" class="form-aux" data-label="sexo">
                                                            <?php
                                                                $bLider="SELECT nome, sobrenome, matricula FROM membros WHERE statusdomembro='ATIVO' AND sexo='Masculino' AND uf='$estadodomembro' AND cidade='$cidadedomembro'";
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
                                                <div class="column width-4">
                                                    <span class="color-black"><strong>ANFITRIÃ</strong> DA CÉLULA.</span>
                                                    <div class="form-select form-element large">
                                                        <select name="anfitria" tabindex="32" class="form-aux" data-label="sexo">
                                                            <?php
                                                                $bLider="SELECT nome, sobrenome, matricula FROM membros WHERE statusdomembro='ATIVO' AND sexo='Feminino' AND uf='$estadodomembro' AND cidade='$cidadedomembro'";
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
                                                <div class="column width-4">
                                                    <span class="color-black"><strong>ESTADO</strong> DA CÉLULA.</span>
                                                    <div class="form-select form-element large">
                                                        <select name="estado" tabindex="32" class="form-aux" data-label="sexo">
                                                            <?php
                                                                $bLider="SELECT nome FROM estados WHERE nome='Rio de Janeiro' GROUP BY nome";
                                                                $rLider=mysqli_query($con, $bLider);
                                                                    while($dLider=mysqli_fetch_array($rLider)):
                                                            ?>
                                                            <option><?php echo $dLider['nome'];?></option>
                                                            <?php
                                                                endwhile;
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="column width-4">
                                                    <span class="color-black"><strong>CIDADE</strong> DA CÉLULA.</span>
                                                    <div class="form-select form-element large">
                                                        <select name="cidade" tabindex="32" class="form-aux" data-label="sexo">
                                                            <?php
                                                                $bLider="SELECT nome FROM cidades WHERE estado='19' GROUP BY nome";
                                                                $rLider=mysqli_query($con, $bLider);
                                                                    while($dLider=mysqli_fetch_array($rLider)):
                                                            ?>
                                                            <option><?php echo $dLider['nome'];?></option>
                                                            <?php
                                                                endwhile;
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="column width-4">
                                                    <span class="color-black"><strong>CEP</strong>.</span>
                                                    <div class="field-wrapper">
                                                        <input type="number"  name="cep" class="form-aux form-date form-element large"  tabindex="5">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="column width-6">
                                                    <span class="color-black"><strong>SECRETÁRIO</strong> DA CÉLULA.</span>
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
                                                    <span class="color-black"><strong>TESOUREIRO</strong> DA CÉLULA.</span>
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
    
</body>
</html>