<?php
/**
 * 登录后首页
 * @author fotomxq <fotomxq.me>
 * @version 15
 * @package oa
 */
/**
 * 引入用户登陆检测模块(包含全局引用)
 * @since 5
 */
require('common/logged.php');

/**
 * 获取当前页面URL
 * @since 6
 */
require(DIR_LIB . DS . 'plug-geturl.php');
$url = pluggeturl();

/**
 * 定义页面指向
 * @since 4
 */
$init_page = 0;
if (isset($_GET['init']) == true) {
    $init_page = $_GET['init'];
    if($init_page >= 5 && $logged_admin == false){
        plugtourl('../common/error.php?e=noadmin');
    }
}
//$init_page_arr = array('center', 'message', 'disk_user', 'task_user', 'performance', 'diary', 'address_book', 'self', 'disk_share', 'task_center', 'message_board', 'message_center', 'system', 'backup', 'user', 'user_group');
$init_page_arr = array('center','message','send','address', 'self','message_center', 'system', 'user', 'user_group');
if (isset($init_page_arr[$init_page]) == false) {
    $init_page = 0;
}

/**
 * 初始化页面URL
 * @since 8
 */
$page_url = 'init.php?init=' . $init_page;

/**
 * 当前用户ID
 * @since 9
 */
$post_user = $oauser->get_session_login();

/**
 * 引入post类并创建实例
 * @since 11
 */
require(DIR_LIB . DS . 'oa-post.php');
$oapost = new oapost($db, $ip_arr['id']);

/**
 * 计算收件箱数量
 * @since 10
 */
$tip_message_row = $oapost->view_list_row(null,null, null, 'private', 'message',$post_user);
/**
 * 计算已发送数量
 * @since 10
 */
$tip_send_row = $oapost->view_list_row($post_user,null, null, 'private', 'message',null);
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title><?php echo $website_title; ?></title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="">
        <meta name="author" content="">

        <!-- Le javascript -->
        <script src="includes/js/jquery.js"></script>
        <script src="includes/js/bootstrap.js"></script>
        <script>
            //ajax消息函数
            function msg(data,success,error){
                var id = "#msg";
                if(data=="2"){
                    $(id).attr("class","alert alert-success");
                    $(id).html("<p>"+success+"</p>");
                }else{
                    $(id).attr("class","alert alert-error");
                    $(id).html("<p>"+error+"</p>");
                }
            }
            
            //延迟刷新或跳转页面模块
            var t;
            function tourl(t,url){
                t = setTimeout("window.location = '"+url+"'",t);
            }
            
            //IP地址
            var ip_addr = "<?php echo $ip_arr['addr']; ?>";
            //打印链接处理
            $(document).ready(function() {
                $("a[href='#print_page']").click(function(){
                    window.print();
                });
            });
        </script>

        <!-- Le styles -->
        <link href="includes/css/bootstrap.css" rel="stylesheet">
        <style type="text/css">
            body {
                padding-top: 60px;
                padding-bottom: 40px;
            }
            .sidebar-nav {
                padding: 9px 0;
            }

            @media (max-width: 980px) {
                /* Enable use of floated navbar text */
                .navbar-text.pull-right {
                    float: none;
                    padding-left: 5px;
                    padding-right: 5px;
                }
            }
        </style>
        <link href="includes/css/bootstrap-responsive.css" rel="stylesheet">

        <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
        <!--[if lt IE 9]>
          <script src="includes/js/html5shiv.js"></script>
        <![endif]-->

        <!-- Fav and touch icons -->
        <link rel="apple-touch-icon-precomposed" sizes="144x144" href="includes/images/logo-144.png">
        <link rel="apple-touch-icon-precomposed" sizes="114x114" href="includes/images/logo-114.png">
        <link rel="apple-touch-icon-precomposed" sizes="72x72" href="includes/images/logo-72.png">
        <link rel="apple-touch-icon-precomposed" href="includes/images/logo-57.png">
        <link rel="shortcut icon" href="includes/images/logo.png">
    </head>

    <body>

        <div class="navbar navbar-inverse navbar-fixed-top">
            <div class="navbar-inner">
                <div class="container-fluid">
                    <button type="button" class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="brand" href="#"><?php echo $website_title; ?></a>
                    <div class="nav-collapse collapse">
                        <p class="navbar-text pull-right">
                            欢迎您  <b><?php $hello_user = $oauser->view_user($oauser->get_session_login()); if($hello_user){ echo $hello_user['user_name']; } unset($hello_user); ?></b>  您的IP地址 : <?php echo $ip_arr['addr']; ?>  
                            <a href="common/logout.php" class="navbar-link"><i class="icon-off icon-white"></i> 退出登陆</a>
                        </p>
                        <ul class="nav">
                            <li class="active"><a href="init.php"><i class="icon-home icon-white"></i> 主页</a></li>
                            <li class="dropdown">
                                <a  href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-user icon-white"></i> 个人中心<b class="caret"></b></a>
                                <ul class="dropdown-menu">
                                    <li><a href="init.php?init=1"><i class="icon-envelope"></i> 收件箱</a></li>
                                    <li><a href="init.php?init=2"><i class="icon-envelope"></i> 已发送</a></li>
