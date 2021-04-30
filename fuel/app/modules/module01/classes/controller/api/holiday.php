<?php

namespace Module01;

/**
 * バイヤー管理システム 販売設定
 * API
 *
 */
class Controller_Api_Holiday extends \Controller_Rest
{
    // format
    protected $format = 'json';
    

    public function get_list()
    {
        $res = [
            'aaa' => 'bbb',
            'bbb' => 'ccc',
        ];
        return $this->response($res);
    }

    /**
     * 選択した新品のデータを複数追加
     *
     * @return type
     */
    public function post_multiregister()
    {
        $response_data_error = [
            'error' => true,
            'msg' => [],
        ];

        $wholesale_id = 14;

        $post = \Input::post();
        $res = [];
        foreach($post['start'] as $p) {
            $tmp['wholesale_id'] = $wholesale_id;
            $tmp['start'] = $p;
            $res[] = $tmp;
        }

        return $this->response($res);

print_r(\Input::post());exit;
        try
        {
            // バリデーションルール設定
            $validation = \Validation::forge();
            $validation->add_field('start', '休業日', 'trim|valid_date');
// $post 
            // バリデーションチェック
            if (!$validation->run(\Input::post()))
            {
                // ここに入ったら、バリデーションエラー
                $errors = $validation->error();
                foreach($errors as $field => $error)
                {
                    $response_data_error['msg'][$field] = $error->get_message();
                }
                \Session::set_flash('error', $response_data_error['msg']);
                // エラーをリターン
                return $this->response($response_data_error);
            }
            // 上記で、バリデーションOKなら、変数に格納
            extract($validation->validated(), EXTR_SKIP);
            
            // foreach内でDML発効前にトランザクションスタート。ちゃんとDB指定する。
            \DB::start_transaction('default');
            
            // モデル呼び出し
            $buyer_index_repos = \Wholesale\Model_Buyer_Index::forge();
            
            // 選択された商品が、すでに新品出品されているか、ひとつずつ精査が必要
            $success_messages = $error_messages = [];
            // 選択された商品が、すでに新品出品されているかフラグ
            $duplicate_flag = false;
            // 選択された商品が、すでに新品出品されているか、ひとつずつ精査が必要
            foreach ($jan_codes as $jan_code)
            {
                // jancodeで一件取得
                $search_m_ds_item_catalog_data = $buyer_index_repos->search_m_ds_item_catalog(['jan_code'=>$jan_code])
                    ->current();
                // 追加のバリデーション
                if (count($search_m_ds_item_catalog_data)==0)
                {
                    $error_messages[] = sprintf('JANコードの値が不正です。（JAN:%s）', $jan_code);
                    continue;
                }
                
                // 登録する予定のデータが登録済みかどうか判定する
                $resultcheck = $buyer_index_repos->search_t_gen_item($wholesale_id, ['jan_code'=>$jan_code]);
                // リターン配列が０で無いなら、既に出品済み
                if (count($resultcheck)>0)
                {
                    // 既に、新品出品されているかフラグ
                    $duplicate_flag = true;
                    // エラーMSGの登録
                    $error_messages[] = sprintf('%s（JAN:%s）は、既に登録されています。', $search_m_ds_item_catalog_data['product_name'], $jan_code);
                    // つづく
                    continue;
                }
                
                // t_gen_itemに新品出品の登録処理
                list($last_insert_id, $affected_rows) = $buyer_index_repos->insert_t_gen_item([
                    'wholesale_id' => $wholesale_id,
                    'jan_code' => $jan_code,
                    'item_name' => $search_m_ds_item_catalog_data['product_name'],
                    'item_kana' => $search_m_ds_item_catalog_data['yj_code']
                        . $search_m_ds_item_catalog_data['product_name_s_byte']
                        . $jan_code
                    ,
                    'item_spec' => $search_m_ds_item_catalog_data['product_unit'],
                    'item_pkg' => $search_m_ds_item_catalog_data['pkg_figure'] . ' '
                        . $search_m_ds_item_catalog_data['pkg_unit'] . ' '
                        . $search_m_ds_item_catalog_data['pkg_unit_unit']
                    ,
                    'item_orig_drug_name' => $search_m_ds_item_catalog_data['product_generic_term'],
                    'item_orig_drug_kana' => $search_m_ds_item_catalog_data['product_sort_name'],
                    'item_price_pkg_dp' => $search_m_ds_item_catalog_data['price_pkg_dp'],
                    'item_maker_name' => $search_m_ds_item_catalog_data['maker_name'] . '/'
                        . $search_m_ds_item_catalog_data['seller_name']
                    ,
                    'item_maker_kana' => $search_m_ds_item_catalog_data['seller_name_kana'],
                    'sale_rate' => 80,// insert時のデフォルト初期値
                    'sale_price' => 80 * (int)$search_m_ds_item_catalog_data['price_pkg_dp'],//CSVからinsertはこうなってる
                    'type' => $search_m_ds_item_catalog_data['yj_code'] . '/'
                        . $search_m_ds_item_catalog_data['maker_name'] . '/'
                        . $search_m_ds_item_catalog_data['seller_name'] . '/'
                        . $search_m_ds_item_catalog_data['pkg_unit'] . '/'
                        . $search_m_ds_item_catalog_data['pkg_unit_unit']
                    ,
                    'item_sts' => '停止中',
                    'stock_qty' => 0,
                    'sellout_qty' => 0,
                    'created_at' => \Date::forge()->format('mysql'),
                    'updated_at' => \Date::forge()->format('mysql'),
                ]);
                // 上記に続き、商品ステータス変更ログ登録
                $result_insert_l_gen_item_sts_log = $buyer_index_repos->insert_l_gen_item_sts_log([
                    'gen_item_id' => $last_insert_id,//上の結果の連番ID
                    'old_item_sts' => '-',
                    'new_item_sts' => '停止中',
                    'created_at' => \Date::forge()->format('mysql'),
                    'updated_at' => \Date::forge()->format('mysql'),
                ]);
                
                // 成功したやつを入れる
                $success_messages[] = sprintf('%s（JAN:%s）', $search_m_ds_item_catalog_data['product_name'], $jan_code);
            }// END foreach
            
            // 既に出品済みのものは未insertでスルー。新規出品だけinsertされてるのでコミット。ちゃんとDB指定する。
            \DB::commit_transaction('default');
            
            // 成功メッセージをセッションに格納する
            if ($success_messages)
            {
                array_unshift($success_messages, '新品の出品登録に成功しました。');
                \Session::set_flash('success', $success_messages);
            }
            // エラーメッセージをセッションに格納する
            if ($error_messages)
            {
                // 既に登録済みがひとつでもあれば、
                if ($duplicate_flag)
                    $error_messages[] = 'こちらの既に登録済みの商品を確認してください。';
                \Session::set_flash('error', $error_messages);
            }
            // レスポンスをリターン
            return $this->response([
                'success' => true,
                'success_messages' => $success_messages,
                'error_messages' => $error_messages,
            ]);
        }
        catch (\Exception $ex)
        {
            // ロールバック。ちゃんとDB指定する。
            \DB::rollback_transaction('default');
            // msgだけを配列で渡している。もらう側は配列チェックが必要。
            $response_data_error['msg'][] = $ex->getMessage();
            // ViewのMSG表示部分にセッション経由でMSGを渡す。
            \Session::set_flash('error', $response_data_error['msg']);
            // レスポンスのリターン
            return $this->response($response_data_error);
        }
    }
    

}
