<div class="modal fade" id="addPriceModal{{ $child->id }}" tabindex="-1" aria-labelledby="addPriceModalLabel{{ $child->id }}" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addPriceModalLabel{{ $child->id }}">
                    Add Monthly Price for {{ $child->name }}
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.children.store-price', $child->id) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="price" class="form-label">Price</label>
                        <div class="input-group">
                            <span class="input-group-text">$</span>
                            <input type="number"
                                   class="form-control"
                                   id="price"
                                   name="price"
                                   step="0.01"
                                   min="0"
                                   required
                                   placeholder="Enter monthly price">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save Price</button>
                </div>
            </form>
        </div>
    </div>
</div>
