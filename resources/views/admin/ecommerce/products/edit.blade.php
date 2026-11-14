@extends('layouts.backend.app')

@section('content')
<div class="content-wrapper">
    <div class="container-full">
        <div class="content-header">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div>
                    <h3 class="page-title text-dark fw-bold mb-1"><i class="ti-pencil text-primary me-2"></i>পণ্য সম্পাদনা (Edit Product)</h3>
                    <p class="text-muted mb-0">{{ $product->name }} (SKU: {{ $product->sku }})</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('products.show', $product->slug) }}" target="_blank" class="btn btn-outline-info">
                        <i class="ti-eye me-1"></i> লাইভ দেখুন
                    </a>
                    <a href="{{ route('admin.ecommerce.products') }}" class="btn btn-outline-secondary">
                        <i class="ti-arrow-left me-1"></i> পণ্য তালিকায় ফিরে যান
                    </a>
                </div>
            </div>
        </div>

        <section class="content">
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fa fa-exclamation-triangle me-1"></i> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <ul class="mb-0">
                        @foreach($errors->all() as $err)
                            <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <form action="{{ route('admin.ecommerce.products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="row">
                    <!-- Left Column: Basic Info & Pricing -->
                    <div class="col-lg-8 col-12">
                        <!-- 1. Basic Details Card -->
                        <div class="box shadow-sm mb-4">
                            <div class="box-header with-border">
                                <h4 class="box-title"><i class="ti-pencil-alt me-1 text-primary"></i>সাধারণ তথ্যাবলী (Basic Information)</h4>
                            </div>
                            <div class="box-body">
                                <div class="row g-3">
                                    <div class="col-md-7 col-12">
                                        <label class="form-label fw-bold">পণ্যের নাম (English) <span class="text-danger">*</span></label>
                                        <input type="text" name="name" class="form-control" required value="{{ old('name', $product->name) }}">
                                    </div>
                                    <div class="col-md-5 col-12">
                                        <label class="form-label fw-bold">পণ্যের নাম (বাংলা)</label>
                                        <input type="text" name="name_bn" class="form-control" value="{{ old('name_bn', $product->name_bn) }}">
                                    </div>

                                    <div class="col-md-4 col-12">
                                        <label class="form-label fw-bold">মেইন ক্যাটাগরি <span class="text-danger">*</span></label>
                                        <select name="category_id" id="categorySelect" class="form-select" required>
                                            <option value="">-- নির্বাচন করুন --</option>
                                            @foreach($categories as $cat)
                                                <option value="{{ $cat->id }}" {{ old('category_id', $product->category_id) == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-4 col-12">
                                        <label class="form-label fw-bold">সাব-ক্যাটাগরি</label>
                                        <select name="subcategory_id" id="subcategorySelect" class="form-select">
                                            <option value="">-- সাব-ক্যাটাগরি নেই --</option>
                                            @if($product->category && $product->category->subcategories)
                                                @foreach($product->category->subcategories as $scat)
                                                    <option value="{{ $scat->id }}" {{ old('subcategory_id', $product->subcategory_id) == $scat->id ? 'selected' : '' }}>{{ $scat->name }}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>

                                    <div class="col-md-4 col-12">
                                        <label class="form-label fw-bold">ব্র্যান্ড</label>
                                        <select name="brand_id" class="form-select">
                                            <option value="">-- ব্র্যান্ড ছাড়া / অন্যান্য --</option>
                                            @foreach($brands as $br)
                                                <option value="{{ $br->id }}" {{ old('brand_id', $product->brand_id) == $br->id ? 'selected' : '' }}>{{ $br->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-4 col-12">
                                        <label class="form-label fw-bold">SKU কোড</label>
                                        <input type="text" name="sku" class="form-control" value="{{ old('sku', $product->sku) }}">
                                    </div>

                                    <div class="col-md-4 col-12">
                                        <label class="form-label fw-bold">প্যাকেজ / একক (Unit)</label>
                                        <input type="text" name="unit" class="form-control" value="{{ old('unit', $product->unit ?: '1 Pc') }}">
                                    </div>

                                    <div class="col-md-4 col-12">
                                        <label class="form-label fw-bold">ওজন (Weight)</label>
                                        <input type="text" name="weight" class="form-control" value="{{ old('weight', $product->weight) }}">
                                    </div>

                                    <div class="col-12">
                                        <label class="form-label fw-bold">পণ্যের পূর্ণ বিবরণ (Description)</label>
                                        <textarea name="description" rows="5" class="form-control">{{ old('description', $product->description) }}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- 2. Pricing & Inventory Card -->
                        <div class="box shadow-sm mb-4">
                            <div class="box-header with-border">
                                <h4 class="box-title"><i class="ti-money me-1 text-success"></i>মূল্য ও ইনভেন্টরি (Price & Stock)</h4>
                            </div>
                            <div class="box-body">
                                <div class="row g-3">
                                    <div class="col-md-4 col-12">
                                        <label class="form-label fw-bold">বিক্রয় মূল্য (Selling Price ৳) <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <span class="input-group-text fw-bold">৳</span>
                                            <input type="number" step="0.01" name="price" class="form-control" required value="{{ old('price', $product->price) }}">
                                        </div>
                                    </div>

                                    <div class="col-md-4 col-12">
                                        <label class="form-label fw-bold">পূর্বের মূল্য / MRP (৳)</label>
                                        <div class="input-group">
                                            <span class="input-group-text fw-bold">৳</span>
                                            <input type="number" step="0.01" name="old_price" class="form-control" value="{{ old('old_price', $product->old_price) }}">
                                        </div>
                                    </div>

                                    <div class="col-md-4 col-12">
                                        <label class="form-label fw-bold">ক্রয় মূল্য (Purchase Cost ৳)</label>
                                        <div class="input-group">
                                            <span class="input-group-text fw-bold">৳</span>
                                            <input type="number" step="0.01" name="purchase_price" class="form-control" value="{{ old('purchase_price', $product->purchase_price) }}">
                                        </div>
                                    </div>

                                    <div class="col-md-4 col-12">
                                        <label class="form-label fw-bold">বর্তমান স্টক পরিমাণ</label>
                                        <input type="number" name="stock_quantity" class="form-control" value="{{ old('stock_quantity', $product->stock_quantity) }}">
                                    </div>

                                    <div class="col-md-4 col-12">
                                        <label class="form-label fw-bold">সর্বনিম্ন অর্ডার পরিমাণ</label>
                                        <input type="number" name="min_order_qty" class="form-control" value="{{ old('min_order_qty', $product->min_order_qty ?: 1) }}">
                                    </div>

                                    <div class="col-md-4 col-12">
                                        <label class="form-label fw-bold">সর্বোচ্চ অর্ডার পরিমাণ</label>
                                        <input type="number" name="max_order_qty" class="form-control" value="{{ old('max_order_qty', $product->max_order_qty) }}">
                                    </div>

                                    <div class="col-md-6 col-12">
                                        <label class="form-label fw-bold">ডেলিভারি সময় টেক্সট</label>
                                        <input type="text" name="delivery_time" class="form-control" value="{{ old('delivery_time', $product->delivery_time ?: 'Delivery 1-2 hours') }}">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- 3. Existing Variants Card -->
                        <div class="box shadow-sm mb-4">
                            <div class="box-header with-border d-flex justify-content-between align-items-center">
                                <h4 class="box-title"><i class="ti-layers me-1 text-info"></i>সাইজ ও কালার ভ্যারিয়েন্ট (Product Variants)</h4>
                                <div class="form-check form-switch mb-0">
                                    <input class="form-check-input" type="checkbox" id="hasVariantsToggle" name="has_variants" value="1" {{ $product->has_variants ? 'checked' : '' }} onchange="toggleVariantSection(this.checked)">
                                    <label class="form-check-label fw-bold" for="hasVariantsToggle">ভ্যারিয়েন্ট সক্রিয় করুন</label>
                                </div>
                            </div>
                            <div class="box-body" id="variantSection" style="{{ $product->has_variants ? 'display:block;' : 'display:none;' }}">
                                @if($product->variants->isNotEmpty())
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-sm align-middle mb-3">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>অ্যাট্রিবিউট</th>
                                                    <th>SKU</th>
                                                    <th>মূল্য</th>
                                                    <th>স্টক</th>
                                                    <th>অবস্থা</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($product->variants as $var)
                                                    <tr>
                                                        <td>
                                                            @if(is_array($var->attribute_values))
                                                                @foreach($var->attribute_values as $k => $v)
                                                                    <span class="badge bg-light text-dark border">{{ $k }}: {{ $v }}</span>
                                                                @endforeach
                                                            @endif
                                                        </td>
                                                        <td><code>{{ $var->sku }}</code></td>
                                                        <td class="fw-bold">৳ {{ number_format($var->price, 0) }}</td>
                                                        <td><span class="badge bg-info">{{ $var->stock_quantity }}</span></td>
                                                        <td>
                                                            <span class="badge {{ $var->is_active ? 'bg-success' : 'bg-secondary' }}">
                                                                {{ $var->is_active ? 'সক্রিয়' : 'নিষ্ক্রিয়' }}
                                                            </span>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @endif

                                <div class="alert alert-light border py-2 small mb-2">
                                    <i class="ti-info-alt me-1"></i> আরো নতুন ভ্যারিয়েন্ট যোগ করতে নিচের সারিসমূহ পূরণ করুন:
                                </div>

                                <div class="table-responsive">
                                    <table class="table table-bordered table-sm align-middle" id="variantTable">
                                        <thead class="table-light">
                                            <tr>
                                                <th>সাইজ / ভ্যালু</th>
                                                <th>কালার (ঐচ্ছিক)</th>
                                                <th>ভ্যারিয়েন্ট SKU</th>
                                                <th>মূল্য (৳)</th>
                                                <th>স্টক</th>
                                                <th style="width: 40px;"></th>
                                            </tr>
                                        </thead>
                                        <tbody id="variantRowsContainer">
                                            <!-- Dynamic Rows Added by JS -->
                                        </tbody>
                                    </table>
                                </div>
                                <button type="button" class="btn btn-sm btn-outline-primary mt-2" onclick="addVariantRow()">
                                    <i class="ti-plus me-1"></i> নতুন ভ্যারিয়েন্ট সারি যোগ করুন
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Right Column: Images, Collections & Settings -->
                    <div class="col-lg-4 col-12">
                        <!-- Main Product Image -->
                        <div class="box shadow-sm mb-4">
                            <div class="box-header with-border">
                                <h4 class="box-title"><i class="ti-image me-1 text-warning"></i>প্রধান ছবি (Main Image)</h4>
                            </div>
                            <div class="box-body text-center">
                                <div class="border rounded p-3 mb-3 bg-light d-flex align-items-center justify-content-center" style="height: 200px; overflow: hidden;">
                                    @if($product->image)
                                        <img id="mainImagePreview" src="{{ asset($product->image) }}" alt="{{ $product->name }}" style="max-height: 100%; max-width: 100%; object-fit: contain;">
                                    @else
                                        <img id="mainImagePreview" src="" alt="ছবি প্রিভিউ" style="max-height: 100%; max-width: 100%; object-fit: contain; display: none;">
                                        <span id="mainImagePlaceholder" class="text-muted"><i class="ti-cloud-up fs-1 d-block mb-1"></i>ছবি আপলোড করুন</span>
                                    @endif
                                </div>
                                <input type="file" name="image" id="mainImageInput" class="form-control" accept="image/*" onchange="previewMainImage(this)">
                                <small class="text-muted d-block mt-1">নতুন ছবি সিলেক্ট করলে পূর্বের ছবি প্রতিস্থাপিত হবে।</small>
                            </div>
                        </div>

                        <!-- Gallery Images -->
                        <div class="box shadow-sm mb-4">
                            <div class="box-header with-border">
                                <h4 class="box-title"><i class="ti-gallery me-1 text-info"></i>গ্যালারি ছবি (Gallery)</h4>
                            </div>
                            <div class="box-body">
                                @if($product->images && $product->images->isNotEmpty())
                                    <div class="d-flex flex-wrap gap-2 mb-3">
                                        @foreach($product->images as $gImg)
                                            <div class="position-relative border rounded p-1 bg-light">
                                                <img src="{{ asset($gImg->image) }}" style="width: 60px; height: 60px; object-fit: cover;" class="rounded">
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                                <label class="form-label small fw-bold">অতিরিক্ত গ্যালারি ছবি যোগ করুন</label>
                                <input type="file" name="gallery_images[]" class="form-control" multiple accept="image/*">
                            </div>
                        </div>

                        <!-- Product Collections & Badges -->
                        <div class="box shadow-sm mb-4">
                            <div class="box-header with-border">
                                <h4 class="box-title"><i class="ti-bookmark-alt me-1 text-danger"></i>কালেকশন ও ডিসপ্লে ট্যাগ</h4>
                            </div>
                            <div class="box-body">
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" name="is_exclusive" id="chkExclusive" value="1" {{ $product->is_exclusive ? 'checked' : '' }}>
                                    <label class="form-check-label fw-bold" for="chkExclusive">Exclusive Deals (এক্সক্লুসিভ অফার)</label>
                                </div>
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" name="is_featured" id="chkFeatured" value="1" {{ $product->is_featured ? 'checked' : '' }}>
                                    <label class="form-check-label fw-bold" for="chkFeatured">Featured Products (ফিচার্ড পণ্য)</label>
                                </div>
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" name="is_new_arrival" id="chkNewArrival" value="1" {{ $product->is_new_arrival ? 'checked' : '' }}>
                                    <label class="form-check-label fw-bold" for="chkNewArrival">New Arrival (নতুন আগমন)</label>
                                </div>
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" name="is_trending" id="chkTrending" value="1" {{ $product->is_trending ? 'checked' : '' }}>
                                    <label class="form-check-label fw-bold" for="chkTrending">Trending Now (ট্রেন্ডিং)</label>
                                </div>
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" name="is_popular" id="chkPopular" value="1" {{ $product->is_popular ? 'checked' : '' }}>
                                    <label class="form-check-label fw-bold" for="chkPopular">Popular Products (জনপ্রিয় পণ্য)</label>
                                </div>
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" name="is_best_selling" id="chkBestSelling" value="1" {{ $product->is_best_selling ? 'checked' : '' }}>
                                    <label class="form-check-label fw-bold" for="chkBestSelling">Best Selling (সর্বোচ্চ বিক্রিত)</label>
                                </div>
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" name="is_daily_offer" id="chkDailyOffer" value="1" {{ $product->is_daily_offer ? 'checked' : '' }}>
                                    <label class="form-check-label fw-bold" for="chkDailyOffer">Daily Offers (দৈনিক অফার)</label>
                                </div>
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" name="is_combo_offer" id="chkComboOffer" value="1" {{ $product->is_combo_offer ? 'checked' : '' }}>
                                    <label class="form-check-label fw-bold" for="chkComboOffer">Combo Offers (কম্বো অফার)</label>
                                </div>
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" name="is_winter_essential" id="chkWinter" value="1" {{ $product->is_winter_essential ? 'checked' : '' }}>
                                    <label class="form-check-label fw-bold" for="chkWinter">Winter Essentials (শীতকালীন স্পেশাল)</label>
                                </div>
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" name="is_monthly_bazar" id="chkMonthlyBazar" value="1" {{ $product->is_monthly_bazar ? 'checked' : '' }}>
                                    <label class="form-check-label fw-bold" for="chkMonthlyBazar">Monthly Bazar (মাসিক বাজার প্যাকেজ)</label>
                                </div>
                                <hr>
                                <div class="mb-3">
                                    <label class="form-label small fw-bold text-danger"><i class="ti-timer me-1"></i>অফার কাউন্টডাউন শেষ হওয়ার তারিখ ও সময়</label>
                                    <input type="datetime-local" name="offer_end_date" class="form-control form-control-sm" value="{{ old('offer_end_date', $product->offer_end_date ? date('Y-m-d\TH:i', strtotime($product->offer_end_date)) : '') }}">
                                    <small class="text-muted">প্রোডাক্ট কার্ডে লাইভ টাইমার (যেমন: 16 DAY : 10 HR) দেখাতে সময় নির্ধারণ করুন</small>
                                </div>
                                <hr>
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="is_active" id="chkActive" value="1" {{ $product->is_active ? 'checked' : '' }}>
                                    <label class="form-check-label fw-bold text-success" for="chkActive">পণ্যটি সক্রিয় রাখুন (Active on Shop)</label>
                                </div>
                            </div>
                        </div>

                        <!-- Action Submit Box -->
                        <div class="box shadow-sm mb-4">
                            <div class="box-body">
                                <button type="submit" class="btn btn-primary btn-lg w-100 mb-2">
                                    <i class="ti-save me-1"></i> আপডেট সংরক্ষণ করুন
                                </button>
                                <a href="{{ route('admin.ecommerce.products') }}" class="btn btn-outline-secondary w-100">
                                    বাতিল করুন
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </section>
    </div>
</div>

<script>
const categorySubcategories = {
    @foreach($categories as $c)
        "{{ $c->id }}": [
            @foreach($c->subcategories as $s)
                { id: "{{ $s->id }}", name: "{{ $s->name }}" },
            @endforeach
        ],
    @endforeach
};

document.getElementById('categorySelect').addEventListener('change', function () {
    const subSelect = document.getElementById('subcategorySelect');
    subSelect.innerHTML = '<option value="">-- সাব-ক্যাটাগরি নেই --</option>';
    const subs = categorySubcategories[this.value] || [];
    subs.forEach(s => {
        const opt = document.createElement('option');
        opt.value = s.id;
        opt.textContent = s.name;
        subSelect.appendChild(opt);
    });
});

function previewMainImage(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const preview = document.getElementById('mainImagePreview');
            preview.src = e.target.result;
            preview.style.display = 'block';
        }
        reader.readAsDataURL(input.files[0]);
    }
}

function toggleVariantSection(checked) {
    document.getElementById('variantSection').style.display = checked ? 'block' : 'none';
}

let variantIndex = 0;
function addVariantRow() {
    const container = document.getElementById('variantRowsContainer');
    const tr = document.createElement('tr');
    tr.id = `vRow_${variantIndex}`;
    tr.innerHTML = `
        <td>
            <input type="text" name="variants[${variantIndex}][attributes][Size]" class="form-control form-control-sm" placeholder="যেমন: S, M, L বা 1Kg">
        </td>
        <td>
            <input type="text" name="variants[${variantIndex}][attributes][Color]" class="form-control form-control-sm" placeholder="যেমন: Red, Blue">
        </td>
        <td>
            <input type="text" name="variants[${variantIndex}][sku]" class="form-control form-control-sm" placeholder="VAR-SKU">
        </td>
        <td>
            <input type="number" step="0.01" name="variants[${variantIndex}][price]" class="form-control form-control-sm" placeholder="0.00">
        </td>
        <td>
            <input type="number" name="variants[${variantIndex}][stock_quantity]" class="form-control form-control-sm" value="20">
        </td>
        <td class="text-center">
            <button type="button" class="btn btn-sm btn-outline-danger p-1" onclick="document.getElementById('vRow_${variantIndex}').remove()">
                <i class="ti-trash"></i>
            </button>
        </td>
    `;
    container.appendChild(tr);
    variantIndex++;
}
</script>
@endsection

