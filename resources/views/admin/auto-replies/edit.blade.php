<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Auto Reply') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('admin.auto-replies.update', $autoReply) }}">
                        @csrf
                        @method('PUT')

                        <!-- Keyword -->
                        <div>
                            <x-input-label for="keyword" :value="__('Keyword')" />
                            <x-text-input id="keyword" class="block mt-1 w-full" type="text" name="keyword" :value="old('keyword', $autoReply->keyword)" required autofocus />
                            <x-input-error :messages="$errors->get('keyword')" class="mt-2" />
                        </div>

                        <!-- Match Type -->
                        <div class="mt-4">
                            <x-input-label for="match_type" :value="__('Match Type')" />
                            <select id="match_type" name="match_type" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block mt-1 w-full">
                                <option value="exact" {{ old('match_type', $autoReply->match_type) == 'exact' ? 'selected' : '' }}>Exact (Sama Persis)</option>
                                <option value="contains" {{ old('match_type', $autoReply->match_type) == 'contains' ? 'selected' : '' }}>Contains (Mengandung Kata)</option>
                            </select>
                            <x-input-error :messages="$errors->get('match_type')" class="mt-2" />
                        </div>

                        <!-- Reply Text -->
                        <div class="mt-4">
                            <x-input-label for="reply_text" :value="__('Reply Text')" />
                            <textarea id="reply_text" name="reply_text" rows="5" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block mt-1 w-full" required>{{ old('reply_text', $autoReply->reply_text) }}</textarea>
                            <x-input-error :messages="$errors->get('reply_text')" class="mt-2" />
                        </div>

                        <!-- Status -->
                        <div class="block mt-4">
                            <label for="is_active" class="inline-flex items-center">
                                <input id="is_active" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="is_active" value="1" {{ old('is_active', $autoReply->is_active) ? 'checked' : '' }}>
                                <span class="ms-2 text-sm text-gray-600">{{ __('Active') }}</span>
                            </label>
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 mr-4" href="{{ route('admin.auto-replies.index') }}">
                                {{ __('Cancel') }}
                            </a>
                            <x-primary-button>
                                {{ __('Update') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
