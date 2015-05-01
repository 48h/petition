<?php
/*
Plugin Name: Petition Form
Is used to create a petiion form, that can also send emails
Version: 1.0
Author: Harrison Ssamanya
*/

define("ACTION_URL",plugins_url()."/myplugin/formHandler.php");
function html_petition_form($pageId) { 
 
     echo '<form class="form-horizontal" id="petitionForm" role="form" action="'.ACTION_URL.'" method="post">'; // post back here, navigate navigates to start page
     echo '<fieldset><legend>Petitionsformular</legend>';
     // <!-- Text input-->
     echo '<div class="form-group">';
     echo '<label class="control-label col-sm-4" for="name">Name</label>';
     echo '<div class="controls col-sm-10">';
     echo '<input name="name" class="input-medium" id="name" required="" type="text" placeholder="Vor- und Nachname">';
     echo '</div></div>';
     // <!-- Text input-->
     echo '<div class="form-group">';
     echo '<label class="control-label col-sm-4" for="email">E-mail</label>';
     echo '<div class="controls col-sm-10">';
     echo '<input name="email" class="input-medium" id="email" required="" type="text" placeholder="beispiel@domäne.de">';
     echo '</div></div>';
     // <!-- Text input-->
     echo '<div class="form-group">';
     echo '<label class="control-label col-sm-4" for="street">Strasse</label>';
     echo '<div class="col-sm-10">';
     echo '<input name="street" class="input-medium" id="street" required="" type="text" placeholder="beispielstrasse Nr.">';
     echo '</div></div>';
     //<!-- Text input-->
     echo '<div class="form-group">';
     echo '<label class="control-label col-sm-4" for="street">PLZ</label>';
     echo '<div class="col-sm-10">';
     echo '<input name="zip" class="input-medium" id="zip" required="" type="text" placeholder="">';
     echo '</div></div>';
     
     //<!-- Text input-->
     echo '<div class="form-group">';
     echo '<label class="control-label col-sm-4" for="place">Ort</label>';
     echo '<div class="col-sm-10">';
     echo '<input name="place" class="input-medium" id="place" required="" type="text" placeholder="">';
     echo '</div></div>';
     
     //<!-- Text input-->
     echo '<div class="form-group">';
     echo '<label class="control-label col-sm-4" for="country">Land</label>';
     echo '<div class="col-sm-10">';
     echo '<input name="country" class="input-medium" id="country" required="" type="text" placeholder="Deutschland">';
     echo '</div></div>';

     // <!-- Button (Double) -->
     echo '<div class="form-group">';
     echo '<label class="control-label col-sm-4" for="submit"></label>';
     echo '<div class="col-sm-10">';
     echo '<button name="pt_submit" class="btn btn-primary" id="pt_submit">Abschicken</button>&nbsp;&nbsp;&nbsp;&nbsp;';
     echo '<button name="reset" class="btn btn-inverse" id="reset">Leeren</button>';
     echo '<input type="hidden" name="pageId" value="'.$pageId.'">';
     echo '</div></div></fieldset>';
     echo '</form>';
}
 
function cf_shortcode($atts) {
    $pId=strval($atts['pageidparam']);
    $pageId = ($pId==null) ? '' : $pId;
    ob_start();
    html_petition_form($pageId); 
    return ob_get_clean();
} 
add_shortcode( 'get_petition_form', 'cf_shortcode' );