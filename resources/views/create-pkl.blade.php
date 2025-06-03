<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Tambah Data PKL') }}
            </h2>
            <nav class="text-sm breadcrumbs">
                <ol class="list-none p-0 inline-flex">
                    <li class="flex items-center">
                        <a href="{{ route('dashboard.pkls.index') }}" class="text-blue-600 hover:text-blue-800">PKL</a>
                        <svg class="fill-current w-3 h-3 mx-3" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512">
                            <path d="M285.476 272.971L91.132 467.314c-9.373 9.373-24.569 9.373-33.941 0l-22.667-22.667c-9.357-9.357-9.375-24.522-.04-33.901L188.505 256 34.484 101.255c-9.335-9.379-9.317-24.544.04-33.901l22.667-22.667c9.373-9.373 24.569-9.373 33.941 0L285.476 239.03c9.373 9.372 9.373 24.568 0 33.941z"/>
                        </svg>
                    </li>
                    <li class="text-gray-500">Create</li>
                </ol>
            </nav>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-8">
                    <div class="mb-8">
                        <h3 class="text-2xl font-bold text-gray-900 mb-2">Create PKL</h3>
                        <p class="text-gray-600">Isi form di bawah untuk menambahkan data PKL baru</p>
                        <div class="mt-2 p-3 bg-blue-50 border border-blue-200 rounded-lg">
                            <p class="text-sm text-blue-800">
                                <svg class="inline w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                                </svg>
                                <strong>Catatan:</strong> Durasi PKL minimal adalah 90 hari (3 bulan)
                            </p>
                        </div>
                    </div>

                    @if ($errors->any())
                        <div class="mb-6 bg-red-50 border border-red-200 rounded-lg p-4">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-sm font-medium text-red-800">Terdapat beberapa error:</h3>
                                    <ul class="mt-2 text-sm text-red-700 list-disc list-inside">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    @endif

                    <form action="{{ route('dashboard.pkls.store') }}" method="POST" class="space-y-8">
                        @csrf
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <!-- Siswa -->
                            <div class="space-y-2">
                                <label for="siswa_id" class="block text-sm font-semibold text-gray-900">
                                    Siswa <span class="text-red-500">*</span>
                                </label>
                                @if(auth()->user()->hasRole('siswa'))
                                    <div class="mt-1 block w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-lg text-gray-900">
                                        {{ auth()->user()->siswa->nama ?? 'Tidak ditemukan' }}
                                    </div>
                                    <input type="hidden" name="siswa_id" value="{{ auth()->user()->siswa->id ?? '' }}">
                                @else
                                    <select name="siswa_id" id="siswa_id" class="mt-1 block w-full px-4 py-3 bg-white border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out">
                                        <option value="">Select an option</option>
                                        @foreach($siswas as $siswa)
                                            <option value="{{ $siswa->id }}" {{ old('siswa_id') == $siswa->id ? 'selected' : '' }}>
                                                {{ $siswa->nama }}
                                            </option>
                                        @endforeach
                                    </select>
                                @endif
                                @error('siswa_id')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Industri -->
                            <div class="space-y-2">
                                <label for="industri_id" class="block text-sm font-semibold text-gray-900">
                                    Industri <span class="text-red-500">*</span>
                                </label>
                                <select name="industri_id" id="industri_id" class="mt-1 block w-full px-4 py-3 bg-white border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out">
                                    <option value="">Select an option</option>
                                    @foreach($industris as $industri)
                                        <option value="{{ $industri->id }}" {{ old('industri_id') == $industri->id ? 'selected' : '' }}>
                                            {{ $industri->nama }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('industri_id')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Guru Pembimbing -->
                            <div class="space-y-2">
                                <label for="guru_id" class="block text-sm font-semibold text-gray-900">
                                    Guru Pembimbing <span class="text-red-500">*</span>
                                </label>
                                <select name="guru_id" id="guru_id" class="mt-1 block w-full px-4 py-3 bg-white border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out">
                                    <option value="">Select an option</option>
                                    @foreach($gurus as $guru)
                                        <option value="{{ $guru->id }}" {{ old('guru_id') == $guru->id ? 'selected' : '' }}>
                                            {{ $guru->nama }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('guru_id')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Tanggal Mulai -->
                            <div class="space-y-2">
                                <label for="mulai" class="block text-sm font-semibold text-gray-900">
                                    Tanggal Mulai <span class="text-red-500">*</span>
                                </label>
                                <input type="date" name="mulai" id="mulai" 
                                       value="{{ old('mulai') }}" 
                                       class="mt-1 block w-full px-4 py-3 bg-white border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out">
                                @error('mulai')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Tanggal Selesai -->
                            <div class="space-y-2">
                                <label for="selesai" class="block text-sm font-semibold text-gray-900">
                                    Tanggal Selesai <span class="text-red-500">*</span>
                                </label>
                                <input type="date" name="selesai" id="selesai" 
                                       value="{{ old('selesai') }}" 
                                       class="mt-1 block w-full px-4 py-3 bg-white border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out">
                                @error('selesai')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                                <div id="duration-info" class="text-sm text-gray-600 hidden">
                                    <span id="duration-text"></span>
                                </div>
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200">
                            <a href="{{ route('dashboard.pkls.index') }}" 
                               class="inline-flex items-center px-6 py-3 border border-gray-300 shadow-sm text-base font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150 ease-in-out">
                                Cancel
                            </a>
                            <button type="submit" id="submit-btn"
                                    class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-lg shadow-sm text-white bg-orange-500 hover:bg-orange-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500 transition duration-150 ease-in-out">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                                Create
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const tanggalMulai = document.getElementById('mulai');
            const tanggalSelesai = document.getElementById('selesai');
            const durationInfo = document.getElementById('duration-info');
            const durationText = document.getElementById('duration-text');
            const submitBtn = document.getElementById('submit-btn');
            
            const MINIMAL_DAYS = 90;

            function calculateDuration() {
                if (tanggalMulai.value && tanggalSelesai.value) {
                    const startDate = new Date(tanggalMulai.value);
                    const endDate = new Date(tanggalSelesai.value);
                    const timeDiff = endDate.getTime() - startDate.getTime();
                    const daysDiff = Math.ceil(timeDiff / (1000 * 3600 * 24)) + 1; // +1 untuk include start date

                    durationInfo.classList.remove('hidden');
                    
                    if (daysDiff < MINIMAL_DAYS) {
                        durationText.innerHTML = `
                            <span class="text-red-600 font-medium">
                                <svg class="inline w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                </svg>
                                Durasi: ${daysDiff} hari (Kurang ${MINIMAL_DAYS - daysDiff} hari dari minimal 90 hari)
                            </span>
                        `;
                        submitBtn.disabled = true;
                        submitBtn.classList.add('opacity-50', 'cursor-not-allowed');
                        submitBtn.classList.remove('hover:bg-orange-600');
                    } else {
                        durationText.innerHTML = `
                            <span class="text-green-600 font-medium">
                                <svg class="inline w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                </svg>
                                Durasi: ${daysDiff} hari (Memenuhi syarat minimal 90 hari)
                            </span>
                        `;
                        submitBtn.disabled = false;
                        submitBtn.classList.remove('opacity-50', 'cursor-not-allowed');
                        submitBtn.classList.add('hover:bg-orange-600');
                    }
                } else {
                    durationInfo.classList.add('hidden');
                    submitBtn.disabled = false;
                    submitBtn.classList.remove('opacity-50', 'cursor-not-allowed');
                    submitBtn.classList.add('hover:bg-orange-600');
                }
            }

            tanggalMulai.addEventListener('change', function() {
                if (this.value) {
                    // Set minimum date untuk tanggal selesai
                    tanggalSelesai.min = this.value;
                    
                    // Auto set tanggal selesai minimal 90 hari dari tanggal mulai
                    const startDate = new Date(this.value);
                    const minEndDate = new Date(startDate);
                    minEndDate.setDate(startDate.getDate() + MINIMAL_DAYS - 1);
                    
                    const minEndDateString = minEndDate.toISOString().split('T')[0];
                    
                    // Jika tanggal selesai sudah diisi tapi kurang dari minimal, reset
                    if (tanggalSelesai.value && tanggalSelesai.value < minEndDateString) {
                        tanggalSelesai.value = minEndDateString;
                    }
                    
                    // Jika tanggal selesai belum diisi, suggest tanggal minimal
                    if (!tanggalSelesai.value) {
                        tanggalSelesai.value = minEndDateString;
                    }
                }
                calculateDuration();
            });

            tanggalSelesai.addEventListener('change', function() {
                if (tanggalMulai.value && this.value < tanggalMulai.value) {
                    alert('Tanggal selesai harus setelah tanggal mulai');
                    this.value = '';
                    return;
                }
                
                if (tanggalMulai.value && this.value) {
                    const startDate = new Date(tanggalMulai.value);
                    const endDate = new Date(this.value);
                    const timeDiff = endDate.getTime() - startDate.getTime();
                    const daysDiff = Math.ceil(timeDiff / (1000 * 3600 * 24)) + 1;
                    
                    if (daysDiff < MINIMAL_DAYS) {
                        const minEndDate = new Date(startDate);
                        minEndDate.setDate(startDate.getDate() + MINIMAL_DAYS - 1);
                        const minEndDateString = minEndDate.toISOString().split('T')[0];
                        
                        if (confirm(`Durasi PKL minimal adalah ${MINIMAL_DAYS} hari. Apakah Anda ingin mengatur tanggal selesai ke ${minEndDate.toLocaleDateString('id-ID')}?`)) {
                            this.value = minEndDateString;
                        }
                    }
                }
                calculateDuration();
            });

            // Form validation
            document.querySelector('form').addEventListener('submit', function(e) {
                if (tanggalMulai.value && tanggalSelesai.value) {
                    const startDate = new Date(tanggalMulai.value);
                    const endDate = new Date(tanggalSelesai.value);
                    const timeDiff = endDate.getTime() - startDate.getTime();
                    const daysDiff = Math.ceil(timeDiff / (1000 * 3600 * 24)) + 1;
                    
                    if (daysDiff < MINIMAL_DAYS) {
                        e.preventDefault();
                        alert(`Durasi PKL harus minimal ${MINIMAL_DAYS} hari. Saat ini hanya ${daysDiff} hari.`);
                        return false;
                    }
                }
            });

            // Initialize calculation if values already exist
            calculateDuration();
        });
    </script>
    @endpush
</x-app-layout>