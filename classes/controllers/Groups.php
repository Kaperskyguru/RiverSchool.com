<?php

class Groups extends Logger
{

    private static $instance;

    private function __construct()
    {

    }

    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function add_group(GroupModel $groupModel)
    {
        try {
            $group_title = $groupModel->get_group_title();
            $group_desc = $groupModel->get_group_desc();
            $group_status_id = $groupModel->get_group_status_id();
            $group_school_id = $groupModel->get_group_school_id();
            $group_user_id = $groupModel->get_group_user_id();
            $showEmail = $groupModel->get_show_email();
            $showPhone = $groupModel->get_show_phone();
            $group_type = $groupModel->get_group_type();

            $query = "INSERT INTO groups(group_title,group_type, group_desc,group_status_id,group_school_id,group_user_id, group_show_email, group_show_phone)"
                . "VALUES(:group_title, :group_type,:group_desc,:group_status_id,:group_school_id,:group_user_id, :group_show_email, :group_show_phone)";
            $this->query($query);

            $this->bind(":group_title", $group_title);
            $this->bind(":group_type", $group_type);
            $this->bind(":group_desc", $group_desc);
            $this->bind(":group_status_id", $group_status_id);
            $this->bind(":group_school_id", $group_school_id);
            $this->bind(":group_user_id", $group_user_id);
            $this->bind(":group_show_email", $showEmail);
            $this->bind(":group_show_phone", $showPhone);
            $this->executer();
            $group_id = $this->lastIdinsert();
            if ($group_id !== 0) {
                if ($this->join_group($group_id, $group_user_id)) {
                    return $group_id;
                }
            } else {
                return 0;
            }
        } catch (PDOException $e) {
            $_SESSION['error'] = $e->getMessage();
            $this->logError($e->getMessage() . ' ==>' . __CLASS__ . '=>' . __FUNCTION__, get_user_uid());
            return 0;
        }
    }

    public function join_group($group_id, $user_id)
    {
        try {
            $query = "INSERT INTO group_memberships(membership_group_id, membership_user_id, membership_date_joined)
    VALUES(:membership_group_id, :membership_user_id, NOW())";
            $this->query($query);
            $this->bind(':membership_group_id', $group_id);
            $this->bind(':membership_user_id', $user_id);
            $this->executer();
            if ($id = $this->lastIdinsert()) {
                return TRUE;
            }
        } catch (PDOException $e) {
            $_SESSION['error'] = $e->getMessage();
            $this->logError($e->getMessage() . ' ==>' . __CLASS__ . '=>' . __FUNCTION__, get_user_uid());
            return FALSE;
        }
    }

    public function insert_group_featured_image_id($image_id, $group_id)
    {
        try {
            $sql = "UPDATE groups SET group_profile_image_id = :group_profile_image_id WHERE group_id = :group_id";
            $this->query($sql);
            $this->bind(':group_profile_image_id', $image_id);
            $this->bind(':group_id', $group_id);
            if ($this->executer()) {
                return TRUE;
            }
        } catch (PDOException $e) {
            $_SESSION['error'] = $e->getMessage();
            $this->logError($e->getMessage() . ' ==>' . __CLASS__ . '=>' . __FUNCTION__, get_user_uid());
            return FALSE;
        }
    }

    public function get_search_groups($term)
    {
        try {
            $query = "SELECT group_id, group_title, group_desc FROM groups WHERE group_desc LIKE '%$term%' OR group_title LIKE '%$term%'";
            $this->query($query);
            $stmt = $this->executer();
            return $stmt;
        } catch (PDOException $e) {
            $_SESSION['error'] = $e->getMessage();
            $this->logError($e->getMessage() . ' ==>' . __CLASS__ . '=>' . __FUNCTION__, get_user_uid());
            return null;
        }
    }

    public function create_group_meta(GroupModel $groupModel, $id)
    {
        try {
            $showEmail = $groupModel->get_show_email();
            $showPhone = $groupModel->get_show_phone();
            $query = "INSERT INTO groups(group_title,group_address,group_desc,group_status_id,group_school_id,group_user_id)"
                . "VALUES(:group_title,:group_address,:group_desc,:group_status_id,:group_school_id,:group_user_id)";
            $this->query($query);
        } catch (PDOException $e) {
            $_SESSION['error'] = $e->getMessage();
            $this->logError($e->getMessage() . ' ==>' . __CLASS__ . '=>' . __FUNCTION__, get_user_uid());
        }
    }

