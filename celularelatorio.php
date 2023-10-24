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
        $bIDR="SELECT id FROM relatorio";
          $rID=mysqli_query($con, $bIDR);
            $nID=mysqli_num_rows($rID);
        $referenciadorelatorio= $nID.mt_rand(00000, 99999);

		$participantes					= mysqli_escape_string($con, $_POST['participantes']);
		$visitantes						= mysqli_escape_string($con, $_POST['visitantes']);
		$homenspresentes				= mysqli_escape_string($con, $_POST['homenspresentes']);
		$mulherespresentes				= mysqli_escape_string($con, $_POST['mulherespresentes']);
		$kidspresentes				= mysqli_escape_string($con, $_POST['kidspresentes']);
		$oferta							= mysqli_escape_string($con, $_POST['oferta']);
		$datadoencontro					= mysqli_escape_string($con, $_POST['datadoencontro']);
		$dinamica						= mysqli_escape_string($con, $_POST['dinamica']);
		$descricao						= mysqli_escape_string($con, $_POST['descricao']);
		$horarioinicio						= mysqli_escape_string($con, $_POST['horarioinicio']);
		$horariotermino						= mysqli_escape_string($con, $_POST['horariotermino']);

			if($oferta > 1000):
				$oferta = number_format($oferta, 2, '', '.');
			elseif($oferta < 1000):
				$oferta = str_replace(',','.', $oferta);
			endif;


        $bDuplicado="SELECT id FROM relatorio WHERE datadoencontro='$datadoencontro' AND oferta='$oferta' AND participantes='$participantes' AND celula='$celuladomembro' AND matriculadacelula='$matriculadacelula'";
          $rDuplicado=mysqli_query($con, $bDuplicado);
            $nDuplicado=mysqli_num_rows($rDuplicado);

        if($nDuplicado > 0):
            $_SESSION['cordamensagem']='red';
            $_SESSION['mensagem']='Este relatório foi enviado anteriormente, acesse o campo para editá-lo';
        else:            
            //Buscar célula do membro realizado o relatório.
            $bCelu="SELECT matricula, lider, matriculadolider, rededacelula FROM celulas WHERE celula='$celuladomembro' AND matricula='$matriculadacelula'";
            $rCelula=mysqli_query($con, $bCelu);
                $dCelula=mysqli_fetch_array($rCelula);
                
                $matriculadacelula=$dCelula['matricula'];
                $liderdacelula=$dCelula['lider'];
                $matriculadolider=$dCelula['matriculadolider'];
                $rededacelula=$dCelula['rededacelula'];
            
            if(!empty($_FILES['fotodacelula']['name'])):
                $extensaodafoto = pathinfo($_FILES['fotodacelula']['name'], PATHINFO_EXTENSION);
                if(in_array($extensaodafoto, $formatospermitidosimagens)):
                    $img_foto 			= $_FILES["capa"]['name'];

                    @mkdir("arq/", 0777); //Cria a pasta se não houver.  
                    @mkdir("arq/relatorios/", 0777); //Cria a pasta se não houver.  
                    @mkdir("arq/relatorios/$rededacelula/", 0777); //Cria a pasta se não houver.  
                    @mkdir("arq/relatorios/$rededacelula/$matriculadacelula/", 0777); //Cria a pasta se não houver.  
                    @mkdir("arq/relatorios/$rededacelula/$matriculadacelula/$anoatual/", 0777); //Cria a pasta se não houver.  
                    @mkdir("arq/relatorios/$rededacelula/$matriculadacelula/$anoatual/$mes/", 0777); //Cria a pasta se não houver.  

                    $pastafoto			= "arq/relatorios/$rededacelula/$matriculadacelula/$anoatual/$mes/";
                    $temporariofoto	    = $_FILES['fotodacelula']['tmp_name'];
                    $tamanhofoto	    = $_FILES['fotodacelula']['size'];
                    $novonomedafoto		= $_FILES['fotodacelula']['name'].'.'.$extensaodafoto;
                        $fotodacelula		= $pastafoto.$novonomedafoto;
                    
                    $quality_capa = 60;	

                    function compress_image($img_foto, $fotodacelula, $quality_capa) {
                        $info = getimagesize($img_foto);
                        if ($info["mime"] == "image/jpeg") $image = imagecreatefromjpeg($img_foto);
                        elseif ($info["mime"] == "image/gif") $image = imagecreatefromgif($img_foto);
                        elseif ($info["mime"] == "image/png") $image = imagecreatefrompng($img_foto);
                        imagejpeg($image, $fotodacelula, $quality_capa);
                        return $fotodacelula;
                    }
                        
                    $temporariofoto = compress_image($_FILES["fotodacelula"]["tmp_name"], $fotodacelula, $quality_capa); //Compacta a imagem 
                    move_uploaded_file($temporariofoto, $fotodacelula);
                endif;
            endif;

            //$oferta = explode('R$ ', $oferta); $oferta = $oferta[1];

            $descricaooferta = "Oferta da célula: $celuladomembro";

            //Criptografia 1
            $recebidode = base64_encode($celuladomembro);
            $descricaooferta  = base64_encode($descricaooferta);

            //Criptografia 2
            $recebidode = base64_encode($celuladomembro);
            $descricaooferta  = base64_encode($descricaooferta);

            
            //Buscar id anterior para evitar código duplicado.
            $biddupli="SELECT id FROM financeiro";
            $riddupli=mysqli_query($con, $biddupli);
                $diddupli=mysqli_num_rows($riddupli);
                
            $ultimoid = $diddupli;

            $codigodaoperacaoo = $ultimoid.mt_rand(1000, 19999);

            for ($i=0; $i < count($_POST['membro']); $i++) { 
                $nomedomembropresente=mysqli_escape_string($con, $_POST['membro'][$i]);
                $matriculamembroC=mysqli_escape_string($con, $_POST['matriculamembroC'][$i]);
                $levouvisitantes=mysqli_escape_string($con, $_POST['nmembro'][$i]);
                $presentenacelula=mysqli_escape_string($con, $_POST["Mp_$matriculamembroC"]);

                $inRelatorio = "INSERT INTO relatorio (dia, mes, ano, referencia, foto, datahora, tipoderelatorio, celula, matriculadacelula, rede, lider, matricula, relator, matricularelator, participantes, visitantes, homens, mulheres, kids, oferta, datadoencontro, houvedinamica, anotacoes, trocadelideres, membro, matriculadomembro, presenca, trouxevisitantes, horarioinicio, horariotermino) VALUES ('$dia', '$mesnumero', '$anoatual', '$referenciadorelatorio', '$fotodacelula', '$datadoencontro', 'RELATÓRIO DE CÉLULA', '$celuladomembro', '$matriculadacelula', '$rededacelula', '$liderdacelula', '$matriculadolider', '$nomedousuario', '$matricula', '$participantes', '$visitantes', '$homenspresentes', '$mulherespresentes', '$kidspresentes', '$oferta', '$datadoencontro', '$dinamica', '$descricao', '', '$nomedomembropresente', '$matriculamembroC', '$presentenacelula', '$levouvisitantes', '$horarioinicio', '$horariotermino')";

                if(mysqli_query($con, $inRelatorio)):
                    $registraroferta=true;
                    $_SESSION['mensagem']="Relatório enviado.";
                    $_SESSION['cordamensagem']='green';
                else:
                    $_SESSION['mensagem']="Erro ao enviar relatório";
                    $_SESSION['cordamensagem']='red';
                endif;
            }

            if($registraroferta):
                //Registrar arrecadaçao financeira.
                $inserir="INSERT INTO financeiro (empresa, codigodoparceiro, urldaempresa, dia, mes, ano, representante, codigodorepresentante, codigodaoperacao, descricao, valorprevisto, dataprevista, valorrealizado, datarealizada, recebidode, pagopor, anexo, tamanhodoarquivo, tipodeoperacao, categoria, centrodecusto, conta1, conta2, tipoderegistro) VALUES ('Emanuel', '0001', 'igrejaemanuel', '$dia', '$mesnumero', '$ano', '$nomedousuario', '$matriculausuario', '$codigodaoperacaoo', '$descricaooferta', '$oferta', '$datadoencontro', '', '', '$recebidode', '', '', '', '', 'Oferta', '', '0000', '', 'CRÉDITO')"; 
                mysqli_query($con, $inserir);
                $registraroferta=false;
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
                                                        <h1 class="mb-0">Relatório de <strong>célula</strong></h1>
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
                                        <form class="form" action="<?echo $_SERVER['REQUEST_URI'];?>" charset="UTF-8" method="post" enctype="Multipart/form-data">
                                            <div class="row">
                                                <div class="column width-12">
                                                    <span class="color-black text-xsmall">Foto do dia da célula</span>
                                                    <div class="field-wrapper">
                                                        <input type="file" name="fotodacelula" class="form-aux form-date form-element small" placeholder="portfolio" tabindex="5">
                                                    </div>
                                                </div>
                                                <div class="column width-3">
                                                    <span class="color-black"><strong>PARTICIPANTES</strong></span>
                                                    <div class="field-wrapper">
                                                        <input type="number"  name="participantes" class="form-aux form-date form-element small"  tabindex="5">
                                                    </div>
                                                </div>
                                                <div class="column width-3">
                                                    <span class="color-black"><strong>VISITANTES</strong></span>
                                                    <div class="field-wrapper">
                                                        <input type="number"  name="visitantes" class="form-aux form-date form-element small"  tabindex="5">
                                                    </div>
                                                </div>
                                                <div class="column width-3">
                                                    <span class="color-black"><strong>HOMENS</strong> PRESENTES</span>
                                                    <div class="field-wrapper">
                                                        <input type="number"  name="homenspresentes" class="form-aux form-date form-element small"  tabindex="5">
                                                    </div>
                                                </div>
                                                <div class="column width-3">
                                                    <span class="color-black"><strong>MULHERES</strong> PRESENTES</span>
                                                    <div class="field-wrapper">
                                                        <input type="number"  name="mulherespresentes" class="form-aux form-date form-element small"  tabindex="5">
                                                    </div>
                                                </div>
                                                <div class="column width-3">
                                                    <span class="color-black"><strong>Crianças</strong> PRESENTES</span>
                                                    <div class="field-wrapper">
                                                        <input type="number"  name="kids" class="form-aux form-date form-element small"  tabindex="5">
                                                    </div>
                                                </div>
                                                <?
                                                    $ministerioArray=explode(',', $ministerio);
                                                    if(in_array('Tesoureiro de célula', $ministerioArray) OR in_array('Tesoureira de célula', $ministerioArray) OR in_array('Líder de célula', $ministerioArray)):
                                                        $tmwi='3';
                                                ?>
                                                <div class="column width-3">
                                                    <span class="color-black"><strong>OFERTA</strong> (R$)</span>
                                                    <div class="field-wrapper">
                                                        <input type="text"  name="oferta" class="form-aux form-date form-element small"  tabindex="5">
                                                    </div>
                                                </div>
                                                <?
                                                    else:
                                                        $tmwi='6';
                                                        echo "<input type='hidden' name='oferta' value=''/>";
                                                    endif;
                                                ?>
                                                <div class="column width-<? echo $tmwi; ?>">
                                                    <span class="color-black"><strong>DATA DO ENCONTRO</strong></span>
                                                    <div class="field-wrapper">
                                                        <input type="date"  name="datadoencontro" value="<?php echo date(('Y-m-d'), strtotime($UConceito . '+ 1 days'));?>" class="form-aux form-date form-element small"  tabindex="5">
                                                    </div>
                                                </div>
                                                <div class="column width-3">
                                                    <span class="color-black">HOUVE <strong>DINÂMICA</strong>?</span>
                                                    <div class="form-select form-element small">
                                                        <select name="dinamica" tabindex="32" class="form-aux" data-label="sexo">
                                                            <option value="1">SIM</option>
                                                            <option value="0">NÃO</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="column width-3">
                                                    <span class="color-black">HORÁRIO DE <strong>INICIO</strong></span>
                                                    <div class="field-wrapper">
                                                        <input type="hour"  name="horarioinicio" value="<?php echo date('H:i');?>" class="form-aux form-date form-element small"  tabindex="5">
                                                    </div>
                                                </div>
                                                <div class="column width-3">
                                                    <span class="color-black">HORÁRIO DE <strong>TÉRMINO</strong></span>
                                                    <div class="field-wrapper">
                                                        <input type="hour"  name="horariotermino" value="<?php echo date('H:i');?>" class="form-aux form-date form-element small"  tabindex="5">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="column width-12">
                                                    <span class="color-black">Descrição de como foi a célula</span>
                                                    <div class="field-wrapper">
                                                        <textarea type="text"  name="descricao" class="form-aux form-date form-element small"  tabindex="5"></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <hr class="border-blue">
                                                    <div class="column width-7">
                                                        <span class="color-black weight-bold">Membro da célula</span>
                                                    </div>
                                                    <div class="column width-7">
                                                        <span class="color-black weight-bold">Presença</span>
                                                    </div>
                                                    <div class="column width-3">
                                                        <span class="color-black weight-bold">Visitantes que ele trouxe</span>
                                                    </div>
                                                    
                                                    <div class="clear pt-40"></div>   
                                                    <?
                                                        $bMembros="SELECT nome, sobrenome, matricula FROM membros WHERE pertenceaqualcelula='$celuladomembro' AND matriculadacelula='$matriculadacelula'";
                                                          $rM=mysqli_query($con, $bMembros);
                                                            while($dM=mysqli_fetch_array($rM)):
                                                    ?>
                                                    <div class="column width-7">
                                                        <span class="color-black">Membro da célula</span>
                                                        <div class="field-wrapper">
                                                            <input type="hidden"  name="matriculamembroC[]" value="<?echo $dM['matricula'];?>" class="form-aux form-date form-element small"  tabindex="5">

                                                            <input type="text"  name="membro[]" readonly value="<?echo base64_decode(base64_decode($dM['nome'])).' '.base64_decode(base64_decode($dM['sobrenome']));?>" class="form-aux form-date form-element small"  tabindex="5">
                                                        </div>
                                                    </div>
                                                    <div class="column width-2">
                                                        <span class="color-black">Presença</span>
                                                        <div class="field-wrapper pt-10 pb-10 left">
                                                            <input tabindex="29<? echo $tb;?>" id="Mp_<?echo $dM['matricula'];?>" class="radio" name="Mp_<?echo $dM['matricula'];?>" value="1" type="radio" checked>
                                                            <label for="Mp_<?echo $dM['matricula'];?>" class="radio-label">P</label>
                                                            <input tabindex="30<? echo $tb;?>" id="Mp_2_<?echo $dM['matricula'];?>" class="radio" name="Mp_<?echo $dM['matricula'];?>" value="0" type="radio">
                                                            <label for="Mp_2_<?echo $dM['matricula'];?>" class="radio-label">F</label>
                                                        </div>
                                                    </div>
                                                    <div class="column width-3">
                                                        <span class="color-black">Visitantes que ele trouxe</span>
                                                        <div class="field-wrapper">
                                                            <input type="number" name="nmembro[]" value="" class="form-aux form-date form-element small" tabindex="5">
                                                        </div>
                                                    </div>
                                                    <?
                                                        endwhile;
                                                    ?>
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