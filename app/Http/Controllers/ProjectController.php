<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Inertia\Response;

class ProjectController extends Controller
{
    public function list(): Response
    {
        return Inertia::render('Project/List');
    }

    public function show(): void {}

    public function store(): void {}

    public function update(): void {}

    public function delete(): void {}
}
