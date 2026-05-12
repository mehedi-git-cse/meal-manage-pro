<?php

namespace App\Http\Controllers;

use App\Models\Deposit;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Carbon\Carbon;

class DepositController extends Controller
{
    /**
     * List deposits — admins/managers see all; staff see own only.
     */
    public function index(Request $request): View
    {
        $user = auth()->user();
        $isAdmin = $user->hasRole(['super_admin', 'manager']);
        $year  = $request->get('year',  now()->year);
        $month = $request->get('month', now()->month);

        $query = Deposit::with(['user', 'recorder'])
            ->orderByDesc('deposit_date')
            ->orderByDesc('id');

        if (!$isAdmin) {
            $query->where('user_id', $user->id);
        } else {
            // Admin filter by user
            if ($request->filled('user_id')) {
                $query->where('user_id', decryptId($request->user_id));
            }
        }

        $query->whereYear('deposit_date', $year)
              ->whereMonth('deposit_date', $month);

        $deposits = $query->paginate(20)->withQueryString();

        $monthTotal = (clone $query)->sum('amount');

        $users = $isAdmin ? User::mealActive()->orderBy('name')->get() : collect();

        // My deposit total for the month (always current user for summary card)
        $myMonthTotal = Deposit::where('user_id', $user->id)
            ->whereYear('deposit_date', $year)
            ->whereMonth('deposit_date', $month)
            ->sum('amount');

        return view('deposits.index', compact(
            'deposits', 'users', 'isAdmin',
            'year', 'month', 'monthTotal', 'myMonthTotal'
        ));
    }

    public function create(): View
    {
        $user    = auth()->user();
        $isAdmin = $user->hasRole(['super_admin', 'manager']);
        $users   = $isAdmin ? User::mealActive()->orderBy('name')->get() : collect([$user]);

        return view('deposits.create', compact('users', 'isAdmin'));
    }

    public function store(Request $request): RedirectResponse
    {
        $user    = auth()->user();
        $isAdmin = $user->hasRole(['super_admin', 'manager']);

        $rules = [
            'amount'       => ['required', 'numeric', 'min:1', 'max:9999999'],
            'deposit_date' => ['required', 'date'],
            'note'         => ['nullable', 'string', 'max:255'],
        ];

        if ($isAdmin) {
            $rules['user_id'] = ['required', 'string'];
        }

        $validated = $request->validate($rules);

        $userId = $isAdmin ? decryptId($request->user_id) : $user->id;

        Deposit::create([
            'user_id'      => $userId,
            'amount'       => $validated['amount'],
            'deposit_date' => $validated['deposit_date'],
            'note'         => $validated['note'] ?? null,
            'recorded_by'  => $user->id,
        ]);

        return redirect()->route('deposits.index')
            ->with('success', 'Deposit recorded successfully.');
    }

    public function edit(Deposit $deposit): View
    {
        $user    = auth()->user();
        $isAdmin = $user->hasRole(['super_admin', 'manager']);

        // Staff can only edit their own deposits
        if (!$isAdmin && $deposit->user_id !== $user->id) {
            abort(403);
        }

        $users = $isAdmin ? User::mealActive()->orderBy('name')->get() : collect([$user]);

        return view('deposits.edit', compact('deposit', 'users', 'isAdmin'));
    }

    public function update(Request $request, Deposit $deposit): RedirectResponse
    {
        $user    = auth()->user();
        $isAdmin = $user->hasRole(['super_admin', 'manager']);

        if (!$isAdmin && $deposit->user_id !== $user->id) {
            abort(403);
        }

        $rules = [
            'amount'       => ['required', 'numeric', 'min:1', 'max:9999999'],
            'deposit_date' => ['required', 'date'],
            'note'         => ['nullable', 'string', 'max:255'],
        ];

        if ($isAdmin) {
            $rules['user_id'] = ['required', 'string'];
        }

        $validated = $request->validate($rules);

        $userId = $isAdmin ? decryptId($request->user_id) : $deposit->user_id;

        $deposit->update([
            'user_id'      => $userId,
            'amount'       => $validated['amount'],
            'deposit_date' => $validated['deposit_date'],
            'note'         => $validated['note'] ?? null,
        ]);

        return redirect()->route('deposits.index')
            ->with('success', 'Deposit updated successfully.');
    }

    public function destroy(Deposit $deposit): RedirectResponse
    {
        $user    = auth()->user();
        $isAdmin = $user->hasRole(['super_admin', 'manager']);

        if (!$isAdmin && $deposit->user_id !== $user->id) {
            abort(403);
        }

        $deposit->delete();

        return redirect()->route('deposits.index')
            ->with('success', 'Deposit deleted.');
    }
}
