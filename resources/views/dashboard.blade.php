<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
                <!-- Total Chat Today -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-blue-500">
                    <div class="text-sm text-gray-500 uppercase font-bold">Total Chat Hari Ini</div>
                    <div class="text-3xl font-extrabold text-gray-900 mt-2">{{ $stats['total_chat_today'] }}</div>
                </div>

                <!-- Total Customer -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-green-500">
                    <div class="text-sm text-gray-500 uppercase font-bold">Total Customer</div>
                    <div class="text-3xl font-extrabold text-gray-900 mt-2">{{ $stats['total_customer'] }}</div>
                </div>

                <!-- Total Chat Active -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-yellow-500">
                    <div class="text-sm text-gray-500 uppercase font-bold">Total Chat Aktif</div>
                    <div class="text-3xl font-extrabold text-gray-900 mt-2">{{ $stats['total_chat_active'] }}</div>
                </div>

                <!-- Webhook Status -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-purple-500">
                    <div class="text-sm text-gray-500 uppercase font-bold">Status Webhook</div>
                    <div class="text-3xl font-extrabold text-gray-900 mt-2">
                        @if($stats['webhook_status'] == 'Active')
                            <span class="text-green-600">{{ $stats['webhook_status'] }}</span>
                        @else
                            <span class="text-red-600">{{ $stats['webhook_status'] }}</span>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Tambahan Section Grafik / Statistik -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-bold mb-4">Grafik Aktivitas Chat</h3>
                    <div class="h-64 bg-gray-50 rounded flex items-center justify-center border border-dashed border-gray-300">
                        <span class="text-gray-400">Area Grafik (Chart.js akan diimplementasikan pada Tahap 4)</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
