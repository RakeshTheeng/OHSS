@extends('layouts.provider')

@section('title', 'Upload KYC Document')

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Upload KYC Document</h1>
        <a href="{{ route('provider.kyc.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i>Back to Documents
        </a>
    </div>

    <!-- Upload Form -->
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-upload me-2"></i>Document Upload
                    </h6>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('provider.kyc.store') }}" enctype="multipart/form-data">
                        @csrf
                        
                        <!-- Document Type -->
                        <div class="mb-3">
                            <label for="document_type" class="form-label">Document Type <span class="text-danger">*</span></label>
                            <select class="form-select @error('document_type') is-invalid @enderror" 
                                    id="document_type" 
                                    name="document_type" 
                                    required>
                                <option value="">Select Document Type</option>
                                <option value="citizenship" {{ old('document_type') === 'citizenship' ? 'selected' : '' }}>
                                    Citizenship Certificate
                                </option>
                                <option value="passport" {{ old('document_type') === 'passport' ? 'selected' : '' }}>
                                    Passport
                                </option>
                                <option value="driving_license" {{ old('document_type') === 'driving_license' ? 'selected' : '' }}>
                                    Driving License
                                </option>
                                <option value="voter_id" {{ old('document_type') === 'voter_id' ? 'selected' : '' }}>
                                    Voter ID Card
                                </option>
                            </select>
                            @error('document_type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Document Number -->
                        <div class="mb-3">
                            <label for="document_number" class="form-label">Document Number <span class="text-danger">*</span></label>
                            <input type="text" 
                                   class="form-control @error('document_number') is-invalid @enderror" 
                                   id="document_number" 
                                   name="document_number" 
                                   value="{{ old('document_number') }}"
                                   placeholder="Enter document number"
                                   required>
                            @error('document_number')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- File Upload -->
                        <div class="mb-4">
                            <label for="document_file" class="form-label">Document File <span class="text-danger">*</span></label>
                            <input type="file" 
                                   class="form-control @error('document_file') is-invalid @enderror" 
                                   id="document_file" 
                                   name="document_file" 
                                   accept=".pdf,.jpg,.jpeg,.png"
                                   required>
                            <div class="form-text">
                                Accepted formats: PDF, JPG, JPEG, PNG. Maximum file size: 5MB
                            </div>
                            @error('document_file')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- File Preview -->
                        <div id="file-preview" class="mb-3" style="display: none;">
                            <label class="form-label">Preview:</label>
                            <div class="border rounded p-3 text-center">
                                <img id="image-preview" src="" alt="Document Preview" class="img-fluid" style="max-height: 300px; display: none;">
                                <div id="pdf-preview" style="display: none;">
                                    <i class="fas fa-file-pdf fa-3x text-danger mb-2"></i>
                                    <p class="mb-0">PDF file selected</p>
                                </div>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="{{ route('provider.kyc.index') }}" class="btn btn-secondary me-md-2">
                                <i class="fas fa-times me-2"></i>Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-upload me-2"></i>Upload Document
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Guidelines Card -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card border-left-warning shadow">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Upload Guidelines
                            </div>
                            <div class="text-gray-900">
                                <ul class="mb-0">
                                    <li><strong>Image Quality:</strong> Ensure the document is clear and all text is readable</li>
                                    <li><strong>Lighting:</strong> Take photos in good lighting conditions</li>
                                    <li><strong>Full Document:</strong> Include the entire document in the image</li>
                                    <li><strong>No Glare:</strong> Avoid reflections or glare on the document</li>
                                    <li><strong>Correct Orientation:</strong> Make sure the document is right-side up</li>
                                    <li><strong>File Size:</strong> Keep files under 5MB for faster upload</li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-exclamation-triangle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('document_file').addEventListener('change', function(e) {
    const file = e.target.files[0];
    const preview = document.getElementById('file-preview');
    const imagePreview = document.getElementById('image-preview');
    const pdfPreview = document.getElementById('pdf-preview');
    
    if (file) {
        preview.style.display = 'block';
        
        if (file.type.startsWith('image/')) {
            const reader = new FileReader();
            reader.onload = function(e) {
                imagePreview.src = e.target.result;
                imagePreview.style.display = 'block';
                pdfPreview.style.display = 'none';
            };
            reader.readAsDataURL(file);
        } else if (file.type === 'application/pdf') {
            imagePreview.style.display = 'none';
            pdfPreview.style.display = 'block';
        }
    } else {
        preview.style.display = 'none';
    }
});
</script>

<style>
.border-left-warning {
    border-left: 0.25rem solid #f6c23e !important;
}
</style>
@endsection
