<?php


namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;

trait ResponseController
{

    /**
     * @var int
     */
    public int $code = 404;

    /**
     * @var string
     */
    public string $status = 'error';

    /**
     * @var string|object
     */
    public string|object $text = '';


    /**
     * @var mixed|null
     */
    public mixed $json = null;

    /**
     * @return JsonResponse
     */
    public function responseJsonApi(): JsonResponse
    {
        $response = [
            'data' => [
                'status' => $this->status,
                'text' => $this->text,
                'json' => $this->json
            ],
            'code' => $this->code
        ];

        return response()->json($response['data'], $response['code']);
    }

}
