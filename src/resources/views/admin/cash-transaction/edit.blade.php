<?php
$title = ($item->id ? 'Edit' : 'Tambah') . ' Transaksi Keuangan';
?>

@extends('admin._layouts.default', [
    'title' => $title,
    'menu_active' => 'finance',
    'nav_active' => 'cash-transaction',
    'back_button_link' => url('/admin/cash-transactions'),
])

@section('content')
  <div class="col-md-8">
    <div class="card card-light">
      <form class="form-horizontal quick-form" method="POST">
        @csrf
        @include('admin._components.card-header', ['title' => $title])
        <div class="card-body">
          <div class="form-group row">
            <label for="datetime" class=" col-form-label col-sm-3">Tanggal</label>
            <div class="col-sm-3">
              <div class="input-group date" id="datetime" data-target-input="nearest">
                <input autofocus type="text"
                  class="form-control datetimepicker-input @error('datetime') is-invalid @enderror"
                  data-target="#datetime" name="datetime" value="{{ old('datetime', format_date($item->datetime)) }}" />
                <div class="input-group-append" data-target="#datetime" data-toggle="datetimepicker">
                  <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                </div>
              </div>
              @error('datetime')
                <span class="text-danger">{{ $message }}</span>
              @enderror
            </div>
          </div>
          <div class="form-group row">
            <label for="account_id" class="col-sm-3 col-form-label">Akun / Rekening</label>
            <div class="col-sm-9">
              <select class="custom-select select2 @error('account_id') is-invalid @enderror" id="account_id"
                name="account_id">
                <option value="" {{ !$item->account_id ? 'selected' : '' }}>-- Akun / Rek --</option>
                @foreach ($accounts as $account)
                  <option value="{{ $account->id }}"
                    {{ old('account_id', $item->account_id) == $account->id ? 'selected' : '' }}>
                    {{ $account->name }}
                  </option>
                @endforeach
              </select>
              @error('account_id')
                <span class="text-danger">{{ $message }}</span>
              @enderror
            </div>
          </div>
          <div class="form-group row">
            <label for="category_id" class="col-sm-3 col-form-label">Kategori</label>
            <div class="col-sm-9">
              <select class="custom-select select2" id="category_id" name="category_id">
                <option value="" {{ !$item->category_id ? 'selected' : '' }}>-- Kategori --</option>
                @foreach ($categories as $category)
                  <option value=" {{ $category->id }}"
                    {{ old('category_id', $item->category_id) == $category->id ? 'selected' : '' }}>
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
                placeholder="Deskripsi" name="description" value="{{ old('description', $item->description) }}">
              @error('description')
                <span class="text-danger">{{ $message }}</span>
              @enderror
            </div>
          </div>
          <div class="form-group row">
            <label for="type" class="col-sm-3 col-form-label">Jenis</label>
            <div class="col-sm-9">
              <div class="form-group clearfix">
                <div class="icheck-primary d-inline mr-3">
                  <input type="radio" id="type1" name="type" value="income" {{ $item->amount >= 0 ? 'checked' : '' }}>
                  <label for="type1" style="font-weight: normal">
                    Pemasukan (+)
                  </label>
                </div>
                <div class="icheck-primary d-inline">
                  <input type="radio" id="type2" name="type" value="expense" {{ $item->amount < 0 ? 'checked' : '' }}>
                  <label for="type2" style="font-weight: normal">
                    Pengeluaran (-)
                  </label>
                </div>
              </div>
            </div>
          </div>
          <div class="form-group row">
            <label for="amount" class="col-sm-3 col-form-label">Jumlah (Rp.) </label>
            <div class="col-sm-3">
              <input type="number" class="form-control text-right @error('amount') is-invalid @enderror" id="amount"
                name="amount" value="{{ old('amount', abs($item->amount)) }}" min="0">
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
@endsection
@section('footscript')
  <script>
    $(document).ready(function() {
      $('.date').datetimepicker({
        format: 'DD-MM-YYYY'
      });
      $('.select2').select2();
      $(document).on('select2:open', () => {
        document.querySelector('.select2-search__field').focus();
      });
    });
  </script>
@endsection
