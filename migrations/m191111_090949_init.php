<?php

use yii\db\Migration;

/**
 * Class m191111_090949_init
 */
class m191111_090949_init extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%user}}', [
            'id' => $this->primaryKey(),
            'username' => $this->string()->notNull()->unique(),
            'email' => $this->string()->null(),
            'status' => $this->smallInteger()->notNull()->defaultValue(10),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ], $tableOptions);


        $this->createTable('{{%customer}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull()->unique(),
            'status' => $this->integer()->notNull()->defaultValue(0),
        ], $tableOptions);


        $this->createTable('{{%history}}', [
            'id' => $this->primaryKey(),
            'ins_ts' => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
            'customer_id' => $this->integer(),
            'user_id' => $this->integer(),
            'event' => $this->string()->notNull(),
            'object' => $this->string(),
            'object_id' => $this->integer(),
            'message' => $this->text()->null(),
            'detail' => $this->text(),
        ], $tableOptions);

        $this->addForeignKey('fk_history__customer_id', 'history', 'customer_id', 'customer', 'id', 'RESTRICT', 'CASCADE');
        $this->addForeignKey('fk_history__user_id', 'history', 'user_id', 'user', 'id', 'RESTRICT', 'CASCADE');


        $this->createTable('{{%call}}', [
            'id' => $this->primaryKey(),
            'ins_ts' => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
            'direction' => $this->smallInteger()->notNull(),
            'user_id' => $this->integer()->null(),
            'customer_id' => $this->integer()->notNull(),
            'status' => $this->smallInteger()->notNull()->defaultValue(0),
            'phone_from' => $this->string()->null(),
            'phone_to' => $this->string()->null(),
            'comment' => $this->text(),
        ], $tableOptions);

        $this->addForeignKey('fk_call__user_id', 'call', 'user_id', 'user', 'id', 'RESTRICT', 'CASCADE');
        $this->addForeignKey('fk_call__customer_id', 'call', 'customer_id', 'customer', 'id', 'RESTRICT', 'CASCADE');


        $this->createTable('{{%fax}}', [
            'id' => $this->primaryKey(),
            'ins_ts' => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
            'user_id' => $this->integer(),
            'from' => $this->string(),
            'to' => $this->string(),
            'direction' => $this->smallInteger()->notNull()->defaultValue(0),
            'type' => $this->string(),
        ], $tableOptions);

        $this->addForeignKey('fk_fax__user_id', 'fax', 'user_id', 'user', 'id', 'RESTRICT', 'CASCADE');


        $this->createTable('{{%sms}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->null(),
            'customer_id' => $this->integer()->notNull(),
            'status' => $this->smallInteger()->notNull()->defaultValue(0),
            'phone_from' => $this->string()->null(),
            'phone_to' => $this->string()->null(),
            'message' => $this->text(),
            'direction' => $this->tinyInteger(),
        ], $tableOptions);

        $this->addForeignKey('fk_sms__user_id', 'sms', 'user_id', 'user', 'id', 'RESTRICT', 'CASCADE');
        $this->addForeignKey('fk_sms__customer_id', 'sms', 'customer_id', 'customer', 'id', 'RESTRICT', 'CASCADE');


        $this->createTable('{{%task}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'customer_id' => $this->integer()->notNull(),
            'status' => $this->smallInteger()->notNull()->defaultValue(0),
            'title' => $this->string()->notNull(),
            'text' => $this->text(),
            'due_date' => $this->dateTime(),
            'priority' => $this->smallInteger(),
        ], $tableOptions);

        $this->addForeignKey('fk_task__user_id', 'task', 'user_id', 'user', 'id', 'RESTRICT', 'CASCADE');
        $this->addForeignKey('fk_task__customer_id', 'task', 'customer_id', 'customer', 'id', 'RESTRICT', 'CASCADE');


    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%task}}');
        $this->dropTable('{{%sms}}');
        $this->dropTable('{{%fax}}');
        $this->dropTable('{{%call}}');
        $this->dropTable('{{%history}}');
        $this->dropTable('{{%customer}}');
        $this->dropTable('{{%user}}');
    }
}
