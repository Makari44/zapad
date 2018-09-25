<?PHP
define ("DIR", dirname ( __FILE__ ));
include DIR.'/system/classes/functions.php';
$func = new functions;
$db = $func->loaddb();
$time = time();

if (isset($_POST["LMI_PAYEE_PURSE"]))
    {
$secret_key = 'c45vg546gvf4g756';

$ik_payment_amount = round(floatval($_POST['LMI_PAYMENT_AMOUNT']),2);
$user_id = intval($_POST['user_id']);

$common_string = $_POST['LMI_PAYEE_PURSE'].$_POST['LMI_PAYMENT_AMOUNT'].
				 $_POST['LMI_PAYMENT_NO'].$_POST['LMI_MODE'].$_POST['LMI_SYS_INVS_NO'].
				 $_POST['LMI_SYS_TRANS_NO'].$_POST['LMI_SYS_TRANS_DATE'].$secret_key.
				 $_POST['LMI_PAYER_PURSE'].$_POST['LMI_PAYER_WM'];
 
$hash = strtoupper(hash("sha256",$common_string));

$fid = fopen("templates_cache/hash.txt", "w+"); 
fwrite($fid, $hash."\n".$_POST['LMI_HASH']); 
fclose($fid);

if ($hash != $_POST['LMI_HASH']) die("SumError");

$summa = sprintf("%.4f", floatval($ik_payment_amount));

$sql = $db->query("SELECT * FROM `users` WHERE `user_id`='$user_id'");
$row = $db->get_row($sql);
     if ($row["user_vvod"] == 0){
         $sum = $summa*15/100+$summa;
     }
     elseif ($row["user_vvod"] > 0){
         $sum = $summa;
     }
    else
    $sum = 0;
if ($summa>=1000){
$last_time = time() + 7776000;
$db->query("INSERT INTO `users_buy`(`plot_id`, `mar_level`,`mar_last`,`date_add`,`date_del`,`user_id`) VALUES ('1','1','$time','$time','$last_time','$user_id')");
$db->query("INSERT INTO `users_buy`(`plot_id`, `mar_level`,`mar_last`,`date_add`,`date_del`,`user_id`) VALUES ('1','1','$time','$time','$last_time','$user_id')");
$db->query("INSERT INTO `users_buy`(`plot_id`, `mar_level`,`mar_last`,`date_add`,`date_del`,`user_id`) VALUES ('1','1','$time','$time','$last_time','$user_id')");
     }
$db->query("UPDATE `users` SET `user_gold_pay`=user_gold_pay+'$sum', `user_vvod`=user_vvod+'$summa' WHERE `user_id`='$user_id'");
 }
?>