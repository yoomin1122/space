<?php
$conn = mysqli_connect('localhost:3306','root','','php'); 
echo '<script>';
if($conn->connect_error) echo 'console.log ("DB에 접속할수 없습니다.");';
else echo 'console.log ("DB에 접속하였습니다.");';
echo '</script>';
?>
<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Space 키 게임</title>
    <link rel="stylesheet" href="style.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script type="text/javascript">
        // 스페이스 바 키코드
        var spaceKeyCode = 32;
        // 점수 초기화
        var score = 0;
        // 게임 종료 여부
        var gameOver = true;
        // 스페이스 바 누른 시간
        var spaceKeyDownTime = 0;
        // 시작 버튼 클릭 이벤트 핸들러
        function startGame() {
            var username = document.getElementById('username').value;
            if (!username) {
                alert('사용자 이름을 입력하세요.');
                return;
            }

            score = 0;
            gameOver = false;
            spaceKeyDownTime = 0;
            document.getElementById('score').innerHTML = score;
            document.getElementById('startBtn').disabled = true;

            // 키 이벤트 리스너 등록
            window.addEventListener('keydown', keyDownHandler);
            window.addEventListener('keyup', keyUpHandler);

            // 게임 종료 타이머
            setTimeout(endGame, 15000);
        }

        // 키 다운 이벤트 핸들러
        function keyDownHandler(event) {
            // 게임이 종료되었을 경우 키 입력 무시
            if (gameOver) {
                return;
            }

            // 스페이스 바를 눌렀을 경우 점수 증가 및 시간 기록
            if (event.keyCode === spaceKeyCode) {
                score++;
                document.getElementById('score').innerHTML = score;

                // 스페이스 바 누른 시간 기록
                if (spaceKeyDownTime === 0) {
                    spaceKeyDownTime = Date.now();
                } else if (Date.now() - spaceKeyDownTime >= 999) {
                    alert('1초 이상 Space 키를 누르셨습니다. 게임이 중지됩니다.');
                    endGame();
                }
            }
        }

        // 키 업 이벤트 핸들러
        function keyUpHandler(event) {
            // 스페이스 바를 뗐을 경우 스페이스 바 누른 시간 초기화
            if (event.keyCode === spaceKeyCode) {
                spaceKeyDownTime = 0;
            }
        }

        // 게임 종료 함수
        function endGame() {
            // 게임 종료 플래그 설정
            gameOver = true;
            document.getElementById('startBtn').disabled = false;

            // 키 이벤트 리스너 제거
            window.removeEventListener('keydown', keyDownHandler);
            window.removeEventListener('keyup', keyUpHandler);

            // 점수 저장
            saveScore();
        }

        // 점수 저장 함수
        function saveScore() {
            var username = document.getElementById('username').value;
            var score = document.getElementById('score').innerHTML;

            $.ajax({
                url: 'save_score.php',
                type: 'POST',
                data: {username: username, score: score},
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        // 랭킹 가져오기
                        getRanking();
                    } else {
                        getRanking();
                        alert('점수 저장에 실패했습니다.');
                        console.log(response);
                    }
                },
                error: function() {
                    getRanking();
                    alert('점수 저장에 실패했습니다.');
                    console.log(response);
                }
            });
        }

        // 랭킹 가져오기 함수
        function getRanking() {
            $.ajax({
                url: 'get_ranking.php',
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        var ranking = response.ranking;
                        var rankingHTML = '';
                        for (var i = 0; i < ranking.length; i++) {
                            rankingHTML += '<li>' +[i+1]+'등 : ' + ranking[i].score + ' - ' + ranking[i].username + '</li>';
                        }
                        document.getElementById('ranking').innerHTML = rankingHTML;
                    } else {
                        console.log(response);
                        alert('랭킹 가져오기에 실패했습니다.');
                        
                    }
                },
                error: function() {
                    console.log(response);
                    alert('랭킹 가져오기에 실패했습니다.');
                    
                }
            });
        }
    getRanking();
    </script>
    <style>
* {
  font-family: "Noto Sans KR", sans-serif;
  text-align: center;
  max-width: 700px;
margin-left : auto;
margin-right : auto;
}

ul {
    list-style-type : none;
    display: flex

}
input {
  margin-top: 3em;
  border-radius: 30px 0px 0px 30px;
  box-shadow: 0 4px 5px 0 rgba(0, 0, 0, 0.2), 0 5px 0 0 rgba(0, 0, 0, 0.19);
  position: relative;
  width: 380px;
  height: 68px;
  text-indent: 17px;
  font-size: 20px;
  border: none;
}

button {
  margin-top: 3em;
  background-color: #848beb;
  border-radius: 0px 30px 30px 0px;
  box-shadow: 0 4px 5px 0 rgba(0, 0, 0, 0.2), 0 5px 0 0 rgba(0, 0, 0, 0.19);
  border: none;
  width: 145px;
  height: 70px;
  font-size: 20px;
  color: #fff;
}
button:hover {
  text-decoration-line: underline;
}
</style>      
</head>
<body>
    <h1>Space 키 게임</h1>
    <input type="text" id="username" autocomplete='off' placeholder="사용자 이름">
    <button id="startBtn" onclick="startGame()">게임 시작</button>
    <p>15초 동안 Space 키를 누르세요!</p>
    <p>점수: <span id="score">0</span></p>
    
    <h2>랭킹</h2>
    <ul id="ranking"></ul>
</body>
</html>
