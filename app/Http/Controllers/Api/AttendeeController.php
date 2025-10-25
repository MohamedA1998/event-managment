<?php

namespace App\Http\Controllers\Api;

use App\CanLoadRelationships;
use App\Http\Controllers\Controller;
use App\Models\Attendee;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class AttendeeController extends Controller implements HasMiddleware
{
    use CanLoadRelationships;

    private array $relations = ['user'];

    public static function middleware(): array
    {
        return [
            new Middleware('auth:api', ['store', 'destroy']),
            new Middleware('throttle:api', ['store', 'destroy'])
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Event $event)
    {
        $this->authorize('viewAny', Attendee::class);

        $attendees = $this->loadRelationships(
            $event->attendees()->latest()
        );

        return $attendees->paginate(10)->toResourceCollection();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Event $event)
    {
        $this->authorize('create', Attendee::class);
        
        $attendee = $event->attendees()->create([
            'user_id' => auth()->guard('api')->id(),
        ]);

        return $this->loadRelationships($attendee)->toResource();
    }

    /**
     * Display the specified resource.
     */
    public function show(Event $event, Attendee $attendee)
    {
        $this->authorize('view', $attendee);

        return $this->loadRelationships($attendee)->toResource();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Event $event, Attendee $attendee)
    {
        $this->authorize('delete', $attendee);

        $attendee->delete();

        return response(status: 204);
    }
}
