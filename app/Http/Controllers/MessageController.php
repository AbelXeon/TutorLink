<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MessageController extends Controller
{
    
    public function Message($conversation_id = null)
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('Auth.Login');
        }

        // 1. Fetch all conversations the logged-in user is a part of (either student or tutor)
        $conversations = DB::table('conversations')
            ->where('student_id', $user->id)
            ->orWhere('tutor_id', $user->id)
            ->orderBy('last_message_at', 'desc')
            ->get();

        // 2. Fetch user names and profile images of the OTHER person in each conversation
        foreach ($conversations as $convo) {
            $otherUserId = ($convo->student_id == $user->id) ? $convo->tutor_id : $convo->student_id;
            $convo->other_user = DB::table('users')->where('id', $otherUserId)->first();
        }

        $activeConvo = null;
        $messages = [];

        // 3. If a specific conversation thread is selected, load its messages
        if ($conversation_id) {
            $activeConvo = DB::table('conversations')->where('id', $conversation_id)->first();

            if ($activeConvo) {
                // Security check: Make sure the logged-in user belongs to this conversation
                if ($activeConvo->student_id == $user->id || $activeConvo->tutor_id == $user->id) {
                    
                    // Load conversation messages
                    $messages = DB::table('messages')
                        ->where('conversation_id', $conversation_id)
                        ->orderBy('created_at', 'asc')
                        ->get();

                    // Mark unread messages received from the other user as read
                    DB::table('messages')
                        ->where('conversation_id', $conversation_id)
                        ->where('sender_id', '!=', $user->id)
                        ->where('is_read', false)
                        ->update(['is_read' => true, 'read_at' => now()]);

                    // Attach the other user's info to the active convo object for the header
                    $otherUserId = ($activeConvo->student_id == $user->id) ? $activeConvo->tutor_id : $activeConvo->student_id;
                    $activeConvo->other_user = DB::table('users')->where('id', $otherUserId)->first();

                } else {
                    abort(403, 'Unauthorized access to this conversation.');
                }
            }
        }

        return view('Messages.Message', compact('user', 'conversations', 'activeConvo', 'messages'));
    }

    // Handles sending a message
    public function SendMessage(Request $request)
    {
        $request->validate([
            'conversation_id' => 'required|exists:conversations,id',
            'message_text'    => 'required|string|max:2000',
        ]);

        $user = Auth::user();

        // 1. Insert message into database
        DB::table('messages')->insert([
            'conversation_id' => $request->conversation_id,
            'sender_id'       => $user->id,
            'message_text'    => $request->message_text,
            'is_read'         => false,
            'created_at'      => now(),
            'updated_at'      => now(),
        ]);

        // 2. Update conversation's last active timestamp
        DB::table('conversations')
            ->where('id', $request->conversation_id)
            ->update(['last_message_at' => now()]);

        // 3. Redirect back to the conversation thread
        return redirect()->route('Messages.Message', $request->conversation_id);
    }


    // --- REPLACE YOUR OLD getMessageCount METHOD WITH THIS ---
    public function getMessageCount($id)
    {
        $count = DB::table('messages')
            ->where('conversation_id', $id)
            ->count();

        // Find the sender of the latest message in this thread
        $lastMessage = DB::table('messages')
            ->where('conversation_id', $id)
            ->orderBy('created_at', 'desc')
            ->first();

        return response()->json([
            'count'          => $count,
            'last_sender_id' => $lastMessage ? $lastMessage->sender_id : null
        ]);
    }

}
