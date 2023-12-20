<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class TaskController extends Controller
{
    
   // Method to get all tasks with optional sorting and filtering
   public function index(Request $request) {

        $query = Task::query(); 
        
        // Check if a sort field is provided
        if ($request->has('sort')) {
            // Get the sort field and direction
            $sortField = $request->input('sort');
            $sortDirection = $request->input('direction', 'asc');
            $validSortFields = ['due_date'];
            // If the sort field is valid, sort the query
            if(in_array($sortField, $validSortFields)) {
                $query->orderBy($sortField, $sortDirection);
            }
        }

        // Check if a status filter is provided
        if ($request->has('status')) {
            // Get the status filter
            $status = $request->input('status');
            $validStatuses = ['in progress', 'completed'];
            // If the status is valid, filter the query
            if (in_array($status, $validStatuses)) {
                $query->where('status', $status);
            }
        }
        
        $tasks = $query->get();

        // Return the tasks
        return response()->json(['tasks' => $tasks], 200);
    }

    // Method to get a specific task by ID
    public function show($id) {
        $task = Task::findOrFail($id);

        // Return the task
        return response()->json(['task' => $task], 200);
    }

        
    // Method to create a new task
    public function store(Request $request) {
        // Validate the request data
         $fields = $request->validate([
            'title' => 'required|max:255',
            'description' => 'required',
            'status' => ['required', Rule::in(['in progress', 'completed'])],
            'due_date' => 'required|date|after:tomorrow',
        ]);
        
        // Create a new task
        $task = Task::create($fields);

        // Return a success response
        return response()->json(['task' => $task, 'message' => 'New Task has been created successfully! ', 201]);
    }

 
    // Method to update a specific task by ID
    public function update(Request $request, $id) {
        
        $fields = $request->validate([
            'title' => 'required|max:255',
            'description' => 'required',
            'status' => ['required', Rule::in(['in progress', 'completed'])],
            'due_date' => 'required|date|after:tomorrow',
        ]);

        $task = Task::findOrFail($id);
        // Update the task
        $task->update($fields);

        // Return a success response
        return response()->json(['task' => $task, 'message' => 'The Task has been updated successfully'], 200);
    }

    // Method to delete a specific task by ID
    public function destroy($id) {

        $task = Task::findOrFail($id);
         // Delete the task
        $task->delete();

        // Return a success response
        return response()->json(['message' => "The Task has been deleted successfully"], 200);
    }

    // Method to update the status of a specific task by ID
    public function updateStatus(Request $request, $id){
        $task = Task::findOrFail($id);
        // Update the status of the task
        $task->status = $request->get('status');
        $task->save();
    
        // Return a success response
        return response()->json(['task' => $task, 'message' => 'The status has been updated successfully'], 200);
    }
}
