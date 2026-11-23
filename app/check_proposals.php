<?php
require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "Proposals:\n";
$proposals = App\Models\Proposal::with(['status', 'leader'])->get();
foreach ($proposals as $p) {
    echo $p->id . ': ' . $p->title . ' - Status: ' . ($p->status->name ?? 'N/A') . ' - Leader: ' . ($p->leader->name ?? 'N/A') . ' - Decision: ' . ($p->decision_note ?? 'None') . "\n";
}



