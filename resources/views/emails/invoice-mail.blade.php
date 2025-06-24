<h1>Halo {{ $nama_customer }},</h1>

<p>Ini adalah invoice Anda untuk bulan {{ \Carbon\Carbon::parse($tanggal)->format('F Y') }}.</p>

<p>Silahkan segera melakukan pembayaran. Terima kasih!</p>