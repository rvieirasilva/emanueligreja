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

    //var_dump($_SESSION);
    if(isset($_SESSION["lideranca"])):
        include "protectcolaborador.php";	
    elseif(isset($_SESSION["membro"])):
        include "protectusuario.php";
    else:
        header("location:index");
	endif;
		
	if(isset($_GET['backup']) AND !empty($_GET['backup'])):
        $anodebackup=$_GET['backup'];
        $ps=$_GET['backup']."-";
        $blancBackup="SELECT id FROM financeiro WHERE datarealizada LIKE '$ps%'";
         $rbBackup=mysqli_query($con, $blancBackup);
           $nBackup=mysqli_num_rows($rbBackup);
           if($nBackup > 0):
                @mkdir("arq/", 0777); //Cria a pasta se não houver. o @ é pra ocultar erro caso ela exista.
                @mkdir("arq/relatorios/", 0777); //Cria a pasta se não houver. o @ é pra ocultar erro caso ela exista.
                @mkdir("arq/relatorios/financeiro/", 0777); //Cria a pasta se não houver. o @ é pra ocultar erro caso ela exista.
                @mkdir("arq/relatorios/financeiro/$ano/", 0777); //Cria a pasta se não houver. o @ é pra ocultar erro caso ela exista.
                @mkdir("arq/relatorios/financeiro/$ano/", 0777); //Cria a pasta se não houver. o @ é pra ocultar erro caso ela exista.
                $arquivo = "arq/relatorios/financeiro/$ano/$ano.csv"; //Terá um log para cada mês do ano.
                $arquivo2 = fopen("arq/relatorios/financeiro/$ano/$ano.csv", "w"); // 1 - CRIAR ARQUIVO DE LOG no endereço informado em arquivo.

                $bBackup="SELECT codigodaoperacao, valorprevisto, descricao, dataprevista, valorrealizado, datarealizada, categoria, conta1, conta2, tipoderegistro FROM financeiro WHERE ano='$anodebackup' AND datarealizada LIKE '$ps%' OR dataprevista LIKE '$ps%'";
                  $rbackup=mysqli_query($con, $bBackup);
                    while($dbackup=mysqli_fetch_array($rbackup)):
                        if(!empty($dbackup['datarealizada'])): $dataBack=$dbackup['datarealizada']; else: $dataBack=$dbackup['dataprevista']; endif;
                        if(!empty($dbackup['valorrealizado'])): $valorBack=$dbackup['valorrealizado']; else: $valorBack=$dbackup['valorprevisto']; endif;
                        
                        $valorBack = number_format($valorBack, 2, ',', '.');
                        $codBack=$dbackup['codigodaoperacao'];
                            $descrBack=base64_decode(base64_decode($dbackup['descricao']));
                        $categoriaBack=$dbackup['categoria'];
                        $contaBack=$dbackup['conta1'];
                        if($dbackup['tipoderegistro'] === 'DÉBITO'): $valorBack="-$valorBack"; endif;

                        $registrofinanceiro=$dataBack.';'.$valorBack.';'.$codBack.';'.$descrBack.';'.$contaBack.';'.$categoriaBack."\n";
                        fwrite($arquivo2, $registrofinanceiro); // 2 - ESCREVER NO ARQUIVO.
                    endwhile;

                fclose($arquivo2); //Fecha o arquivo
                
                $_SESSION['cordamensagem']="orange";
                $_SESSION['mensagem']="Backup gerado com sucesso, <a href='$arquivo' download='$anodebackup - Backup financeiro Emanuel.csv' target='_blank'>Clique aqui para baixar.</a>.";
                header("refresh:5; url=analise?$linkSeguro");
           else:
            $_SESSION['cordamensagem']="orange";
            $_SESSION['mensagem']="Não há lançamentos para o ano informado.";
           endif;
    endif;

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
                        <div class="row full-width">
                            <div class="column width-12">
                                <div class="box rounded small bkg-white shadow">
                                    <div class="title-container">
                                        <div class="title-container-inner">
                                            <div class="row flex">
                                                <div class="column width-8 v-align-middle">
                                                    <div>
                                                        <h1 class="mb-0">Análise <strong>Financeira</strong> da Emanuel</h1>
                                                        <p class="text-large color-charcoal mb-0 mb-mobile-20">Por isso não corro sem objetivo.</p>
                                                    </div>
                                                </div>
                                                <div class="column width-4 v-align-middle">
                                                    <?php
                                                        $bVMpRECEITA = "SELECT sum(valorrealizado) as receitarealizada FROM financeiro WHERE NOT (valorrealizado = '') AND tipoderegistro='CRÉDITO' AND ano='$ano'  AND conta1!='272112-8' AND conta1!='0000' AND conta2!='272112-8' AND conta2!='0000'"; //Venda Mercado Pago.
                                                        $rVMpRECEITA = mysqli_query($con, $bVMpRECEITA);
                                                            $dVMpR = mysqli_fetch_array($rVMpRECEITA);
                
                                                            $ReceitaDisponivelDaEmpresa = $dVMpR['receitarealizada'];
                
                                                        //SQMP = Saque do Mercado Pago
                                                            $bVMpDEBITO = "SELECT sum(valorrealizado) as saquesrealizados FROM financeiro WHERE NOT (valorrealizado = '') AND tipoderegistro='DÉBITO' AND ano='$ano'  AND conta1!='272112-8' AND conta1!='0000' AND conta2!='272112-8' AND conta2!='0000'"; //Venda Mercado Pago.
                                                        $rVMpDEBITO = mysqli_query($con, $bVMpDEBITO);
                                                            $dVMpD = mysqli_fetch_array($rVMpDEBITO);
                
                                                            $SaquesRealizados = $dVMpD['saquesrealizados'];
                
                                                        $saldodisponivel = $ReceitaDisponivelDaEmpresa - $SaquesRealizados;
                                                        $saldodisponivel=number_format($saldodisponivel, 2, ',', '.');
                                                    ?>
                                                    <div>
                                                        <label>Saldo disponível</label>
                                                        <h3 class="mb-0">
                                                            R$ <?php echo $saldodisponivel; ?>
                                                        </h3>
                                                        <!-- <p class="lead mb-0 mb-mobile-20">
                                                                </p> -->
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
                            <div class="column width-12 pb-90 ">
                                <div class="box rounded rounded shadow border-blue bkg-white">
                                    <div class="tabs line-nav rounded left">
                                        <ul class="tab-nav">
                                            <li>
                                                <a href="#tabs-visaogeral">Visão Geral</a>
                                            </li>
                                            <li>
                                                <a href="#tabs-receitas">Receita</a>
                                            </li>
                                            <li>
                                                <a href="#tabs-despesas">Despesa</a>
                                            </li>
                                            <li class="active">
                                                <a href="#tabs-movimentacao">Extrato (Movimentacao Financeira)</a>
                                            </li>
                                        </ul>
                                        <div class="tab-panes">
                                            <div id="tabs-visaogeral">
                                                <div class="tab-content ">

                                                    <!--Comparação previsto/realizado receita-->
                                                    <div class="graph">
                                                        <div class="graph-grid">
                                                            <div class="column width-6">
                                                                <div class="box rounded shadow">
                                                                    <h4 class="pb-0">Comparativo de arrecadação (Previsto/Realizado).
                                                                    </h4>
                                                                    <span class="icon-dot-single large color-blue"></span>
                                                                    <span>Previsto</span>
                                                                    <span class="icon-dot-single large color-green"></span>
                                                                    <span>Realizado.</span>

                                                                    <div class="analise"><canvas id="lineRP" height="300" width="550px" style="width:300px; height: 300px;"></canvas></div>
                                                                    <script>
                                                                        var lineChartData = {
                                                                            labels: [<?php
                                                                                        $bGMes = "SELECT mes FROM financeiro WHERE ano='$ano' GROUP BY mes";
                                                                                            $rGMes = mysqli_query($con, $bGMes);
                                                                                                $totalMes = mysqli_num_rows($rGMes);
                                                                                                    $contarMes = 1;
                                                                                                    //while($dGMes = mysqli_fetch_array($rGMes)):
                                                                                                        if($dGMes['mes'] == '01'):
                                                                                                            echo $dGMes['mes'].',';
                                                                                                        else:
                                                                                                            echo "01,";
                                                                                                        endif;
                                                                                                        if($dGMes['mes'] == '02'):
                                                                                                            echo $dGMes['mes'].',';
                                                                                                        else:
                                                                                                            echo "02,";
                                                                                                        endif;
                                                                                                        if($dGMes['mes'] == '03'):
                                                                                                            echo $dGMes['mes'].',';
                                                                                                        else:
                                                                                                            echo "03,";
                                                                                                        endif;
                                                                                                        if($dGMes['mes'] == '04'):
                                                                                                            echo $dGMes['mes'].',';
                                                                                                        else:
                                                                                                            echo "04,";
                                                                                                        endif;
                                                                                                        if($dGMes['mes'] == '05'):
                                                                                                            echo $dGMes['mes'].',';
                                                                                                        else:
                                                                                                            echo "05,";
                                                                                                        endif;
                                                                                                        if($dGMes['mes'] == '06'):
                                                                                                            echo $dGMes['mes'].',';
                                                                                                        else:
                                                                                                            echo "06,";
                                                                                                        endif;
                                                                                                        if($dGMes['mes'] == '07'):
                                                                                                            echo $dGMes['mes'].',';
                                                                                                        else:
                                                                                                            echo "07,";
                                                                                                        endif;
                                                                                                        if($dGMes['mes'] == '08'):
                                                                                                            echo $dGMes['mes'].',';
                                                                                                        else:
                                                                                                            echo "08,";
                                                                                                        endif;
                                                                                                        if($dGMes['mes'] == '09'):
                                                                                                            echo $dGMes['mes'].',';
                                                                                                        else:
                                                                                                            echo "09,";
                                                                                                        endif;
                                                                                                        if($dGMes['mes'] == '10'):
                                                                                                            echo $dGMes['mes'].',';
                                                                                                        else:
                                                                                                            echo "10,";
                                                                                                        endif;
                                                                                                        if($dGMes['mes'] == '11'):
                                                                                                            echo $dGMes['mes'].',';
                                                                                                        else:
                                                                                                            echo "11,";
                                                                                                        endif;
                                                                                                        if($dGMes['mes'] == '12'):
                                                                                                            echo $dGMes['mes'];
                                                                                                        else:
                                                                                                            echo "12";
                                                                                                        endif;
                                                                                                        // $virgula = ( $totalMes !== $contarMes ) ? ',' : '';
                                                                                                        // echo '"'.$dGMes['mes'].'"'.$virgula;
                                                                                                        $contarMes++;
                                                                                                    // endwhile;
                                                                                        ?>],
                                                                            datasets: [{
                                                                                label: 'Arrecadação',
                                                                                fillColor: "rgba(255,255,255,0)",
                                                                                strokeColor: "#227fbb",
                                                                                pointColor: "#227fbb",
                                                                                pointStrokeColor: "#227fbb",
                                                                                data: [<?php
                                                                                        $ms0N='0';
                                                                                        for($ms0=0; $ms0< '12'; $ms0++){
                                                                                            $ms0N++;
                                                                                            if($ms0N < 10): $ms0N='0'.$ms0N; else: $ms0N = $ms0N; endif; //04 - 10

                                                                                            $bREComp = "SELECT mes, sum(valorprevisto) as totalprevisto FROM financeiro WHERE tipoderegistro='CRÉDITO' AND mes='$ms0N' AND ano='$ano' GROUP BY mes";
                                                                                            $rREComp = mysqli_query($con, $bREComp);
                                                                                                $TotalREComp = mysqli_num_rows($rREComp);
                                                                                                $dREComp=mysqli_fetch_array($rREComp);
                                                                                            if(empty($dREComp['totalprevisto'])):
                                                                                                echo '0,';
                                                                                            else:
                                                                                                echo $dREComp['totalprevisto'].',';
                                                                                            endif;
                                                                                        }
                                                                                        ?>]
                                                                            }, {
                                                                                label: 'Valor realizado',
                                                                                fillColor: "rgba(255,255,255,0)",
                                                                                strokeColor: "#1bb05d",
                                                                                pointColor: "#1bb05d",
                                                                                pointStrokeColor: "#1bb05d",
                                                                                data: [<?php
                                                                                        $ms1N='0';
                                                                                        for($ms1=0; $ms1< '12'; $ms1++){
                                                                                            $ms1N++;
                                                                                            if($ms1N < 10): $ms1N='0'.$ms1N; else: $ms1N = $ms1N; endif; //04 - 10

                                                                                            $bREComp = "SELECT mes, sum(valorrealizado) as totalrealizado FROM financeiro WHERE tipoderegistro='CRÉDITO' AND mes='$ms1N' AND ano='$ano' GROUP BY mes";
                                                                                            $rREComp = mysqli_query($con, $bREComp);
                                                                                                $TotalREComp = mysqli_num_rows($rREComp);
                                                                                                $dREComp=mysqli_fetch_array($rREComp);
                                                                                            if(empty($dREComp['totalrealizado'])):
                                                                                                echo '0,';
                                                                                            else:
                                                                                                echo $dREComp['totalrealizado'].',';
                                                                                            endif;
                                                                                        }
                                                                                        ?>]
                                                                            }]

                                                                        };
                                                                        new Chart(document.getElementById("lineRP").getContext("2d")).Line(
                                                                            lineChartData);
                                                                    </script>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!--Comparação previsto/realizado receita-->

                                                    <!--Comparação previsto/realizado saída-->
                                                    <div class="graph">
                                                        <div class="graph-grid">
                                                            <div class="column width-6">
                                                                <div class="box rounded shadow">
                                                                    <h4 class="pb-0">Comparativo de saída (Previsto/Realizado).</h4>
                                                                    <span class="icon-dot-single large color-orange"></span>
                                                                    <span>Previsto</span>
                                                                    <span class="icon-dot-single large color-youtube"></span>
                                                                    <span>Realizado.</span>
                                                                    <div class="analise"><canvas id="lineSP" height="300" width="550px" style="width:300px; height: 300px;"></canvas></div>
                                                                    <script>
                                                                        var lineChartData = {
                                                                            labels: [<?php
                                                                                            $mescSF='0';
                                                                                        for($mescS=0; $mescS < 12; $mescS++){
                                                                                            $mescSF++;
                                                                                            if($mescSF < 12): $vrg=','; else: $vrg=''; endif;
                                                                                            echo $mescSF.$vrg;
                                                                                        }
                                                                                    ?>],
                                                                            datasets: [{
                                                                                label: 'Saida prevista:',
                                                                                fillColor: "rgba(255,255,255,0)",
                                                                                strokeColor: "#e87f04",
                                                                                pointColor: "#e87f04",
                                                                                pointStrokeColor: "#e87f04",
                                                                                data: [<?php
                                                                                        $ms2N='0';
                                                                                        for($ms2=0; $ms2< '12'; $ms2++){
                                                                                            $ms2N++;
                                                                                            if($ms2N < 10): $ms2N='0'.$ms2N; else: $ms2N = $ms2N; endif; //04 - 10
                                                                                            $bPSComp = "SELECT mes, sum(valorprevisto) as totalprevistoS FROM financeiro WHERE tipoderegistro='DÉBITO'  AND mes='$ms2N' AND ano='$ano' GROUP BY mes";
                                                                                            $rPSComp = mysqli_query($con, $bPSComp);
                                                                                                $dPSComp = mysqli_fetch_array($rPSComp);
                                                                                            if(empty($dPSComp['totalprevistoS'])):
                                                                                                echo '0,';
                                                                                            else:
                                                                                                echo abs($dPSComp['totalprevistoS']).',';
                                                                                            endif;
                                                                                        }
                                                                                        ?>]
                                                                            }, {
                                                                                label: 'Saída',
                                                                                fillColor: "rgba(255,255,255,0)",
                                                                                strokeColor: "#b00",
                                                                                pointColor: "#b00",
                                                                                pointStrokeColor: "#b00",
                                                                                data: [<?php
                                                                                        $ms3N='0';
                                                                                        for($ms3=0; $ms3< '12'; $ms3++){
                                                                                            $ms3N++;
                                                                                            if($ms3N < 10): $ms3N='0'.$ms3N; else: $ms3N = $ms3N; endif; //04 - 10

                                                                                            $bPSComp = "SELECT mes, sum(valorrealizado) as totalrealizadoS FROM financeiro WHERE tipoderegistro='DÉBITO'  AND mes='$ms3N' AND ano='$ano' GROUP BY mes";
                                                                                            $rPSComp = mysqli_query($con, $bPSComp);
                                                                                                $dPSComp = mysqli_fetch_array($rPSComp);
                                                                                            if(empty($dPSComp['totalrealizadoS'])):
                                                                                                echo '0,';
                                                                                            else:
                                                                                                echo abs($dPSComp['totalrealizadoS']).',';
                                                                                            endif;
                                                                                        }
                                                                                        ?>]
                                                                            }]

                                                                        };
                                                                        new Chart(document.getElementById("lineSP").getContext("2d")).Line(
                                                                            lineChartData);
                                                                    </script>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!--Comparação previsto/realizado saída-->

                                                    <div class="clear"></div>

                                                    <!--Comparação anual-->
                                                    <div class="graph pt-40">
                                                        <div class="graph-grid">
                                                            <div class="column width-12">
                                                                <div class="box rounded shadow">
                                                                    <h4 class="pb-0">Comparativo anual</h4>
                                                                    <span class="icon-dot-single large color-green"></span>
                                                                    <span>Arrecadado</span>
                                                                    <span class="icon-dot-single large color-youtube"></span>
                                                                    <span>Pago/Investido.</span>
                                                                    <div class="analise"><canvas id="lineC" height="300" width="1200px" style="width:1200px; height: 300px;"></canvas></div>
                                                                    <script>
                                                                        var lineChartData = {
                                                                            labels: [<?php
                                                                                            $mescSF1='0';
                                                                                        for($mescS01=0; $mescS01 < 12; $mescS01++){
                                                                                            $mescSF1++;
                                                                                            if($mescSF1 < 12): $vrg=','; else: $vrg=''; endif;
                                                                                            echo $mescSF1.$vrg;
                                                                                        }
                                                                                    ?>],
                                                                            datasets: [{
                                                                                label: 'Arrecadação:',
                                                                                fillColor: "rgba(255,255,255,0)",
                                                                                strokeColor: "#1bb05d",
                                                                                pointColor: "#1bb05d",
                                                                                pointStrokeColor: "#1bb05d",
                                                                                data: [<?php
                                                                                            $ms4N='0';
                                                                                            for($ms4=0; $ms4< '12'; $ms4++){
                                                                                                $ms4N++;
                                                                                                if($ms4N < 10): $ms4N='0'.$ms4N; else: $ms4N = $ms4N; endif; //04 - 10
                        
                                                                                                $bPSComp = "SELECT mes, sum(valorrealizado) as totalarrecadado FROM financeiro WHERE tipoderegistro='CRÉDITO' AND mes='$ms4N' AND ano='$ano' GROUP BY mes";
                                                                                                $rEntradaComp = mysqli_query($con, $bPSComp);
                                                                                                    $dEntradaComp = mysqli_fetch_array($rEntradaComp);
                                                                                                if(empty($dEntradaComp['totalarrecadado'])):
                                                                                                    echo '0,';
                                                                                                else:
                                                                                                    echo $dEntradaComp['totalarrecadado'].',';
                                                                                                endif;
                                                                                            }
                                                                                            ?>]
                                                                            }, {
                                                                                label: 'Saída',
                                                                                fillColor: "rgba(255,255,255,0)",
                                                                                strokeColor: "#ea4b35",
                                                                                pointColor: "#ea4b35",
                                                                                pointStrokeColor: "#ea4b35",
                                                                                data: [<?php
                                                                                            $ms5N='0';
                                                                                            for($ms5=0; $ms5< '12'; $ms5++){
                                                                                                $ms5N++;
                                                                                                if($ms5N < 10): $ms5N='0'.$ms5N; else: $ms5N = $ms5N; endif; //04 - 10
                        
                                                                                                $bSaidaComp = "SELECT mes, sum(valorrealizado) as totalpago FROM financeiro WHERE tipoderegistro='DÉBITO' AND mes='$ms5N' AND ano='$ano' GROUP BY mes";
                                                                                                $rSaidaComp = mysqli_query($con, $bSaidaComp);
                                                                                                    $dSaidaComp = mysqli_fetch_array($rSaidaComp);
                                                                                                if(empty($dSaidaComp['totalpago'])):
                                                                                                    echo '0,';
                                                                                                else:
                                                                                                    echo abs($dSaidaComp['totalpago']).',';
                                                                                                endif;
                                                                                            }
                                                                                            ?>]
                                                                            }]

                                                                        };
                                                                        new Chart(document.getElementById("lineC").getContext("2d")).Line(
                                                                            lineChartData);
                                                                    </script>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!--Comparação anual-->

                                                    <!--Arrecadação e Investimento Geral-->
                                                    <div class="graph pt-20">
                                                        <div class="graph-grid">
                                                            <div class="column width-6 pt-20 graph-1">
                                                                <div class="box rounded shadow">
                                                                    <div class="grid-1">
                                                                        <h4 class="pb-0">Arrecadação (Receita) por categoria</h4>
                                                                        <div class="analise"><canvas id="bar1" height="300" width="550px" style="width: 550px; height: 300px;"></canvas></div>
                                                                        <script>
                                                                        var barChartData = {
                                                                            labels: [<?php
                                                                                        $bMidia = "SELECT categoria FROM financeiro WHERE ano='$ano' AND tipoderegistro='CRÉDITO' GROUP BY categoria ORDER by categoria ASC";
                                                                                            $rMidia = mysqli_query($con, $bMidia);
                                                                                                $TotalMidia = mysqli_num_rows($rMidia);
                                                                                                    $ContarMidia = 1;
                                                                                                    while($dMidia = mysqli_fetch_array($rMidia)):
                                                                                                        $virgula = ( $TotalMidia !== $ContarMidia ) ? ',' : '';
                                                                                                        echo '"'.substr($dMidia['categoria'], 0, 15).'"'.$virgula;
                                                                                                        $ContarMidia++;
                                                                                                    endwhile;
                                                                                        ?>],
                                                                            datasets: [{
                                                                                fillColor: "#1bb05d",
                                                                                strokeColor: "#1bb05d",
                                                                                data: [<?php
                                                                                            $bSegui = "SELECT sum(valorrealizado) as arrecadacao FROM financeiro WHERE ano='$ano' AND tipoderegistro='CRÉDITO' GROUP BY categoria ORDER BY categoria ASC";
                                                                                                $rSegui = mysqli_query($con, $bSegui);
                                                                                                    $TotalSegui = mysqli_num_rows($rSegui);
                                                                                                        $ContarSegui = 1;
                                                                                                        while($dSegui = mysqli_fetch_array($rSegui)):
                                                                                                            $virgula = ( $TotalSegui !== $ContarSegui ) ? ',' : '';
                                                                                                            echo $dSegui['arrecadacao'].$virgula;
                                                                                                            $ContarSegui++;
                                                                                                        endwhile;
                                                                                            ?>]
                                                                            }]

                                                                        };
                                                                        new Chart(document.getElementById("bar1").getContext("2d")).Bar(
                                                                            barChartData);
                                                                        </script>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="column width-6 pt-20 graph-1">
                                                                <div class="box rounded shadow">
                                                                    <div class="grid-1">
                                                                        <h4 class="pb-0">Investimento (Saída) por categoria</h4>
                                                            <div class="analise"><canvas id="barInv1" height="300" width="550px" style="width: 550px; height: 300px;"></canvas></div>
                                                                        <script>
                                                                        var barChartData = {
                                                                            labels: [<?php
                                                                                        $bInvAn = "SELECT categoria FROM financeiro WHERE ano='$ano' AND tipoderegistro='DÉBITO' GROUP BY categoria ORDER by categoria ASC";
                                                                                            $rInvAn = mysqli_query($con, $bInvAn);
                                                                                                $TotalMidia = mysqli_num_rows($rInvAn);
                                                                                                    $ContarInvAn = 1;
                                                                                                    while($dInvAn = mysqli_fetch_array($rInvAn)):
                                                                                                        $virgula = ( $TotalInvAn !== $ContarInvAn ) ? ',' : '';
                                                                                                        echo '"'.substr($dInvAn['categoria'], -10).'"'.$virgula;
                                                                                                        $ContarInvAn++;
                                                                                                    endwhile;
                                                                                        ?>],
                                                                            datasets: [{
                                                                                fillColor: "#c23824",
                                                                                strokeColor: "#c23824",
                                                                                data: [<?php
                                                                                            $bSegui = "SELECT sum(valorrealizado) as investido FROM financeiro WHERE ano='$ano' AND tipoderegistro='DÉBITO' GROUP BY categoria ORDER BY categoria ASC";
                                                                                                $rSegui = mysqli_query($con, $bSegui);
                                                                                                    $TotalSegui = mysqli_num_rows($rSegui);
                                                                                                        $ContarSegui = 1;
                                                                                                        while($dSegui = mysqli_fetch_array($rSegui)):
                                                                                                            $virgula = ( $TotalSegui !== $ContarSegui ) ? ',' : '';
                                                                                                            echo abs($dSegui['investido']).$virgula;
                                                                                                            $ContarSegui++;
                                                                                                        endwhile;
                                                                                            ?>]
                                                                            }]

                                                                        };
                                                                        new Chart(document.getElementById("barInv1").getContext("2d"))
                                                                            .Bar(barChartData);
                                                                        </script>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!--Arrecadação e Investimento Geral-->

                                                    <!-- Receita anual -->
                                                    <div class="graph pt-20">
                                                        <div class="graph-grid">
                                                            <div class="column width-6 graph-2 pt-20">
                                                                <div class="box rounded shadow">
                                                                    <div class="grid-1">
                                                                        <h4 class="pb-0">Arrecadação (Receita) anual.</h4>
                                                                <div class="analise"><canvas id="line1" height="300" width="550px" style="width: 550px; height: 300px;"></canvas></div>
                                                                        <script>
                                                                        var lineChartData = {
                                                                            labels: [<?php
                                                                                        $mescSF2='0';
                                                                                    for($mescS02=0; $mescS02 < 12; $mescS02++){
                                                                                        $mescSF2++;
                                                                                        if($mescSF2 < 12): $vrg=','; else: $vrg=''; endif;
                                                                                        echo $mescSF2.$vrg;
                                                                                    }
                                                                                ?>],
                                                                            datasets: [{
                                                                                fillColor: "rgba(255,255,255,0)",
                                                                                strokeColor: "#1bb05d",
                                                                                pointColor: "#1bb05d",
                                                                                pointStrokeColor: "#1bb05d",
                                                                                data: [<?php
                                                                                        $ms6N='0';
                                                                                        for($ms6=0; $ms6< '12'; $ms6++){
                                                                                            $ms6N++;
                                                                                            if($ms6N < 10): $ms6N='0'.$ms6N; else: $ms6N = $ms6N; endif; //04 - 10
                    
                                                                                            $bGM = "SELECT mes, sum(valorrealizado) as totalarrecadado FROM financeiro WHERE tipoderegistro='CRÉDITO' AND mes='$ms6N' AND ano='$ano' GROUP BY mes";
                                                                                            $rGM = mysqli_query($con, $bGM);
                                                                                                $dGM = mysqli_fetch_array($rGM);
                                                                                            if(empty($dGM['totalarrecadado'])):
                                                                                                echo '0,';
                                                                                            else:
                                                                                                echo $dGM['totalarrecadado'].',';
                                                                                            endif;
                                                                                        }
                                                                                        ?>]
                                                                            }]

                                                                        };
                                                                        new Chart(document.getElementById("line1").getContext("2d"))
                                                                            .Line(lineChartData);
                                                                        </script>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="column width-6 graph-2 pt-20">
                                                                <div class="box rounded shadow">
                                                                    <div class="grid-1">
                                                                        <h4 class="pb-0">Débito (Saída).</h4>
                                                                        <div class="analise"><canvas id="line2" height="300" width="550px" style="width: 550px; height: 300px;"></canvas></div>
                                                                        <script>
                                                                        var lineChartData = {
                                                                            labels: [<?php
                                                                                        $mescSF3='0';
                                                                                    for($mescS03=0; $mescS03 < 12; $mescS03++){
                                                                                        $mescSF3++;
                                                                                        if($mescSF3 < 12): $vrg=','; else: $vrg=''; endif;
                                                                                        echo $mescSF3.$vrg;
                                                                                    }
                                                                                ?>],
                                                                            datasets: [{
                                                                                fillColor: "rgba(255,255,255,0)",
                                                                                strokeColor: "#c23824",
                                                                                pointColor: "#c23824",
                                                                                pointStrokeColor: "#c23824",
                                                                                data: [<?php
                                                                                        $ms7N='0';
                                                                                        for($ms7=0; $ms7< '12'; $ms7++){
                                                                                            $ms7N++;
                                                                                            if($ms7N < 10): $ms7N='0'.$ms7N; else: $ms7N = $ms7N; endif; //04 - 10
                    
                                                                                            $bGM = "SELECT mes, sum(valorrealizado) as totalpago FROM financeiro WHERE tipoderegistro='DÉBITO' AND mes='$ms7N' AND ano='$ano' GROUP BY mes";
                                                                                            $rGM = mysqli_query($con, $bGM);
                                                                                                $dGM = mysqli_fetch_array($rGM);
                                                                                            if(empty($dGM['totalpago'])):
                                                                                                echo '0,';
                                                                                            else:
                                                                                                echo abs($dGM['totalpago']).',';
                                                                                            endif;
                                                                                        }
                                                                                        ?>]
                                                                            }]

                                                                        };
                                                                        new Chart(document.getElementById("line2").getContext("2d"))
                                                                            .Line(lineChartData);
                                                                        </script>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!-- Receita anual -->
                                                </div>
                                            </div>
                                            <div id="tabs-receitas">
                                                <div class="tab-content">

                                                    <!--Comparação previsto/realizado receita-->
                                                    <div class="graph">
                                                        <div class="graph-grid">
                                                            <div class="column width-12">
                                                                <div class="box rounded shadow">
                                                                    <h4 class="pb-0">Comparativo de arrecadação (Previsto/Realizado).
                                                                    </h4>
                                                        <div class="analise"><canvas id="CompArrePrevReal" height="300" width="1200px" style="width:1200px; height: 300px;"></canvas></div>
                                                                    <script>
                                                                    var lineChartData = {
                                                                        labels: [<?php
                                                                                        $mescSF4='0';
                                                                                    for($mescS04=0; $mescS04 < 12; $mescS04++){
                                                                                        $mescSF4++;
                                                                                        if($mescSF4 < 12): $vrg=','; else: $vrg=''; endif;
                                                                                        echo $mescSF4.$vrg;
                                                                                    }
                                                                                ?>],
                                                                        datasets: [{
                                                                            label: 'Arrecadação:',
                                                                            fillColor: "rgba(255,255,255,0)",
                                                                            strokeColor: "#227fbb",
                                                                            pointColor: "#227fbb",
                                                                            pointStrokeColor: "#227fbb",
                                                                            data: [<?php
                                                                                        $ms8N='0';
                                                                                        for($ms8=0; $ms8< '12'; $ms8++){
                                                                                            $ms8N++;
                                                                                            if($ms8N < 10): $ms8N='0'.$ms8N; else: $ms8N = $ms8N; endif; //04 - 10
                    
                                                                                            $bPEComp = "SELECT mes, sum(valorprevisto) as totalprevisto FROM financeiro WHERE tipoderegistro='CRÉDITO' AND mes='$ms8N' AND ano='$ano' GROUP BY mes";
                                                                                            $rPEComp = mysqli_query($con, $bPEComp);
                                                                                                $dPEComp = mysqli_fetch_array($rPEComp);
                                                                                            if(empty($dPEComp['totalprevisto'])):
                                                                                                echo '0,';
                                                                                            else:
                                                                                                echo $dPEComp['totalprevisto'].',';
                                                                                            endif;
                                                                                        }
                                                                                        ?>]
                                                                        }, {
                                                                            label: 'Saída',
                                                                            fillColor: "rgba(255,255,255,0)",
                                                                            strokeColor: "#2c3e51",
                                                                            pointColor: "#2c3e51",
                                                                            pointStrokeColor: "#2c3e51",
                                                                            data: [<?php
                                                                                        $ms9N='0';
                                                                                        for($ms9=0; $ms9< '12'; $ms9++){
                                                                                            $ms9N++;
                                                                                            if($ms9N < 10): $ms9N='0'.$ms9N; else: $ms9N = $ms9N; endif; //04 - 10
                    
                                                                                            $bREComp = "SELECT mes, sum(valorrealizado) as totalrealizado FROM financeiro WHERE tipoderegistro='CRÉDITO' AND mes='$ms9N' AND ano='$ano' GROUP BY mes";
                                                                                            $rREComp = mysqli_query($con, $bREComp);
                                                                                                $dREComp = mysqli_fetch_array($rREComp);
                                                                                            if(empty($dREComp['totalrealizado'])):
                                                                                                echo '0,';
                                                                                            else:
                                                                                                echo $dREComp['totalrealizado'].',';
                                                                                            endif;
                                                                                        }
                                                                                        ?>]
                                                                        }]

                                                                    };
                                                                    new Chart(document.getElementById("CompArrePrevReal").getContext(
                                                                        "2d")).Line(lineChartData);
                                                                    </script>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!--Comparação previsto/realizado receita-->

                                                    <!--Arrecadação e Investimento Geral-->
                                                    <div class="graph pt-20">
                                                        <div class="graph-grid">
                                                            <div class="column width-6 pt-20 graph-1">
                                                                <div class="box rounded shadow">
                                                                    <div class="grid-1">
                                                                        <h4 class="pb-0">Arrecadação (Receita) por categoria</h4>
                                                            <div class="analise"><canvas id="ArreCateg" height="300" width="550px" style="width: 550px; height: 300px;"></canvas></div>
                                                                        <script>
                                                                        var barChartData = {
                                                                            labels: [<?php
                                                                                        $bMidia = "SELECT categoria FROM financeiro WHERE ano='$ano' AND tipoderegistro='CRÉDITO' GROUP BY categoria ORDER by categoria ASC";
                                                                                            $rMidia = mysqli_query($con, $bMidia);
                                                                                                $TotalMidia = mysqli_num_rows($rMidia);
                                                                                                    $ContarMidia = 1;
                                                                                                    while($dMidia = mysqli_fetch_array($rMidia)):
                                                                                                        $virgula = ( $TotalMidia !== $ContarMidia ) ? ',' : '';
                                                                                                        echo '"'.substr($dMidia['categoria'], 0, 15).'"'.$virgula;
                                                                                                        $ContarMidia++;
                                                                                                    endwhile;
                                                                                        ?>],
                                                                            datasets: [{
                                                                                fillColor: "#1bb05d",
                                                                                strokeColor: "#1bb05d",
                                                                                data: [<?php
                                                                                            $bSegui = "SELECT sum(valorrealizado) as arrecadacao FROM financeiro WHERE ano='$ano' AND tipoderegistro='CRÉDITO' GROUP BY categoria ORDER BY categoria ASC";
                                                                                                $rSegui = mysqli_query($con, $bSegui);
                                                                                                    $TotalSegui = mysqli_num_rows($rSegui);
                                                                                                        $ContarSegui = 1;
                                                                                                        while($dSegui = mysqli_fetch_array($rSegui)):
                                                                                                            $virgula = ( $TotalSegui !== $ContarSegui ) ? ',' : '';
                                                                                                            echo $dSegui['arrecadacao'].$virgula;
                                                                                                            $ContarSegui++;
                                                                                                        endwhile;
                                                                                            ?>]
                                                                            }]

                                                                        };
                                                                        new Chart(document.getElementById("ArreCateg").getContext("2d"))
                                                                            .Bar(barChartData);
                                                                        </script>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="column width-6 pt-20 graph-1">
                                                                <div class="box rounded shadow">
                                                                    <div class="grid-1">
                                                                        <h4 class="pb-0">Arrecadação (Receita) por conta</h4>
                                                                <div class="analise"><canvas id="ArreConta" height="300" width="550px" style="width: 550px; height: 300px;"></canvas></div>
                                                                        <script>
                                                                        var barChartData = {
                                                                            labels: [<?php
                                                                                        $bArreConta = "SELECT conta1 FROM financeiro WHERE ano='$ano' AND tipoderegistro='CRÉDITO' GROUP BY conta1 ORDER by conta1 ASC";
                                                                                            $rArreConta = mysqli_query($con, $bArreConta);
                                                                                                $TotalArreConta = mysqli_num_rows($rArreConta);
                                                                                                    $ContarArreConta = 1;
                                                                                                    while($dArreConta = mysqli_fetch_array($rArreConta)):
                                                                                                        $virgula = ( $TotalArreConta !== $ContarArreConta ) ? ',' : '';
                                                                                                        echo '"'.substr($dArreConta['conta1'], 0, 15).'"'.$virgula;
                                                                                                        $ContarArreConta++;
                                                                                                    endwhile;
                                                                                    ?>],
                                                                            datasets: [{
                                                                                fillColor: "#1bb05d",
                                                                                strokeColor: "#1bb05d",
                                                                                data: [<?php
                                                                                            $bArreContaD = "SELECT sum(valorrealizado) as arrecadacao FROM financeiro WHERE ano='$ano' AND tipoderegistro='CRÉDITO' GROUP BY conta1 ORDER BY conta1 ASC";
                                                                                                $rArreContaD = mysqli_query($con, $bArreContaD);
                                                                                                    $TotalArreContaD = mysqli_num_rows($rArreContaD);
                                                                                                        $ContarArreContaD = 1;
                                                                                                        while($dArreContaD = mysqli_fetch_array($rArreContaD)):
                                                                                                            $virgula = ( $TotalArreContaD !== $ContarArreContaD ) ? ',' : '';
                                                                                                            echo $dArreContaD['arrecadacao'].$virgula;
                                                                                                            $ContarArreContaD++;
                                                                                                        endwhile;
                                                                                        ?>]
                                                                            }]

                                                                        };
                                                                        new Chart(document.getElementById("ArreConta").getContext("2d"))
                                                                            .Bar(barChartData);
                                                                        </script>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!--Arrecadação e Investimento Geral-->

                                                    <!--Arrecadação e Investimento Geral-->
                                                    <div class="graph pt-20">
                                                        <div class="graph-grid">
                                                            <div class="column width-12 pt-20 graph-1">
                                                                <div class="box rounded shadow">
                                                                    <div class="grid-1">
                                                                        <h4 class="pb-0">Arrecadação por ofertante</h4>
                                                            <div class="analise"><canvas id="ArrecadaPessoa" height="300" width="1200px" style="width: 1200px; height: 300px;"></canvas></div>
                                                                        <script>
                                                                        var barChartData = {
                                                                            labels: [<?php
                                                                                        $bMidia = "SELECT recebidode FROM financeiro WHERE ano='$ano' AND tipoderegistro='CRÉDITO' GROUP BY recebidode ORDER by recebidode ASC";
                                                                                            $rMidia = mysqli_query($con, $bMidia);
                                                                                                $TotalMidia = mysqli_num_rows($rMidia);
                                                                                                    $ContarMidia = 1;
                                                                                                    while($dMidia = mysqli_fetch_array($rMidia)):
                                                                                                        $virgula = ( $TotalMidia !== $ContarMidia ) ? ',' : '';
                                                                                                        $ofertante = base64_decode($dMidia['recebidode']);
                                                                                                        $ofertante = base64_decode($ofertante);
                                                                                                        echo '"'.substr($ofertante, 0, 15).'"'.$virgula;
                                                                                                        $ContarMidia++;
                                                                                                    endwhile;
                                                                                        ?>],
                                                                            datasets: [{
                                                                                fillColor: "#1bb05d",
                                                                                strokeColor: "#1bb05d",
                                                                                data: [<?php
                                                                                            $bSegui = "SELECT sum(valorrealizado) as totalofertado FROM financeiro WHERE ano='$ano' AND tipoderegistro='CRÉDITO' GROUP BY recebidode ORDER BY recebidode ASC";
                                                                                                $rSegui = mysqli_query($con, $bSegui);
                                                                                                    $TotalSegui = mysqli_num_rows($rSegui);
                                                                                                        $ContarSegui = 1;
                                                                                                        while($dSegui = mysqli_fetch_array($rSegui)):
                                                                                                            $virgula = ( $TotalSegui !== $ContarSegui ) ? ',' : '';
                                                                                                            echo $dSegui['totalofertado'].$virgula;
                                                                                                            $ContarSegui++;
                                                                                                        endwhile;
                                                                                            ?>]
                                                                            }]

                                                                        };
                                                                        new Chart(document.getElementById("ArrecadaPessoa").getContext(
                                                                            "2d")).Bar(barChartData);
                                                                        </script>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!--Arrecadação e Investimento Geral-->
                                                </div>
                                            </div>
                                            <div id="tabs-despesas">
                                                <div class="tab-content">

                                                    <!--Comparação previsto/realizado receita-->
                                                    <div class="graph">
                                                        <div class="graph-grid">
                                                            <div class="column width-12">
                                                                <div class="box rounded shadow">
                                                                    <h4 class="pb-0">Comparativo de saída (Previsto/Realizado).</h4>
                                                            <div class="analise"><canvas id="CompSaidaPrevReal" height="300" width="1200px" style="width:1200px; height: 300px;"></canvas></div>
                                                                    <script>
                                                                        var lineChartData = {
                                                                            labels: [<?php
                                                                                            $mescSF5='0';
                                                                                        for($mescS05=0; $mescS05 < 12; $mescS05++){
                                                                                            $mescSF5++;
                                                                                            if($mescSF5 < 12): $vrg=','; else: $vrg=''; endif;
                                                                                            echo $mescSF5.$vrg;
                                                                                        }
                                                                                    ?>],
                                                                            datasets: [{
                                                                                label: 'Pagamento:',
                                                                                fillColor: "rgba(255,255,255,0)",
                                                                                strokeColor: "#ff2200",
                                                                                pointColor: "#ff2200",
                                                                                pointStrokeColor: "#ff2200",
                                                                                data: [<?php
                                                                                            $ms10N='0';
                                                                                            for($ms10=0; $ms10< '12'; $ms10++){
                                                                                                $ms10N++;
                                                                                                if($ms10N < 10): $ms10N='0'.$ms10N; else: $ms10N = $ms10N; endif; //04 - 10
                                                                                                $bPEComp = "SELECT mes, sum(valorprevisto) as totalprevisto FROM financeiro WHERE tipoderegistro='DÉBITO' AND mes='$ms10N' AND ano='$ano' GROUP BY mes";
                                                                                                $rPEComp = mysqli_query($con, $bPEComp);
                                                                                                    $dPEComp = mysqli_fetch_array($rPEComp);
                                                                                                if(empty($dPEComp['totalprevisto'])):
                                                                                                    echo '0,';
                                                                                                else:
                                                                                                    echo $dPEComp['totalprevisto'].',';
                                                                                                endif;
                                                                                            }
                                                                                            ?>]
                                                                            }, {
                                                                                label: 'Saída',
                                                                                fillColor: "rgba(255,255,255,0)",
                                                                                strokeColor: "#b00",
                                                                                pointColor: "#b00",
                                                                                pointStrokeColor: "#b00",
                                                                                data: [<?php
                                                                                            $ms11N='0';
                                                                                            for($ms11=0; $ms11< '12'; $ms11++){
                                                                                                $ms11N++;
                                                                                                if($ms11N < 10): $ms11N='0'.$ms11N; else: $ms11N = $ms11N; endif; //04 - 10
                        
                                                                                                $bREComp = "SELECT mes, sum(valorrealizado) as totalrealizado FROM financeiro WHERE tipoderegistro='DÉBITO' AND mes='$ms11N' AND ano='$ano' GROUP BY mes";
                                                                                                $rREComp = mysqli_query($con, $bREComp);
                                                                                                    $dREComp = mysqli_fetch_array($rREComp);
                                                                                                if(empty($dREComp['totalrealizado'])):
                                                                                                    echo '0,';
                                                                                                else:
                                                                                                    echo $dREComp['totalrealizado'].',';
                                                                                                endif;
                                                                                            }
                                                                                            ?>]
                                                                            }]

                                                                        };
                                                                        new Chart(document.getElementById("CompSaidaPrevReal").getContext(
                                                                            "2d")).Line(lineChartData);
                                                                    </script>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!--Comparação previsto/realizado receita-->

                                                    <!--Arrecadação e Investimento Geral-->
                                                    <div class="graph pt-20">
                                                        <div class="graph-grid">
                                                            <div class="column width-6 pt-20 graph-1">
                                                                <div class="box rounded shadow">
                                                                    <div class="grid-1">
                                                                        <h4 class="pb-0">Pagamento (Saída) por categoria</h4>
                                                            <div class="analise"><canvas id="SaidaCateg" height="300" width="550px" style="width: 550px; height: 300px;"></canvas></div>
                                                                        <script>
                                                                            var barChartData = {
                                                                                labels: [<?php
                                                                                            if($mesnumero < 2): echo "'',"; endif;
                                                                                            $bMidia = "SELECT categoria FROM financeiro WHERE ano='$ano' AND tipoderegistro='DÉBITO' GROUP BY categoria ORDER by categoria ASC";
                                                                                                $rMidia = mysqli_query($con, $bMidia);
                                                                                                    $TotalMidia = mysqli_num_rows($rMidia);
                                                                                                        $ContarMidia = 1;
                                                                                                        while($dMidia = mysqli_fetch_array($rMidia)):
                                                                                                            $virgula = ( $TotalMidia !== $ContarMidia ) ? ',' : '';
                                                                                                            echo '"'.substr($dMidia['categoria'], 0, 15).'"'.$virgula;
                                                                                                            $ContarMidia++;
                                                                                                        endwhile;
                                                                                        ?>],
                                                                                datasets: [{
                                                                                    fillColor: "#ff2200",
                                                                                    strokeColor: "#ff2200",
                                                                                    data: [<?php
                                                                                                if($mesnumero < 2): echo "0,"; endif;
                                                                                                $bSegui = "SELECT sum(valorrealizado) as arrecadacao FROM financeiro WHERE ano='$ano' AND tipoderegistro='DÉBITO' GROUP BY categoria ORDER BY categoria ASC";
                                                                                                    $rSegui = mysqli_query($con, $bSegui);
                                                                                                        $TotalSegui = mysqli_num_rows($rSegui);
                                                                                                            $ContarSegui = 1;
                                                                                                            while($dSegui = mysqli_fetch_array($rSegui)):
                                                                                                                $virgula = ( $TotalSegui !== $ContarSegui ) ? ',' : '';
                                                                                                                echo $dSegui['arrecadacao'].$virgula;
                                                                                                                $ContarSegui++;
                                                                                                            endwhile;
                                                                                            ?>]
                                                                                }]

                                                                            };
                                                                            new Chart(document.getElementById("SaidaCateg").getContext(
                                                                                "2d")).Bar(barChartData);
                                                                        </script>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="column width-6 pt-20 graph-1">
                                                                <div class="box rounded shadow">
                                                                    <div class="grid-1">
                                                                        <h4 class="pb-0">Pagamento (Saída) por conta</h4>
                                                            <div class="analise"><canvas id="SaidaConta" height="300" width="550px" style="width: 550px; height: 300px;"></canvas></div>
                                                                        <script>
                                                                            var barChartData = {
                                                                                labels: [<?php
                                                                                            if($mesnumero < 2): echo "'',"; endif;
                                                                                            $bArreConta = "SELECT conta1 FROM financeiro WHERE ano='$ano' AND tipoderegistro='DÉBITO' GROUP BY conta1 ORDER by conta1 ASC";
                                                                                                $rArreConta = mysqli_query($con, $bArreConta);
                                                                                                    $TotalArreConta = mysqli_num_rows($rArreConta);
                                                                                                        $ContarArreConta = 1;
                                                                                                        while($dArreConta = mysqli_fetch_array($rArreConta)):
                                                                                                            $contafind = $dArreConta['conta1'];
                                                                                                            $bNConta="SELECT nomedaconta FROM contasfinanceiro WHERE conta='$contafind' AND ano='$ano'";
                                                                                                            $rNConta=mysqli_query($con, $bNConta);
                                                                                                                $dNConta=mysqli_fetch_array($rNConta);
                                                                                                            $contaenc = $dNConta['nomedaconta'];
                                                                                                            $virgula = ( $TotalArreConta !== $ContarArreConta ) ? ',' : '';
                                                                                                            echo '"'.substr($contaenc, 0, 15).'"'.$virgula;
                                                                                                            $ContarArreConta++;
                                                                                                        endwhile;
                                                                                                        if($contarArreConta <3):
                                                                                                            echo ",''";
                                                                                                        endif;
                                                                                            ?>],
                                                                                datasets: [{
                                                                                    fillColor: "#ff2200",
                                                                                    strokeColor: "#ff2200",
                                                                                    data: [<?php
                                                                                                if($mesnumero < 2): echo "0,"; endif;
                                                                                                $bArreContaD = "SELECT sum(valorrealizado) as pagoporconta FROM financeiro WHERE ano='$ano' AND tipoderegistro='DÉBITO' GROUP BY conta1 ORDER BY conta1 ASC";
                                                                                                    $rArreContaD = mysqli_query($con, $bArreContaD);
                                                                                                        $TotalArreContaD = mysqli_num_rows($rArreContaD);
                                                                                                            $ContarArreContaD = 1;
                                                                                                            while($dArreContaD = mysqli_fetch_array($rArreContaD)):
                                                                                                                $virgula = ( $TotalArreContaD !== $ContarArreContaD ) ? ',' : '';
                                                                                                                echo "'".$dArreContaD['pagoporconta']."'".$virgula;
                                                                                                                $ContarArreContaD++;
                                                                                                            endwhile;
                                                                                                        if($contarArreContaD <3):
                                                                                                            echo ",0";
                                                                                                        endif;
                                                                                                ?>]
                                                                                }]

                                                                            };
                                                                            new Chart(document.getElementById("SaidaConta").getContext(
                                                                                "2d")).Bar(barChartData);
                                                                        </script>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!--Arrecadação e Investimento Geral-->

                                                    <!--Arrecadação e Investimento Geral-->
                                                    <div class="graph pt-20">
                                                        <div class="graph-grid">
                                                            <div class="column width-12 pt-20 graph-1">
                                                                <div class="box rounded shadow">
                                                                    <div class="grid-1">
                                                                        <h4 class="pb-0">Pagamento por local</h4>
                                                                        <div class="analise"><canvas id="SaidaPessoa" height="300" width="1200px" style="width: 1200px; height: 300px;"></canvas></div>
                                                                        <script>
                                                                            var barChartData = {
                                                                                labels: [<?php
                                                                                            $bMidia = "SELECT recebidode FROM financeiro WHERE ano='$ano' AND tipoderegistro='DÉBITO' GROUP BY recebidode ORDER by recebidode ASC";
                                                                                                $rMidia = mysqli_query($con, $bMidia);
                                                                                                    $TotalMidia = mysqli_num_rows($rMidia);
                                                                                                        $ContarMidia = 1;
                                                                                                        while($dMidia = mysqli_fetch_array($rMidia)):
                                                                                                            $virgula = ( $TotalMidia !== $ContarMidia ) ? ',' : '';
                                                                                                            $ofertante = base64_decode($dMidia['recebidode']);
                                                                                                            $ofertante = base64_decode($ofertante);
                                                                                                            if(empty($ofertante)):
                                                                                                                echo '"'.substr('Não identificado', 0, 15).'"'.$virgula;
                                                                                                            else:
                                                                                                                echo '"'.substr($ofertante, 0, 15).'"'.$virgula;
                                                                                                            endif;
                                                                                                            $ContarMidia++;
                                                                                                        endwhile;
                                                                                            ?>],
                                                                                datasets: [{
                                                                                    fillColor: "#ff2200",
                                                                                    strokeColor: "#ff2200",
                                                                                    data: [<?php
                                                                                                $bSegui = "SELECT sum(valorrealizado) as totalofertado FROM financeiro WHERE ano='$ano' AND tipoderegistro='DÉBITO' GROUP BY recebidode ORDER BY recebidode ASC";
                                                                                                    $rSegui = mysqli_query($con, $bSegui);
                                                                                                        $TotalSegui = mysqli_num_rows($rSegui);
                                                                                                            $ContarSegui = 1;
                                                                                                            while($dSegui = mysqli_fetch_array($rSegui)):
                                                                                                                $virgula = ( $TotalSegui !== $ContarSegui ) ? ',' : '';
                                                                                                                echo $dSegui['totalofertado'].$virgula;
                                                                                                                $ContarSegui++;
                                                                                                            endwhile;
                                                                                                ?>]
                                                                                }]

                                                                            };
                                                                            new Chart(document.getElementById("SaidaPessoa").getContext(
                                                                                "2d")).Bar(barChartData);
                                                                        </script>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!--Arrecadação e Investimento Geral-->
                                                </div>
                                            </div>
                                            <div id="tabs-movimentacao" class="active animate">
                                                <div class="tab-content">
                                                    <div class="column width-12">
                                                        <div class="tabs line-nav vertical rounded left">
                                                            <ul class="tab-nav">
                                                                <?php
                                                                    $bAnoEx="SELECT ano FROM financeiro GROUP BY ano ORDER BY ano DESC";
                                                                    $rAEx=mysqli_query($con, $bAnoEx);
                                                                            $Pa=0;
                                                                        while($dAEx=mysqli_fetch_array($rAEx)):
                                                                            $Pa++;
                                                                    if($dAEx['ano'] === $ano): //Se 1º
                                                                        $tabativa="class='active'";
                                                                    else:
                                                                        $tabativa='';
                                                                    endif;
                                                                ?>
                                                                <li <? echo $tabativa; ?>>
                                                                    <a href="#tabs-ano-<?php echo $dAEx['ano'];?>"> Extrato financeiro de <strong><?php echo $dAEx['ano'];?></strong></a>
                                                                </li>
                                                                <?php
                                                                    endwhile;
                                                                ?>
                                                            </ul>
                                                            <div class="tab-panes">
                                                                <?php
                                                                    $bAnoExP="SELECT ano FROM financeiro GROUP BY ano ORDER BY ano DESC";
                                                                    $rAExP=mysqli_query($con, $bAnoExP);
                                                                        while($dAExP=mysqli_fetch_array($rAExP)):
                                                                ?>
                                                                <div id="tabs-ano-<?php echo $dAExP['ano'];?>">
                                                                    <h3> Extrato de <strong><?php echo $dAExP['ano']; $anotab = $dAExP['ano'];?></strong></h3>

                                                                    <hr />

                                                                    <div class="column width-5">
                                                                        <a href="./analise?<?echo $linkSeguro.'backup='.$ano;?>" class="column full width button small bkg-blue bkg-hover-blue color-white color-hover-white hard-shadow shadow left">
                                                                            Baixar <strong>backup</strong> em .CSV
                                                                        </a>
                                                                    </div>
                                                                    <div class="column width-7">
                                                                        <button type="button" id="btnPrint<?php echo $anotab;?>" class="column full width button small border-blue border-hover-blue color-blue color-hover-blue hard-shadow shadow right">
                                                                            Imprimir movimentação anual
                                                                        </button>
                                                                    </div>
                                                                    <div id="divpublicacao<?php echo $anotab; $prints[] = $anotab; ?>">
                                                                        <div class="column width-12 pt-20 pb-50">
                                                                            <img src="images/igreja-batista-emanuel-logo-Jesus-Cristo.png" class="left"/>
                                                                            <span class="offset-1 text-uppercase pt-0">www.igrejaemanuel.com.br/extratofinanceiro<?php echo $anotab;?></span>
                                                                            <div class="accordion rounded pt-30" data-toggle-multiple>
                                                                                <ul>
                                                                                    <?php
                                                                                        $bMesExP="SELECT mes FROM financeiro WHERE ano='$anotab' GROUP BY mes ORDER BY mes ASC";
                                                                                        $rMExP=mysqli_query($con, $bMesExP);
                                                                                            while($dMExP=mysqli_fetch_array($rMExP)):
                                                                                    ?>
                                                                                    <li class="">
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
                                                                                            elseif($mesaco == '12'):
                                                                                                $mes_nome = "Dezembro";
                                                                                            endif;
                                                                                            
                                                                                            echo $mes_nome.'/'.$anotab; 
                                                                                        ?>
                                                                                        </a>
                                                                                        <div id="accordion-<?php echo $anotab.'-'.$dMExP['mes'];?>">
                                                                                            <div class="accordion-content pt-50 pb-80">
                                                                                                <table class="table small rounded striped">
                                                                                                    <thead>
                                                                                                        <tr
                                                                                                            class="bkg-charcoal color-white center">
                                                                                                            <th>
                                                                                                                <span class="text-medium">
                                                                                                                    Nº
                                                                                                                </span>
                                                                                                            </th>
                                                                                                            <th>
                                                                                                                <span class="text-medium">
                                                                                                                    DATA
                                                                                                                </span>
                                                                                                            </th>
                                                                                                            <th>
                                                                                                                <span class="text-medium">
                                                                                                                    DESCRIÇÃO
                                                                                                                </span>
                                                                                                            </th>
                                                                                                            <th colspan="2">
                                                                                                                <span class="text-medium">
                                                                                                                    ENTRADA
                                                                                                                </span>
                                                                                                            </th>
                                                                                                            <th colspan="2">
                                                                                                                <span class="text-medium">
                                                                                                                    SAÍDA
                                                                                                                </span>
                                                                                                            </th>
                                                                                                        </tr>
                                                                                                    </thead>
                                                                                                    <tbody>
                                                                                                        <?php
                                                                                                            $datafinalizada="$anotab-$mesaco-";
                                                                                                            $bMovFin="SELECT dataprevista, datarealizada, descricao, valorprevisto, valorrealizado, tipoderegistro FROM financeiro WHERE datarealizada LIKE '$datafinalizada%' AND ano='$anotab' ORDER by datarealizada ASC, dia ASC, id asc";
                                                                                                            $rMovFin=mysqli_query($con, $bMovFin);
                                                                                                                    $nmf = 0;
                                                                                                                while($dMovFin=mysqli_fetch_array($rMovFin)):
                                                                                                                    $nmf++;
                                                                                                        ?>
                                                                                                        <tr>
                                                                                                            <td class="color-black"
                                                                                                                width="5%" valign="medium">
                                                                                                                <span class="text-medium">
                                                                                                                    <?php echo $nmf;?>
                                                                                                                </span>
                                                                                                            </td>
                                                                                                            <td class="color-black"
                                                                                                                width="13%" valign="medium">
                                                                                                                <span class="text-medium">
                                                                                                                    <?php
                                                                                                                        if(!empty($dMovFin['datarealizada'])):
                                                                                                                            echo date(('d/m/Y'), strtotime($dMovFin['datarealizada']));
                                                                                                                        else:
                                                                                                                            echo date(('d/m/Y'), strtotime($dMovFin['dataprevista']));
                                                                                                                        endif;
                                                                                                                    ?>
                                                                                                                </span>
                                                                                                            </td>
                                                                                                            <td class="color-black"
                                                                                                                width="52%" valign="medium">
                                                                                                                <span class="text-medium">
                                                                                                                    <?php $descmf = base64_decode($dMovFin['descricao']); echo base64_decode($descmf);?>
                                                                                                                </span>
                                                                                                            </td>
                                                                                                            <td class="color-black left"
                                                                                                                width="3%" valign="medium">
                                                                                                                <span class="text-medium">
                                                                                                                    <?php
                                                                                                                        if($dMovFin['tipoderegistro'] === 'CRÉDITO'):
                                                                                                                    ?>
                                                                                                                    R$
                                                                                                                    <?php
                                                                                                                        endif;
                                                                                                                    ?>
                                                                                                                </span>
                                                                                                            </td>
                                                                                                            <td class="color-black right"
                                                                                                                width="12%" valign="medium">
                                                                                                                <span class="text-medium">
                                                                                                                    <?php
                                                                                                                        if($dMovFin['tipoderegistro'] === 'CRÉDITO'):
                                                                                                                            if(!empty($dMovFin['valorrealizado'])):
                                                                                                                                $valorcreditado = $dMovFin['valorrealizado'];
                                                                                                                            else:
                                                                                                                                $valorcreditado = $dMovFin['valorprevisto'];
                                                                                                                            endif;
                                                                                                                                        
                                                                                                                            if($valorcreditado > 1000):
                                                                                                                                $valorcreditado = number_format($valorcreditado, 2, '.', ',');
                                                                                                                            elseif($valorcreditado < 1000):
                                                                                                                                $valorcreditado = number_format($valorcreditado, 2, ',', ',');
                                                                                                                            endif;

                                                                                                                            echo $valorcreditado;
                                                                                                                        endif;
                                                                                                                    ?>
                                                                                                                </span>
                                                                                                            </td>
                                                                                                            <td class="color-black left"
                                                                                                                width="3%" valign="medium">
                                                                                                                <span class="text-medium">
                                                                                                                    <?php
                                                                                                                        if($dMovFin['tipoderegistro'] === 'DÉBITO'):
                                                                                                                    ?>
                                                                                                                    R$
                                                                                                                    <?php
                                                                                                                        endif;
                                                                                                                    ?>
                                                                                                                </span>
                                                                                                            </td>
                                                                                                            <td class="color-black right"
                                                                                                                width="12%" valign="medium">
                                                                                                                <span class="text-medium">
                                                                                                                    <?php
                                                                                                                        if($dMovFin['tipoderegistro'] === 'DÉBITO'):
                                                                                                                            if(!empty($dMovFin['valorrealizado'])):
                                                                                                                                $valordebitado = -$dMovFin['valorrealizado'];
                                                                                                                                $valordebitadoPOR = $dMovFin['valorrealizado'];
                                                                                                                            else:
                                                                                                                                $valordebitado = -$dMovFin['valorprevisto'];
                                                                                                                                $valordebitadoPOR = $dMovFin['valorprevisto'];
                                                                                                                            endif;
                                                                                                                                        
                                                                                                                            if($valordebitadoPOR >= 1000):
                                                                                                                                @$valordebitadoPOR = number_format($valordebitadoPOR, 2, '.', ',');
                                                                                                                            elseif($valordebitadoPOR < 1000):
                                                                                                                                @$valordebitadoPOR = number_format($valordebitadoPOR, 2, ',', ',');
                                                                                                                            endif;

                                                                                                                            echo $valordebitadoPOR;
                                                                                                                        endif;
                                                                                                                    ?>
                                                                                                                </span>
                                                                                                            </td>
                                                                                                        </tr>
                                                                                                        <?php
                                                                                                                            endwhile;
                                                                                                                        ?>
                                                                                                    </tbody>
                                                                                                </table>
                                                                                                            
                                                                                                <!-- SALDOS -->
                                                                                                <div class="column width-7 pb-50">
                                                                                                    <table class="table small rounded striped">
                                                                                                        <thead>
                                                                                                            <tr class="bkg-charcoal color-white center">
                                                                                                                <th>
                                                                                                                    <span
                                                                                                                        class="text-medium">
                                                                                                                        SALDO ANTERIOR
                                                                                                                    </span>
                                                                                                                </th>
                                                                                                                <th>
                                                                                                                    <span
                                                                                                                        class="text-medium">
                                                                                                                        SALDO ATUAL
                                                                                                                    </span>
                                                                                                                </th>
                                                                                                                <th>
                                                                                                                    <span
                                                                                                                        class="text-medium">
                                                                                                                        PARA CONFERÊNCIA
                                                                                                                    </span>
                                                                                                                </th>
                                                                                                            </tr>
                                                                                                        </thead>
                                                                                                        <tbody>
                                                                                                            <tr>

                                                                                                                <?php
                                                                                                                    $mesanterior = $anotab.'-'.$mesaco.'-'.date('d');
                                                                                                                    $mesanterior = date(('m'), strtotime($mesanterior.'- 1 months'));
                                                                                                                    if($mesaco > 0 AND $mesaco < 02):
                                                                                                                        $anoanterior = $anotab.'-'.$mesaco.'-'.date('d');
                                                                                                                        $anoanterior = date(('Y'), strtotime($mesanterior.'- 1 year'));
                                                                                                                    else:
                                                                                                                        $anoanterior = $anotab;
                                                                                                                    endif;

                                                                                                                    //Buscar saldo anterior CRÉDITO
                                                                                                                    if($mesaco > 0 AND $mesaco < 02): $anodebusca = $anoanterior; else: $anodebusca=$ano; endif;
                                                                                                                    $vencimentobuscado = "$anodebusca-$mesanterior-";
                                                                                                                    $bSAntC="SELECT sum(valorrealizado) as totalsaldocreditoAnt FROM financeiro WHERE tipoderegistro='CRÉDITO' AND conta1!='272112-8' AND conta1!='0000' AND mes < '$mesaco' AND ano='$anotab'";
                                                                                                                    $rSAntC=mysqli_query($con, $bSAntC);
                                                                                                                        $dSAntC=mysqli_fetch_array($rSAntC);

                                                                                                                        $creditoanterior=$dSAntC['totalsaldocreditoAnt'];

                                                                                                                    //Buscar saldo anterior DÉBITO
                                                                                                                    $bSAntD="SELECT sum(valorrealizado) as totalsaldodebitoAnt FROM financeiro WHERE tipoderegistro='DÉBITO' AND conta1!='272112-8' AND conta1!='0000' AND mes < '$mesaco' AND ano='$anotab'";
                                                                                                                    $rSAntD=mysqli_query($con, $bSAntD);
                                                                                                                        $dSAntD=mysqli_fetch_array($rSAntD);

                                                                                                                        $debitoanterior=$dSAntD['totalsaldodebitoAnt'];
                                                                                                                    
                                                                                                                    $SaldoAnterior =  $creditoanterior-$debitoanterior;
                                                                                                                    
                                                                                                                    //Buscar saldo atual CRÉDITO
                                                                                                                    $bSAc="SELECT sum(valorrealizado) as totalsaldocredito FROM financeiro WHERE mes='$mesaco' AND ano='$anotab' AND tipoderegistro='CRÉDITO' AND conta1!='272112-8' AND conta1!='0000'";
                                                                                                                    $rSAc=mysqli_query($con, $bSAc);
                                                                                                                        $dSAc=mysqli_fetch_array($rSAc);

                                                                                                                    //Buscar saldo atual DÉBITO
                                                                                                                    $bSAd="SELECT sum(valorrealizado) as totalsaldodebito FROM financeiro WHERE mes='$mesaco' AND ano='$anotab' AND tipoderegistro='DÉBITO' AND conta1!='272112-8' AND conta1!='0000'";
                                                                                                                    $rSAd=mysqli_query($con, $bSAd);
                                                                                                                        $dSAd=mysqli_fetch_array($rSAd);

                                                                                                                    $SaldoAtual = $dSAc['totalsaldocredito'] - $dSAd['totalsaldodebito'];
                                                                                                                    
                                                                                                                    $SaldoAnterior = number_format($SaldoAnterior, 2, ',', '.');
                                                                                                                    $SaldoAtual = number_format($SaldoAtual, 2, ',', '.');
                                                                                                                ?>
                                                                                                                <td class="right">
                                                                                                                    <span class="text-medium">
                                                                                                                        R$ <?php echo $SaldoAnterior;?>
                                                                                                                    </span>
                                                                                                                </td>
                                                                                                                <td class="right">
                                                                                                                    <span class="text-medium">
                                                                                                                        R$ <?php echo $SaldoAtual;?>
                                                                                                                    </span>
                                                                                                                </td>
                                                                                                                <td>
                                                                                                                    <span
                                                                                                                        class="text-medium">
                                                                                                                        R$
                                                                                                                    </span>
                                                                                                                </td>
                                                                                                            </tr>
                                                                                                        </tbody>
                                                                                                    </table>
                                                                                                </div>
                                                                                                
                                                                                                <!-- DETALHES DA CONTA -->
                                                                                                <div class="column width-5 pb-50">
                                                                                                    <table class="table small rounded striped">
                                                                                                        <thead>
                                                                                                            <tr class="bkg-charcoal color-white center">
                                                                                                                <th colspan='2'>
                                                                                                                    <span class="text-medium">
                                                                                                                        SALDO POR CONTA
                                                                                                                    </span>
                                                                                                                </th>
                                                                                                            </tr>
                                                                                                        </thead>
                                                                                                        <tbody>
                                                                                                            <!-- DETALHES DO SALDO -->
                                                                                                            <?php
                                                                                                                //Buscar nome da conta
                                                                                                                $bNC="SELECT nomedaconta, conta FROM contasfinanceiro WHERE conta!='272112-8' AND conta!='0000' GROUP BY conta";
                                                                                                                $rNC=mysqli_query($con, $bNC);
                                                                                                                    while($dNC=mysqli_fetch_array($rNC)):
                                                                                                                    $nomedaconta=$dNC['nomedaconta'];
                                                                                                                    $contacredito=$dNC['conta'];
                                                                                                                    
                                                                                                                                        
                                                                                                                        /*Buscar saldo anterior */
                                                                                                                        if($mesaco > 0 AND $mesaco < 02): $anodebusca = $anoanterior; else: $anodebusca=$ano; endif;
                                                                                                                        $vencimentobuscado = "$anodebusca-$mesanterior-";

                                                                                                                        $bASan="SELECT sum(valorrealizado) as totalcontacreditoanterior FROM financeiro WHERE tipoderegistro='CRÉDITO' AND conta1!='272112-8' AND conta1!='0000' AND conta1='$contacredito' AND ano='$ano' AND mes < '$mesaco'";
                                                                                                                        $rASn=mysqli_query($con, $bASan);
                                                                                                                            $dCant=mysqli_fetch_array($rASn);

                                                                                                                            $contacreditoanterior=$dCant['totalcontacreditoanterior'];

                                                                                                                        //Buscar saldo anterior DÉBITO
                                                                                                                        $bSAntD="SELECT sum(valorrealizado) as totalcontadebitoanterior FROM financeiro WHERE tipoderegistro='DÉBITO' AND conta1!='272112-8' AND conta1!='0000' AND conta1='$contacredito' AND ano='$ano' AND mes < '$mesaco'";
                                                                                                                        $rSAntD=mysqli_query($con, $bSAntD);
                                                                                                                            $dSAntD=mysqli_fetch_array($rSAntD);

                                                                                                                            $contadebitoanterior=$dSAntD['totalcontadebitoanterior'];
                                                                                                                        
                                                                                                                        $saldocontaanterior =  $contacreditoanterior - ($contadebitoanterior);
                                                                                                                        /*Buscar saldo anterior */
                                                                                                                        
                                                                                                                    $bSCc="SELECT conta1, tipoderegistro, sum(valorrealizado) as totalsaldocredito FROM financeiro WHERE conta1!='272112-8' AND conta1!='0000' AND conta1='$contacredito' AND mes='$mesaco' AND ano='$anotab' AND tipoderegistro='CRÉDITO' GROUP BY conta1";
                                                                                                                    $rSCc=mysqli_query($con, $bSCc);
                                                                                                                        $nSC=mysqli_num_rows($rSCc);
                                                                                                                        if($nSC > 0): 
                                                                                                                            // while($dSCc=mysqli_fetch_array($rSCc)):
                                                                                                                            $dSCc=mysqli_fetch_array($rSCc);
                                                                                                                                $saldocredito=$dSCc['totalsaldocredito'];
                                                                                                                            // endwhile;
                                                                                                                        else:
                                                                                                                            $saldocredito=0.0;
                                                                                                                        endif;
                                                                                                                            

                                                                                                                    $bSD="SELECT conta1, tipoderegistro, sum(valorrealizado) as totalsaldodebito FROM financeiro WHERE conta1!='272112-8' AND conta1!='0000' AND conta1='$contacredito' AND mes='$mesaco' AND ano='$anotab' AND tipoderegistro='DÉBITO' GROUP BY conta1";
                                                                                                                    $rSD=mysqli_query($con, $bSD);
                                                                                                                        $nSD=mysqli_num_rows($rSD);
                                                                                                                        if($nSD > 0): 
                                                                                                                            // while($dSD=mysqli_fetch_array($rSD)):
                                                                                                                            $dSD=mysqli_fetch_array($rSD);
                                                                                                                                $saldododebito=$dSD['totalsaldodebito'];
                                                                                                                            // endwhile;
                                                                                                                        else:
                                                                                                                            $saldododebito=0.0;
                                                                                                                        endif;
                                                                                                                        
                                                                                                                        $saldodacontaatual=$saldocredito - ($saldododebito); //Valor do mês atual

                                                                                                                        $saldodaconta=$saldocontaanterior + $saldodacontaatual; /* Aqui os valores estão convencionados em +ou- então não precisa converter; -() */
                                                                                                                        
                                                                                                                        $saldodaconta=number_format($saldodaconta, 2, ',', '.');
                                                                                                            ?>
                                                                                                            <tr class="right">
                                                                                                                <td>
                                                                                                                    <span
                                                                                                                        class="text-medium">
                                                                                                                        <?php echo $nomedaconta." ($contacredito)"; ?>
                                                                                                                    </span>
                                                                                                                </td>
                                                                                                                <td>
                                                                                                                    <span class="text-medium">
                                                                                                                        R$ <?php echo $saldodaconta; ?>
                                                                                                                    </span>
                                                                                                                </td>
                                                                                                            </tr>
                                                                                                            <?php endwhile; ?>
                                                                                                            <!-- DETALHES DO SALDO -->
                                                                                                        </tbody>
                                                                                                    </table>
                                                                                                </div>
                                                                                                <!-- DETALHES DA CONTA -->
                                                                                                <div class="clear pt-50 pb-150"></div>
                                                                                            </div>
                                                                                        </div>
                                                                                    </li>
                                                                                    <?php
                                                                                        endwhile;
                                                                                    ?>
                                                                                </ul>
                                                                            </div>
                                                                        </div>
                                                                        <div class="clear pt-50 pb-50"></div>
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
            </div>
            <div class="printable"></div>

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