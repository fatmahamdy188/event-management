<?php

namespace App\Http\Controllers\Api;
use App\Http\Resources\AttendeeResourse;
use App\Http\Traits\CanLoadRelationships;
use App\Models\Event;
use App\Models\Attendee;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class AttendeeController extends Controller
{
    use CanLoadRelationships;
    /**
     * Display a listing of the resource.
     */
    private array $relations = ['user'];
    public function index(Event $event)
    {
        Gate::authorize('viewAny', Attendee::class);
        $attendees = $this->loadRelationships(
            $event->attendees()->latest()
        );

        return AttendeeResourse::collection(
            $attendees->paginate());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Event $event)
    {
        Gate::authorize('create', Attendee::class);
        $attendee = $event->attendees()->create([
            'user_id' => $request->user()->id,
        ]);
        return new AttendeeResourse($this->loadRelationships($attendee));
    }

    /**
     * Display the specified resource.
     */
    public function show(Event $event, Attendee $attendee)
    {
        Gate::authorize('view', $attendee);
        return new AttendeeResourse($this->loadRelationships($attendee));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Event $event, Attendee $attendee)
    {
        Gate::authorize('delete', $attendee);
        $attendee->delete();
        return response(status:204);
    }
}
