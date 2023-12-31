@extends('admin._layouts.default', [
    'title' => 'Biaya Operasional',
    'menu_active' => 'cost',
    'nav_active' => 'cost',
])

@section('right-menu')
  <li class="nav-item">
    <a href="{{ url('/admin/costs/edit/0') }}" class="btn plus-btn btn-primary mr-2" title="Baru"><i
        class="fa fa-plus"></i></a>
    <button class="btn btn-default plus-btn mr-2" data-toggle="modal" data-target="#modal-sm" title="Saring"><i
        class="fa fa-filter"></i></button>
  </li>
@endSection

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
          </div>
          <div class="modal-footer justify-content-between">
            <button type="submit" class="btn btn-primary"><i class="fas fa-check mr-2"></i> Terapkan</button>
          </div>
        </div>
      </div>
    </div>
  </form>
  <div class="card card-light">
    @include('admin._components.card-header', [
        'title' => 'Biaya Operasional - ' . month_names($filter->month) . ' ' . $filter->year,
    ])
    <div class="card-body">
      <div class="row">
        <div class="col-md-12">
          <table class="data-table display table table-bordered table-striped table-condensed center-th"
            style="width:100%">
            <thead>
              <tr>
                <th>Tanggal</th>
                <th>Kategori</th>
                <th>Biaya (Rp.)</th>
                <th style="width:40%;">Deskripsi</th>
                <th class="text-center">Aksi</th>
              </tr>
            </thead>
            <tbody>
              <?php $total = 0; ?>
              @foreach ($items as $item)
                <?php $total += $item->amount; ?>
                <tr>
                  <td class="text-center">{{ format_date($item->date) }}</td>
                  <td class="text-center">{{ $item->category->name }}</td>
                  <td class="text-right">{{ $item->amount }}</td>
                  <td>{{ $item->description }}</td>
                  <td class="text-center">
                    <div class="btn-group">
                      <a href="{{ url("/admin/costs/edit/$item->id") }}" class="btn btn-default btn-sm"><i
                          class="fa fa-edit"></i></a>
                      <a onclick="return confirm('Hapus Biaya?')" href="{{ url("/admin/costs/delete/$item->id") }}"
                        class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></a>
                    </div>
                  </td>
                </tr>
              @endforeach
            </tbody>
            <tfoot>
              <tr>
                <th class="text-right" colspan="2">Total (Rp.)</th>
                <th class="text-right">{{ format_number($total) }}</th>
                <th></th>
                <th></th>
              </tr>
            </tfoot>
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
        },
        {
          render: $.fn.dataTable.render.number('.', ',', 0, ''),
          targets: 2
        }
      ];
      $('.data-table').DataTable(DATATABLES_OPTIONS);
    });
  </script>
@endSection
