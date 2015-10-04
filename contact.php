<?php session_start(); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
 <head>
  <title>teaching2fish ltd - Contact Us</title>
  
  <?php include('pg_header.php') ?>
  
  <div id="main">
    
   <?php include('pg_right.php') ?>
   
   <div class="left_side">
    
    <img src="resource/contact.jpg" style="width: 67px; float: right" alt="contact" />
    <h2>Contact</h2>
    <br />
    <h3>Please use the form below should you wish to contact us:</h3>
    <?php include "contact-form.php"; ?> 

   </div><!-- left_side -->
   
  </div><!-- main -->
  
  <?php include('pg_footer.php') ?>