<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Add New FAQ') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('admin.faqs.store') }}">
                        @csrf

                        <!-- Question -->
                        <div class="mt-4">
                            <x-input-label for="question" :value="__('Question')" />
                            <x-text-input id="question" class="block mt-1 w-full" type="text" name="question" :value="old('question')" required />
                            <x-input-error :messages="$errors->get('question')" class="mt-2" />
                        </div>

                        <!-- Answer -->
                        <div class="mt-4">
                            <x-input-label for="answer" :value="__('Answer')" />
                            <textarea id="answer" name="answer" rows="4" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block mt-1 w-full" required>{{ old('answer') }}</textarea>
                            <x-input-error :messages="$errors->get('answer')" class="mt-2" />
                        </div>

                        <!-- Status -->
                        <div class="block mt-4">
                            <label for="is_active" class="inline-flex items-center">
                                <input id="is_active" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                                <span class="ms-2 text-sm text-gray-600">{{ __('Active') }}</span>
                            </label>
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md mr-4" href="{{ route('admin.faqs.index') }}">
                                {{ __('Cancel') }}
                            </a>
                            <x-primary-button>
                                {{ __('Save FAQ') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
