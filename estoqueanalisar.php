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
                                        <a href="#tabs-movimentacao"><strong>Estoque</strong> em tabela</a>
                                    </li>
                                </ul>
                                <div class="tab-panes">
                                    <div id="tabs-visaogeral" class="active animate">
                                        <div class="tab-content ">

                                            <!-- Produtos mais consumidos -->
                                            <div class="graph">
                                                <div class="graph-grid">
                                                    <div class="column width-12">
                                                        <div class="box rounded border-purple medium shadow">
                                                            <h4 class="pt-10">Produtos de <strong>maior</strong> consumo (Esgotados).</h4>
                                                            <!-- <label class="pt-0"><span class="icon-dot-single small color-blue"></span> Nº de Dinâmicas 
                                                            <span class="icon-dot-single small color-green"></span> Nº de células</label> -->

                                                            <div class="analise"><canvas id="produtosmaisconsumidos" height="183px" width="1200px"></canvas></div>
                                                            <script>
                                                            var barChartData = {
                                                                labels: [<?php
                                                                            if($mesnumero < 2): echo "'',"; endif;
                                                                                $bMidia = "SELECT produto, count(situacaodoproduto) as ProConsu FROM estoque WHERE situacaodoproduto = 'Esgotado' AND campus='$membrodocampus' GROUP BY produto ORDER by produto ASC";
                                                                                $rMidia = mysqli_query($con, $bMidia);
                                                                                    $TotalMidia = mysqli_num_rows($rMidia);
                                                                                        $ContarMidia = 1;
                                                                                        while($dMidia = mysqli_fetch_array($rMidia)):
                                                                                            $virgula = ( $TotalMidia !== $ContarMidia ) ? ',' : '';
                                                                                            echo '"'.substr($dMidia['produto'], 0, 13).' ('.$dMidia['ProConsu'].')"'.$virgula;
                                                                                            $ContarMidia++;
                                                                                        endwhile;
                                                                        ?>],
                                                                datasets: [{
                                                                    fillColor: "#f15901",
                                                                    strokeColor: "#f15901",
                                                                    data: [<?php
                                                                                if($mesnumero < 2): echo "0,"; endif;
                                                                                $bSegui = "SELECT count(produto) as ProdEsto FROM estoque WHERE situacaodoproduto = 'Esgotado' AND campus='$membrodocampus' GROUP BY produto ORDER BY produto ASC";
                                                                                    $rSegui = mysqli_query($con, $bSegui);
                                                                                        $TotalSegui = mysqli_num_rows($rSegui);
                                                                                            $ContarSegui = 1;
                                                                                            while($dSegui = mysqli_fetch_array($rSegui)):
                                                                                                $virgula = ( $TotalSegui !== $ContarSegui ) ? ',' : '';
                                                                                                echo $dSegui['ProdEsto'].$virgula;
                                                                                                $ContarSegui++;
                                                                                            endwhile;
                                                                            ?>]
                                                                }]

                                                            };
                                                            new Chart(document.getElementById("produtosmaisconsumidos").getContext(
                                                                "2d")).Bar(barChartData);
                                                            </script>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="clear pt-20"></div>

                                            <!-- Produtos Produtos que vencem em um mês -->
                                            <div class="graph">
                                                <div class="graph-grid">
                                                    <div class="column width-12">
                                                        <div class="box rounded border-purple medium shadow">
                                                            <h4 class="pt-10">Produtos c/ vendimento em até <strong>um mês</strong>.</h4>
                                                            <!-- <label class="pt-0"><span class="icon-dot-single small color-blue"></span> Nº de Dinâmicas 
                                                            <span class="icon-dot-single small color-green"></span> Nº de células</label> -->
                                                            <?
                                                                $vencProMes= date(('Y-m-d'), strtotime($dataeng.'+ 1 months'));
                                                            ?>
                                                            <div class="analise"><canvas id="vencemnoProMes" height="183px" width="1200px"></canvas></div>
                                                            <script>
                                                            var barChartData = {
                                                                labels: [<?php
                                                                            if($mesnumero < 2): echo "'',"; endif;
                                                                                $bMidia = "SELECT produto, count(situacaodoproduto) as ProConsu FROM estoque WHERE situacaodoproduto != 'Esgotado' AND validade <= '$vencProMes' AND campus='$membrodocampus' GROUP BY produto ORDER by produto ASC";
                                                                                $rMidia = mysqli_query($con, $bMidia);
                                                                                    $TotalMidia = mysqli_num_rows($rMidia);
                                                                                        $ContarMidia = 1;
                                                                                        while($dMidia = mysqli_fetch_array($rMidia)):
                                                                                            $virgula = ( $TotalMidia !== $ContarMidia ) ? ',' : '';
                                                                                            echo '"'.substr($dMidia['produto'], 0, 13).' ('.$dMidia['ProConsu'].')"'.$virgula;
                                                                                            $ContarMidia++;
                                                                                        endwhile;
                                                                        ?>],
                                                                datasets: [{
                                                                    fillColor: "#f15901",
                                                                    strokeColor: "#f15901",
                                                                    data: [<?php
                                                                                if($mesnumero < 2): echo "0,"; endif;
                                                                                $bSegui = "SELECT count(produto) as VenceNoProMes FROM estoque WHERE situacaodoproduto != 'Esgotado' AND validade <= '$vencProMes' AND campus='$membrodocampus' GROUP BY produto ORDER BY produto ASC";
                                                                                    $rSegui = mysqli_query($con, $bSegui);
                                                                                        $TotalSegui = mysqli_num_rows($rSegui);
                                                                                            $ContarSegui = 1;
                                                                                            while($dSegui = mysqli_fetch_array($rSegui)):
                                                                                                $virgula = ( $TotalSegui !== $ContarSegui ) ? ',' : '';
                                                                                                echo $dSegui['VenceNoProMes'].$virgula;
                                                                                                $ContarSegui++;
                                                                                            endwhile;
                                                                            ?>]
                                                                }]

                                                            };
                                                            new Chart(document.getElementById("vencemnoProMes").getContext(
                                                                "2d")).Bar(barChartData);
                                                            </script>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="clear pt-20"></div>

                                            <!-- Produtos Produtos que vencem até 3 meses -->
                                            <div class="graph">
                                                <div class="graph-grid">
                                                    <div class="column width-12">
                                                        <div class="box rounded border-purple medium shadow">
                                                            <h4 class="pt-10">Produtos c/ vendimento em até <strong>três meses</strong>.</h4>
                                                            <!-- <label class="pt-0"><span class="icon-dot-single small color-blue"></span> Nº de Dinâmicas 
                                                            <span class="icon-dot-single small color-green"></span> Nº de células</label> -->
                                                            <?
                                                                $Ig1Mes= date(('Y-m-d'), strtotime($dataeng.'+ 1 months'));
                                                                $venc3Mes= date(('Y-m-d'), strtotime($dataeng.'+ 3 months'));
                                                            ?>
                                                            <div class="analise"><canvas id="vencemEm3Meses" height="183px" width="1200px"></canvas></div>
                                                            <script>
                                                            var barChartData = {
                                                                labels: [<?php
                                                                            if($mesnumero < 2): echo "'',"; endif;
                                                                                $bMidia = "SELECT produto, count(situacaodoproduto) as ProConsu FROM estoque WHERE situacaodoproduto != 'Esgotado' AND validade >= '$Ig1Mes' AND validade <= '$venc3Mes' AND campus='$membrodocampus' GROUP BY produto ORDER by produto ASC";
                                                                                $rMidia = mysqli_query($con, $bMidia);
                                                                                    $TotalMidia = mysqli_num_rows($rMidia);
                                                                                        $ContarMidia = 1;
                                                                                        while($dMidia = mysqli_fetch_array($rMidia)):
                                                                                            $virgula = ( $TotalMidia !== $ContarMidia ) ? ',' : '';
                                                                                            echo '"'.substr($dMidia['produto'], 0, 13).' ('.$dMidia['ProConsu'].')"'.$virgula;
                                                                                            $ContarMidia++;
                                                                                        endwhile;
                                                                        ?>],
                                                                datasets: [{
                                                                    fillColor: "#f15901",
                                                                    strokeColor: "#f15901",
                                                                    data: [<?php
                                                                                if($mesnumero < 2): echo "0,"; endif;
                                                                                $bSegui = "SELECT count(produto) as VenceNoProMes FROM estoque WHERE situacaodoproduto != 'Esgotado' AND validade >= '$Ig1Mes' AND validade <= '$venc3Mes' AND campus='$membrodocampus' GROUP BY produto ORDER BY produto ASC";
                                                                                    $rSegui = mysqli_query($con, $bSegui);
                                                                                        $TotalSegui = mysqli_num_rows($rSegui);
                                                                                            $ContarSegui = 1;
                                                                                            while($dSegui = mysqli_fetch_array($rSegui)):
                                                                                                $virgula = ( $TotalSegui !== $ContarSegui ) ? ',' : '';
                                                                                                echo $dSegui['VenceNoProMes'].$virgula;
                                                                                                $ContarSegui++;
                                                                                            endwhile;
                                                                            ?>]
                                                                }]

                                                            };
                                                            new Chart(document.getElementById("vencemEm3Meses").getContext(
                                                                "2d")).Bar(barChartData);
                                                            </script>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="clear pt-20"></div>

                                            <!-- Produtos Produtos que vencem até 10 meses -->
                                            <div class="graph">
                                                <div class="graph-grid">
                                                    <div class="column width-12">
                                                        <div class="box rounded border-purple medium shadow">
                                                            <h4 class="pt-10">Produtos c/ vendimento em até <strong>dez meses</strong>.</h4>
                                                            <!-- <label class="pt-0"><span class="icon-dot-single small color-blue"></span> Nº de Dinâmicas 
                                                            <span class="icon-dot-single small color-green"></span> Nº de células</label> -->
                                                            <?
                                                                $Ig3Mes= date(('Y-m-d'), strtotime($dataeng.'+ 3 months'));
                                                                $venc10Mes= date(('Y-m-d'), strtotime($dataeng.'+ 10 months'));
                                                            ?>
                                                            <div class="analise"><canvas id="vencEm10Meses" height="183px" width="1200px"></canvas></div>
                                                            <script>
                                                            var barChartData = {
                                                                labels: [<?php
                                                                            if($mesnumero < 2): echo "'',"; endif;
                                                                                $bMidia = "SELECT produto, count(situacaodoproduto) as ProConsu FROM estoque WHERE situacaodoproduto != 'Esgotado' AND validade >= '$Ig3Mes' AND validade <= '$venc10Mes' AND campus='$membrodocampus' GROUP BY produto ORDER by produto ASC";
                                                                                $rMidia = mysqli_query($con, $bMidia);
                                                                                    $TotalMidia = mysqli_num_rows($rMidia);
                                                                                        $ContarMidia = 1;
                                                                                        while($dMidia = mysqli_fetch_array($rMidia)):
                                                                                            $virgula = ( $TotalMidia !== $ContarMidia ) ? ',' : '';
                                                                                            echo '"'.substr($dMidia['produto'], 0, 13).' ('.$dMidia['ProConsu'].')"'.$virgula;
                                                                                            $ContarMidia++;
                                                                                        endwhile;
                                                                        ?>],
                                                                datasets: [{
                                                                    fillColor: "#f15901",
                                                                    strokeColor: "#f15901",
                                                                    data: [<?php
                                                                                if($mesnumero < 2): echo "0,"; endif;
                                                                                $bSegui = "SELECT count(produto) as VenceNoProMes FROM estoque WHERE situacaodoproduto != 'Esgotado' AND validade >= '$Ig3Mes' AND validade <= '$venc10Mes' AND campus='$membrodocampus' GROUP BY produto ORDER BY produto ASC";
                                                                                    $rSegui = mysqli_query($con, $bSegui);
                                                                                        $TotalSegui = mysqli_num_rows($rSegui);
                                                                                            $ContarSegui = 1;
                                                                                            while($dSegui = mysqli_fetch_array($rSegui)):
                                                                                                $virgula = ( $TotalSegui !== $ContarSegui ) ? ',' : '';
                                                                                                echo $dSegui['VenceNoProMes'].$virgula;
                                                                                                $ContarSegui++;
                                                                                            endwhile;
                                                                            ?>]
                                                                }]

                                                            };
                                                            new Chart(document.getElementById("vencEm10Meses").getContext(
                                                                "2d")).Bar(barChartData);
                                                            </script>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="clear pt-20"></div>

                                            <!-- Produtos EM USO por categoria -->
                                            <div class="graph">
                                                <div class="graph-grid">
                                                    <div class="column width-12">
                                                        <div class="box rounded border-purple medium shadow">
                                                            <h4 class="pt-10">Produtos <strong>Em uso</strong> por categoria.</h4>
                                                            <!-- <label class="pt-0"><span class="icon-dot-single small color-blue"></span> Nº de Dinâmicas 
                                                            <span class="icon-dot-single small color-green"></span> Nº de células</label> -->

                                                            <div class="analise"><canvas id="produtoporcategoria" height="183px" width="1200px"></canvas></div>
                                                            <script>
                                                            var barChartData = {
                                                                labels: [<?php
                                                                            if($mesnumero < 2): echo "'',"; endif;
                                                                                $bMidia = "SELECT categoriadoproduto, count(situacaodoproduto) as ProEmUso FROM estoque WHERE NOT (situacaodoproduto != 'Em uso') AND campus='$membrodocampus' GROUP BY categoriadoproduto ORDER by categoriadoproduto ASC";
                                                                                $rMidia = mysqli_query($con, $bMidia);
                                                                                    $TotalMidia = mysqli_num_rows($rMidia);
                                                                                        $ContarMidia = 1;
                                                                                        while($dMidia = mysqli_fetch_array($rMidia)):
                                                                                            $virgula = ( $TotalMidia !== $ContarMidia ) ? ',' : '';
                                                                                            echo '"'.substr($dMidia['categoriadoproduto'], 0, 13).' (U:'.$dMidia['ProEmUso'].' : '.$dMidia['ProFecha'].')'.'"'.$virgula;
                                                                                            $ContarMidia++;
                                                                                        endwhile;
                                                                        ?>],
                                                                datasets: [{
                                                                    fillColor: "#fffb00",
                                                                    strokeColor: "#fffb00",
                                                                    data: [<?php
                                                                                if($mesnumero < 2): echo "0,"; endif;
                                                                                $bSegui = "SELECT count(categoriadoproduto) as ProdEsto FROM estoque WHERE NOT (situacaodoproduto != 'Em uso') AND campus='$membrodocampus' GROUP BY categoriadoproduto ORDER BY categoriadoproduto ASC";
                                                                                    $rSegui = mysqli_query($con, $bSegui);
                                                                                        $TotalSegui = mysqli_num_rows($rSegui);
                                                                                            $ContarSegui = 1;
                                                                                            while($dSegui = mysqli_fetch_array($rSegui)):
                                                                                                $virgula = ( $TotalSegui !== $ContarSegui ) ? ',' : '';
                                                                                                echo $dSegui['ProdEsto'].$virgula;
                                                                                                $ContarSegui++;
                                                                                            endwhile;
                                                                            ?>]
                                                                }]

                                                            };
                                                            new Chart(document.getElementById("produtoporcategoria").getContext(
                                                                "2d")).Bar(barChartData);
                                                            </script>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="clear pt-20"></div>

                                            <!-- Produtos FECHADOS por categoria -->
                                            <div class="graph">
                                                <div class="graph-grid">
                                                    <div class="column width-12">
                                                        <div class="box rounded border-purple medium shadow">
                                                            <h4 class="pt-10">Produtos <strong>fechados</strong> por categoria.</h4>
                                                            <!-- <label class="pt-0"><span class="icon-dot-single small color-blue"></span> Nº de Dinâmicas 
                                                            <span class="icon-dot-single small color-green"></span> Nº de células</label> -->

                                                            <div class="analise"><canvas id="ProdCatFech" height="183px" width="1200px"></canvas></div>
                                                            <script>
                                                            var barChartData = {
                                                                labels: [<?php
                                                                            if($mesnumero < 2): echo "'',"; endif;
                                                                                $bMidia = "SELECT categoriadoproduto, count(situacaodoproduto) as ProdFecha FROM estoque WHERE NOT (situacaodoproduto != 'Fechado') AND campus='$membrodocampus' GROUP BY categoriadoproduto ORDER by categoriadoproduto ASC";
                                                                                $rMidia = mysqli_query($con, $bMidia);
                                                                                    $TotalMidia = mysqli_num_rows($rMidia);
                                                                                        $ContarMidia = 1;
                                                                                        while($dMidia = mysqli_fetch_array($rMidia)):
                                                                                            $virgula = ( $TotalMidia !== $ContarMidia ) ? ',' : '';
                                                                                            echo '"'.substr($dMidia['categoriadoproduto'], 0, 13).' (U:'.$dMidia['ProdFecha'].' : '.$dMidia['ProFecha'].')'.'"'.$virgula;
                                                                                            $ContarMidia++;
                                                                                        endwhile;
                                                                        ?>],
                                                                datasets: [{
                                                                    fillColor: "#003df5ff",
                                                                    strokeColor: "#003df5ff",
                                                                    data: [<?php
                                                                                if($mesnumero < 2): echo "0,"; endif;
                                                                                $bSegui = "SELECT count(categoriadoproduto) as ProdEstoFec FROM estoque WHERE NOT (situacaodoproduto != 'Fechado') AND campus='$membrodocampus' GROUP BY categoriadoproduto ORDER BY categoriadoproduto ASC";
                                                                                    $rSegui = mysqli_query($con, $bSegui);
                                                                                        $TotalSegui = mysqli_num_rows($rSegui);
                                                                                            $ContarSegui = 1;
                                                                                            while($dSegui = mysqli_fetch_array($rSegui)):
                                                                                                $virgula = ( $TotalSegui !== $ContarSegui ) ? ',' : '';
                                                                                                echo $dSegui['ProdEstoFec'].$virgula;
                                                                                                $ContarSegui++;
                                                                                            endwhile;
                                                                            ?>]
                                                                }]

                                                            };
                                                            new Chart(document.getElementById("ProdCatFech").getContext(
                                                                "2d")).Bar(barChartData);
                                                            </script>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <div class="clear pt-20"></div>

                                            <!-- Produto -->
                                            <?
                                                $bPr="SELECT produto FROM estoque GROUP BY produto";
                                                  $rPr=mysqli_query($con, $bPr);
                                                        $separador=0;
                                                    while($dPr=mysqli_fetch_array($rPr)):
                                                        $separador++;
                                                        $produtodografico=$dPr['produto'];
                                            ?>
                                            <div class="graph pt-10">
                                                <div class="graph-grid">
                                                    <div class="column width-6">
                                                        <div class="box rounded border-blue medium shadow">
                                                            <h4 class="pt-20">Comparativo do produto; <strong><? echo $dPr['produto'];?></strong></h4>
                                                            <label class="pt-0"><span class="icon-dot-single large color-blue"></span> Em uso <span class="icon-dot-single large color-youtube"></span> Fechados</label>
                                                            <div class="analise"><canvas id="LineCompa<? echo $dPr['produto'];?>" width="550px"></canvas></div>
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
                                                                                strokeColor : "#003df5ff",
                                                                                pointColor : "#003df5ff",
                                                                                pointStrokeColor : "#003df5ff",
                                                                                data : [<?php
                                                                                        $ms10N='0';
                                                                                        for($ms10=0; $ms10< '12'; $ms10++){
                                                                                            $ms10N++;
                                                                                            if($ms10N < 12): $vrg=','; else: $vrg=''; endif;
                                                                                            if($ms10N < 10): $ms10N='0'.$ms10N; else: $ms10N = $ms10N; endif; //04 - 10
                                                                                            $bPEComp = "SELECT mes, count(produto) as ProdEmUso FROM estoque WHERE NOT (produto='') AND produto='$produtodografico' AND situacaodoproduto='Em uso' AND campus='$membrodocampus' AND mes='$ms10N' AND ano='$ano' GROUP BY mes";
                                                                                            $rPEComp = mysqli_query($con, $bPEComp);
                                                                                                $dPEComp = mysqli_fetch_array($rPEComp);
                                                                                            if(empty($dPEComp['ProdEmUso'])):
                                                                                                echo '0'.$vrg;
                                                                                            else:
                                                                                                echo $dPEComp['ProdEmUso'].$vrg;
                                                                                            endif;
                                                                                        }
                                                                                ?>]
                                                                            }, {
                                                                                label:'Ano atual:',
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
                                                                                            $bPEComp = "SELECT produto, count(produto) as prodFec FROM estoque WHERE NOT (produto='') AND produto='$produtodografico' AND situacaodoproduto='Fechado' AND campus='$membrodocampus' AND mes='$ms10N' AND ano='$ano' GROUP BY mes";
                                                                                            $rPEComp = mysqli_query($con, $bPEComp);
                                                                                                $dPEComp = mysqli_fetch_array($rPEComp);
                                                                                            if(empty($dPEComp['prodFec']) OR !isset($dPEComp['prodFec'])):
                                                                                                echo '0'.$vrg;
                                                                                            else:
                                                                                                echo $dPEComp['prodFec'].$vrg;
                                                                                            endif;
                                                                                        }
                                                                                ?>]
                                                                            }
                                                                        ]
                                                                        
                                                                    };
                                                                    new Chart(document.getElementById("LineCompa<? echo $dPr['produto'];?>").getContext("2d")).Line(lineChartData);
                                                            </script>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <? if($separador % 2 !==1):?>
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
                                                        <li class="active">
                                                            <a href="#tabs-estoque-tabela">
                                                                Produtos no <strong>estoque</strong>
                                                            </a>
                                                        </li>
                                                    </ul>
                                                    <div class="tab-panes">
                                                        <div id="tabs-estoque-tabela">
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
                                                                                Produto (Item)
                                                                            </span>
                                                                        </th>
                                                                        <th>
                                                                            <span class="text-medium">
                                                                                Vencimento
                                                                            </span>
                                                                        </th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                        <?php
                                                                            $bMovFin="SELECT id, produto, count(produto) as QntProd, situacaodoproduto, validade FROM estoque WHERE NOT (situacaodoproduto='Esgotado') AND ano='$ano' AND campus='$membrodocampus' GROUP BY situacaodoproduto ORDER BY validade ASC, produto ASC";
                                                                            $rMovFin=mysqli_query($con, $bMovFin);
                                                                                    $nmf = 0;
                                                                                while($dRC=mysqli_fetch_array($rMovFin)):
                                                                                    $nmf++;
                                                                        ?>
                                                                    <tr>
                                                                        <td class="color-black" width="5%" valign="medium">
                                                                            <span class="text-medium">
                                                                                <?php echo $nmf;?>
                                                                            </span>
                                                                        </td>
                                                                        <td class="color-black" width="75%" valign="medium">
                                                                            <span class="text-medium">
                                                                                <?php echo $dRC['produto'].' ('.$dRC['situacaodoproduto'].')';?>
                                                                            </span>
                                                                        </td>
                                                                        <td class="color-black" width="20%" valign="medium">
                                                                            <span class="text-medium">
                                                                                <?php echo $dRC['validade'];?>
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