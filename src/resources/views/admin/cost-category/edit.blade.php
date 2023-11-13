<?php
  $title = ($item->id ? 'Edit' : 'Tambah') . ' Kategori Biaya';
?>

@extends('admin._layouts.default', [
    'title' => $title,
    'menu_active' => 'cost',
    'nav_active' => 'cost-category',
    'back_button_link' => url('/admin/cost-categories/'),
])

@section('content')
  <div class="col-md-8">
    <div class="card card-light">
      <form class="form-horizontal quick-form" method="POST" action="{{ url('/admin/cost-categories/edit/' . (int)$item->id) }}">
        @csrf
        @include('admin._components.card-header', ['title' => $title])
        <div class="card-body">
          <div class="form-group row">
            <label for="name" class="col-sm-3 col-form-label">Nama Kategori</label>
            <div class="col-sm-9">
              <input type="text" class="form-control @error('name') is-invalid @enderror" autofocus id="name"
                placeholder="Nama kategori biaya" name="name" value="{{ old('name', $item->name) }}">
              @error('name')
                <span class="text-danger">{{ $message }}</span>
              @enderror
            </div>
          </div>
          <div class="form-group row">
            <label for="description" class="col-sm-3 col-form-label">Deskripsi</label>
            <div class="col-sm-9">
              <input type="text" class="form-control @error('description') is-invalid @enderror" autofocus id="description"
                placeholder="Deskripsi kategori biaya" name="description" value="{{ old('description', $item->description) }}">
              @error('description')
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
