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

?>
<html lang="pt-BR">
<head>
	<? include "./_head.php"; ?>
</head>
<body class="shop blog home-page">

    <?php
        // if(isset($_SESSION["membro"])):
           // include "./_menu.php";
        // endif;
	?>
            <div class="screen">
                <div class="content clearfix">
                    <div class="section-block featured-3 pt-150 replicable-content">
                        <div class="row">
                            <!-- <div class="column width-4"></div> -->
                            <div class="column width-4 offset-4">
                                <div class="thumbnail background-image-container background-cover rounded">
                                    <img src="images/fundo_carteirinha_2021.png" class="" alt="">
                                    <div class="caption-over-outer">
                                        <div class="caption-over-inner center">
                                            <div class="row">
                                                <!--box rounded small  border-blue-light  -->
                                                <div id="divpublicacao" class="mb-0 horizon" data-threshold="0.3">
                                                    <div class="pb-0 mb-0 thumbnail thick" data-hover-easing="easeInOut" data-hover-speed="500" data-hover-bkg-color="#ffffff" data-hover-bkg-opacity="0.08">
                                                        <img src="<?php echo $fotodeperfil;?>" style="width:100px; height:120px; valign:medium;" width="100" height="90" alt=""/>
                                                    </div>
                                                    <br>
                                                    <div class="left color-charcoal pt-10 team-content-info">
                                                        <div class="column full-width pt-0 pb-0 bkg-white">
                                                            <h4 class="mb-0 pt-10 text-medium text-uppercase">
                                                                <?php echo $nomedousuario; ?>
                                                            </h4>
                                                        </div>
                                                        <div class="column full-width pt-0 pb-10 bkg-white">
                                                            <h6 class="occupation pb-0 mb-0">
                                                                <?php
                                                                    if(!empty($funcaoadministrativa)):
                                                                        $funcAdm = " (".$funcaoadministrativa.")"; else: $funcAdm = ''; endif;
                                                                    echo $funcAdm.' | '.str_replace(',', ', ', $ministerio);
                                                                ?>
                                                            </h6>
                                                        </div>
                                                        
                                                        
                                                        <div class="column full-width pt-0 pb-0 bkg-grey-ultralight">
                                                            <span class="text-xsmall"><strong>MATRÍCULA:</strong></span><span> <?php echo $matriculadousuario;?></span>
                                                        </div>
                                                        <div class="column full-width pt-0 pb-0 bkg-white">
                                                            <span class="text-xsmall"><strong>STATUS DO MEMBRO:</strong></span> <span><?php echo $statusdomembro;?></span>
                                                        </div>

                                                        <div class="column full-width pt-0 pb-0 bkg-grey-ultralight">
                                                            <span class="text-xsmall"><strong>CPF:</strong></span> </span><?php echo $cpfdomembro;?></span>
                                                            <span class="text-xsmall"><strong>RG:</strong></span> </span><?php echo $rgdomembro;?></span>
                                                        </div>

                                                        <div class="column full-width pt-0 pb-0 bkg-white">
                                                            <span class="text-xsmall"><strong>MEMBRO:</strong></span> </span><?php echo $membrodesde;?></span>
                                                            <span class="text-xsmall"><strong>VALIDADE:</strong></span> </span><?php echo $validaate;?></span>
                                                        </div>

                                                        <div class="column full-width pt-0 pb-0 bkg-grey-ultralight">
                                                            <span class="text-xsmall"><strong>Formado em:</strong></span> </span><?php echo $formadoem;?></span>
                                                        </div>

                                                        <br>
                                                    </div>
                                                    <table>
                                                        <tbody>
                                                            <tr class="left">
                                                                <td>
                                                                    <img src="<?php echo $qrcode;?>" class="left" style="width:120px; height:120px;"/>
                                                                </td>
                                                                <td>
                                                                    <p class="text-xsmall pt-0 pb-0 mb-0 color-charcoal text-uppercase bkg-white">
                                                                        Autentificação digital
                                                                    </p>
                                                                    <p class="text-xsmall pt-0 pb-0 mb-0 color-charcoal text-uppercase bkg-white">
                                                                        através do Qr Code.
                                                                    </p>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- <div class="grid-item center">
                                    <div id="divpublicacao" class="box rounded small mb-0 border-blue-light horizon" data-threshold="0.3">
                                        <div class="pb-0 mb-0 thumbnail thick" data-hover-easing="easeInOut" data-hover-speed="500" data-hover-bkg-color="#000000" data-hover-bkg-opacity="0.01">
                                            <img src="<?php echo $fotodeperfil;?>" style="width:110px; height:120px; valign:medium;" width="100" height="90" alt=""/>
                                        </div>
                                        <br>
                                        <div class="left color-charcoal pt-0 team-content-info">
                                            <h4 class="mb-0 pt-10">
                                                <?php echo $nomedousuario; ?>
                                            </h4>
                                            <h6 class="occupation pb-10 mb-0">
                                                <?php
                                                    if(!empty($funcaoadministrativa)): $funcAdm = " (".$funcaoadministrativa.")"; else: $funcAdm = ''; endif;
                                                    echo str_replace(',', ', ', $ministerio).$funcAdm;
                                                ?>
                                            </h6>
                                            <div class="column full-width pt-0 pb-0 bkg-grey-ultralight">
                                                <span class="text-xsmall"><strong>MATRÍCULA:</strong></span><span> <?php echo $matriculadousuario;?></span>
                                            </div>
                                            <div class="column full-width pt-0 pb-0 bkg-white">
                                                <span><strong>STATUS DO MEMBRO:</strong> <?php echo $statusdomembro;?></span>
                                            </div>
                                            <br/> <span><strong>CPF:</strong> <?php echo $cpfdomembro;?></span>
                                            <span><strong>RG:</strong> <?php echo $rgdomembro;?></span>
                                            <br/> <span><strong>MEMBRO DESDE:</strong> <?php echo $membrodesde;?></span>
                                            <br/> <span><strong>VÁLIDA ATÉ:</strong> <?php echo $validaate;?></span>
                                            <br><span><strong>Formado em:</strong> <?php echo $formadoem;?></span>
                                            <br>
                                        </div>
                                        <table>
                                            <tbody>
                                                <tr class="left">
                                                    <td>
                                                        <img src="<?php echo $qrcode;?>" class="left" style="width:120px; height:120px;"/>
                                                    </td>
                                                    <td>
                                                        <span class="text-small color-charcoal text-uppercase">
                                                            Autentificação
                                                            <br/>digital através
                                                            <br/>do Qr Code.
                                                        </span>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div> -->
                            </div>        
                            <!-- <div class="column width-4"></div>                 -->
                        </div>
                        <!-- Form Advanced End -->
                    </div>               
                </div>
                <!-- Content End -->
                <div class="printable"></div>
            </div>
		</div>
	</div>
    
	<? include "./_script.php"; ?>
    
    <script src="js/printThis.js"></script>
	<script type="text/javascript">
			$(document).ready(function () {
			$("#btnPrint").click(function () {
				//get the modal box content and load it into the printable div
				$(".printable").html($("#divpublicacao").html());
				$(".printable").printThis();
			});
		});
	</script>
</body>
</html>