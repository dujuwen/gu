<?php

namespace app\models\base;

use Yii;

/**
 * This is the model class for table "gu_monitor".
 *
 * @property integer $id
 * @property string $code
 * @property integer $status
 * @property string $created_at
 */
class BaseGuMonitor extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'gu_monitor';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['status'], 'integer'],
            [['created_at'], 'safe'],
            [['code'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'code' => '股票代码',
            'status' => '1正常需要监控,2删除不需要监控',
            'created_at' => '创建时间',
        ];
    }
}
