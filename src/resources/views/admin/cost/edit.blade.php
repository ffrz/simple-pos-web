<?php
  $title = ($item->id ? 'Edit' : 'Tambah') . ' Biaya';
?>

@extends('admin._layouts.default', [
    'title' => $title,
    'menu_active' => 'cost',
    'nav_active' => 'cost',
    'back_button_link' => url('/admin/costs/'),
])

@section('content')
  <div class="col-md-8">
    <div class="card card-light">
      <form class="form-horizontal quick-form" method="POST">
        @csrf
        @include('admin._components.card-header', [
            'title' => $title,
            'description' => 'Catat pengeluaran biaya operasional.',
        ])
        <div class="card-body">
          <div class="form-group row">
            <label for="date" class=" col-form-label col-sm-3">Tanggal</label>
            <div class="col-sm-3">
              <div class="input-group date" id="date" data-target-input="nearest">
                <input autofocus type="text"
                  class="form-control datetimepicker-input @error('date') is-invalid @enderror" data-target="#date"
                  name="date" value="{{ old('date', format_date($item->date)) }}" />
                <div class="input-group-append" data-target="#date" data-toggle="datetimepicker">
                  <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                </div>
              </div>
              @error('date')
                <span class="text-danger">{{ $message }}</span>
              @enderror
            </div>
          </div>
          <div class="form-group row">
            <label for="category_id" class="col-sm-3 col-form-label">Kategori</label>
            <div class="col-sm-9">
              <select class="custom-select select2 @error('category_id') is-invalid @enderror" id="category_id"
                name="category_id">
                <option value="" {{ !$item->category_id ? 'selected' : '' }}>-- Kategori --</option>
                @foreach ($categories as $category)
                  <option value="{{ $category->id }}" {{ $item->category_id == $category->id ? 'selected' : '' }}>
                    {{ $category->name }}
                  </option>
                @endforeach
              </select>
              @error('category_id')
                <span class="text-danger">{{ $message }}</span>
              @enderror
            </div>
          </div>
          <div class="form-group row">
            <label for="description" class="col-sm-3 col-form-label">Deskripsi</label>
            <div class="col-sm-9">
              <input type="text" class="form-control @error('description') is-invalid @enderror" id="description"
                placeholder="Deskripsi" name="description" value="<?= e($item->description) ?>">
              @error('description')
                <span class="text-danger">{{ $message }}</span>
              @enderror
            </div>
          </div>
          <div class="form-group row">
            <label for="amount" class="col-sm-3 col-form-label">Jumlah Biaya (Rp.)</label>
            <div class="col-sm-3">
              <input type="text" class="form-control text-right @error('amount') is-invalid @enderror" id="amount"
                name="amount" value="{{ old('amount', $item->amount) }}" inputmode="decimal">
              @error('amount')
                <span class="text-danger">{{ $message }}</span>
              @enderror
            </div>
          </div>
        </div>
        <div class="card-footer">
          <button type="submit" class="btn btn-primary"><i class="fas fa-save mr-1"></i> Simpan</button>
        </div>
      </form>
    </div>
  </div>
@endSection
@section('footscript')
  <script>
    $(document).ready(function() {
      $('.date').datetimepicker({
        format: 'DD-MM-YYYY'
      });
      $('.select2').select2();
      $('input').inputmask();
      $(document).on('select2:open', () => {
        document.querySelector('.select2-search__field').focus();
      });
    });
  </script>
@endSection
