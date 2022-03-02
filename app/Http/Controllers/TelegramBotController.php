<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DefStudio\Telegraph\Models\TelegraphChat;

class TelegramBotController extends Controller
{
    //
  public function index(){

    /** @var TelegraphChat $chat */

    $chat->html("<strong>Hello!<strong>\n\nI'm here!")->send();
  }
}
