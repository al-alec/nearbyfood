<?php

namespace App\Http\Controllers;

use DefStudio\Telegraph\Handlers\WebhookHandler;
use DefStudio\Telegraph\Models\TelegraphChat;
use GuzzleHttp\Exception\TransferException;
use GuzzleHttp\Promise\Utils;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Stringable;
use Telegram\Bot\Actions;
use Telegram\Bot\FileUpload\InputFile;
use Telegram\Bot\Keyboard\Keyboard;

class TelegramBotController extends WebhookHandler
{


  private $html = "<b>Salut, moi c'est alec</b> 🤖
      \nVous avez faim et vous etes fatigué de manger les meme bouffe chaque jour 😫?
      \nVous n'avez pas un grand budget mais vous voulez bien manger 🥯🥗
      \nJe vais a trouver les restaurants, ou petit vendeur ( ayimolou, veyi, Frites, Spaghetti, Poisson braisé, pinon etc..) les plus proche de vous 🍴😋
      \nVous recherchez egalement un endroit calme pour passer du temps avec votre partenaire? 😍🥰👩‍❤️‍👨  Ou encore un(e) ami(e) pour discuter affaire? 💵📧
      \n Je vais vous aider quelque soit votre budget, alors let's go. Explorez par vous meme";

  private $wantToEatText = '🥗 je veux manger';
  private $rechercherText = '🔎 rechercher';
  private $lookLieuText = '🚶🏽‍♂ Trouver un lieu chic pour une sortie ou reunion';
  private $parcourirRestauText = '🍽 parcourir les restau';
  private $parcourirBouffeText = '🍝 parcourir les bouffes';
  private $quoteText = '🧘🏽‍♂️📜 citation inspiration';
  private $prestataireText = 'trouver un prestataire de service';
  private $inscriptionText = '🔏 s\'inscrire';
  private $myspaceText = '🗂 mon espace';
  private $menu;

  public function __construct()
  {
    $this->menu = [
      [$this->wantToEatText, $this->rechercherText],
      [$this->lookLieuText],
      [$this->parcourirRestauText, $this->parcourirBouffeText],
      [$this->prestataireText],
      [$this->inscriptionText, $this->myspaceText],
      [$this->quoteText]
    ];
  }


  public function help()
  {
    $this->start();
  }

  public function actionhandlebudget(){

    $id = $this->data->get('id');

    $response = \Telegram::sendMessage([
      'chat_id' => $this->chat->chat_id,
      'text' => '<b>Bien regarde et fais ton choix</b>',
      'parse_mode' => 'HTML',
    ]);

    $response = \Telegram::sendMessage([
      'chat_id' => $this->chat->chat_id,
      'text' => '<b>.....................</b>',
      'parse_mode' => 'HTML',
    ]);

    $this->parcourirBouffe();
  }

  public function handleBudget(){
    try {

      $keyboard = Keyboard::make()
        ->inline()
        ->row(
          Keyboard::inlineButton(['text' => '100f - 300f', 'callback_data' => 'action:actionhandlebudget;id:1'])
        )
        ->row(
          Keyboard::inlineButton(['text' => '300f - 500f', 'callback_data' => 'action:actionhandlebudget;id:2'])
        )
        ->row(
          Keyboard::inlineButton(['text' => '500f - 1 000f', 'callback_data' => 'action:actionhandlebudget;id:3'])
        )
        ->row(
          Keyboard::inlineButton(['text' => '1 000f - 1 500f', 'callback_data' => 'action:actionhandlebudget;id:4'])
        )
        ->row(
          Keyboard::inlineButton(['text' => '1 500f - 2 500f', 'callback_data' => 'action:actionhandlebudget;id:5'])
        )
        ->row(
          Keyboard::inlineButton(['text' => '2 500f - 3 500f', 'callback_data' => 'action:actionhandlebudget;id:6'])
        )
        ->row(
          Keyboard::inlineButton(['text' => '3 500f - 5 000f', 'callback_data' => 'action:actionhandlebudget;id:7'])
        )
        ->row(
          Keyboard::inlineButton(['text' => '5 000f - 10 000f', 'callback_data' => 'action:actionhandlebudget;id:8'])
        )
        ->row(
          Keyboard::inlineButton(['text' => '> 10 000f', 'callback_data' => 'action:actionhandlebudget;id:9'])
        );

      $response = \Telegram::sendMessage([
        'chat_id' => $this->chat->chat_id,
        'text' => '<b>Quel est ton budget?</b>',
        'parse_mode' => 'HTML',
        'reply_markup' => $keyboard,
      ]);

    }
    catch (\Exception $e) {
      Log::info("alec check this in handleBudget");
      Log::error($e->getMessage());
    }
  }

  protected function handleChatMessage(Stringable $text): void
  {
    switch ($text){
      case $this->parcourirBouffeText:
        $this->handleBudget();
        break;
      case $this->quoteText:
        $this->sendInspireQuote();
        break;
      default:
        if($text == $this->wantToEatText ||
          $text == $this->rechercherText ||
          $text == $this->lookLieuText ||
          $text == $this->parcourirRestauText ||
          $text == $this->parcourirBouffeText ||
          $text == $this->prestataireText ||
          $text == $this->inscriptionText ||
          $text == $this->myspaceText ||
          $text == $this->quoteText){
          $response = \Telegram::sendMessage([
            'chat_id' => $this->chat_id(),
            'text' => '<em>Cette fonctionnalité arrive bientot 🤗🤗</em>',
            'parse_mode' => 'HTML',
          ]);
        }
        else{
          $response = \Telegram::sendMessage([
            'chat_id' => $this->chat_id(),
            'text' => '<em>Désolé, je n\'ai pas compris 🙄</em>',
            'parse_mode' => 'HTML',
          ]);
        }

        break;
    }

//    $messageId = $response->getMessageId();

  }

