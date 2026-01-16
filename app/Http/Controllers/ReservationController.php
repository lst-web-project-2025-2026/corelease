<?php

namespace App\Http\Controllers;

use App\Models\Resource;
use App\Services\ReservationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReservationController extends Controller
{
    public function __construct(
        protected ReservationService $reservationService
    ) {}

    /**
     * Store a newly created reservation in storage.
     */
    public function store(Request $request)
    {
        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'resource_id' => 'required|exists:resources,id',
            'start_date' => 'required|string',
            'end_date' => 'required|string',
            'user_justification' => 'required|string|min:10',
            'configuration' => 'nullable|array',
        ]);

        $validator->after(function ($validator) use ($request) {
            $startStr = $request->input('start_date');
            $endStr = $request->input('end_date');

            // Helper to safely parse date without triggering the PHP 8.3 Xdebug crash
            $safeParse = function ($str) {
                if (!$str) return null;
                $d = \DateTime::createFromFormat('Y-m-d', $str);
                return ($d && $d->format('Y-m-d') === $str) ? \Carbon\Carbon::instance($d) : null;
            };

            $start = $safeParse($startStr);
            $end = $safeParse($endStr);

            if ($startStr && !$start) {
                $validator->errors()->add('start_date', 'The start date is not a valid date.');
            } elseif ($start) {
                if ($start->isPast() && !$start->isToday()) {
                    $validator->errors()->add('start_date', 'The start date must be today or future.');
                }
            }

            if ($endStr && !$end) {
                $validator->errors()->add('end_date', 'The end date is not a valid date.');
            } elseif ($end && $start) {
                if ($end->lte($start)) {
                    $validator->errors()->add('end_date', 'The end date must be after the start date.');
                }
            }
        });

        try {
            $validated = $validator->validate();
            $this->reservationService->create(Auth::user(), $validated);
            return redirect()->back()->with('success', 'Reservation request submitted successfully! A manager will review it soon.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            $resource = \App\Models\Resource::find($request->resource_id);
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput()
                ->with([
                    'old_resource_name' => $resource?->name,
                    'old_category' => $resource?->category?->name,
                ]);
        }
    }
}
