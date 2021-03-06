<?php 
    if($_POST) {
        $d = $_POST;
    }
    if (isset($d['note'])) {
        Note::insert([
            'note' => $d['note'],
            'user_id' => $auth->sub->user_id,
            'created_at' =>  date('Y-m-d H:i:s', time()),
            'updated_at' => date('Y-m-d H:i:s', time()),
        ]);
    }

    header('Location: /');
?>