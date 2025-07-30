<!DOCTYPE html>
<html>
<head>
    <title>Pengingat Pembayaran</title>
</head>
<body>
    <h2>Halo {{ $invoice->nama }},</h2>

    <p>Kami mengingatkan bahwa Anda memiliki tagihan dengan ID Invoice <strong>{{ $invoice->nomor }}</strong> yang belum upload bukti potong.</p>

    <p>Silakan segera melakukan upload bukti potong di web.</p>

    <p>Terima kasih!</p>
</body>
</html>
