<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class MealReminderNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        private string $mealType = 'lunch',
        private string $date = ''
    ) {
        $this->date = $date ?: today()->format('Y-m-d');
    }

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $mealLabel = config('meal.meal_types')[$this->mealType] ?? ucfirst($this->mealType);

        return (new MailMessage)
            ->subject("Meal Reminder: {$mealLabel} for " . date('d M Y', strtotime($this->date)))
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line("Don't forget to log your **{$mealLabel}** for today.")
            ->action('Log Meal Now', route('meals.create'))
            ->line('Please log your meal before end of day.');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type'      => 'meal_reminder',
            'title'     => 'Meal Reminder',
            'message'   => "Don't forget to log your " . (config('meal.meal_types')[$this->mealType] ?? $this->mealType) . ' for today.',
            'meal_type' => $this->mealType,
            'date'      => $this->date,
            'url'       => route('meals.create'),
        ];
    }
}
