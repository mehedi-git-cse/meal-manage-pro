<?php

namespace App\Notifications;

use App\Models\MealCost;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class MonthlyReportNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(private MealCost $mealCost) {}

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Monthly Meal Report — ' . $this->mealCost->month_year)
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('Your monthly meal report for **' . $this->mealCost->month_year . '** is ready.')
            ->line('**Total Meals:** ' . number_format($this->mealCost->total_meals, 1))
            ->line('**Total Bazar Cost:** ' . config('meal.currency_symbol') . number_format($this->mealCost->total_bazar_cost, 2))
            ->line('**Cost Per Meal:** ' . config('meal.currency_symbol') . number_format($this->mealCost->cost_per_meal, 2))
            ->action('View Full Report', route('reports.monthly', ['year' => $this->mealCost->year, 'month' => $this->mealCost->month]))
            ->line('Thank you for using the Meal Management System!');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type'       => 'monthly_report',
            'title'      => 'Monthly Report Ready',
            'message'    => 'Meal report for ' . $this->mealCost->month_year . ' is now available.',
            'month_year' => $this->mealCost->month_year,
            'url'        => route('reports.monthly', ['year' => $this->mealCost->year, 'month' => $this->mealCost->month]),
        ];
    }
}
