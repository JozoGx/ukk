<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Detail PKL') }}
            </h2>
            <div class="flex space-x-2">
                <a href="{{ route('dashboard.pkls.index') }}" 
                   class="inline-flex items-center px-4 py-2 bg-gray-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-600 active:bg-gray-700 focus:outline-none focus:border-gray-700 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                    {{ __('Kembali') }}
                </a>
                @can('update', $pkl)
                    <a href="{{ route('dashboard.pkls.edit', $pkl) }}" 
                       class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150">
                        {{ __('Edit') }}
                    </a>
                @endcan
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Informasi Siswa -->
                        <div class="space-y-4">
                            <div>
                                <x-label for="siswa" value="{{ __('Siswa') }}" />
                                <div class="mt-1 p-3 bg-gray-50 border border-gray-300 rounded-md">
                                    {{ $pkl->siswa->nama ?? '-' }}
                                </div>
                            </div>

                            <div>
                                <x-label for="industri" value="{{ __('Industri') }}" />
                                <div class="mt-1 p-3 bg-gray-50 border border-gray-300 rounded-md">
                                    {{ $pkl->industri->nama ?? '-' }}
                                </div>
                            </div>

                            <div>
                                <x-label for="guru" value="{{ __('Guru Pembimbing') }}" />
                                <div class="mt-1 p-3 bg-gray-50 border border-gray-300 rounded-md">
                                    {{ $pkl->guru->nama ?? '-' }}
                                </div>
                            </div>
                        </div>

                        <!-- Informasi Tanggal -->
                        <div class="space-y-4">
                            <div>
                                <x-label for="tanggal_mulai" value="{{ __('Tanggal Mulai') }}" />
                                <div class="mt-1 p-3 bg-gray-50 border border-gray-300 rounded-md">
                                    {{ $pkl->mulai ? \Carbon\Carbon::parse($pkl->mulai)->format('d/m/Y') : '-' }}
                                </div>
                            </div>

                            <div>
                                <x-label for="tanggal_selesai" value="{{ __('Tanggal Selesai') }}" />
                                <div class="mt-1 p-3 bg-gray-50 border border-gray-300 rounded-md">
                                    {{ $pkl->selesai ? \Carbon\Carbon::parse($pkl->selesai)->format('d/m/Y') : '-' }}
                                </div>
                            </div>

                            <div>
                                <x-label for="status" value="{{ __('Status') }}" />
                                <div class="mt-1 p-3 bg-gray-50 border border-gray-300 rounded-md">
                                    @if($pkl->status == 'aktif')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            Aktif
                                        </span>
                                    @elseif($pkl->status == 'selesai')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            Selesai
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                            {{ ucfirst($pkl->status) }}
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div>
                                <x-label for="durasi" value="{{ __('Durasi PKL') }}" />
                                <div class="mt-1 p-3 bg-gray-50 border border-gray-300 rounded-md">
                                    @if($pkl->tanggal_mulai && $pkl->tanggal_selesai)
                                        {{ \Carbon\Carbon::parse($pkl->mulai)->diffInMonths(\Carbon\Carbon::parse($pkl->selesai)) }} bulan
                                    @else
                                        -
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Informasi Tambahan -->
                    <div class="mt-6 pt-6 border-t border-gray-200">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <x-label for="created_at" value="{{ __('Dibuat Pada') }}" />
                                <div class="mt-1 p-3 bg-gray-50 border border-gray-300 rounded-md">
                                    {{ $pkl->created_at->format('d/m/Y H:i') }}
                                </div>
                            </div>

                            <div>
                                <x-label for="updated_at" value="{{ __('Diperbarui Pada') }}" />
                                <div class="mt-1 p-3 bg-gray-50 border border-gray-300 rounded-md">
                                    {{ $pkl->updated_at->format('d/m/Y H:i') }}
                                </div>
                            </div>
                        </div>
                    </div>

                    @can('delete', $pkl)
                        <div class="mt-6 pt-6 border-t border-gray-200 flex justify-end">
                            <form method="POST" action="{{ route('dashboard.pkls.destroy', $pkl) }}" 
                                  onsubmit="return confirm('Apakah Anda yakin ingin menghapus data PKL ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 active:bg-red-900 focus:outline-none focus:border-red-900 focus:ring ring-red-300 disabled:opacity-25 transition ease-in-out duration-150">
                                    {{ __('Hapus PKL') }}
                                </button>
                            </form>
                        </div>
                    @endcan
                </div>
            </div>
        </div>
    </div>
</x-app-layout>