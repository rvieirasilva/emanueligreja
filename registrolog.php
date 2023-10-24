<?php

    if(isset($_SESSION["membro"])):
        //Cria o arquivo e escreve a mensagem do log | É preciso criar a >>> PASTA e nomea-la com o codigo do cliente
        @mkdir("arq/", 0777); //Cria a pasta se não houver. o @ é pra ocultar erro caso ela exista.
        @mkdir("arq/logs/", 0777); //Cria a pasta se não houver. o @ é pra ocultar erro caso ela exista.
        @mkdir("arq/logs/membros/", 0777); //Cria a pasta se não houver. o @ é pra ocultar erro caso ela exista.
        @mkdir("arq/logs/membros/$matricula/", 0777); //Cria a pasta se não houver. o @ é pra ocultar erro caso ela exista.
        $arquivo = "arq/logs/membros/$matricula/$anoatual-$mes-$matricula.txt"; //Terá um log para cada mês do ano.
        $arquivo2 = fopen("arq/logs/membros/$matricula/$anoatual-$mes-$matricula.txt", "a+"); // 1 - CRIAR ARQUIVO DE LOG no endereço informado em arquivo.
        fwrite($arquivo2, $mensagem); // 2 - ESCREVER NO ARQUIVO.
        fclose($arquivo2); //Fecha o arquivo
            //Insere as informações do log no banco de dados.
            $arq = $arquivo; //Pega o endereço do arquivo onde foi registrado o log
            $gravarlogs = "INSERT INTO logs (cliente, codigodocliente, cnpj, acessou, ip, hora, acao, saiuem, arquivo) VALUES ('$nomedousuario', '$matricula', '', '$dia', '$ip', '$hora', '$mensagem', '','$arq')";
                mysqli_query($con, $gravarlogs);
        //FIM DO LOG QUE REGISTRA O USUÁRIO NESTA PÁGINA.
    elseif(isset($_SESSION["visitante"])):
        //Cria o arquivo e escreve a mensagem do log | É preciso criar a >>> PASTA e nomea-la com o codigo do cliente
        @mkdir("arq/", 0777); //Cria a pasta se não houver. o @ é pra ocultar erro caso ela exista.
        @mkdir("arq/logs/", 0777); //Cria a pasta se não houver. o @ é pra ocultar erro caso ela exista.
        @mkdir("arq/logs/visitante/", 0777); //Cria a pasta se não houver. o @ é pra ocultar erro caso ela exista.
        @mkdir("arq/logs/visitante/$matricula/", 0777); //Cria a pasta se não houver. o @ é pra ocultar erro caso ela exista.
        $arquivo = "arq/logs/visitante/$matricula/$anoatual-$mes-$matricula.txt"; //Terá um log para cada mês do ano.
        $arquivo2 = fopen("arq/logs/visitante/$matricula/$anoatual-$mes-$matricula.txt", "a+"); // 1 - CRIAR ARQUIVO DE LOG no endereço informado em arquivo.
        fwrite($arquivo2, $mensagem); // 2 - ESCREVER NO ARQUIVO.
        fclose($arquivo2); //Fecha o arquivo
            //Insere as informações do log no banco de dados.
            $arq = $arquivo; //Pega o endereço do arquivo onde foi registrado o log
            $gravarlogs = "INSERT INTO logs (cliente, codigodocliente, cnpj, acessou, ip, hora, acao, saiuem, arquivo) VALUES ('$nomedousuario', '$matricula', '', '$dia', '$ip', '$hora', '$mensagem', '','$arq')";
                mysqli_query($con, $gravarlogs);
        //FIM DO LOG QUE REGISTRA O USUÁRIO NESTA PÁGINA.
    endif;
