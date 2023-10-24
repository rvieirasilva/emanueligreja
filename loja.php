<!DOCTYPE html>
<?php
	session_start();
	if(!empty($_GET['lng'])):
		$idioma = $_GET['lng'];
	elseif(empty($_GET['lng']) OR !isset($_GET['lng'])):
		$idioma = 'pt-BR';
	endif;

	include "./_con.php";
	// include "./_configuracao.php";
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
            <div class="screen">
                <div class="content clearfix">
                    <div class="section-block tm-slider-parallax-container pb-30 small bkg-blue">
                        <div class="row">
                            <div class="box rounded small bkg-white shadow">
                                <div class="column width-12">
                                    <div class="title-container">
                                        <div class="title-container-inner">
                                            <div class="row flex">
                                                <div class="column width-8 v-align-middle">
                                                    <div>
                                                        <h1 class="mb-0"><strong>Store</strong></h1>
                                                        <!-- <p class="text-large color-charcoal mb-0 mb-mobile-20">Por isso não corro sem objetivo.</p> -->
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
                            <?php include "./_notificacaomensagem.php"; ?>
                            <div class="box rounded rounded shadow border-blue bkg-white">
                                <div class="column width-12">
                                    <!-- Galeria de produtos -->
                                    <div id="product-grid" class="section-block grid-container products fade-in-progressively no-padding-top" data-layout-mode="masonry" data-grid-ratio="1.5" data-animate-resize data-animate-resize-duration="700">
                                        <div class="row">
                                            <div class="column width-12">
                                                <div class="row grid content-grid-3">
                                                    <?php
                                                        $produtosopcionais="SELECT * FROM produtos GROUP BY produto";
                                                            $rpo=mysqli_query($con, $produtosopcionais);
                                                                while($dpo=mysqli_fetch_array($rpo)):
                                                                
                                                                $vde = $dpo['valor'];
                                                                $fatordocashback = '0.05'; //5% de cashback

                                                                $perccashback = $fatordocashback * 100;
                                                                // $vpt 		  = str_replace(',','.', $dbp['valor']);

                                                                $cashbackdoproduto = $vde * $fatordocashback;
                                                                $cashbackdoproduto = number_format($cashbackdoproduto, 0, '.', ',');

                                                                $recuperar    = $vde * $fatordocashback;
                                                                $moeda        = $fatordocashback * 10;

                                                                $dpontos 	  = $recuperar / $moeda; //Pega o valor recuperado pelo cliente na compra e divide pela moeda. (fator: 0.05 = moeda: 0,50)
                                                                $dpontos	  = number_format($dpontos, 0, '.', ',');
                                                    ?>
                                                    
                                                    <div class="grid-item product portrait grid-sizer">
                                                        <div class="thumbnail product-thumbnail img-scale-in" data-hover-easing="easeInOut" data-hover-speed="700" data-hover-bkg-color="#000000" data-hover-bkg-opacity="0.01">
                                                            <span class="onsale">R$ <?php echo $dpo['valor'];?></span>
                                                            <a class="overlay-link" href="<?php echo $dpo['pagseguro'];?>">
                                                                <img src="<?php echo $dpo['miniatura'];?>"> <!-- style="width:500px;height:350px;" alt=""/>-->
                                                            </a>
                                                            <div class="product-actions center">
                                                                <!-- <?php
                                                                    if(isset($_SESSION['cliente'])):
                                                                ?>
                                                                    <a href="produto?ref=<?php echo $dpo['codigodoproduto'];?><?php echo '&cc='.$codigodocliente.'&token='.$token;?>" class="button pill add-to-cart-button medium">COMPRAR</a>
                                                                <?php
                                                                    endif;
                                                                    if(isset($_SESSION['criativo'])):
                                                                ?>
                                                                    <a href="produto?ref=<?php echo $dpo['codigodoproduto'];?><?php echo '&cc='.$matricula.'&token='.$token;?>" class="button pill add-to-cart-button medium">COMPRAR</a>
                                                                <?php
                                                                    endif;
                                                                ?> -->
                                                                <form action="compraverificarlogin.php?<?php echo $linkSeguro;?>" method="POST" charset="UTF-8">
                                                                    <div class="column width-4">
                                                                        <input type="hidden" name="codigodoprodutoselec" value="<?php echo $dpo['codigodoproduto'];?>"/>
                            
                                                                        <input type="hidden" name="valordoprodutoselec" value="<?php echo $vde;?>"/>
                            
                                                                        <input type="hidden" name="cashbackdoprodutoselec" value="<?php echo $cashbackdoproduto;?>"/>
                            
                                                                        <input type="hidden" name="pontosdoprodutoselec" value="<?php echo $dpontos;?>"/>
                                                                    </div>
                                                                    <div class="column width-12">
                                                                        <!-- Icone do carrinho -->
                                                                        <button type="submit" name="btn-carrinho" class="button add-to-cart-button rounded small">
                                                                            <span class="icon-shopping-cart color-white"></span>
                                                                        </button>
                            
                                                                        <!-- <a data-content="inline" data-aux-classes="tml-promotion-modal tml-padding-small tml-swap-exit-light height-auto rounded" data-toolbar="" data-modal-mode data-modal-width="1140" data-modal-animation="scaleIn" data-lightbox-animation="fadeIn" href="#<?php echo $dpo['codigodoproduto']; ?>" class="lightbox-link button add-to-cart-button rounded small">
                                                                            Detalhes
                                                                        </a> <?php ;?> -->

                                                                        <button type="submit" name="btn-detalhes" class="button add-to-cart-button rounded small"><span class="icon-circle-with-plus color-white"></span> Detalhes</button>

                                                                        <!-- Icone de compra -->
                                                                        <button type="submit" name="btn-compra" class="button add-to-cart-button rounded small">COMPRAR</button>
                                                                    </div>                                        
                                                                </form>
                                                            </div>
                                                        </div>
                                                        
                                                        <div class="product-details center">
                                                            <h3 class="product-title">
                                                                <a href="#">
                                                                    <?php echo $dpo['produto'];?>
                                                                </a>
                                                            </h3>
                                                            <?php
                                                                if($dpo['desconto'] != ''):
                                                                {
                                                            ?>
                                                            <span class="product-price price"><del><span class="amount">R$ <?php echo $dpo['desconto'];?></span></del><ins>
                                                            <?php
                                                                }
                                                                endif;
                                                            ?>
                                                            <span class="amount">R$ <?php echo $dpo['valor'];?></span></ins></span>
                                                            <div class="product-actions-mobile">
                                                                <!-- <?php
                                                                    if(isset($_SESSION['cliente'])):
                                                                ?>
                                                                    <a href="produto?ref=<?php echo $dpo['codigodoproduto'];?><?php echo '&cc='.$codigodocliente.'&token='.$token;?>" class="button pill add-to-cart-button medium">COMPRAR</a>
                                                                <?php
                                                                    endif;
                                                                    if(isset($_SESSION['criativo'])):
                                                                ?>
                                                                    <a href="produto?ref=<?php echo $dpo['codigodoproduto'];?><?php echo '&cc='.$matricula.'&token='.$token;?>" class="button pill add-to-cart-button medium">COMPRAR</a>
                                                                <?php
                                                                    endif;
                                                                ?> -->
                                                                
                                                                <form action="compraverificarlogin.php?<?php echo $linkSeguro;?>" method="POST" charset="UTF-8">
                                                                    <div class="column width-4">
                                                                        <input type="hidden" name="codigodoprodutoselec" value="<?php echo $dpo['codigodoproduto'];?>"/>
                            
                                                                        <input type="hidden" name="valordoprodutoselec" value="<?php echo $vde;?>"/>
                            
                                                                        <input type="hidden" name="cashbackdoprodutoselec" value="<?php echo $cashbackdoproduto;?>"/>
                            
                                                                        <input type="hidden" name="pontosdoprodutoselec" value="<?php echo $dpontos;?>"/>
                                                                    </div>
                                                                    <div class="column width-12">
                                                                        <!-- Icone do carrinho -->
                                                                        <button type="submit" name="btn-carrinho" class="button add-to-cart-button rounded small"><span class="icon-shopping-cart color-white"></span></button>
                            
                                                                        <button type="submit" name="btn-detalhes" class="button add-to-cart-button rounded small"><span class="icon-circle-with-plus color-white"></span> Detalhes</button>
                                                                        
                                                                        <!-- Icone de compra -->
                                                                        <button type="submit" name="btn-compra" class="button add-to-cart-button rounded small">COMPRAR</button>
                                                                    </div>                                        
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <?php endwhile;?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Galeria de produtos -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Content End -->
                <div class="printable"></div>
                                
                <!-- Especificações Modal Simple -->
                <div id="especificacoes" class="pt-70 pb-50 hide">
                    <div class="row">
                        <div class="column width-10 offset-1 center">
                            <!-- Info -->
                            <h2 class="mb-10"><strong><?php echo $produto;?></strong></h2>
                            <p class="mb-30 color-charcoal">
                                <strong>Código do produto: </strong> <?php echo $codigodoproduto;?>
                                <br>
                                
                                    <?php
                                        if($dprod['categoria'] !== 'Evento'):
                                    ?>
                                    <strong>VENDIDO E ENTREGUE POR:</strong>
                                    <?php
                                        else:
                                    ?>
                                    <strong>ORGANIZADO POR:</strong>
                                    <?php
                                        endif;
                                    ?>
                                <?php echo $empresa = $dprod['empresa']; if(!empty($empresa)): echo $empresa; else: echo "Emanuel"; endif;?></p>
                            <!-- Info End -->

                            <!-- Signup -->
                            <div class="signup-form-container">
                                <p align="justify" class="color-charcoal">
                                    <?php echo $especificacoes;?>
                                </p>
                            </div>
                            <!-- Signup End -->

                        </div>
                    </div>
                </div>
                <!-- Especificações Modal Simple End -->
            </div>

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