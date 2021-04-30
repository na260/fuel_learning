<?php

namespace Fuel\Migrations;

class Create_wholesale_holidays
{
	public function up()
	{
		\DBUtil::create_table('wholesale_holidays', array(
			'id' => array('type' => 'int', 'unsigned' => true, 'null' => false, 'auto_increment' => true, 'constraint' => 11),
			'wholesale_id' => array('constraint' => 11, 'null' => false, 'type' => 'int'),
			'title' => array('constraint' => 255, 'null' => false, 'type' => 'varchar'),
			'start' => array('null' => false, 'type' => 'date'),
			'deleted' => array('constraint' => 11, 'null' => true, 'type' => 'int'),
			'created_at' => array('constraint' => 11, 'null' => true, 'type' => 'int', 'unsigned' => true),
			'updated_at' => array('constraint' => 11, 'null' => true, 'type' => 'int', 'unsigned' => true),
		), array('id'));
	}

	public function down()
	{
		\DBUtil::drop_table('wholesale_holidays');
	}
}