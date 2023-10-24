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
					<?php
                        include "./_menuOpcoesMob.php";
                    ?>
				</nav>
				<div class="side-navigation-footer">
					<p class="copyright no-margin-bottom">&copy; <?php echo date('Y'); ?> Igreja Batista Emanuel.</p>
				</div>
			</div>
		</div>
	</aside>
	<!-- Side Navigation Menu End -->

	<div class="wrapper reveal-side-navigation">
		<div class="wrapper-inner">

			<!-- Header -->
			<header class="header header-absolute header-fixed-on-mobile nav-dark" data-bkg-threshold="100" data-sticky-threshold="40">
				<div class="header-inner-top">
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
							<nav class="navigation nav-block secondary-navigation nav-right">
								<ul>
									<?php
										if(isset($_SESSION['membro']) OR isset($_SESSION['lideranca'])):
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
