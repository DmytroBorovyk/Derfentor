<?php

namespace App\Jobs;

use App\Models\Record;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;

class RecordClearJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;

    public function handle()
    {
        $records = Record::where('created_at', '<=', Carbon::now()->subDays(30))->get();
        foreach ($records as $record){
            $file = $record->file;
            if ($file) {
                Storage::disk('private_files')->delete($file->path);
                $file->delete();
            }
            $record->delete();
        }
    }
}
