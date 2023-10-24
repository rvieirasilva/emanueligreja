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
	// endif;
    
    $CategoriaPage = $_GET['c'];
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
                                                        <h1 class="mb-0"><strong>Conceitos</strong> para interiorizar</h1>
                                                        <p class="text-large color-charcoal mb-0 mb-mobile-20">Conheça algumas frases que você precisa interiorizar e que te ajudarão na jornada com Cristo.</p>
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
                                    <div class="form-container">
                                        <label class="color-black"><strong>Frases por autor.</strong></label>
                                        <form class="form" action="" charset="utf-8" method="get">
                                            <div class="row">
                                                <div class="column width-8">
                                                    <div class="field-wrapper">
                                                        <div class="form-select form-element medium">
                                                            <select tabindex="3" name="autor" maxlength= "30" class="form-aux" data-label="turno">
                                                                    <option tabindex="3" value="Rafael Vieira">Rafael Vieira</option>
                                                                <?php 
                                                                    if(empty($_GET['autor'])):
                                                                ?>
                                                                    <option tabindex="3" selected='selected' value="">Todos</option>
                                                                <?php
                                                                    else:
                                                                ?>
                                                                    <option tabindex="3" value="">Todos</option>
                                                                <?php
                                                                    endif;
                                                                ?>
                                                                <?php
                                                                    //Traz os autores
                                                                    $Ac= "SELECT autor, count(conceito) as conceitosdoautor FROM conceitos WHERE NOT (postadoem > '$dataeng') AND conceito != '' GROUP BY autor ORDER BY autor  ASC";
                                                                    $rAc = mysqli_query($con, $Ac);
                                                                        while($dAc = mysqli_fetch_array($rAc)):
                                                                            $filtro_autor   = str_replace(' ', '-', $dAc['autor']);
                                                                ?>
                                                                    <?php
                                                                        if($_GET['autor'] === $dAc['autor']):
                                                                            echo "<option selected='selected' value='".$dAc['autor']."'>";
                                                                        else:
                                                                            echo "<option value='".$dAc['autor']."'>";
                                                                        endif;
                                                                    ?>
                                                                        <?php
                                                                            if($dAc['autor'] === 'Rafael Vieira'):
                                                                                $aut = $dAc['autor'];
                                                                                $LabelAutor = explode(' ', $aut);
                                                                                $LabelAutor = $LabelAutor[0].' '.$LabelAutor[1];
                                                                                echo '<strong>'.$LabelAutor . ' </strong> (' . $dAc['conceitosdoautor'] .')';
                                                                            else:
                                                                                $aut = $dAc['autor'];
                                                                                $LabelAutor = explode(' ', $aut);
                                                                                $LabelAutor = $LabelAutor[0].' '.$LabelAutor[1];
                                                                                echo $LabelAutor . ' (' . $dAc['conceitosdoautor'] .')';
                                                                            endif;
                                                                        ?>
                                                                    </option>
                                                                <?php
                                                                    endwhile;
                                                                ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="column width-2">
                                                    <input type="submit" value="Filtrar" class="column full-width form-submit button rounded medium bkg-blue bkg-hover-blue color-white color-hover-white hard-shadow">
                                                </div>
                                                <div class="column width-2">
                                                    <a href="conceitos?<?php echo $linkSeguro;?>" class="column full-width form-submit button rounded medium bkg-red bkg-hover-red color-white color-hover-white hard-shadow center">Limpar</a>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                <div class="column width-12">
                                    <div class="row grid content-grid-3">
                                        <?php
                                            // <!--CÓDIGO PARA FAZER PESQUISA DOS HORÁRIOS E CRIAR O LOOPING -->
                                            $pagina = (isset($_GET['pagina']))? $_GET['pagina'] : 1;

                                            if(empty($_GET['autor'])):
                                                $bconceitos= "SELECT id FROM conceitos WHERE NOT (postadoem > '$dataeng') AND conceito != '' ORDER BY id DESC";
                                            else:
                                                $AutorFiltro = $_GET['autor'];
                                                $bconceitos= "SELECT id FROM conceitos WHERE NOT (postadoem > '$dataeng') AND conceito != '' AND autor='$AutorFiltro' ORDER BY id DESC";
                                            endif;

                                            $resultadobconceitos = mysqli_query($con, $bconceitos);									
                                            $totaldeconceitos = mysqli_num_rows($resultadobconceitos);

                                            //Defini o número de horarios que serão exibidos por página
                                            $conceitosporpagina = 12;

                                            //defini número de páginas necessárias para exibir todos os horarios.
                                            $totaldepaginas = ceil($totaldeconceitos / $conceitosporpagina);

                                            $inicio = ($conceitosporpagina * $pagina) - $conceitosporpagina;

                                            
                                            //AQUI ELE FAZ A PESQUISA PELO CAMPUS E TRAZ O DADO MAIS RECENTE PELO ANO, SE ESTAMOS EM 2010 ELE TRARÁ OS DADOS DESTE ANO OU DO ÚLTIMO ANO EM QUE O CAMPUS APRESENTOU UMA MATÉRIA REGISTRADA, COM BASE NISSO É FEITO UM FILTRO EXIBINDO POR ORDEM O DIA E DEPOIS O HORÁRIO.
                                            if(empty($_GET['autor'])):
                                                // $osconceitos = "SELECT imagenssite.pasta, conceitos.conceito, conceitos.autor, char_length(conceito) as nconceito FROM imagenssite, conceitos WHERE imagenssite.tipo='MINIATURA' and conceitos.conceito!='' GROUP BY conceitos.conceito ORDER BY rand(), pasta, conceitos.conceito LIMIT $inicio, $conceitosporpagina";
                                                $osconceitos = "SELECT id, conceito, autor, char_length(conceito) as nconceito FROM conceitos WHERE postadoem <= '$dataeng' AND conceito!='' GROUP BY conceito ORDER BY postadoem DESC LIMIT $inicio, $conceitosporpagina";
                                            else:
                                                $AutorFiltro = $_GET['autor'];
                                                // $osconceitos = "SELECT imagenssite.pasta, conceitos.conceito, conceitos.autor, char_length(conceito) as nconceito FROM imagenssite, conceitos WHERE imagenssite.tipo='MINIATURA' and conceitos.conceito!='' AND autor='$AutorFiltro' GROUP BY conceitos.conceito ORDER BY rand(), conceitos.postadoem DESC LIMIT $inicio, $conceitosporpagina";
                                                $osconceitos = "SELECT id, conceito, autor, char_length(conceito) as nconceito FROM conceitos WHERE postadoem <= '$dataeng' AND conceito!='' AND autor='$AutorFiltro' GROUP BY conceito ORDER BY postadoem DESC LIMIT $inicio, $conceitosporpagina";
                                            endif;
                                                $resultadoosconceitos = mysqli_query($con  , $osconceitos);
                                                    while($dadososconceitos = mysqli_fetch_array($resultadoosconceitos)):
                                                        $idfrasePrint = "fraseprint".$dadososconceitos['id'];

                                                        $nconceitoPrint = $dadososconceitos['conceito'];
                                                        $nconceitoPrint = explode(" ", $nconceitoPrint);
                                                        $nconceitoPrint = $nconceitoPrint[0].'_'.$nconceitoPrint[1];
                                        ?>
                                        <?php
                                            if($dadososconceitos['nconceito'] > 180):
                                        ?>
                                        <div class="grid-item grid-sizer" id="<?php echo $idfrasePrint;?>">
                                            <!-- <a id="demo" class="<?php echo $idfrasePrint;?>" onclick="<?php echo $idfrasePrint;?>()" href="#">  -->
                                            <div class="thumbnail" data-hover-speed="1000">
                                                <?php
                                                    $bImgCon = "SELECT fundo, pasta FROM imagenssite WHERE pasta != '$ultimaimagem' AND pasta!=''AND tipo='MINIATURA' ORDER BY rand()";
                                                        $rBIMGCon = mysqli_query($con, $bImgCon);
                                                        $dIMGCon = mysqli_fetch_array($rBIMGCon);
                                                        if($dIMGCon['fundo'] === "Escuro"):
                                                            $corletra= 'white';
                                                        else:
                                                            $corletra='charcoal';
                                                        endif;

                                                        $ultimaimagem = $dIMGCon['pasta'];
                                                ?>
                                                <img src="<?php echo $dIMGCon['pasta'];?>" alt="<?php //echo $dPost['titulo'];?>"/>
                                                <div class="caption-over-outer">
                                                    <div class="caption-over-inner">
                                                        <div class="row">
                                                            <div class="column width-10 offset-1 color-<?php echo $corletra;?>">
                                                                <!-- <span class="icon-quote"></span><br> -->
                                                                <span class="text-large weight-regular pt-0 pb-0">
                                                                    <?php
                                                                        echo $dadososconceitos['conceito'];
                                                                    ?>
                                                                </span>
                                                                <label class="text-medium pt-0 pb-0 weight-bold">
                                                                    <?php
                                                                        echo '('.$dadososconceitos['autor'].')';
                                                                    ?>
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- </a> -->
                                        </div>
                                        <!-- <script>
                                            //Script para impressão da frase mas está saindo com baixa qualidade.
                                            function <?php echo $idfrasePrint;?>() {

                                                var node = document.getElementById('<?php echo $idfrasePrint;?>');

                                                domtoimage.toPng(node)
                                                    .then(function (dataUrl) {
                                                        var img = new Image();
                                                        img.src = dataUrl;
                                                        downloadURI(dataUrl, "<?php echo $nconceitoPrint;?>.png")
                                                    })
                                                    .catch(function (error) {
                                                        console.error('oops, something went wrong!', error);
                                                    });

                                            }

                                            function downloadURI(uri, name) {
                                                var link = document.createElement("a");
                                                link.download = name;
                                                link.href = uri;
                                                document.body.appendChild(link);
                                                link.click();
                                                document.body.removeChild(link);
                                                delete link;
                                            }
                                        </script>								 -->
                                        <?php
                                            else:
                                        ?>
                                            <div class="grid-item grid-sizer">
                                                <!-- <a id="demo" class="<?php echo $idfrasePrint;?>" onclick="<?php echo $idfrasePrint;?>()" href="#">  -->
                                                <div class="thumbnail" data-hover-speed="1000" id="<?php echo $idfrasePrint;?>">
                                                    <?php
                                                        $bImgCon = "SELECT fundo, pasta FROM imagenssite WHERE pasta != '$ultimaimagem' AND pasta!='' AND tipo='MINIATURA' ORDER BY rand()";
                                                            $rBIMGCon = mysqli_query($con, $bImgCon);
                                                            $dIMGCon = mysqli_fetch_array($rBIMGCon);
                                                            if($dIMGCon['fundo'] === "Escuro"):
                                                                $corletra= 'white';
                                                            else:
                                                                $corletra='charcoal';
                                                            endif;

                                                            $ultimaimagem = $dIMGCon['pasta'];
                                                    ?>
                                                    <img src="<?php echo $dIMGCon['pasta'];?>" alt="<?php echo $dadososconceitos['conceito'];?>"/>
                                                    <div class="background-none caption-over-outer">
                                                        <div class="background-none caption-over-inner">
                                                            <div class="row">
                                                                <div class="column width-10 offset-1 color-<?php echo $corletra;?>">
                                                                    <!-- <span class="icon-quote"></span><br> -->
                                                                    <span class="title-medium weight-bold pt-0 pb-0">
                                                                        <?php echo $dadososconceitos['conceito'];?>
                                                                    </span>
                                                                    <label class="text-medium pt-0 pb-0 weight-bold">
                                                                        <?php
                                                                            echo '('.$dadososconceitos['autor'].')';
                                                                        ?>
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- </a> -->
                                            </div>
                                            <!-- <script>									
                                                function <?php echo $idfrasePrint;?>() {
                                                    var node = document.getElementById('<?php echo $idfrasePrint;?>');

                                                    domtoimage.toPng(node)
                                                        .then(function (dataUrl) {
                                                            var img = new Image();
                                                            img.src = dataUrl;
                                                            downloadURI(dataUrl, "<?php echo $nconceitoPrint;?>.png")
                                                        })
                                                        .catch(function (error) {
                                                            console.error('oops, something went wrong!', error);
                                                        });

                                                }

                                                function downloadURI(uri, name) {
                                                    var link = document.createElement("a");
                                                    link.download = name;
                                                    link.href = uri;
                                                    document.body.appendChild(link);
                                                    link.click();
                                                    document.body.removeChild(link);
                                                    delete link;
                                                }
                                            </script> -->
                                        <?php
                                            endif;
                                            endwhile;
                                        ?>	
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Content End -->
                <div class="printable"></div>
                
                <!-- Pagination Section 3 -->
				<div class="section-block pagination-3 bkg-grey-ultralight pt-30">
					<div class="row">
                        <div class="box rounded bkg-white border-blue small">
                            <div class="column width-12">
                                <?php
                                    include "./_paginacao.php";
                                ?>
                            </div>
						</div>
					</div>
				</div>
				<!-- Pagination Section 3 End -->
            </div>

			<!-- Footer -->
			<footer class="footer footer-blue">
				<? include "./_footer_top.php"; ?>
			</footer>
			<!-- Footer End -->

		</div>
	</div>
    
	<? include "./_script.php"; ?>
    
</body>
</html>