<?php
namespace Fuel\Migrations;

class Create_Markers
{
    const TABLE = 'markers';

	public function up()
	{
		\DBUtil::create_table(
			self::TABLE,
			[
                'id' => ['type' => 'int', 'auto_increment' => true],
                'name' => ['type' => 'varchar', 'constraint' => 255],
                'x' => ['type' => 'int'],
                'y' => ['type' => 'int', 'null' => true],
                'z' => ['type' => 'int'],
				'created_at' => ['type' => 'datetime'],
			],
			['id'],
			false,
			'InnoDB',
			'utf8mb4_general_ci'
		);
	}

	public function down()
	{
		\DBUtil::drop_table(self::TABLE);
	}
}
