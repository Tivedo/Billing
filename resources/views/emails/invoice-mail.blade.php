<h1>Halo {{ $invoice->customer->nama }},</h1>

<p>Ini adalah invoice Anda untuk bulan {{ \Carbon\Carbon::parse($invoice->tanggal)->format('F Y') }}.</p>

<p>Jumlah yang harus dibayar: <strong>Rp {{ number_format($invoice->jumlah) }}</strong></p>

<p>Silahkan segera melakukan pembayaran. Terima kasih!</p>