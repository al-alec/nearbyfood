<?php

namespace App\Http\Controllers;

use DefStudio\Telegraph\Handlers\WebhookHandler;
use DefStudio\Telegraph\Models\TelegraphBot;
use Illuminate\Http\Request;
use DefStudio\Telegraph\Models\TelegraphChat;

class TelegramBotController extends WebhookHandler
{
    //
  public function index(){


//    $bot = TelegraphBot::first();

//    $telegraph_bot = $bot->registerWebhook()->send();

    $chat = TelegraphChat::first();

    $chat->html("<strong>Hello!<strong>\n\nI'm here!")->send();
  }

  public function hi()
  {
    $this->chat->markdown("*Hi* happy to be here!")->send();
  }
}
