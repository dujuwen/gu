<?php

namespace app\models\base;

use Yii;

/**
 * This is the model class for table "gu_menu".
 *
 * @property integer $id
 * @property string $label
 * @property string $url
 * @property integer $status
 * @property integer $orde
 * @property string $created_at
 * @property string $updated_at
 */
class BaseGuMenu extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'gu_menu';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['status', 'orde'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['label', 'url'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'label' => '菜单描述',
            'url' => '路径',
            'status' => '状态',
            'orde' => '排序(从小到大)',
            'created_at' => '创建时间',
            'updated_at' => '更新时间',
        ];
    }

    /**
     CREATE TABLE `gu_menu` (
      `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
      `label` varchar(255) NOT NULL DEFAULT '' COMMENT '菜单说明',
      `url` varchar(255) NOT NULL DEFAULT '' COMMENT '路径',
      `status` tinyint(4) NOT NULL DEFAULT '1' COMMENT '1正常,2删除',
      `orde` int(11) NOT NULL DEFAULT '1' COMMENT '排序',
      `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
      `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间',
      PRIMARY KEY (`id`)
    ) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COMMENT='菜单';
     */

}
