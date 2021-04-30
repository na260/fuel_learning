<?php

class Model_Wholesale_Holiday extends \Orm\Model
{
	protected static $_properties = array(
		"id" => array(
			"label" => "Id",
			"data_type" => "int",
		),
		"wholesale_id" => array(
			"label" => "Wholesale id",
			"data_type" => "int",
		),
		"title" => array(
			"label" => "Title",
			"data_type" => "varchar",
		),
		"start" => array(
			"label" => "Start",
			"data_type" => "date",
		),
		"deleted" => array(
			"label" => "Deleted",
			"data_type" => "int",
		),
		"created_at" => array(
			"label" => "Created at",
			"data_type" => "int",
		),
		"updated_at" => array(
			"label" => "Updated at",
			"data_type" => "int",
		),
	);

	protected static $_observers = array(
		'Orm\Observer_CreatedAt' => array(
			'events' => array('before_insert'),
			'property' => 'created_at',
			'mysql_timestamp' => false,
		),
		'Orm\Observer_UpdatedAt' => array(
			'events' => array('before_update'),
			'property' => 'updated_at',
			'mysql_timestamp' => false,
		),
	);

	protected static $_table_name = 'wholesale_holidays';

	protected static $_primary_key = array('id');

	protected static $_has_many = array(
	);

	protected static $_many_many = array(
	);

	protected static $_has_one = array(
	);

	protected static $_belongs_to = array(
	);


    // public function get_list($date, $limit=5, $offset=0)
    public function get_list()
    {
        $ret = DB::select()->
                from(self::$_table_name)->
                // where('published', '>=', $date->format('Y-m-d H:i:s'))->
                // order_by('published', 'desc')->
                // limit($limit)->
                // offset($offset)->
                execute();

        return $ret;
    }


}
