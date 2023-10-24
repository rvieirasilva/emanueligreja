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

                if($codigodaoperacao != ''):
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
                            $inserir="INSERT INTO financeiro (empresa, codigodoparceiro, urldaempresa, dia, mes, ano, representante, codigodorepresentante, codigodaoperacao, descricao, valorprevisto, dataprevista, valorrealizado, datarealizada, recebidode, pagopor, anexo, tamanhodoarquivo, tipodeoperacao, categoria, centrodecusto, conta1, conta2, tipoderegistro) VALUES ('Emanuel', '0001', 'igrejaemanuel', '$diaprevisto', '$mesprevisto', '$anoprevisto', '$nomedousuario', '$matriculausuario', '$codigodaoperacaoo', '$descricao', '$valorprevisto', '$dataprevista', '', '', '$recebidode', '', '$anexo', '$tamanhoanexo', '$tipodeoperacao', '$categoria', '$centrodecusto', '$contadeorigem', '$contadedestino', 'DÉBITO')"; 
                            $TirarDaContaAnterior="INSERT INTO financeiro (empresa, codigodoparceiro, urldaempresa, dia, mes, ano, representante, codigodorepresentante, codigodaoperacao, descricao, valorprevisto, dataprevista, valorrealizado, datarealizada, recebidode, pagopor, anexo, tamanhodoarquivo, tipodeoperacao, categoria, centrodecusto, conta1, conta2, tipoderegistro) VALUES ('Emanuel', '0001', 'igrejaemanuel', '$diaprevisto', '$mesprevisto', '$anoprevisto', '$nomedousuario', '$matriculausuario', '$codigodaoperacaoo', '$descricao', '$valorprevisto', '$dataprevista', '', '', '$recebidode', '', '$anexo', '$tamanhoanexo', '$tipodeoperacao', '$categoria', '$centrodecusto', '$contadedestino', '$contadeorigem', 'CRÉDITO')"; 
                            mysqli_query($con, $TirarDaContaAnterior);
                        elseif(!empty($confirmacaodepagamento)):
                            $inserir="INSERT INTO financeiro (empresa, codigodoparceiro, urldaempresa, dia, mes, ano, representante, codigodorepresentante, codigodaoperacao, descricao, valorprevisto, dataprevista, valorrealizado, datarealizada, recebidode, pagopor, anexo, tamanhodoarquivo, tipodeoperacao, categoria, centrodecusto, conta1, conta2, tipoderegistro) VALUES ('Emanuel', '0001', 'igrejaemanuel', '$diaprevisto', '$mesprevisto', '$anoprevisto', '$nomedousuario', '$matriculausuario', '$codigodaoperacaoo', '$descricao', '$valorprevistofuturo', '$dataprevista', '', '', '$recebidode', '', '$anexo', '$tamanhoanexo', '', '$categoria', '$centrodecusto', '$conta', '', 'CRÉDITO')"; 
                        else:
                            $inserir="INSERT INTO financeiro (empresa, codigodoparceiro, urldaempresa, dia, mes, ano, representante, codigodorepresentante, codigodaoperacao, descricao, valorprevisto, dataprevista, valorrealizado, datarealizada, recebidode, pagopor, anexo, tamanhodoarquivo, tipodeoperacao, categoria, centrodecusto, conta1, conta2, tipoderegistro) VALUES ('Emanuel', '0001', 'igrejaemanuel', '$diaprevisto', '$mesprevisto', '$anoprevisto', '$nomedousuario', '$matriculausuario', '$codigodaoperacaoo', '$descricao', '$valorprevisto', '$dataprevista', '', '', '$recebidode', '', '$anexo', '$tamanhoanexo', '', '$categoria', '$centrodecusto', '$conta', '', 'CRÉDITO')"; 
                        endif;
                    else:
                        if($tipodeoperacao === 'Transferência'): //Caso ocorra uma operação de transferência.
                            $inserir="INSERT INTO financeiro (empresa, codigodoparceiro, urldaempresa, dia, mes, ano, representante, codigodorepresentante, codigodaoperacao, descricao, valorprevisto, dataprevista, valorrealizado, datarealizada, recebidode, pagopor, anexo, tamanhodoarquivo, tipodeoperacao, categoria, centrodecusto, conta1, conta2, tipoderegistro) VALUES ('Emanuel', '0001', 'igrejaemanuel', '$diaprevisto', '$mesprevisto', '$anoprevisto', '$nomedousuario', '$matriculausuario', '$codigodaoperacaoo', '$descricao', '$valorprevisto', '$dataprevista', '', '', '$recebidode', '', '$anexo', '$tamanhoanexo', '$tipodeoperacao', '$categoria', '$centrodecusto', '$contadeorigem', '$contadedestino', 'DÉBITO')"; 
                            $TirarDaContaAnterior="INSERT INTO financeiro (empresa, codigodoparceiro, urldaempresa, dia, mes, ano, representante, codigodorepresentante, codigodaoperacao, descricao, valorprevisto, dataprevista, valorrealizado, datarealizada, recebidode, pagopor, anexo, tamanhodoarquivo, tipodeoperacao, categoria, centrodecusto, conta1, conta2, tipoderegistro) VALUES ('Emanuel', '0001', 'igrejaemanuel', '$diaprevisto', '$mesprevisto', '$anoprevisto', '$nomedousuario', '$matriculausuario', '$codigodaoperacaoo', '$descricao', '$valorprevisto', '$dataprevista', '', '', '$recebidode', '', '$anexo', '$tamanhoanexo', '$tipodeoperacao', '$categoria', '$centrodecusto', '$contadedestino', '$contadeorigem', 'CRÉDITO')"; 
                            mysqli_query($con, $TirarDaContaAnterior);
                        elseif(!empty($confirmacaodepagamento)):
                            $inserir="INSERT INTO financeiro (empresa, codigodoparceiro, urldaempresa, dia, mes, ano, representante, codigodorepresentante, codigodaoperacao, descricao, valorprevisto, dataprevista, valorrealizado, datarealizada, recebidode, pagopor, anexo, tamanhodoarquivo, tipodeoperacao, categoria, centrodecusto, conta1, conta2, tipoderegistro) VALUES ('Emanuel', '0001', 'igrejaemanuel', '$diaprevisto', '$mesprevisto', '$anoprevisto', '$nomedousuario', '$matriculausuario', '$codigodaoperacaoo', '$descricao', '', '', '$valorprevistofuturo', '$dataprevista', '$recebidode', '', '$anexo', '$tamanhoanexo', '', '$categoria', '$centrodecusto', '$conta', '', 'CRÉDITO')"; 
                        else:
                            $inserir="INSERT INTO financeiro (empresa, codigodoparceiro, urldaempresa, dia, mes, ano, representante, codigodorepresentante, codigodaoperacao, descricao, valorprevisto, dataprevista, valorrealizado, datarealizada, recebidode, pagopor, anexo, tamanhodoarquivo, tipodeoperacao, categoria, centrodecusto, conta1, conta2, tipoderegistro) VALUES ('Emanuel', '0001', 'igrejaemanuel', '$diaprevisto', '$mesprevisto', '$anoprevisto', '$nomedousuario', '$matriculausuario', '$codigodaoperacaoo', '$descricao', '$valorprevisto', '$dataprevista', '', '', '$recebidode', '', '$anexo', '$tamanhoanexo', '', '$categoria', '$centrodecusto', '$conta', '', 'CRÉDITO')"; 
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

            //     unset($_SESSION['ultimadataprevista']); //Após todas as repetições encerra as sessions criadas para outras operações acontecerem.
            //     unset($_SESSION['codigodaoperacao']); //Após todas as repetições encerra as sessions criadas para outras operações acontecerem.
        // else:
        //     $_SESSION['cordamensagem']="youtube";	
        //     $_SESSION['mensagem']="Entre em contato com a <strong>EuPedi</strong>. Loja impossibilitada de cadastrar débito.";
        // endif;
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

                if($codigodaoperacao != ''):
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
                            $TirarDaContaAnterior="INSERT INTO financeiro (empresa, codigodoparceiro, urldaempresa, dia, mes, ano, representante, codigodorepresentante, codigodaoperacao, descricao, valorprevisto, dataprevista, valorrealizado, datarealizada, recebidode, pagopor, anexo, tamanhodoarquivo, tipodeoperacao, categoria, centrodecusto, conta1, conta2, tipoderegistro) VALUES ('Emanuel', '0001', 'igrejaemanuel', '$diaprevisto', '$mesprevisto', '$anoprevisto', '$nomedousuario', '$matriculausuario', '$codigodaoperacaoo', '$descricao', '$valorprevisto', '$dataprevista', '$dataprevista', '$valorprevisto', '$dataprevista', '$recebidode', '', '$anexo', '$tamanhoanexo', '$tipodeoperacao', '$categoria', '$centrodecusto', '$contadedestino', '$contadeorigem', 'CRÉDITO')"; 
                            mysqli_query($con, $TirarDaContaAnterior);
                        elseif(!empty($confirmacaodepagamento)):
                            @$inserir="INSERT INTO financeiro (empresa, codigodoparceiro, urldaempresa, dia, mes, ano, representante, codigodorepresentante, codigodaoperacao, descricao, valorprevisto, dataprevista, valorrealizado, datarealizada, recebidode, pagopor, anexo, tamanhodoarquivo, tipodeoperacao, categoria, centrodecusto, conta1, conta2, tipoderegistro) VALUES ('Emanuel', '0001', 'igrejaemanuel', '$diaprevisto', '$mesprevisto', '$anoprevisto', '$nomedousuario', '$matriculausuario', '$codigodaoperacaoo', '$descricao', '$valorprevistofuturo', '$dataprevista', '', '', '$recebidode', '', '$anexo', '$tamanhoanexo', '', '$categoria', '$centrodecusto', '$conta', '', 'DÉBITO')"; 
                        else:
                            @$inserir="INSERT INTO financeiro (empresa, codigodoparceiro, urldaempresa, dia, mes, ano, representante, codigodorepresentante, codigodaoperacao, descricao, valorprevisto, dataprevista, valorrealizado, datarealizada, recebidode, pagopor, anexo, tamanhodoarquivo, tipodeoperacao, categoria, centrodecusto, conta1, conta2, tipoderegistro) VALUES ('Emanuel', '0001', 'igrejaemanuel', '$diaprevisto', '$mesprevisto', '$anoprevisto', '$nomedousuario', '$matriculausuario', '$codigodaoperacaoo', '$descricao', '$valorprevisto', '$dataprevista', '', '', '$recebidode', '', '$anexo', '$tamanhoanexo', '', '$categoria', '$centrodecusto', '$conta', '', 'DÉBITO')"; 
                        endif;
                    else:
                        if($tipodeoperacao === 'Transferência'): //Caso ocorra uma operação de transferência.
                            $inserir="INSERT INTO financeiro (empresa, codigodoparceiro, urldaempresa, dia, mes, ano, representante, codigodorepresentante, codigodaoperacao, descricao, valorprevisto, dataprevista, valorrealizado, datarealizada, recebidode, pagopor, anexo, tamanhodoarquivo, tipodeoperacao, categoria, centrodecusto, conta1, conta2, tipoderegistro) VALUES ('Emanuel', '0001', 'igrejaemanuel', '$diaprevisto', '$mesprevisto', '$anoprevisto', '$nomedousuario', '$matriculausuario', '$codigodaoperacaoo', '$descricao', '$valorprevisto', '$dataprevista', '$dataprevista', '$valorprevisto', '$dataprevista', '$recebidode', '', '$anexo', '$tamanhoanexo', '$tipodeoperacao', '$categoria', '$centrodecusto', '$contadeorigem', '$contadedestino', 'DÉBITO')"; 
                            $TirarDaContaAnterior="INSERT INTO financeiro (empresa, codigodoparceiro, urldaempresa, dia, mes, ano, representante, codigodorepresentante, codigodaoperacao, descricao, valorprevisto, dataprevista, valorrealizado, datarealizada, recebidode, pagopor, anexo, tamanhodoarquivo, tipodeoperacao, categoria, centrodecusto, conta1, conta2, tipoderegistro) VALUES ('Emanuel', '0001', 'igrejaemanuel', '$diaprevisto', '$mesprevisto', '$anoprevisto', '$nomedousuario', '$matriculausuario', '$codigodaoperacaoo', '$descricao', '$valorprevisto', '$dataprevista', '$dataprevista', '$valorprevisto', '$dataprevista', '$recebidode', '', '$anexo', '$tamanhoanexo', '$tipodeoperacao', '$categoria', '$centrodecusto', '$contadedestino', '$contadeorigem', 'CRÉDITO')"; 
                            mysqli_query($con, $TirarDaContaAnterior);
                        elseif(!empty($confirmacaodepagamento)):
                            @$inserir="INSERT INTO financeiro (empresa, codigodoparceiro, urldaempresa, dia, mes, ano, representante, codigodorepresentante, codigodaoperacao, descricao, valorprevisto, dataprevista, valorrealizado, datarealizada, recebidode, pagopor, anexo, tamanhodoarquivo, tipodeoperacao, categoria, centrodecusto, conta1, conta2, tipoderegistro) VALUES ('Emanuel', '0001', 'igrejaemanuel', '$diaprevisto', '$mesprevisto', '$anoprevisto', '$nomedousuario', '$matriculausuario', '$codigodaoperacaoo', '$descricao', '', '', '$valorprevistofuturo', '$dataprevista', '$recebidode', '', '$anexo', '$tamanhoanexo', '', '$categoria', '$centrodecusto', '$conta', '', 'DÉBITO')"; 
                        else:
                            @$inserir="INSERT INTO financeiro (empresa, codigodoparceiro, urldaempresa, dia, mes, ano, representante, codigodorepresentante, codigodaoperacao, descricao, valorprevisto, dataprevista, valorrealizado, datarealizada, recebidode, pagopor, anexo, tamanhodoarquivo, tipodeoperacao, categoria, centrodecusto, conta1, conta2, tipoderegistro) VALUES ('Emanuel', '0001', 'igrejaemanuel', '$diaprevisto', '$mesprevisto', '$anoprevisto', '$nomedousuario', '$matriculausuario', '$codigodaoperacaoo', '$descricao', '$valorprevisto', '$dataprevista', '', '', '$recebidode', '', '$anexo', '$tamanhoanexo', '', '$categoria', '$centrodecusto', '$conta', '', 'DÉBITO')"; 
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
                                                    <h1 class="mb-0">Enviar relatório <strong>Financeiro</strong>.</h1>
                                                    <p class="text-large color-charcoal mb-0 mb-mobile-20"></strong></p>
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
						<div class="column width-12">
							<div>
								<div class="signup-box box rounded medium mb-0 bkg-white border-blue">
                                    <h3 class="color-blue pb-30"><strong><? echo $nomebuttonnovamovimentacao; ?></strong>.</h3>
                                    
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
                                            
                                            <div class="column width-12 pb-20">
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

                                            <hr class="pt-20 pb-0"/>

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