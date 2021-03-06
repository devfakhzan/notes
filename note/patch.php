<?php
    header('Content-type: application/json');
    $response['success'] = false;

    $d = json_decode(file_get_contents('php://input'), true);
    $response['payload'] = $d;

    if (!$d['id'] || !$d['note']) {
        $response['error'] = 'Invalid note. Note cannot be empty!';
    }

    $result = Note::where('id', $d['id'])->first();

    if (!$result) {
        $response['error'] = 'Invalid note!';
        goto return_early;
    }

    if ($result['user_id'] != $auth->sub->user_id) {
        $response['error'] = 'Invalid note to edit!';
        goto return_early;
    }

    $result = Note::where('id', $d['id'])
        ->update([
            'note' => $d['note'],
            'updated_at' => date('Y-m-d H:i:s', time())
        ]);

    if ($result) {
        $response['success'] = true;
    }

    return_early:
    echo json_encode($response);
    