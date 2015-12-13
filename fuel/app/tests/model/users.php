<?php

/*
 * @group App
 */
class Test_Users extends DbTestCase 
{
  protected function getDataSet()
  {
    return $this->createArrayDataset(Fixture_Users::defaultDataset());
  }

  public function test_addusers()
  {
    $user = new Model_Users();
    $user->id = 2;
    $user->name = 'kazuma';
    $user->save();

    $testableQuery = 'SELECT '.implode(',', Fixture_Users::provideTestableProperties()).' FROM users';
    $queryTable = $this->getConnection()->createQueryTable(
      'users', $testableQuery
    );
    $expectedDataset = Arr::merge(Fixture_Users::defaultDataset(), Fixture_Users::expectedDataset());
    $properties = Fixture_Users::provideTestableProperties();
    $testableDataset = ['users' => []];
    foreach ($expectedDataset['users'] as $record) {
      $record = Arr::filter_keys($record, $properties);
      array_push($testableDataset['users'], $record);
    }
    $expectedTable = $this->createArrayDataset($testableDataset)->getTable('users');
    $this->assertTablesEqual($expectedTable, $queryTable);
  }
}
