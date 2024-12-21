<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProjectRequest;
use App\Models\Project;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ProjectController extends Controller
{
    public function list(Request $request): Response
    {
        $projects = Project::where('user_id', $request->user()->id)->with('transactions')->get();

        return Inertia::render('Project/List', [
            'projects' => $projects,
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Project/Create');
    }

    public function show(int $id, Request $request): Response|RedirectResponse
    {
        $project = Project::where('user_id', $request->user()->id)->where('id', $id)->with('transactions')->first();

        if ($project) {
            return Inertia::render('Project/Show', [
                'project' => $project,
            ]);
        }

        return back();
    }

    public function store(StoreProjectRequest $request): RedirectResponse
    {
        $deadlineDate = null;

        if ($request->form['toggleCustomLocation'] && $request->form['customLocation'] !== null && $request->form['customLocation'] !== '') {
            $loca = $request->form['customLocation'];
        } else {
            $filePath = resource_path('js/utils/location.json');
            if (! file_exists($filePath)) {
                return back();
            }

            $locationContent = file_get_contents($filePath);

            if ($locationContent === false) {
                return back();
            }

            $locationData = json_decode($locationContent, true);
            $loca = $locationData[$request->form['location']];
        }

        if ($request->form['toggleDeadline']) {
            $date = Carbon::createFromFormat('Y-m-d', $request->form['deadline']);
            if (now()->timestamp < $date->timestamp) {
                $deadlineDate = $date;
            }
        }

        $project = new Project([
            'user_id' => $request->user()->id,
            'title' => $request->form['title'],
            'description' => $request->form['description'],
            'goal_amount' => $request->form['amountGoal'],
            'location' => $loca,
            'deadline' => $deadlineDate,
        ]);

        $project->save();

        return redirect(route('project.list'));
    }

    public function update(): void {}

    public function delete(): void {}
}
