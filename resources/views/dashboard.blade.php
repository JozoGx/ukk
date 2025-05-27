<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <!-- Card PKL -->
                    <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-lg shadow-lg p-6 text-white">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-lg font-semibold">Data PKL</h3>
                                <p class="text-blue-100">Kelola data PKL siswa</p>
                            </div>
                            <div class="bg-blue-400 bg-opacity-30 rounded-full p-3">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="mt-4">
                            <a href="{{ route('dashboard.pkls') }}" 
                               class="inline-flex items-center text-blue-100 hover:text-white transition-colors duration-200">
                                Lihat Data PKL
                                <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </a>
                        </div>
                    </div>

                    @if(auth()->user()->role === 'siswa')
                        <!-- Card untuk siswa - Create PKL jika belum ada -->
                        @php
                            $userPkl = \App\Models\Pkl::where('siswa_id', auth()->id())->first();
                        @endphp
                        
                        @if(!$userPkl)
                            <div class="bg-gradient-to-r from-green-500 to-green-600 rounded-lg shadow-lg p-6 text-white">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <h3 class="text-lg font-semibold">Daftar PKL</h3>
                                        <p class="text-green-100">Buat pengajuan PKL baru</p>
                                    </div>
                                    <div class="bg-green-400 bg-opacity-30 rounded-full p-3">
                                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                        </svg>
                                    </div>
                                </div>
                                <div class="mt-4">
                                    <a href="{{ route('dashboard.pkls.create') }}" 
                                       class="inline-flex items-center text-green-100 hover:text-white transition-colors duration-200">
                                        Buat PKL Baru
                                        <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        @else
                            <div class="bg-gradient-to-r from-yellow-500 to-yellow-600 rounded-lg shadow-lg p-6 text-white">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <h3 class="text-lg font-semibold">PKL Anda</h3>
                                        <p class="text-yellow-100">Status: {{ ucfirst($userPkl->status) }}</p>
                                    </div>
                                    <div class="bg-yellow-400 bg-opacity-30 rounded-full p-3">
                                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </div>
                                </div>
                                <div class="mt-4">
                                    <a href="{{ route('dashboard.pkls.show', $userPkl) }}" 
                                       class="inline-flex items-center text-yellow-100 hover:text-white transition-colors duration-200">
                                        Lihat Detail PKL
                                        <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        @endif
                    @endif

                    @if(auth()->user()->role === 'admin')
                        <!-- Card untuk admin - Statistics -->
                        <div class="bg-gradient-to-r from-purple-500 to-purple-600 rounded-lg shadow-lg p-6 text-white">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h3 class="text-lg font-semibold">Total PKL</h3>
                                    <p class="text-2xl font-bold">{{ \App\Models\Pkl::count() }}</p>
                                </div>
                                <div class="bg-purple-400 bg-opacity-30 rounded-full p-3">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <div class="bg-gradient-to-r from-red-500 to-red-600 rounded-lg shadow-lg p-6 text-white">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h3 class="text-lg font-semibold">PKL Aktif</h3>
                                    <p class="text-2xl font-bold">{{ \App\Models\Pkl::where('status', 'aktif')->count() }}</p>
                                </div>
                                <div class="bg-red-400 bg-opacity-30 rounded-full p-3">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Recent Activities atau informasi lainnya -->
                <div class="mt-8">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Selamat Datang di Dashboard PKL</h3>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <p class="text-gray-600">
                            @if(auth()->user()->role === 'siswa')
                                Gunakan dashboard ini untuk mengelola data PKL Anda. Anda dapat membuat pengajuan PKL baru dan melihat status PKL yang sudah dibuat.
                            @else
                                Gunakan dashboard ini untuk mengelola semua data PKL siswa. Anda dapat melihat, mengedit, dan menghapus data PKL.
                            @endif
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>