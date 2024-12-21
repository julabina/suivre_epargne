<?php

namespace App\Actions;

use App\Models\Project;
use App\Models\Transaction;
use Illuminate\Database\Eloquent\Collection;

class CalculSparedAmount
{
    /**
     * return spared amount
     *
     * @param Collection<int, Transaction> $transactions
     * @return integer|float
     */
    public function handle(Collection $transactions): int|float
    {
        $spared = 0;

        for ($i = 0; $i < count($transactions); $i++) {
            if ($transactions[$i]['type'] === 'deposit') {
                $spared += $transactions[$i]['amount'];
            } else {
                $spared -= $transactions[$i]['amount'];
            }
        }

        return $spared;
    }

    public function store(int $id): void
    {
        $project = Project::where('id', $id)->with('transactions')->first();

        $spared = $this->handle($project['transactions']);

        if ($spared < 0) {
            $spared = 0;
        }

        if ($spared !== $project->spared) {
            $project->spared = $spared;
            $project->save();
        }
    }
}
