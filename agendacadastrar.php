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

		$autenticacoes					= mysqli_escape_string($con, $_POST['autenticacoes']);
            if($autenticacoes === 'Sim'):
                $autenticacaodoresponsavel= "$codigodoevento, Liberado no Slack";
                $autenticacaodoliderderede= "$codigodoevento, Liberado no Slack";
                $autenticacaodopresidente= "$codigodoevento, Liberado no Slack";
                $autenticacaodatesouraria= "$codigodoevento, Liberado no Slack";
            else:
                $autenticacaodoresponsavel='';
                $autenticacaodoliderderede='';
                $autenticacaodopresidente='';
                $autenticacaodatesouraria='';
            endif;
		$liderdoevento					= mysqli_escape_string($con, $_POST['liderdoevento']);
		$redeoudepartamento				= mysqli_escape_string($con, $_POST['redeoudepartamento']);
		$razao							= mysqli_escape_string($con, $_POST['razao']);
		$emailtrello					= mysqli_escape_string($con, $_POST['emailtrello']);
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
		$visibilidade					= mysqli_escape_string($con, $_POST['visibilidade']);
		$alimentos						= mysqli_escape_string($con, $_POST['alimentos']);

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
				
				move_uploaded_file($temporariominiatura, $miniatura);
			endif;
		endif;

		if(empty($miniatura)):
			$miniatura=$capa;
        endif;
        if(empty($capa)):
			$capa=$miniatura;
		endif;
		
		$bED="SELECT id FROM agenda WHERE evento='$evento' AND datadoinicio='$datadoinicio' AND horariodeinicio='$horariodeinicio' AND datadotermino='$datadotermino' AND endereco='$endereco' AND valor='$valor'";
		  $rED=mysqli_query($con, $bED);
			$nED=mysqli_num_rows($rED);
		
		if($nED > 0):
			$_SESSION['cordamensagem']="red";
			$_SESSION['mensagem']="Este evento foi registrado anteriormente.";
		else:
            $inserirminiaturas= "INSERT INTO agenda (dia, mes, ano, capa, video, miniatura, evento, codigodoevento, datadoinicio, horariodeinicio, datadotermino, horariodetermino, descricaodoevento, valor, endereco, numero, estado, cidade, cep, localdehospedagem, checkin, visibilidade, responsavel, rede, razaodoevento, custoprevisto, arrecadadoemdoacao, valorgastoreal, autenticacaodoresponsavel, autenticacaodoliderderede, autenticacaodopresidente, autenticacaodatesouraria, anexo, alimentos, emailtrello) VALUES ('$dia', '$mesnumero', '$ano', '$capa', '$video', '$miniatura', '$evento', '$codigodoevento', '$datadoinicio', '$horadeinicio', '$datadotermino', '$horadetermino', '$descricao', '$valor', '$endereco', '$numero', '$estado', '$cidade', '$cep', '$localdehospedagem', '$checkin', '$visibilidade', '$liderdoevento', '$redeoudepartamento', '$razao','$valorprevisto', '$valordoado', '', '$autenticacaodoresponsavel', '$autenticacaodoliderderede', '$autenticacaodopresidente', '$autenticacaodatesouraria', '$anexo', '$alimentos', '$emailtrello')";
			
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
				if(!empty($_FILES['capa']['name'])):
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
                    <div id="plev" class="section-block team-2 pt-50 bkg-grey-ultralight">
                        <div class="row">
                            <?php include "./_notificacaomensagem.php"; ?>
                            <div class="box rounded rounded shadow border-blue bkg-white">
                                <div class="column width-12">
                                    <div class="contact-form-container">
                                        <form class="form" action="" charset="UTF-8" method="post" enctype="Multipart/form-data">
                                            <div class="row">
                                                <div class="column width-12">
                                                    <span class="color-black"><strong>E-mail do card no Trello</strong> (As inscrições serão informadas no card.)</span>
                                                    <div class="field-wrapper">
                                                        <input type="text"  name="emailtrello" maxlength="100" class="form-aux form-date form-element large" placeholder="E-mail do card no Trello (As inscrições serão informadas no card.)" tabindex="">
                                                    </div>
                                                </div>
                                                <div class="column width-4">
                                                    <span class="color-black"><strong>Responsável pelo evento</strong></span>
                                                    <div class="form-select form-element large">
                                                        <select name="liderdoevento" tabindex="32" class="form-aux" data-label="lider">
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
                                                            <option value="Igreja Emanuel">Emanuel</option>
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
                                                        <input type="text"  name="razao" maxlength="100" class="form-aux form-date form-element large" placeholder="Título do texto" tabindex="5">
                                                    </div>
                                                </div>
                                                <div class="column width-4">
                                                    <span class="color-black"><strong>Custo previsto</strong> para realização R$.</span>
                                                    <div class="field-wrapper">
                                                        <input type="text"  name="valorprevisto" maxlength="100" class="form-aux form-date form-element large" placeholder="0,00" tabindex="5">
                                                    </div>
                                                </div>
                                                <div class="column width-4">
                                                    <span class="color-black">Valor arrecadado em <strong>doação</strong> R$.</span>
                                                    <div class="field-wrapper">
                                                        <input type="text"  name="valordoado" maxlength="100" class="form-aux form-date form-element large" placeholder="0,00" tabindex="5">
                                                    </div>
                                                </div>
                                                <div class="column width-4">
                                                    <span class="color-black"><strong>Valor do ingresso.</strong> (R$)</span>
                                                    <div class="field-wrapper">
                                                        <input type="text"  name="valor" maxlength="100" class="form-aux form-date form-element large" placeholder="0,00" tabindex="5">
                                                    </div>
                                                </div>
                                                <div class="column width-12">
                                                    <span class="color-black"><strong>Título do evento.</strong></span>
                                                    <div class="field-wrapper">
                                                        <input type="text"  name="evento" maxlength="100" class="form-aux form-date form-element large" placeholder="Título do texto" tabindex="5">
                                                    </div>
                                                </div>
                                                <div class="column width-3">
                                                    <span class="color-black">Data de <strong>inicio.</strong></span>
                                                    <div class="field-wrapper">
                                                        <input type="date"  name="datadoinicio" value="<?php echo $ProgramarPostaPara;?>" class="form-aux form-date form-element large"  tabindex="5">
                                                    </div>
                                                </div>
                                                <div class="column width-3">
                                                    <span class="color-black">Horário de <strong>inicio</strong></span>
                                                    <div class="field-wrapper">
                                                        <input type="time"  name="horadeinicio" value="<?php echo $ProgramarPostaParaHorario;?>" class="form-aux form-date form-element large"  tabindex="5">
                                                    </div>
                                                </div>
                                                <div class="column width-3">
                                                    <span class="color-black">Data de <strong>término.</strong></span>
                                                    <div class="field-wrapper">
                                                        <input type="date"  name="datadotermino" value="<?php echo $ProgramarPostaPara;?>" class="form-aux form-date form-element large"  tabindex="5">
                                                    </div>
                                                </div>
                                                <div class="column width-3">
                                                    <span class="color-black">Horário de <strong>término</strong></span>
                                                    <div class="field-wrapper">
                                                        <input type="time"  name="horadetermino" value="<?php echo $ProgramarPostaParaHorario;?>" class="form-aux form-date form-element large"  tabindex="5">
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
                                                        <input type="text" name="videoyoutube" class="form-aux form-date form-element large" maxlength="150" placeholder="*** 7xSpHlV3ruY" tabindex="5">
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
                                                        <input type="text" name="endereco" class="form-aux form-date form-element large" maxlength="150" placeholder="" tabindex="5">
                                                    </div>
                                                </div>
                                                <div class="column width-3">
                                                    <span class="color-black"><strong>Número</strong></span>
                                                    <div class="field-wrapper">
                                                        <input type="number" name="numero" class="form-aux form-date form-element large" maxlength="150" placeholder="" tabindex="5">
                                                    </div>
                                                </div>
                                                <div class="column width-3">
                                                    <span class="color-black"><strong>CEP</strong></span>
                                                    <div class="field-wrapper">
                                                        <input type="number" name="cep" class="form-aux form-date form-element large" maxlength="150" placeholder="" tabindex="5">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="column width-3">
                                                    <span class="color-black"><strong>Estado</strong></span>
                                                    <div class="form-select form-element large">
                                                        <select name="estado" tabindex="32" class="form-aux" data-label="sexo">
                                                            <?php
                                                                $bLider="SELECT nome FROM estados WHERE uf='RJ' GROUP BY nome";
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
                                                            <?php
                                                                $bLider="SELECT nome FROM cidades WHERE estado='19' GROUP BY nome";
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
                                                        <input type="text" name="localdehospedagem" class="form-aux form-date form-element large" maxlength="150" placeholder="Local de hospedagem" tabindex="5">
                                                    </div>
                                                </div>
                                                <div class="column width-3">
                                                    <span class="color-black"><strong>Check-in</strong></span>
                                                    <div class="field-wrapper">
                                                        <input type="text" name="checkin" class="form-aux form-date form-element large" maxlength="150" placeholder="Local de hospedagem" tabindex="5">
                                                    </div>
                                                </div>
                                                <div class="column width-12">
                                                    <span class="color-black">O inscrito precisará levar algum <strong>alimento</strong>? (Escreva os alimentos separados por virgula e quantidade com um traço ao lado. Exemplo; "alimento - 2")</span>
                                                    <div class="field-wrapper">
                                                        <textarea type="text" name="alimentos" class="form-aux form-date form-element large" maxlength="1000" placeholder="(Escreva os alimentos separados por virgula e quantidade com um traço ao lado. Exemplo; 'alimento - 2')" tabindex="5"></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="column width-12">
                                                    <span class="color-black text-small"><strong>Descrição</strong> do Evento.</span>
                                                    <div class="field-wrapper">
                                                        <textarea cols="20" rows="150" name="txtArtigo" id="txtArtigo" class="form-message form-element large" placeholder="Descrição do evento. (Até 5.000 caracteres)." tabindex="36" ></textarea>
                                                    </div>
                                                </div>
                                                <br>
                                                <div class="column width-3">
                                                    <span class="color-black"><strong>Autenticacao</strong></span>
                                                    <div class="form-select form-element medium">
                                                        <select name="autenticacoes" tabindex="32" class="form-aux" data-label="sexo">
                                                            <option value="Não">Aguardar liberação no site</option>
                                                            <option value="Sim">As liberações foram realizadas no Slack</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="column width-3">
                                                    <span class="color-black"><strong>Visibilidade</strong></span>
                                                    <div class="form-select form-element medium">
                                                        <select name="visibilidade" tabindex="32" class="form-aux" data-label="sexo">
                                                            <option value="Externo">Externo</option>
                                                            <option value="Interno">Interno (Só para os membros)</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="column width-6">
                                                    <span class="color-black">ANEXO</span>
                                                    <div class="field-wrapper">
                                                        <input type="file" name="anexo" class=" color-white color-hover-white small bkg-blue bkg-hover-blue-light pill" placeholder="portfolio" tabindex="5">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="column width-12">
                                                    <button type="submit" name="btn-finalizar" class="form-submit button pill small border-theme bkg-hover-theme color-theme color-hover-white">PUBLICAR</button>
                                                    <button type="button" id="btnPrintPlev" class="form-submit button pill small bkg-base bkg-hover-base color-charcoal color-hover-charcoal ">
                                                        GERAR <strong>PLEV</strong> EM PDF
                                                    </button>
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
            </div>

			<!-- Footer -->
			<footer class="footer footer-blue">
				<? include "./_footer_top.php"; ?>
			</footer>
			<!-- Footer End -->

		</div>
	</div>
                <div class="printable"></div>
	<? include "./_script.php"; ?>
    
    <script src="js/printThis.js"></script>
    <script type="text/javascript">
    $(document).ready(function() {
        $("#btnPrintPlev").click(function() {
            //get the modal box content and load it into the printable div
            $(".printable").html($("#plev").html());
            $(".printable").printThis();
        });
    });
    </script>
</body>
</html>