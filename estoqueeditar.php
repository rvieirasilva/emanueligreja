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

	
	$buscarpost="SELECT * FROM estoque WHERE codigodoestoque='$refdopost'";
		$rbp=mysqli_query($con, $buscarpost);
			$dbp=mysqli_fetch_array($rbp);


	if(isset($_POST['btn-finalizar'])):
        $bCity="SELECT id, uf FROM cidades WHERE nome='$cidadedomembro'";
          $rCity=mysqli_query($con, $bCity);
            $dCity=mysqli_fetch_array($rCity);

        $UFcity=$dCity['uf'];
        $idCity=$dCity['id'];

        $codigodoestoque=mysqli_escape_string($con, $_POST['codigoestoque']);
		$produto					    = mysqli_escape_string($con, $_POST['produto']); $produto = str_replace("'", "", $produto);
		$statusdoproduto			    = mysqli_escape_string($con, $_POST['statusdoproduto']);
		$categoriadoproduto			    = mysqli_escape_string($con, $_POST['categoriadoproduto']);
		$valorunitario				    = mysqli_escape_string($con, $_POST['valorunitario']);
		$quantidade				        = mysqli_escape_string($con, $_POST['quantidade']);
		$validade				        = mysqli_escape_string($con, $_POST['validade']);
		$datadefabricacao				= mysqli_escape_string($con, $_POST['datadefabricacao']);
		$lote							= mysqli_escape_string($con, $_POST['lote']);
		$localdearmazenamento			= mysqli_escape_string($con, $_POST['localdearmazenamento']);
		$descricao						= mysqli_escape_string($con, $_POST['descricao']);

        if(!empty($_POST['novacategoria'])): $categoriadoproduto=mysqli_escape_string($con, $_POST['novacategoria']); endif;

        if($statusdoproduto === "Em uso"):
            $abertoem = date("Y-m-d");
        else:
            $abertoem='';
        endif;

        if($valorunitario > 1000):
            $valorunitario = number_format($valorunitario, 2, '', '.');
        elseif($valorunitario < 1000):
            $valorunitario = str_replace(',','.', $valorunitario);
        endif;

        // $valorfinal=$valorunitario*$quantidade;
        $valorfinal=number_format($valorfinal, 2, '.', '');

        //Registrar arrecadaçao financeira.
        $upEst="UPDATE estoque SET campus='$membrodocampus', codigodocampus='$codigodocampus', adicionadopor='$nomedousuario', matriculadoestoquista='$matriculausuario', codigodoestoque='$codigodoestoque', produto='$produto', valorunitario='$valorunitario', situacaodoproduto='$statusdoproduto', categoriadoproduto='$categoriadoproduto', validade='$validade', datadefabricacao='$datadefabricacao', lote='$lote', localdearmazenamento='$localdearmazenamento', valorfinal='$valorfinal', quantidade='$quantidade', abertoem='$abertoem', notas='$descricao' WHERE codigodoestoque='$refdopost'"; 
        if(mysqli_query($con, $upEst)):
            $_SESSION['cordamensagem']='green';
            $_SESSION['mensagem']="Estoque atualizado com sucesso";
        else:
            $_SESSION['cordamensagem']='red';
            $_SESSION['mensagem']="Erro ao atualizar o estoque";
        endif;
    endif;
	
	//Comando para o modal de alterar miniatura.
	if(isset($_POST['btn-alterarcapa'])):        
		if(!empty($_FILES['imagemcapa']['name'])):
			$extensaocapa = pathinfo($_FILES['imagemcapa']['name'], PATHINFO_EXTENSION);
			if(in_array($extensaocapa, $formatospermitidosimagens)):
				$img_capa 			= $_FILES["imagemcapa"]['name'];

				@mkdir("agenda/", 0777); //Cria a pasta se não houver. 
				@mkdir("agenda/$codigodoevento/", 0777); //Cria a pasta se não houver. 
				@mkdir("agenda/$codigodoevento/capa/", 0777); //Cria a pasta se não houver. 

				$pastacapa			= "agenda/$codigodoevento/capa/";
				$temporariocapa	    = $_FILES['imagemcapa']['tmp_name'];
				$tamanhodacapa	    = $_FILES['imagemcapa']['size'];
				$novoNomecapa		= mt_rand(10,9999).'.'.$extensaocapa;
					$capa		= $pastacapa.$novoNomecapa;
				
				//Compacta a imagem da capa								
				if($tamanhodacapa >= 1000000):
					$quality_capa = 30;	
				elseif($tamanhodacapa >= 5000000 OR $tamanhodacapa < 1000000):
					$quality_capa = 50;
				else:	
					$quality_capa = 60;	
				endif;

				function compress_image($img_capa, $capa, $quality_capa) {
					$info = getimagesize($img_capa);
					if ($info["mime"] == "image/jpeg") $image = imagecreatefromjpeg($img_capa);
					elseif ($info["mime"] == "image/gif") $image = imagecreatefromgif($img_capa);
					elseif ($info["mime"] == "image/png") $image = imagecreatefrompng($img_capa);
					imagejpeg($image, $capa, $quality_capa);
					return $capa;
				}
					
				$temporariocapa = compress_image($_FILES["imagemcapa"]["tmp_name"], $capa, $quality_capa); //Compacta a imagem 
			

				move_uploaded_file($temporariocapa, $capa);		
				
		
                $capa = "UPDATE agenda SET capa='$capa' WHERE codigodoevento='$refdopost'";
                if (mysqli_query($con, $capa)):	

                    @unlink($capaantiga); //Excluí a capa antiga. @ Caso não exista uma não aparecerá o erro.

                    // move_uploaded_file($temporariocapa, $linkcapa);
                
                    $_SESSION['cordamensagem'] = "green";
                    $_SESSION['mensagem'] = "Capa atualizada.";
                    header("refresh:1; url=agendaeditar?m=$matricula&id=$iddopost&token=$token&ref=$refdopost");           
                else:
					unlink($capa);
                    $_SESSION['cordamensagem'] = "red";
                    $_SESSION['mensagem'] = "Ocorreu um erro e a capa não foi atualizada.";
                    //header("Location:ge_posteditarunico?id=$iddopost&".uniqid()."&tgestor=$token");
                endif;
            endif;
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
                                                        <h1 class="mb-0">Editar <strong>estoque</strong></h1>
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
                        <div class="row">
                            <?php include "./_notificacaomensagem.php"; ?>
                            <div class="box rounded rounded shadow border-blue bkg-white">
                                <a href="./estoqueeditarlist?<?echo $linkSeguro;?>">Voltar</a>
                            </div>
                            <div class="box rounded rounded shadow border-blue bkg-white">
                                <form class="form" action="<?echo $_SERVER['REQUEST_URI'];?>" charset="UTF-8" method="post" enctype="Multipart/form-data">
                                    <div class="row">
                                        <?php
                                            //Verificação conforme o número de miniaturas.

                                            $bPostQntd="SELECT id, arquivo FROM estoque WHERE codigodoestoque='$refdopost'";
                                            $rPQntd=mysqli_query($con, $bPostQntd);
                                                $nPQntd = mysqli_num_rows($rPQntd);
                                                $dPQntd=mysqli_fetch_array($rPQntd);
                                                    $fotodoproduto  = $dPQntd['arquivo'];
                                                    $mini_id			= $dPQntd['id'];
                                        ?>
                                        <div class="row">
                                            <div class="column width-12">
                                                <h5 class="color-black"><strong>FOTO ATUAL DO ITEM.</strong>
                                                    <a data-content="inline" data-aux-classes="tml-newsletter-modal tml-exit-light" data-toolbar="" data-modal-mode data-modal-width="600" data-lightbox-animation="fadeIn" href="#editarfotodoitem" class="lightbox-link"><span class="color-black text-small">(ALTERAR FOTO.)</span></a>
                                                </h5>
                                                <img class="center" src="<?php echo $fotodoproduto;?>" width="800" height="400">
                                            </div>
                                        </div>
                                        <br>
                                        <br>
                                        <div class="column width-12">
                                            <span class="color-black"><strong>Código</strong> do estoque <strong>(Opcional)</strong></span>
                                            <div class="field-wrapper">
                                                <input type="text" readonly name="codigoestoque" value="<?echo $dbp['codigodoestoque'];?>" class="form-aux form-date form-element medium"  tabindex="002">
                                            </div>
                                        </div>
                                        <div class="column width-7">
                                            <span class="color-black"><strong>Nome do produto</strong></span>
                                            <div class="field-wrapper">
                                                <input type="text"  name="produto" value="<?echo $dbp['produto'];?>" class="form-aux form-date form-element medium"  tabindex="003">
                                            </div>
                                        </div>
                                        <div class="column width-3">
                                            <span class="color-black"><strong>Valor</strong> R$</span>
                                            <div class="field-wrapper">
                                                <input type="text"  name="valorunitario" value="<?echo $dbp['valorunitario'];?>" class="form-aux form-date form-element medium"  tabindex="004">
                                            </div>
                                        </div>
                                        <div class="column width-2">
                                            <span class="color-black"><strong>Quantidade</strong></span>
                                            <div class="field-wrapper">
                                                <input type="number"  name="quantidade" value="<?echo $dbp['quantidade'];?>" class="form-aux form-date form-element medium"  tabindex="005">
                                            </div>
                                        </div>
                                        <div class="column width-4">
                                            <span class="color-black"><strong>Validade</strong></span>
                                            <div class="field-wrapper">
                                                <input type="date"  name="validade" value="<?echo $dbp['validade'];?>" value="<?php echo date('Y-m-d');?>" class="form-aux form-date form-element medium"  tabindex="006">
                                            </div>
                                        </div>
                                        <div class="column width-4">
                                            <span class="color-black">Data de <strong>Fabricação</strong></span>
                                            <div class="field-wrapper">
                                                <input type="date"  name="datadefabricacao" value="<?echo $dbp['datadefabricacao'];?>" value="<?php echo date('Y-m-d');?>" class="form-aux form-date form-element medium"  tabindex="007">
                                            </div>
                                        </div>
                                        <div class="column width-4">
                                            <span class="color-black"><strong>Lote</strong></span>
                                            <div class="field-wrapper">
                                                <input type="text"  name="lote" value="<?echo $dbp['lote'];?>" class="form-aux form-date form-element medium"  tabindex="008">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="column width-4">
                                            <span class="color-black"><strong>Status</strong> do produto</span>
                                            <div class="form-select form-element medium">
                                                <select name="statusdoproduto" tabindex="019" class="form-aux" data-label="sexo">
                                                    <option><?echo $dbp['situacaodoproduto'];?></option>
                                                    <option>Fechado</option>
                                                    <option>Em uso</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="column width-4">
                                            <span class="color-black"><strong>Categoria</strong> do produto</span>
                                            <div class="form-select form-element medium">
                                                <select name="categoriadoproduto" tabindex="019" class="form-aux" data-label="sexo">
                                                    <option><?echo $dbp['categoriadoproduto'];?></option>
                                                    <option>Som</option>
                                                    <option>Água</option>
                                                    <option>Material para ceia</option>
                                                    <option>Material para obra</option>
                                                    <option>Elétrica</option>
                                                    <option>Mídia</option>
                                                    <option>Infraestrutura</option>
                                                    <option>Limpeza</option>
                                                    <option>Alimentação</option>
                                                    <option>Locomoção</option>
                                                    <?
                                                        $bCat="SELECT categoriadoproduto FROM estoque WHERE categoria !='' GROUP BY categoriadoproduto ORDER BY categoriadoproduto ASC";
                                                        $rCat=mysqli_query($con, $bCat);
                                                            while($dCat=mysqli_fetch_array($rCat)):
                                                    ?>
                                                    <option><? echo $dCat['categoriadoproduto'];?></option>
                                                    <? endwhile; ?>
                                                    <option>Outro</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="column width-4">
                                            <span class="color-black"><strong>Nova</strong> categoria</span>
                                            <div class="field-wrapper">
                                                <input type="text"  name="novacategoria" class="form-aux form-date form-element medium"  tabindex="020">
                                            </div>
                                        </div>
                                        <div class="column width-12">
                                            <span class="color-black"><strong>Armazenado</strong> no local:</span>
                                            <div class="field-wrapper">
                                                <input type="text"  name="localdearmazenamento" value="<?echo $dbp['localdearmazenamento'];?>"  class="form-aux form-date form-element medium"  tabindex="009">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="column width-12">
                                            <span class="color-black">Descrição do produto</span>
                                            <div class="field-wrapper">
                                                <textarea type="text" name="descricao" class="form-aux form-date form-element medium"  tabindex="011"><?echo $dbp['notas'];?></textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <br>
                                        <div class="column width-12">
                                            <button type="submit" tabindex="012" name="btn-finalizar" class="form-submit button pill small border-theme bkg-hover-theme color-theme color-hover-white">Atualizar no estoque</button>
                                        </div>
                                    </div>
                                </form>
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

            <!-- Alterar capa Modal End -->
            <div id="editarfotodoitem" class="section-block pt-0 pb-30 background-none hide">
                
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
                                <form id="plev" class="form" action="" method="post" enctype="Multipart/form-data">
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

		</div>
	</div>

	<? include "./_script.php"; ?>
    
    <script src="js/printThis.js"></script>
    <script type="text/javascript">
    $(document).ready(function() {
        $("#btnPrint<?php echo $prints[$print];?>").click(function() {
            //get the modal box content and load it into the printable div
            $(".printable").html($("#plev").html());
            $(".printable").printThis();
        });
    });
    </script>
</body>
</html>