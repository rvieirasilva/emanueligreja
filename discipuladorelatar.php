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
    
    if(isset($_POST['btn-verificar'])):
        $cpf                    = mysqli_escape_string($con, $_POST['cpf']);
            $cpfVerify=base64_encode($cpf); 
            $cpfVerify=base64_encode($cpfVerify);
        
        $VerificarMembro="SELECT id FROM membros WHERE cpf='$cpfVerify'";
          $rVC=mysqli_query($con, $VerificarMembro);
            $nVC=mysqli_num_rows($rVC);
        
        if($nVC > 0):
            $_SESSION['relatoriodomembro']=true;
        else:
            $_SESSION['cordamensagem']="red";
            $_SESSION['mensagem']="Está ação só pode ser realizada por um membro cadastrado, sendo membro da Emanuel; <a href='./entrar'>cadastre-se</a>";
        endif;
    endif;

    if(isset($_POST['btn-enviar'])):
        $bID="SELECT id FROM relatoriodediscipulado ORDER BY id DESC LIMIT 1";
          $rID=mysqli_query($con, $bID);
            $dID=mysqli_fetch_array($rID);
        
        $codigodorelatorio  =date('ymd').substr($dID['id'], 0, -4).mt_rand(0000,9999);
        $cpfdiscipulador  =mysqli_escape_string($con, $_POST['cpfdiscipulador']);
            $cpfdiscipulador=base64_encode($cpfdiscipulador);
            $cpfdiscipulador=base64_encode($cpfdiscipulador);
        $objetivo  =mysqli_escape_string($con, $_POST['objetivo']);
        $presenca  =mysqli_escape_string($con, $_POST['presenca']);
        $datadodiscipulado  =mysqli_escape_string($con, $_POST['datadodiscipulado']);
        $discipulo          =mysqli_escape_string($con, $_POST['discipulo']);
            $discipulo0  = explode('~', $discipulo);
            $discipulo  = $discipulo0[0];
            $matriculadodiscipulo=$discipulo0[1];
        // $descricao          =mysqli_escape_string($con, $_POST['descricao']);
        $descricao = '';

        $bDiscipulador="SELECT nome, sobrenome, matricula FROM membros WHERE cpf='$cpfdiscipulador'";
          $rDiscipulador=mysqli_query($con, $bDiscipulador);
            $dDiscipulaor=mysqli_fetch_array($rDiscipulador);
        
        $nomedodiscipulador=base64_decode(base64_decode($dDiscipulaor['nome']));
        $sobrenomedodiscipulador=base64_decode(base64_decode($dDiscipulaor['sobrenome']));

        $nomedodiscipulador=$nomedodiscipulador.' '.$sobrenomedodiscipulador;

        $bCd="SELECT membrodocampus, codigodocampus FROM membros WHERE matricula='$matriculadodiscipulo'";
          $rCd=mysqli_query($con, $bCd);
            $dCd=mysqli_fetch_array($rCd);
        
        $campusdodiscipulo=$dCd['membrodocampus'];
        $codigocampusdodiscipulo=$dCd['codigodocampus'];


        $discipulo=base64_encode($discipulo);
        $discipulo=base64_encode($discipulo);

        $bDupl="SELECT id FROM relatoriodediscipulado WHERE discipulador='$nomedodiscipulador' AND datadoencontro='$datadodiscipulado' AND discipulado='$discipulo' AND campusdodiscipulo='$campusdodiscipulo'";
          $rDup=mysqli_query($con, $bDupl);
            $nDup=mysqli_num_rows($rDup);
        if($nDup > 0):
            $_SESSION['cordamensagem']='red';
            $_SESSION['mensagem']="Não inserido. Este relatório foi enviado anteriormente.";
        else:
            $RegDisc="INSERT INTO relatoriodediscipulado (codigodorelatorio, dia, mes, ano, datadoencontro, campus, matriculadocampus, discipulador, matriculadodiscipulador, discipulado, matriculadodiscipulado, campusdodiscipulo, matriculadocampudodiscipulo, encontros, objetivo, descricao) VALUES ('$codigodorelatorio', '$dia', '$mesnumero', '$ano', '$datadodiscipulado', '$membrodocampus', '$codigodocampus', '$nomedodiscipulador', '$matricula', '$discipulo', '$matriculadodiscipulo', '$campusdodiscipulo', '$codigocampusdodiscipulo', '$presenca', '$objetivo', '$descricao')";
            if(mysqli_query($con, $RegDisc)):
                $_SESSION['cordamensagem']='green';
                $_SESSION['mensagem']="Relatório enviado com sucesso.";
                unset($_SESSION['relatoriodomembro']);
            else:
                $_SESSION['cordamensagem']='red';
                $_SESSION['mensagem']="Relatório não enviado, entre em contato com o setor de comunicação da igreja e relate o ocorrido.";
            endif;
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
			<!-- Content -->
			<div class="content clearfix">
                <div class="section-block tm-slider-parallax-container pb-0 small bkg-blue">
					<div class="row">
                        <div class="box rounded small bkg-white shadow">
						    <div class="column width-12">
                                <div class="title-container">
                                    <div class="title-container-inner">
                                        <div class="row flex">
                                            <div class="column width-12 v-align-middle">
                                                <div>
                                                    <h1 class="mb-0">Relatório do discipulado</h1>
                                                    <p class="text-large color-charcoal mb-0 mb-mobile-20"></strong></p>
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
                        <?php
                            include "./_notificacaomensagem.php";
                        ?>
                        <!-- <div class="box rounded bkg-white shadow"> -->
						<div class="column width-12">
							<div>
								<div class="signup-box box rounded medium mb-0 bkg-white border-blue">
                                    <h3 class="color-blue"><strong>Como foi o discipulado?</strong></h3>
                                    <div class="register-form-container">
                                        <form class="login-form" action="<? echo $_SERVER['REQUEST_URI'];?>" method="post" charset="UTF-8">
                                            <div class="row">
                                                <? if(!isset($_SESSION['relatoriodomembro'])): $btn="btn-verificar";?>
                                                <div class="column width-12">
                                                    <div class="field-wrapper">
                                                        <input type="password" name="cpf" class="form-element medium rounded login-form-textfield" placeholder="Seu CPF" required>
                                                    </div>
                                                </div>
                                                <? else: $btn="btn-enviar";?>
                                                    <input type="hidden" value="<?php echo $_POST['cpf'];?>" name="cpfdiscipulador">
                                                <div class="column width-12">
                                                    <span class="color-blue">principal <strong>Objetivo</strong> de mudança proposto no discipulado</span>
                                                    <div class="field-wrapper">
                                                        <input type="text" name="objetivo"  class="form-element medium rounded login-form-textfield" placeholder="Escreva o principal objetivo proposto no encontro deste discipulado" required>
                                                    </div>
                                                </div>
                                                <div class="column width-3">
                                                    <span class="color-blue"><strong>Presença</strong></span>
                                                    <div class="field-wrapper pt-10 pb-10 left bkg-white small rounded">
                                                        <input tabindex="001" id="presente" class="radio" name="presenca" value="1" type="radio" checked>
                                                        <label for="presente" class="radio-label color-blue">Presente</label>
                                                        <input tabindex="002" id="faltou" class="radio" name="presenca" value="0" type="radio">
                                                        <label for="faltou" class="radio-label color-blue">Faltou</label>
                                                    </div>
                                                </div>
                                                <div class="column width-3">
                                                    <span class="color-blue"><strong>Data</strong> do discipulado</span>
                                                    <div class="field-wrapper">
                                                        <input type="date" name="datadodiscipulado" value=<? echo date('Y-m-d');?> class="form-element medium rounded login-form-textfield" placeholder="data" required>
                                                    </div>
                                                </div>
                                                <div class="column width-6">
                                                    <span class="color-blue"><strong>discípulo</strong></span>
                                                    <div class="form-select form-element medium">
                                                        <select name="discipulo" tabindex="019" class="form-aux" data-label="sexo">
                                                            <?
                                                                $bMembros="SELECT nome, sobrenome, matricula FROM membros WHERE NOT (ministerio ='') AND pertenceaqualcelula !=''";
                                                                $rM=mysqli_query($con, $bMembros);
                                                                    while($dM=mysqli_fetch_array($rM)):
                                                            ?>
                                                            <option value="<? echo base64_decode(base64_decode($dM['nome'])).' '.base64_decode(base64_decode($dM['sobrenome'])).'~'.$dM['matricula'];?>"><? echo base64_decode(base64_decode($dM['nome'])).' '.base64_decode(base64_decode($dM['sobrenome'])).' ('.$dM['matricula'].')';?></option>
                                                            <? endwhile; ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <!-- <div class="column width-12">
                                                    <span class="color-blue"><strong>Descrição</strong> do encontro. (Este relatório sera encriptado.)</span>
                                                    <div class="field-wrapper">
                                                        <textarea type="date" name="descricao" placeholder="Escreva aqui uma descrição deste encontro do discipulado" class="form-element medium rounded login-form-textfield"></textarea>
                                                    </div>
                                                </div> -->
                                                <? endif; ?>
                                                <div class="row no-margins">
                                                    <div class="column width-6">
                                                        <button type="submit" value="" name="<? echo $btn; ?>" class="button small rounded bkg-blue bkg-hover-blue hard-shadow"><span class="text-medium color-white color-hover-white weight-bold">Enviar</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
							</div>
						</div>
                    </div>
                </div>
			</div>
			<!-- Content End -->

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