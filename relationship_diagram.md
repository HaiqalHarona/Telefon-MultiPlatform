# SanCo - Full Database Relationship Diagram

## Entity Relationship Diagram

```mermaid
erDiagram
    USER {
        ObjectId _id PK
        string name
        string email
        string avatar
        string status "online | offline | away"
        datetime last_seen_at
        datetime created_at
        string user_tag "Unique public tag"
    }

    CONVERSATION {
        ObjectId _id PK
        string type "direct | group"
        string name "nullable for direct"
        string avatar "nullable"
        array participant_ids "Array of User ObjectIds"
        ObjectId last_message_id FK
        datetime last_activity_at "Used for sorting chat list"
        ObjectId created_by FK
        object metadata
    }

    MESSAGE {
        ObjectId _id PK
        ObjectId conversation_id FK
        ObjectId sender_id FK
        string type "text | image | file | audio | video | system"
        string body "Message content"
        array read_by "Array of {user_id, read_at}"
        array reactions "Array of {user_id, emoji}"
        ObjectId reply_to_id FK "nullable, parent message"
        boolean is_edited
        datetime edited_at
        object metadata
    }

    ATTACHMENT {
        string file_name
        int file_size "bytes"
        string mime_type
        string url
        string thumbnail_url
        int duration "seconds"
        int width "px"
        int height "px"
    }

    FRIENDSHIP {
        ObjectId _id PK
        ObjectId user_id FK "Owner of this record"
        ObjectId friend_id FK "The other user"
        string status "pending | accepted | blocked"
        ObjectId action_user_id FK "Initiator of the state"
        datetime accepted_at
        datetime blocked_at
        object metadata
    }

    USER ||--o{ CONVERSATION : "created_by"
    USER }o--o{ CONVERSATION : "participant_ids[]"
    USER ||--o{ MESSAGE : "sender_id"
    CONVERSATION ||--o{ MESSAGE : "conversation_id"
    CONVERSATION ||--o| MESSAGE : "last_message_id"
    MESSAGE ||--o| MESSAGE : "reply_to_id (thread)"
    MESSAGE ||--o{ ATTACHMENT : "embedsMany (subdocument)"
    USER ||--o{ FRIENDSHIP : "user_id"
    USER ||--o{ FRIENDSHIP : "friend_id"
```

---

## Model Relationships & Functions

### User Model (`app/Models/User.php`)

| Method                     | Description                                                                      |
| :------------------------- | :------------------------------------------------------------------------------- |
| **Messaging Relationships** |                                                                                 |
| `conversations()`          | Gets all conversations where the user's ID is in `participant_ids`.              |
| `messages()`               | Gets all messages sent by this user (1:N).                                       |
| **Friendship Relationships** |                                                                                |
| `friendships()`            | Returns all friendship records owned by the user.                                |
| `friends()`                | Returns only **accepted** friendship records.                                    |
| `sentFriendRequests()`     | Returns friendship records initiated by the user that are still `pending`.       |
| `receivedFriendRequests()` | Returns friendship records where the user is the target and status is `pending`. |
| `blockedUsers()`           | Returns friendship records where the user has blocked others.                    |
| **Friendship Helpers**     |                                                                                  |
| `isFriendWith($id)`        | Checks if a mutual accepted friendship exists with `$id`.                        |
| `hasBlocked($id)`          | Checks if the current user has blocked the user with `$id`.                      |
| `isBlockedBy($id)`         | Checks if the user with `$id` has blocked the current user.                      |

### Conversation Model (`app/Models/Conversation.php`)

| Method                                  | Description                                                                                              |
| :-------------------------------------- | :------------------------------------------------------------------------------------------------------- |
| **Relationships**                       |                                                                                                          |
| `messages()`                            | Relationship to all messages in the conversation.                                                        |
| `lastMessage()`                         | Relationship to the singular most recent message.                                                        |
| `participantsUsers()`                        | Fetches actual `User` models for every ID in `participant_ids`.                                          |
| `creator()`                             | Relationship to the user who created the group/chat.                                                     |
| **Helpers**                             |                                                                                                          |
| `addParticipant($id)`                   | Pushes a new User ID into the `participant_ids` array.                                                   |
| `removeParticipant($id)`                | Pulls a User ID from the `participant_ids` array.                                                        |
| `hasParticipant($userId)`               | Checks if a user is in this conversation.                                                                |
| `getDisplayInfo($preloadedUsers=null)`  | Logic that determines the chat name/avatar (Self, Direct, or Group).                                     |
| **Static Helpers**                      |                                                                                                          |
| `findOrCreateDirect($a, $b)`            | Orchestrates the creation or retrieval of a 1-on-1 chat room.                                            |
| `getInboxFor($user)`                    | Returns a list of conversations for the user, including the 'Display Info' (Name/Avatar) pre-calculated. |
| **Scopes**                              |                                                                                                          |
| `scopeDirect($query)`                   | Scope for direct conversations.                                                                          |
| `scopeGroup($query)`                    | Scope for group conversations.                                                                           |
| `scopeForUser($query, $userId)`         | Scope for conversations where the given user is a participant.                                           |

### Message Model (`app/Models/Message.php`)

