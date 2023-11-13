<?php
use App\Common\Acl;

if (!isset($menu_active)) {
    $menu_active = null;
}

?>
<aside class="main-sidebar sidebar-light-primary elevation-4">
  <a href="{{ url('/admin/') }}" class="brand-link">
    <img src="{{ url('dist/img/logo.png') }}" alt="App Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
    <span class="brand-text font-weight-light">Shift IMS</span>
  </a>
  <div class="sidebar">
    <nav class="mt-2">
      <ul class="nav nav-pills nav-sidebar flex-column nav-flat nav-flat nav-collapse-hide-child" data-widget="treeview"
        role="menu" data-accordion="false">
        <li class="nav-item">
          <a href="{{ url('/admin/') }}" class="nav-link {{ $nav_active == 'dashboard' ? 'active' : '' }}">
            <i class="nav-icon fas fa-tachometer-alt"></i>
            <p>Dashboard</p>
          </a>
        </li>
        <li class="nav-item {{ $menu_active == 'report' ? 'menu-open' : '' }}">
          <a href="#" class="nav-link {{ $menu_active == 'report' ? 'active' : '' }}">
            <i class="nav-icon fas fa-chart-pie"></i>
            <p>
              Laporan
              <i class="right fas fa-angle-left"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="{{ url('/admin/reports/income-statement') }}"
                class="nav-link {{ $nav_active == 'income-statement-report' ? 'active' : '' }}">
                <i class="nav-icon fas fa-file-contract"></i>
                <p>Lap. Laba Rugi</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{ url('/admin/reports/sales-by-category') }}"
                class="nav-link {{ $nav_active == 'sales-by-category' ? 'active' : '' }}">
                <i class="nav-icon fas fa-file-contract"></i>
                <p><small>Lap. Penjualan per Kategori</small></p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{ url('/admin/reports/stock-assets') }}"
                class="nav-link {{ $nav_active == 'stock-report' ? 'active' : '' }}">
                <i class="nav-icon fas fa-file-contract"></i>
                <p>Lap. Stok</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{ url('/admin/reports/cost') }}"
                class="nav-link {{ $nav_active == 'report/cost' ? 'active' : '' }}">
                <i class="nav-icon fas fa-file-contract"></i>
                <p><small>Lap. Biaya Operasional</small></p>
              </a>
            </li>
          </ul>
        </li>
        <li class="nav-item {{ $menu_active == 'sales' ? 'menu-open' : '' }}">
          <a href="#" class="nav-link {{ $menu_active == 'sales' ? 'active' : '' }}">
            <i class="nav-icon fas fa-cart-shopping"></i>
            <p>
              Penjualan
              <i class="right fas fa-angle-left"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="{{ url('/admin/sales-orders/') }}"
                class="nav-link {{ $nav_active == 'sales-order' ? 'active' : '' }}">
                <i class="nav-icon fas fa-cart-shopping"></i>
                <p>Penjualan</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{ url('/admin/service-orders/') }}"
                class="nav-link {{ $nav_active == 'service-order' ? 'active' : '' }}">
                <i class="nav-icon fas fa-screwdriver-wrench"></i>
                <p>Servis</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{ url('admin/customers/') }}"
                class="nav-link {{ $nav_active == 'customer' ? 'active' : '' }}">
                <i class="nav-icon fas fa-users"></i>
                <p>Pelanggan</p>
              </a>
            </li>
          </ul>
        </li>
        <li class="nav-item {{ $menu_active == 'inventory' ? 'menu-open' : '' }}">
          <a href="#" class="nav-link {{ $menu_active == 'inventory' ? 'active' : '' }}">
            <i class="nav-icon fas fa-warehouse"></i>
            <p>
              Inventori
              <i class="right fas fa-angle-left"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="{{ url('/admin/stock-adjustments/') }}"
                class="nav-link {{ $nav_active == 'stock-adjustment' ? 'active' : '' }}">
                <i class="nav-icon fas fa-sliders"></i>
                <p>Penyesuaian Stok</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{ url('/admin/stock-updates/') }}"
                class="nav-link {{ $nav_active == 'stock-update' ? 'active' : '' }}">
                <i class="nav-icon fas fa-clock-rotate-left"></i>
                <p>Riwayat Stok</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{ url('/admin/products/') }}" class="nav-link {{ $nav_active == 'product' ? 'active' : '' }}">
                <i class="nav-icon fas fa-cubes"></i>
                <p>Produk</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{ url('/admin/product-categories/') }}"
                class="nav-link {{ $nav_active == 'product-category' ? 'active' : '' }}">
                <i class="fas fa-boxes nav-icon"></i>
                <p>Kategori Produk</p>
              </a>
            </li>
          </ul>
        </li>
        <li class="nav-item {{ $menu_active == 'purchasing' ? 'menu-open' : '' }}">
          <a href="#" class="nav-link {{ $menu_active == 'purchasing' ? 'active' : '' }}">
            <i class="nav-icon fas fa-truck-fast"></i>
            <p>
              Pembelian
              <i class="right fas fa-angle-left"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="{{ url('/admin/purchase-orders/') }}"
                class="nav-link {{ $nav_active == 'purchase-order' ? 'active' : '' }}">
                <i class="nav-icon fas fa-truck-ramp-box"></i>
                <p>Pembelian</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{ url('/admin/suppliers/') }}"
                class="nav-link {{ $nav_active == 'supplier' ? 'active' : '' }}">
                <i class="nav-icon fas fa-truck"></i>
                <p>Pemasok</p>
              </a>
            </li>
          </ul>
        </li>
        <li class="nav-item {{ $menu_active == 'cost' ? 'menu-open' : '' }}">
          <a href="#" class="nav-link {{ $menu_active == 'cost' ? 'active' : '' }}">
            <i class="nav-icon fas fa-receipt"></i>
            <p>
              Biaya Operasional
              <i class="right fas fa-angle-left"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="{{ url('/admin/costs') }}" class="nav-link {{ $nav_active == 'cost' ? 'active' : '' }}">
                <i class="nav-icon fas fa-receipt"></i>
                <p>Biaya Operasional</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{ url('/admin/cost-categories') }}"
                class="nav-link {{ $nav_active == 'cost-category' ? 'active' : '' }}">
                <i class="nav-icon fas fa-folder-tree"></i>
                <p>Kategori Biaya</p>
              </a>
            </li>
          </ul>
        </li>
        <li class="nav-item {{ $menu_active == 'finance' ? 'menu-open' : '' }}">
          <a href="#" class="nav-link {{ $menu_active == 'finance' ? 'active' : '' }}">
            <i class="nav-icon fas fa-wallet"></i>
            <p>
              Kas
              <i class="right fas fa-angle-left"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="{{ url('/admin/cash-transactions') }}"
                class="nav-link {{ $nav_active == 'cash-transaction' ? 'active' : '' }}">
                <i class="nav-icon fas fa-money-bill-transfer"></i>
                <p>Transaksi Kas</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{ url('/admin/cash-transaction-categories') }}"
                class="nav-link {{ $nav_active == 'cash-transaction-category' ? 'active' : '' }}">
                <i class="nav-icon fas fa-folder-tree"></i>
                <p>Kategori Transaksi</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{ url('/admin/cash-accounts') }}"
                class="nav-link {{ $nav_active == 'cash-account' ? 'active' : '' }}">
                <i class="nav-icon fas fa-money-check"></i>
                <p>Akun / Rekening</p>
              </a>
            </li>
          </ul>
        </li>
        <li class="nav-item {{ $menu_active == 'system' ? 'menu-open' : '' }}">
          <a href="#" class="nav-link {{ $menu_active == 'system' ? 'active' : '' }}">
            <i class="nav-icon fas fa-gears"></i>
            <p>
              Sistem
              <i class="right fas fa-angle-left"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="{{ url('/admin/users') }}" class="nav-link {{ $nav_active == 'users' ? 'active' : '' }}">
                <i class="nav-icon fas fa-user"></i>
                <p>Pengguna</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{ url('/admin/user-groups') }}"
                class="nav-link {{ $nav_active == 'user-groups' ? 'active' : '' }}">
                <i class="nav-icon fas fa-users"></i>
                <p>Grup Pengguna</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{ url('/admin/settings') }}"
                class="nav-link {{ $nav_active == 'settings' ? 'active' : '' }}">
                <i class="nav-icon fas fa-gear"></i>
                <p>Pengaturan</p>
              </a>
            </li>
          </ul>
        </li>
        <li class="nav-item">
          <a href="{{ url('/admin/users/profile/') }}"
            class="nav-link {{ $nav_active == 'profile' ? 'active' : '' }}">
            <i class="nav-icon fas fa-user"></i>
            <p>{{ Auth::user()->username }}</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="{{ url('admin/logout') }}" class="nav-link">
            <i class="nav-icon fas fa-right-from-bracket"></i>
            <p>Keluar</p>
          </a>
        </li>
      </ul>
    </nav>
  </div>
</aside>
