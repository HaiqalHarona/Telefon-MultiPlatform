<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;


class Conversation extends Model
{

    protected $connection = 'mongodb';
    protected $collection = 'conversations';

    protected $fillable = [
        'type',              // direct | group
        'name',              // group name (nullable for direct)
        'avatar',            // group avatar (nullable)
        'participant_ids',   // array of User ObjectIds
        'last_message_id',
        'last_activity_at',
        'created_by',        // User ObjectId
        'metadata',          // arbitrary key-value store
    ];

    protected $casts = [
        'participant_ids'  => 'array',
        'last_activity_at' => 'datetime',
        'metadata'         => 'array',
    ];

    // ── Relationships ──────────────────────────────────────────

    /**
     * All messages in this conversation.
     */
    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    /**
     * The most recent message (denormalized reference).
     */
    public function lastMessage()
    {
        return $this->belongsTo(Message::class, 'last_message_id');
    }

    /**
     * Get participant User models from the embedded IDs array.
     * MongoDB doesn't have pivot tables — participant_ids is a plain array
     * of User ObjectIds stored directly on the conversation document.
     */
    public function participants()
    {
        return User::whereIn('_id', $this->participant_ids ?? [])->get();
    }

    /**
     * The user who created this conversation.
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // ── Helpers ────────────────────────────────────────────────

    /**
     * Add a user to this conversation.
     */
    public function addParticipant(string $userId): void
    {
        if (! in_array($userId, $this->participant_ids ?? [])) {
            $this->push('participant_ids', $userId);
        }
    }

    /**
     * Remove a user from this conversation.
     */
    public function removeParticipant(string $userId): void
    {
        $this->pull('participant_ids', $userId);
    }

    /**
     * Check if a user is in this conversation.
     */
    public function hasParticipant(string $userId): bool
    {
        return in_array($userId, $this->participant_ids ?? []);
    }

    // ── Scopes ─────────────────────────────────────────────────

    public function scopeDirect($query)
    {
        return $query->where('type', 'direct');
    }

    public function scopeGroup($query)
    {
        return $query->where('type', 'group');
    }

    /**
     * Conversations where the given user is a participant.
     * Works because MongoDB's `where` on an array field matches
     * if the array *contains* the given value.
     */
    public function scopeForUser($query, string $userId)
    {
        return $query->where('participant_ids', $userId);
    }
}