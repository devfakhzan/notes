<?php
    session_start();
    header('Content-type: application/json');
    
    require '..\vendor\autoload.php';
    $response['success'] = false;

    $d = json_decode(file_get_contents('php://input'), true);
    $response['payload'] = $d;

    if (!$d['id']) {
        $response['error'] = 'Invalid note!';
    }

    $result = Note::where('id', $d['id'])->first();

    if (!$result) {
        $response['error'] = 'Invalid note!';
        goto return_early;
    }

    if ($result['user_id'] != $_SESSION['user_id']) {
        $response['error'] = 'Invalid note to delete!';
        goto return_early;
    }

    $result = Note::where('id', $d['id'])
        ->delete();

    if ($result) {
        $response['success'] = true;
    }

    return_early:
    echo json_encode($response);
    