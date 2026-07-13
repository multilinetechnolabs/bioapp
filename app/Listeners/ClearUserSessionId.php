<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Logout;

class ClearUserSessionId
{
    public function handle(Logout $event)
    {
        if ($event->user) {
            $event->user->forceFill(['current_session_id' => null])->save();
        }
    }
}
