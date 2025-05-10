<!DOCTYPE html>
<html>
<head>
    <title>Reminder Pembayaran</title>
</head>
<body>
    <h2>Halo {{ $invoice->nama }},</h2>

    <p>Kami mengingatkan bahwa Anda memiliki tagihan dengan ID Invoice <strong>{{ $invoice->nomor }}</strong> yang belum dibayar.</p>

    <p>Silakan segera melakukan pembayaran agar layanan Anda tetap aktif.</p>

    <p>Terima kasih!</p>
</body>
</html>
