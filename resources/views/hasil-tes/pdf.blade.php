<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Hasil Tes</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 12px;
            color: #1f2937;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header h1 {
            font-size: 18px;
            margin: 0 0 4px 0;
        }
        .header p {
            margin: 2px 0;
            color: #4b5563;
            font-size: 12px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 16px;
        }
        th {
            background-color: #10b981;
            color: #ffffff;
            padding: 8px 10px;
            text-align: left;
            font-size: 11px;
            text-transform: uppercase;
        }
        td {
            padding: 7px 10px;
            border-bottom: 1px solid #e5e7eb;
            font-size: 12px;
        }
        tr:nth-child(even) td {
            background-color: #f9fafb;
        }
        .text-center {
            text-align: center;
        }
        .grade {
            font-weight: bold;
        }
        .belum {
            color: #9ca3af;
            font-style: italic;
        }
        .footer {
            margin-top: 24px;
            font-size: 10px;
            color: #9ca3af;
            text-align: right;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Hasil Tes: {{ $sesiTes->jenisOlahraga->nama_olahraga ?? '-' }}</h1>
        <p>Kelas: {{ $sesiTes->kelas->nama_kelas ?? '-' }}</p>
        <p>
            Tanggal: {{ \Carbon\Carbon::parse($sesiTes->tanggal)->format('d M Y') }}
            &nbsp;|&nbsp;
            Waktu: {{ \Carbon\Carbon::parse($sesiTes->waktu_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($sesiTes->waktu_berakhir)->format('H:i') }}
        </p>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 40px;">No Absen</th>
                <th>Nama Siswa</th>
                <th style="width: 90px;">Jenis Kelamin</th>
                <th style="width: 90px;">Nilai Hasil</th>
                <th style="width: 60px;">Grade</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($siswaList as $s)
                @php
                    $hasil = $hasilMap->get($s->id);
                @endphp
                <tr>
                    <td class="text-center">{{ $s->nomor_absen }}</td>
                    <td>{{ $s->nama }}</td>
                    <td>{{ $s->jenis_kelamin ?? '-' }}</td>
                    @if ($hasil)
                        <td class="text-center">{{ $hasil->nilai_hasil }}</td>
                        <td class="text-center grade">{{ $hasil->grade }}</td>
                    @else
                        <td class="text-center belum" colspan="2">Belum mengikuti tes</td>
                    @endif
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center">Tidak ada data siswa di kelas ini.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        Dicetak pada {{ \Carbon\Carbon::now()->format('d M Y H:i') }}
    </div>
</body>
</html>