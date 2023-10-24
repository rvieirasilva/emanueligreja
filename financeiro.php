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

    //var_dump($_SESSION);
    if(isset($_SESSION["lideranca"])):
        include "protectcolaborador.php";	
    elseif(isset($_SESSION["membro"])):
        include "protectusuario.php";
    else:
        session_destroy();
        header("location:index");
	endif;    

    // if(isset($_GET['ref'])):
    //     $ideditado=$_GET['ref'];
    // endif;

    if(isset($_GET['t'])):
        $tipodeoperacaoget=$_GET['t'];
    elseif(isset($_GET['type'])):
        $tipodeoperacaoget=$_GET['type'];
    endif;  

    if(isset($_GET['ms'])):
        $mesdeoperacaoget=$_GET['ms'];
    else:
        $mesdeoperacaoget=$mesnumero;
    endif;

    if(isset($_GET['y'])):
        $yta3=$_GET['y'];
    else:
        $yta3=date('Y');
    endif;

    
	if(isset($_GET['backup']) AND !empty($_GET['backup'])):
        $anodebackup=$_GET['backup'];
        $ps=$_GET['backup']."-";
        $blancBackup="SELECT id FROM financeiro WHERE datarealizada LIKE '$ps%'";
         $rbBackup=mysqli_query($con, $blancBackup);
           $nBackup=mysqli_num_rows($rbBackup);
           if($nBackup > 0):
                @mkdir("arq/", 0777); //Cria a pasta se não houver. o @ é pra ocultar erro caso ela exista.
                @mkdir("arq/relatorios/", 0777); //Cria a pasta se não houver. o @ é pra ocultar erro caso ela exista.
                @mkdir("arq/relatorios/financeiro/", 0777); //Cria a pasta se não houver. o @ é pra ocultar erro caso ela exista.
                @mkdir("arq/relatorios/financeiro/$ano/", 0777); //Cria a pasta se não houver. o @ é pra ocultar erro caso ela exista.
                @mkdir("arq/relatorios/financeiro/$ano/", 0777); //Cria a pasta se não houver. o @ é pra ocultar erro caso ela exista.
                $arquivo = "arq/relatorios/financeiro/$ano/$ano.csv"; //Terá um log para cada mês do ano.
                $arquivo2 = fopen("arq/relatorios/financeiro/$ano/$ano.csv", "w"); // 1 - CRIAR ARQUIVO DE LOG no endereço informado em arquivo.

                $bBackup="SELECT codigodaoperacao, valorprevisto, descricao, dataprevista, valorrealizado, datarealizada, categoria, conta1, conta2, tipoderegistro FROM financeiro WHERE ano='$anodebackup' AND datarealizada LIKE '$ps%' OR dataprevista LIKE '$ps%'";
                  $rbackup=mysqli_query($con, $bBackup);
                    while($dbackup=mysqli_fetch_array($rbackup)):
                        if(!empty($dbackup['datarealizada'])): $dataBack=$dbackup['datarealizada']; else: $dataBack=$dbackup['dataprevista']; endif;
                        if(!empty($dbackup['valorrealizado'])): $valorBack=$dbackup['valorrealizado']; else: $valorBack=$dbackup['valorprevisto']; endif;
                        
                        $valorBack = number_format($valorBack, 2, ',', '.');
                        $codBack=$dbackup['codigodaoperacao'];
                            $descrBack=base64_decode(base64_decode($dbackup['descricao']));
                        $categoriaBack=$dbackup['categoria'];
                        $contaBack=$dbackup['conta1'];
                        if($dbackup['tipoderegistro'] === 'DÉBITO'): $valorBack="-$valorBack"; endif;

                        $registrofinanceiro=$dataBack.';'.$valorBack.';'.$codBack.';'.$descrBack.';'.$contaBack.';'.$categoriaBack."\n";
                        fwrite($arquivo2, $registrofinanceiro); // 2 - ESCREVER NO ARQUIVO.
                    endwhile;

                fclose($arquivo2); //Fecha o arquivo
                
                $_SESSION['cordamensagem']="orange";
                $_SESSION['mensagem']="Backup gerado com sucesso, <a href='$arquivo' download='$anodebackup - Backup financeiro Emanuel.csv' target='_blank'>Clique aqui para baixar.</a>.";
                header("refresh:5; url=financeiro?$linkSeguro");
           else:
            $_SESSION['cordamensagem']="orange";
            $_SESSION['mensagem']="Não há lançamentos para o ano informado.";
           endif;
    endif;

    
    if(isset($_POST['btn-cadastrardebito']) OR isset($_POST['btn-confirmardebito']) OR isset($_POST['btn-confirmardebito2']) OR isset($_POST['btn-cadastrar']) OR isset($_POST['btn-confirmarreceita']) OR isset($_POST['btn-confirmarreceita2']) OR isset($_POST['btn-solicitarsaque']) OR isset($_POST['btn-conta'])):
        $ideditado=mysqli_escape_string($con, $_POST['ref']);

        $newbankcod=mysqli_escape_string($con, $_POST['codbanco']);
        $newbankname=mysqli_escape_string($con, $_POST['nomebanco']);
        if(!empty($newbankcod) AND !empty($newbankname)):
            $newbank = $newbankcod.' - '.$newbankname;
            $snb="SELECT id FROM bancos WHERE cod='$newbankcod' OR banco='$newbankname'";
              $rnb=mysqli_query($con, $snb);
                $nnb=mysqli_num_rows($rnb);
            if($nnb < 1):
                $innb="INSERT INTO bancos (cod, banco) VALUES ('$newbankcod', '$newbankname')";
                if(mysqli_query($con, $innb)):
                    $banconew = $newbankcod.' - '.$newbankname;
                    $_SESSION['codadamensagem']='red';
                    $_SESSION['mensagem']="Houve uma tentativa de cadastrar um banco registrado anteriormente, esta tentativa foi bloqueada.";
                else:
                    $banco='Desconhecido';
                    $_SESSION['codadamensagem']='red';
                    $_SESSION['mensagem']="Erro ao registrar novo banco no sistema.";
                endif;
            else:
                $_SESSION['codadamensagem']='red';
                $_SESSION['mensagem']="Houve uma tentativa de cadastrar um banco registrado anteriormente, esta tentativa foi bloqueada.";
            endif;
        endif;

        
        $dataprevista                       = mysqli_escape_string($con, $_POST['realizadoem']);
        //Pegar dia, mês e ano previsto
        $dataprevistaSEP = explode('-', $dataprevista); // 2020 - 03 - 30
        $anoprevisto     = $dataprevistaSEP[0]; // 2020
        $mesprevisto     = $dataprevistaSEP[1]; // 
        $diaprevisto     = $dataprevistaSEP[2]; // 

        /* VERIFICAR SE HÁ LANÇAMENTO REALIZADO NO MÊS, SE HÁ LIMITE PARA RESERVA, MISSÕES E OFERTA PASTORAL*/
        $buscarPrimeiroLancamentoDoMes="SELECT id FROM financeiro WHERE datarealizada !=''";
          $rPLM=mysqli_query($con, $buscarPrimeiroLancamentoDoMes);
            $nPLM=mysqli_num_rows($rPLM);
        
        if($nPLM < 1):
            $mesanteriorOferta = date(('m'), strtotime($mesnumero.'- 1 months'));

            $Bpartners="SELECT datarealizada FROM financeiro WHERE NOT (datarealizada='') ORDER BY id desc LIMIT 1";
            $rP=mysqli_query($con, $Bpartners);
                $dP=mysqli_fetch_array($rP);

            $ultimolancamento=$dP['datarealizada'];
            $ultimolancamento=explode('-', $ultimolancamento);
            $anolancado=$ultimolancamento[0];
            $meslancado=$ultimolancamento[1];

            if($anolancado < $ano AND $meslancado === '12' AND $mesnumero === '1'):
                $DataDeOferta=$anolancado.'-'.$meslancado;
            else:
                $DataDeOferta=$ano.'-'.$mesanteriorOferta;
            endif;
            
            $bCredOferta = "SELECT sum(valorrealizado) as somadomes FROM financeiro WHERE NOT (valorrealizado = '') AND tipoderegistro='CRÉDITO' AND datarealizada LIKE '$DataDeOferta%' AND conta1!='272112-8' AND conta1!='0000' AND categoria !='FEP'"; //Venda Mercado Pago.
            $rCO=mysqli_query($con, $bCredOferta);
            $dCO=mysqli_fetch_array($rCO);
        
            $CreditoMesDeOferta=$dCO['somadomes'];

            //Saldo do mês de Débito
            $bDebOferta = "SELECT sum(valorrealizado) as somadomesSaida FROM financeiro WHERE NOT (valorrealizado = '') AND tipoderegistro='DÉBITO' AND datarealizada LIKE '$DataDeOferta%' AND conta1!='272112-8' AND conta1!='0000' AND categoria !='FEP'"; //Venda Mercado Pago.
                $rDebitoOferta=mysqli_query($con, $bDebOferta);
                $dDO=mysqli_fetch_array($rDebitoOferta);
            
            $DebitoMesDeOferta=$dDO['somadomesSaida'];

            $saldoDeOferta= $CreditoMesDeOferta - $DebitoMesDeOferta;
            
            if($saldoDeOferta > 100):
                $smR=$saldoDeOferta; //Saldo do mês para cálculo das reservas
                $missions=$smR * 0.1;
                $reservavariavel=$saldoDeOferta*0.2;
                $reservacash=$saldoDeOferta*0.1;
                $trafego=$saldoDeOferta*0.2;
                $ofertapastoral=$saldoDeOferta*0.05;
                $limpeza=$saldoDeOferta*0.03;

                $dataprevista=$ano.'-'.$mesnumero.'-'.$dia;

                /*Reserva missionária*/
                $descricao="Valor reservado para Missões com base na arrecadação do mês anterior";
                $valorprevisto=number_format($missions, 2, '.','');
                $codigodaoperacao='R-Missions-'.$dia.$mesnumero.$ano;
                $recebidode="Missões e Evangelismo";
                $inMissions="INSERT INTO financeiro (empresa, codigodoparceiro, urldaempresa, dia, mes, ano, representante, codigodorepresentante, codigodaoperacao, descricao, valorprevisto, dataprevista, valorrealizado, datarealizada, recebidode, pagopor, anexo, tamanhodoarquivo, tipodeoperacao, categoria, centrodecusto, conta1, conta2, tipoderegistro) VALUES ('Emanuel', '0001', 'igrejaemanuel', '$dia', '$mesnumero', '$ano', '$nomedousuario', '$matriculausuario', '$codigodaoperacao', '$descricao', '$valorprevisto', '$dataprevista', '', '', '$recebidode', '', '', '', '', '$recebidode', '', '1861305-6', '', 'DÉBITO')";
                mysqli_query($con, $inMissions);

                /*Oferta pastora*/
                $descricao="Valor para oferta pastoral com base na arrecadação do mês anterior";
                $valorprevisto=number_format($ofertapastoral, 2, '.','');
                $codigodaoperacao='R-Pastoral-'.$dia.$mesnumero.$ano;
                $recebidode="Oferta Pastoral";
                $inPastoral="INSERT INTO financeiro (empresa, codigodoparceiro, urldaempresa, dia, mes, ano, representante, codigodorepresentante, codigodaoperacao, descricao, valorprevisto, dataprevista, valorrealizado, datarealizada, recebidode, pagopor, anexo, tamanhodoarquivo, tipodeoperacao, categoria, centrodecusto, conta1, conta2, tipoderegistro) VALUES ('Emanuel', '0001', 'igrejaemanuel', '$dia', '$mesnumero', '$ano', '$nomedousuario', '$matriculausuario', '$codigodaoperacao', '$descricao', '$valorprevisto', '$dataprevista', '', '', '$recebidode', '', '', '', '', '$recebidode', '', '1861305-6', '', 'DÉBITO')";
                mysqli_query($con, $inPastoral);

                /*Trafego pago (Evangelismo digital)*/
                $descricao="Valor para evangelismo digital (tráfego pago) com base na arrecadação do mês anterior";
                $valorprevisto=number_format($trafego, 2, '.','');
                $codigodaoperacao='R-Trafego-'.$dia.$mesnumero.$ano;
                $recebidode="Evangelismo digital";
                $inTrafego="INSERT INTO financeiro (empresa, codigodoparceiro, urldaempresa, dia, mes, ano, representante, codigodorepresentante, codigodaoperacao, descricao, valorprevisto, dataprevista, valorrealizado, datarealizada, recebidode, pagopor, anexo, tamanhodoarquivo, tipodeoperacao, categoria, centrodecusto, conta1, conta2, tipoderegistro) VALUES ('Emanuel', '0001', 'igrejaemanuel', '$dia', '$mesnumero', '$ano', '$nomedousuario', '$matriculausuario', '$codigodaoperacao', '$descricao', '$valorprevisto', '$dataprevista', '', '', '$recebidode', '', '', '', '', '$recebidode', '', '1861305-6', '', 'DÉBITO')";
                mysqli_query($con, $inTrafego);

                /*Reserva variavel*/
                $descricao="Valor para reserva variável com base na arrecadação do mês anterior";
                $valorprevisto=number_format($reservavariavel, 2, '.','');
                $codigodaoperacao='R-variavel-'.$dia.$mesnumero.$ano;
                $recebidode="Fundo de Emergência";
                $inVariavelD="INSERT INTO financeiro (empresa, codigodoparceiro, urldaempresa, dia, mes, ano, representante, codigodorepresentante, codigodaoperacao, descricao, valorprevisto, dataprevista, valorrealizado, datarealizada, recebidode, pagopor, anexo, tamanhodoarquivo, tipodeoperacao, categoria, centrodecusto, conta1, conta2, tipoderegistro) VALUES ('Emanuel', '0001', 'igrejaemanuel', '$dia', '$mesnumero', '$ano', '$nomedousuario', '$matriculausuario', '$codigodaoperacao', '$descricao', '$valorprevisto', '$dataprevista', '', '', '$recebidode', '', '', '', '', '$recebidode', '', '1861305-6', '272112-8', 'DÉBITO')";
                mysqli_query($con, $inVariavelD);
                $inVariavelC="INSERT INTO financeiro (empresa, codigodoparceiro, urldaempresa, dia, mes, ano, representante, codigodorepresentante, codigodaoperacao, descricao, valorprevisto, dataprevista, valorrealizado, datarealizada, recebidode, pagopor, anexo, tamanhodoarquivo, tipodeoperacao, categoria, centrodecusto, conta1, conta2, tipoderegistro) VALUES ('Emanuel', '0001', 'igrejaemanuel', '$dia', '$mesnumero', '$ano', '$nomedousuario', '$matriculausuario', '$codigodaoperacao', '$descricao', '$valorprevisto', '$dataprevista', '', '', '$recebidode', '', '', '', '', '$recebidode', '', '272112-8', '1861305-6', 'CRÉDITO')";
                mysqli_query($con, $inVariavelC);

                /*Reserva em dinheiro*/
                $descricao="Valor para reserva em dinheiro com base na arrecadação do mês anterior";
                $valorprevisto=number_format($reservacash, 2, '.','');
                $codigodaoperacao='R-Dinheiro-'.$dia.$mesnumero.$ano;
                $inCashD="INSERT INTO financeiro (empresa, codigodoparceiro, urldaempresa, dia, mes, ano, representante, codigodorepresentante, codigodaoperacao, descricao, valorprevisto, dataprevista, valorrealizado, datarealizada, recebidode, pagopor, anexo, tamanhodoarquivo, tipodeoperacao, categoria, centrodecusto, conta1, conta2, tipoderegistro) VALUES ('Emanuel', '0001', 'igrejaemanuel', '$dia', '$mesnumero', '$ano', '$nomedousuario', '$matriculausuario', '$codigodaoperacao', '$descricao', '$valorprevisto', '$dataprevista', '', '', '$recebidode', '', '', '', '', '$recebidode', '', '1861305-6', '272112-8', 'DÉBITO')";
                mysqli_query($con, $inCashD);
                $inCashC="INSERT INTO financeiro (empresa, codigodoparceiro, urldaempresa, dia, mes, ano, representante, codigodorepresentante, codigodaoperacao, descricao, valorprevisto, dataprevista, valorrealizado, datarealizada, recebidode, pagopor, anexo, tamanhodoarquivo, tipodeoperacao, categoria, centrodecusto, conta1, conta2, tipoderegistro) VALUES ('Emanuel', '0001', 'igrejaemanuel', '$dia', '$mesnumero', '$ano', '$nomedousuario', '$matriculausuario', '$codigodaoperacao', '$descricao', '$valorprevisto', '$dataprevista', '', '', '$recebidode', '', '', '', '', '$recebidode', '', '272112-8', '1861305-6', 'CRÉDITO')";
                mysqli_query($con, $inCashC);

                /*Reserva para limpeza*/
                $descricao="Valor para de verba para material de limpeza, com base na arrecadação do mês anterior";
                $valorprevisto=number_format($limpeza, 2, '.','');
                $codigodaoperacao='R-Limpeza-'.$dia.$mesnumero.$ano;
                $inLimpezaD="INSERT INTO financeiro (empresa, codigodoparceiro, urldaempresa, dia, mes, ano, representante, codigodorepresentante, codigodaoperacao, descricao, valorprevisto, dataprevista, valorrealizado, datarealizada, recebidode, pagopor, anexo, tamanhodoarquivo, tipodeoperacao, categoria, centrodecusto, conta1, conta2, tipoderegistro) VALUES ('Emanuel', '0001', 'igrejaemanuel', '$dia', '$mesnumero', '$ano', '$nomedousuario', '$matriculausuario', '$codigodaoperacao', '$descricao', '$valorprevisto', '$dataprevista', '', '', '$recebidode', '', '', '', '', '$recebidode', '', '1861305-6', '272112-8', 'DÉBITO')";
                mysqli_query($con, $inLimpezaD);
                $inLimpezaC="INSERT INTO financeiro (empresa, codigodoparceiro, urldaempresa, dia, mes, ano, representante, codigodorepresentante, codigodaoperacao, descricao, valorprevisto, dataprevista, valorrealizado, datarealizada, recebidode, pagopor, anexo, tamanhodoarquivo, tipodeoperacao, categoria, centrodecusto, conta1, conta2, tipoderegistro) VALUES ('Emanuel', '0001', 'igrejaemanuel', '$dia', '$mesnumero', '$ano', '$nomedousuario', '$matriculausuario', '$codigodaoperacao', '$descricao', '$valorprevisto', '$dataprevista', '', '', '$recebidode', '', '', '', '', '$recebidode', '', '272112-8', '1861305-6', 'CRÉDITO')";
                mysqli_query($con, $inLimpezaC);
            endif;
        endif;
        /* VERIFICAR SE HÁ LANÇAMENTO REALIZADO NO MÊS, SE HÁ LIMITE PARA RESERVA, MISSÕES E OFERTA PASTORAL*/
    endif;
    
    if(isset($_POST['btn-conta'])):
        $conta      = mysqli_escape_string($con, $_POST['conta']);

        $viewconta                = mysqli_escape_string($con, $_POST['viewconta']);
        $nomedaconta                = mysqli_escape_string($con, $_POST['nomedaconta']);
        $tipodeconta                = mysqli_escape_string($con, $_POST['tipodeconta']);
        if(!empty($banconew)):
            $banco=$banconew;
        else:
            $banco                  =   mysqli_escape_string($con, $_POST['banco']);
        endif;
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
        $bConta = "SELECT conta FROM contasfinanceiro WHERE empresa='emanuel' AND urldaempresa='igrejaemanuel' AND conta='$conta' AND banco='$banco'";
            $rConta = mysqli_query($con, $bConta);
            $nConta=mysqli_num_rows($rConta);
        
        if($nConta < 1):
            //Limitar cinco contas
            $fivecontas="SELECT id FROM contasfinanceiro WHERE empresa='emanuel' AND urldaempresa='igrejaemanuel'";
                $rFC=mysqli_query($con, $fivecontas);
                $nFC=mysqli_num_rows($rFC);

                    $codigodaconta = $nFC.mt_rand(01, 999);

            if($nFC < 5):
                $addConta = "INSERT INTO contasfinanceiro (empresa, codigodoparceiro, urldaempresa, dia, mes, ano, representante, codigodorepresentante, codigodaconta, nomedaconta, Banco, agencia, conta, operador, titular, cpf, tipodeconta, viewconta) VALUES ('emanuel', '0001', 'igrejaemanuel', '$dia', '$mes', '$ano', '$nomedousuario', '$matriculausuario', '$codigodaconta',  '$nomedaconta', '$banco', '$agencia', '$conta', '$operador', '$titular', '$cpf', '$tipodeconta', '$viewconta')";
                mysqli_query($con, $addConta);
            endif;
        else:
            $_SESSION['cordamensagem']='red';
            $_SESSION['mensagem']="Esta conta $conta foi inserida para este banco $banco anteriormente.";
        endif;
    endif;
    
    if(isset($_POST['btn-cadastrardebito'])):
        //if($statusdaempresa === 'APROVADO'):	

            $repetirmes                         = mysqli_escape_string($con, $_POST['repetirmes']);
            $repetirano                         = mysqli_escape_string($con, $_POST['repetirano']);

            $dataprevista                       = mysqli_escape_string($con, $_POST['realizadoem']);
            //Pegar dia, mês e ano previsto
            $dataprevistaSEP = explode('-', $dataprevista); // 2020 - 03 - 30
            $anoprevisto     = $dataprevistaSEP[0]; // 2020
            $mesprevisto     = $dataprevistaSEP[1]; // 
            $diaprevisto     = $dataprevistaSEP[2]; // 

            if($_FILES['anexo']['name'] !== ''):
                //CAPA
                $extensao = pathinfo($_FILES['anexo']['name'], PATHINFO_EXTENSION);
                if(in_array($extensao, $formatospermitidos)):
                    @mkdir("arq/", 0777); //Cria a pasta se não houver. o @ é pra ocultar erro caso
                    @mkdir("arq/financeiro/", 0777); //Cria a pasta se não houver. o @ é pra ocultar erro caso
                    @mkdir("arq/financeiro/arquivos/", 0777); //Cria a pasta se não houver. o @ é pra ocultar erro caso
                    @mkdir("arq/financeiro/arquivos/debito/", 0777); //Cria a pasta se não houver. o @ é pra ocultar erro caso
                    @mkdir("arq/financeiro/arquivos/debito/$ano/", 0777); //Cria a pasta se não houver. o @ é pra ocultar erro caso
                    $pastaanexo			= "arq/financeiro/arquivos/debito/$ano/";
                    $temporarioanexo	= $_FILES['anexo']['tmp_name'];
                    $tamanhoanexo	    = $_FILES['anexo']['size'];
                    $novoNomeanexo		= date('H').mt_rand(6000,9999).'.'.$extensao;
                        $anexo			= $pastaanexo.$novoNomeanexo;
                    move_uploaded_file($temporarioanexo, $anexo);
                endif;
            endif;

            if(!empty($repetirmes) AND !empty($repetirano)):
                //Algorítimo para definir a quantidade de meses que vai ser acrescendado para a operação.
                $datainicialprevista  = $anoprevisto.'-'.$mesprevisto.'-'.$diaprevisto;
                $pedidopararepetirate = $repetirano.'-'.$repetirmes.'-'.$diaprevisto; $pedidopararepetirate=date(('Y-m-d'), strtotime($pedidopararepetirate.'+1 months'));

                $diff = abs(strtotime($pedidopararepetirate) - strtotime($datainicialprevista));

                $years = floor($diff / (365*60*60*24));
                $months = floor(($diff - $years * 365*60*60*24) / (30*60*60*24));
                // $days = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24)/ (60*60*24));

                if($years > 0):
                    $anosemeses = $years * 12;
                else:
                    $anosemeses = 0;
                endif;

                $anosemeses= $anosemeses + $months; //Com base nos anos e os meses definidos
                    // printf("%d years, %d months, %d days\n", $years, $months, $days);
                //Algorítimo para definir a quantidade de meses que vai ser acrescendado para a operação.
            else:
                $anosemeses = 1;
            endif;

            
                $anoZERO=-1;
            for($anosrepetindo=0; $anosrepetindo < $anosemeses; $anosrepetindo++){                
                $anoZERO++;

                
                if($anoZERO < 1):
                    $dataprevista= $anoprevisto.'-'.$mesprevisto.'-'.$diaprevisto;
                    $anoprevisto = $anoprevisto;
                    $mesprevisto = $mesprevisto;
                    $diaprevisto = $diaprevisto;
                    $_SESSION['ultimadataprevista']=$dataprevista; //Fixa a data prevista na SESSION para gerar as próximas.
                else:
                    $ultimadataprevista = $_SESSION['ultimadataprevista'];
                    $dataprevista = date(('Y-m-d'), strtotime($ultimadataprevista."+ $anoZERO months"));
                        //Em cima pega a última data prevista e adiciona um mês conforme o número de meses resultado entre o ínicio e a data de término.
                    
                    $dataprevistaREP = explode('-', $dataprevista); // 2020 - 03 - 30
                    $anoprevisto     = $dataprevistaREP[0]; // 2020
                    $mesprevisto     = $dataprevistaREP[1]; // 
                    $diaprevisto     = $dataprevistaREP[2]; // 
                endif;

                $confirmacaodepagamento             = mysqli_escape_string($con, $_POST['confirmacaodepagamento']);
                $codigodaoperacao                   = mysqli_escape_string($con, $_POST['codigodaoperacao']);
                $descricao                          = mysqli_escape_string($con, $_POST['descricao']);

                @$recebidode                         = mysqli_escape_string($con, $_POST['recebidode']);
                @$recebidode2                        = mysqli_escape_string($con, $_POST['recebidode2']);
                    //Verificar se foi inserido novo ou se é de um Consumidor.
                    if(!empty($recebidode2)): //Se o usuário inseriu um nome no campo Recebido2
                        $recebidode = $recebidode2;
                    endif;
                $categoria                          = mysqli_escape_string($con, $_POST['categoria']);
                $centrodecusto                      = mysqli_escape_string($con, $_POST['centrodecusto']);
                $conta                              = mysqli_escape_string($con, $_POST['conta']);
                $tipodeoperacao                     = mysqli_escape_string($con, $_POST['tipodeoperacao']);
                $contadeorigem                      = mysqli_escape_string($con, $_POST['contadeorigem']);
                $contadedestino                     = mysqli_escape_string($con, $_POST['contadedestino']);

                if(!empty($codigodaoperacao)):
                    $codigodaoperacaoo = $codigodaoperacao;
                elseif(isset($_SESSION['codigodaoperacao'])):
                    $codigodaoperacao = $_SESSION['codigodaoperacao'];
                else:
                    //Buscar id anterior para evitar código duplicado.
                    $biddupli="SELECT id FROM financeiro";
                    @$riddupli=mysqli_query($con, $biddupli);
                        @$diddupli=mysqli_num_rows($riddupli);
                        
                    $ultimoid = $diddupli;

                    $codigodaoperacaoo = $ultimoid.mt_rand(1000, 19999);
                    $codigodaoperacao=$codigodaoperacaoo;
                endif;

                if(!isset($_SESSION['codigodaoperacao'])): //Se não existir vai criar a SESSION.
                    $_SESSION['codigodaoperacao'] = $codigodaoperacao; //Fixa o mesmo código para as operações do próximo mês.
                endif;
                
                $valorprevisto                      = mysqli_escape_string($con, $_POST['valorprevisto']);
                    //Adequa o valor para BD
                    if($valorprevisto >= 1.000): //Se o valor inserido for maior que 1000 utiliza o algoritimo abaixo.
                        @$valorprevisto = explode('.', $valorprevisto); //Separa a milhar 1 099,60
                        @$valorprevisto  = $valorprevisto[0].$valorprevisto[1]; //Une o valor mantendo a virgula: 1099,60
                        @$valorprevisto  = str_replace(',','.',$valorprevisto); //Troca a virgula por ponto para as operações matemáticas do php
                        @$valorprevisto  = $valorprevisto; //Troca a virgula por ponto para as operações matemáticas do php
                    elseif($valorprevisto < 1.000): //Se for inferior utiliza o algoritimo.
                        @$valorprevisto2 = explode(',', $valorprevisto); //Separa as moedas 099 60
                        @$valorprevisto  = $valorprevisto2[0].'.'.$valorprevisto2[1]; //Une as moedas com PONTO.
                    endif;

                //Criptografia 1
                $recebidode = base64_encode($recebidode);
                $descricao  = base64_encode($descricao);

                //Criptografia 2
                $recebidode = base64_encode($recebidode);
                $descricao  = base64_encode($descricao);

                // if($anoZERO > 0): //Nas repetições futuras não será sinalizadas como pagas.
                //     $valorprevistofuturo=''; 
                // else:
                    $valorprevistofuturo=$valorprevisto;
                // endif;
        
                $bED = "SELECT id FROM financeiro WHERE descricao='$descricao' AND recebidode='$recebidode' AND valorprevisto='$valorprevisto' AND dataprevista='$dataprevista' AND tipoderegistro='DÉBITO' AND conta1='$conta'";
                $rBPD = mysqli_query($con, $bED);
                    $nBPD = mysqli_num_rows($rBPD);

                if($nBPD < 1): //Verifica duplicado
                    if($anoZERO > 0): //Adequa informações de valor previsto e valor real
                        if($tipodeoperacao === 'Transferência'): //Caso ocorra uma operação de transferência.
                            $inserir="INSERT INTO financeiro (empresa, codigodoparceiro, urldaempresa, dia, mes, ano, representante, codigodorepresentante, codigodaoperacao, descricao, valorprevisto, dataprevista, valorrealizado, datarealizada, recebidode, pagopor, anexo, tamanhodoarquivo, tipodeoperacao, categoria, centrodecusto, conta1, conta2, tipoderegistro) VALUES ('Emanuel', '0001', 'igrejaemanuel', '$diaprevisto', '$mesprevisto', '$anoprevisto', '$nomedousuario', '$matriculausuario', '$codigodaoperacaoo', '$descricao', '$valorprevisto', '$dataprevista', '$valorprevisto', '$dataprevista', '$recebidode', '', '$anexo', '$tamanhoanexo', '$tipodeoperacao', '$categoria', '$centrodecusto', '$contadeorigem', '$contadedestino', 'DÉBITO')"; 
                            $TirarDaContaAnterior="INSERT INTO financeiro (empresa, codigodoparceiro, urldaempresa, dia, mes, ano, representante, codigodorepresentante, codigodaoperacao, descricao, valorprevisto, dataprevista, valorrealizado, datarealizada, recebidode, pagopor, anexo, tamanhodoarquivo, tipodeoperacao, categoria, centrodecusto, conta1, conta2, tipoderegistro) VALUES ('Emanuel', '0001', 'igrejaemanuel', '$diaprevisto', '$mesprevisto', '$anoprevisto', '$nomedousuario', '$matriculausuario', '$codigodaoperacaoo', '$descricao', '$valorprevisto', '$dataprevista', '$valorprevisto', '$dataprevista', '$recebidode', '', '$anexo', '$tamanhoanexo', '$tipodeoperacao', '$categoria', '$centrodecusto', '$contadedestino', '$contadeorigem', 'CRÉDITO')"; 
                            mysqli_query($con, $TirarDaContaAnterior);
                        elseif(!empty($confirmacaodepagamento)):
                            @$inserir="INSERT INTO financeiro (empresa, codigodoparceiro, urldaempresa, dia, mes, ano, representante, codigodorepresentante, codigodaoperacao, descricao, valorprevisto, dataprevista, valorrealizado, datarealizada, recebidode, pagopor, anexo, tamanhodoarquivo, tipodeoperacao, categoria, centrodecusto, conta1, conta2, tipoderegistro) VALUES ('Emanuel', '0001', 'igrejaemanuel', '$diaprevisto', '$mesprevisto', '$anoprevisto', '$nomedousuario', '$matriculausuario', '$codigodaoperacaoo', '$descricao', '$valorprevistofuturo', '$dataprevista', '', '', '$recebidode', '', '$anexo', '$tamanhoanexo', '$tipodeoperacao', '$categoria', '$centrodecusto', '$conta', '', 'DÉBITO')"; 
                        else:
                            @$inserir="INSERT INTO financeiro (empresa, codigodoparceiro, urldaempresa, dia, mes, ano, representante, codigodorepresentante, codigodaoperacao, descricao, valorprevisto, dataprevista, valorrealizado, datarealizada, recebidode, pagopor, anexo, tamanhodoarquivo, tipodeoperacao, categoria, centrodecusto, conta1, conta2, tipoderegistro) VALUES ('Emanuel', '0001', 'igrejaemanuel', '$diaprevisto', '$mesprevisto', '$anoprevisto', '$nomedousuario', '$matriculausuario', '$codigodaoperacaoo', '$descricao', '$valorprevisto', '$dataprevista', '', '', '$recebidode', '', '$anexo', '$tamanhoanexo', '$tipodeoperacao', '$categoria', '$centrodecusto', '$conta', '', 'DÉBITO')"; 
                        endif;
                    else:
                        if($tipodeoperacao === 'Transferência'): //Caso ocorra uma operação de transferência.
                            $inserir="INSERT INTO financeiro (empresa, codigodoparceiro, urldaempresa, dia, mes, ano, representante, codigodorepresentante, codigodaoperacao, descricao, valorprevisto, dataprevista, valorrealizado, datarealizada, recebidode, pagopor, anexo, tamanhodoarquivo, tipodeoperacao, categoria, centrodecusto, conta1, conta2, tipoderegistro) VALUES ('Emanuel', '0001', 'igrejaemanuel', '$diaprevisto', '$mesprevisto', '$anoprevisto', '$nomedousuario', '$matriculausuario', '$codigodaoperacaoo', '$descricao', '$valorprevisto', '$dataprevista', '$valorprevisto', '$dataprevista', '$recebidode', '', '$anexo', '$tamanhoanexo', '$tipodeoperacao', '$categoria', '$centrodecusto', '$contadeorigem', '$contadedestino', 'DÉBITO')"; 
                            $TirarDaContaAnterior="INSERT INTO financeiro (empresa, codigodoparceiro, urldaempresa, dia, mes, ano, representante, codigodorepresentante, codigodaoperacao, descricao, valorprevisto, dataprevista, valorrealizado, datarealizada, recebidode, pagopor, anexo, tamanhodoarquivo, tipodeoperacao, categoria, centrodecusto, conta1, conta2, tipoderegistro) VALUES ('Emanuel', '0001', 'igrejaemanuel', '$diaprevisto', '$mesprevisto', '$anoprevisto', '$nomedousuario', '$matriculausuario', '$codigodaoperacaoo', '$descricao', '$valorprevisto', '$dataprevista', '$valorprevisto', '$dataprevista', '$recebidode', '', '$anexo', '$tamanhoanexo', '$tipodeoperacao', '$categoria', '$centrodecusto', '$contadedestino', '$contadeorigem', 'CRÉDITO')"; 
                            mysqli_query($con, $TirarDaContaAnterior);
                        elseif(!empty($confirmacaodepagamento)):
                            @$inserir="INSERT INTO financeiro (empresa, codigodoparceiro, urldaempresa, dia, mes, ano, representante, codigodorepresentante, codigodaoperacao, descricao, valorprevisto, dataprevista, valorrealizado, datarealizada, recebidode, pagopor, anexo, tamanhodoarquivo, tipodeoperacao, categoria, centrodecusto, conta1, conta2, tipoderegistro) VALUES ('Emanuel', '0001', 'igrejaemanuel', '$diaprevisto', '$mesprevisto', '$anoprevisto', '$nomedousuario', '$matriculausuario', '$codigodaoperacaoo', '$descricao', '', '', '$valorprevistofuturo', '$dataprevista', '$recebidode', '', '$anexo', '$tamanhoanexo', '$tipodeoperacao', '$categoria', '$centrodecusto', '$conta', '', 'DÉBITO')"; 
                        else:
                            @$inserir="INSERT INTO financeiro (empresa, codigodoparceiro, urldaempresa, dia, mes, ano, representante, codigodorepresentante, codigodaoperacao, descricao, valorprevisto, dataprevista, valorrealizado, datarealizada, recebidode, pagopor, anexo, tamanhodoarquivo, tipodeoperacao, categoria, centrodecusto, conta1, conta2, tipoderegistro) VALUES ('Emanuel', '0001', 'igrejaemanuel', '$diaprevisto', '$mesprevisto', '$anoprevisto', '$nomedousuario', '$matriculausuario', '$codigodaoperacaoo', '$descricao', '$valorprevisto', '$dataprevista', '', '', '$recebidode', '', '$anexo', '$tamanhoanexo', '$tipodeoperacao', '$categoria', '$centrodecusto', '$conta', '', 'DÉBITO')"; 
                        endif;
                    endif;
                    
                    if(mysqli_query($con, $inserir)):
                        //Se inserir no BD move as imagens.
                        //move_uploaded_file($temporariominiatura, $pastaminiatura.$novoNomeminiatura);

                        $_SESSION['cordamensagem']="green";
                        $_SESSION['mensagem']="Débito <strong>cadastrada</strong> com sucesso.";
                        // header("refresh:1, url=financeiro$linkSeguro&type=$tipodeoperacaoget&t=$tipodeoperacaoget&ms=$mesdeoperacaoget&y=$yta2");
                    else:
                        @unlink($anexo); //Exclui o arquivo que foi enviado anteriormente.
                        $_SESSION['cordamensagem']="red";
                        $_SESSION['mensagem']="<strong>Verifique sua conexão</strong>. Erro ao cadastrar débito.";
                    endif;
                    
                else:
                    $_SESSION['cordamensagem']="red";
                    $_SESSION['mensagem']="Esta movimentação foi registrada anteriormente.";
                endif;
                
            }

            unset($_SESSION['ultimadataprevista']); //Após todas as repetições encerra as sessions criadas para outras operações acontecerem.
            unset($_SESSION['codigodaoperacao']); //Após todas as repetições encerra as sessions criadas para outras operações acontecerem.
        // else:
        //     $_SESSION['cordamensagem']="youtube";	
        //     $_SESSION['mensagem']="Entre em contato com a <strong>EuPedi</strong>. Loja impossibilitada de cadastrar débito.";
        // endif;
    endif;

    if(isset($_POST['btn-confirmardebito']) OR isset($_POST['btn-confirmardebito2'])):
        // //if($statusdaempresa === 'APROVADO'):	
            $confirmacaodepagamento = mysqli_escape_string($con, $_POST['confirmacaodepagamento']); //Se vazio é para retirar confirmação de pagamento.
            $tipodeoperacaoget = mysqli_escape_string($con, $_POST['tipodeoperacaoget']);
            $mesdeoperacaoget = mysqli_escape_string($con, $_POST['mesdeoperacaoget']);
            $codigodaoperacao0 = mysqli_escape_string($con, $_POST['codigodaoperacao0']);
            $anexoanterior     = mysqli_escape_string($con, $_POST['anexoanterior']);

            if($_FILES['anexo']['name'] !== ''):
                //Apaga anexo antigo.
                $bAn="SELECT anexo FROM financeiro WHERE codigodaoperacao='$codigodaoperacao0'";
                  $rAn=mysqli_query($con, $bAn);
                    $dAn=mysqli_fetch_array($rAn);

                    $anexoanterior = $dAn['anexo'];

                @unlink($anexoanterior);

                //Move o novo anexo.
                $extensao = pathinfo($_FILES['anexo']['name'], PATHINFO_EXTENSION);
                if(in_array($extensao, $formatospermitidos)):
                    @mkdir("arq/", 0777); //Cria a pasta se não houver. o @ é pra ocultar erro caso
                    @mkdir("arq/financeiro/", 0777); //Cria a pasta se não houver. o @ é pra ocultar erro caso
                    @mkdir("arq/financeiro/arquivos/", 0777); //Cria a pasta se não houver. o @ é pra ocultar erro caso
                    @mkdir("arq/financeiro/arquivos/debito/", 0777); //Cria a pasta se não houver. o @ é pra ocultar erro caso
                    @mkdir("arq/financeiro/arquivos/debito/$ano/", 0777); //Cria a pasta se não houver. o @ é pra ocultar erro caso
                    $pastaanexo			= "arq/financeiro/arquivos/debito/$ano/";
                    $temporarioanexo	= $_FILES['anexo']['tmp_name'];
                    $tamanhoanexo	    = $_FILES['anexo']['size'];
                    $novoNomeanexo		= date('H').mt_rand(6000,9999).'.'.$extensao;
                        $anexo			= $pastaanexo.$novoNomeanexo;
                    move_uploaded_file($temporarioanexo, $anexo);
                endif;
            else:
                $anexo = $anexoanterior;
            endif;
            
            $codigodaoperacao                   = mysqli_escape_string($con, $_POST['codigodaoperacao']);
            $descricao                          = mysqli_escape_string($con, $_POST['descricao']);
            $dataprevista                       = mysqli_escape_string($con, $_POST['realizadoem']);
            //Pegar dia, mês e ano previsto
            $dataprevistaSEP = explode('-', $dataprevista); // 2020 - 03 - 30
            $anoprevisto     = $dataprevistaSEP[0]; // 2020
            $mesprevisto     = $dataprevistaSEP[1]; // 
            $diaprevisto     = $dataprevistaSEP[2]; // 

            @$recebidode                         = mysqli_escape_string($con, $_POST['recebidode']);
            @$recebidode2                        = mysqli_escape_string($con, $_POST['recebidode2']);
            //Verificar se foi inserido novo ou se é de um Consumidor.
            if(!empty($recebidode2)): //Se o usuário inseriu um nome no campo Recebido2
                $recebidode = $recebidode2;
            endif;
            $categoria                          = mysqli_escape_string($con, $_POST['categoria']);
            $centrodecusto                      = mysqli_escape_string($con, $_POST['centrodecusto']);
            $conta                              = mysqli_escape_string($con, $_POST['conta']);
            $tipodeoperacao                     = mysqli_escape_string($con, $_POST['tipodeoperacao']);
            $contadeorigem                      = mysqli_escape_string($con, $_POST['contadeorigem']);
            $contadedestino                     = mysqli_escape_string($con, $_POST['contadedestino']);

            if($codigodaoperacao != ''):
                $codigodaoperacaoo = $codigodaoperacao;
            else:
                $codigodaoperacaoo = $codigodaoperacao0;
            endif;

            @$valorprevisto                      = mysqli_escape_string($con, $_POST['valorprevisto']);
            //Adequa o valor para BD
            if($valorprevisto >= 1.000): //Se o valor inserido for maior que 1000 utiliza o algoritimo abaixo.
                @$valorprevisto = explode('.', $valorprevisto); //Separa a milhar 1 099,60
                @$valorprevisto  = $valorprevisto[0].$valorprevisto[1]; //Une o valor mantendo a virgula: 1099,60
                @$valorprevisto  = str_replace(',','.',$valorprevisto); //Troca a virgula por ponto para as operações matemáticas do php
            elseif($valorprevisto < 1.000): //Se for inferior utiliza o algoritimo.
                @$valorprevisto2 = explode(',', $valorprevisto); //Separa as moedas 099 60
                @$valorprevisto  = $valorprevisto2[0].'.'.$valorprevisto2[1]; //Une as moedas com PONTO.
            endif;

            
            //Criptografia 1
            $recebidode = base64_encode($recebidode);
            $descricao  = base64_encode($descricao);

            //Criptografia 2
            $recebidode = base64_encode($recebidode);
            $descricao  = base64_encode($descricao);
            
            $breapet="SELECT id FROM financeiro WHERE codigodaoperacao='$codigodaoperacao0'";
            $rRPT=mysqli_query($con, $breapet); $nRPT=mysqli_num_rows($rRPT);
            
            if(isset($_POST['btn-confirmardebito2'])):
                if($nRPT > 1):
                    for($UpRp=0; $UpRp < $nRPT; $UpRp++){  
                        if($tipodeoperacao === 'Transferência'): //Caso ocorra uma operação de transferência.
                            $inserir="UPDATE financeiro SET empresa='Emanuel', codigodoparceiro='0001', urldaempresa='igrejaemanuel', representante='$nomedousuario', codigodorepresentante='$matriculausuario', codigodaoperacao='$codigodaoperacaoo', descricao='$descricao', valorprevisto='$valorprevisto', recebidode='$recebidode', pagopor='$nomedousuario', anexo='$anexo', tamanhodoarquivo='$tamanhoanexo', tipodeoperacao='$tipodeoperacao', categoria='$categoria', centrodecusto='$centrodecusto', conta1='$contadeorigem', conta2='$contadedestino' WHERE codigodaoperacao='$codigodaoperacao0' AND ano='$anoprevisto' AND mes ='$mesprevisto'"; 
                        else:
                            if(!empty($confirmacaodepagamento)): //Está pago
                                if($UpRp < 1):
                                    $dataprevistaNEW=explode('-', $dataprevista);
                                    $novoanoprevisto=$dataprevistaNEW[0];
                                    $novomesprevisto=$dataprevistaNEW[1];
                                    $novodiaprevisto=$dataprevistaNEW[2];
                                    $inserir="UPDATE financeiro SET empresa='Emanuel', codigodoparceiro='0001', urldaempresa='igrejaemanuel', dia='$novodiaprevisto', mes='$novomesprevisto', ano='$novoanoprevisto', representante='$nomedousuario', codigodorepresentante='$matriculausuario', codigodaoperacao='$codigodaoperacaoo', descricao='$descricao', valorrealizado='$valorprevisto', datarealizada='$dataprevista', recebidode='$recebidode', pagopor='$nomedousuario', anexo='$anexo', tamanhodoarquivo='$tamanhoanexo', categoria='$categoria', centrodecusto='$centrodecusto', conta1='$conta' WHERE codigodaoperacao='$codigodaoperacao0' AND ano='$novoanoprevisto' AND mes ='$novomesprevisto'";
                                else:
                                    $dataprevista = date(('Y-m-d'), strtotime($dataprevista."+ 1 month"));
                                    $dataprevistaNEW=explode('-', $dataprevista);
                                    $novoanoprevisto=$dataprevistaNEW[0];
                                    $novomesprevisto=$dataprevistaNEW[1];
                                    $novodiaprevisto=$dataprevistaNEW[2];
                                    $inserir="UPDATE financeiro SET empresa='Emanuel', codigodoparceiro='0001', urldaempresa='igrejaemanuel', dia='$novodiaprevisto', mes='$novomesprevisto', ano='$novoanoprevisto', representante='$nomedousuario', codigodorepresentante='$matriculausuario', codigodaoperacao='$codigodaoperacaoo', descricao='$descricao', valorprevisto='$valorprevisto', dataprevista='$dataprevista', recebidode='$recebidode', pagopor='$nomedousuario', anexo='$anexo', tamanhodoarquivo='$tamanhoanexo', categoria='$categoria', centrodecusto='$centrodecusto', conta1='$conta' WHERE codigodaoperacao='$codigodaoperacao0' AND ano='$novoanoprevisto' AND mes ='$novomesprevisto'";
                                endif;
                            else:
                                $dataprevistaNEW=explode('-', $dataprevista);
                                $novoanoprevisto=$dataprevistaNEW[0];
                                $novomesprevisto=$dataprevistaNEW[1];
                                $novodiaprevisto=$dataprevistaNEW[2];
                                $inserir="UPDATE financeiro SET empresa='Emanuel', codigodoparceiro='0001', urldaempresa='igrejaemanuel', dia='$novodiaprevisto', mes='$novomesprevisto', ano='$novoanoprevisto', representante='$nomedousuario', codigodorepresentante='$matriculausuario', codigodaoperacao='$codigodaoperacaoo', descricao='$descricao', valorprevisto='$valorprevisto', valorrealizado='', datarealizada='', recebidode='$recebidode', pagopor='$nomedousuario', anexo='$anexo', tamanhodoarquivo='$tamanhoanexo', categoria='$categoria', centrodecusto='$centrodecusto', conta1='$conta' WHERE codigodaoperacao='$codigodaoperacao0' AND ano='$novoanoprevisto' AND mes>='$novomesprevisto'";
                            endif;
                        endif;
                        if(mysqli_query($con, $inserir)):
                            //Se inserir no BD move as imagens.
                            //move_uploaded_file($temporariominiatura, $pastaminiatura.$novoNomeminiatura);
                            $_SESSION['cordamensagem']="green";
                            $_SESSION['mensagem']="Débito <strong>atualizado</strong> com sucesso.";
                            // header("refresh:1, url=financeiro$linkSeguro&type=$tipodeoperacaoget&t=$tipodeoperacaoget&ms=$mesdeoperacaoget&y=$yta2");
                        else:
                            @unlink($anexo); //Exclui o arquivo que foi enviado anteriormente.
                            $_SESSION['cordamensagem']="red";
                            $_SESSION['mensagem']="<strong>Verifique sua conexão</strong>. Erro ao atualizar débito.";
                        endif;
                    }
                endif;
            elseif(isset($_POST['btn-confirmardebito'])):
                if($tipodeoperacao === 'Transferência'): //Caso ocorra uma operação de transferência.
                    $inserir="UPDATE financeiro SET empresa='Emanuel', codigodoparceiro='0001', urldaempresa='igrejaemanuel', representante='$nomedousuario', codigodorepresentante='$matriculausuario', codigodaoperacao='$codigodaoperacaoo', descricao='$descricao', valorprevisto='$valorprevisto', dataprevista='$dataprevista', recebidode='$recebidode', pagopor='$nomedousuario', anexo='$anexo', tamanhodoarquivo='$tamanhoanexo', tipodeoperacao='$tipodeoperacao', categoria='$categoria', centrodecusto='$centrodecusto', conta1='$contadeorigem', conta2='$contadedestino' WHERE codigodaoperacao='$codigodaoperacao0' AND id='$ideditado'"; 
                else:
                    if(!empty($confirmacaodepagamento)): //Está pago
                        $inserir="UPDATE financeiro SET empresa='Emanuel', codigodoparceiro='0001', urldaempresa='igrejaemanuel', representante='$nomedousuario', codigodorepresentante='$matriculausuario', codigodaoperacao='$codigodaoperacaoo', descricao='$descricao', valorrealizado='$valorprevisto', datarealizada='$dataprevista', recebidode='$recebidode', pagopor='$nomedousuario', anexo='$anexo', tamanhodoarquivo='$tamanhoanexo', categoria='$categoria', centrodecusto='$centrodecusto', conta1='$conta' WHERE codigodaoperacao='$codigodaoperacao0' AND id='$ideditado'";
                    else:
                        $inserir="UPDATE financeiro SET empresa='Emanuel', codigodoparceiro='0001', urldaempresa='igrejaemanuel', representante='$nomedousuario', codigodorepresentante='$matriculausuario', codigodaoperacao='$codigodaoperacaoo', descricao='$descricao', valorprevisto='$valorprevisto', dataprevista='$dataprevista', valorrealizado='', datarealizada='', recebidode='$recebidode', pagopor='$nomedousuario', anexo='$anexo', tamanhodoarquivo='$tamanhoanexo', categoria='$categoria', centrodecusto='$centrodecusto', conta1='$conta' WHERE codigodaoperacao='$codigodaoperacao0' AND id='$ideditado'";
                    endif;
                endif;
                if(mysqli_query($con, $inserir)):
                    //Se inserir no BD move as imagens.
                    //move_uploaded_file($temporariominiatura, $pastaminiatura.$novoNomeminiatura);
                    $_SESSION['cordamensagem']="green";
                    $_SESSION['mensagem']="Débito <strong>atualizado</strong> com sucesso.";
                    // header("refresh:1, url=financeiro$linkSeguro&type=$tipodeoperacaoget&t=$tipodeoperacaoget&ms=$mesdeoperacaoget&y=$yta2");
                else:
                    @unlink($anexo); //Exclui o arquivo que foi enviado anteriormente.
                    $_SESSION['cordamensagem']="red";
                    $_SESSION['mensagem']="<strong>Verifique sua conexão</strong>. Erro ao atualizar débito.";
                endif;
            endif;
            
            
        // // else:
        // //     $_SESSION['cordamensagem']="youtube";	
        // //     $_SESSION['mensagem']="Entre em contato com a <strong>EuPedi</strong>. Loja impossibilitada de atualizar débito.";
        // // endif;
    endif;


    //Para receitas:
    if(isset($_POST['btn-cadastrar'])):
        //if($statusdaempresa === 'APROVADO'):

        $repetirmes                         = mysqli_escape_string($con, $_POST['repetirmes']);
        $repetirano                         = mysqli_escape_string($con, $_POST['repetirano']);

        $dataprevista                       = mysqli_escape_string($con, $_POST['realizadoem']);
        //Pegar dia, mês e ano previsto
        $dataprevistaSEP = explode('-', $dataprevista); // 2020 - 03 - 30
        $anoprevisto     = $dataprevistaSEP[0]; // 2020
        $mesprevisto     = $dataprevistaSEP[1]; // 
        $diaprevisto     = $dataprevistaSEP[2]; // 

        if($_FILES['anexo']['name'] !== ''):
            //CAPA
            $extensao = pathinfo($_FILES['anexo']['name'], PATHINFO_EXTENSION);
            if(in_array($extensao, $formatospermitidos)):
                @mkdir("arq/", 0777); //Cria a pasta se não houver. o @ é pra ocultar erro caso
                @mkdir("arq/financeiro/", 0777); //Cria a pasta se não houver. o @ é pra ocultar erro caso
                @mkdir("arq/financeiro/arquivos/", 0777); //Cria a pasta se não houver. o @ é pra ocultar erro caso
                @mkdir("arq/financeiro/arquivos/receita/", 0777); //Cria a pasta se não houver. o @ é pra ocultar erro caso
                @mkdir("arq/financeiro/arquivos/receita/$ano/", 0777); //Cria a pasta se não houver. o @ é pra ocultar erro caso
                $pastaanexo			= "arq/financeiro/arquivos/receita/$ano/";
                $temporarioanexo	= $_FILES['anexo']['tmp_name'];
                $tamanhoanexo	    = $_FILES['anexo']['size'];
                $novoNomeanexo		= date('H').mt_rand(6000,9999).'.'.$extensao;
                    $anexo			= $pastaanexo.$novoNomeanexo;
                move_uploaded_file($temporarioanexo, $anexo);
            endif;
        endif;
        
        if(!empty($repetirmes) AND !empty($repetirano)):
            //Algorítimo para definir a quantidade de meses que vai ser acrescendado para a operação.
            $datainicialprevista  = $anoprevisto.'-'.$mesprevisto.'-'.$diaprevisto;
            $pedidopararepetirate = $repetirano.'-'.$repetirmes.'-'.$diaprevisto; $pedidopararepetirate=date(('Y-m-d'), strtotime($pedidopararepetirate.'+1 months'));

            $diff = abs(strtotime($pedidopararepetirate) - strtotime($datainicialprevista));

            $years = floor($diff / (365*60*60*24));
            $months = floor(($diff - $years * 365*60*60*24) / (30*60*60*24));
            // $days = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24)/ (60*60*24));

            if($years > 0):
                $anosemeses = $years * 12;
            else:
                $anosemeses = 0;
            endif;

            $anosemeses= $anosemeses + $months; //Com base nos anos e os meses definidos
                // printf("%d years, %d months, %d days\n", $years, $months, $days);
            //Algorítimo para definir a quantidade de meses que vai ser acrescendado para a operação.
        else:
            $anosemeses = 1;
        endif;
        

        // $oss = $pedidopararepetirate;
            $anoZERO=-1;
        for($anosrepetindo=0; $anosrepetindo < $anosemeses; $anosrepetindo++){
            $anoZERO++;

            $confirmacaodepagamento             = mysqli_escape_string($con, $_POST['confirmacaodepagamento']);
            $codigodaoperacao                   = mysqli_escape_string($con, $_POST['codigodaoperacao']);
            $descricao                          = mysqli_escape_string($con, $_POST['descricao']);
            $dataprevista                       = mysqli_escape_string($con, $_POST['realizadoem']);
                //Pegar dia, mês e ano previsto
                $dataprevistaSEP = explode('-', $dataprevista); // 2020 - 03 - 30
                $anoprevisto     = $dataprevistaSEP[0]; // 2020
                $mesprevisto     = $dataprevistaSEP[1]; // 
                $diaprevisto     = $dataprevistaSEP[2]; // 

            if($anoZERO < 1):
                $dataprevista = $dataprevista;
                $anoprevisto = $anoprevisto;
                $mesprevisto = $mesprevisto;
                $diaprevisto = $diaprevisto;
                $_SESSION['ultimadataprevista']=$dataprevista; //Fixa a data prevista na SESSION para gerar as próximas.
            else:
                $ultimadataprevista = $_SESSION['ultimadataprevista'];
                $dataprevista = date(('Y-m-d'), strtotime($ultimadataprevista."+ $anoZERO months"));
                    //Em cima pega a última data prevista e adiciona um mês conforme o número de meses resultado entre o ínicio e a data de término.
                
                $dataprevistaREP = explode('-', $dataprevista); // 2020 - 03 - 30
                $anoprevisto     = $dataprevistaREP[0]; // 2020
                $mesprevisto     = $dataprevistaREP[1]; // 
                $diaprevisto     = $dataprevistaREP[2]; // 
            endif;


            @$recebidode                         = mysqli_escape_string($con, $_POST['recebidode']);
            @$recebidode2                        = mysqli_escape_string($con, $_POST['recebidode2']);
                //Verificar se foi inserido novo ou se é de um Consumidor.
                if(!empty($recebidode2)): //Se o usuário inseriu um nome no campo Recebido2
                    $recebidode = $recebidode2;
                endif;            

            $categoria                          = mysqli_escape_string($con, $_POST['categoria']); $categoria=ucfirst($categoria);
            $centrodecusto                      = mysqli_escape_string($con, $_POST['centrodecusto']);
            $conta                              = mysqli_escape_string($con, $_POST['conta']);
            $tipodeoperacao                     = mysqli_escape_string($con, $_POST['tipodeoperacao']);
            $contadeorigem                      = mysqli_escape_string($con, $_POST['contadeorigem']);
            $contadedestino                     = mysqli_escape_string($con, $_POST['contadedestino']);

            if(!empty($contadeorigem) AND !empty($contadedestino)):
                $tipodeoperacao = 'Transferência';
            endif;

            if(!empty($codigodaoperacao)):
                $codigodaoperacaoo = $codigodaoperacao;
            elseif(isset($_SESSION['codigodaoperacao'])):
                $codigodaoperacao = $_SESSION['codigodaoperacao'];
            else:
                //Buscar id anterior para evitar código duplicado.
                $biddupli="SELECT id FROM financeiro";
                @$riddupli=mysqli_query($con, $biddupli);
                    @$diddupli=mysqli_num_rows($riddupli);
                    
                $ultimoid = $diddupli;

                $codigodaoperacaoo = $ultimoid.mt_rand(1000, 19999);
                $codigodaoperacao = $codigodaoperacaoo;
            endif;

            if(!isset($_SESSION['codigodaoperacao'])): //Se não existir vai criar a SESSION.
                $_SESSION['codigodaoperacao'] = $codigodaoperacao; //Fixa o mesmo código para as operações do próximo mês.
            endif;
            
            $valorprevisto                      = mysqli_escape_string($con, $_POST['valorprevisto']);
                //Adequa o valor para BD
                if($valorprevisto >= 1.000): //Se o valor inserido for maior que 1000 utiliza o algoritimo abaixo.
                    @$valorprevisto = explode('.', $valorprevisto); //Separa a milhar 1 099,60
                    @$valorprevisto  = $valorprevisto[0].$valorprevisto[1]; //Une o valor mantendo a virgula: 1099,60
                    @$valorprevisto  = str_replace(',','.',$valorprevisto); //Troca a virgula por ponto para as operações matemáticas do php
                elseif($valorprevisto < 1.000): //Se for inferior utiliza o algoritimo.
                    @$valorprevisto2 = explode(',', $valorprevisto); //Separa as moedas 099 60
                    @$valorprevisto  = $valorprevisto2[0].'.'.$valorprevisto2[1]; //Une as moedas com PONTO.
                endif;
            
            //Criptografia 1
            $recebidode = base64_encode($recebidode);
            $descricao  = base64_encode($descricao);

            //Criptografia 2
            $recebidode = base64_encode($recebidode);
            $descricao  = base64_encode($descricao);

            // if($anoZERO > 0): //Nas repetições futuras não será sinalizadas como pagas.
            //     $valorprevistofuturo=''; 
            // else:
                $valorprevistofuturo=$valorprevisto;
            // endif;

            //Adicionar categoria
            $bCat="SELECT id, count(id) as ncategorias FROM categoriasfinanceiro WHERE categoria='$categoria'";
                $rCat=mysqli_query($con, $bCat);
                $dCat=mysqli_fetch_array($rCat);

            if(empty($dCat['id'])):
                $codCat=$dCat['ncategorias'].mt_rand(000,999);
                $Newtag="INSERT INTO categoriasfinanceiro (empresa, codigodoparceiro, urldaempresa, dia, mes, ano, representante, codigodorepresentante, codigodacategoria, categoria, tipodecategoria) VALUES ('Emanuel', '0001', 'igrejaemanuel', '$dia', '$mesnumero', '$ano', '$nomedousuario', '$matricula', '$codCat', '$categoria', '$tipodeoperacaoget')";
                mysqli_query($con, $Newtag);
            endif;

            
            $bED = "SELECT id FROM financeiro WHERE descricao='$descricao' AND recebidode='$recebidode' AND valorprevisto='$valorprevisto' AND dataprevista='$dataprevista' AND tipoderegistro='CRÉDITO' AND conta1='$conta'";
                $rBPD = mysqli_query($con, $bED);
                    $nBPD = mysqli_num_rows($rBPD);
                    
            if($nBPD < 1):
                if($anoZERO > 0):
                    if($tipodeoperacao === 'Transferência'): //Caso ocorra uma operação de transferência.
                        $inserir="INSERT INTO financeiro (empresa, codigodoparceiro, urldaempresa, dia, mes, ano, representante, codigodorepresentante, codigodaoperacao, descricao, valorprevisto, dataprevista, valorrealizado, datarealizada, recebidode, pagopor, anexo, tamanhodoarquivo, tipodeoperacao, categoria, centrodecusto, conta1, conta2, tipoderegistro) VALUES ('Emanuel', '0001', 'igrejaemanuel', '$diaprevisto', '$mesprevisto', '$anoprevisto', '$nomedousuario', '$matriculausuario', '$codigodaoperacaoo', '$descricao', '$valorprevisto', '$dataprevista', '$valorprevisto', '$dataprevista', '$recebidode', '', '$anexo', '$tamanhoanexo', '$tipodeoperacao', '$categoria', '$centrodecusto', '$contadeorigem', '$contadedestino', 'DÉBITO')"; 
                        $TirarDaContaAnterior="INSERT INTO financeiro (empresa, codigodoparceiro, urldaempresa, dia, mes, ano, representante, codigodorepresentante, codigodaoperacao, descricao, valorprevisto, dataprevista, valorrealizado, datarealizada, recebidode, pagopor, anexo, tamanhodoarquivo, tipodeoperacao, categoria, centrodecusto, conta1, conta2, tipoderegistro) VALUES ('Emanuel', '0001', 'igrejaemanuel', '$diaprevisto', '$mesprevisto', '$anoprevisto', '$nomedousuario', '$matriculausuario', '$codigodaoperacaoo', '$descricao', '$valorprevisto', '$dataprevista', '$valorprevisto', '$dataprevista', '$recebidode', '', '$anexo', '$tamanhoanexo', '$tipodeoperacao', '$categoria', '$centrodecusto', '$contadedestino', '$contadeorigem', 'CRÉDITO')"; 
                        mysqli_query($con, $TirarDaContaAnterior);
                    elseif(!empty($confirmacaodepagamento)):
                        $inserir="INSERT INTO financeiro (empresa, codigodoparceiro, urldaempresa, dia, mes, ano, representante, codigodorepresentante, codigodaoperacao, descricao, valorprevisto, dataprevista, valorrealizado, datarealizada, recebidode, pagopor, anexo, tamanhodoarquivo, tipodeoperacao, categoria, centrodecusto, conta1, conta2, tipoderegistro) VALUES ('Emanuel', '0001', 'igrejaemanuel', '$diaprevisto', '$mesprevisto', '$anoprevisto', '$nomedousuario', '$matriculausuario', '$codigodaoperacaoo', '$descricao', '$valorprevistofuturo', '$dataprevista', '', '', '$recebidode', '', '$anexo', '$tamanhoanexo', '$tipodeoperacao', '$categoria', '$centrodecusto', '$conta', '', 'CRÉDITO')"; 
                    else:
                        $inserir="INSERT INTO financeiro (empresa, codigodoparceiro, urldaempresa, dia, mes, ano, representante, codigodorepresentante, codigodaoperacao, descricao, valorprevisto, dataprevista, valorrealizado, datarealizada, recebidode, pagopor, anexo, tamanhodoarquivo, tipodeoperacao, categoria, centrodecusto, conta1, conta2, tipoderegistro) VALUES ('Emanuel', '0001', 'igrejaemanuel', '$diaprevisto', '$mesprevisto', '$anoprevisto', '$nomedousuario', '$matriculausuario', '$codigodaoperacaoo', '$descricao', '$valorprevisto', '$dataprevista', '', '', '$recebidode', '', '$anexo', '$tamanhoanexo', '$tipodeoperacao', '$categoria', '$centrodecusto', '$conta', '', 'CRÉDITO')"; 
                    endif;

                else:
                    if($tipodeoperacao === 'Transferência'): //Caso ocorra uma operação de transferência.
                        $inserir="INSERT INTO financeiro (empresa, codigodoparceiro, urldaempresa, dia, mes, ano, representante, codigodorepresentante, codigodaoperacao, descricao, valorprevisto, dataprevista, valorrealizado, datarealizada, recebidode, pagopor, anexo, tamanhodoarquivo, tipodeoperacao, categoria, centrodecusto, conta1, conta2, tipoderegistro) VALUES ('Emanuel', '0001', 'igrejaemanuel', '$diaprevisto', '$mesprevisto', '$anoprevisto', '$nomedousuario', '$matriculausuario', '$codigodaoperacaoo', '$descricao', '$valorprevisto', '$dataprevista', '$valorprevisto', '$dataprevista', '$recebidode', '', '$anexo', '$tamanhoanexo', '$tipodeoperacao', '$categoria', '$centrodecusto', '$contadeorigem', '$contadedestino', 'DÉBITO')"; 
                        $TirarDaContaAnterior="INSERT INTO financeiro (empresa, codigodoparceiro, urldaempresa, dia, mes, ano, representante, codigodorepresentante, codigodaoperacao, descricao, valorprevisto, dataprevista, valorrealizado, datarealizada, recebidode, pagopor, anexo, tamanhodoarquivo, tipodeoperacao, categoria, centrodecusto, conta1, conta2, tipoderegistro) VALUES ('Emanuel', '0001', 'igrejaemanuel', '$diaprevisto', '$mesprevisto', '$anoprevisto', '$nomedousuario', '$matriculausuario', '$codigodaoperacaoo', '$descricao', '$valorprevisto', '$dataprevista', '$valorprevisto', '$dataprevista', '$recebidode', '', '$anexo', '$tamanhoanexo', '$tipodeoperacao', '$categoria', '$centrodecusto', '$contadedestino', '$contadeorigem', 'CRÉDITO')"; 
                        mysqli_query($con, $TirarDaContaAnterior);
                    elseif(!empty($confirmacaodepagamento)):
                        $inserir="INSERT INTO financeiro (empresa, codigodoparceiro, urldaempresa, dia, mes, ano, representante, codigodorepresentante, codigodaoperacao, descricao, valorprevisto, dataprevista, valorrealizado, datarealizada, recebidode, pagopor, anexo, tamanhodoarquivo, tipodeoperacao, categoria, centrodecusto, conta1, conta2, tipoderegistro) VALUES ('Emanuel', '0001', 'igrejaemanuel', '$diaprevisto', '$mesprevisto', '$anoprevisto', '$nomedousuario', '$matriculausuario', '$codigodaoperacaoo', '$descricao', '', '', '$valorprevistofuturo', '$dataprevista', '$recebidode', '', '$anexo', '$tamanhoanexo', '$tipodeoperacao', '$categoria', '$centrodecusto', '$conta', '', 'CRÉDITO')"; 
                    else:
                        $inserir="INSERT INTO financeiro (empresa, codigodoparceiro, urldaempresa, dia, mes, ano, representante, codigodorepresentante, codigodaoperacao, descricao, valorprevisto, dataprevista, valorrealizado, datarealizada, recebidode, pagopor, anexo, tamanhodoarquivo, tipodeoperacao, categoria, centrodecusto, conta1, conta2, tipoderegistro) VALUES ('Emanuel', '0001', 'igrejaemanuel', '$diaprevisto', '$mesprevisto', '$anoprevisto', '$nomedousuario', '$matriculausuario', '$codigodaoperacaoo', '$descricao', '$valorprevisto', '$dataprevista', '', '', '$recebidode', '', '$anexo', '$tamanhoanexo', '$tipodeoperacao', '$categoria', '$centrodecusto', '$conta', '', 'CRÉDITO')"; 
                    endif;
                endif;

                
                if(mysqli_query($con, $inserir)):
                    //Se inserir no BD move as imagens.
                    //move_uploaded_file($temporariominiatura, $pastaminiatura.$novoNomeminiatura);

                    $_SESSION['cordamensagem']="green";
                    $_SESSION['mensagem']="Receita <strong>cadastrada</strong> com sucesso.";
                    // header("refresh:1, url=financeiro$linkSeguro&type=$tipodeoperacaoget&t=$tipodeoperacaoget&ms=$mesdeoperacaoget&y=$yta2");
                else:
                    @unlink($anexo); //Exclui o arquivo que foi enviado anteriormente.
                    $_SESSION['cordamensagem']="red";
                    $_SESSION['mensagem']="<strong>Verifique sua conexão</strong>. Erro ao cadastrar receita.";
                endif;
                
            else:
                $_SESSION['cordamensagem']="red";
                $_SESSION['mensagem']="Esta movimentação foi registrada anteriormente.";
            endif;
            
        }

            unset($_SESSION['ultimadataprevista']); //Após todas as repetições encerra as sessions criadas para outras operações acontecerem.
            unset($_SESSION['codigodaoperacao']); //Após todas as repetições encerra as sessions criadas para outras operações acontecerem.
        // else:
        //     $_SESSION['cordamensagem']="youtube";	
        //     $_SESSION['mensagem']="Entre em contato com a <strong>EuPedi</strong>. Loja impossibilitada de cadastrar débito.";
        // endif;
    endif;

    if(isset($_POST['btn-confirmarreceita']) OR isset($_POST['btn-confirmarreceita2'])):
        //if($statusdaempresa === 'APROVADO'):	
            $confirmacaodepagamento = mysqli_escape_string($con, $_POST['confirmacaodepagamento']); //Se vazio é para retirar confirmação de pagamento.

            $tipodeoperacaoget = mysqli_escape_string($con, $_POST['tipodeoperacaoget']);
            $mesdeoperacaoget = mysqli_escape_string($con, $_POST['mesdeoperacaoget']);

            $codigodaoperacao0 = mysqli_escape_string($con, $_POST['codigodaoperacao0']);
            $anexoanterior     = mysqli_escape_string($con, $_POST['anexoanterior']);

            if($_FILES['anexo']['name'] !== ''):

                //Apaga anexo antigo.
                $bAn="SELECT anexo FROM financeiro WHERE codigodaoperacao='$codigodaoperacao0'";
                  $rAn=mysqli_query($con, $bAn);
                    $dAn=mysqli_fetch_array($rAn);

                    $anexoanterior = $dAn['anexo'];

                @unlink($anexoanterior);

                //Move o novo anexo.
                $extensao = pathinfo($_FILES['anexo']['name'], PATHINFO_EXTENSION);
                if(in_array($extensao, $formatospermitidos)):
                    @mkdir("arq/", 0777); //Cria a pasta se não houver. o @ é pra ocultar erro caso
                    @mkdir("arq/financeiro/", 0777); //Cria a pasta se não houver. o @ é pra ocultar erro caso
                    @mkdir("arq/financeiro/arquivos/", 0777); //Cria a pasta se não houver. o @ é pra ocultar erro caso
                    @mkdir("arq/financeiro/arquivos/receita/", 0777); //Cria a pasta se não houver. o @ é pra ocultar erro caso
                    @mkdir("arq/financeiro/arquivos/receita/$ano/", 0777); //Cria a pasta se não houver. o @ é pra ocultar erro caso
                    $pastaanexo			= "arq/financeiro/arquivos/receita/$ano/";
                    $temporarioanexo	= $_FILES['anexo']['tmp_name'];
                    $tamanhoanexo	    = $_FILES['anexo']['size'];
                    $novoNomeanexo		= date('H').mt_rand(6000,9999).'.'.$extensao;
                        $anexo			= $pastaanexo.$novoNomeanexo;
                    move_uploaded_file($temporarioanexo, $anexo);
                endif;
            else:
                $anexo = $anexoanterior;
            endif;
            
            $codigodaoperacao                   = mysqli_escape_string($con, $_POST['codigodaoperacao']);
            $descricao                          = mysqli_escape_string($con, $_POST['descricao']);
            $dataprevista                       = mysqli_escape_string($con, $_POST['realizadoem']);
            //Pegar dia, mês e ano previsto
            $dataprevistaSEP = explode('-', $dataprevista); // 2020 - 03 - 30
            $anoprevisto     = $dataprevistaSEP[0]; // 2020
            $mesprevisto     = $dataprevistaSEP[1]; // 
            $diaprevisto     = $dataprevistaSEP[2]; // 

            @$recebidode                         = mysqli_escape_string($con, $_POST['recebidode']);
            @$recebidode2                        = mysqli_escape_string($con, $_POST['recebidode2']);
            //Verificar se foi inserido novo ou se é de um Consumidor.
            if(!empty($recebidode2)): //Se o usuário inseriu um nome no campo Recebido2
                $recebidode = $recebidode2;
            endif;
            $categoria                          = mysqli_escape_string($con, $_POST['categoria']);
            $centrodecusto                      = mysqli_escape_string($con, $_POST['centrodecusto']);
            $conta                              = mysqli_escape_string($con, $_POST['conta']);
            $tipodeoperacao                     = mysqli_escape_string($con, $_POST['tipodeoperacao']);
            $contadeorigem                      = mysqli_escape_string($con, $_POST['contadeorigem']);
            $contadedestino                     = mysqli_escape_string($con, $_POST['contadedestino']);

            if($codigodaoperacao != ''):
                $codigodaoperacaoo = $codigodaoperacao;
            else:
                $codigodaoperacaoo = $codigodaoperacao0;
            endif;

            @$valorprevisto                      = mysqli_escape_string($con, $_POST['valorprevisto']);
            //Adequa o valor para BD
            if($valorprevisto >= 1.000): //Se o valor inserido for maior que 1000 utiliza o algoritimo abaixo.
                @$valorprevisto = explode('.', $valorprevisto); //Separa a milhar 1 099,60
                @$valorprevisto  = $valorprevisto[0].$valorprevisto[1]; //Une o valor mantendo a virgula: 1099,60
                @$valorprevisto  = str_replace(',','.',$valorprevisto); //Troca a virgula por ponto para as operações matemáticas do php
            elseif($valorprevisto < 1.000): //Se for inferior utiliza o algoritimo.
                @$valorprevisto2 = explode(',', $valorprevisto); //Separa as moedas 099 60
                @$valorprevisto  = $valorprevisto2[0].'.'.$valorprevisto2[1]; //Une as moedas com PONTO.
            endif;
                
            
            //Criptografia 1
            $recebidode = base64_encode($recebidode);
            $descricao  = base64_encode($descricao);

            //Criptografia 2
            $recebidode = base64_encode($recebidode);
            $descricao  = base64_encode($descricao);

            $breapet="SELECT id FROM financeiro WHERE codigodaoperacao='$codigodaoperacao0'";
            $rRPT=mysqli_query($con, $breapet); $nRPT=mysqli_num_rows($rRPT);

            if(isset($_POST['btn-confirmarreceita'])):
                if($tipodeoperacao === 'Transferência'): //Caso ocorra uma operação de transferência.
                    $inserir="UPDATE financeiro SET empresa='Emanuel', codigodoparceiro='0001', urldaempresa='igrejaemanuel', representante='$nomedousuario', codigodorepresentante='$matriculausuario', codigodaoperacao='$codigodaoperacaoo', descricao='$descricao', valorprevisto='$valorprevisto', dataprevista='$dataprevista', recebidode='$recebidode', pagopor='$nomedousuario', anexo='$anexo', tamanhodoarquivo='$tamanhoanexo', tipodeoperacao='$tipodeoperacao', categoria='$categoria', centrodecusto='$centrodecusto', conta1='$contadeorigem', conta2='$contadedestino' WHERE codigodaoperacao='$codigodaoperacao0' AND id='$ideditado'"; 
                else:
                    if(!empty($confirmacaodepagamento)): //Se vazio retira a confirmação do valor.
                        $inserir="UPDATE financeiro SET empresa='Emanuel', codigodoparceiro='0001', urldaempresa='igrejaemanuel', representante='$nomedousuario', codigodorepresentante='$matriculausuario', codigodaoperacao='$codigodaoperacaoo', descricao='$descricao', valorrealizado='$valorprevisto', datarealizada='$dataprevista', recebidode='$recebidode', pagopor='$nomedousuario', anexo='$anexo', tamanhodoarquivo='$tamanhoanexo', categoria='$categoria', centrodecusto='$centrodecusto', conta1='$conta' WHERE codigodaoperacao='$codigodaoperacao0' AND id='$ideditado'";
                    elseif(empty($confirmacaodepagamento)):
                        $inserir="UPDATE financeiro SET empresa='Emanuel', codigodoparceiro='0001', urldaempresa='igrejaemanuel', representante='$nomedousuario', codigodorepresentante='$matriculausuario', codigodaoperacao='$codigodaoperacaoo', descricao='$descricao', valorprevisto='$valorprevisto', dataprevista='$dataprevista', datarealizada='', valorrealizado='', recebidode='$recebidode', pagopor='$nomedousuario', anexo='$anexo', tamanhodoarquivo='$tamanhoanexo', categoria='$categoria', centrodecusto='$centrodecusto', conta1='$conta' WHERE codigodaoperacao='$codigodaoperacao0' AND id='$ideditado'";
                    endif;
                endif;
                        
                if(mysqli_query($con, $inserir)):
                    //Se inserir no BD move as imagens.
                    //move_uploaded_file($temporariominiatura, $pastaminiatura.$novoNomeminiatura);

                    $_SESSION['cordamensagem']="green";
                    $_SESSION['mensagem']="Receita <strong>atualizada</strong> com sucesso.";
                    // header("refresh:1, url=financeiro$linkSeguro&type=$tipodeoperacaoget&t=$tipodeoperacaoget&ms=$mesdeoperacaoget&y=$yta2");
                else:
                    @unlink($anexo); //Exclui o arquivo que foi enviado anteriormente.
                    $_SESSION['cordamensagem']="red";
                    $_SESSION['mensagem']="<strong>Verifique sua conexão</strong>. Erro ao atualizar receita.";
                endif;
            elseif(isset($_POST['btn-confirmarreceita2'])):
                if($nRPT > 1):
                    for($UpRp=0; $UpRp < $nRPT; $UpRp++){
                        if($tipodeoperacao === 'Transferência'): //Caso ocorra uma operação de transferência.
                            $inserir="UPDATE financeiro SET empresa='Emanuel', codigodoparceiro='0001', urldaempresa='igrejaemanuel', representante='$nomedousuario', codigodorepresentante='$matriculausuario', codigodaoperacao='$codigodaoperacaoo', descricao='$descricao', valorprevisto='$valorprevisto', recebidode='$recebidode', pagopor='$nomedousuario', anexo='$anexo', tamanhodoarquivo='$tamanhoanexo', tipodeoperacao='$tipodeoperacao', categoria='$categoria', centrodecusto='$centrodecusto', conta1='$contadeorigem', conta2='$contadedestino' WHERE codigodaoperacao='$codigodaoperacao0' AND mes = '$mesprevisto'"; 
                        else:
                            if(!empty($confirmacaodepagamento)): //Se vazio retira a confirmação do valor.
                                if($UpRp < 1):
                                    $dataprevistaNEW=explode('-', $dataprevista);
                                    $novoanoprevisto=$dataprevistaNEW[0];
                                    $novomesprevisto=$dataprevistaNEW[1];
                                    $novodiaprevisto=$dataprevistaNEW[2];
                                    $inserir="UPDATE financeiro SET empresa='Emanuel', codigodoparceiro='0001', urldaempresa='igrejaemanuel', dia='$novodiaprevisto', mes='$novomesprevisto', ano='$novoanoprevisto', representante='$nomedousuario', codigodorepresentante='$matriculausuario', codigodaoperacao='$codigodaoperacaoo', descricao='$descricao', valorrealizado='$valorprevisto', datarealizada='$dataprevista', recebidode='$recebidode', pagopor='$nomedousuario', anexo='$anexo', tamanhodoarquivo='$tamanhoanexo', categoria='$categoria', centrodecusto='$centrodecusto', conta1='$conta' WHERE codigodaoperacao='$codigodaoperacao0' AND ano='$novoanoprevisto' AND mes = '$novomesprevisto'";
                                else:
                                    $dataprevista = date(('Y-m-d'), strtotime($dataprevista."+ 1 month"));
                                    $dataprevistaNEW=explode('-', $dataprevista);
                                    $novoanoprevisto=$dataprevistaNEW[0];
                                    $novomesprevisto=$dataprevistaNEW[1];
                                    $novodiaprevisto=$dataprevistaNEW[2];
                                    $inserir="UPDATE financeiro SET empresa='Emanuel', codigodoparceiro='0001', urldaempresa='igrejaemanuel', dia='$novodiaprevisto', mes='$novomesprevisto', ano='$novoanoprevisto', representante='$nomedousuario', codigodorepresentante='$matriculausuario', codigodaoperacao='$codigodaoperacaoo', descricao='$descricao', valorprevisto='$valorprevisto', dataprevista='$dataprevista', recebidode='$recebidode', pagopor='$nomedousuario', anexo='$anexo', tamanhodoarquivo='$tamanhoanexo', categoria='$categoria', centrodecusto='$centrodecusto', conta1='$conta' WHERE codigodaoperacao='$codigodaoperacao0' AND ano='$novoanoprevisto' AND mes = '$novomesprevisto'";
                                endif;
                            else:
                                $dataprevistaNEW=explode('-', $dataprevista);
                                $novoanoprevisto=$dataprevistaNEW[0];
                                $novomesprevisto=$dataprevistaNEW[1];
                                $novodiaprevisto=$dataprevistaNEW[2];
                                $inserir="UPDATE financeiro SET empresa='Emanuel', codigodoparceiro='0001', urldaempresa='igrejaemanuel', dia='$novodiaprevisto', mes='$novomesprevisto', ano='$novoanoprevisto', representante='$nomedousuario', codigodorepresentante='$matriculausuario', codigodaoperacao='$codigodaoperacaoo', descricao='$descricao', dataprevista='$dataprevista', valorprevisto='$valorprevisto', datarealizada='', valorrealizado='', recebidode='$recebidode', pagopor='$nomedousuario', anexo='$anexo', tamanhodoarquivo='$tamanhoanexo', categoria='$categoria', centrodecusto='$centrodecusto', conta1='$conta' WHERE codigodaoperacao='$codigodaoperacao0' AND ano='$novoanoprevisto' AND mes = '$novomesprevisto'";
                            endif;
                        endif;
                        // var_dump($inserir);
                        if(mysqli_query($con, $inserir)):
                            //Se inserir no BD move as imagens.
                            //move_uploaded_file($temporariominiatura, $pastaminiatura.$novoNomeminiatura);

                            $_SESSION['cordamensagem']="green";
                            $_SESSION['mensagem']="Receita <strong>atualizada</strong> com sucesso.";
                            // header("refresh:1, url=financeiro$linkSeguro&type=$tipodeoperacaoget&t=$tipodeoperacaoget&ms=$mesdeoperacaoget&y=$yta2");
                        else:
                            @unlink($anexo); //Exclui o arquivo que foi enviado anteriormente.
                            $_SESSION['cordamensagem']="red";
                            $_SESSION['mensagem']="<strong>Verifique sua conexão</strong>. Erro ao atualizar receita.";
                        endif;
                    }
                endif;
            endif;
            
        // else:
        //     $_SESSION['cordamensagem']="youtube";	
        //     $_SESSION['mensagem']="Entre em contato com a <strong>EuPedi</strong>. Loja impossibilitada de atualizar receita.";
        // endif;
    endif;

    //Solicitação de saque
    if(isset($_POST['btn-solicitarsaque'])):
        //Valor a transferir
        $ValorSolicitadoParaSaque       = mysqli_escape_string($con, $_POST['valoratransferir']);

        //Limpar solicitações que não foram confirmadas -- Sistema se auxiliando.
        $bSolicitacoesBD = "SELECT id, codigodasolicitacao, dia, mes, ano FROM solicitacaodesaque WHERE statusdasolicitacao='PENDENTE'";
          $rSBD = mysqli_query($con, $bSolicitacoesBD);
            while($dSBD = mysqli_fetch_array($rSBD)):

                $CodOpePendente = $dSBD['codigodasolicitacao']; //Para retirar os lançamentos realizados na tabela financeiro
                $IdDaSolicitacaoPendente = $dSBD['id'];

                $diaDaSolicitacao = $dSBD['dia'];
                $mesDaSolicitacao = $dSBD['mes'];
                $anoDaSolicitacao = $dSBD['ano'];

                $dataDaSolicitacao = $anoDaSolicitacao.'-'.$mesDaSolicitacao.'-'.$diaDaSolicitacao;

                $DoisDiasDepois  = date(('Y-m-d'), strtotime($dataDaSolicitacao.'+ 2 days')); //Ver data com dois dias depois.

                if(strtotime($dataeng) > strtotime($DoisDiasDepois)):
                    //Se o dia desta ação for maior que os dois dias após a solicitação,  ela será retirada e o lançamento realizado no financeiro será deletado.

                    //Retirar da tabela financeiro
                    $bLancamentoFinanceiroDoSaque = "SELECT id FROM financeiro WHERE codigodaoperacao='$CodOpePendente' AND tipodeoperacao='SAQUE'";
                      $rLancamentoFinanceiroDoSaque = mysqli_query($con, $bLancamentoFinanceiroDoSaque);
                        $dLFS = mysqli_fetch_array($rLancamentoFinanceiroDoSaque);

                        $IdParaDeletar = $dLFS['id'];

                    $DelFinanceiro = "DELETE FROM financeiro WHERE id='$IdParaDeletar' AND tipodeoperacao='SAQUE'";
                    mysqli_query($con, $DelFinanceiro);

                    //Retirar da tabela solicitação de saque.
                    $DelPedidoDeSaque = "DELETE FROM solicitacaodesaque WHERE id='$IdDaSolicitacaoPendente' AND statusdasolicitacao='PENDENTE'";
                    mysqli_query($con, $DelPedidoDeSaque);
                endif;
            endwhile;
        //Limpar solicitações que não foram confirmadas -- Sistema se auxiliando.


        //Converter valor para formato US$
        if($ValorSolicitadoParaSaque > 1000):
            $ValorSolicitadoParaSaque   = str_replace('.', '', $ValorSolicitadoParaSaque); // 1000,35
            $ValorSolicitadoParaSaque   = str_replace(',','.', $ValorSolicitadoParaSaque); //1000.35
        else:
            $ValorSolicitadoParaSaque   = str_replace(',', '.', $ValorSolicitadoParaSaque); //938.35
        endif;

        //Se o valor solicitado for menor que R$ 20,00 ele receberá esta mensagem.
        if($ValorSolicitadoParaSaque < 20):
            $_SESSION['cordamensagem'] = 'youtube';
            $_SESSION['mensagem'] = "$ValorSolicitadoParaSaque";
            //$_SESSION['mensagem'] = 'O valor mínimo para solicitação de saque (transferência) é de R$ 20,00';
            //header("location:financeiro?m=$matriculausuario&token=$token");
        else:
            //Informações ocultas no formulário -- Hidden
            $saldonodia                         = mysqli_escape_string($con, $_POST['saldodisponivelnodia']);

            //Conta já cadastrada anteriormente
            if(!empty($_POST['bancoparatransferencia'])): // Para não dar erro de campo vazio.
                $ContaCadastrada                = mysqli_escape_string($con, $_POST['bancoparatransferencia']);
                    $ContaCadastrada            = explode('~', $ContaCadastrada);

                    $codigoDaContaCadastrada        = $ContaCadastrada[0];
                    $bancoDaContaCadastrada         = $ContaCadastrada[1];
                    $agenciaDaContaCadastrada       = $ContaCadastrada[2];
                    $contaDaContaCadastrada         = $ContaCadastrada[3];
                    $operadorDaContaCadastrada      = $ContaCadastrada[4];
                    $titularDaContaCadastrada       = $ContaCadastrada[5];
                    $titularCpfDaContaCadastrada    = $ContaCadastrada[6];
                    $tipodecontaDaContaCadastrada   = $ContaCadastrada[7];
            endif;


            //Inserir primeira conta caso não tenha uma.
            if(!empty($_POST['PrimeiraAgencia'])): // Para não dar erro de campo vazio.
                $PrimeiraAgencia                =   mysqli_escape_string($con, $_POST['PrimeiraAgencia']);
                $PrimeiraAgenciaDig             =   mysqli_escape_string($con, $_POST['PrimeiraAgenciaDig']);
                $PrimeiraConta                  =   mysqli_escape_string($con, $_POST['PrimeiraConta']);
                $PrimeiraContaDig               =   mysqli_escape_string($con, $_POST['PrimeiraContaDig']);
                $Primeirobanco                  =   mysqli_escape_string($con, $_POST['Primeirobanco']);
                $Primeirotitular                =   mysqli_escape_string($con, $_POST['Primeirotitular']);
                $PrimeirotitularCpf             =   mysqli_escape_string($con, $_POST['Primeirotitularcpf']);
                $Primeirotipodeconta            =   'CORRENTE';
            endif;

            //Cadastrar Outra conta para transferência
            if(!empty($_POST['PrimeiraAgencia'])): // Para não dar erro de campo vazio.            
                $novaAgencia                    =   mysqli_escape_string($con, $_POST['novaAgencia']);
                $novaAgenciaDig                 =   mysqli_escape_string($con, $_POST['novaAgenciaDig']);
                $novaConta                      =   mysqli_escape_string($con, $_POST['novaConta']);
                $novaContaDig                   =   mysqli_escape_string($con, $_POST['novaContaDig']);
                if(!empty($banconew)):
                    $banco=$banconew;
                else:
                    $banco                          =   mysqli_escape_string($con, $_POST['banco']);
                endif;
                $titular                        =   mysqli_escape_string($con, $_POST['titular']);
                $titularcpf                     =   mysqli_escape_string($con, $_POST['titularcpf']);
                $tipodeconta                    =   'CORRENTE';
            endif;

            //Ajustar para conta registrada ou nova conta
            // 1 - Banco
            if(!empty($bancoDaContaCadastrada)): //Banco usada anteriormente.
                $bancoDefinido = $bancoDaContaCadastrada;
            elseif(!empty($Primeirobanco)): //Primeiro pedido de saque.
                $bancoDefinido = $Primeirobanco;
            elseif(!empty($banco)): //Banco cadastrada para esta operação.
                $bancoDefinido = $banco; 
            endif;
            
            // 2 - Agência
            if(!empty($agenciaDaContaCadastrada)): //Agência utilizada anteriormente.
                $agenciaDefinido    =   $agenciaDaContaCadastrada;
            elseif(!empty($PrimeiraAgencia) AND !empty($PrimeiraAgenciaDig)): //Primeiro pedido de saque -- agência c/ digíto
                $agenciaDefinido = $PrimeiraAgencia.'-'.$PrimeiraAgenciaDig;
            elseif(!empty($PrimeiraAgencia) AND empty($PrimeiraAgenciaDig)): //Primeiro pedido de saque -- agência s/ digíto
                $agenciaDefinido = $PrimeiraAgencia;
            elseif(!empty($novaAgencia) AND !empty($novaAgenciaDig)): //Agência c/ digíto cadastrada para esta operação
                $agenciaDefinido = $novaAgencia.'-'.$novaAgenciaDig;
                $agencia2 = $novaAgencia.'-'.$novaAgenciaDig;
            elseif(!empty($novaAgencia) AND empty($novaAgenciaDig)): //Agência s/ digíto cadastrada para esta operação
                $agenciaDefinido = $novaAgencia;
                $agencia2 = $novaAgencia;
            endif;
            
            // 3 - Conta
            if(!empty($contaDaContaCadastrada)): //Agência utilizada anteriormente.
                $contaDefinido    =   $contaDaContaCadastrada;
            elseif(!empty($PrimeiraConta) AND !empty($PrimeiraContaDig)): //Primeiro pedido de saque -- agência c/ digíto
                $contaDefinido = $PrimeiraConta.'-'.$PrimeiraContaDig;
            elseif(!empty($PrimeiraConta) AND empty($PrimeiraContaDig)): //Primeiro pedido de saque -- agência s/ digíto
                $contaDefinido = $PrimeiraConta;
            elseif(!empty($novaConta) AND !empty($novaContaDig)): //Agência c/ digíto cadastrada para esta operação
                $contaDefinido = $novaConta.'-'.$novaContaDig;
                $contadedestino = $novaConta.'-'.$novaContaDig;
            elseif(!empty($novaConta) AND empty($novaContaDig)): //Agência s/ digíto cadastrada para esta operação
                $contaDefinido = $novaConta;
                $contadedestino = $novaConta;
            endif;

            // 4 - Titular
            if(!empty($titularDaContaCadastrada)): //Titular usada anteriormente.
                $titularDefinido = $titularDaContaCadastrada;
            elseif(!empty($Primeirotitular)): //Primeiro pedido de saque.
                $titularDefinido = $Primeirotitular;
            elseif(!empty($titular)): //Titular cadastrada para esta operação.
                $titularDefinido = $titular; 
            endif;

            // 5 - CPF do titular
            if(!empty($titularCpfDaContaCadastrada)): //Titular usada anteriormente.
                $titularCpfDefinido = $titularCpfDaContaCadastrada;
            elseif(!empty($PrimeirotitularCpf)): //Primeiro pedido de saque.
                $titularCpfDefinido = $PrimeirotitularCpf;
            elseif(!empty($titularcpf)): //Titular cadastrada para esta operação.
                $titularCpfDefinido = $titularcpf; 
            endif;

            // 6 - Tipo de conta
            if(!empty($tipodecontaDaContaCadastrada)): //Titular usada anteriormente.
                $tipodecontaDefinido = $tipodecontaDaContaCadastrada;
            elseif(!empty($Primeirotipodeconta)): //Primeiro pedido de saque.
                $tipodecontaDefinido = $Primeirotipodeconta;
            elseif(!empty($tipodeconta)): //Titular cadastrada para esta operação.
                $tipodecontaDefinido = $tipodeconta; 
            endif;

            // 7 - Código da conta utilizada.
            if(!empty($codigoDaContaCadastrada)): //Titular usada anteriormente.
                $CodigoDaContaDefinida = $codigoDaContaCadastrada;
            elseif(!empty($PrimeiroCodigoParaConta)): //Primeiro pedido de saque.
                $CodigoDaContaDefinida = $PrimeiroCodigoParaConta;
            elseif(!empty($novoCodigoParaEstaConta)): //Titular cadastrada para esta operação.
                $CodigoDaContaDefinida = $novoCodigoParaEstaConta; 
            endif;

            
            //Registrar banco definido CASO não esteja registrado

            $IpDaSolicitacao    = $_SERVER['REMOTE_ADDR'];
            $HoraDaSolicitacao  = date('H:i');

            $saldopossaque      =   $saldonodia - $ValorSolicitadoParaSaque; //O que deve ficar na conta após a operação.

            
            $statusdasolicitacao    =   'PENDENTE'; //O usuário precisará confirmar em outra tela o código (Token da solicitação) que será enviado para o e-mail dele.

            $dataprevistaparaosaque =   date(("Y-m-d"), strtotime($data.'+ 2 days'));

            $bCodigoDoSaque = "SELECT id FROM solicitacaodesaque";
              $rCDS = mysqli_query($con, $bCodigoDoSaque);
                $nCDS = mysqli_num_rows($rCDS);

            $CodigoDaSolicitacao = $nCDS.mt_rand(99, 9999);

            $tokenDaSolicitacao  = mt_rand(100000, 999999); //Token para confirmar a solicitacao

            //Registrar novas contas
            if(!empty($PrimeiraAgencia)): //Registrar essa conta
                $bCodigoPConta = "SELECT id FROM contasfinanceiro";
                  $rCPC = mysqli_query($con, $bCodigoPConta);
                    $nCPC = mysqli_num_rows($rCPC);

                $PrimeiroCodigoParaConta        = $nCPC.mt_rand(001, 999);
                
                $RPC = "INSERT INTO contasfinanceiro (empresa, codigodoparceiro, urldaempresa, dia, mes, ano, representante, codigodorepresentante, codigodaconta, nomedaconta, banco, agencia, conta, operador, titular, cpf, tipodeconta) VALUES ('Emanuel', '0001', 'igrejaemanuel', '$dia', '$mesnumero', '$ano', '$usuario', '$matriculausuario', '$PrimeiroCodigoParaConta', '', '$bancoDefinido', '$agenciaDefinido', '$contaDefinido', '', '$titularDefinido', '$titularCpfDefinido', '$tipodecontaDefinido')";
                    mysqli_query($con, $RPC);
            elseif(!empty($novaAgencia)): //Nova conta para esta operação.
                $bCnC = "SELECT id FROM contasfinanceiro";
                  $rCnC = mysqli_query($con, $bCnC);
                    $nCnC = mysqli_num_rows($rCnC);

                $novoCodigoParaEstaConta        = $nCnC.mt_rand(001, 999);

                $RNC = "INSERT INTO contasfinanceiro (empresa, codigodoparceiro, urldaempresa, dia, mes, ano, representante, codigodorepresentante, codigodaconta, nomedaconta, banco, agencia, conta, operador, titular, cpf, tipodeconta) VALUES ('Emanuel', '0001', 'igrejaemanuel', '$dia', '$mesnumero', '$ano', '$usuario', '$matriculausuario', '$novoCodigoParaEstaConta', '', '$banco', '$novaAgencia2', '$novaConta2', '', '$titular', '$titularcpf', '$tipodeconta')";
                    mysqli_query($con, $RNC);
            endif;

            //Se o saldo for negativo não realiza a operação.
            if($saldopossaque >= 0):
                //Registrar solicitação de saque
                $Ssaque = "INSERT INTO solicitacaodesaque (codigodasolicitacao, dia, mes, ano, empresa, urldaempresa, codigodaempresa, usuariosolicitante, codigodousuario, valorsolicitado, banco, agencia, conta, operador, tipodeconta, titular, titularcpf, ipdesolicitacao, hora, saldonodia, saldopossaque, comprovantedetransferencia, tokendasolicitacao, statusdasolicitacao, '') VALUES ('$CodigoDaSolicitacao', '$dia', '$mesnumero', '$ano', 'Emanuel', 'igrejaemanuel', '0001', '$usuario', '$matriculausuario', '$ValorSolicitadoParaSaque', '$bancoDefinido', '$agenciaDefinido', '$contaDefinido', '', '$tipodecontaDefinido', '$titularDefinido', '$titularCpfDefinido', '$IpDaSolicitacao', '$HoraDaSolicitacao', '$saldonodia', '$saldopossaque', '', '$tokenDaSolicitacao', '$statusdasolicitacao')";

                //Evitar solicitação dublicada.
                $bSSdupli = "SELECT id FROM solicitacaodesaque WHERE codigodaempresa='0001' AND $saldonodia='$saldonodia' AND saldopossaque='$saldopossaque' AND statusdasolicitacao='PENDENTE'";
                $rSSd = mysqli_query($con, $bSSdupli);
                    $nSSd = mysqli_num_rows($rSSd);

                if($nSSd < 1):
                    
                    //Registrar na tabela financeiro o débito deste saque.
                    $SaidaDoSaque = "INSERT INTO financeiro (empresa, codigodoparceiro, urldaempresa, dia, mes, ano, representante, codigodorepresentante, codigodaoperacao, descricao, valorprevisto, dataprevista, valorrealizado, datarealizada, recebidode, pagopor, anexo, tamanhodoarquivo, tipodeoperacao, categoria, centrodecusto, conta1, conta2, tipoderegistro) VALUES ('Emanuel', '0001', 'igrejaemanuel', '$dia', '$mesnumero', '$ano', '$usuario', '$matriculausuario', '$CodigoDaSolicitacao', 'Saque', '$ValorSolicitadoParaSaque', '$dataprevistaparaosaque', '', '', '', '', '', '', 'SAQUE', 'SAQUE', '', 'Emanuel', '$CodigoDaContaDefinida', '$tipodecontaDefinido')";
                        mysqli_query($con, $SaidaDoSaque);
                    
                    //Enviar um e-mail com confirmação da solicitação.
                    if(mysqli_query($con, $Ssaque)):
                        //Enviar e-mail de boas vindas para o representante.
                        include "_enviaremail.php"; // Configuração para envio de e-mail.
                        $mail->setFrom("contato@igrejaemanuel.com.br", "$nomedousuario"); //Enviado por
                        $mail->addAddress("$emailusuario", "Igreja Emanuel");     // Add a recipient 
                            //$mail->addAddress("ellen@example.com");               // Name is optional
                            //$mail->addReplyTo("$email", "Igreja Emanuel"); //Para quem deve responder.
                            //$mail->addCC("$emaildoresponsavel"); //Cópia do email.
                            //$mail->addBCC("$emaildaempresa");	//Cópia oculta do e-mail
                            //$mail->addAttachment("/var/tmp/file.tar.gz");         // Add attachments
                            //$mail->addAttachment("/tmp/image.jpg", "new.jpg");    // Optional name
                        $mail->isHTML(true);                                  // Set email format to HTML
                        $mail->Subject = "$nomedousuario, saque solicitado.";
                        $mail->Body    = "Olá, $nomeusuario.
                                        <p>Foi solicitado um saque para Igreja Emanuel</p>
                                        <p>Informe o código de verificação para concluir o pedido: $tokenDaSolicitacao</p>
                                        <p></p>
                        ";
                        $mail->AltBody = "Olá, $nomeusuario.
                                        <p>Solicitação de saque: $CodigoDaSolicitacao</p>
                                        <p></p>
                        "; // Para o trello!
                        if($mail->send()):
                            $_SESSION['cordamensagem'] = "green";
                            $_SESSION['mensagem']="Solicitação iniciada. Digite o código de confirmação enviado para o seu e-mail.";
                            header("location:confirmarsaque?m=$matriculausuario&token=$token");
                        else:
                            $_SESSION['cordamensagem'] = "red";
                            $_SESSION['mensagem']="<strong>Verifique sua conexão</strong>. Não foi possível solicitar seu saque.";
                            //header("location:confirmarsaque?m=$matriculausuario&token=$token");
                        endif;
                    endif;
                else:
                    $_SESSION['cordamensagem'] = "red";
                    $_SESSION['mensagem']="Uma solicitação idêntica foi realizada anteriormente, verifique se você informou o código de confirmação que te enviamos. Caso acredite que isto é um erro, abra um chamado e nossa equipe irá te auxiliar.";
                    //header("location:confirmarsaque?m=$matriculausuario&token=$token");
                endif;
            else:
                $_SESSION['cordamensagem'] = "red";
                $_SESSION['mensagem']="Não há saldo suficiente para esta solicitação neste momento, ajuste o valor de saque ou aguarde novas receitas.";
                //header("location:confirmarsaque?m=$matriculausuario&token=$token");
            endif;
        endif;
    endif;

    //Excluir operação financeira.
    if(isset($_GET['exc'])):
        $codigodaoperacaoparaexcluir = $_GET['exc'];
        $idparaexcluir = $_GET['idexc'];

        //Buscar anexo para excluir o arquivo.
        $Oper = "SELECT anexo FROM financeiro WHERE codigodaoperacao='$codigodaoperacaoparaexcluir' AND anexo!=''";
          $rOper = mysqli_query($con, $Oper);
            $dcv = mysqli_fetch_array($rOper);

            $anexoexcluido = $dcv['anexo'];

        @unlink($anexoexcluido); //Exclui o arquivo do anexo encontrado.
                 
        $ExcOperacao = "DELETE FROM financeiro WHERE codigodaoperacao='$codigodaoperacaoparaexcluir' AND id='$idparaexcluir'";
        if(mysqli_query($con, $ExcOperacao)):
            //Dados para registrar o log do cliente.
            $mensagem = ("\n$nomedocolaborador ($matricula) Acessou a página financeira e realizou a exclusão da operação financeira: $codigodaoperacaoparaexcluir. Isto através do IP: $ip, na data $dia às $hora.\n"); //Escreva a mensagem que será gravada no log.
              include "./registrolog.php";
            header("Location:financeiro?m=$matricula&token=$token&ms=$mesdeoperacaoget&type=$tipodeoperacaoget&y=$yta3");
        endif;
	endif;
    
    //Excluir operação financeira e repetições
    if(isset($_GET['exct'])):
        $codigodaoperacaoparaexcluir = $_GET['exct'];
        $idparaexcluir = $_GET['idexc'];

        //Buscar anexo para excluir o arquivo.
        $Oper = "SELECT anexo FROM financeiro WHERE codigodaoperacao='$codigodaoperacaoparaexcluir' AND anexo!=''";
          $rOper = mysqli_query($con, $Oper);
            $dcv = mysqli_fetch_array($rOper);

            $anexoexcluido = $dcv['anexo'];

        @unlink($anexoexcluido); //Exclui o arquivo do anexo encontrado.
                 
        $ExcOperacao = "DELETE FROM financeiro WHERE codigodaoperacao='$codigodaoperacaoparaexcluir' AND id >= '$idparaexcluir'";
        if(mysqli_query($con, $ExcOperacao)):
            //Dados para registrar o log do cliente.
            $mensagem = ("\n$nomedocolaborador ($matricula) Acessou a página financeira e realizou a exclusão da operação financeira: $codigodaoperacaoparaexcluir. Isto através do IP: $ip, na data $dia às $hora.\n"); //Escreva a mensagem que será gravada no log.
              include "./registrolog.php";
              header("Location:financeiro?m=$matricula&token=$token&ms=$mesdeoperacaoget&type=$tipodeoperacaoget&y=$yta3");
        endif;
	endif;

    //Pagar operação financeira.
    if(isset($_GET['pay'])):
        $idparapagar = $_GET['ref'];
        $codigodaoperacaoparapagar = $_GET['pay'];
        $tipodeoperacao = $_GET['t'];
        $mesdaoperacao = $_GET['ms'];

        //Buscar anexo para pagar o arquivo.
        $Oper = "SELECT valorprevisto FROM financeiro WHERE codigodaoperacao='$codigodaoperacaoparapagar' AND id='$idparapagar'";
          $rOper = mysqli_query($con, $Oper);
            $dcv = mysqli_fetch_array($rOper);

            $valorprevistoparapagar = $dcv['valorprevisto'];
                 
        $PagarMovimentacao = "UPDATE financeiro SET valorrealizado='$valorprevistoparapagar', datarealizada='$dataeng' WHERE codigodaoperacao='$codigodaoperacaoparapagar' AND id='$idparapagar'";
        if(mysqli_query($con, $PagarMovimentacao)):
            $_SESSION['cordamensagem']='green';
            $_SESSION['mensagem']="Pagamento confirmado com sucesso";
            header("refres:3; url=financeiro$linkSeguro&t=$tipodeoperacao&ms=$mesdaoperacao");
            
            //Dados para registrar o log do cliente.
            $mensagem = ("\n$nomedocolaborador ($matricula) Acessou a página financeira e confirmou o valor previsto da operação financeira: $codigodaoperacaoparapagar. Isto através do IP: $ip, na data $dia às $hora.\n"); //Escreva a mensagem que será gravada no log.
              include "./registrolog.php";
        else:
            $_SESSION['cordamensagem']='red';
            $_SESSION['mensagem']="Erro ao confirmar movimentação.";
        endif;
	endif;

    if(!isset($_GET['type'])):
        $shadowc='shadow';
        $shadowd='';
        $stylebuttonC='button medium rounded bkg-green bkg-hover-green color-white color-hover-white';
        $stylebuttonD='button medium rounded border-red bkg-hover-red color-red color-hover-white';
        $novamovimentacaoColor="button medium rounded bkg-green bkg-hover-green color-white color-hover-white";
        $nomebuttonnovamovimentacao="Adicionar Receita";
        $nomebuttonform = "btn-cadastrar";
        $bkgmodal = 'bkg-green color-white';
        $tipodeformulario = "CRÉDITO";
    elseif($_GET['type'] === 'c'):
        $shadowc='shadow';
        $shadowd='';
        $stylebuttonC='button medium rounded bkg-green bkg-hover-green color-white color-hover-white';
        $stylebuttonD='button medium rounded border-red bkg-hover-red color-red color-hover-white';
        $novamovimentacaoColor="button medium rounded bkg-green bkg-hover-green color-white color-hover-white";
        $nomebuttonnovamovimentacao="Adicionar Receita";
        $nomebuttonform = "btn-cadastrar";
        $bkgmodal = 'bkg-green color-white';
        $tipodeformulario === "CRÉDITO";
        $colorPage = "green";
    elseif($_GET['type'] === 'd'):
        $shadowc='';
        $shadowd='shadow';
        $stylebuttonC='button medium rounded border-blue bkg-hover-green color-green color-hover-white';
        $stylebuttonD='button medium rounded bkg-red bkg-hover-red color-white color-hover-white ';
        $novamovimentacaoColor="button medium rounded bkg-red bkg-hover-red color-white color-hover-white";
        $nomebuttonnovamovimentacao="Adicionar Saída";
        $nomebuttonform = "btn-cadastrardebito";
        $bkgmodal = 'bkg-red color-white';
        $tipodeformulario === "DÉBITO";
        $colorPage = "red";
    else:
        $shadowc='';
        $shadowd='';
    endif;
    
    if(isset($_POST['btn-categoria'])):
        $tipodecategoria      = mysqli_escape_string($con, $_POST['tipodecategoria']);
        $categoria      = mysqli_escape_string($con, $_POST['categoria']);

        //Impedir inserção duplicada.
        $bcategoria = "SELECT categoria FROM categoriasfinanceiro WHERE empresa='Emanuel' AND urldaempresa='igrejaemanuel' AND categoria='$categoria' AND tipodecategoria='$tipodecategoria'";
            $rcategoria = mysqli_query($con, $bcategoria);
            $ncategoria=mysqli_num_rows($rcategoria);
        
        if($ncategoria < 1):
            //Limitar cinco categorias
            $fivecategorias="SELECT id FROM categoriasfinanceiro WHERE empresa='Emanuel' AND urldaempresa='igrejaemanuel' AND tipodecategoria='$tipodecategoria'";
                $rFC=mysqli_query($con, $fivecategorias);
                $nFC=mysqli_num_rows($rFC);

                    $codigodacategoria = $nFC.mt_rand(01, 999);

            // if($nFC < 10):
                $addcategoria = "INSERT INTO categoriasfinanceiro (empresa, codigodoparceiro, urldaempresa, dia, mes, ano, representante, codigodorepresentante, codigodacategoria, categoria, tipodecategoria) VALUES ('Emanuel', '0001', 'igrejaemanuel', '$dia', '$mes', '$ano', '$nomedousuario', '$matriculausuario', '$codigodacategoria', '$categoria', '$tipodecategoria')";
                if(mysqli_query($con, $addcategoria)):
                    $_SESSION['cordamensagem']='green';
                    $_SESSION['mensagem']='<strong>Categoria adicionada</strong>.';
                else:
                    $_SESSION['cordamensagem']='red';
                    $_SESSION['mensagem']='<strong>Categoria adicionada</strong>.';
                endif;
            // endif;
        else:
            $_SESSION['cordamensagem']='red';
            $_SESSION['mensagem']='Categoria adicionada <strong>anteriormente</strong>.';
        endif;
    endif;
    
    if(!isset($_GET['y'])):
        $yeartableactive=date('Y');
        $yta="&y=$yeartableactive";
    elseif(isset($_GET['y']) AND !empty($_GET['y'])):
        $yeartableactive=$_GET['y'];
        $yta="&y=$yeartableactive";
    endif;
    
    //Dados para registrar o log do cliente.
    $mensagem = ("\n$nomedocolaborador ($matricula) Acessou página financeira. Isto através do IP: $ip, na data $dia às $hora.\n"); //Escreva a mensagem que será gravada no log.
        include "registrolog.php";
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
                <div class="section-block tm-slider-parallax-container pb-30 small bkg-blue">
					<div class="row">
                        <div class="box rounded small bkg-white shadow">
						    <div class="column width-12">
                                <div class="title-container">
                                    <div class="title-container-inner">
                                        <div class="row flex">
                                            <div class="column width-12 v-align-middle">
                                                <div>
                                                    <h1 class="mb-0">Planejamento e Controle <strong>Financeiro</strong> da Emanuel</h1>
                                                    <p class="text-large color-charcoal mb-0 mb-mobile-20">Aqui realizamos o controle e o planejamento financeiro da instituição.</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
							</div>
						</div>
					</div>
				</div>
                <div class="section-block team-2 pt-50 pb-0 bkg-grey-ultralight">
                    <div class="row full-width">
                        <div class="column width-12 v-align-middle">
                            <a data-content="inline"
                                data-aux-classes="tml-form-modal tml-exit-light height-auto with-header rounded"
                                data-toolbar="" data-modal-mode data-modal-width="600" data-modal-animation="scaleIn"
                                data-lightbox-animation="fadeIn" href="#solicitarsaque"
                                class="lightbox-link column width-12 button bkg-blue bkg-hover-blue small color-white color-hover-white hard-shadow center">
                                <span class="text-medium">
                                    Solicitar saque
                                </span>
                            </a>
                        </div>
                        <div class="column width-3 v-align-middle">
                            <div class="box rounded bkg-white small border-blue shadow">
                                <div class="pb-0">
                                    <?php
                                        //Pegar valor disponível para a empresa.
                                        //Tipo de operação é Venda com Mercado Pago --> VCMP
                                            $bVMpRECEITA = "SELECT sum(valorrealizado) as receitarealizada FROM financeiro WHERE NOT (valorrealizado = '') AND tipoderegistro='CRÉDITO' AND ano='$ano' AND conta1!='272112-8' AND conta1!='0000' AND categoria !='FEP'"; //Venda Mercado Pago.
                                        $rVMpRECEITA = mysqli_query($con, $bVMpRECEITA);
                                            $dVMpR = mysqli_fetch_array($rVMpRECEITA);

                                            $ReceitaDisponivelDaEmpresa = $dVMpR['receitarealizada'];

                                        //SQMP = Saque do Mercado Pago
                                            $bVMpDEBITO = "SELECT sum(valorrealizado) as saquesrealizados FROM financeiro WHERE NOT (valorrealizado = '') AND tipoderegistro='DÉBITO' AND ano='$ano' AND conta1!='272112-8' AND conta1!='0000' AND categoria !='FEP'"; //Venda Mercado Pago.
                                        $rVMpDEBITO = mysqli_query($con, $bVMpDEBITO);
                                            $dVMpD = mysqli_fetch_array($rVMpDEBITO);

                                            $SaquesRealizados = $dVMpD['saquesrealizados'];

                                        $SaldoDisponivelParaSaque = $ReceitaDisponivelDaEmpresa - $SaquesRealizados;

                                        $Pago24HorasAntes = date(('Y-m-d'), strtotime(date($dataeng).'- 1 day'));

                                        //Saldo bloqueado -- Pagamentos realizados Antes de 24h ou 48h ficam indisponíveis para saque.
                                        $bSBloq = "SELECT sum(valorrealizado) as saldobloqueadodaempresa FROM financeiro WHERE NOT (valorrealizado != '') AND datarealizada > '$Pago24HorasAntes' AND tipodeoperacao='VCMP' AND tipoderegistro='CRÉDITO' AND ano='$ano' AND conta1!='272112-8' AND conta1!='0000' AND categoria != 'FEP'"; //Venda Mercado Pago.
                                        $rSBq = mysqli_query($con, $bSBloq);
                                            $dSBq = mysqli_fetch_array($rSBq);

                                        $SaldoBloqueado = $dSBq['saldobloqueadodaempresa'];

                                        if(empty($SaldoBloqueado)): $SaldoBloqueado = '0,00'; endif;

                                        //Saldo do mês de Receita
                                        if(!isset($_GET['ms']) OR empty($_GET['ms'])): $mesSM=$mesnumero; else: $mesSM=$mesdeoperacaoget; endif;

                                        $bSMC = "SELECT sum(valorrealizado) as somadomes FROM financeiro WHERE NOT (valorrealizado = '') AND tipoderegistro='CRÉDITO' AND ano='$yta3' AND mes='$mesSM' AND conta1!='272112-8' AND conta1!='0000' AND categoria !='FEP'"; //Venda Mercado Pago.
                                          $rSMC=mysqli_query($con, $bSMC);
                                            $dSMC=mysqli_fetch_array($rSMC);
                                        
                                        $CreditoMes=$dSMC['somadomes'];

                                        //Saldo do mês de Débito
                                        $bSMD = "SELECT sum(valorrealizado) as somadomesSaida FROM financeiro WHERE NOT (valorrealizado = '') AND tipoderegistro='DÉBITO' AND ano='$yta3' AND mes='$mesSM' AND conta1!='272112-8' AND conta1!='0000' AND categoria !='FEP'"; //Venda Mercado Pago.
                                          $rSMD=mysqli_query($con, $bSMD);
                                            $dSMD=mysqli_fetch_array($rSMD);
                                        
                                        $DebitoMes=$dSMD['somadomesSaida'];

                                        $saldodomes=$CreditoMes - $DebitoMes; $smN=$saldodomes;
                                        $saldodomes = number_format($saldodomes, 2, ',', '.');

                                        if(empty($SaldoEmDisputa)): $SaldoEmDisputa = '0,00'; endif;
                                    ?>
                                    <span class="title-medium pb-20 color-charchol weight-bold">
                                        <?php
                                            if($SaldoDisponivelParaSaque > 1):
                                                $SaldoDisponivelParaSaquePOR = $SaldoDisponivelParaSaque;
                                                //Adequa o valor para BD
                                                if($SaldoDisponivelParaSaquePOR >= 1000): //Se o valor inserido for maior que 1000 utiliza o algoritimo abaixo.
                                                    @$SaldoDisponivelParaSaquePOR = number_format($SaldoDisponivelParaSaquePOR, 2, ',', '.');
                                                elseif($SaldoDisponivelParaSaquePOR < 1000): //Se for inferior utiliza o algoritimo.
                                                    $SaldoDisponivelParaSaquePOR = number_format($SaldoDisponivelParaSaquePOR, 2, ',',',');
                                                endif;
                                            else:
                                                $SaldoDisponivelParaSaquePOR = $SaldoDisponivelParaSaque;
                                                //Adequa o valor para BD
                                                if($SaldoDisponivelParaSaquePOR <= 1000): //Se o valor inserido for maior que 1000 utiliza o algoritimo abaixo.
                                                    @$SaldoDisponivelParaSaquePOR = number_format($SaldoDisponivelParaSaquePOR, 2, ',','.');
                                                else: //Se for inferior utiliza o algoritimo.
                                                    $SaldoDisponivelParaSaquePOR = number_format($SaldoDisponivelParaSaquePOR, 2, ',',',');
                                                endif;
                                            endif;
                                            echo "R$ ".$SaldoDisponivelParaSaquePOR;
                                        ?>
                                        <a href="./analise?<?echo $linkSeguro;?>" target="_blank"><span class="text-small weight-regular">(Extrato)</span></a>        
                                    </span>
                                    <br />
                                    <span class="text-medium pt-0 pb-0">Indisponível R$ <?php echo $SaldoBloqueado; ?></span>
                                    <br />
                                    <span class="text-medium pt-0 pb-0 color-blue">Saldo do mês R$
                                        <?php echo $saldodomes; ?></span>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Reserva -->
                        <div id="cookie-modal" class="modal-dialog-inner section-block cart-overview pt-0 pb-30 background-none hide">
                            <div class="modal-header bkg-blue color-white">
                                <h4 class="modal-header-title">Fundos de emergência</h4>
                            </div>
                            <?
                                $bEC="SELECT sum(valorrealizado) as valorreservavariavelC FROM financeiro WHERE tipoderegistro='CRÉDITO' AND ano='$yta3' AND conta1='272112-8'";
                                  $rEC=mysqli_query($con, $bEC);
                                    $dEC=mysqli_fetch_array($rEC);
                                $creditoEmergVari=$dEC['valorreservavariavelC'];

                                $bED="SELECT sum(valorrealizado) as valorreservavariavelD FROM financeiro WHERE tipoderegistro='DÉBITO' AND ano='$yta3' AND conta1='272112-8'";
                                  $rED=mysqli_query($con, $bED);
                                    $dED=mysqli_fetch_array($rED);
                                $debitoEmergVari=$dED['valorreservavariavelD'];

                                $fV=$creditoEmergVari - $debitoEmergVari; $fV0=$fV;
                                $fV=number_format($fV, 2, ',', '.');
                                
                                /* Fundo em dinheiro */
                                $bEdinheiroC="SELECT sum(valorrealizado) as valorreservavariavelC FROM financeiro WHERE tipoderegistro='CRÉDITO' AND ano='$yta3' AND conta1='0000'";
                                  $rEdinheiroC=mysqli_query($con, $bEdinheiroC);
                                    $dEC=mysqli_fetch_array($rEdinheiroC);
                                $creditoEmergDinheiro=$dEC['valorreservavariavelC'];

                                $bEdinheiroD="SELECT sum(valorrealizado) as valorreservavariavelD FROM financeiro WHERE tipoderegistro='DÉBITO' AND ano='$yta3' AND conta1='0000'";
                                  $rEdinheiroD=mysqli_query($con, $bEdinheiroD);
                                    $dEdinheiroD=mysqli_fetch_array($rEdinheiroD);
                                $debitoEmergDinheiro=$dEdinheiroD['valorreservavariavelD'];

                                $fDinheiro=$creditoEmergDinheiro- $debitoEmergDinheiro; $fDinheiro0=$fDinheiro;
                                $fDinheiro=number_format($fDinheiro, 2, ',', '.');

                                $pRes=$fV0+$fDinheiro0;
                            ?>
                            <p class="color-charcoal">Saldo disponível no <strong>fundo variável</strong> (Clear Corretora); R$ <? echo $fV;?>
                            <br>Saldo disponível <strong>em dinheiro</strong>; R$ <? echo $fDinheiro;?></p>

                        </div>
                        <!-- Cookie Modal End -->
                        <div class="column width-3 v-align-middle">
                            <div class="box border-blue bkg-white rounded small shadow">
                                    <h3 class="title-medium weight-bold color-blue">RESERVA 
                                        <a data-content="inline" data-aux-classes="tml-form-modal tml-exit-light height-auto with-header rounded" data-toolbar="" data-modal-mode data-modal-width="500" data-modal-animation="scaleIn" data-lightbox-animation="fade" href="#cookie-modal" class="lightbox-link"><span class="icon-info-with-circle small"></span>
                                        </a>
                                        <a href="./analiseemergencia?<?echo $linkSeguro;?>" target="_blank"><span class="text-small weight-regular">(Extrato)</span></a>
                                    </h3>

                                    <?php
                                        
                                    ?>
                                <div class="progress-bar-group pb-10">
                                    <div class="column width-12">
                                        <span class="progress-bar-label pull-left text-medium color-blue">Variável</span>
                                        <span class="progress-bar-label pull-right text-medium color-charcoal">Dinheiro</span>
                                    </div>
                                    <div class="column width-12">
                                        <span class="progress-bar-label pull-left color-blue"><?php echo 'R$ '.$fV;?></span>
                                        <span class="progress-bar-label pull-right color-charcoal"><?php echo 'R$ '.$fDinheiro;?>
                                        </span>
                                    </div>
                                    <br>
                                        <?php
                                            @$vbr = $fV0 / $pRes; //Valor Barra
                                            $vbr=$vbr*100;
                                            @$vbr = number_format($vbr, 0, ',', '.');
                                            if($vbr >= 100): //vmb Demanda do Produto
                                                $vbr = 100;
                                            elseif($vbr > 1 AND $vbr < 10):
                                                $vbr = 10;
                                            elseif($vbr > 10 AND $vbr < 20):
                                                $vbr = 20;
                                            elseif($vbr > 20 AND $vbr < 30):
                                                $vbr = 30;
                                            elseif($vbr > 30 AND $vbr < 40):
                                                $vbr = 40;
                                            elseif($vbr > 40 AND $vbr < 50):
                                                $vbr = 50;
                                            elseif($vbr > 50 AND $vbr < 60):
                                                $vbr = 60;
                                            elseif($vbr > 60 AND $vbr < 70):
                                                $vbr = 70;
                                            elseif($vbr > 70 AND $vbr < 80):
                                                $vbr = 80;
                                            elseif($vbr > 80 AND $vbr < 100):
                                                $vbr = 90;
                                            elseif($vbr >= 98):
                                                $vbr = 100;
                                                //$filtro = array('05' => '10', '15' => '20','25' => '30','35' => '40','45' => '50','55' => '60','65' => '70','75' => '80','85' => '90','95' => '100');
                                                //$vbr = strTr($vbr, $filtro);
                                            elseif($vbr = 0 OR $vbr = ''):
                                                $vbr=0;
                                            endif;
                                        ?>
                                    <div class="progress-bar pill small pt-0 pb-0 bkg-grey-ultralight">
                                        <div class="bar bkg-blue color-white percent-<?php echo $vbr; ?> horizon"
                                            data-animate-in="transX:-100%;duration:1000ms;easing:easeIn;">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Fim da Reserva -->
                        
                        <!-- Entrada financeira -->
                        <div class="column width-3 v-align-middle">
                            <div class="box border-blue bkg-white rounded small shadow">
                                <h3 class="title-medium weight-bold color-green-light">ENTRADA</h3>
                                    <?php
                                        $mesatualguia = $_GET['ms']; //Mês definido por uma alteração na tabela.

                                        if(!empty($_GET['ms'])):
                                            $mesnumeroDASH = $mesatualguia;
                                        else:
                                            $mesnumeroDASH = $mesnumero;
                                        endif;

                                        //Buscar valor previsto.
                                        $bPAG="SELECT sum(valorprevisto) as valortotalprevisto, sum(valorrealizado) as valortotalrealizado FROM financeiro WHERE empresa='Emanuel' AND urldaempresa='igrejaemanuel' AND mes='$mesnumeroDASH' AND ano='$yta3' AND tipoderegistro='CRÉDITO' AND conta1!='272112-8' AND conta1!='0000' AND categoria!='FEP' AND tipodeoperacao!='Transferência'";
                                          $rPAG=mysqli_query($con, $bPAG);
                                            $dPAG = mysqli_fetch_array($rPAG);

                                        $VREAL0 = $dPAG['valortotalrealizado'];
                                            //Adequa o valor para BD
                                            if($VREAL0 >= 1000): //Se o valor inserido for maior que 1000 utiliza o algoritimo abaixo.
                                                @$VREAL = number_format($VREAL0, 2, ',','.');
                                            elseif($VREAL0 < 1000): //Se for inferior utiliza o algoritimo.
                                                @$VREAL = str_replace('.', ',', $VREAL0);
                                            endif;

                                        if(!isset($VREAL0)):
                                            $VREAL = '0,00';
                                        endif;
                                    ?>
                                <div class="progress-bar-group pb-10">
                                    <div class="column width-12">
                                        <span class="progress-bar-label pull-left text-medium color-green">Realizado</span>
                                        <span class="progress-bar-label pull-right text-medium color-grey">Previsto</span>
                                    </div>
                                    <div class="column width-12">
                                        <span class="progress-bar-label pull-left color-green"><?php echo 'R$ '.$VREAL;?></span>
                                        <span class="progress-bar-label pull-right color-grey"><?php $VPREVI0 = $dPAG['valortotalprevisto'];
                                                    //Adequa o valor para BD
                                                    if($VPREVI0 >= 1.000): //Se o valor inserido for maior que 1000 utiliza o algoritimo abaixo.
                                                        @$VPREVI = number_format($VPREVI0, 2, ',','.');
                                                    elseif($VPREVI0 < 1.000): //Se for inferior utiliza o algoritimo.
                                                        @$VPREVI = str_replace('.', ',', $VPREVI0);
                                                    endif;

                                                    if(!isset($VPREVI0)):
                                                        $VPREVI = '0,00';
                                                    endif;

                                                    echo 'R$ '.$VPREVI;?>
                                        </span>
                                    </div>
                                    <br>
                                        <?php
                                            @$vbr = $VREAL0 / $VPREVI0; //Valor Barra
                                            @$vbr = $vbr * 100;
                                            @$vbr = number_format($vbr, 0, ',', '.');

                                            if($vbr >= 100): //vmb Demanda do Produto
                                                $vbr = 100;
                                            elseif($vbr > 1 AND $vbr < 10):
                                                $vbr = 10;
                                            elseif($vbr > 10 AND $vbr < 20):
                                                $vbr = 20;
                                            elseif($vbr > 20 AND $vbr < 30):
                                                $vbr = 30;
                                            elseif($vbr > 30 AND $vbr < 40):
                                                $vbr = 40;
                                            elseif($vbr > 40 AND $vbr < 50):
                                                $vbr = 50;
                                            elseif($vbr > 50 AND $vbr < 60):
                                                $vbr = 60;
                                            elseif($vbr > 60 AND $vbr < 70):
                                                $vbr = 70;
                                            elseif($vbr > 70 AND $vbr < 80):
                                                $vbr = 80;
                                            elseif($vbr > 80 AND $vbr < 90):
                                                $vbr = 90;
                                            elseif($vbr > 90 AND $vbr < 100):
                                                $vbr = 100;
                                                //$filtro = array('05' => '10', '15' => '20','25' => '30','35' => '40','45' => '50','55' => '60','65' => '70','75' => '80','85' => '90','95' => '100');
                                                //$vbr = strTr($vbr, $filtro);
                                            elseif($vbr = 0 OR $vbr = ''):
                                                $vbr=0;
                                            endif;
                                        ?>
                                    <div class="progress-bar pill small pt-0 pb-0 bkg-grey-ultralight">
                                        <div class="bar bkg-green color-white percent-<?php echo $vbr; ?> horizon"
                                            data-animate-in="transX:-100%;duration:1000ms;easing:easeIn;">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Fim da Entrada financeira -->

                        <!-- Saída financeira -->
                        <div class="column width-3 v-align-middle">
                            <div class="box border-red bkg-white rounded small shadow">
                                <h3 class="title-medium weight-bold color-red-light">SAÍDA</h3>
                                    <?php

                                        //Buscar valor previsto.
                                        $bSF="SELECT sum(valorprevisto) as valortotalprevistosaida, sum(valorrealizado) as valortotalrealizadosaida FROM financeiro WHERE NOT (tipoderegistro!='DÉBITO') AND empresa='Emanuel' AND urldaempresa='igrejaemanuel' AND mes='$mesnumeroDASH' AND ano='$yta3' AND conta1!='272112-8' AND conta1!='0000' AND categoria!='FEP' AND tipodeoperacao!='Transferência'";
                                        $rSF=mysqli_query($con, $bSF);
                                            $dSF = mysqli_fetch_array($rSF);

                                        $VPREVISAI0 = $dSF['valortotalprevistosaida'];
                                        $VREALSAI0 = $dSF['valortotalrealizadosaida'];
                                        
                                            //Adequa o valor para BD
                                            if($VREALSAI0 > -1000): //Se o valor inserido for maior que 1000 utiliza o algoritimo abaixo.
                                                @$VREALSAI = number_format($VREALSAI0, 2, ',','.');
                                            elseif($VREALSAI0 <= -1000): //Se for inferior utiliza o algoritimo.
                                                @$VREALSAI = number_format($VREALSAI0, 2, ',', '.');
                                            endif;

                                            if($VPREVISAI0 > -1000): //Se o valor inserido for maior que 1000 utiliza o algoritimo abaixo.
                                                @$VPREVISAI = number_format($VPREVISAI0, 2, ',','.');
                                            elseif($VPREVISAI0 <= -1000): //Se for inferior utiliza o algoritimo.
                                                @$VPREVISAI = number_format($VPREVISAI0, 2, ',','.');
                                            endif;
                                    ?>
                                <div class="progress-bar-group pb-10">
                                    <div class="column width-12">
                                        <span class="progress-bar-label pull-left text-medium color-youtube">Realizado</span>
                                        <span class="progress-bar-label pull-right text-medium color-blue">Previsto</span>
                                    </div>
                                    <div class="column width-12">
                                        <span
                                            class="progress-bar-label pull-left color-youtube"><?php echo 'R$ '.$VREALSAI;?></span>
                                        <span class="progress-bar-label pull-right color-blue"><?php echo 'R$ '.$VPREVISAI;?>
                                        </span>
                                    </div>
                                    <br>
                                        <?php
                                            @$vbrsai = $VREALSAI0 / $VPREVISAI0; //Valor Barra
                                            @$vbrsai = $vbrsai * 100;
                                            @$vbrsai = number_format($vbrsai, 0, ',', '.');

                                            if($vbrsai >= 100): //vmb Demanda do Produto
                                                $vbrsai = 100;
                                            elseif($vbrsai > 1 AND $vbrsai < 10):
                                                $vbrsai = 10;
                                            elseif($vbrsai > 10 AND $vbrsai < 20):
                                                $vbrsai = 20;
                                            elseif($vbrsai > 20 AND $vbrsai < 30):
                                                $vbrsai = 30;
                                            elseif($vbrsai > 30 AND $vbrsai < 40):
                                                $vbrsai = 40;
                                            elseif($vbrsai > 40 AND $vbrsai < 50):
                                                $vbrsai = 50;
                                            elseif($vbrsai > 50 AND $vbrsai < 60):
                                                $vbrsai = 60;
                                            elseif($vbrsai > 60 AND $vbrsai < 70):
                                                $vbrsai = 70;
                                            elseif($vbrsai > 70 AND $vbrsai < 80):
                                                $vbrsai = 80;
                                            elseif($vbrsai > 80 AND $vbrsai < 90):
                                                $vbrsai = 90;
                                            elseif($vbrsai > 90 AND $vbrsai < 100):
                                                $vbrsai = 100;
                                                //$filtro = array('05' => '10', '15' => '20','25' => '30','35' => '40','45' => '50','55' => '60','65' => '70','75' => '80','85' => '90','95' => '100');
                                                //$vbrsai = strTr($vbrsai, $filtro);
                                            endif;
                                        ?>
                                    <div class="progress-bar pill small pt-0 pb-0 bkg-yellow">
                                        <div class="bar bkg-youtube color-white percent-<?php echo $vbrsai; ?> horizon"
                                            data-animate-in="transX:-100%;duration:1000ms;easing:easeIn;">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Fim da Saída financeira -->
                    </div>
                </div>
                <div class="section-block team-2 pt-0 bkg-grey-ultralight">
                    <div class="row full-width ">
                        <div class="column width-12 pt-10 pb-10">
                            <?
                                $saldoparaverbas= $VREAL0 - $VREALSAI0;
                                if($saldoparaverbas > 100):
                                    $smR=$saldoparaverbas; //Saldo do mês para cálculo das reservas $smN
                                    $missions=$smR * 0.1;
                                    $reservavariavel=$saldoparaverbas*0.2;
                                    $reservacash=$saldoparaverbas*0.1;
                                    $trafego=$saldoparaverbas*0.2;
                                    $ofertapastoral=$saldoparaverbas*0.05;
                                    $limpeza=$saldoparaverbas*0.03;
                                    $descontos = $missions + $reservavariavel + $reservacash + $trafego + $ofertapastoral + $limpeza;
                                    $reservabruta=$reservavariavel+$reservacash;
                                    $saldoreal = $saldoparaverbas - $descontos;

                                    $missions = number_format($missions, 2, ',', '.');
                                    $reservavariavel = number_format($reservavariavel, 2, ',', '.');
                                    $reservacash = number_format($reservacash, 2, ',', '.');
                                    $trafego = number_format($trafego, 2, ',', '.');
                                    $ofertapastoral = number_format($ofertapastoral, 2, ',', '.');
                                    $limpeza = number_format($limpeza, 2, ',', '.');
                                    $reservabruta = number_format($reservabruta, 2, ',', '.');
                                    $saldoreal = number_format($saldoreal, 2, ',', '.');
                            ?>
                                <span class="color-blue">Valores para: Reserva para <strong>limpeza (3%)</strong> R$ <? echo $limpeza?> / oferta de <strong>missões (10%)</strong> R$ <? echo $missions?> / <strong>Evangelismo (20%)</strong> R$ <? echo  $trafego?> (Impulsionar mídias). / <strong>Reserva R$ <? echo $reservabruta?></strong>; variável (20%) R$ <? echo $reservavariavel?> ; dinheiro (10%) R$ <? echo $reservacash?> / <strong>Oferta pastoral</strong> (5%) R$ <? echo $ofertapastoral?>. Este é o <strong>saldo real R$ <? echo $saldoreal?></strong> (Aproximado)</span>
                            <? else: ?>
                                <span class="color-blue">Devido a baixa arrecadação <strong>não há recursos para missões e reserva</strong>. Este é o <strong>saldo real R$ <? echo $saldodomes?></strong></span>
                            <? endif; ?>
                        </div>
                        <?php include "./_notificacaomensagem.php"; ?>
                        <div class="column width-12">
                                    <div class="column width-9 pb-20">
                                        <a href="financeiro?<?php echo "m=".$matriculausuario."&token=".$token."&ms=".$mesnumero."&type=c".$yta;?>" class="<?php echo $stylebuttonC.$shadowc;?>"><span class="color-white weight-bold text-medium text-uppercase">ENTRADA</span></a>
                                        <a href="financeiro?<?php echo "m=".$matriculausuario."&token=".$token."&ms=".$mesnumero."&type=d".$yta;?>" class="<?php echo $stylebuttonD.$shadowd;?>"><span class="color-white weight-bold text-medium text-uppercase">SAÍDA</span></a>

                                        <a data-content="inline" data-aux-classes="height-auto rounded" data-toolbar="" data-modal-mode data-modal-width="1140" data-modal-animation="scaleIn" data-lightbox-animation="fadeIn" href="#AdicionarMovimentacaoModal" class="lightbox-link right button rounded color-<?echo $colorPage;?> border-blue border-hover-grey">
                                            <?php echo $nomebuttonnovamovimentacao; ?>
                                        </a>
                                    </div>
                                    <div class="column width-3 pb-20 right">
                                        <a href="./financeiro?<?echo $linkSeguro.'backup='.$ano;?>" class="column full width button small bkg-blue bkg-hover-blue color-white color-hover-white hard-shadow shadow center">
                                            Baixar <strong>backup</strong> em .CSV
                                        </a>
                                    </div>
                            <div class="box small rounded rounded shadow border-blue bkg-white">
                                <div class="row">
                                    <div id="AdicionarMovimentacaoModal" class="pt-70 pb-50 hide <?php //echo $bkgmodal;?>">
                                        <div class="row">
                                            <div class="column width-12">
                                                <!-- Info -->
                                                <h3 class="mb-10 weight-bold center pb-20"><?php echo $nomebuttonnovamovimentacao; ?></h3>
                                                <!-- Info End -->
                                                <!-- Contact Form -->
                                                <div class="contact-form-container">
                                                    <div class="row">
                                                        <div class="column width-12">
                                                            <label class="text-large color-charcoal"><strong>Adicionar categoria.</strong> Será preciso atualizar esta página.</label>
                                                            <div class="field-wrapper">
                                                                <!-- <iframe class="<?php echo $bkgmodal;?>" src="_categoriaparafinanceiro.php<?php echo '?m='.$matriculausuario.'&token='.$token;?>" height="20px" scrolling="no"></iframe> -->
                                                                <form charset="UTF-8" class="form" action="" method="post" enctype="Multipart/form-data">
                                                                    <?php
                                                                        if(!isset($_GET['type']) OR !isset($_GET['t']) OR $_GET['type'] === 'c' OR $_GET['t'] === 'c'):
                                                                    ?>
                                                                        <input type='hidden' value="CRÉDITO" name='tipodecategoria'>
                                                                    <?php
                                                                        elseif($_GET['type'] === 'c' OR $_GET['t'] === 'c'):
                                                                    ?>
                                                                        <input type='hidden' value="DÉBITO" name='tipodecategoria'>
                                                                    <?php
                                                                        endif;
                                                                    ?>
                                                                        <div class="column width-11">
                                                                            <div class="field-wrapper">
                                                                                <input type="text" name="categoria" class="form-email form-element rounded medium" tabindex="035" placeholder="Adicionar categoria." required>
                                                                            </div>
                                                                        </div>
                                                                        
                                                                        <div class="column width-1">
                                                                            <button type="submit" value="" name='btn-categoria' class="form-submit button pill small bkg-green bkg-hover-green color-white color-hover-white hard-shadow"><span class="icon-plus color-white color-hover-white circled"></span></button>
                                                                        </div>
                                                                    </form>
                                                            </div>
                                                        </div>
                                                        
                                                        <div class="column width-12">
                                                            <div class="accordion rounded mb-50">
                                                                <ul>
                                                                    <li class="">
                                                                        <a href="#accordion-1-panel-1">
                                                                            <span class="text-medium">Adicionar Conta</span>
                                                                        </a>
                                                                        <div id="accordion-1-panel-1">
                                                                            <div class="accordion-content">
                                                                                <label class="text-large color-charcoal"><strong>Adicionar conta.</strong> Será preciso atualizar esta página.</label>
                                                                                <!-- <div class="field-wrapper">
                                                                                    <iframe class="<?php echo $bkgmodal;?>" src="_contaparafinanceiro.php<?php echo '?m='.$matriculausuario.'&token='.$token;?>" height="600px" scrolling="no"></iframe>
                                                                                </div> -->
                                                                                <form charset="UTF-8" class="form" action="" method="post" enctype="Multipart/form-data">
                                                                                    <div class="column width-3">
                                                                                        <label class="text-large left color-charcoal"><strong>Nome da conta</strong></label>
                                                                                        <div class="field-wrapper">
                                                                                            <input type="text" name="nomedaconta" class="form-email form-element rounded medium" tabindex="037" placeholder="Adicionar conta." required>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="column width-6">
                                                                                        <label class="text-large left color-charcoal"><strong>Tipo de conta</strong></label>
                                                                                        <div class="form-select form-element rounded medium">
                                                                                            <select name="tipodeconta" tabindex="038" class="form-aux" data-label="Project Budget">
                                                                                                <option>Corrente</option>
                                                                                                <option>Poupança</option>
                                                                                                <option>Corretora</option>
                                                                                                <option>CriptoAtivos</option>
                                                                                            </select>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="column width-3">
                                                                                        <label class="text-large left color-charcoal"><strong>Selecione o seu banco</strong></label>
                                                                                        <div class="form-select form-element rounded medium">
                                                                                            <select name="banco" tabindex="039" class="form-aux" data-label="Project Budget">
                                                                                                    <option>Carteira</option>
                                                                                                <?php
                                                                                                    //Buscar lista de bancos
                                                                                                    $bBancos = "SELECT cod, banco FROM bancos GROUP BY banco";
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
                                                                                        <label class="text-large left color-charcoal"><strong>Cód. Banco</strong></label>
                                                                                        <div class="field-wrapper">
                                                                                            <input type="text" name="codbanco" class="form-email form-element rounded medium" tabindex="040" placeholder="Nº de código do banco">
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="column width-8">
                                                                                        <label class="text-large left color-charcoal"><strong>Nome do Banco</strong></label>
                                                                                        <div class="field-wrapper">
                                                                                            <input type="text" name="nomebanco" class="form-email form-element rounded medium" tabindex="041" placeholder="Nome do banco">
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="column width-4">
                                                                                        <label class="text-large left color-charcoal"><strong>Agência</strong></label>
                                                                                        <div class="field-wrapper">
                                                                                            <input type="text" name="agencia" class="form-email form-element rounded medium" tabindex="042" placeholder="Nº da Agência" required>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="column width-2">
                                                                                        <label class="text-large left color-charcoal"><strong>Digíto</strong></label>
                                                                                        <div class="field-wrapper">
                                                                                            <input type="text" name="agenciaDig" class="form-email form-element rounded medium" tabindex="043" placeholder="Digito">
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="column width-4">
                                                                                        <label class="text-large left color-charcoal"><strong>Conta</strong></label>
                                                                                        <div class="field-wrapper">
                                                                                            <input type="text" name="conta" class="form-email form-element rounded medium" tabindex="044" placeholder="Nº da Conta" required>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="column width-2">
                                                                                        <label class="text-large left color-charcoal"><strong>Digíto</strong></label>
                                                                                        <div class="field-wrapper">
                                                                                            <input type="text" name="contaDig" class="form-email form-element rounded medium" tabindex="045" placeholder="Digito" >
                                                                                        </div>
                                                                                    </div>

                                                                                    <div class="column width-2">
                                                                                        <label class="text-large left color-charcoal"><strong>Operador</strong></label>
                                                                                        <div class="field-wrapper">
                                                                                            <input type="text" name="operador" class="form-email form-element rounded medium" tabindex="047" placeholder="Operador" >
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="column width-6">
                                                                                        <label class="text-large left color-charcoal"><strong>Titular</strong></label>
                                                                                        <div class="field-wrapper">
                                                                                            <input type="text" name="titular" class="form-email form-element rounded medium" tabindex="048" placeholder="Nome completo do titular da conta" required>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="column width-4">
                                                                                        <label class="text-large left color-charcoal"><strong>CPF</strong></label>
                                                                                        <div class="field-wrapper">
                                                                                            <input type="number" name="cpf" class="form-email form-element rounded medium" tabindex="049" placeholder="CPF">
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="column width-12">
                                                                                        <label class="text-large left color-charcoal"><strong>Mostrar valores</strong> aos membros?</label>
                                                                                        <div class="form-select form-element rounded medium">
                                                                                            <select name="viewconta" tabindex="038" class="form-aux" data-label="Project Budget">
                                                                                                <option value="Sim">Sim</option>
                                                                                                <option value="Não">Não</option>
                                                                                            </select>
                                                                                        </div>
                                                                                    </div>
                                                                                    
                                                                                    <div class="column width-12">
                                                                                        <button type="submit" value="" name='btn-conta' class="column width-12 form-submit button rounded small bkg-green bkg-hover-green color-white color-hover-white hard-shadow">
                                                                                            <span class="icon-plus color-white color-hover-white circled"></span> 
                                                                                            <span class="text-medium">Adicionar Conta</span>
                                                                                        </button>
                                                                                    </div>
                                                                                </form>
                                                                            </div>
                                                                        </div>
                                                                    </li>
                                                                </ul>
                                                            </div>
                                                        </div>

                                                        <hr class="pt-0 pb-0"/>

                                                        <form charset="UTF-8" class="form" action="" method="post" enctype="Multipart/form-data">
                                                            <div class="row">
                                                                <div class="column width-9">
                                                                    <label class="text-large color-charcoal">Anexar <strong>Comprovante</strong></label>
                                                                    <div class="field-wrapper">
                                                                        <input type="file" tabindex='050' multiple='multiple'  name="anexo" class="form-fname form-element rounded medium">
                                                                        <!-- accept="file/*" -->
                                                                    </div>
                                                                </div>
                                                                <div class="column width-3">
                                                                    <label
                                                                        class="text-large color-charcoal"><strong>Código</strong>
                                                                        da operação.</label>
                                                                    <div class="field-wrapper">
                                                                        <input type="text"
                                                                            name="codigodaoperacao"
                                                                            maxlength="150" tabindex='051'
                                                                            class="form-fname form-element rounded medium"
                                                                            placeholder="">
                                                                    </div>
                                                                </div>
                                                                <div class="column width-9">
                                                                    <label
                                                                        class="text-large color-charcoal"><strong>Descrição</strong></label>
                                                                    <div class="field-wrapper">
                                                                        <input type="text" name="descricao"
                                                                            maxlength="150"
                                                                            class="form-fname form-element rounded medium"
                                                                            placeholder="" tabindex='052'>
                                                                    </div>
                                                                </div>
                                                                <div class="column width-3">
                                                                    <label
                                                                        class="text-large color-charcoal"><strong>Valor</strong>
                                                                        (R$)</label>
                                                                    <div class="field-wrapper">
                                                                        <input type="text"
                                                                            name="valorprevisto"
                                                                            maxlength="150" tabindex='053'
                                                                            class="form-fname form-element rounded medium"
                                                                            placeholder="1.000,00">
                                                                    </div>
                                                                </div>
                                                                <div class="column width-6">
                                                                    <label class="text-large color-charcoal"><strong>Realizado</strong> em</label>
                                                                    <div class="field-wrapper">
                                                                        <input type="date" name="realizadoem" class="form-email form-element rounded medium" value="<?php if(!isset($_GET['ms'])): echo date('Y-m-d'); else: echo date('Y').'-'.$_GET['ms'].'-'.date('d'); endif; ?>" tabindex="054" required>
                                                                    </div>
                                                                </div>
                                                                <div class="column width-6">
                                                                    <label
                                                                        class="text-large color-charcoal"><strong>Repetir</strong> até</label>
                                                                    <div class="field-wrapper">
                                                                        <div class="column width-4">
                                                                            <div class="form-select form-element rounded medium">
                                                                                <select name="repetirmes" tabindex="055" class="form-aux" data-label="Project Budget">
                                                                                    <option selected="selected"></option>
                                                                                    <option>01</option>
                                                                                    <option>02</option>
                                                                                    <option>03</option>
                                                                                    <option>04</option>
                                                                                    <option>05</option>
                                                                                    <option>06</option>
                                                                                    <option>07</option>
                                                                                    <option>08</option>
                                                                                    <option>09</option>
                                                                                    <option>10</option>
                                                                                    <option>11</option>
                                                                                    <option>12</option>
                                                                                </select>
                                                                            </div>
                                                                        </div>
                                                                        <div class="column width-8">
                                                                            <div class="form-select form-element rounded medium">
                                                                                <select name="repetirano" tabindex="057" class="form-aux" data-label="Project Budget">
                                                                                    <option selected="selected"></option>
                                                                                    <option><?php echo date('Y'); ?></option>
                                                                                    <?php
                                                                                        $anorep=0;
                                                                                        for($anorepetir=0; $anorepetir < 10; $anorepetir++){
                                                                                            $anorep++;
                                                                                    ?>
                                                                                    <option><?php echo date(('Y'), strtotime(date('Y')."+ $anorep year"));?></option>
                                                                                    <?php
                                                                                        }
                                                                                    ?>
                                                                                </select>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="column width-6">
                                                                    <label class="text-large color-charcoal"><strong>Pago ao</strong></label>
                                                                    <div class="form-select form-element rounded medium">
                                                                        <select name="recebidode" tabindex="058" class="form-aux" data-label="Project Budget">
                                                                            <option selected="selected">Oferta em dinheiro</option>
                                                                            <option>Oferta em PIX</option>
                                                                            <option>Oferta em Cartão (Crédito)</option>
                                                                            <option>Oferta em Cartão (Débito)</option>
                                                                            <option>Oferta da célula Abraão</option>
                                                                            <option>Oferta da célula Efraim</option>
                                                                            <option>Retiro</option>
                                                                            <option>Parceiro Fiel - Constante</option>
                                                                            <option>Parceiro Fiel - Tito</option>
                                                                            <option>Parceiro Fiel - Coríntios</option>
                                                                            <option>Indefinido</option>
                                                                            <?php
                                                                                //Buscar consumidores cadastrados
                                                                                $bRD="SELECT nome, sobrenome FROM membros WHERE igreja='Emanuel' ORDER BY nome asc";
                                                                                $rRD=mysqli_query($con, $bRD);
                                                                                while($dRD=mysqli_fetch_array($rRD)):
                                                                            ?>
                                                                            <option>
                                                                                <?php $nomeM = base64_decode($dRD['nome']); $nomeM=base64_decode($nomeM); $sobrenomeM = base64_decode($dRD['sobrenome']); $sobrenomeM=base64_decode($sobrenomeM); echo $nomeM.' '.$sobrenomeM;?>
                                                                            </option>
                                                                            <?php
                                                                                endwhile;
                                                                            ?>
                                                                            <?php
                                                                                //Buscar consumidores cadastrados
                                                                                $bRD2="SELECT recebidode FROM financeiro WHERE empresa='Emanuel' GROUP BY recebidode ORDER BY recebidode ASC";
                                                                                $rRD2=mysqli_query($con, $bRD2);
                                                                                while($dRD2=mysqli_fetch_array($rRD2)):
                                                                            ?>
                                                                            <option>
                                                                                <?php $nomeM2 = base64_decode($dRD2['recebidode']); $nomeM2=base64_decode($nomeM2); echo $nomeM2;?>
                                                                            </option>
                                                                            <?php
                                                                                endwhile;
                                                                            ?>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                <div class="column width-6">
                                                                    <label class="text-large color-charcoal"><strong>Pago para</strong> (Adicionar)</label>
                                                                    <div class="field-wrapper">
                                                                        <input type="text" name="recebidode2" class="form-email form-element rounded medium" tabindex="059">
                                                                    </div>
                                                                </div>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="column width-4">
                                                                        <label
                                                                            class="text-large color-charcoal"><strong>Categoria</strong></label>
                                                                        <div
                                                                            class="form-select form-element rounded medium">
                                                                            <select name="categoria"
                                                                                tabindex="060" class="form-aux"
                                                                                data-label="Project Budget">
                                                                                <?php
                                                                                    if($_GET['t'] = 'c' OR !isset($_GET['t']) OR empty($_GET['t'])): $catT='CRÉDITO'; elseif($_GET['t'] = 'd'): $catT='DÉBITO'; endif;
                                                                                    
                                                                                    $exibircategorias="SELECT categoria FROM categoriasfinanceiro WHERE empresa='Emanuel' AND urldaempresa='igrejaemanuel' AND tipodecategoria='$catT' GROUP BY categoria ORDER BY categoria ASC";
                                                                                        $rc=mysqli_query($con, $exibircategorias);
                                                                                            while($dc=mysqli_fetch_array($rc)):
                                                                                        $categoriareg=$dc['categoria'];
                                                                                        $categArr=$categoriareg;
                                                                                ?>
                                                                                <option><?php echo $categoriareg;?></option>
                                                                                <? endwhile;?>
                                                                                <?
                                                                                    $viewcategoriafin="SELECT categoria FROM financeiro WHERE empresa='Emanuel' AND urldaempresa='igrejaemanuel' AND tipoderegistro='$catT' GROUP BY categoria ORDER BY categoria ASC";
                                                                                        $rcF=mysqli_query($con, $viewcategoriafin);
                                                                                            while($dcF=mysqli_fetch_array($rcF)):
                                                                                    $catF=$dcF['categoria'];
                                                                                ?>
                                                                                <? if(in_array($catF, $categArr)): else:?>
                                                                                <option><?php echo $catF;?></option>
                                                                                <?php
                                                                                    endif;
                                                                                    endwhile;
                                                                                ?>
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                    <div class="column width-4">
                                                                        <label
                                                                            class="text-large color-charcoal"><strong>Centro
                                                                                de custo</strong></label>
                                                                        <div class="field-wrapper">
                                                                            <input type="text"
                                                                                name="centrodecusto"
                                                                                class="form-email form-element rounded medium"
                                                                                tabindex="061">
                                                                        </div>
                                                                    </div>
                                                                    <div class="column width-4">
                                                                        <label
                                                                            class="text-large color-charcoal"><strong>Conta.</strong></label>
                                                                        <div
                                                                            class="form-select form-element rounded medium">
                                                                            <select name="conta" tabindex="063"
                                                                                class="form-aux"
                                                                                data-label="Project Budget">
                                                                                <?php
                                                                                    $bContas="SELECT id, nomedaconta, conta FROM contasfinanceiro WHERE empresa='Emanuel' AND urldaempresa='igrejaemanuel'";
                                                                                    $rFC=mysqli_query($con, $bContas);
                                                                                    while($dFC=mysqli_fetch_array($rFC)):
                                                                                ?>
                                                                                <option value="<?php echo $dFC['conta'];?>">
                                                                                    <?php echo $dFC['nomedaconta'].' ('.$dFC['conta'].')';?>
                                                                                </option>
                                                                                <?php
                                                                                    endwhile;
                                                                                ?>
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                    <div class="column width-4">
                                                                        <label class="text-large color-charcoal"><strong>Tipo de operação.</strong></label>
                                                                        <div class="form-select form-element rounded medium">
                                                                            <select name="tipodeoperacao" tabindex="064" class="form-aux" data-label="Project Budget">
                                                                                <?php
                                                                                    if($_GET['type'] == 'c' OR $_GET['t'] == 'c' OR !isset($_GET['type']) OR !isset($_GET['t'])):
                                                                                ?>
                                                                                
                                                                                <option selected="selected" value="">
                                                                                    Tipo desta operação
                                                                                </option>
                                                                                <option value="PIX">
                                                                                    PIX</option>
                                                                                <option value="Depósito">
                                                                                    Depósito</option>
                                                                                <option value="Transferência">
                                                                                    Transferência</option>
                                                                                <option value="Oferta">
                                                                                    Oferta
                                                                                </option>
                                                                                <option value="Dízimo">
                                                                                    Dízimo
                                                                                </option>
                                                                                <option value="Campanha">
                                                                                    Campanha</option>
                                                                                <option value="Evento">
                                                                                    Evento
                                                                                </option>
                                                                                <option value="Curso">
                                                                                    Curso
                                                                                </option>
                                                                                <option value="Seminário/Workshop">
                                                                                    Seminário/Workshop
                                                                                </option>
                                                                                <option value="Espaço Café">
                                                                                    Espaço Café
                                                                                </option>
                                                                                <option value="Almoço">
                                                                                    Almoço
                                                                                </option>
                                                                                <option value="Rifa">
                                                                                    Rifa
                                                                                </option>
                                                                                <option value="Feirinha">
                                                                                    Feirinha
                                                                                </option>
                                                                                <option value="Empresa social">
                                                                                    Empresa social
                                                                                </option>
                                                                                <option value="Venda de Ativo Patrimonial">
                                                                                    Venda de Ativo Patrimonial
                                                                                </option>
                                                                                <option value="Venda direta">
                                                                                    Venda direta
                                                                                </option>
                                                                                <option value="Cobrança de dívida">
                                                                                    Cobrança de dívida
                                                                                </option>
                                                                                <option value="Rendimento">
                                                                                    Rendimento
                                                                                </option>
                                                                                <option value="Diversos">
                                                                                    Diversos
                                                                                </option>
                                                                                
                                                                                <?php
                                                                                    else:
                                                                                ?>
                                                                                
                                                                                <option selected="selected" value="">
                                                                                    Tipo desta operação
                                                                                </option>
                                                                                <option value="Oferta">
                                                                                    Envio de Oferta
                                                                                </option>
                                                                                <option value="Doação">
                                                                                    Doação
                                                                                </option>
                                                                                <option value="Depósito">
                                                                                    Depósito
                                                                                </option>
                                                                                <option value="Transferência">
                                                                                    Transferência
                                                                                </option>
                                                                                <option value="Compra de Ativo Patrimonial">
                                                                                    Compra de Ativo Patrimonial
                                                                                </option>
                                                                                <option value="Compra de matéria prima">
                                                                                    Compra de matéria prima 
                                                                                </option>
                                                                                <option value="Cobrança de dívida">
                                                                                    Cobrança de dívida 
                                                                                </option>
                                                                                <option value="Custo direto">
                                                                                    Custo direto
                                                                                </option>
                                                                                <option value="Custo indireto">
                                                                                    Custo indireto
                                                                                </option>
                                                                                <option value="Rendimento">
                                                                                    Rendimento
                                                                                </option>
                                                                                <option value="Despesa">
                                                                                    Despesa
                                                                                </option>
                                                                                <option value="Outro">
                                                                                    Outro
                                                                                </option>
                                                                                
                                                                                <?php
                                                                                    endif;
                                                                                ?>
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                    <div class="column width-4">
                                                                        <label class="text-large color-charcoal">
                                                                            <strong>Conta de Origem.</strong> <span class="text-small">(Para Transferência)</span></label>
                                                                        <div
                                                                            class="form-select form-element rounded medium">
                                                                            <select name="contadeorigem"
                                                                                tabindex="065" class="form-aux"
                                                                                data-label="Project Budget">
                                                                                <option selected="selected">
                                                                                </option>
                                                                                <?php
                                                                                    $bContas="SELECT id, nomedaconta, conta FROM contasfinanceiro WHERE empresa='Emanuel' AND urldaempresa='igrejaemanuel'";
                                                                                    $rFC=mysqli_query($con, $bContas);
                                                                                    while($dFC=mysqli_fetch_array($rFC)):
                                                                                ?>
                                                                                <option value="<?php echo $dFC['conta'];?>"><?php echo $dFC['nomedaconta'].' ('.$dFC['conta'].')';?></option>
                                                                                <?php
                                                                                    endwhile;
                                                                                ?>
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                    <div class="column width-4">
                                                                        <label
                                                                            class="text-large color-charcoal"><strong>Conta de Destino.</strong> <span class="text-small">(Para Transferência)</span></label>
                                                                        <div
                                                                            class="form-select form-element rounded medium">
                                                                            <select name="contadedestino"
                                                                                tabindex="066" class="form-aux"
                                                                                data-label="Project Budget">
                                                                                <option selected="selected">
                                                                                </option>
                                                                                <?php
                                                                                    $bContas="SELECT id, nomedaconta, conta FROM contasfinanceiro WHERE empresa='Emanuel' AND urldaempresa='igrejaemanuel'";
                                                                                    $rFC=mysqli_query($con, $bContas);
                                                                                    while($dFC=mysqli_fetch_array($rFC)):
                                                                                ?>
                                                                                <option value="<?php echo $dFC['conta'];?>"><?php echo $dFC['nomedaconta'].' ('.$dFC['conta'].')';?></option>
                                                                                <?php
                                                                                    endwhile;
                                                                                ?>
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="column width-2 rounded small">
                                                                        <div class="column width-4">
                                                                            <input id="checkbox-2-pago" class="" name="confirmacaodepagamento" type="checkbox" checked='checked'>
                                                                        </div>
                                                                        <div class="column width-8 left">
                                                                            <span for="checkbox-2-pago" class="text-xlarge weight-bold color-<?php echo $colorPage;?>">Pago</span>
                                                                        </div>
                                                                    </div>
                                                                    <!-- <div class="column width-3">
                                                                        <div class="form-select form-element rounded medium">
                                                                            <select name="confirmacaodepagamento" tabindex="067" class="form-aux" data-label="">
                                                                                <option selected="selected" vale='PAGO'>PAGO</option>
                                                                                <option value=''>NÃO PAGO</option>
                                                                            </select>
                                                                        </div>
                                                                    </div> -->
                                                                    <div class="column width-10">
                                                                        <button type="submit" name='<?php echo $nomebuttonform;?>' class="column full-width button  medium bkg-green bkg-hover-green color-white color-hover-white">
                                                                            <span class="text-large weight-bold">
                                                                                Cadastrar
                                                                            </span>
                                                                        </button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                                <!-- Contact Form End -->
                                            </div>
                                        </div>
                                    </div>
                                    <div class="column width-12 pt-10">
                                        <?php
                                                $coloryeartable='bkg-blue bkg-hover-navy color-white color-hover-white shadow';
                                                $coloryeartable2='border-blue bkg-hover-blue color-charcoal color-hover-white';

                                            $byears="SELECT ano FROM financeiro GROUP BY ano ORDER BY ano asc";
                                            $ryears=mysqli_query($con, $byears); while($dyears=mysqli_fetch_array($ryears)):
                                                $yeartable=$dyears['ano'];

                                                if(empty($_GET['y']) AND $yeartable === $ano):
                                                    $cyt2=$coloryeartable;
                                                elseif(!empty($_GET['y']) AND $_GET['y'] === $yeartable):
                                                    $cyt2=$coloryeartable;
                                                else:
                                                    $cyt2=$coloryeartable2;
                                                endif;
                                        ?>
                                        <a href="financeiro?<?php echo "m=".$matriculausuario."&token=".$token."&y=".$yeartable?>" class="button medium rounded <?php echo $cyt2; ?>">
                                            <?php echo $yeartable; ?>
                                        </a>
                                        <?php
                                            $bnewyear="SELECT ano FROM financeiro WHERE ano='$ano'";
                                            $rnewyear=mysqli_query($con, $bnewyear); $nnewyear=mysqli_num_rows($rnewyear);
                                            if($nnewyear < 1):
                                        ?>
                                            <a href="financeiro?<?php echo "m=".$matriculausuario."&token=".$token."&y=".$ano?>" class="button medium rounded <?php echo $cyt2; ?>">
                                                <?php echo date('Y'); ?>
                                            </a>                              
                                        <?php endif; endwhile;?>
                                    </div>
                                    <div class="column width-12 pt-10">
                                        <?php
                                            if(!isset($_GET['type'])):
                                                $type = 'c';
                                            else:
                                                $type=$_GET['type'];
                                            endif;
                                            
                                            if($mesnumero === '01' AND !isset($_GET['type']) AND !isset($_GET['ms'])):
                                                $colormes01 = 'bkg-green bkg-hover-green color-white color-hover-white shadow';
                                            elseif($mesnumero !== '01' AND !isset($_GET['type']) AND !isset($_GET['ms'])):
                                                $colormes01 = 'border-blue color-green bkg-hover-green color-hover-white';
                                            elseif($_GET['ms'] === '01' AND $_GET['type'] === 'c'):
                                                $colormes01 = 'bkg-green bkg-hover-green color-white color-hover-white shadow';
                                            elseif($_GET['ms'] !== '01' AND $_GET['type'] === 'c'):
                                                $colormes01 = 'border-blue color-green bkg-hover-green color-hover-white';
                                            elseif($mesnumero === '01' AND !isset($_GET['ms']) AND $_GET['type'] === 'd'):
                                                $colormes01 = 'bkg-red bkg-hover-red color-white color-hover-white shadow';
                                            elseif($mesnumero === '01' AND $_GET['ms']==='01' AND $_GET['type'] === 'd'):
                                                $colormes01 = 'bkg-red bkg-hover-red color-white color-hover-white shadow';
                                            elseif($_GET['ms'] === '01' AND $_GET['type'] === 'd'):
                                                $colormes01 = 'bkg-red bkg-hover-red color-white color-hover-white shadow';
                                            elseif($mesnumero !== '01' AND $_GET['type'] === 'd'):
                                                $colormes01 = 'border-red color-red bkg-hover-red color-hover-white';
                                            elseif($_GET['ms'] !== '01' AND $_GET['type'] === 'd'):
                                                $colormes01 = 'border-red color-red bkg-hover-red color-hover-white';
                                            endif;                                    
                                        ?>
                                        <a href="financeiro?<?php echo "m=".$matriculausuario."&token=".$token."&ms=01&type=".$type."$yta";?>" class="button medium rounded <?php echo $colormes01;?>">
                                            Jan
                                        </a>
                                        <?php
                                            if($mesnumero === '02' AND !isset($_GET['type']) AND !isset($_GET['ms'])):
                                                $colormes01 = 'bkg-green bkg-hover-green color-white color-hover-white shadow';
                                            elseif($mesnumero !== '02' AND !isset($_GET['type']) AND !isset($_GET['ms'])):
                                                $colormes01 = 'border-blue color-green bkg-hover-green color-hover-white';
                                            elseif($_GET['ms'] === '02' AND $_GET['type'] === 'c'):
                                                $colormes01 = 'bkg-green bkg-hover-green color-white color-hover-white shadow';
                                            elseif($_GET['ms'] !== '02' AND $_GET['type'] === 'c'):
                                                $colormes01 = 'border-blue color-green bkg-hover-green color-hover-white';
                                            elseif($mesnumero === '02' AND !isset($_GET['ms']) AND $_GET['type'] === 'd'):
                                                $colormes01 = 'bkg-red bkg-hover-red color-white color-hover-white shadow';
                                            elseif($mesnumero === '02' AND $_GET['ms']==='02' AND $_GET['type'] === 'd'):
                                                $colormes01 = 'bkg-red bkg-hover-red color-white color-hover-white shadow';
                                            elseif($_GET['ms'] === '02' AND $_GET['type'] === 'd'):
                                                $colormes01 = 'bkg-red bkg-hover-red color-white color-hover-white shadow';
                                            elseif($mesnumero !== '02' AND $_GET['type'] === 'd'):
                                                $colormes01 = 'border-red color-red bkg-hover-red color-hover-white';
                                            elseif($_GET['ms'] !== '02' AND $_GET['type'] === 'd'):
                                                $colormes01 = 'border-red color-red bkg-hover-red color-hover-white';
                                            endif;
                                        ?>
                                        <a href="financeiro?<?php echo "m=".$matriculausuario."&token=".$token."&ms=02&type=".$type."$yta";?>" class="button medium rounded <?php echo $colormes01;?>">
                                            Fev
                                        </a>
                                        <?php
                                             if($mesnumero === '03' AND !isset($_GET['type']) AND !isset($_GET['ms'])):
                                                $colormes03 = 'bkg-green bkg-hover-green color-white color-hover-white shadow';
                                            elseif($mesnumero !== '03' AND !isset($_GET['type']) AND !isset($_GET['ms'])):
                                                $colormes03 = 'border-blue color-green bkg-hover-green color-hover-white';
                                            elseif($_GET['ms'] === '03' AND !isset($_GET['ms']) AND $_GET['type'] === 'c'):
                                                $colormes03 = 'bkg-green bkg-hover-green color-white color-hover-white shadow';
                                            elseif($_GET['ms'] === '03' AND $_GET['type'] === 'c'):
                                                $colormes03 = 'bkg-green bkg-hover-green color-white color-hover-white shadow';
                                            elseif($_GET['ms'] !== '03' AND $_GET['type'] === 'c'):
                                                $colormes03 = 'border-blue color-green bkg-hover-green color-hover-white';
                                            elseif($mesnumero === '03' AND !isset($_GET['ms']) AND $_GET['type'] === 'd'):
                                                $colormes03 = 'bkg-red bkg-hover-red color-white color-hover-white shadow';
                                            elseif($mesnumero === '03' AND $_GET['ms']==='03' AND $_GET['type'] === 'd'):
                                                $colormes03 = 'bkg-red bkg-hover-red color-white color-hover-white shadow';
                                            elseif($mesnumero === '03' AND $_GET['type'] === 'd'):
                                                $colormes03 = 'bkg-red bkg-hover-red color-white color-hover-white shadow';
                                            elseif($_GET['ms'] === '03' AND $_GET['type'] === 'd'):
                                                $colormes03 = 'bkg-red bkg-hover-red color-white color-hover-white shadow';
                                            elseif($mesnumero !== '03' AND $_GET['type'] === 'd'):
                                                $colormes03 = 'border-red color-red bkg-hover-red color-hover-white';
                                            elseif($_GET['ms'] !== '03' AND $_GET['type'] === 'd'):
                                                $colormes03 = 'border-red color-red bkg-hover-red color-hover-white';
                                            endif;
                                        ?>
                                        <a href="financeiro?<?php echo "m=".$matriculausuario."&token=".$token."&ms=03&type=".$type."$yta";?>" class="button medium rounded <?php echo $colormes03;?>">
                                            Mar
                                        </a>
                                        <?php
                                            if($mesnumero === '04' AND !isset($_GET['type']) AND !isset($_GET['ms'])):
                                                $colormes04 = 'bkg-green bkg-hover-green color-white color-hover-white shadow';
                                            elseif($mesnumero !== '04' AND !isset($_GET['type']) AND !isset($_GET['ms'])):
                                                $colormes04 = 'border-blue color-green bkg-hover-green color-hover-white';
                                            elseif($_GET['ms'] === '04' AND !isset($_GET['ms']) AND $_GET['type'] === 'c'):
                                                $colormes04 = 'bkg-green bkg-hover-green color-white color-hover-white shadow';
                                            elseif($_GET['ms'] === '04' AND $_GET['type'] === 'c'):
                                                $colormes04 = 'bkg-green bkg-hover-green color-white color-hover-white shadow';
                                            elseif($_GET['ms'] !== '04' AND $_GET['type'] === 'c'):
                                                $colormes04 = 'border-blue color-green bkg-hover-green color-hover-white';
                                            elseif($mesnumero === '04' AND !isset($_GET['ms']) AND $_GET['type'] === 'd'):
                                                $colormes04 = 'bkg-red bkg-hover-red color-white color-hover-white shadow';
                                            elseif($mesnumero === '04' AND $_GET['ms']==='04' AND $_GET['type'] === 'd'):
                                                $colormes01 = 'bkg-red bkg-hover-red color-white color-hover-white shadow';
                                            elseif($mesnumero === '04' AND $_GET['type'] === 'd'):
                                                $colormes04 = 'bkg-red bkg-hover-red color-white color-hover-white shadow';
                                            elseif($_GET['ms'] === '04' AND $_GET['type'] === 'd'):
                                                $colormes04 = 'bkg-red bkg-hover-red color-white color-hover-white shadow';
                                            elseif($mesnumero !== '04' AND $_GET['type'] === 'd'):
                                                $colormes04 = 'border-red color-red bkg-hover-red color-hover-white';
                                            elseif($_GET['ms'] !== '04' AND $_GET['type'] === 'd'):
                                                $colormes04 = 'border-red color-red bkg-hover-red color-hover-white';
                                            endif;
                                        ?>
                                        <a href="financeiro?<?php echo "m=".$matriculausuario."&token=".$token."&ms=04&type=".$type."$yta";?>" class="button medium rounded <?php echo $colormes04;?>">
                                            Abr
                                        </a>
                                        <?php
                                            if($mesnumero === '05' AND !isset($_GET['type']) AND !isset($_GET['ms'])):
                                                $colormes01 = 'bkg-green bkg-hover-green color-white color-hover-white shadow';
                                            elseif($mesnumero !== '05' AND !isset($_GET['type']) AND !isset($_GET['ms'])):
                                                $colormes01 = 'border-blue color-green bkg-hover-green color-hover-white';
                                            elseif($_GET['ms'] === '05' AND $_GET['type'] === 'c'):
                                                $colormes01 = 'bkg-green bkg-hover-green color-white color-hover-white shadow';
                                            elseif($_GET['ms'] !== '05' AND $_GET['type'] === 'c'):
                                                $colormes01 = 'border-blue color-green bkg-hover-green color-hover-white';
                                            elseif($mesnumero === '05' AND !isset($_GET['ms']) AND $_GET['type'] === 'd'):
                                                $colormes01 = 'bkg-red bkg-hover-red color-white color-hover-white shadow';
                                            elseif($mesnumero === '05' AND $_GET['ms']==='05' AND $_GET['type'] === 'd'):
                                                $colormes01 = 'bkg-red bkg-hover-red color-white color-hover-white shadow';
                                            elseif($_GET['ms'] === '05' AND $_GET['type'] === 'd'):
                                                $colormes01 = 'bkg-red bkg-hover-red color-white color-hover-white shadow';
                                            elseif($mesnumero !== '05' AND $_GET['type'] === 'd'):
                                                $colormes01 = 'border-red color-red bkg-hover-red color-hover-white';
                                            elseif($_GET['ms'] !== '05' AND $_GET['type'] === 'd'):
                                                $colormes01 = 'border-red color-red bkg-hover-red color-hover-white';
                                            endif;
                                        ?>
                                        <a href="financeiro?<?php echo "m=".$matriculausuario."&token=".$token."&ms=05&type=".$type."$yta";?>" class="button medium rounded <?php echo $colormes01;?>">
                                            Mai
                                        </a>
                                        <?php
                                            if($mesnumero === '06' AND !isset($_GET['type']) AND !isset($_GET['ms'])):
                                                $colormes01 = 'bkg-green bkg-hover-green color-white color-hover-white shadow';
                                            elseif($mesnumero !== '06' AND !isset($_GET['type']) AND !isset($_GET['ms'])):
                                                $colormes01 = 'border-blue color-green bkg-hover-green color-hover-white';
                                            elseif($_GET['ms'] === '06' AND $_GET['type'] === 'c'):
                                                $colormes01 = 'bkg-green bkg-hover-green color-white color-hover-white shadow';
                                            elseif($_GET['ms'] !== '06' AND $_GET['type'] === 'c'):
                                                $colormes01 = 'border-blue color-green bkg-hover-green color-hover-white';
                                            elseif($mesnumero === '06' AND !isset($_GET['ms']) AND $_GET['type'] === 'd'):
                                                $colormes01 = 'bkg-red bkg-hover-red color-white color-hover-white shadow';
                                            elseif($mesnumero === '06' AND $_GET['ms']==='06' AND $_GET['type'] === 'd'):
                                                $colormes01 = 'bkg-red bkg-hover-red color-white color-hover-white shadow';
                                            elseif($_GET['ms'] === '06' AND $_GET['type'] === 'd'):
                                                $colormes01 = 'bkg-red bkg-hover-red color-white color-hover-white shadow';
                                            elseif($mesnumero !== '06' AND $_GET['type'] === 'd'):
                                                $colormes01 = 'border-red color-red bkg-hover-red color-hover-white';
                                            elseif($_GET['ms'] !== '06' AND $_GET['type'] === 'd'):
                                                $colormes01 = 'border-red color-red bkg-hover-red color-hover-white';
                                            endif;
                                        ?>
                                        <a href="financeiro?<?php echo "m=".$matriculausuario."&token=".$token."&ms=06&type=".$type."$yta";?>" class="button medium rounded <?php echo $colormes01;?>">
                                            Jun
                                        </a>
                                        <?php
                                            if($mesnumero === '07' AND !isset($_GET['type']) AND !isset($_GET['ms'])):
                                                $colormes01 = 'bkg-green bkg-hover-green color-white color-hover-white shadow';
                                            elseif($mesnumero !== '07' AND !isset($_GET['type']) AND !isset($_GET['ms'])):
                                                $colormes01 = 'border-blue color-green bkg-hover-green color-hover-white';
                                            elseif($_GET['ms'] === '07' AND $_GET['type'] === 'c'):
                                                $colormes01 = 'bkg-green bkg-hover-green color-white color-hover-white shadow';
                                            elseif($_GET['ms'] !== '07' AND $_GET['type'] === 'c'):
                                                $colormes01 = 'border-blue color-green bkg-hover-green color-hover-white';
                                            elseif($mesnumero === '07' AND !isset($_GET['ms']) AND $_GET['type'] === 'd'):
                                                $colormes01 = 'bkg-red bkg-hover-red color-white color-hover-white shadow';
                                            elseif($mesnumero === '07' AND $_GET['ms']==='07' AND $_GET['type'] === 'd'):
                                                $colormes01 = 'bkg-red bkg-hover-red color-white color-hover-white shadow';
                                            elseif($_GET['ms'] === '07' AND $_GET['type'] === 'd'):
                                                $colormes01 = 'bkg-red bkg-hover-red color-white color-hover-white shadow';
                                            elseif($mesnumero !== '07' AND $_GET['type'] === 'd'):
                                                $colormes01 = 'border-red color-red bkg-hover-red color-hover-white';
                                            elseif($_GET['ms'] !== '07' AND $_GET['type'] === 'd'):
                                                $colormes01 = 'border-red color-red bkg-hover-red color-hover-white';
                                            endif;
                                        ?>
                                        <a href="financeiro?<?php echo "m=".$matriculausuario."&token=".$token."&ms=07&type=".$type."$yta";?>" class="button medium rounded <?php echo $colormes01;?>">
                                            Jul
                                        </a>
                                        <?php
                                            if($mesnumero === '08' AND !isset($_GET['type']) AND !isset($_GET['ms'])):
                                                $colormes01 = 'bkg-green bkg-hover-green color-white color-hover-white shadow';
                                            elseif($mesnumero !== '08' AND !isset($_GET['type']) AND !isset($_GET['ms'])):
                                                $colormes01 = 'border-blue color-green bkg-hover-green color-hover-white';
                                            elseif($_GET['ms'] === '08' AND $_GET['type'] === 'c'):
                                                $colormes01 = 'bkg-green bkg-hover-green color-white color-hover-white shadow';
                                            elseif($_GET['ms'] !== '08' AND $_GET['type'] === 'c'):
                                                $colormes01 = 'border-blue color-green bkg-hover-green color-hover-white';
                                            elseif($mesnumero === '08' AND !isset($_GET['ms']) AND $_GET['type'] === 'd'):
                                                $colormes01 = 'bkg-red bkg-hover-red color-white color-hover-white shadow';
                                            elseif($mesnumero === '08' AND $_GET['ms']==='08' AND $_GET['type'] === 'd'):
                                                $colormes01 = 'bkg-red bkg-hover-red color-white color-hover-white shadow';
                                            elseif($_GET['ms'] === '08' AND $_GET['type'] === 'd'):
                                                $colormes01 = 'bkg-red bkg-hover-red color-white color-hover-white shadow';
                                            elseif($mesnumero !== '08' AND $_GET['type'] === 'd'):
                                                $colormes01 = 'border-red color-red bkg-hover-red color-hover-white';
                                            elseif($_GET['ms'] !== '08' AND $_GET['type'] === 'd'):
                                                $colormes01 = 'border-red color-red bkg-hover-red color-hover-white';
                                            endif;
                                        ?>
                                        <a href="financeiro?<?php echo "m=".$matriculausuario."&token=".$token."&ms=08&type=".$type."$yta";?>" class="button medium rounded <?php echo $colormes01;?>">
                                            Ago
                                        </a>
                                        <?php
                                            if($mesnumero === '09' AND !isset($_GET['type']) AND !isset($_GET['ms'])):
                                                $colormes01 = 'bkg-green bkg-hover-green color-white color-hover-white shadow';
                                            elseif($mesnumero !== '09' AND !isset($_GET['type']) AND !isset($_GET['ms'])):
                                                $colormes01 = 'border-blue color-green bkg-hover-green color-hover-white';
                                            elseif($_GET['ms'] === '09' AND $_GET['type'] === 'c'):
                                                $colormes01 = 'bkg-green bkg-hover-green color-white color-hover-white shadow';
                                            elseif($_GET['ms'] !== '09' AND $_GET['type'] === 'c'):
                                                $colormes01 = 'border-blue color-green bkg-hover-green color-hover-white';
                                            elseif($mesnumero === '09' AND !isset($_GET['ms']) AND $_GET['type'] === 'd'):
                                                $colormes01 = 'bkg-red bkg-hover-red color-white color-hover-white shadow';
                                            elseif($mesnumero === '09' AND $_GET['ms']==='09' AND $_GET['type'] === 'd'):
                                                $colormes01 = 'bkg-red bkg-hover-red color-white color-hover-white shadow';
                                            elseif($_GET['ms'] === '09' AND $_GET['type'] === 'd'):
                                                $colormes01 = 'bkg-red bkg-hover-red color-white color-hover-white shadow';
                                            elseif($mesnumero !== '09' AND $_GET['type'] === 'd'):
                                                $colormes01 = 'border-red color-red bkg-hover-red color-hover-white';
                                            elseif($_GET['ms'] !== '09' AND $_GET['type'] === 'd'):
                                                $colormes01 = 'border-red color-red bkg-hover-red color-hover-white';
                                            endif;
                                        ?>
                                        <a href="financeiro?<?php echo "m=".$matriculausuario."&token=".$token."&ms=09&type=".$type."$yta";?>" class="button medium rounded <?php echo $colormes01;?>">
                                            Set
                                        </a>
                                        <?php
                                            if($mesnumero === '10' AND !isset($_GET['type']) AND !isset($_GET['ms'])):
                                                $colormes01 = 'bkg-green bkg-hover-green color-white color-hover-white shadow';
                                            elseif($mesnumero !== '10' AND !isset($_GET['type']) AND !isset($_GET['ms'])):
                                                $colormes01 = 'border-blue color-green bkg-hover-green color-hover-white';
                                            elseif($_GET['ms'] === '10' AND $_GET['type'] === 'c'):
                                                $colormes01 = 'bkg-green bkg-hover-green color-white color-hover-white shadow';
                                            elseif($_GET['ms'] !== '10' AND $_GET['type'] === 'c'):
                                                $colormes01 = 'border-blue color-green bkg-hover-green color-hover-white';
                                            elseif($mesnumero === '10' AND !isset($_GET['ms']) AND $_GET['type'] === 'd'):
                                                $colormes01 = 'bkg-red bkg-hover-red color-white color-hover-white shadow';
                                            elseif($mesnumero === '10' AND $_GET['ms']==='10' AND $_GET['type'] === 'd'):
                                                $colormes01 = 'bkg-red bkg-hover-red color-white color-hover-white shadow';
                                            elseif($_GET['ms'] === '10' AND $_GET['type'] === 'd'):
                                                $colormes01 = 'bkg-red bkg-hover-red color-white color-hover-white shadow';
                                            elseif($mesnumero !== '10' AND $_GET['type'] === 'd'):
                                                $colormes01 = 'border-red color-red bkg-hover-red color-hover-white';
                                            elseif($_GET['ms'] !== '10' AND $_GET['type'] === 'd'):
                                                $colormes01 = 'border-red color-red bkg-hover-red color-hover-white';
                                            endif;
                                        ?>
                                        <a href="financeiro?<?php echo "m=".$matriculausuario."&token=".$token."&ms=10&type=".$type."$yta";?>" class="button medium rounded <?php echo $colormes01;?>">
                                            Out
                                        </a>
                                        <?php
                                            if($mesnumero === '11' AND !isset($_GET['type']) AND !isset($_GET['ms'])):
                                                $colormes01 = 'bkg-green bkg-hover-green color-white color-hover-white shadow';
                                            elseif($mesnumero !== '11' AND !isset($_GET['type']) AND !isset($_GET['ms'])):
                                                $colormes01 = 'border-blue color-green bkg-hover-green color-hover-white';
                                            elseif($_GET['ms'] === '11' AND $_GET['type'] === 'c'):
                                                $colormes01 = 'bkg-green bkg-hover-green color-white color-hover-white shadow';
                                            elseif($_GET['ms'] !== '11' AND $_GET['type'] === 'c'):
                                                $colormes01 = 'border-blue color-green bkg-hover-green color-hover-white';
                                            elseif($mesnumero === '11' AND !isset($_GET['ms']) AND $_GET['type'] === 'd'):
                                                $colormes01 = 'bkg-red bkg-hover-red color-white color-hover-white shadow';
                                            elseif($mesnumero === '11' AND $_GET['ms']==='11' AND $_GET['type'] === 'd'):
                                                $colormes01 = 'bkg-red bkg-hover-red color-white color-hover-white shadow';
                                            elseif($_GET['ms'] === '11' AND $_GET['type'] === 'd'):
                                                $colormes01 = 'bkg-red bkg-hover-red color-white color-hover-white shadow';
                                            elseif($mesnumero !== '11' AND $_GET['type'] === 'd'):
                                                $colormes01 = 'border-red color-red bkg-hover-red color-hover-white';
                                            elseif($_GET['ms'] !== '11' AND $_GET['type'] === 'd'):
                                                $colormes01 = 'border-red color-red bkg-hover-red color-hover-white';
                                            endif;
                                        ?>
                                        <a href="financeiro?<?php echo "m=".$matriculausuario."&token=".$token."&ms=11&type=".$type."$yta";?>" class="button medium rounded <?php echo $colormes01;?>">
                                            Nov
                                        </a>
                                        <?php
                                            if($mesnumero === '12' AND !isset($_GET['type']) AND !isset($_GET['ms'])):
                                                $colormes01 = 'bkg-green bkg-hover-green color-white color-hover-white shadow';
                                            elseif($mesnumero !== '12' AND !isset($_GET['type']) AND !isset($_GET['ms'])):
                                                $colormes01 = 'border-blue color-green bkg-hover-green color-hover-white';
                                            elseif($_GET['ms'] === '12' AND $_GET['type'] === 'c'):
                                                $colormes01 = 'bkg-green bkg-hover-green color-white color-hover-white shadow';
                                            elseif($_GET['ms'] !== '12' AND $_GET['type'] === 'c'):
                                                $colormes01 = 'border-blue color-green bkg-hover-green color-hover-white';
                                            elseif($mesnumero === '12' AND !isset($_GET['ms']) AND $_GET['type'] === 'd'):
                                                $colormes01 = 'bkg-red bkg-hover-red color-white color-hover-white shadow';
                                            elseif($mesnumero === '12' AND $_GET['ms']==='12' AND $_GET['type'] === 'd'):
                                                $colormes01 = 'bkg-red bkg-hover-red color-white color-hover-white shadow';
                                            elseif($_GET['ms'] === '12' AND $_GET['type'] === 'd'):
                                                $colormes01 = 'bkg-red bkg-hover-red color-white color-hover-white shadow';
                                            elseif($mesnumero !== '12' AND $_GET['type'] === 'd'):
                                                $colormes01 = 'border-red color-red bkg-hover-red color-hover-white';
                                            elseif($_GET['ms'] !== '12' AND $_GET['type'] === 'd'):
                                                $colormes01 = 'border-red color-red bkg-hover-red color-hover-white';
                                            endif;
                                        ?>
                                        <a href="financeiro?<?php echo "m=".$matriculausuario."&token=".$token."&ms=12&type=".$type."$yta";?>" class="button medium rounded <?php echo $colormes01;?>">
                                            Dez
                                        </a>
                                    </div>
                                    <div class="column width-12 pt-20">    
                                                <?php
                                                    if(!isset($_GET['type'])):
                                                        $colortable='green';
                                                    elseif(isset($_GET['type']) AND $_GET['type'] === 'c'):
                                                        $colortable='green';
                                                    elseif(isset($_GET['type']) AND $_GET['type'] === 'd'):
                                                        $colortable='red';
                                                    endif;
                                                ?>
                                        <table class="table rounded border-<?php echo $colortable;?> style-2 striped small">
                                            <thead>
                                                <tr class="bkg-<?php echo $colortable;?>-light color-white">
                                                    <th>
                                                        <span class="text-medium"><center>Nº</center></span>
                                                    </th>
                                                    <th>
                                                        <span class="text-medium">Descrição</span>
                                                    </th>
                                                    <th>
                                                        <span class="text-medium"><center>Data</center></span>
                                                    </th>
                                                    <th>
                                                        <span class="text-medium"><center>Categoria</center></span>
                                                    </th>
                                                    <th>
                                                        <span class="text-medium"><center>Valor</center></span>
                                                    </th>
                                                    <th></th>
                                                    <th></th>
                                                    <th></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                    if(!isset($_GET['type'])):
                                                        $type='CRÉDITO';
                                                    elseif(isset($_GET['type']) AND $_GET['type'] === 'c'):
                                                        $type='CRÉDITO';
                                                    elseif(isset($_GET['type']) AND $_GET['type'] === 'd'):
                                                        $type='DÉBITO';
                                                    endif;
                                                    
                                                    if(!isset($_GET['ms'])):
                                                        $mesget=$mesnumero;
                                                    elseif(isset($_GET['ms']) AND !empty($_GET['ms'])):
                                                        $mesget=$_GET['ms'];
                                                    endif;
                                                    
                                                    //Buscar parceiros com base na pesquisa realizada.
                                                    $bpg="SELECT id, descricao, dataprevista, datarealizada, categoria, valorprevisto, valorrealizado, codigodaoperacao, anexo, tipodeoperacao FROM financeiro WHERE codigodoparceiro='0001' AND tipoderegistro='$type' AND ano='$yeartableactive' AND mes='$mesget' GROUP BY codigodaoperacao ORDER BY datarealizada DESC, id DESC, dataprevista DESC";
                                                    $rbpg=mysqli_query($con, $bpg);
                                                        $linha='0';
                                                    while($dbpg=mysqli_fetch_array($rbpg)):
                                                        $linha++;
                                                ?>
                                                <tr class="">
                                                    <td class="center color-charcoal text-medium"
                                                        width="3%" valign="medium">
                                                        <span class="text-medium">
                                                            <?php echo $linha;?>
                                                        </span>
                                                    </td>
                                                    <td class="left color-charcoal text-medium"
                                                        width="36%" valign="medium">
                                                        <span class="text-medium">
                                                            <?php $desc1e= base64_decode($dbpg['descricao']); echo base64_decode($desc1e); if($dbpg['tipodeoperacao'] == 'Transferência'): echo " (Transferência)"; endif; ?>
                                                        </span>
                                                    </td>
                                                    <td class="center color-charcoal text-medium"
                                                        width="15%" valign="medium">
                                                        <span class="text-medium">
                                                            <?php
                                                            if(empty($dbpg['datarealizada'])): $dPrev = $dbpg['dataprevista']; echo date(('d/m/Y'), strtotime($dPrev)); else: $dPrev = $dbpg['datarealizada']; echo date(('d/m/Y'), strtotime($dPrev)); endif;?>
                                                        </span>
                                                    </td>
                                                    <td class="center color-charcoal text-medium"
                                                        width="12%" valign="medium">
                                                        <span class="text-medium">
                                                            <?php echo $dbpg['categoria'];?>
                                                        </span>
                                                    </td>
                                                    <td class="center color-charcoal text-medium"
                                                        width="13%" valign="medium"><span class="text-medium">
                                                        R$ <?php 
                                                                if(!empty($dbpg['valorrealizado'])):
                                                                    $valorPOR = $dbpg['valorrealizado'];
                                                                elseif(empty($dbpg['valorrealizado'])):
                                                                    $valorPOR = $dbpg['valorprevisto'];
                                                                endif;
                                                                    //Adequa o valor para BD
                                                                    if($valorPOR >= 1.000): //Se o valor inserido for maior que 1000 utiliza o algoritimo abaixo.
                                                                        @$valorPOR = number_format($valorPOR, 2, ',','.');
                                                                    elseif($valorPOR < 1.000): //Se for inferior utiliza o algoritimo.
                                                                        @$valorPOR = str_replace('.', ',', $valorPOR);
                                                                    endif;
                                                                    echo $valorPOR;?>
                                                        </span>
                                                    </td>
                                                    <td class="center text-small" width="3%" valign="medium">
                                                        <?php
                                                            if(!empty($dbpg['anexo'])):
                                                        ?>
                                                            <a href="<?php echo $dbpg['anexo'];?>" target="_blank">
                                                                <span class="icon-attachment small color-<?php echo $colortable;?>"></span>
                                                            </a>
                                                        <?php
                                                            endif;
                                                        ?>
                                                    </td>
                                                    <td class="center text-small" width="3%" valign="medium">
                                                        <span class="text-medium">
                                                            <!-- <a class="lightbox-link color-<?php echo $colortable;?> color-hover-<?php echo $colortable;?>" data-content="inline" data-aux-classes="tml-form-modal tml-exit-light height-auto with-header rounded" data-toolbar="" data-modal-mode data-modal-width="1140" data-modal-animation="scaleIn" data-lightbox-animation="fadeIn" href="#modal<?//php $COp=$dbpg['codigodaoperacao']; echo  $COp;?>"> -->
                                                            <!-- <a class="color-<?php echo $colortable;?> color-hover-<?php echo $colortable;?>" href="financeiro<?php echo $linkSeguro; $COp=$dbpg['codigodaoperacao']; echo  'lanedit='.$COp.'&ref='.$dbpg['id'].'&type='.$_GET['type'].'&t='.$_GET['type'].'&ms='.$_GET['ms'].$yta;?>">
                                                                <span class=""></span> <span class="icon-cw medium"></span>
                                                            </a> -->
                                                            <a data-content="inline" data-aux-classes="tml-newsletter-modal tml-exit-light height-auto rounded" data-toolbar="" data-modal-mode data-modal-width="1140" data-modal-animation="scaleIn" data-lightbox-animation="fadeIn" href="#modaleditarlanca<?php echo $COp;?>" class="lightbox-link"><span class="color-<?php echo $colortable;?> color-hover-<?php echo $colortable;?> icon-cw medium"></span></a>
                                                        </span>
                                                    </td>
                                                    <td class="center text-small" width="3%" valign="medium">
                                                        <?php
                                                            if(empty($dbpg['valorrealizado'])):
                                                        ?>
                                                            <a href="financeiro?<?php echo $linkSeguro.'type='.$_GET['type'].'&t='.$_GET['type'].$yta.'&ms='.$_GET['ms'].'&pay='.$dbpg['codigodaoperacao'].'&ref='.$dbpg['id'];?>" tabindex="015" class="color-charcoal color-hover-charcoal hard-shadow">
                                                                Pagar
                                                            </a>
                                                        <?php
                                                            endif;
                                                        ?>
                                                    </td>
                                                </tr>
                                                <?php
                                                    endwhile;
                                                ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                    <div class="column width-9 pb-20">
                                        <a href="financeiro?<?php echo "m=".$matriculausuario."&token=".$token."&ms=".$mesnumero."&type=c".$yta;?>" class="<?php echo $stylebuttonC.$shadowc;?>"><span class="color-white weight-bold text-medium text-uppercase">ENTRADA</span></a>
                                        <a href="financeiro?<?php echo "m=".$matriculausuario."&token=".$token."&ms=".$mesnumero."&type=d".$yta;?>" class="<?php echo $stylebuttonD.$shadowd;?>"><span class="color-white weight-bold text-medium text-uppercase">SAÍDA</span></a>

                                        <a data-content="inline" data-aux-classes="height-auto rounded" data-toolbar="" data-modal-mode data-modal-width="1140" data-modal-animation="scaleIn" data-lightbox-animation="fadeIn" href="#AdicionarMovimentacaoModal" class="lightbox-link right button rounded color-<?echo $colorPage;?> border-blue border-hover-grey">
                                            <?php echo $nomebuttonnovamovimentacao; ?>
                                        </a>
                                    </div>
                                    <div class="column width-3 pb-20 right">
                                        <a href="./financeiro?<?echo $linkSeguro.'backup='.$ano;?>" class="column full width button small bkg-blue bkg-hover-blue color-white color-hover-white hard-shadow shadow center">
                                            Baixar <strong>backup</strong> em .CSV
                                        </a>
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
    
        <!-- Modal para solicitação de saque -->
        <div id="solicitarsaque" class="section-block pt-0 pb-30 hide">
            <!-- Intro Title Section 2 -->
            <div class="section-block intro-title-2-1 small bkg-orange-light">
                <div class="media-overlay bkg-black opacity-03"></div>
                <div class="row">
                    <div class="column width-12">
                        <div class="title-container">
                            <div class="title-container-inner center color-white">
                                <span class="center text-small weight-bold">SALDO DISPONIVEL</span>
                                <h1 class="title-medium weight-bold mb-0">
                                    <?php
                                        $SaldoDisponivelParaSaquePOR = $SaldoDisponivelParaSaque; 
                                        //Adequa o valor para BD
                                        if($SaldoDisponivelParaSaquePOR >= 1000): //Se o valor inserido for maior que 1000 utiliza o algoritimo abaixo.
                                            @$SaldoDisponivelParaSaquePOR = number_format($SaldoDisponivelParaSaquePOR, 2, ',','.');
                                        elseif($SaldoDisponivelParaSaquePOR < 1000): //Se for inferior utiliza o algoritimo.
                                            @$SaldoDisponivelParaSaquePOR = str_replace('.', ',', $SaldoDisponivelParaSaquePOR);
                                        endif;
                                        echo $SaldoDisponivelParaSaquePOR;
                                    ?>
                                </h1>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Intro Title Section 2 End --->

            <!-- Signup -->
            <div class="section-block pt-60 pb-0">
                <div class="row">
                    <div class="column width-10 offset-1 center">
                        <form class="form" action="" method="post" charset="UTF-8">
                            <!-- hiddens -->
                            <input type='hidden' name='saldodisponivelnodia'
                                value='<?php echo $SaldoDisponivelParaSaque;?>' />

                            <div class="column width-12">
                                <label class="text-large color-charcoal"><strong>Valor a ser transferido</strong>
                                    R$</label>
                                <div class="field-wrapper">
                                    <input type="text" name="valoratransferir"
                                        class="form-fname form-element rounded xlarge" placeholder="1.000,35"
                                        tabindex="068" required>
                                </div>
                            </div>
                            <div class="column width-12 left pt-10">
                                <?php
                                    //Buscar nome do produto
                                    $bContasE = "SELECT codigodaconta, conta, agencia, banco, nomedaconta, titular, cpf FROM contasfinanceiro WHERE tipodeconta='CORRENTE'";
                                    $rCE=mysqli_query($con, $bContasE);
                                        $nContasE = mysqli_num_rows($rCE);

                                        if($nContasE > 0):
                                ?>
                                <div class="column width-12 left bkg-gradient-purple-haze">
                                    <h4 class="pt-10 pb-0 color-white weight-bold">
                                        Escolha sua conta
                                    </h4>
                                </div>
                                <?php
                                    while($dCE=mysqli_fetch_array($rCE)):
                                ?>
                                <div class="column width-2 pt-0 pb-0">
                                    <input class="" type="radio" id="<?php echo $dCE['codigodaconta'];?>" class=""
                                        value="<?php echo $dCE['codigodaconta'].'~'.$dCE['banco'].'~'.$dCE['agencia'].'~'.$dCE['conta'].'~'.$dCE['operador'].'~'.$dCE['titular'].'~'.$dCE['cpf'].'~'.$dCE['tipodeconta'];?>"
                                        name="bancoparatransferencia">
                                </div>
                                <div class="column width-10 pt-0 pb-0 left">
                                    <label class="text-xlarge color-charcoal" for="<?php echo $dCE['codigodaconta'];?>"
                                        class="radio-label">
                                        <?php
                                            echo $dCE['titular'] .'br>';
                                            echo '(Agência: '.$dCE['agencia'].' - Conta: '.$dCE['conta'].')<br>';
                                            echo $dCE['banco'].' - '.$dCE['tipodeconta'];
                                        ?>
                                    </label>
                                </div>
                                <?php
                                    endwhile;
                                ?>

                                <div class="divider center mt-40 mb-50">
                                    <span class="bkg-white pt-20 color-orange text-medium">
                                        <strong>ou</strong>.
                                    </span>
                                </div>

                                <div class="accordion rounded mb-50">
                                    <ul>
                                        <li class="">
                                            <a href="#accordion-1-panel-1">
                                                <span class="text-medium">
                                                    Cadastrar nova conta para saque
                                                </span>
                                            </a>
                                            <div id="accordion-1-panel-1">
                                                <div class="accordion-content">
                                                    <div class="column width-3">
                                                        <label
                                                            class="text-large color-charcoal"><strong>Agência</strong></label>
                                                    </div>
                                                    <div class="column width-6">
                                                        <div class="field-wrapper">
                                                            <input type="number" name="novaAgencia"
                                                                class="form-fname form-element rounded medium"
                                                                placeholder="agência" tabindex="069">
                                                        </div>
                                                    </div>
                                                    <div class="column width-3">
                                                        <div class="field-wrapper">
                                                            <input type="number" name="novaAgenciaDig"
                                                                class="form-fname form-element rounded medium"
                                                                placeholder="" tabindex="070">
                                                        </div>
                                                    </div>

                                                    <div class="column width-3">
                                                        <label
                                                            class="text-large color-charcoal"><strong>Conta</strong></label>
                                                    </div>
                                                    <div class="column width-6">
                                                        <div class="field-wrapper">
                                                            <input type="number" name="novaConta"
                                                                class="form-fname form-element rounded medium"
                                                                placeholder="agência" tabindex="071">
                                                        </div>
                                                    </div>
                                                    <div class="column width-3">
                                                        <div class="field-wrapper">
                                                            <input type="number" name="novaContaDig"
                                                                class="form-fname form-element rounded medium"
                                                                placeholder="" tabindex="072">
                                                        </div>
                                                    </div>

                                                    <div class="column full-width">
                                                        <label class="text-large left color-charcoal"><strong>Selecione
                                                                o seu banco</strong></label>
                                                        <div class="form-select form-element rounded medium">
                                                            <select name="banco" tabindex="073" class="form-aux"
                                                                data-label="Project Budget">
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

                                                    <div class="column width-12">
                                                        <label
                                                            class="text-large left color-charcoal"><strong>Titular</strong></label>
                                                        <div class="field-wrapper">
                                                            <input type="text" name="titular"
                                                                class="form-fname form-element rounded medium"
                                                                placeholder="" tabindex="074">
                                                        </div>
                                                    </div>
                                                    <div class="column width-12">
                                                        <label
                                                            class="text-large left color-charcoal"><strong>CPF</strong></label>
                                                        <div class="field-wrapper">
                                                            <input type="number" name="titularcpf"
                                                                class="form-fname form-element rounded medium"
                                                                placeholder="" tabindex="075">
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                        </li>
                                    </ul>
                                </div>

                                <?php
                                            //Primeira transferência só aparece a opção para cadastrar uma conta.
                                            else:
                                        ?>
                                <div class="column width-12 left border-orange">
                                    <h4 class="pt-10 pb-0 color-orange weight-bold">
                                        Cadastre sua primeira conta para saque.
                                    </h4>
                                </div>
                                <div class="column width-3 pt-10">
                                    <label class="text-large color-charcoal"><strong>Agência</strong></label>
                                </div>
                                <div class="column width-6 pt-10">
                                    <div class="field-wrapper">
                                        <input type="number" name="PrimeiraAgencia"
                                            class="form-fname form-element rounded medium" placeholder="agência"
                                            tabindex="076" required>
                                    </div>
                                </div>
                                <div class="column width-3 pt-10">
                                    <div class="field-wrapper">
                                        <input type="number" name="PrimeiraAgenciaDig"
                                            class="form-fname form-element rounded medium" placeholder="" tabindex="077">
                                    </div>
                                </div>

                                <div class="column width-3">
                                    <label class="text-large color-charcoal"><strong>Conta</strong></label>
                                </div>
                                <div class="column width-6">
                                    <div class="field-wrapper">
                                        <input type="number" name="PrimeiraConta"
                                            class="form-fname form-element rounded medium" placeholder="agência"
                                            tabindex="078" required>
                                    </div>
                                </div>
                                <div class="column width-3">
                                    <div class="field-wrapper">
                                        <input type="number" name="PrimeiraContaDig"
                                            class="form-fname form-element rounded medium" placeholder="" tabindex="079">
                                    </div>
                                </div>

                                <div class="column full-width">
                                    <label class="text-large left color-charcoal"><strong>Selecione o seu
                                            banco</strong></label>
                                    <div class="form-select form-element rounded medium">
                                        <select name="Primeirobanco" tabindex="080" class="form-aux"
                                            data-label="Project Budget">
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

                                <div class="column width-12">
                                    <label class="text-large left color-charcoal"><strong>Titular</strong></label>
                                    <div class="field-wrapper">
                                        <input type="text" name="Primeirotitular"
                                            class="form-fname form-element rounded medium" placeholder="" tabindex="081"
                                            required>
                                    </div>
                                </div>
                                <div class="column width-12">
                                    <label class="text-large left color-charcoal"><strong>CPF</strong></label>
                                    <div class="field-wrapper">
                                        <input type="number" name="Primeirotitularcpf"
                                            class="form-fname form-element rounded medium" placeholder="" tabindex="082"
                                            required>
                                    </div>
                                </div>

                                <?php
                                            endif;
                                        ?>
                            </div>


                            <div class="row merged-form-elements">
                                <div class="column width-12 pt-20">
                                    <p align="justify" class="text-large color-black pt-0 pb-0">
                                        Ao clicar no botão abaixo, você confirma que o seu saldo definido deve ser
                                        transferido para a conta informada e está consciente dos dados que você informou
                                        acima se tornando responsável por qualquer equivoco em transferência para conta
                                        informada.
                                    </p>
                                    <?php
                                                if($SaldoDisponivelParaSaque >= 20):
                                            ?>
                                    <button type="submit" value="SOLICITAR SAQUE" name="btn-solicitarsaque"
                                        class="button full-width medium bkg-green bkg-hover-green color-white color-hover-white hard-shadow"
                                        tabindex="083">
                                        <span class="text-large">
                                            SOLICITAR SAQUE
                                        </span>
                                    </button>
                                    <?php
                                                else:
                                            ?>
                                    <button type="submit" value="O valor mínimo para saque é de R$ 20,00" disabled
                                        class="button full-width medium bkg-grey bkg-hover-grey color-white color-hover-white hard-shadow"
                                        tabindex="084">
                                        <span class="text-large">
                                            O valor mínimo para saque é de R$ 20,00
                                        </span>
                                    </button>
                                    <?php
                                                endif;
                                            ?>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <!-- Signup End -->

        </div>
        <!-- Modal para solicitação de saqueEnd -->
        
                <!-- Modal para edição financeira -->
                
                <?php
                    // if(isset($_GET['lanedit'])):
                    //     $COpM = $_GET['lanedit'];
                    //     $ideditado=$_GET['ref'];
                    // endif;
                    // if(isset($_GET['lanedit']) AND !empty($_GET['lanedit'])):
                ?>
                    <!-- Modal para o produto escolhido-->
                    <!-- <script>
                        window.setTimeout(function(){
                            document.getElementById("abrirmodaleditarlanc").click();
                        }, 500);
                    </script>
                    <script>
                        window.setTimeout(function(){
                            document.getElementById("abrirmodaleditarlanc").click();
                        }, 1500);
                    </script>
                    <script>
                        window.setTimeout(function(){
                            document.getElementById("abrirmodaleditarlanc").click();
                        }, 2000);
                    </script>
                    <script>
                        window.setTimeout(function(){
                            document.getElementById("abrirmodaleditarlanc").click();
                        }, 24000);
                    </script> -->

                <!-- <a id="abrirmodaleditarlanc" data-content="inline" data-aux-classes="tml-newsletter-modal tml-exit-light height-auto rounded" data-toolbar="" data-modal-mode data-modal-width="1140" data-modal-animation="scaleIn" data-lightbox-animation="fadeIn" href="#modaleditarlanca" class="lightbox-link"></a> -->

                <?php
                    if(!isset($yta2)): $yta2=date('Y'); endif;
                    $bModais="SELECT id, codigodaoperacao FROM financeiro WHERE mes='$mesdeoperacaoget' AND ano='$yta2'";
                    $rModais=mysqli_query($con, $bModais);
                    while($dModais=mysqli_fetch_array($rModais)):
                        $COpM=$dModais['codigodaoperacao'];
                        $ideditado=$dModais['id'];
                ?>
                <!-- Subscribe Modal Simple -->
                <div id="modaleditarlanca<?echo $COpM;?>"
                    class="pt-70 pb-50 hide">
                    <div class="column width-12">
                        <?php
                            //Buscar nome do produto
                            $bPSM = "SELECT representante, descricao FROM financeiro WHERE codigodaoperacao='$COpM' AND id='$ideditado'";
                                $rPSM=mysqli_query($con, $bPSM);
                                $dPSM=mysqli_fetch_array($rPSM);
                        ?>
                        <!-- Info -->
                        <h3 class="mb-10 left">
                            <?php $descModal = base64_decode($dPSM['descricao']); echo base64_decode($descModal);?></h3>
                        <p class="mb-30 left color-charcoal">
                            <?php echo $dPSM['representante'];?></p>
                        <!-- Info End -->

                        <!-- Signup -->
                        <div class="form-container">
                            <?php
                                //Buscar dados da operação financeira que é preciso atualizar.
                                $bOFM = "SELECT id, descricao, dataprevista, valorrealizado, datarealizada, valorprevisto, recebidode, categoria, centrodecusto, tipodeoperacao, conta1, conta2, tipoderegistro FROM financeiro WHERE codigodaoperacao='$COpM' AND mes='$mesdeoperacaoget' AND ano='$yta2'";
                                  $rOFM=mysqli_query($con, $bOFM);
                                    $dOFM= mysqli_fetch_array($rOFM);
                            ?>
                            <form class="form" action="" method="post" charset="UTF-8" enctype="Multipart/form-data">
                                <input type="hidden" name="ref" value="<?php echo $dOFM['id'];?>" />
                                <input type="hidden" name="tipodeoperacaoget" value="<?php echo $_GET['t'];?>" />
                                <input type="hidden" name="mesdeoperacaoget" value="<?php echo $_GET['ms'];?>" />

                                <div class="row">
                                    <div class="column width-9">
                                        <label
                                            class="text-large color-charcoal">Anexar
                                            <strong>Comprovante</strong></label>
                                        <div class="field-wrapper">
                                            <input type="file"
                                                name="anexo"
                                                class="form-element rounded medium left"
                                                placeholder="Substituir ou adicionar comprovante."
                                                tabindex="001">
                                        </div>
                                    </div>
                                    <div class="column width-3">
                                        <label
                                            class="text-large color-charcoal"><strong>Código</strong>
                                            da operação.</label>
                                        <div class="field-wrapper">
                                            <input type="hidden"
                                                name="codigodaoperacao0"
                                                value="<?php echo $COpM;?>">
                                            <input type="text"
                                                name="codigodaoperacao"
                                                value="<?php echo $COpM;?>"
                                                class="form-element rounded medium left"
                                                readonly
                                                placeholder="Substituir ou adicionar comprovante."
                                                tabindex="002">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="column width-9">
                                        <label
                                            class="text-large color-charcoal"><strong>Descrição</strong></label>
                                        <div class="field-wrapper">
                                            <input type="text"
                                                name="descricao"
                                                value="<?php $descMod = base64_decode($dOFM['descricao']); echo base64_decode($descMod);?>"
                                                class="form-element rounded medium left"
                                                placeholder="Substituir ou adicionar comprovante."
                                                tabindex="003">
                                        </div>
                                    </div>
                                    <div class="column width-3">
                                        <label
                                            class="text-large color-charcoal"><strong>Valor</strong>
                                            (R$)</label>
                                        <div class="field-wrapper">
                                            <input type="text"
                                                name="valorprevisto"
                                                value="<?php 
                                                            if(!empty($dOFM['valorrealizado'])):
                                                                $valorPOR = $dOFM['valorrealizado'];
                                                            else:
                                                                $valorPOR = $dOFM['valorprevisto'];
                                                            endif; //Adequa o valor para BD
                                                            if($valorPOR >= 1.000): //Se o valor inserido for maior que 1000 utiliza o algoritimo abaixo.
                                                                @$valorPOR = number_format($valorPOR, 2, ',','.');
                                                            elseif($valorPOR < 1.000): //Se for inferior utiliza o algoritimo.
                                                                @$valorPOR = str_replace('.', ',', $valorPOR);
                                                            endif;
                                                            echo $valorPOR;?>"
                                                class="form-element rounded medium left"
                                                placeholder="1.000,35"
                                                tabindex="004">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="column width-3">
                                        <label
                                            class="text-large color-charcoal"><strong>Realizado</strong> em</label>
                                        <div class="field-wrapper">
                                            <input type="date" name="realizadoem" value="<?php if(empty($dOFM['datarealizada'])): echo $dOFM['dataprevista']; else: echo $dOFM['datarealizada']; endif;?>" class="form-element rounded medium left" placeholder="" tabindex="005">
                                        </div>
                                    </div>
                                    <div class="column width-4">
                                        <label
                                            class="text-large color-charcoal"><strong>Recebido de</strong></label>
                                        <div
                                            class="form-select form-element rounded medium">
                                            <select name="recebidode" tabindex="006" class="form-aux" data-label="Project Budget">
                                                <option selected="selected">
                                                    <?php
                                                        if(!empty($dOFM['recebidode'])):
                                                            $recebidodeModal = base64_decode($dOFM['recebidode']);
                                                            echo base64_decode($recebidodeModal);
                                                        else:
                                                            echo $dOFM['recebidode'];
                                                        endif;
                                                    ?>
                                                </option>
                                                <?php
                                                    //Buscar consumidores cadastrados
                                                    $bRD="SELECT nome, sobrenome FROM membros WHERE igreja='Emanuel'";
                                                    $rRD=mysqli_query($con, $bRD);
                                                    while($dRD=mysqli_fetch_array($rRD)):
                                                ?>
                                                <option>
                                                    <?php $nomeR=base64_decode($dRD['nome']); $nomeR=base64_decode($nomeR);  $sobrenomeR=base64_decode($dRD['sobrenome']); $sobrenomeR=base64_decode($sobrenomeR); echo $nomeR.' '.$sobrenomeR; ?>
                                                </option>
                                                <?php
                                                    endwhile;
                                                ?>
                                                <?php
                                                    //Buscar consumidores cadastrados
                                                    $bRD2="SELECT recebidode FROM financeiro WHERE empresa='Emanuel' GROUP BY recebidode ORDER BY recebidode ASC";
                                                    $rRD2=mysqli_query($con, $bRD2);
                                                    while($dRD2=mysqli_fetch_array($rRD2)):
                                                ?>
                                                <option><?php $nomeM2 = base64_decode($dRD2['recebidode']); $nomeM2=base64_decode($nomeM2); echo $nomeM2;?></option>
                                                <?php
                                                    endwhile;
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="column width-5">
                                        <label
                                            class="text-large color-charcoal"><strong>Recebido</strong>
                                            de (Outro)</label>
                                        <div class="field-wrapper">
                                            <input type="text"
                                                name="recebidode2"
                                                class="form-element rounded medium left"
                                                placeholder=""
                                                tabindex="007">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="column width-4">
                                        <label
                                            class="text-large color-charcoal"><strong>Categoria</strong></label>
                                        <div
                                            class="form-select form-element rounded medium">
                                            <select name="categoria"
                                                tabindex="008"
                                                class="form-aux"
                                                data-label="Project Budget">
                                                <option
                                                    selected="selected">
                                                    <?php echo $dOFM['categoria'];?>
                                                </option>
                                                
                                                <?php
                                                    if($_GET['t'] = 'c' OR !isset($_GET['t']) OR empty($_GET['t'])): $catT='CRÉDITO'; elseif($_GET['t'] = 'd'): $catT='DÉBITO'; endif;
                                                    
                                                    $exibircategorias="SELECT categoria FROM categoriasfinanceiro WHERE empresa='Emanuel' AND urldaempresa='igrejaemanuel' AND tipodecategoria='$catT' GROUP BY categoria ORDER BY categoria ASC";
                                                        $rc=mysqli_query($con, $exibircategorias);
                                                            while($dc=mysqli_fetch_array($rc)):
                                                        $categoriareg=$dc['categoria'];
                                                ?>
                                                <option><?php echo $categoriareg;?></option>
                                                <?
                                                    $viewcategoriafin="SELECT categoria FROM financeiro WHERE NOT (categoria='$categoriareg') AND empresa='Emanuel' AND urldaempresa='igrejaemanuel' AND tipoderegistro='$catT' GROUP BY categoria ORDER BY categoria ASC";
                                                        $rcF=mysqli_query($con, $viewcategoriafin);
                                                            while($dcF=mysqli_fetch_array($rcF)):
                                                    $catF=$dcF['categoria'];
                                                ?>
                                                <option><?php echo $catF;?></option>
                                                <?php
                                                    endwhile;
                                                    endwhile;
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="column width-4">
                                        <label
                                            class="text-large color-charcoal"><strong>Centro
                                                de
                                                custo</strong></label>
                                        <div class="field-wrapper">
                                            <input type="text"
                                                name="centrodecusto"
                                                value="<?php echo $dOFM['centrodecusto'];?>"
                                                class="form-email form-element rounded medium"
                                                tabindex="009">
                                        </div>
                                    </div>
                                    <div class="column width-4">
                                        <label
                                            class="text-large color-charcoal"><strong>Conta.</strong></label>
                                        <div
                                            class="form-select form-element rounded medium">
                                            <select name="conta"
                                                tabindex="010"
                                                class="form-aux"
                                                data-label="Project Budget">
                                                <option selected="selected"> <?php echo $dOFM['conta1'];?> </option>
                                                <?php
                                                    $bContas="SELECT id, nomedaconta, conta FROM contasfinanceiro WHERE empresa='Emanuel' AND urldaempresa='igrejaemanuel' ORDER BY id ASC";
                                                    $rFC=mysqli_query($con, $bContas);
                                                    while($dFC=mysqli_fetch_array($rFC)):
                                                ?>
                                                <option value="<?php echo $dFC['conta'];?>"><?php echo $dFC['nomedaconta'].' ('.$dFC['conta'].')';?></option>
                                                <?php
                                                    endwhile;
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="column width-4">
                                        <label
                                            class="text-large color-charcoal"><strong>Tipo de operação.</strong></label>
                                        <div
                                            class="form-select form-element rounded medium">
                                            <select name="tipodeoperacao" tabindex="011" class="form-aux" data-label="Project Budget">
                                                <option selected="selected">
                                                    <?php echo $dOFM['tipodeoperacao'];?>
                                                </option>
                                                <?php
                                                    if($dOFM['tipoderegistro'] === 'CRÉDITO'):
                                                ?>
                                                
                                                <option selected="selected" value="">
                                                    Tipo desta operação
                                                </option>
                                                <option value="PIX">
                                                    PIX</option>
                                                <option value="Depósito">
                                                    Depósito</option>
                                                <option value="Transferência">
                                                    Transferência</option>
                                                <option value="Oferta">
                                                    Oferta
                                                </option>
                                                <option value="Dízimo">
                                                    Dízimo
                                                </option>
                                                <option value="Campanha">
                                                    Campanha</option>
                                                <option value="Evento">
                                                    Evento
                                                </option>
                                                <option value="Curso">
                                                    Curso
                                                </option>
                                                <option value="Seminário/Workshop">
                                                    Seminário/Workshop
                                                </option>
                                                <option value="Espaço Café">
                                                    Espaço Café
                                                </option>
                                                <option value="Almoço">
                                                    Almoço
                                                </option>
                                                <option value="Rifa">
                                                    Rifa
                                                </option>
                                                <option value="Feirinha">
                                                    Feirinha
                                                </option>
                                                <option value="Empresa social">
                                                    Empresa social
                                                </option>
                                                <option value="Venda de Ativo Patrimonial">
                                                    Venda de Ativo Patrimonial
                                                </option>
                                                <option value="Venda direta">
                                                    Venda direta
                                                </option>
                                                <option value="Cobrança de dívida">
                                                    Cobrança de dívida
                                                </option>
                                                <option value="Rendimento">
                                                    Rendimento
                                                </option>
                                                <option value="Diversos">
                                                    Diversos
                                                </option>
                                                
                                                <?php
                                                    elseif($dOFM['tipoderegistro'] === 'DÉDITO'):
                                                ?>
                                                
                                                <option selected="selected" value="">
                                                    Tipo desta operação
                                                </option>
                                                <option value="Oferta">
                                                    Oferta
                                                </option>
                                                <option value="Doação">
                                                    Doação
                                                </option>
                                                <option value="Depósito">
                                                    Depósito
                                                </option>
                                                <option value="Transferência">
                                                    Transferência
                                                </option>
                                                <option value="Compra de Ativo Patrimonial">
                                                    Compra de Ativo Patrimonial
                                                </option>
                                                <option value="Compra de matéria prima">
                                                    Compra de matéria prima 
                                                </option>
                                                <option value="Cobrança de dívida">
                                                    Cobrança de dívida 
                                                </option>
                                                <option value="Custo direto">
                                                    Custo direto
                                                </option>
                                                <option value="Custo indireto">
                                                    Custo indireto
                                                </option>
                                                <option value="Despesa">
                                                    Despesa
                                                </option>
                                                <option value="Rendimento">
                                                    Rendimento
                                                </option>
                                                <option value="Outro">
                                                    Outro
                                                </option>
                                                
                                                <?php
                                                    endif;
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="column width-4">
                                        <label
                                            class="text-large color-charcoal"><strong>Conta de Origem.</strong></label>
                                        <div
                                            class="form-select form-element rounded medium">
                                            <select name="contadeorigem"
                                                tabindex="012"
                                                class="form-aux"
                                                data-label="Project Budget">
                                                <option
                                                    selected="selected">
                                                    <?php echo $dOFM['conta1'];?>
                                                </option>
                                                <?php
                                                    $bContas="SELECT id, nomedaconta, conta FROM contasfinanceiro WHERE empresa='Emanuel' AND urldaempresa='igrejaemanuel'";
                                                    $rFC=mysqli_query($con, $bContas);
                                                    while($dFC=mysqli_fetch_array($rFC)):
                                                ?>
                                                <option value="<?php echo $dFC['conta'];?>"><?php echo $dFC['nomedaconta'].' ('.$dFC['conta'].')';?></option>
                                                <?php
                                                    endwhile;
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="column width-4">
                                        <label
                                            class="text-large color-charcoal"><strong>Conta
                                                de
                                                Destino.</strong></label>
                                        <div
                                            class="form-select form-element rounded medium">
                                            <select
                                                name="contadedestino"
                                                tabindex="013"
                                                class="form-aux"
                                                data-label="Project Budget">
                                                <option
                                                    selected="selected">
                                                    <?php echo $dOFM['conta2'];?>
                                                </option>
                                                <?php
                                                    $bContas="SELECT id, conta FROM contasfinanceiro WHERE empresa='Emanuel' AND urldaempresa='igrejaemanuel'";
                                                    $rFC=mysqli_query($con, $bContas);
                                                    while($dFC=mysqli_fetch_array($rFC)):
                                                ?>
                                                <option>
                                                    <?php echo $dFC['conta'];?>
                                                </option>
                                                <?php
                                                    endwhile;
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="column width-2 rounded small">
                                        <div class="column width-4">
                                            <input id="checkbox-2-pago" class="" name="confirmacaodepagamento" type="checkbox" checked='checked'>
                                        </div>
                                        <div class="column width-8 left">
                                            <span for="checkbox-2-pago" class="text-xlarge weight-bold color-<?php echo $colorPage;?>">Pago</span>
                                        </div>
                                    </div>
                                    <?php
                                        if($_GET['type'] === 'c' OR $_GET['t'] === 'c'):
                                            $nomedobutton='btn-confirmarreceita';
                                        elseif($_GET['type'] === 'd' OR $_GET['t'] === 'd'):
                                            $nomedobutton='btn-confirmardebito';
                                        endif;
                                    ?>
                                    <div class="column width-2">
                                        <input type="submit" name="<?php echo $nomedobutton;?>" value="Salvar este" tabindex="014" class="column full-width form-submit button rounded medium bkg-blue bkg-hover-blue color-white color-hover-white hard-shadow">
                                    </div>
                                    <div class="column width-3">
                                        <input type="submit" name="<?php echo $nomedobutton;?>2" value="Salvar este e Próximos" tabindex="015" class="column full-width form-submit button rounded medium bkg-green bkg-hover-green color-white color-hover-white hard-shadow">
                                    </div>
                                    <div class="column width-2">
                                        <?php $idexcluirun = $dOFM['id'];?>
                                        <a href="financeiro<?php echo '?m='.$matriculausuario.'&token='.$token.'&ms='.$mesdeoperacaoget.'&type='.$tipodeoperacaoget.'&exc='.$COpM.'&idexc='.$idexcluirun;?>" tabindex="016" class="column full-width form-submit button rounded medium bkg-red bkg-hover-red color-white color-hover-white hard-shadow">Excluir</a>
                                    </div>
                                    <div class="column width-3">
                                        <a href="financeiro<?php echo '?m='.$matriculausuario.'&token='.$token.'&exct='.$COpM.'&idexc='.$idexcluirun.'&ms='.$_GET['ms'].'&type='.$_GET['type'].'&y='."$yta";?>" tabindex="017" class="column full-width form-submit button rounded medium bkg-charcoal bkg-hover-charcoal color-white color-hover-white hard-shadow">Excluir este e Próximos</a>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <!-- Signup End -->

                    </div>
                </div>
                <!-- Subscribe Modal Simple End -->
                <?php
                    endwhile;
                ?>
                <!-- Modal para edição financeira -->

	<? include "./_script.php"; ?>
</body>
</html>