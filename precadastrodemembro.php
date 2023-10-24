<!DOCTYPE html>
<html lang="pt-BR">
    <?php
	session_start();
	include "./_con.php";
	include "./_configuracao.php";
    
    $data = date('d/m/Y');
    
	$v1 = mt_rand(1,10);
    $v2 = rand(1,10);

    $soma = $v1 + $v2;
        //$_SESSION['soma'] = $_POST['soma1'];
       	$s = $_POST['soma1'];

    	//$somado = $_POST['soma'];
        //$_SESSION['somado'] = $_POST['soma'];
        //$s2 = $_SESSION['somado'];
        $s2 = $_POST['soma'];


	if(isset($_POST['btn-cadastrar'])):
		if($s2 === $s):
			$nome0 					= mysqli_escape_string($con, $_POST['nome']);
			$sobrenome 			    = mysqli_escape_string($con, $_POST['sobrenome']);
				// $nomearray	= explode(' ', $nomearray);
				// $nome		= $nomearray[0];
                // $sobrenome  = print_r(array_slice($nomearray, 1));
                
			//$sobrenome 				= mysqli_escape_string($con, $_POST['sobrenome']);
			$sexo 					= mysqli_escape_string($con, $_POST['sexo']);
			$email 					= mysqli_escape_string($con, $_POST['email']);
			$telefonemovel0 			= mysqli_escape_string($con, $_POST['telefonemovel']);
			$whatsapp0 			    = mysqli_escape_string($con, $_POST['whatsapp']);
			$motivo 			    = mysqli_escape_string($con, $_POST['motivo']);
            $declaracao 			= mysqli_escape_string($con, $_POST['declaracao']);
            $status                 = "SOLICITADO";
            $igreja                 = "Emanuel";

            //Criptografia
            $nome = base64_encode($nome0);
            $nome = base64_encode($nome);

            $sobrenome = base64_encode($sobrenome);
            $sobrenome = base64_encode($sobrenome);

            $telefonemovel = base64_encode($telefonemovel0);
            $telefonemovel = base64_encode($telefonemovel);

            $whatsapp = base64_encode($whatsapp0);
            $whatsapp = base64_encode($whatsapp);


            if($declaracao === "SIM"):
                $declaracao = "SIM,  CONFIRMO QUE PREENCHI O PRÉ CADASTRO DE MEMBRO ESPONTÂNEAMENTE, QUE QUERO SER <strong>DECLARADO MEMBRO DA EMANUEL (IGREJA BATISTA EMANUEL)</strong> E QUE ESTOU CIENTE DE QUE AO CONFIRMAR ESTE FORMULÁRIO AUTORIZO O USO DA MINHA IMAGEM EM TODOS OS VEÍCULOS DE DIVULGAÇÃO, COMUNICAÇÃO E PUBLICIDADE PELA EMANUEL.";
            
                //$igreja 				= mysqli_escape_string($con, $_POST['igreja']);
                //$cargo 					= mysqli_escape_string($con, $_POST['cargo']);
                // $telefonemovel = '';
                $igreja=''; $cargo='';

                    //Buscar número de usuários para evitar duplicados.
                    $bIdCada = "SELECT id FROM membros";
                    $RiD = mysqli_query($con, $bIdCada);
                    $nId=mysqli_num_rows($RiD);

                    $nId = substr($nId, 0, 4);

                $matricula				=   date('yd').mt_rand(01,99);

                $senhadigitada1         = $nId.mt_rand(001, 999); //Cria uma senha aleatória para o membro depois alterar.
                $senhadigitada2         = $senhadigitada1;        //Cria uma senha aleatória para o membro depois alterar.

                //$senhadigitada1			= mysqli_escape_string($con, $_POST['senha1']);
                //$senhadigitada2			= mysqli_escape_string($con, $_POST['senha2']);

                //$senhasegura			= 	mt_rand(0, 99).$nId;
                if($senhadigitada1 === $senhadigitada2):
                    $senha				=	password_hash($senhadigitada1, PASSWORD_BCRYPT);;
                    $validaate				=	date('d/m/Y', strtotime(date('Y-m-d'). ' + 6 months'));

                    $duplicado="SELECT id FROM membros WHERE email='$email' OR nome='$nome0' AND sobrenome='$sobrenome' AND sexo='$sexo' AND telefonemovel='$telefonemovel'";
                        $resul=mysqli_query($con, $duplicado);
                            $qnt=mysqli_num_rows($resul);

                    if($qnt > 0):
                        $_SESSION['mensagem']="red";
                        $_SESSION['mensagem']="Este e-mail está cadastrado em nosso sistema.";
                    else:
                        // if():
                            $inserir="INSERT INTO membros (fotodeperfil, nome, sobrenome, descricaodomembro, nascimento, sexo, email, cpf, rg, telefoneresidencial, telefonemovel, whatsapp, endereco, cep, uf, cidade, pai, mae, estadocivil, grauescolar, formadoem, profissao, pisosalarial, igreja, frequentaaemanueldesde, ministerio, funcaoadministrativa, rededepartamento, desejaservirnaigreja, serviremqualarea, pertenceaqualcelula, matriculadacelula, declaracaodemembro, motivo, numerodefamiliaresnaemanuel, datadobatismo, igrejadebatismo, batizadonoespiritosanto, premium, statusdopagamento, matricula, senha, validaate, statusdomembro, midiasocial1, link1, midiasocial2, link2, midiasocial3, link3, midiasocial4, link4, qrcode, membrodocampus, codigodocampus) VALUES ('', '$nome', '$sobrenome', '', '', '$sexo', '$email', '', '', '', '$telefonemovel', '$whatsapp', '', '', '', '', '', '', '', '', '', '', '', '$igreja', '', '', '', '', '', '', '', '', '$declaracao', '$motivo', '', '', '', '', '', '', '$matricula', '$senha', '$validaate', '$status', '', '', '', '', '', '', '', '', '', '', '')";

                        if(mysqli_query($con, $inserir)):    
                            //Email teste
                            include "./_enviaremail.php";
                            $mail->addAddress("$email", "$nome0");     // Add a recipient 
                                //$mail->addAddress("ellen@example.com");               // Name is optional
                                //$mail->addReplyTo("info@example.com", "Information");
                                //$mail->addCC("cc@example.com");
                                //$mail->addBCC("grupormd+g5f5dsztwcbzrbnxf0xs@boards.trello.com");

                                //$mail->addAttachment("/var/tmp/file.tar.gz");         // Add attachments
                                //$mail->addAttachment("/tmp/image.jpg", "new.jpg");    // Optional name
                            $mail->isHTML(true);                                  // Set email format to HTML

                            $mail->Subject = "Bem vindo!";
                            $mail->Body    = "Olá $nome0. 
                                            <p>Sua solicitação de membro foi finalizada com sucesso. Em breve nossa secretaria entrará em contato através do e-mail, pessoalmente ou por telefone para finalizar o processo de membro.</p>
                                            <p>Está é sua matrícula: $matricula</p>
                                            <p><strong>E sua senha provisória: $senhadigitada1</strong></p>
                                            <p>Após nosso contato será possível acessar sua área de membro.</p>
                                            <p>Estou feliz por sua chegada ao nosso ministério, vamos!</p>
                                            <p>Pastor Rafael Vieira</p>
                                            <p<h1>Cristo é o Senhor!</p>
                                            ";
                            $mail->AltBody = "Bem vindo."; // Não é visualizado pelo usuário!
                            if($mail->send()):
                                //Email teste
                                include "./_enviaremail.php";
                                $mail->addAddress("contato@igrejaemanuel.com.br", "Secretaria");     // Add a recipient 
                                    //$mail->addAddress("ellen@example.com");               // Name is optional
                                    //$mail->addReplyTo("info@example.com", "Information");
                                    //$mail->addCC("cc@example.com");
                                    //$mail->addBCC("grupormd+g5f5dsztwcbzrbnxf0xs@boards.trello.com");

                                    //$mail->addAttachment("/var/tmp/file.tar.gz");         // Add attachments
                                    //$mail->addAttachment("/tmp/image.jpg", "new.jpg");    // Optional name
                                $mail->isHTML(true);                                  // Set email format to HTML

                                $mail->Subject = "Solicitação de membro - $nome0";
                                $mail->Body    = "Olá, 
                                                <p>Tem uma solicitação de membro para: $nome0 ($matricula).
                                                <p>Telefone móvel: $telefonemovel0</p>
                                                <p>WhatsApp: $whatsapp0</p>
                                                <p>Solicitação reaizada em: $data</p>
                                                <p<h1>Cristo é o Senhor!</p>
                                                ";
                                $mail->AltBody = "Olá, 
                                                <p>Tem uma solicitação de membro para: $nome0 ($matricula).
                                                <p>Telefone móvel: $telefonemovel0</p>
                                                <p>WhatsApp: $whatsapp0</p>
                                                <p>Solicitação reaizada em: $data</p>
                                                <p><h1>Cristo é o Senhor!</p>"; // Não é visualizado pelo usuário!
                                $mail->send();

                                $_SESSION['cordamensagem']="green";
                                $_SESSION['mensagem']="Bem vindo! Seu cadastro foi feito com sucesso. <strong>Enviamos sua senha para o e-mail cadastrado</strong>, verifique sua caixa de Entrada ou de SPAM.";
                            else:
                                $_SESSION['cordamensagem']="red";
                                $_SESSION['mensagem']="Insira um e-mail válido, não foi possível enviar o e-mail com seu acesso.";
                            endif;
                        else:
                            $_SESSION['cordamensagem']="red";
                            $_SESSION['mensagem']="Infelizmente não conseguimos realizar o cadastro, tente novamente mais tarde.";
                        endif;
                    endif;
                else:
                    $_SESSION['cordamensagem']="red";
                    $_SESSION['mensagem']="As senhas digitadas não são idênticas, favor informar sua senha e repeti-la para confirmar.";
                endif;
            elseif($declaracao === "Não"):
                    $_SESSION['cordamensagem']="red";
                    $_SESSION['mensagem']="Para prosseguir com a solicitação é preciso concordar com a declaração de membro. Queremos muito que você faça parte da nossa igreja, mas compreendemos se optar por não prosseguir com sua requisição de membro.";
            endif;
		else:
			//captcha invalido
            $_SESSION['bot'] = true;
            $_SESSION['cordamensagem']='red';
            $_SESSION['mensagem']='Erro no reCAPTCHA. Informe o valor correto da soma.';
		endif;
	endif;
