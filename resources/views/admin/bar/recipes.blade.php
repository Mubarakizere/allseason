@extends('layouts.admin')

@section('title', 'Cocktail & Drink Recipes — All The Season Garden')

@push('styles')
<style>
    .bar-recipe-wrap {
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
    }

    /* Page Header */
    .bar-recipe-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 20px;
        flex-wrap: wrap;
        gap: 16px;
    }
    .bar-recipe-title-group h1 {
        font-size: 22px;
        font-weight: 700;
        color: #111827;
        margin: 0 0 4px;
        letter-spacing: -0.02em;
    }
    .bar-recipe-title-group p {
        font-size: 13px;
        color: #6b7280;
        margin: 0;
    }
    .btn-add-bar-recipe {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 8px 16px;
        border-radius: 8px;
        background: #dc2626;
        color: #ffffff !important;
        font-size: 13px;
        font-weight: 600;
        text-decoration: none !important;
        border: none;
        cursor: pointer;
        transition: background 0.15s ease;
    }
    .btn-add-bar-recipe:hover {
        background: #b91c1c;
    }

    /* Search Bar */
    .bar-recipe-search-bar {
        position: relative;
        margin-bottom: 24px;
        max-width: 420px;
    }
    .bar-recipe-search-bar i {
        position: absolute;
        left: 14px;
        top: 50%;
        transform: translateY(-50%);
        color: #9ca3af;
        font-size: 14px;
    }
    .bar-recipe-search-input {
        width: 100%;
        height: 42px;
        padding: 0 16px 0 38px;
        border-radius: 8px;
        border: 1px solid #e5e7eb;
        background: #ffffff;
        font-size: 13.5px;
        color: #111827;
        outline: none;
        transition: border-color 0.15s ease;
    }
    .bar-recipe-search-input:focus {
        border-color: #dc2626;
        box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.08);
    }

    /* Cards Grid */
    .bar-recipe-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
        gap: 20px;
    }
    .bar-recipe-card {
        background: #ffffff;
        border: 1px solid #e5e7eb;
        border-radius: 10px;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        height: 100%;
        transition: border-color 0.15s ease, box-shadow 0.15s ease;
    }
    .bar-recipe-card:hover {
        border-color: #d1d5db;
        box-shadow: 0 4px 14px rgba(0,0,0,0.04);
    }

    /* Card Header */
    .bar-recipe-card-header {
        padding: 14px 18px;
        border-bottom: 1px solid #f3f4f6;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    .bar-recipe-dish-img {
        width: 40px;
        height: 40px;
        border-radius: 8px;
        object-fit: cover;
        background: #f3f4f6;
        flex-shrink: 0;
    }
    .bar-recipe-dish-name {
        font-size: 14px;
        font-weight: 700;
        color: #111827;
        margin: 0;
    }
    .bar-recipe-dish-cat {
        font-size: 11.5px;
        color: #6b7280;
    }
    .btn-add-ingr-link {
        background: transparent;
        border: 1px solid #e5e7eb;
        color: #374151;
        width: 30px;
        height: 30px;
        border-radius: 6px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 12px;
        cursor: pointer;
        transition: background 0.15s ease;
    }
    .btn-add-ingr-link:hover {
        background: #f3f4f6;
        color: #111827;
    }

    /* Card Body */
    .bar-recipe-card-body {
        padding: 16px 18px;
        flex: 1;
    }
    .bar-recipe-section-title {
        font-size: 11px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        color: #9ca3af;
        margin-bottom: 10px;
    }
    .bar-recipe-ingr-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }
    .bar-recipe-ingr-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 8px 0;
        border-bottom: 1px solid #f9fafb;
        font-size: 13px;
    }
    .bar-recipe-ingr-item:last-child {
        border-bottom: none;
    }
    .bar-recipe-ingr-name {
        color: #111827;
        font-weight: 500;
        display: flex;
        align-items: center;
        gap: 6px;
    }
    .bar-recipe-ingr-name i {
        font-size: 10px;
        color: #dc2626;
    }
    .btn-remove-ingr {
        background: transparent;
        border: none;
        color: #9ca3af;
        cursor: pointer;
        font-size: 12px;
        padding: 2px 4px;
        transition: color 0.15s;
    }
    .btn-remove-ingr:hover {
        color: #ef4444;
    }

    .no-recipes-box {
        background: #f9fafb;
        border: 1px border-dashed #e5e7eb;
        border-radius: 8px;
        padding: 16px;
        text-align: center;
        font-size: 12.5px;
        color: #9ca3af;
    }
</style>
@endpush

