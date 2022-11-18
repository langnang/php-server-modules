<?php

namespace Langnang\Module\Quote;

use Langnang\Module\PublicApi\PublicApi;
use Langnang\Module\Root\RootController;
use WpOrg\Requests\Requests;

require_once __DIR__ . '/../.mysql/mysql.php';
require_once __DIR__ . '/models.php';

class Quote extends RootController
{
  protected $_class = __CLASS__;
  protected $_table_path = __DIR__ . '/table.json';


  function crawler_rand()
  {
    $meta_controller = new PublicApi();
    $row = $meta_controller->select_rand([
      'slug' => 'quote',
    ]);

    $response = Requests::{$row['method']}($row['url'], (array)$row['headers'], (array)$row['data']);
    $body = json_decode($response->body, true);

    if (!is_null($body)) $response->body = $body;

    switch ($row['response']['type']) {
      case 'object':
        $quote = [];
        $quote['type'] = implode("_", array_slice($row['slug'], 1, sizeof($row['slug']) - 1));
        foreach ((array)$row['response']['corr'] as $key => $value) {
          $quote[$key] = $body[$value];
        }
        $this->insert_item($quote);
        return $quote;
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
        $this->insert_list([$quotes]);
        return $quotes;
        break;
      case 'text':
      default:
        $quote = [];
        $quote['type'] = implode("_", array_slice($row['slug'], 1, sizeof($row['slug']) - 1));
        foreach ((array)$row['response']['corr'] as $key => $value) {
          $quote[$key] = $response->{$value};
        }
        $this->insert_item($quote);
        return $quote;
        break;
    }
  }
}
