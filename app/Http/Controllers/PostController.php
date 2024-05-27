<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use App\Events\UserLogin;
use App\Models\Post;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Traits\ResponseController;

class PostController
{
    use ResponseController;


    /**
     * @OA\Get(
     *  path="/api/posts",
     *  summary="Get list of posts",
     *  description="Pass the page number",
     *  operationId="postsGet",
     *  tags={"post"},
     *  @OA\Parameter(
     *        description="number page",
     *        in="query",
     *        name="page",
     *        required=true,
     *        example="1",
     *   ),
     *  @OA\Response(
     *    response=200,
     *    description="Array of posts received",
     *    @OA\JsonContent(
     *       @OA\Property(property="text", type="string"),
     *       @OA\Property(property="status", type="string", example="success"),
     *       @OA\Property(property="json", type="array", @OA\Items() ),
     *    )
     *  ),
     *  @OA\Response(
     *      response=422,
     *      description="Unprocessable Entity",
     *      @OA\JsonContent(
     *         @OA\Property(property="text", type="string", example="Запрашиваемой страницы не существует"),
     *         @OA\Property(property="status", type="string", example="error"),
     *         @OA\Property(property="json", type="array", @OA\Items() )
     *       )
     *  )
     * )
     */
    public function index(Request $request): JsonResponse
    {
        $validated = Validator::make(['page' => $request->page], [
            'page' => 'integer|min:1',
        ]);

        if ($validated->fails()) {
            $this->text = $validated->errors();
        } else {
            $data = $validated->valid();

            $postList = Post::orderBy('id', 'desc')->paginate(10, ['*'], 'page', $data['page']);

            if (count($postList) > 0) {
                $this->status = 'success';
                $this->code = 200;
                $this->json = $postList;
            } else {
                $this->text = 'Запрашиваемой страницы не существует';
            }
        }

        return $this->responseJsonApi();
    }


    /**
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        $validated = Validator::make(['id' => $id], [
            'id' => 'integer|min:1',
        ]);

        if ($validated->fails()) {
            $this->text = $validated->errors();
        } else {
            $data = $validated->valid();

            $contentPostSingle = Post::where('id', '=', $data['id'])->get();

            if (count($contentPostSingle) > 0) {
                $this->status = 'success';
                $this->code = 200;
                $this->json = $contentPostSingle;
            } else {
                $this->text = 'Запрашиваемой новости не существует';
            }
        }

        return $this->responseJsonApi();
    }
}
