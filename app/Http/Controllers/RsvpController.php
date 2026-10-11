<?php

namespace App\Http\Controllers;
use App\Models\Event;
use App\jobs\SendRsvpEmail;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;



class RsvpController extends Controller
{
    public function store(Event $event): RedirectResponse
    {
        $event->users()->syncWithoutDetaching([auth()->id()]);
        SendRsvpEmail::dispatch(auth()->user(), $event);

        return back();
    }

    public function destroy(Event $event): RedirectResponse
    {
        $event->users()->detach(auth()->id());

        return back();
    }
}
