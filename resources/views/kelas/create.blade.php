<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Kelas</title>
</head>
<body>
    <a href="{{ route('kelas.index') }}">← Kembali</a>
    <h2>Tambah Kelas</h2>

    @if ($errors->any())
        <div style="color:red">
            @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <form method="POST" action="{{ route('kelas.store') }}">
        @csrf
        <label>Nama Kelas</label><br>
        <input type="text" name="nama_kelas" value="{{ old('nama_kelas') }}" required><br><br>
        <button type="submit">Simpan</button>
    </form>
</body>
</html>