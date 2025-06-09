<?php

namespace App\Http\Controllers;

use App\Models\LeaveApplication;
use App\Models\LeaveBalance;
use App\Models\LeaveType;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use LeaveDayCalculator;

class LeaveApplicationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'leave_type_id' => 'required|exists:leave_types,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'nullable|string',
            'is_half_day' => 'boolean',
            'half_day_period' => 'nullable|in:AM,PM|required_if:is_half_day,true',
        ], [
            'leave_type_id.required'      => 'Please select a leave type.',
            'leave_type_id.exists'        => 'The selected leave type is invalid.',
            'start_date.required'         => 'Please choose a start date.',
            'start_date.date'             => 'Start date must be a valid date.',
            'end_date.required'           => 'Please choose an end date.',
            'end_date.date'               => 'End date must be a valid date.',
            'end_date.after_or_equal'     => 'End date must be the same as or after the start date.',
            'is_half_day.boolean'         => 'Invalid half-day value.',
            'half_day_period.required_if' => 'Please choose AM or PM for half-day leave.',
            'half_day_period.in'          => 'Half-day must be either AM or PM.',
        ]);

        $user = Auth::user();

        $leaveType = LeaveType::findOrFail($request->leave_type_id);

        // 1. Calculate number of leave days
        $isHalfDay = $request->boolean('is_half_day');

        $daysRequested = LeaveDayCalculator::getWorkingDays(
            $request->start_date,
            $request->end_date,
            $isHalfDay
        );

        // 2. Check balance if required
        if ($leaveType->requires_balance) {
            $balance = LeaveBalance::where('user_id', $user->id)
                ->where('leave_type_id', $leaveType->id)
                ->first();

            if (!$balance || $balance->balance < $daysRequested) {
                return response()->json(['error' => 'Insifficient leave balance'], 403);
            }
        }

        // 3. Create leave application
        $application = LeaveApplication::create([
            'user_id' => $user->id,
            'leave_type_id' => $leaveType->id,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'reason' => $request->reason,
            'status' => 'pending',
            'is_half_day' => $isHalfDay,
            'half_day_period' => $isHalfDay ? $request->half_day_period : null,
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(LeaveApplication $leaveApplication)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, LeaveApplication $leaveApplication)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(LeaveApplication $leaveApplication)
    {
        //
    }
}
