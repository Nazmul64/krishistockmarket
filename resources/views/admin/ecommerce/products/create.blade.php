@extends('layouts.backend.app')

@section('content')
<div class="content-wrapper">
    <div class="container-full">
        <div class="content-header">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div>
                    <h3 class="page-title text-dark fw-bold mb-1"><i class="ti-plus text-primary me-2"></i>নতুন পণ্য যোগ করুন</h3>
                    <p class="text-muted mb-0">অনলাইন শপে নতুন পণ্য, মূল্য, ছবি, স্টক ও ভ্যারিয়েন্ট এন্ট্রি করুন</p>
                </div>
                <a href="{{ route('admin.ecommerce.products') }}" class="btn btn-outline-secondary">
                    <i class="ti-arrow-left me-1"></i> পণ্য তালিকায় ফিরে যান
                </a>
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

            <form action="{{ route('admin.ecommerce.products.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
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
                                        <input type="text" name="name" class="form-control" required placeholder="e.g. Fortune Biryani Special Basmati Rice 1kg" value="{{ old('name') }}">
                                    </div>
                                    <div class="col-md-5 col-12">
                                        <label class="form-label fw-bold">পণ্যের নাম (বাংলা)</label>
                                        <input type="text" name="name_bn" class="form-control" placeholder="যেমন: ফরচুন বাসমতি চাল ১ কেজি" value="{{ old('name_bn') }}">
                                    </div>

                                    <div class="col-md-4 col-12">
                                        <label class="form-label fw-bold">মেইন ক্যাটাগরি <span class="text-danger">*</span></label>
                                        <select name="category_id" id="categorySelect" class="form-select" required>
                                            <option value="">-- নির্বাচন করুন --</option>
                                            @foreach($categories as $cat)
                                                <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-4 col-12">
                                        <label class="form-label fw-bold">সাব-ক্যাটাগরি</label>
                                        <select name="subcategory_id" id="subcategorySelect" class="form-select">
                                            <option value="">-- সাব-ক্যাটাগরি নেই --</option>
                                        </select>
                                    </div>

                                    <div class="col-md-4 col-12">
                                        <label class="form-label fw-bold">ব্র্যান্ড</label>
                                        <select name="brand_id" class="form-select">
                                            <option value="">-- ব্র্যান্ড ছাড়া / অন্যান্য --</option>
                                            @foreach($brands as $br)
                                                <option value="{{ $br->id }}" {{ old('brand_id') == $br->id ? 'selected' : '' }}>{{ $br->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-4 col-12">
                                        <label class="form-label fw-bold">SKU কোড</label>
                                        <input type="text" name="sku" class="form-control" placeholder="e.g. SKU-8941184 (ফাঁকা রাখলে অটো জেনারেট হবে)" value="{{ old('sku') }}">
                                    </div>

                                    <div class="col-md-4 col-12">
                                        <label class="form-label fw-bold">প্যাকেজ / একক (Unit)</label>
                                        <input type="text" name="unit" class="form-control" placeholder="e.g. 1 Pc, 1 Kg, 500 ml" value="{{ old('unit', '1 Pc') }}">
                                    </div>

                                    <div class="col-md-4 col-12">
                                        <label class="form-label fw-bold">ওজন (Weight)</label>
                                        <input type="text" name="weight" class="form-control" placeholder="e.g. 500g, 1.2kg" value="{{ old('weight') }}">
                                    </div>

                                    <div class="col-12">
                                        <label class="form-label fw-bold">পণ্যের পূর্ণ বিবরণ (Description)</label>
                                        <textarea name="description" rows="5" class="form-control" placeholder="পণ্যের বিস্তারিত বর্ণনা, গুণাগুণ, খাওয়ার নিয়ম বা ব্যবহার বিধি...">{{ old('description') }}</textarea>
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
                                            <input type="number" step="0.01" name="price" class="form-control" required placeholder="0.00" value="{{ old('price') }}">
                                        </div>
                                    </div>

                                    <div class="col-md-4 col-12">
                                        <label class="form-label fw-bold">পূর্বের মূল্য / MRP (৳)</label>
                                        <div class="input-group">
                                            <span class="input-group-text fw-bold">৳</span>
                                            <input type="number" step="0.01" name="old_price" class="form-control" placeholder="0.00" value="{{ old('old_price') }}">
                                        </div>
                                        <small class="text-muted">অফার বা ডিসকাউন্ট দেখানোর জন্য</small>
                                    </div>

                                    <div class="col-md-4 col-12">
                                        <label class="form-label fw-bold">ক্রয় মূল্য (Purchase Cost ৳)</label>
                                        <div class="input-group">
                                            <span class="input-group-text fw-bold">৳</span>
                                            <input type="number" step="0.01" name="purchase_price" class="form-control" placeholder="0.00" value="{{ old('purchase_price') }}">
                                        </div>
                                        <small class="text-muted">লাভ-ক্ষতি ও রিপোর্টিংয়ের জন্য</small>
                                    </div>

                                    <div class="col-md-4 col-12">
                                        <label class="form-label fw-bold">প্রাথমিক স্টক পরিমাণ</label>
                                        <input type="number" name="stock_quantity" class="form-control" value="{{ old('stock_quantity', 50) }}">
                                    </div>

                                    <div class="col-md-4 col-12">
                                        <label class="form-label fw-bold">সর্বনিম্ন অর্ডার পরিমাণ</label>
                                        <input type="number" name="min_order_qty" class="form-control" value="{{ old('min_order_qty', 1) }}">
                                    </div>

                                    <div class="col-md-4 col-12">
                                        <label class="form-label fw-bold">সর্বোচ্চ অর্ডার পরিমাণ</label>
                                        <input type="number" name="max_order_qty" class="form-control" placeholder="সীমাহীন হলে ফাঁকা রাখুন" value="{{ old('max_order_qty') }}">
                                    </div>

                                    <div class="col-md-6 col-12">
                                        <label class="form-label fw-bold">ডেলিভারি সময় টেক্সট</label>
                                        <input type="text" name="delivery_time" class="form-control" value="{{ old('delivery_time', 'Delivery 1-2 hours') }}" placeholder="e.g. Delivery 1-2 hours">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- 3. Product Variants Builder (Size, Color, etc.) -->
                        <div class="box shadow-sm mb-4">
                            <div class="box-header with-border d-flex justify-content-between align-items-center">
                                <h4 class="box-title"><i class="ti-layers me-1 text-info"></i>সাইজ ও কালার ভ্যারিয়েন্ট (Product Variants)</h4>
                                <div class="form-check form-switch mb-0">
                                    <input class="form-check-input" type="checkbox" id="hasVariantsToggle" name="has_variants" value="1" onchange="toggleVariantSection(this.checked)">
                                    <label class="form-check-label fw-bold" for="hasVariantsToggle">ভ্যারিয়েন্ট সক্রিয় করুন</label>
                                </div>
                            </div>
                            <div class="box-body" id="variantSection" style="display: none;">
                                <div class="alert alert-info py-2 small">
                                    <i class="ti-info-alt me-1"></i> ভিন্ন ভিন্ন সাইজ, কালার বা ওজনের জন্য আলাদা SKU, মূল্য ও স্টক এন্ট্রি করুন।
                                </div>

                                <div class="table-responsive">
                                    <table class="table table-bordered table-sm align-middle" id="variantTable">
                                        <thead class="table-light">
                                            <tr>
                                                <th>সাইজ / ভ্যালু</th>
                                                <th>কালার (ঐচ্ছিক)</th>
                                                <th>ভ্যারিয়েন্ট SKU</th>
                                                <th>মূল্য (৳)</th>
                                                <th>পূর্বের মূল্য (৳)</th>
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
                                    <img id="mainImagePreview" src="" alt="ছবি প্রিভিউ" style="max-height: 100%; max-width: 100%; object-fit: contain; display: none;">
                                    <span id="mainImagePlaceholder" class="text-muted"><i class="ti-cloud-up fs-1 d-block mb-1"></i>ছবি নির্বাচন করুন</span>
                                </div>
                                <input type="file" name="image" id="mainImageInput" class="form-control" accept="image/*" onchange="previewMainImage(this)">
                                <small class="text-muted d-block mt-1">সংরক্ষণ ফোল্ডার: <code>public/uploads/product/product-main-images/</code></small>
                            </div>
                        </div>

                        <!-- Gallery Images -->
                        <div class="box shadow-sm mb-4">
                            <div class="box-header with-border">
                                <h4 class="box-title"><i class="ti-gallery me-1 text-info"></i>গ্যালারি ছবি (Multiple Gallery)</h4>
                            </div>
                            <div class="box-body">
                                <input type="file" name="gallery_images[]" class="form-control" multiple accept="image/*">
                                <small class="text-muted d-block mt-1">একসাথে একাধিক ছবি সিলেক্ট করতে পারেন। সংরক্ষণ ফোল্ডার: <code>public/uploads/product/product-gallery/</code></small>
                            </div>
                        </div>

                        <!-- Product Collections & Badges -->
                        <div class="box shadow-sm mb-4">
                            <div class="box-header with-border">
                                <h4 class="box-title"><i class="ti-bookmark-alt me-1 text-danger"></i>কালেকশন ও ডিসপ্লে ট্যাগ</h4>
                            </div>
                            <div class="box-body">
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" name="is_exclusive" id="chkExclusive" value="1">
                                    <label class="form-check-label fw-bold" for="chkExclusive">Exclusive Deals (এক্সক্লুসিভ অফার)</label>
                                </div>
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" name="is_featured" id="chkFeatured" value="1" checked>
                                    <label class="form-check-label fw-bold" for="chkFeatured">Featured Products (ফিচার্ড পণ্য)</label>
                                </div>
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" name="is_new_arrival" id="chkNewArrival" value="1" checked>
                                    <label class="form-check-label fw-bold" for="chkNewArrival">New Arrival (নতুন আগমন)</label>
                                </div>
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" name="is_trending" id="chkTrending" value="1">
                                    <label class="form-check-label fw-bold" for="chkTrending">Trending Now (ট্রেন্ডিং)</label>
                                </div>
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" name="is_popular" id="chkPopular" value="1">
                                    <label class="form-check-label fw-bold" for="chkPopular">Popular Products (জনপ্রিয় পণ্য)</label>
                                </div>
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" name="is_best_selling" id="chkBestSelling" value="1">
                                    <label class="form-check-label fw-bold" for="chkBestSelling">Best Selling (সর্বোচ্চ বিক্রিত)</label>
                                </div>
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" name="is_daily_offer" id="chkDailyOffer" value="1">
                                    <label class="form-check-label fw-bold" for="chkDailyOffer">Daily Offers (দৈনিক অফার)</label>
                                </div>
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" name="is_combo_offer" id="chkComboOffer" value="1">
                                    <label class="form-check-label fw-bold" for="chkComboOffer">Combo Offers (কম্বো অফার)</label>
                                </div>
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" name="is_winter_essential" id="chkWinter" value="1">
                                    <label class="form-check-label fw-bold" for="chkWinter">Winter Essentials (শীতকালীন স্পেশাল)</label>
                                </div>
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" name="is_monthly_bazar" id="chkMonthlyBazar" value="1">
                                    <label class="form-check-label fw-bold" for="chkMonthlyBazar">Monthly Bazar (মাসিক বাজার প্যাকেজ)</label>
                                </div>
                                <hr>
                                <div class="mb-3">
                                    <label class="form-label small fw-bold text-danger"><i class="ti-timer me-1"></i>অফার কাউন্টডাউন শেষ হওয়ার তারিখ ও সময়</label>
                                    <input type="datetime-local" name="offer_end_date" class="form-control form-control-sm" value="{{ old('offer_end_date') }}">
                                    <small class="text-muted">প্রোডাক্ট কার্ডে লাইভ টাইমার (যেমন: 16 DAY : 10 HR) দেখাতে সময় নির্ধারণ করুন</small>
                                </div>
                                <hr>
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="is_active" id="chkActive" value="1" checked>
                                    <label class="form-check-label fw-bold text-success" for="chkActive">পণ্যটি সক্রিয় রাখুন (Active on Shop)</label>
                                </div>
                            </div>
                        </div>

                        <!-- Action Submit Box -->
                        <div class="box shadow-sm mb-4">
                            <div class="box-body">
                                <button type="submit" class="btn btn-success btn-lg w-100 mb-2">
                                    <i class="ti-save me-1"></i> পণ্য সংরক্ষণ করুন (Publish)
                                </button>
                                <a href="{{ route('admin.ecommerce.products') }}" class="btn btn-outline-danger w-100">
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
            const placeholder = document.getElementById('mainImagePlaceholder');
            preview.src = e.target.result;
            preview.style.display = 'block';
            placeholder.style.display = 'none';
        }
        reader.readAsDataURL(input.files[0]);
    }
}

