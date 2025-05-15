<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;

class Checklist extends Model
{
    protected $fillable = [
        'item_id',
        'title',
        'completed',
        'order'
    ];

    protected $casts = [
        'completed' => 'boolean',
        'order' => 'integer'
    ];

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public static function getProgress($itemId)
    {
        $total = self::where('item_id', $itemId)->count();
        if ($total === 0) {
            return 0;
        }

        $completed = self::where('item_id', $itemId)
            ->where('completed', true)
            ->count();

        return round(($completed / $total) * 100);
    }

    public function moveAfter(Checklist $target)
    {
        DB::transaction(function () use ($target) {
            $oldOrder = $this->order;
            $newOrder = $target->order;

            if ($oldOrder < $newOrder) {
                // Moving down
                Checklist::where('item_id', $this->item_id)
                    ->where('order', '>', $oldOrder)
                    ->where('order', '<=', $newOrder)
                    ->decrement('order');
            } else {
                // Moving up
                Checklist::where('item_id', $this->item_id)
                    ->where('order', '>=', $newOrder)
                    ->where('order', '<', $oldOrder)
                    ->increment('order');
            }

            $this->order = $newOrder;
            $this->save();
        });
    }

    public function moveBefore(Checklist $target)
    {
        DB::transaction(function () use ($target) {
            $oldOrder = $this->order;
            $newOrder = $target->order - 1;

            if ($oldOrder < $newOrder) {
                // Moving down
                Checklist::where('item_id', $this->item_id)
                    ->where('order', '>', $oldOrder)
                    ->where('order', '<=', $newOrder)
                    ->decrement('order');
            } else {
                // Moving up
                Checklist::where('item_id', $this->item_id)
                    ->where('order', '>=', $newOrder)
                    ->where('order', '<', $oldOrder)
                    ->increment('order');
            }

            $this->order = $newOrder;
            $this->save();
        });
    }
} 