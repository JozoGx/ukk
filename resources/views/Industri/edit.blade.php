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
                                <x-input id="kontak" class="block mt-1 w-full" type="text" 
                                        name="kontak" :value="old('kontak', $industri->kontak)" required />
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
</x-app-layout>