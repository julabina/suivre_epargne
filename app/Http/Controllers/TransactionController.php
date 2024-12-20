<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Models\Project;
use App\Actions\CalculSparedAmount;
use App\Models\Transaction;

class TransactionController extends Controller
{
    public function add(int $id, Request $request, CalculSparedAmount $calculSparedAmount): RedirectResponse {
        $request->validate([
            'form.amount' => 'required|numeric',
            'form.comment' => 'nullable|max:255|string'
        ]);

        $project = Project::where('id', $id)->with('transactions')->first();

        if ($project && $project->user_id === $request->user()->id) {
            $spared = 0;
            $realAmount = $request->form["amount"];
            
            if (count($project["transactions"]) > 0) {
                $spared = $calculSparedAmount->handle($project["transactions"]);
            }
            
            if (($project->goal_amount - $spared) < $realAmount) {
                $realAmount = $project->goal_amount - $spared;
            }

            $transaction = new Transaction([
                'project_id' => $id,
                'amount' => $realAmount,
                'comment' => $request->form["comment"],
                'type' => 'deposit'
            ]);

            $transaction->save();

            $calculSparedAmount->store($id);
        }

        return redirect(route('project.list'));
    }

    public function update(): void {}

    public function delete(): void {}
}
