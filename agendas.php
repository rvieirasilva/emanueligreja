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

	// if(isset($_SESSION["lideranca"])):
    //     include "protectcolaborador.php";	
    // elseif(isset($_SESSION["membro"])):
    //     include "protectusuario.php";
    // else:
    //     session_destroy();
    //     header("location:index");
	// endif;

	if(isset($_POST['btn-finalizar'])):
		
		$bCodeAgenda="SELECT id FROM agenda";
		  $rCodeAgenda=mysqli_query($con, $bCodeAgenda);
			$nCodeAgenda=mysqli_num_rows($rCodeAgenda);
			
		$codigodoevento       			= $nCodeAgenda.mt_rand(001, 999);

		$liderdoevento					= mysqli_escape_string($con, $_POST['liderdoevento']);
		$redeoudepartamento				= mysqli_escape_string($con, $_POST['redeoudepartamento']);
		$razao							= mysqli_escape_string($con, $_POST['razao']);
		$valorprevisto					= mysqli_escape_string($con, $_POST['valorprevisto']);
		$valordoado					    = mysqli_escape_string($con, $_POST['valordoado']);

		$evento							= mysqli_escape_string($con, $_POST['evento']);
		$valor					= mysqli_escape_string($con, $_POST['valor']);
			if(empty($valor)): $valor = '0.00'; endif;
		$datadoinicio					= mysqli_escape_string($con, $_POST['datadoinicio']);
		$horadeinicio					= mysqli_escape_string($con, $_POST['horadeinicio']);
			$datadeinicioHora  = $datadoinicio.' '.$horadeinicio;

		$datadotermino					= mysqli_escape_string($con, $_POST['datadotermino']);
		$horadetermino					= mysqli_escape_string($con, $_POST['horadetermino']);
			$datadeterminoHora = $datadotermino.' '.$horadetermino;

		$endereco						= mysqli_escape_string($con, $_POST['endereco']);
		$numero							= mysqli_escape_string($con, $_POST['numero']);
		$cep							= mysqli_escape_string($con, $_POST['cep']);
		$estado							= mysqli_escape_string($con, $_POST['estado']);
		$cidade							= mysqli_escape_string($con, $_POST['cidade']);
		$localdehospedagem				= mysqli_escape_string($con, $_POST['localdehospedagem']);
		$checkin						= mysqli_escape_string($con, $_POST['checkin']);
		$videoyoutube					= mysqli_escape_string($con, $_POST['videoyoutube']);
		$descricao						= mysqli_escape_string($con, $_POST['txtArtigo']);
		$visibilidade						= mysqli_escape_string($con, $_POST['visibilidade']);

		if(!empty($videoyoutube)):
			//Simplificar código retirando ou inserindo embed.
			$videoyoutubeF	= explode('/', $videoyoutube); //https: // youtube.com / embed / w00JkhGoII0
			@$videoyoutube_EMBED = $videoyoutubeF[3];
			@$videoyoutube_URL	 = $videoyoutubeF[2];

			if($videoyoutube_EMBED == 'embed'):
				$videoyoutube	=	$videoyoutube;
			elseif($videoyoutube_URL == 'youtu.be'): //Ver se é https://youtu.be/w00JkhGoII0
				$videoyoutube	= "https://youtube.com/embed/$videoyoutube_EMBED";
			else: //Se for só a URL w00JkhGoII0
				$videoyoutube	= "https://youtube.com/embed/$videoyoutube";
			endif;

		endif;
		
		//VÍDEO EXCLUSIVO
		if(!empty($_FILES['video']['name'])):
			$extensaovideo = pathinfo($_FILES['video']['name'], PATHINFO_EXTENSION);
			if(in_array($extensaovideo, $formatospermitidosvideos)):
				@mkdir("agenda/", 0777); //Cria a pasta se não houver. 
				@mkdir("agenda/$codigodoevento/", 0777); //Cria a pasta se não houver. 
				@mkdir("agenda/$codigodoevento/video/", 0777); //Cria a pasta se não houver. 
				
				$pastavideo			= "agenda/$codigodoevento/video/";
				$temporariovideo	    = $_FILES['video']['tmp_name'];
				$novoNomevideo		= mt_rand(10, 9999).'.'.$extensaovideo;
					$video		= $pastavideo.$novoNomevideo;
				move_uploaded_file($temporariovideo, $pastavideo.$novoNomevideo);
			endif;
		endif;

		//CAPA
		if(!empty($_FILES['capa']['name'])):
			$extensaocapa = pathinfo($_FILES['capa']['name'], PATHINFO_EXTENSION);
			if(in_array($extensaocapa, $formatospermitidosimagens)):
				$img_capa 			= $_FILES["capa"]['name'];

				@mkdir("agenda/", 0777); //Cria a pasta se não houver. 
				@mkdir("agenda/$codigodoevento/", 0777); //Cria a pasta se não houver. 
				@mkdir("agenda/$codigodoevento/capa/", 0777); //Cria a pasta se não houver. 

				$pastacapa			= "agenda/$codigodoevento/capa/";
				$temporariocapa	    = $_FILES['capa']['tmp_name'];
				$tamanhodacapa	    = $_FILES['capa']['size'];
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
					
				$temporariocapa = compress_image($_FILES["capa"]["tmp_name"], $capa, $quality_capa); //Compacta a imagem 
			

				move_uploaded_file($temporariocapa, $capa);
			endif;
		endif;

		//ANEXO
		if(!empty($_FILES['anexo']['name'])):
			$extensaoanexo = pathinfo($_FILES['anexo']['name'], PATHINFO_EXTENSION);
			if(in_array($extensaoanexo, $formatospermitidos)):
				@mkdir("agenda/", 0777); //Cria a pasta se não houver. 
				@mkdir("agenda/$codigodoevento/", 0777); //Cria a pasta se não houver. 
				@mkdir("agenda/$codigodoevento/anexo/", 0777); //Cria a pasta se não houver. 
				$pastaanexo			= "agenda/$codigodoevento/anexo/";
				$temporarioanexo	    = $_FILES['anexo']['tmp_name'];
				$novoNomeanexo		= mt_rand(10,9999).'.'.$extensaoanexo;
					$anexo		= $pastaanexo.$novoNomeanexo;
				move_uploaded_file($temporarioanexo, $anexo);
			endif;
		endif;	
		
		if(!empty($_FILES['video']['name'])):
			$upvideo = $video;
		elseif(!empty($videoyoutube)):
			$upvideo = $videoyoutube;
		else:
			$upvideo = '';
		endif;
		
		if(!empty($_FILES['miniatura']['name'])):
			$extensaominiatura = pathinfo($_FILES['miniatura']['name'], PATHINFO_EXTENSION);
			if(in_array($extensaominiatura, $formatospermitidosimagens)):
				@mkdir("agenda/", 0777); //Cria a pasta se não houver. 
				@mkdir("agenda/$codigodoevento/", 0777); //Cria a pasta se não houver. 
				@mkdir("agenda/$codigodoevento/miniatura/", 0777); //Cria a pasta se não houver. 

				$pastaminiatura			= "agenda/$codigodoevento/miniatura/";

				$Img_miniatura 			= $_FILES['miniatura']['name'];
				$tamanhodaminiatura		= $_FILES['miniatura']['size'];
				$temporariominiatura	= $_FILES['miniatura']['tmp_name'];

				$novoNomeminiatura		= mt_rand(10,9999).'.'.$extensaominiatura;
					$miniatura			= $pastaminiatura.$novoNomeminiatura;

				
					//Compacta a miniatura
					if($tamanhodaminiatura >= 1000000):
						$quality_miniatura = 20;	
					elseif($tamanhodaminiatura >= 5000000 AND $tamanhodaminiatura <= 1000000):
						$quality_miniatura = 40;
					else:	
						$quality_miniatura = 50;	
					endif;


					// function compress_image($Img_miniatura, $miniatura, $quality_miniatura) {
					// 	$info = getimagesize($Img_miniatura);
					// 	if ($info["mime"] == "image/jpeg") $imageMiniatura = imagecreatefromjpeg($Img_miniatura);
					// 	elseif ($info["mime"] == "image/gif") $imageMiniatura = imagecreatefromgif($Img_miniatura);
					// 	elseif ($info["mime"] == "image/png") $imageMiniatura = imagecreatefrompng($Img_miniatura);
					// 	imagejpeg($imageMiniatura, $miniatura, $quality_miniatura);
					// 	return $miniatura;
					// }
					// $temporariominiatura = compress_image($temporariominiatura, $miniatura, $quality_miniatura);
					//Compacta a miniatura

                    function compress($Img_miniatura, $miniatura, $quality_miniatura) {
                        $info = getimagesize($Img_miniatura);
                    
                        if ($info['mime'] == 'image/jpeg') 
                            $image = imagecreatefromjpeg($Img_miniatura);                    
                        elseif ($info['mime'] == 'image/gif') 
                            $image = imagecreatefromgif($Img_miniatura);                    
                        elseif ($info['mime'] == 'image/png') 
                            $image = imagecreatefrompng($Img_miniatura);                    
                        imagejpeg($image, $miniatura, $quality_miniatura);                    
                        return $miniatura;
                    }
                    
                    $source_img = $Img_miniatura;
                    $destination_img = $miniatura;                    
                    $temporariominiatura = compress($source_img, $destination_img, $quality_miniatura);
				
				move_uploaded_file($temporariominiatura, $miniatura);
			endif;
		endif;

		if(empty($miniatura) AND !empty($capa)):
			$miniatura=$capa;
		elseif(empty($capa) AND !empty($miniatura)):
			$capa=$miniatura;
		endif;
		
		$bED="SELECT id FROM agenda WHERE evento='$evento' AND datadoinicio='$datadoinicio' AND horariodeinicio='$horariodeinicio' AND datadotermino='$datadotermino' AND endereco='$endereco' AND valor='$valor'";
		  $rED=mysqli_query($con, $bED);
			$nED=mysqli_num_rows($rED);
		
		if($nED > 0):
			$_SESSION['cordamensagem']="red";
			$_SESSION['mensagem']="Este evento foi registrado anteriormente.";
		else:
            $inserirminiaturas= "INSERT INTO agenda (dia, mes, ano, capa, video, miniatura, evento, codigodoevento, datadoinicio, horariodeinicio, datadotermino, horariodetermino, descricaodoevento, valor, endereco, numero, estado, cidade, cep, localdehospedagem, checkin, visibilidade, responsável, rede, razaodoevento, custoprevisto, arrecadadoemdoacao, valorgastoreal, autenticacaodoresponsavel, autenticacaodoliderderede, autenticacaodopresidente, autenticacaodatesouraria) VALUES ('$dia', '$mesnumero', '$ano', '$capa', '$video', '$miniatura', '$evento', '$codigodoevento', '$datadoinicio', '$horadeinicio', '$datadotermino', '$horadetermino', '$descricao', '$valor', '$endereco', '$numero', '$estado', '$cidade', '$cep', '$localdehospedagem', '$checkin', '$visibilidade', '$liderdoevento', '$redeoudepartamento', '$valorprevisto', '$valordoado', '', '', '', '', '')";
			
			if(mysqli_query($con, $inserirminiaturas)):
				$_SESSION['cordamensagem']="green";
				$_SESSION['mensagem']="Parabéns, evento adicionado.";
				//Dados para registrar o log do cliente.
				$mensagem = ("\n$nomedousuario ($matricula) Postou o evento $evento. Isto através do IP: $ip, na data $dia às $hora.\n"); //Escreva a mensagem que será gravada no log.
				include "./registrolog.php";
				//Fim do registro do log.
			else:
				$_SESSION['cordamensagem']="red";
				$_SESSION['mensagem']="Erro ao criar evento.";

				//Excluir arquivos enviados e não fora registrados no BD
				if(!empty($audio)):
					unlink($audio);
				endif;
				if(!empty($upvideo)):
					unlink($upvideo);
				endif;
				if(!empty($anexo)):
					unlink($anexo);
				endif;
				if(!empty($miniatura)):
					unlink($miniatura);
				endif;
				if(!empty($capa)):
					unlink($capa);
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
                                                        <h1 class="mb-0">Publicar <strong>Evento</strong> na agenda</h1>
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
                                    <table class="table striped non-responsive rounded medium full-width">
                                        <thead>
                                        <tr class="bkg-charcoal color-white">
                                            <th>EVENTO</th>
                                            <th class="center">DATA</th>
                                            <th class="center">VALOR</th>
                                            <th></th>
                                        </tr>
                                        </thead>
                                        <tbody>

                                        <?php
                                            // <!--CÓDIGO PARA FAZER PESQUISA DOS HORÁRIOS E CRIAR O LOOPING -->
                                            $pagina = (isset($_GET['pagina']))? $_GET['pagina'] : 1;

                                                $buscarporposts= "SELECT id FROM agenda WHERE ano='$ano' ORDER BY id DESC";
                                                    $resultadobuscarporposts = mysqli_query($con, $buscarporposts);
                                                    
                                                    $totaldeposts = mysqli_num_rows($resultadobuscarporposts);

                                                    //Defini o número de horarios que serão exibidos por página
                                                    $postsporpagina = 30;

                                                    //defini número de páginas necessárias para exibir todos os horarios.
                                                    $totaldepaginas = ceil($totaldeposts / $postsporpagina);

                                                    $inicio = ($postsporpagina * $pagina) - $postsporpagina;

                                            
                                                //AQUI ELE FAZ A PESQUISA PELO CAMPUS E TRAZ O DADO MAIS RECENTE PELO ANO, SE ESTAMOS EM 2010 ELE TRARÁ OS DADOS DESTE ANO OU DO ÚLTIMO ANO EM QUE O CAMPUS APRESENTOU UMA MATÉRIA REGISTRADA, COM BASE NISSO É FEITO UM FILTRO EXIBINDO POR ORDEM O DIA E DEPOIS O HORÁRIO.
                                                //$postsparaeditar= "SELECT * FROM agenda WHERE autor= '$nomedocolaborador' ORDER BY datadapostagem DESC LIMIT $inicio, $postsporpagina";
                                                $postsparaeditar= "SELECT * FROM agenda group BY codigodoevento ORDER BY datadoinicio DESC LIMIT $inicio, $postsporpagina";
                                                    $resultadopostsparaeditar = mysqli_query($con   , $postsparaeditar);
                                                        while($dadospostsparaeditar = mysqli_fetch_array($resultadopostsparaeditar)):
                                                        
                                                
                                                
                                        ?>
                                            <tr class="">
                                                <!-- O CÓDIGO ABAIXO VERIFICA SE O DIA CORRESPONDE COM A SEGUNDA-FEIRA, PARA COLUNA E TRÁS AS MATÉRIAS PARA DESTES DIAS -->
                                                <td class="color-black text-xsmall" width="60%" valign="medium">
                                                    <?php echo $dadospostsparaeditar['evento'];?> 
                                                </td>
                                                <td class="color-black text-xsmall center" width="13%" valign="medium">
                                                    <?php $datadapostagem=$dadospostsparaeditar['datadoinicio']; echo date('d/M/Y', strtotime($datadapostagem));?> 
                                                </td>
                                                <td class="color-black text-xsmall center" width="12%" valign="medium">
                                                    R$ <?php echo $dadospostsparaeditar['valor'];?> 
                                                </td>
                                                <td width="10%" valign="medium">
                                                    <center><a data-content="inline" data-aux-classes="tml-newsletter-modal height-auto rounded" data-toolbar="" data-modal-mode data-modal-width="600" data-modal-animation="scaleIn" data-lightbox-animation="fadeIn" href="#PLEV<?php echo $dadospostsparaeditar['codigodoevento'];?>" class="lightbox-link button rounded small bkg-theme bkg-hover-theme color-white color-hover-white hard-shadow">Abrir</a></center>
                                                </td>
                                            </tr>
                                            <?php endwhile; ?>
                                        </tbody>
                                    </table>
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
    <?
        if($funcaoadministrativa === 'Presidente'):
            $bPLEV= "SELECT id, evento, codigodoevento, anexo FROM agenda WHERE datadoinicio < '$dataeng' AND autenticacaodopresidente ='' group BY codigodoevento ORDER BY datadoinicio DESC LIMIT $inicio, $postsporpagina";
        elseif($funcaoadministrativa === 'Tesoureiro' OR $funcaoadministrativa === 'Tesoureira'):
            $bPLEV= "SELECT id, evento, codigodoevento, anexo FROM agenda WHERE datadoinicio < '$dataeng' AND autenticacaodatesouraria ='' group BY codigodoevento ORDER BY datadoinicio DESC LIMIT $inicio, $postsporpagina";
        endif;

        $rPLEV = mysqli_query($con   , $bPLEV);
        while($dPLEV = mysqli_fetch_array($rPLEV)):
    ?>
        <div id="PLEV<?echo $dPLEV['codigodoevento'];?>" class="pt-70 pb-50 hide">
            <div class="row">
                <div class="column width-10 offset-1 center">
                    <!-- Info -->
                    <h3 class="mb-10"><? echo $dPLEV['evento'];?></h3>
                    <p class="mb-30"><? echo $dPLEV['codigodoevento'];?></p>
                    <!-- Info End -->

                    <!-- Contact Form -->
                    <div class="contact-form-container">
                        <form class="contact-form" action="" method="post">
                            <div class="row">
                                <a href="./<? echo $dPLEV['anexo'];?>" target="_blank">Abrir arquivo do PLEV</a>
                            </div>
                            <div class="row">
                                <div class="column width-12">
                                    <input type="submit" value="Liberar evento" class="form-submit button rounded medium bkg-theme bkg-hover-theme color-white color-hover-white">
                                </div>
                            </div>
                        </form>
                        <div class="form-response"></div>
                    </div>
                    <!-- Contact Form End -->

                </div>
            </div>
        </div>
    <? endwhile;?>
    
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