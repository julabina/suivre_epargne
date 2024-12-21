<?php

namespace App\Http\Controllers;

use App\Actions\CalculSparedAmount;
use App\Models\Project;
use App\Models\Transaction;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function add(int $id, Request $request, CalculSparedAmount $calculSparedAmount): RedirectResponse
    {
        $request->validate([
            'form.amount' => 'required|numeric',
            'form.comment' => 'nullable|max:255|string',
            'type' => 'string|in:deposit,withdrawal',
        ]);

        $project = Project::where('id', $id)->with('transactions')->first();

        if ($project && $project->user_id === $request->user()->id) {
            $spared = 0;
            $realAmount = $request->form['amount'];

            if (count($project['transactions']) > 0) {
                $spared = $calculSparedAmount->handle($project['transactions']);
            }

            if ($request->type === 'deposit' && (($project->goal_amount - $spared) < $realAmount)) {
                $realAmount = $project->goal_amount - $spared;
            } elseif ($request->type === 'withdrawal' && $spared < $realAmount) {
                $realAmount = $spared;
            }

            $transaction = new Transaction([
                'project_id' => $id,
                'amount' => $realAmount,
                'comment' => $request->form['comment'],
                'type' => $request->type,
            ]);

            $transaction->save();

            $calculSparedAmount->store($id);
        }

        return redirect(route('project.list'));
    }

    public function update(): void {}

    public function delete(): void {}
}
