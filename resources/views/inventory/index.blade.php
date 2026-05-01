@extends('layouts.app', ['title' => 'Inventory'])

@section('content')
    <div class="grid grid-2">
        <div class="panel">
            <div class="page-head">
                <div class="section-title">
                    <h1>Inventory</h1>
                    <p>Track clinic supplies, stock levels, suppliers, and expiry dates.</p>
                </div>
            </div>
            @include('inventory._form')
        </div>
        <div class="table-wrap">
            <div class="page-head">
                <div class="section-title">
                    <h3>Inventory Items</h3>
                    <p>Monitor low-stock clinic materials.</p>
                </div>
                <span class="badge">{{ $items->total() }} records</span>
            </div>
            <form method="GET" action="{{ route('inventory.index') }}" class="filters">
                <label class="wide">Search
                    <input type="search" name="search" value="{{ request('search') }}" placeholder="Item, category, or supplier">
                </label>
                <label>Stock
                    <select name="stock">
                        <option value="">All stock</option>
                        <option value="low" @selected(request('stock') === 'low')>Low stock only</option>
                    </select>
                </label>
                <div class="actions">
                    <button type="submit">Filter</button>
                    <a class="button secondary" href="{{ route('inventory.index') }}">Reset</a>
                </div>
            </form>
            <table>
                <thead><tr><th>Item</th><th>Stock</th><th>Supplier</th><th>Expiry</th><th></th></tr></thead>
                <tbody>
                @forelse ($items as $item)
                    <tr>
                        <td data-label="Item"><strong>{{ $item->name }}</strong><div class="meta">{{ $item->category }}</div></td>
                        <td data-label="Stock">{{ $item->quantity }} {{ $item->unit }}<div class="meta">{{ $item->stock_status }} | Reorder at {{ $item->reorder_level }}</div></td>
                        <td data-label="Supplier">{{ $item->supplier ?: 'Not listed' }}</td>
                        <td data-label="Expiry">{{ $item->expires_at?->format('M d, Y') ?? 'N/A' }}</td>
                        <td data-label="Actions">
                            <div class="actions">
                                <a class="button secondary" href="{{ route('inventory.edit', $item) }}">Edit</a>
                                <form class="inline-form" method="POST" action="{{ route('inventory.destroy', $item) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button class="danger" type="submit" onclick="return confirm('Delete this inventory item?')">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="muted">No inventory items yet.</td></tr>
                @endforelse
                </tbody>
            </table>
            <div class="pagination">{{ $items->links() }}</div>
        </div>
    </div>
@endsection
