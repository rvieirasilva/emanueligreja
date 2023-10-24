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
        

	//var_dump($_SESSION);
    //Dados para registrar o log do cliente.
        $mensagem = ("\n$nomedocolaborador ($matricula) Acessou o portal da transparência. Isto através do IP: $ip, na data $dia às $hora.\n"); //Escreva a mensagem que será gravada no log.
        include "registrolog.php";

    if($mesnumero == '03' OR $mesnumero == '06' OR $mesnumero == '09' OR $mesnumero == '12'):
        if($dia >= 25 AND $dia <= 30):
            //Ver se é lider
            $ministerioacesso = explode(',', $ministerio);
            if(in_array('Membro', $ministerioacesso) OR in_array('Diacono', $ministerioacesso) OR in_array('Diáconisa', $ministerioacesso)):
                $_SESSION['cordamensagem'] = "red";
                $_SESSION['mensagem']="O acesso a esta área é restrito ao ministério pastoral";
                header("location: emanuel?$linkSeguro");
            endif;
        else:
            header("location: emanuel?$linkSeguro");
        endif;
    else:
        header("location: emanuel?$linkSeguro");    
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
                                                        <h1 class="mb-0"><strong>Transparência</strong> Financeira da Emanuel.</h1>
                                                        <p class="text-large color-charcoal mb-0 mb-mobile-20">
                                                            Nossa missão é <strong>Anunciar Jesus e ser culturalmente relevante.</strong>
                                                        </p>
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
                                <div class="column width-12 pb-90 ">          
                                    <div class="tabs line-nav vertical rounded left">
                                        <ul class="tab-nav">
                                            <?php
                                                            $bAnoEx="SELECT ano FROM financeiro GROUP BY ano ORDER BY ano DESC";
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
                                                    Extrato financeiro de
                                                    <strong><?php echo $dAEx['ano'];?></strong>
                                                </a>
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
                                                <h3>
                                                    Extrato de
                                                    <strong><?php echo $dAExP['ano']; $anotab = $dAExP['ano'];?></strong>
                                                </h3>

                                                <hr />

                                                <div class="column width-12">

                                                    <button type="button" id="btnPrint<?php echo $anotab;?>"
                                                        class="column full width button small border-blue border-hover-blue color-blue color-hover-blue hard-shadow shadow right">
                                                        Imprimir movimentação anual
                                                    </button>
                                                </div>

                                                <div class="column width-12 pt-20"
                                                    id="divpublicacao<?php echo $anotab; $prints[] = $anotab; ?>">
                                                    <div class="accordion rounded mb-50">
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
                                                                    else:
                                                                        $mes_nome = "Dezembro";
                                                                    endif;
                                                                    
                                                                    echo $mes_nome.'/'.$anotab; 
                                                                ?>
                                                                </a>
                                                                <div
                                                                    id="accordion-<?php echo $anotab.'-'.$dMExP['mes'];?>">
                                                                    <div class="accordion-content">
                                                                        <table class="table small rounded striped">
                                                                            <thead>
                                                                                <tr
                                                                                    class="bkg-charcoal color-white center">
                                                                                    <th>
                                                                                        <span class="text-small">
                                                                                            Nº
                                                                                        </span>
                                                                                    </th>
                                                                                    <th>
                                                                                        <span class="text-small">
                                                                                            DATA
                                                                                        </span>
                                                                                    </th>
                                                                                    <th colspan="2">
                                                                                        <span class="text-small">
                                                                                            ENTRADA
                                                                                        </span>
                                                                                    </th>
                                                                                    <th colspan="2">
                                                                                        <span class="text-small">
                                                                                            SAÍDA
                                                                                        </span>
                                                                                    </th>
                                                                                </tr>
                                                                            </thead>
                                                                            <tbody>
                                                                                <?php
                                                                                    $bMovFin="SELECT dataprevista, datarealizada, valorprevisto, valorrealizado, tipoderegistro FROM financeiro WHERE mes='$mesaco' AND ano='$anotab' GROUP BY codigodaoperacao ORDER by dia ASC, id asc";
                                                                                    $rMovFin=mysqli_query($con, $bMovFin);
                                                                                            $nmf = 0;
                                                                                        while($dMovFin=mysqli_fetch_array($rMovFin)):
                                                                                            $nmf++;
                                                                                ?>
                                                                                <tr>
                                                                                    <td class="color-black"
                                                                                        width="5%" valign="medium">
                                                                                        <span class="text-small">
                                                                                            <?php echo $nmf;?>
                                                                                        </span>
                                                                                    </td>
                                                                                    <td class="color-black"
                                                                                        width="13%" valign="medium">
                                                                                        <span class="text-small">
                                                                                            <?php
                                                                                                if(!empty($dMovFin['datarealizada'])):
                                                                                                    echo date(('d/m/Y'), strtotime($dMovFin['datarealizada']));
                                                                                                else:
                                                                                                    echo date(('d/m/Y'), strtotime($dMovFin['dataprevista']));
                                                                                                endif;
                                                                                            ?>
                                                                                        </span>
                                                                                    </td>
                                                                                    <td class="color-black left"
                                                                                        width="3%" valign="medium">
                                                                                        <span class="text-small">
                                                                                            R$
                                                                                        </span>
                                                                                    </td>
                                                                                    <td class="color-black right"
                                                                                        width="12%" valign="medium">
                                                                                        <span class="text-small">
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
                                                                                        <span class="text-small">
                                                                                            R$
                                                                                        </span>
                                                                                    </td>
                                                                                    <td class="color-black right"
                                                                                        width="12%" valign="medium">
                                                                                        <span class="text-small">
                                                                                            <?php
                                                                                                if($dMovFin['tipoderegistro'] === 'DÉBITO'):
                                                                                                    if(!empty($dMovFin['valorrealizado'])):
                                                                                                        $valordebitado = -$dMovFin['valorrealizado'];
                                                                                                    else:
                                                                                                        $valordebitado = -$dMovFin['valorprevisto'];
                                                                                                    endif;
                                                                                                    // $valordebitadoPOR = $dMovFin['valorrealizado'];
                                                                                                                
                                                                                                    if($valordebitadoPOR > 1000):
                                                                                                        $valordebitadoPOR = number_format($valordebitadoPOR, 2, '.', ',');
                                                                                                    elseif($valordebitadoPOR < 1000):
                                                                                                        $valordebitadoPOR = number_format($valordebitadoPOR, 2, ',', ',');
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

                                                                        <!-- DETALHES DA CONTA -->
                                                                        <div class="column width-5">
                                                                            <table
                                                                                class="table small rounded striped">
                                                                                <thead>
                                                                                    <tr
                                                                                        class="bkg-charcoal color-white center">
                                                                                        <th colspan='2'>
                                                                                            <span
                                                                                                class="text-small">
                                                                                                DETALHES DO SALDO
                                                                                            </span>
                                                                                        </th>
                                                                                    </tr>
                                                                                </thead>
                                                                                <tbody>
                                                                                    <!-- DETALHES DO SALDO -->
                                                                                    <?php
                                                                                                    $bSCc="SELECT conta1, tipoderegistro, sum(valorrealizado) as totalsaldo FROM financeiro WHERE mes='$mesaco' AND ano='$anotab' AND tipoderegistro='CRÉDITO' GROUP BY conta1";
                                                                                                    $rSCc=mysqli_query($con, $bSCc);
                                                                                                        while($dSCc=mysqli_fetch_array($rSCc)):
                                                                                                            
                                                                                                        $bSCd="SELECT conta1, tipoderegistro, sum(valorrealizado) as totalsaldo FROM financeiro WHERE mes='$mesaco' AND ano='$anotab' AND tipoderegistro='DÉBITO' GROUP BY conta1";
                                                                                                            $rSCd=mysqli_query($con, $bSCd);
                                                                                                            while($dSCd=mysqli_fetch_array($rSCd)):

                                                                                                    if($dSCc['conta1'] === $dSCd['conta1']):
                                                                                                        $conta = $dSCc['conta1'];
                                                                                                        $saldo = $dSCc['totalsaldo'] - $dSCd['totalsaldo'];
                                                                                                    else:
                                                                                                        $conta = $dSCc['conta1'];
                                                                                                        $saldo = $dSCc['totalsaldo'];
                                                                                                    endif;

                                                                                                    if($saldo > 1000):
                                                                                                        $saldo = number_format($saldo, 2, '.', ',');
                                                                                                    elseif($saldo < 1000):
                                                                                                        $saldo = number_format($saldo, 2, ',', ',');
                                                                                                    endif;

                                                                                                    //Buscar nome da conta
                                                                                                    $bNC="SELECT nomedaconta FROM contasfinanceiro WHERE conta='$conta'";
                                                                                                    $rNC=mysqli_query($con, $bNC);
                                                                                                        $dNC=mysqli_fetch_array($rNC);
                                                                                                ?>
                                                                                    <tr class="right">
                                                                                        <td>
                                                                                            <span
                                                                                                class="text-small">
                                                                                                <?php echo $dNC['nomedaconta']; ?>
                                                                                            </span>
                                                                                        </td>
                                                                                        <td>
                                                                                            <span
                                                                                                class="text-small">
                                                                                                R$
                                                                                                <?php echo $saldo; ?>
                                                                                            </span>
                                                                                        </td>
                                                                                    </tr>
                                                                                    <?php
                                                                                                    endwhile;
                                                                                                    endwhile;
                                                                                                ?>
                                                                                    <!-- DETALHES DO SALDO -->
                                                                                </tbody>
                                                                            </table>
                                                                        </div>
                                                                        <!-- DETALHES DA CONTA -->

                                                                        <!-- SALDOS -->
                                                                        <div class="column width-7">
                                                                            <table
                                                                                class="table small rounded striped">
                                                                                <thead>
                                                                                    <tr
                                                                                        class="bkg-charcoal color-white center">
                                                                                        <th>
                                                                                            <span
                                                                                                class="text-small">
                                                                                                SALDO ANTERIOR
                                                                                            </span>
                                                                                        </th>
                                                                                        <th>
                                                                                            <span
                                                                                                class="text-small">
                                                                                                SALDO ATUAL
                                                                                            </span>
                                                                                        </th>
                                                                                        <th>
                                                                                            <span
                                                                                                class="text-small">
                                                                                                PARA CONFERÊNCIA
                                                                                            </span>
                                                                                        </th>
                                                                                    </tr>
                                                                                </thead>
                                                                                <tbody>
                                                                                    <tr>
                                                                                        <!-- DETALHES DO SALDO -->
                                                                                        <?php
                                                                                                    $mesanterior = $mesaco;
                                                                                                    $mesanterior = $mesanterior--;
                                                                                                    if($mesaco > 0 AND $mesaco < 02):
                                                                                                        $anoanterior = $anotab;
                                                                                                        $anoanterior = $anoanterior--;
                                                                                                    else:
                                                                                                        $anoanterior = $anotab;
                                                                                                    endif;

                                                                                                    //Buscar saldo anterior CRÉDITO
                                                                                                    if($mesaco > 0 AND $mesaco < 02):
                                                                                                        $bSAntC="SELECT sum(valorrealizado) as totalsaldo FROM financeiro WHERE mes='$mesanterior' AND ano='$anoanterior' AND tipoderegistro='CRÉDITO' GROUP BY conta1";
                                                                                                    else:
                                                                                                        $bSAntC="SELECT sum(valorrealizado) as totalsaldo FROM financeiro WHERE mes='$mesanterior' AND ano='$ano' AND tipoderegistro='CRÉDITO' GROUP BY conta1";
                                                                                                    endif;
                                                                                                    $rSAntC=mysqli_query($con, $bSAntC);
                                                                                                        $dSAntC=mysqli_fetch_array($rSAntC);

                                                                                                    //Buscar saldo anterior CRÉDITO
                                                                                                    if($mesaco > 0 AND $mesaco < 02):
                                                                                                        $bSAntD="SELECT sum(valorrealizado) as totalsaldo FROM financeiro WHERE mes='$mesanterior' AND ano='$anoanterior' AND tipoderegistro='DÉBITO' GROUP BY conta1";
                                                                                                    else:
                                                                                                        $bSAntD="SELECT sum(valorrealizado) as totalsaldo FROM financeiro WHERE mes='$mesanterior' AND ano='$ano' AND tipoderegistro='DÉBITO' GROUP BY conta1";
                                                                                                    endif;
                                                                                                    $rSAntD=mysqli_query($con, $bSAntD);
                                                                                                        $dSAntD=mysqli_fetch_array($rSAntD);

                                                                                                    $SaldoAnterior = $dSAntC['totalsaldo'] - $dSAntD['totalsaldo'];

                                                                                                    //Buscar saldo atual CRÉDITO
                                                                                                    $bSAc="SELECT sum(valorrealizado) as totalsaldo FROM financeiro WHERE mes='$mesaco' AND ano='$anotab' AND tipoderegistro='CRÉDITO'";
                                                                                                    $rSAc=mysqli_query($con, $bSAc);
                                                                                                        $dSAc=mysqli_fetch_array($rSAc);

                                                                                                    //Buscar saldo atual CRÉDITO
                                                                                                    $bSAd="SELECT sum(valorrealizado) as totalsaldo FROM financeiro WHERE mes='$mesaco' AND ano='$anotab' AND tipoderegistro='DÉBITO'";
                                                                                                    $rSAd=mysqli_query($con, $bSAd);
                                                                                                        $dSAd=mysqli_fetch_array($rSAd);

                                                                                                    $SaldoAtual = $dSAc['totalsaldo'] - $dSAd['totalsaldo'];
                                                                                                    
                                                                                                    if($SaldoAnterior > 1000):
                                                                                                        $SaldoAnterior = number_format($SaldoAnterior, 2, '.', ',');
                                                                                                    elseif($SaldoAnterior < 1000):
                                                                                                        $SaldoAnterior = number_format($SaldoAnterior, 2, ',', ',');
                                                                                                    endif;
                                                                                                    if($SaldoAtual > 1000):
                                                                                                        $SaldoAtual = number_format($SaldoAtual, 2, '.', ',');
                                                                                                    elseif($SaldoAtual < 1000):
                                                                                                        $SaldoAtual = number_format($SaldoAtual, 2, ',', ',');
                                                                                                    endif;
                                                                                                ?>

                                                                                        <td class="right">
                                                                                            <span
                                                                                                class="text-small">
                                                                                                R$
                                                                                                <?php echo $SaldoAnterior;?>
                                                                                            </span>
                                                                                        </td>
                                                                                        <td class="right">
                                                                                            <span
                                                                                                class="text-small">
                                                                                                R$
                                                                                                <?php echo $SaldoAtual;?>
                                                                                            </span>
                                                                                        </td>
                                                                                        <td>
                                                                                            <span
                                                                                                class="text-small">
                                                                                                R$
                                                                                            </span>
                                                                                        </td>
                                                                                        <!-- DETALHES DO SALDO -->
                                                                                    </tr>
                                                                                </tbody>
                                                                            </table>

                                                                            <div class="column width-4">
                                                                                <span class="text-small">VISTO DO
                                                                                    TESOUREIRO (A)</span>
                                                                                <div
                                                                                    class="box large border-grey-ultralight rounded">
                                                                                </div>
                                                                            </div>
                                                                            <div class="column width-4">
                                                                                <span class="text-small">VISTO DA
                                                                                    TESTEMUNHA</span>
                                                                                <div
                                                                                    class="box large border-grey-ultralight rounded">
                                                                                </div>
                                                                            </div>
                                                                            <div class="column width-4">
                                                                                <span class="text-small">VISTO DO
                                                                                    PRESIDENTE</span>
                                                                                <div
                                                                                    class="box large border-grey-ultralight rounded">
                                                                                </div>
                                                                            </div>
                                                                        </div>
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
                                        
                            <?php
                                include "./bloginterpages.php";
                            ?>
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