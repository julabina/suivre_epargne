<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\StoreProjectRequest;
use App\Models\Project;
use Carbon\Carbon;

class ProjectController extends Controller
{
    public function list(Request $request): Response
    {
        $projects = Project::where('user_id', $request->user()->id)->get();

        return Inertia::render('Project/List', [
            'projects' => $projects
        ]);
    }
    
    public function create(): Response
    {
        return Inertia::render('Project/Create');
    }

    public function show(): void {}

    public function store(StoreProjectRequest $request): RedirectResponse {
        $loca;
        $deadlineDate = null;

        if ($request->form['toggleCustomLocation'] && $request->form['customLocation'] !== null && $request->form['customLocation'] !== "") {
            $loca = $request->form['customLocation'];           
        } else {
            $filePath = resource_path("js/utils/location.json");
            $locationContent = file_get_contents($filePath);
    
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
            'deadline' => $deadlineDate
        ]);

        $project->save();

        return redirect(route('project.list'));
    }

    public function update(): void {}

    public function delete(): void {}
}
