<?php
error_reporting(E_ALL);

ini_set('session.gc_maxlifetime', 604800);
ob_start();
@session_start();
//header('X-Powered-By: Scrin (Scrin.name), Version 1.0');



define ("DIR", dirname ( __FILE__ ));
define ("MODULES", DIR.'/system/modules/');

$time = time();

    $current_lang = (!empty($_SESSION["lang"])) ? $_SESSION["lang"] : "ru";

    include DIR . '/system/lang/'.$current_lang.'.php';

$sitename = 'Mega-Ogorod.com';
$activation = TRUE;
//$mcache = new memcache;
//$mcache->connect('localhost', 11211); 

$static_domain = 'http://mega-ogorod.com';

$page = explode('/',$_SERVER['REQUEST_URI']);
$page[1] = (!empty($page[1])) ? $page[1] : "index";
if (empty($page[2])) $method = "index"; else $method = $page[2];


/*if (!empty($_SESSION["l"]))
{
    if ($time-$_SESSION["l"]<1)
    {
        die("Включена защита от ботов. Ваши данные переданы администрации");
    }
    
}
    $_SESSION["l"] = $time;*/

include DIR.'/system/classes/functions.php';
include DIR.'/libs/Smarty.class.php';     


$smarty = new Smarty();
$func = new functions;
$db = $func->loaddb();
    if (isset($_SESSION["user_id"]))
    {
        include DIR.'/system/components/user.php';
    }else{
        $user_status = 0;
        $auth = 0;
    }
        
                //Для шаблонизатора
                $info = array();

                
                include DIR.'/config/ps.php';
                include DIR.'/system/components/dopinfo.php';

                $info_page['start'] = $system['start'];
                $worked = ($time-strtotime($system['start']))/(24*3600);
		$info_page['worked'] = ceil($worked);
                
                // Статистика на мемкеш
                //Всего пользователей
              // if(!$mcache->get('all_users'))
              //  {
                    $all_users = $db->super_query("SELECT count(user_id) c FROM `users`");
                   // $mcache->set('all_users', $all_users, false, 60);    
                //}
                //else
               // {
                    //$all_users = $mcache->get('all_users');
               // }
		$info_page['all_users'] = $all_users["c"];
                
                //Всего выплачено
               // if(!$mcache->get('all_pay'))
               // {
                    $all_pay = $db->super_query("SELECT sum(pay_sum) sum FROM `pay` WHERE `status`='pay'");
               //     $mcache->set('all_pay', $all_pay, false, 60);    
              //  }
              //  else
              //  {
               //     $all_pay = $mcache->get('all_pay');
              //  }
		$info_page['all_pay'] = $all_pay["sum"];
                $info_page['time'] = time();

$tfstats = time() - 60*60*24;
$all_users_new = $db->super_query("SELECT count(user_id) c FROM `users` WHERE `user_reg` > $tfstats");
$info_page['all_users_new'] = $all_users_new["c"];

$all_users_akt = $db->super_query("SELECT count(user_id) c FROM `users` WHERE `user_last_login` > $tfstats");
$info_page['all_users_aktiv'] = $all_users_akt["c"];
                
                
                $smarty->assign("info_page",$info_page);

                $smarty->assign("auth",$auth);
                $smarty->template_dir = 'templates';
                $smarty->compile_dir  = 'templates_cache';
    
    
    
    
    include DIR.'/system/components/page.php';
    $page_text= ob_get_contents();
    ob_end_clean();

$tex_raboti = 1; // Вкл. сайт 1, Выкл сайт 0.

if (empty($tex_raboti))
{   
if ($user_status==1)
{
   
}else{
 echo '<html>
<head>
<meta http-equiv="cache-control" content="max-age=0" />
<meta http-equiv="cache-control" content="no-cache" />
<meta http-equiv="expires" content="0" />
<meta http-equiv="pragma" content="no-cache" />
<meta charset="UTF-8">
<meta http-equiv="refresh" content="300; url=/" />
</head>
<body>
<title>Технический перерыв!</title>
<div style="padding-top: 100px">
<center>
<font SIZE="+2" COLOR="#ff0000">Технический перерыв<br>
Внеплановые технические работы скоро включим сайт обратно.<br>
С уважением, Администрация!<br></font>
</body>';   
 return;
}
}
    
include DIR.'/system/header.php';
    echo $page_text;	
			
$reserve = $db->super_query("SELECT sys_value FROM `system` WHERE `sys_id`='1'");
//$reserve_kup = $db->super_query("SELECT reserve FROM `kupon_stat` WHERE `id`='1'");
                               
		

?>	
</div>
</section> 
</div>
</aside>
</div>
<div class="qoobright">

