<?php

namespace App\Http\Controllers;

use DefStudio\Telegraph\Handlers\WebhookHandler;
use DefStudio\Telegraph\Keyboard\Button;
use DefStudio\Telegraph\Keyboard\Keyboard;
use DefStudio\Telegraph\Models\TelegraphBot;
use Illuminate\Http\Request;
use DefStudio\Telegraph\Models\TelegraphChat;

class TelegramBotController extends WebhookHandler
{
  //
  public function index()
  {


//    $bot = TelegraphBot::first();

//    $telegraph_bot = $bot->registerWebhook()->send();

    $chat = TelegraphChat::first();

    $chat->html("<strong>Hello!<strong>\n\nI'm here!")->send();
  }

  public function hi()
  {
    $this->chat->markdown("*Hi* happy to be here!")->send();
  }

  public function help()
  {
    $html = "<b>Salut, moi c'est alec</b> 🤖 \n
      \n
      Vous avez faim et vous etes fatigué de manger les meme bouffe chaque jour 😫?

      Vous n'avez pas un grand budget mais vous voulez bien manger 🥯🥗 \n

      \n
      Je vais a trouver les restaurants, ou petit vendeur ( ayimolou, veyi, Frites, Spaghetti, Poisson braisé, pinon etc..) les plus proche de vous 🍴😋

      \n Vous recherchez egalement un endroit calme pour passer du temps avec votre partenaire? 😍🥰👩‍❤️‍👨  Ou encore un(e) ami(e) pour discuter affaire? 💵📧

      \n Je vais vous aider quelque soit votre budget, alors let's go. Explorez par vous meme";


    $this->chat->html($html)->keyboard(
      Keyboard::make()->buttons([
          Button::make('Je veux manger')->action('delete')->param('id', '42'),
          Button::make('Je veux passer du temps quelque part')->url('https://test.it'),
    ]))->send();
  }

}
