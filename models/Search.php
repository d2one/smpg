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
            ['type', 'string'],
            ['type', 'required'],
            ['status', 'string'],
            [['resources_count'], 'integer'],
            [['created_at'], 'safe'],
            ['url', function ($attribute, $params) {
                if (empty($this->attributes[$attribute])) {
                    $this->addError($attribute, 'Empty url');
                };

                $filtered = filter_var($this->attributes[$attribute], FILTER_VALIDATE_URL);
                if (!$filtered) {
                    $this->addError($attribute, 'Not Valid url');
                }
            }],
            ['text', function ($attribute, $params) {
                if (!empty($this->attributes[$attribute]) && $this->attributes['type'] != 'text') {
                    $this->addError($attribute, 'Not available text for this type');
                };
            }],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'url' => 'Site',
            'type' => 'Search Type',
            'resources_count' => 'Resources Count',
            'status' => 'Status',
            'created_at' => 'Date',
            'text' => 'Text',
        ];
    }
}
