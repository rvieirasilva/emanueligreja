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

    $id=$_GET['id'];

	if(isset($_SESSION["lideranca"])):
        include "protectcolaborador.php";	
    elseif(isset($_SESSION["membro"])):
        include "protectusuario.php";
    else:
        session_destroy();
        header("location:index");
	endif;
	
    $bReD="SELECT * FROM relatoriodeculto WHERE id='$id'";
      $rRD=mysqli_query($con, $bReD); $dRD=mysqli_fetch_array($rRD);
      $codigodorelatorio=$dRD['codigodorelatorio'];
      
	if(isset($_POST['btn-finalizar'])):
        $bID="SELECT id FROM relatoriodeculto ORDER BY id DESC LIMIT 1";
          $rID=mysqli_query($con, $bID);
            $dID=mysqli_fetch_array($rID);
        $lastID=$dID['id'];

        // $codigodorelatorio=$lastID.mt_rand(0000,9999);

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
        $inRel="UPDATE relatoriodeculto SET dia='$dia', mes='$mesnumero', ano='$ano', codigodorelatorio='$codigodorelatorio', participantes='$participantes', visitantes='$visitantes', homenspresentes='$homenspresentes', mulherespresentes='$mulherespresentes', kids='$kids', tipodeculto='$tipodeculto', datadoculto='$datadoculto', conversao='$conversao', nconvertidos='$nconvertidos', live='$live', canaldalive='$canaldalive', aguanacaixa='$aguanacaixa', aguanobebedouro='$aguanobebedouro', copos='$copos', papelhigienico='$papelhigienico', papelmao='$papelmao', lanchekids='$lanchekids', igrejalimpa='$igrejalimpa', qualidadesom='$qualidadesom', chuva='$chuva', pregador='$pregador', horarioinicio='$horarioinicio', horariotermino='$horariotermino', descricao='$descricao', relator='$nomeusuario', matriculadorelator='$matricula', datadorelatorio='$dataeng', horadorelatorio='$hora', ipdorelatorio='$ip', campusrelator='$membrodocampus' WHERE id='$id'";
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
                $inRelMINISTERIO="UPDATE relatoriodeculto SET ministro='$ministro', matriculadoministro='$matriculadoMinistro', trouxevisitantes='$trouxevisitantes', presencanaoracao='$presentenaOracao', funcoesministeriais='$FuncoesCumpridas', ministrodocampus='$ministrodoCampus', codigodocampus='$codigodoCampus' WHERE matriculadoministro='$matriculadoMinistro' AND codigodorelatorio='$codigodorelatorio'";
                if(mysqli_query($con, $inRelMINISTERIO)):
                    $_SESSION['cordamensagem']="green";
                    $_SESSION['mensagem']="Relatório enviado com sucesso.";
                else:
                    $_SESSION['cordamensagem']="red";
                    $_SESSION['mensagem']="Não foi possível inserir o relatório geral";
                endif;
            }
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
                                                        <h1 class="mb-0">Editar relatório de <strong>célula</strong></h1>
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
                                                <!-- <div class="column width-12">
                                                    <span class="color-black text-xsmall">Foto do dia da célula</span>
                                                    <div class="field-wrapper">
                                                        <input type="file" value="<? echo $dRD['fotodacelula']; ?>" name="fotodacelula" class="form-aux form-date form-element medium" placeholder="portfolio" tabindex="001">
                                                    </div>
                                                </div> -->
                                                <div class="column width-2">
                                                    <span class="color-black"><strong>PARTICIPANTES</strong></span>
                                                    <div class="field-wrapper">
                                                        <input type="number"  value="<? echo $dRD['participantes']; ?>" name="participantes" value='0' class="form-aux form-date form-element medium"  tabindex="002">
                                                    </div>
                                                </div>
                                                <div class="column width-2">
                                                    <span class="color-black"><strong>VISITANTES</strong></span>
                                                    <div class="field-wrapper">
                                                        <input type="number"  value="<? echo $dRD['visitantes']; ?>" name="visitantes" value='0' class="form-aux form-date form-element medium"  tabindex="003">
                                                    </div>
                                                </div>
                                                <div class="column width-2">
                                                    <span class="color-black"><strong>HOMENS</strong></span>
                                                    <div class="field-wrapper">
                                                        <input type="number"  value="<? echo $dRD['homenspresentes']; ?>" name="homenspresentes" value='0' class="form-aux form-date form-element medium"  tabindex="004">
                                                    </div>
                                                </div>
                                                <div class="column width-2">
                                                    <span class="color-black"><strong>MULHERES</strong></span>
                                                    <div class="field-wrapper">
                                                        <input type="number"  value="<? echo $dRD['mulherespresentes']; ?>" name="mulherespresentes" value='0' class="form-aux form-date form-element medium"  tabindex="005">
                                                    </div>
                                                </div>
                                                <div class="column width-2">
                                                    <span class="color-black"><strong>Crianças</strong></span>
                                                    <div class="field-wrapper">
                                                        <input type="number"  value="<? echo $dRD['kids']; ?>" name="kids" value='0' class="form-aux form-date form-element medium"  tabindex="006">
                                                    </div>
                                                </div>
                                                <div class="column width-2">
                                                    <span class="color-black"><strong>EVENTO</strong>?</span>
                                                    <div class="form-select form-element medium">
                                                        <select name="tipodeculto" tabindex="007" class="form-aux" data-label="sexo">
                                                            <option value="<? echo $dRD['tipodeculto']; ?>"><? if($dRD['tipodeculto'] == 1): echo "SIM"; else: echo "NÃO"; endif;?></option>
                                                            <option value="0">NÃO</option>
                                                            <option value="1">SIM</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="column width-2">
                                                    <span class="color-black"><strong>DATA DO CULTO</strong></span>
                                                    <div class="field-wrapper">
                                                        <input type="date"  value="<? echo $dRD['datadoculto']; ?>" name="datadoculto" value="<?php echo date('Y-m-d');?>" class="form-aux form-date form-element medium"  tabindex="008">
                                                    </div>
                                                </div>
                                                <div class="column width-2">
                                                    <span class="color-black"><strong>CONVERSÃO</strong></span>
                                                    <div class="form-select form-element medium">
                                                        <select name="conversao" tabindex="009" class="form-aux" data-label="sexo">
                                                            <option value="<? echo $dRD['conversao']; ?>"><? if($dRD['conversao'] == 1): echo "SIM"; else: echo "NÃO"; endif;?></option>
                                                            <option value="0">NÃO</option>
                                                            <option value="1">SIM</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="column width-2">
                                                    <span class="color-black"><strong>LIVE</strong></span>
                                                    <div class="form-select form-element medium">
                                                        <select name="live" tabindex="010" class="form-aux" data-label="sexo">
                                                            <option value="<? echo $dRD['live']; ?>"><? if($dRD['live'] == 1): echo "SIM"; else: echo "NÃO"; endif;?></option>
                                                            <option value="1">SIM</option>
                                                            <option value="0">NÃO</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="column width-2">
                                                    <span class="color-black"><strong>CANAL</strong></span>
                                                    <div class="form-select form-element medium">
                                                        <select name="canaldalive" tabindex="011" class="form-aux" data-label="sexo">
                                                            <option><? echo $dRD['canaldalive']; ?></option>
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
                                                            <option value="<? echo $dRD['aguanacaixa']; ?>"><? if($dRD['aguanacaixa'] == 1): echo "SIM"; else: echo "NÃO"; endif;?></option>
                                                            <option value="1">SIM</option>
                                                            <option value="0">NÃO</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="column width-2">
                                                    <span class="color-black"><strong>Água</strong> na bebedouro</span>
                                                    <div class="form-select form-element medium">
                                                        <select name="aguanobebedouro" tabindex="013" class="form-aux" data-label="sexo">
                                                            <option value="<? echo $dRD['aguanobebedouro']; ?>"><? if($dRD['aguanobebedouro'] == 1): echo "SIM"; else: echo "NÃO"; endif;?></option>
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
                                                            <option value="<? echo $dRD['copos']; ?>"><? if($dRD['copos'] == 1): echo "SIM"; else: echo "NÃO"; endif;?></option>
                                                            <option value="1">SIM</option>
                                                            <option value="0">NÃO</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="column width-2">
                                                    <span class="color-black"><strong>Papel</strong> higiênico</span>
                                                    <div class="form-select form-element medium">
                                                        <select name="papelhigienico" tabindex="015" class="form-aux" data-label="sexo">
                                                            <option value="<? echo $dRD['papelhigienico']; ?>"><? if($dRD['papelhigienico'] == 1): echo "SIM"; else: echo "NÃO"; endif;?></option>
                                                            <option value="1">SIM</option>
                                                            <option value="0">NÃO</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="column width-2">
                                                    <span class="color-black"><strong>Papel</strong> de mão</span>
                                                    <div class="form-select form-element medium">
                                                        <select name="papelmao" tabindex="016" class="form-aux" data-label="sexo">
                                                            <option value="<? echo $dRD['papelmao']; ?>"><? if($dRD['papelmao'] == 1): echo "SIM"; else: echo "NÃO"; endif;?></option>
                                                            <option value="1">SIM</option>
                                                            <option value="0">NÃO</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="column width-2">
                                                    <span class="color-black"><strong>Lanche</strong> p/ Kids</span>
                                                    <div class="form-select form-element medium">
                                                        <select name="lanchekids" tabindex="017" class="form-aux" data-label="sexo">
                                                            <option value="<? echo $dRD['lanchekids']; ?>"><? if($dRD['lanchekids'] == 1): echo "SIM"; else: echo "NÃO"; endif;?></option>
                                                            <option value="1">SIM</option>
                                                            <option value="0">NÃO</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="column width-2">
                                                    <span class="color-black">Igreja <strong>limpa</strong></span>
                                                    <div class="form-select form-element medium">
                                                        <select name="igrejalimpa" tabindex="018" class="form-aux" data-label="sexo">
                                                            <option value="<? echo $dRD['igrejalimpa']; ?>"><? if($dRD['igrejalimpa'] == 1): echo "SIM"; else: echo "NÃO"; endif;?></option>
                                                            <option value="1">SIM</option>
                                                            <option value="0">NÃO</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="column width-2">
                                                    <span class="color-black">Qualidade do <strong>som</strong></span>
                                                    <div class="form-select form-element medium">
                                                        <select name="qualidadesom" tabindex="019" class="form-aux" data-label="sexo">
                                                            <option value="<? echo $dRD['qualidadesom']; ?>"><? if($dRD['qualidadesom'] == 1): echo "SIM"; else: echo "NÃO"; endif;?></option>
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
                                                            <option value="<? echo $dRD['chuva']; ?>"><? if($dRD['chuva'] == 1): echo "SIM"; else: echo "NÃO"; endif;?></option>
                                                            <option value="1">Sim</option>
                                                            <option value="0">Não</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="column width-2">
                                                    <span class="color-black"><strong>Pregador</strong></span>
                                                    <div class="form-select form-element medium">
                                                        <select  name="pregador" tabindex="021" class="form-aux" data-label="sexo">
                                                            <option value="<? echo $dRD['pregador']; ?>"><? echo $dRD['pregador']; ?></option>
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
                                                        <input type="text"  value="<? echo $dRD['pregadordefora']; ?>" name="pregadordefora" class="form-aux form-date form-element medium"  tabindex="022">
                                                    </div>
                                                </div>
                                                <div class="column width-2">
                                                    <span class="color-black">Nº <strong>CONVERTERAM-SE</strong>?</span>
                                                    <div class="field-wrapper">
                                                        <input type="number"  value="<? echo $dRD['nconvertidos']; ?>" name="nconvertidos" value='0' class="form-aux form-date form-element medium"  tabindex="023">
                                                    </div>
                                                </div>
                                                <div class="column width-2">
                                                    <span class="color-black">HORÁRIO DE <strong>INICIO</strong></span>
                                                    <div class="field-wrapper">
                                                        <input type="hour"  value="<? echo $dRD['horarioinicio']; ?>" name="horarioinicio" value="<?php echo date('H:i');?>" class="form-aux form-date form-element medium"  tabindex="024">
                                                    </div>
                                                </div>
                                                <div class="column width-2">
                                                    <span class="color-black">HORÁRIO DE <strong>TÉRMINO</strong></span>
                                                    <div class="field-wrapper">
                                                        <input type="hour"  value="<? echo $dRD['horariotermino']; ?>" name="horariotermino" value="<?php echo date('H:i');?>" class="form-aux form-date form-element medium"  tabindex="025">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="column width-12">
                                                    <span class="color-black">Descrição de como foi o culto</span>
                                                    <div class="field-wrapper">
                                                        <textarea type="text" value="<? echo $dRD['descricao']; ?>" name="descricao" class="form-aux form-date form-element medium"  tabindex="026"></textarea>
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
                                                        $bMembros="SELECT ministro, matriculadoministro, trouxevisitantes, presencanaoracao, funcoesministeriais FROM relatoriodeculto WHERE NOT (ministro ='') AND codigodorelatorio='$codigodorelatorio'";
                                                          $rM=mysqli_query($con, $bMembros);
                                                            $tb=0;
                                                            while($dM=mysqli_fetch_array($rM)):
                                                                $tb++;
                                                        
                                                       
                                                    ?>
                                                    <div class="box rounded border-blue small shadow">
                                                        <div class="column width-4">
                                                            <span class="color-black"></span>
                                                            <div class="field-wrapper">
                                                                <input type="hidden"  value="<? echo $dM['matriculadoministro']; ?>" name="matriculamembroC[]" value="<?echo $dM['matriculadoministro'];?>" class="form-aux form-date form-element medium"  tabindex="27<? echo $tb;?>">

                                                                <input type="text" value="<? echo $dM['ministro']; ?>" name="membro[]" readonly value="<?echo $dM['ministro'];?>" class="form-aux form-date form-element medium"  tabindex="28<? echo $tb;?>">
                                                            </div>
                                                        </div>
                                                        <div class="column width-2">
                                                            <div class="field-wrapper pt-10 pb-10 left">
                                                                <input tabindex="29<? echo $tb;?>" id="Mp_<?echo $dM['matriculadoministro'];?>" class="radio" name="Mp_<?echo $dM['matriculadoministro'];?>" value="1" type="radio" <? if($dM['presencanaoracao'] == 1): echo "checked"; endif; ?>>
                                                                <label for="Mp_<?echo $dM['matriculadoministro'];?>" class="radio-label">P</label>
                                                                <input tabindex="30<? echo $tb;?>" id="Mp_2_<?echo $dM['matriculadoministro'];?>" class="radio" name="Mp_<?echo $dM['matriculadoministro'];?>" value="0" type="radio" <? if($dM['presencanaoracao'] == 0): echo "checked"; endif; ?>>
                                                                <label for="Mp_2_<?echo $dM['matriculadoministro'];?>" class="radio-label">F</label>
                                                            </div>
                                                        </div>
                                                        <div class="column width-2">
                                                            <div class="field-wrapper">
                                                                <input type="number" name="nmembro[]" value="<?echo $dM['trouxevisitantes'];?>" class="form-aux form-date form-element medium" tabindex="31<? echo $tb;?>">
                                                            </div>
                                                        </div>
                                                        <div class="column width-3">
                                                            <div class="field-wrapper pt-10 pb-10 left">
<input tabindex="32<? echo $tb;?>" id="fun_<?echo $dM['matriculadoministro'];?>" class="radio" name="fc<?echo $dM['matriculadoministro'];?>" value="1" type="radio" <? if($dM['funcoesministeriais'] == 1): echo "checked"; endif; ?>>
<label for="fun_<?echo $dM['matriculadoministro'];?>" class="radio-label small">Sim</label>
<input tabindex="33<? echo $tb;?>" id="fun_2_<?echo $dM['matriculadoministro'];?>" class="radio" name="fc<?echo $dM['matriculadoministro'];?>" value="0" type="radio" <? if($dM['funcoesministeriais'] == 0): echo "checked"; endif; ?>>
<label for="fun_2_<?echo $dM['matriculadoministro'];?>" class="radio-label small">Não</label>
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