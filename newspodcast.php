<div class="blog-masonry grid-container fade-in-progressively" data-layout-mode="fitRows" data-grid-ratio="1.5" data-animate-resize data-animate-resize-duration="0">
    <div class="row">
        <div class="column width-12">                                
            <div class="column width-12 right right-on-mobile">
                <form action="" method='get' charset='utf-8'>
                    <div class="column width-10">
                        <div class="form-select form-element medium color-black right">
                            <select name="filter" tabindex="32" class="form-aux" data-label="">
                                <option selected="selected" value="">
                                    Limpar filtro
                                </option>
                                <option value="desc">
                                    Mais novo a mais antigo
                                </option>
                                <option value="asc">
                                    Mais antigo a mais novo
                                </option>
                            </select>
                        </div>
                    </div>
                    <div class="column width-2">
                        <button type="submit" class="button rounded small border-blue bkg-hover-blue color-blue color-hover-white">
                            Filtrar
                        </button>
                    </div>
                </form>
            </div>
            <div class="row grid content-grid-3">
                <?php  
                    //Trazer vídeos futuros.

                    // <!--CÓDIGO PARA FAZER PESQUISA DOS HORÁRIOS E CRIAR O LOOPING -->
                    $pagina = (isset($_GET['pagina']))? $_GET['pagina'] : 1;

                    $baudios= "SELECT id FROM blog WHERE NOT (datadapostagem > '$dataeng') AND categoria='Podcasts' ORDER BY datadapostagem DESC";
                        $rVid = mysqli_query($con, $baudios);
                        
                        $totaldeposts = mysqli_num_rows($rVid);

                        //Defini o número de horarios que serão exibidos por página
                        $postsporpagina = 9;

                        //defini número de páginas necessárias para exibir todos os horarios.
                        $totaldepaginas = ceil($totaldeposts / $postsporpagina);

                        $inicio = ($postsporpagina * $pagina) - $postsporpagina;


                    //AQUI ELE FAZ A PESQUISA PELO CAMPUS E TRAZ O DADO MAIS RECENTE PELO ANO, SE ESTAMOS EM 2010 ELE TRARÁ OS DADOS DESTE ANO OU DO ÚLTIMO ANO EM QUE O CAMPUS APRESENTOU UMA MATÉRIA REGISTRADA, COM BASE NISSO É FEITO UM FILTRO EXIBINDO POR ORDEM O DIA E DEPOIS O HORÁRIO.
                    if(isset($_GET['filter']) AND empty($_GET['filter'])):
                        $bAudiosAmanha = "SELECT referencia, titulo, textoancora, miniatura, audio, categoria, datadapostagem, capa FROM blog WHERE NOT (datadapostagem < '$dataeng') AND categoria='Podcasts' AND audio !='' GROUP BY titulo ORDER BY datadapostagem DESC, id DESC LIMIT 4";
                    elseif(isset($_GET['filter']) AND $_GET['filter'] == 'desc'):
                        $bAudiosAmanha = "SELECT referencia, titulo, textoancora, miniatura, audio, categoria, datadapostagem, capa FROM blog WHERE NOT (datadapostagem < '$dataeng') AND categoria='Podcasts' AND audio !='' GROUP BY titulo ORDER BY datadapostagem DESC, id DESC LIMIT 4";
                    elseif(isset($_GET['filter']) AND $_GET['filter'] == 'asc'):
                        $bAudiosAmanha = "SELECT referencia, titulo, textoancora, miniatura, audio, categoria, datadapostagem, capa FROM blog WHERE NOT (datadapostagem < '$dataeng') AND categoria='Podcasts' AND audio !='' GROUP BY titulo ORDER BY datadapostagem ASC, id ASC LIMIT 4";
                    endif;
                        $raudiosAmanha = mysqli_query($con, $bAudiosAmanha);
                            $naudiosAmanha = mysqli_num_rows($raudiosAmanha);
                                $PodCastt = 0;
                            while($dAuddAmanha = mysqli_fetch_array($raudiosAmanha)):
                                $PodCastt++;
                        //$titulodopost = $dAuddAmanha['titulo'];
                        $tituloAmanha 	  = str_replace(' ', '-', $titulodopost);
                    if($naudiosAmanha > 0):
                ?>
                <div class="grid-item grid-sizer">
                    <article class="post color-charcoal">
                        <h3 class="text-medium weight-light color-charcoal color-hover-charcoal">
                            <?php
                                $datePor = date(('d/m/Y'), strtotime($dAuddAmanha['datadapostagem']));
                                echo "<strong>Vídeo novo em: ".$datePor."</strong>";
                                    echo "<br>";
                                    //echo $dAuddAmanha['titulo'];
                            ?>
                        <br>
                        <?php
                            if(!isset($_SESSION["membro"]) AND !isset($_SESSION["lideranca"])):
                        ?>
                        <a data-content="inline" data-aux-classes="tml-newsletter-modal tml-exit-light pill" data-toolbar=""  href="#inscrever" class="lightbox-link">
                        <?php
                            elseif(isset($_SESSION["lideranca"])):
                        ?>
                        <!--<a href="vermais?<?php echo $linkSeguro;?>&vermais=Close&pos=<?php echo $dMais['referencia'];?>&<?php include "_filtroparalink.php";  $tituloamigavel = strTr($titulo, $filtro); echo $tituloamigavel; ?>">-->
                        <div class='mejs-container'>
                            <audio preload='none' src="<?php echo $dAuddAmanha['audio']; ?>"></audio>
                        </div>
                        <?php
                            elseif(isset($_SESSION["membro"])):
                        ?>
                        <a href="#">
                        <?php
                            endif;
                        ?>
                            <?php echo "<strong>Podcast</strong>: ".$PodCastt.' - '.$dAuddAmanha['titulo'];?>
                        </a></h3>
                    </article>
                </div>
                <?php 
                    endif;
                    endwhile;   
                    //Vídeos publicados

                    //AQUI ELE FAZ A PESQUISA PELO CAMPUS E TRAZ O DADO MAIS RECENTE PELO ANO, SE ESTAMOS EM 2010 ELE TRARÁ OS DADOS DESTE ANO OU DO ÚLTIMO ANO EM QUE O CAMPUS APRESENTOU UMA MATÉRIA REGISTRADA, COM BASE NISSO É FEITO UM FILTRO EXIBINDO POR ORDEM O DIA E DEPOIS O HORÁRIO.
                    if(isset($_GET['filter']) AND empty($_GET['filter'])):
                        $bAudios = "SELECT referencia, titulo, textoancora, miniatura, audio, categoria, datadapostagem, capa FROM blog WHERE NOT (datadapostagem > '$dataeng') AND categoria='Podcasts' AND audio !='' GROUP BY titulo ORDER BY datadapostagem DESC, id DESC LIMIT $inicio, $postsporpagina";
                    elseif(isset($_GET['filter']) AND $_GET['filter'] == 'desc'):
                        $bAudios = "SELECT referencia, titulo, textoancora, miniatura, audio, categoria, datadapostagem, capa FROM blog WHERE NOT (datadapostagem > '$dataeng') AND categoria='Podcasts' AND audio !='' GROUP BY titulo ORDER BY datadapostagem DESC, id DESC LIMIT $inicio, $postsporpagina";
                    elseif(isset($_GET['filter']) AND $_GET['filter'] == 'asc'):
                        $bAudios = "SELECT referencia, titulo, textoancora, miniatura, audio, categoria, datadapostagem, capa FROM blog WHERE NOT (datadapostagem > '$dataeng') AND categoria='Podcasts' AND audio !='' GROUP BY titulo ORDER BY datadapostagem ASC, id ASC LIMIT $inicio, $postsporpagina";
                    endif;
                        $raudios = mysqli_query($con, $bAudios);
                                $PodCastt_ = 0;
                            while($dAudd = mysqli_fetch_array($raudios)):
                                $PodCastt_++;
                        $Msgs++;

                        $titulodopost = $dAudd['titulo'];
                        $titulo 	  = str_replace(' ', '-', $titulodopost);
                ?>
                <div class="grid-item grid-sizer">
                    <article class="post">
                        <div class="post-media">
                            <div class="thumbnail" data-hover-easing="easeInOut" data-hover-speed="700" data-hover-bkg-color="#000000" data-hover-bkg-opacity="0.9">
                                <img src="<?php echo $dAudd['miniatura'] ;?>" style="width:350px; height:200px;" alt=""/>
                                <span class="overlay-info">
                                </span>
                            </div>
                        </div>
                        <div class=" pt-10 pb-0">
                            <h5 class="text-medium weight-light color-charcoal color-hover-charcoal">
                                <?php echo "<strong>Podcast ".$PodCastt_.'</strong> - '.$dAudd['titulo'];?>
                            </h5>
                            <div class='mejs-container'>
                                <audio preload='none' src="<?php echo $dAudd['audio']; ?>"></audio>
                            </div>
                        </div>                                                        
                    </article>
                </div>
                <?php endwhile;?>
            </div>
        </div>
    </div>
</div>