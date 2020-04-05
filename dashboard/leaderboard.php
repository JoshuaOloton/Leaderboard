<?php
  $userRanking = [];
  function getUSerRankings($fetched_array)
  {
    include "../config/connect.php";

    //user class for each fetched user
    class User 
    {
      public $nickname;
      public $track;
      public $score;

      function __construct($nickname,$track,$score){
        $this->nickname = $nickname;
        $this->track = $track;
        $this->score = $score;
      }
    }

    if (isset($_GET['filter']) && !empty($_GET['filter'])) {
      $filter = $_GET['filter'];
      if ($filter == 'overall') {
        $sql = "SELECT * FROM user WHERE `isAdmin` = 0 ORDER BY `score` DESC LIMIT 20";
      }else {
        $get_university_SQL = "SELECT * FROM universities WHERE `university_id` = '$filter'";
        $get_university_SQL_result = mysqli_query($conn,$get_university_SQL);
        if ($get_university_SQL) {
          while ($university_id = mysqli_fetch_assoc($get_university_SQL_result)) {
            $university = $university_id['university'];
            $sql = "SELECT * FROM user WHERE `isAdmin` = 0 AND `university` = '$university' ORDER BY `score` DESC LIMIT 20";
          }
        }
      }
    }else {
      $sql = "SELECT * FROM user WHERE `isAdmin` = 0 ORDER BY `score` DESC LIMIT 20";
    }
      $result = mysqli_query($conn,$sql);
      if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $nickname = $row['nickname'];
            $track = $row['track'];
            $score = $row['score'];        
            $user = new User($nickname,$track,$score);
            array_push($fetched_array,$user);
        }
      }
      $res =json_encode($fetched_array);
      $file = fopen('results.json','w') or die('error creating file');
      fwrite($file,$res);
}
  getUSerRankings($userRanking);
 ?>
 <!DOCTYPE html>
<html>
    <head>
      <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
      <link rel="stylesheet" href="./index.css">
      <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    </head>
    <body>
      <div class="filter">
        <form id="filterform">
          <select name="" id="filter" class="form-control">
            <option id="overall" value="overall">Overall Rankings</option>
            <?php
              include "../config/connect.php";
              global $conn;
              //get filter parameters
              $sql = "SELECT DISTINCT `university` FROM user";
              $result = mysqli_query($conn,$sql);

              if ($result) {
                while ($row = mysqli_fetch_assoc($result)) {
                  $university = $row;
                  getUniversityId($university);
                  
                }
              }

              function getUniversityId($university){
                global $conn;
                $university = $university['university'];               
                $query = "SELECT * FROM universities WHERE `university` = '$university'";
                $query_result = mysqli_query($conn,$query);
                if ($query_result) {
                  while ($universities_id = mysqli_fetch_assoc($query_result)) {
                    $id = $universities_id['university_id'];
                    echo '<option id=\''.$id.'\' value=\''.$id.'\'>'.$university.'</option>';
                  }
                }
              }
            ?>
           </select>
          <button type="submit" class="btn btn-warning">Filter</button>
        </form>
      </div>
      <div class="center">
        <div class="top3">
          <div class="two item">
            <div class="pos">
              
            </div>
            <div class="pic"></div>
            <div class="name">
              
            </div>
            <div class="track">empty</div>
            <div class="score">
              
            </div>
          </div>
          <div class="one item">
            <div class="pos">
              
            </div>
            <div class="pic"></div>
            <div class="name">
             
            </div>
            <div class="track"></div>
            <div class="score">
           
            </div>
          </div>
          <div class="three item">
            <div class="pos">
           
            </div>
            <div class="pic"></div>
            <div class="name">
              
            </div>
            <div class="track"></div>
            <div class="score">

            </div>
          </div>
        </div>
          <div class="list others">
          </div>
        </div>      
      <script src="leaderboard.js"></script>
    </body>
</html>