<?php

use App\Console\Commands\Reminders\InactivityWarningCommand;
use App\Console\Commands\WordOfTheDayCommand;
use Illuminate\Support\Facades\Schedule;

// Own custom cron commands
Schedule::command(WordOfTheDayCommand::class)->daily()->at('00:01');

// 3th party cron commands
Schedule::command('ban:delete-expired')->everyMinute();
Schedule::command('notify:article-prune-reminder')->dailyAt('00:40');
Schedule::command(InactivityWarningCommand::class)->dailyAt('00:30');
Schedule::command('model:prune')->dailyAt('00:45');
Schedule::command('backup:clean')->daily()->at('01:00');
Schedule::command('backup:run')->daily()->at('01:30');
Schedule::command('backup:monitor')->daily()->at('03:00');
