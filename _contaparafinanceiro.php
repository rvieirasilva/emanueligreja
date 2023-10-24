<!DOCTYPE html>
	<?php
		session_start();
        require_once "_con.php";
        require_once "_configuracao.php";
        
?>
<html lang="pt-br">
	<?php
		require_once "_head.php";
	?>
<body class="shop catalogue-products">

    <!-- Content -->
    <div class="content clearfix">
        <?php
            if(isset($_POST['btn-conta'])):
                $conta      = mysqli_escape_string($con, $_POST['conta']);

                $nomedaconta                = mysqli_escape_string($con, $_POST['nomedaconta']);
                $tipodeconta                = mysqli_escape_string($con, $_POST['tipodeconta']);
                $banco                      = mysqli_escape_string($con, $_POST['banco']);
                $agencia                    = mysqli_escape_string($con, $_POST['agencia']);
                $agenciaDig                 = mysqli_escape_string($con, $_POST['agenciaDig']);
                    if(!empty($agencia) AND !empty($agenciaDig)): //Primeiro pedido de saque -- agência c/ digíto
                        $agencia = $agencia.'-'.$agenciaDig;
                    elseif(!empty($agencia) AND empty($agenciaDig)): //Primeiro pedido de saque -- agência s/ digíto
                        $agencia = $agencia;
                    endif;
                $conta                      = mysqli_escape_string($con, $_POST['conta']);
                $contaDig                   = mysqli_escape_string($con, $_POST['contaDig']);
                    if(!empty($conta) AND !empty($contaDig)): //Primeiro pedido de saque -- agência c/ digíto
                        $conta = $conta.'-'.$contaDig;
                    elseif(!empty($conta) AND empty($contaDig)): //Primeiro pedido de saque -- agência s/ digíto
                        $conta = $conta;
                    endif;
                $operador                   = mysqli_escape_string($con, $_POST['operador']);
                $titular                    = mysqli_escape_string($con, $_POST['titular']);
                $cpf                        = mysqli_escape_string($con, $_POST['cpf']);

                //Impedir inserção duplicada.
                $bConta = "SELECT conta FROM contasfinanceiro WHERE empresa='emanuel' AND urldaempresa='igrejaemanuel' AND conta='$conta'";
                    $rConta = mysqli_query($con, $bConta);
                    $nConta=mysqli_num_rows($rConta);
                
                if($nConta < 1):
                    //Limitar cinco contas
                    $fivecontas="SELECT id FROM contasfinanceiro WHERE empresa='emanuel' AND urldaempresa='igrejaemanuel'";
                        $rFC=mysqli_query($con, $fivecontas);
                        $nFC=mysqli_num_rows($rFC);

                            $codigodaconta = $nFC.mt_rand(01, 999);

                    if($nFC < 5):
                        $addConta = "INSERT INTO contasfinanceiro (empresa, codigodoparceiro, urldaempresa, dia, mes, ano, representante, codigodorepresentante, codigodaconta, nomedaconta, Banco, agencia, conta, operador, titular, cpf, tipodeconta) VALUES ('emanuel', '0001', 'igrejaemanuel', '$dia', '$mes', '$ano', '$nomedousuario', '$matriculausuario', '$codigodaconta',  '$nomedaconta', '$banco', '$agencia', '$conta', '$operador', '$titular', '$cpf', '$tipodeconta')";
                        mysqli_query($con, $addConta);
                    endif;
                endif;
            endif;
        ?>
        <form charset="UTF-8" class="form" action="" method="post" enctype="Multipart/form-data">
            <div class="column width-3">
                <label class="text-large left color-charcoal"><strong>Nome da conta</strong></label>
                <div class="field-wrapper">
                    <input type="text" name="nomedaconta" class="form-email form-element rounded medium" tabindex="4" placeholder="Adicionar conta." required>
                </div>
            </div>
            <div class="column width-6">
                <label class="text-large left color-charcoal"><strong>Tipo de conta</strong></label>
                <div class="form-select form-element rounded medium">
                    <select name="tipodeconta" tabindex="4" class="form-aux" data-label="Project Budget">
                        <option>Corrente</option>
                        <option>Poupança</option>
                    </select>
                </div>
            </div>
            <div class="column width-3">
                <label class="text-large left color-charcoal"><strong>Selecione o seu banco</strong></label>
                <div class="form-select form-element rounded medium">
                    <select name="banco" tabindex="4" class="form-aux" data-label="Project Budget">
                            <option>Carteira</option>
                        <?php
                            //Buscar lista de bancos
                            $bBancos = "SELECT codigo, banco FROM bancos GROUP BY banco";
                            $rBancos = mysqli_query($con, $bBancos);
                                while($dBancos=mysqli_fetch_array($rBancos)):
                        ?>
                            <option><?php echo $dBancos['banco']; ?></option>
                        <?php
                            //Fim da busca da lista de bancos
                            endwhile;
                        ?>
                    </select>
                </div>
            </div>
            
            <div class="column width-4">
                <label class="text-large left color-charcoal"><strong>Agência</strong></label>
                <div class="field-wrapper">
                    <input type="text" name="agencia" class="form-email form-element rounded medium" tabindex="4" placeholder="Nº da Agência" required>
                </div>
            </div>
            <div class="column width-2">
                <label class="text-large left color-charcoal"><strong>Digíto</strong></label>
                <div class="field-wrapper">
                    <input type="text" name="agenciaDig" class="form-email form-element rounded medium" tabindex="4" placeholder="Digito" required>
                </div>
            </div>
            <div class="column width-4">
                <label class="text-large left color-charcoal"><strong>Conta</strong></label>
                <div class="field-wrapper">
                    <input type="text" name="conta" class="form-email form-element rounded medium" tabindex="4" placeholder="Nº da Conta" required>
                </div>
            </div>
            <div class="column width-2">
                <label class="text-large left color-charcoal"><strong>Digíto</strong></label>
                <div class="field-wrapper">
                    <input type="text" name="contaDig" class="form-email form-element rounded medium" tabindex="4" placeholder="Digito" required>
                </div>
            </div>

            <div class="column width-2">
                <label class="text-large left color-charcoal"><strong>Operador</strong></label>
                <div class="field-wrapper">
                    <input type="text" name="operador" class="form-email form-element rounded medium" tabindex="4" placeholder="Operador" required>
                </div>
            </div>
            <div class="column width-6">
                <label class="text-large left color-charcoal"><strong>Titular</strong></label>
                <div class="field-wrapper">
                    <input type="text" name="titular" class="form-email form-element rounded medium" tabindex="4" placeholder="Nome completo do titular da conta" required>
                </div>
            </div>
            <div class="column width-4">
                <label class="text-large left color-charcoal"><strong>CPF</strong></label>
                <div class="field-wrapper">
                    <input type="number" name="cpf" class="form-email form-element rounded medium" tabindex="4" placeholder="CPF" required>
                </div>
            </div>
            
            <div class="column width-2">
                <button type="submit" value="" name='btn-conta' class="form-submit button pill small bkg-green bkg-hover-green color-white color-hover-white hard-shadow"><span class="icon-plus color-white color-hover-white circled"></span></button>
            </div>
        </form>
        
        <div class="column width-12 pt-150"></div>
    </div>
    <!-- Content End -->

	<!-- Js -->
	<?php
		require_once "_scripts.php";
	?>
</body>
</html>