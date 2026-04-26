<?php

use Illuminate\Support\Facades\Broadcast;
use App\Broadcasting\UserPresence;

Broadcast::channel('presence.chat', UserPresence::class);