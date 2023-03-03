<?php

test('dump data to string', function () {
  $data = 'test';
  $dump = ThiagoMeloo\TerminalDebug\Helpers\Dump::toString($data);
  expect($dump)->toBe("string(4) \"test\"\n");
});


test('dump data to array', function () {
  $dump = ThiagoMeloo\TerminalDebug\Helpers\Dump::toString([
    'name' => 'Jhon Doe',
  ]);

  expect($dump)->toBe("array(1) {\n  [\"name\"]=>\n  string(8) \"Jhon Doe\"\n}\n");
});


test('dump data to object', function () {

  $obj = new stdClass();
  $obj->name = 'Jhon Doe';

  $valueInMemory = spl_object_id($obj);

  $dump = ThiagoMeloo\TerminalDebug\Helpers\Dump::toString($obj);

  expect($dump)->toBe("object(stdClass)#$valueInMemory (1) {\n  [\"name\"]=>\n  string(8) \"Jhon Doe\"\n}\n");
});
