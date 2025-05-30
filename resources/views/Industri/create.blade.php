<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center space-x-2">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Industri') }}
            </h2>
            <span class="text-gray-500">/</span>
            <span class="text-gray-600">Create</span>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200">
                <div class="p-6">
                    <h1 class="text-2xl font-bold text-gray-900 mb-6">Create Industri</h1>
                    
                    <!-- Success Message -->
                    @if(session('success'))
                        <div class="mb-4 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg">
                            {{ session('success') }}
                        </div>
                    @endif

                    <form action="{{ route('industri.store') }}" method="POST" class="space-y-6">
                        @csrf
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Name -->
                            <div>
                                <label for="nama" class="block text-sm font-medium text-gray-700 mb-2">
                                    Nama <span class="text-red-500">*</span>
                                </label>
                                <input type="text" 
                                       name="nama" 
                                       id="nama" 
                                       value="{{ old('nama') }}"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm bg-white text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('nama') border-red-300 focus:ring-red-500 focus:border-red-500 @enderror"
                                       placeholder="Masukkan nama industri"
                                       required>
                                @error('nama')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Bidang Usaha -->
                            <div>
                                <label for="bidang_usaha" class="block text-sm font-medium text-gray-700 mb-2">
                                    Bidang Usaha <span class="text-red-500">*</span>
                                </label>
                                <input type="text" 
                                       name="bidang_usaha" 
                                       id="bidang_usaha" 
                                       value="{{ old('bidang_usaha') }}"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm bg-white text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('bidang_usaha') border-red-300 focus:ring-red-500 focus:border-red-500 @enderror"
                                       placeholder="Masukkan bidang usaha"
                                       required>
                                @error('bidang_usaha')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Alamat -->
                            <div>
                                <label for="alamat" class="block text-sm font-medium text-gray-700 mb-2">
                                    Alamat <span class="text-red-500">*</span>
                                </label>
                                <textarea name="alamat" 
                                          id="alamat" 
                                          rows="3"
                                          class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm bg-white text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('alamat') border-red-300 focus:ring-red-500 focus:border-red-500 @enderror"
                                          placeholder="Masukkan alamat lengkap"
                                          required>{{ old('alamat') }}</textarea>
                                @error('alamat')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Kontak -->
                            <div>
                                <label for="kontak" class="block text-sm font-medium text-gray-700 mb-2">
                                    Kontak <span class="text-red-500">*</span>
                                </label>
                                <input type="text" 
                                       name="kontak" 
                                       id="kontak" 
                                       value="{{ old('kontak') }}"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm bg-white text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('kontak') border-red-300 focus:ring-red-500 focus:border-red-500 @enderror"
                                       placeholder="Masukkan nomor telepon/HP"
                                       required>
                                @error('kontak')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Email -->
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                                Email <span class="text-red-500">*</span>
                            </label>
                            <input type="email" 
                                   name="email" 
                                   id="email" 
                                   value="{{ old('email') }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm bg-white text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('email') border-red-300 focus:ring-red-500 focus:border-red-500 @enderror"
                                   placeholder="Masukkan alamat email"
                                   required>
                            @error('email')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex items-center justify-start space-x-4 pt-6 border-t border-gray-200">
                            <button type="submit" 
                                    class="bg-orange-500 hover:bg-orange-600 text-white font-medium py-2.5 px-6 rounded-lg transition-colors duration-200 shadow-sm focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2">
                                Create
                            </button>
                            <button type="submit" 
                                    name="create_another"
                                    value="1"
                                    class="bg-gray-600 hover:bg-gray-700 text-white font-medium py-2.5 px-6 rounded-lg transition-colors duration-200 shadow-sm focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2">
                                Create & create another
                            </button>
                            <a href="{{ route('industri.index') }}" 
                               class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium py-2.5 px-6 rounded-lg transition-colors duration-200 shadow-sm border border-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2">
                                Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>