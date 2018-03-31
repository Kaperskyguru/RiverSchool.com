<?php include 'dashboard-header.php' ?>
        <div id="page-wrapper">
            <div class="container-fluid">
                <!-- Page Heading -->
                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header">
                            Messages
                        </h1>
                        <ol class="breadcrumb">
                            <li>
                                <i class="fa fa-dashboard"></i>  <a href="index.html">Dashboard</a>
                            </li>
                            <li class="active">
                                <i class="fa fa-desktop"></i> compose Messages
                            </li>
                        </ol>
                    </div>
                </div>
                <!-- /.row -->
                <div class="row">
                    <div class="col-lg-12">
                        <h2>Compose Message</h2>
                    </div>
                </div>
                <div>
                 <form>
                   <div class="form-group">
                     <label for="receiver">Enter Receiver Name</label>
                     <input type="text" name="receiver" id="receiver" class="form-control">
                   </div>
                   <div class="form-group">
                     <label>Subject:</label>
                     <input name="subject" id="subject" class="form-control" type="text">
                   </div>
                   <div class="form-group">
                     <label>Message</label>
                     <textarea name="message" id="message" class="form-control" rows="5"></textarea>
                   </div>
                    <div class="form-group" style="padding-top: 20px;">
                        <button type="button" id="send_message" name="send_message" class="btn btn-lg btn-success">Send Message</button>
                        <button type="button" class="btn btn-lg btn-success">Save Draft</button>
                    </div>
                 </form>
              </div>
            </div>
        </div>
        <!-- /#page-wrapper -->
    </div>
    <!-- /#wrapper -->

    <!-- jQuery -->
    <script src="js/jquery.js"></script>
    <script src="js/script.js"></script>
    <!-- Bootstrap Core JavaScript -->
    <script src="js/bootstrap.min.js"></script>

</body>

</html>
