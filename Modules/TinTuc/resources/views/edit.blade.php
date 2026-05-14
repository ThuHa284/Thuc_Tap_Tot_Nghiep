@extends('layouts.master') 

@section('content')
{{-- Menu ngang --}}
<div class="row">
    <div class="col-12">
        @include('tintuc::components.tintuc-menu')
    </div>
</div>

{{-- Breadcrumb --}}
<nav aria-label="breadcrumb" class="mb-4">
    <ol class="breadcrumb bg-white shadow-sm rounded-3 px-3 py-2 mb-0">
        <li class="breadcrumb-item"><a href="/" class="text-decoration-none text-secondary"><i class="fas fa-home"></i></a></li>
        <li class="breadcrumb-item"><a href="{{ route('tintuc.index') }}" class="text-decoration-none text-secondary">Tin Tức</a></li>
        <li class="breadcrumb-item active text-dark fw-medium" aria-current="page">Chỉnh sửa</li>
    </ol>
</nav>

<div class="row justify-content-center">
    <div class="col-lg-10">
        <div class="card border-0 shadow-lg rounded-3">
            <div class="card-header bg-warning text-dark border-0 py-4">
                <h4 class="mb-0 fw-bold">
                    <i class="fas fa-edit me-2"></i>Cập nhật Tin Tức
                </h4>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('tintuc.update', $tinTuc->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT') 
                    
                    <div class="row g-4">
                        <div class="col-md-8">
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control rounded-3 border-0 shadow-sm @error('title') is-invalid @enderror" 
                                       name="title" id="title" placeholder="Tiêu đề" value="{{ old('title', $tinTuc->title) }}" required>
                                <label for="title"><i class="fas fa-heading me-2 text-muted"></i>Tiêu đề tin tức</label>
                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="form-floating mb-3">
                                        <select class="form-select rounded-3 border-0 shadow-sm @error('loaitin_id') is-invalid @enderror" 
                                                name="loaitin_id" id="loaitin" required>
                                            <option value="">-- Chọn loại tin --</option>
                                            @foreach($loaiTins as $loai)
                                                <option value="{{ $loai->id }}" {{ (old('loaitin_id', $tinTuc->loaitin_id) == $loai->id) ? 'selected' : '' }}>
                                                    {{ $loai->name }} 
                                                </option>
                                            @endforeach
                                        </select>
                                        <label for="loaitin"><i class="fas fa-folder me-2 text-muted"></i>Loại tin</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating mb-3">
                                        <select name="status" class="form-select rounded-3 border-0 shadow-sm" id="status">
                                            <option value="1" {{ old('status', $tinTuc->status) == '1' ? 'selected' : '' }}>Hiển thị</option>
                                            <option value="0" {{ old('status', $tinTuc->status) == '0' ? 'selected' : '' }}>Ẩn</option>
                                        </select>
                                        <label for="status"><i class="fas fa-toggle-on me-2 text-muted"></i>Trạng thái</label>
                                    </div>
                                </div>
                            </div>

                            <div class="form-floating mb-3">
                                <input type="date" class="form-control rounded-3 border-0 shadow-sm" 
                                       name="date1" id="date1" 
                                       value="{{ old('date1', $tinTuc->date1 ? \Carbon\Carbon::parse($tinTuc->date1)->format('Y-m-d') : '') }}">
                                <label for="date1"><i class="fas fa-calendar-alt me-2 text-muted"></i>Ngày diễn ra</label>
                            </div>

                            <div class="card border-0 bg-light rounded-3 p-3 mt-3">
                                <label class="form-label fw-bold mb-3">
                                    <i class="fas fa-paperclip me-2 text-muted"></i>Tệp đính kèm
                                </label>
                                @if(!empty($tinTuc->attachment_path))
                                    <div class="alert alert-info py-2 px-3 mb-3">
                                        <i class="fas fa-file-alt me-1"></i>
                                        {{ $tinTuc->attachment_display_label ? $tinTuc->attachment_display_label . ' - ' : '' }}Tệp hiện tại:
                                        <a href="{{ asset($tinTuc->attachment_path) }}" target="_blank" class="fw-semibold text-decoration-none">Xem chi tiết</a>
                                    </div>
                                @endif
                                <input type="text" class="form-control rounded-3 mb-2 @error('attachment_label') is-invalid @enderror" name="attachment_label" value="{{ old('attachment_label', $tinTuc->attachment_label) }}" placeholder="Nhập chữ hiển thị trước tệp đính kèm">
                                <input type="file" class="form-control rounded-3 @error('attachment') is-invalid @enderror" name="attachment" accept=".pdf,.xls,.xlsx,.csv,.doc,.docx">
                                <small class="text-muted mt-2 d-block">Chọn file mới để thay thế tệp đính kèm hiện tại</small>
                                <small class="text-muted d-block">Ví dụ: Quy định, Mẫu đơn, Danh sách</small>
                                @error('attachment_label')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                                @error('attachment')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="card border-0 bg-light rounded-3 p-3 mt-3">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <label class="form-label fw-bold mb-0">
                                        <i class="fas fa-layer-group me-2 text-muted"></i>Tệp đính kèm bổ sung
                                    </label>
                                    <button type="button" class="btn btn-sm btn-outline-primary rounded-pill" onclick="addExtraAttachment()">Thêm tệp</button>
                                </div>
                                <div id="extraAttachmentsContainer" class="d-grid gap-3">
                                    @foreach(old('extra_attachments', $tinTuc->attachments ?? []) as $index => $extraAttachment)
                                        <div class="border rounded-3 bg-white p-3">
                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                <strong class="text-dark small">Tệp bổ sung</strong>
                                                <button type="button" class="btn btn-sm btn-outline-danger rounded-pill" onclick="this.closest('.border').remove()">Xóa</button>
                                            </div>
                                            <input type="text" name="extra_attachments[{{ $index }}][label]" class="form-control rounded-3 mb-2" placeholder="Nhãn hiển thị" value="{{ $extraAttachment['label'] ?? '' }}">
                                            @if(!empty($extraAttachment['path']))
                                                <input type="hidden" name="extra_attachments[{{ $index }}][existing_path]" value="{{ $extraAttachment['path'] }}">
                                                <input type="hidden" name="extra_attachments[{{ $index }}][existing_name]" value="{{ $extraAttachment['name'] ?? '' }}">
                                                <div class="alert alert-info py-2 px-3 mb-2">
                                                    <a href="{{ asset($extraAttachment['path']) }}" target="_blank" class="fw-semibold text-decoration-none">{{ $extraAttachment['label'] ?? 'Tệp bổ sung' }} - Xem chi tiết</a>
                                                </div>
                                            @endif
                                            <input type="file" name="extra_attachments[{{ $index }}][file]" class="form-control rounded-3" accept=".pdf,.xls,.xlsx,.csv,.doc,.docx">
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="card border-0 bg-light rounded-3 p-3">
                                <label class="form-label fw-bold mb-3">
                                    <i class="fas fa-image me-2 text-muted"></i>Hình ảnh đại diện
                                </label>
                                <div class="text-center mb-3" id="imagePreviewContainer">
                                    @if(!empty($tinTuc->img))
                                        <div class="bg-white rounded-3 p-2 border">
                                            <img src="{{ asset($tinTuc->img) }}" class="img-fluid rounded-2" style="max-height: 200px;" alt="Ảnh hiện tại">
                                            <p class="text-success small mt-2 mb-0"><i class="fas fa-check-circle me-1"></i>Ảnh hiện tại</p>
                                        </div>
                                    @else
                                        <div class="bg-white rounded-3 p-4 border-2 border-dashed">
                                            <i class="fas fa-cloud-upload-alt fa-4x text-muted" id="uploadIcon"></i>
                                            <p class="text-muted small mt-2 mb-0">Chọn ảnh mới</p>
                                        </div>
                                    @endif
                                </div>
                                <input type="file" class="form-control rounded-3 @error('img') is-invalid @enderror" 
                                       name="img" accept="image/*" id="imgInput" onchange="previewImage(this)">
                                <small class="text-muted mt-2 d-block">Chọn file mới để thay đổi ảnh</small>
                                @error('img')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="card border-0 shadow-sm rounded-3 mt-4">
                        <div class="card-header bg-white border-bottom py-3">
                            <h5 class="mb-0 fw-bold text-dark"><i class="fas fa-align-left me-2 text-primary"></i>Nội dung bài viết</h5>
                        </div>
                        <div class="card-body p-3 p-md-4">
                            <textarea name="content" id="content" class="form-control rounded-3 @error('content') is-invalid @enderror" rows="10" placeholder="Nhập nội dung bài viết...">{{ old('content', $tinTuc->content) }}</textarea>
                            @error('content')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <hr class="my-4">
                    
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('tintuc.index') }}" class="btn btn-outline-secondary rounded-pill px-4">
                            <i class="fas fa-arrow-left me-1"></i> Quay lại
                        </a>
                        <button type="submit" class="btn btn-warning text-dark rounded-pill px-5">
                            <i class="fas fa-save me-1"></i> Lưu Thay đổi
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
    .btn-warning {
        background: linear-gradient(135deg, #f6d365 0%, #fda085 100%);
        border: none;
        color: #333;
    }
    .btn-warning:hover {
        background: linear-gradient(135deg, #f5c55a 0%, #fc9358 100%);
        color: #333;
    }
    .form-floating > .form-control:focus,
    .form-floating > .form-control:not(:placeholder-shown) {
        padding-top: 1.625rem;
        padding-bottom: 0.625rem;
    }
    .form-floating > label {
        padding: 1rem 1rem;
    }
</style>

<script>
function previewImage(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('imagePreviewContainer').innerHTML = 
                '<div class="bg-white rounded-3 p-2 border"><img src="' + e.target.result + '" class="img-fluid rounded-2" style="max-height: 200px;"></div>';
        }
        reader.readAsDataURL(input.files[0]);
    }
}

