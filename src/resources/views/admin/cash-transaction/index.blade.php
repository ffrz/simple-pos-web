@extends('admin._layouts.default', [
    'title' => 'Transaksi Keuangan',
    'menu_active' => 'finance',
    'nav_active' => 'cash-transaction',
])

@section('right-menu')
  <li class="nav-item">
    <a href="{{ url('admin/cash-transactions/edit/0') }}" class="btn plus-btn btn-primary mr-2" title="Baru"><i
        class="fa fa-plus"></i></a>
    <button class="btn btn-default plus-btn mr-2" data-toggle="modal" data-target="#modal-sm" title="Saring"><i
        class="fa fa-filter"></i></button>
  </li>
@endsection

@section('content')
  <form method="GET" class="form-horizontal">
    <div class="modal fade" id="modal-sm">
      <div class="modal-dialog modal-md">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title">Penyaringan</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="form-group row">
              <label class="col-form-label col-sm-4" for="year">Tahun</label>
              <div class="col-sm-8">
                <select class="custom-select mt-2" id="year" name="year">
                  @for ($year = date('Y'); $year >= 2022; $year--)
                    <option value="{{ $year }}" {{ $filter->year == $year ? 'selected' : '' }}>{{ $year }}
                    </option>
                  @endfor
                </select>
              </div>
            </div>
            <div class="form-group row">
              <label class="col-form-label col-sm-4" for="month">Bulan</label>
              <div class="col-sm-8">
                <select class="custom-select mt-2" id="month" name="month">
                  <option value="1" {{ $filter->month == 1 ? 'selected' : '' }}>Januari</option>
                  <option value="2" {{ $filter->month == 2 ? 'selected' : '' }}>Februari</option>
                  <option value="3" {{ $filter->month == 3 ? 'selected' : '' }}>Maret</option>
                  <option value="4" {{ $filter->month == 4 ? 'selected' : '' }}>April</option>
                  <option value="5" {{ $filter->month == 5 ? 'selected' : '' }}>Mei</option>
                  <option value="6" {{ $filter->month == 6 ? 'selected' : '' }}>Juni</option>
                  <option value="7" {{ $filter->month == 7 ? 'selected' : '' }}>Juli</option>
                  <option value="8" {{ $filter->month == 8 ? 'selected' : '' }}>Agustus</option>
                  <option value="9" {{ $filter->month == 9 ? 'selected' : '' }}>September</option>
                  <option value="10" {{ $filter->month == 10 ? 'selected' : '' }}>Oktober</option>
                  <option value="11" {{ $filter->month == 11 ? 'selected' : '' }}>November</option>
                  <option value="12" {{ $filter->month == 12 ? 'selected' : '' }}>Desember</option>
                </select>
              </div>
            </div>
            <div class="form-group row">
              <label class="col-form-label col-sm-4" for="account_id">Akun</label>
              <div class="col-sm-8">
                <select class="custom-select mt-2" id="account_id" name="account_id">
                  <option value="0" {{ $filter->account_id == 0 ? 'selected' : '' }}>Semua Akun</option>
                  @foreach ($accounts as $account)
                    <option value="{{ $account->id }}" {{ $filter->account_id == $account->id ? 'selected' : '' }}>
                      {{ $account->name }}
                    </option>
                  @endforeach
                </select>
              </div>
            </div>
            <div class="form-group row">
              <label class="col-form-label col-sm-4" for="category_id">Kategori Transaksi</label>
              <div class="col-sm-8">
                <select class="custom-select mt-2" id="category_id" name="category_id">
                  <option value="0" {{ $filter->category_id == 0 ? 'selected' : '' }}>Semua Kategori</option>
                  @foreach ($categories as $category)
                    <option value="{{ $category->id }}" {{ $filter->category_id == $category->id ? 'selected' : '' }}>
                      {{ $category->name }}
                    </option>
                  @endforeach
                </select>
              </div>
            </div>
          </div>
          <div class="modal-footer justify-content-between">
            <button type="submit" class="btn btn-primary"><i class="fas fa-check mr-2"></i> Terapkan</button>
          </div>
        </div>
      </div>
    </div>
  </form>
  <div class="card">
    @include('admin._components.card-header', ['title' => 'Daftar Transaksi'])
    <div class="card-body">
      <div class="row">
        <div class="col-md-12">
          <table class="data-table display table table-bordered table-striped table-condensed center-th"
            style="width:100%">
            <thead>
              <tr>
                <th>Tanggal</th>
                <th>Akun</th>
                <th>Kategori</th>
                <th>Jumlah (Rp.)</th>
                <th style="width:40%;">Deskripsi</th>
                <th class="text-center" style="max-width:10%">Aksi</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($items as $item)
                <tr>
                  <td class="text-center">{{ format_date($item->datetime, 'dd-MM-yyyy HH:mm:ss') }}</td>
                  <td class="text-center">{{ $item->account->name }}</td>
                  <td class="text-center">{{ $item->category->name }}</td>
                  <td class="text-right text-{{ $item->amount > 0 ? 'success' : 'danger' }}">{{ $item->amount }}</td>
                  <td>{{ $item->description }}</td>
                  <td class="text-center">
                    <div class="btn-group">
                      <a href="{{ url("/admin/cash-transactions/edit/$item->id") }}" class="btn btn-default btn-sm">
                        <i class="fa fa-edit"></i>
                      </a>
                      <a onclick="return confirm('Hapus Biaya?')"
                        href="{{ url("/admin/cash-transactions/delete/$item->id") }}" class="btn btn-danger btn-sm">
                        <i class="fa fa-trash"></i>
                      </a>
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
        [0, 'desc']
      ];
      DATATABLES_OPTIONS.columnDefs = [{
        targets: 5,
        orderable: false,
      }, {
        targets: 3,
        render: $.fn.dataTable.render.number('.', ',', 0, ''),
      }];
      $('.data-table').DataTable(DATATABLES_OPTIONS);
    });
  </script>
@endsection
