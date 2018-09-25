<?php
define ("DIR", dirname ( __FILE__ ));
include DIR.'/system/classes/functions.php';
$func = new functions;
$db = $func->loaddb();


$summa = 700.00;
$user_id = 6;

///// Конкурс инвесторов //////
$sql = $db->query("SELECT * FROM `kon_invest` LIMIT 1");
$views = $db->get_row($sql);
if ($views['status']==1)
{
$db->Query("SELECT * FROM `kon_invest` WHERE status = '1' LIMIT 1");
$res = $db->get_row();
$status = $res['status'];

if ($status == 1)
{
$resp = $db->query("SELECT `user_id` FROM `kon_invest_users` WHERE `user_id`='$user_id'");
$respp = $db->get_row($resp);

if ($respp > 0)
{
       $db->query("UPDATE `kon_invest_users` SET `summa`=summa+'$summa' WHERE `user_id`='$user_id'");
       }else
       
       $db->query("INSERT INTO `kon_invest_users`(`user_id`, `summa`) VALUES ('$user_id','$summa')");
       }
}
///// Конкурс инвесторов //////
?>