<main role="main" class="container">
<?php $this->load->view('layouts/_alert'); ?>
	<div class="row">
		<div class="col-md-12">
			<div class="card mb-3">
				<div class="card-header">
					Keranjang Belanja
				</div>
				<div class="card-body">
					<table class="table">
						<thead>
							<tr>
								<th>Produk</th>
								<th class="text-center">Harga</th>
								<th class="text-center">Jumlah</th>
								<th class="text-center">Subtotal</th>
							</tr>
						</thead>
						<tbody>
							<?php foreach($content as $row) : ?>
							<tr>
								<td>
									<p><img src="<?= $row->image ? base_url("/image/product/$row->image") : base_url('/image/product/default.jpg') ?>" alt="" height="50"> <strong><?= $row->title ?></strong></p>
								</td>
								<td class="text-center">Rp <?= number_format($row->price, 0, ',', '.') ?>,-</td>
								<td>
									<form action="">
										<div class="input-group">
											<input type="number text-center" class="form-control" value="<?= $row->qty ?>">
											<div class="input-group-append">
												<button class="btn btn-info" type="submit"><i class="fas fa-check"></i></button>
											</div>
										</div>
									</form>
								</td>
								<td class="text-center">Rp <?= number_format($row->subtotal, 0, ',', '.') ?>,-</td>
								<td>
									<form action="">
										<button type="submit" class="btn btn-danger"><i class="fas fa-trash-alt"></i></button>
									</form>
								</td>
							</tr>
							<?php endforeach ?>
							<tr>
								<td colspan="3"><b>Total:</b></td>
								<td class="text-center"><b>RP <?= number_format(array_sum(array_column($content, 'subtotal')), 0, ',', '.') ?>,-</b></td>
								<td></td>
							</tr>
						</tbody>
					</table>
				</div>
				<div class="card-footer">
					<a href="checkout.html" class="btn btn-success float-right">Pembayaran <i class="fas fa-angle-right"></i></a>
					<a href="<?= base_url('/') ?>" class="btn btn-warning text-white"><i class="fas fa-angle-left"></i> Kembali Belanja</a>
				</div>
			</div>
		</div>
	</div>
</main>
