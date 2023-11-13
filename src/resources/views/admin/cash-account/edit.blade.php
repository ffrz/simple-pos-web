<?php

use App\Models\CashAccount;

$title = ($item->id ? 'Edit' : 'Tambah') . ' Akun / Rekening';

?>

@extends('admin._layouts.default', [
    'title' => $title,
    'menu_active' => 'finance',
    'nav_active' => 'cash-account',
    'back_button_link' => url('admin/cash-accounts/'),
])

@section('content')
  <div class="col-md-8">
    <div class="card card-light">
      <form class="form-horizontal quick-form" method="POST"
        action="{{ url('admin/cash-accounts/edit/' . (int) $item->id) }}">
        @csrf
        @include('admin._components.card-header', ['title' => $title])
        <div class="card-body">
          <div class="form-group row">
            <label for="type" class="col-sm-3 col-form-label">Jenis Akun</label>
            <div class="col-sm-9">
              <select name="type" id="type" class="form-control @error('type') is-invalid @enderror">
                <option value="{{ CashAccount::ACCOUNT_TYPE_CASH }}">{{ CashAccount::typeNames(CashAccount::ACCOUNT_TYPE_CASH) }}</option>
                <option value="{{ CashAccount::ACCOUNT_TYPE_BANK }}">{{ CashAccount::typeNames(CashAccount::ACCOUNT_TYPE_BANK) }}</option>
              </select>
              @error('type')
                <span class="text-danger">{{ $message }}</span>
              @enderror
            </div>
          </div>
          <div class="form-group row">
            <label for="name" class="col-sm-3 col-form-label">Nama Akun</label>
            <div class="col-sm-9">
              <input type="text" class="form-control @error('name') is-invalid @enderror" autofocus id="name"
                placeholder="Nama Akun / Rekening" name="name" value="{{ $item->name }}">
              @error('name')
                <span class="text-danger">{{ $message }}</span>
              @enderror
            </div>
          </div>
          <div class="form-group row">
            <label for="balance" class="col-sm-3 col-form-label">Saldo</label>
            <div class="col-sm-3">
              <input type="number" step="1"
                class="form-control text-right select-all-on-focus @error('balance') is-invalid @enderror"
                id="balance" name="balance" value="{{ (int)$item->balance }}">
            </div>
            @error('balance')
              <span class="text-danger">{{ $message }}</span>
            @enderror
          </div>
        </div>
        <div class="card-footer">
          <button type="submit" class="btn btn-primary"><i class="fas fa-save mr-1"></i> Simpan</button>
        </div>
      </form>
    </div>
  </div>
@endsection
