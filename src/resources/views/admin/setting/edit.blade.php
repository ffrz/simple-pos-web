@extends('admin._layouts.default', [
    'title' => 'Pengaturan',
    'menu_active' => 'system',
    'nav_active' => 'settings',
])
@section('content')
<div class="card card-light">
    <form class="form-horizontal quick-form" method="POST" action="{{ url('admin/settings/save') }}">
        @include('admin._components.card-header', ['title' => 'Pengaturan'])
        <div class="card-body">
            @csrf
            <div class="form-group row">
                <label for="store_name" class="col-sm-2 col-form-label">Nama Toko</label>
                <div class="col-sm-10">
                    <input type="text" autofocus class="form-control @error('store_name') is-invalid @enderror"
                        id="store_name" placeholder="Nama Toko" name="store_name" value="{{ $data['store_name'] }}">
                </div>
                @error('store_name')
                    <span class="offset-sm-2 col-sm-10 error form-error">
                        {{ $message }}
                    </span>
                @enderror
            </div>
            <div class="form-group row">
                <label for="store_address   " class="col-sm-2 col-form-label">Alamat</label>
                <div class="col-sm-10">
                    <textarea class="form-control" id="store_address" name="store_address">{{ $data['store_address'] }}</textarea>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-primary"><i class="fas fa-save mr-2"></i> Simpan</button>
        </div>
    </form>
</div>
@endSection