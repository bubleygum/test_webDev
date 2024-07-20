<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Transaksi</title>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.24/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script defer src="https://use.fontawesome.com/releases/v5.15.4/js/all.js"
        integrity="sha384-rOA1PnstxnOBLzCLMcre8ybwbTmemjzdNlILg8O7z1lUkLXozs4DHonlDtnE7fpc"
        crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL"
        crossorigin="anonymous"></script>
    <style>
        body {
            padding-top: 30px;
            background-color: #f8f9fa; /* Light grey background */
        }
        .container {
            margin-top: 30px;
            padding: 20px;
            border-radius: 8px;
            background-color: #ffffff; /* White background for the container */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Subtle shadow for better separation */
        }
        h1 {
            margin-bottom: 20px;
        }
        .btn-back, .btn-primary {
            margin-right: 10px;
        }
        .table {
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Data Transaksi</h1>
        <div class="mb-3">
            <a href="{{ url('/') }}" class="btn btn-secondary btn-back">Kembali ke Home</a>
            <a href="{{ route('transaksi.create') }}" class="btn btn-primary">Tambah Transaksi</a>
        </div>
        <!-- Data Table -->
        <table id="transaksiTable" class="table table-bordered table-striped dt-responsive">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nama Barang</th>
                    <th>Jumlah Terjual</th>
                    <th>Tanggal Transaksi</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($transaksi as $transaksi)
                    <tr>
                        <td>{{ $transaksi->id }}</td>
                        <td>{{ $transaksi->barang->nama }}</td>
                        <td>{{ $transaksi->jumlah_terjual }}</td>
                        <td>{{ $transaksi->tanggal_transaksi }}</td>
                        <td>
                            <a href="{{ route('transaksi.edit', $transaksi->id) }}" class="btn btn-warning btn-sm">Edit</a>
                            <form action="{{ route('transaksi.destroy', $transaksi->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <h1>Filter dan Perbandingan</h1>
        <div class="mb-4">
            <form action="{{ route('transaksi.index') }}" method="GET">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="start_date">Tanggal Mulai</label>
                            <input type="date" name="start_date" id="start_date" class="form-control" value="{{ request('start_date') }}">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="end_date">Tanggal Akhir</label>
                            <input type="date" name="end_date" id="end_date" class="form-control" value="{{ request('end_date') }}">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="sort_order">Urutkan Berdasarkan</label>
                            <select name="sort_order" id="sort_order" class="form-control">
                                <option value="desc" {{ request('sort_order') == 'desc' ? 'selected' : '' }}>Terbanyak Terjual</option>
                                <option value="asc" {{ request('sort_order') == 'asc' ? 'selected' : '' }}>Terendah Terjual</option>
                            </select>
                        </div>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary mt-3">Tampilkan</button>
            </form>
        </div>

        @if(isset($comparisonData))
            <h2>Perbandingan Jenis Barang</h2>
            <table id="comparisonTable" class="table table-bordered table-striped dt-responsive">
                <thead>
                    <tr>
                        <th>Jenis Barang</th>
                        <th>Total Terjual</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($comparisonData as $data)
                        <tr>
                            <td>{{ $data->jenis }}</td>
                            <td>{{ $data->total_terjual }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif

    </div>

    <script>
        $(document).ready(function() {
            $('#transaksiTable').DataTable({
                responsive: true
            });

            @if(isset($comparisonData))
                $('#comparisonTable').DataTable({
                    responsive: true
                });
            @endif
        });
    </script>
</body>
</html>
