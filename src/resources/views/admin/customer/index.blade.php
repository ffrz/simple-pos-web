@extends('admin._layouts.default', [
    'title' => 'Pelanggan',
    'menu_active' => 'sales',
    'nav_active' => 'customer',
])

@section('right-menu')
  <li class="nav-item">
    <a href="<?= url('admin/customers/edit/0') ?>" class="btn plus-btn btn-primary mr-2" title="Baru"><i
        class="fa fa-plus"></i></a>
  </li>
@endsection

@section('content')
  <div class="card card-light">
    @include('admin._components.card-header', ['title' => 'Daftar Pelanggan'])
    <div class="card-body">
      <div class="row">
        <div class="col-md-12 table-responsive">
          <table class="data-table display table table-bordered table-striped table-condensed center-th"
            style="width:100%">
            <thead>
              <tr>
                <th>Nama</th>
                <th>Kontak</th>
                <th>Alamat</th>
                <th>URL</th>
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($items as $item) : ?>
              <tr>
                <td><?= e($item->name) ?></td>
                <td><?= e($item->contact) ?></td>
                <td><?= e($item->address) ?></td>
                <td><?= e($item->url) ?></td>
                <td class="text-center">
                  <div class="btn-group">
                    <a href="<?= url("/admin/customers/view/$item->id") ?>" class="btn btn-default btn-sm">
                      <i class="fa fa-eye"></i>
                    </a>
                    <a href="<?= url("/admin/customers/edit/$item->id") ?>" class="btn btn-default btn-sm">
                      <i class="fa fa-edit"></i>
                    </a>
                    <a onclick="return confirm('Hapus pemasok?')" href="<?= url("/admin/customers/delete/$item->id") ?>" class="btn btn-danger btn-sm">
                      <i class="fa fa-trash"></i>
                    </a>
                  </div>
                </td>
              </tr>
              <?php endforeach ?>
            </tbody>
            <thead>
              <tr>
                <th>Nama</th>
                <th>Kontak</th>
                <th>Alamat</th>
                <th>URL</th>
                <th>Aksi</th>
              </tr>
            </thead>
          </table>
        </div>
      </div>
    </div>
  </div>
@endsection
@section('footscript')
  <script>
    $(function() {
      DATATABLES_OPTIONS.order = [
        [0, 'asc']
      ];
      DATATABLES_OPTIONS.columnDefs = [{
        orderable: false,
        targets: 4
      }];
      $('.data-table').DataTable(DATATABLES_OPTIONS);
    });
  </script>
@endsection
