<?php

use yii\db\Migration;

/**
 * Class m230105_103954_create_table_wish_list
 */
class m230105_103954_create_table_wish_list extends Migration
{
    /**
     * {@inheritDoc}
     */
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'ENGINE=InnoDB CHARSET=utf8';
        }

        $this->createTable('wish_list', [
            'id' => $this->primaryKey(11)->unsigned(),
            'client_id' => $this->integer(11)->notNull(),
            'size' => $this->integer(11)->notNull(),
            'location_id' => $this->integer(11)->notNull(),
            'type_company' => $this->integer(11)->notNull(),
            'type_production' => $this->integer(11)->notNull(),
            'add_info' => $this->text(),
            'completed_at' => $this->integer(11),
            'created_at' => $this->integer(11)->notNull(),
            'updated_at' => $this->integer(11)->notNull(),
        ], $tableOptions);
    }

    /**
     * @return bool
     */
    public function down(): bool
    {
        return  false;
    }
}
