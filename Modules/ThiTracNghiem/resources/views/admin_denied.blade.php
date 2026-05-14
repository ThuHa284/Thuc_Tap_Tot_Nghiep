@extends('layouts.master')

@section('title', 'Thi trắc nghiệm')

@section('content')
<div class="container mt-5">
    <div class="alert alert-warning" role="alert">
        <h4 class="alert-heading">Thông báo</h4>
        <p>Hiện tại module Thi trắc nghiệm không khả dụng cho tài khoản Admin. Vui lòng quay lại sau!</p>
        <hr>
        <a href="{{ url('/') }}" class="btn btn-primary">← Quay lại trang chủ</a>
    </div>
</div>
@endsection