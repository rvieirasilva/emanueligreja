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
    
    $anoanterior = date("Y", strtotime($ano.'- 1 year'));
    $anoanteriorb = date("Y", strtotime($ano.'- 1 year'));
    
    $mesanterior = date("m", strtotime($ano.'- 1 months'));
    $mesanteriorb = date("m", strtotime($ano.'- 1 months'));

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
                                                <h1 class="mb-0">Análise de <strong>culto</strong></h1>
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
                        <div class="box rounded rounded shadow border-blue bkg-white">
                            <div class="tabs line-nav rounded left">
                                <ul class="tab-nav">
                                    <li class="active">
                                        <a href="#tabs-visaogeral">Visão Geral (Gráfico)</a>
                                    </li>
                                    <li>
                                        <a href="#tabs-movimentacao"><strong>Ranking</strong> ministerial</a>
                                    </li>
                                </ul>
                                <div class="tab-panes">
                                    <div id="tabs-visaogeral" class="active animate">
                                        <div class="tab-content ">

                                            <!-- Ministros evangelizadores -->
                                            <div class="graph">
                                                <div class="graph-grid">
                                                    <div class="column width-12">
                                                        <div class="box rounded border-purple medium shadow">
                                                            <h4 class="pt-10"><strong>Evangelizadores</strong></h4>
                                                            <!-- <label class="pt-0"><span class="icon-dot-single small color-blue"></span> Nº de Dinâmicas 
                                                            <span class="icon-dot-single small color-green"></span> Nº de células</label> -->

                                                            <div class="analise"><canvas id="evangelizadores" height="183px" width="1200px"></canvas></div>
                                                            <script>
                                                                var barChartData = {
                                                                    labels: [<?php
                                                                                if($mesnumero < 2): echo "'',"; endif;
                                                                                $bMidia = "SELECT ministro, sum(trouxevisitantes) as evangelizados FROM relatoriodeculto WHERE NOT (trouxevisitantes = '') AND ano='$ano' AND campusrelator='$membrodocampus' GROUP BY ministro ORDER by ministro ASC";
                                                                                    $rMidia = mysqli_query($con, $bMidia);
                                                                                        $TotalMidia = mysqli_num_rows($rMidia);
                                                                                            $ContarMidia = 1;
                                                                                            while($dMidia = mysqli_fetch_array($rMidia)):
                                                                                                $virgula = ( $TotalMidia !== $ContarMidia ) ? ',' : '';
                                                                                                echo '"'.substr($dMidia['ministro'], 0, 13).' ('.$dMidia['evangelizados'].')'.'"'.$virgula;
                                                                                                $ContarMidia++;
                                                                                            endwhile;
                                                                            ?>],
                                                                    datasets: [{
                                                                        fillColor: "#003df5ff",
                                                                        strokeColor: "#003df5ff",
                                                                        data: [<?php
                                                                                    if($mesnumero < 2): echo "0,"; endif;
                                                                                    $bSegui = "SELECT sum(trouxevisitantes) as evangelizou FROM relatoriodeculto WHERE NOT (trouxevisitantes = '') AND ano='$ano' AND campusrelator='$membrodocampus' GROUP BY ministro ORDER BY ministro ASC";
                                                                                        $rSegui = mysqli_query($con, $bSegui);
                                                                                            $TotalSegui = mysqli_num_rows($rSegui);
                                                                                                $ContarSegui = 1;
                                                                                                while($dSegui = mysqli_fetch_array($rSegui)):
                                                                                                    $virgula = ( $TotalSegui !== $ContarSegui ) ? ',' : '';
                                                                                                    echo $dSegui['evangelizou'].$virgula;
                                                                                                    $ContarSegui++;
                                                                                                endwhile;
                                                                                ?>]
                                                                    }]

                                                                };
                                                                new Chart(document.getElementById("evangelizadores").getContext(
                                                                    "2d")).Bar(barChartData);
                                                            </script>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="clear pt-20"></div>
                                            
                                            <!-- Ministros na oração -->
                                            <div class="graph">
                                                <div class="graph-grid">
                                                    <div class="column width-12">
                                                        <div class="box rounded border-purple medium shadow">
                                                            <h4 class="pt-10"><strong>Ministros na oração</strong></h4>
                                                            <!-- <label class="pt-0"><span class="icon-dot-single small color-blue"></span> Nº de Dinâmicas 
                                                            <span class="icon-dot-single small color-green"></span> Nº de células</label> -->

                                                            <div class="analise"><canvas id="presentesnaoracao" height="183px" width="1200px"></canvas></div>
                                                            <script>
                                                                var barChartData = {
                                                                    labels: [<?php
                                                                                if($mesnumero < 2): echo "'',"; endif;
                                                                                $bMidia = "SELECT ministro, sum(presencanaoracao) as orouXvezes FROM relatoriodeculto WHERE NOT (presencanaoracao = '') AND ano='$ano' AND campusrelator='$membrodocampus' GROUP BY ministro ORDER by ministro ASC";
                                                                                    $rMidia = mysqli_query($con, $bMidia);
                                                                                        $TotalMidia = mysqli_num_rows($rMidia);
                                                                                            $ContarMidia = 1;
                                                                                            while($dMidia = mysqli_fetch_array($rMidia)):
                                                                                                $virgula = ( $TotalMidia !== $ContarMidia ) ? ',' : '';
                                                                                                echo '"'.substr($dMidia['ministro'], 0, 13).' ('.$dMidia['orouXvezes'].')'.'"'.$virgula;
                                                                                                $ContarMidia++;
                                                                                            endwhile;
                                                                            ?>],
                                                                    datasets: [{
                                                                        fillColor: "#003df5ff",
                                                                        strokeColor: "#003df5ff",
                                                                        data: [<?php
                                                                                    if($mesnumero < 2): echo "0,"; endif;
                                                                                    $bSegui = "SELECT sum(presencanaoracao) as noracoes FROM relatoriodeculto WHERE NOT (presencanaoracao = '') AND ano='$ano' AND campusrelator='$membrodocampus' GROUP BY ministro ORDER BY ministro ASC";
                                                                                        $rSegui = mysqli_query($con, $bSegui);
                                                                                            $TotalSegui = mysqli_num_rows($rSegui);
                                                                                                $ContarSegui = 1;
                                                                                                while($dSegui = mysqli_fetch_array($rSegui)):
                                                                                                    $virgula = ( $TotalSegui !== $ContarSegui ) ? ',' : '';
                                                                                                    echo $dSegui['noracoes'].$virgula;
                                                                                                    $ContarSegui++;
                                                                                                endwhile;
                                                                                ?>]
                                                                    }]

                                                                };
                                                                new Chart(document.getElementById("presentesnaoracao").getContext(
                                                                    "2d")).Bar(barChartData);
                                                            </script>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="clear pt-20"></div>

                                            <!-- Funções ministeriais -->
                                            <div class="graph">
                                                <div class="graph-grid">
                                                    <div class="column width-12">
                                                        <div class="box rounded border-purple medium shadow">
                                                            <h4 class="pt-10"><strong>Cumpriu as funções do culto</strong></h4>
                                                            <!-- <label class="pt-0"><span class="icon-dot-single small color-blue"></span> Nº de Dinâmicas 
                                                            <span class="icon-dot-single small color-green"></span> Nº de células</label> -->

                                                            <div class="analise"><canvas id="funcoesministeriais" height="183px" width="1200px"></canvas></div>
                                                            <script>
                                                                var barChartData = {
                                                                    labels: [<?php
                                                                                if($mesnumero < 2): echo "'',"; endif;
                                                                                $bMidia = "SELECT ministro, sum(funcoesministeriais) as fezfuncao FROM relatoriodeculto WHERE NOT (funcoesministeriais = '') AND ano='$ano' AND campusrelator='$membrodocampus' GROUP BY ministro ORDER by ministro ASC";
                                                                                    $rMidia = mysqli_query($con, $bMidia);
                                                                                        $TotalMidia = mysqli_num_rows($rMidia);
                                                                                            $ContarMidia = 1;
                                                                                            while($dMidia = mysqli_fetch_array($rMidia)):
                                                                                                $virgula = ( $TotalMidia !== $ContarMidia ) ? ',' : '';
                                                                                                echo '"'.substr($dMidia['ministro'], 0, 13).' ('.$dMidia['fezfuncao'].')'.'"'.$virgula;
                                                                                                $ContarMidia++;
                                                                                            endwhile;
                                                                            ?>],
                                                                    datasets: [{
                                                                        fillColor: "#003df5ff",
                                                                        strokeColor: "#003df5ff",
                                                                        data: [<?php
                                                                                    if($mesnumero < 2): echo "0,"; endif;
                                                                                    $bSegui = "SELECT sum(funcoesministeriais) as fezfunc FROM relatoriodeculto WHERE NOT (funcoesministeriais = '') AND ano='$ano' AND campusrelator='$membrodocampus' GROUP BY ministro ORDER BY ministro ASC";
                                                                                        $rSegui = mysqli_query($con, $bSegui);
                                                                                            $TotalSegui = mysqli_num_rows($rSegui);
                                                                                                $ContarSegui = 1;
                                                                                                while($dSegui = mysqli_fetch_array($rSegui)):
                                                                                                    $virgula = ( $TotalSegui !== $ContarSegui ) ? ',' : '';
                                                                                                    echo $dSegui['fezfunc'].$virgula;
                                                                                                    $ContarSegui++;
                                                                                                endwhile;
                                                                                ?>]
                                                                    }]

                                                                };
                                                                new Chart(document.getElementById("funcoesministeriais").getContext(
                                                                    "2d")).Bar(barChartData);
                                                            </script>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="clear pt-20"></div>

                                            <!-- Pregadores -->
                                            <div class="graph">
                                                <div class="graph-grid">
                                                    <div class="column width-12">
                                                        <div class="box rounded border-purple medium shadow">
                                                            <h4 class="pt-10"><strong>Pregadores</strong></h4>
                                                            <!-- <label class="pt-0"><span class="icon-dot-single small color-blue"></span> Nº de Dinâmicas 
                                                            <span class="icon-dot-single small color-green"></span> Nº de células</label> -->

                                                            <div class="analise"><canvas id="pregadores" height="183px" width="1200px"></canvas></div>
                                                            <script>
                                                                var barChartData = {
                                                                    labels: [<?php
                                                                                if($mesnumero < 2): echo "'',"; endif;
                                                                                $bMidia = "SELECT pregador, count(pregador) as quantidadedepregacoes FROM relatoriodeculto WHERE NOT (pregador = '') AND ano='$ano' AND campusrelator='$membrodocampus' GROUP BY pregador ORDER by pregador ASC";
                                                                                    $rMidia = mysqli_query($con, $bMidia);
                                                                                        $TotalMidia = mysqli_num_rows($rMidia);
                                                                                            $ContarMidia = 1;
                                                                                            while($dMidia = mysqli_fetch_array($rMidia)):
                                                                                                $virgula = ( $TotalMidia !== $ContarMidia ) ? ',' : '';
                                                                                                echo '"'.substr($dMidia['pregador'], 0, 20).' ('.$dMidia['quantidadedepregacoes'].')'.'"'.$virgula;
                                                                                                $ContarMidia++;
                                                                                            endwhile;
                                                                            ?>],
                                                                    datasets: [{
                                                                        fillColor: "#ff0000",
                                                                        strokeColor: "#ff0000",
                                                                        data: [<?php
                                                                                    if($mesnumero < 2): echo "0,"; endif;
                                                                                    $bSegui = "SELECT count(pregador) as qpregacoes FROM relatoriodeculto WHERE NOT (pregador = '') AND ano='$ano' AND campusrelator='$membrodocampus' GROUP BY pregador ORDER BY pregador ASC";
                                                                                        $rSegui = mysqli_query($con, $bSegui);
                                                                                            $TotalSegui = mysqli_num_rows($rSegui);
                                                                                                $ContarSegui = 1;
                                                                                                while($dSegui = mysqli_fetch_array($rSegui)):
                                                                                                    $virgula = ( $TotalSegui !== $ContarSegui ) ? ',' : '';
                                                                                                    echo $dSegui['qpregacoes'].$virgula;
                                                                                                    $ContarSegui++;
                                                                                                endwhile;
                                                                                ?>]
                                                                    }]

                                                                };
                                                                new Chart(document.getElementById("pregadores").getContext(
                                                                    "2d")).Bar(barChartData);
                                                            </script>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="clear pt-20"></div>

                                            <!-- Participantes do culto no mês -->
                                            <div class="graph pt-10">
                                                <div class="graph-grid">
                                                    <div class="column width-12">
                                                        <div class="box rounded border-blue medium shadow">
                                                            <h4 class="pt-20">Participantes por culto no mês <? echo $mesnumero.'/'.$ano?>.</h4>
                                                            <span class="icon-dot-single large color-green-light"></span> <span><?echo $ano?>.</span>
                                                            <div class="analise"><canvas id="linePresentes" width="1150px"></canvas></div>
                                                            <script>
                                                                    var lineChartData = {
                                                                        labels : [<?php
                                                                                $bPEComp = "SELECT count(id) as virgs, datadoculto FROM relatoriodeculto WHERE NOT (participantes='') AND campusrelator='$membrodocampus' AND mes='$mesnumero' AND ano='$ano' GROUP BY datadoculto";
                                                                                $rPEComp = mysqli_query($con, $bPEComp);
                                                                                $novirgs=0;
                                                                                  while($dPEComp = mysqli_fetch_array($rPEComp)):
                                                                                    $novirgs++;
                                                                                    $diadoculto=explode('-', $dPEComp['datadoculto']); $diadoculto=$diadoculto[2];
                                                                                    if($novirg < $dPEComp['virgs']): $vrg=','; else: $vrg=''; endif;
                                                                                    echo $diadoculto.$vrg;
                                                                                endwhile;
                                                                                ?>],
                                                                        datasets : [{
                                                                                label:'Mês atual:',
                                                                                fillColor : "transparent",
                                                                                strokeColor : "#1bb05d",
                                                                                pointColor : "#1bb05d",
                                                                                pointStrokeColor : "#1bb05d",
                                                                                data : [<?php
                                                                                            $bPEComp = "SELECT count(id) as virgs, participantes FROM relatoriodeculto WHERE NOT (participantes='') AND campusrelator='$membrodocampus' AND mes='$mesnumero' AND ano='$ano' GROUP BY datadoculto";
                                                                                            $rPEComp = mysqli_query($con, $bPEComp);
                                                                                                $novirg2=0;
                                                                                              while($dPEComp = mysqli_fetch_array($rPEComp)):
                                                                                                $novirg2++;
                                                                                                if($novirg < $dPEComp['virgs']): $vrg=','; else: $vrg=''; endif;
                                                                                                echo $dPEComp['participantes'].$vrg;
                                                                                              endwhile;
                                                                                ?>]
                                                                            }
                                                                        ]
                                                                        
                                                                    };
                                                                    new Chart(document.getElementById("linePresentes").getContext("2d")).Line(lineChartData);
                                                            </script>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- frequência -->
                                            <div class="graph pt-10">
                                                <div class="graph-grid">
                                                    <div class="column width-12">
                                                        <div class="box rounded border-blue medium shadow">
                                                            <h4 class="pt-20">Comparativo de frequência</h4>
                                                            <span class="icon-dot-single large color-black"></span> <span><?echo $anoanteriorb?></span>
                                                            <span class="icon-dot-single large color-green-light"></span> <span><?echo $ano?>.</span>
                                                            <div class="analise"><canvas id="LineCompa" width="1200px"></canvas></div>
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
                                                                                            $bPEComp = "SELECT mes, sum(participantes) as frequenciaanterior FROM relatoriodeculto WHERE NOT (participantes='') AND campusrelator='$membrodocampus' AND mes='$ms10N' AND ano='$anoanteriorb' GROUP BY mes";
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
                                                                                strokeColor : "#1bb05d",
                                                                                pointColor : "#1bb05d",
                                                                                pointStrokeColor : "#1bb05d",
                                                                                data : [<?php
                                                                                        $ms10N='0';
                                                                                        for($ms10=0; $ms10 < '12'; $ms10++){
                                                                                            $ms10N++;
                                                                                            if($ms10N < 12): $vrg=','; else: $vrg=''; endif;
                                                                                            if($ms10N < 10): $ms10N='0'.$ms10N; else: $ms10N = $ms10N; endif; //04 - 10
                                                                                            $bPEComp = "SELECT mes, sum(participantes) as frequenciaatual FROM relatoriodeculto WHERE NOT (participantes='') AND campusrelator='$membrodocampus' AND mes='$ms10N' AND ano='$ano' GROUP BY mes";
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
                                                                    new Chart(document.getElementById("LineCompa").getContext("2d")).Line(lineChartData);
                                                            </script>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="clear pt-20"></div>
                                            
                                            <!-- frequência Homens/Mulheres -->
                                            <div class="graph pt-10">
                                                <div class="graph-grid">
                                                    <div class="column width-12">
                                                        <div class="box rounded border-blue medium shadow">
                                                            <h4 class="pt-20">Participação de Homens/Mulheres</h4>
                                                            <span class="icon-dot-single large color-black"></span> <span>Homens</span>
                                                            <span class="icon-dot-single large color-green-light"></span> <span>Mulheres</span>
                                                            <div class="analise"><canvas id="lineHM"  height="300px" width="1200px"></canvas></div>
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
                                                                                label:'Homens:',
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
                                                                                            $bPEComp = "SELECT mes, sum(homenspresentes) as homensnoculto FROM relatoriodeculto WHERE NOT (homenspresentes='') AND campusrelator='$membrodocampus' AND mes='$ms10N' AND ano='$ano' GROUP BY mes";
                                                                                            $rPEComp = mysqli_query($con, $bPEComp);
                                                                                                $dPEComp = mysqli_fetch_array($rPEComp);
                                                                                            if(empty($dPEComp['homensnoculto'])):
                                                                                                echo '0'.$vrg;
                                                                                            else:
                                                                                                echo $dPEComp['homensnoculto'].$vrg;
                                                                                            endif;
                                                                                        }
                                                                                ?>]
                                                                            }, {
                                                                                label:'Mulheres:',
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
                                                                                            $bPEComp = "SELECT mes, sum(mulherespresentes) as mulheresnoculto FROM relatoriodeculto WHERE NOT (mulherespresentes='') AND campusrelator='$membrodocampus' AND mes='$ms10N' AND ano='$ano' GROUP BY mes";
                                                                                            $rPEComp = mysqli_query($con, $bPEComp);
                                                                                                $dPEComp = mysqli_fetch_array($rPEComp);
                                                                                            if(empty($dPEComp['mulheresnoculto'])):
                                                                                                echo '0'.$vrg;
                                                                                            else:
                                                                                                echo $dPEComp['mulheresnoculto'].$vrg;
                                                                                            endif;
                                                                                        }
                                                                                ?>]
                                                                            }
                                                                        ]
                                                                        
                                                                    };
                                                                    new Chart(document.getElementById("lineHM").getContext("2d")).Line(lineChartData);
                                                            </script>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <div class="clear pt-20"></div>  
                                                                                                                
                                            <!-- visitantes  -->
                                            <div class="graph">
                                                <div class="graph-grid">
                                                    <div class="column width-12">
                                                        <div class="box rounded border-blue medium shadow">
                                                            <h4 class="pt-10">Comparativo de <strong>visitantes</strong> (mês).</h4>
                                                                <label class="pt-0"><span class="icon-dot-single small color-blue"></span> Ano Atual</label>
                                                            <!-- <span class="icon-dot-single large color-navy"></span> <span>Ano Atual.</span> -->

                                                            <div class="analise"><canvas id="lineRP" width="1200px"></canvas></div>
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
                                                                                label:'Arrecadação:',
                                                                                fillColor : "transparent",
                                                                                strokeColor : "#227fbb",
                                                                                pointColor : "#227fbb",
                                                                                pointStrokeColor : "#227fbb",
                                                                                data : [<?php
                                                                                        $ms10N='0';
                                                                                        for($ms10=0; $ms10 < '12'; $ms10++){
                                                                                            $ms10N++;
                                                                                            if($ms10N < 12): $vrg=','; else: $vrg=''; endif;
                                                                                            if($ms10N < 10): $ms10N='0'.$ms10N; else: $ms10N = $ms10N; endif; //04 - 10
                                                                                            $bPEComp = "SELECT mes, sum(visitantes) as visitantesano FROM relatoriodeculto WHERE NOT (visitantes='') AND campusrelator='$membrodocampus' AND mes='$ms10N' AND ano='$ano' GROUP BY mes";
                                                                                            $rPEComp = mysqli_query($con, $bPEComp);
                                                                                                $dPEComp = mysqli_fetch_array($rPEComp);
                                                                                            if(empty($dPEComp['visitantesano'])):
                                                                                                echo '0'.$vrg;
                                                                                            else:
                                                                                                echo $dPEComp['visitantesano'].$vrg;
                                                                                            endif;
                                                                                        }
                                                                                ?>]
                                                                            }
                                                                        ]
                                                                        
                                                                    };
                                                                    new Chart(document.getElementById("lineRP").getContext("2d")).Line(lineChartData);
                                                            </script>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="clear pt-20"></div>  
                                            
                                            <!-- Homens -->
                                            <div class="graph">
                                                <div class="graph-grid">
                                                    <div class="column width-6">
                                                        <div class="box rounded border-blue medium shadow">
                                                            <h4 class="pt-10">Comparativo de <strong>Homens</strong> presentes (mês).</h4>
                                                                <label class="pt-0"><span class="icon-dot-single small color-blue"></span> Ano Atual</label>
                                                            <!-- <span class="icon-dot-single large color-navy"></span> <span>Ano Atual.</span> -->

                                                            <div class="analise"><canvas id="lineSP" width="550px"></canvas></div>
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
                                                                                label:'Arrecadação:',
                                                                                fillColor : "transparent",
                                                                                strokeColor : "#040a58",
                                                                                pointColor : "#040a58",
                                                                                pointStrokeColor : "#040a58",
                                                                                data : [<?php
                                                                                        $ms10N='0';
                                                                                        for($ms10=0; $ms10 < '12'; $ms10++){
                                                                                            $ms10N++;
                                                                                            if($ms10N < 12): $vrg=','; else: $vrg=''; endif;
                                                                                            if($ms10N < 10): $ms10N='0'.$ms10N; else: $ms10N = $ms10N; endif; //04 - 10
                                                                                            $bHp = "SELECT mes, sum(homenspresentes) as homensano FROM relatoriodeculto WHERE NOT (homenspresentes ='') AND campusrelator='$membrodocampus' AND mes='$ms10N' AND ano='$ano' GROUP BY mes";
                                                                                            $rHp = mysqli_query($con, $bHp);
                                                                                                $dHp = mysqli_fetch_array($rHp);
                                                                                            if(empty($dHp['homensano'])):
                                                                                                echo '0'.$vrg;
                                                                                            else:
                                                                                                echo $dHp['homensano'].$vrg;
                                                                                            endif;
                                                                                        }
                                                                                ?>]
                                                                            }
                                                                        ]
                                                                        
                                                                    };
                                                                    new Chart(document.getElementById("lineSP").getContext("2d")).Line(lineChartData);
                                                            </script>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- Mulheres -->
                                            <div class="graph">
                                                <div class="graph-grid">
                                                    <div class="column width-6">
                                                        <div class="box rounded border-purple medium shadow">
                                                            <h4 class="pt-10">Comparativo de <strong>Mulheres</strong> presentes (mês).</h4>
                                                                <label class="pt-0"><span class="icon-dot-single small color-blue"></span> Ano Atual</label>
                                                            <!-- <span class="icon-dot-single large color-navy"></span> <span>Ano Atual.</span> -->

                                                            <div class="analise"><canvas id="lineMC" width="550px"></canvas></div>
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
                                                                                label:'Arrecadação:',
                                                                                fillColor : "transparent",
                                                                                strokeColor : "#ff0037",
                                                                                pointColor : "#ff0037",
                                                                                pointStrokeColor : "#ff0037",
                                                                                data : [<?php
                                                                                        $ms10N='0';
                                                                                        for($ms10=0; $ms10 < '12'; $ms10++){
                                                                                            $ms10N++;
                                                                                            if($ms10N < 12): $vrg=','; else: $vrg=''; endif;
                                                                                            if($ms10N < 10): $ms10N='0'.$ms10N; else: $ms10N = $ms10N; endif; //04 - 10
                                                                                            $bMp = "SELECT mes, sum(mulherespresentes) as mulheresnacelula FROM relatoriodeculto WHERE NOT (mulherespresentes='') AND campusrelator='$membrodocampus' AND mes='$ms10N' AND ano='$ano' GROUP BY mes";
                                                                                            $rMp = mysqli_query($con, $bMp);
                                                                                                $dMp = mysqli_fetch_array($rMp);
                                                                                            if(empty($dMp['mulheresnacelula'])):
                                                                                                echo '0'.$vrg;
                                                                                            else:
                                                                                                echo $dMp['mulheresnacelula'].$vrg;
                                                                                            endif;
                                                                                        }
                                                                                ?>]
                                                                            }
                                                                        ]
                                                                        
                                                                    };
                                                                    new Chart(document.getElementById("lineMC").getContext("2d")).Line(lineChartData);
                                                            </script>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="clear pt-20"></div> 

                                            <!-- Conversão -->
                                            <div class="graph">
                                                <div class="graph-grid">
                                                    <div class="column width-12">
                                                        <div class="box rounded border-purple medium shadow">
                                                            <h4 class="pt-10">Acompanhamento de <strong>conversões</strong></h4>
                                                                <label class="pt-0"><span class="icon-dot-single large color-blue"></span> Novos convertidos<span class="icon-dot-single large color-red-light"></span> Nº de cultos</label>

                                                            <div class="analise"><canvas id="lineconversao" width="1200px"></canvas></div>
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
                                                                                label:'Conversão no culto:',
                                                                                fillColor : "transparent",
                                                                                strokeColor : "#003df5ff",
                                                                                pointColor : "#003df5ff",
                                                                                pointStrokeColor : "#003df5ff",
                                                                                data : [<?php
                                                                                        $ms10N='0';
                                                                                        for($ms10=0; $ms10 < '12'; $ms10++){
                                                                                            $ms10N++;
                                                                                            if($ms10N < 12): $vrg=','; else: $vrg=''; endif;
                                                                                            if($ms10N < 10): $ms10N='0'.$ms10N; else: $ms10N = $ms10N; endif; //04 - 10
                                                                                            $bOp = "SELECT mes, sum(conversao) as cultoscomconversao FROM relatoriodeculto WHERE NOT (conversao='') AND campusrelator='$membrodocampus' AND mes='$ms10N' AND ano='$ano' GROUP BY mes";
                                                                                            $rOp = mysqli_query($con, $bOp);
                                                                                                $dOp = mysqli_fetch_array($rOp);
                                                                                            if(empty($dOp['cultoscomconversao'])):
                                                                                                echo '0'.$vrg;
                                                                                            else:
                                                                                                echo $dOp['cultoscomconversao'].$vrg;
                                                                                            endif;
                                                                                        }
                                                                                ?>]
                                                                            },{
                                                                                label:'Número de cultos no mês:',
                                                                                fillColor : "transparent",
                                                                                strokeColor : "#ff0000",
                                                                                pointColor : "#ff0000",
                                                                                pointStrokeColor : "#ff0000",
                                                                                data : [<?php
                                                                                        $ms10N='0';
                                                                                        for($ms10=0; $ms10 < '12'; $ms10++){
                                                                                            $ms10N++;
                                                                                            if($ms10N < 12): $vrg=','; else: $vrg=''; endif;
                                                                                            if($ms10N < 10): $ms10N='0'.$ms10N; else: $ms10N = $ms10N; endif; //04 - 10
                                                                                            $bOp = "SELECT mes, count(mes) as QCConversao FROM relatoriodeculto WHERE NOT (conversao='') AND campusrelator='$membrodocampus' AND mes='$ms10N' AND ano='$ano' GROUP BY mes";
                                                                                            $rOp = mysqli_query($con, $bOp);
                                                                                                $dOp = mysqli_fetch_array($rOp);
                                                                                            if(empty($dOp['QCConversao'])):
                                                                                                echo '0'.$vrg;
                                                                                            else:
                                                                                                echo $dOp['QCConversao'].$vrg;
                                                                                            endif;
                                                                                        }
                                                                                ?>]
                                                                            }
                                                                        ]
                                                                        
                                                                    };
                                                                    new Chart(document.getElementById("lineconversao").getContext("2d")).Line(lineChartData);
                                                            </script>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div> 

                                            <div class="clear pt-20"></div> 
                                            
                                            <!-- Quantidade de convertidos -->
                                            <div class="graph">
                                                <div class="graph-grid">
                                                    <div class="column width-6">
                                                        <div class="box rounded border-purple medium shadow">
                                                            <h4 class="pt-10">Acompanhamento de <strong>novos membros</strong></h4>
                                                                <label class="pt-0"><span class="icon-dot-single large color-blue"></span> Novos membros
                                                                <!-- <span class="icon-dot-single large color-youtube"></span> Nº de cultos -->
                                                            </label>

                                                            <div class="analise"><canvas id="lineNovosMembros" width="550px"></canvas></div>
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
                                                                                label:'Conversão no culto:',
                                                                                fillColor : "transparent",
                                                                                strokeColor : "#003df5ff",
                                                                                pointColor : "#003df5ff",
                                                                                pointStrokeColor : "#003df5ff",
                                                                                data : [<?php
                                                                                        $ms10N='0';
                                                                                        for($ms10=0; $ms10 < '12'; $ms10++){
                                                                                            $ms10N++;
                                                                                            if($ms10N < 12): $vrg=','; else: $vrg=''; endif;
                                                                                            if($ms10N < 10): $ms10N='0'.$ms10N; else: $ms10N = $ms10N; endif; //04 - 10
                                                                                            $bOp = "SELECT mes, sum(nconvertidos) as novosmembros FROM relatoriodeculto WHERE NOT (nconvertidos='') AND campusrelator='$membrodocampus' AND mes='$ms10N' AND ano='$ano' GROUP BY mes";
                                                                                            $rOp = mysqli_query($con, $bOp);
                                                                                                $dOp = mysqli_fetch_array($rOp);
                                                                                            if(empty($dOp['novosmembros'])):
                                                                                                echo '0'.$vrg;
                                                                                            else:
                                                                                                echo $dOp['novosmembros'].$vrg;
                                                                                            endif;
                                                                                        }
                                                                                ?>]
                                                                            }
                                                                        ]
                                                                        
                                                                    };
                                                                    new Chart(document.getElementById("lineNovosMembros").getContext("2d")).Line(lineChartData);
                                                            </script>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div> 
                                            
                                            <!-- Quantidade de membros ativos/desativados -->
                                            <div class="graph">
                                                <div class="graph-grid">
                                                    <div class="column width-6">
                                                        <div class="box rounded border-purple medium shadow">
                                                            <h4 class="pt-10">Acompanhamento de <strong>entrada/saída</strong> de membros</h4>
                                                                <label class="pt-0"><span class="icon-dot-single large color-blue"></span> Membros ativos
                                                                <span class="icon-dot-single large color-youtube"></span> Membros inativos
                                                            </label>

                                                            <div class="analise"><canvas id="lineMembros" width="550px"></canvas></div>
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
                                                                                label:'Membros:',
                                                                                fillColor : "transparent",
                                                                                strokeColor : "#003df5ff",
                                                                                pointColor : "#003df5ff",
                                                                                pointStrokeColor : "#003df5ff",
                                                                                data : [<?php
                                                                                        $ms10N='0';
                                                                                        for($ms10=0; $ms10 < '12'; $ms10++){
                                                                                            $ms10N++;
                                                                                            if($ms10N < 12): $vrg=','; else: $vrg=''; endif;
                                                                                            if($ms10N < 10): $ms10N='0'.$ms10N; else: $ms10N = $ms10N; endif; //04 - 10
                                                                                            $bOp = "SELECT count(statusdomembro) as membros FROM membros WHERE NOT (statusdomembro='') AND statusdomembro='Ativo' GROUP BY statusdomembro";
                                                                                            $rOp = mysqli_query($con, $bOp);
                                                                                                $dOp = mysqli_fetch_array($rOp);
                                                                                            if(empty($dOp['membros'])):
                                                                                                echo '0'.$vrg;
                                                                                            else:
                                                                                                echo $dOp['membros'].$vrg;
                                                                                            endif;
                                                                                        }
                                                                                ?>]
                                                                            },{
                                                                                label:'Ex-Membros:',
                                                                                fillColor : "transparent",
                                                                                strokeColor : "#ff0000",
                                                                                pointColor : "#ff0000",
                                                                                pointStrokeColor : "#ff0000",
                                                                                data : [<?php
                                                                                        $ms10N='0';
                                                                                        for($ms10=0; $ms10 < '12'; $ms10++){
                                                                                            $ms10N++;
                                                                                            if($ms10N < 12): $vrg=','; else: $vrg=''; endif;
                                                                                            if($ms10N < 10): $ms10N='0'.$ms10N; else: $ms10N = $ms10N; endif; //04 - 10
                                                                                            $bOp = "SELECT count(statusdomembro) as exmembros FROM membros WHERE NOT (statusdomembro='') AND statusdomembro!='Ativo' AND statusdomembro!='Solicitado' GROUP BY statusdomembro";
                                                                                            $rOp = mysqli_query($con, $bOp);
                                                                                                $dOp = mysqli_fetch_array($rOp);
                                                                                            if(empty($dOp['exmembros'])):
                                                                                                echo '0'.$vrg;
                                                                                            else:
                                                                                                echo $dOp['exmembros'].$vrg;
                                                                                            endif;
                                                                                        }
                                                                                ?>]
                                                                            }
                                                                        ]
                                                                        
                                                                    };
                                                                    new Chart(document.getElementById("lineMembros").getContext("2d")).Line(lineChartData);
                                                            </script>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div> 

                                            <div class="clear pt-20"></div> 
                                            
                                            <!-- Água no banheiro -->
                                            <div class="graph">
                                                <div class="graph-grid">
                                                    <div class="column width-6">
                                                        <div class="box rounded border-purple medium shadow">
                                                            <h4 class="pt-10">Comparativo <strong>água no banheiro/Nº de cultos</strong></h4>
                                                             <label class="pt-0"><span class="icon-dot-single small color-blue"></span> Tem água <span class="icon-dot-single small color-black"></span> Nº de cultos</label>

                                                            <div class="analise"><canvas id="lineaguacaixa" width="550px"></canvas></div>
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
                                                                                label:'Arrecadação:',
                                                                                fillColor : "transparent",
                                                                                strokeColor : "#003df5ff",
                                                                                pointColor : "#003df5ff",
                                                                                pointStrokeColor : "#003df5ff",
                                                                                data : [<?php
                                                                                        $ms10N='0';
                                                                                        for($ms10=0; $ms10 < '12'; $ms10++){
                                                                                            $ms10N++;
                                                                                            if($ms10N < 12): $vrg=','; else: $vrg=''; endif;
                                                                                            if($ms10N < 10): $ms10N='0'.$ms10N; else: $ms10N = $ms10N; endif; //04 - 10
                                                                                            $bOp = "SELECT mes, sum(aguanacaixa) as temagua FROM relatoriodeculto WHERE NOT (aguanacaixa='') AND campusrelator='$membrodocampus' AND mes='$ms10N' AND ano='$ano' GROUP BY mes";
                                                                                            $rOp = mysqli_query($con, $bOp);
                                                                                                $dOp = mysqli_fetch_array($rOp);
                                                                                            if(empty($dOp['temagua'])):
                                                                                                echo '0'.$vrg;
                                                                                            else:
                                                                                                echo $dOp['temagua'].$vrg;
                                                                                            endif;
                                                                                        }
                                                                                ?>]
                                                                            },{
                                                                                label:'Arrecadação:',
                                                                                fillColor : "transparent",
                                                                                strokeColor : "#003df5ff",
                                                                                pointColor : "#003df5ff",
                                                                                pointStrokeColor : "#003df5ff",
                                                                                data : [<?php
                                                                                        $ms10N='0';
                                                                                        for($ms10=0; $ms10 < '12'; $ms10++){
                                                                                            $ms10N++;
                                                                                            if($ms10N < 12): $vrg=','; else: $vrg=''; endif;
                                                                                            if($ms10N < 10): $ms10N='0'.$ms10N; else: $ms10N = $ms10N; endif; //04 - 10
                                                                                            $bOp = "SELECT mes, count(mes) as quantidadedeculto FROM relatoriodeculto WHERE NOT (aguanacaixa='') AND campusrelator='$membrodocampus' AND mes='$ms10N' AND ano='$ano' GROUP BY mes";
                                                                                            $rOp = mysqli_query($con, $bOp);
                                                                                                $dOp = mysqli_fetch_array($rOp);
                                                                                            if(empty($dOp['quantidadedeculto'])):
                                                                                                echo '0'.$vrg;
                                                                                            else:
                                                                                                echo $dOp['quantidadedeculto'].$vrg;
                                                                                            endif;
                                                                                        }
                                                                                ?>]
                                                                            }
                                                                        ]
                                                                        
                                                                    };
                                                                    new Chart(document.getElementById("lineaguacaixa").getContext("2d")).Line(lineChartData);
                                                            </script>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div> 
                                            
                                            <!-- Água no bebedouro -->
                                            <div class="graph">
                                                <div class="graph-grid">
                                                    <div class="column width-6">
                                                        <div class="box rounded border-purple medium shadow">
                                                            <h4 class="pt-10">Comparativo <strong>água no bebedouro / Nº de cultos</strong></h4>
                                                             <label class="pt-0"><span class="icon-dot-single small color-blue"></span> Àgua no bebedouro <span class="icon-dot-single small color-black"></span> Nº de cultos</label>

                                                            <div class="analise"><canvas id="lineaguanobebedouro" width="550px"></canvas></div>
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
                                                                                label:'Arrecadação:',
                                                                                fillColor : "transparent",
                                                                                strokeColor : "#003df5ff",
                                                                                pointColor : "#003df5ff",
                                                                                pointStrokeColor : "#003df5ff",
                                                                                data : [<?php
                                                                                        $ms10N='0';
                                                                                        for($ms10=0; $ms10 < '12'; $ms10++){
                                                                                            $ms10N++;
                                                                                            if($ms10N < 12): $vrg=','; else: $vrg=''; endif;
                                                                                            if($ms10N < 10): $ms10N='0'.$ms10N; else: $ms10N = $ms10N; endif; //04 - 10
                                                                                            $bOp = "SELECT mes, sum(aguanobebedouro) as temagua FROM relatoriodeculto WHERE NOT (aguanobebedouro='') AND campusrelator='$membrodocampus' AND mes='$ms10N' AND ano='$ano' GROUP BY mes";
                                                                                            $rOp = mysqli_query($con, $bOp);
                                                                                                $dOp = mysqli_fetch_array($rOp);
                                                                                            if(empty($dOp['temagua'])):
                                                                                                echo '0'.$vrg;
                                                                                            else:
                                                                                                echo $dOp['temagua'].$vrg;
                                                                                            endif;
                                                                                        }
                                                                                ?>]
                                                                            },{
                                                                                label:'Arrecadação:',
                                                                                fillColor : "transparent",
                                                                                strokeColor : "#003df5ff",
                                                                                pointColor : "#003df5ff",
                                                                                pointStrokeColor : "#003df5ff",
                                                                                data : [<?php
                                                                                        $ms10N='0';
                                                                                        for($ms10=0; $ms10 < '12'; $ms10++){
                                                                                            $ms10N++;
                                                                                            if($ms10N < 12): $vrg=','; else: $vrg=''; endif;
                                                                                            if($ms10N < 10): $ms10N='0'.$ms10N; else: $ms10N = $ms10N; endif; //04 - 10
                                                                                            $bOp = "SELECT mes, count(mes) as quantidadedecultoB FROM relatoriodeculto WHERE NOT (aguanobebedouro='') AND campusrelator='$membrodocampus' AND mes='$ms10N' AND ano='$ano' GROUP BY mes";
                                                                                            $rOp = mysqli_query($con, $bOp);
                                                                                                $dOp = mysqli_fetch_array($rOp);
                                                                                            if(empty($dOp['quantidadedecultoB'])):
                                                                                                echo '0'.$vrg;
                                                                                            else:
                                                                                                echo $dOp['quantidadedecultoB'].$vrg;
                                                                                            endif;
                                                                                        }
                                                                                ?>]
                                                                            }
                                                                        ]
                                                                        
                                                                    };
                                                                    new Chart(document.getElementById("lineaguanobebedouro").getContext("2d")).Line(lineChartData);
                                                            </script>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div> 
                                            
                                            <div class="clear pt-20"></div> 

                                            <!-- Kids -->
                                            <div class="graph">
                                                <div class="graph-grid">
                                                    <div class="column width-6">
                                                        <div class="box rounded border-purple medium shadow">
                                                            <h4 class="pt-10">Comparativo de <strong>Kids</strong> presentes (mês).</h4>
                                                             <label class="pt-0"><span class="icon-dot-single small color-blue"></span> Ano Atual</label>
                                                            <!-- <span class="icon-dot-single large color-navy"></span> <span>Ano Atual.</span> -->

                                                            <div class="analise"><canvas id="lineKC" width="550px"></canvas></div>
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
                                                                                label:'Arrecadação:',
                                                                                fillColor : "transparent",
                                                                                strokeColor : "#003df5ff",
                                                                                pointColor : "#003df5ff",
                                                                                pointStrokeColor : "#003df5ff",
                                                                                data : [<?php
                                                                                        $ms10N='0';
                                                                                        for($ms10=0; $ms10 < '12'; $ms10++){
                                                                                            $ms10N++;
                                                                                            if($ms10N < 12): $vrg=','; else: $vrg=''; endif;
                                                                                            if($ms10N < 10): $ms10N='0'.$ms10N; else: $ms10N = $ms10N; endif; //04 - 10
                                                                                            $bMp = "SELECT mes, sum(kids) as kidsnoculto FROM relatoriodeculto WHERE NOT (kids='') AND campusrelator='$membrodocampus' AND mes='$ms10N' AND ano='$ano' GROUP BY mes";
                                                                                            $rMp = mysqli_query($con, $bMp);
                                                                                                $dMp = mysqli_fetch_array($rMp);
                                                                                            if(empty($dMp['kidsnoculto'])):
                                                                                                echo '0'.$vrg;
                                                                                            else:
                                                                                                echo $dMp['kidsnoculto'].$vrg;
                                                                                            endif;
                                                                                        }
                                                                                ?>]
                                                                            }
                                                                        ]
                                                                        
                                                                    };
                                                                    new Chart(document.getElementById("lineKC").getContext("2d")).Line(lineChartData);
                                                            </script>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div> 
                                            
                                            <!-- Copo para água -->
                                            <div class="graph">
                                                <div class="graph-grid">
                                                    <div class="column width-6">
                                                        <div class="box rounded border-purple medium shadow">
                                                            <h4 class="pt-10">Acompanhamento <strong>copos p/ água / Nº de cultos</strong></h4>
                                                             <label class="pt-0"><span class="icon-dot-single small color-blue"></span> Teve copo <span class="icon-dot-single small color-black"></span> Culto sem copo</label>

                                                            <div class="analise"><canvas id="lineCopoAgua" width="550px"></canvas></div>
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
                                                                                label:'Arrecadação:',
                                                                                fillColor : "transparent",
                                                                                strokeColor : "#003df5ff",
                                                                                pointColor : "#003df5ff",
                                                                                pointStrokeColor : "#003df5ff",
                                                                                data : [<?php
                                                                                        $ms10N='0';
                                                                                        for($ms10=0; $ms10 < '12'; $ms10++){
                                                                                            $ms10N++;
                                                                                            if($ms10N < 12): $vrg=','; else: $vrg=''; endif;
                                                                                            if($ms10N < 10): $ms10N='0'.$ms10N; else: $ms10N = $ms10N; endif; //04 - 10
                                                                                            $bOp = "SELECT mes, sum(copos) as temcopo FROM relatoriodeculto WHERE NOT (copos='') AND campusrelator='$membrodocampus' AND mes='$ms10N' AND ano='$ano' GROUP BY mes";
                                                                                            $rOp = mysqli_query($con, $bOp);
                                                                                                $dOp = mysqli_fetch_array($rOp);
                                                                                            if(empty($dOp['temcopo'])):
                                                                                                echo '0'.$vrg;
                                                                                            else:
                                                                                                echo $dOp['temcopo'].$vrg;
                                                                                            endif;
                                                                                        }
                                                                                ?>]
                                                                            },{
                                                                                label:'Arrecadação:',
                                                                                fillColor : "transparent",
                                                                                strokeColor : "#003df5ff",
                                                                                pointColor : "#003df5ff",
                                                                                pointStrokeColor : "#003df5ff",
                                                                                data : [<?php
                                                                                        $ms10N='0';
                                                                                        for($ms10=0; $ms10 < '12'; $ms10++){
                                                                                            $ms10N++;
                                                                                            if($ms10N < 12): $vrg=','; else: $vrg=''; endif;
                                                                                            if($ms10N < 10): $ms10N='0'.$ms10N; else: $ms10N = $ms10N; endif; //04 - 10
                                                                                            $bOp = "SELECT mes, count(mes) as cultossemcopos FROM relatoriodeculto WHERE NOT (copos='') AND campusrelator='$membrodocampus' AND mes='$ms10N' AND ano='$ano' GROUP BY mes";
                                                                                            $rOp = mysqli_query($con, $bOp);
                                                                                                $dOp = mysqli_fetch_array($rOp);
                                                                                            if(empty($dOp['cultossemcopos'])):
                                                                                                echo '0'.$vrg;
                                                                                            else:
                                                                                                echo $dOp['cultossemcopos'].$vrg;
                                                                                            endif;
                                                                                        }
                                                                                ?>]
                                                                            }
                                                                        ]
                                                                        
                                                                    };
                                                                    new Chart(document.getElementById("lineCopoAgua").getContext("2d")).Line(lineChartData);
                                                            </script>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div> 
                                            
                                            <div class="clear pt-20"></div> 
                                            
                                            <!-- papel higiênico -->
                                            <div class="graph">
                                                <div class="graph-grid">
                                                    <div class="column width-6">
                                                        <div class="box rounded border-purple medium shadow">
                                                            <h4 class="pt-10">Acompanhamento <strong>papel higiênico</strong></h4>
                                                             <label class="pt-0"><span class="icon-dot-single small color-blue"></span> Teve papel <span class="icon-dot-single small color-black"></span> Culto sem papel</label>

                                                            <div class="analise"><canvas id="linepapelhigi" width="550px"></canvas></div>
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
                                                                                label:'Tem papel:',
                                                                                fillColor : "transparent",
                                                                                strokeColor : "#003df5ff",
                                                                                pointColor : "#003df5ff",
                                                                                pointStrokeColor : "#003df5ff",
                                                                                data : [<?php
                                                                                        $ms10N='0';
                                                                                        for($ms10=0; $ms10 < '12'; $ms10++){
                                                                                            $ms10N++;
                                                                                            if($ms10N < 12): $vrg=','; else: $vrg=''; endif;
                                                                                            if($ms10N < 10): $ms10N='0'.$ms10N; else: $ms10N = $ms10N; endif; //04 - 10
                                                                                            $bOp = "SELECT mes, sum(papelhigienico) as tempapel FROM relatoriodeculto WHERE NOT (papelhigienico='') AND campusrelator='$membrodocampus' AND mes='$ms10N' AND ano='$ano' GROUP BY mes";
                                                                                            $rOp = mysqli_query($con, $bOp);
                                                                                                $dOp = mysqli_fetch_array($rOp);
                                                                                            if(empty($dOp['tempapel'])):
                                                                                                echo '0'.$vrg;
                                                                                            else:
                                                                                                echo $dOp['tempapel'].$vrg;
                                                                                            endif;
                                                                                        }
                                                                                ?>]
                                                                            },{
                                                                                label:'Sem papel:',
                                                                                fillColor : "transparent",
                                                                                strokeColor : "#003df5ff",
                                                                                pointColor : "#003df5ff",
                                                                                pointStrokeColor : "#003df5ff",
                                                                                data : [<?php
                                                                                        $ms10N='0';
                                                                                        for($ms10=0; $ms10 < '12'; $ms10++){
                                                                                            $ms10N++;
                                                                                            if($ms10N < 12): $vrg=','; else: $vrg=''; endif;
                                                                                            if($ms10N < 10): $ms10N='0'.$ms10N; else: $ms10N = $ms10N; endif; //04 - 10
                                                                                            $bOp = "SELECT mes, count(mes) as cultossempapel FROM relatoriodeculto WHERE NOT (papelhigienico='') AND campusrelator='$membrodocampus' AND mes='$ms10N' AND ano='$ano' GROUP BY mes";
                                                                                            $rOp = mysqli_query($con, $bOp);
                                                                                                $dOp = mysqli_fetch_array($rOp);
                                                                                            if(empty($dOp['cultossempapel'])):
                                                                                                echo '0'.$vrg;
                                                                                            else:
                                                                                                echo $dOp['cultossempapel'].$vrg;
                                                                                            endif;
                                                                                        }
                                                                                ?>]
                                                                            }
                                                                        ]
                                                                        
                                                                    };
                                                                    new Chart(document.getElementById("linepapelhigi").getContext("2d")).Line(lineChartData);
                                                            </script>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div> 
                                            
                                            <!-- papel de mão -->
                                            <div class="graph">
                                                <div class="graph-grid">
                                                    <div class="column width-6">
                                                        <div class="box rounded border-purple medium shadow">
                                                            <h4 class="pt-10">Acompanhamento <strong>papel de mão</strong></h4>
                                                             <label class="pt-0"><span class="icon-dot-single small color-blue"></span> Teve papel <span class="icon-dot-single small color-black"></span> Culto sem papel</label>

                                                            <div class="analise"><canvas id="linepapelmão" width="550px"></canvas></div>
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
                                                                                label:'Tem papel:',
                                                                                fillColor : "transparent",
                                                                                strokeColor : "#003df5ff",
                                                                                pointColor : "#003df5ff",
                                                                                pointStrokeColor : "#003df5ff",
                                                                                data : [<?php
                                                                                        $ms10N='0';
                                                                                        for($ms10=0; $ms10 < '12'; $ms10++){
                                                                                            $ms10N++;
                                                                                            if($ms10N < 12): $vrg=','; else: $vrg=''; endif;
                                                                                            if($ms10N < 10): $ms10N='0'.$ms10N; else: $ms10N = $ms10N; endif; //04 - 10
                                                                                            $bOp = "SELECT mes, sum(papelmao) as tempapelMao FROM relatoriodeculto WHERE NOT (papelmao='') AND campusrelator='$membrodocampus' AND mes='$ms10N' AND ano='$ano' GROUP BY mes";
                                                                                            $rOp = mysqli_query($con, $bOp);
                                                                                                $dOp = mysqli_fetch_array($rOp);
                                                                                            if(empty($dOp['tempapelMao'])):
                                                                                                echo '0'.$vrg;
                                                                                            else:
                                                                                                echo $dOp['tempapelMao'].$vrg;
                                                                                            endif;
                                                                                        }
                                                                                ?>]
                                                                            },{
                                                                                label:'Sem papel:',
                                                                                fillColor : "transparent",
                                                                                strokeColor : "#003df5ff",
                                                                                pointColor : "#003df5ff",
                                                                                pointStrokeColor : "#003df5ff",
                                                                                data : [<?php
                                                                                        $ms10N='0';
                                                                                        for($ms10=0; $ms10 < '12'; $ms10++){
                                                                                            $ms10N++;
                                                                                            if($ms10N < 12): $vrg=','; else: $vrg=''; endif;
                                                                                            if($ms10N < 10): $ms10N='0'.$ms10N; else: $ms10N = $ms10N; endif; //04 - 10
                                                                                            $bOp = "SELECT mes, count(mes) as cultossempapelMao FROM relatoriodeculto WHERE NOT (papelmao='') AND campusrelator='$membrodocampus' AND mes='$ms10N' AND ano='$ano' GROUP BY mes";
                                                                                            $rOp = mysqli_query($con, $bOp);
                                                                                                $dOp = mysqli_fetch_array($rOp);
                                                                                            if(empty($dOp['cultossempapelMao'])):
                                                                                                echo '0'.$vrg;
                                                                                            else:
                                                                                                echo $dOp['cultossempapelMao'].$vrg;
                                                                                            endif;
                                                                                        }
                                                                                ?>]
                                                                            }
                                                                        ]
                                                                        
                                                                    };
                                                                    new Chart(document.getElementById("linepapelmão").getContext("2d")).Line(lineChartData);
                                                            </script>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div> 
                                            
                                            <div class="clear pt-20"></div> 
                                            
                                            <!-- Lanch dos Kids -->
                                            <div class="graph">
                                                <div class="graph-grid">
                                                    <div class="column width-6">
                                                        <div class="box rounded border-purple medium shadow">
                                                            <h4 class="pt-10">Acompanhamento <strong>lanche</strong> dos Kids</h4>
                                                             <label class="pt-0"><span class="icon-dot-single small color-blue"></span> Teve lanche <span class="icon-dot-single small color-black"></span> Culto sem lanche</label>

                                                            <div class="analise"><canvas id="lanchedoskids" width="550px"></canvas></div>
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
                                                                                label:'Com lanche:',
                                                                                fillColor : "transparent",
                                                                                strokeColor : "#003df5ff",
                                                                                pointColor : "#003df5ff",
                                                                                pointStrokeColor : "#003df5ff",
                                                                                data : [<?php
                                                                                        $ms10N='0';
                                                                                        for($ms10=0; $ms10 < '12'; $ms10++){
                                                                                            $ms10N++;
                                                                                            if($ms10N < 12): $vrg=','; else: $vrg=''; endif;
                                                                                            if($ms10N < 10): $ms10N='0'.$ms10N; else: $ms10N = $ms10N; endif; //04 - 10
                                                                                            $bOp = "SELECT mes, sum(lanchekids) as tevelanche FROM relatoriodeculto WHERE NOT (lanchekids='') AND campusrelator='$membrodocampus' AND mes='$ms10N' AND ano='$ano' GROUP BY mes";
                                                                                            $rOp = mysqli_query($con, $bOp);
                                                                                                $dOp = mysqli_fetch_array($rOp);
                                                                                            if(empty($dOp['tevelanche'])):
                                                                                                echo '0'.$vrg;
                                                                                            else:
                                                                                                echo $dOp['tevelanche'].$vrg;
                                                                                            endif;
                                                                                        }
                                                                                ?>]
                                                                            },{
                                                                                label:'Sem lanche:',
                                                                                fillColor : "transparent",
                                                                                strokeColor : "#003df5ff",
                                                                                pointColor : "#003df5ff",
                                                                                pointStrokeColor : "#003df5ff",
                                                                                data : [<?php
                                                                                        $ms10N='0';
                                                                                        for($ms10=0; $ms10 < '12'; $ms10++){
                                                                                            $ms10N++;
                                                                                            if($ms10N < 12): $vrg=','; else: $vrg=''; endif;
                                                                                            if($ms10N < 10): $ms10N='0'.$ms10N; else: $ms10N = $ms10N; endif; //04 - 10
                                                                                            $bOp = "SELECT mes, count(mes) as cultosemlanche FROM relatoriodeculto WHERE NOT (lanchekids='') AND campusrelator='$membrodocampus' AND mes='$ms10N' AND ano='$ano' GROUP BY mes";
                                                                                            $rOp = mysqli_query($con, $bOp);
                                                                                                $dOp = mysqli_fetch_array($rOp);
                                                                                            if(empty($dOp['cultosemlanche'])):
                                                                                                echo '0'.$vrg;
                                                                                            else:
                                                                                                echo $dOp['cultosemlanche'].$vrg;
                                                                                            endif;
                                                                                        }
                                                                                ?>]
                                                                            }
                                                                        ]
                                                                        
                                                                    };
                                                                    new Chart(document.getElementById("lanchedoskids").getContext("2d")).Line(lineChartData);
                                                            </script>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div> 
                                            
                                            <!-- Igreja limpa -->
                                            <div class="graph">
                                                <div class="graph-grid">
                                                    <div class="column width-6">
                                                        <div class="box rounded border-purple medium shadow">
                                                            <h4 class="pt-10">Acompanhamento de <strong>igreja limpa</strong></h4>
                                                             <label class="pt-0"><span class="icon-dot-single small color-blue"></span> Limpa <span class="icon-dot-single small color-black"></span> Suja</label>

                                                            <div class="analise"><canvas id="igrejalimpa" width="550px"></canvas></div>
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
                                                                                label:'Tem papel:',
                                                                                fillColor : "transparent",
                                                                                strokeColor : "#003df5ff",
                                                                                pointColor : "#003df5ff",
                                                                                pointStrokeColor : "#003df5ff",
                                                                                data : [<?php
                                                                                        $ms10N='0';
                                                                                        for($ms10=0; $ms10 < '12'; $ms10++){
                                                                                            $ms10N++;
                                                                                            if($ms10N < 12): $vrg=','; else: $vrg=''; endif;
                                                                                            if($ms10N < 10): $ms10N='0'.$ms10N; else: $ms10N = $ms10N; endif; //04 - 10
                                                                                            $bOp = "SELECT mes, sum(igrejalimpa) as estavalimpa FROM relatoriodeculto WHERE NOT (igrejalimpa='') AND campusrelator='$membrodocampus' AND mes='$ms10N' AND ano='$ano' GROUP BY mes";
                                                                                            $rOp = mysqli_query($con, $bOp);
                                                                                                $dOp = mysqli_fetch_array($rOp);
                                                                                            if(empty($dOp['estavalimpa'])):
                                                                                                echo '0'.$vrg;
                                                                                            else:
                                                                                                echo $dOp['estavalimpa'].$vrg;
                                                                                            endif;
                                                                                        }
                                                                                ?>]
                                                                            },{
                                                                                label:'Sem papel:',
                                                                                fillColor : "transparent",
                                                                                strokeColor : "#003df5ff",
                                                                                pointColor : "#003df5ff",
                                                                                pointStrokeColor : "#003df5ff",
                                                                                data : [<?php
                                                                                        $ms10N='0';
                                                                                        for($ms10=0; $ms10 < '12'; $ms10++){
                                                                                            $ms10N++;
                                                                                            if($ms10N < 12): $vrg=','; else: $vrg=''; endif;
                                                                                            if($ms10N < 10): $ms10N='0'.$ms10N; else: $ms10N = $ms10N; endif; //04 - 10
                                                                                            $bOp = "SELECT mes, count(mes) as estavasuja FROM relatoriodeculto WHERE NOT (igrejalimpa='') AND campusrelator='$membrodocampus' AND mes='$ms10N' AND ano='$ano' GROUP BY mes";
                                                                                            $rOp = mysqli_query($con, $bOp);
                                                                                                $dOp = mysqli_fetch_array($rOp);
                                                                                            if(empty($dOp['estavasuja'])):
                                                                                                echo '0'.$vrg;
                                                                                            else:
                                                                                                echo $dOp['estavasuja'].$vrg;
                                                                                            endif;
                                                                                        }
                                                                                ?>]
                                                                            }
                                                                        ]
                                                                        
                                                                    };
                                                                    new Chart(document.getElementById("igrejalimpa").getContext("2d")).Line(lineChartData);
                                                            </script>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div> 
                                            
                                            <div class="clear pt-20"></div> 
                                            
                                            <!-- Som bom -->
                                            <div class="graph">
                                                <div class="graph-grid">
                                                    <div class="column width-6">
                                                        <div class="box rounded border-purple medium shadow">
                                                            <h4 class="pt-10">Qualidade do <strong>som</strong></h4>
                                                             <label class="pt-0"><span class="icon-dot-single small color-blue"></span> Som bom<span class="icon-dot-single small color-black"></span> Som ruim</label>

                                                            <div class="analise"><canvas id="qualidadedosom" width="550px"></canvas></div>
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
                                                                                label:'Tem papel:',
                                                                                fillColor : "transparent",
                                                                                strokeColor : "#003df5ff",
                                                                                pointColor : "#003df5ff",
                                                                                pointStrokeColor : "#003df5ff",
                                                                                data : [<?php
                                                                                        $ms10N='0';
                                                                                        for($ms10=0; $ms10 < '12'; $ms10++){
                                                                                            $ms10N++;
                                                                                            if($ms10N < 12): $vrg=','; else: $vrg=''; endif;
                                                                                            if($ms10N < 10): $ms10N='0'.$ms10N; else: $ms10N = $ms10N; endif; //04 - 10
                                                                                            $bOp = "SELECT mes, sum(qualidadesom) as sombom FROM relatoriodeculto WHERE NOT (qualidadesom='') AND campusrelator='$membrodocampus' AND mes='$ms10N' AND ano='$ano' GROUP BY mes";
                                                                                            $rOp = mysqli_query($con, $bOp);
                                                                                                $dOp = mysqli_fetch_array($rOp);
                                                                                            if(empty($dOp['sombom'])):
                                                                                                echo '0'.$vrg;
                                                                                            else:
                                                                                                echo $dOp['sombom'].$vrg;
                                                                                            endif;
                                                                                        }
                                                                                ?>]
                                                                            },{
                                                                                label:'Sem papel:',
                                                                                fillColor : "transparent",
                                                                                strokeColor : "#003df5ff",
                                                                                pointColor : "#003df5ff",
                                                                                pointStrokeColor : "#003df5ff",
                                                                                data : [<?php
                                                                                        $ms10N='0';
                                                                                        for($ms10=0; $ms10 < '12'; $ms10++){
                                                                                            $ms10N++;
                                                                                            if($ms10N < 12): $vrg=','; else: $vrg=''; endif;
                                                                                            if($ms10N < 10): $ms10N='0'.$ms10N; else: $ms10N = $ms10N; endif; //04 - 10
                                                                                            $bOp = "SELECT mes, count(mes) as somruim FROM relatoriodeculto WHERE NOT (qualidadesom='') AND campusrelator='$membrodocampus' AND mes='$ms10N' AND ano='$ano' GROUP BY mes";
                                                                                            $rOp = mysqli_query($con, $bOp);
                                                                                                $dOp = mysqli_fetch_array($rOp);
                                                                                            if(empty($dOp['somruim'])):
                                                                                                echo '0'.$vrg;
                                                                                            else:
                                                                                                echo $dOp['somruim'].$vrg;
                                                                                            endif;
                                                                                        }
                                                                                ?>]
                                                                            }
                                                                        ]
                                                                        
                                                                    };
                                                                    new Chart(document.getElementById("qualidadedosom").getContext("2d")).Line(lineChartData);
                                                            </script>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div> 
                                            
                                            <!-- Culto com chuva -->
                                            <div class="graph">
                                                <div class="graph-grid">
                                                    <div class="column width-6">
                                                        <div class="box rounded border-purple medium shadow">
                                                            <h4 class="pt-10">Cultos com <strong>chuva</strong></h4>
                                                             <label class="pt-0"><span class="icon-dot-single small color-blue"></span> Com chuva <span class="icon-dot-single small color-black"></span> Sem chuva</label>

                                                            <div class="analise"><canvas id="igrejalimpa" width="550px"></canvas></div>
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
                                                                                label:'Tem papel:',
                                                                                fillColor : "transparent",
                                                                                strokeColor : "#003df5ff",
                                                                                pointColor : "#003df5ff",
                                                                                pointStrokeColor : "#003df5ff",
                                                                                data : [<?php
                                                                                        $ms10N='0';
                                                                                        for($ms10=0; $ms10 < '12'; $ms10++){
                                                                                            $ms10N++;
                                                                                            if($ms10N < 12): $vrg=','; else: $vrg=''; endif;
                                                                                            if($ms10N < 10): $ms10N='0'.$ms10N; else: $ms10N = $ms10N; endif; //04 - 10
                                                                                            $bOp = "SELECT mes, sum(chuva) as chovendo FROM relatoriodeculto WHERE NOT (chuva='') AND campusrelator='$membrodocampus' AND mes='$ms10N' AND ano='$ano' GROUP BY mes";
                                                                                            $rOp = mysqli_query($con, $bOp);
                                                                                                $dOp = mysqli_fetch_array($rOp);
                                                                                            if(empty($dOp['chovendo'])):
                                                                                                echo '0'.$vrg;
                                                                                            else:
                                                                                                echo $dOp['chovendo'].$vrg;
                                                                                            endif;
                                                                                        }
                                                                                ?>]
                                                                            },{
                                                                                label:'Sem papel:',
                                                                                fillColor : "transparent",
                                                                                strokeColor : "#003df5ff",
                                                                                pointColor : "#003df5ff",
                                                                                pointStrokeColor : "#003df5ff",
                                                                                data : [<?php
                                                                                        $ms10N='0';
                                                                                        for($ms10=0; $ms10 < '12'; $ms10++){
                                                                                            $ms10N++;
                                                                                            if($ms10N < 12): $vrg=','; else: $vrg=''; endif;
                                                                                            if($ms10N < 10): $ms10N='0'.$ms10N; else: $ms10N = $ms10N; endif; //04 - 10
                                                                                            $bOp = "SELECT mes, count(mes) as semchuva FROM relatoriodeculto WHERE NOT (chuva='') AND campusrelator='$membrodocampus' AND mes='$ms10N' AND ano='$ano' GROUP BY mes";
                                                                                            $rOp = mysqli_query($con, $bOp);
                                                                                                $dOp = mysqli_fetch_array($rOp);
                                                                                            if(empty($dOp['semchuva'])):
                                                                                                echo '0'.$vrg;
                                                                                            else:
                                                                                                echo $dOp['semchuva'].$vrg;
                                                                                            endif;
                                                                                        }
                                                                                ?>]
                                                                            }
                                                                        ]
                                                                        
                                                                    };
                                                                    new Chart(document.getElementById("igrejalimpa").getContext("2d")).Line(lineChartData);
                                                            </script>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div> 

                                            <div class="clear pt-20"></div> 
                                            
                                            <!-- Live -->
                                            <div class="graph">
                                                <div class="graph-grid">
                                                    <div class="column width-6">
                                                        <div class="box rounded border-purple medium shadow">
                                                            <h4 class="pt-10">Cultos <strong>online (live)</strong></h4>
                                                             <label class="pt-0"><span class="icon-dot-single large color-blue"></span>Cultos com Live 
                                                             <span class="icon-dot-single large color-youtube"></span>Cultos sem live</label>

                                                            <div class="analise"><canvas id="lineDinamicas" width="550px"></canvas></div>
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
                                                                                label:'Lives:',
                                                                                fillColor : "transparent",
                                                                                strokeColor : "#003df5ff",
                                                                                pointColor : "#003df5ff",
                                                                                pointStrokeColor : "#003df5ff",
                                                                                data : [<?php
                                                                                        $ms10N='0';
                                                                                        for($ms10=0; $ms10 < '12'; $ms10++){
                                                                                            $ms10N++;
                                                                                            if($ms10N < 12): $vrg=','; else: $vrg=''; endif;
                                                                                            if($ms10N < 10): $ms10N='0'.$ms10N; else: $ms10N = $ms10N; endif; //04 - 10
                                                                                            $bOp = "SELECT mes, sum(live) as houvelive FROM relatoriodeculto WHERE NOT (live='') AND campusrelator='$membrodocampus' AND mes='$ms10N' AND ano='$ano' GROUP BY mes";
                                                                                            $rOp = mysqli_query($con, $bOp);
                                                                                                $dOp = mysqli_fetch_array($rOp);
                                                                                            if(empty($dOp['houvelive'])):
                                                                                                echo '0'.$vrg;
                                                                                            else:
                                                                                                echo $dOp['houvelive'].$vrg;
                                                                                            endif;
                                                                                        }
                                                                                ?>]
                                                                            },  {
                                                                                label:'Quantidade de cultos por mês:',
                                                                                fillColor : "transparent",
                                                                                strokeColor : "#ff0000",
                                                                                pointColor : "#ff0000",
                                                                                pointStrokeColor : "#ff0000",
                                                                                data : [<?php
                                                                                        $ms10N='0';
                                                                                        for($ms10=0; $ms10 < '12'; $ms10++){
                                                                                            $ms10N++;
                                                                                            if($ms10N < 12): $vrg=','; else: $vrg=''; endif;
                                                                                            if($ms10N < 10): $ms10N='0'.$ms10N; else: $ms10N = $ms10N; endif; //04 - 10
                                                                                            $bNDin = "SELECT mes, count(mes) as numerodecultos FROM relatoriodeculto WHERE NOT (live='') AND campusrelator='$membrodocampus' AND mes='$ms10N' AND ano='$ano' GROUP BY mes";
                                                                                            $rNDin = mysqli_query($con, $bNDin);
                                                                                                $dNDin = mysqli_fetch_array($rNDin);
                                                                                            if(empty($dNDin['numerodecultos'])):
                                                                                                echo '0'.$vrg;
                                                                                            else:
                                                                                                echo $dNDin['numerodecultos'].$vrg;
                                                                                            endif;
                                                                                        }
                                                                                ?>]
                                                                            }, 
                                                                        ]
                                                                        
                                                                    };
                                                                    new Chart(document.getElementById("lineDinamicas").getContext("2d")).Line(lineChartData);
                                                            </script>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                             <!-- Canal da Live -->
                                            <div class="graph">
                                                <div class="graph-grid">
                                                    <div class="column width-6">
                                                        <div class="box rounded border-purple medium shadow">
                                                            <h4 class="pt-10">Canais das <strong>Lives</strong></h4>
                                                             <!-- <label class="pt-0"><span class="icon-dot-single small color-blue"></span> Nº de Dinâmicas 
                                                             <span class="icon-dot-single small color-green"></span> Nº de células</label> -->

                                                             <div class="analise"><canvas id="canaldalive" height="195px" width="550px"></canvas></div>
                                                            <script>
                                                                var barChartData = {
                                                                    labels: [<?php
                                                                                if($mesnumero < 2): echo "'',"; endif;
                                                                                $bMidia = "SELECT canaldalive, count(canaldalive) as ChanelLive FROM relatoriodeculto WHERE NOT (canaldalive = '') AND ano='$ano' AND campusrelator='$membrodocampus' GROUP BY canaldalive ORDER by canaldalive ASC";
                                                                                    $rMidia = mysqli_query($con, $bMidia);
                                                                                        $TotalMidia = mysqli_num_rows($rMidia);
                                                                                            $ContarMidia = 1;
                                                                                            while($dMidia = mysqli_fetch_array($rMidia)):
                                                                                                $virgula = ( $TotalMidia !== $ContarMidia ) ? ',' : '';
                                                                                                echo '"'.substr($dMidia['canaldalive'], 0, 15).' ('.$dMidia['ChanelLive'].')'.'"'.$virgula;
                                                                                                $ContarMidia++;
                                                                                            endwhile;
                                                                            ?>],
                                                                    datasets: [{
                                                                        fillColor: "#00cad1",
                                                                        strokeColor: "#00cad1",
                                                                        data: [<?php
                                                                                    if($mesnumero < 2): echo "0,"; endif;
                                                                                    $bSegui = "SELECT count(canaldalive) as canallives FROM relatoriodeculto WHERE NOT (canaldalive = '') AND ano='$ano' AND campusrelator='$membrodocampus' GROUP BY canaldalive ORDER BY canaldalive ASC";
                                                                                        $rSegui = mysqli_query($con, $bSegui);
                                                                                            $TotalSegui = mysqli_num_rows($rSegui);
                                                                                                $ContarSegui = 1;
                                                                                                while($dSegui = mysqli_fetch_array($rSegui)):
                                                                                                    $virgula = ( $TotalSegui !== $ContarSegui ) ? ',' : '';
                                                                                                    echo $dSegui['canallives'].$virgula;
                                                                                                    $ContarSegui++;
                                                                                                endwhile;
                                                                                ?>]
                                                                    }]

                                                                };
                                                                new Chart(document.getElementById("canaldalive").getContext(
                                                                    "2d")).Bar(barChartData);
                                                            </script>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="clear pt-20"></div>
                                            
                                            <!-- Horário de inicio -->
                                            <div class="graph">
                                                <div class="graph-grid">
                                                    <div class="column width-6">
                                                        <div class="box rounded border-purple medium shadow">
                                                            <h4 class="pt-10">Horário de <strong>inicio</strong></h4>
                                                             <!-- <label class="pt-0"><span class="icon-dot-single small color-blue"></span> Nº de Dinâmicas 
                                                             <span class="icon-dot-single small color-green"></span> Nº de células</label> -->

                                                             <div class="analise"><canvas id="horarios" height="183px" width="550px"></canvas></div>
                                                            <script>
                                                                var barChartData = {
                                                                    labels: [<?php
                                                                                if($mesnumero < 2): echo "'',"; endif;
                                                                                $bMidia = "SELECT horarioinicio, count(horarioinicio) as lengeHi FROM relatoriodeculto WHERE NOT (horarioinicio = '') AND ano='$ano' AND campusrelator='$membrodocampus' GROUP BY horarioinicio ORDER by horarioinicio ASC";
                                                                                    $rMidia = mysqli_query($con, $bMidia);
                                                                                        $TotalMidia = mysqli_num_rows($rMidia);
                                                                                            $ContarMidia = 1;
                                                                                            while($dMidia = mysqli_fetch_array($rMidia)):
                                                                                                $virgula = ( $TotalMidia !== $ContarMidia ) ? ',' : '';
                                                                                                echo '"'.substr($dMidia['horarioinicio'], 0, 15).' ('.$dMidia['lengeHi'].')'.'"'.$virgula;
                                                                                                $ContarMidia++;
                                                                                            endwhile;
                                                                            ?>],
                                                                    datasets: [{
                                                                        fillColor: "#040a58",
                                                                        strokeColor: "#040a58",
                                                                        data: [<?php
                                                                                    if($mesnumero < 2): echo "0,"; endif;
                                                                                    $bSegui = "SELECT count(horarioinicio) as horariodeinicio FROM relatoriodeculto WHERE NOT (horarioinicio = '') AND ano='$ano'  AND campusrelator='$membrodocampus' GROUP BY horarioinicio ORDER BY horarioinicio ASC";
                                                                                        $rSegui = mysqli_query($con, $bSegui);
                                                                                            $TotalSegui = mysqli_num_rows($rSegui);
                                                                                                $ContarSegui = 1;
                                                                                                while($dSegui = mysqli_fetch_array($rSegui)):
                                                                                                    $virgula = ( $TotalSegui !== $ContarSegui ) ? ',' : '';
                                                                                                    echo $dSegui['horariodeinicio'].$virgula;
                                                                                                    $ContarSegui++;
                                                                                                endwhile;
                                                                                ?>]
                                                                    }]

                                                                };
                                                                new Chart(document.getElementById("horarios").getContext(
                                                                    "2d")).Bar(barChartData);
                                                            </script>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <!-- Horário de termino -->
                                            <div class="graph">
                                                <div class="graph-grid">
                                                    <div class="column width-6">
                                                        <div class="box rounded border-purple medium shadow">
                                                            <h4 class="pt-10">Horário de <strong>Término</strong></h4>
                                                             <!-- <label class="pt-0"><span class="icon-dot-single small color-blue"></span> Nº de Dinâmicas 
                                                             <span class="icon-dot-single small color-green"></span> Nº de células</label> -->

                                                             <div class="analise"><canvas id="horariosT" height="183px" width="550px"></canvas></div>
                                                            <script>
                                                                var barChartData = {
                                                                    labels: [<?php
                                                                                if($mesnumero < 2): echo "'',"; endif;
                                                                                $bMidia = "SELECT horariotermino, count(horariotermino) as legenHt FROM relatoriodeculto WHERE NOT (horariotermino = '') AND ano='$ano' AND campusrelator='$membrodocampus' GROUP BY horariotermino ORDER by horariotermino ASC";
                                                                                    $rMidia = mysqli_query($con, $bMidia);
                                                                                        $TotalMidia = mysqli_num_rows($rMidia);
                                                                                            $ContarMidia = 1;
                                                                                            while($dMidia = mysqli_fetch_array($rMidia)):
                                                                                                $virgula = ( $TotalMidia !== $ContarMidia ) ? ',' : '';
                                                                                                echo '"'.substr($dMidia['horariotermino'], 0, 15).' ('.$dMidia['legenHt'].')'.'"'.$virgula;
                                                                                                $ContarMidia++;
                                                                                            endwhile;
                                                                            ?>],
                                                                    datasets: [{
                                                                        fillColor: "#ff0000",
                                                                        strokeColor: "#ff0000",
                                                                        data: [<?php
                                                                                    if($mesnumero < 2): echo "0,"; endif;
                                                                                    $bSegui = "SELECT count(horariotermino) as horariodotermino FROM relatoriodeculto WHERE NOT (horariotermino = '') AND ano='$ano' AND campusrelator='$membrodocampus' GROUP BY horariotermino ORDER BY horariotermino ASC";
                                                                                        $rSegui = mysqli_query($con, $bSegui);
                                                                                            $TotalSegui = mysqli_num_rows($rSegui);
                                                                                                $ContarSegui = 1;
                                                                                                while($dSegui = mysqli_fetch_array($rSegui)):
                                                                                                    $virgula = ( $TotalSegui !== $ContarSegui ) ? ',' : '';
                                                                                                    echo $dSegui['horariodotermino'].$virgula;
                                                                                                    $ContarSegui++;
                                                                                                endwhile;
                                                                                ?>]
                                                                    }]

                                                                };
                                                                new Chart(document.getElementById("horariosT").getContext(
                                                                    "2d")).Bar(barChartData);
                                                            </script>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="tabs-movimentacao">
                                        <div class="tab-content">
                                            <div class="column width-12">
                                                <div class="tabs line-nav horizontal rounded left">
                                                    <ul class="tab-nav small">
                                                        <li class="active">
                                                            <a href="#tabs-evangelistas">
                                                                Melhores <strong>Evangelistas</strong>
                                                            </a>
                                                        </li>
                                                        <li class="">
                                                            <a href="#tabs-constancia">
                                                                Mais <strong>constantes</strong>
                                                            </a>
                                                        </li>
                                                        <li class="">
                                                            <a href="#tabs-comprometimento">
                                                                Mais <strong>responsáveis</strong>
                                                            </a>
                                                        </li>
                                                    </ul>
                                                    <div class="tab-panes">
                                                        <div id="tabs-evangelistas">
                                                            <table class="table small rounded striped">
                                                                <thead>
                                                                    <tr class="bkg-charcoal color-white center">
                                                                        <th>
                                                                            <span class="text-medium">
                                                                                Nº
                                                                            </span>
                                                                        </th>
                                                                        <th>
                                                                            <span class="text-medium">
                                                                                Ministro (a)
                                                                            </span>
                                                                        </th>
                                                                        <th>
                                                                            <span class="text-medium">
                                                                                Evangelizador
                                                                            </span>
                                                                        </th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                        <?php
                                                                            $bMovFin="SELECT id, ministro, sum(trouxevisitantes) as evangelizador FROM relatoriodeculto WHERE NOT (trouxevisitantes='') AND ano='$ano' AND campusrelator='$membrodocampus' GROUP BY ministro ORDER by evangelizador DESC";
                                                                            $rMovFin=mysqli_query($con, $bMovFin);
                                                                                    $nmf = 0;
                                                                                while($dRC=mysqli_fetch_array($rMovFin)):
                                                                                    $nmf++;
                                                                        ?>
                                                                    <tr>
                                                                        <td class="color-black" width="5%" valign="medium">
                                                                            <span class="text-medium">
                                                                                <?php echo $nmf;?>º
                                                                            </span>
                                                                        </td>
                                                                        <td class="color-black" width="75%" valign="medium">
                                                                            <span class="text-medium">
                                                                                <?php echo $dRC['ministro'];?>
                                                                            </span>
                                                                        </td>
                                                                        <td class="color-black" width="20%" valign="medium">
                                                                            <span class="text-medium">
                                                                                <?php echo $dRC['evangelizador'];?>
                                                                            </span>
                                                                        </td>
                                                                    </tr>
                                                                    <? endwhile;?>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                        <div id="tabs-constancia">
                                                            <table class="table small rounded striped">
                                                                <thead>
                                                                    <tr class="bkg-charcoal color-white center">
                                                                        <th>
                                                                            <span class="text-medium">
                                                                                Nº
                                                                            </span>
                                                                        </th>
                                                                        <th>
                                                                            <span class="text-medium">
                                                                                Ministro (a)
                                                                            </span>
                                                                        </th>
                                                                        <th>
                                                                            <span class="text-medium">
                                                                                Constância
                                                                            </span>
                                                                        </th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                        <?php
                                                                            $bMovFin="SELECT id, ministro, sum(presencanaoracao) as constantes FROM relatoriodeculto WHERE NOT (presencanaoracao='') AND ano='$ano' AND campusrelator='$membrodocampus' GROUP BY ministro ORDER by constantes DESC";
                                                                            $rMovFin=mysqli_query($con, $bMovFin);
                                                                                    $nCons = 0;
                                                                                while($dRC=mysqli_fetch_array($rMovFin)):
                                                                                    $nCons++;
                                                                        ?>
                                                                    <tr>
                                                                        <td class="color-black" width="5%" valign="medium">
                                                                            <span class="text-medium">
                                                                                <?php echo $nCons;?>º
                                                                            </span>
                                                                        </td>
                                                                        <td class="color-black" width="75%" valign="medium">
                                                                            <span class="text-medium">
                                                                                <?php echo $dRC['ministro'];?>
                                                                            </span>
                                                                        </td>
                                                                        <td class="color-black" width="20%" valign="medium">
                                                                            <span class="text-medium">
                                                                                <?php echo $dRC['constantes'];?>
                                                                            </span>
                                                                        </td>
                                                                    </tr>
                                                                    <? endwhile;?>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                        <div id="tabs-comprometimento">
                                                            <table class="table small rounded striped">
                                                                <thead>
                                                                    <tr class="bkg-charcoal color-white center">
                                                                        <th>
                                                                            <span class="text-medium">
                                                                                Nº
                                                                            </span>
                                                                        </th>
                                                                        <th>
                                                                            <span class="text-medium">
                                                                                Ministro (a)
                                                                            </span>
                                                                        </th>
                                                                        <th>
                                                                            <span class="text-medium">
                                                                                Responsabilidade
                                                                            </span>
                                                                        </th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                        <?php
                                                                            $bMovFin="SELECT id, ministro, sum(funcoesministeriais) as comprometimento FROM relatoriodeculto WHERE NOT (funcoesministeriais='') AND ano='$ano' AND campusrelator='$membrodocampus' GROUP BY ministro ORDER by comprometimento DESC";
                                                                            $rMovFin=mysqli_query($con, $bMovFin);
                                                                                    $nCons = 0;
                                                                                while($dRC=mysqli_fetch_array($rMovFin)):
                                                                                    $nCons++;
                                                                        ?>
                                                                    <tr>
                                                                        <td class="color-black" width="5%" valign="medium">
                                                                            <span class="text-medium">
                                                                                <?php echo $nCons;?>º
                                                                            </span>
                                                                        </td>
                                                                        <td class="color-black" width="75%" valign="medium">
                                                                            <span class="text-medium">
                                                                                <?php echo $dRC['ministro'];?>
                                                                            </span>
                                                                        </td>
                                                                        <td class="color-black" width="20%" valign="medium">
                                                                            <span class="text-medium">
                                                                                <?php echo $dRC['comprometimento'];?>
                                                                            </span>
                                                                        </td>
                                                                    </tr>
                                                                    <? endwhile;?>
                                                                </tbody>
                                                            </table>
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