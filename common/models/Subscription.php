<?php

namespace common\models;

use yii\db\ActiveRecord;
use yii\db\Query;

class Subscription extends ActiveRecord
{
    public static function tableName(): string
    {
        return '{{%subscription}}';
    }

    public function rules(): array
    {
        return [
            [['author_id', 'phone'], 'required'],
            [['author_id'], 'integer'],
            [['author_id'], 'exist', 'targetClass' => Author::class, 'targetAttribute' => ['author_id' => 'id']],
            [['phone'], 'string', 'max' => 20],
            [['phone'], 'match', 'pattern' => '/^\+?[0-9\s\-\(\)]{7,20}$/'],
        ];
    }

    public function getAuthor(): Query
    {
        return $this->hasOne(Author::class, ['id' => 'author_id']);
    }
}