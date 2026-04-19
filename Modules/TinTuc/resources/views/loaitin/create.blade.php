@extends('layouts.master') 

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card mb-4 rounded-0 border-dark">
            <div class="card-header bg-white border-bottom border-dark rounded-0">
                <h4 class="mb-0 text-uppercase fw-bold">Thêm Loại Tin (Danh mục)</h4>
            </div>
            <div class="card-body">
                <form action="{{ route('loaitin.store') }}" method="POST">
                    @csrf 
                    <div class="mb-3">
                        <label class="fw-bold mb-1">Tên Loại Tin</label>
                        <input type="text" name="name" class="form-control rounded-0 border-dark" required>
                    </div>
                    
                    <div class="d-flex justify-content-between mt-4">
                        <a href="{{ route('tintuc.index') }}" class="btn btn-outline-dark rounded-0 px-4">Hủy</a>
                        <button type="submit" class="btn btn-dark rounded-0 px-4">Lưu Danh Mục</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection