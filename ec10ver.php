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

	if(isset($_SESSION["lideranca"])):
        include "protectcolaborador.php";	
    elseif(isset($_SESSION["membro"])):
        include "protectusuario.php";
    else:
        session_destroy();
        header("location:index");
	endif;

    
	$iddopost=$_GET['id'];
	$refdopost=$_GET['ref'];


	$id_altmini = $_GET['altmini'];	//Get gerado ao alterar miniatura múltiplas	

	
	if(isset($_POST['btn-addcategoria'])):
		$categoria = mysqli_escape_string($con, $_POST['categoria']);
		
		$duplicado="SELECT id FROM blogcategorias WHERE categoria='$categoria'";
		$rd=mysqli_query($con, $duplicado);
		$nd=mysqli_num_rows($rd);
		if($nd > 0):
			$_SESSION['mensagem']="Já existe está categoria registrada.";
		else:
			$inserir="INSERT INTO blogcategorias (categoria) VALUES ('$categoria')";
			mysqli_query($con, $inserir);
			$_SESSION['mensagem']="Categoria adicionada com sucesso, <strong>atualize a página</strong>.";
			//header("Location:_categoriaparaposts.php");
		endif;
	endif;
	
	$buscarpost="SELECT * FROM ec10 WHERE id='$iddopost'";
      $rbp=mysqli_query($con, $buscarpost);
		$dbp=mysqli_fetch_array($rbp);
          $ndp=mysqli_num_rows($rbp);

    if(isset($_GET['v'])):
        $aberto=$_GET['v'];
        $up="UPDATE ec10 SET aberto='Sim' WHERE id='$aberto'";
        if(mysqli_query($con, $up)):
            $_SESSION['cordamensagem']="green";
            $_SESSION['mensagem']="Sugestão visualizada no site.";
            header("location:./ec10listar?$linkSeguro");
        else:
            $_SESSION['cordamensagem']="";
            $_SESSION['mensagem']="Erro ao marcar sugestão como visualizada, tente novamente mais tarde.";
        endif;
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
                                                        <h1 class="mb-0">Ver mensagem do projeto <strong>Ec10</strong></h1>
                                                        <p class="text-large color-charcoal mb-0 mb-mobile-20"></p>
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
                                <div class="column width-12">
                                    <div class="column width-12 color-black text-medium">
                                        <p><strong>Data de envio: </strong> <?echo $dbp['dia'].'/'.$dbp['mes'].'/'.$dbp['ano'];?>
                                            <br><strong>Nome que indicou: </strong><?echo $dbp['nome'];?>
                                            <br><strong>E-mail: </strong><?echo $dbp['email'];?>
                                            <br><strong>WhatsApp: </strong><?echo $dbp['whatsapp'];?>
                                        </p>
                                        
                                        <p><strong>É membro? </strong><?echo $dbp['membro'];?>
                                        <br><strong>Percebi isto no encontro: </strong><?echo $dbp['motivo'];?>
                                        <br><strong>Em uma escala de 0 a 10 o quanto você recomendaria a Emanuel para um amigo? </strong><?echo $dbp['nps'];?>
                                        <br><strong>Como você classificaria a músicas? </strong><?echo $dbp['musica'];?>
                                        <br><strong>Como você classificaria a palavra? </strong><?echo $dbp['palavra'];?>
                                        <br><strong>Como você classificaria nosso horário? </strong><?echo $dbp['horario'];?>
                                        <br><strong>Como você classificaria nossa localização?</strong> <? echo $dbp['localizacao']; ?>
                                        <br><strong>Como você classificaria nossa Recepção? </strong><?echo $dbp['recepcao'];?>
                                        <br><strong>Em uma escala de 0 a 10 como você classifica nossa organização? </strong><?echo $dbp['organizacao'];?>
                                        <br><strong>Em uma escala de 0 a 10 como você classifica a limpeza da Igreja? </strong><?echo $dbp['limpeza'];?>
                                        <br><strong>O que precisa ser melhorado na Emanuel?</strong>
                                            <br><? echo $dbp['descricao']; ?>
                                        <p><strong>Podemos contar com você pra implantar essa sugestão? </strong> <?echo $dbp['participar'];?></p>
                                    </div>
                                    <div class="contact-form-container pt-10">
                                        <div class="row">
                                            <div class="column width-12 pt-10">
                                                <a href="./ec10ver?<?echo $linkSeguro.'v='.$iddopost;?>" class="form-submit button pill small border-theme bkg-hover-theme color-theme color-hover-white">Marcar como visualizado</a>
                                                <a href="./ec10listar?<?echo $linkSeguro;?>" class="form-submit button pill small bkg-theme bkg-hover-theme color-white color-hover-white">Retornar</a>
                                            </div>
                                        </div>
                                        <div class="form-response"></div>
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
    

	<!-- Alterar capa Modal End -->
	<div id="editarcapadopost" class="section-block pt-0 pb-30 background-none hide">
		
		<!-- Intro Title Section 2 -->
		<div class="thumbnail xsmall">
			<img src="<?php echo $dbp['capa'];?>" width="825" height="400" alt="">
		</div>
		<!-- Intro Title Section 2 End -->

		<!-- Signup -->
		<div class="section-block pt-60 pb-0">
			<div class="row">
				<div class="column width-12 left">
					<div class="signup-form-container">
						<div class="row">
							<div class="column width-10 offset-1">
								<p>
									<?php
									
									?>
								</p>
							</div>
						</div>
						<form class="form" action="" method="post" enctype="Multipart/form-data">
							<div class="row">
								<div class="column width-12">
									<input type="file" name="imagemcapa" class="button medium border-charcoal-light color-blue-light color-hover-blue">
								</div>
							</div>
							<div class="row">
								<div class="column width-5 left">
									<button type="submit" value="ALTERAR CAPA" name="btn-alterarcapa" class="button medium border-red color-blue-lght color-hover-blue">ALTERAR CAPA</button>
								</div>
							</div>
							
						</form>
						<!--<div class="form-response show"></div>-->
					</div>
				</div>
			</div>
		</div>
		<!-- Signup End -->

	</div>
	<!-- Fim Alterar capa Modal End -->

	<?php
		$MiniGet="SELECT id, miniatura FROM blog WHERE id='$id_altmini'";
			$rMG=mysqli_query($con, $MiniGet);
			$dMG=mysqli_fetch_array($rMG);
	?>

	<!-- Alterar miniatura Modal End -->
	<div id="editarminiaturadopost" class="section-block pt-0 pb-30 background-none hide">
		
		<!-- Intro Title Section 2 -->
		<div class="thumbnail xsmall">
			<img src="<?php echo $dMG['miniatura'];?>" width="825" height="400" alt="">
		</div>
		<!-- Intro Title Section 2 End -->

		<!-- Signup -->
		<div class="section-block pt-60 pb-0">
			<div class="row">
				<div class="column width-12 left">
					<div class="signup-form-container">
						<div class="row">
							<div class="column width-10 offset-1">
								<p>
									<?php
									
									?>
								</p>
							</div>
						</div>
						<form class="form" action="" method="post" enctype="multipart/form-data">
							<div class="row">
								<div class="column width-12">
									<input type="file" name="miniatura" class="button medium border-charcoal-light color-blue-light color-hover-blue">
								</div>
							</div>
							<div class="row">
								<div class="column width-5 left">
									<input type="submit" value="ALTERAR MINIATURA" name="btn-alterarminiatura" class="button medium border-red color-blue-lght color-hover-blue">
								</div>
							</div>
							
						</form>
						<!--<div class="form-response show"></div>-->
					</div>
				</div>
			</div>
		</div>
		<!-- Signup End -->
	</div>
	<!-- Fim Alterar miniatura Modal End -->

	<!-- Adicionar miniatura modal End -->
	<div id="addminiatura"  class="section-block pt-0 pb-30 background-none hide">
		
		<!-- Intro Title Section 2 -->
		<div class="thumbnail xsmall">
			<div class="row">
				<div class="column width-12">
					<h2 class="center pt-20 pb-10 "> Adicionar miniatura</h2>
				</div>
			</div>
		</div>
		<!-- Intro Title Section 2 End -->

		<!-- Signup -->
		<div class="section-block pt-60 pb-0">
			<div class="row">
				<div class="column width-12 left">
					<div class="signup-form-container">
						<div class="row">
							<div class="column width-10 offset-1">
								<p>
									<?php
									
									?>
								</p>
							</div>
						</div>
						<form class="form" action="" method="post" enctype="multipart/form-data">
							<!-- Campos do post invísiveis -->
							<input type="hidden"  name="titulo" maxlength="100" class="form-aux form-date form-element large" value='<?php echo $dbp['titulo'];?>' placeholder="Título do texto" tabindex="5">
							
							<input type="hidden"  value="<?php echo $dbp['datadapostagem'];?>" name="datadapostagem" class="form-aux form-date form-element large"  tabindex="5">

							<input type="hidden"  value="<?php echo $dbp['capa'];?>" name="capanew" class="form-aux form-date form-element large"  tabindex="5">
							
							<input type="hidden" cols="5" rows="10" name="textoancora" maxlength="500" class="form-aux form-date form-element large" placeholder="Digite aqui o texto de destaque deste post. // Máximo de 500 caracteres." tabindex="5" value="<?php echo $dbp['textoancora'];?>"/>
							
							<input type="hidden" name="categoria" value="<?php echo $dbp['categoria']; ?>"/>

							<input type="hidden" name="txtArtigo" id="txtArtigo" class="form-message form-element large" placeholder="Descrição do case. (Até 5.000 caracteres)." tabindex="36"  value="<?php echo $dbp['texto'];?>"/>

							<input type="hidden" cols="5" rows="10" name="bibliografia" maxlength="4800" class="form-aux form-date form-element large" placeholder="Informe a bibliografia do seu texto" tabindex="5" value="<?php echo $dbp['bibliografia'];?>"/>
							<!-- Fim dos campos invisíveis -->

							<div class="row">
								<div class="column width-12">
									<input type="file" name="miniaturanew" class="button medium border-charcoal-light color-blue-light color-hover-blue">
								</div>
							</div>

							<div class="row">
								<div class="column width-5 left">
									<input type="submit" value="Adicionar miniatura" name="btn-adicionarminiatura" class="button medium border-red color-blue-lght color-hover-blue">
								</div>
							</div>
							
						</form>
						<!--<div class="form-response show"></div>-->
					</div>
				</div>
			</div>
		</div>
		<!-- Signup End -->
	</div>
	<!-- Fim Adicionar miniatura modal End -->


	<?php
		if(isset($_GET['altmini']) AND !empty($_GET['altmini'])):
	?>
		<script>
			window.setTimeout(function(){
				document.getElementById("minialt").click();
			}, 500);
		</script>
		<script>
			window.setTimeout(function(){
				document.getElementById("minialt").click();
			}, 2000);
		</script>
		<a id='minialt' data-content="inline" data-aux-classes="tml-newsletter-modal tml-exit-light" data-toolbar="" data-modal-mode data-modal-width="600" data-lightbox-animation="fadeIn" href="#editarminiaturadopost" class="lightbox-link"><label class="color-black">(ALTERAR MINIATURA.)</label></a>
	<?php
		endif;
	?>

	<? include "./_script.php"; ?>
    
</body>
</html>