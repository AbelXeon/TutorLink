@extends('Layouts.Layout')

@section('title', 'Inbox - TutorLink')

@section('content')

<style>
    @import url('https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Inter:wght@400;500;600;700;800&display=swap');

    :root{
        --ink:#0a0a0a;
        --paper:#f5f4f1;
        --white:#ffffff;
        --blue:#1350e0;
        --blue-dark:#0d3aa8;
        --line: rgba(10,10,10,0.14);
    }
    .inbox-wrap { font-family: 'Inter', sans-serif; overflow-x: hidden; }
    .display-font {
        font-family: 'Bebas Neue', sans-serif;
        letter-spacing: 0.03em;
        text-transform: uppercase;
    }
    .swiss-panel { border-radius: 0; border: 1px solid var(--line); box-shadow: none; }
    .avatar-flat {
        background: var(--paper);
        color: var(--ink);
        border: 1px solid var(--line);
    }
    .convo-link { transition: background-color .15s ease; }
    .convo-link:hover { background: var(--paper); }
    .convo-active { border-left: 3px solid var(--blue); background: rgba(19,80,224,0.05); }
    .convo-inactive { border-left: 3px solid transparent; }

    .msg-bubble-me { background: var(--blue); color: var(--white); border-radius: 0; }
    .msg-bubble-other { background: var(--white); color: var(--ink); border: 1px solid var(--line); border-radius: 0; }

    .icon-btn-flat {
        border-radius: 0;
        background: var(--paper);
        border: 1px solid var(--line);
        color: #6b6b6b;
        transition: background-color .15s ease, color .15s ease;
    }
    .icon-btn-flat:hover { background: var(--white); color: var(--blue); }
    .icon-btn-flat.is-active { background: var(--blue); color: var(--white); border-color: var(--blue); }

    .chat-input-flat {
        border-radius: 0;
        border: 1px solid var(--line);
    }
    .chat-input-flat:focus { outline: none; border-color: var(--ink); box-shadow: none; }

    .btn-send-flat {
        border-radius: 0;
        background: var(--ink);
        color: var(--white);
        transition: background-color .15s ease;
    }
    .btn-send-flat:hover { background: var(--blue); }
    .btn-send-flat:disabled { opacity: 0.5; }

    .map-pin-tag { color: var(--blue); }

    @media (max-width: 767px) {
        .inbox-shell { height: calc(100vh - 8rem) !important; }
    }
    @media (max-width: 380px) {
        .icon-btn-flat { padding: 0.4rem !important; }
        .icon-btn-flat svg { height: 1.1rem; width: 1.1rem; }
        .btn-send-flat { padding: 0.4rem !important; }
        .btn-send-flat svg { height: 1.1rem; width: 1.1rem; }
    }
</style>

