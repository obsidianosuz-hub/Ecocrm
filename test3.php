<?php
$teachers = \App\Models\User::withoutGlobalScopes()->where('role', 'teacher')->get();
echo "Found " . $teachers->count() . " teachers.\n";
foreach($teachers as $t) {
    echo "ID: $t->id, Name: $t->name, Company: $t->company_id\n";
    // Fix them just in case
    if (!$t->company_id && auth()->user()) {
        $t->company_id = auth()->user()->company_id;
        $t->save();
        echo "Fixed company_id for $t->name\n";
    } elseif (!$t->company_id) {
        $t->company_id = 1;
        $t->save();
        echo "Fixed company_id for $t->name (defaulted to 1)\n";
    }
}