function toggleVariantSection(checked) {
    document.getElementById('variantSection').style.display = checked ? 'block' : 'none';
    if (checked && document.querySelectorAll('#variantRowsContainer tr').length === 0) {
        addVariantRow();
    }
}

let variantIndex = 0;
function addVariantRow() {
    const container = document.getElementById('variantRowsContainer');
    const tr = document.createElement('tr');
    tr.id = `vRow_${variantIndex}`;
    tr.innerHTML = `
        <td>
            <input type="text" name="variants[${variantIndex}][attributes][Size]" class="form-control form-control-sm" placeholder="যেমন: S, M, L, XL বা 1Kg">
        </td>
        <td>
            <input type="text" name="variants[${variantIndex}][attributes][Color]" class="form-control form-control-sm" placeholder="যেমন: Red, Blue, Black">
        </td>
        <td>
            <input type="text" name="variants[${variantIndex}][sku]" class="form-control form-control-sm" placeholder="VAR-SKU">
        </td>
        <td>
            <input type="number" step="0.01" name="variants[${variantIndex}][price]" class="form-control form-control-sm" placeholder="0.00">
        </td>
        <td>
            <input type="number" step="0.01" name="variants[${variantIndex}][old_price]" class="form-control form-control-sm" placeholder="0.00">
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

