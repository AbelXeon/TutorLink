@extends('Layouts.Layout')

@section('title', 'Inbox - TutorLink')

@section('content')
<div class="bg-white rounded-lg shadow-md border border-gray-200 overflow-hidden flex h-[calc(100vh-10rem)] relative">

    <!-- LEFT SIDEBAR: ACTIVE CONVERSATIONS LIST -->
    <div class="w-1/3 border-r border-gray-200 flex flex-col">
        <!-- UPDATED: Dynamic Sidebar Header with Go Home Arrow Redirection -->
        <div class="p-4 border-b border-gray-200 flex justify-between items-center bg-gray-50">
            <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wider">Conversations</h3>

            @if(strtolower(Auth::user()->role?->role_type) === 'teacher')
                <a href="{{ route('tutor.dashboard') }}" class="text-xs font-semibold text-indigo-600 hover:text-indigo-500 flex items-center gap-1 transition">
                    &larr; Dashboard
                </a>
            @else
                <a href="{{ route('student.dashboard') }}" class="text-xs font-semibold text-indigo-600 hover:text-indigo-500 flex items-center gap-1 transition">
                    &larr; Dashboard
                </a>
            @endif
        </div>

        <div class="flex-grow overflow-y-auto divide-y divide-gray-100">
            @forelse($conversations as $conv)
                @php
                    $otherUser = (Auth::id() === $conv->student_id) ? $conv->tutor : $conv->student;
                    $lastMessage = $conv->messages->first();
                @endphp
                <a href="{{ route('messages.show', $otherUser->username) }}" class="flex items-center gap-3 p-4 hover:bg-gray-50 transition {{ isset($activeConversation) && $activeConversation->id === $conv->id ? 'bg-indigo-50' : '' }}">

                    <div class="w-10 h-10 rounded-full bg-indigo-100 flex items-center justify-center font-bold text-indigo-600 flex-shrink-0">
                        {{ substr($otherUser->first_name, 0, 1) }}
                    </div>

                    <div class="flex-grow min-w-0">
                        <div class="flex justify-between items-baseline mb-0.5">
                            <span class="text-sm font-bold text-gray-900 truncate">{{ $otherUser->first_name }} {{ $otherUser->last_name }}</span>
                            @if($lastMessage)
                                <span class="text-[10px] text-gray-400">{{ $lastMessage->created_at->diffForHumans() }}</span>
                            @endif
                        </div>
                        <p class="text-xs text-gray-500 truncate">
                            @if($lastMessage)
                                {{ $lastMessage->file_type ? 'Sent an attachment' : $lastMessage->message_text }}
                            @else
                                No messages yet.
                            @endif
                        </p>
                    </div>
                </a>
            @empty
                <p class="text-sm text-gray-500 italic p-6 text-center">No active chat channels yet.</p>
            @endforelse
        </div>
    </div>

    <!-- RIGHT MAIN AREA: ACTIVE CHAT CONTAINER -->
    <div class="w-2/3 flex flex-col bg-gray-50">
        @if(isset($activeConversation))
            @php
                $chatPartner = (Auth::id() === $activeConversation->student_id) ? $activeConversation->tutor : $activeConversation->student;
            @endphp

            <!-- Chat Partner Header -->
            <div class="bg-white p-4 border-b border-gray-200 flex items-center gap-3 shadow-sm z-10">
                <div class="w-10 h-10 rounded-full bg-indigo-100 flex items-center justify-center font-bold text-indigo-600">
                    {{ substr($chatPartner->first_name, 0, 1) }}
                </div>
                <div>
                    <h4 class="text-sm font-bold text-gray-900">{{ $chatPartner->first_name }} {{ $chatPartner->last_name }}</h4>
                    <span class="text-xs text-green-500 font-semibold flex items-center gap-1">
                        <span class="h-2 w-2 rounded-full bg-green-500 animate-pulse"></span> Active Now
                    </span>
                </div>
            </div>

            <!-- Messages Window (Scrollable) -->
            <div id="messages_window" class="flex-grow p-6 overflow-y-auto space-y-4">
                @foreach($activeConversation->messages as $msg)
                    @php
                        $isMe = ($msg->sender_id === Auth::id());
                    @endphp
                    <div class="flex {{ $isMe ? 'justify-end' : 'justify-start' }} last-message-marker" data-id="{{ $msg->id }}">
                        <div class="max-w-[70%] rounded-lg p-3 shadow-sm text-sm {{ $isMe ? 'bg-indigo-600 text-white rounded-br-none' : 'bg-white text-gray-900 rounded-bl-none' }}">

                            @if(!$msg->file_type)
                                <p>{{ $msg->message_text }}</p>

                            @elseif($msg->file_type == 'image')
                                <div class="space-y-1">
                                    <!-- UPDATED: Clicking now runs openImageLightbox() instead of window.open() -->
                                    <img src="{{ asset('storage/' . $msg->file_path) }}" class="rounded max-h-60 object-cover shadow-sm cursor-pointer" alt="Attachment" onclick="openImageLightbox(this.src)" />
                                    @if($msg->message_text) <p class="mt-1">{{ $msg->message_text }}</p> @endif
                                </div>

                            @elseif($msg->file_type == 'document')
                                <div class="flex items-center gap-2">
                                    <svg class="h-8 w-8 text-red-500" fill="currentColor" viewBox="0 0 20 20"><path d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z"/></svg>
                                    <div class="truncate">
                                        <a href="{{ asset('storage/' . $msg->file_path) }}" download class="font-bold underline hover:text-indigo-200 truncate block">Download Attachment</a>
                                        <span class="text-[10px] text-gray-400">Secure Document File</span>
                                    </div>
                                </div>

                            @elseif($msg->file_type == 'location')
                                @php
                                    $coords = explode(',', $msg->message_text);
                                @endphp
                                <div class="space-y-2">
                                    <span class="text-xs font-bold block uppercase tracking-wider text-indigo-200">Shared Location Pin</span>
                                    <a href="https://www.google.com/maps?q={{ $coords[0] }},{{ $coords[1] }}" target="_blank" class="inline-flex items-center gap-1 bg-white text-indigo-600 font-bold px-3 py-1.5 rounded border border-gray-200 hover:bg-gray-50 transition shadow-sm text-xs">
                                        📍 View Live Map
                                    </a>
                                </div>
                            @endif

                            <span class="block text-[10px] text-right mt-1 opacity-70">{{ $msg->created_at->format('g:i A') }}</span>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Unified Attachment & Location Preview Box -->
            <div id="preview_container" class="hidden px-6 py-3 bg-gray-100 border-t border-gray-200 flex items-center justify-between shadow-inner">
                <div class="flex items-center gap-3">
                    <img id="image_preview" src="" class="h-16 w-16 object-cover rounded border border-gray-300 shadow-sm hidden" alt="Preview" />

                    <div id="location_preview" class="hidden flex items-center gap-2 text-indigo-600 font-bold text-sm">
                        📍 <span id="location_coords_text"></span>
                    </div>

                    <span id="preview_text" class="text-xs text-gray-500 font-medium">Ready to send.</span>
                </div>
                <button type="button" id="clear_preview_btn" class="text-xs font-semibold text-red-600 hover:text-red-800">Remove</button>
            </div>

            <!-- Message Input & Attachment Submission bar -->
            <div class="bg-white p-4 border-t border-gray-200 shadow-lg">
                <form id="chat_form" action="{{ route('messages.store', $activeConversation->id) }}" method="POST" enctype="multipart/form-data" class="flex items-center gap-3">
                    @csrf

                    <label for="attachment" class="p-2 bg-gray-100 hover:bg-gray-200 rounded-full cursor-pointer text-gray-500 hover:text-indigo-600 transition" title="Attach Image or Document">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                        </svg>
                    </label>
                    <input id="attachment" name="attachment" type="file" class="hidden" accept=".jpg,.jpeg,.png,.pdf,.doc,.docx" />

                    <button type="button" id="geo_btn" class="p-2 bg-gray-100 hover:bg-gray-200 rounded-full text-gray-500 hover:text-indigo-600 transition" title="Share Current Location">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                    </button>

                    <input type="hidden" id="latitude" name="latitude">
                    <input type="hidden" id="longitude" name="longitude">

                    <input id="chat_input" name="message_text" type="text" autocomplete="off" placeholder="Type a message..." class="flex-grow py-2 px-4 border border-gray-300 rounded-full focus:outline-none focus:ring-2 focus:ring-indigo-500 text-sm" />

                    <button type="submit" id="submit_send_btn" class="p-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-full shadow transition disabled:opacity-50">
                        <svg class="h-6 w-6 transform rotate-90" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                        </svg>
                    </button>
                </form>
            </div>

            <!-- JAVASCRIPT: POLLING & GEOLOCATION CONTROLLER -->
            <script>
                const messagesWindow = document.getElementById('messages_window');
                const chatForm = document.getElementById('chat_form');
                const chatInput = document.getElementById('chat_input');
                const submitSendBtn = document.getElementById('submit_send_btn');
                const attachmentInput = document.getElementById('attachment');
                const previewContainer = document.getElementById('preview_container');
                const imagePreview = document.getElementById('image_preview');
                const locationPreview = document.getElementById('location_preview');
                const locationCoordsText = document.getElementById('location_coords_text');
                const previewText = document.getElementById('preview_text');
                const clearPreviewBtn = document.getElementById('clear_preview_btn');
                const geoBtn = document.getElementById('geo_btn');
                const latInput = document.getElementById('latitude');
                const lngInput = document.getElementById('longitude');

                // Track rendered IDs to prevent loops
                const renderedMessageIds = new Set();
                document.querySelectorAll('.last-message-marker').forEach(element => {
                    renderedMessageIds.add(parseInt(element.getAttribute('data-id')));
                });

                let lastMessageId = {{ $activeConversation->messages->last()?->id ?? 0 }};
                let isSending = false;

                messagesWindow.scrollTop = messagesWindow.scrollHeight;

                // 1. FILE PREVIEW CONTROLLER
                attachmentInput.addEventListener('change', function() {
                    const file = this.files[0];
                    if (file) {
                        const extension = file.name.split('.').pop().toLowerCase();
                        locationPreview.classList.add('hidden');

                        if (['jpg', 'jpeg', 'png'].includes(extension)) {
                            const reader = new FileReader();
                            reader.onload = function(e) {
                                imagePreview.src = e.target.result;
                                imagePreview.classList.remove('hidden');
                                previewText.textContent = "Photo attachment selected.";
                                previewContainer.classList.remove('hidden');
                            }
                            reader.readAsDataURL(file);
                        } else {
                            imagePreview.classList.add('hidden');
                            previewText.textContent = `Document selected: (${file.name})`;
                            previewContainer.classList.remove('hidden');
                        }
                    }
                });

                // Clear Preview
                clearPreviewBtn.addEventListener('click', function() {
                    attachmentInput.value = '';
                    latInput.value = '';
                    lngInput.value = '';
                    chatInput.disabled = false;
                    chatInput.placeholder = "Type a message...";
                    previewContainer.classList.add('hidden');
                    imagePreview.src = '';
                    locationPreview.classList.add('hidden');
                    geoBtn.classList.remove('text-indigo-600');
                });

                // 2. SUBMIT FORM VIA AJAX
                chatForm.addEventListener('submit', function(e) {
                    e.preventDefault();

                    if (isSending) return;

                    const messageText = chatInput.value.trim();
                    const hasFile = attachmentInput.value !== '';
                    const hasLocation = latInput.value !== '';

                    if (!messageText && !hasFile && !hasLocation) return;

                    isSending = true;
                    submitSendBtn.disabled = true;

                    const formData = new FormData(this);

                    fetch(this.action, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        isSending = false;
                        submitSendBtn.disabled = false;

                        if (data.success) {
                            chatInput.value = '';
                            attachmentInput.value = '';
                            previewContainer.classList.add('hidden');
                            imagePreview.src = '';
                            locationPreview.classList.add('hidden');
                            latInput.value = '';
                            lngInput.value = '';
                            chatInput.placeholder = "Type a message...";
                            chatInput.disabled = false;
                            geoBtn.classList.remove('text-indigo-600');

                            appendMessageBubble(data.message, true);
                        }
                    })
                    .catch(err => {
                        isSending = false;
                        submitSendBtn.disabled = false;
                        console.error(err);
                    });
                });

                // 3. REAL-TIME AJAX POLL (3 seconds interval)
                function pollMessages() {
                    if (!lastMessageId) return;

                    fetch("{{ route('messages.updates', $activeConversation->id) }}?last_message_id=" + lastMessageId, {
                        headers: { 'X-Requested-With': 'XMLHttpRequest' }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.messages.length > 0) {
                            data.messages.forEach(msg => {
                                const isMe = (msg.sender_id === data.current_user_id);
                                appendMessageBubble(msg, isMe);
                            });

                            const lastMsg = data.messages[data.messages.length - 1];
                            if (lastMsg.sender_id !== data.current_user_id) {
                                let alertSound = new Audio('/sounds/notification.mp3');
                                alertSound.play().catch(e => console.log("Sound play deferred"));
                            }
                        }
                    })
                    .catch(err => console.error(err));
                }

                function appendMessageBubble(msg, isMe) {
                    if (renderedMessageIds.has(msg.id)) return;

                    renderedMessageIds.add(msg.id);
                    lastMessageId = msg.id;

                    const div = document.createElement('div');
                    div.className = `flex ${isMe ? 'justify-end' : 'justify-start'} last-message-marker`;
                    div.setAttribute('data-id', msg.id);

                    let msgBody = '';
                    if (!msg.file_type) {
                        msgBody = `<p>${msg.message_text}</p>`;
                    } else if (msg.file_type === 'image') {
                        msgBody = `<img src="/storage/${msg.file_path}" class="rounded max-h-60 object-cover shadow-sm cursor-pointer" onclick="openImageLightbox(this.src)" />`;
                        if (msg.message_text) msgBody += `<p class="mt-1">${msg.message_text}</p>`;
                    } else if (msg.file_type === 'document') {
                        msgBody = `
                            <div class="flex items-center gap-2">
                                <svg class="h-8 w-8 text-red-500" fill="currentColor" viewBox="0 0 20 20"><path d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z"/></svg>
                                <a href="/storage/${msg.file_path}" download class="font-bold underline truncate block">Download Attachment</a>
                            </div>
                        `;
                    } else if (msg.file_type === 'location') {
                        const coords = msg.message_text.split(',');
                        msgBody = `
                            <span class="text-xs font-bold block uppercase tracking-wider text-indigo-200">Shared Location Pin</span>
                            <a href="https://www.google.com/maps?q=${coords[0]},${coords[1]}" target="_blank" class="inline-flex items-center gap-1 bg-white text-indigo-600 font-bold px-3 py-1.5 rounded border border-gray-200 hover:bg-gray-50 transition shadow-sm text-xs">📍 View Live Map</a>
                        `;
                    }

                    div.innerHTML = `
                        <div class="max-w-[70%] rounded-lg p-3 shadow-sm text-sm ${isMe ? 'bg-indigo-600 text-white rounded-br-none' : 'bg-white text-gray-900 rounded-bl-none'}">
                            ${msgBody}
                            <span class="block text-[10px] text-right mt-1 opacity-70">Just Now</span>
                        </div>
                    `;

                    messagesWindow.appendChild(div);
                    messagesWindow.scrollTop = messagesWindow.scrollHeight;
                }

                setInterval(pollMessages, 1500); // 1.5 seconds

                // 4. GPS GEOLOCATION CONTROLLER
                geoBtn.addEventListener('click', function() {
                    if (navigator.geolocation) {
                        geoBtn.classList.add('text-indigo-600', 'animate-pulse');

                        navigator.geolocation.getCurrentPosition(
                            function(position) {
                                const lat = position.coords.latitude.toFixed(5);
                                const lng = position.coords.longitude.toFixed(5);

                                latInput.value = position.coords.latitude;
                                lngInput.value = position.coords.longitude;

                                attachmentInput.value = '';
                                imagePreview.classList.add('hidden');

                                locationCoordsText.textContent = `${lat}, ${lng}`;
                                locationPreview.classList.remove('hidden');
                                previewText.textContent = "Current GPS Coordinates pinned.";
                                previewContainer.classList.remove('hidden');

                                chatInput.placeholder = "Location pinned! Ready to send.";
                                chatInput.disabled = true;
                                geoBtn.classList.remove('animate-pulse');
                            },
                            function(error) {
                                alert("Failed to fetch location. Please check your browser's location permissions.");
                                geoBtn.classList.remove('text-indigo-600', 'animate-pulse');
                            }
                        );
                    } else {
                        alert("Geolocation is not supported by your browser.");
                    }
                });
            </script>
        @else
            <!-- Default placeholder when no active conversation is opened -->
            <div class="flex-grow flex flex-col items-center justify-center text-center p-6">
                <svg class="h-16 w-16 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                </svg>
                <h4 class="mt-4 text-lg font-bold text-gray-900">Your Inbox</h4>
                <p class="text-sm text-gray-500 max-w-sm mt-1">Select an active conversation from the sidebar list to start exchanging messages, attachments, and locations securely.</p>
            </div>
        @endif
    </div>

