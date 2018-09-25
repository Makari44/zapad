<?PHP
define ("DIR", dirname ( __FILE__ ));
include DIR.'/system/classes/functions.php';
$func = new functions;
$db = $func->loaddb();
$time = time();

$fk_merchant_id = '14062'; //merchant_id ID мазагина в free-kassa.ru http://free-kassa.ru/merchant/cabinet/help/
$fk_merchant_key = ''; //Секретное слово http://free-kassa.ru/merchant/cabinet/profile/tech.php
$fk_merchant_key2 = 'HYuju7yuhyt6gty67'; //Секретное слово2 (result) http://free-kassa.ru/merchant/cabinet/profile/tech.php

$hash = md5($fk_merchant_id.":".$_POST['AMOUNT'].":".$fk_merchant_key2.":".$_POST['MERCHANT_ORDER_ID']);

if ($hash == $_POST['SIGN']) {
$summa = $_POST['AMOUNT'];
$user_id = $_POST['MERCHANT_ORDER_ID'];
	
$db->query("UPDATE `users` SET `user_gold_pay`=user_gold_pay+'$summa', `user_vvod`=user_vvod+'$summa' WHERE `user_id`='$user_id'");
$db->query("INSERT INTO `pay_all`(`user_id`,`pay_time`,`pay_sum`) VALUES ('$user_id','$time','$summa')");
$func->AddHistory($user_id,17,0,$summa,"p",0);

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

///// Конкурс рефералов //////
$sql = $db->query("SELECT * FROM `kon_ref` LIMIT 1");
$views = $db->get_row($sql);
if ($views['status']==1)
{
$db->Query("SELECT * FROM `kon_ref` WHERE status = '1' LIMIT 1");
$res = $db->get_row();
$status = $res['status'];

$db->Query("SELECT `user_referer` FROM `users` WHERE `user_id` = '$user_id' LIMIT 1");
$res2 = $db->get_row();
$user_idd = $res2['user_referer'];
if ($user_idd > 1)
{
if ($status == 1)
{
$resp = $db->query("SELECT `user_id` FROM `kon_ref_users` WHERE `user_id`='$user_idd'");
$respp = $db->get_row($resp);

if ($respp > 0)
{
       $db->query("UPDATE `kon_ref_users` SET `summa`=summa+'$summa' WHERE `user_id`='$user_idd'");
       }else
       
       $db->query("INSERT INTO `kon_ref_users`(`user_id`, `summa`) VALUES ('$user_idd','$summa')");
       }
}
}
///// Конкурс рефералов //////
}

?>