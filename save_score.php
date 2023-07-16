<?php
// 데이터베이스 연결 설정
$host = 'localhost:3306';
$db = 'php';
$user = 'root';
$password = 'yoominserver1122';

try {
    // 데이터베이스 연결
    $conn = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // 사용자 이름과 점수 가져오기
    $username = isset($_POST['username']) ? $_POST['username'] : '';
    $score = isset($_POST['score']) ? intval($_POST['score']) : 0;



    // 사용자 이름이 비어있을 경우 에러 응답
    if ($username === '') {
        $response = array('success' => false, 'message' => '사용자 이름을 입력하세요.');
        echo json_encode($response);
        exit;
    }

    // 점수 저장
    $stmt=$conn->prepare("INSERT INTO php.scores (username, score) VALUES (:username, :score);");
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':score', $score);
    $stmt->execute();

    // 응답 전송
    $response = array('success' => true);
    echo json_encode($response);
} catch (PDOException $e) {
    // 에러 응답
    $response = array('success' => false, 'message' => $e->getMessage());
    echo json_encode($response);


}

