<?php
require '../init.php';
$userController->cookie_login();
if ($userController->is_authenticated()) {
    require 'member_header.php';
} else {
    require 'header.php';
}

if(isset($_POST['submit']) && $_SERVER['REQUEST_METHOD'] == "POST"){
  echo uploadFiles();
}

$id1 = $_GET['id'];
$id = intval($id1);

if ($id == 0) {
header("Location:../index.php", true, 301);
exit;
} else {
    $row = $groupController->get_group_by_id($id);
    if (is_null($row) || empty($row)) {
        //Log message here
        header("Location: groups.php");
    } else {
        extract($row);
        ?>
        <section>
            <div class="container">
                <div class="row">
                  <div class="col-md-12">
                    <div class="pad-up-50">
                      <h1 class=""><?php echo $group_title; ?></h1>
                    </div>
                  </div>
                    <div class="col-md-8 group_layout">

                      <ul class="nav nav-tabs">
                        <li class="active"><a data-toggle="tab" href="#discussion">Discussions</a></li>
                        <li><a data-toggle="tab" href="#member">Members</a></li>
                        <li><a data-toggle="tab" href="#about">About</a></li>
                        <li><a data-toggle="tab" href="#details">Admin Details</a></li>
                      </ul>

                      <div class="tab-content">
                        <div id="discussion" class="tab-pane fade in active">
                          <?php if($groupController->is_group_member($group_id, get_user_uid()) && $userController->is_authenticated()){?>
                                  <!--Leave a reply form-->
                                  <div class="reply-form">
                                    <div class="row">
                                      <div class="col-md-10">
                                        <h3 class="section-heading">Start A Discussion </h3>
                                      </div>
                                      <div class="col-md-2">
                                        <a href='group_page.php?id=<?php echo $group_id; ?>' id='leave_group' gid = '<?php echo $group_id; ?>' class='btn btn-danger'>Leave Group</a>
                                      </div>
                                    </div>
                                      <!--Third row-->
                                      <div class="row">
                                          <form class="col-md-12" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">
                                              <div class="form-group">
                                                  <label for="commentBox">Type your topic in the box below:</label>
                                                  <textarea type="text" rows="5" id="commentBox" name="commentBox" class="form-control"></textarea>
                                                  <input type="hidden" id="d" name="d" value="<?php echo $id; ?>"></input>
                                              </div>
                                              <div class="form-group">
                                                  <input type="file" class="form" name="file[]"></input>
                                              </div>
                                              <div class="form-group">
                                                  <input type="file" class="form" name="file[]"></input>
                                              </div>

                                              <div class="form-group">
                                                  <a href="#cc">
                                                      <a href="#" class="btn btn-primary" name="submit" id="submit" gid="<?php echo $id; ?>">Post Topic</a>
                                                  </a>
                                              </div>
                                              <!--/.Content column-->
                                          </form>
                                      </div>
                                      <!--/.Third row-->
                                  </div>
                                  <?php
                                  }else{
                                    echo "<p>Not yet a member, Join group to start discussing </p><br />";
                                    echo "<a href='group_page.php?id=$group_id' id='join_group' gid = '$group_id' class='btn btn-primary'>Join Group</a>";
                                  }
                                  ?>
                                  <!--/.Leave a reply form-->
                                  <div class="row">
                                    <div class="col-md-10">
                                  <h3>Group Discussions (<?php echo $group_comment_count; ?>)</h3>
                                </div>
                                <div class="col-md-2">
                                  <ul class="nav nav-pills">
                                  <li class="dropdown">
                                   <a class="dropdown-toggle btn btn-success" data-toggle="dropdown" href="#">Sort By <span class="caret"></span></a>
                                   <ul class="dropdown-menu">
                                     <li><a href="#" pid = "<?php echo $group_id ?>" class="">Newest</a></li>
                                     <li><a href="#" pid = "<?php echo $group_id ?>" class="">Best</a></li>
                                     <li><a href="#" pid = "<?php echo $group_id ?>" class="">Oldest</a></li>
                                   </ul>
                                 </li>
                               </ul>
                                </div>
                                </div>
                                  <hr />
                                  <!-- commentlist here -->
                                  <div class="row">
                                      <div class="col-md-12 pad-bottom-20">
                                          <?php
                                          $stmt = $groupController->get_group_discussions_by_id($group_id);
                                          if ($stmt->rowCount() > 0) {
                                              while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                                  extract($row);
                                                  ?>
                                                  <div class="commentlist">
                                                      <ul>
                                                          <div class="comment-wrap">
                                                              <div class="comment-avatar">
                                                                  <img alt="" src="../res/imgs/1.jpg" class="" height="45" width="45">
                                                              </div>
                                                              <div class="author-comment">
                                                                  <cite class="fn"><?php echo $userController->get_user_username_by_id($discussion_user_id); ?></cite>
                                                                  <div class="">
                                                                      <a href="">	<?php echo timeAgo($discussion_date); ?></a>
                                                                  </div>
                                                              </div>
                                                              <div class="clear"></div>
                                                              <div class="comment-content">
                                                                  <p><?php echo $discussion_body ?>;</p>
                                                              </div>
                                                              <div class="like">
                                                                  <a rel="nofollow" class="comment-reply-link" href="http://localhost/kaperskyguru/testing-out-the-newsletter/?replytocom=5#respond" aria-label="Reply to Kap man"><span class="fa fa-thumbs-up"></span> Like <span class="badge">0</span></a>
                                                              </div>
                                                              <div class="dislike">
                                                                  <a rel="nofollow" class="comment-reply-link" href="#" aria-label="Reply to Kap man"><span class="fa fa-thumbs-down"></span> Dislike <span class="badge badge-danger">0</span></a>
                                                              </div>
                                                              <div class="reply">
                                                                  <a rel="nofollow" class="comment-reply-link" pid="<?php echo $discussion_id ?>" href="#" id="reply" aria-label="Reply to Kap man"><span class="fa fa-reply"></span> Reply</a>
                                                              </div>
                                                          </div>
                                                          <!-- Replies -->
                                                          <?php
                                                          $stmt1 = $replyController->get_replies_by_discussion_id($discussion_id);
                                                          if ($stmt1->rowCount() > 0) {
                                                              while ($row1 = $stmt1->fetch(PDO::FETCH_ASSOC)) {
                                                                  extract($row1);
                                                                  ?>
                                                                  <li class="replies" style="margin-left:15px">
                                                                      <div class="comment-wrap ">
                                                                          <div class="comment-avatar">
                                                                              <img alt="" src="../res/imgs/1.jpg" class="" height="45" width="45">
                                                                          </div>
                                                                          <div class="author-comment">
                                                                              <cite class="fn">Posted by: <?php echo $userController->get_user_username_by_id($comment_user_id); ?></cite>
                                                                              <div class="">
                                                                                  <a href="">	<?php echo timeAgo($reply_date); ?></a>
                                                                              </div>
                                                                          </div>
                                                                          <div class="clear"></div>
                                                                          <div class="comment-content">
                                                                              <p><?php echo $reply_content ?>;</p>
                                                                          </div>
                                                                          <div class="like">
                                                                              <a rel="nofollow" class="comment-reply-link" href="http://localhost/kaperskyguru/testing-out-the-newsletter/?replytocom=5#respond" aria-label="Reply to Kap man"><span class="fa fa-thumbs-up"></span> Like <span class="badge">0</span></a>
                                                                          </div>
                                                                          <div class="dislike">
                                                                              <a rel="nofollow" class="comment-reply-link" href="#" aria-label="Reply to Kap man"><span class="fa fa-thumbs-down"></span> Dislike <span class="badge badge-danger">0</span></a>
                                                                          </div>
                                                                          <div class="reply">
                                                                              <a rel="nofollow" class="comment-reply-link" pid="<?php echo $reply_id ?>" href="#" id="reply" aria-label="Reply to Kap man"><span class="fa fa-reply"></span> Reply</a>
                                                                          </div>
                                                                      </div>
                                                                  </li>
                                                                  <?php
                                                              }
                                                          }
                                                          ?>
                                                      </ul>
                                                  </div>
                                                  <?php
                                              }
                                          } else {
                                              echo "<p>No Discussions Yet</p>";
                                          }
                                          ?>
                              </div>
                          </div>

                        </div>

                        <div id="member" class="tab-pane fade">
                          <h2>All Group members</h2>
                          <div class="row">

                            <?php
                              $stmt = $groupController->get_group_members($group_id);
                                if($stmt->rowCount() > 0){
                                  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                    extract($row);?>
                                      <div class="col-md-4" style="border-right:1px solid #eee">
                                        <div class="row">
                                        <div class="col-md-4">
                                            <img src="../res/imgs/1.jpg" class="img-responsive img-thumbnail">
                                            <?php if($groupController->is_group_admin($group_id, get_user_uid())){ ?>
                                              <a href="#" class="btn btn-xs btn-danger">Remove</a>
                                            <?php }?>
                                        </div>
                                        <div class="col-md-8">
                                            <a href="#"><h5><?php echo $userController->get_user_username_by_id($membership_user_id);?></h5></a>
                                            <p><?php echo "am a good boy";?></p>
                                        </div>
                                      </div>
                                      </div>
                                    <?php
                                    }
                                  }
                                  ?>
                          </div>
                          <hr >
                        </div>

                        <div id="about" class="tab-pane fade">
                          <h2>About Group</h2>
                          <table class="table-condensed table-bordered table-responsive">
                            <tr>
                              <th>
                                Group Admin:
                              </th>
                              <td>
                                <?php echo $userController->get_user_display_name_by_id($group_user_id, true) ?>
                              </td>
                              </tr>
                              <tr>
                              <th>
                                Created on:
                              </th>
                              <td>
                                <?php echo get_formatted_date($group_date_created);?>
                              </td>
                            </tr>
                            <tr>
                              <th>
                                # Members:
                              </th>
                              <td>
                                <?php echo $groupController->get_number_group_members_by_id($group_id);
                                if($groupController->is_group_member($group_id, get_user_uid())){echo ' (you are a member)';};?>
                              </td>
                            </tr>
                            <tr>
                              <th>
                                Institution:
                              </th>
                              <td>
                              <?php echo $schoolController->get_school_name_by_id($group_school_id);?>
                              </td>
                            </tr>
                          </table>
                          <div class="text-center pad-up-20">
                              <img src="../res/imgs/1.jpg" class="img-responsive img-thumbnail">
                          </div>
                          <div class="pad-bottom-50 pad-up-20">
                            <h4>Description:</h4>
                              <h5>
                                  <p style="text-align: justify;">
                                      <?php echo $group_desc; ?>
                                  </p>
                              </h5>
                          </div>
                        </div>
                        <div id="details" class="tab-pane fade">
                          <h3>Admin Contacts</h3>
                          <ul class="list-group">
                            <?php if($groupController->group_show_phone($group_id)){?>
                            <li class="list-group-item"> <span class="fa fa-phone"> 08145655380</span></li>
                          <?php } if($groupController->group_show_email($group_id)) {?>
                            <li class="list-group-item"> <span class="fa fa-envelope"> </span> solomoneseme@gmail.com</li>
                          <?php }?>
                          </ul>
                          <h4>Send Personal Message to Group Admin</h4>
                          <div>
                            <div class="form-group">
                              <label for="subject">Subject:</label>
                              <input required id="subject" class="form-control" type="text" />
                            </div>

                            <div class="form-group">
                              <label for="body">Message:</label>
                              <textarea required id="message" rows="5" class="form-control" type="text"></textarea>
                            </div>
                            <div class="form-group">
                              <button id="send_group_admin_message" uid="<?php echo $group_user_id;?>" class="btn btn-danger" type="text" value="">Send Message</button>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  <?php
                  }
                }
                ?>
            <div class="col-md-4">
                <div class="col-sm-12">
                    <?php require '../include/tabs.php'; ?>
                </div>
                <div class="col-sm-12">
                    <div class="pad-bottom-20">
                        <img src="../res/imgs/8722.gif" class="img-responsive">
                    </div>
                    <div class="pad-bottom-20">
                        <img src="../res/imgs/p.gif" class="img-responsive">
                    </div>
                    <div class="pad-bottom-20">
                        <img src="../res/imgs/m.png" class="img-responsive">
                    </div>
                    <div class="pad-bottom-20">
                        <img src="../res/imgs/m.png" class="img-responsive">
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- </section> -->
<?php include 'footer.php'; ?>
<script>
    $(document).ready(function () {
        $('#reply').click(function (e) {
            e.prgroupDefault();
            var pid = $(this).attr('pid');
            alert(pid);
            // $.ajax({
            //   method:"group",
            //   url:"read-news.php"
            //   data:{pid:pid},
            //   success: function(da) {
            //       alert(da);
            //   };
            // });
        });

        $('#reminder').click(function(e) {
          e.prgroupDefault();
          var group_id = $(this).attr('pid');
          $.ajax({
            method: "POST",
            url: "set_reminder.php",
            data:{set_reminder:1, group_id:group_id},
            success: function(data) {
              if(data == 'TRUE'){
              $('#reminder').attr('disabled','disabled');
            }else if (data == 'FALSE') {
              //$(this).html('Reminder Set');
            }else{
              alert(data);
            }
          }
          });
        });

    });
