<?php
$xpdo_meta_map['CronTabManagerRuleMemberTask']= array (
  'package' => 'crontabmanager',
  'version' => '1.1',
  'table' => 'ctma_task_rules_member_task',
  'extends' => 'xPDOObject',
  'tableMeta' =>
  array (
    'engine' => 'InnoDB',
  ),
  'fields' =>
  array (
    'rule_id' => null,
    'task_id' => null,
  ),
  'fieldMeta' =>
  array (
    'rule_id' =>
    array (
      'dbtype' => 'int',
      'precision' => '10',
      'attributes' => 'unsigned',
      'phptype' => 'integer',
      'null' => false,
      'index' => 'pk',
    ),
    'task_id' =>
    array (
      'dbtype' => 'int',
      'precision' => '10',
      'attributes' => 'unsigned',
      'phptype' => 'integer',
      'null' => false,
      'index' => 'pk',
    ),
  ),
  'indexes' =>
  array (
    'rule' =>
    array (
      'alias' => 'rule',
      'primary' => true,
      'unique' => true,
      'type' => 'BTREE',
      'columns' =>
      array (
        'rule_id' =>
        array (
          'length' => '',
          'collation' => 'A',
          'null' => false,
        ),
        'task_id' =>
        array (
          'length' => '',
          'collation' => 'A',
          'null' => false,
        ),
      ),
    ),
    'rule_id' =>
    array (
      'alias' => 'rule_id',
      'primary' => false,
      'unique' => false,
      'type' => 'BTREE',
      'columns' =>
      array (
        'rule_id' =>
        array (
          'length' => '',
          'collation' => 'A',
          'null' => false,
        ),
      ),
    ),
    'task_id' =>
    array (
      'alias' => 'task_id',
      'primary' => false,
      'unique' => false,
      'type' => 'BTREE',
      'columns' =>
      array (
        'task_id' =>
        array (
          'length' => '',
          'collation' => 'A',
          'null' => false,
        ),
      ),
    ),
  ),
  'aggregates' =>
  array (
    'Rule' =>
    array (
      'class' => 'CronTabManagerRule',
      'local' => 'rule_id',
      'foreign' => 'id',
      'cardinality' => 'one',
      'owner' => 'foreign',
    ),
    'Task' =>
    array (
      'class' => 'CronTabManagerTask',
      'local' => 'task_id',
      'foreign' => 'id',
      'cardinality' => 'one',
      'owner' => 'foreign',
    ),
  ),
);
