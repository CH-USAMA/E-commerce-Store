@extends('layouts.admin')

@section('title', 'Add New Store')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm border-0">
                <div class="card-body p-4">
                    <form action="{{ route('admin.stores.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Store Name</label>
                                <input type="text" name="name" id="store_name" class="form-control" required
                                    placeholder="Jabulani Hardware - Qumbu">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Store Slug (for URL)</label>
                                <input type="text" name="slug" id="store_slug" class="form-control" required
                                    placeholder="jabulani-qumbu">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Store Image</label>
                            <input type="file" name="image" class="form-control" accept="image/*">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Full Address</label>
                            <textarea name="address" class="form-control" required rows="3"></textarea>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Province</label>
                                <select name="province" class="form-select" required>
                                    <option value="">Select Province</option>
                                    <option value="Gauteng">Gauteng</option>
                                    <option value="KwaZulu-Natal">KwaZulu-Natal</option>
                                    <option value="Western Cape">Western Cape</option>
                                    <option value="Eastern Cape">Eastern Cape</option>
                                    <option value="Free State">Free State</option>
                                    <option value="Limpopo">Limpopo</option>
                                    <option value="Mpumalanga">Mpumalanga</option>
                                    <option value="Northern Cape">Northern Cape</option>
                                    <option value="North West">North West</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Store Managers</label>
                                <select name="manager_ids[]" class="form-select select2" multiple>
                                    @foreach($managers as $manager)
                                        <option value="{{ $manager->id }}">{{ $manager->name }}</option>
                                    @endforeach
                                </select>
                                <small class="text-muted">You can select multiple managers for this branch.</small>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Latitude</label>
                                <input type="number" step="any" name="lat" class="form-control" placeholder="-26.2041">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Longitude</label>
                                <input type="number" step="any" name="lng" class="form-control" placeholder="28.0473">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Contact Details</label>
                            <input type="text" name="contact_details" class="form-control" placeholder="+27 11 123 4567">
                        </div>
                        <div class="d-flex justify-content-end gap-2 mt-4">
                            <a href="{{ route('admin.stores.index') }}" class="btn btn-outline-secondary">Cancel</a>
                            <button type="submit" class="btn btn-jabulani px-4">Save Store</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script>
        document.getElementById('store_name').addEventListener('input', function () {
            let slug = this.value.toLowerCase()
                .replace(/[^\w ]+/g, '')
                .replace(/ +/g, '-');
            document.getElementById('store_slug').value = slug;
        });
    </script>
@endpush