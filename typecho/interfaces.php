<?php

namespace Langnang\Module\Typecho;

interface TypechoInterface
{
  function execute_create_table();
  function execute_drop_table();
  function execute_insert_item($vars);
  function execute_import_list($vars);
  function execute_delete_list($vars);
  function execute_update_item($vars);
  function execute_select_count($vars);
  function execute_select_list($vars);
  function execute_select_tree($vars);
  function execute_select_item($vars);
  function execute_export_list($vars);
}
