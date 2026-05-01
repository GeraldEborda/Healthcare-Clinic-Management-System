@php($editing = isset($item))
<form method="POST" action="{{ $editing ? route('inventory.update', $item) : route('inventory.store') }}">
    @csrf
    @if ($editing)
        @method('PUT')
    @endif
    <div class="form-grid">
        <label>Item Name <input type="text" name="name" value="{{ old('name', $item->name ?? '') }}" required></label>
        <label>Category <input type="text" name="category" value="{{ old('category', $item->category ?? '') }}" placeholder="Medicine, Supply, Equipment" required></label>
        <label>Quantity <input type="number" min="0" name="quantity" value="{{ old('quantity', $item->quantity ?? 0) }}" required></label>
        <label>Unit <input type="text" name="unit" value="{{ old('unit', $item->unit ?? 'pcs') }}" required></label>
        <label>Reorder Level <input type="number" min="0" name="reorder_level" value="{{ old('reorder_level', $item->reorder_level ?? 0) }}" required></label>
        <label>Expiration Date <input type="date" name="expires_at" value="{{ old('expires_at', isset($item) ? $item->expires_at?->format('Y-m-d') : '') }}"></label>
        <label class="full">Supplier <input type="text" name="supplier" value="{{ old('supplier', $item->supplier ?? '') }}"></label>
    </div>
    <div class="actions">
        <button type="submit">{{ $editing ? 'Update Item' : 'Add Item' }}</button>
        @if ($editing)
            <a class="button secondary" href="{{ route('inventory.index') }}">Cancel</a>
        @endif
    </div>
</form>
