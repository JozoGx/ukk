<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Detail Industri') }}
            </h2>
            <div class="flex space-x-2">
                <a href="{{ route('industri.index') }}" 
                   class="inline-flex items-center px-4 py-2 bg-gray-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-600 active:bg-gray-700 focus:outline-none focus:border-gray-700 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                    {{ __('Kembali') }}
                </a>
                @if(auth()->user()->hasRole('admin'))
                    <a href="{{ route('industri.edit', $industri) }}" 
                       class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150">
                        {{ __('Edit') }}
                    </a>
                @endif
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Informasi Dasar Industri -->
                        <div class="space-y-4">
                            <div>
                                <x-label for="nama" value="{{ __('Nama Industri') }}" />
                                <div class="mt-1 p-3 bg-gray-50 border border-gray-300 rounded-md">
                                    {{ $industri->nama ?? '-' }}
                                </div>
                            </div>

                            <div>
                                <x-label for="bidang_usaha" value="{{ __('Bidang Usaha') }}" />
                                <div class="mt-1 p-3 bg-gray-50 border border-gray-300 rounded-md">
                                    {{ $industri->bidang_usaha ?? '-' }}
                                </div>
                            </div>

                            <div>
                                <x-label for="alamat" value="{{ __('Alamat') }}" />
                                <div class="mt-1 p-3 bg-gray-50 border border-gray-300 rounded-md">
                                    {{ $industri->alamat ?? '-' }}
                                </div>
                            </div>
                        </div>

                        <!-- Informasi Kontak -->
                        <div class="space-y-4">
                            <div>
                                <x-label for="kontak" value="{{ __('Nomor Kontak') }}" />
                                <div class="mt-1 p-3 bg-gray-50 border border-gray-300 rounded-md">
                                    {{ $industri->kontak ?? '-' }}
                                </div>
                            </div>

                            <div>
                                <x-label for="email" value="{{ __('Email') }}" />
                                <div class="mt-1 p-3 bg-gray-50 border border-gray-300 rounded-md">
                                    {{ $industri->email ?? '-' }}
                                </div>
                            </div>

                            <div>
                                <x-label for="website" value="{{ __('Website') }}" />
                                <div class="mt-1 p-3 bg-gray-50 border border-gray-300 rounded-md">
                                    @if($industri->website)
                                        <a href="{{ $industri->website }}" target="_blank" class="text-blue-600 hover:text-blue-800 underline">
                                            {{ $industri->website }}
                                        </a>
                                    @else
                                        -
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Informasi PKL Terkait -->
                    @if($industri->pkls && $industri->pkls->count() > 0)
                        <div class="mt-6 pt-6 border-t border-gray-200">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">PKL di Industri Ini</h3>
                            <div class="bg-gray-50 rounded-lg p-4">
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                    <div class="text-center">
                                        <div class="text-2xl font-bold text-blue-600">{{ $industri->pkls->count() }}</div>
                                        <div class="text-sm text-gray-600">Total PKL</div>
                                    </div>
                                    <div class="text-center">
                                        <div class="text-2xl font-bold text-green-600">{{ $industri->pkls->where('status', 'aktif')->count() }}</div>
                                        <div class="text-sm text-gray-600">PKL Aktif</div>
                                    </div>
                                    <div class="text-center">
                                        <div class="text-2xl font-bold text-gray-600">{{ $industri->pkls->where('status', 'selesai')->count() }}</div>
                                        <div class="text-sm text-gray-600">PKL Selesai</div>
                                    </div>
                                </div>

                                <!-- Daftar Siswa PKL -->
                                @if($industri->pkls->count() > 0)
                                    <div class="mt-6">
                                        <h4 class="text-md font-medium text-gray-800 mb-3">Daftar Siswa PKL:</h4>
                                        <div class="space-y-2">
                                            @foreach($industri->pkls as $pkl)
                                                <div class="flex items-center justify-between bg-white p-3 rounded-md border">
                                                    <div class="flex items-center space-x-3">
                                                        <div class="flex-shrink-0 h-8 w-8">
                                                            <div class="h-8 w-8 rounded-full bg-blue-100 flex items-center justify-center">
                                                                <span class="text-xs font-medium text-blue-800">
                                                                    {{ strtoupper(substr($pkl->siswa->nama ?? 'N', 0, 1)) }}
                                                                </span>
                                                            </div>
                                                        </div>
                                                        <div>
                                                            <div class="text-sm font-medium text-gray-900">
                                                                {{ $pkl->siswa->nama ?? 'Nama tidak tersedia' }}
                                                            </div>
                                                            <div class="text-xs text-gray-500">
                                                                {{ $pkl->mulai ? \Carbon\Carbon::parse($pkl->mulai)->format('d/m/Y') : '-' }} 
                                                                @if($pkl->selesai)
                                                                    - {{ \Carbon\Carbon::parse($pkl->selesai)->format('d/m/Y') }}
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="flex items-center space-x-2">
                                                        @if($pkl->status == 'aktif')
                                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                                Aktif
                                                            </span>
                                                        @elseif($pkl->status == 'selesai')
                                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                                Selesai
                                                            </span>
                                                        @else
                                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                                {{ ucfirst($pkl->status) }}
                                                            </span>
                                                        @endif
                                                        
                                                        @if(Route::has('dashboard.pkls.show'))
                                                            <a href="{{ route('dashboard.pkls.show', $pkl) }}" 
                                                               class="text-blue-600 hover:text-blue-800 text-xs underline">
                                                                Detail
                                                            </a>
                                                        @endif
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif

                    <!-- Informasi Tambahan -->
                    <div class="mt-6 pt-6 border-t border-gray-200">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <x-label for="created_at" value="{{ __('Dibuat Pada') }}" />
                                <div class="mt-1 p-3 bg-gray-50 border border-gray-300 rounded-md">
                                    {{ $industri->created_at->format('d/m/Y H:i') }}
                                </div>
                            </div>

                            <div>
                                <x-label for="updated_at" value="{{ __('Diperbarui Pada') }}" />
                                <div class="mt-1 p-3 bg-gray-50 border border-gray-300 rounded-md">
                                    {{ $industri->updated_at->format('d/m/Y H:i') }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tombol Hapus - Hanya untuk Admin -->
                    @if(auth()->user()->hasRole('admin'))
                        <div class="mt-6 pt-6 border-t border-gray-200 flex justify-end">
                            <form method="POST" action="{{ route('industri.destroy', $industri) }}" 
                                  onsubmit="return confirm('Apakah Anda yakin ingin menghapus industri {{ $industri->nama }}? Semua data PKL terkait juga akan terpengaruh.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 active:bg-red-900 focus:outline-none focus:border-red-900 focus:ring ring-red-300 disabled:opacity-25 transition ease-in-out duration-150">
                                    {{ __('Hapus Industri') }}
                                </button>
                            </form>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>