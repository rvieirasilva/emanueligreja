<?php
    session_start();

    require ('../configProduto.php');
    require ('../lib/vendor/autoload.php');
        
    //&Jj7
    #Variables
    $email                  = filter_input(INPUT_POST,'email',FILTER_VALIDATE_EMAIL);
    $cardNumber             = filter_input(INPUT_POST,'cardNumber',FILTER_DEFAULT);
    $securityCode           = filter_input(INPUT_POST,'securityCode',FILTER_DEFAULT);
    $cardExpirationMonth    = filter_input(INPUT_POST,'cardExpirationMonth',FILTER_DEFAULT);
    $cardExpirationYear     = filter_input(INPUT_POST,'cardExpirationYear',FILTER_DEFAULT);
    $cardholderName         = filter_input(INPUT_POST,'cardholderName',FILTER_DEFAULT);
    $docType                = filter_input(INPUT_POST,'docType',FILTER_DEFAULT);
    $docNumber              = filter_input(INPUT_POST,'docNumber',FILTER_DEFAULT);
    $installments           = filter_input(INPUT_POST,'installments',FILTER_DEFAULT);
    $amount                 = filter_input(INPUT_POST,'amount',FILTER_DEFAULT);
    $description            = filter_input(INPUT_POST,'description',FILTER_DEFAULT);
    $paymentMethodId        = filter_input(INPUT_POST,'paymentMethodId',FILTER_DEFAULT);
    $token                  = filter_input(INPUT_POST,'token',FILTER_DEFAULT);

    //Identificação da empresa para nosso BD
    $codigodocliente_Form   = filter_input(INPUT_POST,'codigodocliente_Form',FILTER_DEFAULT);
    $codigodaempresa        = filter_input(INPUT_POST,'$codigodaempresa',FILTER_DEFAULT);
    $urldaempresa_form      = filter_input(INPUT_POST,'urldaempresa',FILTER_DEFAULT);
    $empresa                = filter_input(INPUT_POST,'empresa',FILTER_DEFAULT);
    $pontosgeradosparaocliente                = filter_input(INPUT_POST,'pontosgeradosparaocliente',FILTER_DEFAULT);
    //@$numerodeusuarioclientepagante      = filter_input(INPUT_POST,'numerodeusuarioclientepagante',FILTER_DEFAULT);
    //@$valmensalclientepagante            = filter_input(INPUT_POST,'valmensalclientepagante',FILTER_DEFAULT);

    //$formadepagamento  = mysqli_escape_string($con, $_POST['formadepagamento']);
    $TipoDeEntrega          = filter_input(INPUT_POST,'entrega',FILTER_DEFAULT); 
    $PrazoDeEntrega         = filter_input(INPUT_POST,'prazodeentrega',FILTER_DEFAULT); 
    $carrinhodocliente      = filter_input(INPUT_POST,'codigodocarrinho',FILTER_DEFAULT);
        $codigodocarrinho   = filter_input(INPUT_POST,'codigodocarrinho',FILTER_DEFAULT); //Duplicado caso usem essa string abaixo.

    $dia_do_pagamento                   = date('d');
    $mes_do_pagamento                   = date('m');
    $ano_do_pagamento                   = date('Y');


    $anoatual                           = date('y');
    $ano                                = date('Y');
    $dia                                = date('d');
    $data                               = date('d/m/Y');
    $dataeng                            = date('Y-m-d');
    $mes                                = date('M');
    $mesnumero                          = date('m');
    $hora                               = date('H:m:s');
    $horaa                              = date('h');
    $horab                              = date('i');

    
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
        /*
            approved            accredited
            in_process          pending_contingency         Em até 2 dias úteis informaremos por e-mail o resultado.
            in_process          pending_review_manual       Em até 2 dias [...] foi aprovado ou se precisamos de mais informações.
            rejected
        */

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



    if($status_Do_Pagamento === 'approved'):
        $status_Do_Pagamento = 'APROVADO';
        $statusDoUsuario    = 'ATIVO';
    elseif($status_Do_Pagamento === 'in_process'):
        $status_Do_Pagamento = 'PENDENTE';
        $statusDoUsuario    = 'CADASTRADO';
    elseif($status_Do_Pagamento === 'rejected'):
        $status_Do_Pagamento = 'REJEITADO';
        $statusDoUsuario    = 'CADASTRADO';
    endif;
    

    $status_Do_Pagamento == 'approved';
        $status_Do_Pagamento = 'APROVADO';
        $statusDoUsuario    = 'ATIVO';
    

    include "../_con.php"; // Conexão.

      
        //Cria uma token para redirecioanar o usuário para página de acesso.
        $buscartoken = "SELECT token FROM tokens GROUP BY token ORDER BY rand(), id DESC LIMIT 1";
        $rbuscartoken = mysqli_query($con, $buscartoken);
            $dbuscartoken=	mysqli_fetch_array($rbuscartoken);
                    
            $_SESSION['token']=$dbuscartoken['token'];
            $token = $_SESSION['token'];

    
    
    if($status_Do_Pagamento === 'APROVADO' OR $status_Do_Pagamento === 'approved'):

        //Buscar código do parceiro, carrinho, urldaempresa, 
        $bFinanc = "SELECT codigodaempresa, codigodocarrinho, urldaempresa FROM carrinho WHERE codigodocarrinho='$carrinhodocliente'";
          $rFinanc = mysqli_query($con, $bFinanc);
            $dFinanc = mysqli_fetch_array($rFinanc);

        $codigodoparceiro_carrinho  = $dFinanc['codigodaempresa'];
        $codigodocarrinho_carrinho  = $dFinanc['codigodaempresa'];
        $urldaempresa_carrinho      = $dFinanc['codigodaempresa'];
        
        $atformadepagamento="UPDATE carrinho SET formadepagamento='$Forma_Do_Pagamento', tempodeentregaestimado='$PrazoDeEntrega', tipodefreteindicada='$TipoDeEntrega', statusdocarrinho='PENDENTE' WHERE codigodocarrinho='$carrinhodocliente'";

        if(mysqli_query($con, $atformadepagamento)):

            //Enviar e-mail para o cliente informando o pedido finalizado.
            include "../_enviaremail.php"; // Configuração para envio de e-mail.
            $mail->setFrom("contato@eupedi.com", "$urldaempresa"); //Enviado por
            $mail->addAddress("$emaildocliente", "$nomedocliente");     // Add a recipient 
                //$mail->addAddress("ellen@example.com");               // Name is optional
            $mail->addReplyTo("mensagem@eupedi.com", "$urldaempresa"); //Para quem deve responder.
                //$mail->addCC("$emaildoresponsavel"); //Cópia do email.
            $mail->addBCC("faelvieirasilva+xcolxwuy3pqjrqef5by7@boards.trello.com");	//Cópia oculta do e-mail
                //$mail->addAttachment("/var/tmp/file.tar.gz");         // Add attachments
                //$mail->addAttachment("/tmp/image.jpg", "new.jpg");    // Optional name
            $mail->isHTML(true);                                  // Set email format to HTML
            $mail->Subject = "$urldaempresa, seu pedido foi finalizado.";
            $mail->Body    = "Olá, $nomedocliente.
                            <p>Seu pedido foi finalizado com sucesso.<p>
                            <p></p>
                            <p>Você pode acompanhar o status do seu pedido em nossa página, basta acessar, abrir o menu e clicar em: <strong> Acompanhar pedido</strong></p>
                                <br>
                                <h2><strong>Código do seu pedido</strong> $carrinhodocliente</h2>.</p>
                            <p></p>
                            <p><h3><strong>$urldaempresa</strong></h3></p>
                            <p></p>
                            ";
            $mail->AltBody = "Olá, $nomedocliente.
                            <p>Seu pedido foi finalizado com sucesso.<p>
                            <p></p>
                            <p>Você pode acompanhar o status do seu pedido em nossa página, basta acessar, abrir o menu e clicar em: <strong> Acompanhar pedido</strong></p>
                                <br>
                                <h2><strong>Código do seu pedido</strong> $carrinhodocliente</h2>.</p>
                            <p></p>
                            <p><h3><strong>$urldaempresa</strong></h3></p>
                            <p></p>
                            "; // Para o trello!
            $mail->send();
            
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


            $BQtdOperacoes = "SELECT id FROM controledepagamento";
            $rBQO = mysqli_query($con, $BQtdOperacoes);
                $nBQO = mysqli_num_rows($rBQO);

            $codigodaoperacao = $nBQO.mt_rand(99,9999);

            //Intermediacação do MercadoPago
            $taxadeintermediacao            = '4.99'; //04-06-2020 
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
            $PagCarrinho = "UPDATE carrinho SET formadepagamento='$Forma_Do_Pagamento', carrinhofechadoem='$dataeng' WHERE codigodocarrinho = '$carrinhodocliente'";
            mysqli_query($con, $PagCarrinho);

            //Registrar na receita essa venda realizada com tipo de operação VCMP (Venda Com Marcado Pago)
                $descricaoFinanceira = "Compra do carrinho: $carrinhodocliente";

                //Liquidez da venda
                $valorPrevistoFinanceiro = $amount - ($valordaintermediacao + $valordecomissao); //90.39 - (7.50 + 8.90)
                $dataprevistaFinanceiro = $ano.'-'.$mesnumero.'-'.$dia; //2020-10-09
                    $dataprevistaFinanceiro = date(("Y-m-d"), strtotime($dataprevistaFinanceiro .'+ 2 days'));

            $RegistrarVendaNaReceita="INSERT INTO financeiro (empresa, codigodoparceiro, urldaempresa, dia, mes, ano, representante, codigodorepresentante, codigodaoperacao, descricao, valorprevisto, dataprevista, valorrealizado, datarealizada, recebidode, pagopor, anexo, tamanhodoarquivo, tipodeoperacao, categoria, centrodecusto, conta1, conta2, tipoderegistro) VALUES ('$empresa', '$codigodoparceiro_carrinho', '$urldaempresa_form', '$dia_do_pagamento', '$mesnumero', '$ano_do_pagamento', '$cliente', '$codigodocliente', '$codigodaoperacao', '$descricaoFinanceira', '', '', '$valorPrevistoFinanceiro', '$dataprevistaFinanceiro', 'Mercado Pago', '$cliente', '', '', 'VCMP', '', 'VCMP', '', '', 'RECEITA')";
                mysqli_query($con, $RegistrarVendaNaReceita);

            //Registra o pagamento para controle
            $PagAprovado = "INSERT INTO controledepagamento (codigodaoperacao, dia, mes, ano, hora, codigodaempresa, urldaempresa, codigodocarrinho, valortotaldocarrinho, quantidadedeitensnocarrinho, codigodedesconto, desconto, cliente, codigodocliente, email, telefone, cardnumber, tipodedocumento, numerododocumento, valortotal, parcelas, taxadeintermediacao, valordaintermediacao, taxadecomissao, valordecomissao, statusdopagamento, statusdaoperacao, nota, tokendecriptografia, taxadejurosdocartao, valordejurosdocartao) VALUES ('$codigodaoperacao', '$dia_do_pagamento', '$mesnumero', '$ano_do_pagamento', '$hora', '$codigodaempresa', '$urldaempresa', '$carrinhodocliente', '$amount', '$quantidadedeitensnocarrinho', '$codigodedesconto', '$desconto', '$cliente', '$codigodocliente', '$emaildocliente', '$telefonedocliente', '', '$docType', '$docNumber', '$ValorTotalPosParcelas', '$installments', '$taxadeintermediacao', '$valordaintermediacao', '$taxadecomissao', '$valordecomissao', '$status_Do_Pagamento', '$statusdaoperacao', '', '$tokendecriptografia', '$taxadejurosdocartao', '$valordejurosdocartao')";
            if(mysqli_query($con, $PagAprovado)):

                $_SESSION['cordamensagem'] = 'green';
                $_SESSION['mensagem'] = "Parabéns pelo pedido, sua entrega chegará dentro do prazo informado";

                //Enviar e-mail para cliente e empresa.
                $bEnviarEmail="SELECT email FROM parceiros WHERE urldaempresa='$urldaempresa'";
                  $rEE=mysqli_query($con, $bEnviarEmail);
                    $dEE=mysqli_fetch_array($rEE);

                    $comunicarempresa                 = $dEE['email'];

                //Enviar e-mail de boas vindas para a empresa.
                include "../_enviaremail.php"; // Configuração para envio de e-mail.
                $mail->setFrom("contato@eupedi.com", "$urldaempresa"); //Enviado por
                $mail->addAddress("$email", "$nomedocliente");     // Add a recipient 
                    //$mail->addAddress("ellen@example.com");               // Name is optional
                    //$mail->addReplyTo("$email", "$empresa"); //Para quem deve responder.
                    //$mail->addCC("$email"); //Cópia do email.
                $mail->addBCC("$emaildaempresa");	//Cópia oculta do e-mail
                    //$mail->addAttachment("/var/tmp/file.tar.gz");         // Add attachments
                    //$mail->addAttachment("/tmp/image.jpg", "new.jpg");    // Optional name
                $mail->isHTML(true);                                  // Set email format to HTML
                $mail->Subject = "$urldaempresa - Seu pedido $carrinhodocliente foi pago";
                $mail->Body    = "Olá $nomedocliente,
                                <p>Seu pagamento foi aprovado, acompanhe o seu pedido diretamente por sua conta.</p>
                                <h3><strong>$urldaempresa</strong></h3>
                                <p></p>";
                $mail->AltBody    = "Olá $nomedocliente,
                                <p>Seu pagamento foi aprovado, acompanhe o seu pedido diretamente por sua conta.</p>
                                <h3><strong>$urldaempresa</strong></h3>
                                <p></p>"; // Para o trello!
                $mail->send();

                //Atualiza os pontos do cliente
                $bPontosDoConsumidor = "SELECT pontos FROM consumidor WHERE codigodocliente='$codigodocliente' AND urldaempresa='$urldaempresa'";
                  $rPDC = mysqli_query($con, $bPontosDoConsumidor);
                    $dPDC = mysqli_fetch_array($rPDC);

                $PontosAntigos = $dPDC['pontos'];
                $NovosPontos   = $PontosAntigos + $pontosgeradosparaocliente;

                $UpPontosConsumidor = "UPDATE consumidor SET pontos='$NovosPontos' WHERE codigodocliente='$codigodocliente' AND urldaempresa='$urldaempresa'";
                mysqli_query($con, $UpPontosConsumidor);

                //Atualiza o número de produtos disponíveis.
                $bProdutosConsumidosDaEmpresa = "SELECT codigodoproduto, count(codigodoproduto) as produtosadquiridos from carrinho WHERE codigodocarrinho='$carrinhodocliente'";
                  $rPCDE = mysqli_query($con, $bProdutosConsumidosDaEmpresa);
                    while($dPCDE=mysqli_fetch_array($rPCDE)):
                        $ProdutoAdquirido               = $dPCDE['codigodoproduto'];
                        $QuantidadeDoProdutoAdquirido   = $dPCDE['produtosadquiridos'];

                        //Encontra o produto cadastrado
                        $bProdutoCadastradoPelaEmpresa = "SELECT quantidadedisponivel FROM produtos WHERE codigodoproduto='$ProdutoAdquirido' AND urldaempresa='$urldaempresa'";
                          $rPCPE = mysqli_query($con, $bProdutoCadastradoPelaEmpresa);
                            $dPCPE = mysqli_fetch_array($rPCPE);

                        $QuantidadeAntiga = $dPCPE['quantidadedisponivel'];

                        //Reduz a quantidade disponível
                        $NovaQuantidadeDisponivel = $QuantidadeAntiga - $QuantidadeDoProdutoAdquirido;

                        //Atualiza a quantidade.
                        $UpQuantidadeParaVenda = "UPDATE produtos SET quantidadedisponivel='$NovaQuantidadeDisponivel' WHERE urldaempresa='$urldaempresa' AND codigodoproduto='$ProdutoAdquirido'";
                        mysqli_query($con, $UpQuantidadeParaVenda);
                    endwhile;
        
                header("location:../compraconcluida?cc=$codigodocliente&token=$token&car=$codigodocarrinho");
            else:
                $_SESSION['cordamensagem'] = 'red';
                $_SESSION['mensagem'] = "<strong>Verifique sua conexão</strong>. Seu pedido foi aprovado, mas houve um erro ao registrar essa operação, //verifique seu e-mail e entre em contato com nossa pizzaria para confirmar o seu pedido.";
        
                header("location:../compraconcluida?cc=$codigodocliente&token=$token&car=$codigodocarrinho");
            endif;
        endif;
    else: //if($status_Do_Pagamento !== 'APROVADO')
        /*
        //Cancela a cobrança por estar pendente
        MercadoPago\SDK::setAccessToken(A_TOKEN);
        $payment = MercadoPago\Payment::find_by_id($paymentMethodId);
        $payment->status = "cancelled";
        $payment->update();
        */
        
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
        $taxadeintermediacao            = '4.99'; //04-06-2020 
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

        
        //Enviar e-mail de boas vindas para a empresa.
        include "../_enviaremail.php"; // Configuração para envio de e-mail.
        $mail->setFrom("contato@eupedi.com", "eupedi"); //Enviado por
        $mail->addAddress("rafael@eupedi.com", "CEO Rafael");     // Add a recipient 
            //$mail->addAddress("ellen@example.com");               // Name is optional
            //$mail->addReplyTo("$email", "$empresa"); //Para quem deve responder.
            //$mail->addCC("$email"); //Cópia do email.
            //$mail->addBCC("rafael@eupedi.com");	//Cópia oculta do e-mail
            //$mail->addAttachment("/var/tmp/file.tar.gz");         // Add attachments
            //$mail->addAttachment("/tmp/image.jpg", "new.jpg");    // Optional name
        $mail->isHTML(true);                                  // Set email format to HTML
        $mail->Subject = "URGENTE eupedi - Erro para receber pagamento.";
        $mail->Body    = "eupedi.
                        <p>Ocorreu um erro ao processar o pagamento. O status não veio como aprovado, pendente ou repro.</p>
                        <p>Verifique, estamos perdendo dinheiro!!!</p>
                        <h3><strong>eupedi</strong></h3>
                        <p></p>";
        $mail->AltBody    = "eupedi.
                        <p>Ocorreu um erro ao processar o pagamento. O status não veio como aprovado, pendente ou repro.</p>
                        <p>Verifique, estamos perdendo dinheiro!!!</p>
                        <p></p>
                        <h3><strong>eupedi</strong></h3>
                        <p></p>"; // Para o trello!
        $mail->send();

        //Se ficar pendente ele será direcionado para página minha conta, onde receberá a informação que estamos aguardando o pagamento ser liberado.
        $_SESSION['cordamensagem'] = 'orange';
        $_SESSION['mensagem'] = "O <strong>MercadoPago</strong> informou que o seu pagamento não foi aprovado. Tente novamente ou insira um novo método de pagamento.";
        header("location:../comprafinalizar?cc=$codigodocliente&token=$token&car=$codigodocarrinho");
        
    endif;
