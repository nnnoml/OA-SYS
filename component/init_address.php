<?php
/**
 * 用户页面
 * @author fotomxq <fotomxq.me>
 * @version 4
 * @package oa
 */
if (isset($init_page) == false) {
    die();
}

/**
 * 初始化变量
 */
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$max = 10;
$sort = 0;
$desc = false;

/**
 * 获取用户列表记录数
 */
$userlist_row = $oauser->get_user_row(null);

/**
 * 计算页码
 */
if ($page < 1) {
    $page = 1;
}
$page_max = ceil($userlist_row / $max);
if ($page > $page_max) {
    $page = $page_max;
}
$page_prev = $page - 1;
$page_next = $page + 1;

/**
 * 获取用户列表
 */
$userlist = $oauser->view_user_list(null, $page, $max, $sort, $desc);
?>

<h2>通讯录</h2>
<table class="table1">
  
        <tr>
            <th><i class="icon-th-list"></i> ID</th>
            <th><i class="icon-user"></i> 用户名</th>
            <th><i class="icon-tags"></i> 昵称</th>
            <th><i class="icon-envelope"></i> 邮箱</th>
            <th><i class="icon-th"></i> 用户组</th>
        </tr>

    <tbody class="user_list">
        <?php
        if($userlist){
            foreach($userlist as $v){
                if($v['user_username']=='admin') continue;

                $v_group = $oauser->view_group($v['user_group']);
                $v_ip = $coreip->view($v['id']);
            ?>
        <tr>
            <td><?php echo $v['id']; ?></td>
            <td><?php echo $v['user_username']; ?></td>
            <td><?php echo $v['user_name']; ?></td>
            <td><?php echo $v['user_email']; ?></td>
            <td><?php echo $v_group['group_name']; ?></td>
        </tr>
        <?php } } ?>
    </tbody>
</table>



<table class="table table-hover table-bordered table-striped">
    <thead>
        <tr>
            <th><i class="icon-th-list"></i> ID</th>
            <th><i class="icon-user"></i> 用户名</th>
            <th><i class="icon-tags"></i> 昵称</th>
            <th><i class="icon-envelope"></i> 邮箱</th>
            <th><i class="icon-th"></i> 用户组</th>
        </tr>
    </thead>
    <tbody id="user_list">
        <?php
        if($userlist){
            foreach($userlist as $v){
                if($v['user_username']=='admin') continue;

                $v_group = $oauser->view_group($v['user_group']);
                $v_ip = $coreip->view($v['id']);
            ?>
        <tr>
            <td><?php echo $v['id']; ?></td>
            <td><?php echo $v['user_username']; ?></td>
            <td><?php echo $v['user_name']; ?></td>
            <td><?php echo $v['user_email']; ?></td>
            <td><?php echo $v_group['group_name']; ?></td>
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