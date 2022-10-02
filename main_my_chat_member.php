<?php

KirimPerintah('sendMessage',[
    'chat_id' => $config['id_developer'],
    'text' => "nih:".print_r($update['my_chat_member'],true),
]);

// if(!empty($update['my_chat_member']['new_chat_member']['status'])
// and $update['my_chat_member']['new_chat_member']['status'] == 'kicked'){
//     setChatData($chat_id,['active'=>false],false);
// }
// else{
//     setChatData($chat_id,['active'=>true]);
// }

// 		Array
// (
//     [update_id] => 588710485
//     [my_chat_member] => Array
//         (
//             [chat] => Array
//                 (
//                     [id] => 2063236800
//                     [first_name] => Aya
//                     [username] => iniayaku
//                     [type] => private
//                 )

//             [from] => Array
//                 (
//                     [id] => 2063236800
//                     [is_bot] => 
//                     [first_name] => Aya
//                     [username] => iniayaku
//                     [language_code] => id
//                 )

//             [date] => 1640052257
//             [old_chat_member] => Array
//                 (
//                     [user] => Array
//                         (
//                             [id] => 249532802
//                             [is_bot] => 1
//                             [first_name] => GalihJKBOT
//                             [username] => galihjkbot
//                         )

//                     [status] => kicked
//                     [until_date] => 0
//                 )

//             [new_chat_member] => Array
//                 (
//                     [user] => Array
//                         (
//                             [id] => 249532802
//                             [is_bot] => 1
//                             [first_name] => GalihJKBOT
//                             [username] => galihjkbot
//                         )

//                     [status] => member
//                 )
//         )
// )