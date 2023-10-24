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
                                                        <h1 class="mb-0"><strong>Movimentação</strong> financeira de <strong>emergência</strong></h1>
                                                        <p class="text-large color-charcoal mb-0 mb-mobile-20">Por isso não corro sem objetivo.</p>
                                                    </div>
                                                </div>
                                                <div class="column width-4 v-align-middle">
                                                    <?php
                                                        $bVMpRECEITA = "SELECT sum(valorrealizado) as receitarealizada FROM financeiro WHERE NOT (valorrealizado = '') AND tipoderegistro='CRÉDITO' AND ano='$ano' AND conta1='272112-8' OR conta1='0000' AND tipoderegistro='CRÉDITO' AND ano='$ano'"; //Venda Mercado Pago.
                                                        $rVMpRECEITA = mysqli_query($con, $bVMpRECEITA);
                                                            $dVMpR = mysqli_fetch_array($rVMpRECEITA);
                
                                                            $ReceitaDisponivelDaEmpresa = $dVMpR['receitarealizada'];
                
                                                        //SQMP = Saque do Mercado Pago
                                                            $bVMpDEBITO = "SELECT sum(valorrealizado) as saquesrealizados FROM financeiro WHERE NOT (valorrealizado = '') AND tipoderegistro='DÉBITO' AND ano='$ano' AND conta1='272112-8' OR conta1='0000' AND tipoderegistro='DÉBITO' AND ano='$ano'"; //Venda Mercado Pago.
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
                                    <div class="column width-12">
                                        <div class="tabs line-nav vertical rounded left">
                                            <ul class="tab-nav">
                                                <?php
                                                    $bAnoEx="SELECT ano FROM financeiro WHERE conta1='272112-8' OR conta1='0000' GROUP BY ano ORDER BY ano DESC";
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
                                                    <a href="#tabs-ano-<?php echo $dAEx['ano'];?>"> Extrato emergencial de <strong><?php echo $dAEx['ano'];?></strong></a>
                                                </li>
                                                <?php
                                                    endwhile;
                                                ?>
                                            </ul>
                                            <div class="tab-panes">
                                                <?php
                                                    $bAnoExP="SELECT ano FROM financeiro WHERE conta1='272112-8' OR conta1='0000' GROUP BY ano ORDER BY ano DESC";
                                                    $rAExP=mysqli_query($con, $bAnoExP);
                                                        while($dAExP=mysqli_fetch_array($rAExP)):
                                                ?>
                                                <div id="tabs-ano-<?php echo $dAExP['ano'];?>">
                                                    <h3> Extrato <strong>emergencial de <?php echo $dAExP['ano']; $anotab = $dAExP['ano'];?></strong></h3>

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
                                                                        $bMesExP="SELECT mes FROM financeiro WHERE ano='$anotab' AND conta1='272112-8' OR conta1='0000' GROUP BY mes ORDER BY mes ASC";
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
                                                                                            $datafinalizada=$anotab.'-'.$mesaco.'-';
                                                                                            // $bMovFin="SELECT dataprevista, datarealizada, descricao, valorprevisto, valorrealizado, tipoderegistro FROM financeiro WHERE conta1='272112-8' OR conta1='0000' AND datarealizada LIKE '$datafinalizada%' AND datarealizada!='' ORDER by datarealizada ASC, dia ASC, id asc";
                                                                                            $bMovFinE="SELECT dataprevista, datarealizada, descricao, valorprevisto,valorrealizado,conta1, tipoderegistro from financeiro where NOT (datarealizada ='') AND conta1='272112-8' AND datarealizada like '$datafinalizada%' OR conta1='0000' AND datarealizada like '$datafinalizada%'";
                                                                                            $rMovFin=mysqli_query($con, $bMovFinE);
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
                                                                                                    <?php $descmf = base64_decode($dMovFin['descricao']); echo base64_decode($descmf).' ('.$dMovFin['conta1'].')';?>
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
                                                                                        <? endwhile;?>
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
                    $bSAntC="SELECT sum(valorrealizado) as totalsaldocreditoAnt FROM financeiro WHERE conta1='272112-8' AND tipoderegistro='CRÉDITO' AND mes < '$mesaco' AND ano='$anotab' OR conta1='0000' AND tipoderegistro='CRÉDITO' AND mes < '$mesaco' AND ano='$anotab'";
                    $rSAntC=mysqli_query($con, $bSAntC);
                        $dSAntC=mysqli_fetch_array($rSAntC);

                        $creditoanterior=$dSAntC['totalsaldocreditoAnt'];

                    //Buscar saldo anterior DÉBITO
                    $bSAntD="SELECT sum(valorrealizado) as totalsaldodebitoAnt FROM financeiro WHERE conta1='272112-8' AND tipoderegistro='DÉBITO' AND mes < '$mesaco' AND ano='$anotab' OR conta1='0000' AND tipoderegistro='DÉBITO' AND mes < '$mesaco' AND ano='$anotab'";
                    $rSAntD=mysqli_query($con, $bSAntD);
                        $dSAntD=mysqli_fetch_array($rSAntD);

                        $debitoanterior=$dSAntD['totalsaldodebitoAnt'];
                    
                    $SaldoAnterior =  $creditoanterior-$debitoanterior;
                    
                    //Buscar saldo atual CRÉDITO
                    $bSAc="SELECT sum(valorrealizado) as totalsaldocredito FROM financeiro WHERE conta1='272112-8' AND mes='$mesaco' AND ano='$anotab' AND tipoderegistro='CRÉDITO' OR conta1='0000' AND mes='$mesaco' AND ano='$anotab' AND tipoderegistro='CRÉDITO'";
                    $rSAc=mysqli_query($con, $bSAc);
                        $dSAc=mysqli_fetch_array($rSAc);

                    //Buscar saldo atual DÉBITO
                    $bSAd="SELECT sum(valorrealizado) as totalsaldodebito FROM financeiro WHERE conta1='272112-8' AND mes='$mesaco' AND ano='$anotab' AND tipoderegistro='DÉBITO' OR conta1='0000' AND mes='$mesaco' AND ano='$anotab' AND tipoderegistro='DÉBITO'";
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
                $bNC="SELECT nomedaconta, conta FROM contasfinanceiro WHERE conta='272112-8' OR conta='0000' GROUP BY conta";
                $rNC=mysqli_query($con, $bNC);
                    while($dNC=mysqli_fetch_array($rNC)):
                    $nomedaconta=$dNC['nomedaconta'];
                    $contacredito=$dNC['conta'];
                    
                                        
                        /*Buscar saldo anterior */
                        if($mesaco > 0 AND $mesaco < 02): $anodebusca = $anoanterior; else: $anodebusca=$ano; endif;
                        $vencimentobuscado = "$anodebusca-$mesanterior-";

                        $bASan="SELECT sum(valorrealizado) as totalcontacreditoanterior FROM financeiro WHERE conta1='$contacredito' AND tipoderegistro='CRÉDITO' AND ano='$ano' AND mes < '$mesaco'";
                        $rASn=mysqli_query($con, $bASan);
                            $dCant=mysqli_fetch_array($rASn);

                            $contacreditoanterior=$dCant['totalcontacreditoanterior'];

                        //Buscar saldo anterior DÉBITO
                        $bSAntD="SELECT sum(valorrealizado) as totalcontadebitoanterior FROM financeiro WHERE conta1='$contacredito' AND tipoderegistro='DÉBITO' AND ano='$ano' AND mes < '$mesaco'";
                        $rSAntD=mysqli_query($con, $bSAntD);
                            $dSAntD=mysqli_fetch_array($rSAntD);

                            $contadebitoanterior=$dSAntD['totalcontadebitoanterior'];
                        
                        $saldocontaanterior =  $contacreditoanterior - ($contadebitoanterior);
                        /*Buscar saldo anterior */
                        
                    $bSCc="SELECT conta1, tipoderegistro, sum(valorrealizado) as totalsaldocredito FROM financeiro WHERE conta1='$contacredito' AND mes='$mesaco' AND ano='$anotab' AND tipoderegistro='CRÉDITO' GROUP BY conta1";
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
                            

                    $bSD="SELECT conta1, tipoderegistro, sum(valorrealizado) as totalsaldodebito FROM financeiro WHERE conta1='$contacredito' AND mes='$mesaco' AND ano='$anotab' AND tipoderegistro='DÉBITO' GROUP BY conta1";
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