</script>

<?php
function uploadFiles(){
  $error_status = 1;
  $target_dir = "../res/imgs/group/";

  for($i = 0; count($_FILES['file']['name']); $i++){
    echo "string";
  $image_to_upload = $_FILES['file'];
  $image_name = $image_to_upload['name'][$i];
  $target_file = $target_dir . basename($image_to_upload['name'][$i]);
  $image_file_type = pathinfo($target_file, PATHINFO_EXTENSION);
if(!empty($target_file)){
    if(getimagesize($image_to_upload['tmp_name'][$i]) !== FALSE){
      $error_status = 1;
    }else{
      $error_status = 0;
    }

    if(file_exists($target_file)){
      $error_status = 0;
    }else{
      $error_status = 1;
    }

    if($image_to_upload['size'] > 10485760){
      $error_status = 0;
    }

    if($image_file_type != "jpg" && $image_file_type != "png" && $image_file_type != "jpeg" && $image_file_type != "gif"){
      $error_status = 0;
    }else{
      $error_status = 1;
    }

    if($error_status == 1){
      if(move_uploaded_file($image_to_upload['tmp_name'][$i], $target_file)){
        return dirname($target_file)."/".basename($image_to_upload['name'][$i]);
      }
    }
  }else {
    return 'none';
  }
}
}
