<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreEveentRequest;
use App\Http\Requests\UpdateEventRequest;
use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Event::with('user')->paginate(10)->toResourceCollection();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreEveentRequest $request)
    {
        $event = Event::create([
            ...$request->validated(),
            'user_id' => 1
        ]);

        return $event->toResource();
    }

    /**
     * Display the specified resource.
     */
    public function show(Event $event)
    {
        return $event->load('user', 'attendees')->toResource();
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateEventRequest $request, Event $event)
    {
        $event->update($request->validated());

        return $event->toResource();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Event $event)
    {
        $event->delete();

        return response(status: 204);
    }
}
