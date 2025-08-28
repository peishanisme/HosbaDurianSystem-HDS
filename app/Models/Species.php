<?php

namespace App\Models;

use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Species extends Model
{
    use LogsActivity, SoftDeletes;
    protected $fillable = [
        'name',
        'code',
        'description',
    ];

    protected static function booted()
    {
        static::updated(function ($model) {
            if ($model->isDirty('code')) {
                $oldCode = $model->getOriginal('code');
                $newCode = $model->code;

                $model->trees()
                    ->get()
                    ->each(function ($tree) use ($oldCode, $newCode) {
                        // Extract the numeric part
                        $parts = explode('-', $tree->tree_tag, 2);
                        if (count($parts) === 2) {
                            $tree->tree_tag = $newCode . '-' . $parts[1];

                            // Only save if it's different and not empty
                            if (!empty($tree->tree_tag) && $tree->tree_tag !== $tree->getOriginal('tree_tag')) {
                                $tree->save();
                            }
                        }
                    });
            }
        });
    }
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->useLogName('species')
            ->setDescriptionForEvent(fn(string $eventName) => "A species has been $eventName.")
            ->dontSubmitEmptyLogs();
    }

    public function trees(): HasMany
    {
        return $this->hasMany(Tree::class);
    }
}
