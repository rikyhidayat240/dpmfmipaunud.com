<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Rekap Poin Keaktifan Fungsionaris</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 12px;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .header h2, .header h3 {
            margin: 0;
            padding: 2px;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .table th, .table td {
            border: 1px solid #000;
            padding: 8px;
            text-align: center;
        }
        .table th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="header">
        <h2>REKAP POIN KEAKTIFAN FUNGSIONARIS</h2>
        <h3>DPM FMIPA UNIVERSITAS UDAYANA 2026</h3>
    </div>

    <table class="table">
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="45%">Nama Lengkap</th>
                <th width="30%">Jabatan</th>
                <th width="20%">Poin Keaktifan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $index => $user)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td style="text-align: left;">{{ $user->name }}</td>
                <td>{{ $user->specifiedRole ?? 'Belum Ditambahkan' }}</td>
                <td>{{ $user->poin_keaktifan }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div style="margin-top: 50px; text-align: right; width: 100%;">
        <p>Mengetahui,</p>
        <br><br><br>
        <p><strong>Admin DPM FMIPA</strong></p>
    </div>
</body>
</html>
