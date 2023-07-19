<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\DigitalFile;

class DownloadToken extends Model
{
    protected $table = 'download_tokens';

    public $guarded = [];

    /**
     * @return BelongsTo
     */
    public function file(): BelongsTo
    {
        return $this->belongsTo(DigitalFile::class, 'digital_file_id');
    }
}
