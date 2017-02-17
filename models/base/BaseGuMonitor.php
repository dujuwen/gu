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

    /**
     CREATE TABLE `gu_monitor` (
      `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
      `code` varchar(255) NOT NULL DEFAULT '' COMMENT '股票代码',
      `status` tinyint(4) NOT NULL DEFAULT '1' COMMENT '1正常需要监控,2删除不需要监控',
      `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
      PRIMARY KEY (`id`),
      UNIQUE KEY `ix_uni_code` (`code`)
    ) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4;
     */
}
