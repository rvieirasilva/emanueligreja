<?php
    session_start();

    require ('../configProduto.php');
    require ('../lib/vendor/autoload.php');
        
    //&Jj7
    #Variables
    $email                                      = filter_input(INPUT_POST,'email',FILTER_VALIDATE_EMAIL);
    $cardNumber                                 = filter_input(INPUT_POST,'cardNumber',FILTER_DEFAULT);
    $securityCode                               = filter_input(INPUT_POST,'securityCode',FILTER_DEFAULT);
    $cardExpirationMonth                        = filter_input(INPUT_POST,'cardExpirationMonth',FILTER_DEFAULT);
    $cardExpirationYear                         = filter_input(INPUT_POST,'cardExpirationYear',FILTER_DEFAULT);
    $cardholderName                             = filter_input(INPUT_POST,'cardholderName',FILTER_DEFAULT);
    $docType                                    = filter_input(INPUT_POST,'docType',FILTER_DEFAULT);
    $docNumber                                  = filter_input(INPUT_POST,'docNumber',FILTER_DEFAULT);
    $installments                               = filter_input(INPUT_POST,'installments',FILTER_DEFAULT);
    $amount                                     = filter_input(INPUT_POST,'amount',FILTER_DEFAULT);
    $description                                = filter_input(INPUT_POST,'description',FILTER_DEFAULT);
    $paymentMethodId                            = filter_input(INPUT_POST,'paymentMethodId',FILTER_DEFAULT);
    $token                                      = filter_input(INPUT_POST,'token',FILTER_DEFAULT);

    //Identificação da empresa para nosso BD
    $codigodocliente_Form                       = filter_input(INPUT_POST,'codigodocliente_Form',FILTER_DEFAULT);
    $codigodaempresa                            = filter_input(INPUT_POST,'codigodaempresa',FILTER_DEFAULT);
    $urldaempresa_form                          = filter_input(INPUT_POST,'urldaempresa',FILTER_DEFAULT);
    $empresa                                    = filter_input(INPUT_POST,'empresa',FILTER_DEFAULT);
    $emaildaloja                                = filter_input(INPUT_POST,'emaildaempresa',FILTER_DEFAULT);
    $emailrepresentantedaloja                   = filter_input(INPUT_POST,'emailrepresentantedaempresa',FILTER_DEFAULT);
    $pontosgeradosparaocliente                  = filter_input(INPUT_POST,'pontosgeradosparaocliente',FILTER_DEFAULT);

    $formadepagamento                           = filter_input(INPUT_POST,'formadepagamento',FILTER_DEFAULT); 
    $TipoDeEntrega                              = filter_input(INPUT_POST,'entrega',FILTER_DEFAULT); 
    $PrazoDeEntrega                             = filter_input(INPUT_POST,'prazodeentrega',FILTER_DEFAULT);
    $ValorDaEntrega                             = filter_input(INPUT_POST,'valordaentrega',FILTER_DEFAULT); 
    $carrinhodocliente                          = filter_input(INPUT_POST,'codigodocarrinho',FILTER_DEFAULT);
      $codigodocarrinho                         = filter_input(INPUT_POST,'codigodocarrinho',FILTER_DEFAULT); //Duplicado caso usem essa string abaixo.

    $dia_do_pagamento                           = date('d');
    $mes_do_pagamento                           = date('m');
    $ano_do_pagamento                           = date('Y');


    $anoatual                                   = date('y');
    $ano                                        = date('Y');
    $dia                                        = date('d');
    $data                                       = date('d/m/Y');
    $dataeng                                    = date('Y-m-d');
    $mes                                        = date('M');
    $mesnumero                                  = date('m');
    $hora                                       = date('H:m:s');
    $horaa                                      = date('h');
    $horab                                      = date('i');

    
    
    #Method
    MercadoPago\SDK::setAccessToken(A_TOKEN);
    $payment = new MercadoPago\Payment();
    $payment->transaction_amount = $amount;
    $payment->token = $token;
    $payment->description = $description;
    $payment->installments = $installments;
    $payment->payment_method_id = $paymentMethodId;
    $payment->payer = array(
        "email" => $email
    );
    $payment->save();

        //echo $payment->status;  //Segundo a aula do youtube

        //Controle para pagamento do cliente.

        //Status            Status_detail
        //approved            accredited
        //in_process          pending_contingency         Em até 2 dias úteis informaremos por e-mail o resultado.
        //in_process          pending_review_manual       Em até 2 dias [...] foi aprovado ou se precisamos de mais //informações.
        //rejected

    $status_Do_Pagamento    = $payment->status; //Verifica se foi aprovado ou não.
    $Situacao_Do_Pagamento  = $payment->status_detail; //Verifica o motivo do status do pagamento.
    $Forma_Do_Pagamento     = $payment->payment_method_id; //Verifica o motivo do status do pagamento.
    
    //$payment_id             = $payment->$payment_id; // Pega o ID do pagamento para em caso de ser pendente cancelar ele.

    //Informações para controle interno
    $ValorTotalPosParcelas      = $payment->transaction_details->total_paid_amount;
    $ValorDasParcelas           = $payment->transaction_details->installment_amount;
    $UltCardNumber              = $payment->transaction_details->last_four_digits;
        $UltCardNumber  = substr($UltCardNumber, -4);

    $taxadejurosdocartao    =   ( $ValorTotalPosParcelas / $amount ) - 1 ; // ( 107.25 / 90.79  ) - 1 = 0,18
    //    $taxadejurosdocartao = $taxadejurosdocartao * 100; //0,18 * 100 = 18.13

    $valordejurosdocartao   = $amount * $taxadejurosdocartao; //90.79 * 0.1813 = 16.46



    if($status_Do_Pagamento == 'approved'):
        $status_Do_Pagamento = 'APROVADO';
    elseif($status_Do_Pagamento == 'in_process'):
        $status_Do_Pagamento = 'PENDENTE';
    elseif($status_Do_Pagamento == 'rejected'):
        $status_Do_Pagamento = 'REJEITADO';
    endif;
    
        //$status_Do_Pagamento == 'approved';
        //    $status_Do_Pagamento = 'APROVADO';
        //    $statusDoUsuario    = 'ATIVO';   

    //Erros
    $ErroDoPagamento    = $payment->error->message;
    $DescricaoDoErro    = $payment->error->causes[0]->description;
    $CodigoDoErro       = $payment->error->status;
    
    //echo '<pre>',print_r($payment),'</pre>'; //Segundo a aula do youtube

    //echo $ErroDoPagamento;
    //echo "<br>";
    //echo $CodigoDoErro;

    //echo $status_Do_Pagamento;
    //echo "<br>";
    //echo $Situacao_Do_Pagamento;

    
    include "../_con.php"; // Conexão.

    
    //Cria uma token para redirecioanar o usuário para página de acesso.
    $buscartoken = "SELECT token FROM tokens GROUP BY token ORDER BY rand(), id DESC LIMIT 1";
    $rbuscartoken = mysqli_query($con, $buscartoken);
        $dbuscartoken=	mysqli_fetch_array($rbuscartoken);
                
        $_SESSION['token']  = $dbuscartoken['token'];
        $token              = $dbuscartoken['token'];
        
    if(!empty($ErroDoPagamento)):

        if($CodigoDoErro == '400'):
            $MensagemDoPagamento = "Ocorreu uma falha ao processar seu pagamento <strong>nossa equipe foi notificada</strong> tente novamente em alguns minutos.";
        elseif($CodigoDoErro == '205'):
            $MensagemDoPagamento = "Digite o número do seu cartão";
        elseif($CodigoDoErro == '205'):
            $MensagemDoPagamento = "Digite o mês de vencimento do seu cartão.";
        elseif($CodigoDoErro == '209'):
            $MensagemDoPagamento = "Digite o ano de vencimento do seu cartão.";
        elseif($CodigoDoErro == '212'):
            $MensagemDoPagamento = "Informe o tipo do seu documento";
        elseif($CodigoDoErro == '213'):
            $MensagemDoPagamento = "Informe seu documento";
        elseif($CodigoDoErro == '214'):
            $MensagemDoPagamento = "Informe o número do seu documento";
        elseif($CodigoDoErro == '220'):
            $MensagemDoPagamento = "Não foi possível identificar o banco do seu cartão.";
        elseif($CodigoDoErro == '221'):
            $MensagemDoPagamento = "Digite nome e sobrenome da mesma forma que está em seu cartão.";
        elseif($CodigoDoErro == '224'):
            $MensagemDoPagamento = "Digite seu código de segurança (CVV).";
        elseif($CodigoDoErro == 'E301'):
            $MensagemDoPagamento = "Há algo de errado com o número do seu cartão.";
        elseif($CodigoDoErro == 'E302'):
            $MensagemDoPagamento = "Há algo de errado com o código CVV do seu cartão.";
        elseif($CodigoDoErro == '316'):
            $MensagemDoPagamento = "Digite um nome válido.";
        elseif($CodigoDoErro == '322'):
            $MensagemDoPagamento = "Confira seu documento.";
        elseif($CodigoDoErro == '323'):
            $MensagemDoPagamento = "Confira seu documento.";
        elseif($CodigoDoErro == '324'):
            $MensagemDoPagamento = "Confira seu documento.";
        elseif($CodigoDoErro == '325'):
            $MensagemDoPagamento = "Confira a data de vencimento do seu cartão";
        elseif($CodigoDoErro == '326'):
            $MensagemDoPagamento = "Confira a data de vencimento do seu cartão";
        elseif($CodigoDoErro == 'default'):
            $MensagemDoPagamento = "Confira os dados inseridos.";
        elseif($CodigoDoErro == '106'):
            $MensagemDoPagamento = "O <strong>MercadoPago</strong> informou que o seu cartão não pode efetuar pagamentos a usuários de outros países.";
        elseif($CodigoDoErro == '109'):
            $MensagemDoPagamento = "O <strong>MercadoPago</strong> informou que o seu cartão não processa pagamentos parcelados. Escolha outro cartão ou outra forma de pagamento";
        elseif($CodigoDoErro == '126'):
            $MensagemDoPagamento = "O <strong>MercadoPago</strong> informou que não conseguimos processar seu pagamento";
        elseif($CodigoDoErro == '129'):
            $MensagemDoPagamento = "O <strong>MercadoPago</strong> informou que o seu cartão não processa pagamentos para o valor selecionado. Escolha outro cartão ou outra forma de pagamento.";
        elseif($CodigoDoErro == '145'):
            $MensagemDoPagamento = "O <strong>MercadoPago</strong> informou que uma das partes com a qual está tentando realizar o pagamento é um usuário de teste e a outra é um usuário real";
        elseif($CodigoDoErro == '150'):
            $MensagemDoPagamento = "O <strong>MercadoPago</strong> informou que você não pode efetuar pagamentos";
        elseif($CodigoDoErro == '151'):
            $MensagemDoPagamento = "O <strong>MercadoPago</strong> informou que você não pode efetuar pagamentos";
        elseif($CodigoDoErro == '160'):
            $MensagemDoPagamento = "O <strong>MercadoPago</strong> informou que não conseguimos processar seu pagamento";
        elseif($CodigoDoErro == '204'):
            $MensagemDoPagamento = "O <strong>MercadoPago</strong> informou que sua bandeira não está disponível para pagamento nesse momento";
        elseif($CodigoDoErro == '801'):
            $MensagemDoPagamento = "O <strong>MercadoPago</strong> informou que você realizou um pagamento similar há poucos instantes e por isso não foi possível concluir esta operação. Tente novamente mais tarde.";
        else:
            $MensagemDoPagamento = "O <strong>MercadoPago</strong> informou que ocorreu um erro ao processar seu pagamento. <srtong>Nossa equipe foi notificada e está atuando para restabelecer as operações</srtong>. Tente novamente mais tarde.";
        endif;
        
        //Enviar e-mail  para a empresa.
        include "../_enviaremail.php"; // Configuração para envio de e-mail.
        $mail->setFrom("contato@eupedi.com", "eupedi"); //Enviado por
        $mail->addAddress("rafael@faelvieirasilva.com.br", "CEO Rafael");     // Add a recipient 
            //$mail->addAddress("ellen@example.com");               // Name is optional
            //$mail->addReplyTo("$email", "$empresa"); //Para quem deve responder.
            //$mail->addCC("$email"); //Cópia do email.
        $mail->addBCC("faelvieirasilva+xcolxwuy3pqjrqef5by7@boards.trello.com");	//Cópia oculta do e-mail
            //$mail->addAttachment("/var/tmp/file.tar.gz");         // Add attachments
            //$mail->addAttachment("/tmp/image.jpg", "new.jpg");    // Optional name
        $mail->isHTML(true);                                  // Set email format to HTML
        $mail->Subject = "URGENTE eupedi - Erro para receber pagamento.";
        $mail->Body    = "eupedi.
                        <p>Ocorreu um erro ao processar o pagamento do carrinho $carrinhodocliente para o cliente: $codigodocliente_Form na compra da empresa $urldaempresa_form. Ocorreu o erro: $CodigoDoErro com a seguinte decrição: $DescricaoDoErro e foi enviada esta mensagem para o usuário; $MensagemDoPagamento</p>
                        <p>Verifique, estamos perdendo dinheiro!!!</p>
                        <h3><strong>eupedi</strong></h3>
                        <p></p>";
        $mail->AltBody    = "eupedi.
                        <p>Ocorreu um erro ao processar o pagamento. O status veio como: $status_Do_Pagamento por este motivo: $Situacao_Do_Pagamento</p>
                        <p>Verifique, estamos perdendo dinheiro!!!</p>
                        <p></p>
                        <h3><strong>eupedi</strong></h3>
                        <p></p>"; // Para o trello!
        $mail->send();

        //Se ficar pendente ele será direcionado para página minha conta, onde receberá a informação que estamos aguardando o pagamento ser liberado.
        $_SESSION['cordamensagem'] = 'orange';
        $_SESSION['mensagem'] = $MensagemDoPagamento;

        header("location:../comprafinalizar?cc=$codigodocliente_Form&token=$token&car=$codigodocarrinho");
            
    else:

        if($status_Do_Pagamento == 'APROVADO' OR $status_Do_Pagamento == 'approved'):

            //Buscar código do parceiro, carrinho, urldaempresa, 
            $bFinanc = "SELECT codigodaempresa, codigodocarrinho, urldaempresa FROM carrinho WHERE codigodocarrinho='$carrinhodocliente'";
              $rFinanc = mysqli_query($con, $bFinanc);
                $dFinanc = mysqli_fetch_array($rFinanc);

            $codigodoparceiro_carrinho  = $dFinanc['codigodaempresa'];
            $codigodocarrinho_carrinho  = $dFinanc['codigodaempresa'];
            $urldaempresa_carrinho      = $dFinanc['codigodaempresa'];
            
            $atformadepagamento="UPDATE carrinho SET formadepagamento='$tipodepagamento', tempodeentregaestimado='$PrazoDeEntrega', tipodefreteindicada='$TipoDeEntrega', valordofreteindicada='$ValorDaEntrega', statusdocarrinho='PENDENTE' WHERE codigodocarrinho='$carrinhodocliente'";

            if(mysqli_query($con, $atformadepagamento)):

                
                //Com o pagamento aprovado ele é direcionado para página inicial
                $bDCar = "SELECT sum(quantidade) as quantidadedeitensnocarrinho, cupomdedesconto, descontodocupom, cliente, codigodocliente, emaildocliente, telefonedocliente FROM carrinho WHERE codigodocarrinho='$carrinhodocliente'";
                $rDCar = mysqli_query($con, $bDCar);
                    //$nDCar = mysqli_num_rows($rDCar);
                    $dDCar = mysqli_fetch_array($rDCar);

                $quantidadedeitensnocarrinho    = $dDCar['quantidadedeitensnocarrinho'];
                $codigodedesconto               = $dDCar['cupomdedesconto'];
                $desconto                       = $dDCar['descontodocupom'];
                $cliente                        = $dDCar['cliente'];
                $codigodocliente                = $dDCar['codigodocliente'];
                $emaildocliente                 = $dDCar['emaildocliente'];
                $telefonedocliente              = $dDCar['telefonedocliente'];


                //Enviar e-mail para o cliente informando o pedido finalizado.
                include "../_enviaremail.php"; // Configuração para envio de e-mail.
                $mail->setFrom("contato@eupedi.com", "$urldaempresa_form"); //Enviado por
                $mail->addAddress("$emaildocliente", "$nomedocliente");     // Add a recipient 
                    //$mail->addAddress("ellen@example.com");               // Name is optional
                    //$mail->addReplyTo("mensagem@eupedi.com", "$urldaempresa_form"); //Para quem deve responder.
                    //$mail->addCC("$emaildoresponsavel"); //Cópia do email.
                $mail->addBCC("faelvieirasilva+xcolxwuy3pqjrqef5by7@boards.trello.com");	//Cópia oculta do e-mail
                    //$mail->addAttachment("/var/tmp/file.tar.gz");         // Add attachments
                    //$mail->addAttachment("/tmp/image.jpg", "new.jpg");    // Optional name
                $mail->isHTML(true);                                  // Set email format to HTML
                $mail->Subject = "$urldaempresa_form, seu pedido foi finalizado.";
                $mail->Body    = "Olá, $nomedocliente.
                                <p>Seu pedido foi finalizado com sucesso.<p>
                                <p></p>
                                <p>Você pode acompanhar o status do seu pedido em nossa página, basta acessar, abrir o menu e clicar em: <strong> Acompanhar pedido</strong></p>
                                    <br>
                                    <h2><strong>Código do seu pedido</strong> $carrinhodocliente</h2>.</p>
                                <p></p>
                                <p><h3><strong>$urldaempresa_form</strong></h3></p>
                                <p></p>
                                ";
                $mail->AltBody = "Olá, $nomedocliente.
                                <p>Seu pedido foi finalizado com sucesso.<p>
                                <p></p>
                                <p>Você pode acompanhar o status do seu pedido em nossa página, basta acessar, abrir o menu e clicar em: <strong> Acompanhar pedido</strong></p>
                                    <br>
                                    <h2><strong>Código do seu pedido</strong> $carrinhodocliente</h2>.</p>
                                <p></p>
                                <p><h3><strong>$urldaempresa_form</strong></h3></p>
                                <p></p>
                                "; // Para o trello!
                if($mail->send()):
                    //Enviar e-mail para o cliente informando o pedido finalizado.
                    include "_enviaremail.php"; // Configuração para envio de e-mail.
                    $mail->setFrom("noreply@eupedi.com", "EuPedi"); //Enviado por
                    $mail->addAddress("$emaildaloja", "$urldaempresa");     // Add a recipient 
                        //$mail->addAddress("ellen@example.com");               // Name is optional
                    $mail->addReplyTo("noreply@eupedi.com", "EuPedi"); //Para quem deve responder.
                    $mail->addCC("$emailrepresentantedaloja"); //Cópia do email.
                    $mail->addBCC("faelvieirasilva+xcolxwuy3pqjrqef5by7@boards.trello.com");	//Cópia oculta do e-mail
                        //$mail->addAttachment("/var/tmp/file.tar.gz");         // Add attachments
                        //$mail->addAttachment("/tmp/image.jpg", "new.jpg");    // Optional name
                    $mail->isHTML(true);                                  // Set email format to HTML
                    $mail->Subject = "$urldaempresa, mais um pedido finalizado";
                    $mail->Body    = "Cliente: $nomedocliente.
                                    <p>Seu pedido foi finalizado com sucesso.<p>
                                    <h2><strong>Pago no crédito</strong></h2>.
                                    <h2><strong>Código do seu pedido</strong> $carrinhodocliente</h2>.
                                    <p></p>
                                    ";
                    $mail->AltBody = "Cliente: $nomedocliente.
                                    <p>Seu pedido foi finalizado com sucesso.<p>
                                    <h2><strong>Pago no crédito</strong></h2>.
                                    <h2><strong>Código do seu pedido</strong> $carrinhodocliente</h2>.
                                    <p></p>
                                    "; // Para o trello!
                    $mail->send();
                endif;
                

                $BQtdOperacoes = "SELECT id FROM controledepagamento";
                $rBQO = mysqli_query($con, $BQtdOperacoes);
                    $nBQO = mysqli_num_rows($rBQO);

                $codigodaoperacao = $nBQO.mt_rand(99,9999);

                //Intermediacação do MercadoPago
                if($formadepagamento == 'credit_card'):
                    $taxadeintermediacao            = '4.99'; //04-06-2020 
                    $tipodepagamento = 'Crédito';
                elseif($formadepagamento == 'debit_card'):
                    $taxadeintermediacao            = '3.99'; //04-06-2020
                    $tipodepagamento = 'Débito';
                elseif($formadepagamento == 'dinheiro'):
                    $taxadeintermediacao            = '0'; //04-06-2020
                    $tipodepagamento = 'Dinheiro';
                endif;

                    $taxadeintermediacaoPercentual  = $taxadeintermediacao / 100;
                
                $valordaintermediacao           = $ValorTotalPosParcelas * $taxadeintermediacaoPercentual;
                    $valordaintermediacao   = number_format($valordaintermediacao, 2);

                if($installments > 1 AND $installments < 3):
                    //taxa de parcelamento
                        //Parcelas	Custo de parcelamento	Custo especial por oferecer sem juros
                        // 2                ~2,39%~          2,03%
                        // 3                4,78%            4,06%
                        // 4                7,17%            6,09%
                        // 5                8,99%            7,64%
                        // 6                10,49%           8,92%
                        // 7                11,83%           10,06%
                        // 8                12,49%           10,62%
                        // 9                13,21%           11,23%
                        // 10               14,59%           12,41%
                        // 11               16,00%           13,60%
                        // 12               17,41%           14,80%
                    
                        $CustoPorAbsorcaoDaTarifaParcelamento = '2.03';
                            $CustoPorAbsorcaoDaTarifaParcelamentoPercentual = $CustoPorAbsorcaoDaTarifaParcelamento / 100;

                    
                    $ValorDoCustoDasParcelas           = $ValorTotalPosParcelas * $CustoPorAbsorcaoDaTarifaParcelamentoPercentual;
                        $ValorDoCustoDasParcelas   = number_format($ValorDoCustoDasParcelas, 2);

                    $valordaintermediacao = $valordaintermediacao + $ValorDoCustoDasParcelas;
                endif;

                //Comissao
                if(empty($taxadecomissaoeupedi)):
                    $taxadecomissaoeupedi   = '2.99';
                endif;

                $taxadecomissao                 = $taxadecomissaoeupedi; 
                    $taxadecomissaoPercentual   = $taxadecomissao / 100;

                $valordecomissao    =   $ValorTotalPosParcelas * $taxadecomissaoPercentual;
                    $valordecomissao = number_format($valordecomissao, 2);

                $statusdaoperacao   =   $Situacao_Do_Pagamento;

                $tokendecriptografia = '';

                //Atualiza Status do Pagamento do Carrinho
                $PagCarrinho = "UPDATE carrinho SET formadepagamento='$tipodepagamento', carrinhofechadoem='$dataeng' WHERE codigodocarrinho = '$carrinhodocliente'";
                mysqli_query($con, $PagCarrinho);

                //Registrar na receita essa venda realizada com tipo de operação VCMP (Venda Com Marcado Pago)
                    $descricaoFinanceira = "Compra do carrinho: $carrinhodocliente";

                    //Liquidez da venda
                    $valorPrevistoFinanceiro = $amount - ($valordaintermediacao + $valordecomissao); //90.39 - (7.50 + 8.90)
                    $dataprevistaFinanceiro = $ano.'-'.$mesnumero.'-'.$dia; //2020-10-09
                        $dataprevistaFinanceiro = date(("Y-m-d"), strtotime($dataprevistaFinanceiro .'+ 2 days'));

                $RegistrarVendaNaReceita="INSERT INTO financeiro (empresa, codigodoparceiro, urldaempresa, dia, mes, ano, representante, codigodorepresentante, codigodaoperacao, descricao, valorprevisto, dataprevista, valorrealizado, datarealizada, recebidode, pagopor, anexo, tamanhodoarquivo, tipodeoperacao, categoria, centrodecusto, conta1, conta2, tipoderegistro) VALUES ('$empresa', '$codigodoparceiro_carrinho', '$urldaempresa_form', '$dia_do_pagamento', '$mesnumero', '$ano_do_pagamento', '$cliente', '$codigodocliente', '$codigodaoperacao', '$descricaoFinanceira', '$valorPrevistoFinanceiro', '$dataprevistaFinanceiro', '', '', 'Mercado Pago', '$cliente', '', '', 'VCMP', '', 'VCMP', '', '', 'RECEITA')";
                    mysqli_query($con, $RegistrarVendaNaReceita);

                //Registra o pagamento para controle
                $PagAprovado = "INSERT INTO controledepagamento (codigodaoperacao, dia, mes, ano, hora, codigodaempresa, urldaempresa, codigodocarrinho, valortotaldocarrinho, quantidadedeitensnocarrinho, codigodedesconto, desconto, cliente, codigodocliente, email, telefone, cardnumber, tipodedocumento, numerododocumento, valortotal, parcelas, taxadeintermediacao, valordaintermediacao, taxadecomissao, valordecomissao, statusdopagamento, statusdaoperacao, nota, tokendecriptografia, taxadejurosdocartao, valordejurosdocartao) VALUES ('$codigodaoperacao', '$dia_do_pagamento', '$mesnumero', '$ano_do_pagamento', '$hora', '$codigodaempresa', '$urldaempresa_form', '$carrinhodocliente', '$amount', '$quantidadedeitensnocarrinho', '$codigodedesconto', '$desconto', '$cliente', '$codigodocliente', '$emaildocliente', '$telefonedocliente', '', '$docType', '$docNumber', '$ValorTotalPosParcelas', '$installments', '$taxadeintermediacao', '$valordaintermediacao', '$taxadecomissao', '$valordecomissao', '$status_Do_Pagamento', '$statusdaoperacao', '', '$tokendecriptografia', '$taxadejurosdocartao', '$valordejurosdocartao')";
                if(mysqli_query($con, $PagAprovado)):

                    $_SESSION['cordamensagem'] = 'green';
                    $_SESSION['mensagem'] = "Parabéns pelo pedido, sua entrega chegará dentro do prazo informado";

                    //Enviar e-mail para cliente e empresa.
                    $bEnviarEmail="SELECT email FROM parceiros WHERE urldaempresa='$urldaempresa_form'";
                        $rEE=mysqli_query($con, $bEnviarEmail);
                        $dEE=mysqli_fetch_array($rEE);

                        $comunicarempresa                 = $dEE['email'];

                    //Enviar e-mail de boas vindas para a empresa.
                    //include "../_enviaremail.php"; // Configuração para envio de e-mail.
                    //$mail->setFrom("contato@eupedi.com", "$urldaempresa_form"); //Enviado por
                    //$mail->addAddress("$email", "$nomedocliente");     // Add a recipient 
                        //$mail->addAddress("ellen@example.com");               // Name is optional
                        //$mail->addReplyTo("$email", "$empresa"); //Para quem deve responder.
                        //$mail->addCC("$email"); //Cópia do email.
                    //$mail->addBCC("$emaildaempresa");	//Cópia oculta do e-mail
                        //$mail->addAttachment("/var/tmp/file.tar.gz");         // Add attachments
                        //$mail->addAttachment("/tmp/image.jpg", "new.jpg");    // Optional name
                    //$mail->isHTML(true);                                  // Set email format to HTML
                    //$mail->Subject = "$urldaempresa_form - Seu pedido $carrinhodocliente foi pago";
                    //$mail->Body    = "Olá $nomedocliente,
                    //                <p>Seu pagamento foi aprovado, acompanhe o seu pedido diretamente por sua conta.</p>
                    //                <h3><strong>$urldaempresa_form</strong></h3>
                    //                <p></p>";
                    //$mail->AltBody    = "Olá $nomedocliente,
                    //                <p>Seu pagamento foi aprovado, acompanhe o seu pedido diretamente por sua conta.</p>
                    //                <h3><strong>$urldaempresa_form</strong></h3>
                    //                <p></p>"; // Para o trello!
                    //$mail->send();

                    //Atualiza os pontos do cliente
                    $bPontosDoConsumidor = "SELECT pontos FROM consumidor WHERE codigodocliente='$codigodocliente' AND urldaempresa='$urldaempresa_form'";
                        $rPDC = mysqli_query($con, $bPontosDoConsumidor);
                        $dPDC = mysqli_fetch_array($rPDC);

                    $PontosAntigos = $dPDC['pontos'];
                    $NovosPontos   = $PontosAntigos + $pontosgeradosparaocliente;

                    $UpPontosConsumidor = "UPDATE consumidor SET pontos='$NovosPontos' WHERE codigodocliente='$codigodocliente' AND urldaempresa='$urldaempresa_form'";
                    mysqli_query($con, $UpPontosConsumidor);

                    //Atualiza o número de produtos disponíveis.
                    $bProdutosConsumidosDaEmpresa = "SELECT codigodoproduto, count(codigodoproduto) as produtosadquiridos from carrinho WHERE codigodocarrinho='$carrinhodocliente'";
                        $rPCDE = mysqli_query($con, $bProdutosConsumidosDaEmpresa);
                        while($dPCDE=mysqli_fetch_array($rPCDE)):
                            $ProdutoAdquirido               = $dPCDE['codigodoproduto'];
                            $QuantidadeDoProdutoAdquirido   = $dPCDE['produtosadquiridos'];

                            //Encontra o produto cadastrado
                            $bProdutoCadastradoPelaEmpresa = "SELECT quantidadedisponivel FROM produtos WHERE codigodoproduto='$ProdutoAdquirido' AND urldaempresa='$urldaempresa_form'";
                                $rPCPE = mysqli_query($con, $bProdutoCadastradoPelaEmpresa);
                                $dPCPE = mysqli_fetch_array($rPCPE);

                            $QuantidadeAntiga = $dPCPE['quantidadedisponivel'];

                            //Reduz a quantidade disponível
                            $NovaQuantidadeDisponivel = $QuantidadeAntiga - $QuantidadeDoProdutoAdquirido;

                            //Atualiza a quantidade.
                            $UpQuantidadeParaVenda = "UPDATE produtos SET quantidadedisponivel='$NovaQuantidadeDisponivel' WHERE urldaempresa='$urldaempresa_form' AND codigodoproduto='$ProdutoAdquirido'";
                            mysqli_query($con, $UpQuantidadeParaVenda);
                        endwhile;

                    //Altera o statuso do pagamento das compras em pendências pois ela foi forçada na página comprafinalizar para pagamento por cartão.
                    $UPAutorizacaoDeCompra = "UPDATE autorizacaodecompra SET statusdopagamento='PAGO' WHERE statusdopagamento='PENDENTE' AND codigodoconsumidor='$codigodocliente' AND urldaempresa='$urldaempresa_form' ";
                      mysqli_query($con, $UPAutorizacaoDeCompra);
                    
                    unset($_SESSION['carrinhoaberto']); //Fecha a sessão do carrinho para que o cliente possa comprar outros lanches abrindo outro carrinho se quiser.
                    header("location:../compraconcluida?cc=$codigodocliente&token=$token&car=$codigodocarrinho");
                else:
                    $_SESSION['cordamensagem'] = 'red';
                    $_SESSION['mensagem'] = "<strong>Verifique sua conexão</strong>. Seu pedido foi aprovado, mas houve um erro ao registrar essa operação, //verifique seu e-mail e entre em contato com nossa pizzaria para confirmar o seu pedido.";
            
                    header("location:../compraconcluida?cc=$codigodocliente&token=$token&car=$codigodocarrinho");
                endif;
            endif;
        elseif($status_Do_Pagamento == 'PENDENTE' OR $status_Do_Pagamento == 'in_process'):

            if($Situacao_Do_Pagamento == 'pending_contingency'):
                $MensagemDoPagamento = "O MercadoPago informou que o seu pagamento está pendente e estão processando o pagamento. Não se preocupe, em menos de 2 dias úteis informaremos por e-mail se foi creditado. <strong>Caso tenha sido creditado, solicite o storno e informe o número do seu carrinho ($carrinhodocliente), iremos averiguar e realizar o estorno.</strong>";
            elseif($Situacao_Do_Pagamento == 'pending_review_manual'):
                $MensagemDoPagamento = "O <strong>Mercado Pago</strong> informou que seu pagamento está pendente, por favor tente com outro método de pagamento. <strong>Atenção:</strong> Em menos de 2 dias úteis informaremos por e-mail se foi creditado ou se necessitamos de mais informação, e após sermos informados pelo Mercado Pago iniciaremos automáticamente o processo de estorno <strong>caso tenha sido creditado o valor</strong>.";
            endif;

            //in_process	        pending_contingency	                    Estamos processando o pagamento. Não se preocupe, em 
                                                                        //  menos de 2 dias úteis informaremos por e-mail se foi creditado.

            //in_process	        pending_review_manual	                Estamos processando seu pagamento. Não se preocupe, 
                                                                    //      em menos de 2 dias úteis informaremos por e-mail se foi  creditado ou se necessitamos de mais informação.
            
            //Cancela a cobrança por estar pendente
            //MercadoPago\SDK::setAccessToken(A_TOKEN);
            //$payment = MercadoPago\Payment::find_by_id($paymentMethodId);
            //$payment->status = "cancelled";
            //$payment->update();
            
            
            $bDCar = "SELECT sum(quantidade) as quantidadedeitensnocarrinho, cupomdedesconto, descontodocupom, cliente, codigodocliente, emaildocliente, telefonedocliente FROM carrinho WHERE codigodocarrinho='$carrinhodocliente'";
            $rDCar = mysqli_query($con, $bDCar);
                //$nDCar = mysqli_num_rows($rDCar);
                $dDCar = mysqli_fetch_array($rDCar);

            $quantidadedeitensnocarrinho    = $dDCar['quantidadedeitensnocarrinho'];
            $codigodedesconto               = $dDCar['cupomdedesconto'];
            $desconto                       = $dDCar['descontodocupom'];
            $cliente                        = $dDCar['cliente'];
            $codigodocliente                = $dDCar['codigodocliente'];
            $emaildocliente                 = $dDCar['emaildocliente'];
            $telefonedocliente              = $dDCar['telefonedocliente'];


            $BQtdOperacoes = "SELECT id FROM controledepagamento";
            $rBQO = mysqli_query($con, $BQtdOperacoes);
                $nBQO = mysqli_num_rows($rBQO);

            $codigodaoperacao = $nBQO.mt_rand(99,9999);

            //Intermediacação do MercadoPago
            if($formadepagamento == 'credit_card'):
                $taxadeintermediacao            = '4.99'; //04-06-2020 
                $tipodepagamento = 'Crédito';
            elseif($formadepagamento == 'debit_card'):
                $taxadeintermediacao            = '3.99'; //04-06-2020
                $tipodepagamento = 'Débito';
            elseif($formadepagamento == 'dinheiro'):
                $taxadeintermediacao            = '0'; //04-06-2020
                $tipodepagamento = 'Dinheiro';
            endif;
            
                $taxadeintermediacaoPercentual  = $taxadeintermediacao / 100;
            
            $valordaintermediacao           = $ValorTotalPosParcelas * $taxadeintermediacaoPercentual;
                $valordaintermediacao       = number_format($valordaintermediacao, 2);

            //Comissao
            if(empty($taxadecomissaoeupedi)):
                $taxadecomissaoeupedi   = '2.99';
            endif;

            $taxadecomissao                 = $taxadecomissaoeupedi; 
                $taxadecomissaoPercentual   = $taxadecomissao / 100;

            $valordecomissao    =   $ValorTotalPosParcelas * $taxadecomissaoPercentual;
                $valordecomissao = number_format($valordecomissao, 2);

            $statusdaoperacao   =   $Situacao_Do_Pagamento;

            $tokendecriptografia = '';

            //Atualiza Status do Pagamento do Carrinho devido o pagamento não ter sido aprovado.
            $PagCarrinho = "UPDATE carrinho SET formadepagamento='', statusdocarrinho='ABERTO' WHERE codigodocarrinho = '$carrinhodocliente'";
            mysqli_query($con, $PagCarrinho);

            
            //Enviar e-mail  para a empresa.
            include "../_enviaremail.php"; // Configuração para envio de e-mail.
            $mail->setFrom("contato@eupedi.com", "eupedi"); //Enviado por
            $mail->addAddress("rafael@faelvieirasilva.com.br", "CEO Rafael");     // Add a recipient 
                //$mail->addAddress("ellen@example.com");               // Name is optional
                //$mail->addReplyTo("$email", "$empresa"); //Para quem deve responder.
                //$mail->addCC("$email"); //Cópia do email.
                //$mail->addBCC("rafael@eupedi.com");	//Cópia oculta do e-mail
                //$mail->addAttachment("/var/tmp/file.tar.gz");         // Add attachments
                //$mail->addAttachment("/tmp/image.jpg", "new.jpg");    // Optional name
            $mail->isHTML(true);                                  // Set email format to HTML
            $mail->Subject = "URGENTE eupedi - Erro para receber pagamento.";
            $mail->Body    = "eupedi.
                            <p>Ocorreu um erro ao processar o pagamento do carrinho $carrinhodocliente para o cliente: $codigodocliente_Form na compra da empresa $urldaempresa_form. O status veio como: $status_Do_Pagamento por este motivo: $Situacao_Do_Pagamento</p>
                            <p>Verifique, estamos perdendo dinheiro!!!</p>
                            <h3><strong>eupedi</strong></h3>
                            <p></p>";
            $mail->AltBody    = "eupedi.
                            <p>Ocorreu um erro ao processar o pagamento. O status veio como: $status_Do_Pagamento por este motivo: $Situacao_Do_Pagamento</p>
                            <p>Verifique, estamos perdendo dinheiro!!!</p>
                            <p></p>
                            <h3><strong>eupedi</strong></h3>
                            <p></p>"; // Para o trello!
            $mail->send();

            //Se ficar pendente ele será direcionado para página minha conta, onde receberá a informação que estamos aguardando o pagamento ser liberado.
            $_SESSION['cordamensagem'] = 'orange';
            $_SESSION['mensagem'] = "$MensagemDoPagamento";

            header("location:../comprafinalizar?cc=$codigodocliente_Form&token=$token&car=$codigodocarrinho");
            
        elseif($status_Do_Pagamento == 'REJEITADO' OR $status_Do_Pagamento == 'rejected'):

            if($Situacao_Do_Pagamento == 'cc_rejected_bad_filled_card_number'):
                $MensagemDoPagamento = "Revise o número do cartão.";
            elseif($Situacao_Do_Pagamento == 'cc_rejected_bad_filled_date'):
                $MensagemDoPagamento = "Revise a data de vencimento.";
            elseif($Situacao_Do_Pagamento == 'cc_rejected_bad_filled_other'):
                $MensagemDoPagamento = "Revise os dados.";
            elseif($Situacao_Do_Pagamento == 'cc_rejected_bad_filled_security_code'):
                $MensagemDoPagamento = "Revise o código de segurança do cartão.";
            elseif($Situacao_Do_Pagamento == 'cc_rejected_blacklist'):
                $MensagemDoPagamento = "O <strong>Mercado Pago informou que </strong>: não conseguiram processar seu pagamento.";
            elseif($Situacao_Do_Pagamento == 'cc_rejected_call_for_authorize'):
                $MensagemDoPagamento = "O <strong>Mercado Pago</strong> informou que seu banco não autorizou este pagaento.";
            elseif($Situacao_Do_Pagamento == 'cc_rejected_card_disabled'):
                $MensagemDoPagamento = "Ligue para sua administradora e ative o seu cartão. O telefone está no verso do seu cartão.";
            elseif($Situacao_Do_Pagamento == 'cc_rejected_card_error'):
                $MensagemDoPagamento = "<strong>O MercadoPago</strong> não conseguiu processar seu pagamento. <strong>Tenteo outro método depagamento.</strong>";
            elseif($Situacao_Do_Pagamento == 'cc_rejected_duplicated_payment'):
                $MensagemDoPagamento = "O <strong>MercadoPago</strong> informou que você já efetuou um pagamento duplicado com esse valor. Caso precise pagar novamente, utilize outro cartão ou outra forma de pagamento.";
            elseif($Situacao_Do_Pagamento == 'cc_rejected_high_risk'):
                $MensagemDoPagamento = "O <strong>MercadoPago</strong> informou que seu pagamento foi recusado. Escolha outra forma de pagamento. Recomendamos meios de pagamento em dinheiro.";
            elseif($Situacao_Do_Pagamento == 'cc_rejected_insufficient_amount'):
                $MensagemDoPagamento = "O <strong>MercadoPago</strong> informou que não há saldo insuficiente.";
            elseif($Situacao_Do_Pagamento == 'cc_rejected_invalid_installments'):
                $MensagemDoPagamento = "O <strong>MercadoPago</strong> informou que <strong>$Forma_Do_Pagamento</strong> informou que não processa pagamentos em $installments parcelas.";
            elseif($Situacao_Do_Pagamento == 'cc_rejected_max_attempts'):
                $MensagemDoPagamento = "O <strong>MercadoPago</strong> informou que você atingiu o limite de tentativas permitido para este método de pagamento. Escolha outro cartão ou outra forma de pagamento.";
            elseif($Situacao_Do_Pagamento == 'cc_rejected_other_reason'):
                $MensagemDoPagamento = "O <strong>MercadoPago</strong> informou que <strong>$Forma_Do_Pagamento</strong> não processa o pagamento.";
            else:
                $MensagemDoPagamento = "O <strong>MercadoPago</strong> informou que algum campo do seu cartão foi inserido de forma errada, revise seus dados.";
            endif;


            //in_process	        pending_contingency	                    Estamos processando o pagamento. Não se preocupe, em 
                                                                        //  menos de 2 dias úteis informaremos por e-mail se foi creditado.

            //in_process	        pending_review_manual	                Estamos processando seu pagamento. Não se preocupe, 
                                                                    //      em menos de 2 dias úteis informaremos por e-mail se foi  creditado ou se necessitamos de mais informação.
            
            //Cancela a cobrança por estar pendente
              //MercadoPago\SDK::setAccessToken(access_token:A_TOKEN);
              //$payment = MercadoPago\Payment::find_by_id($paymentMethodId);
              //$payment->status = "cancelled";
              //$payment->update();
            
            
            $bDCar = "SELECT sum(quantidade) as quantidadedeitensnocarrinho, cupomdedesconto, descontodocupom, cliente, codigodocliente, emaildocliente, telefonedocliente FROM carrinho WHERE codigodocarrinho='$carrinhodocliente'";
            $rDCar = mysqli_query($con, $bDCar);
                //$nDCar = mysqli_num_rows($rDCar);
                $dDCar = mysqli_fetch_array($rDCar);

            $quantidadedeitensnocarrinho    = $dDCar['quantidadedeitensnocarrinho'];
            $codigodedesconto               = $dDCar['cupomdedesconto'];
            $desconto                       = $dDCar['descontodocupom'];
            $cliente                        = $dDCar['cliente'];
            $codigodocliente                = $dDCar['codigodocliente'];
            $emaildocliente                 = $dDCar['emaildocliente'];
            $telefonedocliente              = $dDCar['telefonedocliente'];


            $BQtdOperacoes = "SELECT id FROM controledepagamento";
            $rBQO = mysqli_query($con, $BQtdOperacoes);
                $nBQO = mysqli_num_rows($rBQO);

            $codigodaoperacao = $nBQO.mt_rand(99,9999);

            //Intermediacação do MercadoPago
            if($formadepagamento == 'credit_card'):
                $taxadeintermediacao            = '4.99'; //04-06-2020 
                $tipodepagamento = 'Crédito';
            elseif($formadepagamento == 'debit_card'):
                $taxadeintermediacao            = '3.99'; //04-06-2020
                $tipodepagamento = 'Débito';
            elseif($formadepagamento == 'dinheiro'):
                $taxadeintermediacao            = '0'; //04-06-2020
                $tipodepagamento = 'Dinheiro';
            endif;
            
                $taxadeintermediacaoPercentual  = $taxadeintermediacao / 100;
            
            $valordaintermediacao           = $ValorTotalPosParcelas * $taxadeintermediacaoPercentual;
                $valordaintermediacao       = number_format($valordaintermediacao, 2);

            //Comissao
            if(empty($taxadecomissaoeupedi)):
                $taxadecomissaoeupedi   = '2.99';
            endif;

            $taxadecomissao                 = $taxadecomissaoeupedi; 
                $taxadecomissaoPercentual   = $taxadecomissao / 100;

            $valordecomissao    =   $ValorTotalPosParcelas * $taxadecomissaoPercentual;
                $valordecomissao = number_format($valordecomissao, 2);

            $statusdaoperacao   =   $Situacao_Do_Pagamento;

            $tokendecriptografia = '';

            //Atualiza Status do Pagamento do Carrinho devido o pagamento não ter sido aprovado.
            $PagCarrinho = "UPDATE carrinho SET formadepagamento='', statusdocarrinho='ABERTO' WHERE codigodocarrinho = '$carrinhodocliente'";
            mysqli_query($con, $PagCarrinho);

            
            //Enviar e-mail  para a empresa.
            include "../_enviaremail.php"; // Configuração para envio de e-mail.
            $mail->setFrom("contato@eupedi.com", "eupedi"); //Enviado por
            $mail->addAddress("rafael@faelvieirasilva.com.br", "CEO Rafael");     // Add a recipient 
                //$mail->addAddress("ellen@example.com");               // Name is optional
                //$mail->addReplyTo("$email", "$empresa"); //Para quem deve responder.
                //$mail->addCC("$email"); //Cópia do email.
                //$mail->addBCC("rafael@eupedi.com");	//Cópia oculta do e-mail
                //$mail->addAttachment("/var/tmp/file.tar.gz");         // Add attachments
                //$mail->addAttachment("/tmp/image.jpg", "new.jpg");    // Optional name
            $mail->isHTML(true);                                  // Set email format to HTML
            $mail->Subject = "URGENTE eupedi - Erro para receber pagamento.";
            $mail->Body    = "eupedi.
                            <p>Ocorreu um erro ao processar o pagamento do carrinho $carrinhodocliente para o cliente: $codigodocliente_Form na compra da empresa $urldaempresa_form. O status veio como: $status_Do_Pagamento por este motivo: $Situacao_Do_Pagamento</p>
                            <p>Verifique, estamos perdendo dinheiro!!!</p>
                            <h3><strong>eupedi</strong></h3>
                            <p></p>";
            $mail->AltBody    = "eupedi.
                            <p>Ocorreu um erro ao processar o pagamento. O status veio como: $status_Do_Pagamento por este motivo: $Situacao_Do_Pagamento</p>
                            <p>Verifique, estamos perdendo dinheiro!!!</p>
                            <p></p>
                            <h3><strong>eupedi</strong></h3>
                            <p></p>"; // Para o trello!
            $mail->send();

            //Se ficar pendente ele será direcionado para página minha conta, onde receberá a informação que estamos aguardando o pagamento ser liberado.
            $_SESSION['cordamensagem'] = 'orange';
            $_SESSION['mensagem'] = "$MensagemDoPagamento";

            header("location:../comprafinalizar?cc=$codigodocliente_Form&token=$token&car=$codigodocarrinho");
            
        endif;

    endif;
