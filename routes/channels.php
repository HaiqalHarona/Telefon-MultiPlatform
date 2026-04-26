<?php
 
use Illuminate\Support\Facades\Broadcast;
use App\Broadcasting\UserPresence;
 
Broadcast::channel('presence.chat', UserPresence::class);
 
Broadcast::channel('message.{conversationId}', function ($user, $conversationId) {
    return \App\Models\Conversation::where('_id', $conversationId)
        ->where('participant_ids', (string) $user->_id)
        ->exists();
});