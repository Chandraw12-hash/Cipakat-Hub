<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Surat Domisili</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 40px;
            line-height: 1.6;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
        }
        .title {
            font-size: 24px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .subtitle {
            font-size: 14px;
        }
        .content {
            margin-top: 30px;
        }
        .signature {
            margin-top: 50px;
            text-align: right;
        }
        .table-data {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        .table-data td {
            padding: 8px;
            border-bottom: 1px solid #ddd;
        }
        .label {
            font-weight: bold;
            width: 30%;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="title">PEMERINTAH DESA CIPAKAT</div>
        <div class="subtitle">Kecamatan ... Kabupaten ...</div>
        <div class="subtitle">Alamat: ...</div>
    </div>

    <div class="content">
        <center><h3>SURAT KETERANGAN DOMISILI</h3></center>
        <p>Nomor: {{ $surat->id }}/SKD/{{ date('Y') }}</p>

        <p>Yang bertanda tangan di bawah ini, Kepala Desa Cipakat, menerangkan bahwa:</p>

        <table class="table-data">
            <tr>
                <td class="label">Nama</td>
                <td>: {{ $surat->user->name }}</td>
            </tr>
            <tr>
                <td class="label">NIK</td>
                <td>: {{ $surat->user->nik ?? '-' }}</td>
            </tr>
            <tr>
                <td class="label">Tempat, Tanggal Lahir</td>
                <td>: {{ $surat->user->tempat_lahir ?? '-' }}, {{ $surat->user->tanggal_lahir ?? '-' }}</td>
            </tr>
            <tr>
                <td class="label">Jenis Kelamin</td>
                <td>: {{ $surat->user->jenis_kelamin ?? '-' }}</td>
            </tr>
            <tr>
                <td class="label">Pekerjaan</td>
                <td>: {{ $surat->user->pekerjaan ?? '-' }}</td>
            </tr>
            <tr>
                <td class="label">Alamat</td>
                <td>: {{ $surat->user->alamat ?? '-' }}</td>
            </tr>
        </table>

        <p>Adalah benar penduduk Desa Cipakat yang bertempat tinggal di alamat tersebut di atas.</p>
        <p>Surat keterangan ini dibuat untuk keperluan {{ $surat->keterangan ?? 'administrasi' }}.</p>
        <p>Demikian surat keterangan ini dibuat, agar dapat digunakan sebagaimana mestinya.</p>
    </div>

    <div class="signature">
        <p>Cipakat, {{ date('d F Y') }}</p>
        <p>Kepala Desa Cipakat</p>
        <br><br><br>
        <p><u>{{ $kepalaDesa }}</u></p>
        <p>NIP. ...</p>
    </div>
</body>
</html>
