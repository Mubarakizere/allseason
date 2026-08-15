<style>
    .stat-card {
        background: #ffffff;
        border: 1px solid #e5e7eb;
        border-radius: 10px;
        padding: 18px 20px;
        height: 100%;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        transition: border-color 0.15s ease, box-shadow 0.15s ease;
        text-decoration: none !important;
        color: inherit !important;
    }
    .stat-card:hover {
        border-color: #d1d5db;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.03);
    }
    .stat-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 12px;
    }
    .stat-title {
        font-size: 13px;
        font-weight: 600;
        color: #6b7280;
        margin: 0;
        text-transform: uppercase;
        letter-spacing: 0.03em;
    }
    .stat-icon-wrapper {
        width: 36px;
        height: 36px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 14px;
        flex-shrink: 0;
    }
    .stat-value {
        font-size: 24px;
        font-weight: 700;
        color: #111827;
        margin: 0 0 6px 0;
        letter-spacing: -0.02em;
    }
    .stat-footer {
        display: flex;
        align-items: center;
        font-size: 12px;
        font-weight: 500;
        color: #9ca3af;
        margin-top: 4px;
    }
    .stat-footer i {
        margin-left: 4px;
        font-size: 10px;
        transition: transform 0.15s ease;
    }
    .stat-card:hover .stat-footer i {
        transform: translateX(3px);
    }

    /* Icon Theme Variations */
    .icon-pending { background: #fff7ed; color: #f97316; }
    .icon-delivery { background: #f0fdf4; color: #16a34a; }
    .icon-instore { background: #eff6ff; color: #2563eb; }
    .icon-all { background: #faf5ff; color: #9333ea; }
</style>

<div class="row g-3">
    <!-- Pending Orders -->
    <div class="col-lg-3 col-sm-6">
        <a href="{{ route('admin.orders.index', ['filter' => 'pending']) }}" class="stat-card">
            <div>
                <div class="stat-header">
                    <span class="stat-title">Pending Orders</span>
                    <div class="stat-icon-wrapper icon-pending">
                        <i class="fas fa-clock"></i>
                    </div>
                </div>
                <div class="stat-value">{{ number_format($pending_orders_count) }}</div>
            </div>
            <div class="stat-footer">
                <span>View pending orders</span>
                <i class="fas fa-chevron-right"></i>
            </div>
        </a>
    </div>

    <!-- Delivery Orders -->
    <div class="col-lg-3 col-sm-6">
        <a href="{{ route('admin.orders.index', ['filter' => 'delivery']) }}" class="stat-card">
            <div>
                <div class="stat-header">
                    <span class="stat-title">Delivery Orders</span>
                    <div class="stat-icon-wrapper icon-delivery">
                        <i class="fas fa-truck"></i>
                    </div>
                </div>
                <div class="stat-value">{{ number_format($delivery_orders_count) }}</div>
            </div>
            <div class="stat-footer">
                <span>View delivery orders</span>
                <i class="fas fa-chevron-right"></i>
            </div>
        </a>
    </div>

    <!-- Dine-in / Instore Orders -->
    <div class="col-lg-3 col-sm-6">
        <a href="{{ route('admin.orders.index', ['filter' => 'instore']) }}" class="stat-card">
            <div>
                <div class="stat-header">
                    <span class="stat-title">Dine-in Orders</span>
                    <div class="stat-icon-wrapper icon-instore">
                        <i class="fas fa-utensils"></i>
                    </div>
                </div>
                <div class="stat-value">{{ number_format($instore_orders_count) }}</div>
            </div>
            <div class="stat-footer">
                <span>View dine-in orders</span>
                <i class="fas fa-chevron-right"></i>
            </div>
        </a>
    </div>

    <!-- Total Orders -->
    <div class="col-lg-3 col-sm-6">
        <a href="{{ route('admin.orders.index') }}" class="stat-card">
            <div>
                <div class="stat-header">
                    <span class="stat-title">All Orders</span>
                    <div class="stat-icon-wrapper icon-all">
                        <i class="fas fa-shopping-bag"></i>
                    </div>
                </div>
                <div class="stat-value">{{ number_format($all_orders_count) }}</div>
            </div>
            <div class="stat-footer">
                <span>View all orders</span>
                <i class="fas fa-chevron-right"></i>
            </div>
        </a>
    </div>
</div>