<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Container\Attributes\Auth;
use Illuminate\Http\Request;

class TodoController extends Controller
{
    public function get(Request $request){
        $user = auth()->user();

        $limit = $request->payload['limit'] ?? 10;
        $page = $request->payload['page'] ?? 1;
        $search = $request->payload['search'] ?? '';
        $type = $request->payload['type'] ?? '';


        $tasks = Task::getTask($user->id)
            ->when($search, function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query->where('title', 'like', "%$search%")
                          ->orWhere('description', 'like', "%$search%");
                });
            })
            ->when($type, function ($query) use ($type) {
                $query->where('type', $type);
            })
            ->paginate($limit, ['*'], 'page', $page);

        // Transform tasks
        $transformedTasks = $tasks->map(function ($task) {
            return (new Task)->mapTask($task);
        });

        $lookups = [
            'types' => Task::Types,
        ];

       // Return paginated response
        return response()->json([
            "tasks" => $transformedTasks,
            "pagination" => [
                'current_page' => $tasks->currentPage(),
                'per_page' => $tasks->perPage(),
                'total' => $tasks->total(),
                'selectedType' => $type,
                'last_page' => $tasks->lastPage(),
                'searchQuery'=> $request->payload['search'] ?? '',
            ],
            "lookups" => $lookups,
        ]);
    }

    public function create(Request $request){
        $user = auth()->user();

        $task = Task::create([
            'user_id' => $user->id,
            'title' => $request->title,
            'description' => $request->description,
            'type' => $request->type,
            'is_done' => 0,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Task added successfully!',
            'task' => $task
        ], 201);
    }

    public function delete($id){

        $meesage =  'Task deleted successfully!';

        $task = Task::findOrFail($id);

        $task->delete();

        return response()->json([
            'success' => true,
            'message' => $meesage,
            'task' => $task
        ], 200);
    }

    public function edit(Request $request){
        $task = Task::find($request->id);

        if($task == null){
            return response()->json(["message"=>"Task not found"],404);
        }else{
            if($request->is_editing == true){
                $task = $task->update([
                    'title' => $request->title ?? null,
                    'description' => $request->description ?? null,
                    'type' => is_array($request->type) && isset($request->type['id']) ? $request->type['id'] : $request->type,
                    'is_done' => $request->is_done ?? 0,
                ]);
            }else{
                $task =$task->update([
                    'is_done' => $request->is_done ?? 0,
                ]);
            }
                
        }


        return response()->json([
            'success' => true,
            'message' => 'Task edit successfully!',
            'task' => $task
        ], 200);
    }
}
