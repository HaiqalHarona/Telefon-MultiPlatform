<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;


class Message extends Model
{

    protected $connection = 'mongodb';
    protected $collection = 'messages';

    protected $fillable = [
        'conversation_id',
        'sender_id',
        'type',           // text | image | file | audio | video | system
        'body',           // text content
        'read_by',        // array of { user_id, read_at }
        'reactions',      // array of { user_id, emoji }
        'reply_to_id',    // parent Message ObjectId (thread)
        'is_edited',
        'edited_at',
        'metadata',
    ];

    protected $casts = [
        'read_by'     => 'array',
        'reactions'   => 'array',
        'is_edited'   => 'boolean',
        'edited_at'   => 'datetime',
        'metadata'    => 'array',
    ];

    protected $with = ['sender'];

    // ── Relationships ──────────────────────────────────────────

    /**
     * The conversation this message belongs to.
     */
    public function conversation()
    {
        return $this->belongsTo(Conversation::class);
    }

    /**
     * The user who sent this message.
     */
    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    /**
     * The message this is a reply to (thread parent).
     */
    public function replyTo()
    {
        return $this->belongsTo(self::class, 'reply_to_id');
    }

    /**
     * All replies to this message (thread children).
     */
    public function replies()
    {
        return $this->hasMany(self::class, 'reply_to_id');
    }

    /**
     * Attachments embedded within this message.
     */
    public function attachments()
    {
        return $this->embedsMany(Attachment::class);
    }

    // ── Helpers ────────────────────────────────────────────────

    /**
     * Mark this message as read by a user.
     */
    public function markReadBy(string $userId): void
    {
        $alreadyRead = collect($this->read_by)
            ->pluck('user_id')
            ->contains($userId);

        if (! $alreadyRead) {
            $this->push('read_by', [
                'user_id' => $userId,
                'read_at' => now()->toISOString(),
            ]);
        }
    }

    /**
     * Add or replace a reaction from a user.
     */
    public function addReaction(string $userId, string $emoji): void
    {
        // Remove existing reaction from same user first
        $this->pull('reactions', ['user_id' => $userId]);

        $this->push('reactions', [
            'user_id' => $userId,
            'emoji'   => $emoji,
        ]);
    }

    /**
     * Remove a reaction from a user.
     */
    public function removeReaction(string $userId): void
    {
        $this->pull('reactions', ['user_id' => $userId]);
    }

    /**
     * Check if this message has been read by a user.
     */
    public function isReadBy(string $userId): bool
    {
        return collect($this->read_by)
            ->pluck('user_id')
            ->contains($userId);
    }

    /**
     * Load messages in the conversation
     */
    public static function getMessages(string $conversationId, int $loadLimit = 20)
    {
        return static::where('conversation_id', $conversationId)
        ->with('attachments')
        ->latest()
        ->paginate($loadLimit);
    }
}
