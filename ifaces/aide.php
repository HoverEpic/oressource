<?php session_start(); ?>
<?php
    if (isset($_SESSION['id']) AND (strpos($_SESSION['niveau'], 'bi') !== false))
      {  include "tete.php" ?>
   
        <h1>need help? call me!</h1>
 <p>...and if I don't answer, bear in mind that a help file is actually under construction...and please be patient</p> 
         


<?php include "pied.php" ?>
<?php }
    else
    header('Location: ../') ;
?>
       
      
