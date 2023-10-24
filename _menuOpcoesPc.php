<li class="current contains-sub-menu sub-menu-indicator">
    <a href="<?php echo $pageinicial; if(!empty($linkSeguro)): echo '?'.$linkSeguro; endif; ?>">Emanuel</a>
    <ul class="sub-menu">
        <li>
            <a href="quemsomos<?php if(!empty($linkSeguro)): echo '?'.$linkSeguro; endif; ?>">Quem somos</a>
        </li>
        <li>
            <a href="lideranca<?php if(!empty($linkSeguro)): echo '?'.$linkSeguro; endif; ?>">Liderança</a>
        </li>
        <li>
            <a href="eventos<?php if(!empty($linkSeguro)): echo '?'.$linkSeguro; endif; ?>">Agenda</a>
        </li>
        <?php
            if(!isset($_SESSION['membro'])):
        ?>
        <li>
            <a href="precadastrodemembro<?php if(!empty($linkSeguro)): echo '?'.$linkSeguro; endif; ?>">Pré-cadastro de membro</a>
        </li>
        <?php
            endif;
        ?>
        <li>
            <a href="discipuladorelatar?<?php if(!empty($linkSeguro)): echo $linkSeguro; endif; ?>">Enviar relatório de discipulado</a>
        </li>
        <li>
            <a href="estoquebaixa?<?php if(!empty($linkSeguro)): echo $linkSeguro; endif; ?>">Dar baixa no estoque</a>
        </li>
    </ul>
</li>
<li class="current">
    <a href="eventos<?php if(!empty($linkSeguro)): echo '?'.$linkSeguro; endif;?>">
        Eventos
    </a>
</li>	
<li class="current">
    <a href="">Redes</a>
    <ul class="sub-menu">
        <?php
            $bRedes="SELECT nomedarede FROM rede GROUP BY nomedarede";
              $rRedes=mysqli_query($con, $bRedes);
                while($dRedes=mysqli_fetch_array($rRedes)):
        ?>
        <li>
            <a href="redes<?php if(!empty($linkSeguro)): echo '?'.$linkSeguro; endif; ?>r=<?php echo $dRedes['nomedarede'];?>">
                <?php echo $dRedes['nomedarede'];?>
            </a>
        </li>
        <?php
            endwhile;
        ?>
    </ul>
</li>
<li class="current">
    <a href="">Células</a>
    <ul class="sub-menu">
        <li>
            <a href="celuladownload<?php if(!empty($linkSeguro)): echo '?'.$linkSeguro; endif;?>">Downloads</a>
        </li>
        <li>
            <a href="esbocodecelula<?php if(!empty($linkSeguro)): echo '?'.$linkSeguro; endif;?>">Esboço</a>
        </li>
        <?
            if(in_array('Secretário de célula', $ministerioArray) OR in_array('Secretária de célula', $ministerioArray) OR in_array('Líder de célula', $ministerioArray)):
        ?>
        <li>
            <a href="celularelatorio<?php if(!empty($linkSeguro)): echo '?'.$linkSeguro; endif;?>">Relatório de célula</a>
        </li>
        <?
            endif;
        ?>
    </ul>
</li>
<li class="current">
    <a href="">Conteúdo</a>
    <ul class="sub-menu">
        <?php
            $bCCon="SELECT categoria FROM blog WHERE categoria!='Esboço de célula' AND categoria !='' group by categoria";
            // $bCCon="SELECT categoria FROM blogcategorias WHERE categoria!='Esboço de célula' AND categoria !='' group by categoria";
            $rCC=mysqli_query($con, $bCCon);
                while($dCC=mysqli_fetch_array($rCC)):
                    // $categoriaregistrada=$dCC['categoria'];
                    // $postcategoriablog="SELECT id FROM blog WHERE categoria LIKE '%$categoriaregistrada%'";
                    //   $rpcb=mysqli_query($con, $postcategoriablog);
                    //     $npcb=mysqli_num_rows($rpcb);
                    // if($npcb > 0):
        ?>
        <li>
            <a href="news<?php if(!empty($linkSeguro)): echo '?'.$linkSeguro; endif;?>c=<?php echo $dCC['categoria'];?>"><?php echo $dCC['categoria'];?></a>
        </li>
        <?php
            // endif;
            endwhile;
        ?>
    </ul>
