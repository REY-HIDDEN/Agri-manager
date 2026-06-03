@extends('layouts.app')

@section('title', 'New Sale')

@section('content')
<div class="page-header">
    <h2><i class="fas fa-cart-plus me-2"></i>New Sale</h2>
    <a href="{{ route('sales.index') }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left me-1"></i> Back
    </a>
</div>

<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('sales.store') }}" id="saleForm">
            @csrf

            <div class="row g-4 mb-4">
                <div class="col-md-4">
                    <label class="form-label">Buyer <span class="text-danger">*</span></label>
                    <select name="buyer_id" class="form-select @error('buyer_id') is-invalid @enderror" required>
                        <option value="">Select Buyer</option>
                        @foreach($buyers as $buyer)
                            <option value="{{ $buyer->id }}" {{ old('buyer_id') == $buyer->id ? 'selected' : '' }}>
                                {{ $buyer->name }} - {{ $buyer->phone }}
                            </option>
                        @endforeach
                    </select>
                    @error('buyer_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label">Sale Date <span class="text-danger">*</span></label>
                    <input type="date" name="sale_date" class="form-control @error('sale_date') is-invalid @enderror"
                           value="{{ old('sale_date', date('Y-m-d')) }}" required>
                    @error('sale_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label">Payment Status <span class="text-danger">*</span></label>
                    <select name="payment_status" class="form-select @error('payment_status') is-invalid @enderror" required>
                        <option value="pending" {{ old('payment_status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="paid" {{ old('payment_status') == 'paid' ? 'selected' : '' }}>Paid</option>
                        <option value="partial" {{ old('payment_status') == 'partial' ? 'selected' : '' }}>Partial</option>
                    </select>
                    @error('payment_status') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>

            <h5 class="mb-3"><i class="fas fa-boxes me-2"></i>Products</h5>

            @if($errors->has('error'))
                <div class="alert alert-danger">{{ $errors->first('error') }}</div>
            @endif

            <div class="table-responsive mb-3">
                <table class="table table-bordered" id="productsTable">
                    <thead>
                        <tr>
                            <th style="width: 40%">Product</th>
                            <th style="width: 20%">Available</th>
                            <th style="width: 20%">Quantity</th>
                            <th style="width: 15%">Subtotal</th>
                            <th style="width: 5%"></th>
                        </tr>
                    </thead>
                    <tbody id="productsBody">
                        <tr class="product-row">
                            <td>
                                <select name="products[0][product_id]" class="form-select product-select" onchange="updateProductInfo(this)" required>
                                    <option value="">Select Product</option>
                                    @foreach($products as $product)
                                        <option value="{{ $product->id }}" data-price="{{ $product->selling_price }}" data-stock="{{ $product->quantity }}">
                                            {{ $product->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </td>
                            <td class="stock-display text-center align-middle">0</td>
                            <td>
                                <input type="number" name="products[0][quantity]" class="form-control product-qty"
                                       min="1" value="1" required onchange="updateSubtotal(this)" onkeyup="updateSubtotal(this)">
                            </td>
                            <td class="subtotal-display text-center align-middle">$0.00</td>
                            <td>
                                <button type="button" class="btn btn-outline-danger btn-sm" onclick="removeProduct(this)" style="display:none;">
                                    <i class="fas fa-times"></i>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <button type="button" class="btn btn-outline-success mb-3" onclick="addProduct()">
                <i class="fas fa-plus me-1"></i> Add Product
            </button>

            <div class="row justify-content-end mb-3">
                <div class="col-md-4">
                    <div class="card bg-light">
                        <div class="card-body">
                            <div class="d-flex justify-content-between mb-2">
                                <span>Total Items:</span>
                                <strong id="totalItems">0</strong>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span>Total Amount:</span>
                                <strong id="totalAmount" class="text-success fs-5">$0.00</strong>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12">
                <button type="submit" class="btn btn-primary btn-lg">
                    <i class="fas fa-check me-1"></i> Complete Sale
                </button>
                <a href="{{ route('sales.index') }}" class="btn btn-outline-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    let productIndex = 1;

    function addProduct() {
        const template = document.querySelector('.product-row').cloneNode(true);
        const selects = template.querySelectorAll('select, input');

        selects.forEach(el => {
            const name = el.getAttribute('name');
            if (name) {
                el.setAttribute('name', name.replace(/\[\d+\]/, `[${productIndex}]`));
                el.value = '';
            }
            if (el.type === 'number') el.value = 1;
        });

        template.querySelector('.subtotal-display').textContent = '$0.00';
        template.querySelector('.stock-display').textContent = '0';
        template.querySelector('.btn-outline-danger').style.display = 'inline-block';
        template.querySelectorAll('[onchange]').forEach(el => {
            const handler = el.getAttribute('onchange');
            if (handler) el.setAttribute('onchange', handler);
        });

        document.getElementById('productsBody').appendChild(template);
        productIndex++;
        updateTotals();
    }

    function removeProduct(btn) {
        btn.closest('tr').remove();
        updateTotals();
    }

    function updateProductInfo(select) {
        const row = select.closest('tr');
        const option = select.options[select.selectedIndex];
        const stock = option.getAttribute('data-stock') || 0;
        const price = option.getAttribute('data-price') || 0;

        row.querySelector('.stock-display').textContent = stock;
        row.querySelector('.product-qty').max = stock;
        updateSubtotal(row.querySelector('.product-qty'));
    }

    function updateSubtotal(input) {
        const row = input.closest('tr');
        const select = row.querySelector('.product-select');
        const option = select.options[select.selectedIndex];
        const price = parseFloat(option.getAttribute('data-price') || 0);
        const qty = parseInt(input.value) || 0;
        const subtotal = price * qty;

        row.querySelector('.subtotal-display').textContent = '$' + subtotal.toFixed(2);
        updateTotals();
    }

    function updateTotals() {
        let totalItems = 0;
        let totalAmount = 0;

        document.querySelectorAll('.product-row').forEach(row => {
            const qty = parseInt(row.querySelector('.product-qty')?.value) || 0;
            const subtotalText = row.querySelector('.subtotal-display')?.textContent || '$0.00';
            const subtotal = parseFloat(subtotalText.replace('$', '')) || 0;

            totalItems += qty;
            totalAmount += subtotal;
        });

        document.getElementById('totalItems').textContent = totalItems;
        document.getElementById('totalAmount').textContent = '$' + totalAmount.toFixed(2);
    }

    // Form validation
    document.getElementById('saleForm').addEventListener('submit', function(e) {
        let valid = true;
        document.querySelectorAll('.product-select').forEach(select => {
            if (!select.value) valid = false;
        });
        if (!valid) {
            e.preventDefault();
            alert('Please select a product for each row.');
        }
    });
</script>
@endpush
