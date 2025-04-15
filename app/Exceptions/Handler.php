<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Illuminate\Support\Facades\Http;
use Illuminate\Session\TokenMismatchException;
use Psr\Log\LogLevel;

class Handler extends ExceptionHandler
{
    private $slackWebhookUrl;

    public function __construct()
    {
        $this->slackWebhookUrl = env('SLACK_WEBHOOK_URL');
    }

    private function logToSlack($message, $context = [])
    {
        if ($this->slackWebhookUrl) {
            try {
                Http::post($this->slackWebhookUrl, [
                    'text' => $message . ' ' . json_encode($context),
                ]);
            } catch (\Exception $e) {
                // Silently fail if Slack logging fails
            }
        }
    }

    /**
     * A list of exception types with their corresponding custom log levels.
     *
     * @var array<class-string<\Throwable>, \Psr\Log\LogLevel::*>
     */
    protected $levels = [
        //
    ];

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<\Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        // $this->reportable(function (Throwable $e) {
        //     if ($this->shouldReport($e)) {
        //         $this->logToSlack('Exception reported:', [
        //             'exception' => $e->getMessage(),
        //             'file' => $e->getFile(),
        //             'line' => $e->getLine(),
        //         ]);
        //     }
        // });
    }

    public function render($request, Throwable $exception)
    {
        if ($exception instanceof TokenMismatchException) {
            return redirect()->route('login')->with('error', 'Your session has expired. Please log in again.');
        }
        
        return parent::render($request, $exception);
    }

    public function report(Throwable $e)
    {
        // if ($this->shouldReport($e)) {
        //     $this->logToSlack('Exception reported:', [
        //         'exception' => $e->getMessage(),
        //         'file' => $e->getFile(),
        //         'line' => $e->getLine(),
        //     ]);
        // }
        
        // parent::report($e);
    }
}
