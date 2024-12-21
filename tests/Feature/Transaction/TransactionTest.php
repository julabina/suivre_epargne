<?php

namespace Tests\Feature\Project;

use App\Models\Project;
use App\Models\Transaction;
use App\Models\User;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertDatabaseCount;

it('can add fund for first time', function () {
    $user = User::factory()->create();

    $project = Project::factory()->create([
        'goal_amount' => 1000,
        'user_id' => $user->id,
    ]);

    $form = [
        'amount' => $amount = rand(1, 800),
        'comment' => $comment = fake()->optional()->sentence,
    ];

    $response = actingAs($user)
        ->post(
            uri: route('transaction.add', ['id' => $project->id]),
            data: [
                'form' => $form,
                'type' => 'deposit',
            ]
        );

    assertDatabaseCount('transactions', 1);

    $transaction = Transaction::first();
    $p = Project::first();

    $response->assertRedirectToRoute('project.list');

    expect(strval($transaction->amount))->toBe(strval($amount));
    expect($transaction->comment)->toBe($comment);
    expect($p->spared)->toBe(strval($amount));
});

it('can add fund', function () {
    $user = User::factory()->create();

    $amountVal = rand(20, 500);

    $project = Project::factory()->create([
        'goal_amount' => 1000,
        'user_id' => $user->id,
        'spared' => $amountVal,
    ]);

    $t1 = Transaction::factory()->create([
        'project_id' => $project->id,
        'type' => 'deposit',
        'amount' => $amountVal,
    ]);

    $form = [
        'amount' => $amount = rand(1, 500),
        'comment' => $comment = fake()->optional()->sentence,
    ];

    $response = actingAs($user)
        ->post(
            uri: route('transaction.add', ['id' => $project->id]),
            data: [
                'form' => $form,
                'type' => 'deposit',
            ]
        );

    assertDatabaseCount('transactions', 2);

    $transaction = Transaction::orderBy('id', 'desc')->get();
    $p = Project::first();

    $response->assertRedirectToRoute('project.list');

    expect(strval($transaction[0]->amount))->toBe(strval($amount));
    expect($transaction[0]->comment)->toBe($comment);
    expect($p->spared)->toBeGreaterThan($amountVal);
    expect($p->spared)->toEqual($amountVal + $amount);
});

it('can remove fund', function () {
    $user = User::factory()->create();

    $amountVal = rand(500, 800);

    $project = Project::factory()->create([
        'goal_amount' => 1000,
        'user_id' => $user->id,
        'spared' => $amountVal,
    ]);

    $t1 = Transaction::factory()->create([
        'project_id' => $project->id,
        'type' => 'deposit',
        'amount' => $amountVal,
    ]);

    $form = [
        'amount' => $amount = rand(1, 400),
        'comment' => $comment = fake()->optional()->sentence,
    ];

    $response = actingAs($user)
        ->post(
            uri: route('transaction.add', ['id' => $project->id]),
            data: [
                'form' => $form,
                'type' => 'withdrawal',
            ]
        );

    assertDatabaseCount('transactions', 2);

    $transaction = Transaction::orderBy('id', 'desc')->get();
    $p = Project::first();

    $response->assertRedirectToRoute('project.list');

    expect(strval($transaction[0]->amount))->toBe(strval($amount));
    expect($transaction[0]->comment)->toBe($comment);
    expect($p->spared)->toBeLessThan($amountVal);
    expect($p->spared)->toEqual($amountVal - $amount);
});
