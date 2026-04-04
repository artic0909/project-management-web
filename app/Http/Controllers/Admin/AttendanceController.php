<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Models\AttendanceSetting;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        $guard = $this->getGuard();
        $user = auth()->guard($guard)->user();
        $routePrefix = $guard;

        $query = Attendance::with('user');

        if ($guard !== 'admin') {
            $query->where('user_id', $user->id)
                  ->where('user_type', ucfirst($guard));
        } else {
            // Admin can filter by type or user
            if ($request->filled('user_type')) {
                $query->where('user_type', $request->user_type);
            }
        }

        if ($request->filled('date')) {
            $query->where('date', $request->date);
        } else {
            // Default to today or current month? User said "show table of all attendances"
            // For now, let's paginate all
        }

        $attendances = $query->latest('date')->latest('check_in_time')->paginate(20)->withQueryString();
        
        $settings = AttendanceSetting::first() ?? AttendanceSetting::create([
            'dev_checkin_time' => '10:00:00',
            'dev_checkout_time' => '19:00:00',
            'sale_checkin_time' => '10:00:00',
            'sale_checkout_time' => '19:00:00',
        ]);

        $todayAttendance = null;
        if($guard !== 'admin'){
            $todayAttendance = Attendance::where('user_id', $user->id)
                ->where('user_type', ucfirst($guard))
                ->where('date', now()->toDateString())
                ->first();
        }

        return view('admin.attendance.index', compact('attendances', 'settings', 'routePrefix', 'todayAttendance'));
    }

    public function storeSettings(Request $request)
    {
        $request->validate([
            'dev_checkin_time' => 'required',
            'dev_checkout_time' => 'required',
            'sale_checkin_time' => 'required',
            'sale_checkout_time' => 'required',
        ]);

        $settings = AttendanceSetting::first() ?? new AttendanceSetting();
        $settings->fill($request->all());
        $settings->save();

        return back()->with('success', 'Attendance settings updated successfully.');
    }

    public function giveAttendance(Request $request)
    {
        $guard = $this->getGuard();
        $user = auth()->guard($guard)->user();
        $date = now()->toDateString();
        $time = now()->toTimeString();

        $attendance = Attendance::where('user_id', $user->id)
            ->where('user_type', get_class($user))
            ->where('date', $date)
            ->first();

        $screenshot = $request->input('screenshot');
        $path = null;

        if ($screenshot) {
            $imageData = str_replace('data:image/png;base64,', '', $screenshot);
            $imageData = str_replace(' ', '+', $imageData);
            $fileName = 'attendance_' . $guard . '_' . $user->id . '_' . now()->timestamp . '.png';
            $path = 'attendance/' . $fileName;
            Storage::disk('public')->put($path, base64_decode($imageData));
        }

        if (!$attendance) {
            // Check-in
            $settings = AttendanceSetting::first();
            $targetTimeStr = $guard === 'developer' ? $settings->dev_checkin_time : $settings->sale_checkin_time;
            $targetTime = Carbon::parse($targetTimeStr);
            $checkTime = Carbon::parse($time);
            
            // Calculate absolute difference in minutes
            // If negative, it means they arrived BEFORE the target time
            $lateMinutes = $targetTime->diffInMinutes($checkTime, false);
            
            $status = 'Present';
            $graceThreshold = $settings->grace_period_minutes ?? 15;
            
            if ($lateMinutes > $graceThreshold) {
                $status = 'Late';
            }

            Attendance::create([
                'user_id' => $user->id,
                'user_type' => get_class($user),
                'date' => $date,
                'check_in_time' => $time,
                'check_in_screenshot' => $path,
                'status' => $status,
                'late_minutes' => $lateMinutes,
                'is_checked_in' => true,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            return response()->json(['success' => true, 'message' => 'Checked in successfully at ' . now()->format('h:i A')]);
        } else {
            // Check-out
            if ($attendance->is_checked_in) {
                $checkIn = Carbon::parse($attendance->check_in_time);
                $checkOut = now();
                $totalMinutes = $checkOut->diffInMinutes($checkIn);

                $attendance->update([
                    'check_out_time' => $time,
                    'check_out_screenshot' => $path,
                    'total_minutes' => $totalMinutes,
                    'is_checked_in' => false,
                    'ip_address' => $request->ip(), // Update to last IP
                ]);
                return response()->json(['success' => true, 'message' => 'Checked out successfully at ' . now()->format('h:i A')]);
            } else {
                return response()->json(['success' => false, 'message' => 'You have already checked out for today.']);
            }
        }
    }

    private function getGuard()
    {
        if (auth()->guard('admin')->check()) return 'admin';
        if (auth()->guard('sale')->check()) return 'sale';
        if (auth()->guard('developer')->check()) return 'developer';
        return 'web';
    }
}
