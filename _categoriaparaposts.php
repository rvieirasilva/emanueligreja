<?php {
    ?>
    <!DOCTYPE html>
	
    <html>
    <?php
        session_start();
        include "_con.php";
        include "_head.php"; //Arquivo de Head para páginas genéricas.
    ?>
    <body>
            <?php
               if(isset($_POST['btn-addcategoria'])):
                    $categoria = mysqli_escape_string($con, $_POST['categoria']);
                        
                    $duplicado="SELECT * FROM blogcategorias WHERE categoria='$categoria'";
                    $rd=mysqli_query($con, $duplicado);
                    $nd=mysqli_num_rows($rd);
                    if($nd > 0):
                        $_SESSION['mensagem']="Já existe está categoria registrada.";
                    else:
                        $inserir="INSERT INTO blogcategorias (categoria) VALUES ('$categoria')";
                        mysqli_query($con, $inserir);
                        $_SESSION['mensagem']="Categoria adicionada com sucesso, <strong>atualize a página</strong>.";
                        //header("Location:_categoriaparaposts.php");
                    endif;
                endif;
            ?>
        <form action="" method="post">
            <?php
                //AQUI É EXIBIDO UMA MENSAGEM CASO EXISTA NO BD UMA NOTA PARA AVALIAÇÃO QUE O PROFESSOR ESCOLHEU.
                if(isset($_SESSION['mensagem'])):
                    echo "<div class='box left rounded small bkg-purple color-white dismissable'>";
                        echo $_SESSION['mensagem'];		
                    echo "</div>";
                    UNSET($_SESSION['mensagem']);
                endif;
                //print_r($inserir);
            ?>
            <div class="column width-11">
                <div class="field-wrapper">
                    <input type="text"  name="categoria" maxlength="100" class="form-aux form-date form-element large" placeholder="Adicionar nova categoria" tabindex="5">
                </div>
            </div>
            <div class="column width-1">
                <div class="field-wrapper">
                    <button type="submit"  name="btn-addcategoria" maxlength="100" class=" button pill bkg-hover-theme color-white color-hover-white bkg-theme small" tabindex="5"><span class="icon-plus color-white color-hover-white circled"></span></button>
                </div>
            </div>
        </form>
        
    <?php
		include "_scripts.php"; //Páginas com todos os scripts de fundo de página.
	?>
    </body>
    </html>

<?php }
?>
