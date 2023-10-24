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
    
    $carrinhodocliente=$_GET['car'];
    $codigodoproduto = $_GET['cp'];

    //Buscar dados do produto.
    $dprod   = "SELECT * FROM produtos WHERE codigodoproduto = '$codigodoproduto'";
     $rprod  = mysqli_query($con, $dprod);
      $dprod = mysqli_fetch_array($rprod);
      
          $categoria = $dprod['categoria'];

    // if(empty($carrinhodocliente) AND !isset($_SESSION['detalhesdoproduto'])):
    //     unset($_SESSION['detalhesdoproduto']);
    //     header("location:loja?$linkSeguro");
    // endif;
        
    if(isset($_POST['btn-pagar'])):
        for ($i=0; $i < count($_POST['quantidade']); $i++) { 
            $iddopro = mysqli_escape_string($con, $_POST['id'][$i]);
            $quantidade = mysqli_escape_string($con, $_POST['quantidade'][$i]);
                $pontosdoproduto        = mysqli_escape_string($con, $_POST['pontos'][$i]);
                $pontos = $pontosdoproduto * $quantidade;
                //$pontos = '1.792';

                $valordoproduto        = mysqli_escape_string($con, $_POST['valor'][$i]);
                $valordoproduto        = str_replace(',','.', $valordoproduto); //Troca a virgula por ponto EUA.
                $valor = $valordoproduto * $quantidade;

                //$valordoproduto        = mysqli_escape_string($con, $_POST['valor'][$i]);
                //$valordoproduto        = str_replace('.','', $valordoproduto); //Tira os pontos dos milhares.

            $atualizarquantidade="UPDATE carrinho SET quantidade='$quantidade', valorcomdesconto='$valor', pontos='$pontos' WHERE id='$iddopro'";
            if(mysqli_query($con, $atualizarquantidade)):
                header("location:comprafinalizar?cc=$codigodocliente&token=$token&car=$codigodocarrinho");
            endif;
        }
    endif;

    if(isset($_GET['exc'])):
        $iddoproduto = $_GET['exc'];
        //Excluir produto do carrinho.
        $del="DELETE FROM carrinho WHERE id='$iddoproduto'";
        
        if(mysqli_query($con, $del)):
            $_SESSION['mensagem']="Produto excluído com sucesso.";
            header("refresh:3; url='comprafinalizarcheck?cc=$codigodocliente&token=$token&car=$codigodocarrinho'");
        else:
            $_SESSION['mensagem']="Erro ao excluir produto, verifique sua conexão e tente novamente mais tarde.";
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
			<!-- Content -->
			<div class="content clearfix">
                <div class="section-block tm-slider-parallax-container pb-0 small bkg-blue">
					<div class="row">
                        <div class="box rounded small bkg-white shadow">
						    <div class="column width-12">
                                <div class="title-container">
                                    <div class="title-container-inner">
                                        <div class="row flex">
                                            <div class="column width-12 v-align-middle">
                                                <div>
                                                    <h1 class="mb-0">BEM-VINDO</h1>
                                                    <p class="text-large color-charcoal mb-0 mb-mobile-20">Aqui você encontra nossos conteúdos de forma antecipada, <strong>cursos, artigos e muito mais pra você</strong></p>
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
						<div class="column width-5 offset-1 push-6 ">
							<div>
								<div class="signup-box box rounded xlarge mb-0 bkg-blue border-blue-light">
                                    <h3 class="color-white"><strong>Sou membro</strong> ou possuo uma conta de visitante.</h3>
                                    <div class="register-form-container">
                                        <form class="login-form" action="_loginmembro.php" method="post" charset="UTF-8">
                                            <div class="row">
                                                <div class="column width-12">
                                                    <input type="hidden" value="<?php echo $urldaempresa;?>" name="urldaempresa">
                                                    <div class="field-wrapper">
                                                        <input type="text" name="cpf" class="form-element medium rounded login-form-textfield" placeholder="E-mail" required>
                                                    </div>
                                                </div>
                                                <div class="column width-12">
                                                    <div class="field-wrapper">
                                                        <input type="password" name="senha" class="form-element medium rounded login-form-textfield" placeholder="Password" required>
                                                    </div>
                                                </div>
                                                <div class="row no-margins">
                                                    <div class="column width-6">
                                                        <button type="submit" value="Entrar" name="btn-acessarusuario" class="button small rounded bkg-white bkg-hover-white hard-shadow"><span class="text-medium color-blue color-hover-blue-light weight-bold">Entrar</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
							</div>
						</div>
                        <div class="column width-6 pull-6">
							<div>
								<div class="signup-box box rounded xlarge shadow bkg-white">
									<h3>Crie sua conta <strong>de visitante</strong>.</h3>
									<p class="mb-20">Se você <strong>não é membro da Emanuel</strong> crie sua conta como visitante</p>
									<div class="form">
										<form class="form" charset="UTF-8" action="_cadastrarvisitante.php" method="post">
                                            <div class="row">
                                                <div class="column width-4">
                                                    <label class="color-charcoal"><strong>NOME (COMPLETO)</strong></label>
                                                    <input type="text" name="nome" class="form-fname form-element medium" placeholder="Digite seu primeiro nome" tabindex="01" required>
                                                </div>
                                                <div class="column width-8">
                                                    <label class="color-charcoal"><strong>SOBRENOME (COMPLETO)</strong></label>
                                                    <input type="text" name="sobrenome" class="form-fname form-element medium" placeholder="Digite seu sobrenome completo" tabindex="01" required>
                                                </div>
                                                <div class="column width-12">
                                                    <label class="color-charcoal"><strong>E-MAIL</strong> (Enviaremos sua Senha de acesso para este e-mail)</label>
                                                    <input type="email" name="email" class="form-fname form-element medium" placeholder="Seu E-mail principal" tabindex="02" required>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="column width-6">
                                                    <span class=" color-charcoal  weight-bold pb-10">Sexo.</span>
                                                    <div class="form-select form-element rounded medium">
                                                        <select name="sexo" tabindex="6" class="form-aux" data-label="Project Budget">
                                                            <option>Feminino</option>
                                                            <option>Masculino</option>
                                                        </select>
                                                    </div> 
                                                </div> 
                                                <div class="column width-6">
                                                    <span class=" color-charcoal weight-bold pb-10">Nos conheceu através?</span>
                                                    <div class="form-select form-element rounded medium">
                                                        <select name="conheceu" tabindex="6" class="form-aux" data-label="Project Budget"> 
                                                            <option>
                                                                Amigo(a)
                                                            </option>
                                                            <option>
                                                                Facebook
                                                            </option>
                                                            <option>
                                                                Instagram
                                                            </option>
                                                            <option>
                                                                YouTube
                                                            </option>
                                                            <option>
                                                                Twitter
                                                            </option>
                                                            <option>
                                                                WhatsApp
                                                            </option>
                                                            <option>
                                                                Célula
                                                            </option>
                                                            <option>
                                                                Panfleto
                                                            </option>
                                                            <option>
                                                                Passei em frente.
                                                            </option>
                                                            <option>
                                                                Outro
                                                            </option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="column width-6">
                                                    <label class="color-charcoal">TEL. <strong>MÓVEL</strong></label>
                                                    <input type="tel" name="telefonemovel" class="form-fname form-element medium" placeholder="21 00001111" tabindex="02" required>
                                                </div>
                                                <div class="column width-6">
                                                    <label class="color-charcoal"><strong>WHATSAPP</strong></label>
                                                    <input type="tel" name="whatsapp" class="form-fname form-element medium" placeholder="21 00001111" tabindex="02">
                                                </div>
                                                <div class="column width-12">
                                                    <p class="color-black pb-10" align='justify'>
                                                        Você confirma que preencheu este cadastro espontâneamente, que quer participar do evento da <strong>Emanuel (Igreja Batista Emanuel)</strong> e que está ciente de que ao confirmar este formulário autorizará o uso da sua imagem em todos os veículos de divulgação, comunicação e publicidade pela Emanuel por tempo indeterminado <strong>?</strong>
                                                    </p>
                                                </div>
                                                
                                                <div class="column width-12">
                                                    <div class="form-select form-element rounded medium">
                                                        <select name="declaracao" tabindex="6" class="form-aux" data-label="Project Budget"> 
                                                            <option>
                                                                Sim
                                                            </option>
                                                            <option>
                                                                Não
                                                            </option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <input type="hidden" name='v1' value='<?php echo $v1;?>'/>
                                                <input type="hidden" name='v2' value='<?php echo $v2;?>'/>

                                                <div class="column width-12 border-theme pt-20 pb-20">
                                                    <label class="center color-charcoal">PARA FINALIZAR <strong>RESOLVA O CÁLCULO ABAIXO.</strong></label>
                                                    <div class="field-wrapper">
                                                        <div class="column width-4 pt-20">
                                                            <h3 class="right">
                                                                <?php echo $v1.' + '.$v2.' = ';?>
                                                            </h3>
                                                        </div>
                                                        <div class="column width-6">
                                                            <div class="field-wrapper">
                                                                <input type="hidden" name="soma1" class="form-name form-element medium" value="<?php $somaa = $v1+$v2; echo $somaa;?>">
                                                                <input type="number" name="soma" class="form-name form-element large" placeholder="Informar valor" tabindex="03" required>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="column width-12 pt-20">
                                                    <button tabindex="04" type="submit" name="btn-cadastrar" class="form-submit button small rounded bkg-blue bkg-hover-blue-light color-white color-hover-white hard-shadow"><span class="text-medium weight-bold">Criar conta</span></button>
                                                    <!-- <a tabindex="05" href="<?php echo $loginUrl; ?>" class="form-submit button small bkg-facebook bkg-hover-facebook rounded color-white color-hover-white hard-shadow">
                                                        <span class="icon-facebook left"></span>  Entrar com Facebook
                                                    </a> -->
										            <p align="justify" class="text-large color-charcoal mt-20"><small>Ao clicar em "criar conta" você declara ter lido e aceito nossos <a href="#">termos</a></small>.</p>
                                                </div>
                                            </div>
                                        </form>
									</div>
								</div>
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