<?php

namespace Module01;

class Controller_Index extends \Controller
{
	public function action_index()
	{
		return \Response::forge(\View::forge('sample/index'));
	}

	public function action_hello()
	{
		return \Response::forge(\Presenter::forge('sample/hello'));
	}

}
