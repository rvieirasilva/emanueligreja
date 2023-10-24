<?php
    if(isset($_SESSION['mensagem'])):
        if(isset($_SESSION['cordamensagem'])):
            $cordamensagem = $_SESSION['cordamensagem'];
        else:
            $cordamensagem = "blue";
        endif;
        echo "<div class='column width-6 offset-6'>";
            echo "<div class='box bkg-$cordamensagem color-white rounded dismissable shadow'>";
                echo $_SESSION['mensagem'];
            echo "</div>";
        echo "</div>";
        unset($_SESSION['cordamensagem']);
        unset($_SESSION['mensagem']);
    endif;
