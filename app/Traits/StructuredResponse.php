<?php


namespace App\Traits;

use Illuminate\Http\JsonResponse;

trait StructuredResponse
{

    /**
     * @var int
     */
    public int $code = 422;

    /**
     * @var string
     */
    public string $status = 'error';

    /**
     * @var mixed|null
     */
    public mixed $text = null;

    /**
     * @var mixed|null
     */
    public mixed $dataJson = null;

    /**
     * @return JsonResponse
     */
    public function responseJsonApi(): JsonResponse
    {
        $response = [
            'data' => [
                'status' => $this->status,
                'text' => $this->text,
                'json' => $this->dataJson
            ],
            'code' => $this->code
        ];

        return response()->json($response['data'], $response['code']);
    }

}
