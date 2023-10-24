
                                        <div class="section-block blog-masonry grid-container pt-20 pb-30 bkg-white" data-layout-mode="masonry" data-grid-ratio="1.5" data-animate-resize data-set-dimensions data-animate-resize-duration="600" data-as-bkg-image>
                                            <div class="row">
                                                <div class="column width-12">
                                                    <div class="row grid content-grid-3">
                                                        <?php    
                                                            if(!isset($_GET['ms']) OR empty($_GET['ms'])):
                                                                $selectmes="";
                                                            elseif(isset($_GET['ms']) AND !empty($_GET['ms'])):
                                                                $selectmes=" datadoinicio LIKE '%$mesdoevento$' AND";
                                                            endif;

                                                            //AQUI ELE FAZ A PESQUISA PELO CAMPUS E TRAZ O DADO MAIS RECENTE PELO ANO, SE ESTAMOS EM 2010 ELE TRARÁ OS DADOS DESTE ANO OU DO ÚLTIMO ANO EM QUE O CAMPUS APRESENTOU UMA MATÉRIA REGISTRADA, COM BASE NISSO É FEITO UM FILTRO EXIBINDO POR ORDEM O DIA E DEPOIS O HORÁRIO.
                                                            if(!isset($_SESSION['membro'])):
                                                                if(isset($_GET['ms']) AND !empty($_GET['ms'])):
                                                                    $bMais = "SELECT video, miniatura, evento, codigodoevento, datadoinicio, datadotermino, valor FROM agenda WHERE datadoinicio LIKE '%$mesdoevento%' AND ano='$ano' AND visibilidade='Externo' GROUP BY codigodoevento ORDER BY datadoinicio DESC";
                                                                else:
                                                                    $bMais = "SELECT video, miniatura, evento, codigodoevento, datadoinicio, datadotermino, valor FROM agenda WHERE ano='$ano' AND visibilidade='Externo' GROUP BY codigodoevento ORDER BY datadoinicio DESC";
                                                                endif;
                                                            else:
                                                                if(isset($_GET['ms']) AND !empty($_GET['ms'])):
                                                                    $bMais = "SELECT video, miniatura, evento, codigodoevento, datadoinicio, datadotermino, valor FROM agenda WHERE datadoinicio LIKE '%$mesdoevento%' AND ano='$ano' GROUP BY codigodoevento ORDER BY datadoinicio ASC";
                                                                else:
                                                                    $bMais = "SELECT video, miniatura, evento, codigodoevento, datadoinicio, datadotermino, valor FROM agenda WHERE ano='$ano' GROUP BY codigodoevento ORDER BY datadoinicio ASC";
                                                                endif;
                                                            endif;
                                                                $rMais = mysqli_query($con, $bMais);
                                                                        $gridItem = 0;
                                                                    while($dMais = mysqli_fetch_array($rMais)):
                                                                        $gridItem++;											
                                                                $Msgs++;
                                                                
                                                                // $SlideDoPost            = $dMais['slidedeminis'];
                                                                $miniaturasDoPost[]     = $dMais['miniatura'];
                                                                $refDoPost              = $dMais['codigodoevento'];
                                                                $titulodopost           = $dMais['evento'];
                                                                $titulo 	            = str_replace(' ', '-', $titulodopost);
                                                                $datadoinicio=$dMais['datadoinicio'];
                                                                $datadoinicio = date(('d/m/Y'), strtotime($datadoinicio));
                                                                $datadotermino=$dMais['datadotermino'];
                                                                $datadotermino = date(('d/m/Y'), strtotime($datadotermino));
                                                            if($gridItem === 1):
                                                        ?>
                                                        <div class="grid-item portrait">
                                                            <div class="thumbnail tm-slider-container content-slider post-slider rounded overlay-fade-img-scale-in" data-hover-easing="easeInOut" data-hover-speed="700" data-hover-bkg-color="#000000" data-gradient data-gradient-spread="70%" data-hover-bkg-opacity="0.75">
                                                                <a class="overlay-link" href="evento?<?php echo $linkSeguro;?>pos=<?php echo $dMais['codigodoevento'];?>&<?php include "_filtroparalink.php";   $tituloamigavel = strTr($titulo, $filtro); echo $tituloamigavel; ?>">
                                                                    <?php
                                                                        if(!empty($dMais['video'])):
                                                                    ?>
                                                                    <div class="grid-item grid-sizer wide large">
                                                                    <?php
                                                                        elseif(empty($dMais['video'])):
                                                                    ?>
                                                                    <ul class="tms-slides">	
                                                                        <?php
                                                                            if(!isset($_SESSION['membro'])):
                                                                                if(isset($_GET['ms']) AND !empty($_GET['ms'])):
                                                                                    $bMiniaturas="SELECT miniatura, evento FROM agenda WHERE NOT (miniatura = '') AND datadoinicio LIKE '%$mesdoevento%' AND codigodoevento='$refDoPost' AND visibilidade='Externo' GROUP BY miniatura ORDER BY datadoinicio DESC, id ASC";
                                                                                else:
                                                                                    $bMiniaturas="SELECT miniatura, evento FROM agenda WHERE NOT (miniatura = '') AND codigodoevento='$refDoPost' AND visibilidade='Externo' GROUP BY miniatura ORDER BY datadoinicio DESC, id ASC";
                                                                                endif;
                                                                            else:
                                                                                if(isset($_GET['ms']) AND !empty($_GET['ms'])):
                                                                                    $bMiniaturas="SELECT miniatura, evento FROM agenda WHERE NOT (miniatura = '') AND datadoinicio LIKE '%$mesdoevento%' AND codigodoevento='$refDoPost' GROUP BY miniatura ORDER BY datadoinicio DESC, id ASC";
                                                                                else:
                                                                                    $bMiniaturas="SELECT miniatura, evento FROM agenda WHERE NOT (miniatura = '') AND codigodoevento='$refDoPost' GROUP BY miniatura ORDER BY datadoinicio DESC, id ASC";
                                                                                endif;
                                                                            endif;
                                                                            $rMini=mysqli_query($con, $bMiniaturas);
                                                                            $nMini = mysqli_num_rows($rMini);
                                                                            while($dMiniaturas=mysqli_fetch_array($rMini)):
                                                                        ?>
                                                                        <li class="tms-slide" data-image data-force-fit data-as-bkg-image>
                                                                            <img data-src="<?php echo $dMiniaturas['miniatura']; ?>" src="images/blank.png" alt="<?php echo $dMiniaturas['evento']; ?>"/>
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
                                                                                        R$ <?php echo $dMais['valor'];?>
                                                                                    </span></span></span>
                                                                                </span>
                                                                                <span class="post-title">
                                                                                <?php echo $dMais['evento'].' / '.$datadoinicio.' até '.$datadotermino;?>
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
                                                                <a class="overlay-link" href="evento?<?php echo $linkSeguro;?>pos=<?php echo $dMais['codigodoevento'];?>&<?php include "_filtroparalink.php";   $tituloamigavel = strTr($titulo, $filtro); echo $tituloamigavel; ?>">
                                                                    <?php
                                                                        if(!empty($dMais['video'])):
                                                                    ?>
                                                                    <div class="grid-item grid-sizer wide large">
                                                                    <?php
                                                                        elseif(empty($dMais['video'])):
                                                                    ?>
                                                                    <ul class="tms-slides">	
                                                                        <?php
                                                                            if(!isset($_SESSION['membro'])):
                                                                                if(isset($_GET['ms']) AND !empty($_GET['ms'])):
                                                                                    $bMiniaturas="SELECT miniatura, evento FROM agenda WHERE NOT (miniatura = '')
                                                                                    AND datadoinicio LIKE '%$mesdoevento%' AND visibilidade='Externo' AND codigodoevento='$refDoPost' GROUP BY miniatura ORDER BY datadoinicio DESC, id ASC";
                                                                                else:
                                                                                    $bMiniaturas="SELECT miniatura, evento FROM agenda WHERE NOT (miniatura = '') AND visibilidade='Externo' AND codigodoevento='$refDoPost' GROUP BY miniatura ORDER BY datadoinicio DESC, id ASC";
                                                                                endif;
                                                                            else:
                                                                                if(isset($_GET['ms']) AND !empty($_GET['ms'])):
                                                                                    $bMiniaturas="SELECT miniatura, evento FROM agenda WHERE NOT (miniatura = '')
                                                                                    AND datadoinicio LIKE '%$mesdoevento%' AND codigodoevento='$refDoPost' GROUP BY miniatura ORDER BY datadoinicio DESC, id ASC";
                                                                                else:
                                                                                    $bMiniaturas="SELECT miniatura, evento FROM agenda WHERE NOT (miniatura = '') AND codigodoevento='$refDoPost' GROUP BY miniatura ORDER BY datadoinicio DESC, id ASC";
                                                                                endif;
                                                                            endif;
                                                                            $rMini=mysqli_query($con, $bMiniaturas);
                                                                            $nMini = mysqli_num_rows($rMini);
                                                                            while($dMiniaturas=mysqli_fetch_array($rMini)):
                                                                        ?>
                                                                        <li class="tms-slide" data-image data-force-fit data-as-bkg-image>
                                                                            <img data-src="<?php echo $dMiniaturas['miniatura']; ?>" src="images/blank.png" style="width:300px; height:300px;" alt="<?php echo $dMiniaturas['evento']; ?>"/>
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
                                                                                    <span class="post-tags"><span><span class="post-tag label small rounded bkg-green color-white color-hover-white bkg-hover-green">R$ <?php echo $dMais['valor'];?></span></span></span>
                                                                                </span>
                                                                                <span class="post-title">
                                                                                    <?php echo $dMais['evento'].' / '.$datadoinicio.' até '.$datadotermino;?>
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