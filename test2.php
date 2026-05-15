<?php
try {
    $groups = collect();
    $schedules = collect();
    $html = view('dashboards.teacher', compact('groups', 'schedules'))->render();
    echo "SUCCESS\n";
} catch (\Throwable $e) {
    echo "ERR: " . $e->getMessage() . "\n" . $e->getFile() . ":" . $e->getLine();
}
