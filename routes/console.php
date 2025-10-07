<?php

use Illuminate\Support\Facades\Schedule;

Schedule::command('check:missed-checkins')->everyMinute();
