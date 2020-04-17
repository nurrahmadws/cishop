<main role="main" class="container">
	<?php $this->load->view('layouts/_alert'); ?>
	<div class="row">
		<div class="col-md-12">
			<div class="card">
				<div class="card-header">
					Checkout Berhasil
				</div>
				<div class="card-body">
					<h5>Nomor Order: <?= $content->invoice ?></h5>
					<p>Terima Kasih Sudah Berbelanja</p>
					<p>Silahkan Lakukan Pembayaran Untuk Bisa Kami Proses Selanjutnya Dengan Cara:</p>
					<ol>
						<li>Lakukan Pembayaran Pada Rekening <b>BCA 03838882</b> a/n PT OLSHOP</li>
						<li>Sertakan Keterangan Dengan Nomor Order <b><?= $content->invoice ?></b></li>
						<li>Total Pembayaran: <strong>Rp <?= number_format($content->total, 0, ',', '.') ?>,-</strong></li>
					</ol>
					<p>Jika Sudah, Silahkan Kirimkan Bukti Transfer Dihalaman Konfirmasi, Atau Bisa <a href="<?= base_url("/myorder/detail/$content->invoice") ?>">Klik Disini</a>!</p>
					<a href="<?= base_url('/') ?>" class="btn btn-primary"><i class="fas fa-angle-left"></i> Kembali</a>
				</div>
			</div>
		</div>
	</div>
</main>
