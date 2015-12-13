<?php

class DbTestCase extends \PHPUnit_Extensions_Database_TestCase
{
  protected function getConnection()
  {
    $db = \Database_Connection::instance();
    return $this->createDefaultDBConnection($db->connection(), 'users');
  }

  protected function getDataSet() {}
}
