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
        <li class="breadcrumb-item active text-dark fw-medium" aria-current="page">Thêm Mới</li>
    </ol>
</nav>

<div class="row justify-content-center">
    <div class="col-lg-10">
        <div class="card border-0 shadow-lg rounded-3">
            <div class="card-header bg-gradient text-white border-0 py-4">
                <h4 class="mb-0 fw-bold">
                    <i class="fas fa-plus-circle me-2"></i>Thêm Tin Tức Mới
                </h4>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('tintuc.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf 
                    
                    <div class="row g-4">
                        <div class="col-md-8">
                            <div class="form-floating mb-3">
                                <input type="text" name="title" class="form-control rounded-3 border-0 shadow-sm @error('title') is-invalid @enderror" 
                                       id="title" placeholder="Tiêu đề tin tức" value="{{ old('title') }}" required>
                                <label for="title"><i class="fas fa-heading me-2 text-muted"></i>Tiêu đề tin tức</label>
                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="form-floating mb-3">
                                        <select name="loaitin_id" class="form-select rounded-3 border-0 shadow-sm @error('loaitin_id') is-invalid @enderror" 
                                                id="loaitin" required>
                                            <option value="">-- Chọn loại tin --</option>
                                            @foreach($loaiTins as $loai)
                                                <option value="{{ $loai->id }}" {{ old('loaitin_id') == $loai->id ? 'selected' : '' }}>{{ $loai->name }}</option>
                                            @endforeach
                                        </select>
                                        <label for="loaitin"><i class="fas fa-folder me-2 text-muted"></i>Loại Tin</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating mb-3">
                                        <select name="status" class="form-select rounded-3 border-0 shadow-sm" id="status">
                                            <option value="1" {{ old('status', '1') == '1' ? 'selected' : '' }}>Hiển thị</option>
                                            <option value="0" {{ old('status') == '0' ? 'selected' : '' }}>Ẩn</option>
                                        </select>
                                        <label for="status"><i class="fas fa-toggle-on me-2 text-muted"></i>Trạng thái</label>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="form-floating mb-3">
                                <input type="date" name="date1" class="form-control rounded-3 border-0 shadow-sm" id="date1" value="{{ old('date1') }}">
                                <label for="date1"><i class="fas fa-calendar-alt me-2 text-muted"></i>Ngày diễn ra (nếu có)</label>
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="card border-0 bg-light rounded-3 p-3">
                                <label class="form-label fw-bold mb-3">
                                    <i class="fas fa-image me-2 text-muted"></i>Hình ảnh đại diện
                                </label>
                                <div class="text-center mb-3" id="imagePreviewContainer">
                                    <div class="bg-white rounded-3 p-4 border-2 border-dashed">
                                        <i class="fas fa-cloud-upload-alt fa-4x text-muted" id="uploadIcon"></i>
                                        <p class="text-muted small mt-2 mb-0">Click để chọn ảnh</p>
                                    </div>
                                </div>
                                <input type="file" name="img" class="form-control rounded-3 @error('img') is-invalid @enderror" 
                                       accept="image/*" id="imgInput" onchange="previewImage(this)">
                                <small class="text-muted mt-2 d-block">JPG, PNG, GIF, WEBP (tối đa 2MB)</small>
                                @error('img')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="card border-0 bg-light rounded-3 p-3 mt-3">
                                <label class="form-label fw-bold mb-3">
                                    <i class="fas fa-paperclip me-2 text-muted"></i>Tệp đính kèm
                                </label>
                                <input type="text" name="attachment_label" class="form-control rounded-3 mb-2 @error('attachment_label') is-invalid @enderror" value="{{ old('attachment_label') }}" placeholder="Nhập chữ hiển thị trước tệp đính kèm">
                                <input type="file" name="attachment" class="form-control rounded-3 @error('attachment') is-invalid @enderror" accept=".pdf,.xls,.xlsx,.csv,.doc,.docx">
                                <small class="text-muted mt-2 d-block">PDF, XLS, XLSX, CSV, DOC, DOCX (tối đa 10MB)</small>
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
                                <div id="extraAttachmentsContainer" class="d-grid gap-3"></div>
                            </div>
                        </div>
                    </div>

                    <div class="card border-0 shadow-sm rounded-3 mt-4">
                        <div class="card-header bg-white border-bottom py-3">
                            <h5 class="mb-0 fw-bold text-dark"><i class="fas fa-align-left me-2 text-primary"></i>Nội dung bài viết</h5>
                        </div>
                        <div class="card-body p-3 p-md-4">
                            <textarea name="content" id="content" class="form-control rounded-3 @error('content') is-invalid @enderror" rows="10" placeholder="Nhập nội dung bài viết...">{{ old('content') }}</textarea>
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
                        <button type="submit" class="btn btn-primary rounded-pill px-5">
                            <i class="fas fa-save me-1"></i> Lưu Tin Tức
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
    .bg-gradient {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
    }
    .btn-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
        color: white;
    }
    .btn-primary:hover {
        background: linear-gradient(135deg, #5a6fd6 0%, #6a4190 100%);
        color: white;
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

let extraAttachmentIndex = 0;

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

<script>
const usedDeclarationSemesters = JSON.parse(document.getElementById('usedDeclarationSemestersData')?.textContent || '{}');

function getYearFromDeclarationStart() {
    const value = document.getElementById('khai_bao_start_at')?.value;
    return value ? value.substring(0, 4) : null;
}

function syncSemesterOptions() {
    const year = getYearFromDeclarationStart();
    const semesterSelect = document.getElementById('khai_bao_ky');
    if (!semesterSelect) return;

    Array.from(semesterSelect.options).forEach(option => {
        if (!option.value) {
            option.disabled = false;
            return;
        }

        const usedSemesters = year ? (usedDeclarationSemesters[year] || []) : [];
        option.disabled = usedSemesters.includes(Number(option.value));
    });
}

// Neu co query param type=khai_bao, tu dong bat checkbox va set required
document.addEventListener('DOMContentLoaded', function() {
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.get('type') === 'khai_bao') {
        const checkbox = document.getElementById('is_khai_bao_noi_tru');
        if (checkbox) {
            checkbox.checked = true;
        }
        const kySelect = document.getElementById('khai_bao_ky');
        if (kySelect) {
            kySelect.setAttribute('required', 'required');
        }
    }
    syncSemesterOptions();
});

document.getElementById('khai_bao_start_at')?.addEventListener('change', syncSemesterOptions);
@endsection
