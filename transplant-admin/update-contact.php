<?php
session_start();
error_reporting(0);
include('includes/config.php');
if (strlen($_SESSION['tlogin']) == 0) {
    header('location:tindex.php');
} else {
// Code for change password	
    if (isset($_POST['submit'])) {
        $address = $_POST['address'];
        $email = $_POST['email'];
        $contactno = $_POST['contactno'];
        $sql = "update tablecontactusinfo set Address=:address,Email=:email,ContactNo=:contactno";
        $query = $dbh->prepare($sql);
        $query->bindParam(':address', $address, PDO::PARAM_STR);
        $query->bindParam(':email', $email, PDO::PARAM_STR);
        $query->bindParam(':contactno', $contactno, PDO::PARAM_STR);
        if ($query->execute()) {
            $msg = " Contact information of Transplant Center updated successfully.";
        } else {
            $error = " Sorry! Try again.";
        }
    }
    ?>

    <!doctype html>
    <html lang="en">

        <head>
            <meta charset="utf-8">
            <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
            <meta name="description" content="">
            <meta name="author" content="">

            <title>Admin | Update Contact Info</title>

            <link rel="stylesheet" href="css/font-awesome.min.css"> <!-- Bootstrap Icons -->
            <link rel="stylesheet" href="css/bootstrap.min.css"> <!-- Main Bootstrap -->
            <link rel="stylesheet" href="css/style.css"> <!-- Header Stye -->
            <style>
                .errorWrap {
                    padding: 10px;
                    margin: 0 0 20px 0;
                    background: #fff;
                    border-left: 4px solid #dd3d36;
                    -webkit-box-shadow: 0 1px 1px 0 rgba(0,0,0,.1);
                    box-shadow: 0 1px 1px 0 rgba(0,0,0,.1);
                }
                .succWrap{
                    padding: 10px;
                    margin: 0 0 20px 0;
                    background: #fff;
                    border-left: 4px solid #5cb85c;
                    -webkit-box-shadow: 0 1px 1px 0 rgba(0,0,0,.1);
                    box-shadow: 0 1px 1px 0 rgba(0,0,0,.1);
                }
            </style>
        </head>
        <body>
            <?php include('includes/header.php'); ?>
            <div class="ts-main-content">
                <?php include('includes/leftbar.php'); ?>
                <div class="content-wrapper">
                    <div class="container-fluid">

                        <div class="row">
                            <div class="col-md-12">

                                <h2 class="page-title"><font face="Comic Sans MS" color="red">Update Contact Information</font></h2>

                                <div class="row">
                                    <div class="col-md-10">
                                        <div class="panel panel-default">
                                            <div class="panel-heading" style="color: green;">Form fields</div>
                                            <div class="panel-body">
                                                <form method="post"  class="form-horizontal">


                                                    <?php if ($error) { ?><div class="errorWrap"><strong>ERROR</strong>:<?php echo htmlentities($error); ?> </div><?php } else if ($msg) {
                                                        ?><div class="succWrap"><strong>SUCCESS</strong>:<?php echo htmlentities($msg); ?> </div><?php } ?>
                                                    <?php
                                                    $sql = "SELECT * from  tablecontactusinfo ";
                                                    $query = $dbh->prepare($sql);
                                                    $query->execute();
                                                    $results = $query->fetchAll(PDO::FETCH_OBJ);
                                                    $cnt = 1;
                                                    if ($query->rowCount() > 0) {
                                                        foreach ($results as $result) {
                                                            ?>	

                                                            <div class="form-group">
                                                                <label class="col-sm-4 control-label">Address</label>
                                                                <div class="col-sm-8">
                                                                    <textarea class="form-control" name="address" id="address" required><?php echo htmlentities($result->Address); ?></textarea>
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <label class="col-sm-4 control-label">Email</label>
                                                                <div class="col-sm-8">
                                                                    <input type="email" class="form-control" name="email" id="email" value="<?php echo htmlentities($result->Email); ?>" required>
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <label class="col-sm-4 control-label">Contact Number</label>
                                                                <div class="col-sm-8">
                                                                    <input type="text"  pattern="[0-9]{10}" maxlength="10" class="form-control" title="Remove any character/spaces/negative numbers. Enter numbers only, e.g 0325553693." value="<?php echo htmlentities($result->ContactNo); ?>" name="contactno" id="contactno" required>

                                                                </div>
                                                            </div>
                                                        <?php
                                                        }
                                                    }
                                                    ?>
                                                    <div class="hr-dashed"></div>
                                                    <div class="form-group">
                                                        <div class="col-sm-8 col-sm-offset-4">

                                                            <button class="btn btn-primary btn-lg" name="submit" type="submit"><font face="Trebuchet MS" style="font-size:14px;"><b>Update</b>&nbsp;<span class="glyphicon glyphicon-ok"></span></font></button>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Loading Scripts -->
            <script src="js/jquery.min.js"></script>
            <script src="js/bootstrap.min.js"></script>
            <script src="js/main.js"></script>

        </body>
    </html>
    <?php
} 