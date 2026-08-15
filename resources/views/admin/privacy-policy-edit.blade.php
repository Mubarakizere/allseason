@extends('layouts.admin')

@section('title', 'Privacy Policy Editor — All The Season Garden')

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/summernote@0.9.0/dist/summernote-lite.min.css" rel="stylesheet">

<style>
    .prv-wrap {
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
    }

    /* Page Header */
    .prv-header {
        margin-bottom: 24px;
    }
    .prv-header h1 {
        font-size: 22px;
        font-weight: 700;
        color: #111827;
        margin: 0 0 4px;
        letter-spacing: -0.02em;
    }
    .prv-header p {
        font-size: 13px;
        color: #6b7280;
        margin: 0;
    }

    /* Card */
    .prv-card {
        background: #ffffff;
        border: 1px solid #e5e7eb;
        border-radius: 10px;
        overflow: hidden;
    }
    .prv-card-header {
        padding: 16px 20px;
        border-bottom: 1px solid #f3f4f6;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    .prv-card-title {
        font-size: 15px;
        font-weight: 700;
        color: #111827;
        margin: 0;
    }

    /* Summernote Editor Custom Style */
    .note-editor.note-frame {
        border: 1px solid #e5e7eb !important;
        border-radius: 8px !important;
        overflow: hidden;
    }
    .note-toolbar {
        background: #f9fafb !important;
        border-bottom: 1px solid #e5e7eb !important;
        padding: 6px 10px !important;
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/summernote@0.9.0/dist/summernote-lite.min.js"></script>

<script>
    $(document).ready(function() {
        $('#summernote').summernote({
            placeholder: 'Write your Privacy Policy content here...',
            tabsize: 2,
            height: 480,
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'italic', 'underline', 'clear']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['table', ['table']],
                ['insert', ['link']],
                ['view', ['codeview', 'help']]
            ]
        });
    });
</script>
@endpush

@section('content')
<div class="content-wrapper prv-wrap">
    
    @include('partials.message-bag')

    {{-- Page Header --}}
    <div class="prv-header">
        <h1>Privacy Policy Editor</h1>
        <p>Edit the official Privacy Policy published on the website.</p>
    </div>

    {{-- Editor Card --}}
    <div class="prv-card">
        <div class="prv-card-header">
            <h3 class="prv-card-title">Document Content</h3>
            <span class="badge bg-light text-secondary border font-weight-normal" style="font-size: 11.5px;">Live Public Page</span>
        </div>

        <form action="{{ route('admin.privacy-policy.update') }}" method="POST">
            @csrf
            <div class="card-body p-4">
                <div class="mb-3">
                    <textarea id="summernote" name="content" class="form-control" required>{{ old('content', $privacyPolicy->content ?? '') }}</textarea>
                </div>
            </div>

            <div class="card-footer bg-white border-top p-3 d-flex align-items-center justify-content-between">
                <button type="button" class="btn btn-light px-4" onclick="history.go(-1)">
                    <i class="fas fa-arrow-left me-1"></i> Back
                </button>
                <button type="submit" class="btn btn-danger px-4 font-weight-bold">
                    <i class="fas fa-save me-1"></i> Save Changes
                </button>
            </div>
        </form>
    </div>

</div>
@endsection