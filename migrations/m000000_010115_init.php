<?php

use yii\db\Schema;
use yii\db\Migration;

class m000000_010115_init extends Migration {

    public function safeUp() {
        $this->createTable('tbl_ym_settings', [
            'id' => 'pk',
            'token' => Schema::TYPE_STRING . " NULL DEFAULT NULL COMMENT'Yandex Token'",
                ], "COMMENT'Module Settings'"
        );
        $this->insert('tbl_ym_settings', ['id' => 1, 'token' => null]);
		$this->insert('tbl_ym_settings', ['id' => 2, 'token' => null]);
		$this->insert('tbl_ym_settings', ['id' => 3, 'token' => null]);
		$this->insert('tbl_ym_settings', ['id' => 4, 'token' => null]);
    }

    public function down() {
        $this->dropTable('tbl_ym_settings');
    }

}
