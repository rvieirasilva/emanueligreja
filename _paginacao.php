<ul class="pagination">
    <?php    
        //Paginação, somar a quantidade de horarios.
        //$totaldepaginas;

        // DEFINI O LIMITE DE PÁGINAS ANTES E DEPOIS
        $limitedepaginas = 5;

        //Ver nome da página
        $PaginaInUser   = $_SERVER['REQUEST_URI']; ///podcasts?vermais=podcasts

        if(!empty($linkSeguro)):
            $linkSeguro_page = $linkSeguro."&"; //O link seguro traz o token do usuário logado. Se não houver usuário ficará em branco.
        endif;
        
        if(isset($_GET)):
            $PaginaInUser   = explode('?', $PaginaInUser); //podcasts ? vermais=podcasts
            $PaginaInUser   = $PaginaInUser[0]; //podcasts
            //$PaginaInUser_Get = $PaginaInUser[1]; // vermais=

            $link_paginacao = $PaginaInUser.$linkSeguro_page; 
        else:
            //$PaginaInUser   = explode('/', $PaginaInUser); //faelvieirasilva.com.br / mensagens
            //$PaginaInUser   = $PaginaInUser[1]; //mensagens
            //$PaginaInUser_Get = '';

            $link_paginacao = $PaginaInUser.$linkSeguro_page;
        endif;
        // echo $PaginaInUser;
    ?>
    <?php
        if($pagina > 1):
            $pagebackSeta = $pagina - 1;
        else:
            $pagebackSeta = '1';
        endif;
    ?>
    <li class="color-black">
    <a class="pagination-previous icon-left-open-big" href="<?php echo $link_paginacao;?>pagina=<?php echo $pagebackSeta;?>"></a>
    </li>
    
        <?php
        //PÁGINAS ANTERIORES A ATUAL 
        for ($pageback = $pagina - $limitedepaginas; $pageback <= $pagina - 1; $pageback++)
        {
            if($pageback >= 1) 
            {   ?>
                <li class="page-item"><a href="<?php echo $link_paginacao; if(isset($_GET['c'])): echo 'c='.$_GET['c'].'&'; endif;?>pagina=<?php echo $pageback;?>"><?php echo $pageback;?></a></li>
        <?php
            }
        } ?>
        
    <!-- PÁGINA ATUAL SEM LINK -->
    <li class="current"><a><?php echo $pagina;?></a></li>
    
    <?php
        //PÁGINAS POSTERIORES A ATUAL 
        for ($pagefront = $pagina + 1; $pagefront <= $pagina +$limitedepaginas; $pagefront++)
        {
            if($pagefront <= $totaldepaginas) 
            {   ?>
                <li class="page-item"><a href="<?php echo $link_paginacao; if(isset($_GET['c'])): echo 'c='.$_GET['c'].'&'; endif;?>pagina=<?php echo $pagefront;?>"><?php echo $pagefront;?></a></li>
        <?php
            }
        } ?>
    <!-- Exibe a última página -->
    <?php
        if($pagina < $totaldepaginas):
            $pagebackSetanext = $pagina + 1;
        else:
            $pagebackSetanext = $totaldepaginas;
        endif;
    ?>
    <li class="color-black">
    <a class="pagination-next icon-right-open-big" href="<?php echo $link_paginacao;?>pagina=<?php echo $pagebackSetanext;?>"></a>
    </li>
</ul>
