<?php

class Fixture_Users
{
  protected static $_testable_properties = [
    'id',
    'name',
  ];

  public static function provideTestableProperties()
  {
    return self::$_testable_properties;
  }

  public static function defaultDataset()
  {
    return [
      'users' => [
        [
          'id' => 1,
          'name' => NAME
        ]
      ]
    ];
  }

  public static function expectedDataset()
  {
    return [
      'users' => [
        [
          'id' => 2,
          'name' => 'kazuma'
        ]
      ]
    ];
  }
}
