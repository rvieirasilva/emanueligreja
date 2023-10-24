<?php
    session_start();
    include "./_con.php"; //Conecta com banco de dados.
    include "./_configuracao.php"; //Traz informações necessárias para uso das páginas.

    if (isset($_POST['btn-acessarusuario'])) :
        $erros              = array();
        $emaildousuario     = mysqli_escape_string($con, $_POST['cpf']);
        $senhadousuario     = mysqli_escape_string($con, $_POST['senha']);

        if(empty($emaildousuario)):
            $_SESSION['cordamensagem'] = "red";
            $_SESSION['mensagem'] = "É preciso informar o e-mail, CPF ou matrícula de acesso.";
            header("index");
        elseif(empty($senhadousuario)):
            $_SESSION['cordamensagem'] = "red";
            $_SESSION['mensagem'] = "É preciso informar sua senha";
            header("location:entrar");
        else :
            $sql = "SELECT senha FROM membros WHERE matricula='$emaildousuario' OR email='$emaildousuario' OR cpf='$emaildousuario'";
            $resultado = mysqli_query($con, $sql);
                $dados = mysqli_fetch_array($resultado);

            $hash = $dados['senha'];

            if (mysqli_num_rows($resultado) > 0) :
                if (password_verify($senhadousuario, $hash)) :
                    //if($senhadousuario != ''):
                    $sql = "SELECT id, statusdomembro, nome, sobrenome, email, cpf, telefonemovel, igreja, statusdopagamento, matricula FROM membros WHERE matricula='$emaildousuario' OR email='$emaildousuario' OR cpf='$emaildousuario'";
                      $resultado = mysqli_query($con, $sql);
                        $dusuariologado=mysqli_fetch_array($resultado);

                    if($dusuariologado['statusdomembro'] !== 'Ativo'):
                        $_SESSION['cordamensagem'] = "red";
                        $_SESSION['mensagem'] = "Para acessar é preciso estar regularizado como membro da Emanuel. Caso acredite que isto é um equívoco, entre em contato com seu líder ou com nossa secretaria para atualizar suas informações.";
                        var_dump($dusuariologado['statusdomembro']);
                        session_destroy();
                        header("location:entrar");
                    else:
                        if (mysqli_num_rows($resultado) == 1) :

                            $dados = mysqli_fetch_array($resultado); 
                            //DADOS DO usuario
                            $nomeusuario                    = $dusuariologado['nome'];
                            $sobrenomeusuario               = $dusuariologado['sobrenome'];
                            $nomedousuario                  = $nomeusuario.' '.$sobrenomeusuario;
                            $emailusuario                   = $dusuariologado['email'];
                            $cpfusuario                     = $dusuariologado['cpf'];
                            $telefonemovelusuario           = $dusuariologado['telefonemovel'];
                            $igrejausuario                  = $dusuariologado['igreja'];
                            $cargoeclesiasticousuario       = $dusuariologado['cargoministerial'];
                            $statusdopagamentousuario       = $dusuariologado['statusdopagamento'];
                            $matricula                      = $dusuariologado['matricula'];
                            $matriculausuario               = $dusuariologado['matricula'];
                            $validoateusuario               = $dusuariologado['validoate'];

                            $tempodesessao                  = 4320000; //Equivale a segundos.
                            $_SESSION['registro']           = time(); //Registra quando o usuário logou.
                            $_SESSION['limite']             = $tempodesessao;

                            $_SESSION['membro']            = true;
                            $_SESSION["$matricula"]         = $matricula;
                            $_SESSION["matricula"]          = $matricula;
                            $matricula                      = $_SESSION['matricula'];
                            $_SESSION['id']                 = $dusuariologado['id'];


                            $token  = date('dmY') . mt_rand(00001, 99999) . time();
                            $ano    = date('Y');
                            
                            //Criação dos tokens. DADOS PARA TOKEN
                            $segurancaampliada = "INSERT INTO tokens (token, ano, criadopor) VALUES ('$token', '$ano', '$matricula')";
                            mysqli_query($con, $segurancaampliada);

                            //Criar sessão com token inicial
                            $buscartoken                    = "SELECT token FROM tokens GROUP BY token ORDER BY rand(), id DESC LIMIT 1";
                            $rbuscartoken                   = mysqli_query($con, $buscartoken);
                                $dbuscartoken                   = mysqli_fetch_array($rbuscartoken);

                            $token                          = $dbuscartoken['token'];

                            $_SESSION['token']              = $dbuscartoken['token'];
                            $token                          = $_SESSION['token'];
                            $tempodotoken                   = 10; //em segundos.
                            $_SESSION['limitedotoken']      = $tempodotoken;

                            //Criar sessão para proteger conta do usuario.
                            $_SESSION["$matricula"]         = $matricula;
                            $_SESSION['membro']            = true;
                            $_SESSION['matricula']          = $dusuariologado['matricula'];
                            
                            if(empty($cpfusuario)):
                                $_SESSION['cordamensagem']="orange";
                                $_SESSION['mensagem']= "Você precisa completar seus dados para ter acesso as demais áreas do site";

                                header("location:configurar?token=$token&m=$matricula");
                            else:
                                header("location:emanuel?token=$token&m=$matricula");
                            endif;
                        else :
                            $_SESSION['cordamensagem'] = "red";
                            $_SESSION['mensagem'] = "Usuário não encontrado";
                            header("location:entrar");
                        endif;
                    endif;
                else :
                    $_SESSION['cordamensagem'] = "red";
                    $_SESSION['mensagem'] = "Verifique sua senha";
                    header("location:entrar");
                endif;
            else:
                $sql = "SELECT senha FROM visitantes WHERE matricula='$emaildousuario' OR email='$emaildousuario' OR cpf='$emaildousuario'";
                $resultado = mysqli_query($con, $sql);
                    $dados = mysqli_fetch_array($resultado);

                $hash = $dados['senha'];

                if (mysqli_num_rows($resultado) > 0) :
                    //if($senhadousuario != ''):
                    $sql = "SELECT id, statusdomembro, nome, sobrenome, email, cpf, telefonemovel, igreja, statusdopagamento, matricula FROM visitantes WHERE matricula='$emaildousuario' OR email='$emaildousuario' OR cpf='$emaildousuario'";
                      $resultado = mysqli_query($con, $sql);
                        $dvisitantelogado=mysqli_fetch_array($resultado);

                    if($dvisitantelogado['statusdomembro'] !== "Ativo"):
                        $_SESSION['cordamensagem'] = "red";
                        $_SESSION['mensagem'] = "Para acessar é preciso estar regularizado como membro da Emanuel. Caso acredite que isto é um equívoco, entre em contato com seu líder ou com nossa secretaria para atualizar suas informações.";

                        session_destroy();
                        header("location:entrar");
                    else:
                        if (mysqli_num_rows($resultado) == 1) :

                            $dados = mysqli_fetch_array($resultado); 
                            //DADOS DO usuario
                            $nomeusuario                    = $dvisitantelogado['nome'];
                            $sobrenomeusuario               = $dvisitantelogado['sobrenome'];
                            $nomedousuario                  = $nomeusuario.' '.$sobrenomeusuario;
                            $emailusuario                   = $dvisitantelogado['email'];
                            $cpfusuario                     = $dvisitantelogado['cpf'];
                            $telefonemovelusuario           = $dvisitantelogado['telefonemovel'];
                            $igrejausuario                  = $dvisitantelogado['igreja'];
                            $cargoeclesiasticousuario       = $dvisitantelogado['cargoministerial'];
                            $statusdopagamentousuario       = $dvisitantelogado['statusdopagamento'];
                            $matricula                      = $dvisitantelogado['matricula'];
                            $matriculausuario               = $dvisitantelogado['matricula'];
                            $validoateusuario               = $dvisitantelogado['validoate'];

                            $tempodesessao                  = 4320000; //Equivale a segundos.
                            $_SESSION['registro']           = time(); //Registra quando o usuário logou.
                            $_SESSION['limite']             = $tempodesessao;

                            $_SESSION['visitante']            = true;
                            $_SESSION["$matricula"]         = $matricula;
                            $_SESSION["matricula"]          = $matricula;
                            $matricula                      = $_SESSION['matricula'];
                            $_SESSION['id']                 = $dvisitantelogado['id'];


                            $token  = date('dmY') . mt_rand(00001, 99999) . time();
                            $ano    = date('Y');
                            
                            //Criação dos tokens. DADOS PARA TOKEN
                            $segurancaampliada = "INSERT INTO tokens (token, ano, criadopor) VALUES ('$token', '$ano', '$matricula')";
                            mysqli_query($con, $segurancaampliada);

                            //Criar sessão com token inicial
                            $buscartoken                    = "SELECT token FROM tokens GROUP BY token ORDER BY rand(), id DESC LIMIT 1";
                            $rbuscartoken                   = mysqli_query($con, $buscartoken);
                                $dbuscartoken                   = mysqli_fetch_array($rbuscartoken);

                            $token                          = $dbuscartoken['token'];

                            $_SESSION['token']              = $dbuscartoken['token'];
                            $token                          = $_SESSION['token'];
                            $tempodotoken                   = 10; //em segundos.
                            $_SESSION['limitedotoken']      = $tempodotoken;

                            //Criar sessão para proteger conta do usuario.
                            $_SESSION["$matricula"]         = $matricula;
                            $_SESSION['visitante']            = true;
                            $_SESSION['matricula']          = $dvisitantelogado['matricula'];
                            
                            // if(empty($cpfusuario)):
                            //     $_SESSION['cordamensagem']="orange";
                            //     $_SESSION['mensagem']= "Você precisa completar seus dados para ter acesso as demais áreas do site";

                            //     header("location:configurar?token=$token&m=$matricula");
                            // else:
                                header("location:loja?token=$token&m=$matricula");
                            // endif;
                        else :
                            $_SESSION['cordamensagem'] = "red";
                            $_SESSION['mensagem'] = "Usuário não encontrado";
                            header("location:entrar");
                        endif;
                    endif;
                else:
                    $_SESSION['cordamensagem'] = "red";
                    $_SESSION['mensagem'] = "Usuário não encontrado";
                    header("location:entrar");
                endif;
            endif;
        endif;
    endif;

    if(!isset($_POST['btn-acessarusuario'])):
        header('location:entrar');
    endif;
    //require_once "protect.php"; //Protege as páginas de acesso não autorizado.
    
                    