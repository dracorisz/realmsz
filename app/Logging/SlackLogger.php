<?php
namespace App\Logging;

use Monolog\Logger;
use Monolog\Handler\SlackWebhookHandler;

class SlackLogger
{
    public function __invoke(array $config)
    {
        $logger = new Logger('slack');
        $logger->pushHandler(new SlackWebhookHandler(env('SLACK_WEBHOOK_URL')));
        return $logger;
    }
}
