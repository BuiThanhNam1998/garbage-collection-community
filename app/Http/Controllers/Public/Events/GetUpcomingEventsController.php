<?php

namespace App\Http\Controllers\Public\Events;

use App\Http\Controllers\Controller;
use App\Repositories\EventRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;

class GetUpcomingEventsController extends Controller
{
    protected $eventRepository;

    public function __construct(EventRepository $eventRepository)
    {
        $this->eventRepository = $eventRepository;
    }

    public function index(Request $request)
    {
        try {
            $startDate = Carbon::now();
            $endDate = Carbon::now()->addMonth();

            $events = $this->eventRepository->queryBetweenDate($startDate, $endDate)
                ->get();

            return response()->json([
                'events' => $events,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to fetch event list',
            ], 500);
        }
    }
}
