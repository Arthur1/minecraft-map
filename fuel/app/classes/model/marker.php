<?php
class Model_Marker
{
	// テーブル名
	const TABLE = 'markers';

	/**
	 * 全レコードを取得
	 */
	public static function get_list(): array
	{
		$query = DB::select()
					->from(self::TABLE)
					->order_by('id', 'asc');
		return $query->execute()->as_array();
	}

	/**
	 * レコードを追加
	 */
	public static function create(string $name, int $x, int $y, int $z): int
	{
		$query = DB::insert(self::TABLE)
					->set([
						'name' => $name,
						'x' => $x,
						'y' => $y,
						'z' => $z,
						'created_at' => date('Y-m-d H:i:s'),
					]);
		return $query->execute()[0];
	}

	/**
	 * レコードを削除
	 */
	public static function delete(int $id)
	{
		$query = DB::delete(self::TABLE)
					->where('id', '=', $id);
		$query->execute();
	}
}
