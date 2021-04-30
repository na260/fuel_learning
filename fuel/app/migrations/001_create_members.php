<?php
namespace Fuel\Migrations;
 
class Create_members
{
    const TABLE_NAME = 'members';
    public function up()
    {
        // create table
        \DBUtil::create_table(self::TABLE_NAME, [
            'id' => ['constraint' => 11, 'type' => 'int', 'unsigned' => true, 'auto_increment' => true],
            'name' => ['constraint' => 32, 'type' => 'varchar'],
            'type' => ['constraint' => 11, 'type' => 'int', 'unsigned' => true],
            'email' => ['constraint' => 1024, 'type' => 'varchar', 'null' => false],
            'phone_number' => ['constraint' => 11, 'type' => 'int', 'unsigned' => true],
            'status' => ['constraint' => '"active","inactive","banned"', 'type' => 'enum'],
            'created_at' => ['type' => 'datetime'],
            'updated_at' => ['type' => 'datetime', 'null' => true],
            'deleted_at' => ['type' => 'datetime', 'null' => true],
        ], ['id']);
 
        // create index
        \DBUtil::create_index(self::TABLE_NAME, 
            ['type'],
            'idx_type'
        );
    }
 
    public function down()
    {
        // drop table
        \DBUtil::drop_table(self::TABLE_NAME);
 
        // drop index
        \DBUtil::drop_index(self::TABLE_NAME, 'idx_type');
    }
}
