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
     *     path="/v1/tasks",
     *     tags={"Tasks"},
     *     summary="Buscar todas as tarefas",
     *     description="Retorna todas as tarefas cadastradas.",
     *     @OA\Response(
     *         response=200,
     *         description="Lista de tarefas retornada com sucesso."
     *     )
     * )
     */
    public function index()
    {
        $tasks = Task::with("user")->get();
        return TaskResource::collection($tasks);
    }


    /**
     * @OA\Post(
     *     path="/v1/tasks",
     *     tags={"Tasks"},
     *     summary="Cadastrar nova tarefa",
     *     description="Cria uma nova tarefa.",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"title", "user_id"},
     *             @OA\Property(property="title", type="string", example="Tarefa 1"),
     *             @OA\Property(property="description", type="string", example="Descrição a tarefa"),
     *             @OA\Property(property="user_id", type="integer", example=1)
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Tarefa criada com sucesso."
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Erro de validação."
     *     )
     * )
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


    /**
     * @OA\Get(
     *     path="/v1/tasks/{id}",
     *     tags={"Tasks"},
     *     summary="Buscar tarefa por ID",
     *     description="Retorna os dados de uma tarefa específica pelo ID.",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID da tarefa",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Tarefa encontrada com sucesso."
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Tarefa não encontrada."
     *     )
     * )
     */
    public function show(int $id)
    {
        $task = Task::find($id);

        if ($task) {
            return response()->json($task, 200);
        }

        return response()->json(["message" => "Nenhuma informação encontrada"], 404);
    }

    /**
     * @OA\Put(
     *     path="/v1/tasks/{id}",
     *     tags={"Tasks"},
     *     summary="Actualizar tarefa",
     *     description="Actualiza o status de uma tarefa existente.",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID da tarefa",
     *         @OA\Schema(type="string", example="1")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"status", "user_id"},
     *             @OA\Property(property="status", type="string", example="concluído"),
     *             @OA\Property(property="user_id", type="integer", example=1)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Tarefa actualizada com sucesso."
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Tarefa não encontrada."
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Erro de validação."
     *     )
     * )
     */
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


    /**
     * @OA\Delete(
     *     path="/v1/tasks/{id}",
     *     tags={"Tasks"},
     *     summary="Eliminar tarefa",
     *     description="Eliminar uma tarefa existente pelo ID.",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID da tarefa",
     *         @OA\Schema(type="string", example="1")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Tarefa eliminada com sucesso."
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Tarefa não encontrada."
     *     )
     * )
     */
    public function destroy(string $id)
    {
        $task = Task::find($id);

        if ($task) {
            $task->delete();
            return response()->json(["message" => "Eliminado com sucesso"], 200);
        }

        return response()->json(["message" => "Nenhuma informação encontrada"], 404);
    }


    /**
     * @OA\Get(
     *     path="/v1/tasks/status/{status}",
     *     tags={"Tasks"},
     *     summary="Filtrar tarefas por status",
     *     description="Retorna tarefas com o status informado. Pode filtrar opcionalmente por user_id via query parameter.",
     *     @OA\Parameter(
     *         name="status",
     *         in="path",
     *         required=true,
     *         description="Status da tarefa",
     *         @OA\Schema(type="string", example="concluído")
     *     ),
     *     @OA\Parameter(
     *         name="user_id",
     *         in="query",
     *         required=false,
     *         description="ID do usuário para filtro opcional",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Lista de tarefas filtradas."
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Nenhuma tarefa encontrada com esse filtro."
     *     )
     * )
     */
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
