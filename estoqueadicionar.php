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
	
	if(isset($_POST['btn-finalizar'])):

        $bCity="SELECT id, uf FROM cidades WHERE nome='$cidadedomembro'";
          $rCity=mysqli_query($con, $bCity);
            $dCity=mysqli_fetch_array($rCity);

        $UFcity=$dCity['uf'];
        $idCity=$dCity['id'];

        if(empty($_POST['codigoestoque'])):
            $bIDR="SELECT id FROM estoque";
            $rID=mysqli_query($con, $bIDR);
                $nID=mysqli_num_rows($rID);

            $codigodoestoque= $UFcity.'-'.$idCity.'/'.$nID.mt_rand(00000, 99999);
        else:
            $codigodoestoque=mysqli_escape_string($con, $_POST['codigoestoque']);
            $codigodoestoque=$UFcity.'-'.$idCity.'/'.$codigodoestoque;
        endif;        

		$produto					= mysqli_escape_string($con, $_POST['produto']); $produto = str_replace("'", "", $produto);
		$statusdoproduto					= mysqli_escape_string($con, $_POST['statusdoproduto']);
		$categoriadoproduto					= mysqli_escape_string($con, $_POST['categoriadoproduto']);
		$valorunitario						= mysqli_escape_string($con, $_POST['valorunitario']);
		$quantidade				= mysqli_escape_string($con, $_POST['quantidade']);
		$validade				= mysqli_escape_string($con, $_POST['validade']);
		$datadefabricacao				= mysqli_escape_string($con, $_POST['datadefabricacao']);
		$lote							= mysqli_escape_string($con, $_POST['lote']);
		$localdearmazenamento					= mysqli_escape_string($con, $_POST['localdearmazenamento']);
		$descricao						= mysqli_escape_string($con, $_POST['descricao']);

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
        // if($valorfinal > 1000): $valorfinal=number_format($valorfinal, 2, '.', ''); else: $valorfinal=number_format($valorfinal, 2, '.', ''); endif;

        $bDuplicado="SELECT id FROM estoque WHERE produto='$produto' AND validade='$validade' AND datadefabricacao='$datadefabricacao' AND campus='$membrodocampus'";
          $rDuplicado=mysqli_query($con, $bDuplicado);
            $nDuplicado=mysqli_num_rows($rDuplicado);

        if($nDuplicado > 0):
            $_SESSION['cordamensagem']='red';
            $_SESSION['mensagem']='Este item foi enviado anteriormente, acesse o estoque para editá-lo';
        else:            
            if(!empty($_FILES['fotodoproduto']['name'])):
                $extensaodafoto = pathinfo($_FILES['fotodoproduto']['name'], PATHINFO_EXTENSION);
                if(in_array($extensaodafoto, $formatospermitidosimagens)):
                    $img_foto 			= $_FILES["capa"]['name'];

                    @mkdir("arq/", 0777); //Cria a pasta se não houver.  
                    @mkdir("arq/estoque/", 0777); //Cria a pasta se não houver.  
                    @mkdir("arq/estoque/$membrodocampus/", 0777); //Cria a pasta se não houver.  
                    @mkdir("arq/estoque/$membrodocampus/$ano/", 0777); //Cria a pasta se não houver.  
                    @mkdir("arq/estoque/$membrodocampus/$ano/$mes/", 0777); //Cria a pasta se não houver.  

                    $pastafoto			= "arq/estoque/$membrodocampus/$ano/$mes/";
                    $temporariofoto	    = $_FILES['fotodoproduto']['tmp_name'];
                    $tamanhofoto	    = $_FILES['fotodoproduto']['size'];
                    // $novonomedafoto		= $_FILES['fotodoproduto']['name'].'.'.$extensaodafoto;
                    $novonomedafoto		= $_FILES['fotodoproduto']['name'];
                        $fotodoproduto		= $pastafoto.$novonomedafoto;
                    
                    $quality_capa = 60;	

                    function compress_image($img_foto, $fotodoproduto, $quality_capa) {
                        $info = getimagesize($img_foto);
                        if ($info["mime"] == "image/jpeg") $image = imagecreatefromjpeg($img_foto);
                        elseif ($info["mime"] == "image/gif") $image = imagecreatefromgif($img_foto);
                        elseif ($info["mime"] == "image/png") $image = imagecreatefrompng($img_foto);
                        imagejpeg($image, $fotodoproduto, $quality_capa);
                        return $fotodoproduto;
                    }
                        
                    $temporariofoto = compress_image($_FILES["fotodoproduto"]["tmp_name"], $fotodoproduto, $quality_capa); //Compacta a imagem 
                    move_uploaded_file($temporariofoto, $fotodoproduto);
                endif;
            endif;

            //Registrar arrecadaçao financeira.
            $inserir="INSERT INTO estoque (dia, mes, ano, campus, codigodocampus, adicionadopor, matriculadoestoquista, codigodoestoque, produto, valorunitario, situacaodoproduto, categoriadoproduto, validade, datadefabricacao, lote, arquivo, tamanhodoarquivo, localdearmazenamento, valorfinal, quantidade, abertoem, consumidoem, descartadoem, motivododescarte, notas) VALUES ('$dia', '$mesnumero', '$ano', '$membrodocampus', '$codigodocampus', '$nomedousuario', '$matriculausuario', '$codigodoestoque', '$produto', '$valorunitario', '$statusdoproduto', '$categoriadoproduto', '$validade', '$datadefabricacao', '$lote', '$fotodoproduto', '$tamanhofoto', '$localdearmazenamento', '$valorfinal', '$quantidade', '$abertoem', '', '', '', '$descricao')"; 
            if(mysqli_query($con, $inserir)):
                $_SESSION['cordamensagem']='green';
                $_SESSION['mensagem']="Estoque registrado com sucesso";
            else:
                $_SESSION['cordamensagem']='red';
                $_SESSION['mensagem']="Erro ao cadastrar estoque";
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
                                                        <h1 class="mb-0">Registrar <strong>estoque</strong></h1>
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
                                <div class="column width-12">
                                    <div class="contact-form-container">
                                        <form class="form" action="<?echo $_SERVER['REQUEST_URI'];?>" charset="UTF-8" method="post" enctype="Multipart/form-data">
                                            <div class="row">
                                                <div class="column width-6">
                                                    <span class="color-black text-xsmall">Foto produto</span>
                                                    <div class="field-wrapper">
                                                        <input type="file" name="fotodoproduto" class="form-aux form-date form-element medium" placeholder="portfolio" tabindex="001">
                                                    </div>
                                                </div>
                                                <div class="column width-6">
                                                    <span class="color-black"><strong>Código</strong> do estoque <strong>(Opcional)</strong></span>
                                                    <div class="field-wrapper">
                                                        <input type="text"  name="codigoestoque" class="form-aux form-date form-element medium"  tabindex="002">
                                                    </div>
                                                </div>
                                                <div class="column width-7">
                                                    <span class="color-black"><strong>Nome do produto</strong></span>
                                                    <div class="field-wrapper">
                                                        <input type="text"  name="produto" class="form-aux form-date form-element medium"  tabindex="003">
                                                    </div>
                                                </div>
                                                <div class="column width-3">
                                                    <span class="color-black"><strong>Valor</strong> R$</span>
                                                    <div class="field-wrapper">
                                                        <input type="text"  name="valorunitario" class="form-aux form-date form-element medium"  tabindex="004">
                                                    </div>
                                                </div>
                                                <div class="column width-2">
                                                    <span class="color-black"><strong>Quantidade</strong></span>
                                                    <div class="field-wrapper">
                                                        <input type="number"  name="quantidade" class="form-aux form-date form-element medium"  tabindex="005">
                                                    </div>
                                                </div>
                                                <div class="column width-4">
                                                    <span class="color-black"><strong>Validade</strong></span>
                                                    <div class="field-wrapper">
                                                        <input type="date"  name="validade" value="<?php echo date('Y-m-d');?>" class="form-aux form-date form-element medium"  tabindex="006">
                                                    </div>
                                                </div>
                                                <div class="column width-4">
                                                    <span class="color-black">Data de <strong>Fabricação</strong></span>
                                                    <div class="field-wrapper">
                                                        <input type="date"  name="datadefabricacao" value="<?php echo date('Y-m-d');?>" class="form-aux form-date form-element medium"  tabindex="007">
                                                    </div>
                                                </div>
                                                <div class="column width-4">
                                                    <span class="color-black"><strong>Lote</strong></span>
                                                    <div class="field-wrapper">
                                                        <input type="text"  name="lote" class="form-aux form-date form-element medium"  tabindex="008">
                                                    </div>
                                                </div>
                                                <div class="column width-6">
                                                    <span class="color-black"><strong>Status</strong> do produto</span>
                                                    <div class="form-select form-element medium">
                                                        <select name="statusdoproduto" tabindex="019" class="form-aux" data-label="sexo">
                                                            <option>Fechado</option>
                                                            <option>Em uso</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="column width-3">
                                                    <span class="color-black"><strong>Categoria</strong> do produto</span>
                                                    <div class="form-select form-element medium">
                                                        <select name="categoriadoproduto" tabindex="019" class="form-aux" data-label="sexo">
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
                                                <div class="column width-3">
                                                    <span class="color-black"><strong>Nova</strong> categoria</span>
                                                    <div class="field-wrapper">
                                                        <input type="text"  name="novacategoria" class="form-aux form-date form-element medium"  tabindex="020">
                                                    </div>
                                                </div>
                                                <div class="column width-12">
                                                    <span class="color-black"><strong>Armazenado</strong> no local:</span>
                                                    <div class="field-wrapper">
                                                        <input type="text"  name="localdearmazenamento" class="form-aux form-date form-element medium"  tabindex="009">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="column width-12">
                                                    <span class="color-black">Descrição do produto</span>
                                                    <div class="field-wrapper">
                                                        <textarea type="text"  name="descricao" class="form-aux form-date form-element medium"  tabindex="011"></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <br>
                                                <div class="column width-12">
                                                    <button type="submit" tabindex="012" name="btn-finalizar" class="form-submit button pill small border-theme bkg-hover-theme color-theme color-hover-white">Registrar no estoque</button>
                                                </div>
                                            </div>
                                        </form>
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