?>

    <head>
        <? include "./_head.php"; ?>
    </head>

    <body class="shop blog home-page">

        <?php
        // if(isset($_SESSION["membro"])):
            include "./_menu.php";
        // endif;
	?>
        <!-- Content -->
        <div class="content clearfix">
            <div class="section-block tm-slider-parallax-container pb-30 small bkg-blue">
                <div class="row">
                    <div class="box rounded small bkg-white shadow">
                        <div class="column width-12">
                            <div class="title-container">
                                <div class="title-container-inner">
                                    <div class="row flex">
                                        <div class="column width-6 v-align-middle">
                                            <div>
                                                <h1 class="mb-0">Pré cadastro de membro.</h1>
                                                <p class="text-large color-charcoal mb-0 mb-mobile-20">Uma igreja que
                                                    anuncia Jesus como único salvador e busca ser relevante
                                                    culturalmente</p>
                                            </div>
                                        </div>
                                        <div class="column width-6 v-align-middle">
                                            <div>
                                                <ul
                                                    class="breadcrumb inline-block mb-0 pull-right clear-float-on-mobile">
                                                    <li>
                                                        <a href="http://igrejaemanuel.com.br">Inicio</a>
                                                    </li>
                                                    <li>
                                                        Pré-cadastro
                                                    </li>
                                                </ul>
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
                    <?php
                            include "./_notificacaomensagem.php";
                        ?>
                    <div class="box rounded bkg-white shadow">
                        <div class="column width-12">
                            <form class="form" charset="UTF-8" action="<? echo $_SERVER['SCRIPT_URI'];?>" method="post">
                                <div class="row">
                                    <div class="column width-4">
                                        <label class="color-charcoal"><strong>NOME (COMPLETO)</strong></label>
                                        <input type="text" name="nome"
                                            <?php if(isset($_POST['btn-cadastrar'])): echo "value='".$_POST['nome']."'"; endif; ?>
                                            class="form-fname form-element large"
                                            placeholder="Digite seu primeiro nome (Ele estará em seus certificados e documentos da Emanuel)"
                                            tabindex="01" required>
                                    </div>
                                    <div class="column width-8">
                                        <label class="color-charcoal"><strong>SOBRENOME (COMPLETO)</strong></label>
                                        <input type="text" name="sobrenome"
                                            <?php if(isset($_POST['btn-cadastrar'])): echo "value='".$_POST['sobrenome']."'"; endif; ?>
                                            class="form-fname form-element large"
                                            placeholder="Digite seu sobrenome completo (Ele estará em seus certificados e documentos da Emanuel)"
                                            tabindex="01" required>
                                    </div>
                                    <div class="column width-12">
                                        <label class="color-charcoal"><strong>E-MAIL</strong> (Enviaremos sua Senha de
                                            acesso para este e-mail)</label>
                                        <input type="email" name="email"
                                            <?php if(isset($_POST['btn-cadastrar'])): echo "value='".$_POST['email']."'"; endif; ?>
                                            class="form-fname form-element large" placeholder="E-mail principal"
                                            tabindex="02" required>
                                    </div>
                                    <div class="column width-12">
                                        <span class=" color-charcoal title-medium weight-bold pb-10">Sexo.</span>
                                        <div class="form-select form-element rounded medium">
                                            <select name="sexo" tabindex="6" class="form-aux"
                                                data-label="Project Budget">
                                                <option>Feminino</option>
                                                <option>Masculino</option>
                                            </select>
                                        </div>
                                        <div class="column width-4">
                                            <label class="color-charcoal">TEL. <strong>MÓVEL</strong></label>
                                            <input type="tel" name="telefonemovel"
                                                <?php if(isset($_POST['btn-cadastrar'])): echo "value='".$_POST['telefonemovel']."'"; endif; ?>
                                                class="form-fname form-element large" placeholder="21 00001111"
                                                tabindex="02" required>
                                        </div>
                                        <div class="column width-4">
                                            <label class="color-charcoal"><strong>WHATSAPP</strong></label>
                                            <input type="tel" name="whatsapp"
                                                <?php if(isset($_POST['btn-cadastrar'])): echo "value='".$_POST['whatsapp']."'"; endif; ?>
                                                class="form-fname form-element large" placeholder="21 00001111"
                                                tabindex="02">
                                        </div>
                                        <div class="column width-12">
                                            <span class=" color-charcoal title-medium weight-bold pb-10">Por que deseja
                                                ser membro da Emanuel?</span>
                                            <div class="form-select form-element rounded medium">
                                                <select name="motivo" tabindex="6" class="form-aux"
                                                    data-label="Project Budget">
                                                    <?php
                                                        if(isset($_POST['btn-cadastrar'])): 
                                                            if($_POST['motivo'] == "Conversão"):
                                                                echo "<option value='Conversão' selected='selected'>Aceitei Jesus na Emanuel.</option>";
                                                            elseif($_POST['motivo'] == "Retorno"):
                                                                echo "<option value='Retorno' selected='selected'>
                                                                    Retornei para Cristo na Emanuel
                                                                </option>";
                                                            elseif($_POST['motivo'] == "Migração"):
                                                                echo "<option value='Retorno' selected='selected'>
                                                                    Visitei e quero fazer parte da Emanuel.
                                                                </option>";
                                                            endif;
                                                        endif;
                                                    ?>
                                                    <option value="Conversão">Aceitei Jesus na Emanuel.</option>
                                                    <option value="Retorno">Retornei para Cristo na Emanuel</option>
                                                    <option value="Migração">Visitei e quero fazer parte da Emanuel.
                                                    </option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="column width-12">
                                            <span class="color-black">
                                                VOCÊ CONFIRMA QUE PREENCHEU ESTE PRÉ CADASTRO DE MEMBRO ESPONTÂNEAMENTE,
                                                QUE QUER SER <strong>DECLARADO MEMBRO DA EMANUEL (IGREJA BATISTA
                                                    EMANUEL)</strong> E QUE ESTÁ CIENTE DE QUE AO CONFIRMAR ESTE
                                                FORMULÁRIO AUTORIZARÁ O USO DA SUA IMAGEM EM TODOS OS VEÍCULOS DE
                                                DIVULGAÇÃO, COMUNICAÇÃO E PUBLICIDADE PELA EMANUEL.
                                            </span>

                                            <div class="field-wrapper pt-10 pb-10">
                                                <input id="radio-sim" class="form-element radio rounded"
                                                    name="declaracao" value="SIM" type="radio" required>
                                                <label for="radio-sim" class="radio-label color-black" tabindex="6">
                                                    Sim
                                                </label>

                                                <input id="radio-nao" class="form-element radio rounded"
                                                    name="declaracao" value="Não" type="radio" checked>
                                                <label for="radio-nao" class="radio-label color-black" tabindex="6">
                                                    Não
                                                </label>
                                            </div>
                                            <div class="column width-12 border-theme pt-20 pb-20">
                                                <label class="left color-charcoal">PARA FINALIZAR <strong>RESOLVA O
                                                        CÁLCULO ABAIXO.</strong></label>
                                                <div class="field-wrapper">
                                                    <div class="column width-2 pt-20">
                                                        <h3 class="right">
                                                            <?php echo $v1.' + '.$v2.' = ';?>
                                                        </h3>
                                                    </div>
                                                    <div class="column width-10 left">
                                                        <div class="field-wrapper">
                                                            <input type="hidden" name="soma1"
                                                                class="form-name form-element medium"
                                                                value="<?php $somaa = $v1+$v2; echo $somaa;?>">
                                                            <input type="number" name="soma"
                                                                class="form-name form-element medium"
                                                                placeholder="Informar valor" tabindex="03" required>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="column width-12 pt-20">
                                                <button tabindex="04" type="submit" name="btn-cadastrar"
                                                    class="form-submit button small rounded bkg-blue bkg-hover-blue-light color-white color-hover-white hard-shadow"><span
                                                        class="text-large color-white">Finalizar</span></button>
                                                <!-- <a tabindex="05" href="<?php echo $loginUrl; ?>" class="form-submit button small bkg-facebook bkg-hover-facebook rounded color-white color-hover-white hard-shadow">
                                                <span class="icon-facebook left"></span>  Entrar com Facebook
                                            </a> -->
                                            </div>
                                        </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Content End -->

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