    public function get_group_by_id($id)
    {
        try {
            $query = "SELECT * FROM groups WHERE group_status_id = 5 AND group_status_id != 6  AND group_id = $id";
            $this->query($query);
            $row = $this->resultset();
            return $row;
        } catch (PDOException $e) {
            $_SESSION['error'] = $e->getMessage();
            $this->logError($e->getMessage() . ' ==>' . __CLASS__ . '=>' . __FUNCTION__, get_user_uid());
            return null;
        }
    }

    public function get_group_model_by_id($id)
    {
        try {
            $query = "SELECT status_body FROM statuses WHERE status_id = $id";
            $this->query($query);
            $row = $this->resultset();
            extract($row);
            return $status_body;
        } catch (PDOException $e) {
            $_SESSION['error'] = $e->getMessage();
            $this->logError($e->getMessage() . ' ==>' . __CLASS__ . '=>' . __FUNCTION__, get_user_uid());
            return null;
        }
    }

    public function get_group_status_by_id($id)
    {
        try {
            $query = "SELECT status_body FROM statuses WHERE status_id = $id";
            $this->query($query);
            $row = $this->resultset();
            extract($row);
            return $status_body;
        } catch (PDOException $e) {
            $_SESSION['error'] = $e->getMessage();
            $this->logError($e->getMessage() . ' ==>' . __CLASS__ . '=>' . __FUNCTION__, get_user_uid());
            return null;
        }
    }

    public function group_show_email($group_id)
    {
        try {
            $sql = "SELECT group_show_email FROM groups WHERE group_id = :group_id";
            $this->query($sql);
            $this->bind(':group_id', $group_id);
            $row = $this->resultset();
            extract($row);
            if ($group_show_email == 1) {
                return TRUE;
            } else {
                return FALSE;
            }
        } catch (PDOException $e) {
            $_SESSION['error'] = $e->getMessage();
            $this->logError($e->getMessage() . ' ==>' . __CLASS__ . '=>' . __FUNCTION__, get_user_uid());
            return FALSE;
        }
    }

    public function trash_group($group_id)
    {
        try {
            $query = "UPDATE groups SET group_status_id = 6 WHERE group_id = :group_id";
            $this->query($query);
            $this->bind(':group_id', $group_id);
            $this->executer();
            if ($this->lastIdinsert()) {
                return TRUE;
            }
        } catch (PDOException $e) {
            $_SESSION['error'] = $e->getMessage();
            $this->logError($e->getMessage() . ' ==>' . __CLASS__ . '=>' . __FUNCTION__, get_user_uid());
            return FALSE;
        }
    }

    public function update_member_count($group_id, $increment)
    {
        $count = $this->get_member_count($group_id);
        try {
            if ($increment) {
                $count = $count + 1;
                $query = "UPDATE groups SET group_member_count = :group_member_count WHERE group_id = :group_id";
                $this->query($query);
                $this->bind(':group_member_count', $count);
                $this->bind(':group_id', $group_id);
                $this->executer();
            } else {
                $count = $count - 1;
                $query = "UPDATE groups SET group_member_count = :group_member_count WHERE group_id = :group_id";
                $this->query($query);
                $this->bind(':group_member_count', $count);
                $this->bind(':group_id', $group_id);
                $this->executer();
            }
            if ($this->lastIdinsert()) {
                return TRUE;
            } else {
                return FALSE;
            }
        } catch (PDOException $e) {
            $_SESSION['error'] = $e->getMessage();
            $this->logError($e->getMessage() . ' ==>' . __CLASS__ . '=>' . __FUNCTION__, get_user_uid());
            return FALSE;
        }
    }

    public function get_member_count($id)
    {
        try {
            $query = 'SELECT group_member_count FROM groups WHERE group_id = :id';
            $this->query($query);
            $this->bind(':id', $id);
            $row = $this->resultset();
            extract($row);
            return $group_member_count;
        } catch (PDOException $e) {
            $_SESSION['error'] = $e->getMessage();
            $this->logError($e->getMessage() . ' ==>' . __CLASS__ . '=>' . __FUNCTION__, get_user_uid());
            return FALSE;
        }
    }

