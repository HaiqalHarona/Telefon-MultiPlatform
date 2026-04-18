# Telefon — MongoDB Database Schema

## Entity Relationship Diagram

```mermaid
erDiagram
    USERS {
        ObjectId _id PK
        string name
        string email
        string password
        string avatar
        string status "online | offline | away"
        datetime last_seen_at
        datetime email_verified_at
        datetime created_at
        datetime updated_at
    }

    CONVERSATIONS {
        ObjectId _id PK
        string type "direct | group"
        string name "nullable, group only"
        string avatar "nullable, group only"
        array participant_ids FK "array of User ObjectIds"
        ObjectId last_message_id FK
        datetime last_activity_at
        ObjectId created_by FK
        object metadata
        datetime created_at
        datetime updated_at
    }

    MESSAGES {
        ObjectId _id PK
        ObjectId conversation_id FK
        ObjectId sender_id FK
        string type "text | image | file | audio | video | system"
        string body
        array read_by "embedded: user_id, read_at"
        array reactions "embedded: user_id, emoji"
        ObjectId reply_to_id FK "nullable, thread parent"
        boolean is_edited
        datetime edited_at
        object metadata
        datetime created_at
        datetime updated_at
    }

    ATTACHMENTS {
        string file_name
        int file_size "bytes"
        string mime_type
        string url
        string thumbnail_url "nullable"
        int duration "nullable, seconds"
        int width "nullable, px"
        int height "nullable, px"
    }

    USERS ||--o{ MESSAGES : "sends (sender_id)"
    USERS ||--o{ CONVERSATIONS : "creates (created_by)"
    USERS }o--o{ CONVERSATIONS : "participates (participant_ids[])"
    CONVERSATIONS ||--o{ MESSAGES : "contains (conversation_id)"
    CONVERSATIONS |o--|| MESSAGES : "last message (last_message_id)"
    MESSAGES |o--o{ MESSAGES : "thread (reply_to_id)"
    MESSAGES ||--o{ ATTACHMENTS : "embeds (subdocument)"
```

## Collections Overview

### `users`
Standard auth collection. Each user can participate in many conversations and send many messages.

### `conversations`
Represents either a **direct** (1-to-1) or **group** chat. Participants are stored as an embedded array of User ObjectIds — no pivot collection needed.

### `messages`
Each message belongs to one conversation and one sender. Supports:
- **Threading** — `reply_to_id` points to a parent message
- **Read receipts** — `read_by[]` array of `{ user_id, read_at }`
- **Reactions** — `reactions[]` array of `{ user_id, emoji }`

### `attachments` *(embedded, not a collection)*
Stored **inside** each message document via `embedsMany`. Not a standalone collection — fetched automatically with the parent message.

---

## Data Flow

```mermaid
sequenceDiagram
    participant U as User
    participant C as Conversation
    participant M as Message
    participant A as Attachment

    U->>C: Create conversation (direct/group)
    Note over C: participant_ids[] = [user1, user2, ...]

    U->>M: Send message
    Note over M: conversation_id, sender_id, body

    M->>A: Attach file (embedded)
    Note over A: Stored inside message document

    M-->>C: Update last_message_id & last_activity_at

    U->>M: Read message
    Note over M: push to read_by[] { user_id, read_at }

    U->>M: React to message
    Note over M: push to reactions[] { user_id, emoji }

    U->>M: Reply to message
    Note over M: reply_to_id = parent message _id
```

## Example Documents

### Conversation
```json
{
  "_id": "662a1b...",
  "type": "group",
  "name": "Project Team",
  "avatar": null,
  "participant_ids": ["661f0a...", "661f0b...", "661f0c..."],
  "last_message_id": "662b3c...",
  "last_activity_at": "2026-04-18T15:00:00Z",
  "created_by": "661f0a...",
  "metadata": {}
}
```

### Message (with embedded attachment)
```json
{
  "_id": "662b3c...",
  "conversation_id": "662a1b...",
  "sender_id": "661f0a...",
  "type": "image",
  "body": "Check this out!",
  "attachments": [
    {
      "file_name": "photo.jpg",
      "file_size": 245000,
      "mime_type": "image/jpeg",
      "url": "/storage/attachments/photo.jpg",
      "thumbnail_url": "/storage/attachments/photo_thumb.jpg",
      "width": 1920,
      "height": 1080
    }
  ],
  "read_by": [
    { "user_id": "661f0b...", "read_at": "2026-04-18T15:01:00Z" }
  ],
  "reactions": [
    { "user_id": "661f0b...", "emoji": "👍" }
  ],
  "reply_to_id": null,
  "is_edited": false,
  "edited_at": null,
  "metadata": {}
}
```
