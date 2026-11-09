@forelse($properties as $item)
    <div class="col-lg-4 col-md-6 col-12 property-item-card-col">
        <div class="card property-card h-100 border-0 rounded-4 shadow-sm overflow-hidden property-card-hover" style="transition: transform 0.25s ease, box-shadow 0.25s ease;">
            <!-- Property Image with Badges -->
            <div class="position-relative" style="height: 220px; overflow: hidden; background: #e2e8f0;">
                <img src="{{ $item->featured_image ?: 'https://images.unsplash.com/photo-1500382017468-9049fed747ef?w=800&auto=format&fit=crop&q=60' }}" 
                     alt="{{ $item->property_name }}" 
                     class="w-100 h-100" 
                     style="object-fit: cover; transition: transform 0.4s ease;">
                
                <!-- Badges -->
                <div class="position-absolute top-0 start-0 m-3 d-flex flex-column gap-1">
                    <span class="badge px-2 py-1 rounded" style="background: rgba(15, 23, 42, 0.85) !important; color: #ffffff !important;">
                        {{ $item->property_type_name }}
                    </span>
                    @if($item->is_featured)
                        <span class="badge bg-warning text-dark px-2 py-1 rounded fw-bold">
                            <i class="fa fa-star me-1"></i> {{ $isBn ? 'স্পেশাল অফার' : 'Special Offer' }}
                        </span>
                    @endif
                </div>

                <div class="position-absolute top-0 end-0 m-3">
                    @if($item->status === 'available')
                        <span class="badge bg-success text-white px-2 py-1 rounded-pill shadow-sm">
                            {{ $isBn ? 'বিক্রয়যোগ্য' : 'Available' }}
                        </span>
                    @elseif($item->status === 'reserved')
                        <span class="badge bg-warning text-dark px-2 py-1 rounded-pill shadow-sm">
                            {{ $isBn ? 'রিজার্ভড' : 'Reserved' }}
                        </span>
                    @endif
                </div>

                <!-- Area Badge at Bottom-Right of Image -->
                <div class="position-absolute bottom-0 end-0 m-3 px-3 py-1 rounded-3 small fw-bold" style="background: rgba(15, 23, 42, 0.85) !important; color: #ffffff !important;">
                    <i class="fa fa-expand me-1 text-warning"></i> {{ number_format($item->total_area, 2) }} {{ $item->measurement_unit }}
                </div>
            </div>

            <!-- Card Body -->
            <div class="card-body property-card-body p-4 d-flex flex-column">
                <div class="small text-muted font-monospace mb-1">{{ $item->property_code }}</div>
                <h5 class="fw-bold mb-2" style="font-size: 1.15rem; line-height: 1.4;">
                    <a href="{{ route('properties.show', $item->id) }}" class="property-title-link text-decoration-none hover-primary">
                        {{ $item->property_name }}
                    </a>
                </h5>

                <!-- Location Info -->
                <div class="d-flex align-items-center text-muted small mb-3">
                    <i class="fa fa-map-marker text-danger me-2 fs-6"></i>
                    <span>{{ $item->upazila ? $item->upazila . ', ' : '' }}{{ $item->district }}</span>
                    @if($item->dag_number)
                        <span class="mx-1">•</span>
                        <span>{{ $isBn ? 'দাগ: ' : 'Dag: ' }}{{ $item->dag_number }}</span>
                    @endif
                </div>

                <!-- Highlights / Description -->
                @if($item->features)
                    <div class="p-2 rounded-3 mb-3 small text-truncate property-feature-preview" style="background: rgba(16, 185, 129, 0.08); color: #065f46; border: 1px solid rgba(16, 185, 129, 0.2);">
                        <i class="fa fa-check text-success me-1"></i> {{ $item->features }}
                    </div>
                @elseif($item->description)
                    <p class="text-muted small mb-3 text-truncate-2" style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                        {{ $item->description }}
                    </p>
                @endif

                <div class="mt-auto pt-3 border-top">
                    <!-- Price Row -->
                    <div class="d-flex align-items-end justify-content-between mb-3">
                        <div>
                            <div class="small text-muted">{{ $isBn ? 'সর্বমোট বিক্রয়মূল্য:' : 'Total Price:' }}</div>
                            <div class="fw-bold font-monospace text-success fs-5">
                                ৳ {{ number_format($item->selling_price) }}
                            </div>
                        </div>
                        @if($item->booking_amount > 0)
                            <div class="text-end">
                                <div class="small text-muted">{{ $isBn ? 'বুকিং ডিপোজিট:' : 'Booking Deposit:' }}</div>
                                <div class="fw-bold font-monospace text-primary">
                                    ৳ {{ number_format($item->booking_amount) }}
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- Action Buttons -->
                    <div class="row g-2">
                        <div class="col-6">
                            <a href="{{ route('properties.show', $item->id) }}" class="btn btn-outline-dark btn-sm w-100 py-2 fw-bold">
                                <i class="fa fa-eye me-1"></i> {{ $isBn ? 'বিস্তারিত' : 'Details' }}
                            </a>
                        </div>
                        <div class="col-6">
                            <button type="button" 
                                    class="btn btn-success btn-sm w-100 py-2 fw-bold"
                                    onclick="openBookingModal('{{ $item->id }}', '{{ addslashes($item->property_name) }}', '{{ number_format($item->selling_price) }}')">
                                <i class="fa fa-paper-plane me-1"></i> {{ $isBn ? 'বুকিং / সাইট' : 'Book / Visit' }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@empty
    <div class="col-12 py-5 text-center">
        <div class="p-5 bg-white rounded-4 shadow-sm property-empty-box mx-auto" style="max-width: 600px;">
            <div class="mb-3 d-inline-flex align-items-center justify-content-center rounded-circle" style="width: 80px; height: 80px; background: #f1f5f9; color: #94a3b8;">
                <i class="fa fa-map-o fa-2x"></i>
            </div>
            <h4 class="fw-bold text-dark mb-2">{{ $isBn ? 'কোনো প্রপার্টি খুঁজে পাওয়া যায়নি!' : 'No Properties Found!' }}</h4>
            <p class="text-muted small mb-4">{{ $isBn ? 'আপনার ফিল্টারের সাথে মিল রেখে কোনো প্রপার্টি পাওয়া যায়নি। অন্য কিওয়ার্ড দিয়ে অনুসন্ধান করুন অথবা ফিল্টার রিসেট করুন।' : 'No properties matched your search filters. Please try searching with different keywords or reset filters.' }}</p>
            <button type="button" onclick="resetAllPropertyFilters()" class="btn btn-success fw-bold px-4 rounded-pill">
                <i class="fa fa-refresh me-1"></i> {{ $isBn ? 'সকল প্রপার্টি দেখুন' : 'View All Properties' }}
            </button>
        </div>
    </div>
@endforelse