    public function group_show_phone($group_id)
    {
        try {
            $sql = "SELECT group_show_phone FROM groups WHERE group_id = :group_id";
            $this->query($sql);
            $this->bind(':group_id', $group_id);
            $row = $this->resultset();
            extract($row);
            if ($group_show_phone == 1) {
                return TRUE;
            } else {
                return FALSE;
            }
        } catch (PDOException $e) {
            $_SESSION['error'] = $e->getMessage();
            $this->logError($e->getMessage() . ' ==>' . __CLASS__ . '=>' . __FUNCTION__, get_user_uid());
            return FALSE;
        }
    }

    public function is_group_admin($group_id, $user_id)
    {
        try {
            $sql = "SELECT group_id FROM groups WHERE group_user_id = :user_id AND group_id = :group_id";
            $this->query($sql);
            $this->bind(':user_id', $user_id);
            $this->bind(':group_id', $group_id);
            $row = $this->executer();
            if ($row->rowCount() > 0) {
                return TRUE;
            } else {
                return FALSE;
            }
        } catch (PDOException $e) {
            $_SESSION['error'] = $e->getMessage();
            $this->logError($e->getMessage() . ' ==>' . __CLASS__ . '=>' . __FUNCTION__, get_user_uid());
            return FALSE;
        }
    }

    public function leave_group($group_id, $user_id)
    {
        try {
            $sql = "DELETE FROM group_memberships WHERE membership_group_id = :group_id AND membership_user_id = :user_id";
            $this->query($sql);
            $this->bind(':group_id', $group_id);
            $this->bind(':user_id', $user_id);
            $stmt = $this->executer();
            if ($stmt) {
                return TRUE;
            } else {
                return FALSE;
            }
        } catch (PDOException $e) {
            $_SESSION['error'] = $e->getMessage();
            $this->logError($e->getMessage() . ' ==>' . __CLASS__ . '=>' . __FUNCTION__, get_user_uid());
            return FALSE;
        }
    }

    public function add_group_discussions($body, $user_id, $group_id){
        try{
            $query = "INSERT INTO group_discussions(discussion_body, discussion_user_id, discussion_group_id)VALUES(:discussion_body, :user_id, :group_id)";
            $this->query($query);
            $this->bind(':discussion_body', $body);
            $this->bind(':user_id', $user_id);
            $this->bind(':group_id', $group_id);
            $this->executer();
            return $this->lastIdinsert();
        }catch(Error $e){
            $this->logError($e->getMessage() . ' ==>' . __CLASS__ . '=>' . __FUNCTION__, get_user_uid());
            return 0;
        }
    }
    public function get_group_discussions_by_group_id($discussion_id)
    {
        try {
            $query = "SELECT * FROM group_discussions WHERE discussion_group_id = :discussion_id";
            $this->query($query);
            $this->bind(':discussion_id', $discussion_id);
            $stmt = $this->executer();
            return $stmt;
        } catch (PDOException $e) {
            $_SESSION['error'] = $e->getMessage();
            $this->logError($e->getMessage() . ' ==>' . __CLASS__ . '=>' . __FUNCTION__, get_user_uid());
            return FALSE;
        }
    }

    public function get_group_members($group_id)
    {
        try {
            $query = "SELECT * FROM group_memberships WHERE membership_group_id = :group_id";
            $this->query($query);
            $this->bind('group_id', $group_id);
            $stmt = $this->executer();
            return $stmt;
        } catch (PDOException $e) {
            $_SESSION['error'] = $e->getMessage();
            $this->logError($e->getMessage() . ' ==>' . __CLASS__ . '=>' . __FUNCTION__, get_user_uid());
            return null;
        }
    }

    public function get_group_title_by_id($id)
    {
        try {
            $query = "SELECT group_title FROM groups WHERE group_id = $id";
            $this->query($query);
            $row = $this->resultset();
            return $row['group_title'];
        } catch (PDOException $e) {
            $_SESSION['error'] = $e->getMessage();
            $this->logError($e->getMessage() . ' ==>' . __CLASS__ . '=>' . __FUNCTION__, get_user_uid());
            return null;
        }
    }

    public function get_group_school_by_id($id)
    {
        try {
            $query = "SELECT school_abbr FROM schools WHERE school_id = $id";
            $this->query($query);
            $row = $this->resultset();
            extract($row);
            return $school_abbr;
        } catch (PDOException $e) {
            $_SESSION['error'] = $e->getMessage();
            $this->logError($e->getMessage() . ' ==>' . __CLASS__ . '=>' . __FUNCTION__, get_user_uid());
            return null;
        }
    }

