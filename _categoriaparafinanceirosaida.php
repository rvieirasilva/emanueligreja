<!DOCTYPE html>
	<?php
		session_start();
        include "_con.php";
        include "_configuracao.php";
        
?>
<html lang="pt-br">
	<?php
		include "_head.php";
	?>
<body class="shop catalogue-products">

    <!-- Content -->
    <div class="content clearfix">
        <?php
            if(isset($_POST['btn-categoria'])):
                $categoria      = mysqli_escape_string($con, $_POST['categoria']);
                $tipodecategoria      = 'DEBITO';

                //Impedir inserção duplicada.
                $bcategoria = "SELECT categoria FROM categoriasfinanceiro WHERE empresa='Emanuel' AND urldaempresa='igrejaemanuel' AND tipodecategoria='$categoria'";
                    $rcategoria = mysqli_query($con, $bcategoria);
                    $ncategoria=mysqli_num_rows($rcategoria);
                
                if($ncategoria < 1):
                    //Limitar cinco categorias
                    $fivecategorias="SELECT id FROM categoriasfinanceiro WHERE empresa='Emanuel' AND urldaempresa='igrejaemanuel' AND tipodecategoria='$tipodecategoria'";
                        $rFC=mysqli_query($con, $fivecategorias);
                        $nFC=mysqli_num_rows($rFC);

                            $codigodacategoria = $nFC.mt_rand(01, 999);

                    if($nFC < 10):
                        $addcategoria = "INSERT INTO categoriasfinanceiro (empresa, codigodoparceiro, urldaempresa, dia, mes, ano, representante, codigodorepresentante, codigodacategoria, categoria, tipodecategoria) VALUES ('Emanuel', '0001', 'igrejaemanuel', '$dia', '$mes', '$ano', '$nomedousuario', '$matriculausuario', '$codigodacategoria', '$categoria', '$tipodecategoria')";
                        mysqli_query($con, $addcategoria);
                    endif;
                endif;
            endif;
        ?>
        <form charset="UTF-8" class="form" action="" method="post" enctype="Multipart/form-data">
            <div class="column width-11">
                <div class="field-wrapper">
                    <input type="text" name="categoria" class="form-email form-element rounded medium" tabindex="4" placeholder="Adicionar categoria." required>
                </div>
            </div>
            
            <div class="column width-1">
                <button type="submit" value="" name='btn-categoria' class="form-submit button pill small bkg-green bkg-hover-green color-white color-hover-white hard-shadow"><span class="icon-plus color-white color-hover-white circled"></span></button>
            </div>
        </form>
    </div>
    <!-- Content End -->

	<!-- Js -->
	<?php
		include "_scripts.php";
	?>
</body>
</html>
