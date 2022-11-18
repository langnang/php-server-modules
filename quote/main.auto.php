<?php

use Langnang\Module\PublicApi\PublicApi;
use Langnang\Module\Automate\AutometeItem;
use Langnang\Module\Meta\Meta;
use Langnang\Module\Mock\Mock;
use Langnang\Module\Quote\Quote;
use Langnang\Module\Content\Content;

global $_AUTOMATE;

class AutoQuoteItem extends AutometeItem
{

  public $name = "Quote";
  function __construct()
  {
    $api_controller = new PublicApi();
    $options = $api_controller->select_list([
      'status' => 'quote',
      'size' => PHP_INT_MAX,
    ])['rows'];

    $this->options = array_map(function ($item) {
      $item['slug'] = explode("_", $item['slug']);
      return $item;
    }, $options);
  }
  function start()
  {
    global $_FAKER;
    if (sizeof($this->options) === 0) {
      print('Missing Quote APIs');
      exit();
    }

    $row = $_FAKER->randomElement($this->options);

    $response = request($row);

    $status = explode("_", $row['status']);

    $row = [
      "type" => "quote",
      "created" => time(),
      "modified" => time(),
      "status" => implode("_", array_slice($status, 1, sizeof($status) - 1))
    ];

    if (strpos($response['headers']['content-type'], 'text/plain;') !== false) {
      $row['title'] = $response['body'];
      $row['text'] = json_encode($response, JSON_UNESCAPED_UNICODE);
    }



    $content_controller = new Content();
    $content = $content_controller->insert_item($row);

    return array_merge($row, $content);

    // switch ($row['response']['type']) {
    //   case 'object':
    //     $quote = [];
    //     $quote['type'] = implode("_", array_slice($row['slug'], 1, sizeof($row['slug']) - 1));
    //     foreach ((array)$row['response']['corr'] as $key => $value) {
    //       $quote[$key] = $body[$value];
    //     }
    //     $controller->insert_item($quote);
    //     $result = $quote;
    //     break;
    //   case 'array':
    //     $quotes = array_map(function ($item) use ($row) {
    //       $quote = [];
    //       $quote['type'] = implode("_", array_slice($row['slug'], 1, sizeof($row['slug']) - 1));
    //       foreach ((array)$row['response']['corr'] as $key => $value) {
    //         $quote[$key] = $item[$value];
    //       }
    //       return $quote;
    //     }, $body);
    //     $controller->insert_list([$quotes]);
    //     $result = $quotes;
    //     break;
    //   case 'text':
    //   default:
    //     $quote = [];
    //     $quote['type'] = implode("_", array_slice($row['slug'], 1, sizeof($row['slug']) - 1));
    //     foreach ((array)$row['response']['corr'] as $key => $value) {
    //       $quote[$key] = $response->{$value};
    //     }
    //     $controller->insert_item($quote);
    //     $result = $quote;
    //     break;
    // }
    // return $result;
  }
}

$_AUTOMATE->insert(new AutoQuoteItem());
