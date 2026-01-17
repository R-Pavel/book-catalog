<?php

namespace common\models;

use yii\base\InvalidConfigException;
use yii\db\ActiveRecord;
use yii\db\Query;

class Book extends ActiveRecord
{
    public static function tableName(): string
    {
        return '{{book}}';
    }

    public function rules(): array
    {
        return [
            [['name', 'year', 'isbn'], 'required'],
            [['year'], 'integer', 'min' => 1800, 'max' => date('Y')],
            [['name', 'isbn'], 'string', 'max' => 255],
            [['description'], 'string'],
            [['cover_photo'], 'string', 'max' => 255],
        ];
    }

    /**
     * @throws InvalidConfigException
     */
    public function getAuthors(): Query
    {
        return $this
            ->hasMany(Author::class, ['id' => 'author_id'])
            ->viaTable('{{book_author}}', ['book_id' => 'id']);
    }

}