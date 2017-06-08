<?php

namespace AppBundle\Controller;

use Symfony\Component\Debug\Exception\FlattenException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Log\DebugLoggerInterface;

class ExceptionController
{
    protected $twig;

    /**
     * @var bool Add esception trace stack content when debug mode is on
     */
    protected $debug;

    public function __construct(\Twig_Environment $twig, $debug)
    {
        $this->twig = $twig;
        $this->debug = $debug;
    }

    /**
     * Converts an Exception to a Response.
     *
     * A "showException" request parameter can be used to force display of an error page (when set to false) or
     * the exception page (when true). If it is not present, the "debug" value passed into the constructor will
     * be used.
     *
     * @param Request $request The request
     * @param FlattenException $exception A FlattenException instance
     * @param DebugLoggerInterface $logger A DebugLoggerInterface instance
     *
     * @return Response
     *
     */
    public function showAction(Request $request, FlattenException $exception, DebugLoggerInterface $logger = null)
    {

        $code = $exception->getStatusCode();
        $message = isset(Response::$statusTexts[$code]) ? Response::$statusTexts[$code] : '';

        $data = array(
            'code' => $code,
            'message' => $message,
        );

        if ($this->debug) {
            $data['data'] = [
                'message' => $exception->getMessage(),
                'file' => $exception->getFile(),
                'line' => $exception->getLine(),
                'trace' => $exception->getTrace(),
            ];
        }

        $headers = [
            'Content-Type' => 'application/json',
        ];

        return new Response(json_encode($data, JSON_PRETTY_PRINT), $code, $headers);
    }
}
