<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use App\Models\FeatureBan;
use Illuminate\Console\Command;

#[Signature('feature-bans:expire')]
#[Description('Delete expired feature bans and clear their stored Pennant values')]
final class ExpireFeatureBans extends Command
{
    /**
     * Execute the console command.
     */
    public function handle()
    {
        $count = 0;
 
        // Per-model delete on purpose: bulk deletes skip model events,
        // which would leave the stored Pennant value as "false".
        FeatureBan::expired()->with('user')->each(function (FeatureBan $ban) use (&$count) {
            $ban->delete();
            $count++;
        });
 
        $this->info("Expired {$count} feature ban(s).");
 
        return self::SUCCESS;
    }
}