</li>	
<?php
    if(isset($_SESSION["membro"])):
        $ministerioArray = explode(',', $ministerio);
        
    if(in_array('Pastor', $ministerioArray) OR in_array("Pastora", $ministerioArray) OR in_array("Evangelista", $ministerioArray) OR in_array("Diacono", $ministerioArray) OR in_array("Diáconisa", $ministerioArray) OR in_array("Coordenador de rede", $ministerioArray) OR in_array("Líder de rede", $ministerioArray) OR in_array('Pastor', $ministerioArray) OR in_array("Pastora", $ministerioArray) OR in_array("Evangelista", $ministerioArray) OR in_array("Diacono", $ministerioArray) OR in_array("Diáconisa", $ministerioArray) OR in_array("Coordenador de rede", $ministerioArray) OR in_array("Líder de rede", $ministerioArray) OR $funcaoadministrativa == 'Presidente' OR $funcaoadministrativa == "Vice Presidente" OR $funcaoadministrativa == "Secretário" OR $funcaoadministrativa == "Tesoureiro" OR $funcaoadministrativa == "II Vice Presidente" OR $funcaoadministrativa == "II Secretário" OR $funcaoadministrativa == "II Tesoureiro" OR $funcaoadministrativa !== '' AND $ministerio !== "Membro"):
