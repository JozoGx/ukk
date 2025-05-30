<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Edit PKL') }}
            </h2>
            <a href="{{ route('dashboard.pkls.index', $pkl) }}" 
               class="inline-flex items-center px-4 py-2 bg-gray-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-600 active:bg-gray-700 focus:outline-none focus:border-gray-700 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                {{ __('Kembali') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6">
                    <form method="POST" action="{{ route('dashboard.pkls.update', $pkl) }}">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Siswa -->
                            <div>
                                <x-label for="siswa_id" value="{{ __('Siswa') }}" />
                                <select id="siswa_id" name="siswa_id" 
                                        class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-full"
                                        {{ auth()->user()->role === 'siswa' ? 'disabled' : '' }}>
                                    <option value="">Pilih Siswa</option>
                                    @foreach($siswas as $siswa)
                                        <option value="{{ $siswa->id }}" 
                                                {{ old('siswa_id', $pkl->siswa_id) == $siswa->id ? 'selected' : '' }}>
                                            {{ $siswa->nama }}
                                        </option>
                                    @endforeach
                                </select>
                                @if(auth()->user()->role === 'siswa')
                                    <input type="hidden" name="siswa_id" value="{{ $pkl->siswa_id }}">
                                    <p class="text-sm text-gray-500 mt-1">Anda tidak dapat mengubah data siswa</p>
                                @endif
                                <x-input-error for="siswa_id" class="mt-2" />
                            </div>

                            <!-- Industri -->
                            <div>
                                <x-label for="industri_id" value="{{ __('Industri') }}" />
                                <select id="industri_id" name="industri_id" 
                                        class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-full">
                                    <option value="">Pilih Industri</option>
                                    @foreach($industris as $industri)
                                        <option value="{{ $industri->id }}" 
                                                {{ old('industri_id', $pkl->industri_id) == $industri->id ? 'selected' : '' }}>
                                            {{ $industri->nama }}
                                        </option>
                                    @endforeach
                                </select>
                                <x-input-error for="industri_id" class="mt-2" />
                            </div>

                            <!-- Guru Pembimbing -->
                            <div>
                                <x-label for="guru_id" value="{{ __('Guru Pembimbing') }}" />
                                <select id="guru_id" name="guru_id" 
                                        class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-full">
                                    <option value="">Pilih Guru Pembimbing</option>
                                    @foreach($gurus as $guru)
                                        <option value="{{ $guru->id }}" 
                                                {{ old('guru_id', $pkl->guru_id) == $guru->id ? 'selected' : '' }}>
                                            {{ $guru->nama }}
                                        </option>
                                    @endforeach
                                </select>
                                <x-input-error for="guru_id" class="mt-2" />
                            </div>

                            <!-- Tanggal Mulai -->
                            <div>
                                <x-label for="mulai" value="{{ __('Tanggal Mulai') }}" />
                                <x-input id="mulai" class="block mt-1 w-full" type="date" 
                                        name="mulai" :value="old('mulai', $pkl->mulai)" required />
                                <x-input-error for="mulai" class="mt-2" />
                            </div>

                            <!-- Tanggal Selesai -->
                            <div>
                                <x-label for="selesai" value="{{ __('Tanggal Selesai') }}" />
                                <x-input id="selesai" class="block mt-1 w-full" type="date" 
                                        name="selesai" :value="old('selesai', $pkl->selesai)" required />
                                <x-input-error for="selesai" class="mt-2" />
                            </div>

                            @if(auth()->user()->role === 'admin')
                                <!-- Status (hanya admin yang bisa mengubah) -->
                                <div>
                                    <x-label for="status" value="{{ __('Status') }}" />
                                    <select id="status" name="status" 
                                            class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-full">
                                        <option value="aktif" {{ old('status', $pkl->status) == 'aktif' ? 'selected' : '' }}>Aktif</option>
                                        <option value="selesai" {{ old('status', $pkl->status) == 'selesai' ? 'selected' : '' }}>Selesai</option>
                                        <option value="dibatalkan" {{ old('status', $pkl->status) == 'dibatalkan' ? 'selected' : '' }}>Dibatalkan</option>
                                    </select>
                                    <x-input-error for="status" class="mt-2" />
                                </div>
                            @else
                                <input type="hidden" name="status" value="{{ $pkl->status }}">
                            @endif
                        </div>

                        <div class="flex items-center justify-end mt-6 space-x-4">
                            <a href="{{ route('dashboard.pkls.index', $pkl) }}" 
                               class="inline-flex items-center px-4 py-2 bg-gray-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-600 active:bg-gray-700 focus:outline-none focus:border-gray-700 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                                {{ __('Batal') }}
                            </a>

                            <x-button class="ml-4">
                                {{ __('Update PKL') }}
                            </x-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        // Validasi tanggal selesai harus setelah tanggal mulai
        document.getElementById('mulai').addEventListener('change', function() {
            const tanggalMulai = this.value;
            const tanggalSelesaiInput = document.getElementById('selesai');
;
            
            if (tanggalMulai) {
                tanggalSelesaiInput.min = tanggalMulai;
                
                // Jika tanggal selesai sudah diisi dan lebih kecil dari tanggal mulai, reset
                if (tanggalSelesaiInput.value && tanggalSelesaiInput.value <= tanggalMulai) {
                    tanggalSelesaiInput.value = '';
                }
            }
        });

        // Set minimum date pada page load
        document.addEventListener('DOMContentLoaded', function() {
            const tanggalMulai = document.getElementById('mulai').value;
            if (tanggalMulai) {
                document.getElementById('selesai').min = tanggalMulai;
            }
        });
    </script>
    @endpush
</x-app-layout>