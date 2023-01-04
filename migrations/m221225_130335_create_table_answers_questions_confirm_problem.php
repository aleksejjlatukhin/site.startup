<?php

use yii\db\Migration;

/**
 * Class m221225_130335_create_table_answers_questions_confirm_problem
 */
class m221225_130335_create_table_answers_questions_confirm_problem extends Migration
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

        $this->createTable('answers_questions_confirm_problem', [
            'id' => $this->primaryKey(11)->unsigned(),
            'question_id' => $this->integer(11)->notNull(),
            'respond_id' => $this->integer(11)->notNull(),
            'answer' => $this->text()
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
