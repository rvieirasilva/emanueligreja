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
                                                <h1 class="mb-0">Análise <strong>pessoal</strong></h1>
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
                            <h3 class="mb-0">Análise do <strong>Pessoal</strong></h3>
                        </div>
                        <div class="box rounded rounded shadow border-blue bkg-white">
                            <div class="tabs line-nav rounded left">
                                <ul class="tab-nav">
                                    <li class="active">
                                        <a href="#tabs-visaogeral">Visão em Gráfico</a>
                                    </li>
                                </ul>
                                <div class="tab-panes">
                                    <div id="tabs-visaogeral" class="active animate">
                                        <div class="tab-content ">

                                            <!-- Evangelismo -->
                                            <div class="graph pt-10">
                                                <div class="graph-grid">
                                                    <div class="column width-12">
                                                        <div class="box rounded border-blue medium shadow">
                                                            <h4 class="pt-20">Pessoas <strong>que você apresentou para Cristo</strong></h4>
                                                            <span class="icon-dot-single large color-black"></span> <span><?echo $anoanteriorb?></span>
                                                            <span class="icon-dot-single large color-green-light"></span> <span><?echo $ano?>.</span>
                                                            <div class="analise"><canvas id="lineEvangelismo"  width="1200px"></canvas></div>
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
                                                                                strokeColor : "#1bb05d",
                                                                                pointColor : "#1bb05d",
                                                                                pointStrokeColor : "#1bb05d",
                                                                                data : [<?php
                                                                                        $ms10N='0';
                                                                                        for($ms10=0; $ms10< '12'; $ms10++){
                                                                                            $ms10N++;
                                                                                            if($ms10N < 12): $vrg=','; else: $vrg=''; endif;
                                                                                            if($ms10N < 10): $ms10N='0'.$ms10N; else: $ms10N = $ms10N; endif; //04 - 10
                                                                                            $bPEComp = "SELECT ministro, sum(trouxevisitantes) as evangelizados FROM relatoriodeculto WHERE NOT (trouxevisitantes = '') AND ministro='$nomedousuario' AND ano='$anoanteriorb' AND campusrelator='$membrodocampus' GROUP BY mes";
                                                                                            $rPEComp = mysqli_query($con, $bPEComp);
                                                                                                $dPEComp = mysqli_fetch_array($rPEComp);
                                                                                            if(empty($dPEComp['evangelizados'])):
                                                                                                echo '0'.$vrg;
                                                                                            else:
                                                                                                echo $dPEComp['evangelizados'].$vrg;
                                                                                            endif;
                                                                                        }
                                                                                ?>]
                                                                            }, {
                                                                                label:'Ano atual:',
                                                                                fillColor : "transparent",
                                                                                strokeColor : "#0044ff",
                                                                                pointColor : "#0044ff",
                                                                                pointStrokeColor : "#0044ff",
                                                                                data : [<?php
                                                                                        $ms10N='0';
                                                                                        for($ms10=0; $ms10 < '12'; $ms10++){
                                                                                            $ms10N++;
                                                                                            if($ms10N < 12): $vrg=','; else: $vrg=''; endif;
                                                                                            if($ms10N < 10): $ms10N='0'.$ms10N; else: $ms10N = $ms10N; endif; //04 - 10
                                                                                            $bPEComp = "SELECT mes, sum(trouxevisitantes) as evangelizou FROM relatoriodeculto WHERE NOT (trouxevisitantes='') AND campusrelator='$membrodocampus' AND mes='$ms10N' AND ano='$ano' GROUP BY mes";
                                                                                            $rPEComp = mysqli_query($con, $bPEComp);
                                                                                                $dPEComp = mysqli_fetch_array($rPEComp);
                                                                                            if(empty($dPEComp['evangelizou'])):
                                                                                                echo '0'.$vrg;
                                                                                            else:
                                                                                                echo $dPEComp['evangelizou'].$vrg;
                                                                                            endif;
                                                                                        }
                                                                                ?>]
                                                                            }
                                                                        ]
                                                                        
                                                                    };
                                                                    new Chart(document.getElementById("lineEvangelismo").getContext("2d")).Line(lineChartData);
                                                            </script>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <div class="clear pt-20"></div>

                                            <!-- frequência -->
                                            <div class="graph">
                                                <div class="graph-grid">
                                                    <div class="column width-6">
                                                        <div class="box rounded border-blue medium shadow">
                                                            <h4 class="pt-20">Seu nível de <strong>constância em adoração</strong> a Cristo.</h4>
                                                            <span class="icon-dot-single large color-green"></span> <span><?echo $anoanteriorb?></span>
                                                            <span class="icon-dot-single large color-blue"></span> <span><?echo $ano?>.</span>
                                                            <div class="analise"><canvas id="LineFrequencia" width="550px"></canvas></div>
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
                                                                                strokeColor : "#1bb05d",
                                                                                pointColor : "#1bb05d",
                                                                                pointStrokeColor : "#1bb05d",
                                                                                data : [<?php
                                                                                        $ms10N='0';
                                                                                        for($ms10=0; $ms10< '12'; $ms10++){
                                                                                            $ms10N++;
                                                                                            if($ms10N < 12): $vrg=','; else: $vrg=''; endif;
                                                                                            if($ms10N < 10): $ms10N='0'.$ms10N; else: $ms10N = $ms10N; endif; //04 - 10
                                                                                            $bPEComp = "SELECT mes, sum(presencanaoracao) as frequenciaanterior FROM relatoriodeculto WHERE NOT (presencanaoracao='') AND campusrelator='$membrodocampus' AND ministro='$nomedousuario' AND mes='$ms10N' AND ano='$anoanteriorb' GROUP BY mes";
                                                                                            $rPEComp = mysqli_query($con, $bPEComp);
                                                                                                $dPEComp = mysqli_fetch_array($rPEComp);
                                                                                            if(empty($dPEComp['frequenciaanterior'])):
                                                                                                echo '0'.$vrg;
                                                                                            else:
                                                                                                echo $dPEComp['frequenciaanterior'].$vrg;
                                                                                            endif;
                                                                                        }
                                                                                ?>]
                                                                            }, {
                                                                                label:'Ano atual:',
                                                                                fillColor : "transparent",
                                                                                strokeColor : "#0044ff",
                                                                                pointColor : "#0044ff",
                                                                                pointStrokeColor : "#0044ff",
                                                                                data : [<?php
                                                                                        $ms10N='0';
                                                                                        for($ms10=0; $ms10 < '12'; $ms10++){
                                                                                            $ms10N++;
                                                                                            if($ms10N < 12): $vrg=','; else: $vrg=''; endif;
                                                                                            if($ms10N < 10): $ms10N='0'.$ms10N; else: $ms10N = $ms10N; endif; //04 - 10
                                                                                            $bPEComp = "SELECT mes, sum(presencanaoracao) as frequenciaatual FROM relatoriodeculto WHERE NOT (presencanaoracao='') AND campusrelator='$membrodocampus' AND ministro='$nomedousuario' AND mes='$ms10N' AND ano='$ano' GROUP BY mes";
                                                                                            $rPEComp = mysqli_query($con, $bPEComp);
                                                                                                $dPEComp = mysqli_fetch_array($rPEComp);
                                                                                            if(empty($dPEComp['frequenciaatual'])):
                                                                                                echo '0'.$vrg;
                                                                                            else:
                                                                                                echo $dPEComp['frequenciaatual'].$vrg;
                                                                                            endif;
                                                                                        }
                                                                                ?>]
                                                                            }
                                                                        ]
                                                                        
                                                                    };
                                                                    new Chart(document.getElementById("LineFrequencia").getContext("2d")).Line(lineChartData);
                                                            </script>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Funcoes exercidas -->
                                            <div class="graph">
                                                <div class="graph-grid">
                                                    <div class="column width-6">
                                                        <div class="box rounded border-blue medium shadow">
                                                            <h4 class="pt-20">Comparativo do seu <strong>comprometimento</strong>.</h4>
                                                            <span class="icon-dot-single large color-green"></span> <span><?echo $anoanteriorb?></span>
                                                            <span class="icon-dot-single large color-blue"></span> <span><?echo $ano?>.</span>
                                                            <div class="analise"><canvas id="LineFuncoesMinisteriais" width="550px"></canvas></div>
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
                                                                                strokeColor : "#1bb05d",
                                                                                pointColor : "#1bb05d",
                                                                                pointStrokeColor : "#1bb05d",
                                                                                data : [<?php
                                                                                        $ms10N='0';
                                                                                        for($ms10=0; $ms10< '12'; $ms10++){
                                                                                            $ms10N++;
                                                                                            if($ms10N < 12): $vrg=','; else: $vrg=''; endif;
                                                                                            if($ms10N < 10): $ms10N='0'.$ms10N; else: $ms10N = $ms10N; endif; //04 - 10
                                                                                            $bPEComp = "SELECT mes, sum(funcoesministeriais) as frequenciaanterior FROM relatoriodeculto WHERE NOT (funcoesministeriais='') AND campusrelator='$membrodocampus' AND ministro='$nomedousuario' AND mes='$ms10N' AND ano='$anoanteriorb' GROUP BY mes";
                                                                                            $rPEComp = mysqli_query($con, $bPEComp);
                                                                                                $dPEComp = mysqli_fetch_array($rPEComp);
                                                                                            if(empty($dPEComp['frequenciaanterior'])):
                                                                                                echo '0'.$vrg;
                                                                                            else:
                                                                                                echo $dPEComp['frequenciaanterior'].$vrg;
                                                                                            endif;
                                                                                        }
                                                                                ?>]
                                                                            }, {
                                                                                label:'Ano atual:',
                                                                                fillColor : "transparent",
                                                                                strokeColor : "#0044ff",
                                                                                pointColor : "#0044ff",
                                                                                pointStrokeColor : "#0044ff",
                                                                                data : [<?php
                                                                                        $ms10N='0';
                                                                                        for($ms10=0; $ms10 < '12'; $ms10++){
                                                                                            $ms10N++;
                                                                                            if($ms10N < 12): $vrg=','; else: $vrg=''; endif;
                                                                                            if($ms10N < 10): $ms10N='0'.$ms10N; else: $ms10N = $ms10N; endif; //04 - 10
                                                                                            $bPEComp = "SELECT mes, sum(funcoesministeriais) as frequenciaatual FROM relatoriodeculto WHERE NOT (funcoesministeriais='') AND campusrelator='$membrodocampus' AND ministro='$nomedousuario' AND mes='$ms10N' AND ano='$ano' GROUP BY mes";
                                                                                            $rPEComp = mysqli_query($con, $bPEComp);
                                                                                                $dPEComp = mysqli_fetch_array($rPEComp);
                                                                                            if(empty($dPEComp['frequenciaatual'])):
                                                                                                echo '0'.$vrg;
                                                                                            else:
                                                                                                echo $dPEComp['frequenciaatual'].$vrg;
                                                                                            endif;
                                                                                        }
                                                                                ?>]
                                                                            }
                                                                        ]
                                                                        
                                                                    };
                                                                    new Chart(document.getElementById("LineFuncoesMinisteriais").getContext("2d")).Line(lineChartData);
                                                            </script>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <div class="clear pt-20"></div>

                                            <!-- Presença na célula -->
                                            <div class="graph">
                                                <div class="graph-grid">
                                                    <div class="column width-6">
                                                        <div class="box rounded border-blue medium shadow">
                                                            <h4 class="pt-20">Seu nível de <strong>constância em relacionamentos através da célula</strong>.</h4>
                                                            <span class="icon-dot-single large color-green"></span> <span><?echo $anoanteriorb?></span>
                                                            <span class="icon-dot-single large color-blue"></span> <span><?echo $ano?>.</span>
                                                            <div class="analise"><canvas id="linefreqCelula" width="550px"></canvas></div>
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
                                                                                strokeColor : "#1bb05d",
                                                                                pointColor : "#1bb05d",
                                                                                pointStrokeColor : "#1bb05d",
                                                                                data : [<?php
                                                                                        $ms10N='0';
                                                                                        for($ms10=0; $ms10< '12'; $ms10++){
                                                                                            $ms10N++;
                                                                                            if($ms10N < 12): $vrg=','; else: $vrg=''; endif;
                                                                                            if($ms10N < 10): $ms10N='0'.$ms10N; else: $ms10N = $ms10N; endif; //04 - 10
                                                                                            $bPEComp = "SELECT mes, sum(presenca) as freqCelAnterior FROM relatorio WHERE NOT (presenca='') AND matriculadomembro='$matricula' AND mes='$ms10N' AND ano='$anoanterior' GROUP BY mes";
                                                                                            $rPEComp = mysqli_query($con, $bPEComp);
                                                                                                $dPEComp = mysqli_fetch_array($rPEComp);
                                                                                            if(empty($dPEComp['freqCelAnterior'])):
                                                                                                echo '0'.$vrg;
                                                                                            else:
                                                                                                echo $dPEComp['freqCelAnterior'].$vrg;
                                                                                            endif;
                                                                                        }
                                                                                ?>]
                                                                            }, {
                                                                                label:'Ano atual:',
                                                                                fillColor : "transparent",
                                                                                strokeColor : "#0044ff",
                                                                                pointColor : "#0044ff",
                                                                                pointStrokeColor : "#0044ff",
                                                                                data : [<?php
                                                                                        $ms10N='0';
                                                                                        for($ms10=0; $ms10 < '12'; $ms10++){
                                                                                            $ms10N++;
                                                                                            if($ms10N < 12): $vrg=','; else: $vrg=''; endif;
                                                                                            if($ms10N < 10): $ms10N='0'.$ms10N; else: $ms10N = $ms10N; endif; //04 - 10
                                                                                            $bPEComp = "SELECT mes, sum(presenca) as freqCelAtual FROM relatorio WHERE NOT (presenca='') AND matriculadomembro='$matricula' AND mes='$ms10N' AND ano='$anoatual' GROUP BY mes";
                                                                                            $rPEComp = mysqli_query($con, $bPEComp);
                                                                                                $dPEComp = mysqli_fetch_array($rPEComp);
                                                                                            if(empty($dPEComp['freqCelAtual'])):
                                                                                                echo '0'.$vrg;
                                                                                            else:
                                                                                                echo $dPEComp['freqCelAtual'].$vrg;
                                                                                            endif;
                                                                                        }
                                                                                ?>]
                                                                            }
                                                                        ]
                                                                        
                                                                    };
                                                                    new Chart(document.getElementById("linefreqCelula").getContext("2d")).Line(lineChartData);
                                                            </script>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Funcoes exercidas -->
                                            <div class="graph">
                                                <div class="graph-grid">
                                                    <div class="column width-6">
                                                        <div class="box rounded border-blue medium shadow">
                                                            <h4 class="pt-20">Pessoas que você <strong>apresentou para Cristo através da célula</strong>.</h4>
                                                            <span class="icon-dot-single large color-green"></span> <span><?echo $anoanteriorb?></span>
                                                            <span class="icon-dot-single large color-blue"></span> <span><?echo $ano?>.</span>
                                                            <div class="analise"><canvas id="LineEvangelismoCelula" width="550px"></canvas></div>
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
                                                                                strokeColor : "#1bb05d",
                                                                                pointColor : "#1bb05d",
                                                                                pointStrokeColor : "#1bb05d",
                                                                                data : [<?php
                                                                                        $ms10N='0';
                                                                                        for($ms10=0; $ms10< '12'; $ms10++){
                                                                                            $ms10N++;
                                                                                            if($ms10N < 12): $vrg=','; else: $vrg=''; endif;
                                                                                            if($ms10N < 10): $ms10N='0'.$ms10N; else: $ms10N = $ms10N; endif; //04 - 10
                                                                                            $bPEComp = "SELECT mes, sum(trouxevisitantes) as evangelismocelula FROM relatorio WHERE NOT (trouxevisitantes='') AND matriculadomembro='$matricula' AND mes='$ms10N' AND ano='$anoanterior' GROUP BY mes";
                                                                                            $rPEComp = mysqli_query($con, $bPEComp);
                                                                                                $dPEComp = mysqli_fetch_array($rPEComp);
                                                                                            if(empty($dPEComp['evangelismocelula'])):
                                                                                                echo '0'.$vrg;
                                                                                            else:
                                                                                                echo $dPEComp['evangelismocelula'].$vrg;
                                                                                            endif;
                                                                                        }
                                                                                ?>]
                                                                            }, {
                                                                                label:'Ano atual:',
                                                                                fillColor : "transparent",
                                                                                strokeColor : "#0044ff",
                                                                                pointColor : "#0044ff",
                                                                                pointStrokeColor : "#0044ff",
                                                                                data : [<?php
                                                                                        $ms10N='0';
                                                                                        for($ms10=0; $ms10 < '12'; $ms10++){
                                                                                            $ms10N++;
                                                                                            if($ms10N < 12): $vrg=','; else: $vrg=''; endif;
                                                                                            if($ms10N < 10): $ms10N='0'.$ms10N; else: $ms10N = $ms10N; endif; //04 - 10
                                                                                            $bPEComp = "SELECT mes, sum(trouxevisitantes) as evangelismoatual FROM relatorio WHERE NOT (trouxevisitantes='') AND matriculadomembro='$matricula' AND mes='$ms10N' AND ano='$anoatual' GROUP BY mes";
                                                                                            $rPEComp = mysqli_query($con, $bPEComp);
                                                                                                $dPEComp = mysqli_fetch_array($rPEComp);
                                                                                            if(empty($dPEComp['evangelismoatual'])):
                                                                                                echo '0'.$vrg;
                                                                                            else:
                                                                                                echo $dPEComp['evangelismoatual'].$vrg;
                                                                                            endif;
                                                                                        }
                                                                                ?>]
                                                                            }
                                                                        ]
                                                                        
                                                                    };
                                                                    new Chart(document.getElementById("LineEvangelismoCelula").getContext("2d")).Line(lineChartData);
                                                            </script>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <div class="clear pt-20"></div>

                                            <!-- Discipulados realizados -->
                                            <div class="graph">
                                                <div class="graph-grid">
                                                    <div class="column width-6">
                                                        <div class="box rounded border-blue medium shadow">
                                                            <h4 class="pt-20">Comparativo de <strong>discipulados</strong> realizados.</h4>
                                                            <span class="icon-dot-single large color-green"></span> <span><?echo $anoanteriorb?></span>
                                                            <span class="icon-dot-single large color-blue"></span> <span><?echo $ano?>.</span>
                                                            <div class="analise"><canvas id="lineDiscipulador" width="550px"></canvas></div>
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
                                                                                strokeColor : "#1bb05d",
                                                                                pointColor : "#1bb05d",
                                                                                pointStrokeColor : "#1bb05d",
                                                                                data : [<?php
                                                                                        $ms10N='0';
                                                                                        for($ms10=0; $ms10< '12'; $ms10++){
                                                                                            $ms10N++;
                                                                                            if($ms10N < 12): $vrg=','; else: $vrg=''; endif;
                                                                                            if($ms10N < 10): $ms10N='0'.$ms10N; else: $ms10N = $ms10N; endif; //04 - 10
                                                                                            $bPEComp = "SELECT mes, count(discipulador) as discipuladosfeitosantes FROM relatoriodediscipulado WHERE NOT (discipulador='') AND matriculadodiscipulador='$matricula' AND mes='$ms10N' AND ano='$anoanteriorb' GROUP BY mes";
                                                                                            $rPEComp = mysqli_query($con, $bPEComp);
                                                                                                $dPEComp = mysqli_fetch_array($rPEComp);
                                                                                            if(empty($dPEComp['discipuladosfeitosantes'])):
                                                                                                echo '0'.$vrg;
                                                                                            else:
                                                                                                echo $dPEComp['discipuladosfeitosantes'].$vrg;
                                                                                            endif;
                                                                                        }
                                                                                ?>]
                                                                            }, {
                                                                                label:'Ano atual:',
                                                                                fillColor : "transparent",
                                                                                strokeColor : "#0044ff",
                                                                                pointColor : "#0044ff",
                                                                                pointStrokeColor : "#0044ff",
                                                                                data : [<?php
                                                                                        $ms10N='0';
                                                                                        for($ms10=0; $ms10 < '12'; $ms10++){
                                                                                            $ms10N++;
                                                                                            if($ms10N < 12): $vrg=','; else: $vrg=''; endif;
                                                                                            if($ms10N < 10): $ms10N='0'.$ms10N; else: $ms10N = $ms10N; endif; //04 - 10
                                                                                            $bPEComp = "SELECT mes, count(discipulador) as discipuladosAtuais FROM relatoriodediscipulado WHERE NOT (discipulador='') AND matriculadodiscipulador='$matricula' AND mes='$ms10N' AND ano='$ano' GROUP BY mes";
                                                                                            $rPEComp = mysqli_query($con, $bPEComp);
                                                                                                $dPEComp = mysqli_fetch_array($rPEComp);
                                                                                            if(empty($dPEComp['discipuladosAtuais'])):
                                                                                                echo '0'.$vrg;
                                                                                            else:
                                                                                                echo $dPEComp['discipuladosAtuais'].$vrg;
                                                                                            endif;
                                                                                        }
                                                                                ?>]
                                                                            }
                                                                        ]
                                                                        
                                                                    };
                                                                    new Chart(document.getElementById("lineDiscipulador").getContext("2d")).Line(lineChartData);
                                                            </script>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Discipulados recebidos -->
                                            <div class="graph">
                                                <div class="graph-grid">
                                                    <div class="column width-6">
                                                        <div class="box rounded border-blue medium shadow">
                                                            <h4 class="pt-20">Comparativo de encontros com <strong>meu discipulador</strong>.</h4>
                                                            <span class="icon-dot-single large color-green"></span> <span><?echo $anoanteriorb?></span>
                                                            <span class="icon-dot-single large color-blue"></span> <span><?echo $ano?>.</span>
                                                            <div class="analise"><canvas id="lineDiscipulo" width="550px"></canvas></div>
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
                                                                                strokeColor : "#1bb05d",
                                                                                pointColor : "#1bb05d",
                                                                                pointStrokeColor : "#1bb05d",
                                                                                data : [<?php
                                                                                        $ms10N='0';
                                                                                        for($ms10=0; $ms10< '12'; $ms10++){
                                                                                            $ms10N++;
                                                                                            if($ms10N < 12): $vrg=','; else: $vrg=''; endif;
                                                                                            if($ms10N < 10): $ms10N='0'.$ms10N; else: $ms10N = $ms10N; endif; //04 - 10
                                                                                            $bPEComp = "SELECT mes, count(discipulado) as discipuladosAnterior FROM relatoriodediscipulado WHERE NOT (discipulado='') AND matriculadodiscipulado='$matricula' AND mes='$ms10N' AND ano='$anoanteriorb' GROUP BY mes";
                                                                                            $rPEComp = mysqli_query($con, $bPEComp);
                                                                                                $dPEComp = mysqli_fetch_array($rPEComp);
                                                                                            if(empty($dPEComp['discipuladosAnterior'])):
                                                                                                echo '0'.$vrg;
                                                                                            else:
                                                                                                echo $dPEComp['discipuladosAnterior'].$vrg;
                                                                                            endif;
                                                                                        }
                                                                                ?>]
                                                                            }, {
                                                                                label:'Ano atual:',
                                                                                fillColor : "transparent",
                                                                                strokeColor : "#0044ff",
                                                                                pointColor : "#0044ff",
                                                                                pointStrokeColor : "#0044ff",
                                                                                data : [<?php
                                                                                        $ms10N='0';
                                                                                        for($ms10=0; $ms10 < '12'; $ms10++){
                                                                                            $ms10N++;
                                                                                            if($ms10N < 12): $vrg=','; else: $vrg=''; endif;
                                                                                            if($ms10N < 10): $ms10N='0'.$ms10N; else: $ms10N = $ms10N; endif; //04 - 10
                                                                                            $bPEComp = "SELECT mes, count(discipulado) as discipuladoAtual FROM relatoriodediscipulado WHERE NOT (discipulado='') AND matriculadodiscipulado='$matricula' AND mes='$ms10N' AND ano='$ano' GROUP BY mes";
                                                                                            $rPEComp = mysqli_query($con, $bPEComp);
                                                                                                $dPEComp = mysqli_fetch_array($rPEComp);
                                                                                            if(empty($dPEComp['discipuladoAtual'])):
                                                                                                echo '0'.$vrg;
                                                                                            else:
                                                                                                echo $dPEComp['discipuladoAtual'].$vrg;
                                                                                            endif;
                                                                                        }
                                                                                ?>]
                                                                            }
                                                                        ]
                                                                        
                                                                    };
                                                                    new Chart(document.getElementById("lineDiscipulo").getContext("2d")).Line(lineChartData);
                                                            </script>
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