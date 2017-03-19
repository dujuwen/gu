<?php

namespace app\models\base;

use Yii;

/**
 * This is the model class for table "gu_recent".
 *
 * @property integer $id
 * @property string $code
 * @property string $day
 * @property double $final_zjc
 * @property string $created_at
 * @property string $updated_at
 */
class BaseGuRecent extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'gu_recent';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['day'], 'required'],
            [['day', 'created_at', 'updated_at'], 'safe'],
            [['final_zjc'], 'number'],
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
            'day' => '日期',
            'final_zjc' => '今天最终增减仓',
            'created_at' => '创建时间',
            'updated_at' => '更新时间',
        ];
    }
}
