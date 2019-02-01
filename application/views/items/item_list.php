		
		<!-- Page Heading -->
		<h1 class="my-4"><?= $title ?></h1>
		<div class="row">
			<?php foreach($items as $row) { ?>
				<div class="col-lg-3 col-md-4 col-sm-6 portfolio-item">
					<div class="card h-100">
						<img class="card-img-top" src="<?php echo base_url(); ?>assets/images/products/<?php echo $row['item_photo']; ?>" alt="<?php echo $row['item_description'] ?>">
						<div class="card-body">
							<h6 class="card-title">
								<?php echo $row['item_description'] ?>
							</h6>
							<p class="card-text">
								₱<?php echo number_format($row['unit_price'], 2); ?> - 
								$<?php echo number_format($row['unit_price'] / $usd_rate, 2); ?>
								<hr />
								<div class="row">
									<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
										<small><b>PROMO</b>:<br />SAMPLE</small>
									</div>
									<div class="col-lg-5 col-md-5 col-sm-5 col-xs-5">
										<small><b>BONUS</b>:<br />SAMPLE</small>
									</div>
									<div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 text-center">
										<button type="button" class="btn btn-success btn-sm">
											<i class="fas fa-shopping-cart"></i>
										</button>
									</div>
								</div>

								<hr />
								<div class="row">
									<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
										<small><b>PROMO</b>:<br />SAMPLE</small>
									</div>
									<div class="col-lg-5 col-md-5 col-sm-5 col-xs-5">
										<small><b>BONUS</b>:<br />SAMPLE</small>
									</div>
									<div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 text-center">
										<button type="button" class="btn btn-success btn-sm">
											<i class="fas fa-shopping-cart"></i>
										</button>
									</div>
								</div>

								<hr />
								<div class="row">
									<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
										<small><b>PROMO</b>:<br />SAMPLE</small>
									</div>
									<div class="col-lg-5 col-md-5 col-sm-5 col-xs-5">
										<small><b>BONUS</b>:<br />SAMPLE</small>
									</div>
									<div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 text-center">
										<button type="button" class="btn btn-success btn-sm">
											<i class="fas fa-shopping-cart"></i>
										</button>
									</div>
								</div>
							</p>
						</div>
					</div>
				</div>
			<?php } ?>
		</div>