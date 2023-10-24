<!-- Side Navigation Menu -->
	<aside class="side-navigation-wrapper enter-right" data-no-scrollbar data-animation="slide-in">
		<div class="side-navigation-scroll-pane">
			<div class="side-navigation-inner">
				<div class="side-navigation-header">
					<div class="navigation-hide side-nav-hide">
						<a href="#">
							<span class="icon-cancel medium"></span>
						</a>
					</div>
				</div>
				<nav class="side-navigation nav-block">
					<ul>
						<?php
							include "./_menuOpcoesMob.php";
						?>
					</ul>\
				</nav>
				<div class="side-navigation-footer">
					<p class="copyright no-margin-bottom">&copy; <?php echo date('Y');?> Igreja Emanuel</p>
				</div>
			</div>
		</div>
	</aside>
	<!-- Side Navigation Menu End -->

	<div class="wrapper reveal-side-navigation">
		<div class="wrapper-inner">

			<!-- Header -->
			<header class="header header-absolute header-fixed-on-mobile header-transparent" data-bkg-threshold="100" data-sticky-threshold="40">
				<div class="header-inner-top header-inner-top-dark dark-2">
					<div class="nav-bar">
						<div class="row flex">
							<?php
								include "./_menuTopo.php";
							?>
						</div>
					</div>
				</div>
				<div class="header-inner">
					<div class="row nav-bar">
						<div class="column width-12 nav-bar-inner">
							<div class="logo">
								<div class="logo-inner">
									<?php
										include "./_menuLogo.php";
									?>
								</div>
							</div>
							<!-- <nav class="navigation nav-block secondary-navigation nav-right">
								<ul>
									<li>
										<!-- Dropdown Cart Overview ->
										<div class="dropdown">
											<a href="#" class="nav-icon cart button no-page-fade"><span class="cart-indication"><span class="icon-shopping-bag"></span> <span class="badge"></span></span></a>
											<ul class="dropdown-list custom-content cart-overview">
												<li class="cart-item">
													<a href="single-product-device.html" class="product-thumbnail">
														<img src="images/shop/cart/cart-thumb-small.jpg" alt="" />
													</a>
													<div class="product-details">
														<a href="single-product-device.html" class="product-title">
															Bo BeoPlay A1 Grey
														</a>
														<span class="product-quantity">1 x</span>
														<span class="product-price"><span class="currency">$</span>278.00</span>
														<a href="#" class="product-remove icon-cancel"></a>
													</div>
												</li>
												<li class="cart-item">
													<a href="single-product-device.html" class="product-thumbnail">
														<img src="images/shop/cart/cart-thumb-small-2.jpg" alt="" />
													</a>
													<div class="product-details">
														<a href="single-product-device.html" class="product-title">
															Camera Canon Eos Rebel
														</a>
														<span class="product-quantity">1 x</span>
														<span class="product-price"><span class="currency">$</span>1199.00</span>
														<a href="#" class="product-remove icon-cancel"></a>
													</div>
												</li>
												<li class="cart-item">
													<a href="single-product-device.html" class="product-thumbnail">
														<img src="images/shop/cart/cart-thumb-small-3.jpg" alt="" />
													</a>
													<div class="product-details">
														<a href="single-product-device.html" class="product-title">
															Apple AirPods
														</a>
														<span class="product-quantity">1 x</span>
														<span class="product-price"><span class="currency">$</span>169.00</span>
														<a href="#" class="product-remove icon-cancel"></a>
													</div>
												</li>
												<li class="cart-subtotal">
													Sub Total
													<span class="amount"><span class="currency">$</span>1685.00</span>
												</li>
												<li class="cart-actions">
													<a href="cart.html" class="view-cart mt-10">View Cart</a>
													<a href="checkout.html" class="checkout button small rounded">Checkout Now</a>
												</li>
											</ul>
										</div>
									</li>
									<li class="aux-navigation hide">
										<!-- Aux Navigation ->
										<a href="#" class="navigation-show side-nav-show nav-icon">
											<span class="icon-menu"></span>
										</a>
									</li>
								</ul>
							</nav> -->
							<nav class="navigation nav-block secondary-navigation nav-right">
								<ul>
									<?php
										if(isset($_SESSION['membro']) OR isset($_SESSION['lideranca']) OR isset($_SESSION['visitante'])):
									?>
									<li>
										<a href="logout" class="button small rounded no-page-fade no-label-on-mobile no-margin-bottom"><span class="icon-lock-open left"></span><span>Sair</span></a>
									</li>
									<?php
										else:
									?>
									<li>
										<!-- Dropdown Login -->
										<div class="v-align-middle">
											<div class="dropdown">
												<!-- Dropdown Login -->
												<div class="v-align-middle">
													<div class="dropdown">
														<?php
															include "./_menuLoginDrop.php";
														?>
													</div>
												</div>
											</div>
										</div>
									</li>
									<?php
										endif;
									?>
									<li class="aux-navigation hide">
										<!-- Aux Navigation -->
										<a href="#" class="navigation-show side-nav-show nav-icon">
											<span class="icon-menu"></span>
										</a>
									</li>
								</ul>
							</nav>
							<nav class="navigation nav-block primary-navigation nav-right">
								<ul>
									<?php
										include "./_menuOpcoesPc.php";
									?>								
								</ul>
							</nav>
						</div>
					</div>
				</div>
			</header>
			<!-- Header End -->
