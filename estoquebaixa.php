<!DOCTYPE html>
<?php
	session_start();
	if(!empty($_GET['lng'])):
		$idioma = $_GET['lng'];
	elseif(empty($_GET['lng']) OR !isset($_GET['lng'])):
		$idioma = 'pt-BR';
	endif;

	include "./_con.php";
	include "./_configuracao.php";    
    
    if(isset($_POST['btn-baixaestoque'])):
        $codigodoproduto        = mysqli_escape_string($con, $_POST['codigodoproduto']);
        // $situacaodoproduto      = mysqli_escape_string($con, $_POST['situacaodoproduto']);
        $situacaodoestoqueencontrado      = mysqli_escape_string($con, $_POST['situacaodoestoqueencontrado']);
        $disponivelnoestoque      = mysqli_escape_string($con, $_POST['disponivelnoestoque']);
        $itemaberto      = mysqli_escape_string($con, $_POST['itemaberto']);

        $quantidadeposbaixa= $disponivelnoestoque - $itemaberto;
        if($situacaodoestoqueencontrado == 'Fechado'):
            $situacaodoestoqueencontrado = "Em uso";
        elseif($situacaodoestoqueencontrado == 'Em uso' AND $quantidadeposbaixa < 1):
            $situacaodoestoqueencontrado = "Esgotado";
        else:
            $situacaodoestoqueencontrado = $situacaodoestoqueencontrado;
        endif;


        $cpf                    = mysqli_escape_string($con, $_POST['cpf']);
            $cpfVerify=base64_encode($cpf); 
            $cpfVerify=base64_encode($cpfVerify);
        
        $VerificarMembro="SELECT id FROM membros WHERE cpf='$cpfVerify'";
          $rVC=mysqli_query($con, $VerificarMembro);
            $nVC=mysqli_num_rows($rVC);
        
        if($nVC > 0):
            $upEst="UPDATE estoque SET situacaodoproduto='$situacaodoestoqueencontrado', quantidade='$quantidadeposbaixa' WHERE codigodoestoque='$codigodoproduto'";
            if(mysqli_query($con, $upEst)):
                $_SESSION['cordamensagem']="green";
                $_SESSION['mensagem']="Estoque atualizado com sucesso.";
            else:
                $_SESSION['cordamensagem']="red";
                $_SESSION['mensagem']="Verifique o código do produto, não foi possível dar baixa no estoque.";
            endif;
        else:
            $_SESSION['cordamensagem']="red";
            $_SESSION['mensagem']="Está ação só pode ser realizada por um membro cadastrado, sendo membro da Emanuel; <a href='./entrar'>cadastre-se</a>";
        endif;
    endif;

    if(isset($_POST['btn-buscarestoque'])):
        $codigodoproduto=mysqli_escape_string($con, $_POST['codigodoproduto']);
        $bP="SELECT situacaodoproduto, quantidade FROM estoque WHERE codigodoestoque='$codigodoproduto'";
          $rP=mysqli_query($con, $bP);
            $dP=mysqli_fetch_array($rP);
        
        $situacaodoprodutoencontrada=$dP['situacaodoproduto'];
        $quantidadeencontrada=$dP['quantidade'];
    endif;
?>
<html lang="pt-BR">
<head>
	<? include "./_head.php"; ?>
