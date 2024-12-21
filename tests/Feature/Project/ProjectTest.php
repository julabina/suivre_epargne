<?php

namespace Tests\Feature\Project;

use App\Models\Project;
use App\Models\User;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertDatabaseCount;

test('project list page is displayed', function () {
    $user = User::factory()->create();

    $response = $this
        ->actingAs($user)
        ->get('/');

    $response->assertOk();
});

test('create project page is displayed', function () {
    $user = User::factory()->create();

    $response = $this
        ->actingAs($user)
        ->get('/projet');

    $response->assertOk();
});

test('show project page is displayed', function () {
    $user = User::factory()->create();

    $project = Project::factory()->create([
        'user_id' => $user->id,
    ]);

    $response = actingAs($user)
        ->get(
            uri: route('project.show', ['id' => $project->id]),
        );

    $response->assertOk();
});

it('can store project', function () {
    $user = User::factory()->create();

    $form = [
        'title' => $title = fake()->sentence,
        'description' => $description = fake()->optional()->sentence,
        'amountGoal' => $amount = fake()->randomFloat(2, 1, 10000),
        'location' => '1',
        'customLocation' => fake()->word,
        'toggleCustomLocation' => fake()->boolean,
        'toggleDeadline' => false,
        'deadline' => null,
    ];

    $response = actingAs($user)
        ->post(
            uri: route('project.store'),
            data: [
                'form' => $form,
            ]
        );

    assertDatabaseCount('projects', 1);

    $project = Project::first();

    $response->assertRedirectToRoute('project.list');

    expect($project->title)->toBe($title);
    expect($project->description)->toBe($description);
    expect($project->goal_amount)->toBe($amount);
});
