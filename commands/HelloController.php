<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\commands;

use yii\console\Controller;
use app\library\Util;
use yii\helpers\StringHelper;
use app\models\GuFix;

/**
 * This command echoes the first argument that you have entered.
 *
 * This command is provided as an example for you to learn how to create console commands.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class HelloController extends Controller
{
    /**
     * This command echoes what you have entered as the message.
     * 
     * /Users/junwendu/Project/github/gu
     * gu git:(master) ./yii hello/index
     * 
     * @param string $message the message to be echoed.
     */
    public function actionIndex()
    {
        //获取上证和深证指数地址
        $sh_zs_index_url = 'http://qt.gtimg.cn/r=0.3978993779751039q=s_sh000001,s_sz399001';
        //数据格式
        //v_s_sh000001 = "1~上证指数~000001~3212.99~-4.94~-0.15~241507706~25796524~~";
        //v_s_sz399001 = "51~深证成指~399001~10177.25~-87.67~-0.85~184658833~26178875~~";
    }

    //固定数据导入
    //.yii hello/fix
    public function actionFix()
    {
        $fix_url = 'http://stock.gtimg.cn/data/index.php?appn=rank&t=ranka/last&o=1&l=80&v=list_data&p=';

        for ($page = 1; $page < 40; $page++) {
            try {
                $data = Util::curl($fix_url . $page);
                $pos = strpos($data, "data:'");
                $strData = rtrim(substr($data, $pos + 6), "'};");
                $arrData = explode(',', $strData);
                if (is_array($arrData) && count($arrData)) {
                    foreach ($arrData as $idStr) {
                        $code = substr($idStr, 2);
    
                        $model = GuFix::findOne(['code' => $code]);
                        if ($model) {
                            continue;
                        }
    
                        $model = new GuFix();
                        $model->code = $code;
                        if (StringHelper::startsWith($idStr, GuFix::TYPE_SH)) {
                            $model->type = GuFix::$types[GuFix::TYPE_SH];
                        } else {
                            $model->type = GuFix::$types[GuFix::TYPE_SZ];
                        }
                        $model->save();
                    }
                } else {
                    break;
                }
                $page++;
            } catch (\Exception $e) {
                echo $e->getMessage() . PHP_EOL;
            }
        }
    }
}
