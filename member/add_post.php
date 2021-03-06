<?php
require 'dashboard-header.php';

$error = array();
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = get_user_uid();
    if (empty($_FILES['post_image']['name']) || empty($_FILES['post_image']['tmp_name']) || empty($_FILES['post_image'])) {
        $error_text = "Image is required";
    }
    if (empty($_POST['title'])) {
        $error_text = 'Please title is required';
    } else {
        $newsModel->set_post_title($_POST['title']);
    }
    if (empty($_POST['desc'])) {
        $error_text = 'Please Content is required';
    } else {
        $newsModel->set_post_content($_POST['desc']);
    }
    if (empty($_POST['cat'])) {
        $error_text = 'Please Category is required';
    } else {
        $newsModel->set_post_category_id($_POST['cat']);
    }
    if (empty($_POST['school'])) {
        $error_text = 'Please School is required';
    } else {
        $newsModel->set_post_school_id($_POST['school']);
    }
    $newsModel->set_post_user_id($id);
    $newsModel->set_post_status_id(2);
    if (!isset($error_text)) {
      $new_id = $newsControler->addNews($newsModel);
        if ($new_id != 0) {
            $image_id = uploadFiles($id, $new_id);
           if( $image_id != 0){
                if($newsControler->insert_post_featured_image_id($image_id, $new_id)){
                    // Lodge here


                    // Notify user
                    $message = 'You inserted a new post';
                    $notifier->add_notification(build_notification($notifyModel, get_user_uid(), 'New Post', $message));
                    $succes_text = "Your Post is pending verifications...";
                }
            }else{
                $error_text = "Your Post inserted without featured Image... ";
            }

        } else {
            $error_text = "We could not Post it ".$new_id;
        }
    }
}
?>
<div class="container marg-to-10">
    <div class="row">
        <div class="col-md-10 member_layout">
            <h2>Create a new post<hr /></h2>
            <?php display_error();

            if (!is_null($_GET['id'])) {
                $postId = $_GET['id'];
                $row = $newsControler->get_post_by_id($postId);
                extract($row); ?>

                <form role="form" action="<?php htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="POST" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="title">Title:</label>
                        <input class="form-control" value="<?php echo $post_title; ?>" id="title" name="title" type="text" />
                    </div>
                    <div class="form-group">
                        <label for="desc" > Description: </label>
                        <textarea rows ="10" id="desc" name="desc" class="form-control"><?php echo $post_content ?> </textarea>
                        
                    </div>
                    <div class="form-group">
                        <label for="post_image">Choose Featured image: </label>
                        <input type="file" id="post_image[]" name="post_image[]" class="form-control" />
                    </div>
                    <div class="form-group">
                        <label for="cat">Category:</label>
                        <select class="form-control" id="cat" name="cat">
                            <option value="<?php echo $post_category_id ?>"><?php echo $newsControler->get_category_name_by_id($post_category_id); ?> </option>
                            <?php $newsControler->get_post_category($post_category_id); ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="school">Select School</label>
                        <select class="form-control" id="school" name="school">
                            <option value="<?php echo $post_school_id ?>"><?php echo $schoolController->get_school_name_by_id($post_school_id); ?> </option>
                            <?php $schoolController->get_schools($post_school_id); ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <button class="btn btn-success" type="submit">Update</button>
                    </div>
                </form>




        <?php } else { ?>


            <form role="form" action="<?php htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="title">Title:</label>
                    <input class="form-control" id="title" name="title" type="text" />
                </div>
                <div class="form-group">
                    <label for="desc" > Description: </label>
                    <textarea rows ="10" id="post_desc" name="desc" class="form-control"> </textarea>
                    
                </div>
                <div class="form-group">
                    <label for="post_image">Choose Featured image: </label>
                    <input type="file" id="post_image[]" name="post_image[]" class="form-control" />
                </div>
                <div class="form-group">
                    <label for="cat">Category:</label>
                    <select class="form-control" id="cat" name="cat">
                        <option value="">Select Categories </option>
                        <?php $newsControler->get_post_category(); ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="school">Select School</label>
                    <select class="form-control" id="school" name="school">
                        <option value="0">All School </option>
                        <?php $schoolController->get_schools(); ?>
                    </select>
                </div>
                <div class="form-group">
                    <button class="btn btn-success" type="submit">Submit</button>
                </div>
            </form>


        <?php }?>




        </div>
    </div>
</div>

<?php

function uploadFiles($user_id, $inserted_id) {
    $files = $_FILES["post_image"];
    return Resources::upload_image($files, $user_id, $inserted_id, "posts");
}

require_once('footer.php');
