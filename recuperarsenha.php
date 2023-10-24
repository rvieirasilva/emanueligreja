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

        
        if(isset($_POST['btn-recuperarsenhamembro'])):
            $emaildigitado		= $_POST['email'];
    
                $buscaremail = "SELECT nome, email, matricula, id FROM membros WHERE NOT (matricula ='') AND email='$emaildigitado' ORDER BY id DESC LIMIT 1";
                $rbuscaremail=mysqli_query($con, $buscaremail);
                    $dbuscaremail=mysqli_fetch_array($rbuscaremail);
                        $emailencontrado = $dbuscaremail['email'];
    
            if($emailencontrado === $emaildigitado):
                $nomdocolaborador						=	$dbuscaremail['nome'];
                $matriculaparatrocarsenha		        = $dbuscaremail['matricula'];
                $idpararecuperar						=	$dbuscaremail['id'];
                    $validoate							= date('Y-m-d', strtotime(date('Y-m-d'). ' + 1 day'));
    
                //Segurança compartilhada
                $buscartoken = "SELECT token FROM tokens GROUP BY token ORDER BY rand(), id DESC LIMIT 1";
                $rbuscartoken = mysqli_query($con, $buscartoken);
                    $dbuscartoken=	mysqli_fetch_array($rbuscartoken);
                        $token		=	$dbuscartoken['token'];
                        
                //Email com o link para trocar senha.
                include "_enviaremail.php"; //Traz página com as configurações para envio do e-mail.
                $mail->addAddress("$emailencontrado", "$nomedocolaborador");     // Add a recipient 
                    //$mail->addAddress("ellen@example.com");               // Name is optional
                    //$mail->addReplyTo("info@example.com", "Information");
                    //$mail->addCC("cc@example.com");
                    //$mail->addBCC("bcc@example.com");
    
                    //$mail->addAttachment("/var/tmp/file.tar.gz");         // Add attachments
                    //$mail->addAttachment("/tmp/image.jpg", "new.jpg");    // Optional name
                $mail->isHTML(true);                                  // Set email format to HTML
    
                $mail->Subject = "Alterar Senha";
                $mail->Body    = "Olá $nomedocolaborador.
                                <p>Sua solicitação para alterar senha foi atendida.</p>
                                <p>Este é o link <strong>Válido por 24h</strong> para você poder alterar sua senha</p>
                                <a href='https://igrejaemanuel.com.br/recuperarsenha?ref=".$matriculaparatrocarsenha."&id=".$idpararecuperar."&v=".$validoate."&token=".$token."' class='button'>Clique para alterar Senha.</a>
                                <p>Se preferir copie e cole o link no seu navegador</p>
                                <p><a href='https://igrejaemanuel.com.br/recuperarsenha?ref=".$matriculaparatrocarsenha."&id=".$idpararecuperar."&v=".$validoate."&token=".$token."'></a></p>
                                <p>https://igrejaemanuel.com.br/recuperarsenha?ref=".$matriculaparatrocarsenha."&id=".$idpararecuperar."&v=".$validoate."&token=".$token."</p>
                                <p></p>";
                $mail->AltBody = "Mensagem da Igreja Emanuel"; // Não é visualizado pelo usuário!

                if($mail->send()):

                    $_SESSION['cordamensagem']="green";
                    $_SESSION['mensagem']="As instruções para recuperar sua senha foram enviadas por e-mail.";
                    header("location:index");
                else:
                    $_SESSION['cordamensagem']="red";
                    $_SESSION['mensagem']="Não foi possível enviar as instruções para recuperar sua senha, tente novamente mais tarde.";
                    header("location:index");
                endif;
            else:
                    $_SESSION['cordamensagem']="red";
                    $_SESSION['mensagem']="Não conseguimos validar suas informações para recuperação do e-mail";
                header("location:index");
            endif;
        endif;
        
        
        $matriculaparatrocarsenha       = $_GET['ref'];
        // $idpararecuperar                = $_GET['idpararecuperar'];
        // $validoate                      = $_GET['validoate'];
    
        // Confirma as senhas preenchidas, criptografa e faz a atualização.
        if (isset($_POST['btn-recuperarsenha'])):
            $novasenha      =     mysqli_escape_string($con, $_POST['novasenha1']);
            $novasenhaconf  =     mysqli_escape_string($con, $_POST['novasenha2']);
            //$id       =     mysqli_escape_string($con, $_POST['id']);
            $validaate					= date(('Y-m-d'), strtotime(date('Y-m-d'). ' + 6 months'));
                // mysqli_escape_string($con,
            
            if ($novasenha===$novasenhaconf)
            {
                //AQUI CRIA-SE UMA SENHA SEGURA PADRÃO, PARA O RESPONSÁVEL ALTERAR POSTERIORMENTE.
                    $novasenhasegura    = password_hash($novasenha, PASSWORD_BCRYPT);
                        $anoatual       = date('Y');
                    //PARA FAZER A INSERÇÃO É PRECISO O MYSQLI_QUERY // COMO O SQL JÁ POSSUÍA ELE USAVA O ANTECESSOR.
                    $upnovasenha = "UPDATE membros SET senha='$novasenhasegura', validaate='$validaate' WHERE matricula='$matriculaparatrocarsenha'";
                if(mysqli_query($con, $upnovasenha)):
                    //SE OS DADOS FOREM INSERIDOS ELE GRAVA O LOG.
                    //conexão
                    
                    /*  INICIO DA AÇÃO PARA LOG */
                    //AQUI SERÃO CAPTADOS OS DADOS PARA MENSAGEM
                    //DADOS
                    $sqllog = "SELECT email, nome, matricula FROM membros WHERE matricula='$matriculaparatrocarsenha'";
                    $resultadolog= mysqli_query($con, $sqllog);
                    $dadoslog = mysqli_fetch_array($resultadolog);
    
                    //Email confirmando a troca da senha.
                    $email  =   $dadoslog['email']; // Caso não tenha sido resgatado essa informação anteriormente.
                    $colaborador  =   $dadoslog['nome']; // Caso não tenha sido resgatado essa informação anteriormente.
                    include "_enviaremail.php";
                    $mail->addAddress("$email", "$colaborador");     // Add a recipient 
                        //$mail->addAddress("ellen@example.com");               // Name is optional
                        //$mail->addReplyTo("info@example.com", "Information");
                        //$mail->addCC("cc@example.com");
                        //$mail->addBCC("bcc@example.com");
    
                        //$mail->addAttachment("/var/tmp/file.tar.gz");         // Add attachments
                        //$mail->addAttachment("/tmp/image.jpg", "new.jpg");    // Optional name
                    $mail->isHTML(true);                                  // Set email format to HTML
    
                    $mail->Subject = "Recuperação de senha.";
                    $mail->Body    = "Olá $colaborador.
                                    <p>Parabéns sua senha foi alterada com sucesso.</p>
                                    <p>Ela será valida até:".$validaate."</p>
                                    <p><strong>Altere sua senha antes desse prazo</p>
                                    <h1>Cristo é o Senhor</h1>";
                    $mail->AltBody = "Emanuel"; // Não é visualizado pelo usuário!
                    $mail->send();
                    /* */
                
                    $colaborador    			=   $dadoslog['nome'];
                    $data						=	date('d/m/Y');
                    $hora						=	date('G:i:s');
                    $mesdolog					= 	date('m');
                    $matriculalog				=	$dadoslog['matricula'];
                        $ip						=	$_SERVER["REMOTE_ADDR"];
    
                    //Dados para registrar o log do cliente.
                        $mensagem = ("\n$colaborador Alterou sua senha. Isto através do IP: $ip, na data $dia às $hora.\n"); //Escreva a mensagem que será gravada no log.
                        include "./registrolog.php";
                    //Fim do registro do log.  
                   $_SESSION['mensagem'] = "Senha alterada com sucesso."; 
                endif;
            }
                else
                {
                    $_SESSION['mensagem'] = "<strong>Erro!</strong> <br>As senhas informadas não são identicas.";
                }
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
			<!-- Content -->
			<div class="content clearfix">
                <div class="section-block tm-slider-parallax-container pb-0 small bkg-blue">
					<div class="row full-width">
                        <div class="column width-12">
                            <div class="box rounded small bkg-white shadow">
                                <div class="title-container">
                                    <div class="title-container-inner">
                                        <div class="row flex">
                                            <div class="column width-12 v-align-middle">
                                                <div>
                                                    <h1 class="mb-0">Recuperar senha</h1>
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
                        <!-- <div class="box rounded bkg-white shadow"> -->
						<div class="column width-12">
                            <div class="box rounded xlarge shadow bkg-white">
                                <h3>Atualize <strong>sua senha</strong>.</h3>
                                <form charset="UTF-8" class="form" action="" method="POST">
                                    <!--<input type="hidden" name="id" value="<?php //echo $id;?>">-->
                                    <div class="column width-12 color-charcoal">
                                        <label><strong>DIGITE NOVA SENHA</strong></label>
                                        <input required type="password" minlength= "4" maxlength= "30" name="novasenha1" class="form-fname form-element medium" placeholder="Digite sua nova senha.">
                                    </div>
                                    <div class="column width-12 color-charcoal">
                                        <label><strong>CONFIRME NOVA SENHA</strong></label>
                                        <input required type="password" minlength= "4" maxlength= "30" name="novasenha2" class="form-fname form-element medium" placeholder="Confirme sua nova senha.">
                                        <label class="color-red-light">Mínimo de 4 caracteres</label>
                                    </div>
                                    <div class="column width-6">
                                        <p></p>
                                        <button type="submit" name="btn-recuperarsenha" class="form-submit button small pill bkg-blue-light bkg-hover-blue color-white color-hover-white">ATUALIZAR SENHA</button>
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