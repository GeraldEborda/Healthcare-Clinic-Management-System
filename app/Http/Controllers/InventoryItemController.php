<?php

namespace App\Http\Controllers;

use App\Models\InventoryItem;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class InventoryItemController extends Controller
{
    public function index(): View
    {
        return view('inventory.index', [
            'items' => InventoryItem::query()
                ->when(request('search'), function ($query, string $search) {
                    $query->where(function ($query) use ($search) {
                        $query->where('name', 'like', "%{$search}%")
                            ->orWhere('category', 'like', "%{$search}%")
                            ->orWhere('supplier', 'like', "%{$search}%");
                    });
                })
                ->when(request('stock') === 'low', fn ($query) => $query->whereColumn('quantity', '<=', 'reorder_level'))
                ->latest()
                ->paginate(10)
                ->withQueryString(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        InventoryItem::create($this->validatedData($request));

        return redirect()->route('inventory.index')->with('status', 'Inventory item added.');
    }

    public function edit(InventoryItem $inventory): View
    {
        return view('inventory.edit', ['item' => $inventory]);
    }

    public function update(Request $request, InventoryItem $inventory): RedirectResponse
    {
        $inventory->update($this->validatedData($request));

        return redirect()->route('inventory.index')->with('status', 'Inventory item updated.');
    }

    public function destroy(InventoryItem $inventory): RedirectResponse
    {
        $inventory->delete();

        return redirect()->route('inventory.index')->with('status', 'Inventory item removed.');
    }

    private function validatedData(Request $request): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'category' => ['required', 'string', 'max:255'],
            'quantity' => ['required', 'integer', 'min:0'],
            'unit' => ['required', 'string', 'max:50'],
            'reorder_level' => ['required', 'integer', 'min:0'],
            'supplier' => ['nullable', 'string', 'max:255'],
            'expires_at' => ['nullable', 'date'],
        ]);
    }
}
