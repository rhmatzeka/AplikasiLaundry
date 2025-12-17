<?php
require 'config/db.php';

if(!isset($_SESSION['user'])) exit;

$action = $_POST['action'] ?? '';
$user_id = $_SESSION['user']['id'];

// 1. KIRIM PESAN
if($action == 'send') {
    $order_id = $_POST['order_id'];
    $room_type = $_POST['room_type'] ?? 'driver'; // 'driver' atau 'admin'
    $message = htmlspecialchars($_POST['message']); 

    if(!empty($message)) {
        $stmt = $pdo->prepare("INSERT INTO chats (order_id, sender_id, message, room_type) VALUES (?, ?, ?, ?)");
        $stmt->execute([$order_id, $user_id, $message, $room_type]);
    }
}

// 2. AMBIL PESAN (LOAD CHAT)
if($action == 'get') {
    $order_id = $_POST['order_id'];
    $room_type = $_POST['room_type'] ?? 'driver';
    
    // Ambil chat filter by room_type
    $stmt = $pdo->prepare("SELECT c.*, u.nama, u.role 
                           FROM chats c 
                           JOIN users u ON c.sender_id = u.id 
                           WHERE c.order_id = ? AND c.room_type = ? 
                           ORDER BY c.created_at ASC");
    $stmt->execute([$order_id, $room_type]);
    $chats = $stmt->fetchAll();

    if(count($chats) == 0) {
        echo '<div class="text-center text-gray-400 text-sm mt-4">Belum ada percakapan dengan '.ucfirst($room_type).'.</div>';
    }

    foreach($chats as $c) {
        $is_me = ($c['sender_id'] == $user_id);
        $align = $is_me ? 'justify-end' : 'justify-start';
        $bg = $is_me ? 'bg-blue-600 text-white rounded-br-none' : 'bg-gray-200 text-gray-800 rounded-bl-none';
        $time = date('H:i', strtotime($c['created_at']));
        $sender_name = $is_me ? 'Saya' : htmlspecialchars($c['nama']);

        echo "
        <div class='flex $align mb-3'>
            <div class='max-w-[75%]'>
                <p class='text-[10px] text-gray-500 mb-1 " . ($is_me ? 'text-right' : '') . "'>$sender_name</p>
                <div class='$bg p-3 rounded-2xl shadow-sm text-sm break-words'>
                    {$c['message']}
                </div>
                <p class='text-[9px] text-gray-400 mt-1 " . ($is_me ? 'text-right' : '') . "'>$time</p>
            </div>
        </div>
        ";
    }
}
?>