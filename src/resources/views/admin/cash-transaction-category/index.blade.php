@extends('admin._layouts.default', [
    'title' => 'Kategori Transaksi',
    'menu_active' => 'finance',
    'nav_active' => 'cash-transaction-category',
])

@section('right-menu')
  <li class="nav-item">
    <a href="{{ url('admin/cash-transaction-categories/edit/0') }}" class="btn plus-btn btn-primary mr-2" title="Baru"><i
        class="fa fa-plus"></i></a>
  </li>
@endsection

@section('content')
  <div class="card card-primary">
    <div class="card-body">
      <div class="row">
        <div class="col-md-12">
          <table class="data-table display table table-bordered table-striped table-condensed center-th"
            style="width:100%">
            <thead>
              <tr>
                <th style="max-width:10%">No</th>
                <th>Nama Kategori</th>
                <th class="text-center" style="max-width:10%">Aksi</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($items as $i => $item)
                <tr>
                  <td class="text-right">{{ $i + 1 }}</td>
                  <td>{{ $item->name }}</td>
                  <td class="text-center">
                    <div class="btn-group">
                      <a href="{{ url("/admin/cash-transaction-categories/edit/$item->id") }}"
                        class="btn btn-default btn-sm"><i class="fa fa-edit"></i></a>
                      <a onclick="return confirm('Hapus kategori?')"
                        href="{{ url("/admin/cash-transaction-categories/delete/$item->id") }}"
                        class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></a>
                    </div>
                  </td>
                </tr>
              @endforeach
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
        targets: 2
      }];
      $('.data-table').DataTable(DATATABLES_OPTIONS);
    });
  </script>
@endsection