    public function get_group_review_count_by_id($id)
    {
        try {
            $query = "SELECT group_review_count FROM groups WHERE group_id = $id";
            $this->query($query);
            $row = $this->resultset();
            extract($row);
            return $group_review_count;
        } catch (PDOException $e) {
            $_SESSION['error'] = $e->getMessage();
            $this->logError($e->getMessage() . ' ==>' . __CLASS__ . '=>' . __FUNCTION__, get_user_uid());
            return null;
        }
    }

    public function get_groups_by_user_id($user_id)
    {
        try {
            $query = "SELECT * FROM groups WHERE group_user_id = $user_id AND group_status_id != 6";
            $this->query($query);
            $stmt = $this->executer();
            return $stmt;
        } catch (PDOException $e) {
            $_SESSION['error'] = $e->getMessage();
            $this->logError($e->getMessage() . ' ==>' . __CLASS__ . '=>' . __FUNCTION__, get_user_uid());
            return null;
        }
    }

    public function get_group_membership_by_user_id($user_id)
    {
        try {
            $query = "SELECT * FROM group_memberships WHERE membership_user_id = $user_id";
            $this->query($query);
            $stmt = $this->executer();
            return $stmt;
        } catch (PDOException $e) {
            $_SESSION['error'] = $e->getMessage();
            $this->logError($e->getMessage() . ' ==>' . __CLASS__ . '=>' . __FUNCTION__, get_user_uid());
            return null;
        }
    }

    public function get_number_group_members_by_id($group_id)
    {
        try {
            $query = "SELECT membership_user_id FROM group_memberships WHERE membership_group_id = $group_id";
            $this->query($query);
            $stmt = $this->executer();
            return $stmt->rowCount();
        } catch (PDOException $e) {
            $_SESSION['error'] = $e->getMessage();
            $this->logError($e->getMessage() . ' ==>' . __CLASS__ . '=>' . __FUNCTION__, get_user_uid());
            return null;
        }
    }

