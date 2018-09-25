<?php
define ("DIR", dirname ( __FILE__ ));
include DIR.'/system/classes/functions.php';
$func = new functions;
$db = $func->loaddb();
$time = time();

if (isset($_POST['m_operation_id']) && isset($_POST['m_sign']))
{
	$m_key = 'RUf5gYK7vqJEbZaJ';
	$arHash = array($_POST['m_operation_id'],
			$_POST['m_operation_ps'],
			$_POST['m_operation_date'],
			$_POST['m_operation_pay_date'],
			$_POST['m_shop'],
			$_POST['m_orderid'],
			$_POST['m_amount'],
			$_POST['m_curr'],
			$_POST['m_desc'],
			$_POST['m_status'],
			$m_key);
	$sign_hash = strtoupper(hash('sha256', implode(':', $arHash)));
	if ($_POST['m_sign'] == $sign_hash && $_POST['m_status'] == 'success')
	{

//$ik_payment_amount = round(floatval($_POST['m_amount']),2);
//$summa = sprintf("%.4f", floatval($ik_payment_amount));
//$user_id = intval($_POST['user_id']);	

//$sum = round(floatval($_POST["m_amount"]),2);
//$summa = number_format($sum, 2, '.', '');
//$user_id = $_POST["m_orderid"];

$summa = round(floatval($_POST["m_amount"]),2);
$user_id = $_POST["m_orderid"];

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

		echo $_POST['m_orderid'].'|success';
		exit;
	}
	echo $_POST['m_orderid'].'|error';
}
?>