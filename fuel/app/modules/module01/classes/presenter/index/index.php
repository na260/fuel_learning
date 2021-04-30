<?php
namespace Module01;

/**
 * Fuel is a fast, lightweight, community driven PHP 5.4+ framework.
 *
 * @package    Fuel
 * @version    1.8.2
 * @author     Fuel Development Team
 * @license    MIT License
 * @copyright  2010 - 2019 Fuel Development Team
 * @link       https://fuelphp.com
 */

/**
 * The welcome hello presenter.
 *
 * @package  app
 * @extends  Presenter
 */
class Presenter_Index_Index extends \Presenter
{
	/**
	 * Prepare the view data, keeping this in here helps clean up
	 * the controller.
	 *
	 * @return void
	 */
	public function view()
	{

        // $comment = \Model_Comment::forge();
        // $r = $comment->get_list();
        // print_r($r);

        $repos = \Model_Wholesale_Holiday::forge();
        // $repos->get_list();

        $this->set('result', $repos->get_list()->as_array());
		$this->name = $this->request()->param('name', 'World');
	}
}
