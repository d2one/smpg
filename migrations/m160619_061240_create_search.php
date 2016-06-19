<?php

use yii\db\Migration;

/**
 * Handles the creation for table `search`.
 */
class m160619_061240_create_search extends Migration
{

    //CREATE SEQUENCE search_ids;
    //CREATE TABLE search (id INTEGER PRIMARY KEY DEFAULT NEXTVAL('search_ids'), url VARCHAR, type VARCHAR DEFAULT 'added', resources_count SMALLINT DEFAULT 0, status VARCHAR DEFAULT 'added', created_at timestamptz NOT NULL DEFAULT now(), text VARCHAR);

    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->db->createCommand('CREATE SEQUENCE search_ids')->execute();
        $this->db->createCommand('CREATE TABLE search 
            (
              id INTEGER PRIMARY KEY DEFAULT NEXTVAL(\'search_ids\'), 
              url VARCHAR, 
              type VARCHAR DEFAULT \'added\', 
              resources_count SMALLINT DEFAULT 0, 
              status VARCHAR, 
              created_at timestamptz NOT NULL DEFAULT now(), 
              text VARCHAR
            )')->execute();
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('search');
    }
}
