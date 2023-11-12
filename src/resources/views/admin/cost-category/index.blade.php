@extends('admin._layouts.default', [
    'title' => 'Kategori Biaya',
    'menu_active' => 'cost',
    'nav_active' => 'cost-category',
])

@section('right-menu')
  <li class="nav-item">
    <a href="<?= url('admin/cost-categories/edit/0') ?>" class="btn plus-btn btn-primary mr-2" title="Baru"><i
        class="fa fa-plus"></i></a>
  </li>
@endsection

@section('content')
  <div class="card card-light">
    @include('admin._components.card-header', ['title' => 'Kategori Biaya'])
    <div class="card-body">
      <div class="row">
        <div class="col-md-12">
          <table class="data-table display table table-bordered table-striped table-condensed center-th"
            style="width:100%">
            <thead>
              <tr>
                <th style="max-width:25%">Nama Kategori</th>
                <th>Deskripsi</th>
                <th class="text-center" style="max-width:10%">Aksi</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($items as $item) : ?>
              <tr>
                <td>{{ $item->name }}</td>
                <td>{{ $item->description }}</td>
                <td class="text-center">
                  <div class="btn-group">
                    <a href="{{ url("/admin/cost-categories/edit/$item->id") }}" class="btn btn-default btn-sm"><i
                        class="fa fa-edit"></i></a>
                    <a onclick="return confirm('Hapus kategori?')"
                      href="{{ url("/admin/cost-categories/delete/$item->id") }}" class="btn btn-danger btn-sm"><i
                        class="fa fa-trash"></i></a>
                  </div>
                </td>
              </tr>
              <?php endforeach ?>
            </tbody>
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
        targets: 1
      }];
      $('.data-table').DataTable(DATATABLES_OPTIONS);
    });
  </script>
@endsection
