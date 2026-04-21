<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Surat Keterangan</title>
    <style>
        body {
            font-family: 'Times New Roman', Times, serif;
            margin: 40px;
            line-height: 1.5;
            font-size: 12pt;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .header .title {
            font-size: 16pt;
            font-weight: bold;
            text-transform: uppercase;
            margin-bottom: 5px;
        }
        .header .subtitle {
            font-size: 14pt;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .header .address {
            font-size: 11pt;
            margin-bottom: 5px;
        }
        .header .line {
            border-bottom: 2px solid #000;
            margin-top: 5px;
        }
        .surat-title {
            text-align: center;
            font-size: 14pt;
            font-weight: bold;
            text-decoration: underline;
            margin: 20px 0;
        }
        .body-text {
            text-align: justify;
            margin-top: 20px;
        }
        .table-data {
            width: 100%;
            margin: 20px 0;
            border-collapse: collapse;
        }
        .table-data td {
            padding: 8px 5px;
            border-bottom: 1px solid #ccc;
        }
        .label {
            width: 30%;
            font-weight: bold;
        }
        .signature {
            margin-top: 50px;
            text-align: right;
        }
        .footer {
            margin-top: 30px;
            font-size: 10pt;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="title">PEMERINTAHAN DESA CIPAKAT</div>
        <div class="subtitle">KECAMATAN ... KABUPATEN ...</div>
        <div class="address">Alamat: Jl. ... No. ... Desa Cipakat</div>
        <div class="line"></div>
    </div>

    <div class="surat-title">
        SURAT KETERANGAN
    </div>

    <div style="text-align: center; margin-bottom: 20px;">
        Nomor: {{ $surat->id }}/SK/{{ date('Y') }}
    </div>

    <div class="body-text">
        Yang bertanda tangan di bawah ini, Kepala Desa Cipakat, menerangkan bahwa:
    </div>

    <table class="table-data">
        <tr>
            <td class="label">Nama</td>
            <td>: {{ $surat->user->name ?? '-' }}</td>
        </tr>
        <tr>
            <td class="label">NIK</td>
            <td>: {{ $surat->user->nik ?? '-' }}</td>
        </tr>
        <tr>
            <td class="label">Tempat, Tanggal Lahir</td>
            <td>: {{ $surat->user->tempat_lahir ?? '-' }},
                {{ $surat->user->tanggal_lahir ? \Carbon\Carbon::parse($surat->user->tanggal_lahir)->format('d/m/Y') : '-' }}
            </td>
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
            <td>: {{ $surat->user->alamat ?? '-' }}, RT/RW {{ $surat->user->rt_rw ?? '-' }}, Kode Pos {{ $surat->user->kode_pos ?? '-' }}</td>
        </tr>
    </table>

    <div class="body-text">
        Adalah benar penduduk Desa Cipakat yang bertempat tinggal di alamat tersebut di atas.
    </div>

    <div class="body-text">
        Surat keterangan ini dibuat untuk keperluan <strong>{{ $surat->keterangan ?? 'administrasi' }}</strong>.
    </div>

    <div class="body-text">
        Demikian surat keterangan ini dibuat, agar dapat digunakan sebagaimana mestinya.
    </div>

    <div class="signature">
        <table width="100%">
            <tr>
                <td width="60%"></td>
                <td width="40%">
                    Cipakat, {{ $tanggal_cetak }}<br>
                    Kepala Desa Cipakat<br><br><br><br>
                    <u><strong>{{ $kepalaDesa }}</strong></u><br>
                    NIP. ...
                </td>
            </tr>
        </table>
    </div>

    <div class="footer">
        *Surat ini dibuat secara elektronik dan tidak memerlukan tanda tangan basah*
    </div>
</body>
</html>
