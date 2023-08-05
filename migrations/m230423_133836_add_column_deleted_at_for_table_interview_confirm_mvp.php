<?php

use yii\db\Migration;

/**
 * Class m230423_133836_add_column_deleted_at_for_table_interview_confirm_mvp
 */
class m230423_133836_add_column_deleted_at_for_table_interview_confirm_mvp extends Migration
{
    /**
     * {@inheritDoc}
     */
    public function up()
    {
        $this->addColumn('interview_confirm_mvp', 'deleted_at', $this->integer(11));
    }

    /**
     * @return bool
     */
    public function down(): bool
    {
        return  false;
    }
}
