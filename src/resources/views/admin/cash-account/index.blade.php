<?php
use App\Models\CashAccount;
?>

@extends('admin._layouts.default', [
    'title' => 'Akun / Rekening',
    'menu_active' => 'finance',
    'nav_active' => 'cash-account',
])

@section('right-menu')
  <li class="nav-item">
    <a href="{{ url('admin/cash-accounts/edit/0') }}" class="btn plus-btn btn-primary mr-2" title="Baru"><i
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
                <th>Nama Akun / Rek</th>
                <th>Jenis</th>
                <th>Saldo (Rp.)</th>
                <th class="text-center" style="max-width:10%">Aksi</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($items as $item) : ?>
              <tr>
                <td>{{ $item->name }}</td>
                <td>{{ CashAccount::typeNames($item->type) }}</td>
                <td class="text-right">{{ $item->balance }}</td>
                <td class="text-center">
                  <div class="btn-group">
                    <a href="{{ url("/admin/cash-accounts/edit/$item->id") }}" class="btn btn-default btn-sm"><i
                        class="fa fa-edit"></i></a>
                    <a onclick="return confirm('Hapus akun?')" href="{{ url("/admin/cash-accounts/delete/$item->id") }}"
                      class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></a>
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
        targets: 3,
        orderable: false,
      }, {
        targets: 2,
        render: $.fn.dataTable.render.number('.', ',', 0, ''),
      }];
      $('.data-table').DataTable(DATATABLES_OPTIONS);
    });
  </script>
@endsection
