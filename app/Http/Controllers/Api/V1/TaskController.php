<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\TaskResource;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TaskController extends Controller
{
    /**
     * @OA\Get(
     *  tags={"/v1/tasks"},
     *  summary="Buscar todas as tarefas",
     *  path="/v1/tasks",
     *  description="Este endpoint é resposnsável por retornar todas as tarefas cadastradas. Não requer nenhum parametro.",
     * 
     * @OA\Response(
     *  response="200", description="Retorno da lista"
     *  )
     * )
     * @return TaskResource
     */

    public function index()
    {
        $tasks = Task::with("user")->get();
        return TaskResource::collection($tasks);
    }


    /**
     * @OA\POST(
     *  tags={"/v1/tasks"},
     *  summary="Cadastrar nova tarefa",
     *  path="/v1/tasks",
     *  description="Este endpoint é resposnsável por criar uma nova tarefa",
     * 
     * @OA\RequestBody(
     *      required=true, 
     *      @OA\JsonContent(
     *          type="object", 
     *          @OA\Property(property="title", type="string"),
     *          @OA\Property(property="description", type="string"),
     *          @OA\Property(property="user_id", type="int"),
     *      )
     *  ),
     * 
     * @OA\Response(
     *  response="201", description="Cadastrado com sucesso"
     *  )
     * )
     * @param Request
     * @return TaskResource
     */
    public function store(Request $request)
    {
        $rules = [
            "title" => "required|string|min:3",
            "description" => "nullable|string|min:3",
            "user_id" => "required|numeric",
        ];

        $messages = [
            "title.required" => "O nome é obrigatório",
            "title.string" => "O nome deve ser letras",
            "title.min" => "deve conter 3 caracteres no mínimo",
            "description.string" => "A descrição deve ser texto",
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

    public function update(Request $request, string $id)
    {

       $rules = [
            "status" => "required|string",
            "user_id" => "required|numeric",
        ];

        $messages = [
            "status.required" => "O estado é obrigatório",
            "status.string" => "O estado deve ser letras",
            "user_id.required" => "O id do usuário é obrigatório",
            "user_id.numeric" => "O id do usuário deve ser numérico",
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

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

    public function destroy(string $id)
    {
        $task = Task::find($id);
        
        if ($task) {
            $task->delete();
            return response()->json(["message" => "Eliminado com sucesso"], 200);
        }

        return response()->json(["message" => "Nenhuma informação encontrada"], 404);
    }
    
    public function filterByStatus(Request $request, string $status)
    {
        $task = Task::where("status", $status);

        if ($request->user_id) {
            $task->where("user_id", $request->user_id);
        }

        if (count($task->get()) > 0) {
            return response()->json($task->get(), 200);
        }

        return response()->json(["message" => "Nenhuma informação encontrada"], 404);
    }

    public function filterByUser(Request $request, int $user_id)
    {
        $task = Task::where("user_id", $user_id)->get();

        if (count($task) > 0) {
            return response()->json($task, 200);
        }

        return response()->json(["message" => "Nenhuma informação encontrada"], 404);
    }
}
