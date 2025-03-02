<?php

namespace App\Http\Controllers\Api;


use App\Http\Resources\AttendeeResource;
use App\Http\Traits\CanLoadRelationship;
use App\Models\Attendee;
use App\Models\Event;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Gate;

class AttendeeController extends Controller
{
    use CanLoadRelationship, AuthorizesRequests;

    private array $relations = ['user'];
    public function __construct()
    {
        $this->middleware('auth:sanctum')->except(['index', 'show', 'update']);
        $this->authorizeResource(Attendee::class, 'attendee');
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Event $event)
    {
        $attendees = $this->loadRelationship(
            $event->attendees()->latest()
        );

        return AttendeeResource::collection(
            $attendees->paginate(10)
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Event $event)
    {
        $attendee = $this->loadRelationship($event->attendees()->create([
            'user_id' => $request->user()->id,
        ])
    );

        return new AttendeeResource($attendee);
    }

    /**
     * Display the specified resource.
     */
    public function show(Event $event, Attendee $attendee)
    {
        return new AttendeeResource($this->loadRelationship($attendee));
    }



    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Event $event, Attendee $attendee)
    {
        // if(Gate::denies('delete-attendee', [$event, $attendee])){
        //     abort(403,'You are not allowed to delete this attendee');
        // }

        $attendee->delete();
        return response(status: 204);
    }
}
