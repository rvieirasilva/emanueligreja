<?php    
	session_start();
    include "./_con.php";
    include "./_configuracao.php";

    $data = date('d/m/Y');
    
	$v1 = mysqli_escape_string($con, $_POST['v1']); //Valor passado pelo formulário.
    $v2 = mysqli_escape_string($con, $_POST['v2']); //Valor passado pelo formulário.

    $soma = $v1 + $v2;
        //$_SESSION['soma'] = $_POST['soma1'];
       	$s = $_POST['soma1'];

    	//$somado = $_POST['soma'];
        //$_SESSION['somado'] = $_POST['soma'];
        //$s2 = $_SESSION['somado'];
        $s2 = $_POST['soma'];


	if(isset($_POST['btn-cadastrar'])):
		if($s2 === $s):
			$nome 					= mysqli_escape_string($con, $_POST['nome']);
			$sobrenome 			    = mysqli_escape_string($con, $_POST['sobrenome']);
				// $nomearray	= explode(' ', $nomearray);
				// $nome		= $nomearray[0];
                // $sobrenome  = print_r(array_slice($nomearray, 1));
                
			//$sobrenome 				= mysqli_escape_string($con, $_POST['sobrenome']);
			$sexo 					= mysqli_escape_string($con, $_POST['sexo']);
			$email 					= mysqli_escape_string($con, $_POST['email']);
			$telefonemovel 			= mysqli_escape_string($con, $_POST['telefonemovel']);
			$whatsapp 			    = mysqli_escape_string($con, $_POST['whatsapp']);
			$conheceucomo 			    = mysqli_escape_string($con, $_POST['conheceu']);
            $declaracao 			= mysqli_escape_string($con, $_POST['declaracao']);
            $status                 = "Ativo";
            $igreja                 = "Emanuel";

            //Criptografia
            $nome = base64_encode($nome);
            $nome = base64_encode($nome);

            $sobrenome = base64_encode($sobrenome);
            $sobrenome = base64_encode($sobrenome);

            $telefonemovel = base64_encode($telefonemovel);
            $telefonemovel = base64_encode($telefonemovel);

            $whatsapp = base64_encode($whatsapp);
            $whatsapp = base64_encode($whatsapp);


            if($declaracao === "SIM" OR $declaracao === "Sim"):
                $declaracao = "SIM, CONFIRMA QUE PREENCHEU ESTE CADASTRO ESPONTÂNEAMENTE, QUE QUER PARTICIPAR DO EVENTO DA  EMANUEL (IGREJA BATISTA EMANUEL) E QUE ESTÁ CIENTE DE QUE AO CONFIRMAR ESTE FORMULÁRIO AUTORIZARÁ O USO DA SUA IMAGEM EM TODOS OS VEÍCULOS DE DIVULGAÇÃO, COMUNICAÇÃO E PUBLICIDADE PELA EMANUEL.";
            
                //$igreja 				= mysqli_escape_string($con, $_POST['igreja']);
                //$cargo 					= mysqli_escape_string($con, $_POST['cargo']);
                // $telefonemovel = ''; 
                $igreja=''; $cargo='';

                    //Buscar número de usuários para evitar duplicados.
                    $bIdCada = "SELECT id FROM visitantes";
                    $RiD = mysqli_query($con, $bIdCada);
                    $nId=mysqli_num_rows($RiD);

                    $nId = substr($nId, 0, 4);

                $matricula				=   date('dmy').mt_rand(001,999);

                $senhadigitada1         = $nId.mt_rand(001, 999); //Cria uma senha aleatória para o membro depois alterar.
                $senhadigitada2         = $senhadigitada1;        //Cria uma senha aleatória para o membro depois alterar.

                //$senhadigitada1			= mysqli_escape_string($con, $_POST['senha1']);
                //$senhadigitada2			= mysqli_escape_string($con, $_POST['senha2']);

                //$senhasegura			= 	mt_rand(0, 99).$nId;
                if($senhadigitada1 === $senhadigitada2):
                    $senha				=	password_hash($senhadigitada1, PASSWORD_BCRYPT);;
                    $validaate				=	date('d/m/Y', strtotime(date('Y-m-d'). ' + 6 months'));

                    $duplicado="SELECT email FROM visitantes WHERE email='$email'";
                        $resul=mysqli_query($con, $duplicado);
                            $qnt=mysqli_num_rows($resul);

                    if($qnt > 0):
                        $_SESSION['mensagem']="red";
                        $_SESSION['mensagem']="Este e-mail está cadastrado em nosso sistema.";
                        header("Location:https://igrejaemanuel.com.br");
                    else:
                        // if():
                            $inserir="INSERT INTO visitantes (fotodeperfil, nome, sobrenome, descricaodomembro, nascimento, sexo, email, cpf, rg, telefoneresidencial, telefonemovel, whatsapp, endereco, cep, uf, cidade, pai, mae, estadocivil, grauescolar, formadoem, profissao, pisosalarial, igreja, frequentaaemanueldesde, ministerio, funcaoadministrativa, rededepartamento, desejaservirnaigreja, serviremqualarea, pertenceaqualcelula, matriculadacelula, declaracaodemembro, conheceucomo, numerodefamiliaresnaemanuel, datadobatismo, igrejadebatismo, batizadonoespiritosanto, premium, statusdopagamento, matricula, senha, validaate, statusdomembro, midiasocial1, link1, midiasocial2, link2, midiasocial3, link3, midiasocial4, link4, qrcode) VALUES ('', '$nome', '$sobrenome', '', '', '$sexo', '$email', '', '', '', '$telefonemovel', '$whatsapp', '', '', '', '', '', '', '', '', '', '', '', '$igreja', '', '', '', '', '', '', '', '', '$declaracao', '$conheceucomo', '', '', '', '', '', '', '$matricula', '$senha', '$validaate', '$status', '', '', '', '', '', '', '', '', '')";

                        if(mysqli_query($con, $inserir)):  
                            $nome = base64_decode($nome);
                            $nome = base64_decode($nome);

                            //Email teste
                            include "./_enviaremailPay.php";
                            $mail->addAddress("$email", "$nome");     // Add a recipient 
                                //$mail->addAddress("ellen@example.com");               // Name is optional
                                //$mail->addReplyTo("info@example.com", "Information");
                                //$mail->addCC("cc@example.com");
                                //$mail->addBCC("grupormd+g5f5dsztwcbzrbnxf0xs@boards.trello.com");

                                //$mail->addAttachment("/var/tmp/file.tar.gz");         // Add attachments
                                //$mail->addAttachment("/tmp/image.jpg", "new.jpg");    // Optional name
                            $mail->isHTML(true);                                  // Set email format to HTML

                            $mail->Subject = "Bem vindo!";
                            $mail->Body    = "Olá $nome. 
                                            <p>Seu cadastro foi finalizado com sucesso.</p>
                                            <p>Está é sua matrícula: $matricula</p>
                                            <p><strong>E sua senha provisória: $senhadigitada1</strong></p>
                                            <p>Te aguardamos em nossos eventos e desejamos que suas compras em nossa loja te proporcionem crescimento. Todo recurso arrecadado aqui é investido em missões e na manutenção das nossas ações.</p>
                                            <p>Pastor Rafael Vieira</p>
                                            <p<h1>Jesus Cristo é o Senhor!</p>
                                            ";
                            $mail->AltBody = "Olá $nome. 
                                            <p>Seu cadastro foi finalizado com sucesso.</p>
                                            <p>Está é sua matrícula: $matricula</p>
                                            <p>Te aguardamos em nossos eventos e desejamos que suas compras em nossa loja te proporcionem crescimento. Todo recurso arrecadado aqui é investido em missões e na manutenção das nossas ações.</p>
                                            <p>Pastor Rafael Vieira</p>
                                            <p<h1>Jesus Cristo é o Senhor!</p>"; // Não é visualizado pelo usuário!
                            if($mail->send()):
                                $_SESSION['cordamensagem']="green";
                                $_SESSION['mensagem']="Bem vindo! Seu cadastro foi feito com sucesso. <strong>Enviamos sua senha para o e-mail cadastrado</strong>, verifique sua caixa de Entrada ou de SPAM.";
                                // header("Location:https://igrejaemanuel.com.br");

                                //Faz o login
                                $sql = "SELECT * FROM visitantes WHERE matricula='$email' OR email='$email' OR cpf='$email'";
                                $resultado = mysqli_query($con, $sql);
                                  $dusuariologado=mysqli_fetch_array($resultado);
                                    $dados = mysqli_fetch_array($resultado); 

                                    //DADOS DO usuario
                                    $nomeusuario                    = $dusuariologado['nome'];
                                    $sobrenomeusuario               = $dusuariologado['sobrenome'];
                                    $nomedousuario                  = $nomeusuario.' '.$sobrenomeusuario;
                                    $emailusuario                   = $dusuariologado['email'];
                                    $cpfusuario                     = $dusuariologado['cpf'];
                                    $telefonemovelusuario           = $dusuariologado['telefonemovel'];
                                    $igrejausuario                  = $dusuariologado['igreja'];
                                    $cargoeclesiasticousuario       = $dusuariologado['cargoministerial'];
                                    $statusdopagamentousuario       = $dusuariologado['statusdopagamento'];
                                    $matricula                      = $dusuariologado['matricula'];
                                    $matriculausuario               = $dusuariologado['matricula'];
                                    $validoateusuario               = $dusuariologado['validoate'];

                                    $tempodesessao                  = 4320000; //Equivale a segundos.
                                    $_SESSION['registro']           = time(); //Registra quando o usuário logou.
                                    $_SESSION['limite']             = $tempodesessao;

                                    $_SESSION['visitante']            = true;
                                    $_SESSION["$matricula"]         = $matricula;
                                    $_SESSION["matricula"]          = $matricula;
                                    // $matricula                      = $_SESSION['matricula'];
                                    $_SESSION['id']                 = $dusuariologado['id'];


                                    $token  = date('dmY') . mt_rand(00001, 99999) . time();
                                    $ano    = date('Y');
                                    
                                    //Criação dos tokens. DADOS PARA TOKEN
                                    $segurancaampliada = "INSERT INTO tokens (token, ano, criadopor) VALUES ('$token', '$ano', '$matricula')";
                                    mysqli_query($con, $segurancaampliada);

                                    //Criar sessão com token inicial
                                    $buscartoken                    = "SELECT token FROM tokens GROUP BY token ORDER BY rand(), id DESC LIMIT 1";
                                    $rbuscartoken                   = mysqli_query($con, $buscartoken);
                                        $dbuscartoken                   = mysqli_fetch_array($rbuscartoken);

                                    $token                          = $dbuscartoken['token'];

                                    $_SESSION['token']              = $dbuscartoken['token'];
                                    $token                          = $_SESSION['token'];
                                    $tempodotoken                   = 10; //em segundos.
                                    $_SESSION['limitedotoken']      = $tempodotoken;

                                    //Criar sessão para proteger conta do usuario.
                                    $_SESSION["$matricula"]         = $matricula;
                                    $_SESSION['visitante']            = true;
                                    $_SESSION['matricula']          = $dusuariologado['matricula'];
                                    
                                    if(isset($_SESSION['codigodoprodutoescolhido'])):
                                        $codigodoproduto = $_SESSION['codigodoprodutoescolhido'];
                                        header("Location:details?m=$matricula&token=$token&cp=$codigodoproduto");
                                    else:
                                        header("location:loja?token=$token&m=$matricula");
                                    endif;
                            else:
                                $_SESSION['cordamensagem']="red";
                                $_SESSION['mensagem']="Insira um e-mail válido, não foi possível enviar o e-mail com seu acesso.";
                                header("Location:https://igrejaemanuel.com.br");
                            endif;
                        else:
                            $_SESSION['cordamensagem']="red";
                            $_SESSION['mensagem']="Infelizmente não conseguimos realizar o cadastro, tente novamente mais tarde.";
                            header("Location:https://igrejaemanuel.com.br");
                        endif;
                    endif;
                else:
                    $_SESSION['cordamensagem']="red";
                    $_SESSION['mensagem']="As senhas digitadas não são idênticas, favor informar sua senha e repeti-la para confirmar.";
                    header("Location:https://igrejaemanuel.com.br");
                endif;
            else:
                $_SESSION['cordamensagem']="red";
                $_SESSION['mensagem']="Para prosseguir com a solicitação é preciso concordar com a declaração de membro. Queremos muito que você faça parte da nossa igreja, mas compreendemos se optar por não prosseguir com sua requisição de membro.";
                header("Location:https://igrejaemanuel.com.br");
            endif;
		else:
			//captcha invalido
            $_SESSION['bot'] = true;
            $_SESSION['cordamensagem']='red';
            $_SESSION['mensagem']='Erro no reCAPTCHA. Informe o valor correto da soma.';
            header("Location:https://igrejaemanuel.com.br");
		endif;
	endif;