<div class="qoobright-block">
<?php if (empty($user_id)){?>
	<div class="qoobright-footer">
		<div class="qoobright-block-header">Профиль игрока</div>
		<div class="qoobright-block-content">
			<div class="profile-content" style="display: block;">
<form action="/login/" method="POST">
<p><br/>
<input type="text" name="email" style="width: 85%;padding: 3px 2px;margin-left: 20px;" placeholder="Введите Email" rel="Введите Email"/><br/><br/>
<input type="password" name="passw" style="width: 85%;padding: 3px 2px;margin-left: 20px;" placeholder="Введите Пароль" rel="Введите Пароль"/><br/>
<center><input type="submit" class="buttons" value="Войти"/><br/>
<a href="/register/">Регистрация</a> / <a href="/resetpassword/">Забыли пароль?</a></center>
</p>
</form>     
</div>
	</div>
	</div>
</div>				

<?}else{?>
<div class="righter">

<div class="public_articles">
	<div class="title">Меню</div>
	<ul class="articlesList"><b>

 <?php
                                            if ($user_status==1){?>
                                                <li><a href="/admin/"><img src="/files/ok000000.png" border="0" align="absmiddle"> Админка</a></li>
                                            <?}?>
                <li><a href="/profile/"><img src="/files/user0000.png" border="0" align="absmiddle"> Мой профиль</a></li>


		<li><a href="/mail/in/"><img src="/files/mail0000.png" border="0" align="absmiddle"> Мои сообщения [0]</a></li>
		<li><a href="/shop/"><img src="/files/shop0000.png" border="0" align="absmiddle"> Магазин птиц</a></li>
		<li><a href="/plants/"><img src="/files/farm0000.png" border="0" align="absmiddle"> Моя ферма</a></li>
		<li><a href="/sklad/"><img src="/files/sklad2.png" border="0" align="absmiddle"> Мой склад</a></li>
                <li><a href="/market/"><img src="/files/sell0000.png" border="0" align="absmiddle"> Рынок</a></li>
		<li><a href="/ref/index/"><img src="/files/ref20000.png" border="0" align="absmiddle"> Мои рефералы</a></li>
		<li><a href="/bonus/"><img src="/files/bonus.png" border="0" align="absmiddle"> Ежедневный бонус</a></li>
		<li><a href="/auction/"><img src="/files/auction.png" border="0" align="absmiddle"> Аукцион ставок</a></li>
		<li><a href="/kupon/"><img src="/files/kupon.png" border="0" align="absmiddle"> Купоны</a></li>
		<li><a href="/konkurs_invest/"><img src="/files/investory.png" border="0" align="absmiddle"> Конкурс инвесторов</a></li>
		<li><a href="/konkurs_ref/"><img src="/files/ref00000.png" border="0" align="absmiddle"> Конкурс рефералов</a></li>
                <li><a href="/game/history/"><img src="/files/obm.png" border="0" align="absmiddle"> История операций</a></li>
                <li><a href="/settings/"><img src="/files/config.png" border="0" align="absmiddle"> Настройки</a></li>
                <li><a href="/exit/" style="color: red;"><img src="/files/exit.png" border="0" align="absmiddle"> Выход</a></li>

</b></ul>
<div class="title">Баланс</div><br>
	<ul class="articlesList" style="margin-top: -20px;">
		<li style="font-size: 18px;padding: 5px 10px;"><img src="/files/ruble000.png" border="0" align="absmiddle"> Для покупок: <b><?=$user_gold_pay?></b> руб.</li>
		<li style="font-size: 18px;padding: 5px 10px;"><img src="/files/ruble000.png" border="0" align="absmiddle"> На вывод: <b><?=$user_gold?></b> руб.</li>
		<li><a href="/pay/"><img src="/files/insert00.png" style="margin: 0 3px -3px 0px;"> <b>Пополнить баланс</a></li>
		<li><a href="/payout/payeer/"><img src="/files/payment0.png" style="margin: 0 5px -3px -2px;"> Вывод средств</a></li>
		<li><a href="/game/addmoney/"><img src="/files/obm11.png" style="margin: 0 3px -3px 0px;"> Обменник</b></a></li>
	</ul>
</div>	

<?
}
$pay_all = $db->super_query("SELECT SUM(pay_sum) sum FROM `pay_all`");
?>
 <div class="public_articles" style="margin-top: -40px;">
<div class="title">Статистика</div><br>
	<ul class="articlesList" style="margin-top: -20px;">
		<li><img src="/files/vsego.gif" border="0" align="absmiddle"> <b>Пользователей:</b> <?=$info_page["all_users"]?> чел.</li>
		<li><img src="/files/vsego24.png" border="0" align="absmiddle"> <b>Новых за 24 часа:</b> <?=$info_page["all_users_new"]?> чел.</li>
		<li><img src="/files/ruble000.png" border="0" align="absmiddle"> <b>Активных за 24 часа:</b> <?=$info_page['all_users_aktiv']?> чел.</li>
		<li><img src="/files/ruble000.png" border="0" align="absmiddle"> <b>Пополнено:</b> <?=$pay_all["sum"]+$reserve["sys_value"]?> руб.</li>
		<li><img src="/files/ruble000.png" border="0" align="absmiddle"> <b>Выплачено:</b> <?=$info_page["all_pay"]+0?> руб.</li>
		<li><img src="/files/ruble000.png" border="0" align="absmiddle"> <b>Резерв выплат:</b> <?=$info_page["all_pay"]+0?> руб.</li>
		<li><img src="/files/stimer.png" border="0" align="absmiddle"> <b>Проект работает:</b> <?=$info_page["worked"]?>-й день</li>
                <li><img src="/files/ClockTime.png" border="0" align="absmiddle"> <b>Время на сервере:</b> <span id="seconds">0</span></li>
	</ul>
</div>

                        


<script Language="JavaScript">
 var hours = <?php echo date("H"); ?>;
 var min = <?php echo date("i"); ?>;
 var sec = <?php echo date("s"); ?>;
 function display() {
 sec+=1;
 if (sec>=60)
 {
 min+=1;
 sec=0;
 }
 if (min>=60)
 {
 hours+=1;
 min=0;
 }
 if (hours>=24)
 hours=0;


 if (sec<10)
 sec2display = "0"+sec;
 else
 sec2display = sec;


 if (min<10)
 min2display = "0"+min;
 else
 min2display = min;

 if (hours<10)
 hour2display = "0"+hours;
 else
 hour2display = hours;

 document.getElementById("seconds").innerHTML = hour2display+":"+min2display+":"+sec2display;
 setTimeout("display();", 1000);
 }

 display();
 </script>

</div>
</aside> 
  

  
</div>
  