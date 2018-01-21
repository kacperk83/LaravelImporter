<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\Request;

/**
 * Class InternalServerException
 *
 * @package App\Exceptions
 *
 * @author  Kacper Kowalski kacperk83@gmail.com
 */
class InternalServerException extends \Exception implements Responsable
{
    /**
     * @var array|null $errors
     */
    protected $errors = [];

    /**
     * @var Exception $innerException
     */
    protected $innerException;

    /**
     * @var int $responseCode
     */
    protected $responseCode = 500;

    /**
     * InternalServerException constructor.
     *
     * @param string         $message
     * @param Exception|null $innerException
     */
    public function __construct(
        string $message,
        ?Exception $innerException
    ) {
        $this->innerException = $innerException;

        parent::__construct($message, $this->responseCode, $innerException);
    }

    /**
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function toResponse($request)
    {
        return response()->json([
            'status' => $this->responseCode,
            'message' => $this->message,
            'exception' => $this->innerException->getTrace()
        ], 500);
    }
}
