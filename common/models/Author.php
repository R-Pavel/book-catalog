<?php

declare(strict_types=1);

namespace common\models;

use yii\base\InvalidConfigException;
use yii\db\ActiveRecord;
use yii\db\Query;

class Author extends ActiveRecord
{
    public static function tableName(): string
    {
        return '{{%author}}';
    }

    public function rules(): array
    {
        return [
            [['full_name'], 'required'],
            [['full_name'], 'string', 'max' => 255],
        ];
    }

    /**
     * @throws InvalidConfigException
     */
    public function getBooks(): Query
    {
        return $this
            ->hasMany(Book::class, ['id' => 'book_id'])
            ->viaTable('{{%book_author}}', ['author_id' => 'id']);
    }

    public function getSubscriptions(): Query
    {
        return $this->hasMany(Subscription::class, ['author_id', 'id']);
    }
}