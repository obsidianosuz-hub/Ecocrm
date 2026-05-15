<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    $user = App\Models\User::first();
    $user->role = 'teacher';
    auth()->login($user);
    $groups = \App\Models\Group::where('teacher_id', auth()->id())->with('course', 'students', 'room')->get();
    $schedules = \App\Models\Schedule::whereIn('group_id', $groups->pluck('id'))->with('group.room')->get();
    $activeShift = \App\Models\Shift::where('user_id', auth()->id())->whereNull('ended_at')->first();
    echo view('dashboards.teacher', compact('activeShift', 'groups', 'schedules'))->render();
    echo 'SUCCESS';
} catch (\Throwable $e) {
    echo $e->getMessage() . " in " . $e->getFile() . ":" . $e->getLine();
}
