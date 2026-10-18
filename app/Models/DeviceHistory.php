<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable(['device_id', 'event_type', 'old_site_id', 'new_site_id', 'changed_by', 'event_at', 'notes'])]
class DeviceHistory extends Model
{
    protected $table = 'device_history';

    protected function casts(): array
    {
        return [
            'event_at' => 'datetime',
        ];
    }

    public function device(): BelongsTo
    {
        return $this->belongsTo(Device::class);
    }

    public function oldSite(): BelongsTo
    {
        return $this->belongsTo(Site::class, 'old_site_id');
    }
    
    public function newSite(): BelongsTo
    {
        return $this->belongsTo(Site::class, 'new_site_id');
    }

    public function changedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'changed_by');
    }
}
