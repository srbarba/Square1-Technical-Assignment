<?php

namespace App\Models\Cron;

use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    const STATUS_PENDING = "pending";
    const STATUS_PROCESSING = "processing";
    const STATUS_ERROR = "error";
    const STATUS_COMPLETED = "completed";

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'cron_schedule';

    /**
     * The primary key in the table.
     *
     * @var string
     */
    protected $primaryKey = "schedule_id";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'schedule_id', 'command', 'status', 'message', 'scheduled_at', 'executed_at', 'finished_at', 'expired_at'
    ];
}
