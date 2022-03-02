<?php

use DefStudio\Telegraph\Handlers\EmptyWebhookHandler;
use DefStudio\Telegraph\Telegraph;

return [
    /*
     * Sets Telegraph messages default parse mode
     * allowed values: html|markdown
     */
    'default_parse_mode' => Telegraph::PARSE_HTML,

    /*
     * Sets the handler to be used when Telegraph
     * receives a new webhook call.
     *
     * For reference, see https://def-studio.github.io/telegraph/webhooks/overview
     */
    'webhook_handler' => \App\Http\Controllers\TelegramBotController::class,

    /*
     * If enabled, Telegraph dumps received
     * webhook messages to logs
     */
    'debug_mode' => true,
];
