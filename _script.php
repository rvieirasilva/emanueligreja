    <!-- Subscribe Advanced Modal End -->
    <div id="sermembro" class="section-block pt-0 pb-30 background-none hide">
        <!-- Signup -->
        <div class="section-block pt-60 pb-0">
            <div class="row">
                <div class="column width-12 left">
                    <div class="signup-form-container">
                        <div class="row">
                            <div class="column width-10 offset-1">
                                <p>
                                    
                                </p>
                            </div>
                        </div>
                        <form class="form" charset="UTF-8" action="" method="post">
                            <h3 class="color-blue">Olá, escreva sua mensagem e nos informe o e-mail e/ou seu WhatsApp para que possamos entrar em contato.</h3>
                            <div class="row">
                                <div class="column width-8">
                                    <div class="field-wrapper">
                                        <input type="email" name="email" <?php if(isset($_SESSION['bot'])): echo 'value="'.$_POST['email'].'"'; endif;?> class="form-name form-element large" placeholder="Digite seu e-mail." tabindex="1" required>
                                    </div>
                                </div>
                                <div class="column width-4">
                                    <div class="field-wrapper">
                                        <input type="tel" name="whatsapp" <?php if(isset($_SESSION['bot'])): echo 'value="'.$_POST['whatsapp'].'"'; endif;?> class="form-name form-element large" placeholder="Seu WhatsApp." tabindex="1" >
                                    </div>
                                </div>
                                <div class="column width-12">
                                    <div class="field-wrapper">
                                        <textarea type="text" name="mensagem" maxlenght="500" class="form-email form-element large" placeholder="Me diga como posso te servir?" tabindex="2" required><?php if(isset($_SESSION['bot'])): echo $_POST['mensagem']; endif;?> </textarea>
                                    </div>
                                </div>
                                <div class="column width-12 border-theme pt-20 pb-10">
                                    <label class="center color-charcoal"><strong>RESOLVA O CÁLCULO ABAIXO.</strong></label>
                                    <div class="field-wrapper">
                                        <div class="column width-4 pt-20 offset-2">
                                            <h3 class="right">
                                                <?php echo $v1.' + '.$v2.' = ';?>
                                            </h3>
                                        </div>
                                        <div class="column width-4">
                                            <div class="field-wrapper">
                                                <input type="hidden" name="soma1" class="form-name form-element large" value="<?php $somaa = $v1+$v2; echo $somaa;?>">
                                                <input type="number" name="soma" class="form-name form-element large" placeholder="Informar valor" tabindex="1" required>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="column width-12 pt-10 pb-10">
                                    <button type="submit" name="btn-enviarmensagem" class="pt-20  button medium bkg-blue bkg-hover-blue-light color-white color-hover-white no-margin-bottom pill">Não sou um rôbo! Enviar mensagem</button>
                                    <?php
                                        unset($_SESSION['soma']);
                                        unset($_SESSION['somado']);
                                    ?>
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
    <!-- Subscribe Advanced Modal End --> 

    
    <!-- Subscribe Advanced Modal End -->
    <div id="pedidodeoracao" class="section-block pt-0 pb-30 background-none hide">
        <!-- Signup -->
        <div class="section-block pt-60 pb-0">
            <div class="row">
                <div class="column width-12 left">
                    <div class="signup-form-container">
                        <div class="row">
                            <div class="column width-10 offset-1">
                                <p>
                                    
                                </p>
                            </div>
                        </div>
                        <form class="form" charset="UTF-8" action="quemsomos" method="post">
                            <h3 class="color-blue">Pedido de oração</h3>
                            <span class="color-blue">Queremos orar com você e te entregar todo suporte possível.</span>
                            <div class="row">
                                <div class="column width-8">
                                    <div class="field-wrapper">
                                        <input type="email" name="email" <?php if(isset($_SESSION['bot'])): echo 'value="'.$_POST['email'].'"'; endif;?> class="form-name form-element large" placeholder="Digite seu e-mail." tabindex="1" required>
                                    </div>
                                </div>
                                <div class="column width-4">
                                    <div class="field-wrapper">
                                        <input type="tel" name="whatsapp" <?php if(isset($_SESSION['bot'])): echo 'value="'.$_POST['whatsapp'].'"'; endif;?> class="form-name form-element large" placeholder="Seu WhatsApp." tabindex="1" >
                                    </div>
                                </div>
                                <div class="column width-12">
                                    <div class="field-wrapper">
                                        <textarea type="text" name="mensagem" maxlenght="500" class="form-email form-element large" placeholder="Me diga como posso te servir?" tabindex="2" required><?php if(isset($_SESSION['bot'])): echo $_POST['mensagem']; endif;?> </textarea>
                                    </div>
                                </div>
                                <div class="column width-12 border-theme pt-20 pb-10">
                                    <label class="center color-charcoal"><strong>RESOLVA O CÁLCULO ABAIXO.</strong></label>
                                    <div class="field-wrapper">
                                        <div class="column width-4 pt-20 offset-2">
                                            <h3 class="right">
                                                <?php echo $v1.' + '.$v2.' = ';?>
                                            </h3>
                                        </div>
                                        <div class="column width-4">
                                            <div class="field-wrapper">
                                                <input type="hidden" name="soma1" class="form-name form-element large" value="<?php $somaa = $v1+$v2; echo $somaa;?>">
                                                <input type="number" name="soma" class="form-name form-element large" placeholder="Informar valor" tabindex="1" required>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="column width-12 pt-10 pb-10">
                                    <button type="submit" name="btn-enviarmensagem" class="pt-20  button medium bkg-blue bkg-hover-blue-light color-white color-hover-white no-margin-bottom pill">Não sou um rôbo! Enviar mensagem</button>
                                    <?php
                                        unset($_SESSION['soma']);
                                        unset($_SESSION['somado']);
                                    ?>
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
    <!-- Subscribe Advanced Modal End --> 

    
    <!-- INICIO Recuperar senha do usuário -->
    <?php
        if(isset($_POST['btn-recuperarsenhauser'])):
            $email=mysqli_escape_string($con, $_POST['email']);
                //Verificar se email pertence a um usuário cadastrado.
                $brecuperar="SELECT email, nome, matricula FROM membros WHERE email='$email'";
                    $rrcuperar=mysqli_query($con, $brecuperar);
                       $drecuperar=mysqli_fetch_array($rrcuperar);
                        $exist=mysqli_num_rows($rrcuperar);

                        $nomerecuperar=$drecuperar['nome'];
                        $matrecuperar=$drecuperar['matricula'];

                        $iduni=uniqid();
            if($exist > '0'):
                //Inicio do código para enviar e-mail.
                include "_enviaremail.php"; //Traz página com as configurações para envio do e-mail.
                $mail->addAddress("$email", "$nomerecuperar");     // Add a recipient 
                    //$mail->addAddress("ellen@example.com");               // Name is optional
                    //$mail->addReplyTo("info@example.com", "Information");
                    //$mail->addCC("cc@example.com");
                    //$mail->addBCC("$emaildorepresentante");

                    //$mail->addAttachment("/var/tmp/file.tar.gz");         // Add attachments
                    //$mail->addAttachment("/tmp/image.jpg", "new.jpg");    // Optional name
                $mail->isHTML(true);                                  // Set email format to HTML
                $mail->Subject = "Recuperar senha.";
                $mail->Body    = "Olá $nomerecuperar. 
                                <p>Você solicitou uma recuperação de senha</p>
                                <p>Para recuperar sua senha clique no botão abaixo ou copie e cole o link no navegador para recuperar sua senha.</p>
                                <p><a href='https://igrejaemanuel.com.br/recuperarsenha?ref=$matrecuperar' target='_blank'><button>RECUPERAR SENHA</button></a></p>

                                <p>https://igrejaemanuel.com.br/recuperarsenha?ref=$matrecuperar&token=$iduni</p>
                                <p><h5>Igreja Emanuel</h5></p>
                                <p><h1>Cristo é o Senhor!</h1></p>
                                ";
                $mail->AltBody = "Recuperar Senha"; // Não é visualizado pelo usuário!
                $mail->send();
                //Fim do código para enviar e-mail.
            else:
                $_SESSION['mensagemsenha']="Este e-mail não está cadastrado.";
            endif;
        endif;
    ?>
    <div id="recuperarsenhauser" class="section-block pt-0 pb-30 background-none hide">
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
                        <form class="form" charset="UTF-8" action="recuperarsenha.php" method="post">
                            <h2 class="color-blue">Informe seu e-mail para recuperar sua senha.</h2>
                            <?php
                                if(isset($_SESSION['mensagemsenha']))   :
                                    {
                            ?>
                                <h5 class="color-blue"><?php echo $_SESSION['mensagemsenha'];?></h5>
                            <?php
                                    }
                                endif;
                            ?>
                            <div class="row">
                                <div class="column width-12">
                                    <div class="field-wrapper">
                                        <input type="text" name="email" class="form-name form-element large" placeholder="E-mail cadastrado" tabindex="1" required>
                                    </div>
                                </div>
                                <div class="column width-6">
                                    <input type="submit" value="RECUPERAR SENHA" name="btn-recuperarsenhamembro" class="form-submit pill button small bkg-blue bkg-hover-blue-light color-white color-hover-white no-margin-bottom">
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
    <!-- FIM Recuperar senha do usuário --> 

    <div id="atualizarsenha" class="section-block pt-0 pb-30 background-none hide">
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
                                <button type="submit" name="btn-recuperarsenha" class="form-submit button small pill bkg-red-light bkg-hover-red color-white color-hover-white">SALVAR SENHA</button>
                            </div>
                    
                        </form>
                        <!--<div class="form-response show"></div>-->
                    </div>
                </div>
            </div>
        </div>
        <!-- Signup End -->

    </div>

        
    <div id="logarmembro" class="section-block pt-0 pb-30 background-none hide">
        <!-- Sign In/ Sign Up -->
        <div class="section-block pt-40 pb-0">
            <div class="row">
                <div class="column width-12 center">
                    <div class="modal-dialog-inner">
                        <div class="form-container">
                            <div class="row">
                                <div class="column width-12">
                                    <div class="tabs button-nav bordered rounded medium center">
                                        <!--
                                        <a href="#" class="button rounded medium center full-width bkg-facebook bkg-hover-facebook color-white color-hover-white mb-10"><span class="icon-facebook left"></span><span>Entrar com Facebook</span></a>
                                        <div class="divider center mt-0 mb-20"><span class="bkg-white text-large">Ou</span></div>
                                        -->
                                        <ul class="tab-nav">
                                            <li class="active">
                                                <a href="#tabs-account-1">Possuo conta como Membro ou Visitante</a>
                                            </li>
                                            <li>
                                                <a href="#tabs-account-2">Me cadastrar como visitante</a>
                                            </li>
                                        </ul>
                                        <div class="tab-panes">

                                            <!-- Sign In -->
                                            <div id="tabs-account-1" class="active animate">
                                                <div class="tab-content left">
                                                    <div class="login-form-container">
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
                                                                        <input type="submit" value="Entrar" name="btn-acessarusuario" class="button medium rounded bkg-orange-light bkg-hover-orange-light color-white color-hover-white">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </form>
                                                        <div class="form-response"></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- Sign In End -->

                                            <!-- Sign Up -->
                                            <div id="tabs-account-2">
                                                <div class="tab-content left">
                                                    <div class="register-form-container">
                                                        <form class="form" charset="UTF-8" action="_cadastrarvisitante.php" method="post">
                                                            <div class="row">
                                                                <div class="column width-4">
                                                                    <label class="color-charcoal"><strong>NOME (COMPLETO)</strong></label>
                                                                    <input type="text" name="nome" class="form-fname form-element large" placeholder="Digite seu primeiro nome" tabindex="01" required>
                                                                </div>
                                                                <div class="column width-8">
                                                                    <label class="color-charcoal"><strong>SOBRENOME (COMPLETO)</strong></label>
                                                                    <input type="text" name="sobrenome" class="form-fname form-element large" placeholder="Digite seu sobrenome completo" tabindex="01" required>
                                                                </div>
                                                                <div class="column width-12">
                                                                    <label class="color-charcoal"><strong>E-MAIL</strong> (Enviaremos sua Senha de acesso para este e-mail)</label>
                                                                    <input type="email" name="email" class="form-fname form-element large" placeholder="Seu E-mail principal" tabindex="02" required>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="column width-3">
                                                                    <span class=" color-charcoal  weight-bold pb-10">Sexo.</span>
                                                                    <div class="form-select form-element rounded medium">
                                                                        <select name="sexo" tabindex="6" class="form-aux" data-label="Project Budget">
                                                                            <option>Feminino</option>
                                                                            <option>Masculino</option>
                                                                        </select>
                                                                    </div> 
                                                                </div> 
                                                                <div class="column width-3">
                                                                    <label class="color-charcoal">TEL. <strong>MÓVEL</strong></label>
                                                                    <input type="tel" name="telefonemovel" class="form-fname form-element medium" placeholder="21 00001111" tabindex="02" required>
                                                                </div>
                                                                <div class="column width-3">
                                                                    <label class="color-charcoal"><strong>WHATSAPP</strong></label>
                                                                    <input type="tel" name="whatsapp" class="form-fname form-element medium" placeholder="21 00001111" tabindex="02">
                                                                </div>
                                                                <div class="column width-3">
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
                                                                <div class="column width-12">
                                                                    <span class="color-black">
                                                                        VOCÊ CONFIRMA QUE PREENCHEU ESTE CADASTRO ESPONTÂNEAMENTE, QUE QUER PARTICIPAR DO EVENTO DA  <strong> EMANUEL (IGREJA BATISTA EMANUEL)</strong> E QUE ESTÁ CIENTE DE QUE AO CONFIRMAR ESTE FORMULÁRIO AUTORIZARÁ O USO DA SUA IMAGEM EM TODOS OS VEÍCULOS DE DIVULGAÇÃO, COMUNICAÇÃO E PUBLICIDADE PELA EMANUEL.
                                                                    </span>
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
                                                                        <div class="column width-3 pt-20 offset-2">
                                                                            <h3 class="right">
                                                                                <?php echo $v1.' + '.$v2.' = ';?>
                                                                            </h3>
                                                                        </div>
                                                                        <div class="column width-6">
                                                                            <div class="field-wrapper">
                                                                                <input type="hidden" name="soma1" class="form-name form-element large" value="<?php $somaa = $v1+$v2; echo $somaa;?>">
                                                                                <input type="number" name="soma" class="form-name form-element large" placeholder="Informar valor" tabindex="03" required>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="column width-12 pt-20">
                                                                    <button tabindex="04" type="submit" name="btn-cadastrar" class="form-submit button small rounded bkg-blue bkg-hover-blue-light color-white color-hover-white hard-shadow">Criar conta</button>
                                                                    <!-- <a tabindex="05" href="<?php echo $loginUrl; ?>" class="form-submit button small bkg-facebook bkg-hover-facebook rounded color-white color-hover-white hard-shadow">
                                                                        <span class="icon-facebook left"></span>  Entrar com Facebook
                                                                    </a> -->
                                                                </div>
                                                            </div>
                                                        </form>
                                                    </div>
                                                    <p class="lead mt-20"><small>Ao clicar em "criar conta" você declara ter lido e aceito nossos <a href="#">termos</a></small>.</p>
                                                </div>
                                            </div>
                                            <!-- Sign Up End -->

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Sign In/ Sign Up End -->
    </div>

 <script src="js/jquery-3.2.1.min.js"></script>
	<!-- <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyC3JCAhNj6tVAO_LSb8M-AzMlidiT-RPAs"></script> -->
	<script src="js/timber.master.min.js"></script>

	
    <?php
        if(isset($_GET)):
            @$pagamento_page = $_SERVER['REQUEST_URI'];
            @$pagamento_page = explode('/', $pagamento_page);
            @$pagamento_page = $pagamento_page[1];

            //Tira os GET's.
            @$pagamento_page = explode('?', $pagamento_page);
            @$pagamento_page = $pagamento_page[0];
        else:
            @$pagamento_page = $_SERVER['REQUEST_URI'];
            @$pagamento_page = explode('/', $pagamento_page);
            @$pagamento_page = $pagamento_page[1];
        endif;
    ?>
    <?php
        if(!isset($_POST['btn-desconto']) AND $pagamento_page === 'checkout' or $pagamento_page ==='comprafinalizar' or $pagamento_page === 'doacoes' or $pagamento_page === 'evento'):
    ?>
        <?php
            if($pagamento_page === 'checkout' OR $pagamento_page === 'comprafinalizar' OR $pagamento_page === 'doacoes' or $pagamento_page === 'evento'): // AND $FDPagCheck === 'Crédito'):
        ?>
            <!-- Scripts para Intermediação do pagamento Mercado Pago -->
            <script src="https://secure.mlstatic.com/sdk/javascript/v1/mercadopago.js"></script>
        <?php
            endif;
            if($pagamento_page === 'checkout'):
        ?>
            <script src="lib/js/javascript.js"></script>
            
        <?php
            elseif($pagamento_page === 'doacoes' or $pagamento_page === 'evento'):
        ?>
            <script src="lib/js/javascriptDoacoes.js"></script>

        <?php
            elseif($pagamento_page === 'comprafinalizar' AND $FDPagCheck === 'Crédito'):
        ?>
            <script src="lib/js/javascriptProduto.js"></script>
        <?php
            endif;
        ?>
        <?php
            if($pagamento_page === 'checkout' OR $pagamento_page === 'comprafinalizar' AND $FDPagCheck === 'Crédito'):
        ?>
            <!--
            <script src="https://www.mercadopago.com.br/integrations/v1/web-tokenize-checkout.js"></script>
            <script src="https://www.mercadopago.com/v2/security.js" view="home"></script>
            -->
        <?php
            endif;
        endif;

        if($pagamento_page === 'blogescrever' OR $pagamento_page === 'fraseescrever' OR $pagamento_page === 'fraseeditar' OR $pagamento_page === 'blogeditar' OR $pagamento_page === 'celulaesboco' OR $pagamento_page === 'celulaesbocoeditar' OR $pagamento_page === 'acervoup' OR $pagamento_page === 'acervoeditar' OR $pagamento_page === 'editarquemsomos' OR $pagamento_page === 'agendacadastrar' OR $pagamento_page === 'agendaeditar'):
    ?>
        <script src="ckeditor/ckeditor.js"></script>
        <script>
            CKEDITOR.replace('txtArtigo'); 
        </script>
        <script>
            CKEDITOR.replace('bibliografia'); 
        </script>
    <?php
        endif;
    ?>

    <script>
        window.setTimeout(function(){
            document.getElementById("mantenedorfiel").click();
        }, 200);
    </script>
    <a id="mantenedorfiel" data-content="inline" data-aux-classes="tml-promotion-modal tml-padding-small tml-swap-exit-light height-auto rounded" data-toolbar="" data-modal-mode data-modal-width="1140" data-modal-animation="scaleIn" data-lightbox-animation="fadeIn" href="#promotion-modal" class="lightbox-link"></a>

    <div id="promotion-modal" class="hide">
        <div class="section-block hero-5 hero-5-2 pb-mobile-20 show-media-column-on-mobile">
            <div class="media-column width-5">
                <div class="media-overlay bkg-black opacity-01"></div>
            </div>
            <div class="row">
                <div class="column width-5 offset-6">
                    <div class="hero-content split-hero-content">
                        <div class="hero-content-inner left horizon" data-animate-in="preset:slideInRightShort;duration:1000ms;delay:200ms;" data-threshold="0.6">
                            <h3 class="mb-30 color-charcoal">Ajude-nos na implatação da Igreja e a darmos <strong><i>Start nas ações sociais</i></strong>.</h3>
                            <p class="color-charcoal">Seja um mantenedor <strong>fiel</strong> com oferta a partir de R$ 9,90</p>
                            <div class="product-actions">
                                <a href="https://www.mercadopago.com.br/subscriptions/checkout?preapproval_plan_id=2c938084816dca930181865c317d0757" target="_blank"  rel="noopener noreferrer" class="button rounded medium bkg-theme bkg-hover-theme color-white color-hover-white add-to-cart-button medium"><span class="text-large">Quero ser um mantenedor</span></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>