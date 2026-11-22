<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BusinessApplication extends Model
{
    use HasFactory;

    protected $table = 'business_applications';

    protected $fillable = [
        'type',
        'name',
        'phone',
        'whatsapp_number',
        'email',
        'address',
        'division',
        'district',
        'upazila',
        'profession',
        'amount',
        'quantity',
        'showroom_size',
        'showroom_location',
        'age',
        'marital_status',
        'gender',
        'previous_pilgrimage',
        'has_passport',
        'selection_reason',
        'is_financially_capable',
        'source_info',
        'remarks',
        'status',
        'admin_notes',
    ];

    public function getTypeLabelAttribute(): string
    {
        return match ($this->type) {
            'investor' => 'বিনিয়োগকারী (Investor)',
            'wholesale' => 'হোলসেল (Wholesale)',
            'franchise' => 'ফ্র্যাঞ্চাইজি (Franchise)',
            'umrah_haj' => 'উমরাহ/হজ (Umrah Haj)',
            default => ucfirst($this->type),
        };
    }

    public function getStatusBadgeAttribute(): string
    {
        return match ($this->status) {
            'pending' => '<span class="badge bg-warning text-dark"><i class="fa fa-clock-o me-1"></i> অপেক্ষমান (Pending)</span>',
            'contacted' => '<span class="badge bg-info text-white"><i class="fa fa-phone me-1"></i> যোগাযোগ হয়েছে (Contacted)</span>',
            'approved' => '<span class="badge bg-success text-white"><i class="fa fa-check me-1"></i> অনুমোদিত (Approved)</span>',
            'rejected' => '<span class="badge bg-danger text-white"><i class="fa fa-times me-1"></i> বাতিল (Rejected)</span>',
            default => '<span class="badge bg-secondary">' . ucfirst($this->status) . '</span>',
        };
    }
}
