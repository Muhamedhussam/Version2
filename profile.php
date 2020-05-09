<?php
include('./classes/DB.php');
include('./classes/Login.php');

$username = "";
$isFollowing = False;
if (isset($_GET['username'])) {
        if (DB::query('SELECT username FROM users WHERE username=:username', array(':username'=>$_GET['username']))) {

                $username = DB::query('SELECT username FROM users WHERE username=:username', array(':username'=>$_GET['username']))[0]['username'];
                $userid = DB::query('SELECT id FROM users WHERE username=:username', array(':username'=>$_GET['username']))[0]['id'];
                $followerid = Login::isLoggedIn();

                if (isset($_POST['follow'])) {

                        if ($userid != $followerid) {

                                if (!DB::query('SELECT follower_id FROM followers WHERE user_id=:userid', array(':userid'=>$userid))) {
                                        DB::query('INSERT INTO followers VALUES (\'\', :userid, :followerid)', array(':userid'=>$userid, ':followerid'=>$followerid));
                                } else {
                                        echo 'Already following!';
                                }
                                $isFollowing = True;
                        }
                }
                if (isset($_POST['unfollow'])) {

                        if ($userid != $followerid) {

                                if (DB::query('SELECT follower_id FROM followers WHERE user_id=:userid', array(':userid'=>$userid))) {
                                        DB::query('DELETE FROM followers WHERE user_id=:userid AND follower_id=:followerid', array(':userid'=>$userid, ':followerid'=>$followerid));
                                }
                                $isFollowing = False;
                        }
                }
                if (DB::query('SELECT follower_id FROM followers WHERE user_id=:userid', array(':userid'=>$userid))) {
                        //echo 'Already following!';
                        $isFollowing = True;
                }
                if  (isset($_POST['post'])){
                  $postbody  = $_post['postbody'];
                  $userid= Login::isLoggedIn();
                  if(strlen($postbody)> 160 ||  strlen($postbody)  < 1)[

                    die('Incorrect lenght!');
                  ]
                  htmlspecialchars
                DB::query('INSERT INTO posts VALUES(\'\',  :postbody , NOW(), :userid, 0)', array (':postbody'=>$postbody,':userid'=>$userid));

                }
                $dbposts = DB::query('SELECT * FROM  posts WHERE user_id=:userid  ORDER BY id DESC', array(':userid'=>$userid));
                $post = "";
                foreach ($dbposts as $p) {
                $posts  .=  $p['body']."<hr /></br />"; 
                }

        } else {
                die('User not found!');
        }
}

?>
<h1><?php echo $username; ?>'s Profile</h1>
<form action="profile.php?username=<?php echo $username; ?>" method="post">
        <?php
        if ($userid != $followerid) {
                if ($isFollowing) {
                        echo '<input type="submit" name="unfollow" value="Unfollow">';
                } else {
                        echo '<input type="submit" name="follow" value="Follow">';
                }
        }
        ?>
</form>
<form action="profile.php?username=<?php echo $username; ?>" method="post">
<textarea name="postbody" rows="8" cols="80"></textarea>
<input type="submit"  name="post" value="Post">
</form>
<div  class="posts">
  <?php echo $posts; ?>
</div>
