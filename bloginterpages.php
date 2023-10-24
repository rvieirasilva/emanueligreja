
				<!-- Content Section -->
				<div class="section-block pb-40 bkg-grey-ultralight">
					<div class="row">
						<div class="column width-8 left">
							<h3><strong>Emanuel</strong> News</h3>
							<p class="lead mb-50">Estudos, artigos e mais conteúdo para impactar sua vida e te auxiliar a mudar sua história.</p>
						</div>
					</div>
				</div>
				<!-- Content Section End -->

				<!-- Portfolio Grid -->
				<div class="section-block grid-container fade-in-progressively small-margins pt-0 bkg-grey-ultralight" data-layout-mode="masonry" data-grid-ratio="1" data-animate-filter-duration="700" data-set-dimensions data-animate-resize data-animate-resize-duration="0.8" data-as-bkg-image>
					<div class="row">
						<div class="column width-12">
							<div class="row grid content-grid-3">
                                <?php
                                    $bPost = "SELECT titulo, miniatura, referencia FROM blog WHERE categoria!='Videos' AND miniatura !='' LIMIT 3";
                                      $rPost = mysqli_query($con, $bPost);
                                        while($dPost=mysqli_fetch_array($rPost)):
                                ?>
								<div class="grid-item grid-sizer">
									<div class="thumbnail rounded img-scale-in" data-hover-speed="1000">
										<img src="<?php echo $dPost['miniatura'];?>" alt="<?php echo $dPost['titulo'];?>"/>
										<div class="caption-over-outer">
											<div class="caption-over-inner v-align-bottom color-white">
												<p class="lead">
                                                    <a href="post?<?php echo $linkSeguro;?>&pos=<?php echo $dPost['referencia'];?>&<?php include "_filtroparalink.php";  $tituloamigavel = strTr($titulo, $filtro); echo $tituloamigavel; ?>">
                                                        <?php echo $dPost['titulo'];?>
                                                        <br><span class="icon-arrow-with-circle-right"></span>
                                                    </a>
                                                </p>
											</div>
										</div>
									</div>
								</div>
                                <?php
                                    endwhile;
                                ?>
							</div>
						</div>
					</div>
				</div>
				<!-- Portfolio Grid -->
