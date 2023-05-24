 <!--------------------side bar -------------------------->

 <?php $currentPath = $_SERVER['REQUEST_URI'];



  ?>


 <aside class="sidebar">

   <div class="sidebarlogo"><a href="index.php">
       <h3 >CRSC</h3>
   </div></a>
   <div class="userimgdiv text-center">
     <a href="" class="text-center"><img src="images/user.png" alt="user" id="userimg"></a>
     <p class="text-center" style="font-size: 130%;">
       <?= $firstname . " " . $lastname  ?> <br>
       <span class="text-center" style="font-size: 70%;">
         <?= $loginemail ?>
       </span>
     </p>


     <p class="text-center" style="font-size: 120%; color: hotpink">
       <?= $role ?>
     </p>
   </div>
   <div class="sidebaroptions">

     <ul>
       <a href="squads.php" class="<?php if (strpos($currentPath, 'squads')) echo "active-link" ?>">
         <li>Squad</li>
       </a>
       <a href="training.php" class="<?php if (strpos($currentPath, 'training')) echo "active-link" ?>">
         <li>Training</li>
       </a>
       <a href="galaevents.php" class="<?php if (strpos($currentPath, 'gala')) echo "active-link" ?>">
         <li>Gala</li>
       </a>

      
       <a href="allswimmers.php" class="<?php if (strpos($currentPath, 'allswimmers')) echo "active-link" ?>">
         <li>Swimmers</li>
       </a>

       
       <div class="btn-group dropend">
         <a class="btn  dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-bs-toggle="dropdown" aria-expanded="false"
         class="<?php if(strpos($currentPath, 'profile') || strpos($currentPath, 'security')||strpos($currentPath, 'ward') ) echo "active-link" ?>">
           Settings
         </a>

         <ul class="dropdown-menu" aria-labelledby="dropdownMenuLink">
           <li><a class="dropdown-item" href="profile.php">Profile</a></li>
           <li><a class="dropdown-item" href="passwords.php">Security</a></li>
           <li><a class="dropdown-item" href="ward.php">Ward</a></li>
         </ul>
       </div>
     </ul>
   </div>
 </aside>