?>
            <li class="current">
                <a href="#" class="contains-sub-menu">Gestão ministerial</a>
                <ul class="sub-menu">
                    <?php
                        if(in_array('Membro', $ministerioArray) OR in_array('Pastor', $ministerioArray) OR in_array('Pastora', $ministerioArray) OR in_array('Evangelista', $ministerioArray) OR in_array('Diacono', $ministerioArray) OR in_array('Diáconisa', $ministerioArray) OR in_array('Coordenadorde de rede', $ministerioArray) OR in_array('Líderde de rede', $ministerioArray) OR in_array('Pastor', $ministerioArray) OR in_array('Pastora', $ministerioArray) OR in_array('Evangelista', $ministerioArray) OR in_array('Diacono', $ministerioArray) OR in_array('Diáconisa', $ministerioArray) OR in_array('Coordenado de célula', $ministerioArray) OR in_array('Líde de célula', $ministerioArray) OR $funcaoadministrativa == 'Presidente' OR $funcaoadministrativa == "Vice Presidente" OR $funcaoadministrativa == "Secretário" OR $funcaoadministrativa == "Tesoureiro" OR $funcaoadministrativa == "II Vice Presidente" OR $funcaoadministrativa == "II Secretário" OR $funcaoadministrativa == "II Tesoureiro" OR $funcaoadministrativa === "Tesoureira" OR $funcaoadministrativa === "II Tesoureira" OR $funcaoadministrativa !== ''):
                    ?>
                            <li>
                                <a href="#" class="contains-sub-menu"><span class="icon-line-graph color-blue"></span> Dashboad</a>
                                <ul class="sub-menu">
                                    <li>
                                        <a href="membroanalise<?php if(!empty($linkSeguro)): echo '?'.$linkSeguro; endif;?>">Análise pessoal</a>
                                    </li>
                                    <?php
                                        if($funcaoadministrativa === 'Presidente' OR $funcaoadministrativa === "Tesoureiro" OR $funcaoadministrativa === "II Tesoureiro" OR $funcaoadministrativa == "Vice Presidente" OR $funcaoadministrativa == "Secretário" OR $funcaoadministrativa == "Tesoureiro" OR $funcaoadministrativa == "II Vice Presidente" OR $funcaoadministrativa == "II Secretário" OR $funcaoadministrativa == "II Tesoureiro" OR $ministerio !== "Membro"):
                                    ?>
                                    <li>
                                        <a href="discipuladoanalise<?php if(!empty($linkSeguro)): echo '?'.$linkSeguro; endif;?>">Análise dos discipulados</a>
                                    </li>
                                    <li>
                                        <a href="analise<?php if(!empty($linkSeguro)): echo '?'.$linkSeguro; endif;?>">Análise Financeira</a>
                                    </li>
                                    <li>
                                        <a href="cultoanalise<?php if(!empty($linkSeguro)): echo '?'.$linkSeguro; endif;?>">Análise dos encontros</a>
                                    </li>
                                    <li>
                                        <a href="estoqueanalisar<?php if(!empty($linkSeguro)): echo '?'.$linkSeguro; endif;?>">Análise do Estoque</a>
                                    </li>
                                    <li>
                                        <a href="celulaanalise<?php if(!empty($linkSeguro)): echo '?'.$linkSeguro; endif;?>">Análise das células</a>
                                    </li>
                                    <? endif; ?>
                                </ul>
                            </li>
                            <li>
                                <a href="#" class="contains-sub-menu">Financeiro</a>
                                <ul class="sub-menu">
                                    <?php
                                        if($funcaoadministrativa === 'Presidente' OR $funcaoadministrativa === "Tesoureiro" OR $funcaoadministrativa === "II Tesoureiro" OR $funcaoadministrativa === "Tesoureira" OR $funcaoadministrativa === "II Tesoureira"):
                                    ?>
                                        <li>
                                            <a href="financeiroimport<?php if(!empty($linkSeguro)): echo '?'.$linkSeguro; endif;?>">Importar dados de um arquivo</a>
                                        </li>
                                        <li>
                                            <a href="financeiro<?php if(!empty($linkSeguro)): echo '?'.$linkSeguro; endif;?>">Registrar movimento</a>
                                        </li>
                                    <?php
                                        endif;
                                    ?>
                                    <li>
                                        <a href="analise<?php if(!empty($linkSeguro)): echo '?'.$linkSeguro; endif;?>">Análise financeira</a>
                                    </li>
                                </ul>
                            </li>
                        <?php
                            if(in_array('Pastor', $ministerioArray) OR in_array("Pastora", $ministerioArray) OR in_array("Evangelista", $ministerioArray) OR in_array("Diacono", $ministerioArray) OR in_array("Diáconisa", $ministerioArray) OR in_array('Pastor', $ministerioArray) OR in_array("Pastora", $ministerioArray) OR in_array("Evangelista", $ministerioArray) OR in_array("Diacono", $ministerioArray) OR in_array("Diáconisa", $ministerioArray) OR $funcaoadministrativa == 'Presidente' OR $funcaoadministrativa == "Vice Presidente" OR $funcaoadministrativa == "Secretário" OR $funcaoadministrativa == "Tesoureiro" OR $funcaoadministrativa == "II Vice Presidente" OR $funcaoadministrativa == "II Secretário" OR $funcaoadministrativa == "II Tesoureiro" OR $ministerio !== "Membro"):
                        ?>
                            <li>
                                <a href="#" class="contains-sub-menu">Conteúdo</a>
                                <ul class="sub-menu">
                                    <li>
                                        <a href="blogescrever<?php if(!empty($linkSeguro)): echo '?'.$linkSeguro; endif;?>">Publicar artigo</a>
                                    </li>
                                    <li>
                                        <a href="bloglistar<?php if(!empty($linkSeguro)): echo '?'.$linkSeguro; endif;?>">Editar artigo</a>
                                    </li>
                                    <li>
                                        <a href="fraseescrever<?php if(!empty($linkSeguro)): echo '?'.$linkSeguro; endif;?>">Publicar frase</a>
                                    </li>
                                    <li>
                                        <a href="fraselistar<?php if(!empty($linkSeguro)): echo '?'.$linkSeguro; endif;?>">Editar frase</a>
                                    </li>
                                </ul>
                            </li>
                        <?php
                            endif;
                            if(in_array('Pastor', $ministerioArray) OR in_array("Pastora", $ministerioArray) OR in_array("Diáconisa", $ministerioArray) OR $funcaoadministrativa == "Secretário" OR $funcaoadministrativa == "II Secretário" OR $funcaoadministrativa == "Vice Presiente" OR $funcaoadministrativa == "Tesoureiro" OR $funcaoadministrativa == "Tesoureira"):
                        ?>
                            <li>
                                <a href="#" class="contains-sub-menu">Gestão de equipe</a>
                                <ul class="sub-menu">
                                    <li>
                                        <a href="equipemembrolistar<?php if(!empty($linkSeguro)): echo '?'.$linkSeguro; endif;?>">Editar/Aprovar membro</a>
                                    </li>
                                    <li>
                                        <a href="equipeemail<?php if(!empty($linkSeguro)): echo '?'.$linkSeguro; endif;?>">Enviar e-mail para liderança</a>
                                    </li>
                                    <li>
                                        <a href="discipuladorelatar?<?php if(!empty($linkSeguro)): echo $linkSeguro; endif;?>">Enviar relatório de discipulado</a>
                                    </li>
                                    <li>
                                        <a href="discipuladoanalise?<?php if(!empty($linkSeguro)): echo $linkSeguro; endif;?>">Análisar discipulados</a>
                                    </li>
                                </ul>
                            </li>
                            <li>
                                <a href="#" class="contains-sub-menu">Eventos/Agenda</a>
                                <ul class="sub-menu">
                                    <li>
                                        <a href="agendacadastrar<?php if(!empty($linkSeguro)): echo '?'.$linkSeguro; endif;?>">Cadastrar agenda</a>
                                    </li>
                                    <li>
                                        <a href="agendalistar<?php if(!empty($linkSeguro)): echo '?'.$linkSeguro; endif;?>">Editar</a>
                                    </li>
                                </ul>
                            </li>
                        <?php
                            endif;
                            if($funcaoadministrativa == 'Presidente' OR $funcaoadministrativa == "Vice Presidente" OR $funcaoadministrativa == "Secretário" OR $funcaoadministrativa == "II Vice Presidente" OR $funcaoadministrativa == "II Secretário" OR $ministerio == "Coordenador de rede" OR $ministerio == "Líder de rede"):
                        ?>
                            <li>
                                <a href="#" class="contains-sub-menu">Campus / Igreja</a>
                                <ul class="sub-menu">
                                    <li>
                                        <a href="estoqueadicionar<?php if(!empty($linkSeguro)): echo '?'.$linkSeguro; endif;?>">Registrar Estoque</a>
                                    </li>
                                    <li>
                                        <a href="estoqueeditarlist<?php if(!empty($linkSeguro)): echo '?'.$linkSeguro; endif;?>">Editar Estoque</a>
                                    </li>
                                    <li class="offset-1">
                                        <a href="estoquebaixa<?php if(!empty($linkSeguro)): echo '?'.$linkSeguro; endif;?>">Dar baixa no estoque</a>
                                    </li>
                                    <li>
                                        <a href="estoqueanalisar<?php if(!empty($linkSeguro)): echo '?'.$linkSeguro; endif;?>">Análisar estoque</a>
                                    </li>
                                    <li>
                                        <a href="relatoriodeculto<?php if(!empty($linkSeguro)): echo '?'.$linkSeguro; endif;?>">Relatório de culto</a>
                                    </li>
                                    <li>
                                        <a href="cultoeditarrelatorio<?php if(!empty($linkSeguro)): echo '?'.$linkSeguro; endif;?>">Editar relatório de culto</a>
                                    </li>
                                    <li>
                                        <a href="cultoanalise<?php if(!empty($linkSeguro)): echo '?'.$linkSeguro; endif;?>">Análise de culto</a>
                                    </li>
                                    <?php
                                        if($funcaoadministrativa == 'Presidente' OR $funcaoadministrativa == "Vice Presidente"):
                                    ?>
                                    <li>
                                        <a href="campusabrir<?php if(!empty($linkSeguro)): echo '?'.$linkSeguro; endif;?>">Abrir campus</a>
                                    </li>
                                    <?php
                                        endif;
                                    ?>
                                    <li>
                                        <a href="campuseditar<?php if(!empty($linkSeguro)): echo '?'.$linkSeguro; endif;?>">Editar campus</a>
                                    </li>
                                </ul>
                            </li>
                            <li>
                                <a href="#" class="contains-sub-menu">Rede / Departamento</a>
                                <ul class="sub-menu">
                                    <!-- <li>
                                        <a href="rederelatorio<?php if(!empty($linkSeguro)): echo '?'.$linkSeguro; endif;?>">Relatório de rede</a>
                                    </li>
                                    <li>
                                        <a href="redeanalise<?php if(!empty($linkSeguro)): echo '?'.$linkSeguro; endif;?>">Análise de rede</a>
                                    </li> -->
                                    <?php
                                        if($funcaoadministrativa == 'Presidente' OR $funcaoadministrativa == "Vice Presidente" OR $ministerio == "Coordenador de rede"):
                                    ?>
                                    <li>
                                        <a href="redeabrir<?php if(!empty($linkSeguro)): echo '?'.$linkSeguro; endif;?>">Abrir rede</a>
                                    </li>
                                    <?php
                                        endif;
                                    ?>
                                    <li>
                                        <a href="redeeditar<?php if(!empty($linkSeguro)): echo '?'.$linkSeguro; endif;?>">Editar rede</a>
                                    </li>
                                </ul>
                            </li>
                        <?php
                            endif;
                            if($ministerio !== in_array("Membro", $ministerioArray) AND in_array('Pastor', $ministerioArray) OR in_array("Pastora", $ministerioArray) OR in_array("Evangelista", $ministerioArray) OR in_array("Diacono", $ministerioArray) OR in_array("Diáconisa", $ministerioArray) OR in_array("Coordenador de rede", $ministerioArray) OR in_array("Líder de célula", $ministerioArray) OR in_array("Líder de rede", $ministerioArray) OR in_array('Pastor', $ministerioArray) OR in_array("Pastora", $ministerioArray) OR in_array("Evangelista", $ministerioArray) OR in_array("Diacono", $ministerioArray) OR in_array("Diáconisa", $ministerioArray) OR in_array("Coordenador de rede", $ministerioArray) OR in_array("Líder de célula", $ministerioArray) OR in_array("Líder de rede", $ministerioArray) OR $funcaoadministrativa == 'Presidente' OR $funcaoadministrativa == "Vice Presidente" OR $funcaoadministrativa == "Secretário" OR $funcaoadministrativa == "Tesoureiro" OR $funcaoadministrativa == "II Vice Presidente" OR $funcaoadministrativa == "II Secretário" OR $funcaoadministrativa == "II Tesoureiro"):
                        ?>
                            <li>
                                <a href="#" class="contains-sub-menu">Célula</a>
                                <ul class="sub-menu">
                                    <li>
                                        <a href="celularelatorio<?php if(!empty($linkSeguro)): echo '?'.$linkSeguro; endif;?>">Relatório de célula</a>
                                    </li>
                                    <li>
                                        <a href="celulaanalise<?php if(!empty($linkSeguro)): echo '?'.$linkSeguro; endif;?>">Análise de célula</a>
                                    </li>
                                    <?php
                                        if(in_array('Líder de célula', $ministerioArray) OR in_array('Coordenador de rede', $ministerioArray) OR $funcaoadministrativa == 'Presidente' OR $funcaoadministrativa == 'Vice Presidente' OR $funcaoadministrativa == 'Secretário' OR $funcaoadministrativa == 'II Secretário'):
                                    ?>
                                    <li>
                                        <a href="celulaesboco<?php if(!empty($linkSeguro)): echo '?'.$linkSeguro; endif;?>">Publicar esboço</a>
                                    </li>
                                    <li>
                                        <a href="celulaesbocolistar<?php if(!empty($linkSeguro)): echo '?'.$linkSeguro; endif;?>">Editar esboço</a>
                                    </li>
                                    <li>
                                        <a href="celulaabrir<?php if(!empty($linkSeguro)): echo '?'.$linkSeguro; endif;?>">Abrir célula</a>
                                    </li>
                                    <li>
                                        <a href="celulalistar<?php if(!empty($linkSeguro)): echo '?'.$linkSeguro; endif;?>">Editar célula</a>
                                    </li>
                                    <?php
                                        endif;
                                    ?>
                                </ul>
                            </li>
                        <?php
                            endif;
                            if(in_array('Membro', $ministerioArray) OR in_array('Pastor', $ministerioArray) OR in_array("Pastora", $ministerioArray) OR in_array("Evangelista", $ministerioArray) OR in_array('Pastor', $ministerioArray) OR in_array("Pastora", $ministerioArray) OR in_array("Evangelista", $ministerioArray) OR $funcaoadministrativa == 'Presidente' OR $funcaoadministrativa == "Vice Presidente" OR $funcaoadministrativa == "II Vice Presidente" OR $funcaoadministrativa == "Líder de rede" AND $rededepartamento = 'Mídia e Comunicação'):
                        ?>
                        <li>
                            <a href="#" class="contains-sub-menu">Personalizar site</a>
                            <ul class="sub-menu">
                                <li>
                                    <a href="addimagemtopo<?php if(!empty($linkSeguro)): echo '?'.$linkSeguro; endif;?>">Adicionar imagem no site</a>
                                </li>
                                <li>
                                    <a href="alterarimagemdotopo<?php if(!empty($linkSeguro)): echo '?'.$linkSeguro; endif;?>">Alterar imagens do site</a>
                                </li>
                                <li>
                                    <a href="liveprogramar<?php if(!empty($linkSeguro)): echo '?'.$linkSeguro; endif;?>">Programar live</a>
                                </li>
                                <!-- <li>
                                    <a href="personalizar<?php if(!empty($linkSeguro)): echo '?'.$linkSeguro; endif;?>">Personalizar geral</a>
                                </li> -->
                                <li>
                                    <a href="editarindex<?php if(!empty($linkSeguro)): echo '?'.$linkSeguro; endif;?>">Página inicial</a>
                                </li>
                                <li>
                                    <a href="editarquemsomos<?php if(!empty($linkSeguro)): echo '?'.$linkSeguro; endif;?>">Página Quem somos</a>
                                </li>
                            </ul>
                        </li>
                    <?php
                        endif;
                        endif;
                    ?>
                </ul>
            </li>
<?php
    endif;
    endif;
?>		
<?php
    if(!isset($_SESSION["membro"])):
?>									
<li class="current">
    <a href="live<?php if(!empty($linkSeguro)): echo '?'.$linkSeguro; endif;?>">
        Ao Vivo
    </a>
</li>
<? endif; ?>	
<li class="current">
    <a href="frases<?php if(!empty($linkSeguro)): echo '?'.$linkSeguro; endif;?>">
        Frases
    </a>
</li>									
<li class="current">
    <a href="doacoes<?php if(!empty($linkSeguro)): echo '?'.$linkSeguro; endif;?>">
        Dizímo & Oferta
    </a>
</li>	
<?php
    if(isset($_SESSION["membro"])):
?>							
<li>
    <a href="configurar<?php if(!empty($linkSeguro)): echo '?'.$linkSeguro; endif;?>">
        Configurar
    </a>
</li>	
<?php
    endif;
?>
