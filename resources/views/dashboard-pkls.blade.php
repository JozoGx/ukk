<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Lapor PKL') }}
            </h2>
            @if(!$hasPkl || $isAdmin)
                <a href="{{ route('dashboard.pkls.create') }}" 
                   class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Lapor PKL
                </a>
            @endif
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Alert Messages -->
            @if(session('success'))
                <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            @if(session('error'))
                <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif

            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4">
                                <div class="text-sm font-medium text-gray-500">Total PKL</div>
                                <div class="text-2xl font-bold text-gray-900">{{ $totalPkls ?? $pkls->total() }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4">
                                <div class="text-sm font-medium text-gray-500">PKL Aktif</div>
                                <div class="text-2xl font-bold text-gray-900">
                                    {{ $pklAktif ?? $pkls->where('mulai', '<=', now())->where('selesai', '>=', now())->count() }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-yellow-500 rounded-full flex items-center justify-center">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4">
                                <div class="text-sm font-medium text-gray-500">PKL Selesai</div>
                                <div class="text-2xl font-bold text-gray-900">
                                    {{ $pklSelesai ?? $pkls->where('selesai', '<', now())->count() }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- PKL Data Table -->
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-medium text-gray-900">Data PKL Seluruh Siswa</h3>
                        <div class="flex items-center space-x-4">
                            <!-- Search Input -->
                            <form method="GET" action="{{ route('dashboard.pkls.index') }}" class="flex items-center space-x-2">
                                <input type="text" 
                                       name="search" 
                                       id="searchInput" 
                                       value="{{ request('search') }}"
                                       placeholder="Cari siswa, industri, atau guru..." 
                                       class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                
                                <!-- Per Page Selector -->
                                <select name="per_page" 
                                        id="perPageSelect"
                                        onchange="this.form.submit()"
                                        class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                    <option value="5" {{ request('per_page', 10) == 5 ? 'selected' : '' }}>5 per halaman</option>
                                    <option value="10" {{ request('per_page', 10) == 10 ? 'selected' : '' }}>10 per halaman</option>
                                    <option value="20" {{ request('per_page', 10) == 20 ? 'selected' : '' }}>20 per halaman</option>
                                </select>
                                
                                <button type="submit" class="inline-flex items-center px-3 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                    </svg>
                                </button>
                                
                                @if(request('search'))
                                    <a href="{{ route('dashboard.pkls.index') }}" class="inline-flex items-center px-3 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                    </a>
                                @endif
                            </form>
                        </div>
                    </div>

                    @if($pkls->count() > 0)
                        <!-- Results Info -->
                        <div class="mb-4 text-sm text-gray-600">
                            Menampilkan {{ $pkls->firstItem() }} sampai {{ $pkls->lastItem() }} dari {{ $pkls->total() }} hasil
                            @if(request('search'))
                                untuk pencarian "<strong>{{ request('search') }}</strong>"
                            @endif
                        </div>

                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200" id="pklTable">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Siswa
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Industri
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Guru Pembimbing
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Periode
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Status
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Aksi
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($pkls as $pkl)
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center">
                                                    <div class="flex-shrink-0 h-10 w-10">
                                                        <div class="h-10 w-10 rounded-full bg-gray-300 flex items-center justify-center">
                                                            <span class="text-sm font-medium text-gray-700">
                                                                {{ substr($pkl->siswa->nama, 0, 1) }}
                                                            </span>
                                                        </div>
                                                    </div>
                                                    <div class="ml-4">
                                                        <div class="text-sm font-medium text-gray-900">
                                                            {{ $pkl->siswa->nama }}
                                                        </div>
                                                        <div class="text-sm text-gray-500">
                                                            {{ $pkl->siswa->kelas ?? 'Kelas tidak diset' }}
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-medium text-gray-900">{{ $pkl->industri->nama }}</div>
                                                <div class="text-sm text-gray-500">{{ $pkl->industri->alamat }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-medium text-gray-900">{{ $pkl->guru->nama }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-900">
                                                    {{ \Carbon\Carbon::parse($pkl->mulai)->format('d M Y') }}
                                                </div>
                                                <div class="text-sm text-gray-500">
                                                    s/d {{ \Carbon\Carbon::parse($pkl->selesai)->format('d M Y') }}
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                @php
                                                    $now = now();
                                                    $start = \Carbon\Carbon::parse($pkl->mulai);
                                                    $end = \Carbon\Carbon::parse($pkl->selesai);
                                                @endphp
                                                
                                                @if($now < $start)
                                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                        Belum Mulai
                                                    </span>
                                                @elseif($now >= $start && $now <= $end)
                                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                                        Sedang Berlangsung
                                                    </span>
                                                @else
                                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">
                                                        Selesai
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                <div class="flex items-center space-x-2">
                                                    <a href="{{ route('dashboard.pkls.show', $pkl) }}" 
                                                       class="text-indigo-600 hover:text-indigo-900" title="Lihat Detail">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                        </svg>
                                                    </a>
                                                    
                                                    @if($isAdmin)
                                                        <a href="{{ route('dashboard.pkls.edit', $pkl) }}" 
                                                           class="text-yellow-600 hover:text-yellow-900" title="Edit">
                                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                            </svg>
                                                        </a>
                                                        
                                                        <form action="{{ route('dashboard.pkls.destroy', $pkl) }}" method="POST" class="inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" 
                                                                    class="text-red-600 hover:text-red-900" title="Hapus"
                                                                    onclick="return confirm('Apakah Anda yakin ingin menghapus data PKL ini?')">
                                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                                </svg>
                                                            </button>
                                                        </form>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="mt-6">
                            {{ $pkls->withQueryString()->links('pagination::tailwind') }}
                        </div>

                    @elseif(request('search'))
                        <!-- No Search Results -->
                        <div class="text-center py-12">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">Tidak ada hasil pencarian</h3>
                            <p class="mt-1 text-sm text-gray-500">
                                Tidak ditemukan data PKL untuk pencarian "<strong>{{ request('search') }}</strong>".
                            </p>
                            <div class="mt-6">
                                <a href="{{ route('dashboard.pkls.index') }}" 
                                   class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                                    Tampilkan Semua Data
                                </a>
                            </div>
                        </div>
                    @else
                        <!-- No Data -->
                        <div class="text-center py-12">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">Belum ada data PKL</h3>
                            <p class="mt-1 text-sm text-gray-500">Mulai dengan menambahkan data PKL pertama.</p>
                            @if(!$hasPkl || $isAdmin)
                                <div class="mt-6">
                                    <a href="{{ route('dashboard.pkls.create') }}" 
                                       class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                        </svg>
                                        Tambah Data PKL
                                    </a>
                                </div>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        // Auto submit search form when typing (debounced)
        let searchTimeout;
        document.getElementById('searchInput')?.addEventListener('input', function(e) {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                // Optional: Auto-submit after 500ms of no typing
                // e.target.form.submit();
            }, 500);
        });

        // Submit form when Enter key is pressed
        document.getElementById('searchInput')?.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                e.target.form.submit();
            }
        });
    </script>
    @endpush
</x-app-layout>