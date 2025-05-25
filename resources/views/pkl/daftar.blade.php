
<form action="{{ route('pkl.store') }}" method="POST">
    @csrf
    <input type="hidden" name="siswa_id" value="{{ auth()->user()->id }}">
    
    <label>Pilih Industri</label>
    <select name="industri_id">
        @foreach($industris as $industri)
            <option value="{{ $industri->id }}">{{ $industri->nama }}</option>
        @endforeach
    </select>

    <label>Tanggal Mulai</label>
    <input type="date" name="mulai" required>

    <label>Tanggal Selesai</label>
    <input type="date" name="selesai" required>

    <button type="submit">Daftar PKL</button>
</form>
