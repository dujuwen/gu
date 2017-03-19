<?php

namespace app\models\base;

use Yii;

/**
 * This is the model class for table "gu_monitor".
 *
 * @property integer $id
 * @property string $code
 * @property integer $status
 * @property integer $orde
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
            [['status', 'orde'], 'integer'],
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
            'orde' => '排序',
            'status' => '状态',
            'created_at' => '创建时间',
        ];
    }
}
