<?php

class Model_Users extends \Orm\Model
{
  protected static $_table_name = 'users';

  protected static $_properties = array(
    'id',
    'name',
  );
}
