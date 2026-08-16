@extends('layouts.admin')

@section('title', 'Food Recipe Management — All The Season Garden')

@push('styles')
<style>
    .recipe-wrap {
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
    }

    /* Page Header */
    .recipe-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 20px;
        flex-wrap: wrap;
        gap: 16px;
    }
    .recipe-title-group h1 {
        font-size: 22px;
        font-weight: 700;
        color: #111827;
        margin: 0 0 4px;
        letter-spacing: -0.02em;
    }
    .recipe-title-group p {
        font-size: 13px;
        color: #6b7280;
        margin: 0;
    }
    .btn-add-recipe {
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
    .btn-add-recipe:hover {
        background: #b91c1c;
    }

    /* Search Bar Top */
    .recipe-search-bar {
        position: relative;
        margin-bottom: 24px;
        max-width: 420px;
    }
    .recipe-search-bar i {
        position: absolute;
        left: 14px;
        top: 50%;
        transform: translateY(-50%);
        color: #9ca3af;
        font-size: 14px;
    }
    .recipe-search-input {
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
    .recipe-search-input:focus {
        border-color: #dc2626;
        box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.08);
    }

    /* Cards Grid */
    .recipe-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
        gap: 20px;
    }
    .recipe-card {
        background: #ffffff;
        border: 1px solid #e5e7eb;
        border-radius: 10px;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        height: 100%;
        transition: border-color 0.15s ease, box-shadow 0.15s ease;
    }
    .recipe-card:hover {
        border-color: #d1d5db;
        box-shadow: 0 4px 14px rgba(0,0,0,0.04);
    }

    /* Card Header */
    .recipe-card-header {
        padding: 14px 18px;
        border-bottom: 1px solid #f3f4f6;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    .recipe-dish-img {
        width: 40px;
        height: 40px;
        border-radius: 8px;
        object-fit: cover;
        background: #f3f4f6;
        flex-shrink: 0;
    }
    .recipe-dish-name {
        font-size: 14px;
        font-weight: 700;
        color: #111827;
        margin: 0;
    }
    .recipe-dish-cat {
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
    .recipe-card-body {
        padding: 16px 18px;
        flex: 1;
    }
    .recipe-section-title {
        font-size: 11px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        color: #9ca3af;
        margin-bottom: 10px;
    }
    .recipe-ingr-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }
    .recipe-ingr-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 8px 0;
        border-bottom: 1px solid #f9fafb;
        font-size: 13px;
    }
    .recipe-ingr-item:last-child {
        border-bottom: none;
    }
    .recipe-ingr-name {
        color: #111827;
        font-weight: 500;
        display: flex;
        align-items: center;
        gap: 6px;
    }
    .recipe-ingr-name i {
        font-size: 8px;
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
        // Real-time Search Filter for Dishes
        $('#recipe-search-input').on('keyup', function() {
            var query = $(this).val().toLowerCase();

            $('.recipe-card-wrapper').each(function() {
                var dishName = $(this).data('dish_name').toLowerCase();
                var categoryName = $(this).data('cat_name').toLowerCase();
                var ingrNames = $(this).data('ingr_names').toLowerCase();

                if (dishName.includes(query) || categoryName.includes(query) || ingrNames.includes(query)) {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            });
        });

        // Set dish in modal when '+' is clicked
        $('.add-ingredient-to-menu-btn').click(function() {
            var menuId = $(this).data('menu_id');
            if (menuId) {
                $('#recipeMenuId').val(menuId);
            }
        });
    });
</script>
@endpush

