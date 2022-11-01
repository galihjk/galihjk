<?php
$channel_username = "@galihjksoal";
if(isDiawali($reply_to_message_text,"[SOAL]\n\nBalas pesan ini untuk membuat soal survey. ")){
    $jenis_soal = 'survey';
    $soal = $message_text;
    $channel_post = KirimPerintah('sendMessage',[
        'chat_id' => $channel_username,
        'text'=> "Loading...",
        'parse_mode'=>'HTML',
    ]);
    $id_soal = $channel_post['result']['message_id'];
    $data_soal = [
        'soal'=>$soal,
        'vote'=>[
            $from_id => 1,
        ],
        'vtsc'=>1,
        'jawab'=>[],
    ];
    updateSoalPost($id_soal,$jenis_soal,$data_soal);
    saveData("soal/$jenis_soal/$id_soal",$data_soal);
    userContributeSoal($from_id);

    KirimPerintah('sendMessage',[
        'chat_id' => $chat_id,
        'text'=> "Berhasil. Lihat soalmu di: https://t.me/galihjksoal/$id_soal",
        'parse_mode'=>'HTML',
        'reply_markup' => [
            'force_reply'=>false,
        ],
    ]);
}
elseif(isDiawali($reply_to_message_text,"[SOAL]\n\nKenapa kamu ingin menghapus soal ini?")){
    $explode = explode("|ID:",$reply_to_message_text);
    if(!empty($explode[1])){
        $kodesoal = $explode[1];
        $explode2 = explode("|",$kodesoal);
        if(!empty($explode2[1])){
            $jenis_soal = $explode2[0];
            $id_soal = $explode2[1];
            $data_soal = loadData("soal/$jenis_soal/$id_soal");
            if(!empty($data_soal['soal'])){
                if(isset($data_soal['delsc'])){
                    KirimPerintah('sendMessage',[
                        'chat_id' => $chat_id,
                        'text'=> "Soal ini memang sedang dalam proses penghapusan..",
                        'parse_mode'=>'HTML',
                    ]);
                }
                else{
                    $data_soal['delsc'] = 0;
                    $data_soal['alasan'] = $message_text;
                    saveData("soal/$jenis_soal/$id_soal",$data_soal);
                    $soal = $data_soal['soal'];
                    $text = getUser($from_id)['first_name'] . " ingin <b>MENGHAPUS</b> soal ini\n===\n$soal\n===\nAlasan: <b>$message_text</b>\n\n";
                    $text .= "*<i>Akan diproses dalam 3 hari</i>";
                    $channel_post = KirimPerintah('sendMessage',[
                        'chat_id' => $channel_username,
                        'text'=> $text,
                        'parse_mode'=>'HTML',
                        'reply_to_message_id' =>$id_soal,
                        'reply_markup' => inlineKeyBoard([
                            ["$emoji_check Hapus","soal_yesdelete_$id_soal"."__$jenis_soal"],
                            ["$emoji_cross Jangan","soal_nodelete_$id_soal"."__$jenis_soal"],
                        ],2),
                    ]);
                    if(!empty($channel_post['result']['message_id'])){
                        $post_message_id = $channel_post['result']['message_id'];
                        create_job("
                            \$delcheck_jenis = '$jenis_soal';
                            \$delcheck_id = '$id_soal';
                            \$chat_id = '$chat_id';
                            \$delcheck_msgid = '$post_message_id';
                            include('galihjk/games/soal/delcheck.php');
                            ",
                            // time()+(3*24*60*60)
                            time()+20
                        );
                        KirimPerintah('sendMessage',[
                            'chat_id' => $chat_id,
                            'text'=> "Silakan konfirmasi di sini jika anda yakin: https://t.me/galihjksoal/$post_message_id",
                            'parse_mode'=>'HTML',
                        ]);
                    }                    
                }
                userContributeSoal($from_id);
            }
        }
    }
    
}
elseif(isDiawali($reply_to_message_text,"[SOAL]\n\nUntuk memudahkan proses edit, silakan ")){
    //EDIT SOAL
    $explode = explode("|ID:",$reply_to_message_text);
    if(!empty($explode[1])){
        $kodesoal = $explode[1];
        $explode2 = explode("|",$kodesoal);
        if(!empty($explode2[1])){
            $jenis_soal = $explode2[0];
            $id_soal = $explode2[1];
            $data_soal = loadData("soal/$jenis_soal/$id_soal");
            if(!empty($data_soal['soal'])){
                if(isset($data_soal['editsc'])){
                    KirimPerintah('sendMessage',[
                        'chat_id' => $chat_id,
                        'text'=> "Soal ini sedang dalam proses edit..",
                        'parse_mode'=>'HTML',
                    ]);
                }
                else{
                    $data_soal['editby'] = $from_id;
                    $data_soal['editsc'] = 0;
                    $data_soal['edit'] = $message_text;
                    saveData("soal/$jenis_soal/$id_soal",$data_soal);
                    $soal = $data_soal['soal'];
                    $text = getUser($from_id)['first_name'] . " ingin <b>MENGUBAH</b> soal ini\n===\n$soal\n$emoji_down$emoji_down MENJADI $emoji_down$emoji_down\n<b>$message_text</b>\n===\n";
                    $text .= "*<i>Akan diproses dalam 3 hari</i>";
                    $channel_post = KirimPerintah('sendMessage',[
                        'chat_id' => $channel_username,
                        'text'=> $text,
                        'parse_mode'=>'HTML',
                        'reply_to_message_id' =>$id_soal,
                        'reply_markup' => inlineKeyBoard([
                            ["$emoji_check Setuju","soal_yesedit_$id_soal"."__$jenis_soal"],
                            ["$emoji_cross Jangan","soal_noedit_$id_soal"."__$jenis_soal"],
                        ],2),
                    ]);
                    if(!empty($channel_post['result']['message_id'])){
                        $post_message_id = $channel_post['result']['message_id'];
                        create_job("
                            \$editcheck_jenis = '$jenis_soal';
                            \$editcheck_id = '$id_soal';
                            \$chat_id = '$chat_id';
                            \$editcheck_msgid = '$post_message_id';
                            include('galihjk/games/soal/editcheck.php');
                            ",
                            // time()+(3*24*60*60)
                            time()+20
                        );
                        KirimPerintah('sendMessage',[
                            'chat_id' => $chat_id,
                            'text'=> "Silakan konfirmasi di sini jika anda yakin: https://t.me/galihjksoal/$post_message_id",
                            'parse_mode'=>'HTML',
                        ]);
                    }                    
                }
                userContributeSoal($from_id);
            }
        }
    }
    
}
elseif(isDiawali($reply_to_message_text,"[SOAL]\n\nJawaban untuk:\n======\n")){
    //TAMBAH JAWABAN SOAL
    $explode = explode("|ID:",$reply_to_message_text);
    if(!empty($explode[1])){
        $kodesoal = $explode[1];
        $explode2 = explode("|",$kodesoal);
        if(!empty($explode2[1])){
            $jenis_soal = $explode2[0];
            $id_soal = $explode2[1];
            $data_soal = loadData("soal/$jenis_soal/$id_soal");
            if(!empty($data_soal['soal'])){
                $jawaban_submit = $message_text;
                KirimPerintah('sendMessage',[
                    'chat_id' => $chat_id,
                    'text'=> "Kamu menambahkan jawaban '$jawaban_submit' untuk soal $jenis_soal -- http://t.me/galihjksoal/$id_soal",
                    'parse_mode'=>'HTML',
                ]);
                // if(isset($data_soal['editsc'])){
                //     KirimPerintah('sendMessage',[
                //         'chat_id' => $chat_id,
                //         'text'=> "Soal ini sedang dalam proses edit..",
                //         'parse_mode'=>'HTML',
                //     ]);
                // }
                // else{
                //     $data_soal['editby'] = $from_id;
                //     $data_soal['editsc'] = 0;
                //     $data_soal['edit'] = $message_text;
                //     saveData("soal/$jenis_soal/$id_soal",$data_soal);
                //     $soal = $data_soal['soal'];
                //     $text = getUser($from_id)['first_name'] . " ingin <b>MENGUBAH</b> soal ini\n===\n$soal\n$emoji_down$emoji_down MENJADI $emoji_down$emoji_down\n<b>$message_text</b>\n===\n";
                //     $text .= "*<i>Akan diproses dalam 3 hari</i>";
                //     $channel_post = KirimPerintah('sendMessage',[
                //         'chat_id' => $channel_username,
                //         'text'=> $text,
                //         'parse_mode'=>'HTML',
                //         'reply_to_message_id' =>$id_soal,
                //         'reply_markup' => inlineKeyBoard([
                //             ["$emoji_check Setuju","soal_yesedit_$id_soal"."__$jenis_soal"],
                //             ["$emoji_cross Jangan","soal_noedit_$id_soal"."__$jenis_soal"],
                //         ],2),
                //     ]);
                //     if(!empty($channel_post['result']['message_id'])){
                //         $post_message_id = $channel_post['result']['message_id'];
                //         create_job("
                //             \$editcheck_jenis = '$jenis_soal';
                //             \$editcheck_id = '$id_soal';
                //             \$chat_id = '$chat_id';
                //             \$editcheck_msgid = '$post_message_id';
                //             include('galihjk/games/soal/editcheck.php');
                //             ",
                //             // time()+(3*24*60*60)
                //             time()+20
                //         );
                //         KirimPerintah('sendMessage',[
                //             'chat_id' => $chat_id,
                //             'text'=> "Silakan konfirmasi di sini jika anda yakin: https://t.me/galihjksoal/$post_message_id",
                //             'parse_mode'=>'HTML',
                //         ]);
                //     }                    
                // }
                userContributeSoal($from_id);
            }
        }
    }
}