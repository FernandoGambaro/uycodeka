
  <?php 
   $pagetitle="Update Student's Record";
  include "includes/header.php"; ?>


<?php $db = new db(); ?>


  <?php 
    if (isset($_POST['update'])):?>
      <?php
      $studentName = $_POST['name'];
      $dob = $_POST['dob'];
      $gender = $_POST['gender'];
      $email = $_POST['email'];
      $phone= $_POST['phone'];
      $add= $_POST['add'];
      $session = $_POST['session'];
      $program= $_POST['program'];
      $semester= $_POST['semester'];
      $rollno= $_GET ['std_roll_no'];

      if($db->update_std($conn,$studentName,$dob,$gender,$email,$phone,$add,$rollno,$session, $program, $semester)){
      $status= "Student's Information Updated Successfully";
      }
     ?>
     <?php endif ?> 

     <?php 
        $std_id = array();
        if (isset($_GET['std_roll_no'])) {
          $std_id = $_GET['std_roll_no'];
        }
       ?>

<div class="container">
    
        <?php 
            $update = $db->get_single_std($conn,"student_table",$std_id);
        ?>
          <?php foreach ($update as $key) { ?>


                <div class="row">
                    <div class="templatemo-line-header" style="margin-top: 0px;" >
                        <div class="text-center">
                            <hr class="team_hr team_hr_left hr_gray"/><span class="span_blog txt_darkgrey txt_orange">Updating Student</span>
                            <hr class="team_hr team_hr_right hr_gray" />
                        </div>
                    </div>
                </div>
                <?php if (isset($status)): ?>

      <div class="alert alert-success alert-dismissible" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              <?php echo $status; ?>
      </div>


    <?php endif ?> 

<div class="form-container">

    <form method="post" role="form" action="student_update.php?std_roll_no=<?php echo $key['std_roll_no']; ?>">
       <div class="container">
           <div class="row">
           <div class="col-lg-4">

          <div class="form-group">
            <label for="name"> Student Name(*) </label>
            <input type="text" name="name" class="form-control"  value="<?php echo $key['student_name']; ?>" required id="name" placeholder="student Name" >
          </div>
          </div>
           
           <div class="col-lg-4">
          <div class="form-group">
            <label for="dob"> Date Of Birth </label>
            <input type="date" name="dob" class="form-control" value="<?php echo $key['dob']; ?>" id="dob" >
          </div>
          </div>
        </div>
        </div> <!-- col-container-->
       
        <div class="container">
           <div class="row">

        <div class="col-lg-4">
          <div class="form-group">
          <label for="gender">Gender(*)</label>
           <select class="form-control" name="gender"  required id="gender" >
           <?php echo $key['gender']; ?>
           <option> </option>
           <option value="male">Male</option>
           <option value="female">Female</option> 
           </select>
          </div>
        </div>
          <!-- </div> -->
          <!-- <div class="col-lg-6 push-right">  -->
        <div class="col-lg-4">
          <div class="form-group">
            <label for="email">Email</label>
            <input type="email" name="email" class="form-control" value="<?php echo $key['email']; ?>" required id="email" placeholder=" Email" >
          </div>
       </div>
       </div>
       </div><!-- col-container-->

      <div class="container">
       <div class="row">
       <div class="col-lg-4">
        <div class="form-group">
            <label for="phone">Phone </label>
            <input type="text" name="phone" class="form-control" value="<?php echo $key['phone']; ?>" id="phone" placeholder="Phone Number" >
          </div>
       </div>
       <div class="col-lg-4">
          <div class="form-group">
            <label for="add">Address</label>
            <textarea name="add" class="form-control"  id="add" placeholder="Your address please" rows="3" ><?php echo $key['address']; ?></textarea>
          </div>
       </div>
       </div>
     </div><!-- col-container-->
      <div class="container">
       <div class="row">
       <div class="col-lg-4">
      <div class="form-group">
            <label for="session" >Session</label>
            <input type="text" class="form-control" id="session" placeholder="session" name="session"  value="<?php echo $key['Session']; ?>" >
        </div>
        </div>
          <div class="col-lg-4">
          <div class="form-group">
          <label for="program"  class="col-sm-2 control-label">Program</label>
           <select  class="form-control" name="program"  required id="program" name="program"  value="<?php echo $key['Program']; ?>" >
          <option></option>
           <option >MCS</option>
           <option >BSCS</option>
           <option >BSSC</option>
           <option >Mphil</option>
           <option >PHD</option>
           </select>
          </div>  
          </div>
        </div>
          </div>

          <div class="col-lg-4">
          <div class="form-group">
          <label for="semester"  class="col-sm-2 control-label">Semester</label>
           <select  class="form-control" name="semester"  required id="semester"  value="<?php echo $key['Semester']; ?>"  >
           <option></option>
           <option>1st</option>
           <option>2nd</option>
           <option>3rd</option> 
           <option>4th</option>
           <option>5th</option>
           <option>6th</option>
           <option>7th</option>
           <option>8th</option>
           </select>
          </div>  
          </div>
          <div "form-actions"> <br><br>
          <div class="ui mini buttons col-sm-offset-3 col-sm-3">
          <button type="submit" class="ui mini positive button" name="update">Update</button>
          <div class="or"></div>
          <a href="student.php" type="submit" class="ui mini button" name="back">Back</a>
          </div>
          </div>
       </form>
   <?php } ?>
          </div>
     </div><!--container-->	 
<?php include "includes/footer.php"; ?>