</head>
<body class="shop blog home-page">

    <?php
        // if(isset($_SESSION["membro"])):
            include "./_menu.php";
        // endif;
	?>
			<!-- Content -->
			<div class="content clearfix">
                <div class="section-block tm-slider-parallax-container pb-0 small bkg-blue">
					<div class="row">
                        <div class="box rounded small bkg-white shadow">
						    <div class="column width-12">
                                <div class="title-container">
                                    <div class="title-container-inner">
                                        <div class="row flex">
                                            <div class="column width-12 v-align-middle">
                                                <div>
                                                    <h1 class="mb-0">Dar baixa neste item do estoque</h1>
                                                    <p class="text-large color-charcoal mb-0 mb-mobile-20"></strong></p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
							</div>
						</div>
					</div>
				</div>
                <div class="section-block team-2 pt-50 bkg-grey-ultralight">
                    <div class="row">
                        <?php
                            include "./_notificacaomensagem.php";
                        ?>
                        <!-- <div class="box rounded bkg-white shadow"> -->
						<div class="column width-8 offset-2">
							<div>
								<div class="signup-box box rounded xlarge mb-0 bkg-blue border-blue-light">
                                    <h3 class="color-white"><strong>Dar baixa no estoque</strong>.</h3>
                                    <div class="register-form-container">
                                        <form class="login-form" action="<? echo $_SERVER['REQUEST_URI'];?>" method="post" charset="UTF-8">
                                            <div class="row">
                                                <?
                                                    if(!isset($_POST['btn-buscarestoque'])):
                                                ?>
                                                <div class="column width-12">
                                                    <input type="hidden" value="<?php echo $urldaempresa;?>" name="urldaempresa">
                                                    <div class="field-wrapper">
                                                        <input type="text" name="codigodoproduto" <? if(isset($_GET['idb'])): $baixaC=$_GET['idb']; echo "value='$baixaC'"; endif;?> class="form-element medium rounded login-form-textfield" placeholder="Código de estoque do item" required>
                                                    </div>
                                                </div>
                                                <div class="row no-margins">
                                                    <div class="column width-6">
                                                        <button type="submit" value="Entrar" name="btn-buscarestoque" class="button small rounded bkg-white bkg-hover-white hard-shadow"><span class="text-medium color-blue color-hover-blue-light weight-bold">BUSCAR ITEM</span>
                                                    </div>
                                                </div>
                                                
                                                <?
                                                    endif;
                                                    if(isset($_POST['btn-buscarestoque'])):
                                                        if($quantidadeencontrada > 0):
                                                        if(!isset($_SESSION['membro'])):
                                                ?>
                                                <div class="column width-12">
                                                    <div class="field-wrapper">
                                                        <input type="password" name="cpf" class="form-element medium rounded login-form-textfield" placeholder="Seu CPF" required>
                                                    </div>
                                                </div>
                                                <? else: ?>
                                                    <input type="hidden" value="<? echo $cpfdomembro; ?>" name='cpf' />
                                                <? endif; ?>
                                                    <input type="hidden" value="<? echo $situacaodoprodutoencontrada; ?>" name='situacaodoestoqueencontrado' />
                                                    <input type="hidden" value="<? echo $quantidadeencontrada; ?>" name='disponivelnoestoque' />
                                                <div class="column width-12">
                                                    <input type="hidden" value="<?php echo $urldaempresa;?>" name="urldaempresa">
                                                    <div class="field-wrapper">
                                                        <input type="text" name="codigodoproduto" readonly value="<? echo $codigodoproduto?>" class="form-element medium rounded login-form-textfield" placeholder="Código de estoque do item" required>
                                                    </div>
                                                </div>
                                                <div class="column width-12">
                                                    <div class="field-wrapper">
                                                        <span class="color-white">Informe a quantidade de itens que foram <strong>abertos</strong>.</span>
                                                        <input type="number" min="1" max="<? echo $quantidadeencontrada?>" step="1" name="itemaberto" class="form-element medium rounded login-form-textfield" value="1" required>
                                                    </div>
                                                </div>
                                                <!-- <div class="column width-6">
                                                    <span class="color-white"><strong>Situação</strong> do produto</span>
                                                    <div class="form-select form-element medium">
                                                        <select name="situacaodoproduto" tabindex="019" class="form-aux" data-label="sexo">
                                                            <option>Em uso</option>
                                                            <option>Esgotado</option>
                                                        </select>
                                                    </div>
                                                </div> -->
                                                <div class="row no-margins">
                                                    <div class="column width-6">
                                                        <button type="submit" value="Entrar" name="btn-baixaestoque" class="button small rounded bkg-white bkg-hover-white hard-shadow"><span class="text-medium color-blue color-hover-blue-light weight-bold">DAR BAIXA</span>
                                                    </div>
                                                </div>
                                                <? else: ?>
                                                    <h4 class="color-white">Este item está esgotado.</h4>
                                                <? endif; endif;?>
                                            </div>
                                        </form>
                                    </div>
                                </div>
							</div>
						</div>
                    </div>
                </div>
			</div>
			<!-- Content End -->

			<!-- Footer -->
			<footer class="footer footer-blue">
				<? include "./_footer_top.php"; ?>
			</footer>
			<!-- Footer End -->

		</div>
	</div>

	<? include "./_script.php"; ?>
</body>
</html>