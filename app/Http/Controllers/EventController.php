<?php

namespace App\Http\Controllers;
use App\Models\Event;
use App\Models\MeetupGroup;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use App\Http\Requests\StoreEventRequest;
use App\Http\Requests\UpdateEventRequest;
use Illuminate\Support\Str;
use Illuminate\Http\Redirectresponse;
use Illuminate\Support\Facades\Gate;


class EventController extends Controller
{
    public function index()
    {
        $events = Event::query()
        ->with(['meetupGroup.user'])
        ->where('starts_at', '>=', now())
        ->orderBy('starts_at')
        ->get();

        $user = auth()->user();

        return Inertia::render('events/index', [
            'events' => $events,
            'canCreateEvent' => $user ? $user->meetupGroups()->exists()
            : false,
        ]);
    }

    public function show(Event $event): Response 
    {
        $event->load('meetupGroup.user');

        $user = auth()->user();

        return Inertia::render('events/show', [
            'event' => $event,
            //check if we have user sigin and can they update event
            'canEdit' => $user?->can('update', $event) ?? false,
            'isRsvped' => $user ? $event->users()->where('user_id', $user->id)->exists(): false,
            'attendeeCount' => $event->users()->count(),
        ]);
    }

    public function create(): Response
    {
        $meetupGroups = auth()->user()->meetupGroups()->orderBy('name')->get
        (['id', 'name']);
        return Inertia::render('events/create', [
            'meetupGroups' => $meetupGroups
        ]);
    }

    public function store(StoreEventRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $validated['slug'] = Str::slug($validated['title']);

        $event = Event::create($validated);

        return redirect()->route('events.show', $event);
    }

    public function edit(Event $event): Response
    {   

        Gate::authorize('update', $event);

        return Inertia::render('events/edit', [
            'event' => $event,
        ]);
    }

    public function update(UpdateEventRequest $request, Event $event): RedirectResponse 
    {

        Gate::authorize('update', $event);

        $event->update($request->validated());

        return redirect()->route('events.show', $event);
    }
 }
