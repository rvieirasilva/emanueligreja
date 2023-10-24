<!DOCTYPE html>
<?php
	session_start();
	if(!empty($_GET['lng'])):
		$idioma = $_GET['lng'];
	elseif(empty($_GET['lng']) OR !isset($_GET['lng'])):
		$idioma = 'pt-BR';
	endif;

	include "./_con.php";

    // $nomedarede = $_GET['r'];

	// $bRede = "SELECT * from rede WHERE nomedarede != '' AND nomedarede='$nomedarede' ORDER BY id desc";
	//   $rRede = mysqli_query($con, $bRede);
	//     $dRede = mysqli_fetch_array($rRede);
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
                                                        <h1 class="mb-0">Liderança</h1>
                                                        <p class="text-large color-charcoal mb-0 mb-mobile-20">Nossa missão é <strong>anunciar Jesus e ser culturalmente relevante</strong></p>
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
                                <div class="column width-5 right left-on-mobile">
                                    <div class="pu-10">
                                        <p class="lead text-large weight-light color-charcoal mb-50" align="justify">
                                            No dia seguinte, João estava novamente com dois de seus discípulos. Quando viu Jesus passar, olhou para ele e declarou: "Vejam! É o Cordeiro de Deus!". Ao ouvirem isso, os dois discípulos de João seguiram a Jesus. Jesus olhou em volta e viu que o seguiam. "O que vocês querem?", perguntou. Eles responderam: "Rabi (que significa 'Mestre'), onde o senhor está hospedado?". "<strong>Venham e vejam</strong>", disse ele. Era cerca de quatro horas da tarde quando o acompanharam até o lugar onde Jesus estava hospedado, e passaram o resto do dia com ele.
                                        </p>
                                    </div>
                                </div>
                                <div class="column width-6 offset-1">
                                    <div class="row flex">
                                        <div class="pu-10">
                                            <p class="weight-light color-charcoal mb-0 pt-0" align="justify">
                                                Uma equipe que persiste em <strong>apontar para Jesus</strong>, sem receio de perder seguidores, nosso ministério em conduzir todos para Cristo. Compreendemos que cada pessoa tem um chamado ministerial definido por Cristo e precisamos exercer bem aquilo que fomos chamados a realizar. <strong>Estamos servindo a todos no corpo de Cristo.</strong>
                                            </p>
                                            <p class="weight-light color-charcoal mb-0" align="justify">
                                                Ele <strong>(Jesus)</strong> designou alguns para apóstolos, outros para profetas, outros para evangelistas, outros para pastores e mestres. Eles são os responsáveis por preparar o povo santo para realizar sua obra e edificar o corpo de Cristo, até que todos alcancemos a unidade que a fé e conhecimento de Deus produzem e amadureçamos, chegando à completa medida da estatura de Cristo.
                                                <cite>Efésios 4.11-13 NVT</cite>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                                   
                            <div class="box rounded rounded shadow border-blue bkg-white">             
                                <!-- Team Grid -->
                                <div class="pt-0 team-2">
                                    <div class="row">
                                        <div class="column width-6">
                                            <h2 class="mb-50">Liderança da <strong>Igreja Emanuel</strong>.</h2>
                                        </div>
                                        <div class="column width-12">
                                            <div class="row content-grid-4">
                                                <?php
                                                    //Buscar dados do líder informado
                                                    $bL="SELECT nome, sobrenome, ministerio, funcaoadministrativa, fotodeperfil, descricaodomembro, midiasocial1, link1, midiasocial2, link2, midiasocial3, link3, midiasocial4, link4 FROM membros WHERE ministerio!='' GROUP BY matricula";
                                                    $rL=mysqli_query($con, $bL);
                                                        while($dL=mysqli_fetch_array($rL)):
                                                    
                                                        $ministeriodolider = $dL['ministerio'];
                                                        $ministeriodoliderArray = explode(',',$dL['ministerio']);
                                                    if(in_array('Pastor', $ministeriodoliderArray) OR in_array('Pastora', $ministeriodoliderArray)):
                                                    $termo='';
                                                    //if(in_array('Líder de célula', $ministeriodoliderArray)):
                                                ?>
                                                    <div class="grid-item horizon pt-10" data-animate-in="preset:slideInUpShort;duration:1000ms;" data-threshold="0.3">
                                                        <h4 class="color-charcoal mb-5"><?php $sobrenomeL = base64_decode($dL['sobrenome']); $sobrenomeL=base64_decode($sobrenomeL); $OLidArray = explode(' ', $sobrenomeL); $nomeL=base64_decode($dL['nome']); $nomeL=base64_decode($nomeL); $lider= $nomeL.' '.$OLidArray[0]; echo $lider?></h4>
                                                        <h4 class="occupation"><?php if($dL['funcaoadministrativa'] !== 'Tesoureiro' AND $dL['funcaoadministrativa'] !== 'Tesoureira' AND $dL['funcaoadministrativa'] !== 'II Tesoureiro' AND $dL['funcaoadministrativa'] !== 'II Tesoureira'): echo $dL['funcaoadministrativa'].' | '; endif; echo $ministeriodolider;?></h4>
                                                        <center>
                                                            <div class="thumbnail circle border-white thick" data-hover-easing="easeInOut" data-hover-speed="500" data-hover-bkg-color="#000000" data-hover-bkg-opacity="0.01">
                                                                <img src="<?php echo $dL['fotodeperfil'];?>" style="width:160px; height:160px;" width="150" height="150" alt="<?php echo $lider;?>"/>
                                                            </div>
                                                        </center>
                                                        <div class="team-content-info color-charcoal">
                                                            <p>
                                                                <?php echo $dL['descricaodomembro'];?>
                                                            </p>
                                                            <ul class="social-list list-horizontal">
                                                                <li>
                                                                    <a href="https://<?php $link1 = base64_decode($dL['link1']); echo base64_decode($link1);?>">
                                                                        <span class="icon-<?php echo $dL['midiasocial1'];?>-with-circle medium"></span>
                                                                    </a>
                                                                </li>
                                                                <li>
                                                                    <a href="https://<?php $link2 = base64_decode($dL['link2']); echo base64_decode($link2);?>">
                                                                        <span class="icon-<?php echo $dL['midiasocial2'];?>-with-circle medium"></span>
                                                                    </a>
                                                                </li>
                                                                <li>
                                                                    <a href="https://<?php $link3 = base64_decode($dL['link3']); echo base64_decode($link3);?>">
                                                                        <span class="icon-<?php echo $dL['midiasocial3'];?>-with-circle medium"></span>
                                                                    </a>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                <?php
                                                    elseif(in_array('Presbítero', $ministeriodoliderArray)):
                                                ?>
                                                    <div class="grid-item horizon" data-animate-in="preset:slideInUpShort;duration:1000ms;" data-threshold="0.3">
                                                        <h4 class="color-charcoal mb-5"><?php $sobrenomeL = base64_decode($dL['sobrenome']); $sobrenomeL=base64_decode($sobrenomeL); $OLidArray = explode(' ', $sobrenomeL); $nomeL=base64_decode($dL['nome']); $nomeL=base64_decode($nomeL); $lider= $nomeL.' '.$OLidArray[0]; echo $lider;?></h4>
                                                        <h4 class="occupation"><?php if($dL['funcaoadministrativa'] !== 'Tesoureiro' AND $dL['funcaoadministrativa'] !== 'Tesoureira' AND $dL['funcaoadministrativa'] !== 'II Tesoureiro' AND $dL['funcaoadministrativa'] !== 'II Tesoureira'): echo $dL['funcaoadministrativa'].' | '; endif; echo $ministeriodolider;?></h4>
                                                        <!-- <div class="thumbnail no-margin-bottom" data-hover-easing="easeInOut" data-hover-speed="500" data-hover-bkg-color="#ffffff" data-hover-bkg-opacity="0.9">
                                                            <img src="<?php echo $dL['fotodeperfil'];?>" style="width:330px; height:400px;" width="760" height="500" alt="<?php echo $lider;?>"/>
                                                        </div> -->
                                                        <div class="team-content-info color-charcoal">
                                                            <p>
                                                                <?php echo $dL['descricaodomembro'];?>
                                                            </p>
                                                            <ul class="social-list list-horizontal">
                                                                <li>
                                                                    <a href="https://<?php $link1 = base64_decode($dL['link1']); echo base64_decode($link1);?>">
                                                                        <span class="icon-<?php echo $dL['midiasocial1'];?>-with-circle medium"></span>
                                                                    </a>
                                                                </li>
                                                                <li>
                                                                    <a href="https://<?php $link2 = base64_decode($dL['link2']); echo base64_decode($link2);?>">
                                                                        <span class="icon-<?php echo $dL['midiasocial2'];?>-with-circle medium"></span>
                                                                    </a>
                                                                </li>
                                                                <li>
                                                                    <a href="https://<?php $link3 = base64_decode($dL['link3']); echo base64_decode($link3);?>">
                                                                        <span class="icon-<?php echo $dL['midiasocial3'];?>-with-circle medium"></span>
                                                                    </a>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                <?php
                                                    elseif(in_array('Evangelista', $ministeriodoliderArray)):
                                                ?>
                                                    <div class="grid-item horizon" data-animate-in="preset:slideInUpShort;duration:1000ms;" data-threshold="0.3">
                                                        <h4 class="color-charcoal mb-5"><?php $sobrenomeL = base64_decode($dL['sobrenome']); $sobrenomeL=base64_decode($sobrenomeL); $OLidArray = explode(' ', $sobrenomeL); $nomeL=base64_decode($dL['nome']); $nomeL=base64_decode($nomeL); $lider= $nomeL.' '.$OLidArray[0]; echo $lider?></h4>
                                                        <h4 class="occupation"><?php if($dL['funcaoadministrativa'] !== 'Tesoureiro' AND $dL['funcaoadministrativa'] !== 'Tesoureira' AND $dL['funcaoadministrativa'] !== 'II Tesoureiro' AND $dL['funcaoadministrativa'] !== 'II Tesoureira'): echo $dL['funcaoadministrativa'].' | '; endif; echo $ministeriodolider;?></h4>
                                                        <!-- <div class="thumbnail no-margin-bottom" data-hover-easing="easeInOut" data-hover-speed="500" data-hover-bkg-color="#ffffff" data-hover-bkg-opacity="0.9">
                                                            <img src="<?php echo $dL['fotodeperfil'];?>" style="width:330px; height:400px;" width="760" height="500" alt="<?php echo $lider;?>"/>
                                                        </div> -->
                                                        <div class="team-content-info color-charcoal">
                                                            <p>
                                                                <?php echo $dL['descricaodomembro'];?>
                                                            </p>
                                                            <ul class="social-list list-horizontal">
                                                                <li>
                                                                    <a href="https://<?php $link1 = base64_decode($dL['link1']); echo base64_decode($link1);?>">
                                                                        <span class="icon-<?php echo $dL['midiasocial1'];?>-with-circle medium"></span>
                                                                    </a>
                                                                </li>
                                                                <li>
                                                                    <a href="https://<?php $link2 = base64_decode($dL['link2']); echo base64_decode($link2);?>">
                                                                        <span class="icon-<?php echo $dL['midiasocial2'];?>-with-circle medium"></span>
                                                                    </a>
                                                                </li>
                                                                <li>
                                                                    <a href="https://<?php $link3 = base64_decode($dL['link3']); echo base64_decode($link3);?>">
                                                                        <span class="icon-<?php echo $dL['midiasocial3'];?>-with-circle medium"></span>
                                                                    </a>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                <?php
                                                    elseif(in_array('Missionária', $ministeriodoliderArray)):
                                                ?>
                                                    <div class="grid-item horizon" data-animate-in="preset:slideInUpShort;duration:1000ms;" data-threshold="0.3">
                                                        <h4 class="color-charcoal mb-5"><?php $sobrenomeL = base64_decode($dL['sobrenome']); $sobrenomeL=base64_decode($sobrenomeL); $OLidArray = explode(' ', $sobrenomeL); $nomeL=base64_decode($dL['nome']); $nomeL=base64_decode($nomeL); $lider= $nomeL.' '.$OLidArray[0]; echo $lider?></h4>
                                                        <h4 class="occupation"><?php if($dL['funcaoadministrativa'] !== 'Tesoureiro' AND $dL['funcaoadministrativa'] !== 'Tesoureira' AND $dL['funcaoadministrativa'] !== 'II Tesoureiro' AND $dL['funcaoadministrativa'] !== 'II Tesoureira'): echo $dL['funcaoadministrativa'].' | '; endif; echo $ministeriodolider;?></h4>
                                                        <!-- <div class="thumbnail no-margin-bottom" data-hover-easing="easeInOut" data-hover-speed="500" data-hover-bkg-color="#ffffff" data-hover-bkg-opacity="0.9">
                                                            <img src="<?php echo $dL['fotodeperfil'];?>" style="width:330px; height:400px;" width="760" height="500" alt="<?php echo $lider;?>"/>
                                                        </div> -->
                                                        <div class="team-content-info color-charcoal">
                                                            <p>
                                                                <?php echo $dL['descricaodomembro'];?>
                                                            </p>
                                                            <ul class="social-list list-horizontal">
                                                                <li>
                                                                    <a href="https://<?php $link1 = base64_decode($dL['link1']); echo base64_decode($link1);?>">
                                                                        <span class="icon-<?php echo $dL['midiasocial1'];?>-with-circle medium"></span>
                                                                    </a>
                                                                </li>
                                                                <li>
                                                                    <a href="https://<?php $link2 = base64_decode($dL['link2']); echo base64_decode($link2);?>">
                                                                        <span class="icon-<?php echo $dL['midiasocial2'];?>-with-circle medium"></span>
                                                                    </a>
                                                                </li>
                                                                <li>
                                                                    <a href="https://<?php $link3 = base64_decode($dL['link3']); echo base64_decode($link3);?>">
                                                                        <span class="icon-<?php echo $dL['midiasocial3'];?>-with-circle medium"></span>
                                                                    </a>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                <?php
                                                    elseif(in_array('Diacono', $ministeriodoliderArray) OR in_array('Diáconisa', $ministeriodoliderArray)):
                                                ?>
                                                    <div class="grid-item horizon" data-animate-in="preset:slideInUpShort;duration:1000ms;" data-threshold="0.3">
                                                        <h4 class="color-charcoal mb-5"><?php $sobrenomeL = base64_decode($dL['sobrenome']); $sobrenomeL=base64_decode($sobrenomeL); $OLidArray = explode(' ', $sobrenomeL); $nomeL=base64_decode($dL['nome']); $nomeL=base64_decode($nomeL); $lider= $nomeL.' '.$OLidArray[0]; echo $lider?></h4>
                                                        <h4 class="occupation"><?php if($dL['funcaoadministrativa'] !== 'Tesoureiro' AND $dL['funcaoadministrativa'] !== 'Tesoureira' AND $dL['funcaoadministrativa'] !== 'II Tesoureiro' AND $dL['funcaoadministrativa'] !== 'II Tesoureira'): echo $dL['funcaoadministrativa'].' | '; endif; echo $ministeriodolider;?></h4>
                                                        <!-- <div class="thumbnail no-margin-bottom" data-hover-easing="easeInOut" data-hover-speed="500" data-hover-bkg-color="#ffffff" data-hover-bkg-opacity="0.9">
                                                            <img src="<?php echo $dL['fotodeperfil'];?>" style="width:330px; height:400px;" width="760" height="500" alt="<?php echo $lider;?>"/>
                                                        </div> -->
                                                        <div class="team-content-info color-charcoal">
                                                            <p>
                                                                <?php echo $dL['descricaodomembro'];?>
                                                            </p>
                                                            <ul class="social-list list-horizontal">
                                                                <li>
                                                                    <a href="https://<?php $link1 = base64_decode($dL['link1']); echo base64_decode($link1);?>">
                                                                        <span class="icon-<?php echo $dL['midiasocial1'];?>-with-circle medium"></span>
                                                                    </a>
                                                                </li>
                                                                <li>
                                                                    <a href="https://<?php $link2 = base64_decode($dL['link2']); echo base64_decode($link2);?>">
                                                                        <span class="icon-<?php echo $dL['midiasocial2'];?>-with-circle medium"></span>
                                                                    </a>
                                                                </li>
                                                                <li>
                                                                    <a href="https://<?php $link3 = base64_decode($dL['link3']); echo base64_decode($link3);?>">
                                                                        <span class="icon-<?php echo $dL['midiasocial3'];?>-with-circle medium"></span>
                                                                    </a>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                <?php
                                                    elseif(in_array('Coordenador de Rede', $ministeriodoliderArray)):
                                                ?>
                                                    <div class="grid-item horizon" data-animate-in="preset:slideInUpShort;duration:1000ms;" data-threshold="0.3">
                                                        <h4 class="color-charcoal mb-5"><?php $sobrenomeL = base64_decode($dL['sobrenome']); $sobrenomeL=base64_decode($sobrenomeL); $OLidArray = explode(' ', $sobrenomeL); $nomeL=base64_decode($dL['nome']); $nomeL=base64_decode($nomeL); $lider= $nomeL.' '.$OLidArray[0]; echo $lider?></h4>
                                                        <h4 class="occupation"><?php if($dL['funcaoadministrativa'] !== 'Tesoureiro' AND $dL['funcaoadministrativa'] !== 'Tesoureira' AND $dL['funcaoadministrativa'] !== 'II Tesoureiro' AND $dL['funcaoadministrativa'] !== 'II Tesoureira'): echo $dL['funcaoadministrativa'].' | '; endif; echo $ministeriodolider;?></h4>
                                                        <!-- <div class="thumbnail no-margin-bottom" data-hover-easing="easeInOut" data-hover-speed="500" data-hover-bkg-color="#ffffff" data-hover-bkg-opacity="0.9">
                                                            <img src="<?php echo $dL['fotodeperfil'];?>" style="width:330px; height:400px;" width="760" height="500" alt="<?php echo $lider;?>"/>
                                                        </div> -->
                                                        <div class="team-content-info color-charcoal">
                                                            <p>
                                                                <?php echo $dL['descricaodomembro'];?>
                                                            </p>
                                                            <ul class="social-list list-horizontal">
                                                                <li>
                                                                    <a href="https://<?php $link1 = base64_decode($dL['link1']); echo base64_decode($link1);?>">
                                                                        <span class="icon-<?php echo $dL['midiasocial1'];?>-with-circle medium"></span>
                                                                    </a>
                                                                </li>
                                                                <li>
                                                                    <a href="https://<?php $link2 = base64_decode($dL['link2']); echo base64_decode($link2);?>">
                                                                        <span class="icon-<?php echo $dL['midiasocial2'];?>-with-circle medium"></span>
                                                                    </a>
                                                                </li>
                                                                <li>
                                                                    <a href="https://<?php $link3 = base64_decode($dL['link3']); echo base64_decode($link3);?>">
                                                                        <span class="icon-<?php echo $dL['midiasocial3'];?>-with-circle medium"></span>
                                                                    </a>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                <?php
                                                    elseif(in_array('Líder de rede', $ministeriodoliderArray)):
                                                ?>
                                                    <div class="grid-item horizon" data-animate-in="preset:slideInUpShort;duration:1000ms;" data-threshold="0.3">
                                                        <h4 class="color-charcoal mb-5"><?php $sobrenomeL = base64_decode($dL['sobrenome']); $sobrenomeL=base64_decode($sobrenomeL); $OLidArray = explode(' ', $sobrenomeL); $nomeL=base64_decode($dL['nome']); $nomeL=base64_decode($nomeL); $lider= $nomeL.' '.$OLidArray[0]; echo $lider?></h4>
                                                        <h4 class="occupation"><?php if($dL['funcaoadministrativa'] !== 'Tesoureiro' AND $dL['funcaoadministrativa'] !== 'Tesoureira' AND $dL['funcaoadministrativa'] !== 'II Tesoureiro' AND $dL['funcaoadministrativa'] !== 'II Tesoureira'): echo $dL['funcaoadministrativa'].' | '; endif; echo $ministeriodolider;?></h4>
                                                        <!-- <div class="thumbnail no-margin-bottom" data-hover-easing="easeInOut" data-hover-speed="500" data-hover-bkg-color="#ffffff" data-hover-bkg-opacity="0.9">
                                                            <img src="<?php echo $dL['fotodeperfil'];?>" style="width:330px; height:400px;" width="760" height="500" alt="<?php echo $lider;?>"/>
                                                        </div> -->
                                                        <div class="team-content-info color-charcoal">
                                                            <p>
                                                                <?php echo $dL['descricaodomembro'];?>
                                                            </p>
                                                            <ul class="social-list list-horizontal">
                                                                <li>
                                                                    <a href="https://<?php $link1 = base64_decode($dL['link1']); echo base64_decode($link1);?>">
                                                                        <span class="icon-<?php echo $dL['midiasocial1'];?>-with-circle medium"></span>
                                                                    </a>
                                                                </li>
                                                                <li>
                                                                    <a href="https://<?php $link2 = base64_decode($dL['link2']); echo base64_decode($link2);?>">
                                                                        <span class="icon-<?php echo $dL['midiasocial2'];?>-with-circle medium"></span>
                                                                    </a>
                                                                </li>
                                                                <li>
                                                                    <a href="https://<?php $link3 = base64_decode($dL['link3']); echo base64_decode($link3);?>">
                                                                        <span class="icon-<?php echo $dL['midiasocial3'];?>-with-circle medium"></span>
                                                                    </a>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                <?php
                                                    elseif(in_array('Supervisor de célula', $ministeriodoliderArray)):
                                                ?>
                                                    <div class="grid-item horizon" data-animate-in="preset:slideInUpShort;duration:1000ms;" data-threshold="0.3">
                                                        <h4 class="color-charcoal mb-5"><?php $sobrenomeL = base64_decode($dL['sobrenome']); $sobrenomeL=base64_decode($sobrenomeL); $OLidArray = explode(' ', $sobrenomeL); $nomeL=base64_decode($dL['nome']); $nomeL=base64_decode($nomeL); $lider= $nomeL.' '.$OLidArray[0]; echo $lider?></h4>
                                                        <h4 class="occupation"><?php if($dL['funcaoadministrativa'] !== 'Tesoureiro' AND $dL['funcaoadministrativa'] !== 'Tesoureira' AND $dL['funcaoadministrativa'] !== 'II Tesoureiro' AND $dL['funcaoadministrativa'] !== 'II Tesoureira'): echo $dL['funcaoadministrativa'].' | '; endif; echo $ministeriodolider;?></h4>
                                                        <!-- <div class="thumbnail no-margin-bottom" data-hover-easing="easeInOut" data-hover-speed="500" data-hover-bkg-color="#ffffff" data-hover-bkg-opacity="0.9">
                                                            <img src="<?php echo $dL['fotodeperfil'];?>" style="width:330px; height:400px;" width="760" height="500" alt="<?php echo $lider;?>"/>
                                                        </div> -->
                                                        <div class="team-content-info color-charcoal">
                                                            <p>
                                                                <?php echo $dL['descricaodomembro'];?>
                                                            </p>
                                                            <ul class="social-list list-horizontal">
                                                                <li>
                                                                    <a href="https://<?php $link1 = base64_decode($dL['link1']); echo base64_decode($link1);?>">
                                                                        <span class="icon-<?php echo $dL['midiasocial1'];?>-with-circle medium"></span>
                                                                    </a>
                                                                </li>
                                                                <li>
                                                                    <a href="https://<?php $link2 = base64_decode($dL['link2']); echo base64_decode($link2);?>">
                                                                        <span class="icon-<?php echo $dL['midiasocial2'];?>-with-circle medium"></span>
                                                                    </a>
                                                                </li>
                                                                <li>
                                                                    <a href="https://<?php $link3 = base64_decode($dL['link3']); echo base64_decode($link3);?>">
                                                                        <span class="icon-<?php echo $dL['midiasocial3'];?>-with-circle medium"></span>
                                                                    </a>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                <?php
                                                    elseif(in_array('Líder de célula', $ministeriodoliderArray)):
                                                ?>
                                                    <div class="grid-item horizon" data-animate-in="preset:slideInUpShort;duration:1000ms;" data-threshold="0.3">
                                                        <h4 class="color-charcoal mb-5"><?php $sobrenomeL = base64_decode($dL['sobrenome']); $sobrenomeL=base64_decode($sobrenomeL); $OLidArray = explode(' ', $sobrenomeL); $nomeL=base64_decode($dL['nome']); $nomeL=base64_decode($nomeL); $lider= $nomeL.' '.$OLidArray[0]; echo $lider?></h4>
                                                        <h4 class="occupation"><?php if($dL['funcaoadministrativa'] !== 'Tesoureiro' AND $dL['funcaoadministrativa'] !== 'Tesoureira' AND $dL['funcaoadministrativa'] !== 'II Tesoureiro' AND $dL['funcaoadministrativa'] !== 'II Tesoureira'): echo $dL['funcaoadministrativa'].' | '; endif; echo $ministeriodolider;?></h4>
                                                        <!-- <div class="thumbnail no-margin-bottom" data-hover-easing="easeInOut" data-hover-speed="500" data-hover-bkg-color="#ffffff" data-hover-bkg-opacity="0.9">
                                                            <img src="<?php echo $dL['fotodeperfil'];?>" style="width:330px; height:400px;" width="760" height="500" alt="<?php echo $lider;?>"/>
                                                        </div> -->
                                                        <div class="team-content-info color-charcoal">
                                                            <p>
                                                                <?php echo $dL['descricaodomembro'];?>
                                                            </p>
                                                            <ul class="social-list list-horizontal">
                                                                <li>
                                                                    <a href="https://<?php $link1 = base64_decode($dL['link1']); echo base64_decode($link1);?>">
                                                                        <span class="icon-<?php echo $dL['midiasocial1'];?>-with-circle medium"></span>
                                                                    </a>
                                                                </li>
                                                                <li>
                                                                    <a href="https://<?php $link2 = base64_decode($dL['link2']); echo base64_decode($link2);?>">
                                                                        <span class="icon-<?php echo $dL['midiasocial2'];?>-with-circle medium"></span>
                                                                    </a>
                                                                </li>
                                                                <li>
                                                                    <a href="https://<?php $link3 = base64_decode($dL['link3']); echo base64_decode($link3);?>">
                                                                        <span class="icon-<?php echo $dL['midiasocial3'];?>-with-circle medium"></span>
                                                                    </a>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                <?php
                                                    endif;
                                                    endwhile;
                                                ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Team Grid End -->
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