| Method                                        | Description                                                                                            |
| :-------------------------------------------- | :----------------------------------------------------------------------------------------------------- |
| **Relationships**                             |                                                                                                        |
| `conversation()`                              | Parent relationship to the Conversation.                                                               |
| `sender()`                                    | Relationship to the User who sent the message.                                                         |
| `replyTo()`                                   | Relationship to the parent message if this is a reply.                                                 |
| `replies()`                                   | Relationship to all messages replying to this one.                                                     |
| `attachments()`                               | Accessor for sub-document attachments (images/files).                                                  |
| **Helpers**                                   |                                                                                                        |
| `markReadBy($userId)`                         | Adds the user to the `read_by` array with a timestamp.                                                 |
| `isReadBy($userId)`                           | Checks if a specific user has viewed this message.                                                     |
| `addReaction($userId, $emoji)`                | Adds/Updates an emoji reaction in the `reactions` array.                                               |
| `removeReaction($userId)`                     | Removes a reaction from the specified user.                                                            |
| **Static Helpers**                            |                                                                                                        |
| `getMessages($conversationId, $loadLimit=20)` | Loads `messages` in the conversation. With a message load limit of 20 with paginate built-in function. |

### Attachment Model (`app/Models/Attachment.php`)

| Method              | Description                                                                   |
| :------------------ | :---------------------------------------------------------------------------- |
| **Helpers**         |                                                                               |
| `isImage()`         | Check if this attachment is an image (checks if `mime_type` starts with `image/`). |
| `isVideo()`         | Check if this attachment is a video (checks if `mime_type` starts with `video/`). |
| `isAudio()`         | Check if this attachment is audio (checks if `mime_type` starts with `audio/`). |
| `humanFileSize()`   | Get human-readable file size (converts bytes to B/KB/MB/GB).                  |

### Friendship Model (`app/Models/Friendship.php`)

| Method                                   | Description                                                                 |
| :--------------------------------------- | :-------------------------------------------------------------------------- |
| **Relationships**                        |                                                                             |
| `user()`                                 | Relationship to the user who owns this friendship record.                   |
| `friend()`                               | Relationship to the other user in the friendship.                           |
| **Scopes**                               |                                                                             |
| `scopePending($query)`                   | Scope for pending friendship records.                                       |
| `scopeAccepted($query)`                  | Scope for accepted friendship records.                                      |
| `scopeBlocked($query)`                   | Scope for blocked friendship records.                                       |
| `scopeForUser($query, $userId)`          | Scope for records belonging to a specific user.                             |
| `scopeIncomingFor($query, $userId)`      | Scope for incoming friend requests where user is the target.                |
| **Static Helpers**                       |                                                                             |
| `sendRequest($senderId, $receiverId)`    | Creates a new `pending` record. Prevents duplicates and checks for blocks.  |
| `acceptRequest($accepterId, $senderId)`  | Marks original as `accepted` and creates a reciprocal record.               |
| `rejectRequest($rejecterId, $senderId)`  | Deletes a pending friend request document.                                  |
| `removeFriend($userId, $friendId)`       | Deletes **both** reciprocal records (Unfriend).                             |
| `blockUser($blockerId, $blockedId)`      | Removes existing friendships and creates a singular `blocked` record.       |
| `unblockUser($blockerId, $blockedId)`    | Removes a block record.                                                     |
| `areFriends($userA, $userB)`             | Verifies if an accepted record exists between two users.                    |
| `hasBlocked($blockerId, $blockedId)`     | Checks if userA has blocked userB.                                          |
| `getPendingRequests($userId)`            | Gets all incoming pending friend requests with eager-loaded sender.         |
| `getSentRequests($userId)`               | Gets all pending requests the user has sent with eager-loaded recipient.    |

---

## Database Architecture Overview

### MongoDB Specifics

- **Eloquent-Compatible**: We use the `mongodb/laravel-mongodb` package, allowing us to use standard Laravel relationships (`hasMany`, `belongsTo`) while benefiting from MongoDB's flexible schema.
- **Embedded Arrays**: Instead of complex pivot tables for "Participant Lists" or "Read Receipts", we use arrays (`participant_ids`, `read_by`). This is significantly faster in NoSQL.
- **Atomic Operations**: We use `$push` and `$pull` for adding/removing items from arrays to ensure data integrity without refreshing the entire document.

### The Symmetric Friendship System

To ensure both Alice and Bob can see each other in their "Friends" list with high performance, we use a **reciprocal document** pattern:

**Status Flow:**
- `pending` → `accepted` (both sides created)
- `pending` → `rejected` (document removed via `rejectRequest()`)
- `accepted` → `removed` (both documents deleted via `removeFriend()`)
- `*` → `blocked` (blocker keeps one doc via `blockUser()`, other side's doc removed)
- `blocked` → `unblocked` (block record removed via `unblockUser()`)

**Reciprocal Pattern Example:**
1. Alice accepts Bob's request.
2. Doc 1: `user_id: Alice, friend_id: Bob, status: accepted`
3. Doc 2: `user_id: Bob, friend_id: Alice, status: accepted`
   This allows us to query `Friendship::where('user_id', auth()->id())->where('status', 'accepted')` and get a simple list of friends instantly.

**Key Design Principles:**
- **Directional Records**: Each document represents a directional relationship between two users.
- **Mutual Friendships**: A mutual friendship is represented by TWO documents (one per side).
- **Single Document States**: Pending/blocked states only need ONE document from the initiator.
- **Atomic Operations**: Uses MongoDB's atomic operations for data integrity.