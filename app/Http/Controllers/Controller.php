<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    protected function logToSlack($message, $context = [])
    {
        $slackWebhookUrl = env('SLACK_WEBHOOK_URL');
        if ($slackWebhookUrl) {
            try {
                Http::post($slackWebhookUrl, [
                    'text' => $message . ' ' . json_encode($context),
                ]);
            } catch (\Exception $e) {
                // Log the error locally if Slack notification fails
                Log::error('Failed to send Slack notification', [
                    'message' => $message,
                    'context' => $context,
                    'error' => $e->getMessage(),
                ]);
            }
        }
    }
}
