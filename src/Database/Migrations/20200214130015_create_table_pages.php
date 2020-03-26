<?php

namespace Spreadaurora\ci4_page\Database\Migrations;

use CodeIgniter\Database\Migration;

class Migration_create_table_pages extends Migration
{
    public function up()
    {
        $fields = [
            'id_page'            => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'id_parent'          => ['type' => 'INT', 'constraint' => 11],
            'template'           => ['type' => 'VARCHAR', 'constraint' => 255],
            'active'             => ['type' => 'INT', 'constraint' => 11],
            'no_follow_no_index' => ['type' => 'INT', 'constraint' => 11],
            'slug'               => ['type' => 'VARCHAR', 'constraint' => 255],
            'order'              => ['type' => 'INT', 'constraint' => 11],
            'created_at'         => ['type' => 'DATETIME', 'null' => true],
            'updated_at'         => ['type' => 'DATETIME', 'null' => true],
            'deleted_at'         => ['type' => 'DATETIME', 'null' => true],
        ];

        $this->forge->addField($fields);
        $this->forge->addKey('id_page', true);
        $this->forge->addKey('created_at');
        $this->forge->addKey('updated_at');
        $this->forge->addKey('deleted_at');
        $this->forge->createTable('pages');


        $fields = [
            'page_id_page'      => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'default' => 0],
            'id_lang'           => ['type' => 'INT', 'constraint' => 11],
            'name'              => ['type' => 'VARCHAR', 'constraint' => 255],
            'description_short' => ['type' => 'TEXT'],
            'description'       => ['type' => 'TEXT'],
            'meta_title'        => ['type' => 'VARCHAR', 'constraint' => 255],
            'meta_description'  => ['type' => 'VARCHAR', 'constraint' => 255],
            'meta_description'  => ['type' => 'VARCHAR', 'constraint' => 255],
            'tags'              => ['type' => 'VARCHAR', 'constraint' => 255],
        ];

        $this->forge->addField($fields);
        // $this->forge->addKey(['id_item', 'id_lang'], false, true);
        $this->forge->addKey('id_item');
        $this->forge->addKey('id_lang');
        $this->forge->addForeignKey('page_id_page', 'pages', 'id_page', false, 'CASCADE');
        $this->forge->createTable('pages_langs', true);


        /***** BUILDER ***********/
        $fields = [
            'id_builder'   => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'page_id_page' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'default' => 0],
            'id_field'     => ['type' => 'VARCHAR', 'constraint' => 255],
            'handle'       => ['type' => 'VARCHAR', 'constraint' => 255],
            'class'        => ['type' => 'VARCHAR', 'constraint' => 255],
            'id'           => ['type' => 'VARCHAR', 'constraint' => 128],
            'type'         => ['type' => 'VARCHAR', 'constraint' => 128],
            'options'      => ['type' => 'TEXT'],
            'created_at'   => ['type' => 'DATETIME', 'null' => true],
            'updated_at'   => ['type' => 'DATETIME', 'null' => true],
            'deleted_at'   => ['type' => 'DATETIME', 'null' => true],
        ];

        $this->forge->addField($fields);
        $this->forge->addKey('id_builder', true);
        $this->forge->addKey('created_at');
        $this->forge->addKey('updated_at');
        $this->forge->addKey('deleted_at');
        $this->forge->addForeignKey('page_id_page', 'pages', 'id_page', false, 'CASCADE');
        $this->forge->createTable('builders');


        $fields = [
            'builder_id_builder' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'default' => 0],
            'id_lang'            => ['type' => 'INT', 'constraint' => 11],
            'content'            => ['type' => 'TEXT']
        ];

        $this->forge->addField($fields);
        // $this->forge->addKey(['id_item', 'id_lang'], false, true);
        $this->forge->addKey('builder_id_builder');
        $this->forge->addKey('id_lang');
        $this->forge->addForeignKey('builder_id_builder', 'builders', 'id_builder', false, 'CASCADE');
        $this->forge->createTable('builders_langs', true);
    }

    //--------------------------------------------------------------------

    public function down()
    {
        $this->forge->dropTable('pages');
        $this->forge->dropTable('pages_langs');
        $this->forge->dropTable('builders');
        $this->forge->dropTable('builders_langs');
    }
}