@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
    $(document).ready(function() {
        // Real-time Search Filter
        $('#bar-recipe-search-input').on('keyup', function() {
            var query = $(this).val().toLowerCase();

            $('.bar-recipe-card-wrapper').each(function() {
                var drinkName = $(this).data('drink_name').toLowerCase();
                var categoryName = $(this).data('cat_name').toLowerCase();
                var ingrNames = $(this).data('ingr_names').toLowerCase();

                if (drinkName.includes(query) || categoryName.includes(query) || ingrNames.includes(query)) {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            });
        });

        // Set drink in modal when '+' is clicked
        $('.add-ingredient-to-drink-btn').click(function() {
            var menuId = $(this).data('menu_id');
            if (menuId) {
                $('#barRecipeMenuId').val(menuId);
            }
        });
    });
</script>
@endpush

@section('content')
<div class="content-wrapper bar-recipe-wrap">
    
    @include('partials.message-bag')

    {{-- Page Header --}}
    <div class="bar-recipe-header">
        <div class="bar-recipe-title-group">
            <h1>Cocktail & Drink Recipes</h1>
            <p>Link cocktails and mixed drinks to raw spirits and mixers for automatic pour deduction.</p>
        </div>
        <button class="btn-add-bar-recipe" data-bs-toggle="modal" data-bs-target="#addBarRecipeModal">
            <i class="fas fa-plus me-1"></i> Add Cocktail Ingredient
        </button>
    </div>

    {{-- Real-time Search Input --}}
    <div class="bar-recipe-search-bar">
        <i class="fas fa-search"></i>
        <input type="text" id="bar-recipe-search-input" class="bar-recipe-search-input" placeholder="Search drink, category or spirit mixer...">
    </div>

    {{-- Recipe Cards Grid --}}
    <div class="bar-recipe-grid">
        @forelse ($menus as $menu)
            @php
                $ingrNames = $menu->recipes ? $menu->recipes->pluck('stockItem.name')->filter()->implode(' ') : '';
            @endphp
            <div class="bar-recipe-card-wrapper" 
                 data-drink_name="{{ $menu->name }}" 
                 data-cat_name="{{ $menu->category->name ?? '' }}"
                 data-ingr_names="{{ $ingrNames }}">
                 
                <div class="bar-recipe-card">
                    
                    {{-- Header --}}
                    <div class="bar-recipe-card-header">
                        <div class="d-flex align-items-center gap-3">
                            <img src="{{ $menu->image_url }}" 
                                 alt="{{ $menu->name }}" 
                                 class="bar-recipe-dish-img"
                                 onerror="this.onerror=null;this.src='/assets/images/placeholder.jpg';">
                            <div>
                                <h3 class="bar-recipe-dish-name">{{ $menu->name }}</h3>
                                <div class="bar-recipe-dish-cat">{{ $menu->category->name ?? 'Beverage Item' }}</div>
                            </div>
                        </div>
                        <button class="btn-add-ingr-link add-ingredient-to-drink-btn"
                                data-menu_id="{{ $menu->id }}"
                                data-bs-toggle="modal"
                                data-bs-target="#addBarRecipeModal"
                                title="Add Ingredient">
                            <i class="fas fa-plus"></i>
                        </button>
                    </div>

                    {{-- Body --}}
                    <div class="bar-recipe-card-body">
                        <div class="bar-recipe-section-title">Spirits & Mixers:</div>
                        
                        @if($menu->recipes && $menu->recipes->count() > 0)
                            <ul class="bar-recipe-ingr-list">
                                @foreach ($menu->recipes as $recipe)
                                    <li class="bar-recipe-ingr-item">
                                        <div class="bar-recipe-ingr-name">
                                            <i class="fas fa-wine-bottle"></i>
                                            <span>{{ $recipe->stockItem->name ?? 'Bar Item' }}</span>
                                        </div>
                                        <div class="d-flex align-items-center gap-2">
                                            <span class="badge bg-light text-dark border font-weight-normal" style="font-size: 11.5px; padding: 3px 8px;">
                                                {{ number_format($recipe->quantity, 2) }} {{ $recipe->stockItem->unit ?? '' }}
                                            </span>
                                            <form action="{{ route('admin.bar.recipes.destroy', $recipe->id) }}" method="POST" onsubmit="return confirm('Remove ingredient from recipe?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn-remove-ingr" title="Remove Ingredient">
                                                    <i class="fas fa-times-circle"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <div class="no-recipes-box">
                                <i class="fas fa-info-circle me-1 text-muted"></i> No recipe ingredients linked yet.
                            </div>
                        @endif
                    </div>

                </div>
            </div>
        @empty
            <div class="col-12" style="grid-column: 1 / -1;">
                <div class="no-recipes-box py-5">
                    <p class="mb-0">No beverage menu items found.</p>
                </div>
            </div>
        @endforelse
    </div>

</div>

{{-- Add Cocktail Recipe Modal --}}
<div class="modal fade" id="addBarRecipeModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form action="{{ route('admin.bar.recipes.store') }}" method="POST" style="width: 100%;">
            @csrf
            <div class="modal-content border-0" style="border-radius: 10px;">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title font-weight-bold">Add Cocktail / Drink Ingredient</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body py-3">
                    <div class="mb-3">
                        <label class="fw-semibold mb-1" style="font-size: 12px;">Select Cocktail / Drink *</label>
                        <select name="menu_id" id="barRecipeMenuId" class="form-select" required style="font-size: 13px;">
                            <option value="">-- Select Drink Item --</option>
                            @foreach ($menus as $menu)
                                <option value="{{ $menu->id }}">{{ $menu->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="fw-semibold mb-1" style="font-size: 12px;">Select Raw Spirit / Mixer *</label>
                        <select name="stock_item_id" id="barRecipeStockItemId" class="form-select" required style="font-size: 13px;">
                            <option value="">-- Select Bar Stock Item --</option>
                            @foreach ($ingredients as $ing)
                                <option value="{{ $ing->id }}">{{ $ing->name }} (Unit: {{ $ing->unit }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-2">
                        <label class="fw-semibold mb-1" style="font-size: 12px;">Pour Quantity Required per Serving *</label>
                        <input type="number" step="0.01" name="quantity" class="form-control" required placeholder="e.g. 6.00 for 6cl Rum / 1 for 1 Bottle" style="font-size: 13px;">
                        <small class="text-muted d-block mt-1" style="font-size: 11px;">Specify pour quantity per glass/serving (e.g. 6 Centiliters Rum, 1 Bottle Tonic).</small>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0 pb-3">
                    <button type="button" class="btn btn-light px-4 me-2" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger px-4 font-weight-bold">Save Ingredient</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
