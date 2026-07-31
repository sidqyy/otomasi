<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Products') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white/60 backdrop-blur-xl border border-white shadow-xl shadow-slate-200/50 sm:rounded-2xl transition-all duration-300">
                <div class="p-8 text-slate-800">
                    <div class="flex justify-between items-center mb-8">
                        <h3 class="text-2xl font-semibold text-slate-800 tracking-tight">Manage Products</h3>
                        <a href="{{ route('admin.products.create') }}" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-emerald-500 to-teal-600 border border-transparent rounded-xl font-semibold text-sm text-white uppercase tracking-wide hover:from-emerald-400 hover:to-teal-500 hover:shadow-lg hover:-translate-y-0.5 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 transition-all duration-300 ease-in-out">
                            Add New Product
                        </a>
                    </div>

                    <div class="overflow-x-auto rounded-xl border border-slate-100">
                        <table class="min-w-full divide-y divide-slate-100">
                            <thead class="bg-slate-50/80">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product Name</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($products as $product)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $product->name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Rp {{ number_format($product->price, 0, ',', '.') }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Available</span>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3" class="px-6 py-4 text-center text-gray-500">No products found.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
