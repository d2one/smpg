<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "search".
 *
 * @property integer $id
 * @property string $url
 * @property string $type
 * @property integer $resources_count
 * @property string $status
 * @property string $created_at
 * @property string $text
 */
class Search extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'search';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['url', 'type', 'status', 'text'], 'string'],
            [['resources_count'], 'integer'],
            [['created_at'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'url' => 'Url',
            'type' => 'Type',
            'resources_count' => 'Resources Count',
            'status' => 'Status',
            'created_at' => 'Created At',
            'text' => 'Text',
        ];
    }
}
