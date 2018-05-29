<?php
/**
 * 收件箱中心
 * @author fotomxq <fotomxq.me>
 * @version 4
 * @package oa
 */
if (isset($init_page) == false) {
    die();
}

/**
 * 初始化变量
 * @since 3
 */
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$max = 10;
$sort = 0;
$desc = true;
$post_type = 'file';
$post_status = isset($_GET['status']) ? $_GET['status'] : 'private';

/**
 * 操作消息内容
 * @since 1
 */
$message = '';
$message_bool = false;


/**
 * 获取用户列表
 */
$userlist = $oauser->view_user_list(null, 1, 99999);
$user_id = $oauser->view_user($oauser->get_session_login())['id'];


/**
 * 上传新的文件
 * @since 1
 */
/**
 * 引入文件处理类
 * @since 1
 */
require(DIR_LIB . DS . 'core-file.php');

/**
 * 处理上传
 * @since 1
 */
$upload_post_name = 'add_uploadfile';
$file_dest = '';//文件上传目录;
if (isset($_FILES[$upload_post_name]) == true) {
    if ($_FILES[$upload_post_name]['error'] == 0) {
        $config_uploadfile_on = $oaconfig->load('UPLOADFILE_ON');
        if ($config_uploadfile_on > 0) {
            $config_uploadfile_min = $oaconfig->load('UPLOADFILE_SIZE_MIN');
            $config_uploadfile_max = $oaconfig->load('UPLOADFILE_SIZE_MAX');
            $file_size = $_FILES[$upload_post_name]['size'] / 1024;
            if ($file_size > $config_uploadfile_min && $file_size < $config_uploadfile_max) {
                //判断文件类型是否正确
                $config_uploadfile_hibit_type = $oaconfig->load('UPLOADFILE_INHIBIT_TYPE');
                $config_uploadfile_hibit_type_arr = null;
                if ($config_uploadfile_hibit_type) {
                    $config_uploadfile_hibit_type_arr = explode(',', $config_uploadfile_hibit_type);
                }
                unset($config_uploadfile_hibit_type);
                $file_type = substr(strrchr($_FILES[$upload_post_name]['name'], '.'), 1);
                if (in_array($file_type, $config_uploadfile_hibit_type_arr) == false || $config_uploadfile_hibit_type_arr == null) {
                    $post_file_sha1 = sha1_file($_FILES[$upload_post_name]['tmp_name']);

                        //如果文件不存在，则开始转移文件
                        $file_dest_dir =  DS . date('Ym') . DS . date('d');
                        if (corefile::new_dir(DIR_UPLOAD.DS.$file_dest_dir) == true) {

                            $file_ext = explode('.', $_FILES[$upload_post_name]['name']);
                            $file_dest = $file_dest_dir . DS . md5(rand(1, 99999).time()) . '.'.end($file_ext);

                            if (corefile::move_upload($_FILES[$upload_post_name]["tmp_name"], DIR_UPLOAD.DS.$file_dest) == true) {
//                                $post_res = $oapost->add($_FILES[$upload_post_name]['name'], '', $post_type, 0, $post_user, $post_file_sha1, $_FILES[$upload_post_name]['name'], $file_dest, 'public', $_FILES[$upload_post_name]['type']);
//                                if ($post_res > 0) {
//                                    //上传成功，创建记录
//                                    $upload_id = $post_res;
//                                } else {
//                                    corefile::delete_file($file_dest);
//                                    $message = '文件上传失败，无法创建相关数据。';
//                                    $message_bool = false;
//                                }

                                /**
                                 * 上传成功才添加文字内容
                                 * 添加新的消息
                                 * @since 1
                                 */

                                if (isset($_POST['new_message']) == true && isset($_POST['form_user_id']) == true) {

                                    $title = '';
                                    if (isset($_POST['new_title']) == true) {
                                        $title = $_POST['new_title'];
                                    } else {
                                        //引入截取字符串模块
                                        require(DIR_LIB . DS . 'plug-substrutf8.php');
                                        $title = plugsubstrutf8($_POST['new_message'], 100);
                                    }
                                    $new_user_view = $oauser->view_user($_POST['form_user_id']);
                                    if ($new_user_view) {
                                        if ($oapost->add($title, $_POST['new_message'], 'message', $_POST['form_user_id'], $user_id,$file_dest, 'private', null)) {
                                            $message = '消息成功发送！';
                                            $message_bool = true;
                                        } else {
                                            $message = '无法发送消息。';
                                            $message_bool = false;
                                        }
                                    } else {
                                        $message = '该用户不存在！';
                                        $message_bool = false;
                                    }
                                }

                            } else {
                                $message = '文件上传失败，无法移动文件。';
                                $message_bool = false;
                            }
                        } else {
                            $message = '文件上传失败，无法操作目录。';
                            $message_bool = false;
                        }

                } else {
                    $message = '文件上传失败，您不能上传这种文件';
                    if (is_array($config_uploadfile_hibit_type_arr) == true) {
                        $message .= '：' . implode('、', $config_uploadfile_hibit_type_arr);
                    }
                    $message_bool = false;
                }
            } else {
                $message = '文件上传失败，文件必须在' . $config_uploadfile_min . ' KB到' . $config_uploadfile_max . ' KB之间。';
                $message_bool = false;
            }
        } else {
            $message = '系统已经关闭了文件上传功能。';
            $message_bool = false;
        }
    } else {
        $message = '文件上传失败，发生未知异常。';
        $message_bool = false;
    }
}


