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

	
	$buscarpost="SELECT * FROM agenda WHERE id='$iddopost' AND codigodoevento='$refdopost'";
		$rbp=mysqli_query($con, $buscarpost);
			$dbp=mysqli_fetch_array($rbp);


	if(isset($_POST['btn-finalizar'])):
		
		$bCodeAgenda="SELECT id FROM agenda";
		  $rCodeAgenda=mysqli_query($con, $bCodeAgenda);
			$nCodeAgenda=mysqli_num_rows($rCodeAgenda);
			
		$codigodoevento       			= $dbp['codigodoevento'];

        $responsavel=mysqli_escape_string($con, $_POST['liderdoevento']);
        $rede=mysqli_escape_string($con, $_POST['redeoudepartamento']);
        $emailtrello=mysqli_escape_string($con, $_POST['emailtrello']);
        $razaodoevento=mysqli_escape_string($con, $_POST['razao']);
        $custoprevisto=mysqli_escape_string($con, $_POST['valorprevisto']);
        $arrecadadoemdoacao=mysqli_escape_string($con, $_POST['valordoado']);
        $valor=mysqli_escape_string($con, $_POST['valor']);
        $alimentos=mysqli_escape_string($con, $_POST['alimentos']);

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
				//move_uploaded_file($temporarioanexo, $pastaanexo.$novoNomeanexo);
			endif;
		endif;	
		
		if(!empty($_FILES['video']['name'])):
			$upvideo = $video;
		elseif(!empty($videoyoutube)):
			$upvideo = $videoyoutube;
		else:
			$upvideo = '';
		endif;
		
		
        $inserirminiaturas= "UPDATE agenda SET responsavel='$responsavel', rede='$rede', razaodoevento='$razaodoevento', custoprevisto='$custoprevisto', arrecadadoemdoacao='$arrecadadoemdoacao', valor='$valor', video='$video', evento='$evento', datadoinicio='$datadoinicio', horariodeinicio='$horadeinicio', datadotermino='$datadotermino', horariodetermino='$horadetermino', descricaodoevento='$descricao', alimentos='$alimentos', valor='$valor', endereco='$endereco', numero='$numero', estado='$estado', cidade='$cidade', cep='$cep', localdehospedagem='$localdehospedagem', checkin='$checkin', emailtrello='$emailtrello' WHERE codigodoevento='$codigodoevento'";
        
        if(mysqli_query($con, $inserirminiaturas)):
            move_uploaded_file($temporariovideo, $pastavideo.$novoNomevideo);
            // move_uploaded_file($temporariocapa, $capa);
            move_uploaded_file($temporarioanexo, $pastaanexo.$novoNomeanexo);
            // move_uploaded_file($temporariominiatura, $miniatura);
            $_SESSION['cordamensagem']="green";
            $_SESSION['mensagem']="Parabéns, evento atualizado.";

            //Dados para registrar o log do cliente.
            $mensagem = ("\n$nomedousuario ($matricula) Postou o evento $evento. Isto através do IP: $ip, na data $dia às $hora.\n"); //Escreva a mensagem que será gravada no log.
            include "./registrolog.php";
            //Fim do registro do log.

            header("refresh:1");
        else:
            $_SESSION['cordamensagem']="red";
            $_SESSION['mensagem']="Erro ao atualizar evento.";
            //Excluir arquivos enviados
            if(!empty($upvideo)):
                unlink($upvideo);
            endif;
            if(!empty($anexo)):
                unlink($anexo);
            endif;
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
	
    //Comando para o modal de alterar miniatura.
	if(isset($_POST['btn-alterarminiatura'])):
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


			function compress_image($Img_miniatura, $miniatura, $quality_miniatura) {
				$info = getimagesize($Img_miniatura);
				if ($info["mime"] == "image/jpeg") $imageMiniatura = imagecreatefromjpeg($Img_miniatura);
				elseif ($info["mime"] == "image/gif") $imageMiniatura = imagecreatefromgif($Img_miniatura);
				elseif ($info["mime"] == "image/png") $imageMiniatura = imagecreatefrompng($Img_miniatura);
				imagejpeg($imageMiniatura, $miniatura, $quality_miniatura);
				return $miniatura;
			}
			$temporariominiatura = compress_image($temporariominiatura, $miniatura, $quality_miniatura);
			//Compacta a miniatura
			
            move_uploaded_file($temporariominiatura, $miniatura);
            
			//Fim da compressão
            $miniatura = "UPDATE agenda SET miniatura='$miniatura' WHERE id='$id_altmini'";
			if (mysqli_query($con, $miniatura)):
				@unlink($miniaturaantiga); //Excluí a miniatura antiga. @ Caso não exista uma não aparecerá o erro.
				
				// move_uploaded_file($temporariominiatura, $miniatura);
					$_SESSION['cordamensagem'] = "green";
            		$_SESSION['mensagem'] = "Miniatura atualizada.";
				
					header("refresh:1; url=agendaeditar?m=$matricula&id=$iddopost&token=$token&ref=$refdopost");
            else:
				unlink($miniatura);
				$_SESSION['cordamensagem'] = "red";
        		$_SESSION['mensagem'] = "Erro ao atualizar miniatura.";
			
				header("refresh:1; url=agendaeditar?m=$matricula&id=$iddopost&token=$token&ref=$refdopost");
			endif;
		else:
			$_SESSION['cordamensagem'] = "red";
			$_SESSION['mensagem'] = "Esse formato de imagem não é permitido. (Formato aceitáveis; JPG, PNG, BITMAP)";
		endif;
	endif;

    
	//Excluir miniatura
	if(isset($_GET['excmini'])):
		$id_MiniExcluir=$_GET['excmini'];
		$refparaexc=$_GET['ref'];
	
		$excluir="DELETE FROM agenda WHERE id='$id_MiniExcluir' AND codigodoevento='$refparaexc'";
		if(mysqli_query($con, $excluir)):
			$_SESSION['cordamensagem']="red";
			$_SESSION['mensagem']="Evento excluido.";
			//Dados para registrar o log do cliente.
			$mensagem = ("\n$nomedousuario ($matricula) Excluíu o evento de código: $refparaexc. Isto através do IP: $ip, na data $dia às $hora.\n"); //Escreva a mensagem que será gravada no log.
			include "./registrolog.php";
            //Fim do registro do log.
            header("location:agendalistar?$linkSeguro");
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
                                                        <h1 class="mb-0">Editar <strong>Evento</strong></h1>
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
                                <a href="./agendalistar?<?echo $linkSeguro;?>">Voltar</a>
                            </div>
                            <div class="box rounded rounded shadow border-blue bkg-white">
                                <?php
                                    //Verificação conforme o número de miniaturas.

                                    $bPostQntd="SELECT id, capa, miniatura FROM agenda WHERE codigodoevento='$refdopost'";
                                    $rPQntd=mysqli_query($con, $bPostQntd);
                                        $nPQntd = mysqli_num_rows($rPQntd);
                                        while($dPQntd=mysqli_fetch_array($rPQntd)):
                                            $mini_miniaturas[]  = $dPQntd['miniatura'];
                                            $mini_id[]			= $dPQntd['id'];
                                        endwhile;

                                    if($nPQntd > 1):
                                        $areaCapa = '12';
                                    else:
                                        $areaCapa = '8';
                                    endif;
                                ?>
                                <div class="row">
                                    <div class="column width-<?php echo $areaCapa;?>">
                                        <h5 class="color-black"><strong>CAPA ATUAL.</strong>
                                        <a data-content="inline" data-aux-classes="tml-newsletter-modal tml-exit-light" data-toolbar="" data-modal-mode data-modal-width="600" data-lightbox-animation="fadeIn" href="#editarcapadopost" class="lightbox-link"><span class="color-black text-small">(ALTERAR CAPA.)</span></a>
                                    </h5>
                                        <img class="center" src="<?php echo $dbp["capa"];?>" width="800" height="400">
                                    </div>

                                    <?php
                                        for($editmini=0; $editmini < count($mini_miniaturas); $editmini++) {
                                    ?>
                                        <div class="column width-4">
                                            <h5 class="color-black"><strong>MINIATURA ATUAL.</strong>
                                                <a href= "agendaeditar?m=<?php echo $matricula;?>&token=<?php echo $token;?>&id=<?php echo $iddopost;?>&ref=<?php echo $refdopost;?>&altmini=<?php echo $mini_id[$editmini];?>" data-content="inline" data-aux-classes="tml-newsletter-modal tml-exit-light" data-toolbar="" data-modal-mode data-modal-width="600" data-lightbox-animation="fadeIn" href="#editarminiaturadopost" class="overlay-link"><span class="color-black text-small">(ALTERAR MINIATURA.)</span></a>
                                            </h5>


                                        <!-- <span class="color-black"> | </span> -->

                                        <!-- <a href= "agendaeditar?m=<?php echo $matricula;?>&token=<?php echo $token;?>&id=<?php echo $iddopost;?>&ref=<?php echo $refdopost;?>&excmini=<?php echo $mini_id[$editmini];?>" class="overlay-link"><span class="color-red">Excluir miniatura</span></a> -->

                                            <img class="<center>" src="<?php echo $mini_miniaturas[$editmini];?>" width="300" height="200">
                                            </div>
                                    <?php
                                        }
                                    ?>
                                </div>
                                <br>
                                <br>
                                <div class="column width-12">
                                    <div class="contact-form-container">
                                        <form class="form" action="" charset="UTF-8" method="post" enctype="Multipart/form-data">
                                            <div class="row">
                                                <div class="column width-12">
                                                    <span class="color-black"><strong>E-mail do card no Trello</strong> (As inscrições serão informadas no card.)</span>
                                                    <div class="field-wrapper">
                                                        <input type="text" value="<? echo $dbp['emailtrello'];?>" name="emailtrello" maxlength="100" class="form-aux form-date form-element large" placeholder="E-mail do card no Trello (As inscrições serão informadas no card.)" tabindex="">
                                                    </div>
                                                </div>
                                                <div class="column width-4">
                                                    <span class="color-black"><strong>Responsável pelo evento</strong></span>
                                                    <div class="form-select form-element large">
                                                        <select name="liderdoevento" tabindex="32" class="form-aux" data-label="lider">
                                                            <option value="<? echo $dbp['responsavel'];?>"><? echo $dbp['responsavel']?></option>
                                                            <?
                                                                $bMembros="SELECT nome, sobrenome, matricula FROM membros WHERE not (matricula = '') GROUP BY matricula";
                                                                $rM=mysqli_query($con, $bMembros);
                                                                    while($dM=mysqli_fetch_array($rM)):
                                                            ?>
                                                            <option value="<?php echo base64_decode(base64_decode($dM['nome'])).' '.base64_decode(base64_decode($dM['sobrenome'])).'~'.$dM['matricula'];?>"><?php echo base64_decode(base64_decode($dM['nome'])).' '.base64_decode(base64_decode($dM['sobrenome']));?></option>
                                                            <?php
                                                                endwhile;
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="column width-4">
                                                    <span class="color-black">Evento da <strong>Rede/Departamento</strong></span>
                                                    <div class="form-select form-element large">
                                                        <select name="redeoudepartamento" tabindex="32" class="form-aux" data-label="lider">
                                                            <option value="<? echo $dbp['rede'];?>"><? echo $dbp['rede'];?></option>
                                                            <?
                                                                $bRedeEvento="SELECT nomedarede, liderdarede, matricula, areadarede FROM rede WHERE NOT (terminodalideranca < '$dataeng') ORDER BY areadarede ASC, nomedarede ASC";
                                                                $rRE=mysqli_query($con, $bRedeEvento);
                                                                    while($dRE=mysqli_fetch_array($rRE)):
                                                            ?>
                                                            <option value="<?php echo $dRE['nomedarede'].' '.$dRE['liderdarede'].'~'.$dRE['matricula'];?>"><?php echo $dRE['areadarede'].' - '.$dRE['nomedarede'].' ('.$dRE['liderdarede'].')';?></option>
                                                            <?php
                                                                endwhile;
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="column width-4">
                                                    <span class="color-black">Qual <strong>Razão</strong> para realização deste evento?</span>
                                                    <div class="field-wrapper">
                                                        <input type="text" value="<? echo $dbp['razaodoevento'];?>" name="razao" maxlength="100" class="form-aux form-date form-element large" placeholder="Título do texto" tabindex="5">
                                                    </div>
                                                </div>
                                                <div class="column width-4">
                                                    <span class="color-black"><strong>Custo previsto</strong> para realização R$.</span>
                                                    <div class="field-wrapper">
                                                        <input type="text"  value="<? echo $dbp['custoprevisto'];?>" name="valorprevisto" maxlength="100" class="form-aux form-date form-element large" placeholder="0,00" tabindex="5">
                                                    </div>
                                                </div>
                                                <div class="column width-4">
                                                    <span class="color-black">Valor arrecadado em <strong>doação</strong> R$.</span>
                                                    <div class="field-wrapper">
                                                        <input type="text"  value="<? echo $dbp['arrecadadoemdoacao'];?>" name="valordoado" maxlength="100" class="form-aux form-date form-element large" placeholder="0,00" tabindex="5">
                                                    </div>
                                                </div>
                                                <div class="column width-4">
                                                    <span class="color-black"><strong>Valor do ingresso.</strong> (R$)</span>
                                                    <div class="field-wrapper">
                                                        <input type="text"  value="<? echo $dbp['valor'];?>" name="valor" maxlength="100" class="form-aux form-date form-element large" placeholder="0,00" tabindex="5">
                                                    </div>
                                                </div>
                                                <div class="column width-12">
                                                    <span class="color-black"><strong>Título do evento.</strong></span>
                                                    <div class="field-wrapper">
                                                        <input type="text" <?php echo "value='".$dbp['evento']."'";?> name="evento" maxlength="100" class="form-aux form-date form-element large" placeholder="Título do texto" tabindex="5">
                                                    </div>
                                                </div>
                                                <div class="column width-3">
                                                    <span class="color-black">Data de <strong>inicio.</strong></span>
                                                    <div class="field-wrapper">
                                                        <input type="date"  <?php echo "value='".$dbp['datadoinicio']."'";?>name="datadoinicio" value="<?php echo $ProgramarPostaPara;?>" class="form-aux form-date form-element large"  tabindex="5">
                                                    </div>
                                                </div>
                                                <div class="column width-3">
                                                    <span class="color-black">Horário de <strong>inicio</strong></span>
                                                    <div class="field-wrapper">
                                                        <input type="time"  <?php echo "value='".$dbp['horariodeinicio']."'";?>name="horadeinicio" value="<?php echo $ProgramarPostaParaHorario;?>" class="form-aux form-date form-element large"  tabindex="5">
                                                    </div>
                                                </div>
                                                <div class="column width-3">
                                                    <span class="color-black">Data de <strong>término.</strong></span>
                                                    <div class="field-wrapper">
                                                        <input type="date"  <?php echo "value='".$dbp['datadotermino']."'";?>name="datadotermino" value="<?php echo $ProgramarPostaPara;?>" class="form-aux form-date form-element large"  tabindex="5">
                                                    </div>
                                                </div>
                                                <div class="column width-3">
                                                    <span class="color-black">Horário de <strong>término</strong></span>
                                                    <div class="field-wrapper">
                                                        <input type="time"  <?php echo "value='".$dbp['horariodetermino']."'";?>name="horadetermino" value="<?php echo $ProgramarPostaParaHorario;?>" class="form-aux form-date form-element large"  tabindex="5">
                                                    </div>
                                                </div>
                                                <div class="column width-6">
                                                    <span class="color-black">VÍDEO <strong>EXCLUSIVO</strong></span>
                                                    <div class="field-wrapper">
                                                        <input type="file" name="video" class=" color-white color-hover-white small bkg-red bkg-hover-red-light rounded" placeholder="portfolio" tabindex="5">
                                                    </div>
                                                </div>
                                                <div class="column width-6">
                                                    <span class="color-black">VÍDEO <strong>DO YOUTUBE</strong></span>
                                                    <div class="field-wrapper">
                                                        <input type="text" <?php echo "value='".$dbp['videoyoutube']."'";?>name="videoyoutube" class="form-aux form-date form-element large" maxlength="150" placeholder="*** 7xSpHlV3ruY" tabindex="5">
                                                    </div>
                                                </div>
                                                <div class="column width-6">
                                                    <span class="color-black">Capa do evento. (2500 x 1200px)</span>
                                                    <div class="field-wrapper">
                                                        <input type="file" name="capa" class="form-aux form-date form-element large" placeholder="portfolio" tabindex="5">
                                                    </div>
                                                </div>
                                                <div class="column width-6">
                                                    <span class="color-black">Miniatura do evento</span>
                                                    <div class="field-wrapper">
                                                        <input type="file" name="miniatura"  class="form-aux form-date form-element large" placeholder="portfolio" tabindex="5">
                                                    </div>
                                                </div>
                                                <div class="column width-6">
                                                    <span class="color-black"><strong>Endereço</strong></span>
                                                    <div class="field-wrapper">
                                                        <input type="text" <?php echo "value='".$dbp['endereco']."'";?>name="endereco" class="form-aux form-date form-element large" maxlength="150" placeholder=" tabindex="5">
                                                    </div>
                                                </div>
                                                <div class="column width-3">
                                                    <span class="color-black"><strong>Número</strong></span>
                                                    <div class="field-wrapper">
                                                        <input type="number" <?php echo "value='".$dbp['numero']."'";?>name="numero" class="form-aux form-date form-element large" maxlength="150" placeholder=" tabindex="5">
                                                    </div>
                                                </div>
                                                <div class="column width-3">
                                                    <span class="color-black"><strong>CEP</strong></span>
                                                    <div class="field-wrapper">
                                                        <input type="number" <?php echo "value='".$dbp['cep']."'";?>name="cep" class="form-aux form-date form-element large" maxlength="150" placeholder=" tabindex="5">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="column width-3">
                                                    <span class="color-black"><strong>Estado</strong></span>
                                                    <div class="form-select form-element large">
                                                        <select name="estado" tabindex="32" class="form-aux" data-label="sexo">
                                                            <option><?php echo $dbp['estado'];?></option>
                                                            <?php
                                                                $bLider="SELECT nome FROM estado WHERE nome='Rio de Janeiro' GROUP BY nome";
                                                                $rLider=mysqli_query($con, $bLider);
                                                                    while($dLider=mysqli_fetch_array($rLider)):
                                                            ?>
                                                            <option><?php echo $dLider['nome'];?></option>
                                                            <?php
                                                                endwhile;
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="column width-3">
                                                    <span class="color-black"><strong>Cidade</strong></span>
                                                    <div class="form-select form-element large">
                                                        <select name="cidade" tabindex="32" class="form-aux" data-label="sexo">
                                                            <option><?php echo $dbp['cidade'];?></option>
                                                            <?php
                                                                $bLider="SELECT nome FROM cidade WHERE estado='19' GROUP BY nome";
                                                                $rLider=mysqli_query($con, $bLider);
                                                                    while($dLider=mysqli_fetch_array($rLider)):
                                                            ?>
                                                            <option><?php echo $dLider['nome'];?></option>
                                                            <?php
                                                                endwhile;
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="column width-3">
                                                    <span class="color-black"><strong>Local de hospedagem</strong></span>
                                                    <div class="field-wrapper">
                                                        <input type="text" <?php echo "value='".$dbp['localdehospedagem']."'";?>name="localdehospedagem" class="form-aux form-date form-element large" maxlength="150" placeholder="Local de hospedagem tabindex="5">
                                                    </div>
                                                </div>
                                                <div class="column width-3">
                                                    <span class="color-black"><strong>Check-in</strong></span>
                                                    <div class="field-wrapper">
                                                        <input type="text" <?php echo "value='".$dbp['checkin']."'";?>name="checkin" class="form-aux form-date form-element large" maxlength="150" placeholder="Local de hospedagem tabindex="5">
                                                    </div>
                                                </div>
                                                <div class="column width-12">
                                                    <span class="color-black">O inscrito precisará levar algum <strong>alimento</strong>? (Escreva os alimentos separados por virgula e quantidade com um traço ao lado. Exemplo; "alimento - 2")</span>
                                                    <div class="field-wrapper">
                                                        <textarea type="text" name="alimentos" class="form-aux form-date form-element large" maxlength="150" placeholder="(Escreva os alimentos separados por virgula e quantidade com um traço ao lado. Exemplo; 'alimento - 2')" tabindex="5"><? echo $dbp['alimentos'];?></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="column width-12">
                                                    <span class="color-black text-small"><strong>Descrição</strong> do Evento.</span>
                                                    <div class="field-wrapper">
                                                        <textarea cols="20" rows="150" name="txtArtigo" id="txtArtigo" class="form-message form-element large" placeholder="Descrição do evento. (Até 5.000 caracteres)." tabindex="36" ><?php echo $dbp['descricaodoevento'];?></textarea>
                                                    </div>
                                                </div>
                                                <br>
                                                <div class="column width-12">
                                                    <span class="color-black">ANEXO</span>
                                                    <div class="field-wrapper">
                                                        <input type="file" name="anexo" class=" color-white color-hover-white small bkg-blue bkg-hover-blue-light pill" placeholder="portfolio" tabindex="5">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <br>
                                                <div class="column width-12">
                                                    <button type="submit" name="btn-finalizar" class="form-submit button pill small border-theme bkg-hover-theme color-theme color-hover-white">Atualizar</button>

                                                    <a href="agendaeditar?m=<?php echo $matricula;?>&token=<?php echo $token;?>&id=<?php echo $iddopost;?>&ref=<?php echo $refdopost;?>&excmini=<?php echo $mini_id[$editmini];?>" class="form-submit button pill small bkg-red bkg-hover-red color-white color-hover-white">
                                                        Excluir
                                                    </a>
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

            <?php
                $MiniGet="SELECT id, miniatura FROM agenda WHERE id='$id_altmini'";
                    $rMG=mysqli_query($con, $MiniGet);
                    $dMG=mysqli_fetch_array($rMG);
            ?>

            <!-- Alterar miniatura Modal End -->
            <div id="editarminiaturadopost" class="section-block pt-0 pb-30 background-none hide">
                
                <!-- Intro Title Section 2 -->
                <div class="thumbnail xsmall">
                    <img src="<?php echo $dMG["miniatura"];?>" width="825" height="400" alt="">
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


		</div>
	</div>

    
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
	<?php
		endif;
	?>
	<a id='minialt' data-content="inline" data-aux-classes="tml-newsletter-modal tml-exit-light" data-toolbar="" data-modal-mode data-modal-width="600" data-lightbox-animation="fadeIn" href="#editarminiaturadopost" class="lightbox-link"><label class="color-black">(ALTERAR MINIATURA.)</label></a>


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