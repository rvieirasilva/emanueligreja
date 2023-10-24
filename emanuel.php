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
    // if(isset($_SESSION["lideranca"])):
    //     include "protectcolaborador.php";	
    // elseif(isset($_SESSION["membro"])):
    //     include "protectusuario.php";
    // else:
    //     header("location:index");
	// endif;
	
	
	//var_dump($_SESSION);
    //Dados para registrar o log do cliente.
        $mensagem = ("\n$nomedocolaborador ($matricula) Acessou o portal. Isto através do IP: $ip, na data $dia às $hora.\n"); //Escreva a mensagem que será gravada no log.
        include "registrolog.php";
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
                                                        <h1 class="mb-0">Bem vindo <strong><?php echo $nomeusuario;?></strong>.</h1>
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
                        <div class="row full-width">
                            <?php include "./_notificacaomensagem.php"; ?>                                   
                            <div class="full-width"> 
                                <!-- <div class="box rounded rounded shadow border-blue bkg-white">  -->
                                <div class="column width-12 pb-10">
                                    <h4>Sua posição no <strong>Ranking ministerial</strong></h4>
                                </div>
                                <div class="column width-3">
                                    <div class="box small rounded bkg-white border-blue shadow">
                                        <div class="column width-12">
                                            <label class="text-medium color-charcoal"><strong>Evangelizador</strong> <span class="text-small">(Pessoas p/ Cristo)</span></label>
                                            <span class="label rounded bkg-blue color-white">
                                            <span class="icon-globe color-white"></span>  
                                                <?
                                                    $bMovFin="SELECT id, ministro, sum(trouxevisitantes) as evangelizador FROM relatoriodeculto WHERE NOT (trouxevisitantes='') AND ano='$ano' AND campusrelator='$membrodocampus' GROUP BY ministro ORDER by evangelizador DESC";
                                                    $rEvn=mysqli_query($con, $bMovFin);
                                                        $Position=0;
                                                        while($dRE=mysqli_fetch_array($rEvn)):
                                                            $Position++;
                                                            if($nomedousuario === $dRE['ministro']):
                                                                echo $Position.'º Colocado, (Trouxe: '.$dRE['evangelizador'].')';
                                                            endif;
                                                        endwhile;
                                                ?>
                                            </span>
                                        </div>
                                        
                                        <div class="column width-12 pt-10">
                                            <label class="text-medium color-charcoal"><strong>Constância</strong> <span class="text-small">(Presença nos Cultos)</span></label>
                                            <span class="label rounded bkg-red color-white">
                                            <span class="icon-tree color-white"></span>  
                                                <?
                                                    $bRC="SELECT id, ministro, sum(presencanaoracao) as constantes FROM relatoriodeculto WHERE NOT (presencanaoracao='') AND ano='$ano' AND campusrelator='$membrodocampus' GROUP BY ministro ORDER by constantes DESC";
                                                    $rRc=mysqli_query($con, $bRC);
                                                        $PosCons=0;
                                                        while($dRcon=mysqli_fetch_array($rRc)):
                                                            $PosCons++;
                                                            if($nomedousuario === $dRcon['ministro']):
                                                                echo $PosCons.'º Colocado, (Est.: '.$dRcon['constantes'].')';
                                                            endif;
                                                        endwhile;
                                                ?>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="column width-3">
                                    <div class="box small rounded bkg-white border-blue shadow">
                                        <div class="column width-12">
                                            <label class="text-medium color-charcoal"><strong>Compromentimento</strong> <span class="text-small">(Suas Funções)</span></label>
                                            <span class="label rounded bkg-navy color-white">
                                            <span class="icon-trophy color-white"></span>  
                                                <?
                                                    $bRcomp="SELECT id, ministro, sum(funcoesministeriais) as comprometimento FROM relatoriodeculto WHERE NOT (funcoesministeriais='') AND ano='$ano' AND campusrelator='$membrodocampus' GROUP BY ministro ORDER by comprometimento DESC";
                                                    $rRcomp=mysqli_query($con, $bRcomp);
                                                        $PosComp=0;
                                                        while($dRComp=mysqli_fetch_array($rRcomp)):
                                                            $PosComp++;
                                                            if($nomedousuario === $dRComp['ministro']):
                                                                echo $PosComp.'º Colocado, (Exec: '.$dRComp['comprometimento'].')';
                                                            endif;
                                                        endwhile;
                                                ?>
                                            </span>
                                        </div>
                                        
                                        <div class="column width-12 pt-10">
                                            <label class="text-medium color-charcoal">Frequência na <strong>Célula</strong></label>
                                            <span class="label rounded bkg-turquoise color-white">
                                            <span class="icon-trophy color-white"></span>  
                                                <?
                                                    $bRcomp="SELECT membro, sum(presenca) as particiCel FROM relatorio WHERE NOT (membro='') AND ano='$anoatual' AND tipoderelatorio='RELATÓRIO DE CÉLULA' AND matriculadacelula='$matriculadacelula' AND matriculadomembro='$matricula' GROUP BY membro ORDER by particiCel DESC";
                                                    $rRcomp=mysqli_query($con, $bRcomp);
                                                        $PosComp=0;
                                                        while($dRComp=mysqli_fetch_array($rRcomp)):
                                                            $PosComp++;
                                                            if($nomedousuario === $dRComp['membro']):
                                                                echo $PosComp.'º Colocado, (Presenças: '.$dRComp['particiCel'].')';
                                                            endif;
                                                        endwhile;
                                                ?>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="column width-3">
                                    <div class="box small rounded bkg-white border-blue shadow">
                                        <div class="column width-12 pt-10">
                                            <label class="text-medium color-charcoal"><strong>Discipulador</strong></label>
                                            <span class="label rounded bkg-charcoal color-white">
                                            <span class="icon-trophy color-white"></span>  
                                                <?
                                                    $bRcomp="SELECT discipulador, sum(encontros) as discipulados FROM relatoriodediscipulado WHERE NOT (discipulador='') AND ano='$ano' AND campus='$membrodocampus' GROUP BY discipulador ORDER by discipulados DESC";
                                                    $rRcomp=mysqli_query($con, $bRcomp);
                                                        $PosComp=0;
                                                        while($dRComp=mysqli_fetch_array($rRcomp)):
                                                            $PosComp++;
                                                            if($nomedousuario === $dRComp['discipulador']):
                                                                echo $PosComp.'º Colocado, (Presenças: '.$dRComp['discipulados'].')';
                                                            endif;
                                                        endwhile;
                                                ?>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="column width-3">
                                    <div class="box small rounded bkg-white border-blue shadow">
                                        <div class="column width-12 pt-10">
                                            <label class="text-medium color-red">Projeto <strong>Ec10</strong></label>
                                            <span class="label rounded bkg-red color-white">
                                            <span class="icon-chat color-white"></span>  
                                                <?
                                                    $bEc="SELECT count(id) as sugests FROM ec10 WHERE NOT (descricao='') AND aberto =''";
                                                    $rEc=mysqli_query($con, $bEc);
                                                        $dEc=mysqli_fetch_array($rEc);

                                                    echo "<a href='./ec10listar?$linkSeguro'>".$dEc['sugests'].' sugestões, clique para abrir'."</a>";
                                                ?>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="column width-12"> 
                                <div class="box rounded rounded shadow border-blue bkg-white"> 
                                    <div class="column width-3">
                                        <div class="box rounded small pt-12 pb-10 bkg-white border-grey color-charcoal shadow rounded border-grey-light">
                                            <div class="column width-12 pt-20 pb-20">
                                                <div class="feature-column center"> 
                                                    <a href="./agendas<? echo '?'.$linkSeguro; ?>" class="link ">Eventos em aberto</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div> 
                                    <div class="column width-3">
                                        <div class="box rounded small pt-12 pb-10 bkg-white border-grey color-charcoal shadow rounded border-grey-light">
                                            <div class="column width-12 pt-20 pb-20">
                                                <div class="feature-column center"> 
                                                    <a data-content="inline" data-aux-classes="tml-form-modal tml-exit-light height-auto with-header rounded" data-toolbar="" data-modal-mode data-modal-width="500" data-modal-animation="scaleIn" data-lightbox-animation="fade" href="#modalcelula" class="lightbox-link ">Informações da célula</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="modalcelula" class="modal-dialog-inner section-block cart-overview pt-0 pb-30 background-none hide">
                                        <?
                                            $bRl="SELECT horario, diadeencontro, estado, municipio, cep, anfitriao, anfitria, lider FROM celulas WHERE celula='$celuladomembro' AND matricula='$matriculadacelula'";
                                            $rRl=mysqli_query($con, $bRl);
                                                $dRl=mysqli_fetch_array($rRl);
                                        ?>  
                                        <div class="modal-header bkg-blue color-white">
                                            <h4 class="modal-header-title">Equipe e Local da célula.</h4>
                                        </div>
                                        
                                        <p>
                                            <? if($sexo === 'Masculino'): ?>
                                            <label class="text-small pt-0 pb-0"><strong>Anfitrião</strong> <? echo base64_decode(base64_decode($dRl['anfitriao']));?></label> 
                                            <? endif; if($sexo === 'Feminino'): ?>
                                            <label class="text-small pt-0 pb-0"><strong>Anfitriã</strong> <? echo base64_decode(base64_decode($dRl['anfitria']));?></label>
                                            <? endif; ?>
                                            <label class="text-small pt-0 pb-0"><strong>Anfitriã</strong> <? echo base64_decode(base64_decode($dRl['lider']));?></label>
                                            <label class="text-small pt-0 pb-0"><strong>Dia e Horário</strong> <? echo $dRl['diadeencontro'].', '.$dRl['horario'];?></label>
                                            <label class="text-small pt-0 pb-0"><strong>Local:</strong> <? echo $dRl['cep'].' - '.$dRl['municipio'].'/'.$dRl['estado'];?></label>
                                        </p>
                                    </div>
                                    <?
                                        $bRl="SELECT id FROM celulas WHERE matriculasecretario='$matricula'";
                                        $rRl=mysqli_query($con, $bRl);
                                            $nRl=mysqli_num_rows($rRl);
                                        if($nRl > 0):
                                    ?>                              
                                    <div class="column width-3">
                                        <a href="celularelatorio<?php echo '?'.$linkSeguro;?>">
                                            <div class="box rounded small pt-12 pb-10 bkg-white border-grey color-blue shadow rounded border-grey-light">
                                                <div class="column width-12 pt-20 pb-20">
                                                    <div class="feature-column center"> 
                                                        <span class="text-medium pt-0 pb-0">Enviar relatório da Célula</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                    <? endif; ?>
                                    <?
                                        $bRl="SELECT id FROM celulas WHERE matriculadolider='$matricula'";
                                        $rRl=mysqli_query($con, $bRl);
                                            $nRl=mysqli_num_rows($rRl);
                                        if($nRl > 0):
                                    ?>                              
                                    <div class="column width-3">
                                        <a href="celulaconfig<?php echo '?'.$linkSeguro;?>">
                                            <div class="box rounded small pt-12 pb-10 bkg-white border-grey color-blue shadow rounded border-grey-light">
                                                <div class="column width-12 pt-20 pb-20">
                                                    <div class="feature-column center"> 
                                                        <span class="text-medium pt-0 pb-0">Configuração da célula</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                    <div class="column width-3">
                                        <a href="celulaanalise<?php echo '?'.$linkSeguro;?>">
                                            <div class="box rounded small pt-12 pb-10 bkg-white border-grey color-blue shadow rounded border-grey-light">
                                                <div class="column width-12 pt-20 pb-20">
                                                    <div class="feature-column center"> 
                                                        <span class="text-medium pt-0 pb-0">Ver relatório da Célula</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                    <? endif; ?>
                                </div>
                                <div class="box rounded rounded shadow border-blue bkg-white"> 
                                    <?php
                                        if(isset($_SESSION["lideranca"])):
                                    ?>    
                                        <div class="column width-3">
                                            <a href="comprabuscarvisaogeral<?php echo '?'.$linkSeguro;?>">
                                                <div class="box rounded small pt-12 pb-10 bkg-white color-green shadow rounded border-grey-light">
                                                    <div class="column width-12 pt-20 pb-20">
                                                        <div class="feature-column left"> 
                                                            <span class="text-small weight-bold pt-0 pb-0">Ver todos pedidos.</span>
                                                            <br>
                                                            <span class="pt-0 pb-0">Novos, Preparando e Entregando.</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                        <div class="column width-3">
                                            <a href="comprabuscar<?php echo '?'.$linkSeguro;?>">
                                                <div class="box rounded small pt-12 pb-10 bkg-white color-green shadow rounded border-grey-light">
                                                    <div class="column full-width pt-20 pb-20">
                                                        <div class="feature-column left"> 
                                                            <span class="text-small weight-bold pt-0 pb-0">Novos pedidos.</span>
                                                            <br>
                                                            <span class="pt-0 pb-0">Pedidos realizados que estão aguardando confirmação de preparo.</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                        <div class="column width-3">
                                            <a href="comprabuscarentrega<?php echo '?'.$linkSeguro;?>">
                                                <div class="box rounded small pt-12 pb-10 bkg-white color-green shadow rounded border-grey-light">
                                                    <div class="column full-width pt-20 pb-20">
                                                        <div class="feature-column left"> 
                                                            <span class="text-small weight-bold pt-0 pb-0">Prontos para Entrega</span>
                                                            <br>
                                                            <span class="pt-0 pb-0">Finalizado preparo ou Aguardando retirada.</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                        <div class="column width-3">
                                            <a href="comprabuscarentregue<?php echo '?'.$linkSeguro;?>">
                                                <div class="box rounded small pt-12 pb-10 bkg-white color-green shadow rounded border-grey-light">
                                                    <div class="column full-width pt-20 pb-20">
                                                        <div class="feature-column left"> 
                                                            <span class="text-small weight-bold pt-0 pb-0">Finalizando Entrega.</span>
                                                            <br>
                                                            <span class="pt-0 pb-0">A caminho do destino ou pronto para ser retirado.</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                        <div class="column width-3 color-white">
                                            <a href="comprabuscarfinalizados<?php echo '?'.$linkSeguro;?>">
                                                <div class="box rounded small pt-12 pb-10 bkg-red shadow rounded border-grey-light">
                                                    <div class="column full-width pt-10 pb-10">
                                                        <div class="feature-column left"> 
                                                            <span class="text-small weight-bold pt-0 pb-0">Pedidos finalizados.</span>
                                                            <br>
                                                            <span class="pt-0 pb-0">Pedidos com status de entrega ou retirada concluída.</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                        <div class="column width-3">
                                            <a href="clientecadastrar<?php echo '?'.$linkSeguro;?>">
                                                <div class="box rounded small pt-12 pb-10 bkg-white shadow rounded border-grey-light">
                                                    <div class="column full-width pt-20 pb-20">
                                                        <div class="feature-column left"> 
                                                            <span class="text-small weight-bold pt-0 pb-0">Cadastrar cliente.</span>
                                                            <br>
                                                            <span class="pt-0 pb-0">Registrar novo cliente no sistema.</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                    <?php
                                        elseif(isset($_SESSION["membro"])):
                                    ?>
                                        <div class="column width-3">
                                            <?php
                                                if($mesnumero == '03' OR $mesnumero == '06' OR $mesnumero == '09' OR $mesnumero == '12'):
                                                    if($dia >= 25 AND $dia <= 30):
                                                        $cortransp="green";
                                                        $icontransp='icon-lock-open';
                                                        $linktransp= "transparencia?token=$token&m=$matricula";
                                                    else:
                                                        $cortransp="orange";
                                                        $icontransp='icon-lock';
                                                        $linktransp= "#";
                                                    endif;
                                                else:
                                                    $cortransp="red";
                                                    $icontransp='icon-lock';
                                                    $linktransp= "#";
                                                endif;
                                            ?>
                                            <a href="<?php echo $linktransp;?>">
                                                <div class="box rounded small pt-12 pb-10 bkg-white shadow rounded border-white">
                                                    <div class="column width-12 pt-20 pb-20">
                                                        <div class="feature-column left color-<?php echo $cortransp; ?>"> 
                                                            <span class="text-small weight-bold pt-0 pb-0">
                                                                <span class="<?php echo $icontransp; ?>"></span> Transparência financeira.
                                                            </span>
                                                            <br>
                                                            <?php
                                                                if($mesnumero <= '03'):
                                                            ?>
                                                                <span class="pt-0 pb-0">Disponível após 25/03.</span>
                                                            <?php
                                                                elseif($mesnumero <= '06'):
                                                            ?>
                                                                <span class="pt-0 pb-0">Disponível após 25/06.</span>
                                                            <?php
                                                                elseif($mesnumero <= '09'):
                                                            ?>
                                                                <span class="pt-0 pb-0">Disponível após 25/09.</span>
                                                            <?php
                                                                elseif($mesnumero <= '12'):
                                                            ?>
                                                                <span class="pt-0 pb-0">Disponível após 25/09.</span>
                                                            <?php
                                                                endif;
                                                            ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                        <div class="column width-3">
                                            <a href="carteirinhademembro?<?php echo $linkSeguro;?>" target="_blank">
                                                <div class="box rounded small pt-12 pb-10 bkg-white color-green shadow rounded border-white">
                                                    <div class="column width-12 pt-20 pb-20">
                                                        <div class="feature-column left"> 
                                                            <span class="text-small weight-bold pt-0 pb-0">Carteirinha digital.</span>
                                                            <br>
                                                            <span class="pt-0 pb-0">Clique para abrir sua carteirinha digital</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                        <div class="column width-3">
                                            <a href="doacoes" target="_blank">
                                                <div class="box rounded small pt-12 pb-10 bkg-white color-green shadow rounded border-white">
                                                    <div class="column width-12 pt-20 pb-20">
                                                        <div class="feature-column left"> 
                                                            <span class="text-small weight-bold pt-0 pb-0">Ofertar/Dizimar.</span>
                                                            <br>
                                                            <span class="pt-0 pb-0">Clique para realizar uma oferta ou entregar seu dizímo.</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                        <div class="column width-3">
                                            <a href="configurar<?echo $linkSeguro;?>" target="_blank">
                                                <div class="box rounded small pt-12 pb-10 bkg-white color-green shadow rounded border-white">
                                                    <div class="column width-12 pt-20 pb-20">
                                                        <div class="feature-column left"> 
                                                            <span class="text-small weight-bold pt-0 pb-0">Configurar sua conta.</span>
                                                            <br>
                                                            <span class="pt-0 pb-0">Clique para alterar seus dados</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                        <!-- <div class="column width-3">
                                            <a href="https://m.me/faelvieirasilva<?php echo '?token='.$token.'&m='.$matricula;?>">
                                                <div class="box rounded small pt-12 pb-10 bkg-white color-green shadow rounded border-white">
                                                    <div class="column width-12 pt-20 pb-20">
                                                        <div class="feature-column left"> 
                                                            <span class="text-small weight-bold pt-0 pb-0">Falar com o Pastor.</span>
                                                            <br>
                                                            <span class="pt-0 pb-0">Agendar atendimento pastoral.</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </a>
                                        </div> -->
                                    <?php
                                        endif;
                                    ?>
                                </div>
                                        
                                <?php include "./bloginterpages.php"; ?>
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