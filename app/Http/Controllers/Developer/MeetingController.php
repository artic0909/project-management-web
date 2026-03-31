<?php

namespace App\Http\Controllers\Developer;

use App\Http\Controllers\Controller;
use App\Models\Meeting;
use Illuminate\Http\Request;

class MeetingController extends Controller
{
    public function index()
    {
        $devId = auth()->guard('developer')->id();
        $meetings = Meeting::whereJsonContains('assigndev_ids', (string)$devId)
            ->with(['lead', 'order', 'project', 'createdBy'])
            ->orderBy('meeting_date', 'desc')
            ->orderBy('meeting_time', 'desc')
            ->paginate(15);
            
        return view('developer.meetings.index', compact('meetings'));
    }

    public function show(Meeting $meeting)
    {
        $devId = auth()->guard('developer')->id();
        if (!in_array((string)$devId, $meeting->assigndev_ids ?? [])) {
            abort(403);
        }
        
        $meeting->load(['lead', 'order', 'project', 'createdBy']);
        return view('developer.meetings.show', compact('meeting'));
    }
}
