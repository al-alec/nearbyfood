<?php

namespace App\Http\Controllers;

use DefStudio\Telegraph\Models\TelegraphBot;
use Illuminate\Http\Request;
use DefStudio\Telegraph\Models\TelegraphChat;

class TelegramBotController extends Controller
{
    //
  public function index(){


//    $bot = TelegraphBot::first();

//    $telegraph_bot = $bot->registerWebhook()->send();

    $chat = TelegraphChat::first();

    $chat->html("<strong>Hello!<strong>\n\nI'm here!")->send();
  }
}
