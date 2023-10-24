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
        $s2 = $_POST['soma'];


	if(isset($_POST['btn-cadastrar'])):
		if($s2 === $s):
			$nome=mysqli_escape_string($con,$_POST['nome']);
            $email=mysqli_escape_string($con,$_POST['email']);
            $whatsapp=mysqli_escape_string($con,$_POST['whatsapp']);
            $membro=mysqli_escape_string($con,$_POST['membro']);
            $motivo=mysqli_escape_string($con,$_POST['motivo']);
            $nps=mysqli_escape_string($con,$_POST['nps']);
            $musica=mysqli_escape_string($con,$_POST['musica']);
            $palavra=mysqli_escape_string($con,$_POST['palavra']);
            $horario=mysqli_escape_string($con,$_POST['horario']);
            $localizacao=mysqli_escape_string($con,$_POST['localizacao']);
            $recepcao=mysqli_escape_string($con,$_POST['recepcao']);
            $organizacao=mysqli_escape_string($con,$_POST['organizacao']);
            $limpeza=mysqli_escape_string($con,$_POST['limpeza']);
            $descricao=mysqli_escape_string($con,$_POST['descricao']);
            $participar=mysqli_escape_string($con,$_POST['participar']);

            $bDupl="SELECT id FROM ec10 WHERE nome='$nome' AND email='$email' AND motivo='$motivo' AND descricao='$descricao'";
              $rD=mysqli_query($con, $bDupl);
                $nD=mysqli_num_rows($rD);
            if($nD<1):
                $inS="INSERT INTO ec10 (dia, mes, ano, nome, email, whatsapp, motivo, nps, musica, palavra, horario, localizacao, membro, recepcao, organizacao, limpeza, descricao, participar, aberto) VALUES ('$dia', '$mesnumero', '$ano', '$nome', '$email', '$whatsapp', '$motivo', '$nps', '$musica', '$palavra', '$horario', '$localizacao', '$membro', '$recepcao', '$organizacao', '$limpeza', '$descricao', '$participar', '')";
                if(mysqli_query($con, $inS)):
                    include "./_enviaremail.php";
                    $mail->addAddress("faelvieirasilva+s72id1qtrktnygbvdsnh@boards.trello.com", "Projeto EC10");     // Add a recipient
                    $mail->isHTML(true);                                  // Set email format to HTML
                    $mail->Subject = "Projeto EC10 - $motivo #Importante #Ministério #ProjetoEC10";
                    $mail->Body    = "Está foi a mensagem: 
                                    <p>Nome que indicou: $nome</p>
                                    <p>E-mail: $email</p>
                                    <p>WhatsApp: $whatsapp</p>
                                    <p>É membro? $membro</p>
                                    <p>Percebi isto no encontro: $motivo</p>
                                    <p>Em uma escala de 0 a 10 o quanto você recomendaria a Emanuel para um amigo? $nps</p>
                                    <p>Como você classificaria a músicas? $musica</p>
                                    <p>Como você classificaria a palavra? $palavra</p>
                                    <p>Como você classificaria nosso horário? $horario</p>
                                    <p>Como você classificaria nossa localização?$localizacao</p>
                                    <p>Como você classificaria nossa Recepção? $recepcao</p>
                                    <p>Em uma escala de 0 a 10 como você classifica nossa organização? $organizacao</p>
                                    <p>Em uma escala de 0 a 10 como você classifica a limpeza da Igreja? $limpeza</p>
                                    <p>O que precisa ser melhorado na Emanuel?</p>
                                    <p>$descricao</p>
                                    <p>Podemos contar com você pra implantar essa sugestão? (Se sim, informe seu nome e e-mail) $participar</p>
                                    ";
                    $mail->AltBody = "Está foi a mensagem: 
                                    Nome que indicou: $nome
                                    E-mail: $email
                                    WhatsApp: $whatsapp
                                    É membro? $membro
                                    Percebi isto no encontro: $motivo
                                    Em uma escala de 0 a 10 o quanto você recomendaria a Emanuel para um amigo? $nps
                                    Como você classificaria a músicas? $musica
                                    Como você classificaria a palavra? $palavra
                                    Como você classificaria nosso horário? $horario
                                    Como você classificaria nossa localização?$localizacao
                                    Como você classificaria nossa Recepção? $recepcao
                                    Em uma escala de 0 a 10 como você classifica nossa organização? $organizacao
                                    Em uma escala de 0 a 10 como você classifica a limpeza da Igreja? $limpeza
                                    O que precisa ser melhorado na Emanuel?
                                    $descricao
                                    Podemos contar com você pra implantar essa sugestão? (Se sim, informe seu nome e e-mail) $participar
                                    "; // Não é visualizado pelo usuário!
                    $mail->send();

                    $_SESSION['cordamensagem']="green";
                    $_SESSION['mensagem']="Obrigado, sua indicação vai nos permitir <strong>amolar nosso machado</strong>, obrigado por contribuir no projeto EC10.";
                else:
                    $_SESSION['cordamensagem']="red";
                    $_SESSION['mensagem']="Não conseguimos inserir esta sugestão, tente novamente mais tarde, por favor.";
                endif;
            else:
                $_SESSION['cordamensagem']="red";
                $_SESSION['mensagem']="Encontramos uma sugestão identica a esta, obrigado por sua indicação.";
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
        <div class="content clearfix bkg-white">
            <div class="section-block tm-slider-parallax-container pb-30 small bkg-blue">
                <div class="row">
                    <div class="box rounded small bkg-white shadow">
                        <div class="column width-12">
                            <div class="title-container">
                                <div class="title-container-inner">
                                    <div class="row ">
                                        <div class="column width-12">
                                            <h1 class="mb-0">PROJETO ECLESIASTES 10.10</h1>
                                            <h4>O que você acredita que precisamos melhorar para alcançarmos a excelência?</h4>
                                            <p class="text-large color-charcoal mb-0 mb-mobile-20"><b>Se o machado está cego</b> e sua lâmina não foi afiada</a>, é preciso golpear com mais força; agir com sabedoria assegura o sucesso. (Eclesiastes 10.10 NVT) / O projeto "<strong>Afia a lâmina</strong>" é um movimento onde <strong>nós servimos como um corpo para melhorar e servirmos melhor.</strong></p>
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
                                    <div class="column width-12">
                                        <span class=" color-charcoal text-large weight-bold pb-10">Podemos contar com você pra implantar essa sugestão? (Se sim, informe seu nome e e-mail)</span>
                                        <div class="form-select form-element rounded medium">
                                            <select name="participar" tabindex="05" class="form-aux"
                                                data-label="Project Budget">
                                                <option value="Sim">Sim</option>
                                                <option value="Não">Não</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="column width-4">
                                        <label class="color-charcoal text-large"><strong>NOME (COMPLETO)</strong> (Opcional)</label>
                                        <input type="text" name="nome" <?php if(isset($_POST['btn-cadastrar'])): echo "value='".$_POST['nome']."'"; endif; ?> class="form-fname form-element large" placeholder="Digite seu nome" tabindex="01">
                                    </div>
                                    <div class="column width-4">
                                        <label class="color-charcoal text-large"><strong>E-MAIL</strong> (Opcional)</label>
                                        <input type="email" name="email"
                                            <?php if(isset($_POST['btn-cadastrar'])): echo "value='".$_POST['email']."'"; endif; ?>
                                            class="form-fname form-element large" placeholder="E-mail principal"
                                            tabindex="02">
                                    </div>
                                    <div class="column width-4">
                                        <label class="color-charcoal text-large">TELEFONE <strong>WHATSAPP</strong> (Opcional)</label>
                                        <input type="tel" name="whatsapp"
                                            <?php if(isset($_POST['btn-cadastrar'])): echo "value='".$_POST['email']."'"; endif; ?>
                                            class="form-fname form-element large" placeholder="219xxxx1234"
                                            tabindex="03">
                                    </div>
                                    <div class="column width-12">
                                        <span class=" color-charcoal text-large weight-bold pb-10">Você é membro da Emanuel (Opcional)</span>
                                        <div class="form-select form-element rounded medium">
                                            <select name="membro" tabindex="04" class="form-aux"
                                                data-label="Project Budget">
                                                <option value="Sim">Sim</option>
                                                <option value="Não">Não</option>
                                                <option value="Pretendo Ser">Pretendo ser</option>
                                                <option value="Não pretendo Ser">Não pretendo ser</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="column width-12">
                                        <span class=" color-charcoal text-large weight-bold pb-10">Você detectou algo em que tipo de encontro? (Opcional)</span>
                                        <div class="form-select form-element rounded medium">
                                            <select name="motivo" tabindex="05" class="form-aux"
                                                data-label="Project Budget">
                                                <option value="Não quero falar">Não quero falar</option>
                                                <option value="GC">GC</option>
                                                <option value="Coliseu">Coliseu</option>
                                                <option value="Culto semanal">Culto semanal</option>
                                                <option value="Culto de Domingo">Culto de Domingo</option>
                                                <option value="Evento das Mulheres">Evento das Mulheres</option>
                                                <option value="Evento dos Homens">Evento dos Homens</option>
                                                <option value="Evento dos Kids">Evento dos Kids</option>
                                                <option value="Outro">Outro</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="column width-12">
                                        <div class="column width-6 pb-20">
                                            <label class="color-charcoal text-large">Em uma escala de 0 a 10 o quanto você <strong>convidaria</strong> um amigo para Emanuel?</strong></label>
                                            <?
                                                for($nps=0; $nps<11; $nps++){
                                            ?>
                                            <span>
                                                <input tabindex="06" id="<?echo $nps; ?>" value="<?echo $nps; ?>" class="form-element radio small" name="nps" type="radio">
                                                <label for="<?echo $nps; ?>" class="radio-label"><span class="text-medium"><?echo $nps; ?></span></label>
                                            </span>
                                            <?}?>
                                        </div>
                                        <div class="column width-3 pb-20">
                                            <label class="color-charcoal text-large">Como você classificaria a <strong>músicas</strong>?</label>
                                            <span>
                                                <input tabindex="07" id="Boa" value="Boa" class="form-element radio small v-align-middle" name="musica" type="radio">
                                                <label for="Boa" class="radio-label"><span class="text-medium">Boa</span></label>
                                            </span>
                                            <span>
                                                <input tabindex="08" id="Ruim" value="Ruim" class="form-element radio small v-align-middle" name="musica" type="radio">
                                                <label for="Ruim" class="radio-label"><span class="text-medium">Ruim</span></label>
                                            </span>
                                        </div>
                                        <div class="column width-3 pb-20">
                                            <label class="color-charcoal text-large">Como você classificaria a <strong>palavra</strong>?</label>
                                            <span>
                                                <input tabindex="09" id="BoaPalavra" value="Boa" class="form-element radio small v-align-middle" name="palavra" type="radio">
                                                <label for="BoaPalavra" class="radio-label"><span class="text-medium">Boa</span></label>
                                            </span>
                                            <span>
                                                <input tabindex="10" id="RuimPalavra" value="Ruim" class="form-element radio small v-align-middle" name="palavra" type="radio">
                                                <label for="RuimPalavra" class="radio-label"><span class="text-medium">Ruim</span></label>
                                            </span>
                                        </div>
                                        <div class="column width-4 pb-20">
                                            <label class="color-charcoal text-large">Como você classificaria nosso <strong>horário</strong>?</label>
                                            <span>
                                                <input tabindex="11" id="BomHorario" value="Bom" class="form-element radio small v-align-middle" name="horario" type="radio">
                                                <label for="BomHorario" class="radio-label"><span class="text-medium">Bom</span></label>
                                            </span>
                                            <span>
                                                <input tabindex="12" id="RuimHorario" value="Ruim" class="form-element radio small v-align-middle" name="horario" type="radio">
                                                <label for="RuimHorario" class="radio-label"><span class="text-medium">Ruim</span></label>
                                            </span>
                                        </div>
                                        <div class="column width-4 pb-20">
                                            <label class="color-charcoal text-large">Como você classificaria nossa <strong>localização</strong>?</label>
                                            <span>
                                                <input tabindex="13" id="BoaLocalizacao" value="Boa" class="form-element radio small v-align-middle" name="localizacao" type="radio">
                                                <label for="BoaLocalizacao" class="radio-label"><span class="text-medium">Boa</span></label>
                                            </span>
                                            <span>
                                                <input tabindex="14" id="RuimLocalizacao" value="Ruim" class="form-element radio small v-align-middle" name="localizacao" type="radio">
                                                <label for="RuimLocalizacao" class="radio-label"><span class="text-medium">Ruim</span></label>
                                            </span>
                                        </div>
                                        <div class="column width-4 pb-20">
                                            <label class="color-charcoal text-large">Como você classificaria nossa <strong>Recepção</strong>?</label>
                                            <span>
                                                <input tabindex="15" id="BoaRecepcao" value="Boa" class="form-element radio small v-align-middle" name="recepcao" type="radio">
                                                <label for="BoaRecepcao" class="radio-label"><span class="text-medium">Boa</span></label>
                                            </span>
                                            <span>
                                                <input tabindex="16" id="RuimRecepcao" value="Ruim" class="form-element radio small v-align-middle" name="recepcao" type="radio">
                                                <label for="RuimRecepcao" class="radio-label"><span class="text-medium">Ruim</span></label>
                                            </span>
                                        </div>
                                        <div class="column width-6 pb-20">
                                            <label class="color-charcoal text-large">Em uma escala de 0 a 10 como você classifica nossa <strong>organização?</strong></label>
                                            <?
                                                for($org=0; $org<11; $org++){
                                                    $orgR="organiza$org";
                                            ?>
                                            <span>
                                                <input tabindex="17" id="<?echo $orgR; ?>" value="<?echo $org; ?>" class="form-element radio small v-align-middle" name="organizacao" type="radio">
                                                <label for="<?echo $orgR; ?>" class="radio-label"><span class="text-medium"><?echo $org; ?></span></label>
                                            </span>
                                            <?}?>
                                        </div>
                                        <div class="column width-6 pb-20">
                                            <label class="color-charcoal text-large">Em uma escala de 0 a 10 como você classifica a <strong>limpeza</strong> da Igreja?</label>
                                            <?
                                                for($limp=0; $limp<11; $limp++){
                                                    $limpR="limpeza$limp";
                                            ?>
                                            <span>
                                            <input tabindex="28" id="<?echo $limpR; ?>" value="<?echo $limp; ?>" class="form-element radio small v-align-middle" name="limpeza" type="radio">
                                            <label for="<?echo $limpR; ?>" class="radio-label"><span class="text-medium"><?echo $limp; ?></span></label>
                                            </span>
                                            <?}?>
                                        </div>
                                        
                                        <div class="column width-12">
                                            <label class="color-charcoal text-large"><strong>O que precisa ser melhorado</strong> na Emanuel?</label>
                                            <textarea type="text" name="descricao" class="form-name border-blue form-element medium" placeholder="Descreva o que você acredita que precisa ser melhorado na Emanuel para alcançarmos a excelência?" tabindex="29" required></textarea>
                                        </div>
                                        <div class="column width-12">
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
                                                        class="text-large color-white">Enviar</span></button>
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