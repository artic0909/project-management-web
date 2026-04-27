<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Models\AttendanceSetting;
use App\Models\Sale;
use App\Models\Developer;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        $guard = $this->getGuard();
        $user = auth()->guard($guard)->user();
        $routePrefix = $guard;

        $query = Attendance::with('user');

        if ($guard !== 'admin') {
            // For Sales person or Developer, show their own attendance
            $userType = $guard === 'developer' ? 'Developer' : 'Sale';
            $query->where('user_id', $user->id)
                  ->where('user_type', $userType);
        } else {
            // Admin default view: Could show all, but usually redirected from sidebar
            if ($request->filled('user_type')) {
                $query->where('user_type', $request->user_type);
            }
        }

        $this->applyFilters($query, $request);

        // One-time fix for existing messed up records (Optional, but ensures sum() works)
        // We only do this if total_seconds is 0 or negative but both times exist
        Attendance::where('total_seconds', '<=', 0)
            ->whereNotNull('check_in_time')
            ->whereNotNull('check_out_time')
            ->get()
            ->each(function($att) {
                $cIn = Carbon::parse($att->date->toDateString() . ' ' . $att->check_in_time);
                $cOut = Carbon::parse($att->date->toDateString() . ' ' . $att->check_out_time);
                $diff = abs($cOut->diffInSeconds($cIn, false));
                if($diff > 0){
                    $att->update(['total_seconds' => $diff]);
                }
            });

        // Sum of all filtered results (using ABS to handle any lingering negatives)
        $totalWorkSeconds = $query->sum(DB::raw('ABS(total_seconds)'));

        $attendances = $query->latest('date')->latest('check_in_time')->paginate(20)->withQueryString();
        
        $settings = AttendanceSetting::first() ?? AttendanceSetting::create([
            'dev_checkin_time' => '10:00:00',
            'dev_checkout_time' => '19:00:00',
            'sale_checkin_time' => '10:00:00',
            'sale_checkout_time' => '19:00:00',
        ]);

        $todayAttendance = null;
        if($guard !== 'admin'){
            $userType = $guard === 'developer' ? 'Developer' : 'Sale';
            $todayAttendance = Attendance::where('user_id', $user->id)
                ->where('user_type', $userType)
                ->where('date', now()->toDateString())
                ->first();
        }

        $absentCountQuery = clone $query;
        $totalAbsentDays = $absentCountQuery->where('status', 'Absent')->count();

        return view('admin.attendance.index', compact('attendances', 'settings', 'routePrefix', 'todayAttendance', 'totalWorkSeconds', 'totalAbsentDays'));
    }

    public function saleIndex(Request $request)
    {
        $this->authorizeAdmin();
        $routePrefix = 'admin';
        
        $query = Attendance::with('user')->where('user_type', 'Sale');
        
        $this->applyFilters($query, $request);

        // Sum of all filtered results
        $totalWorkSeconds = $query->sum(DB::raw('ABS(total_seconds)'));

        $attendances = $query->latest('date')->latest('check_in_time')->paginate(20)->withQueryString();
        $settings = AttendanceSetting::first();
        $allSales = Sale::all();
        
        $absentCountQuery = clone $query;
        $totalAbsentDays = $absentCountQuery->where('status', 'Absent')->count();

        return view('admin.attendance.sale-index', compact('attendances', 'settings', 'routePrefix', 'allSales', 'totalWorkSeconds', 'totalAbsentDays'));
    }

    public function devIndex(Request $request)
    {
        $this->authorizeAdmin();
        $routePrefix = 'admin';
        
        $query = Attendance::with('user')->where('user_type', 'Developer');
        
        $this->applyFilters($query, $request);

        // Sum of all filtered results
        $totalWorkSeconds = $query->sum(DB::raw('ABS(total_seconds)'));

        $attendances = $query->latest('date')->latest('check_in_time')->paginate(20)->withQueryString();
        $settings = AttendanceSetting::first();
        $allDevelopers = Developer::all();
        
        $absentCountQuery = clone $query;
        $totalAbsentDays = $absentCountQuery->where('status', 'Absent')->count();

        return view('admin.attendance.dev-index', compact('attendances', 'settings', 'routePrefix', 'allDevelopers', 'totalWorkSeconds', 'totalAbsentDays'));
    }

    private function applyFilters($query, Request $request)
    {
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('date', [$request->start_date, $request->end_date]);
        } elseif ($request->filled('date')) {
            $query->where('date', $request->date);
        }

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }
    }

    public function storeSettings(Request $request)
    {
        $request->validate([
            'dev_checkin_time' => 'required',
            'dev_checkout_time' => 'required',
            'sale_checkin_time' => 'required',
            'sale_checkout_time' => 'required',
            'grace_period_minutes' => 'nullable|integer',
            'lunch_time' => 'nullable|numeric',
            'lunch_time_unit' => 'nullable|in:minutes,hours',
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
        $now = now();
        $dateStr = $now->toDateString();
        $timeStr = $now->toTimeString();

        // Use the mapped alias from relations map
        $userType = $guard === 'developer' ? 'Developer' : ($guard === 'sale' ? 'Sale' : 'Unknown');

        $attendance = Attendance::where('user_id', $user->id)
            ->where('user_type', $userType)
            ->where('date', $dateStr)
            ->first();

        $screenshot = $request->input('screenshot');
        $path = null;

        if ($screenshot) {
            $imageData = str_replace('data:image/png;base64,', '', $screenshot);
            $imageData = str_replace(' ', '+', $imageData);
            $fileName = 'attendance_' . $guard . '_' . $user->id . '_' . $now->timestamp . '.png';
            $path = 'attendance/' . $fileName;
            Storage::disk('public')->put($path, base64_decode($imageData));
        }

        if (!$attendance) {
            // Check-in
            
            // --- AUTO ABSENT RECORDS ---
            $lastAttendance = Attendance::where('user_id', $user->id)
                ->where('user_type', $userType)
                ->where('date', '<', $dateStr)
                ->orderBy('date', 'desc')
                ->first();
            
            if ($lastAttendance) {
                // Determine last record date
                $lastDate = ($lastAttendance->date instanceof Carbon) 
                    ? $lastAttendance->date->copy() 
                    : Carbon::parse($lastAttendance->date);
                
                $todayDate = Carbon::parse($dateStr);
                $diff = $lastDate->diffInDays($todayDate);
                
                if ($diff > 1) {
                    for ($i = 1; $i < $diff; $i++) {
                        $absentDateObj = $lastDate->copy()->addDays($i);
                        $absentDateStr = $absentDateObj->toDateString();
                        
                        $exists = Attendance::where('user_id', $user->id)
                            ->where('user_type', $userType)
                            ->where('date', $absentDateStr)
                            ->exists();
                        
                        if (!$exists) {
                            Attendance::create([
                                'user_id' => $user->id,
                                'user_type' => $userType,
                                'date' => $absentDateStr,
                                'status' => 'Absent',
                                'is_checked_in' => false,
                                'note' => 'System Auto Absent',
                            ]);
                        }
                    }
                }
            }
            // --- END AUTO ABSENT ---

            $settings = AttendanceSetting::first();
            $targetTimeStr = $guard === 'developer' ? $settings->dev_checkin_time : $settings->sale_checkin_time;
            
            // Explicitly use the record date to avoid day-boundary issues
            $targetTime = Carbon::parse($dateStr . ' ' . $targetTimeStr);
            $lateSeconds = $targetTime->diffInSeconds($now, false);
            
            $status = 'Present';
            $graceThresholdSeconds = ($settings->grace_period_minutes ?? 15) * 60;
            
            if ($lateSeconds > $graceThresholdSeconds) {
                $status = 'Late';
            }

            Attendance::create([
                'user_id' => $user->id,
                'user_type' => $userType,
                'date' => $dateStr,
                'check_in_time' => $timeStr,
                'check_in_screenshot' => $path,
                'status' => $status,
                'late_seconds' => $lateSeconds > 0 ? $lateSeconds : 0,
                'is_checked_in' => true,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            return response()->json(['success' => true, 'message' => 'Checked in successfully at ' . $now->format('h:i A')]);
        } else {
            // Check-out
            if ($attendance->is_checked_in) {
                // Ensure date parsing is perfect
                $dbDateStr = ($attendance->date instanceof Carbon) ? $attendance->date->toDateString() : Carbon::parse($attendance->date)->toDateString();
                $checkInDateTime = Carbon::parse($dbDateStr . ' ' . $attendance->check_in_time);
                
                // Using ABSOLUTE difference to prevent negative values (-58)
                $totalSeconds = abs($now->diffInSeconds($checkInDateTime, false));

                $attendance->update([
                    'check_out_time' => $timeStr,
                    'check_out_screenshot' => $path,
                    'total_seconds' => $totalSeconds,
                    'is_checked_in' => false,
                    'ip_address' => $request->ip(),
                ]);
                return response()->json(['success' => true, 'message' => 'Checked out successfully at ' . $now->format('h:i A')]);
            } else {
                return response()->json(['success' => false, 'message' => 'You have already checked out for today.']);
            }
        }
    }

    public function bulkDestroy(Request $request)
    {
        $this->authorizeAdmin();
        $ids = $request->ids;
        if (is_array($ids) && count($ids) > 0) {
            Attendance::whereIn('id', $ids)->delete();
            return response()->json(['success' => true]);
        }
        return response()->json(['success' => false]);
    }

    public function destroy($id)
    {
        $this->authorizeAdmin();
        Attendance::findOrFail($id)->delete();
        return back()->with('success', 'Attendance record deleted successfully.');
    }

    private function getGuard()
    {
        if (auth()->guard('admin')->check()) return 'admin';
        if (auth()->guard('sale')->check()) return 'sale';
        if (auth()->guard('developer')->check()) return 'developer';
        return 'web';
    }

    private function authorizeAdmin()
    {
        if (!auth()->guard('admin')->check()) {
            abort(403, 'Unauthorized action.');
        }
    }
}
