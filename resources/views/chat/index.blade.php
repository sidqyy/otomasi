<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Chat Inbox') }}
        </h2>
    </x-slot>

    <div class="py-4 h-[75vh] min-h-[500px]" x-data="chatApp()">
        <div class="max-w-7xl mx-auto h-full">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg flex h-full border border-gray-200">
                
                <!-- Sidebar Kontak -->
                <div class="w-1/3 md:w-1/4 border-r border-gray-200 flex flex-col bg-gray-50 min-w-[250px]">
                    <div class="p-4 border-b border-gray-200 bg-white">
                        <input type="text" placeholder="Cari percakapan..." class="w-full border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>
                    <div class="flex-1 overflow-y-auto">
                        @foreach($contacts as $contact)
                            <div @click="loadMessages({{ $contact->id }})" class="p-4 border-b border-gray-100 hover:bg-gray-100 cursor-pointer flex items-center transition duration-150 ease-in-out">
                                <div class="w-12 h-12 bg-indigo-100 rounded-full flex items-center justify-center text-indigo-500 font-bold text-lg">
                                    {{ substr($contact->push_name ?? $contact->name ?? 'U', 0, 1) }}
                                </div>
                                <div class="ml-4 flex-1">
                                    <h4 class="text-sm font-bold text-gray-900">{{ $contact->push_name ?? $contact->name ?? $contact->phone_number }}</h4>
                                    <p class="text-xs text-gray-500 truncate">
                                        {{ $contact->messages->first()->content ?? 'Mulai percakapan' }}
                                    </p>
                                </div>
                                <div class="text-xs text-gray-400">
                                    {{ $contact->messages->first() ? $contact->messages->first()->created_at->format('H:i') : '' }}
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Chat Area -->
                <div class="w-2/3 flex flex-col bg-[#efeae2]">
                    <template x-if="!activeContact">
                        <div class="flex-1 flex items-center justify-center text-gray-500 flex-col">
                            <svg class="w-16 h-16 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
                            <p>Pilih kontak untuk memulai percakapan</p>
                        </div>
                    </template>
                    
                    <template x-if="activeContact">
                        <div class="flex flex-col h-full">
                            <!-- Header Chat -->
                            <div class="p-4 bg-white border-b border-gray-200 flex items-center shadow-sm z-10">
                                <div class="w-10 h-10 bg-indigo-100 rounded-full flex items-center justify-center text-indigo-500 font-bold">
                                    <span x-text="activeContact.push_name ? activeContact.push_name.charAt(0) : 'U'"></span>
                                </div>
                                <div class="ml-4">
                                    <h3 class="font-bold text-gray-800" x-text="activeContact.push_name || activeContact.phone_number"></h3>
                                    <span class="text-xs text-green-500 flex items-center">
                                        <span class="w-2 h-2 bg-green-500 rounded-full mr-1"></span> Aktif
                                    </span>
                                </div>
                                <div class="ml-auto">
                                    <button class="px-3 py-1 bg-red-100 text-red-600 rounded text-xs font-bold hover:bg-red-200 transition">Ambil Alih Chat</button>
                                </div>
                            </div>

                            <!-- Messages Area -->
                            <div class="flex-1 p-4 overflow-y-auto space-y-4" id="chat-messages" style="background-image: url('https://user-images.githubusercontent.com/15075759/28719144-86dc0f70-73b1-11e7-911d-60d70fcded21.png'); background-repeat: repeat;">
                                <template x-for="message in messages" :key="message.id">
                                    <div :class="message.direction === 'inbound' ? 'flex justify-start' : 'flex justify-end'">
                                        <div :class="message.direction === 'inbound' ? 'bg-white text-gray-800' : 'bg-[#dcf8c6] text-gray-800'" class="max-w-md rounded-lg p-3 shadow-sm relative">
                                            <p class="text-sm" x-text="message.content"></p>
                                            <span class="text-[10px] text-gray-500 block text-right mt-1" x-text="formatTime(message.created_at)"></span>
                                        </div>
                                    </div>
                                </template>
                            </div>

                            <!-- Input Area -->
                            <div class="p-4 bg-gray-100 border-t border-gray-200">
                                <form @submit.prevent="sendMessage" class="flex items-center space-x-2">
                                    <button type="button" class="text-gray-500 hover:text-gray-700 p-2">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path></svg>
                                    </button>
                                    <input x-model="newMessage" type="text" class="flex-1 border-gray-300 rounded-full shadow-sm focus:border-indigo-500 focus:ring-indigo-500 py-2 px-4" placeholder="Ketik pesan...">
                                    <button type="submit" class="bg-indigo-600 text-white rounded-full p-2 hover:bg-indigo-700 transition" :disabled="!newMessage.trim()">
                                        <svg class="w-6 h-6 transform rotate-90" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </template>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function chatApp() {
            return {
                activeContact: null,
                messages: [],
                newMessage: '',
                
                loadMessages(contactId) {
                    fetch(`/chat/${contactId}`)
                        .then(res => res.json())
                        .then(data => {
                            this.activeContact = data.contact;
                            this.messages = data.messages;
                            this.scrollToBottom();
                        });
                },

                sendMessage() {
                    if (!this.newMessage.trim() || !this.activeContact) return;
                    
                    const content = this.newMessage;
                    this.newMessage = '';

                    // Optimistic update
                    this.messages.push({
                        id: Date.now(),
                        content: content,
                        direction: 'outbound',
                        created_at: new Date().toISOString()
                    });
                    this.scrollToBottom();

                    fetch(`/chat/${this.activeContact.id}/send`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({ message: content })
                    });
                },

                formatTime(dateString) {
                    const date = new Date(dateString);
                    return date.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
                },

                scrollToBottom() {
                    setTimeout(() => {
                        const container = document.getElementById('chat-messages');
                        if (container) {
                            container.scrollTop = container.scrollHeight;
                        }
                    }, 50);
                }
            }
        }
    </script>
    @endpush
</x-app-layout>
