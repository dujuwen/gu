<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\commands;

use yii\console\Controller;

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
}