  public function handleResultQuote($quote){
    Log::info("ici la response");
    Log::info($quote);
  }

  private function sendInspireQuote(){

    $url = 'https://zenquotes.io/api/random';
    $nbPages = 1;
    $promises = [];
    \Telegram::sendChatAction([
      'chat_id' => $this->chat_id(),
      'action' => Actions::TYPING
    ]);

    for ($page=0 ; $page < $nbPages ; $page++) {
      $promises[] = Http::async()->get($url);
    }

// Wait for the responses to be received
    $responses = Utils::unwrap($promises);


    $keyboard = Keyboard::make()
      ->inline()
      ->row(
        Keyboard::inlineButton(['text' => 'Test', 'callback_data' => 'data']),
        Keyboard::inlineButton(['text' => 'Btn 2', 'callback_data' => 'data_from_btn2'])
      );

    $keyboard = Keyboard::make()
      ->inline()
      ->row(
        Keyboard::inlineButton(['text' => '👍🏽', 'callback_data' => 'data']),
        Keyboard::inlineButton(['text' => '❤️', 'callback_data' => 'data']),
        Keyboard::inlineButton(['text' => '👎🏽', 'callback_data' => 'data'])
      );

    \Telegram::sendMessage([
      'chat_id' => $this->chat_id(),
      'text' =>'<em><b>'.$responses[0][0]['q'].'</b></em> '.$responses[0][0]['a'],
      'parse_mode' => 'HTML',
      'reply_markup' => $keyboard

    ]);
  }

  private function parcourirBouffe(){

    $plats = [
      ['id' => 1, 'text' => '<b>Ayimolou zozo</b>', 'photo' => 'https://nearbyfood.alexisyehadji.com/img/ayimolou.jpg', 'prix' => 400],
      ['id' => 2, 'text' => '<b>Du bon Khom</b>', 'photo' => 'https://nearbyfood.alexisyehadji.com/img/khom.jpg', 'prix' => 500],
      ['id' => 3, 'text' => '<b>Riz au gras</b>', 'photo' => 'https://nearbyfood.alexisyehadji.com/img/rizgras.jpg', 'prix' => 700],
      ['id' => 4, 'text' => '<b>Salade verte</b>', 'photo' => 'https://nearbyfood.alexisyehadji.com/img/saladeavocat.jpg', 'prix' => 1000],
      ['id' => 5, 'text' => '<b>Salade d\'avocat</b>', 'photo' => 'https://nearbyfood.alexisyehadji.com/img/saladeverte.jpg', 'prix' => 1200],
    ];
    try {

      foreach ($plats as $plat){
        $keyboard = Keyboard::make()
          ->inline()
          ->row(
            Keyboard::inlineButton(['text' => 'choir ce plat', 'callback_data' => 'action:actionhandlechoixplat;id:'.$plat['id']])
          );

        $response = \Telegram::sendPhoto([
          'chat_id' => $this->chat_id(),
          'photo' => InputFile::create($plat['photo'], 'al'),
          'caption' => $plat['text'].'; Prix: '.$plat['prix'],
          'parse_mode' => 'HTML',
          'reply_markup' => $keyboard,

        ]);
      }
    }
    catch (\Exception $e) {
      Log::info("alec check this");
      Log::error($e);
    }
  }


  public function start()
  {

    $chati = TelegraphChat::whereChatId($this->request['message']['from']['id']);

    try {
      if (!$chati) {
        $this->chat = TelegraphChat::create([
          'chat_id' => $this->request['message']['from']['id'],
          'telegraph_bot_id' => 1,
          'name' => $this->request['message']['from']['first_name'],
        ]);
      }

      $reply_markup = \Telegram\Bot\Keyboard\Keyboard::make([
        'keyboard' => $this->menu,
        'resize_keyboard' => true,
        'one_time_keyboard' => true,
        'request_location' => true,
      ]);

      $response = \Telegram::sendMessage([
        'chat_id' => $this->chat_id(),
        'text' => $this->html,
        'parse_mode' => 'HTML',
        'reply_markup' => $reply_markup
      ]);
    } catch (\Exception $e) {
      Log::info("alec check this");
      Log::error($e->getMessage());
    }


//    $this->chat->html($this->html)
//      ->keyboard(
//        Keyboard::make()->buttons([
//          Button::make('🥗 Je veux manger')->action('delete')->param('id', '42'),
//          Button::make('🚶🏽‍♂ Je veux passer du temps quelque part')->url('https://test.it'),
//          Button::make('Je suis un vendeur')->url('https://test.it'),
//          Button::make('🔎 Ou decrivez ce que vous cherchez')->url('https://test.it'),
//
//        ]))
//      ->send();
  }

  public function chat_id()
  {
    return $this->request['message']?$this->request['message']['from']['id']:$this->request['callback_query']['from']['id'];
  }


}
