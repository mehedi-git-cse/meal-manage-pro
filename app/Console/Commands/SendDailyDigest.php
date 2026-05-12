<?php

namespace App\Console\Commands;

use App\Models\MealEntry;
use App\Models\User;
use App\Notifications\MealReminderNotification;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class SendDailyDigest extends Command
{
    protected $signature = 'meals:send-daily-digest {--meal-type=lunch : The meal type to remind about}';
    protected $description = 'Send daily meal digest / reminder to all active meal users';

    public function handle(): int
    {
        $mealType = $this->option('meal-type');
        $today    = today()->format('Y-m-d');

        $users = User::active()->mealActive()->get();

        $notified = 0;

        foreach ($users as $user) {
            $alreadyLogged = MealEntry::where('user_id', $user->id)
                ->where('meal_date', $today)
                ->where('meal_type', $mealType)
                ->exists();

            if (!$alreadyLogged) {
                try {
                    $user->notify(new MealReminderNotification($mealType, $today));
                    $notified++;
                } catch (\Exception $e) {
                    Log::warning("Failed to notify user {$user->id}: " . $e->getMessage());
                }
            }
        }

        $this->info("Daily digest sent to {$notified} users (meal type: {$mealType}).");

        return Command::SUCCESS;
    }
}
