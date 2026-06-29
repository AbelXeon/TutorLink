<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Messages - TutorLink</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .chat-container {
            height: calc(100vh - 120px);
        }
        .conversations-sidebar {
            height: 100%;
            overflow-y: auto;
            border-right: 1px solid #dee2e6;
        }
        .chat-window {
            height: 100%;
            display: flex;
            flex-direction: column;
        }
        .chat-messages {
            flex-grow: 1;
            overflow-y: auto;
            padding: 1.5rem;
            background-color: #f8f9fa;
        }
        .message-bubble {
            max-width: 70%;
            padding: 0.75rem 1rem;
            border-radius: 15px;
            margin-bottom: 1rem;
        }
        .message-sent {
            background-color: #0d6efd;
            color: white;
            align-self: flex-end;
            border-bottom-right-radius: 2px;
        }
        .message-received {
            background-color: #e9ecef;
            color: #212529;
            align-self: flex-start;
            border-top-left-radius: 2px;
        }
    </style>
</head>
<body class="bg-light">

<!-- Top Navigation -->
<nav class="navbar navbar-dark bg-dark shadow-sm">
    <div class="container">
        <a class="navbar-brand fw-bold" href="javascript:history.back()">← Back</a>
    </div>
</nav>

<div class="container py-3">
    <div class="card shadow border-0 chat-container">
        <div class="row g-0 h-100">

            <!-- Left Panel: Conversation Sidebar List -->
            <div class="col-md-4 conversations-sidebar bg-white p-3">
                <h5 class="fw-bold text-dark mb-3">Chats</h5>
                
                <div class="list-group list-group-flush">
                    @forelse($conversations as $convo)
                        @if($convo->other_user)
                            <a href="{{ route('Messages.Message', $convo->id) }}" 
                               class="list-group-item list-group-item-action py-3 border-0 rounded mb-2 d-flex align-items-center gap-3 {{ $activeConvo && $activeConvo->id == $convo->id ? 'bg-primary text-white' : 'bg-light' }}">
                                
                                <!-- User Icon/Photo -->
                                @if($convo->other_user->profile_image)
                                    <img src="{{ asset('storage/' . $convo->other_user->profile_image) }}" 
                                         class="rounded-circle" style="width: 45px; height: 45px; object-fit: cover;">
                                @else
                                    <div class="rounded-circle d-flex align-items-center justify-content-center fw-bold {{ $activeConvo && $activeConvo->id == $convo->id ? 'bg-white text-primary' : 'bg-primary text-white' }}" 
                                         style="width: 45px; height: 45px; font-size: 1.2rem;">
                                        {{ strtoupper(substr($convo->other_user->first_name, 0, 1)) }}
                                    </div>
                                @endif

                                <!-- Chat Title Info -->
                                <div class="w-100 overflow-hidden">
                                    <div class="d-flex justify-content-between align-items-baseline">
                                        <h6 class="mb-0 fw-bold">{{ $convo->other_user->first_name }} {{ $convo->other_user->last_name }}</h6>
                                    </div>
                                    <span class="small opacity-75">Click to chat</span>
                                </div>
                            </a>
                        @endif
                    @empty
                        <div class="text-center py-5 text-muted">
                            <p class="mb-0">No active chats found.</p>
                            <small>Conversations will appear once booking requests are accepted.</small>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Right Panel: Message Feed Area -->
            <div class="col-md-8 h-100">
                @if($activeConvo)
                    <div class="chat-window">
                        
                        <!-- Chat Header -->
                        <div class="p-3 bg-white border-bottom d-flex align-items-center gap-3">
                            @if($activeConvo->other_user->profile_image)
                                <img src="{{ asset('storage/' . $activeConvo->other_user->profile_image) }}" 
                                     class="rounded-circle" style="width: 40px; height: 40px; object-fit: cover;">
                            @else
                                <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center fw-bold" 
                                     style="width: 40px; height: 40px; font-size: 1.1rem;">
                                    {{ strtoupper(substr($activeConvo->other_user->first_name, 0, 1)) }}
                                </div>
                            @endif
                            <h5 class="fw-bold mb-0 text-dark">
                                {{ $activeConvo->other_user->first_name }} {{ $activeConvo->other_user->last_name }}
                            </h5>
                        </div>

                        <!-- Messages Thread container -->
                        <div class="chat-messages d-flex flex-column" id="chat-box">
                            @forelse($messages as $msg)
                                <div class="message-bubble {{ $msg->sender_id == $user->id ? 'message-sent' : 'message-received' }}">
                                    <p class="mb-1">{{ $msg->message_text }}</p>
                                    <small class="d-block text-end opacity-75" style="font-size: 0.7rem;">
                                        {{ \Carbon\Carbon::parse($msg->created_at)->format('h:i A') }}
                                    </small>
                                </div>
                            @empty
                                <div class="text-center my-auto text-muted">
                                    <p class="mb-0">No messages in this chat yet.</p>
                                    <small>Send a message below to start the conversation.</small>
                                </div>
                            @endforelse
                        </div>

                        <!-- Message Input Form footer -->
                        <div class="p-3 bg-white border-top">
                            <form action="{{ route('Messages.Send') }}" method="POST" class="m-0">
                                @csrf
                                <input type="hidden" name="conversation_id" value="{{ $activeConvo->id }}">
                                
                                <div class="input-group">
                                    <input type="text" name="message_text" class="form-control" 
                                           placeholder="Type your message..." required autocomplete="off">
                                    <button type="submit" class="btn btn-primary px-4">Send</button>
                                </div>
                            </form>
                        </div>

                    </div>
                @else
                    <!-- Empty State Window (No chat selected) -->
                    <div class="d-flex align-items-center justify-content-center h-100 bg-light text-muted">
                        <div class="text-center">
                            <div class="fs-1 mb-2">💬</div>
                            <h5>Select a chat thread to start messaging</h5>
                            <p class="small mb-0">Conversations are created when booking requests are accepted.</p>
                        </div>
                    </div>
                @endif
            </div>

        </div>
    </div>
