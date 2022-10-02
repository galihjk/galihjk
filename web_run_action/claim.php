<?php 
$data = loadData("data");
$data_old = $data;

htmlBegin();
if(empty($_GET['code'])) goto skip_to_end;
$explode = explode("|",$_GET['code']);
if(count($explode) != 4) goto skip_to_end;
$user_id = $explode[0];
$gametype = $explode[1];
$claim_chat_id = $explode[2];
$claimcode = $explode[3];

checkExpiredUnclaimeds($user_id);

$userdata = getUser($user_id);

if(empty($userdata['unclaimeds'][$gametype][$claim_chat_id][$claimcode])){
    KirimPerintah('sendMessage',[
        'chat_id' => $user_id,
        'text'=> "GAGAL: Kayaknya yang mau kamu klaim itu sudah kadaluarsa atau sudah diklaim sebelumnya.. coba cek /claim lagi..",
        'parse_mode'=>'HTML',
    ]);
    goto skip_to_end;
}

$unclaimeds = $userdata['unclaimeds'];
$claimdata = $unclaimeds[$gametype][$claim_chat_id][$claimcode];
$point_add = $claimdata[0];

$point = $userdata['point'] ?? 0;
$w_point = $userdata['w_point'] ?? [];

$point += $point_add;
if(empty($w_point[$gametype][$claim_chat_id])) $w_point[$gametype][$claim_chat_id] = 0;

//ini nanti dipake:
//$w_point[$gametype][$claim_chat_id] += $point_add;

unset($unclaimeds[$gametype][$claim_chat_id][$claimcode]);
if(empty($unclaimeds[$gametype][$claim_chat_id])) unset($unclaimeds[$gametype][$claim_chat_id]);
if(empty($unclaimeds[$gametype])) unset($unclaimeds[$gametype]);

setUser($user_id,[
    'unclaimeds'=>$unclaimeds,
    'point'=>$point,
    'w_point'=>$w_point,
]);

KirimPerintah('sendMessage',[
    'chat_id' => $user_id,
    'text'=> "Selamat! Anda telah mendapatkan $point_add /point ..",
    'parse_mode'=>'HTML',
]);

$tokpeds = explode(" ",loadData("setting/tokpeds"));
$tokpeds_index = loadData("setting/tokpeds_index",0);

if(!isset($tokpeds[$tokpeds_index])){
    $tokpeds_index = 0;
    shuffle($tokpeds);
    saveData("setting/tokpeds",implode(" ",$tokpeds));   
}

$tokped_code = $tokpeds[$tokpeds_index];

$tokpeds_index++;
saveData("setting/tokpeds_index",$tokpeds_index);

?>
    Berhasil, silakan close browser ini.
    <script type="text/javascript">
        var sudah = false;
        if(!sudah){
            sudah = true;
            setTimeout(() => {
                window.location.href = "https://tokopedia.link/<?= $tokped_code ?>";
            }, 500);
        }
    </script>
<?php

skip_to_end:

?>
    <script>
        setTimeout(() => {
            window.close('','_parent','');
        }, 1000);
        setTimeout(() => {
            window.location.href = "https://t.me/<?= $config['bot_username'] ?>";
        }, 1500);
    </script>
<?php

if($data_old != $data){
    saveData("data", $data);
}
htmlEnd();