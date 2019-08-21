<?php
class Controller_Api_Marker extends Controller_Rest
{
	protected $format = 'json';
	private $status_code = 200;

	/**
	 * 全マーカーのJSONデータを出力
	 */
	public function get_list()
	{
		return Model_Marker::get_list();
	}

	/**
	 * 新しいマーカーを作成
	 */
	public function post_create()
	{
		// CSRF token check
		if (! self::check_token()) {
			$this->status_code = 403;
			return ['error' => 'お手数ですが、再度送信してください'];
		}

		$id = Model_Marker::create(
			Input::json('name'),
			Input::json('x'),
			Input::json('y'),
			Input::json('z')
		);
		return [
			'result' => true,
			'id' => $id,
		];
	}

	/**
	 * マーカーを削除
	 */
	public function post_delete()
	{
		// CSRF token check
		if (! self::check_token()) {
			$this->status_code = 403;
			return ['error' => 'お手数ですが、再度送信してください'];
		}

		Model_Marker::delete(Input::json('id'));
		return ['result' => true];
	}

	/**
	 * リクエストヘッダ内のCSRFトークンをチェック
	 */
	private static function check_token()
	{
		$csrf_token = Input::headers('X-CSRF-TOKEN');
		return Security::check_token($csrf_token);
	}

	public function after($response)
	{
		$response = parent::after($response);
		$response->status = $this->status_code;
		return $response;
	}
}
