<?php

namespace Module01;

class Controller_Index extends \Controller
{
	public function action_index()
	{
        return \Response::forge(\Presenter::forge('index/index'));
	}

	public function action_hello()
	{
		return \Response::forge(\Presenter::forge('index/hello'));
	}



    public function action_multiregister()
    {
        $this->format = 'json';
        $response_data_error = [
            'error' => true,
            'msg' => [],
        ];

        return $this->response($response_data_error);
        // return \Response::forge($response_data_error);

    }
}
