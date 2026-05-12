<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBazarEntryRequest;
use App\Models\BazarCategory;
use App\Models\BazarEntry;
use App\Models\BazarVendor;
use App\Models\User;
use App\Repositories\BazarRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BazarController extends Controller
{
    public function __construct(private readonly BazarRepository $bazarRepository) {}

    public function index(Request $request)
    {
        $filters = $request->only(['search', 'user_id', 'category_id', 'year', 'month', 'date_from', 'date_to']);
        if (!empty($filters['user_id'])) {
            $filters['user_id'] = decryptId($filters['user_id']);
        }
        if (!empty($filters['category_id'])) {
            $filters['category_id'] = decryptId($filters['category_id']);
        }
        $entries = $this->bazarRepository->getAllPaginated($filters);
        $users = User::active()->orderBy('name')->get();
        $categories = BazarCategory::active()->orderBy('name')->get();

        $monthlyTotal = $this->bazarRepository->getMonthlyTotal(now()->year, now()->month);
        $myMonthlyTotal = $this->bazarRepository->getUserMonthlyTotal(auth()->id(), now()->year, now()->month);
        $categoryExpenses = $this->bazarRepository->getCategoryWiseExpense(now()->year, now()->month);

        return view('bazar.index', compact('entries', 'users', 'categories', 'monthlyTotal', 'myMonthlyTotal', 'categoryExpenses', 'filters'));
    }

    public function create()
    {
        $users = User::active()->orderBy('name')->get();
        $categories = BazarCategory::active()->orderBy('name')->get();
        $vendors = BazarVendor::active()->orderBy('name')->get();
        $today = today()->format('Y-m-d');

        return view('bazar.create', compact('users', 'categories', 'vendors', 'today'));
    }

    public function store(StoreBazarEntryRequest $request)
    {
        $data = $request->validated();

        if ($request->hasFile('receipt_image')) {
            $data['receipt_image'] = $request->file('receipt_image')->store('receipts', 'public');
        }

        $this->bazarRepository->create($data);

        return redirect()->route('bazar.index')
            ->with('success', 'Bazar entry added successfully.');
    }

    public function show(BazarEntry $bazar)
    {
        $bazar->load('user', 'category', 'vendor', 'verifiedBy');
        return view('bazar.show', compact('bazar'));
    }

    public function edit(BazarEntry $bazar)
    {
        $users = User::active()->orderBy('name')->get();
        $categories = BazarCategory::active()->orderBy('name')->get();
        $vendors = BazarVendor::active()->orderBy('name')->get();

        return view('bazar.edit', compact('bazar', 'users', 'categories', 'vendors'));
    }

    public function update(StoreBazarEntryRequest $request, BazarEntry $bazar)
    {
        $data = $request->validated();

        if ($request->hasFile('receipt_image')) {
            if ($bazar->receipt_image) Storage::disk('public')->delete($bazar->receipt_image);
            $data['receipt_image'] = $request->file('receipt_image')->store('receipts', 'public');
        }

        $this->bazarRepository->update($bazar, $data);

        return redirect()->route('bazar.index')
            ->with('success', 'Bazar entry updated successfully.');
    }

    public function destroy(BazarEntry $bazar)
    {
        if ($bazar->receipt_image) Storage::disk('public')->delete($bazar->receipt_image);
        $this->bazarRepository->delete($bazar);

        return redirect()->route('bazar.index')
            ->with('success', 'Bazar entry deleted successfully.');
    }

    public function verify(BazarEntry $bazar)
    {
        $bazar->update([
            'is_verified' => true,
            'verified_by' => auth()->id(),
        ]);

        return back()->with('success', 'Entry verified successfully.');
    }

    // ─── Category Management ────────────────────────────────────────────────

    public function categories()
    {
        $categories = BazarCategory::withCount('bazarEntries')->orderBy('name')->get();
        return view('bazar.categories', compact('categories'));
    }

    public function storeCategory(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:100', 'unique:bazar_categories,name'],
            'icon' => ['nullable', 'string', 'max:100'],
            'color' => ['nullable', 'string', 'max:20'],
            'description' => ['nullable', 'string', 'max:500'],
        ]);

        BazarCategory::create($data);

        return back()->with('success', 'Category added successfully.');
    }

    // ─── Vendor Management ──────────────────────────────────────────────────

    public function vendors()
    {
        $vendors = BazarVendor::withCount('bazarEntries')->orderBy('name')->get();
        return view('bazar.vendors', compact('vendors'));
    }

    public function storeVendor(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:20'],
            'email' => ['nullable', 'email', 'max:255'],
            'address' => ['nullable', 'string', 'max:500'],
            'contact_person' => ['nullable', 'string', 'max:255'],
        ]);

        BazarVendor::create($data);

        return back()->with('success', 'Vendor added successfully.');
    }
}
