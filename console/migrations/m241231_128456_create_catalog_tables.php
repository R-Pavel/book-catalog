<?php

use yii\db\Migration;

class m241231_128456_create_catalog_tables extends Migration
{
    public function safeUp()
    {
        $this->createTable('{{%book}}', [
            'id'          => $this->primaryKey(),
            'name'        => $this->string(255),
            'year'        => $this->integer(),
            'isbn'        => $this->string(20)->unique(),
            'description' => $this->text(),
            'cover_photo' => $this->string(255),
            'created_at'  => $this->integer(),
            'updated_at'  => $this->integer(),
        ]);

        $this->createTable('{{%author}}', [
            'id'         => $this->primaryKey(),
            'full_name'  => $this->string(255),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
        ]);

        $this->createTable('{{%book_author}}', [
            'book_id'   => $this->integer(),
            'author_id' => $this->integer(),
        ]);

        $this->createTable('{{%subscription}}', [
            'id'         => $this->primaryKey(),
            'author_id'  => $this->integer(),
            'phone'      => $this->string(20),
            'created_at' => $this->integer(),
        ]);
    }

    public function down()
    {
        $this->dropTable('{{%subscription}}');
        $this->dropTable('{{%book_author}}');
        $this->dropTable('{{%author}}');
        $this->dropTable('{{%book}}');
    }
}
