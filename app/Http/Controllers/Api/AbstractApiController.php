<?php

namespace App\Http\Controllers\Api;

use Cnsi\Logger\Logger;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;

class AbstractApiController extends BaseController
{
    use ValidatesRequests;

    /**
     * @var string
     */
    protected $logger_channel = '';

    /**
     * @var Logger
     */
    protected $logger;

    public function __construct(Request $request)
    {
        if (!isset($this->logger_channel))
            throw new \LogicException(get_class($this) . ' must have a property $logger_channel');

        $this->logger = new Logger($this->logger_channel, true, true);

        // log request
        $this->logger->info('------ START ------');
        $this->logger->info('[URI]      ' . $request->route()->uri());
        $this->logger->info('[FROM]     ' . $request->ip());
        $this->logger->info('[HEADERS]  ' . json_encode($request->header(),  JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));
        $this->logger->info('[DATA]     ' . json_encode($request->all(),  JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));
        $this->logger->info('=================');
        $this->logger->info('Calling middlewares...');
    }

    protected function message($message, $status = 200, ?array $data = null, array $headers = []): \Illuminate\Http\JsonResponse
    {
        $this->logger->info('=================');
        $this->logger->info('[RESPONSE][CODE]    ' . $status);
        $this->logger->info('[RESPONSE][MESSAGE] ' . json_encode(['message' => $message],  JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));
        $this->logger->info('------ DONE ------');
        return response()->json(['message' => $message, 'data' => $data], $status, $headers);
    }
}
