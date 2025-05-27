@props(['siswas', 'industris', 'gurus', 'pkl' => null, 'isEdit' => false])

<div class="space-y-6">
    <!-- Siswa Selection -->
    <div>
        <label for="siswa_id" class="block text-sm font-medium text-gray-700 mb-2">
            Siswa <span class="text-red-500">*</span>
        </label>
        <select name="siswa_id" id="siswa_id" required
                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
            <option value="">Pilih Siswa</option>
            @foreach($siswas as $siswa)
                <option value="{{ $siswa->id }}" 
                        {{ (old('siswa_id', $pkl->siswa_id ?? '') == $siswa->id) ? 'selected' : '' }}>
                    {{ $siswa->nama }} 
                    @if($siswa->kelas)
                        - {{ $siswa->kelas }}
                    @endif
                </option>
            @endforeach
        </select>
        @error('siswa_id')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <!-- Industri Selection -->
    <div>
        <label for="industri_id" class="block text-sm font-medium text-gray-700 mb-2">
            Industri <span class="text-red-500">*</span>
        </label>
        <select name="industri_id" id="industri_id" required
                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
            <option value="">Pilih Industri</option>
            @foreach($industris as $industri)
                <option value="{{ $industri->id }}" 
                        {{ (old('industri_id', $pkl->industri_id ?? '') == $industri->id) ? 'selected' : '' }}>
                    {{ $industri->nama }}
                    @if($industri->alamat)
                        - {{ Str::limit($industri->alamat, 50) }}
                    @endif
                </option>
            @endforeach
        </select>
        @error('industri_id')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <!-- Guru Pembimbing Selection -->
    <div>
        <label for="guru_id" class="block text-sm font-medium text-gray-700 mb-2">
            Guru Pembimbing <span class="text-red-500">*</span>
        </label>
        <select name="guru_id" id="guru_id" required
                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
            <option value="">Pilih Guru Pembimbing</option>
            @foreach($gurus as $guru)
                <option value="{{ $guru->id }}" 
                        {{ (old('guru_id', $pkl->guru_id ?? '') == $guru->id) ? 'selected' : '' }}>
                    {{ $guru->nama }}
                    @if($guru->mata_pelajaran)
                        - {{ $guru->mata_pelajaran }}
                    @endif
                </option>
            @endforeach
        </select>
        @error('guru_id')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <!-- Date Fields -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Tanggal Mulai -->
        <div>
            <label for="tanggal_mulai" class="block text-sm font-medium text-gray-700 mb-2">
                Tanggal Mulai <span class="text-red-500">*</span>
            </label>
            <input type="date" name="tanggal_mulai" id="tanggal_mulai" required
                   value="{{ old('tanggal_mulai', $pkl->tanggal_mulai ?? '') }}"
                   min="{{ date('Y-m-d') }}"
                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
            @error('tanggal_mulai')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Tanggal Selesai -->
        <div>
            <label for="tanggal_selesai" class="block text-sm font-medium text-gray-700 mb-2">
                Tanggal Selesai <span class="text-red-500">*</span>
            </label>
            <input type="date" name="tanggal_selesai" id="tanggal_selesai" required
                   value="{{ old('tanggal_selesai', $pkl->tanggal_selesai ?? '') }}"
                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
            @error('tanggal_selesai')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const tanggalMulai = document.getElementById('tanggal_mulai');
    const tanggalSelesai = document.getElementById('tanggal_selesai');
    
    // Update minimum date for tanggal_selesai when tanggal_mulai changes
    tanggalMulai.addEventListener('change', function() {
        const startDate = new Date(this.value);
        const nextDay = new Date(startDate);
        nextDay.setDate(startDate.getDate() + 1);
        
        const minDate = nextDay.toISOString().split('T')[0];
        tanggalSelesai.min = minDate;
        
        // If current end date is before new start date, clear it
        if (tanggalSelesai.value && tanggalSelesai.value <= this.value) {
            tanggalSelesai.value = '';
        }
    });
    
    // Validate that end date is after start date
    tanggalSelesai.addEventListener('change', function() {
        if (tanggalMulai.value && this.value <= tanggalMulai.value) {
            alert('Tanggal selesai harus setelah tanggal mulai');
            this.value = '';
        }
    });
    
    // Set initial minimum date for tanggal_selesai if tanggal_mulai has value
    if (tanggalMulai.value) {
        const startDate = new Date(tanggalMulai.value);
        const nextDay = new Date(startDate);
        nextDay.setDate(startDate.getDate() + 1);
        
        const minDate = nextDay.toISOString().split('T')[0];
        tanggalSelesai.min = minDate;
    }
});
</script>
@endpush