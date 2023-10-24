<li class="current">
    <a href="#" class="contains-sub-menu">Emanuel</a>
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
        <li>
            <a href="precadastrodemembro<?php if(!empty($linkSeguro)): echo '?'.$linkSeguro; endif; ?>">Pré-cadastro de membro</a>
        </li>
    </ul>
</li>
<li class="">
    <a href="#" class="contains-sub-menu">Redes</a>
    <ul class="sub-menu">
        <?php
            $bRedes="SELECT nomedarede FROM rede GROUP BY nomedarede";
              $rRedes=mysqli_query($con, $bRedes);
                while($dRedes=mysqli_fetch_array($rRedes)):
        ?>
        <li>
            <a href="redes?<?php if(!empty($linkSeguro)): echo '?'.$linkSeguro; endif; ?>r=<?php echo $dRedes['nomedarede'];?>">
                <?php echo $dRedes['nomedarede'];?>
            </a>
        </li>
        <?php
            endwhile;
        ?>
    </ul>
</li>
<li class="">
    <a href="#" class="contains-sub-menu">Células</a>
    <ul class="sub-menu">
    <li>
            <a href="celuladownload<?php if(!empty($linkSeguro)): echo $linkSeguro; endif;?>">Downloads</a>
        </li>
        <li>
            <a href="esbocodecelula<?php if(!empty($linkSeguro)): echo $linkSeguro; endif;?>">Esboço</a>
        </li>
    </ul>
</li>
<li class="">
    <a href="#" class="contains-sub-menu">Conteúdo</a>
    <ul class="sub-menu">    
        <?php
            $bCCon="SELECT categoria FROM blog WHERE categoria!='Esboço de célula' AND categoria !='' group by categoria";
            $rCC=mysqli_query($con, $bCCon);
                while($dCC=mysqli_fetch_array($rCC)):
        ?>
        <li>
            <a href="news<?php if(!empty($linkSeguro)): echo '?'.$linkSeguro; endif;?>c=<?php echo $dCC['categoria'];?>"><?php echo $dCC['categoria'];?></a>
        </li>
        <?php
            endwhile;
        ?>
    </ul>
</li>											
<li>
    <a href="live<?php if(!empty($linkSeguro)): echo '?'.$linkSeguro; endif;?>">
        Ao Vivo
    </a>
</li>	
<li>
    <a href="frases<?php if(!empty($linkSeguro)): echo '?'.$linkSeguro; endif;?>">
        Frases
    </a>
</li>									
<li>
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
