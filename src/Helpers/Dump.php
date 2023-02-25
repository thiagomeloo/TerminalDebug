<?php

namespace ThiagoMeloo\TerminalDebug\Helpers;

class Dump
{
  /**
   * Dump data to convert to string and trait one or more values.
   * 
   * @param mixed ...$values
   */
  public static function toString(...$values): string
  {
    if (count($values) === 1) {
      return self::dump($values[0]);
    }

    return self::dump($values);
  }

  /**
   * Dump data to string
   * 
   * @param mixed $data
   */
  protected static function dump($data)
  {
    ob_start();
    var_dump($data);
    $data = ob_get_clean();
    return $data;
  }
}