    public function display_availabe_groups($startpoint, $limit, $res)
    {
        try {
            $stmt = $this->get_groups($startpoint, $limit);
            if ($stmt->rowCount() > 0) {
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    extract($row);
                    ?>
                    <div class="col-sm-3 pad-bottom-20">
                        <div>
                            <?php $res::display($res->get_image_url($group_id, 'group'), array_merge($res::SAMPLE_IMAGE_OPTIONS, array("crop" => "fill"))); ?>
                        </div>
                        <div>
                            <h4><?php echo $group_title; ?></h4>
                            <?php if ($this->is_group_member($group_id, get_user_uid())) { ?>
                                <a href="<?php echo SITEURL; ?>/groups/<?php echo $group_id; ?>"
                                   class="btn btn-primary">Goto Group</a>
                            <?php } else { ?>
                                <a href="<?php echo SITEURL; ?>/groups/<?php echo $group_id; ?>"
                                   class="btn btn-primary">Join Group</a>
                            <?php } ?>
                        </div>
                    </div>
                    <?php
                }
            }
        } catch (PDOException $e) {
            $_SESSION['error'] = $e->getMessage();
            $this->logError($e->getMessage() . ' ==>' . __CLASS__ . '=>' . __FUNCTION__, get_user_uid());
        }
    }

    public function get_groups($startpoint, $limit)
    {
        try {
            $query = "SELECT * FROM groups WHERE group_status_id = 5 AND group_status_id != 6 LIMIT $startpoint, $limit";
            $this->query($query);
            $stmt = $this->executer();
            return $stmt;
        } catch (PDOException $e) {
            $_SESSION['error'] = $e->getMessage();
            $this->logError($e->getMessage() . ' ==>' . __CLASS__ . '=>' . __FUNCTION__, get_user_uid());
            return null;
        }
    }

    public function is_group_member($group_id, $user_id)
    {
        try {
            $sql = "SELECT membership_id FROM group_memberships WHERE membership_user_id = :user_id AND membership_group_id = :group_id";
            $this->query($sql);
            $this->bind(':user_id', $user_id);
            $this->bind(':group_id', $group_id);
            $row = $this->executer();
            if ($row->rowCount() == 0) {
                return FALSE;
            } else {
                return TRUE;
            }
        } catch (PDOException $e) {
            $_SESSION['error'] = $e->getMessage();
            $this->logError($e->getMessage() . ' ==>' . __CLASS__ . '=>' . __FUNCTION__, get_user_uid());
            return FALSE;
        }
    }

    public function display_search_groups($length = 0, $res, $group_school_id)
    {
        try {
            $stmt = $this->get_groups_by_school_id($group_school_id);
            if ($stmt->rowCount() > 0) {
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    extract($row);
                    ?>
                    <div class="col-sm-3 pad-bottom-20">
                        <div>
                            <?php $res::display($res->get_image_url($group_id, 'group'), array_merge($res::SAMPLE_IMAGE_OPTIONS, array("crop" => "fill"))); ?>
                        </div>
                        <div>
                            <h4><?php echo $group_title; ?></h4>
                            <?php if ($this->is_group_member($group_id, get_user_uid())) { ?>
                                <a href="<?php echo SITEURL; ?>/groups/<?php echo $group_id; ?>"
                                   class="btn btn-primary">Goto Group</a>
                            <?php } else { ?>
                                <a href="<?php echo SITEURL; ?>/groups/<?php echo $group_id; ?>"
                                   class="btn btn-primary">Join Group</a>
                            <?php } ?>
                        </div>
                    </div>
                    <?php
                }
            } else {
                echo '<div> <h2> No Record was found in our database </h2></div>';
            }
        } catch (PDOException $e) {
            $_SESSION['error'] = $e->getMessage();
            $this->logError($e->getMessage() . ' ==>' . __CLASS__ . '=>' . __FUNCTION__, get_user_uid());
        }
    }

    public function get_groups_by_school_id($school_id)
    {
        try {
            $query = "SELECT * FROM groups WHERE group_status_id = 5 AND group_school_id = $school_id";
            $this->query($query);
            $stmt = $this->executer();
            return $stmt;
        } catch (PDOException $e) {
            $_SESSION['error'] = $e->getMessage();
            $this->logError($e->getMessage() . ' ==>' . __CLASS__ . '=>' . __FUNCTION__, get_user_uid());
            return null;
        }
    }

    public function display_group_discussios($group_id)
    {
        try {
            $stmt = $groupController->get_group_discussions_by_id($group_id);
            if ($stmt->rowCount() > 0) {
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    extract($row);
                    ?>
                    <div class="commentlist">
                    <ul>
                    <div class="comment-wrap">
                        <div class="comment-avatar">
                            <!-- <img alt="" src="../res/imgs/1.jpg" class="" height="45" width="45"> -->
                        </div>
                        <div class="author-comment">
                            <cite
                                class="fn"><?php echo $userControler->get_user_username_by_id($discussion_user_id); ?></cite>

                            <div class="">
                                <a href="">    <?php echo time_ago($discussion_date); ?></a>
                            </div>
                        </div>
                        <div class="clear"></div>
                        <div class="comment-content">
                            <p><?php echo $discussion_body ?>;</p>
                        </div>
                        <div class="like">
                            <a rel="nofollow" class="comment-reply-link"
                               href="http://localhost/kaperskyguru/testing-out-the-newsletter/?replytocom=5#respond"
                               aria-label="Reply to Kap man"><span class="fa fa-thumbs-up"></span> Like <span
                                    class="badge">0</span></a>
                        </div>
                        <div class="dislike">
                            <a rel="nofollow" class="comment-reply-link" href="#" aria-label="Reply to Kap man"><span
                                    class="fa fa-thumbs-down"></span> Dislike <span class="badge badge-danger">0</span></a>
                        </div>
                        <div class="reply">
                            <a rel="nofollow" class="comment-reply-link" pid="<?php echo $discussion_id ?>" href="#"
                               id="reply" aria-label="Reply to Kap man"><span class="fa fa-reply"></span> Reply</a>
                        </div>
                    </div>
                    <?php
                }
            }
        } catch (PDOException $e) {
            $_SESSION['error'] = $e->getMessage();
            $this->logError($e->getMessage() . ' ==>' . __CLASS__ . '=>' . __FUNCTION__, get_user_uid());
            return null;
        }
    }

    private function __clone()
    {

    }

}
        