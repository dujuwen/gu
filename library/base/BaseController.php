<?php

namespace app\library\base;

use yii;
use app\models\GuMenu;
use app\models\User;
use app\library\Util;

class BaseController extends \yii\web\Controller
{
    public function beforeAction($action) {
        Yii::$app->view->params['leftMenu'] = $this->getBackendLeftMenu();
        Yii::$app->view->params['rightMenu'] = $this->getBackendRightMenu();

        return parent::beforeAction($action);
    }

    public function getGet($key, $default = null)
    {
        return Yii::$app->request->get($key, $default);
    }

    public function getPost($key, $default = null)
    {
        return Yii::$app->request->post($key, $default);
    }

    public function isAjax(){

        return Yii::$app->request->isAjax;
    }

    public function isPost(){

        return Yii::$app->request->isPost;
    }

    public function getPage()
    {
        $page = (int) $this->getGet('page', 1);
        if ($page < 1) {
            return 1;
        } else {
            return $page;
        }
    }

    public function exitJson($data) {
        header('Content-Type: application/json; charset=utf8');
        header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
        header("Cache-Control: post-check=0, pre-check=0", false);
        header("Pragma: no-cache");
        if(! defined('JSON_PRETTY_PRINT')) {
            echo json_encode($data);
            exit;
        }
        $is_debug = (defined('YII_DEBUG') && YII_DEBUG) || isset($_GET['p']);
        $opts = JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE;
        if($is_debug) {
            $opts |= JSON_PRETTY_PRINT;
        }
        echo json_encode($data, $opts) . ($is_debug ? "\n" : '');
        exit;
    }

    protected function getBackendLeftMenu()
    {
        //二级菜单像这样
        //$ret[1]['label'] = 'hahh';
        //$ret[1]['items'][] = ['url' => '', 'label' => '监视'];

        if (Yii::$app->user->isGuest) {
            return [];
        }

        $ret = [];
        $ms = GuMenu::find()->select('*')->where(['status' => User::STATUS_NORMAL])->orderBy('orde')->asArray()->all();
        foreach ($ms as $menu) {
            $ret[$menu['id']]['label'] = $menu['label'];
            $ret[$menu['id']]['url'] = Util::isWindows() ? ('/gu/web/index.php' . $menu['url'] ): $menu['url'];
        }

        return $ret;
    }

    protected function getBackendRightMenu()
    {
        $menuItems = [
            ['label' => '首页', 'url' => ['/site/index']],
        ];

        if (Yii::$app->user->isGuest) {
            $menuItems[] = ['label' => '登录', 'url' => ['/site/login']];
        } else {
            $menuItems[] = [
                'label' => '注销 (' . Yii::$app->user->identity->username  . ')',
                'url' => ['/site/logout'],
                'linkOptions' => ['data-method' => 'post']
            ];
        }

        return $menuItems;
    }
}