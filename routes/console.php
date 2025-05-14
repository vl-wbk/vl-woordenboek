<?php

use Illuminate\Support\Facades\Schedule;

Schedule::command('ban:delete-expired')->everyMinute();
Schedule::command('modal:prune')->daily()->at('02:00');
Schedule::command('model:prune')->dailyAt('00:45');
Schedule::command('backup:clean')->daily()->at('01:00');
Schedule::command('backup:run')->daily()->at('01:30');
Schedule::command('backup:monitor')->daily()->at('03:00');
