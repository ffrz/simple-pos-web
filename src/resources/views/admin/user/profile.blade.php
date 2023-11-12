@extends('admin._layouts.default', [
    'title' => 'Profil Saya',
    'nav_active' => 'profile',
])

@section('content')
  <div class="card card-light">
    <form class="form-horizontal quick-form" method="POST" action="{{ url('admin/users/profile') }}">
      @include('admin._components.card-header', ['title' => 'Profil Saya'])
      <div class="card-body">
        @csrf
        <div class="form-row">
          <div class="form-group col-md-4">
            <label for="username">ID Pengguna</label>
            <input type="text" class="form-control @error('username') 'is-invalid' @enderror" id="username" readonly
              value="{{ $user->username }}">
          </div>
          <div class="form-group col-md-4">
            <label for="name">Nama Lengkap</label>
            <input type="text" class="form-control @error('name') 'is-invalid' @enderror" autofocus id="name"
              placeholder="Nama Lengkap" name="name" value="{{ old('name', $user->name) }}">
            @error('name')
              <span class="text-danger">{{ $message }}</span>
            @enderror
          </div>
        </div>
        <div class="form-row">
          <div class="form-group col-md-4">
            <label for="password">Kata Sandi <span class="text-muted">(Isi untuk mengganti kata sandi.)</span></label>
            <input type="password" class="form-control" id="password" name="password" value="{{ old('password') }}">
            @error('password')
              <span class="text-danger">{{ $message }}</span>
            @enderror
          </div>
          <div class="form-group col-md-4">
            <label for="password_confirmation">Ulangi Kata Sandi<span class="text-muted"></span></label>
            <input type="password" class="form-control @error('password_confirmation') 'is-invalid' @enderror"
              id="password_confirmation" placeholder="Kata Sandi" name="password_confirmation"
              value="{{ old('password_confirmation') }}">
            @error('password_confirmation')
              <span class="text-danger">{{ $message }}</span>
            @enderror
          </div>
        </div>
        <div class="form-row">
          <div class="form-group col-md-2">
            <div class="custom-control custom-checkbox">
              <input disabled type="checkbox" class="custom-control-input " id="active"
                {{ $user->is_active ? 'checked="checked"' : '' }}>
              <label class="custom-control-label" for="active">Aktif</label>
            </div>
          </div>
          <div class="form-group col-md-2">
            <div class="custom-control custom-checkbox">
              <input disabled type="checkbox" class="custom-control-input " id="is_admin"
                {{ $user->is_admin ? 'checked="checked"' : '' }}>
              <label class="custom-control-label" for="is_admin">Administrator</label>
            </div>
          </div>
        </div>
      </div>
      <div class="card-footer">
        <div>
          <button type="submit" class="btn btn-primary"><i class="fas fa-save mr-1"></i> Simpan</button>
        </div>
      </div>
    </form>
  </div>
  </div>
@endsection
