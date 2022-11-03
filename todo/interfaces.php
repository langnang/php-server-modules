<?php

namespace Langnang\Module\Todo;

interface TodoInterface
{
  function insert();
  function delete();
  function update();
  function select();
}
