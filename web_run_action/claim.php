<?php 
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
        'text'=> "GAGAL: Kayaknya yang mau kamu claim itu sudah kadaluarsa atau sudah diclaim sebelumnya.. coba cek /claim lagi..",
        'parse_mode'=>'HTML',
    ]);
    goto skip_to_end;
}

$unclaimeds = $userdata['unclaimeds'];
$claimdata = $unclaimeds[$gametype][$claim_chat_id][$claimcode];

KirimPerintah('sendMessage',[
    'chat_id' => $user_id,
    'text'=> "Selamat, user. Anda telah mendapatkan xxx poin." . print_r($claimdata, true),
    'parse_mode'=>'HTML',
]);

$tokpeds = explode(" ",loadData("setting/tokpeds"));
$tokpeds_index = loadData("setting/tokpeds_index",0);
$tokpeds_index++;
saveData("setting/tokpeds_index",$tokpeds_index);

KirimPerintah('sendMessage',[
    'chat_id' => $user_id,
    'text'=> "Tokepes: $tokpeds_index.." . print_r($tokpeds, true),
    'parse_mode'=>'HTML',
]);

?>
    Berhasil, silakan close browser ini.
    <script type="text/javascript">
        var sudah = false;
        if(!sudah){
            sudah = true;
            setTimeout(() => {
                window.location.href = "https://tokopedia.link/kZ8Br26bosb";
            }, 500);
        }
    </script>
<?php

skip_to_end:

?>
    <script>
        setTimeout(() => {
            window.close('','_parent','');
            window.location.href = "https://t.me/<?= $config['bot_username'] ?>";
        }, 500);
    </script>
<?php

htmlEnd();