<div class="inbox-wrap">
<div class="inbox-shell swiss-panel bg-white overflow-hidden flex h-[calc(100vh-10rem)] relative">

    <!-- LEFT SIDEBAR: ACTIVE CONVERSATIONS LIST -->
    <div class="{{ isset($activeConversation) ? 'hidden md:flex' : 'flex' }} w-full md:w-1/3 border-r flex-col" style="border-color: var(--line);">
        <div class="p-4 border-b flex justify-between items-center" style="border-color: var(--line); background: var(--paper);">
            <h3 class="text-sm display-font text-gray-900">Conversations</h3>

            @if(strtolower(Auth::user()->role?->role_type) === 'teacher')
                <a href="{{ route('tutor.dashboard') }}" class="text-xs font-semibold flex items-center gap-1 transition" style="color: var(--blue);">
                    <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M15 6L9 12L15 18" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    Dashboard
                </a>
            @else
                <a href="{{ route('student.dashboard') }}" class="text-xs font-semibold flex items-center gap-1 transition" style="color: var(--blue);">
                    <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M15 6L9 12L15 18" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    Dashboard
                </a>
            @endif
        </div>

        <div class="flex-grow overflow-y-auto divide-y" style="border-color: var(--line);">
            @forelse($conversations as $conv)
                @php
                    $otherUser = (Auth::id() === $conv->student_id) ? $conv->tutor : $conv->student;
                    $lastMessage = $conv->messages->first();
                @endphp
                <a href="{{ route('messages.show', $otherUser->username) }}" class="convo-link {{ isset($activeConversation) && $activeConversation->id === $conv->id ? 'convo-active' : 'convo-inactive' }} flex items-center gap-3 p-4">

                    <div class="avatar-flat w-10 h-10 rounded-full flex items-center justify-center font-bold flex-shrink-0">
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
    <div class="{{ isset($activeConversation) ? 'flex' : 'hidden md:flex' }} w-full md:w-2/3 flex-col" style="background: var(--paper);">
        @if(isset($activeConversation))
            @php
                $chatPartner = (Auth::id() === $activeConversation->student_id) ? $activeConversation->tutor : $activeConversation->student;
            @endphp

            <!-- Chat Partner Header -->
            <div class="bg-white p-4 border-b flex items-center gap-3 z-10" style="border-color: var(--line);">
                <a href="{{ route('messages.index') }}" class="md:hidden text-gray-500 hover:text-gray-800 -ml-1 p-1">
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M15 6L9 12L15 18" stroke-linecap="round" stroke-linejoin="round"/></svg>
                </a>
                <div class="avatar-flat w-10 h-10 rounded-full flex items-center justify-center font-bold">
                    {{ substr($chatPartner->first_name, 0, 1) }}
                </div>
                <div>
                    <h4 class="text-sm font-bold text-gray-900">{{ $chatPartner->first_name }} {{ $chatPartner->last_name }}</h4>
                    <span class="text-xs text-green-600 font-semibold flex items-center gap-1">
                        <span class="h-2 w-2 rounded-full bg-green-500 animate-pulse"></span> Active Now
                    </span>
                </div>
            </div>

            <!-- Messages Window (Scrollable) -->
            <div id="messages_window" class="flex-grow p-4 sm:p-6 overflow-y-auto space-y-4">
                @foreach($activeConversation->messages as $msg)
                    @php
                        $isMe = ($msg->sender_id === Auth::id());
                    @endphp
                    <div class="flex {{ $isMe ? 'justify-end' : 'justify-start' }} last-message-marker" data-id="{{ $msg->id }}">
                        <div class="max-w-[85%] sm:max-w-[70%] p-3 text-sm {{ $isMe ? 'msg-bubble-me' : 'msg-bubble-other' }}">

                            @if(!$msg->file_type)
                                <p>{{ $msg->message_text }}</p>

                            @elseif($msg->file_type == 'image')
                                <div class="space-y-1">
                                    <img src="{{ $msg->file_path) }}" class="max-h-60 object-cover cursor-pointer" alt="Attachment" onclick="openImageLightbox(this.src)" />
                                    @if($msg->message_text) <p class="mt-1">{{ $msg->message_text }}</p> @endif
                                </div>

                            @elseif($msg->file_type == 'document')
                                <div class="flex items-center gap-2">
                                    <svg class="h-8 w-8 text-red-500" fill="currentColor" viewBox="0 0 20 20"><path d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z"/></svg>
                                    <div class="truncate">
                                        <a href="{{ $msg->file_path) }}" download class="font-bold underline truncate block">Download Attachment</a>
                                        <span class="text-[10px] opacity-70">Secure Document File</span>
                                    </div>
                                </div>

                            @elseif($msg->file_type == 'location')
                                @php
                                    $coords = explode(',', $msg->message_text);
                                @endphp
                                <div class="space-y-2">
                                    <span class="text-xs font-bold block uppercase tracking-wider opacity-80">Shared Location Pin</span>
                                    <a href="https://www.google.com/maps?q={{ $coords[0] }},{{ $coords[1] }}" target="_blank" class="inline-flex items-center gap-1.5 bg-white font-bold px-3 py-1.5 border hover:bg-gray-50 transition text-xs" style="color: var(--blue); border-color: var(--line);">
                                        <svg class="w-3.5 h-3.5 map-pin-tag" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 21C12 21 19 14.5 19 9.5C19 5.4 15.9 2 12 2C8.1 2 5 5.4 5 9.5C5 14.5 12 21 12 21Z" stroke-linejoin="round"/><circle cx="12" cy="9.5" r="2.5"/></svg>
                                        View Live Map
                                    </a>
                                </div>
                            @endif

                            <span class="block text-[10px] text-right mt-1 opacity-70">{{ $msg->created_at->format('g:i A') }}</span>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Unified Attachment & Location Preview Box -->
            <div id="preview_container" class="hidden px-4 sm:px-6 py-3 border-t flex items-center justify-between" style="background: var(--white); border-color: var(--line);">
                <div class="flex items-center gap-3">
                    <img id="image_preview" src="" class="h-16 w-16 object-cover border hidden" style="border-color: var(--line);" alt="Preview" />

                    <div id="location_preview" class="hidden flex items-center gap-2 font-bold text-sm" style="color: var(--blue);">
                        <svg class="w-4 h-4 map-pin-tag" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 21C12 21 19 14.5 19 9.5C19 5.4 15.9 2 12 2C8.1 2 5 5.4 5 9.5C5 14.5 12 21 12 21Z" stroke-linejoin="round"/><circle cx="12" cy="9.5" r="2.5"/></svg>
                        <span id="location_coords_text"></span>
                    </div>

                    <span id="preview_text" class="text-xs text-gray-500 font-medium">Ready to send.</span>
                </div>
                <button type="button" id="clear_preview_btn" class="text-xs font-semibold text-red-600 hover:text-red-800">Remove</button>
            </div>

            <!-- Message Input & Attachment Submission bar -->
            <div class="bg-white p-3 sm:p-4 border-t" style="border-color: var(--line);">
                <form id="chat_form" action="{{ route('messages.store', $activeConversation->id) }}" method="POST" enctype="multipart/form-data" class="flex items-center gap-2 sm:gap-3">
                    @csrf

                    <label for="attachment" class="icon-btn-flat p-2 cursor-pointer shrink-0" title="Attach Image or Document">
                        <svg class="h-5 w-5 sm:h-6 sm:w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                        </svg>
                    </label>
                    <input id="attachment" name="attachment" type="file" class="hidden" accept=".jpg,.jpeg,.png,.pdf,.doc,.docx" />

                    <button type="button" id="geo_btn" class="icon-btn-flat p-2 shrink-0" title="Share Current Location">
                        <svg class="h-5 w-5 sm:h-6 sm:w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                    </button>

                    <input type="hidden" id="latitude" name="latitude">
                    <input type="hidden" id="longitude" name="longitude">

                    <input id="chat_input" name="message_text" type="text" autocomplete="off" placeholder="Type a message..." class="chat-input-flat flex-1 min-w-0 py-2 px-3 sm:px-4 text-sm" />

                    <button type="submit" id="submit_send_btn" class="btn-send-flat p-2 shrink-0">
                        <svg class="h-5 w-5 sm:h-6 sm:w-6 transform rotate-90" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                        </svg>
                    </button>
                </form>
            </div>

            <!-- JAVASCRIPT: INSTANT OPTIMISTIC SENDER, FAST GEO & WEBSOCKET/POLL CONTROLLER -->
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

                const renderedMessageIds = new Set();
                document.querySelectorAll('.last-message-marker').forEach(element => {
                    if (element.getAttribute('data-id')) {
                        renderedMessageIds.add(parseInt(element.getAttribute('data-id')));
                    }
                });

                let lastMessageId = {{ $activeConversation->messages->last()?->id ?? 0 }};
                const currentUserId = {{ Auth::id() }};
                const conversationId = {{ $activeConversation->id }};

                messagesWindow.scrollTop = messagesWindow.scrollHeight;

                // 1. FILE PREVIEW CONTROLLER
                attachmentInput.addEventListener('change', function() {
                    const file = this.files[0];
                    if (file) {
                        const extension = file.name.split('.').pop().toLowerCase();
                        locationPreview.classList.add('hidden');
                        latInput.value = '';
                        lngInput.value = '';
                        geoBtn.classList.remove('is-active');
                        
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
                    geoBtn.classList.remove('is-active');
                });

                // 2. INSTANT OPTIMISTIC SUBMIT (Instant Feedback like Telegram)
                chatForm.addEventListener('submit', function(e) {
                    e.preventDefault();

                    const messageText = chatInput.value.trim();
                    const hasFile = attachmentInput.files && attachmentInput.files.length > 0;
                    const hasLocation = latInput.value !== '';

                    if (!messageText && !hasFile && !hasLocation) return;

                    const tempId = 'temp_' + Date.now();
                    
                    // Optimistically show message immediately
                    if (!hasFile) {
                        const optimisticMsg = {
                            id: tempId,
                            message_text: hasLocation ? `${latInput.value},${lngInput.value}` : messageText,
                            file_type: hasLocation ? 'location' : null,
                            file_path: null,
                            is_optimistic: true
                        };
                        appendMessageBubble(optimisticMsg, true);
                    }

                    const formData = new FormData(this);

                    // Reset form inputs instantly
                    chatInput.value = '';
                    attachmentInput.value = '';
                    previewContainer.classList.add('hidden');
                    imagePreview.src = '';
                    locationPreview.classList.add('hidden');
                    latInput.value = '';
                    lngInput.value = '';
                    chatInput.placeholder = "Type a message...";
                    chatInput.disabled = false;
                    geoBtn.classList.remove('is-active');

                    fetch(this.action, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Replace temporary optimistic message with real message
                            const tempElem = document.querySelector(`[data-id="${tempId}"]`);
                            if (tempElem) {
                                tempElem.setAttribute('data-id', data.message.id);
                                renderedMessageIds.add(data.message.id);
                            } else {
                                appendMessageBubble(data.message, true);
                            }
                            lastMessageId = Math.max(lastMessageId, data.message.id);
                        }
                    })
                    .catch(err => {
                        console.error("Send error:", err);
                        const tempElem = document.querySelector(`[data-id="${tempId}"]`);
                        if (tempElem) {
                            tempElem.style.opacity = '0.5';
                            tempElem.title = 'Failed to send. Check connection.';
                        }
                    });
                });

                // 3. FAST REAL-TIME UPDATES (WebSocket + Fallback Polling)
                function setupWebSocket() {
                    if (window.Echo) {
                        window.Echo.private(`chat.${conversationId}`)
                            .listen('MessageSent', (e) => {
                                if (e.message.sender_id !== currentUserId) {
                                    appendMessageBubble(e.message, false);
                                    let alertSound = new Audio('/sounds/notification.mp3');
                                    alertSound.play().catch(() => {});
                                }
                            });
                        return true;
                    }
                    return false;
                }

                // Polling runs smoothly (backed by WebSocket if available)
                function pollMessages() {
                    if (!lastMessageId) return;

                    fetch("{{ route('messages.updates', $activeConversation->id) }}?last_message_id=" + lastMessageId, {
                        headers: { 'X-Requested-With': 'XMLHttpRequest' }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.messages && data.messages.length > 0) {
                            data.messages.forEach(msg => {
                                const isMe = (msg.sender_id === data.current_user_id);
                                appendMessageBubble(msg, isMe);
                            });

                            const lastMsg = data.messages[data.messages.length - 1];
                            if (lastMsg.sender_id !== data.current_user_id) {
                                let alertSound = new Audio('/sounds/notification.mp3');
                                alertSound.play().catch(() => {});
                            }
                        }
                    })
                    .catch(err => console.error(err));
                }

                if (!setupWebSocket()) {
    setInterval(pollMessages, 5000); 
}

                function appendMessageBubble(msg, isMe) {
                    if (msg.id && typeof msg.id === 'number' && renderedMessageIds.has(msg.id)) return;
                    if (msg.id && typeof msg.id === 'number') {
                        renderedMessageIds.add(msg.id);
                        lastMessageId = Math.max(lastMessageId, msg.id);
                    }

                    const div = document.createElement('div');
                    div.className = `flex ${isMe ? 'justify-end' : 'justify-start'} last-message-marker`;
                    div.setAttribute('data-id', msg.id);

                    let msgBody = '';
                    if (!msg.file_type) {
                        msgBody = `<p>${escapeHtml(msg.message_text)}</p>`;
                    } else if (msg.file_type === 'image') {
    const imgSrc = msg.file_path || '';
    msgBody = `<img src="${imgSrc}" class="max-h-60 object-cover cursor-pointer" onclick="openImageLightbox(this.src)" />`;
    if (msg.message_text) msgBody += `<p class="mt-1">${escapeHtml(msg.message_text)}</p>`;
} else if (msg.file_type === 'document') {
    msgBody = `
        <div class="flex items-center gap-2">
            <svg class="h-8 w-8 text-red-500" fill="currentColor" viewBox="0 0 20 20"><path d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z"/></svg>
            <a href="${msg.file_path}" download class="font-bold underline truncate block">Download Attachment</a>
        </div>
    `;
                    } else if (msg.file_type === 'location') {
                        const coords = (msg.message_text || '').split(',');
                        msgBody = `
                            <span class="text-xs font-bold block uppercase tracking-wider opacity-80">Shared Location Pin</span>
                            <a href="https://www.google.com/maps?q=${coords[0]},${coords[1]}" target="_blank" class="inline-flex items-center gap-1.5 bg-white font-bold px-3 py-1.5 border hover:bg-gray-50 transition text-xs" style="color:#1350e0; border-color: rgba(10,10,10,0.14);">
                                <svg class="w-3.5 h-3.5" style="color:#1350e0;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 21C12 21 19 14.5 19 9.5C19 5.4 15.9 2 12 2C8.1 2 5 5.4 5 9.5C5 14.5 12 21 12 21Z" stroke-linejoin="round"/><circle cx="12" cy="9.5" r="2.5"/></svg>
                                View Live Map
                            </a>
                        `;
                    }

                    div.innerHTML = `
                        <div class="max-w-[85%] sm:max-w-[70%] p-3 text-sm ${isMe ? 'msg-bubble-me' : 'msg-bubble-other'}">
                            ${msgBody}
                            <span class="block text-[10px] text-right mt-1 opacity-70">Just Now</span>
                        </div>
                    `;

                    messagesWindow.appendChild(div);
                    messagesWindow.scrollTop = messagesWindow.scrollHeight;
                }

                function escapeHtml(text) {
                    if (!text) return '';
                    const div = document.createElement('div');
                    div.textContent = text;
                    return div.innerHTML;
                }

                // 4. FAST HIGH-SPEED GEOLOCATION CONTROLLER
                geoBtn.addEventListener('click', function() {
                    if (navigator.geolocation) {
                        geoBtn.classList.add('is-active');
                        previewText.textContent = "Acquiring GPS position...";
                        previewContainer.classList.remove('hidden');

                        const geoOptions = {
                            enableHighAccuracy: false, // Prevents 10-second GPS satellite stall
                            timeout: 5000,             // Fast 5s fallback
                            maximumAge: 60000          // Uses instant cache if available
                        };

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

                                chatInput.placeholder = "Location pinned! Ready to send.";
                                chatInput.disabled = true;
                            },
                            function(error) {
                                alert("Failed to fetch location. Please check your browser's location permissions.");
                                geoBtn.classList.remove('is-active');
                                previewContainer.classList.add('hidden');
                            },
                            geoOptions
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
                <h4 class="mt-4 text-lg display-font text-gray-900">Your Inbox</h4>
                <p class="text-sm text-gray-500 max-w-sm mt-1">Select an active conversation from the sidebar list to start exchanging messages, attachments, and locations securely.</p>
            </div>
        @endif
    </div>

</div>
</div>

<!-- IMAGE LIGHTBOX MODAL -->
<div id="image_lightbox" class="hidden fixed inset-0 z-50 bg-black bg-opacity-90 flex items-center justify-center p-4 transition duration-300 select-none">
    <button type="button" id="close_lightbox_btn" class="absolute top-6 right-6 text-white hover:text-gray-300 focus:outline-none">
        <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
        </svg>
    </button>
    <img id="lightbox_image" src="" class="max-w-full max-h-[90vh] object-contain shadow-lg" alt="Full size image" />
</div>

<script>
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

    lightbox.addEventListener('click', (e) => {
        if (e.target === lightbox) {
            lightbox.classList.add('hidden');
            lightboxImg.src = '';
        }
    });
</script>
@endsection