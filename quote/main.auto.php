<?php

use Langnang\Module\Api\Api;
use Langnang\Module\Automate\AutometeItem;
use Langnang\Module\Meta\Meta;
use Langnang\Module\Mock\Mock;
use Langnang\Module\Quote\Quote;

global $_AUTOMATE;

class AutoQuoteItem extends AutometeItem
{

  public $name = "Quote";
  function __construct()
  {
    $api_controller = new Api();
    $this->options = $api_controller->select_list([
      'slug' => 'quote',
      'size' => PHP_INT_MAX,
    ])['rows'];
  }
  function start()
  {
    global $_FAKER;
    if (sizeof($this->options) === 0) {
      print('Missing Quote APIs');
      exit();
    }
    $controller = new Quote();

    $row = $_FAKER->randomElement($this->options);

    $result = [];
    $response = WpOrg\Requests\Requests::{$row['method']}($row['url'], (array)$row['headers'], (array)$row['data']);
    $body = json_decode($response->body, true);
    if (!is_null($body)) $response->body = $body;

    switch ($row['response']['type']) {
      case 'object':
        $quote = [];
        $quote['type'] = implode("_", array_slice($row['slug'], 1, sizeof($row['slug']) - 1));
        foreach ((array)$row['response']['corr'] as $key => $value) {
          $quote[$key] = $body[$value];
        }
        $controller->insert_item($quote);
        $result = $quote;
        break;
      case 'array':
        $quotes = array_map(function ($item) use ($row) {
          $quote = [];
          $quote['type'] = implode("_", array_slice($row['slug'], 1, sizeof($row['slug']) - 1));
          foreach ((array)$row['response']['corr'] as $key => $value) {
            $quote[$key] = $item[$value];
          }
          return $quote;
        }, $body);
        $controller->insert_list([$quotes]);
        $result = $quotes;
        break;
      case 'text':
      default:
        $quote = [];
        $quote['type'] = implode("_", array_slice($row['slug'], 1, sizeof($row['slug']) - 1));
        foreach ((array)$row['response']['corr'] as $key => $value) {
          $quote[$key] = $response->{$value};
        }
        $controller->insert_item($quote);
        $result = $quote;
        break;
    }
    return $result;
  }
}

$_AUTOMATE->insert(new AutoQuoteItem());
