<?php

namespace app\models;

use yii\db\ActiveRecord;

/**
 * This is the model class for table "requests".
 *
 * @property int $id
 * @property int $user_id
 * @property int $amount
 * @property int $term
 * @property string $status
 * @property int $created_at
 * @property int $updated_at
 */
class Request extends ActiveRecord
{
    public static function tableName()
    {
        return 'requests';
    }

    public function rules()
    {
        return [
            [['user_id', 'amount', 'term'], 'required'],
            [['user_id', 'amount', 'term', 'created_at', 'updated_at'], 'integer'],
            [['status'], 'string', 'max' => 20],
            [['status'], 'default', 'value' => 'pending'],
        ];
    }

    public function beforeSave($insert)
    {
        $time = time();
        if ($insert) {
            $this->created_at = $time;
        }
        $this->updated_at = $time;
        return parent::beforeSave($insert);
    }
}
