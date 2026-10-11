<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Event;

class EventPolicy
{
    /**
     * Create a new policy instance.
     */
   public function update(User $user, Event $event): bool
   {
    // checking the person who try to updated is the owner of meet up group
        return $user->id === $event->meetupGroup->user_id;
   }
}
