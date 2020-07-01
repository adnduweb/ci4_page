<?php

namespace Adnduweb\Ci4_page\Database\Migrations;

use CodeIgniter\Database\Migration;

class Migration_create_table_page extends Migration
{
    public function up()
    {
        $fields = [
            'id'                 => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'id_parent'          => ['type' => 'INT', 'constraint' => 11, 'default' => 0],
            'template'           => ['type' => 'VARCHAR', 'constraint' => 255],
            'active'             => ['type' => 'INT', 'constraint' => 11],
            'visible_title'      => ['type' => 'INT', 'constraint' => 11, 'default' => 0],
            'no_follow_no_index' => ['type' => 'INT', 'constraint' => 11, 'null' => true],
            'handle'             => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'order'              => ['type' => 'INT', 'constraint' => 11, 'null' => true],
            'created_at'         => ['type' => 'DATETIME', 'null' => true],
            'updated_at'         => ['type' => 'DATETIME', 'null' => true],
            'deleted_at'         => ['type' => 'DATETIME', 'null' => true],
        ];

        $this->forge->addField($fields);
        $this->forge->addKey('id', true);
        $this->forge->addKey('created_at');
        $this->forge->addKey('updated_at');
        $this->forge->addKey('deleted_at');
        $this->forge->createTable('pages');


        $fields = [
            'id_page_lang'      => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'page_id'           => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'default' => 0],
            'id_lang'           => ['type' => 'INT', 'constraint' => 11],
            'name'              => ['type' => 'VARCHAR', 'constraint' => 255],
            'name_2'            => ['type' => 'VARCHAR', 'constraint' => 255],
            'description_short' => ['type' => 'TEXT'],
            'description'       => ['type' => 'TEXT'],
            'meta_title'        => ['type' => 'VARCHAR', 'constraint' => 255],
            'meta_description'  => ['type' => 'VARCHAR', 'constraint' => 255],
            'tags'              => ['type' => 'VARCHAR', 'constraint' => 255],
            'slug'              => ['type' => 'VARCHAR', 'constraint' => 255],
        ];

        $this->forge->addField($fields);
        $this->forge->addKey('id_page_lang', true);
        $this->forge->addKey('id_lang');
        $this->forge->addForeignKey('page_id', 'pages', 'id', false, 'CASCADE');
        $this->forge->createTable('pages_langs', true);


        $fields = [
            'id_404'             => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'source'             => ['type' => 'INT', 'constraint' => 11],
            'url'                => ['type' => 'VARCHAR', 'constraint' => 255],
            'http'               => ['type' => 'INT', 'constraint' => 11],
            'created_at'         => ['type' => 'DATETIME', 'null' => true],
            'updated_at'         => ['type' => 'DATETIME', 'null' => true],
            'deleted_at'         => ['type' => 'DATETIME', 'null' => true],
        ];

        $this->forge->addField($fields);
        $this->forge->addKey('id_404', true);
        $this->forge->addKey('created_at');
        $this->forge->addKey('updated_at');
        $this->forge->addKey('deleted_at');
        $this->forge->createTable('pages_404');
    }

    //--------------------------------------------------------------------

    public function down()
    {
        $this->forge->dropTable('pages');
        $this->forge->dropTable('pages_langs');
        $this->forge->dropTable('pages_404');
    }
}
