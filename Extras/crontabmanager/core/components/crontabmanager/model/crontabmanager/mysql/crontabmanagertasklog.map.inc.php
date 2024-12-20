<?php
$xpdo_meta_map['CronTabManagerTaskLog']= array (
  'package' => 'crontabmanager',
  'version' => '1.1',
  'table' => 'ctma_task_log',
  'extends' => 'xPDOSimpleObject',
  'tableMeta' => 
  array (
    'engine' => 'InnoDB',
  ),
  'fields' => 
  array (
    'task_id' => 0,
    'last_run' => 0,
    'end_run' => 0,
    'createdon' => 0,
    'updatedon' => 0,
    'completed' => 0,
    'notification' => 0,
    'ignore_action' => 0,
    'memory_usage' => 0.0,
    'exec_time' => 0.0,
    'hash' => '',
  ),
  'fieldMeta' => 
  array (
    'task_id' => 
    array (
      'dbtype' => 'int',
      'precision' => '10',
      'phptype' => 'integer',
      'null' => true,
      'default' => 0,
    ),
    'last_run' => 
    array (
      'dbtype' => 'int',
      'precision' => '10',
      'phptype' => 'integer',
      'null' => true,
      'default' => 0,
    ),
    'end_run' => 
    array (
      'dbtype' => 'int',
      'precision' => '10',
      'phptype' => 'integer',
      'null' => true,
      'default' => 0,
    ),
    'createdon' => 
    array (
      'dbtype' => 'int',
      'precision' => '20',
      'phptype' => 'timestamp',
      'null' => false,
      'default' => 0,
    ),
    'updatedon' => 
    array (
      'dbtype' => 'int',
      'precision' => '20',
      'phptype' => 'timestamp',
      'null' => false,
      'default' => 0,
    ),
    'completed' => 
    array (
      'dbtype' => 'tinyint',
      'precision' => '1',
      'phptype' => 'boolean',
      'null' => true,
      'default' => 0,
    ),
    'notification' => 
    array (
      'dbtype' => 'tinyint',
      'precision' => '1',
      'phptype' => 'boolean',
      'null' => true,
      'default' => 0,
    ),
    'ignore_action' => 
    array (
      'dbtype' => 'tinyint',
      'precision' => '1',
      'phptype' => 'boolean',
      'null' => true,
      'default' => 0,
    ),
    'memory_usage' => 
    array (
      'dbtype' => 'decimal',
      'precision' => '13,3',
      'phptype' => 'float',
      'null' => true,
      'default' => 0.0,
    ),
    'exec_time' => 
    array (
      'dbtype' => 'decimal',
      'precision' => '13,3',
      'phptype' => 'float',
      'null' => true,
      'default' => 0.0,
    ),
    'hash' => 
    array (
      'dbtype' => 'char',
      'precision' => '32',
      'phptype' => 'string',
      'null' => true,
      'default' => '',
      'index' => 'index',
    ),
  ),
  'indexes' => 
  array (
    'last_run' => 
    array (
      'alias' => 'last_run',
      'primary' => false,
      'unique' => false,
      'type' => 'BTREE',
      'columns' => 
      array (
        'last_run' => 
        array (
          'length' => '',
          'collation' => 'A',
          'null' => false,
        ),
      ),
    ),
    'end_run' => 
    array (
      'alias' => 'end_run',
      'primary' => false,
      'unique' => false,
      'type' => 'BTREE',
      'columns' => 
      array (
        'end_run' => 
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
    'ignore_action' => 
    array (
      'alias' => 'ignore_action',
      'primary' => false,
      'unique' => false,
      'type' => 'BTREE',
      'columns' => 
      array (
        'ignore_action' => 
        array (
          'length' => '',
          'collation' => 'A',
          'null' => false,
        ),
      ),
    ),
    'completed' => 
    array (
      'alias' => 'completed',
      'primary' => false,
      'unique' => false,
      'type' => 'BTREE',
      'columns' => 
      array (
        'completed' => 
        array (
          'length' => '',
          'collation' => 'A',
          'null' => false,
        ),
      ),
    ),
    'notification' => 
    array (
      'alias' => 'notification',
      'primary' => false,
      'unique' => false,
      'type' => 'BTREE',
      'columns' => 
      array (
        'notification' => 
        array (
          'length' => '',
          'collation' => 'A',
          'null' => false,
        ),
      ),
    ),
    'createdon' => 
    array (
      'alias' => 'createdon',
      'primary' => false,
      'unique' => false,
      'type' => 'BTREE',
      'columns' => 
      array (
        'createdon' => 
        array (
          'length' => '',
          'collation' => 'A',
          'null' => false,
        ),
      ),
    ),
    'hash' => 
    array (
      'alias' => 'hash',
      'primary' => false,
      'unique' => false,
      'type' => 'BTREE',
      'columns' => 
      array (
        'hash' => 
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