</div>

<!-- NEW: IMAGE LIGHTBOX MODAL (Allows full-screen viewing without leaving the page) -->
<div id="image_lightbox" class="hidden fixed inset-0 z-50 bg-black bg-opacity-90 flex items-center justify-center p-4 transition duration-300 select-none">
    <button type="button" id="close_lightbox_btn" class="absolute top-6 right-6 text-white hover:text-gray-300 focus:outline-none">
        <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
        </svg>
    </button>
    <img id="lightbox_image" src="" class="max-w-full max-h-[90vh] object-contain rounded shadow-lg" alt="Full size image" />
</div>

<script>
    // LIGHTBOX JAVASCRIPT CONTROLLER
    const lightbox = document.getElementById('image_lightbox');
    const lightboxImg = document.getElementById('lightbox_image');
    const closeLightboxBtn = document.getElementById('close_lightbox_btn');

    function openImageLightbox(src) {
        lightboxImg.src = src;
        lightbox.classList.remove('hidden');
    }

    closeLightboxBtn.addEventListener('click', () => {
        lightbox.classList.add('hidden');
        lightboxImg.src = '';
    });

    // Close lightbox if clicking outside the image background
    lightbox.addEventListener('click', (e) => {
        if (e.target === lightbox) {
            lightbox.classList.add('hidden');
            lightboxImg.src = '';
        }
    });
</script>
@endsection
