
                                <div class="content-inner blog-masonry grid-container pt-20 pb-30 bkg-white" data-layout-mode="masonry" data-grid-ratio="1.5" data-animate-resize data-set-dimensions data-animate-resize-duration="600" data-as-bkg-image>
                                <div class="row">
                                    <div class="column width-12">
                                        <div class="row grid content-grid-3">
                                            <?php    
                                                //AQUI ELE FAZ A PESQUISA PELO CAMPUS E TRAZ O DADO MAIS RECENTE PELO ANO, SE ESTAMOS EM 2010 ELE TRARÁ OS DADOS DESTE ANO OU DO ÚLTIMO ANO EM QUE O CAMPUS APRESENTOU UMA MATÉRIA REGISTRADA, COM BASE NISSO É FEITO UM FILTRO EXIBINDO POR ORDEM O DIA E DEPOIS O HORÁRIO.
                                                $bMais = "SELECT referencia, titulo, textoancora, miniatura, video, categoria, datadapostagem, capa, visualizacoes FROM blog WHERE datadapostagem <= '$dataHora' AND categoria='Close' GROUP BY referencia ORDER BY datadapostagem DESC LIMIT $inicio, $postsporpagina";
                                                    $rMais = mysqli_query($con, $bMais);
                                                            $gridItem = 0;
                                                        while($dMais = mysqli_fetch_array($rMais)):
                                                            $gridItem++;											
                                                    $Msgs++;
        
                                                    
                                                    $SlideDoPost            = $dMais['slidedeminis'];
                                                    $miniaturasDoPost[]     = $dMais['miniatura'];
                                                    $refDoPost              = $dMais['referencia'];
                                                    $titulodopost           = $dMais['titulo'];
                                                    $titulo 	            = str_replace(' ', '-', $titulodopost);
                                                if($gridItem === 1):
                                            ?>
                                                <?php
                                                    if(!empty($dMais['video'])):
                                                ?>
                                                <div class="grid-item grid-sizer wide large">
                                                <?php
                                                    elseif(empty($dMais['video'])):
                                                ?>
                                                <div class="grid-item portrait">
                                                <?php
                                                    endif;
                                                ?>
                                                <div class="thumbnail tm-slider-container content-slider post-slider rounded overlay-fade-img-scale-in" data-hover-easing="easeInOut" data-hover-speed="700" data-hover-bkg-color="#000000" data-gradient data-gradient-spread="70%" data-hover-bkg-opacity="0.75">
                                                    <?php
                                                        if(!isset($_SESSION["membro"]) and !isset($_SESSION["lideranca"])):
                                                    ?>
                                                        <a class="link" href="cadastrar">
                                                    <?php
                                                        else:
                                                    ?>
                                                    <a class="overlay-link" href="post?<?php echo $linkSeguro;?>pos=<?php echo $dMais['referencia'];?>&<?php include "_filtroparalink.php";   $tituloamigavel = strTr($titulo, $filtro); echo $tituloamigavel; ?>">
                                                    <?php
                                                        endif;
                                                    ?>
                                                        <?php
                                                            if(!empty($dMais['video'])):
                                                        ?>
                                                        <iframe  src="<?php echo $dMais['video'];?>" width="500" height="300"></iframe>
                                                        <?php
                                                            elseif(empty($dMais['video'])):
                                                        ?>
                                                        <ul class="tms-slides">	
                                                            <?php
                                                                $bMiniaturas="SELECT miniatura, titulo FROM blog WHERE NOT (miniatura = '') AND referencia='$refDoPost' GROUP BY miniatura ORDER BY id ASC ";
                                                                $rMini=mysqli_query($con, $bMiniaturas);
                                                                $nMini = mysqli_num_rows($rMini);
                                                                while($dMiniaturas=mysqli_fetch_array($rMini)):
                                                            ?>
                                                            <li class="tms-slide" data-image data-force-fit data-as-bkg-image>
                                                                <?php
                                                                    if(!isset($_SESSION["membro"]) and !isset($_SESSION["lideranca"])):
                                                                ?>
                                                                    <img src="img/site/close_block.jpeg" alt=""/>
                                                                <?php
                                                                    else:
                                                                ?>
                                                                <img data-src="<?php echo $dMiniaturas['miniatura']; ?>" src="images/blank.png" alt="<?php echo $dMiniaturas['titulo']; ?>"/>
                                                                <?php
                                                                    endif;
                                                                ?>
                                                            </li>
                                                            <?php
                                                                endwhile;
                                                            ?>
                                                        </ul>
                                                        <?php
                                                            endif;
                                                        ?>
                                                        <span class="overlay-info v-align-bottom center">
                                                            <span>
                                                                <span>
                                                                    <span class="post-info">
                                                                        <span class="post-tags"><span><span class="post-tag label small rounded bkg-green color-white color-hover-white bkg-hover-green">
                                                                        <?php echo $dMais['categoria'];?>
                                                                        </span></span></span>
                                                                    </span>
                                                                    <span class="post-title">
                                                                    <?php echo $dMais['titulo'];?>
                                                                    </span>
                                                                    <span class="post-info">
                                                                        <span class="post-date"><?php echo $dMais['visualizacoes'];?> views</span>
                                                                    </span>
                                                                </span>
                                                            </span>
                                                        </span>
                                                    </a>
                                                </div>
                                            </div>
                                            <?php
                                                elseif($gridItem > 1):
                                            ?>
                                            <div class="grid-item portrait">
                                                <div class="thumbnail tm-slider-container content-slider post-slider rounded overlay-fade-img-scale-in" data-hover-easing="easeInOut" data-hover-speed="700" data-hover-bkg-color="#000000" data-gradient data-gradient-spread="70%" data-hover-bkg-opacity="0.75">
                                                    <?php
                                                        if(!isset($_SESSION["membro"]) and !isset($_SESSION["lideranca"])):
                                                    ?>
                                                        <a class="link" href="cadastrar">
                                                    <?php
                                                        else:
                                                    ?>
                                                    <a class="overlay-link" href="post?<?php echo $linkSeguro;?>pos=<?php echo $dMais['referencia'];?>&<?php include "_filtroparalink.php";   $tituloamigavel = strTr($titulo, $filtro); echo $tituloamigavel; ?>">
                                                    <?php
                                                        endif;
                                                    ?>
                                                        <?php
                                                            if(!empty($dMais['video'])):
                                                        ?>
                                                        <div class="grid-item grid-sizer wide large">
                                                        <?php
                                                            elseif(empty($dMais['video'])):
                                                        ?>
                                                        <ul class="tms-slides">	
                                                            <?php
                                                                $bMiniaturas="SELECT miniatura, titulo FROM blog WHERE NOT (miniatura = '') AND referencia='$refDoPost' GROUP BY miniatura ORDER BY id ASC ";
                                                                $rMini=mysqli_query($con, $bMiniaturas);
                                                                $nMini = mysqli_num_rows($rMini);
                                                                while($dMiniaturas=mysqli_fetch_array($rMini)):
                                                            ?>
                                                            <li class="tms-slide" data-image data-force-fit data-as-bkg-image>
                                                                <?php
                                                                    if(!isset($_SESSION["membro"]) and !isset($_SESSION["lideranca"])):
                                                                ?>
                                                                    <img src="img/site/close_block.jpeg" alt=""/>
                                                                <?php
                                                                    else:
                                                                ?>
                                                                <img data-src="<?php echo $dMiniaturas['miniatura']; ?>" src="images/blank.png" style="width:300px; height:300px;" alt="<?php echo $dMiniaturas['titulo']; ?>"/>
                                                                <?php
                                                                    endif;
                                                                ?>
                                                            </li>
                                                            <?php
                                                                endwhile;
                                                            ?>
                                                        </ul>
                                                            <?php
                                                                endif;
                                                            ?>
                                                        <span class="overlay-info v-align-bottom center">
                                                            <span>
                                                                <span>
                                                                    <span class="post-info">
                                                                        <span class="post-tags"><span><span class="post-tag label small rounded bkg-green color-white color-hover-white bkg-hover-green"><?php echo $dMais['categoria'];?></span></span></span>
                                                                    </span>
                                                                    <span class="post-title">
                                                                        <?php echo $dMais['titulo'];?>
                                                                    </span>
                                                                    <span class="post-info">
                                                                        <span class="post-date"><?php echo $dMais['visualizacoes'];?> views</span>
                                                                    </span>
                                                                </span>
                                                            </span>
                                                        </span>
                                                    </a>
                                                </div>
                                            </div>
                                            <?php
                                                    endif;
                                                endwhile;
                                            ?>
                                        </div>
                                    </div>
                                </div>
                            </div>