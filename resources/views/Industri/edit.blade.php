<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Edit Industri') }}
            </h2>
            <a href="{{ route('industri.index') }}" 
               class="inline-flex items-center px-4 py-2 bg-gray-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-600 active:bg-gray-700 focus:outline-none focus:border-gray-700 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                {{ __('Kembali') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6">
                    <form method="POST" action="{{ route('industri.update', $industri) }}">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Nama Industri -->
                            <div>
                                <x-label for="nama" value="{{ __('Nama Industri') }}" />
                                <x-input id="nama" class="block mt-1 w-full" type="text" 
                                        name="nama" :value="old('nama', $industri->nama)" required />
                                <x-input-error for="nama" class="mt-2" />
                            </div>

                            <!-- Bidang Usaha -->
                            <div>
                                <x-label for="bidang_usaha" value="{{ __('Bidang Usaha') }}" />
                                <x-input id="bidang_usaha" class="block mt-1 w-full" type="text" 
                                        name="bidang_usaha" :value="old('bidang_usaha', $industri->bidang_usaha)" required />
                                <x-input-error for="bidang_usaha" class="mt-2" />
                            </div>

                            <!-- Alamat -->
                            <div class="md:col-span-2">
                                <x-label for="alamat" value="{{ __('Alamat') }}" />
                                <textarea id="alamat" name="alamat" rows="3"
                                          class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-full"
                                          required>{{ old('alamat', $industri->alamat) }}</textarea>
                                <x-input-error for="alamat" class="mt-2" />
                            </div>

                            <!-- Kontak -->
                            <div>
                                <x-label for="kontak" value="{{ __('Kontak / Telepon') }}" />
                                <div class="relative mt-1">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <span class="text-gray-500 sm:text-sm">+62</span>
                                    </div>
                                    <input type="text" 
                                           name="kontak" 
                                           id="kontak" 
                                           value="{{ old('kontak', $industri->kontak) }}"
                                           class="w-full pl-12 pr-3 py-2 border border-gray-300 rounded-md shadow-sm bg-white text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                                           placeholder="8xxxxxxxxxx"
                                           pattern="8[0-9]{8,12}"
                                           title="Nomor harus dimulai dengan 8 dan minimal 9 digit"
                                           required>
                                </div>
                                <p class="mt-1 text-xs text-gray-500">Format: 8xxxxxxxxxx (tanpa +62 atau 0)</p>
                                <x-input-error for="kontak" class="mt-2" />
                            </div>

                            <!-- Email -->
                            <div>
                                <x-label for="email" value="{{ __('Email') }}" />
                                <x-input id="email" class="block mt-1 w-full" type="email" 
                                        name="email" :value="old('email', $industri->email)" required />
                                <x-input-error for="email" class="mt-2" />
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-6 space-x-4">
                            <a href="{{ route('industri.index') }}" 
                               class="inline-flex items-center px-4 py-2 bg-gray-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-600 active:bg-gray-700 focus:outline-none focus:border-gray-700 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                                {{ __('Batal') }}
                            </a>

                            <x-button class="ml-4">
                                {{ __('Update Industri') }}
                            </x-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const kontakInput = document.getElementById('kontak');
            
            // Function to convert display format (0xxx) to input format (8xxx)
            function convertToInputFormat(value) {
                if (value.startsWith('0')) {
                    return '8' + value.substring(1);
                }
                return value;
            }
            
            // Convert existing value on page load
            if (kontakInput.value) {
                kontakInput.value = convertToInputFormat(kontakInput.value);
            }
            
            kontakInput.addEventListener('input', function(e) {
                let value = e.target.value.replace(/\D/g, ''); // Remove non-digits
                
                // If starts with 62, remove it
                if (value.startsWith('62')) {
                    value = value.substring(2);
                }
                
                // If starts with 0, remove it
                if (value.startsWith('0')) {
                    value = value.substring(1);
                }
                
                // Ensure it starts with 8
                if (value && !value.startsWith('8')) {
                    if (value.charAt(0) !== '8') {
                        value = '8' + value;
                    }
                }
                
                // Limit to 13 digits (8 + 12 max)
                if (value.length > 13) {
                    value = value.substring(0, 13);
                }
                
                e.target.value = value;
            });
            
            // Prevent non-numeric input
            kontakInput.addEventListener('keypress', function(e) {
                if (!/[0-9]/.test(e.key) && !['Backspace', 'Delete', 'Tab', 'Enter', 'ArrowLeft', 'ArrowRight'].includes(e.key)) {
                    e.preventDefault();
                }
            });
        });
    </script>
    @endpush
</x-app-layout>