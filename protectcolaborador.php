<?php
    //Proteção com base no teoken.
    //Pesquisa para verificar se o token que está na URL existe no nosso sistema ou se o usuário digitou para burlar a url.
    $token = $_SESSION['token'];
    $buscartoken = "SELECT token FROM tokens WHERE token='$token' GROUP BY token";
        $rbuscartoken = mysqli_query($con, $buscartoken);
            $dbuscartoken=mysqli_fetch_array($rbuscartoken);
                $tokenencontrado = $dbuscartoken['token'];

            //verificar
            if($token!=$tokenencontrado):
                session_destroy();
                session_unset();
                header('Location:index');
                exit();
            endif;

    //Verificar se o token na URL está vazio e tentem acessar o sistema.
    if(empty($_GET['token'])):
        session_unset();
        session_destroy();
        header('Location:index');
        exit();
    endif;

    //Verificar se existe o token na URL, para impedir que o usuário apague o token para tentar acessar o sistema.
    if(!isset($_GET['token'])):
        session_unset();
        session_destroy();
        header('Location:index');
        exit();
    endif;

    //Verificar se o usuário fez login, se não tiver feito login ele irá ser direcionado para o inicio.
    if(!isset($_SESSION["$matricula"])):
        session_destroy();
        session_unset();
        header('Location:index');
        exit();
    endif;
    
    //Se a matrícula informada na URL for diferente da matrícula registrada no login, destroy a sessao.
    if($_GET['m'] != $_SESSION["$matricula"]):
        session_destroy();
        session_unset();
        header('Location:index');
        exit();
    endif;

    //Proteger informações para cada leitor. Impedir que depois de logado o cliente tente acessar informações de outro cliente inserindo manualmente o código do cliente na URL.
    $getcolaborador 			= $_GET['m'];
    $matricula	                = $_SESSION['matricula'];

    if($matricula!=$getcolaborador):
        session_destroy();
        session_unset();
        header('Location:index');
    endif;

    //Para páginas que utilizam a matricula.
    if(isset($_GET['m'])):
        $matricula=$_GET['m'];
    endif;

    //Verifica se há uma sessão registro com o tempo que o usuário logou.
    if($_SESSION['registro']):
        $segundos = time() - $_SESSION['registro'];
    endif;

    //Se o cliente ficar uma 10 minutos sem interagir será realizado um logout automático.
    if($segundos > $_SESSION['limite']):
        session_destroy();
        header("Location:index?tempoesgotado");
    endif;

    //Defini quanto tempo o usuário tem de logado.
    if($_SESSION['registro']):
        $segundosparaotoken = time() - $_SESSION['registro'];
    endif;
    
    //Se o tempo que ele tem logado for menor que o limite definido é realizado uma busca para atualizar o token na URL impedindo que o usuário grave uma log e utilize para acessar as páginas manualmente.
    if($segundosparaotoken < $_SESSION['limitedotoken']):
        $buscartoken = "SELECT token FROM tokens GROUP BY token ORDER BY rand(), id DESC LIMIT 1";
            $rbuscartoken = mysqli_query($con, $buscartoken);
                $dbuscartoken=	mysqli_fetch_array($rbuscartoken);
        
        $_SESSION['token']=$dbuscartoken['token'];
            $token = $_SESSION['token'];
    endif;

    if(!isset($_SESSION["lideranca"])):
        header("location:logout");
    endif;
