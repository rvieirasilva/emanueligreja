<?php
    session_start();

    include ('../configDoacoes.php');
    include ('../lib/vendor/autoload.php');
        
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
        $description = base64_encode($description);
        $description = base64_encode($description);
    $paymentMethodId                            = filter_input(INPUT_POST,'paymentMethodId',FILTER_DEFAULT);
    $token                                      = filter_input(INPUT_POST,'token',FILTER_DEFAULT);

    //Dados complementares do formulário
    $tipodecontribuicao                        = filter_input(INPUT_POST,'tipodecontribuicao',FILTER_DEFAULT);
    $nomeofertante                              = filter_input(INPUT_POST,'nomeofertante',FILTER_DEFAULT);
        $nomeofertante = base64_encode($nomeofertante);
        $nomeofertante = base64_encode($nomeofertante);

    
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

    // var_dump($_POST);
    
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
        $valordejurosdocartao = number_format($valordejurosdocartao, 2, '', '.');   

    if($status_Do_Pagamento == 'approved'):
        $status_Do_Pagamento = 'APROVADO';
    elseif($status_Do_Pagamento == 'in_process'):
        $status_Do_Pagamento = 'PENDENTE';
    elseif($status_Do_Pagamento == 'rejected'):
        $status_Do_Pagamento = 'REJEITADO';
    endif;

    
    //Erros
    @$ErroDoPagamento    = $payment->error->message;
    @$DescricaoDoErro    = $payment->error->causes[0]->description;
    @$CodigoDoErro       = $payment->error->status;
    
    // echo '<pre>',print_r($payment),'</pre>'; //Segundo a aula do youtube
    
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
        $mail->setFrom("financas@igrejaemanuel.com.br", "Igreja Emauel"); //Enviado por
        $mail->addAddress("$email", "$nomeofertante");     // Add a recipient 
            //$mail->addAddress("ellen@example.com");               // Name is optional
            //$mail->addReplyTo("$email", "$empresa"); //Para quem deve responder.
            //$mail->addCC("$email"); //Cópia do email.
        $mail->addBCC("financas@igrejaemanuel.com.br");	//Cópia oculta do e-mail
            //$mail->addAttachment("/var/tmp/file.tar.gz");         // Add attachments
            //$mail->addAttachment("/tmp/image.jpg", "new.jpg");    // Optional name
        $mail->isHTML(true);                                  // Set email format to HTML
        $mail->Subject = "URGENTE - Erro para receber doação.";
        $mail->Body    = "IBE.
                        <p>Ocorreu um erro ao processar a doação tentada por: $cardholderName. Na data; $dia/$mesnumero/$ano
                        <h3><strong>Igreja Emanuel</strong></h3>
                        <p></p>
                        ";
        $mail->AltBody    = "IBE.
                        <p>Ocorreu um erro ao processar a doação tentada por: $cardholderName. Na data; $dia/$mesnumero/$ano
                        <h3><strong>Igreja Emanuel</strong></h3>
                        <p></p>
                        "; // Para o trello!
        $mail->send();

        //Se ficar pendente ele será direcionado para página minha conta, onde receberá a informação que estamos aguardando o pagamento ser liberado.
        $_SESSION['cordamensagem'] = 'orange';
        $_SESSION['mensagem'] = $MensagemDoPagamento;

        header("location:../doacoes");
    
    else:
        if($status_Do_Pagamento == 'APROVADO' OR $status_Do_Pagamento == 'approved'):
            
            $BQtdOperacoes = "SELECT id FROM financeiro";
            $rBQO = mysqli_query($con, $BQtdOperacoes);
                $nBQO = mysqli_num_rows($rBQO);

            $codigodaoperacao = $nBQO.mt_rand(99,9999);

            // //Intermediacação do MercadoPago
            // if($formadepagamento == 'credit_card'):
                $taxadeintermediacao            = '4.99'; //04-06-2020 
                $tipodepagamento = 'Crédito';
            // elseif($formadepagamento == 'debit_card'):
            //     $taxadeintermediacao            = '3.99'; //04-06-2020
            //     $tipodepagamento = 'Débito';
            // elseif($formadepagamento == 'dinheiro'):
            //     $taxadeintermediacao            = '0'; //04-06-2020
            //     $tipodepagamento = 'Dinheiro';
            // endif;

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
            
            //Liquidez da venda
            $valorPrevistoFinanceiro = $amount - $valordaintermediacao; //90.39 - (7.50 + 8.90)
            $dataprevistaFinanceiro = $ano.'-'.$mesnumero.'-'.$dia; //2020-10-09
                $dataprevistaFinanceiro = date(("Y-m-d"), strtotime($dataprevistaFinanceiro .'+ 2 days'));

            $inFin = "INSERT INTO financeiro (empresa, codigodoparceiro, urldaempresa, dia, mes, ano, representante, codigodorepresentante, codigodaoperacao, descricao, valorprevisto, dataprevista, valorrealizado, datarealizada, recebidode, pagopor, anexo, tamanhodoarquivo, tipodeoperacao, categoria, centrodecusto, conta1, conta2, tipoderegistro) VALUES ('Igreja Emanuel', '0001', 'igrejaemanuel', '$dia', '$mesnumero', '$ano', '', '', '$codigodaoperacao', '$description', '$valorPrevistoFinanceiro', '$dataprevistaFinanceiro', '$valorPrevistoFinanceiro', '$dataprevistaFinanceiro', '$nomeofertante', '', '', '', '$tipodecontribuicao', '$tipodecontribuicao', '', '0999-3', '', 'CRÉDITO')";
            
            if(mysqli_query($con, $inFin)):
                //Enviar e-mail agradecendo.
                include "../_enviaremail.php"; // Configuração para envio de e-mail.
                $mail->setFrom("financas@igrejaemanuel.com.br", "Igreja Emauel"); //Enviado por
                $mail->addAddress("$email", "Igreja Emanuel");     // Add a recipient 
                    //$mail->addAddress("ellen@example.com");               // Name is optional
                    //$mail->addReplyTo("$email", "$empresa"); //Para quem deve responder.
                    //$mail->addCC("$email"); //Cópia do email.
                $mail->addBCC("financas@igrejaemanuel.com.br");	//Cópia oculta do e-mail
                    //$mail->addAttachment("/var/tmp/file.tar.gz");         // Add attachments
                    //$mail->addAttachment("/tmp/image.jpg", "new.jpg");    // Optional name
                $mail->isHTML(true);                                  // Set email format to HTML
                $mail->Subject = "Igreja Emanuel - Obrigado por sua contribuição";
                $mail->Body    = "Nós construímos a história da igreja. Sua contribuição faz parte do que estamos construindo. As ações sociais, o avanço da igreja, cada família auxiliada, tudo o que fazemos é graças à Cristo e sua liberalidade.
                                <h3><strong>Igreja Emanuel</strong></h3>
                                <p></p>
                                ";
                $mail->AltBody    = "Nós construímos a história da igreja. Sua contribuição faz parte do que estamos construindo. As ações sociais, o avanço da igreja, cada família auxiliada, tudo o que fazemos é graças à Cristo e sua liberalidade.
                                <h3><strong>Igreja Emanuel</strong></h3>
                                <p></p>
                                "; // Para o trello!
                $mail->send();
            endif;
            
            //Se ficar pendente ele será direcionado para página minha conta, onde receberá a informação que estamos aguardando o pagamento ser liberado.
            $_SESSION['cordamensagem'] = 'blue';
            $_SESSION['mensagem'] = "Sua contribuição foi aprovada, obrigado por sua liberalidade";

            $_SESSION['comprovantetipodecontribuicao']  = $tipodecontribuicao;
            $_SESSION['comprovantevalordacontribuicao'] = $amount;
            $_SESSION['comprovantenomedoofertante']     = $nomeofertante;
            $_SESSION['comprovantedatadaoferta']        = $dataeng;

            header("location:../ofertacomprovante");
            // header("location:../doacoes");
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
        
            $BQtdOperacoes = "SELECT id FROM financeiro";
            $rBQO = mysqli_query($con, $BQtdOperacoes);
                $nBQO = mysqli_num_rows($rBQO);

            $codigodaoperacao = $nBQO.mt_rand(99,9999);

            // //Intermediacação do MercadoPago
            // if($formadepagamento == 'credit_card'):
                $taxadeintermediacao            = '4.99'; //04-06-2020 
                $tipodepagamento = 'Crédito';
            // elseif($formadepagamento == 'debit_card'):
            //     $taxadeintermediacao            = '3.99'; //04-06-2020
            //     $tipodepagamento = 'Débito';
            // elseif($formadepagamento == 'dinheiro'):
            //     $taxadeintermediacao            = '0'; //04-06-2020
            //     $tipodepagamento = 'Dinheiro';
            // endif;

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

            //Liquidez da venda
            $valorPrevistoFinanceiro = $amount - $valordaintermediacao; //90.39 - (7.50 + 8.90)
            
            $dataprevistaFinanceiro = $ano.'-'.$mesnumero.'-'.$dia; //2020-10-09
                $dataprevistaFinanceiro = date(("Y-m-d"), strtotime($dataprevistaFinanceiro .'+ 2 days'));

            $inFin = "INSERT INTO financeiro (empresa, codigodoparceiro, urldaempresa, dia, mes, ano, representante, codigodorepresentante, codigodaoperacao, descricao, valorprevisto, dataprevista, valorrealizado, datarealizada, recebidode, pagopor, anexo, tamanhodoarquivo, tipodeoperacao, categoria, centrodecusto, conta1, conta2, tipoderegistro) VALUES ('Igreja Emanuel', '0001', 'igrejaemanuel', '$dia', '$mesnumero', '$ano', '', '', '$codigodaoperacao', '$description', '$valorPrevistoFinanceiro', '$dataprevistaFinanceiro', '', '', '$nomeofertante', '', '', '', '$tipodecontribuicao', '$tipodecontribuicao', '', '0999-3', '', 'CRÉDITO')";
            
            mysqli_query($con, $inFin);

            
            //Enviar e-mail agradecendo.
            include "../_enviaremail.php"; // Configuração para envio de e-mail.
            $mail->setFrom("financas@igrejaemanuel.com.br", "Igreja Emauel"); //Enviado por
            $mail->addAddress("$email", "Igreja Emanuel");     // Add a recipient 
                //$mail->addAddress("ellen@example.com");               // Name is optional
                //$mail->addReplyTo("$email", "$empresa"); //Para quem deve responder.
                //$mail->addCC("$email"); //Cópia do email.
            $mail->addBCC("financas@igrejaemanuel.com.br");	//Cópia oculta do e-mail
                //$mail->addAttachment("/var/tmp/file.tar.gz");         // Add attachments
                //$mail->addAttachment("/tmp/image.jpg", "new.jpg");    // Optional name
            $mail->isHTML(true);                                  // Set email format to HTML
            $mail->Subject = "Igreja Emanuel - Contribuição pendente";
            $mail->Body    = "Ficamos contentes com sua generosidade, contudo, precisamos informar que a administradora retornou que sua contribuição está pendente. Ela pode ser processada em até dois dias ou não. Entre em contato com a administradora do seu cartão e com nossa equipe que te auxiliaremos.
                            <h3><strong>Igreja Emanuel</strong></h3>
                            <p></p>
                            ";
            $mail->AltBody    = "Ficamos contentes com sua generosidade, contudo, precisamos informar que a administradora retornou que sua contribuição está pendente. Ela pode ser processada em até dois dias ou não. Entre em contato com a administradora do seu cartão e com nossa equipe que te auxiliaremos.
                            <h3><strong>Igreja Emanuel</strong></h3>
                            <p></p>
                            "; // Para o trello!
            $mail->send();

            
            //Se ficar pendente ele será direcionado para página minha conta, onde receberá a informação que estamos aguardando o pagamento ser liberado.
            $_SESSION['cordamensagem'] = 'orange';
            $_SESSION['mensagem'] = "$MensagemDoPagamento";
            header("location:../doacoes");
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
            
            
            //Se ficar pendente ele será direcionado para página minha conta, onde receberá a informação que estamos aguardando o pagamento ser liberado.
            $_SESSION['cordamensagem'] = 'orange';
            $_SESSION['mensagem'] = "$MensagemDoPagamento";
            header("location:../doacoes");
        endif;
    endif;

