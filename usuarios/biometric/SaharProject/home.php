<?php
session_start();

             if(isset($_SESSION['id'])){
              $username=($_SESSION['username']);
              $userId=($_SESSION['id']);
            }
            else{
              header("Location:login.php");
            }

 $pagetitle="Home Page";
 include "includes/header.php";
      include "includes/slider.php";
      ?>
        <div class="templatemo-welcome" id="templatemo-welcome">
            <div class="container">
                <div class="templatemo-slogan text-center">
                    <span class="txt_darkgrey">Welcome to </span><span class="txt_orange">Home Page</span>
                    <p class="txt_slogan">
                </div>	
            </div>
        </div>   
        

    <div id="templatemo-blog">
            <div class="container">
                <div class="row">
                 <?php include "includes/sidebar.php";?>
                <div class="blog_box">
                    <div class="col-sm-5 col-md-6 blog_post">
                        <ul class="list-inline">
                        <li>    
                        <div class="clearfix"> </div>
                        <p class="blog_text">
                            The Institute of computing and information technology, since its inception in 1987 has been focusing on human resource development through its various programs. With the rapid development in computing technologies and the associated growth in information technology (IT) applications, the importance of the computer sciences as a discipline has become of paramount importance. The availability of properly trained computer professionals determines the extent to which IT applications can be developed. This situation has created new and exciting opportunities for those who are well qualified and properly trained in IT. The BSCS and MCS programs at the Institute of Computing & Information Technology (ICIT) offer an excellent opportunity to individuals for a career in the IT field. 
                            With a first class qualified faculty, laboratories equipped wiht the state-of-the-art computing facilities and a well stocked library, the ICIT offers an opportunity for achieving standard and high quality computer science education in PAKISTAN. The said objective in mind, the institute has started MS and Ph.D programs.</p>
                            </li>
                        </ul>
                    </div> <!-- /.blog_post 1 --> 
                </div>
              </div>
           </div>
    </div>
<?php include "includes/footer.php"; ?>