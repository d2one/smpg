<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "resources".
 *
 * @property integer $id
 * @property integer $search_id
 * @property string $content
 */
class Resources extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'resources';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['search_id'], 'integer'],
            [['content'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'search_id' => 'Search ID',
            'content' => 'Content',
        ];
    }
}
