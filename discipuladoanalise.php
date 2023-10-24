<!DOCTYPE html>
<?php
    session_start();
    if (!empty($_GET['lng'])) :
        $idioma = $_GET['lng'];
    elseif (empty($_GET['lng']) or !isset($_GET['lng'])) :
        $idioma = 'pt-BR';
    endif;

    include "./_con.php";
    include "./_configuracao.php";

    //var_dump($_SESSION);
    if (isset($_SESSION["lideranca"])) :
        include "protectcolaborador.php";
    elseif (isset($_SESSION["membro"])) :
        include "protectusuario.php";
    else :
        header("location:index");
    endif;
    
    $anoanterior = $anoatual - 1;
    $anoanteriorb = date("Y", strtotime($ano.'- 1 year'));

    //var_dump($_SESSION);
    //Dados para registrar o log do cliente.
    $mensagem = ("\n$nomedocolaborador ($matricula) Acessou o portal. Isto através do IP: $ip, na data $dia às $hora.\n"); //Escreva a mensagem que será gravada no log.
    include "registrolog.php";
?>
<html lang="pt-BR">

<head>
    <? include "./_head.php"; ?>
    <script src="./js/chart.js"></script>
    <style>
        .analise{
            overflow-x: scroll;
        }
    </style>
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
                                                <h1 class="mb-0">Análise do <strong>discipulados</strong></h1>
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
                <div class="row full-width">
                    <?php include "./_notificacaomensagem.php"; ?>
                    <div class="column full-width pb-90 ">
                        <div class="column with-12 pb-50">
                            <h3 class="mb-0">Análise do <strong>discipulados</strong></h3>
                        </div>
                        <div class="box rounded rounded shadow border-blue bkg-white">
                            <div class="tabs line-nav rounded left">
                                <ul class="tab-nav">
                                    <li class="active">
                                        <a href="#tabs-visaogeral">Visão Geral (Gráfico)</a>
                                    </li>
                                    <li>
                                        <a href="#tabs-movimentacao">Relatórios em Tabela</a>
                                    </li>
                                </ul>
                                <div class="tab-panes">
                                    <div id="tabs-visaogeral" class="active animate">
                                        <div class="tab-content ">
                                            <div class="graph">
                                                <div class="graph-grid">
                                                    <div class="column width-12">
                                                        <div class="box rounded border-purple medium shadow">
                                                            <h4 class="pt-10"><strong>Discipulados</strong></h4>
                                                             <!-- <label class="pt-0"><span class="icon-dot-single small color-blue"></span> Nº de Dinâmicas 
                                                             <span class="icon-dot-single small color-green"></span> Nº de células</label> -->

                                                            <div class="analise"><canvas id="discipulados" height="183px" width="1200px"></canvas></div>
                                                            <script>
                                                                var barChartData = {
                                                                    labels: [<?php
                                                                                if($mesnumero < 2): echo "'',"; endif;
                                                                                $bMidia = "SELECT discipulado, sum(encontros) as encontros FROM relatoriodediscipulado WHERE NOT (discipulado = '') AND ano='$ano' AND matriculadodiscipulador='$matricula' GROUP BY discipulado ORDER by discipulado ASC";
                                                                                    $rMidia = mysqli_query($con, $bMidia);
                                                                                        $TotalMidia = mysqli_num_rows($rMidia);
                                                                                            $ContarMidia = 1;
                                                                                            while($dMidia = mysqli_fetch_array($rMidia)):
                                                                                                $nomedisci = base64_decode(base64_decode($dMidia['discipulado']));
                                                                                                $virgula = ( $TotalMidia !== $ContarMidia ) ? ',' : '';
                                                                                                echo '"'.substr($nomedisci, 0, 15).' ('.$dMidia['encontros'].')'.'"'.$virgula;
                                                                                                $ContarMidia++;
                                                                                            endwhile;
                                                                            ?>],
                                                                    datasets: [{
                                                                        fillColor: "#00cad1",
                                                                        strokeColor: "#00cad1",
                                                                        data: [<?php
                                                                                    if($mesnumero < 2): echo "0,"; endif;
                                                                                    $bSegui = "SELECT sum(encontros) as discipulados FROM relatoriodediscipulado WHERE NOT (encontros = '') AND ano='$ano' AND matriculadodiscipulador='$matricula' GROUP BY encontros ORDER BY encontros ASC";
                                                                                        $rSegui = mysqli_query($con, $bSegui);
                                                                                            $TotalSegui = mysqli_num_rows($rSegui);
                                                                                                $ContarSegui = 1;
                                                                                                while($dSegui = mysqli_fetch_array($rSegui)):
                                                                                                    $virgula = ( $TotalSegui !== $ContarSegui ) ? ',' : '';
                                                                                                    echo $dSegui['discipulados'].$virgula;
                                                                                                    $ContarSegui++;
                                                                                                endwhile;
                                                                                ?>]
                                                                    }]

                                                                };
                                                                new Chart(document.getElementById("discipulados").getContext(
                                                                    "2d")).Bar(barChartData);
                                                            </script>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <div class="clear pt-20"></div> 

                                            <!-- frequência -->
                                            <div class="graph pt-10">
                                                <div class="graph-grid">
                                                    <div class="column width-12">
                                                        <div class="box rounded border-blue medium shadow">
                                                            <h4 class="pt-20">Comparativo de <strong>Discipulados por ano</strong>.</h4>
                                                            <label class="pt-0">
                                                                <span class="icon-dot-single small color-black"></span> <span><?echo $anoanteriorb?></span>
                                                                <span class="icon-dot-single small color-green-light"> </span> <span><?echo $ano?>.</span>
                                                            </label>
                                                            <div class="analise"><canvas id="LineCompa"  height="300px" width="1200px"></canvas></div>
                                                            <script>
                                                                    var lineChartData = {
                                                                        labels : [<?php
                                                                                    $mescSF5='0';
                                                                                for($mescS05=0; $mescS05 < 12; $mescS05++){
                                                                                    $mescSF5++;
                                                                                    if($mescSF5 < 12): $vrg=','; else: $vrg=''; endif;
                                                                                    echo $mescSF5.$vrg;
                                                                                }
                                                                            ?>],
                                                                        datasets : [
                                                                            {
                                                                                label:'Ano anterior:',
                                                                                fillColor : "transparent",
                                                                                strokeColor : "#000000",
                                                                                pointColor : "#000000",
                                                                                pointStrokeColor : "#000000",
                                                                                data : [<?php
                                                                                        $ms10N='0';
                                                                                        for($ms10=0; $ms10< '12'; $ms10++){
                                                                                            $ms10N++;
                                                                                            if($ms10N < 12): $vrg=','; else: $vrg=''; endif;
                                                                                            if($ms10N < 10): $ms10N='0'.$ms10N; else: $ms10N = $ms10N; endif; //04 - 10
                                                                                            $bPEComp = "SELECT mes, sum(encontros) as discipuladosanteriores FROM relatoriodediscipulado WHERE discipulado!='' AND  matriculadodiscipulador='$matricula' AND mes='$ms10N' AND ano='$anoanteriorb' GROUP BY mes";
                                                                                            $rPEComp = mysqli_query($con, $bPEComp);
                                                                                                $dPEComp = mysqli_fetch_array($rPEComp);
                                                                                            if(empty($dPEComp['discipuladosanteriores'])):
                                                                                                echo '0'.$vrg;
                                                                                            else:
                                                                                                echo $dPEComp['discipuladosanteriores'].$vrg;
                                                                                            endif;
                                                                                        }
                                                                                ?>]
                                                                            }, {
                                                                                label:'Ano atual:',
                                                                                fillColor : "transparent",
                                                                                strokeColor : "#1bb05d",
                                                                                pointColor : "#1bb05d",
                                                                                pointStrokeColor : "#1bb05d",
                                                                                data : [<?php
                                                                                        $ms10N='0';
                                                                                        for($ms10=0; $ms10 < '12'; $ms10++){
                                                                                            $ms10N++;
                                                                                            if($ms10N < 12): $vrg=','; else: $vrg=''; endif;
                                                                                            if($ms10N < 10): $ms10N='0'.$ms10N; else: $ms10N = $ms10N; endif; //04 - 10
                                                                                            $bPEComp = "SELECT sum(encontros) as discipuladosnoano FROM relatoriodediscipulado WHERE discipulado!='' AND matriculadodiscipulador='$matricula' AND mes='$ms10N' AND ano='$ano' GROUP BY mes";
                                                                                            $rPEComp = mysqli_query($con, $bPEComp);
                                                                                                $dPEComp = mysqli_fetch_array($rPEComp);
                                                                                            if(empty($dPEComp['discipuladosnoano'])):
                                                                                                echo '0'.$vrg;
                                                                                            else:
                                                                                                echo $dPEComp['discipuladosnoano'].$vrg;
                                                                                            endif;
                                                                                        }
                                                                                ?>]
                                                                            }
                                                                        ]
                                                                        
                                                                    };
                                                                    new Chart(document.getElementById("LineCompa").getContext("2d")).Line(lineChartData);
                                                            </script>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>                                                                       
                                            
                                            <div class="clear pt-20"></div> 
                                            
                                            <?
                                                $bPr="SELECT discipulado, matriculadodiscipulado FROM relatoriodediscipulado WHERE discipulado!='' AND matriculadodiscipulador='$matricula' AND ano='$ano' GROUP BY discipulado ORDER BY discipulado ASC";
                                                  $rPr=mysqli_query($con, $bPr);
                                                    $separador=0;
                                                    while($dPr=mysqli_fetch_array($rPr)):
                                                        $separador++;
                                                        $discipuladodografico=$dPr['discipulado'];

                                                    // Definir cor
                                                    $color1="SELECT count(encontros) as discipuladosmarcados FROM relatoriodediscipulado WHERE  discipulado='$discipuladodografico' AND matriculadodiscipulador='$matricula' AND ano='$ano' GROUP BY mes";
                                                      $rC1=mysqli_query($con, $color1);
                                                        $dC1=mysqli_fetch_array($rC1);
                                                    $presenteX=$dC1['discipuladosmarcados'];
                                                    $color2="SELECT sum(encontros) as discipuladopresente FROM relatoriodediscipulado WHERE NOT (discipulado='') AND discipulado='$discipuladodografico' AND matriculadodiscipulador='$matricula' AND ano='$ano' GROUP BY mes";
                                                      $rC2=mysqli_query($con, $color2);
                                                        $dC2=mysqli_fetch_array($rC2);
                                                    $presenteY=$dC2['discipuladopresente'];
                                                    $percents=$presenteY/$presenteX;
                                                    $perc= $percents*100;
                                                    
                                                    if($percents >=0.85):
                                                        $colorgraf='#0044ff';
                                                        $indicator="Excelente - $perc%";
                                                    elseif($percents < 0.8 AND $percents >= 0.6):
                                                        $colorgraf='#fc560a';
                                                        $indicator="Regular - $perc%";
                                                    elseif($percents < 0.6):
                                                        $colorgraf='#ff0000';
                                                        $indicator="Muito Ruim - $perc%";
                                                    endif;
                                            ?>
                                            <div class="graph pt-0">
                                                <div class="graph-grid">
                                                    <div class="column width-6">
                                                        <div class="box rounded border-blue medium shadow">
                                                            <h4 class="pt-0">Encontros com o discípulo; <strong><? echo substr(base64_decode(base64_decode($dPr['discipulado'])), 0, 15).'...';?></strong></h4>
                                                            <label class="pt-0"><font color="<? echo $colorgraf;?>"><span class="icon-dot-single large"></span> Nº de presença no discipulado <strong>(<? echo $indicator;?>)</strong> </font><span class="icon-dot-single large color-youtube"></span> Nº de discipulados agendados/mês</label>
                                                            <div class="analise"><canvas id="LineCompa<? echo $dPr['matriculadodiscipulado'];?>" width="550px"></canvas></div>
                                                            <script>
                                                                    var lineChartData = {
                                                                        labels : [<?php
                                                                                    $mescSF5='0';
                                                                                for($mescS05=0; $mescS05 < 12; $mescS05++){
                                                                                    $mescSF5++;
                                                                                    if($mescSF5 < 12): $vrg=','; else: $vrg=''; endif;
                                                                                    echo $mescSF5.$vrg;
                                                                                }
                                                                            ?>],
                                                                        datasets : [
                                                                            {
                                                                                label:'Quantidade de discipulados:',
                                                                                fillColor : "transparent",
                                                                                strokeColor : "#3a3a3a",
                                                                                pointColor : "#3a3a3a",
                                                                                pointStrokeColor : "#3a3a3a",
                                                                                data : [<?php
                                                                                        $ms10N='0';
                                                                                        for($ms10=0; $ms10 < '12'; $ms10++){
                                                                                            $ms10N++;
                                                                                            if($ms10N < 12): $vrg=','; else: $vrg=''; endif;
                                                                                            if($ms10N < 10): $ms10N='0'.$ms10N; else: $ms10N = $ms10N; endif; //04 - 10
                                                                                            $bPEComp = "SELECT discipulado, count(discipulado) as discipulados FROM relatoriodediscipulado WHERE NOT (discipulado='') AND discipulado='$discipuladodografico' AND matriculadodiscipulador='$matricula' AND mes='$ms10N' AND ano='$ano' GROUP BY mes";
                                                                                            $rPEComp = mysqli_query($con, $bPEComp);
                                                                                                $dPEComp = mysqli_fetch_array($rPEComp);
                                                                                            if(empty($dPEComp['discipulados'])):
                                                                                                echo '0'.$vrg;
                                                                                            else:
                                                                                                echo $dPEComp['discipulados'].$vrg;
                                                                                            endif;
                                                                                        }
                                                                                ?>]
                                                                            },
                                                                            {
                                                                                label:'Presença na célula:',
                                                                                fillColor : "transparent",
                                                                                strokeColor : "<? echo $colorgraf;?>",
                                                                                pointColor : "<? echo $colorgraf;?>",
                                                                                pointStrokeColor : "<? echo $colorgraf;?>",
                                                                                data : [<?php
                                                                                        $ms10N='0';
                                                                                        for($ms10=0; $ms10< '12'; $ms10++){
                                                                                            $ms10N++;
                                                                                            if($ms10N < 12): $vrg=','; else: $vrg=''; endif;
                                                                                            if($ms10N < 10): $ms10N='0'.$ms10N; else: $ms10N = $ms10N; endif; //04 - 10
                                                                                            $bPEComp = "SELECT mes, sum(encontros) as presentenodiscipulado FROM relatoriodediscipulado WHERE NOT (encontros='') AND discipulado='$discipuladodografico' AND matriculadodiscipulador='$matricula' AND mes='$ms10N' AND ano='$ano' GROUP BY mes";
                                                                                            $rPEComp = mysqli_query($con, $bPEComp);
                                                                                                $dPEComp = mysqli_fetch_array($rPEComp);
                                                                                            if(empty($dPEComp['presentenodiscipulado'])):
                                                                                                echo '0'.$vrg;
                                                                                            else:
                                                                                                echo $dPEComp['presentenodiscipulado'].$vrg;
                                                                                            endif;
                                                                                        }
                                                                                ?>]
                                                                            }
                                                                        ]
                                                                        
                                                                    };
                                                                    new Chart(document.getElementById("LineCompa<? echo $dPr['matriculadodiscipulado'];?>").getContext("2d")).Line(lineChartData);
                                                            </script>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <? if($separador % 2 == 0):?>
                                                <div class="clear pt-20"></div> 
                                            <? endif; ?>
                                            <? endwhile; ?>
                                        </div>
                                    </div>
                                    <div id="tabs-movimentacao">
                                        <div class="tab-content">
                                            <div class="column width-12">
                                                <div class="tabs line-nav horizontal rounded left">
                                                    <ul class="tab-nav small">
                                                        <?php
                                                            $bAnoEx="SELECT ano FROM relatoriodediscipulado WHERE matriculadodiscipulador='$matricula' GROUP BY ano ORDER BY ano DESC";
                                                            $rAEx=mysqli_query($con, $bAnoEx);
                                                                    $Pa=0;
                                                                while($dAEx=mysqli_fetch_array($rAEx)):
                                                                    $Pa++;
                                                            if($Pa > 0 AND $Pa < 2): //Se 1º
                                                        ?>
                                                        <li class="active">
                                                        <?php
                                                            else:
                                                        ?>
                                                        <li>
                                                        <?php
                                                            endif;
                                                        ?>
                                                            <a href="#tabs-ano-<?php echo $dAEx['ano'];?>">
                                                                Relatório de discipulado do ano: <strong><?php echo $dAEx['ano'];?></strong>
                                                            </a>
                                                        </li>
                                                        <?php
                                                            endwhile;
                                                        ?>
                                                    </ul>
                                                    <div class="tab-panes">
                                                        <?php
                                                            $bAnoExP="SELECT ano FROM relatoriodediscipulado WHERE matriculadodiscipulador='$matricula' GROUP BY ano ORDER BY ano DESC";
                                                            $rAExP=mysqli_query($con, $bAnoExP);
                                                                while($dAExP=mysqli_fetch_array($rAExP)):
                                                        ?>
                                                        <div id="tabs-ano-<?php echo $dAExP['ano'];?>">
                                                            <h3>
                                                                Relatório de <strong><?php echo $dAExP['ano']; $anotab = $dAExP['ano'];?></strong>
                                                            </h3>

                                                            <hr/>

                                                            <div class="column width-12">
                                                                            
                                                                <button type="button" id="btnPrint<?php echo $anotab;?>" class="column full width button small border-blue border-hover-blue color-blue color-hover-blue hard-shadow shadow right">
                                                                    Imprimir relatório
                                                                </button>
                                                            </div>

                                                            <div class="column width-12 pt-20" id="divpublicacao<?php echo $anotab; $prints[] = $anotab; ?>">
                                                                <div class="accordion rounded mb-50">
                                                                    <ul>
                                                                        <?php
                                                                            $bMesExP="SELECT mes FROM relatoriodediscipulado WHERE matriculadodiscipulador='$matricula' AND ano='$anotab' GROUP BY mes ORDER BY mes ASC";
                                                                            $rMExP=mysqli_query($con, $bMesExP);
                                                                                while($dMExP=mysqli_fetch_array($rMExP)):
                                                                        ?>
                                                                        <li class="active">

                                                                            <a href="#accordion-<?php echo $anotab.'-'.$dMExP['mes'];?>">
                                                                                <?php
                                                                                    $mesaco = $dMExP['mes'];
                                                                                    if($mesaco == '01'):
                                                                                        $mes_nome = "Janeiro";
                                                                                    elseif($mesaco == '02'):
                                                                                        $mes_nome = "Fevereiro";
                                                                                    elseif($mesaco == '03'):
                                                                                        $mes_nome = "Março";
                                                                                    elseif($mesaco == '04'):
                                                                                        $mes_nome = "Abril";
                                                                                    elseif($mesaco == '05'):
                                                                                        $mes_nome = "Maio";
                                                                                    elseif($mesaco == '06'):
                                                                                        $mes_nome = "Junho";
                                                                                    elseif($mesaco == '07'):
                                                                                        $mes_nome = "Julho";
                                                                                    elseif($mesaco == '08'):
                                                                                        $mes_nome = "Agosto";
                                                                                    elseif($mesaco == '09'):
                                                                                        $mes_nome = "Setembro";
                                                                                    elseif($mesaco == '10'):
                                                                                        $mes_nome = "Outubro";
                                                                                    elseif($mesaco == '11'):
                                                                                        $mes_nome = "Novembro";
                                                                                    else:
                                                                                        $mes_nome = "Dezembro";
                                                                                    endif;
                                                                                    
                                                                                    echo $mes_nome.'/'.$anotab; 
                                                                                ?>
                                                                            </a>
                                                                            <div id="accordion-<?php echo $anotab.'-'.$dMExP['mes'];?>">
                                                                                <div class="accordion-content">
                                                                                    <table class="table small rounded striped">
                                                                                        <thead>
                                                                                            <tr class="bkg-charcoal color-white center">
                                                                                                <th>
                                                                                                    <span class="text-medium">
                                                                                                        Nº
                                                                                                    </span>
                                                                                                </th>
                                                                                                <th></th>
                                                                                                <th>
                                                                                                    <span class="text-medium">
                                                                                                        Discipulo (a)
                                                                                                    </span>
                                                                                                </th>
                                                                                                <th>
                                                                                                    <span class="text-medium">
                                                                                                        Data
                                                                                                    </span>
                                                                                                </th>
                                                                                                <th>
                                                                                                    <span class="text-medium">
                                                                                                        Objetivo
                                                                                                    </span>
                                                                                                </th>
                                                                                        </thead>
                                                                                        <tbody>
                                                                                                <?php
                                                                                                    $bMovFin="SELECT id, discipulador, discipulado, objetivo, descricao FROM relatoriodediscipulado WHERE matriculadodiscipulador='$matricula' AND mes='$mesaco' AND ano='$anotab' GROUP BY datadoencontro ORDER by datadoencontro DESC, id DESC";
                                                                                                    $rMovFin=mysqli_query($con, $bMovFin);
                                                                                                            $nmf = 0;
                                                                                                        while($dRC=mysqli_fetch_array($rMovFin)):
                                                                                                            $nmf++;
                                                                                                ?>
                                                                                            <tr>
                                                                                                <td class="color-black" width="3%" valign="medium">
                                                                                                    <span class="text-medium">
                                                                                                        <?php echo $nmf;?>
                                                                                                    </span>
                                                                                                </td>
                                                                                                <td class="color-black" width="3%" valign="medium">
                                                                                                    <span class="text-medium">
                                                                                                        <a data-content="inline" data-aux-classes="tml-newsletter-modal height-auto rounded" data-toolbar="" data-modal-mode data-modal-width="600" data-modal-animation="scaleIn" data-lightbox-animation="fadeIn" href="#notas-modal-<? echo $dRC['id'];?>" class="lightbox-link"><span class="icon-message"></span></a>
                                                                                                    </span>
                                                                                                </td>
                                                                                                <td class="color-black" width="20%" valign="medium">
                                                                                                    <span class="text-medium">
                                                                                                        <?php echo base64_decode(base64_decode($dRC['discipulado']));?>
                                                                                                    </span>
                                                                                                </td>
                                                                                                <td class="color-black" width="13%" valign="medium">
                                                                                                    <span class="text-medium">
                                                                                                        <?php echo date(('d/m/y'), strtotime($dRC['datadoencontro']));?>
                                                                                                    </span>
                                                                                                </td>
                                                                                                <td class="color-black" width="15%" valign="medium">
                                                                                                    <span class="text-medium">
                                                                                                        <?php echo $dRC['objetivo'];?>
                                                                                                    </span>
                                                                                                </td>
                                                                                            </tr>
                                                                                                                                                                                        
                                                                                            <div id="notas-modal-<? echo $dRC['id'];?>" class="pt-70 pb-50 hide">
                                                                                                <div class="row">
                                                                                                    <div class="column width-10 offset-1 left">

                                                                                                        <!-- Info -->
                                                                                                        <h3 class="mb-10"><? echo base64_decode(base64_decode($dRC['discipulado']));?></h3>
                                                                                                        <p class="mb-30" alignt="jutify">
                                                                                                            <?
                                                                                                                echo "Objetivo definido no discipulado: ".$dRC['objetivo'].'<br>';
                                                                                                                echo $dRC['descricao'];
                                                                                                            ?>
                                                                                                        </p>
                                                                                                    </div>
                                                                                                </div>
                                                                                            </div>
                                                                                                <?php
                                                                                                    endwhile;
                                                                                                ?>
                                                                                        </tbody>
                                                                                    </table>
                                                                                </div>
                                                                            </div>
                                                                        </li>
                                                                        <?php
                                                                            endwhile;
                                                                        ?>
                                                                    </ul>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <?php
                                                            endwhile;
                                                        ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
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
    for ($print = 0; $print < count($prints); $print++) {
    ?>
        <script type="text/javascript">
            $(document).ready(function() {
                $("#btnPrint<?php echo $prints[$print]; ?>").click(function() {
                    //get the modal box content and load it into the printable div
                    $(".printable").html($("#divpublicacao<?php echo $prints[$print]; ?>").html());
                    $(".printable").printThis();
                });
            });
        </script>
    <?php
    }
    ?>
</body>

</html>