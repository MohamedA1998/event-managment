<?php

namespace App\Http\Controllers\Api;

use App\CanLoadRelationships;
use App\Http\Controllers\Controller;
use App\Http\Resources\AttendeeResource;
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
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Event $event)
    {
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
        $attendee = $event->attendees()->create([
            'user_id' => 1
        ]);

        return $this->loadRelationships($attendee)->toResource();
    }

    /**
     * Display the specified resource.
     */
    public function show(Event $event, Attendee $attendee)
    {
        return $this->loadRelationships($attendee)->toResource();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Event $event, Attendee $attendee)
    {
        $attendee->delete();

        return response(status: 204);
    }
}