@section('content')
<div class="content-wrapper recipe-wrap">
    
    @include('partials.message-bag')

    {{-- Page Header --}}
    <div class="recipe-header">
        <div class="recipe-title-group">
            <h1>Food Recipe Management</h1>
            <p>Link menu dishes to raw ingredients for automatic kitchen inventory deduction.</p>
        </div>
        <button class="btn-add-recipe" data-bs-toggle="modal" data-bs-target="#addRecipeModal">
            <i class="fas fa-plus me-1"></i> Add Recipe Ingredient
        </button>
    </div>

    {{-- Real-time Search Input --}}
    <div class="recipe-search-bar">
        <i class="fas fa-search"></i>
        <input type="text" id="recipe-search-input" class="recipe-search-input" placeholder="Search dish, category or ingredient...">
    </div>

    {{-- Recipe Cards Grid --}}
    <div class="recipe-grid">
        @forelse ($menus as $menu)
            @php
                $ingrNames = $menu->recipes ? $menu->recipes->pluck('stockItem.name')->filter()->implode(' ') : '';
            @endphp
            <div class="recipe-card-wrapper" 
                 data-dish_name="{{ $menu->name }}" 
                 data-cat_name="{{ $menu->category->name ?? '' }}"
                 data-ingr_names="{{ $ingrNames }}">
                 
                <div class="recipe-card">
                    
                    {{-- Header --}}
                    <div class="recipe-card-header">
                        <div class="d-flex align-items-center gap-3">
                            <img src="{{ $menu->image_url }}" 
                                 alt="{{ $menu->name }}" 
                                 class="recipe-dish-img"
                                 onerror="this.onerror=null;this.src='/assets/images/placeholder.jpg';">
                            <div>
                                <h3 class="recipe-dish-name">{{ $menu->name }}</h3>
                                <div class="recipe-dish-cat">{{ $menu->category->name ?? 'Food Item' }}</div>
                            </div>
                        </div>
                        <button class="btn-add-ingr-link add-ingredient-to-menu-btn"
                                data-menu_id="{{ $menu->id }}"
                                data-bs-toggle="modal"
                                data-bs-target="#addRecipeModal"
                                title="Add Ingredient">
                            <i class="fas fa-plus"></i>
                        </button>
                    </div>

                    {{-- Body --}}
                    <div class="recipe-card-body">
                        <div class="recipe-section-title">Required Ingredients:</div>
                        
                        @if($menu->recipes && $menu->recipes->count() > 0)
                            <ul class="recipe-ingr-list">
                                @foreach ($menu->recipes as $recipe)
                                    <li class="recipe-ingr-item">
                                        <div class="recipe-ingr-name">
                                            <i class="fas fa-circle"></i>
                                            <span>{{ $recipe->stockItem->name ?? 'Ingredient' }}</span>
                                        </div>
                                        <div class="d-flex align-items-center gap-2">
                                            <span class="badge bg-light text-dark border font-weight-normal" style="font-size: 11.5px; padding: 3px 8px;">
                                                {{ number_format($recipe->quantity, 2) }} {{ $recipe->stockItem->unit ?? '' }}
                                            </span>
                                            <form action="{{ route('admin.kitchen.recipes.destroy', $recipe->id) }}" method="POST" data-confirm-message="Are you sure you want to remove this ingredient from the recipe?">
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
                    <p class="mb-0">No food menu items found.</p>
                </div>
            </div>
        @endforelse
    </div>

</div>

{{-- Add Recipe Modal --}}
<div class="modal fade" id="addRecipeModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form action="{{ route('admin.kitchen.recipes.store') }}" method="POST" style="width: 100%;">
            @csrf
            <div class="modal-content border-0" style="border-radius: 10px;">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title font-weight-bold">Add Recipe Ingredient</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body py-3">
                    <div class="mb-3">
                        <label class="fw-semibold mb-1" style="font-size: 12px;">Select Menu Dish *</label>
                        <select name="menu_id" id="recipeMenuId" class="form-select" required style="font-size: 13px;">
                            <option value="">-- Select Food Dish --</option>
                            @foreach ($menus as $menu)
                                <option value="{{ $menu->id }}">{{ $menu->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="fw-semibold mb-1" style="font-size: 12px;">Select Raw Material Ingredient *</label>
                        <select name="stock_item_id" id="recipeStockItemId" class="form-select" required style="font-size: 13px;">
                            <option value="">-- Select Ingredient --</option>
                            @foreach ($ingredients as $ing)
                                <option value="{{ $ing->id }}">{{ $ing->name }} (Unit: {{ $ing->unit }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-2">
                        <label class="fw-semibold mb-1" style="font-size: 12px;">Quantity Required per Portion *</label>
                        <input type="number" step="0.001" name="quantity" class="form-control" required placeholder="e.g. 0.250 for 250g / 10 for 10g" style="font-size: 13px;">
                        <small class="text-muted d-block mt-1" style="font-size: 11px;">Specify portion used per 1 dish order (e.g. 0.25 Kg Meat, 10 Grams Salt, 15 ml Oil).</small>
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
