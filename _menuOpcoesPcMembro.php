<li class="current contains-sub-menu sub-menu-indicator">
    <a href="index">Emanuel</a>
    <ul class="sub-menu">
        <li>
            <a href="quemsomos<?php if(!empty($linkSeguro)): echo $linkSeguro; endif; ?>">Quem somos</a>
        </li>
        <li>
            <a href="ministerio<?php if(!empty($linkSeguro)): echo $linkSeguro; endif; ?>">Ministério</a>
        </li>
    </ul>
</li>
<li>
    <a href="">Redes</a>
    <ul class="sub-menu">
        <?php
            $bRedes="SELECT nomedarede FROM personalizarredes GROUP BY nomedarede";
              $rRedes=mysqli_query($con, $bRedes);
                while($dRedes=mysqli_fetch_array($rRedes)):
        ?>
        <li>
            <a href="redes<?php if(!empty($linkSeguro)): echo $linkSeguro; endif; ?>r=<?php echo $dRedes['nomedarede'];?>">
                <?php echo $dRedes['nomedarede'];?>
            </a>
        </li>
        <?php
            endwhile;
        ?>
    </ul>
</li>
<li>
    <a href="">Células</a>
    <ul class="sub-menu">
        <li>
            <a href="downloadcelulas">Downloads</a>
        </li>
        <li>
            <a href="esbocodecelula">Esboço</a>
        </li>
    </ul>
</li>
<li>
    <a href="">Conteúdo</a>
    <ul class="sub-menu">        
        <?php
            $bCCon="SELECT categoria FROM blog group by categoria";
              $rCC=mysqli_query($con, $bCCon);
                while($dCC=mysqli_fetch_array($rCC)):
        ?>
        <li>
            <a href="news?c=<?php echo $dCC['categoria'].'&m='.$matricula.'&token='.$token;?>"><?php echo $dCC['categoria'];?></a>
        </li>
        <?php
            endwhile;
        ?>
    </ul>
</li>									
<li>
    <a href="live">
        Ao Vivo
    </a>
</li>	
<li>
    <a href="agenda">
        Agenda
    </a>
</li>									
<li>
    <a href="doacoes">
        Dizímo & Oferta
    </a>
</li>								
<li>
    <a href="extratofinanceiro">
        Transparência
    </a>
</li>									
