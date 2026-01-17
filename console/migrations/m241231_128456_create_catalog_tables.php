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
        $this->addPrimaryKey('pk_book_author', '{{%book_author}}', ['book_id', 'author_id']);
        $this->addForeignKey('fk_book_author_book',   '{{%book_author}}', 'book_id',   '{{%book}}',   'id', 'CASCADE');
        $this->addForeignKey('fk_book_author_author', '{{%book_author}}', 'author_id', '{{%author}}', 'id', 'CASCADE');

        $this->createTable('{{%subscription}}', [
            'id'         => $this->primaryKey(),
            'author_id'  => $this->integer(),
            'phone'      => $this->string(20),
            'created_at' => $this->integer(),
        ]);
        $this->addForeignKey('fk_subscription_author', '{{%subscription}}', 'author_id', '{{%author}}', 'id', 'CASCADE');
    }

    public function down()
    {
        $this->dropTable('{{%subscription}}');
        $this->dropTable('{{%book_author}}');
        $this->dropTable('{{%author}}');
        $this->dropTable('{{%book}}');
    }
}
