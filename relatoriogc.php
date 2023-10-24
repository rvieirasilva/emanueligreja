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
        
        $VerificarMembro="SELECT id, nome, sobrenome, descricaodomembro, nascimento, email, sexo, igreja, frequentaaemanueldesde, ministerio, funcaoadministrativa, rededepartamento, statusdopagamento, matricula, validaate, statusdomembro, uf, cidade, pai, mae, estadocivil, grauescolar, formadoem, pertenceaqualcelula, matriculadacelula, datadobatismo, motivo, fotodeperfil, telefonemovel, whatsapp, cpf, rg, formadoem, endereco, cep, pisosalarial, midiasocial1, link1, midiasocial2, link2, midiasocial3, link3, midiasocial4, link4, qrcode, membrodocampus, codigodocampus FROM membros WHERE cpf='$cpfVerify'";
          $rVC=mysqli_query($con, $VerificarMembro);
            $nVC=mysqli_num_rows($rVC);
              $dVM=mysqli_fetch_array($rVC);
        
        if($nVC > 0):
            $nomeusuario                    = $dVM['nome'];
            $sobrenomeusuario               = $dVM['sobrenome'];
            
            $nomeusuario           = base64_decode($dVM['nome']);
                $nomeusuario       = base64_decode($nomeusuario);
            
            $sobrenomeusuario           = base64_decode($dVM['sobrenome']);
                $sobrenomeusuario       = base64_decode($sobrenomeusuario);
                
            $nomedousuario                  = $nomeusuario.' '.$sobrenomeusuario;
            $emailusuario                   = $dVM['email'];
            $sexo                           = $dVM['sexo'];
            $nascimento                     = $dVM['nascimento'];
            $igrejausuario                  = $dVM['igreja'];
            $cargoministerial               = $dVM['ministerio'];
            $ministerio                     = $dVM['ministerio'];
            $formadoem                      = $dVM['formadoem'];
            $funcaoadministrativa           = $dVM['funcaoadministrativa'];
            $rededepartamento               = $dVM['rededepartamento'];
            $statusdopagamentousuario       = $dVM['statusdopagamento'];
            $statusdomembro                 = $dVM['statusdomembro'];
            $matricula                      = $dVM['matricula'];
            $matriculausuario               = $dVM['matricula'];
            $matriculadousuario             = $dVM['matricula'];
            $estadodomembro                 = $dVM['uf'];
            $cidadedomembro                 = $dVM['cidade'];
            $celuladomembro                 = $dVM['pertenceaqualcelula'];
            $matriculadacelula              = $dVM['matriculadacelula'];
            $fotodeperfil                   = $dVM['fotodeperfil'];
            $qrcode                         = $dVM['qrcode'];
            $membrodocampus                         = $dVM['membrodocampus'];
            $codigodocampus                         = $dVM['codigodocampus'];
            $membrodesde                    = date(('d/m/y'), strtotime($dVM['frequentaaemanueldesde']));
            $validaate                    = date(('d/m/y'), strtotime($dVM['validaate']));
            // $validaate                    = $dVM['validaate'];
            
            
            $telefonemovelusuario           = base64_decode($dVM['telefonemovel']);
                $telefonemovelusuario       = base64_decode($telefonemovelusuario);
                
            $whatsappdomembro           = base64_decode($dVM['whatsapp']);
                $whatsappdomembro       = base64_decode($whatsappdomembro);

            $cpfdomembro           = base64_decode($dVM['cpf']);
                $cpfdomembro       = base64_decode($cpfdomembro);
                
            $rgdomembro            = base64_decode($dVM['rg']);
                $rgdomembro        = base64_decode($rgdomembro);

            $enderecodomembro            = base64_decode($dVM['endereco']);
                $enderecodomembro        = base64_decode($enderecodomembro);

            $cepdomembro            = base64_decode($dVM['cep']);
                $cepdomembro        = base64_decode($cepdomembro);

            $pisosalarialdomembro            = base64_decode($dVM['pisosalarial']);
                $pisosalarialdomembro        = base64_decode($pisosalarialdomembro);

            $link1domembro            = base64_decode($dVM['link1']);
                $link1domembro        = base64_decode($link1domembro);

            $link2domembro            = base64_decode($dVM['link2']);
                $link2domembro        = base64_decode($link2domembro);

            $link3domembro            = base64_decode($dVM['link3']);
                $link3domembro        = base64_decode($link3domembro);

            $link4domembro            = base64_decode($dVM['link4']);
                $link4domembro        = base64_decode($link4domembro);
                
            $_SESSION['nomeusuario']=$nomeusuario;
            $_SESSION['sobrenomeusuario']=$sobrenomeusuario;
            $_SESSION['nomedousuario']=$nomedousuario;
            $_SESSION['emailusuario']=$emailusuario;
            $_SESSION['sexo']=$sexo;
            $_SESSION['nascimento']=$nascimento;
            $_SESSION['igrejausuario']=$igrejausuario;
            $_SESSION['cargoministerial']=$cargoministerial;
            $_SESSION['ministerio']=$ministerio;
            $_SESSION['formadoem']=$formadoem;
            $_SESSION['funcaoadministrativa']=$funcaoadministrativa;
            $_SESSION['rededepartamento']=$rededepartamento;
            $_SESSION['statusdopagamentousuario']=$statusdopagamentousuario;
            $_SESSION['statusdomembro']=$statusdomembro;
            $_SESSION['matricula']=$matricula;
            $_SESSION['estadodomembro']=$estadodomembro;
            $_SESSION['cidadedomembro']=$cidadedomembro;
            $_SESSION['celuladomembro']=$celuladomembro;
            $_SESSION['matriculadacelula']=$matriculadacelula;
            $_SESSION['fotodeperfil']=$fotodeperfil;
            $_SESSION['qrcode']=$qrcode;
            $_SESSION['membrodocampus']=$membrodocampus;
            $_SESSION['codigodocampus']=$codigodocampus;
            $_SESSION['membrodesde']=$membrodesde;
            $_SESSION['validaate']=$validaate;
            $_SESSION['telefonemovelusuario']=$telefonemovelusuario;
            $_SESSION['whatsappdomembro']=$whatsappdomembro;
            $_SESSION['cpfdomembro']=$cpfdomembro;
            $_SESSION['rgdomembro']=$rgdomembro;
            $_SESSION['enderecodomembro']=$enderecodomembro;
            $_SESSION['cepdomembro']=$cepdomembro;
            $_SESSION['pisosalarialdomembro']=$pisosalarialdomembro;
            $_SESSION['link1domembro']=$link1domembro;
            $_SESSION['link2domembro']=$link2domembro;
            $_SESSION['link3domembro']=$link3domembro;
            $_SESSION['link4domembro    ']=$link4domembro;

            $nomeusuario=$_SESSION['nomeusuario'];
            $sobrenomeusuario=$_SESSION['sobrenomeusuario'];
            $nomedousuario=$_SESSION['nomedousuario'];
            $emailusuario=$_SESSION['emailusuario'];
            $sexo=$_SESSION['sexo'];
            $nascimento=$_SESSION['nascimento'];
            $igrejausuario=$_SESSION['igrejausuario'];
            $cargoministerial=$_SESSION['cargoministerial'];
            $ministerio=$_SESSION['ministerio'];
            $formadoem=$_SESSION['formadoem'];
            $funcaoadministrativa=$_SESSION['funcaoadministrativa'];
            $rededepartamento=$_SESSION['rededepartamento'];
            $statusdopagamentousuario=$_SESSION['statusdopagamentousuario'];
            $statusdomembro=$_SESSION['statusdomembro'];
            $matricula=$_SESSION['matricula'];
            $estadodomembro=$_SESSION['estadodomembro'];
            $cidadedomembro=$_SESSION['cidadedomembro'];
            $celuladomembro=$_SESSION['celuladomembro'];
            $matriculadacelula=$_SESSION['matriculadacelula'];
            $fotodeperfil=$_SESSION['fotodeperfil'];
            $qrcode=$_SESSION['qrcode'];
            $membrodocampus=$_SESSION['membrodocampus'];
            $codigodocampus=$_SESSION['codigodocampus'];
            $membrodesde=$_SESSION['membrodesde'];
            $validaate=$_SESSION['validaate'];
            $telefonemovelusuario=$_SESSION['telefonemovelusuario'];
            $whatsappdomembro=$_SESSION['whatsappdomembro'];
            $cpfdomembro=$_SESSION['cpfdomembro'];
            $rgdomembro=$_SESSION['rgdomembro'];
            $enderecodomembro=$_SESSION['enderecodomembro'];
            $cepdomembro=$_SESSION['cepdomembro'];
            $pisosalarialdomembro=$_SESSION['pisosalarialdomembro'];
            $link1domembro=$_SESSION['link1domembro'];
            $link2domembro=$_SESSION['link2domembro'];
            $link3domembro=$_SESSION['link3domembro'];
            $link4domembro=$_SESSION['link4domembro'];
                
            $_SESSION['relatoriodomembro']=true;
            $_SESSION['cpfrelatoriorapido']=$cpfVerify;
        else:
            $_SESSION['cordamensagem']="red";
            $_SESSION['mensagem']="Está ação só pode ser realizada por um membro cadastrado, sendo membro da Emanuel; <a href='./entrar'>cadastre-se</a>";
        endif;
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
		$kidspresentes				    = mysqli_escape_string($con, $_POST['kidspresentes']);
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
                session_unset();
                session_destroy();
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
                                                    <h1 class="mb-0">Enviar relatório do <strong>GC</strong>.</h1>
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
                                    <div class="register-form-container">
                                        <form class="login-form" action="<? echo $_SERVER['REQUEST_URI'];?>" method="post" charset="UTF-8">
                                            <div class="row">
                                                <? if(!isset($_SESSION['relatoriodomembro'])): $btn="btn-verificar";?>
                                                <h3 class="color-blue"><strong>Relatório rápido de GC</strong>.</h3>
                                                <div class="column width-12">
                                                    <div class="field-wrapper">
                                                        <input type="password" name="cpf" class="form-element medium rounded login-form-textfield" placeholder="Seu CPF" required>
                                                    </div>
                                                </div>
                                                <div class="row no-margins">
                                                    <div class="column width-6">
                                                        <button type="submit" value="" name="<? echo $btn; ?>" class="button small rounded bkg-blue bkg-hover-blue hard-shadow"><span class="text-medium color-white color-hover-white weight-bold">Enviar</span>
                                                    </div>
                                                </div>

                                                <?
                                                    else:
                                                ?>
                                                <div class="column width-12 pb-20">
                                                    <div class="column full-width bkg-base box medium rounded border-blue"><a href="./logout"><strong>Sair</strong> do relatório <strong>rápido</strong> do GC.</a>
                                                    </div>
                                                </div>
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
                                                <?
                                                    endif;
                                                ?>
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