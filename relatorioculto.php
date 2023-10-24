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

            $_SESSION['relatoriodomembro']=true;
            $_SESSION['cpfrelatoriorapido']=$cpfVerify;
        else:
            $_SESSION['cordamensagem']="red";
            $_SESSION['mensagem']="Está ação só pode ser realizada por um membro cadastrado, sendo membro da Emanuel; <a href='./entrar'>cadastre-se</a>";
        endif;
    endif;

    if(isset($_SESSION['relatoriodomembro'])):
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
    endif;
                
    
	if(isset($_POST['btn-finalizar'])):
        $bID="SELECT id FROM relatoriodeculto ORDER BY id DESC LIMIT 1";
          $rID=mysqli_query($con, $bID);
            $dID=mysqli_fetch_array($rID);
        $lastID=$dID['id'];

        $codigodorelatorio=$lastID.mt_rand(0000,9999);

        $participantes=mysqli_escape_string($con, $_POST['participantes']);
        $visitantes=mysqli_escape_string($con, $_POST['visitantes']);
        $homenspresentes=mysqli_escape_string($con, $_POST['homenspresentes']);
        $mulherespresentes=mysqli_escape_string($con, $_POST['mulherespresentes']);
        $kids=mysqli_escape_string($con, $_POST['kids']);
        $tipodeculto=mysqli_escape_string($con, $_POST['tipodeculto']);
        $datadoculto=mysqli_escape_string($con, $_POST['datadoculto']);
        $conversao=mysqli_escape_string($con, $_POST['conversao']);
        $nconvertidos=mysqli_escape_string($con, $_POST['nconvertidos']);
        $live=mysqli_escape_string($con, $_POST['live']);
        $canaldalive=mysqli_escape_string($con, $_POST['canaldalive']);
        $aguanacaixa=mysqli_escape_string($con, $_POST['aguanacaixa']);
        $aguanobebedouro=mysqli_escape_string($con, $_POST['aguanobebedouro']);
        $copos=mysqli_escape_string($con, $_POST['copos']);
        $papelhigienico=mysqli_escape_string($con, $_POST['papelhigienico']);
        $papelmao=mysqli_escape_string($con, $_POST['papelmao']);
        $lanchekids=mysqli_escape_string($con, $_POST['lanchekids']);
        $igrejalimpa=mysqli_escape_string($con, $_POST['igrejalimpa']);
        $qualidadesom=mysqli_escape_string($con, $_POST['qualidadesom']);
        $chuva=mysqli_escape_string($con, $_POST['chuva']);
        $pregador=mysqli_escape_string($con, $_POST['pregador']); $pregador=explode(" ", $pregador); $pregador= $pregador[0].' '.$pregador[1];
        $pregadordefora=mysqli_escape_string($con, $_POST['pregadordefora']);
        $horarioinicio=mysqli_escape_string($con, $_POST['horarioinicio']);
        $horariotermino=mysqli_escape_string($con, $_POST['horariotermino']);
        $descricao=mysqli_escape_string($con, $_POST['descricao']);

        if(empty($visitantes)): $visitantes = '0'; endif;
        if(empty($canaldalive)): $canaldalive = 'Sem live'; endif;
        
        if(!empty($pregadordefora)):
            $pregador=$pregadordefora;
        endif;

        //Insercao geral
        $inRel="INSERT INTO relatoriodeculto (dia, mes, ano, codigodorelatorio, participantes, visitantes, homenspresentes, mulherespresentes, kids, tipodeculto, datadoculto, conversao, nconvertidos, live, canaldalive, aguanacaixa, aguanobebedouro, copos, papelhigienico, papelmao, lanchekids, igrejalimpa, qualidadesom, chuva, pregador, horarioinicio, horariotermino, descricao, ministro, matriculadoministro, trouxevisitantes, presencanaoracao, funcoesministeriais, ministrodocampus, codigodocampus, relator, matriculadorelator, datadorelatorio, horadorelatorio, ipdorelatorio, campusrelator) VALUES ('$dia', '$mesnumero', '$ano', '$codigodorelatorio', '$participantes', '$visitantes', '$homenspresentes', '$mulherespresentes', '$kids', '$tipodeculto', '$datadoculto', '$conversao', '$nconvertidos', '$live', '$canaldalive', '$aguanacaixa', '$aguanobebedouro', '$copos', '$papelhigienico', '$papelmao', '$lanchekids', '$igrejalimpa', '$qualidadesom', '$chuva', '$pregador', '$horarioinicio', '$horariotermino', '$descricao', '', '', '', '', '', '', '', '$nomeusuario', '$matricula', '$dataeng', '$hora', '$ip', '$membrodocampus')";
        if(mysqli_query($con, $inRel)):
            for ($minis=0; $minis < count($_POST['matriculamembroC']); $minis++) { 
                $matriculadoMinistro=mysqli_escape_string($con, $_POST['matriculamembroC'][$minis]);
                $ministro=mysqli_escape_string($con, $_POST['membro'][$minis]);
                $presentenaOracao=mysqli_escape_string($con, $_POST["Mp_$matriculadoMinistro"]);
                $trouxevisitantes=mysqli_escape_string($con, $_POST['nmembro'][$minis]);
                $FuncoesCumpridas=mysqli_escape_string($con, $_POST["fc$matriculadoMinistro"]);

                $bcampus="SELECT membrodocampus, codigodocampus FROM membros WHERE matricula='$matriculadoMinistro'";
                $rC=mysqli_query($con, $bcampus);
                    $dC=mysqli_fetch_array($rC);
                
                $ministrodoCampus=$dC['membrodocampus'];
                $codigodoCampus=$dC['codigodocampus'];

                //Insercao de cada ministro
                $inRelMINISTERIO="INSERT INTO relatoriodeculto (dia, mes, ano, codigodorelatorio, participantes, visitantes, homenspresentes, mulherespresentes, kids, tipodeculto, datadoculto, conversao, nconvertidos, live, canaldalive, aguanacaixa, aguanobebedouro, copos, papelhigienico, papelmao, lanchekids, igrejalimpa, qualidadesom, chuva, pregador, horarioinicio, horariotermino, descricao, ministro, matriculadoministro, trouxevisitantes, presencanaoracao, funcoesministeriais, ministrodocampus, codigodocampus, relator, matriculadorelator, datadorelatorio, horadorelatorio, ipdorelatorio, campusrelator) VALUES ('$dia', '$mesnumero', '$ano', '$codigodorelatorio', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '$ministro', '$matriculadoMinistro', '$trouxevisitantes', '$presentenaOracao', '$FuncoesCumpridas', '$ministrodoCampus', '$codigodoCampus', '', '', '', '', '', '')";
                if(mysqli_query($con, $inRelMINISTERIO)):
                    $_SESSION['cordamensagem']="green";
                    $_SESSION['mensagem']="Relatório enviado com sucesso.";
                else:
                    $_SESSION['cordamensagem']="red";
                    $_SESSION['mensagem']="Não foi possível inserir o relatório geral";
                endif;
            }
            session_unset();
        else:
            $_SESSION['cordamensagem']="red";
            $_SESSION['mensagem']="Não foi possível inserir o relatório geral";
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
                                                    <h1 class="mb-0">Enviar relatório do <strong>culto</strong>.</h1>
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
                    <div class="column full-width bkg-grey-ultralight">
                        <?php
                            include "./_notificacaomensagem.php";
                        ?>
                        <!-- <div class="box rounded bkg-white shadow"> -->
						<!-- <div class="column full-width"> -->
                            <div class="box rounded medium bkg-white border-blue">
                                <div class="">
                                    <form class="form" action="<?echo $_SERVER['REQUEST_URI'];?>" charset="UTF-8" method="post" enctype="Multipart/form-data">
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
                                            <? else:?>
                                            <div class="column width-2">
                                                <span class="color-black"><strong>PARTICIPANTES</strong></span>
                                                <div class="field-wrapper">
                                                    <input type="number"  name="participantes" value='0' class="form-aux form-date form-element medium"  tabindex="002">
                                                </div>
                                            </div>
                                            <div class="column width-2">
                                                <span class="color-black"><strong>VISITANTES</strong></span>
                                                <div class="field-wrapper">
                                                    <input type="number"  name="visitantes" value='0' class="form-aux form-date form-element medium"  tabindex="003">
                                                </div>
                                            </div>
                                            <div class="column width-2">
                                                <span class="color-black"><strong>HOMENS</strong></span>
                                                <div class="field-wrapper">
                                                    <input type="number"  name="homenspresentes" value='0' class="form-aux form-date form-element medium"  tabindex="004">
                                                </div>
                                            </div>
                                            <div class="column width-2">
                                                <span class="color-black"><strong>MULHERES</strong></span>
                                                <div class="field-wrapper">
                                                    <input type="number"  name="mulherespresentes" value='0' class="form-aux form-date form-element medium"  tabindex="005">
                                                </div>
                                            </div>
                                            <div class="column width-2">
                                                <span class="color-black"><strong>Crianças</strong></span>
                                                <div class="field-wrapper">
                                                    <input type="number"  name="kids" value='0' class="form-aux form-date form-element medium"  tabindex="006">
                                                </div>
                                            </div>
                                            <div class="column width-2">
                                                <span class="color-black"><strong>EVENTO</strong>?</span>
                                                <div class="form-select form-element medium">
                                                    <select name="tipodeculto" tabindex="007" class="form-aux" data-label="sexo">
                                                        <option value="0">NÃO</option>
                                                        <option value="1">SIM</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="column width-2">
                                                <span class="color-black"><strong>DATA DO CULTO</strong></span>
                                                <div class="field-wrapper">
                                                    <input type="date"  name="datadoculto" value="<?php echo date('Y-m-d');?>" class="form-aux form-date form-element medium"  tabindex="008">
                                                </div>
                                            </div>
                                            <div class="column width-2">
                                                <span class="color-black"><strong>CONVERSÃO</strong></span>
                                                <div class="form-select form-element medium">
                                                    <select name="conversao" tabindex="009" class="form-aux" data-label="sexo">
                                                        <option value="0">NÃO</option>
                                                        <option value="1">SIM</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="column width-2">
                                                <span class="color-black"><strong>LIVE</strong></span>
                                                <div class="form-select form-element medium">
                                                    <select name="live" tabindex="010" class="form-aux" data-label="sexo">
                                                        <option value="1">SIM</option>
                                                        <option value="0">NÃO</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="column width-2">
                                                <span class="color-black"><strong>CANAL</strong></span>
                                                <div class="form-select form-element medium">
                                                    <select name="canaldalive" tabindex="011" class="form-aux" data-label="sexo">
                                                        <option>YouTube</option>
                                                        <option>Facebook</option>
                                                        <option>Instagram</option>
                                                        <option>TikTok</option>
                                                        <option>Twister</option>
                                                        <option>Site</option>
                                                        <option>Outro</option>
                                                        <option value="Sem live">Nenhum</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="column width-2">
                                                <span class="color-black"><strong>Água</strong> na caixa</span>
                                                <div class="form-select form-element medium">
                                                    <select name="aguanacaixa" tabindex="012" class="form-aux" data-label="sexo">
                                                        <option value="1">SIM</option>
                                                        <option value="0">NÃO</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="column width-2">
                                                <span class="color-black"><strong>Água</strong> na bebedouro</span>
                                                <div class="form-select form-element medium">
                                                    <select name="aguanobebedouro" tabindex="013" class="form-aux" data-label="sexo">
                                                        <option value="1">SIM</option>
                                                        <option value="0">NÃO</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="column width-2">
                                                <span class="color-black"><strong>Copos</strong></span>
                                                <div class="form-select form-element medium">
                                                    <select name="copos" tabindex="014" class="form-aux" data-label="sexo">
                                                        <option value="1">SIM</option>
                                                        <option value="0">NÃO</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="column width-2">
                                                <span class="color-black"><strong>Papel</strong> higiênico</span>
                                                <div class="form-select form-element medium">
                                                    <select name="papelhigienico" tabindex="015" class="form-aux" data-label="sexo">
                                                        <option value="1">SIM</option>
                                                        <option value="0">NÃO</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="column width-2">
                                                <span class="color-black"><strong>Papel</strong> de mão</span>
                                                <div class="form-select form-element medium">
                                                    <select name="papelmao" tabindex="016" class="form-aux" data-label="sexo">
                                                        <option value="1">SIM</option>
                                                        <option value="0">NÃO</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="column width-2">
                                                <span class="color-black"><strong>Lanche</strong> p/ Kids</span>
                                                <div class="form-select form-element medium">
                                                    <select name="lanchekids" tabindex="017" class="form-aux" data-label="sexo">
                                                        <option value="1">SIM</option>
                                                        <option value="0">NÃO</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="column width-2">
                                                <span class="color-black">Igreja <strong>limpa</strong></span>
                                                <div class="form-select form-element medium">
                                                    <select name="igrejalimpa" tabindex="018" class="form-aux" data-label="sexo">
                                                        <option value="1">SIM</option>
                                                        <option value="0">NÃO</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="column width-2">
                                                <span class="color-black">Qualidade do <strong>som</strong></span>
                                                <div class="form-select form-element medium">
                                                    <select name="qualidadesom" tabindex="019" class="form-aux" data-label="sexo">
                                                        <option value="1">Boa</option>
                                                        <option value="0">Ruim</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="column width-2">
                                                <span class="color-black"><strong>Choveu</strong></span>
                                                <div class="form-select form-element medium">
                                                    <select name="chuva" tabindex="020" class="form-aux" data-label="sexo">
                                                        <option value="1">Sim</option>
                                                        <option value="0">Não</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="column width-2">
                                                <span class="color-black"><strong>Pregador</strong></span>
                                                <div class="form-select form-element medium">
                                                    <select name="pregador" tabindex="021" class="form-aux" data-label="sexo">
                                                        <?
                                                            $bMP="SELECT nome, sobrenome, matricula FROM membros WHERE NOT (matricula ='') AND statusdomembro ='Ativo' AND pertenceaqualcelula !='' GROUP BY matricula ORDER BY nome ASC";
                                                            $rMP=mysqli_query($con, $bMP);
                                                                while($dMP=mysqli_fetch_array($rMP)):
                                                        ?>
                                                        <option value="<? echo base64_decode(base64_decode($dMP['nome'])).' '.base64_decode(base64_decode($dMP['sobrenome'])).'~'.$dMP['matricula'];?>"><? echo base64_decode(base64_decode($dMP['nome'])).' '.base64_decode(base64_decode($dMP['sobrenome']))?></option>
                                                        <? endwhile; ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="column width-2">
                                                <span class="color-black">Outro <strong>Pregador</strong></span>
                                                <div class="field-wrapper">
                                                    <input type="text"  name="pregadordefora" class="form-aux form-date form-element medium"  tabindex="022">
                                                </div>
                                            </div>
                                            <div class="column width-2">
                                                <span class="color-black">Nº <strong>CONVERTERAM-SE</strong>?</span>
                                                <div class="field-wrapper">
                                                    <input type="number"  name="nconvertidos" value='0' class="form-aux form-date form-element medium"  tabindex="023">
                                                </div>
                                            </div>
                                            <div class="column width-2">
                                                <span class="color-black">HORÁRIO DE <strong>INICIO</strong></span>
                                                <div class="field-wrapper">
                                                    <input type="hour"  name="horarioinicio" value="<?php echo date('H:i');?>" class="form-aux form-date form-element medium"  tabindex="024">
                                                </div>
                                            </div>
                                            <div class="column width-2">
                                                <span class="color-black">HORÁRIO DE <strong>TÉRMINO</strong></span>
                                                <div class="field-wrapper">
                                                    <input type="hour"  name="horariotermino" value="<?php echo date('H:i');?>" class="form-aux form-date form-element medium"  tabindex="025">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="column width-12">
                                                <span class="color-black">Descrição de como foi o culto</span>
                                                <div class="field-wrapper">
                                                    <textarea type="text" name="descricao" class="form-aux form-date form-element medium"  tabindex="026"></textarea>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <hr class="border-blue">
                                                <div class="column width-4">
                                                    <span class="color-black weight-bold">Ministério</span>
                                                </div>
                                                <div class="column width-2">
                                                    <span class="color-black weight-bold">Presença na Oração</span>
                                                </div>
                                                <div class="column width-2">
                                                    <span class="color-black weight-bold">Visitantes</span>
                                                </div>
                                                <div class="column width-3">
                                                    <span class="color-black weight-bold">Cumpriu suas <strong>funções no culto</strong>?</span>
                                                </div>
                                                
                                                <div class="clear pt-20"></div>      
                                                <?
                                                    $bMembros="SELECT nome, sobrenome, matricula FROM membros WHERE NOT (ministerio ='') AND pertenceaqualcelula !=''";
                                                        $rM=mysqli_query($con, $bMembros);
                                                        $tb=0;
                                                        while($dM=mysqli_fetch_array($rM)):
                                                            $tb++;
                                                ?>
                                                <div class="box rounded border-blue small shadow">
                                                    <div class="column width-4">
                                                        <span class="color-black"></span>
                                                        <div class="field-wrapper">
                                                            <input type="hidden"  name="matriculamembroC[]" value="<?echo $dM['matricula'];?>" class="form-aux form-date form-element medium"  tabindex="27<? echo $tb;?>">

                                                            <input type="text"  name="membro[]" readonly value="<?echo base64_decode(base64_decode($dM['nome'])).' '.base64_decode(base64_decode($dM['sobrenome']));?>" class="form-aux form-date form-element medium"  tabindex="28<? echo $tb;?>">
                                                        </div>
                                                    </div>
                                                    <div class="column width-2">
                                                        <div class="field-wrapper pt-10 pb-10 left">
                                                            <input tabindex="29<? echo $tb;?>" id="Mp_<?echo $dM['matricula'];?>" class="radio" name="Mp_<?echo $dM['matricula'];?>" value="1" type="radio" checked>
                                                            <label for="Mp_<?echo $dM['matricula'];?>" class="radio-label">P</label>
                                                            <input tabindex="30<? echo $tb;?>" id="Mp_2_<?echo $dM['matricula'];?>" class="radio" name="Mp_<?echo $dM['matricula'];?>" value="0" type="radio">
                                                            <label for="Mp_2_<?echo $dM['matricula'];?>" class="radio-label">F</label>
                                                        </div>
                                                    </div>
                                                    <div class="column width-2">
                                                        <div class="field-wrapper">
                                                            <input type="number" name="nmembro[]" value="0" class="form-aux form-date form-element medium" tabindex="31<? echo $tb;?>">
                                                        </div>
                                                    </div>
                                                    <div class="column width-3">
                                                        <div class="field-wrapper pt-10 pb-10 left">
                                                            <input tabindex="32<? echo $tb;?>" id="fun_<?echo $dM['matricula'];?>" class="radio" name="fc<?echo $dM['matricula'];?>" value="1" type="radio" checked>
                                                            <label for="fun_<?echo $dM['matricula'];?>" class="radio-label small">Sim</label>
                                                            <input tabindex="33<? echo $tb;?>" id="fun_2_<?echo $dM['matricula'];?>" class="radio" name="fc<?echo $dM['matricula'];?>" value="0" type="radio">
                                                            <label for="fun_2_<?echo $dM['matricula'];?>" class="radio-label small">Não</label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <?
                                                    endwhile;
                                                ?>
                                        </div>
                                        <div class="row">
                                            <br>
                                            <div class="column width-12">
                                                <button type="submit" tabindex="100" name="btn-finalizar" class="form-submit button pill small border-theme bkg-hover-theme color-theme color-hover-white">PUBLICAR</button>
                                            </div>
                                        </div>
                                        <? endif;?>
                                    </form>
                                </div>
                            </div>
						<!-- </div> -->
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