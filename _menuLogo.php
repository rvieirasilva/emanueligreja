<?php
    if(!isset($_SESSION['membro'])):
?>
<a href="https://igrejaemanuel.com.br"><img src="images/igreja-batista-emanuel-logo-Jesus-Cristo.png" alt="Igreja Batista Emanuel" /></a>
<a href="https://igrejaemanuel.com.br"><img src="images/igreja-batista-emanuel-logo-Jesus-Cristo-branco.png" alt="Igreja Batista Emanuel" /></a>
<?php
    else:
?>
<a href="emanuel?<?php echo $linkSeguro;?>"><img src="images/igreja-batista-emanuel-logo-Jesus-Cristo.png" alt="Igreja Batista Emanuel" /></a>
<a href="emanuel?<?php echo $linkSeguro;?>"><img src="images/igreja-batista-emanuel-logo-Jesus-Cristo-branco.png" alt="Igreja Batista Emanuel" /></a>
<?php
    endif;
?>