/**
 * 删除消息
 * @since 3
 */
if (isset($_GET['del']) == true) {
    $del_view = $oapost->view($_GET['del']);
    if ($del_view) {

        if (!corefile::delete_file($del_view['post_url'])) {
            $message = '删除失败,无法删除该文件。';
            $message_bool = false;
        }
        else{
            if ($oapost->del($_GET['del'])) {
                $message = '删除成功！';
                $message_bool = true;
            } else {
                $message = '删除失败,请重试';
                $message_bool = false;
            }
        }
    } else {
        $message = '无法删除该消息，该消息不存在。';
        $message_bool = false;
    }
}

/**
 * 获取消息列表记录数
 * @since 3
 */
$message_list_row = $oapost->view_list_row(null,null, null, 'private', 'message',$post_user);

/**
 * 计算页码
 * @since 1
 */
$page_max = ceil($message_list_row / $max);
if ($page < 1) {
    $page = 1;
} else {
    if ($page > $page_max) {
        $page = $page_max;
    }
}
$page_prev = $page - 1;
$page_next = $page + 1;

/**
 * 获取消息列表
 * @since 3
 */
$message_list = $oapost->view_list(null, null, null, 'private', 'message', $page, $max, $sort, $desc,$post_user);

?>
<!-- 管理表格 -->
<h2>收件箱</h2>
<table class="table table-hover table-bordered table-striped">
    <thead>
        <tr>
            <th><i class="icon-calendar"></i> 时间</th>
            <th><i class="icon-user"></i> 发送者</th>
            <th><i class="icon-comment"></i> 消息</th>
            <th><i class="icon-file"></i> 文件</th>
            <th><i class="icon-asterisk"></i> 操作</th>
        </tr>
    </thead>
    <tbody id="message_list">
        <?php if($message_list){ foreach($message_list as $v){ ?>
        <tr>
            <td><?php echo $v['post_date']; ?></td>
            <td><?php $message_user = $oauser->view_user($v['post_user']); if($message_user){ echo '<a href="javascript:;'.$page_url.'&user='.$message_user['id'].'" target="_self">'.$message_user['user_name'].'</a>'; unset($message_user); } ?></td>
            <td><?php echo $v['post_title']; ?></td>

            <td><div class="btn-group"><a href="../common/file_download.php?id=<?php echo $v['id']; ?>" class="btn"><i class="icon-file"></i> 下载</a></div></td>

            <td><div class="btn-group"><a href="<?php echo $page_url.'&view='.$v['id']; ?>" class="btn" target="_self"><i class="icon-search"></i> 详情</a><a href="<?php echo $page_url.'&user='.$v['post_user']; ?>" class="btn" target="_self"><i class="icon-envelope"></i> 回复</a><a href="<?php echo $page_url.'&del='.$v['id']; ?>" class="btn btn-danger" target="_self"><i class="icon-trash icon-white"></i> 删除</a></div></td>
        </tr>
        <?php } } ?>
    </tbody>
