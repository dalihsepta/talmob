<!DOCTYPE html>
<html>

<head>
	<title><?= $title ?></title>
	<link rel="shortcut icon" href="<?= base_url() ?>assets/assets_shop/img/logo.ico">
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
	<script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
</head>

<body>

	<style type="text/css">
		.table-data {
			width: 100%;
			border-collapse: collapse;
		}

		.table-data tr th,
		.table-data tr td {
			border: 1px solid black;
			font-size: 10pt;
		}
	</style>

	<section id="header-kop">
		<div class="container-fluid mt-4">
			<table class="table table-borderless">
				<tbody>
					<tr>
						<td rowspan="3" width="16%" class="text-center">
							<img src="<?= base_url('assets/assets_stisla') ?>/assets/img/logo.png" alt="logo" width="80" />
						</td>
						<td class="text-center">
							<h3>PT. ARSA BAROKAH TRANS</h3>
						</td>
						<td rowspan="3" width="16%">&nbsp;</td>
					</tr>
					<tr>
						<td class="text-center">Jl. Galunggung No.76, Triwung Kidul, Kec. Kademangan, Kota Probolinggo, Jawa Timur 67224</td>
					</tr>
				</tbody>
			</table>
			<hr style="height: 2px" color="#0F0E20">
		</div>
	</section>

	<section id="body-of-report">
		<div class="container-fluid">
			<h4 class="text-center mt-4 mb-2">Laporan Transaksi</h4>
			<h5 class="text-center mb-5">Tanggal: <?= IndonesiaTgl($_GET['dari']) . " s/d " . IndonesiaTgl($_GET['sampai']); ?></h5>
			<table class="table-data" id="nilai">
				<thead>
					<tr align="center">
						<th>No</th>
						<th>User</th>
						<th>Mobil</th>
						<th>Tgl Sewa</th>
						<th>Tgl Kembali</th>
						<th>Tgl Pengembalian</th>
						<th>Total Sewa</th>
						<th>Total Denda</th>
						<th>Status</th>
						<th>Status Pembayaran</th>
						<th>Dana Masuk</th>
					</tr>
				</thead>
				<tbody>
					<?php
					$no = 1;
					foreach ($laporan as $l) : ?>
						<tr align="center">
							<td><?= $no++ ?></td>
							<td><?= $l->nama ?></td>
							<td><?= $l->merk ?></td>
							<td><?= IndonesiaTgl($l->tanggal_sewa) ?></td>
							<td><?= IndonesiaTgl($l->tanggal_kembali) ?></td>
							<td>
								<?php if (IndonesiaTgl($l->tanggal_pengembalian) == "00-00-0000") { ?>
									-
								<?php } else { ?>
									<?= IndonesiaTgl($l->tanggal_pengembalian) ?>
								<?php } ?>
							</td>
							<td><?= format_rupiah($l->total_sewa) ?></td>
							<td><?= format_rupiah($l->total_denda) ?></td>
							<td>
								<?php if (($l->status) == 0) {
									echo "Batal";
								} elseif (($l->status) == 1) {
									echo "Disewa";
								} else {
									echo "Selesai";
								} ?>
							</td>
							<td>
								<?php if (($l->status_pembayaran) == 0) {
									echo "Belum Dibayar";
								} elseif (($l->status_pembayaran) == 1) {
									echo "Menunggu Konfirmasi";
								} elseif (($l->status_pembayaran) == 2) {
									echo "Sudah Dibayar";
								} else {
									echo "Batal";
								} ?>
							</td>
							<td><?php if (($l->status_pembayaran) == 2) {
									echo format_rupiah($l->total_sewa+$l->total_denda);
								} ?></td>
						</tr>
						
					<?php endforeach; ?>
				</tbody>
<?php
//hitung data total
$koneksi = mysqli_connect('localhost','db_rentalmobil','db_rentalmobil','db_rentalmobil');
$data = $koneksi->query("select sum(if(status_pembayaran=2, total_sewa,0)+total_denda) as totalmasuk from transaksi");
$sqlbelumdisewa = $koneksi->query("SELECT * FROM transaksi where transaksi.status_pembayaran like '0'");
$belumsdisewa = mysqli_num_rows($sqlbelumdisewa);

$sqltg_konfirm = $koneksi->query("SELECT * FROM transaksi where transaksi.status_pembayaran like '1'");
$tg_konfirm = mysqli_num_rows($sqltg_konfirm);

$sqlsudahdibayar = $koneksi->query("SELECT * FROM transaksi where transaksi.status_pembayaran like '2'");
$sudahdibayar = mysqli_num_rows($sqlsudahdibayar);

$sqlbatal = $koneksi->query("SELECT * FROM transaksi where transaksi.status_pembayaran like '3'");
$batal = mysqli_num_rows($sqlbatal);

$sqltr_total = $koneksi->query("SELECT * FROM transaksi where transaksi.status_pembayaran");
$tr_total = mysqli_num_rows($sqltr_total);
$total=0;

while ($r=$data->fetch_array()){
	$total =$r["totalmasuk"];
}
?>
<td colspan="10" align="right"><b>Total Pemasukan</b></td>
<td colspan="1" align="center"><b><?php echo format_rupiah($total); ?></b></td>
</table>
<br>
<b>Total transaksi sudah dibayar = <?php echo $sudahdibayar; ?> </b><br>
<!--b>Total transaksi belum dibayar = <?php echo $belumsdisewa; ?> </b><br>
<b>Total transaksi menunggu konfirmasi = <?php echo $tg_konfirm; ?> </b><br-->
<b style="color:red">Total transaksi batal = <?php echo $batal; ?> </b><br><br>
<!--b>Total seluruh transaksi = <?php echo $tr_total; ?></b-->


</div>
	</section>
<style>
		.container{width: 600px;margin: auto;text-align: center;}
		table{
			margin: auto;
		}
		th,td{
			border:1px solid #000;
			padding: 5px;
		}
		span{
			font-weight: bold;
			margin-left: 170px;
		}
</style>
<script type="text/javascript">
		window.print();
</script>

</body>

</html>