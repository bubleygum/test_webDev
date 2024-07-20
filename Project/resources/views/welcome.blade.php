<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aplikasi Data Penjualan</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center">Selamat Datang di Aplikasi Data Penjualan</h1>
        <p class="text-center">Aplikasi ini digunakan untuk mengelola data barang dan transaksi penjualan dengan fitur CRUD (Create, Read, Update, Delete), pencarian, dan pengurutan data. Anda juga dapat membandingkan jenis barang berdasarkan jumlah terjual dengan rentang waktu tertentu.</p>
        <div class="text-center mt-4">
            <a href="{{ route('barang.index') }}" class="btn btn-primary btn-lg mx-2">Lihat Data Barang</a>
            <a href="{{ route('transaksi.index') }}" class="btn btn-success btn-lg mx-2">Lihat Data Transaksi</a>
        </div>
    </div>
</body>
</html>
