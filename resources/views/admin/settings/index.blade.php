<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Pengaturan Bot AI') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="mb-4 bg-emerald-100 border border-emerald-400 text-emerald-700 px-4 py-3 rounded relative shadow-sm" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form action="{{ route('admin.settings.update') }}" method="POST">
                        @csrf

                        <div class="mb-6">
                            <label for="ai_system_prompt" class="block font-medium text-sm text-gray-700 mb-2">Instruksi Sistem AI (System Prompt)</label>
                            <p class="text-sm text-gray-500 mb-3">Tuliskan kepribadian, gaya bahasa, dan aturan bagaimana AI harus membalas pesan pelanggan. Jika kosong, sistem akan menggunakan instruksi bawaan.</p>
                            
                            <textarea id="ai_system_prompt" name="ai_system_prompt" rows="8" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm w-full" placeholder="Contoh: Anda adalah asisten pelanggan yang ramah...">{{ old('ai_system_prompt', $promptSetting->value) }}</textarea>
                            
                            @error('ai_system_prompt')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <x-primary-button>
                                {{ __('Simpan Pengaturan') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
