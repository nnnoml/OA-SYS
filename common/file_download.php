<?php

/**
 * 下载文件
 * @author fotomxq <fotomxq.me>
 * @version 1
 * @package oa
 */
/**
 * 引入用户登陆检测模块(包含全局引用)
 * @since 1
 */

require('logged.php');

/**
 * 引入post类并创建实例
 * @since 1
 */
require(DIR_LIB . DS . 'oa-post.php');
$oapost = new oapost($db, $ip_arr['id']);

/**
 * 下载文件
 * @since 1
 */
if (isset($_GET['id']) == true) {
    $download_view = $oapost->view($_GET['id']);

    if ($download_view) {
        //判断该用户是否可以下载
        $user_id = $oauser->view_user($oauser->get_session_login())['id'];

        $download_password_boolean = $oauser->downloadCheck($_GET['id'],$user_id);

        if ($download_password_boolean) {
            plugtourl($website_url . '/upload' . $download_view['post_url']);
        } else {
            plugtourl('../common/error.php?e=downloadfile');
        }
    }
}
?>
