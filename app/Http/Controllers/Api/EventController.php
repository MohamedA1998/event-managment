<?php

namespace App\Http\Controllers\Api;

use App\CanLoadRelationships;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreEveentRequest;
use App\Http\Requests\UpdateEventRequest;
use App\Models\Event;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class EventController extends Controller implements HasMiddleware
{
    use CanLoadRelationships;

    private array $relations = ['user', 'attendees', 'attendees.user'];

    public static function middleware(): array
    {
        return [
            new Middleware('auth:api', except: ['index', 'show']),
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $query = $this->loadRelationships(Event::query());

        return $query->latest()->paginate(10)->toResourceCollection();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreEveentRequest $request)
    {
        $event = Event::create([
            ...$request->validated(),
            'user_id' => auth()->guard('api')->id(),
        ]);

        return $this->loadRelationships($event)->toResource();
    }

    /**
     * Display the specified resource.
     */
    public function show(Event $event)
    {
        return $this->loadRelationships($event)->toResource();
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateEventRequest $request, Event $event)
    {
        $event->update($request->validated());

        return $this->loadRelationships($event)->toResource();
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