let extraAttachmentIndex = document.querySelectorAll('#extraAttachmentsContainer .border').length;

function addExtraAttachment(existing = {}) {
    const container = document.getElementById('extraAttachmentsContainer');
    const index = extraAttachmentIndex++;
    const wrapper = document.createElement('div');
    wrapper.className = 'border rounded-3 bg-white p-3';
    wrapper.innerHTML = `
        <div class="d-flex justify-content-between align-items-center mb-2">
            <strong class="text-dark small">Tệp bổ sung</strong>
            <button type="button" class="btn btn-sm btn-outline-danger rounded-pill" onclick="this.closest('.border').remove()">Xóa</button>
        </div>
        <input type="text" name="extra_attachments[${index}][label]" class="form-control rounded-3 mb-2" placeholder="Nhãn hiển thị" value="${existing.label || ''}">
        ${existing.path ? `<input type="hidden" name="extra_attachments[${index}][existing_path]" value="${existing.path}"><input type="hidden" name="extra_attachments[${index}][existing_name]" value="${existing.name || ''}"><div class="alert alert-info py-2 px-3 mb-2"><a href="/${existing.path}" target="_blank" class="fw-semibold text-decoration-none">${existing.label || 'Tệp hiện tại'} - Xem chi tiết</a></div>` : ''}
        <input type="file" name="extra_attachments[${index}][file]" class="form-control rounded-3" accept=".pdf,.xls,.xlsx,.csv,.doc,.docx">
    `;
    container.appendChild(wrapper);
}
</script>
@endsection
