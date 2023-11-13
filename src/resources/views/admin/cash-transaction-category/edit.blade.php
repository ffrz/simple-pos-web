<?php
  $title = ($item->id ? 'Edit' : 'Tambah') . ' Kategori Transaksi';
?>

@extends('admin._layouts.default', [
    'title' => $title,
    'menu_active' => 'finance',
    'nav_active' => 'cash-transaction-category',
    'back_button_link' => url('/admin/cash-transaction-categories'),
])

@section('content')
  <div class="col-md-8">
    <div class="card card-light">
      <form class="form-horizontal quick-form" method="POST" action="{{ url('/admin/cash-transaction-categories/edit/' . (int)$item->id) }}">
        @csrf
        @include('admin._components.card-header', ['title' => $title])
        <div class="card-body">
          <div class="form-group row">
            <label for="name" class="col-sm-3 col-form-label">Nama Kategori</label>
            <div class="col-sm-9">
              <input type="text" class="form-control @error('name') is-invalid @enderror" autofocus id="name"
                placeholder="Nama kategori transaksi" name="name" value="{{ old('name', $item->name) }}">
              @error('name')
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
