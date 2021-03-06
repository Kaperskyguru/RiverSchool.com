<?php
require_once '../init.php';
echo set_title('Available Roommates', 'Schooling in rivers state just got easier - '.get_site_name());
$userController->cookie_login();
if($userController->is_authenticated()){
    require_once '../include/member_header.php';
}else {
    require_once '../include/header.php';
}
$page = (int) (!isset($_GET["page"]) ? 1 : $_GET["page"]);
?>
<section id="roomate" class="marg-to-50 pad-up-50">
    <div class="container">
        <div class="row margin-btom-20">
            <div class="col-md-9 group_layout">
                <h2>Available Roommates</h2>
                <div class="row" style="padding-top: 20px; margin: 10px;background-color:#eee">
                    <div class="col-md-10">
                        <div class="form-group row">
                            <div class="col-md-4">
                                <label>School:</label>
                                <select id="roommate_school" class="form-control">
                                    <option disabled="true" selected="">Select a school from here to get latest information</option>
                                    <?php $schoolController->get_schools(); ?>
                                </select>
                            </div>
                            <div class="col-md-8">
                                <div class="col-md-6">
                                    <label>Gender:</label>
                                    <select id="roommate_gender" class="form-control" id="roommate_gender">
                                        <option value= 0 selected="">Select gender information</option>
                                        <option value= 1>Male</option>
                                        <option value= 2 >Female</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label>Type:</label>
                                    <select id="roommate_type" class="form-control">
                                        <option value= 0 selected="">Select Roommate Type</option>
                                        <option value = 1>I Have A Room</option>
                                        <option value= 2>I Dont Have A Room</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="">
                                <button id="roommate_search_submit" type="submit" class="fa fa-search btn-lg btn-block btn-success"> Search</button>                        </div>
                            </div>

                        </div>
                    </div>
                    <div class="pad-up-20">
                        <div id="roommate_content">
                            <?php $limit = 12; //if you want to dispaly 10 records per page then you have to change here
                            $startpoint = ($page * $limit) - $limit;
                            $roommateController->display_availabe_roommates($startpoint, $limit, $resources); ?>
                        </div>
                    </div>
                    <!--  Pagination starts here -->
                    <div class="margin-btom-20 pagination">
                        <ul class="pagination">
                            <?php echo $paging->pagination("roommates", $limit, $page);?>
                        </ul>
                    </div>
                </div>

                <!-- <div class="col-md-3 pad-up-20">
                    <div class="container-fluid">
                    <div class="row">
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
                            <img src="../res/imgs/s.png" class="img-responsive">
                        </div>
                    </div>
                    </div>
                </div> -->
            </div>
        </div>
    </section>
    <?php
    require_once '../include/footer.php';
