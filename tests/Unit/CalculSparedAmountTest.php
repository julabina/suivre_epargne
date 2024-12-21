<?php 

namespace Tests\Unit;

/* use Tests\TestCase;
use App\Models\Project;
use App\Models\User;
use App\Models\Transaction;
use App\Actions\CalculSparedAmount;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertDatabaseCount;

test('handle calcul spared', function () {
    $user = User::factory()->create();

    $project = Project::factory()->create([
        'goal_amount' => 1000,
        'user_id' => $user->id
    ]);

    $transactions = Transaction::factory()->count(5)->create([
        'project_id' => $project->id,
        'type' => fake()->random()->arrayElement(['deposit','withdrawal', 'deposit']),
        'amount' => rand(20, 100)
    ]);

    $calculatedSpared = CalculSparedAmount::handle($transactions);

    dd($calculatedSpared);

}); */