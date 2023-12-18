<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class TaskController extends Controller
{
   public function index(Request $request) {

        $query = Task::query(); 
        
        if ($request->has('sort')) {
            $sortField = $request->input('sort');
            $sortDirection = $request->input('direction', 'asc');
            $validSortFields = ['due_date'];
            if(in_array($sortField, $validSortFields)) {
                $query->orderBy($sortField, $sortDirection);
            }
        }

        if ($request->has('status')) {
            $status = $request->input('status');
            $validStatuses = ['in progress', 'completed'];
            if (in_array($status, $validStatuses)) {
                $query->where('status', $status);
            }
        }
        
        $tasks = $query->get();

        return response()->json(['tasks' => $tasks], 200);
    }

    public function show($id) {
        $task = Task::findOrFail($id);

        return response()->json(['task' => $task], 200);
    }

        
    
    public function store(Request $request) {
         $fields = $request->validate([
            'title' => 'required|max:255',
            'description' => 'required',
            'status' => ['required', Rule::in(['in progress', 'completed'])],
            'due_date' => 'required|date|after:tomorrow',
        ]);
        

        $task = Task::create($fields);

        return response()->json(['task' => $task, 'message' => 'New Task has been created successfully! ', 201]);
    }

 
    public function update(Request $request, $id) {
        
        $fields = $request->validate([
            'title' => 'required|max:255',
            'description' => 'required',
            'status' => ['required', Rule::in(['in progress', 'completed'])],
            'due_date' => 'required|date|after:tomorrow',
        ]);

        $task = Task::findOrFail($id);
        $task->update($fields);

        return response()->json(['task' => $task, 'message' => 'The Task has been updated successfully'], 200);
    }

    public function destroy($id) {

        $task = Task::findOrFail($id);
        $task->delete();

        return response()->json(['message' => "The Task has been deleted successfully"], 200);
    }

    public function updateStatus(Request $request, $id){
        $task = Task::findOrFail($id);
        $task->status = $request->get('status');
        $task->save();
    
        return response()->json(['task' => $task, 'message' => 'The status has been updated successfully'], 200);
    }
}
