
<div class="content-inner blog-masonry grid-container pt-20 pb-30 bkg-white" data-layout-mode="masonry" data-grid-ratio="1.5" data-animate-resize data-set-dimensions data-animate-resize-duration="600" data-as-bkg-image>
    <div class="row">
        <div class="column width-12">
            <div class="row grid content-grid-3">
                <?php    
                    //AQUI ELE FAZ A PESQUISA PELO CAMPUS E TRAZ O DADO MAIS RECENTE PELO ANO, SE ESTAMOS EM 2010 ELE TRARÁ OS DADOS DESTE ANO OU DO ÚLTIMO ANO EM QUE O CAMPUS APRESENTOU UMA MATÉRIA REGISTRADA, COM BASE NISSO É FEITO UM FILTRO EXIBINDO POR ORDEM O DIA E DEPOIS O HORÁRIO.
                    $bMais = "SELECT referencia, titulo, textoancora, miniatura, video, categoria, datadapostagem, capa, visualizacoes FROM blog WHERE datadapostagem <= '$dataHora' AND categoria='Videos' ORDER BY datadapostagem DESC LIMIT $inicio, $postsporpagina";
                        $rMais = mysqli_query($con, $bMais);
                                $gridItem = 0;
                            while($dMais = mysqli_fetch_array($rMais)):
                                $gridItem++;											
                        $Msgs++;

                        $refDoPost    = $dMais['referencia'];
                        $titulodopost = $dMais['titulo'];
                        $titulo 	  = str_replace(' ', '-', $titulodopost);
                
                
                    $iframevideo=$dMais['video'];  
                        $iframevideo	= explode('/', $iframevideo); //https: // youtube.com / embed / w00JkhGoII0
                        @$videoiframe_URL	 = $iframevideo[2];
                    
                    if($gridItem == '1'): $siz=' grid-sizer wide large'; else: $siz=''; endif;
                ?>
                <div class="grid-item <? echo $siz; ?>">
                    <div class="thumbnail rounded overlay-fade-img-scale-in" data-hover-easing="easeInOut" data-hover-speed="700" data-hover-bkg-color="#000000" data-gradient data-gradient-spread="90%" data-hover-bkg-opacity="1.5">
                        <a class="overlay-link" href="post?<?php echo $linkSeguro;?>pos=<?php echo $dMais['referencia'];?>&<?php include "_filtroparalink.php";   $tituloamigavel = strTr($titulo, $filtro); echo $tituloamigavel; ?>">
                            <?php
                                if(!empty($dMais['video'])):
                            ?>
                            <?     
                                $iframevideo=$dMais['video'];  
                                    $iframevideo	= explode('/', $iframevideo); //https: // youtube.com / embed / w00JkhGoII0
                                    @$videoiframe_URL	 = $iframevideo[2];
                                if($videoiframe_URL === 'youtube.com'):
                            ?>
                                <iframe bkg="transparent" width='500' height='300' src="<? echo $dMais['video']; ?>?showinfo=0&amp;loop=1" poster="<? echo $dMais['capa']; ?>"></iframe>
                            <? else: ?>
                                <div class="row flex">
                                    <video controls loading='lazy' poster="<? echo $dMais['capa']; ?>">
                                        <source type='video/mp4' src='<? echo $dMais['video']; ?>'>
                                    </video>
                                </div>
                            <? endif; ?>
                            <?php
                                elseif(empty($dMais['video'])):
                            ?>
                            <img src="<?php echo $dMais['miniatura'];?>" alt="<?php echo $dMais['titulo'];?>"/>
                            <?php
                                endif;
                            ?>
                            <span class="overlay-info v-align-bottom center">
                                <span>
                                    <span>
                                        <span class="post-info">
                                            <span class="post-tags"><span><span class="post-tag label small rounded bkg-pink color-white bkg-hover-pink bkg-hover-white">
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
                <?endwhile;?>
            </div>
        </div>
    </div>
</div>