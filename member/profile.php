<?php
include 'dashboard-header.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['profile_image'])) {
    $user_id = get_user_uid();
    if (empty($_FILES['profile_image']['name']) || empty($_FILES['profile_image']['tmp_name']) || empty($_FILES['profile_image'])) {
        $error_text = "Image is required";
        //$newsModel->set_post_featured_image_id(NULL);
    } else {
        $image_id = uploadFiles($user_id, 0, $resources);
        if ($image_id == 0) {
            $error_text = "Not Uploaded";
        } else {
            $userController->insert_user_profile_id($image_id, $user_id);
            $success_text = "Profile Picture Changed Successfully";
        }
    }
}
?>
<style>
    h3{
        color: grey;
    }
    #error{
        font-size: 13px;
    }
</style>
<div id="page">
    <div class="container-fluid">
        <!-- Page Heading -->
        <div class="row">
            <div class="col-lg-12">
                <h2 style="color:red" class="page-header">
                    Hello, <?php echo $userController->get_user_username_by_id(get_user_uid()); ?>
                    <?php display_error(); ?>
                </h2>
            </div>
        </div>
            <div class="margin-btom-20">
                <ul class="nav nav-tabs nav-justified">
                    <li class="active"><a data-toggle="tab" href="#UpdateProfile">Update Profile</a></li>
                    <li><a data-toggle="tab" href="#Changepassword">Change password</a></li>
                    <li><a data-toggle="tab" href="#Bankinformation">Bank information</a></li>
                    <li><a data-toggle="tab" href="#profilepicture">Profile Picture</a></li>
                </ul>
                <div class="tab-content">
                    <div id="UpdateProfile" class="tab-pane fade in active">
                        <h3>Personal Information</h3>
                        <div class="form-group row">
                            <div class="col-xs-6">
                                <label for="first_name">First Name: </label>
                                <input id="first_name" name="first_name" value="<?php echo $userController->get_user_detail_by_column_name('user_name', get_user_uid()); ?>" type="text" class="form-control" />
                            </div>
                            <div class="col-xs-6">
                                <label for="last_name"> Last Name: </label>
                                <input id="last_name" name="last_name" type="text" class="form-control" />
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-xs-6">
                                <label for="display_name">Display Name: </label>
                                <input id="display_name" value="<?php echo $userController->get_user_detail_by_column_name('user_display_name', get_user_uid()); ?>" name="display_name" type="text" class="form-control" />
                            </div>
                            <div class="col-xs-6">
                                <label for="phone_number">Phone Number: </label>
                                <input id="phone_number" value="<?php echo $userController->get_user_detail_by_column_name('user_phone_number', get_user_uid()); ?>" name="phone_number" type="number" class="form-control" />
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-xs-6">
                                <label for="gender">Gender: </label>
                                <select id="gender" value="<?php echo $userController->get_user_detail_by_column_name('user_gender', get_user_uid()); ?>" name="gender" class="form-control">
                                    <option value="0" disabled>select your gender</option>
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                </select>
                            </div>
                            <div class="col-xs-6">
                                <label for="birthday">Birthday: </label>
                                <input id="birthday" value="<?php echo $userController->get_user_detail_by_column_name('user_birthday', get_user_uid()); ?>" name="birthday" type="date" class="form-control" />
                            </div>
                        </div>

                        <h3> Login Information</h3>
                        <div class="form-group row">
                            <div class="col-xs-6">
                                <label for="email_address">Email Address: </label>
                                <input id="email_address" value="<?php echo $userController->get_user_detail_by_column_name('user_email', get_user_uid()); ?>" name="email_address" type="email" class="form-control" />
                            </div>
                            <div class="col-xs-6">
                                <label for="user_name">User Name: </label>
                                <input id="user_name" value="<?php echo $userController->get_user_detail_by_column_name('user_user_name', get_user_uid()); ?>" name="user_name" type="text" class="form-control" />
                            </div>
                        </div>

                    <h3>School Information</h3>
                        <div class="form-group row">
                            <div class="col-xs-6">
                                <label for="school">School: </label>
                                <select id="school" value="<?php echo $userController->get_user_detail_by_column_name('user_school_id', get_user_uid()); ?>" name="school" class="form-control">
                                    <?php $schoolController->get_schools() ?>
                                </select>
                            </div>

                            <div class="col-xs-6">
                                <label for="level">Level: </label>
                                <select id="level" value="<?php echo $userController->get_user_detail_by_column_name('user_level', get_user_uid()); ?>" name="level" class="form-control">
                                    <?php $schoolController->get_schools() ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="course_of_study">Course of Study: </label>
                            <input id="course_of_study" value="<?php echo $userController->get_user_detail_by_column_name('user_course_of_study', get_user_uid()); ?>" name="course_of_study" type="text" class="form-control" />
                        </div>
                        <h3>About Me</h3>
                        <div class="form-group">
                            <label for="address">Address: </label>
                            <textarea id="address" placeholder="<?php echo $userController->get_user_detail_by_column_name('user_address', get_user_uid()); ?>" name="address" rows="3" class="form-control"></textarea>
                        </div>

                        <div class="form-group">
                            <label id="about_you">About You: </label>
                            <textarea id="about_you" placeholder="<?php echo $userController->get_user_detail_by_column_name('user_about', get_user_uid()); ?>" name="about_you" rows="3" class="form-control"></textarea>
                        </div>
                        <div class="form-group">
                            <button class="text-center btn btn-danger btn-lg" id="submit_personal_data" type="submit">Update Profile</button>
                        </div>
                    </div>
                    <div id="Changepassword" class="tab-pane fade">
                        <h3>Change Password</h3>
                        <div class="container-fluid">
                            <div class="col-md-6" id="d">
                                <div class="form-horizontal">
                                    <div class="form-group">
                                        <label for="old_password" class="control-label col-sm-3"> Old Password: </label>
                                        <div class="col-sm-8">
                                            <input id="old_password" type="password" class="form-control" />
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="new_password" class="control-label col-sm-3"> New Password: </label>
                                        <div class="col-sm-8">
                                            <input id="new_password" type="password" class="form-control" />
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="confirm_password" class="control-label col-sm-3">Confirm Password: </label>
                                        <div class="col-sm-8">
                                            <input id="confirm_password" type="password" class="form-control" />
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="col-sm-6">
                                            <div class="checkbox">
                                                <label><input id="log_all_out" type="checkbox" checked> Log out of all accounts</label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <button id="change_password"  class="btn btn-primary" >Change Password</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="Bankinformation" class="tab-pane fade">
                        <h3>Bank Information</h3>
                        <div class="form-group">
                            <label for="bank_name">Bank:</label>
                            <input id="bank_name" value="<?php echo $userController->get_user_detail_by_column_name('user_bank_name', get_user_uid()); ?>" type="text" class="form-control" />
                        </div>
                        <div class="form-group">
                            <label for="account_name">Account Name:</label>
                            <input id="account_name" value="<?php echo $userController->get_user_detail_by_column_name('user_bank_account_name', get_user_uid()); ?>" type="text" class="form-control" />
                        </div>
                        <div class="form-group">
                            <label for="account_number">Account Number:</label>
                            <input id="account_number" value="<?php echo $userController->get_user_detail_by_column_name('user_bank_account_number', get_user_uid()); ?>" type="text" class="form-control" />
                        </div>
                        <div class="form-group">
                            <button id="change_bank_information" class="btn btn-danger">Submit</button>
                        </div>
                    </div>

                    <div id="profilepicture" class="tab-pane fade">
                        <h4>Profile Picture</h4>
                        <div class="row">
                            <div class="col-md-6">
                                <form role="form" action="<?php htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST" enctype="multipart/form-data">
                                    <div class="form-group">
                                        <input id="profile_image" name="profile_image[]" type="file" >
                                    </div>
                                    <br><br>
                                    <div class="form-group">
                                        <button id="img_submit" class="text-center btn btn-primary btn-lg" disabled type="submit">Upload Profile Photo</button>
                                    </div>
                                </form>
                             </div>
                            <div class="col-md-6">
                            <?php 
                            if ($resources->get_image_url($userController->get_user_Profile_id(get_user_uid()), 'profiles') != NULL) {
                                $resources::display("Rischoola/profiles/tn8YZk4247_C360_2015-03-30-16-37-19-188.jpg", array_merge($resources::DETAILS_IMAGE_OPTIONS, array("crop" => "fill")));                           
                            }else{
                                echo '<h2> No Profile Image </h2>';
                            }?> 
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
<?php

function uploadFiles($user_id, $inserted_id, $resources) {
    $files = $_FILES["profile_image"];
    return Image::upload_image($files, $user_id, $inserted_id, $resources, "profiles");
}
require_once('footer.php');
