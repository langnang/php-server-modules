<?php

namespace Langnang\Module\Root;

use Exception;

class RootModel
{
  function __construct(array $args = [])
  {
    if (!is_array($args)) throw new Exception("empty array args.");
    foreach ($this as $name => $init_value) {
      if (!isset($args[$name])) continue;
      $this->__set($name,  $args[$name]);
    }

    if (method_exists($this, '__root__construct')) {
      $this->{'__root__construct'}($args);
    }
  }
  function __set($name, $value)
  {
    if (is_int($this->{$name})) $value = (int)$value;
    elseif (is_bool($this->{$name})) $value = (bool)$value;
    elseif (is_string($this->{$name})) $value = (string)$value;
    elseif (is_float($this->{$name})) $value = (float)$value;
    elseif (is_array($this->{$name})) $value = (array)$value;

    if (method_exists($this, 'set_' . $name)) {
      $this->{'set_' . $name}($value);
    } else if (!property_exists($this, $name)) {
      return;
    } else {
      $this->{$name} = $value;
    }
  }
  function __get($name)
  {
    if (!isset($this->{$name})) return;
    return $this->{$name};
  }
}