<!--                                    <li><a href="init.php?init=2"><i class="icon-hdd"></i> 网络硬盘</a></li>-->
<!--                                    <li><a href="init.php?init=3"><i class="icon-list-alt"></i> 计划任务</a></li>-->
<!--                                    <li><a href="init.php?init=4"><i class="icon-check"></i> 业绩考评</a></li>-->
<!--                                    <li><a href="init.php?init=5"><i class="icon-pencil"></i> 工作日记</a></li>-->
                                    <li><a href="init.php?init=3"><i class="icon-book"></i> 通讯录</a></li>
                                    <li><a href="init.php?init=4"><i class="icon-user"></i> 修改信息</a></li>
                                </ul>
                            </li>
<!--                            <li class="dropdown">-->
<!--                                <a  href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-retweet icon-white"></i> 共享协作<b class="caret"></b></a>-->
<!--                                <ul class="dropdown-menu">-->
<!--                                    <li><a href="init.php?init=8"><i class="icon-share"></i> 文件共享中心</a></li>-->
<!--<!--                                    <li><a href="init.php?init=9"><i class="icon-tasks"></i> 生产任务中心</a></li>-->-->
<!--<!--                                    <li><a href="init.php?init=10"><i class="icon-comment"></i> 公共留言薄</a></li>-->-->
<!--                                </ul>-->
<!--                            </li>-->
                            <?php if($logged_admin == true){ ?>
                            <li class="dropdown">
                                <a  href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-wrench icon-white"></i> 系统<b class="caret"></b></a>
                                <ul class="dropdown-menu">
<!--                                    <li><a href="init.php?init=5"><i class="icon-envelope"></i> 消息中心</a></li>-->
                                    <li><a href="init.php?init=6"><i class="icon-asterisk"></i> 系统设置</a></li>
<!--                                    <li><a href="init.php?init=13"><i class="icon-random"></i> 备份和恢复</a></li>-->
                                    <li><a href="init.php?init=7" target="_self"><i class="icon-user"></i> 用户管理</a></li>
                                    <li><a href="init.php?init=8" target="_self"><i class="icon-th-large"></i> 用户组管理</a></li>
                                </ul>
                            </li>
                            <?php } ?>
                        </ul>
                    </div><!--/.nav-collapse -->
                </div>
            </div>
        </div>

        <div class="container-fluid">
            <div class="row-fluid">
                <div class="span3">
                    <div class="well sidebar-nav">
                        <ul class="nav nav-list">
                            <li class="nav-header">个人中心</li>
                            <li><a href="init.php?init=1"><i class="icon-envelope"></i> 收件箱 <?php if($tip_message_row>0){ ?><span class="badge badge-info"><?php echo $tip_message_row; ?></span><?php } ?></a></li>
                            <li><a href="init.php?init=2"><i class="icon-envelope"></i> 已发送 <?php if($tip_send_row>0){ ?><span class="badge badge-info"><?php echo $tip_send_row; ?></span><?php } ?></a></li>
<!--                            <li><a href="init.php?init=2"><i class="icon-hdd"></i> 网络硬盘</a></li>-->
<!--                            <li><a href="init.php?init=3"><i class="icon-list-alt"></i> 计划任务 --><?php //if($tip_task_user_row>0){ ?><!--<span class="badge badge-info">--><?php //echo $tip_task_user_row; ?><!--</span>--><?php //} ?><!--</a></li>-->
<!--                            <li><a href="init.php?init=4"><i class="icon-check"></i> 业绩考评</a></li>-->
<!--                            <li><a href="init.php?init=5"><i class="icon-pencil"></i> 工作日记</a></li>-->
                            <li><a href="init.php?init=3"><i class="icon-book"></i> 通讯录</a></li>
                            <li><a href="init.php?init=4"><i class="icon-user"></i> 修改信息</a></li>
<!--                            <li class="nav-header">共享协作</li>-->
<!--                            <li><a href="init.php?init=8"><i class="icon-share"></i> 文件共享中心</a></li>-->
<!--                            <li><a href="init.php?init=9"><i class="icon-tasks"></i> 生产任务中心 --><?php //if($tip_task_center_row>0){ ?><!--<span class="badge badge-Inverse">--><?php //echo $tip_task_center_row; ?><!--</span>--><?php //} ?><!--</a></li>-->
<!--                            <li><a href="init.php?init=10"><i class="icon-comment"></i> 公共留言薄</a></li>-->
                            <?php if($logged_admin == true){ ?>
                            <li class="nav-header">系统</li>
<!--                            <li><a href="init.php?init=5"><i class="icon-envelope"></i> 消息中心</a></li>-->
                            <li><a href="init.php?init=6"><i class="icon-asterisk"></i> 系统设置</a></li>
<!--                            <li><a href="init.php?init=13"><i class="icon-random"></i> 备份和恢复</a></li>-->
                            <li><a href="init.php?init=7" target="_self"><i class="icon-user"></i> 用户管理</a></li>
                            <li><a href="init.php?init=8" target="_self"><i class="icon-th-large"></i> 用户组管理</a></li>
                            <?php } ?>
                        </ul>
                    </div><!--/.well -->
                </div><!--/span-->
                <div class="span9">
                    <div id="msg" class="alert alert-success hide"><button type="button" class="close" data-dismiss="alert">&times;</button></div>
                    <?php
                    /**
                     * 引入内部内容
                     * @since 4
                     */
                    require(DIR_COMP.'/init_' . $init_page_arr[$init_page] . '.php');
                    ?>
                </div><!--/span-->
            </div><!--/row-->
            <hr>
            <footer>
                <p>
                    <?php
                    echo $website_footer;
                    ?>
                </p>
            </footer>

        </div><!--/.fluid-container-->

    </body>
</html>