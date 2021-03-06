<?php
session_start();
error_reporting(0);
include('includes/config.php');
if (strlen($_SESSION['tlogin']) == 0) {
    header('location:tindex.php');
} else {

    if (isset($_POST['submit'])) {
        $username = "Admin";
        $fullname = $_POST['fullname'];
        $passport = $_POST['pass'];
        $nationality = $_POST['nationality'];
        $mobile = $_POST['mobileno'];
        $email = $_POST['email'];
        $state = $_POST['state'];
        $gender = $_POST['gender'];
        $dob = $_POST['dob'];
        $bloodtype = $_POST['bloodtype'];
        $address = $_POST['address'];
        $organ1 = $_POST['organ1'];
        $organ2 = $_POST['organ2'];
        $organ3 = $_POST['organ3'];
        $organ4 = $_POST['organ4'];
        $organ5 = $_POST['organ5'];
        $status = 1;
        //insert image
        $name = $_FILES['myfile']['name']; //stores image name
        $type = $_FILES['myfile']['type']; //stores image type
        $data = file_get_contents($_FILES['myfile']['tmp_name']); //store image
        //store image
        $valid_extensions = array('jpeg', 'jpg', 'png');  //valid file types
        $ext = pathinfo($name, PATHINFO_EXTENSION);
        $imageSize = $_FILES['myfile']['size']; // image size 

        if (in_array($ext, $valid_extensions)) {
            if ($imageSize > 1000 && $imageSize <= 1000000) { // image size checker
                if (!empty($_POST['organ1']) || !empty($_POST['organ2']) || !empty($_POST['organ3']) || !empty($_POST['organ4']) || !empty($_POST['organ5'])) {
                    $ret = " SELECT Passport,Email FROM tableorgandonors  where  Passport=:pass || Email=:email";
                    $queryt = $dbh->prepare($ret);
                    $queryt->bindParam(':pass', $passport, PDO::PARAM_STR);
                    $queryt->bindParam(':email', $email, PDO::PARAM_STR);
                    $queryt->execute();
                    $results = $queryt->fetchAll(PDO::FETCH_OBJ);
                    if ($queryt->rowCount() == 0) {

                        $sql = "INSERT INTO  tableorgandonors(FullName,Passport,Nationality,MobileNumber,Email,State,Gender,DateOfBirth,BloodType,Address,OrganDonated,OrganDonated2,OrganDonated3,OrganDonated4,OrganDonated5,User,name,mime,data,status) VALUES(:fullname,:passport,:nationality,:mobile,:email,:state,:gender,:dob,:bloodtype,:address,:organ1,:organ2,:organ3,:organ4,:organ5,:username,:name,:type,:data,:status)";
                        $query = $dbh->prepare($sql);
                        $query->bindParam(':fullname', $fullname, PDO::PARAM_STR);
                        $query->bindParam(':passport', $passport, PDO::PARAM_STR);
                        $query->bindParam(':nationality', $nationality, PDO::PARAM_STR);
                        $query->bindParam(':mobile', $mobile, PDO::PARAM_STR);
                        $query->bindParam(':email', $email, PDO::PARAM_STR);
                        $query->bindParam(':state', $state, PDO::PARAM_STR);
                        $query->bindParam(':gender', $gender, PDO::PARAM_STR);
                        $query->bindParam(':dob', $dob, PDO::PARAM_STR);
                        $query->bindParam(':bloodtype', $bloodtype, PDO::PARAM_STR);
                        $query->bindParam(':address', $address, PDO::PARAM_STR);
                        $query->bindParam(':organ1', $organ1, PDO::PARAM_STR);
                        $query->bindParam(':organ2', $organ2, PDO::PARAM_STR);
                        $query->bindParam(':organ3', $organ3, PDO::PARAM_STR);
                        $query->bindParam(':organ4', $organ4, PDO::PARAM_STR);
                        $query->bindParam(':organ5', $organ5, PDO::PARAM_STR);
                        $query->bindParam(':username', $username, PDO::PARAM_STR);
                        $query->bindParam(':name', $name, PDO::PARAM_STR);
                        $query->bindParam(':type', $type, PDO::PARAM_STR);
                        $query->bindParam(':data', $data, PDO::PARAM_STR);
                        $query->bindParam(':status', $status, PDO::PARAM_STR);


                        $query->execute();
                        $lastInsertId = $dbh->lastInsertId();
                        if ($lastInsertId) {
                            require 'phpmailer/PHPMailerAutoload.php';
                            $mail = new PHPMailer;
//$mail->SMTPDebug = 4;                               // Enable verbose debug output
                            $mail->isSMTP();                                      // Set mailer to use SMTP
                            $mail->SMTPOptions = array(
                                'ssl' => array(
                                    'verify_peer' => false,
                                    'verify_peer_name' => false,
                                    'allow_self_signed' => true
                                )
                            );
                            $mail->Host = 'smtp.aol.com';  // Specify main and backup SMTP servers
                            $mail->SMTPAuth = true;                               // Enable SMTP authentication
                            $mail->Username = 'nadrex2009@aol.com';                 // SMTP username
                            $mail->Password = 'NkmS2013';                           // SMTP password
                            $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
                            $mail->Port = 587;                                    // TCP port to connect to

                            $mail->setFrom('nadrex2009@aol.com', 'Online Organ Matching System');
                            $mail->addAddress($email, 'Donor');     // Add a recipient
//$mail->addAddress('ellen@example.com');               // Name is optional
                            $mail->addReplyTo('nadrex2009@aol.com');
//$mail->addCC('cc@example.com');
//$mail->addBCC('bcc@example.com');

                            $mail->addAttachment('images/banner1.jpg');         // Add attachments
//$mail->addAttachment('/images/ts-avatar.png', 'Pic');    // Optional name
                            $mail->isHTML(true);                                  // Set email format to HTML

                            if ($gender == "Male") {
                                $g = "Mr.";
                            } else {
                                $g = "Ms.";
                            }

                            $mail->Subject = 'Registration Successful-Organ Donor!';
                            $mail->Body = "Dear $g $fullname,<br><br>This email is to inform you that we have registered you in OOMS as organ donor successfully! By donating your organs, you are saving people suffering from organ failure.<br> "
                                    . "Please contact us if you have any queries by calling our transplant center at 03-2555 4447 or through our email: ooms@aol.com."
                                    . "<br><br> Kind Regards,<br>OOMS";
                            $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

                            if (!$mail->send()) {
                                echo "<script>alert(Message could not be sent.');</script>";
                                // echo 'Mailer Error: ' . $mail->ErrorInfo;
                            } else {
                                $msg = " Donor registered successfully in OOMS Donor List and Email is sent to the donor to confirm registration as organ donor.";
                            }
                        } else {
                            $error = " Oops! Something went wrong. Please try again.";
                        }
                    } else {

                        $error = "  Passport/IC no. or Email address already exists. Please use other Passport/IC No. or new email address.";
                    }
                } else {
                    $error = " Please select at least one organ to donate.";
                }
            } else {
                $error = " Oops! Picture size might be greater than 1 MB. Please upload picture less than 1 MB.";
            }
        } else {
            $error = " Unsupported Image Type. Please upload jpg, jpeg, png extension files only.";
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

            <title>Admin | Add Donor</title>

            <link rel="stylesheet" href="css/font-awesome.min.css"> <!-- Bootstrap Icons -->
            <link rel="stylesheet" href="css/bootstrap.min.css"> <!-- Main Bootstrap -->
            <link rel="stylesheet" href="css/style.css"> <!-- Header Stye -->
            <link rel="stylesheet" href="css/tindex_c.css">

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
                        <a href="add-hospital-account.php"></a>
                        <div class="row">
                            <div class="col-md-12">
                                <h2 class="page-title"><font face="Comic Sans MS" color="red">Add a Donor</font></h2>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="panel panel-default">
                                            <div class="panel-heading" style="color: green;">Enter Donor Information Below</div>

                                            <?php if ($error) { ?><div class="errorWrap"><strong>ERROR</strong>:<?php echo htmlentities($error); ?> </div><?php } else if ($msg) {
                                                ?><div class="succWrap"><strong>SUCCESS</strong>:<?php echo htmlentities($msg); ?> </div><?php } ?>

                                            <div class="panel-body">
                                                <form method="post" class="form-horizontal" enctype="multipart/form-data">
                                                    <div class="form-group">
                                                        <label class="col-sm-2 control-label">Full Name<span style="color:red">*</span></label>
                                                        <div class="col-sm-4">
                                                            <input type="text" name="fullname" placeholder="Donor name can contain letters only" class="form-control" pattern="[a-zA-Z\s]+" maxLength="25" required>
                                                        </div>
                                                        <label class="col-sm-2 control-label">IC/Passport No.<span style="color:red">*</span></label>
                                                        <div class="col-sm-4">
                                                            <input type="text" name="pass" class="form-control"  maxlength="12" placeholder="Enter your IC/Passport No."  required>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="col-sm-2 control-label">Nationality<span style="color:red">*</span></label>
                                                        <div class="col-sm-4">
                                                            <select name="nationality" class="form-control" value="<?php echo htmlentities($result->Nationality); ?>" required>
                                                                <option value="">---Select---</option>
                                                                <option value="Malaysian">Malaysian</option>
                                                                <option value="Permanent Resident">Permanent Resident</option>
                                                                <option value="Non-Malaysian">Non-Malaysian</option>
                                                            </select>
                                                        </div>
                                                        <label class="col-sm-2 control-label">Mobile Number<span style="color:red">*</span></label>
                                                        <div class="col-sm-4">
                                                            <input type="text" name="mobileno" pattern="[0-9]{14}" maxlength="14" title="Remove any characters/spaces/negative numbers. Enter numbers only, e.g. 00601113328804" placeholder="Enter only 14 numeric values." class="form-control"  required>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="col-sm-2 control-label">Email<span style="color:red">*</span></label>
                                                        <div class="col-sm-4">
                                                            <input type="email" name="email" placeholder="Please provide valid email" class="form-control" required>
                                                        </div>
                                                        <label class="col-sm-2 control-label">Date of Birth<span style="color:red">*</span></label>
                                                        <div class="col-sm-4">
                                                            <input type="date" name="dob" min="1930-01-01" max="2018-05-25" class="form-control" required>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="col-sm-2 control-label">Gender<span style="color:red">*</span></label>
                                                        <div class="col-sm-4">
                                                            <select name="gender" class="form-control" required>
                                                                <option value="">---Select---</option>
                                                                <option value="Male">Male</option>
                                                                <option value="Female">Female</option>
                                                            </select>
                                                        </div>
                                                        <label class="col-sm-2 control-label">Blood Type<span style="color:red">*</span> </label>
                                                        <div class="col-sm-4">
                                                            <select name="bloodtype" class="form-control" required>
                                                                <option value="">---Select---</option>
                                                                <option value="A">A</option>
                                                                <option value="B">B</option>
                                                                <option value="AB">AB</option>
                                                                <option value="O">O</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="col-sm-2 control-label">Organ To Donate<span style="color:red">*</span> </label>
                                                        <div class="col-sm-5">
                                                            <label class="checkbox-inline">
                                                                <input type="checkbox"  name="organ1" id="organ1"  value="Kidney">Kidney</label>

                                                            <label class="checkbox-inline">
                                                                <input type="checkbox"  name="organ2" id="organ2"  value="Heart">Heart</label>
                                                            <label class="checkbox-inline">
                                                                <input type="checkbox" name="organ3" id="organ3"  value="Lungs">Lungs</label>
                                                            <label class="checkbox-inline">
                                                                <input type="checkbox" name="organ4" id="organ4"  value="Liver">Liver</label>
                                                            <label class="checkbox-inline">
                                                                <input type="checkbox" name="organ5" id="organ5"  value="Pancreas">Pancreas</label>
                                                        </div>                           

                                                        <label class="col-sm-1 control-label">State<span style="color:red">*</span></label>
                                                        <div class="col-sm-4">
                                                            <select name="state" class="form-control" required>
                                                                <option value="">---Select---</option>
                                                                <option value="Johor">Johor</option>
                                                                <option value="Kedah">Kedah</option>
                                                                <option value="Kelantan">Kelantan</option>
                                                                <option value="Kuala Lumpur">Kuala Lumpur</option>
                                                                <option value="Melaka">Melaka</option>
                                                                <option value="Negeri Sembilan">Negeri Sembilan</option>
                                                                <option value="Pahang">Pahang</option>
                                                                <option value="Penang">Penang</option>
                                                                <option value="Perak">Perak</option>
                                                                <option value="Perlis">Perlis</option>
                                                                <option value="Sabah">Sabah</option>
                                                                <option value="Sarawak">Sarawak</option>
                                                                <option value="Selangor">Selangor</option>
                                                                <option value="Terengganu">Terengganu</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="col-sm-2 control-label">Address/Zip Code<span style="color:red">*</span></label>
                                                        <div class="col-sm-10">
                                                            <input type="text" class="form-control" name="address" placeholder="Please write donor's address" maxlength="50" required>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="col-sm-2 control-label"><span class="glyphicon glyphicon-picture"></span>&nbsp;Donor Picture<span style="color:red">*</span></label>
                                                        <div class="col-sm-4">
                                                            <input type="file"  name="myfile" required>
                                                            <p class="help-block"><font style="color:red;"><i> Picture size must be less than 1 MB. Picture type can be jpg, jpeg and png only.</i</font></p>
                                                        </div>
                                                    </div>                                                
                                                    <div class="form-group">
                                                        <div class="col-sm-8 col-sm-offset-2">
                                                            <a class="btn btn-md btn-default" href="tdashboard.php" role="button"><font face="Trebuchet MS" style="font-size:14px;"><span class="glyphicon glyphicon-arrow-left"></span>&nbsp;<b>Back</b></font></a>
                                                            <button class="btn btn-primary btn-lg" name="submit"  type="submit"><font face="Trebuchet MS" style="font-size:14px;"><span class="glyphicon glyphicon-plus"></span> &nbsp;<b>Add Donor</b></font></button>
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