</div>

<!-- JavaScript to automatically scroll the chat view to the bottom -->
<!-- Replace your current <script> at the bottom of Messages/Message.blade.php with this: -->
<script>
    // 1. Auto-scroll to the bottom of the chat container
    const chatBox = document.getElementById('chat-box');
    if (chatBox) {
        chatBox.scrollTop = chatBox.scrollHeight;
    }

   // --- REPLACE YOUR OLD SCRIPT BLOCK WITH THIS ---
    @if($activeConvo)
        let currentMessageCount = {{ count($messages) }};
        const conversationId = {{ $activeConvo->id }};
        const currentUserId = {{ $user->id }}; // Gets your logged-in ID

        // Synthesizes a soft chime sound
        function playMessageChime() {
            try {
                const ctx = new (window.AudioContext || window.webkitAudioContext)();
                const osc = ctx.createOscillator();
                const gain = ctx.createGain();
                osc.connect(gain);
                gain.connect(ctx.destination);
                
                osc.type = 'sine';
                osc.frequency.setValueAtTime(523.25, ctx.currentTime); // C5
                osc.frequency.setValueAtTime(783.99, ctx.currentTime + 0.1); // G5
                
                gain.gain.setValueAtTime(0.12, ctx.currentTime);
                gain.gain.exponentialRampToValueAtTime(0.01, ctx.currentTime + 0.4);
                
                osc.start(ctx.currentTime);
                osc.stop(ctx.currentTime + 0.4);
            } catch (e) {
                console.log("Audio blocked.");
            }
        }

        function checkForNewMessages() {
            const url = "{{ route('api.messages.count', ['id' => ':id']) }}".replace(':id', conversationId);

            fetch(url)
                .then(response => response.json())
                .then(data => {
                    // ONLY chime and reload if there is a new message AND it was sent by the OTHER person
                    if (data.count > currentMessageCount && data.last_sender_id != currentUserId) {
                        currentMessageCount = data.count; // Stop double-checking
                        playMessageChime();
                        
                        setTimeout(() => {
                            window.location.reload();
                        }, 100);
                    }
                })
                .catch(err => console.error("Error syncing chat:", err));
        }

        // Poll for new messages every 4 seconds
        setInterval(checkForNewMessages, 4000);
    @endif
</script>

</body>
</html>