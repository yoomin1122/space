<?php
// 데이터베이스 연결 설정
$host = 'localhost:3306';
$db = '';
$user = 'root';
$password = '';

try {
    // 데이터베이스 연결
    $conn = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // 랭킹 가져오기
    
    $query = "SELECT username, score FROM php.scores ORDER BY score DESC LIMIT 3";
    $stmt = $conn->query($query);
    $ranking = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // 응답 전송
    $response = array('success' => true, 'ranking' => $ranking);
    echo json_encode($response);
} catch (PDOException $e) {
    // 에러 응답
    $response = array('success' => false, 'message' => $e->getMessage());
    echo json_encode($response);
}
