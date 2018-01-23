
<?php
 $pagetitle="Updating-Teacher's Record";
 include "includes/header.php";
  $db = new db();

  ?>

 <?php

    if (isset($_POST['update'])):?>
      <?php
      $firstName = $_POST['name'];
      $lastName = $_POST['lname'];
      $dob = $_POST['dob'];
      $gender = $_POST['gender'];
      $email = $_POST['email'];
      $phone= $_POST['phone'];
      $degree= $_POST['degree'];
      $salary= $_POST['salary'];
      $address= $_POST['address'];
      $id= $_GET['teacher_id'];
      if($db->update_teacher_record($conn,$firstName,$lastName,$dob,$gender,$email,$phone,$degree,$salary,$address,$id)){
             $status= "Teacher's Information Updated Successfully";
        }
       ?>
     <?php endif ?>   

      <?php 
        $t_id = array();
        if (isset($_GET['teacher_id'])) {
          $t_id = $_GET['teacher_id'];
        }
       ?>


<div class="container">
        <?php 
            $update = $db->get_single_teacher($conn,"teacher_table",$t_id);
        ?>
        <?php foreach ($update as $key) { ?>
               <div class="row">
                    <div class="templatemo-line-header" style="margin-top: 0px;" >
                        <div class="text-center">
                            <hr class="team_hr team_hr_left hr_gray"/><span class="span_blog txt_darkgrey txt_orange">Update The Teacher</span>
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

    <form method="post" role="form" action="teacher_update.php?teacher_id=<?php echo $key['teacher_id']; ?>">
    <div class="container">
    <div class="row">
    <div class="col-lg-3">
          <div class="form-group">
            <label for="name" > First Name (*)</label>
            <input type="text" class="form-control" required id="name" placeholder="First Name" name="name" value="<?php echo $key['first_name']; ?>">
          </div>
    </div>
    <div class="col-lg-3">
          <div class="form-group">
            <label for="lname" > Last Name</label>
            <input type="text" class="form-control" required id="lname" placeholder="Last Name"  name="lname" value="<?php echo $key['last_name']; ?>">
          </div>
    </div>
    </div>
    </div>
    <div class="container">
    <div class="row">
    <div class="col-lg-3">
          <div class="form-group">
            <label for="dob"> Date Of Birth </label>
            <input type="date" class="form-control" id="dob" name="dob" value="<?php echo $key['dob']; ?>">
          </div>
    </div>
    <div class="col-lg-3">
          <div class="form-group">
          <label for="gender" >Gender</label>
           <select  class="form-control" name="gender" required id="sex" name="gender" >
           <?php echo $key['gender']; ?>
           <option>-------select-------</option>
           <option>Male</option>
           <option>Female</option> 
           </select>
          </div>
     </div>
     </div>
     </div>

     <div class="container">
    <div class="row">
    <div class="col-lg-3">
          <div class="form-group">
            <label for="email">Email address </label>
            <input type="email" class="form-control" required id="email" placeholder=" Email" name="email" value="<?php echo $key['email']; ?>">
          </div>
          </div>
    <div class="col-lg-3">
          <div class="form-group">
            <label for="phone">Phone </label>
            <input type="text" class="form-control" id="phone" placeholder="Phone Number" name="phone" value="<?php echo $key['phone']; ?>">
          </div>
    </div>
    </div>
    </div>
    <div class="container">
    <div class="row">
    <div class="col-lg-3">
           <div class="form-group">
          <label for="degree" >Degree</label>
           <select  class="form-control" name="degree"  required id="degree" name="degree">
           <?php echo $key['degree']; ?>
           <option>-------select-------</option>
           <option >Bachelor</option>
           <option >Master</option>
           <option >M.Phil</option>
           <option >P.HD</option>
           </select>
          </div>
          </div>

    <div class="col-lg-3">
          <div class="form-group">
            <label for="salary"> Salary </label>
            <input type="text" class="form-control" required id="salary" placeholder=" Enter salary"  name="salary" value="<?php echo $key['salary']; ?>">
          </div>
    </div>
    </div>
    </div>
    <div class="container">
    <div class="row">
    <div class="col-lg-3">
          <div class="form-group">
            <label for="address">Address</label>
            <textarea class="form-control" id="address" placeholder="Your address please" rows="3" name="address"><?php echo $key['address']; ?></textarea>
          </div>
          </div>
    </div>
    </div>

          <div class="ui mini buttons col-sm-offset-3 col-sm-3">
          <button type="submit" class="ui mini positive button" name="update">Update</button>
          <div class="or"></div>
          <a href="teacher.php" type="submit" class="ui mini button" name="back">Back</a>
          </div>
      
       </form>
        <?php } ?>

           </div><!--form-container-->  
           </div><!--container-->	    
<?php include "includes/footer.php"; ?>
