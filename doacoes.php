<!DOCTYPE html>
<?php
	session_start();
	if(!empty($_GET['lng'])):
		$idioma = $_GET['lng'];
	elseif(empty($_GET['lng']) OR !isset($_GET['lng'])):
		$idioma = 'pt-BR';
    endif;
    
    include "./_con.php";
    include "./configDoacoes.php";

    if(isset($_POST['btn-contribuicao'])):

        $membro                 =   mysqli_escape_string($con, $_POST["membro"]);
        $tipodecontribuicao     =   mysqli_escape_string($con, $_POST['tipodecontribuicao']);
        $contribuicao           =   mysqli_escape_string($con, $_POST['contribuicao']);
        $contribuicao2          =   mysqli_escape_string($con, $_POST['contribuicao2']); //Outro valor > 1000
            
            if($contribuicao2 >= '1.000'):                
                $contribuicao2 = str_replace('R$ ', '', $contribuicao2);
                $contribuicao2 = explode('.', $contribuicao2); // 1 999,35
                $contribuicao2 = $contribuicao2[0].$contribuicao2[1]; //1999,35
                $contribuicao2 = str_replace(',','.', $contribuicao2); // 1999,35 >> 1999.35
            elseif($contribuicao2 < '1.000'):
                $contribuicao2 = str_replace('R$ ', '', $contribuicao2);
                $contribuicao2 = str_replace(',','.', $contribuicao2); // 1999,35 >> 1999.35
            endif;

        if(!empty($contribuicao2)):
            $contribuicao = $contribuicao2;
        else:
            $contribuicao = $contribuicao;
        endif;

        if(!empty($_POST['contribuicao2'])):
            $contribuicao0=$contribuicao2;
            // $contribuicao0 = str_replace('R$ ', '', $_POST['contribuicao2']);
            // $contribuicao0 = $contribuicao2.'.00';
        else:
            $contribuicao0 = $_POST['contribuicao'];
            // if($contribuicao0 >= 1000):
            //     $contribuicao0 = number_format($contribuicao0, 2, '.','');
            // elseif($contribuicao0 < 1000):
            //     $contribuicao0 = str_replace(',', '.', $contribuicao0);
            // endif;
        endif;

        if($contribuicao0 >= 1000):
            $contribuicao0 = number_format($contribuicao0, 2, ',','.');
        elseif($contribuicao0 < 1000):
            $contribuicao0 = str_replace('.', ',', $contribuicao0);
        endif;

        $email                  =   mysqli_escape_string($con, $_POST['email']);
        $nome                   =   mysqli_escape_string($con, $_POST['nomeofertante']);

        //Email teste
        // include "./_enviaremail.php";
        // $mail->addAddress("faelvieirasilva+x9pxd6ypscrar3ft2gad@boards.trello.com", "$nome");     // Add a recipient 
        //     //$mail->addAddress("ellen@example.com");               // Name is optional
        //     //$mail->addReplyTo("info@example.com", "Information");
        //     //$mail->addCC("cc@example.com");
        //     $mail->addBCC("prrafaelvieira@igrejaemanuel.com.br");

        //     //$mail->addAttachment("/var/tmp/file.tar.gz");         // Add attachments
        //     //$mail->addAttachment("/tmp/image.jpg", "new.jpg");    // Optional name
        // $mail->isHTML(true);                                  // Set email format to HTML

        // $mail->Subject = "Envio de oferta - $nome";
        // $mail->Body    = "O (a) $nome. 
        //                 <p>Iniciou o envio de uma oferta/dizímo através do site da Emanuel</p>
        //                 <p>Nome: $nome</p>
        //                 <p>E-mail: $email</p>
        //                 <p>Valor: R$ $contribuicao0</p>
        //                 ";
        // $mail->AltBody = "O (a) $nome. 
        //                 <p>Iniciou o envio de uma oferta/dizímo através do site da Emanuel</p>
        //                 <p>Nome: $nome</p>
        //                 <p>E-mail: $email</p>
        //                 <p>Valor: R$ $contribuicao0</p>
        //                 "; // Não é visualizado pelo usuário!
        // $mail->send();
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
                                                        <h1 class="mb-0"><strong>Ofertas & Dízimos</strong></h1>
                                                        <p class="text-large color-charcoal mb-0 mb-mobile-20">
                                                            "Oferta não tem valor, tem significado". (Pr Rafael Vieira), Não se trata do valor e sim do quanto significa para você aquilo que você está contribuindo.</p>
                                                    </div>
                                                </div>
                                                <div class="column width-3 v-align-middle">
                                                    <div>
                                                        <ul class="breadcrumb inline-block mb-0 pull-right clear-float-on-mobile">
                                                            <li>
                                                                <a href="index">Inicio</a>
                                                            </li>
                                                            <li>
                                                                Oferta
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
                        <div class="row flex">
                            <?php include "./_notificacaomensagem.php"; ?>
                            <div class="box rounded rounded shadow bkg-white">
                                <div class="column width-12 pt-30">
                                    <!-- <div class="column width-12"> -->
                                        <?php
                                            if(!isset($_POST['btn-contribuicao'])):
                                        ?>
                                            <form action="" method="post" charset="utf-8">
                                                <div class="billing-details">
                                                    <div class="form-container">
                                                            <!-- Payment Method -->
                                                            <!-- <div class="row"> -->
                                                                <div class="column width-4">
                                                                    <div class="box rounded border-blue small shadow">
                                                                        <span class="title-medium color-charcoal left font-alt-1 pb-10">
                                                                            Enviar sua <strong>oferta&dízimo</strong> por <strong>PIX</strong>.
                                                                        </span>
                                                                        <label class="color-charcoal left pt-10 text-medium">Utilize a chave PIX: <strong>oferta@igrejaemanuel.com.br</strong> ou o <strong>QR Code (PIX):</strong></label>
                                                                        <div class="column width-12 pt-10 pb-20 center">
                                                                            <img src="./images/Qr Code - NuBank - Emanuel.jpeg">
                                                                        </div>
                                                                        <p class="text-small pt-20 color-charcoal" align="justify">
                                                                            Estamos na fase de implantação, por isso ainda não temos nosso CNPJ e, consequentemente, nossa conta bancária. Esta conta foi cedida, <strong>completamente</strong> por nosso pastor, durante este período. <strong>Temos um processo de transparência financeira e prestação de conta em assembléia com toda igreja, em caso de dúvida pode entrar em contato com a gente; <a href="mailto:contato@igrejaemanuel.com.br" target="_blank">contato@igrejaemanuel.com.br</a>. Envie sua oferta e nos ajude a implantar a Emanuel e outras ações missionárias e sociais que estamos cooperando</strong>.
                                                                        </p>
                                                                    </div>
                                                                </div>
                                                                <div class="column width-8">
                                                                    <div class="box rounded border-white small">
                                                                    <div class="tabs button-nav rounded small bordered left mb-20">
                                                                        <div class="tab-content">
                                                                            <div class="row">
                                                                                <div class="column width-12">
                                                                                    <span class=" color-charcoal title-medium weight-bold">Você é membro da Emanuel?</span>
                                                                                    <div class="field-wrapper pt-10 pb-10">
                                                                                        <input id="membro-1" class="form-element radio" name="membro" value="Sim" type="radio" checked>
                                                                                        <label for="membro-1" class="text-large radio-label">Sim</label>

                                                                                        <input id="membro-2" class="form-element radio" name="membro" value="Não" type="radio" >
                                                                                        <label for="membro-2" class="text-large radio-label">Não</label>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="column width-12">
                                                                                    <span class=" color-charcoal title-medium weight-bold pb-10">Como você deseja ajudar?</span>
                                                                                    <div class="form-select form-element rounded medium">
                                                                                        <select name="tipodecontribuicao" tabindex="6" class="form-aux" data-label="Project Budget">
                                                                                            <option selected="selected" value="Oferta">Oferta</option>
                                                                                            <option value="Dízimo">Dízimo</option>
                                                                                            <option value="Almoço beneficente">Almoço Beneficente.</option>
                                                                                            <option value="Campanha; Construindo por todos.">Campanha; Construindo por todos.</option>
                                                                                            <option value="Outros">Outros.</option>
                                                                                        </select>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="row">
                                                                                <div class="column width-12">
                                                                                    <span class=" color-charcoal title-medium weight-bold">Valor da contribuição.</span>
                                                                                    <div class="field-wrapper pt-10 pb-10">
                                                                                        <input id="radio-1" class="form-element radio" name="contribuicao" value='5.00' type="radio">
                                                                                        <label for="radio-1" class="text-large radio-label">R$ 5,00</label>

                                                                                        <input id="radio-2" class="form-element radio" name="contribuicao" value='10.00' type="radio" checked>
                                                                                        <label for="radio-2" class="text-large radio-label">R$ 10,00</label>

                                                                                        <input id="radio-8" class="form-element radio" name="contribuicao" value='15.00' type="radio" checked>
                                                                                        <label for="radio-8" class="text-large radio-label">R$ 15,00</label>

                                                                                        <input id="radio-3" class="form-element radio" name="contribuicao" value='20.00' type="radio">
                                                                                        <label for="radio-3" class="text-large radio-label">R$ 20,00</label>

                                                                                        <input id="radio-4" class="form-element radio" name="contribuicao" value='50.00' type="radio">
                                                                                        <label for="radio-4" class="text-large radio-label">R$ 50,00</label>

                                                                                        <!-- <input id="radio-5" class="form-element radio" name="contribuicao" value='70.00' type="radio">
                                                                                        <label for="radio-5" class="text-large radio-label">R$ 70,00</label> -->

                                                                                        <input id="radio-6" class="form-element radio" name="contribuicao" value='100.00' type="radio">
                                                                                        <label for="radio-6" class="text-large radio-label">R$ 100,00</label>

                                                                                        <input id="radio-7" class="form-element radio" name="contribuicao" value='1000.00' type="radio">
                                                                                        <label for="radio-7" class="text-large radio-label">R$ 1.000.00</label>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="column width-12">
                                                                                    <span class=" color-charcoal weight-bold pt-10">Outro valor (R$)</span>
                                                                                    <div class="field-wrapper">
                                                                                        <input type="text" min="1" step="1" max='20000' name="contribuicao2" class="form-fname form-element rounded medium" placeholder="Outro valor" tabindex="1">
                                                                                    </div>
                                                                                </div>
                                                                                <div class="column width-12">
                                                                                    <label>E-mail</label>
                                                                                    <input type="text" name="email" placeholder="E-mail" class="form-fname form-element rounded medium"/>
                                                                                </div>
                                                                                <div class="column width-12">
                                                                                    <label>Seu nome</label>
                                                                                    <input type="text" name="nomeofertante" placeholder="Seu nome" class="form-fname form-element rounded medium"/>
                                                                                </div>
                                                                            </div>
                                                                            <div class="row">
                                                                                <div class="column width-6 center pt-0 pb-10">
                                                                                    <button type="submit" name="btn-contribuicao" class="column width-12 button rounded medium bkg-blue bkg-hover-blue color-white color-hover-white mb-0 hard-shadow shadow" tabindex="27">
                                                                                        <span class="title-medium">
                                                                                        Avançar
                                                                                        </span>
                                                                                        <span class="icon-right-open-big medium"></span>
                                                                                    </button>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    </div>
                                                                </div>
                                                            <!-- </div> -->
                                                        <!--</form>-->
                                                    </div>
                                                </div>
                                            </form>
                                        <?php
                                            elseif(isset($_POST['btn-contribuicao'])):
                                        ?>
                                            <form action="controllers/PaymentControllerDoacoes.php" method="post" id="pay" name="pay">
                                                <div class="billing-details">
                                                    <div class="form-container">
                                                        <!--<form action="/controllers/PaymentControllerProduto.php" method="post" id="pay" name="pay">-->
                                                            <!-- Payment Method -->
                                                            <div class="row">
                                                                <div class="column width-12">
                                                                    <div class="tabs button-nav rounded small bordered left mb-20">
                                                                        <div class="tab-content">
                                                                            <div class="row">
                                                                                <div class="column width-4">
                                                                                    <div class="column width-12">
                                                                                        <span class=" color-charcoal title-medium weight-bold">Você é membro da Emanuel?</span>
                                                                                        <div class="field-wrapper pt-10 pb-10">
                                                                                            <input id="membro-1" class="form-element radio" name="membro" type="radio" value="<?php echo $membro?>" checked>
                                                                                            <label for="membro-1" class="text-large radio-label"><?php echo $membro?></label>
                                                                                                
                                                                                            </label>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="column width-12">
                                                                                        <span class=" color-charcoal title-medium weight-bold">Valor da contribuição.</span>
                                                                                        <div class="field-wrapper pt-10 pb-10">
                                                                                            <input type="hidden" name="amount" id='amount' value='<?php echo $contribuicao; ?>'/>
                                                                                            <span class="title-medium color-blue">
                                                                                                <?php echo 'R$ '.$contribuicao0; ?> </span> <span>/ "Oferta não tem valor, tem significado. (Pr Rafael Vieira) - Que Cristo te abençoe."
                                                                                            </span>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="column width-12">
                                                                                        <span class=" color-charcoal title-medium weight-bold pb-10">Como você deseja contribuir?</span>
                                                                                        <div class="form-select form-element rounded medium">
                                                                                            <select name="tipodecontribuicao" tabindex="6" class="form-aux" data-label="Project Budget">
                                                                                                <option selected="selected" value="<?php echo $tipodecontribuicao;?>"><?php echo $tipodecontribuicao;?></option>
                                                                                            </select>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                                    
                                                                                <div class="column width-8">
                                                                                    <div class="column width-12">
                                                                                        <span class=" color-charcoal title-medium weight-bold pt-10">Realizada no Cartão (Crédito)</span>
                                                                                    </div>
                                                                                    <div class="column width-12">
                                                                                        <label>Nome</label>
                                                                                        <input type="text" id="cardholderName" data-checkout="cardholderName" placeholder="Nome" class="form-fname form-element rounded medium"/>
                                                                                    </div>

                                                                                    <div class="column width-4">
                                                                                        <label>Tipo de documento</label>
                                                                                        <div class="row">
                                                                                            <div class="column width-12">
                                                                                                <div class="form-select form-element rounded medium"
                                                                                                    tabindex="2">
                                                                                                    <select id="docType" data-checkout="docType"></select>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="column width-8">
                                                                                        <label>Número</label>
                                                                                        <input type="text" id="docNumber" data-checkout="docNumber" placeholder="19119119100" class="form-element rounded medium"/>
                                                                                    </div>

                                                                                    <div class="column width-12">
                                                                                        <label>Número do cartão</label>
                                                                                        <div class="input-indication">
                                                                                            <input type="text" id="cardNumber" data-checkout="cardNumber" placeholder="Número do cartão." onselectstart="return false" onpaste="return true" onCopy="return false" onCut="return false" onDrag="return false" onDrop="return false" autocomplete=off class="form-fname form-element rounded medium"/>

                                                                                            <div  class="form-element input-icon rounded bkg-grey-ultralight inherit-style"> <span class="icon-credit-card"></span> <div class="brand"></div></div>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="column width-4">
                                                                                        <label>Código de segurança (CVV)</label>
                                                                                        <div class="input-indication">
                                                                                                <input type="text" id="securityCode" data-checkout="securityCode" placeholder="CVV" onselectstart="return false" onpaste="return true" onCopy="return false" onCut="return false" onDrag="return false" onDrop="return false" autocomplete=off class="form-security-code form-element rounded medium center"/>
                                                                                            <div
                                                                                                class="form-element input-icon rounded bkg-grey-ultralight inherit-style">
                                                                                                <a data-content="inline"
                                                                                                    href="#cvv-modal"
                                                                                                    class="lightbox-link icon-info border-orange-light color-orange-light"
                                                                                                    data-aux-classes="tml-cart-modal tml-exit-light no-margins height-auto"
                                                                                                    data-modal-mode data-toolbar=""
                                                                                                    data-modal-width="300"
                                                                                                    data-lightbox-animation="fade"
                                                                                                    data-modal-animation="slideInTop"></a>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="column width-4">
                                                                                        <label>Mês de validade</label>
                                                                                        <div class="input-indication">
                                                                                            <input type="text" id="cardExpirationMonth" data-checkout="cardExpirationMonth" placeholder="11" onselectstart="return false" onpaste="return true" onCopy="return false" onCut="return false" onDrag="return false" onDrop="return false" autocomplete=off class="form-expiration form-element rounded medium"/>
                                                                                            <div
                                                                                                class="form-element input-icon rounded bkg-grey-ultralight inherit-style">
                                                                                                <span class="icon-calendar"></span>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="column width-4">
                                                                                        <label>Ano de validade</label>
                                                                                        <div class="input-indication">
                                                                                            <input type="text" id="cardExpirationYear" data-checkout="cardExpirationYear" placeholder="2025" onselectstart="return false" onpaste="return true" onCopy="return false" onCut="return false" onDrag="return false" onDrop="return false" autocomplete=off class="form-expiration form-element rounded medium"/>
                                                                                            <div
                                                                                                class="form-element input-icon rounded bkg-grey-ultralight inherit-style">
                                                                                                <span class="icon-calendar"></span>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                    
                                                                                    <div class="column width-12">
                                                                                        <label>Parcelas</label>
                                                                                        <div class="row">
                                                                                            <div class="column width-12">
                                                                                                <div class="form-select form-element rounded medium" tabindex="2">
                                                                                                    <select id="installments"  name="installments"></select>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                            
                                                                                    <input type="hidden" id="email" name="email" value="<?php echo $email;?>" placeholder="your email" />
                                                                                    <input type="hidden" name="nomeofertante" value="<?php echo $nome;?>" placeholder="your email" />

                                                                                    <input type="hidden" name="amount" id="amount" value="<?php echo $contribuicao;?>"/>
                                                                                    <input type="hidden" name="description" value="Contribuição realizada no site da Emanuel"/>
                                                                                    <input type="hidden" name="paymentMethodId"/>
                                                                                    <!--<input type="submit" value="Pay!" />-->

                                                                                    <!-- Submit Payment -->
                                                                                    <div class="column width-12">
                                                                                        <p align="justify" class="title-small mb-0 color-charcoal pt-10 pb-20">
                                                                                            Sua contribuição será realizada através do  <strong>MercadoPago</strong>.
                                                                                        </p>
                                                                                    </div>
                                                                                    <div class="column width-3">
                                                                                        <a href=doacoes  class="column width-12 button rounded small bkg-grey-ultralight bkg-hover-grey color-blue color-hover-blue mb-0 hard-shadow shadow" tabindex="8"/>
                                                                                            <span class="title-medium">
                                                                                                Voltar
                                                                                            </span>
                                                                                        </a>
                                                                                    </div>
                                                                                    <div class="column width-9">
                                                                                        <button type="submit" value="Concluir contribuição." class="column width-12 button rounded small bkg-blue bkg-hover-blue color-white color-hover-white mb-0 hard-shadow shadow" tabindex="8"/>
                                                                                            <span class="title-medium">
                                                                                                Concluir contribuição.
                                                                                            </span>
                                                                                        </button>
                                                                                    </div>
                                                                                    <!-- Submit Payment End -->
                                                                                </div>
                                                                            </div>
                                                                        
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        <!--</form>-->
                                                    </div>
                                                </div>
                                            </form>
                                        <?php
                                            endif;
                                        ?>
                                    <!-- </div> -->
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
    
	<?php include "./_script.php";?>
    
</body>
</html>