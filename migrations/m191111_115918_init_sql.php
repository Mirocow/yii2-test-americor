<?php

use yii\db\Migration;

/**
 * Class m191111_115918_init_sql
 */
class m191111_115918_init_sql extends Migration
{
    private $initTables = [
        'customer',
        'user',
        'history',
        'sms',

        'task',
        'call',
        'fax',
    ];

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        foreach ($this->initTables as $table) {
            foreach (file(__DIR__ . '/init/' . $table . '.sql') as $sql) {
                $this->execute($sql);
            }
        }
    }

    public function execute($sql, $params = [])
    {
        return trim($sql) && parent::execute($sql, $params);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        foreach (array_reverse($this->initTables) as $table) {
            $this->delete($table);
        }
    }
}

