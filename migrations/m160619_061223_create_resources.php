<?php

use yii\db\Migration;

/**
 * Handles the creation for table `resources`.
 */
class m160619_061223_create_resources extends Migration
{

    //CREATE SEQUENCE resource_ids;
    //CREATE TABLE resources (id INTEGER PRIMARY KEY DEFAULT NEXTVAL('resource_ids'), search_id INTEGER, content VARCHAR);
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->db->createCommand('CREATE SEQUENCE resource_ids')->execute();
        $this->db->createCommand('CREATE TABLE resources (id INTEGER PRIMARY KEY DEFAULT NEXTVAL(\'resource_ids\'), search_id INTEGER, content VARCHAR)')->execute();
        $this->db->createCommand('CREATE INDEX search_ids_index ON resources (search_id)')->execute();
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('resources');
    }
}
