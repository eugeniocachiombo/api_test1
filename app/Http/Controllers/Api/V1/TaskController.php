<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\TaskResource;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TaskController extends Controller
{
    public function index()
    {
        $tasks = Task::with("user")->get();
        return TaskResource::collection($tasks);
    }

    public function store(Request $request)
    {
        $rules = [
            "title" => "required|string|min:3",
            "description" => "nullable",
            "user_id" => "required|numeric",
        ];

        $messages = [
            "title.required" => "O nome é obrigatório",
            "title.string" => "O nome deve ser letras",
            "title.min" => "deve conter 3 caracteres no mínimo",
            "user_id.required" => "O id do usuário é obrigatório",
            "user_id.numeric" => "O id do usuário deve ser numérico",
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        Task::create($validator->validated());
        return response()->json(["message" => "Cadastrado com sucesso"], 201);
    }

    public function show(int $id)
    {
        $task = Task::find($id);

        if ($task) {
            return response()->json($task, 200);
        }

        return response()->json(["message" => "Nenhuma informação encontrada"], 404);
    }

    public function filterByStatus(string $status, int $user_id)
    {
        $task = Task::where("status", $status)
        ->where("user_id", $user_id)
        ->get();

        if (count($task) > 0) {
            return response()->json($task, 200);
        }

        return response()->json(["message" => "Nenhuma informação encontrada"], 404);
    }

    public function update(Request $request, string $id)
    {
       
        $task = Task::where("id", $id)
        ->where("user_id", $request->user_id)
        ->first();
        
        if ($task) {
            $task->status = $request->status;
            $task->save();
            return response()->json(["message" => "Actualizado com sucesso"], 200);
        }

        return response()->json(["message" => "Nenhuma informação encontrada"], 404);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
