<?php

namespace Langnang\Module\Root;

interface RootInterface
{
  function execute_create_table($vars);
  function execute_drop_table($vars);
  function execute_insert_item($vars);
  function execute_delete_list($vars);
  function execute_update_item($vars);
  function execute_select_list($vars);
  function execute_select_tree($vars);
  function execute_select_item($vars);
}
