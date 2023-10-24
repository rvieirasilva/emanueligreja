<?php
    session_start();
    require ('../config.php');
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
    $usuariologadodoclientepagante      = filter_input(INPUT_POST,'usuariologadodoclientepagante',FILTER_DEFAULT);
    $clientepagante                     = filter_input(INPUT_POST,'clientepagante',FILTER_DEFAULT);
    $planodoclientepagante              = filter_input(INPUT_POST,'planodoclientepagante',FILTER_DEFAULT);
        if($planodoclientepagante === "Básico"):
            $plan_get = 'bas';
        elseif($planodoclientepagante === "PRO"):
            $plan_get = 'pro';
        elseif($planodoclientepagante === "Empresa"):
            $plan_get = 'emp';
        endif;
    $numerodeusuarioclientepagante      = filter_input(INPUT_POST,'numerodeusuarioclientepagante',FILTER_DEFAULT);
    $valmensalclientepagante            = filter_input(INPUT_POST,'valmensalclientepagante',FILTER_DEFAULT);

    $dia_do_pagamento                   = date('d');
    $mes_do_pagamento                   = date('m');
    $ano_do_pagamento                   = date('Y');

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
        //echo '<pre>',print_r($payment),'</pre>'; //Segundo a aula do youtube

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

    //if(!isset($statusDoUsuario)):
        require "../_con.php";

        //Atualiza nossos dados no Banco de Dados.
        $UpPag = "UPDATE parceiros SET dia='$dia_do_pagamento', mes='$mes_do_pagamento', ano='$ano_do_pagamento', statusdoparceiro = '$status_Do_Pagamento', plano='$planodoclientepagante', numerodeusuarios='$numerodeusuarioclientepagante', mensalidade='$valmensalclientepagante', formadepagamento='$Forma_Do_Pagamento' WHERE codigodoparceiro='$clientepagante'";
        mysqli_query($con, $UpPag);

        //Atualiza dados dos usuários no Banco de Dados.
        $UpPagUSER = "UPDATE usuarios SET statusdousuario = '$statusDoUsuario' WHERE codigodaempresa='$clientepagante'";
        mysqli_query($con, $UpPagUSER);
        
        //Cria uma token para redirecioanar o usuário para página de acesso.
        $buscartoken = "SELECT token FROM tokens GROUP BY token ORDER BY rand(), id DESC LIMIT 1";
        $rbuscartoken = mysqli_query($con, $buscartoken);
            $dbuscartoken=	mysqli_fetch_array($rbuscartoken);
                    
            $_SESSION['token']=$dbuscartoken['token'];
            $token = $_SESSION['token'];

        
        if($status_Do_Pagamento === 'APROVADO'):
            //Com o pagamento aprovado ele é direcionado para página inicial
            $_SESSION['cordamensagem'] = 'green';
            $_SESSION['mensagem'] = "Bem vindo.";
            header("location:../areadevenda?cc=$usuariologadodoclientepagante&token=$token");
        elseif($status_Do_Pagamento === 'PENDENTE'):
            //Se ficar pendente ele será direcionado para página minha conta, onde receberá a informação que estamos aguardando o pagamento ser liberado.
            $_SESSION['cordamensagem'] = 'orange';
            $_SESSION['mensagem'] = "O <strong>MercadoPago</strong> informou que o seu pagamento está pendente e receberemos novas instruções em até 48h.";
            header("location:../minhaconta?cc=$usuariologadodoclientepagante&token=$token");
        elseif($status_Do_Pagamento === 'REJEITADO'): //Se o pagamento for rejeitado o usuário será redirecionado para a página de checkout para tentar nova forma de pagamento.
            $_SESSION['cordamensagem'] = 'red';
            $_SESSION['mensagem'] = "O <strong>MercadoPago</strong> informou que o seu pagamento foi rejeitado, tente outra forma de pagamento.";
            header("location:../checkout?cc=$usuariologadodoclientepagante&token=$token&plan=$plan_get");
        elseif($status_Do_Pagamento !== 'APROVADO' AND $status_Do_Pagamento !== 'PENDENTE' AND $status_Do_Pagamento !== 'REJEITADO'): //Aconteceu um erro

            //Enviar e-mail de boas vindas para a empresa.
            include "_enviaremail.php"; // Configuração para envio de e-mail.
            $mail->setFrom("contato@eupedi.com", "eupedi"); //Enviado por
            $mail->addAddress("rafael@clubcriativo.com", "CEO Rafael");     // Add a recipient 
                //$mail->addAddress("ellen@example.com");               // Name is optional
                //$mail->addReplyTo("$email", "$empresa"); //Para quem deve responder.
                //$mail->addCC("$email"); //Cópia do email.
                //$mail->addBCC("rafael@clubcriativo.com");	//Cópia oculta do e-mail
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
            //Se o pagamento for rejeitado o usuário será redirecionado para a página de checkout para tentar nova forma de pagamento.
            $_SESSION['cordamensagem'] = 'red';
            $_SESSION['mensagem'] = "Ocorreu um erro com o seu pagamento tente novamente mais tarde.";
            header("location:../checkout?cc=$usuariologadodoclientepagante&token=$token&plan=$plan_get");
        endif;
    //else:
        //Se o pagamento for rejeitado o usuário será redirecionado para a página de checkout para tentar nova forma de pagamento.
        //$_SESSION['cordamensagem'] = 'red';
        //$_SESSION['mensagem'] = "<strong>Entre em contato conosco</strong> pois o <strong>MercadoPago</strong> não informou o Status do seu pagamento e por isso não conseguimos identificar de forma automática se o seu pagamento foi aprovado ou não. Contudo nossa equipe poderá realizar o procedimento de forma personalizada para você. <strong>Envie um e-mail para: pagamento@eupedi.com</strong> informando esse erro.";
        //header("location:../checkout?cc=$usuariologadodoclientepagante&token=$token&plan=$plan_get");
        //header("location:../checkout?cc=$usuariologadodoclientepagante&token=$token&plan=$plan_get");
    //endif;
