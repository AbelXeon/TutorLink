<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Models\Conversation;
use App\Models\Message;
use App\Models\Notification;
use App\Models\User;
use App\Events\MessageSent;

class MessageController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $conversations = Conversation::where('student_id', $user->id)
            ->orWhere('tutor_id', $user->id)
            ->with(['student', 'tutor', 'messages' => function($q) {
                $q->orderBy('created_at', 'desc')->limit(1);
            }])
            ->orderBy('last_message_at', 'desc')
            ->get();

        return view('Messages.Message', compact('conversations'));
    }

    public function show($username)
    {
        $user = Auth::user();
        $chatPartner = User::where('username', $username)->firstOrFail();

        $activeConversation = Conversation::where(function($q) use ($user, $chatPartner) {
                $q->where('student_id', $user->id)->where('tutor_id', $chatPartner->id);
            })
            ->orWhere(function($q) use ($user, $chatPartner) {
                $q->where('student_id', $chatPartner->id)->where('tutor_id', $user->id);
            })
            ->with(['student', 'tutor', 'messages.sender'])
            ->firstOrFail();

        Message::where('conversation_id', $activeConversation->id)
            ->where('sender_id', '!=', $user->id)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        $conversations = Conversation::where('student_id', $user->id)
            ->orWhere('tutor_id', $user->id)
            ->with(['student', 'tutor', 'messages' => function($q) {
                $q->orderBy('created_at', 'desc')->limit(1);
            }])
            ->orderBy('last_message_at', 'desc')
            ->get();

        return view('Messages.Message', compact('conversations', 'activeConversation'));
    }

    public function sendMessage(Request $request, $id)
    {
        $user = Auth::user();
        $conversation = Conversation::findOrFail($id);

        $request->validate([
            'message_text' => 'nullable|string|max:1000',
            'attachment'   => 'nullable|file|mimes:jpeg,png,jpg,pdf,doc,docx|max:2048', 
            'latitude'     => 'nullable|numeric',
            'longitude'    => 'nullable|numeric',
        ]);

        $filePath = null;
        $fileType = null;
        $messageText = $request->message_text;

        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $extension = strtolower($file->getClientOriginalExtension());
            
            if (in_array($extension, ['jpg', 'jpeg', 'png'])) {
                $fileType = 'image';
                $filePath = $this->resizeAndSaveImage($file, 'attachments');
            } else {
                $fileType = 'document';
                $filePath = $file->store('attachments', 'public');
            }
        }

        if ($request->filled('latitude') && $request->filled('longitude')) {
            $fileType = 'location';
            $messageText = "{$request->latitude},{$request->longitude}";
        }

        if (!$messageText && !$filePath) {
            return back()->withErrors(['message_text' => 'Cannot send an empty message.']);
        }

        $message = Message::create([
            'conversation_id' => $conversation->id,
            'sender_id'       => $user->id,
            'message_text'    => $messageText,
            'file_path'       => $filePath,
            'file_type'       => $fileType,
        ]);

        $conversation->update(['last_message_at' => now()]);

        $recipientId = ($conversation->student_id === $user->id) ? $conversation->tutor_id : $conversation->student_id;
        Notification::create([
            'user_id'           => $recipientId,
            'notification_type' => 'message',
            'title'             => 'New Message from ' . $user->first_name,
            'message'           => $fileType ? "Sent you a {$fileType}." : Str::limit($messageText, 50),
            'action_url'        => route('messages.show', $user->username),
        ]);

        $messageData = $message->load('sender');

        // Optional WebSocket broadcast if MessageSent event exists
        if (class_exists('App\Events\MessageSent')) {
            broadcast(new MessageSent($messageData))->toOthers();
        }

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => $messageData
            ]);
        }

        return redirect()->route('messages.show', $user->username);
    }

    public function getNewMessages($id, Request $request)
    {
        $request->validate([
            'last_message_id' => 'required|integer'
        ]);

        $user = Auth::user();

        $conversation = Conversation::where('id', $id)
            ->where(function($q) use ($user) {
                $q->where('student_id', $user->id)->orWhere('tutor_id', $user->id);
            })
            ->firstOrFail();

        $newMessages = Message::where('conversation_id', $conversation->id)
            ->where('id', '>', $request->last_message_id)
            ->with('sender')
            ->orderBy('created_at', 'asc')
            ->get();

        Message::where('conversation_id', $conversation->id)
            ->where('sender_id', '!=', $user->id)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        return response()->json([
            'messages' => $newMessages,
            'current_user_id' => $user->id
        ]);
    }

    private function resizeAndSaveImage($file, $destinationPath)
    {
        list($width, $height, $type) = getimagesize($file);
        
        $maxDimension = 600;
        $ratio = $width / $height;
        
        if ($ratio > 1) {
            $newWidth = $maxDimension;
            $newHeight = $maxDimension / $ratio;
        } else {
            $newWidth = $maxDimension * $ratio;
            $newHeight = $maxDimension;
        }

        switch ($type) {
            case IMAGETYPE_JPEG:
                $src = imagecreatefromjpeg($file);
                break;
            case IMAGETYPE_PNG:
                $src = imagecreatefrompng($file);
                break;
            default:
                return $file->store($destinationPath, 'public');
        }

        $dst = imagecreatetruecolor($newWidth, $newHeight);

        if ($type == IMAGETYPE_PNG) {
            imagealphablending($dst, false);
            imagesavealpha($dst, true);
        }

        imagecopyresampled($dst, $src, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);

        $filename = md5(uniqid()) . '.jpg';
        $fullPath = storage_path('app/public/' . $destinationPath . '/' . $filename);

        if (!file_exists(dirname($fullPath))) {
            mkdir(dirname($fullPath), 0755, true);
        }

        imagejpeg($dst, $fullPath, 80);

        imagedestroy($src);
        imagedestroy($dst);

        return $destinationPath . '/' . $filename;
    }
}