</table>

<!-- 页码 -->
<ul class="pager">
    <li class="previous<?php if($page<=1){ echo ' disabled'; } ?>">
        <a href="<?php echo $page_url.'&page='.$page_prev; ?>">&larr; 上一页</a>
    </li>
    <li class="next<?php if($page>=$page_max){ echo ' disabled'; } ?>">
        <a href="<?php echo $page_url.'&page='.$page_next; ?>">下一页 &rarr;</a>
    </li>
</ul>

<?php
if (isset($_GET['view']) == false) {

    $send_user = 0;
    if(isset($_GET['user']) == true){
        $send_user_view = $oauser->view_user((int)$_GET['user']);

        if($send_user_view){
            $send_user = $send_user_view['id'];
        }
    }
    ?>
    <!-- 发布消息 -->
    <h2 id="send"><?php if($send_user) echo "回复"; else echo "发送";?>消息</h2>
    <form action="<?php echo $page_url; ?>" method="post" class="form-actions form-horizontal" enctype="multipart/form-data">
        <div class="control-group">
            <label class="control-label" for="form_user_id">接收人</label>
            <div class="controls">
                <select <?php if($send_user) echo "disabled" ?> id="form_user_id" name="form_user_id" class="form-control">
                    <?php
                    if($userlist){
                    foreach($userlist as $v){
                        if($v['id'] == $user_id) continue;
                    ?>
                    <option <?php if($send_user == $v['id'] ) echo "selected='selected'" ?> value="<?php echo $v['id']; ?>"><?php echo $v['user_name']; ?></option>
                    <?php } } ?>
                </select>
            </div>
            </div>
        <div class="control-group">
            <label class="control-label" for="new_message">消息内容</label>
            <div class="controls">
                <textarea rows="5" style="width:100%;" id="new_message" name="new_message" placeholder="消息内容……"></textarea>
            </div>
        </div>
        <div class="control-group">
            <label class="control-label" for="add_uploadfile">选择文件</label>
            <div class="controls">
                <input type="file" id="add_uploadfile" name="add_uploadfile" />
            </div>
        </div>
        <div class="control-group">
            <div class="controls">
                <button type="submit" class="btn btn-primary"><i class="icon-ok icon-white"></i> 发送</button>
            </div>
        </div>
    </form>

        <?php
}
        if (isset($_GET['view']) == true) {
            $view_message = $oapost->view($_GET['view']);

            if ($view_message) {
                if($user_id == $post_user){
                ?>
                <!-- 查看消息详情 -->
                <div id="view" class="form-actions">
                    <p><strong><?php echo $view_message['post_title']; ?></strong></p>
                    <p><em><?php echo $view_message['post_date']; ?> - <?php $message_user = $oauser->view_user($view_message['post_user']); if($message_user){ echo '<a href="'.$page_url.'&user='.$message_user['id'].'" target="_self">'.$message_user['user_name'].'</a>'; unset($message_user); } ?></em></p>
                    <p><?php echo $view_message['post_content']; ?></p>
                    <p><a href="<?php echo $page_url.'&user='.$view_message['id']; ?>" role="button" class="btn"><i class="icon-envelope"></i> 回复</a><a href="<?php echo $page_url; ?>" role="button" class="btn"><i class="icon-repeat"></i> 返回</a></p>
                </div>
                <?php
                }
            }
        }
        ?>

        <!-- Javascript -->
        <script>
            $(document).ready(function(){
                var message = "<?php echo $message; ?>";
                var message_bool = "<?php echo $message_bool ? '2' : '1'; ?>";
                if(message != ""){
                    msg(message_bool,message,message);
                }
            });
            window.onsubmit=function(){
                $("#form_user_id").removeAttr("disabled");
            }
        </script>