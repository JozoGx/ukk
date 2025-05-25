<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Posts') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    This is the Posts page.
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

<h2>Log Aktivitas Siswa</h2>

@foreach($posts as $post)
    <div style="margin-bottom: 1rem;">
        <strong>{{ $post->judul }}</strong><br>
        <p>{{ $post->konten }}</p>
        <small>{{ $post->created_at }}</small>
    </div>
@endforeach
