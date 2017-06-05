<!DOCTYPE HTML>
<html>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

    <title>
      Circles USA CEPT
    </title>
  <style>
 .nextbutton {
    background-color: #069B54; /* GREEN */
    border: none;
    color: white;
    padding: 5px 5px;
    text-align: center;
    text-decoration: none;
    display: inline-block;
    font-size: 10px;
    margin: 4px 2px;
    cursor: pointer;
}
  .donebutton {
    background-color: #EF5F0A; /* ORANGE */
    border: none;
    color: white;
    padding: 5px 5px;
    text-align: center;
    text-decoration: none;
    display: inline-block;
    font-size: 10px;
    margin: 4px 2px;
    cursor: pointer;
}
  .anotherbutton {
    background-color: #008FD9; /* BLUE */
    border: none;
    color: white;
    padding: 5px 5px;
    text-align: center;
    text-decoration: none;
    display: inline-block;
    font-size: 10px;
    margin: 4px 2px;
    cursor: pointer;
}
  .centered {
    margin: auto;
    width: 60%;
    border: 6px ridge rgb(0, 159, 222); color: rgb(0, 0, 0);
    padding: 10px;
    box-shadow: 5px 5px 5px #cccccc;
    border-radius: 8px;
    position: relative;
}
  .h2 {
	font-family: Helvetica Neue,Helvetica,Arial,sans-serif;
	font-weight: bold;
	color: rgb(0, 163, 222);
}
  .h3 {
	font-family: Helvetica Neue,Helvetica,Arial,sans-serif;
	//font-weight: bold;
	color: black;
}
  .p1 {
	display: inline;
	font-family: Helvetica Neue,Helvetica,Arial,sans-serif;
	font-weight: bold;
	color: rgb(0, 163, 222);
	font-size: 18px;
}
  .p2 {
	display: inline;
	font-family: Helvetica Neue,Helvetica,Arial,sans-serif;
	font-weight: bold;
	color: rgb(0, 163, 222);
	font-size: 18px;
}
  .form-els {
	display: inline;
	font-family: Helvetica Neue,Helvetica,Arial,sans-serif;
	font-weight: bold;
}
  .button-text {
	display: inline;
	font-family: Helvetica Neue,Helvetica,Arial,sans-serif;
	font-size: 14px;
	//font-weight: bold;
}
  .org-hr {
 	width: 100%;
    border-style: outset;
    border-width: 3px;
    color: rgb(242, 117, 34);
}
  </style>


  </head>

  <body>
  
<!-- BEGIN OUTSIDE FORM -->
<div id="MainDIV" class="centered">
<form id="cl_currentbenefits" enctype="multipart/form-data" method="POST" action="cl_infos.php" novalidate="novalidate" style="padding: 10px;" autocomplete="on">

	<!-- START HEADER -->
	<div style="float: left;">
	  <img alt="Circles USA" id="cusalogo" src="images/circles-usa-new.png" style="display: inline;"/>
	</div>
	<div style="text-align: center; float: right;">
      <h2 class="h2">Cliff Effect Planning Tool</h2>
    </div>
 	<div style="text-align: center; float: left; width: 100%;">
          <p class="p1">
            <br>Circle Leader Information
          </p>
          <hr class="org-hr">
        </div>
    <!-- END HEADER -->
<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
<script type="text/javascript">
window.onload = function()
	{
    document.getElementById("DoneButtonRow").style.display="none";
  }
function CheckButtons()
  {
    var x1 = document.getElementById("ChildCareY").checked;
    var x2 = document.getElementById("ChildCareN").checked;
    var x3 = document.getElementById("FoodStampsY").checked;
    var x4 = document.getElementById("FoodStampsN").checked;
    var x5 = document.getElementById("HudSubsidyY").checked;
    var x6 = document.getElementById("HudSubsidyN").checked;
    var x7 = document.getElementById("TANFY").checked;
    var x8 = document.getElementById("TANFN").checked;
	var x9 = document.getElementById("MedicalY").checked;
    var x10 = document.getElementById("MedicalN").checked;
    if (x1==true || x2==true)
    {
      if (x3==true || x4==true)
        {
          if (x5==true || x6==true)
          {
            if (x7==true || x8==true)
              {
				  if (x9==true || x10==true)
              {
                document.getElementById("DoneButtonRow").style.display="block";
              }
          }
        }
	}
    }
	

  }
  </script>
      <div id="Benefits:" style="opacity: 1; float:left; width: 100%;">
        <div style="float: left; width: 100%;">
	<table width="90%" align="center">
	<tr><td class="button-text" align="left" style="font-weight:bold;">Child Care
	</td><td width="20%"><fieldset id="ChildCareset" style="border:0;"><input type="radio" id="ChildCareY" name="cur_childcare" value="Yes" onchange="CheckButtons();"/><span class="p3">Yes</span>
																	<input type="radio" id="ChildCareN" name="cur_childcare" value="No" onchange="CheckButtons()";/><span class="p3">No</span></fieldset></td></tr>
  <tr><td colspan="2" align="center" class="p2"><hr class="org-hr"></td></tr>
  <tr><td class="button-text" align="left" style="font-weight:bold">Food Stamps 
  </td><td width="20%"><fieldset id="FoodStampsSet" style="border:0;"><input type="radio" id="FoodStampsY" name="cur_foodstamps" value="Yes" onchange="CheckButtons();"/><span  class="p3">Yes</span>
																<input type="radio" id="FoodStampsN" name="cur_foodstamps" value="No" onchange="CheckButtons();"/><span  class="p3">No</span></fieldset></td></tr>
  <tr><td colspan="2" align="center" class="p2"><hr class="org-hr"></td></tr>
	<tr><td class="button-text" align="left" style="font-weight:bold">Hud Subsidy
	</td><td width="20%"><fieldset id="HudSubsidySet" style="border:0;"><input type="radio" id="HudSubsidyY" name="cur_hudsubsidy" value="Yes" onchange="CheckButtons();"/><span  class="p3">Yes</span>
																	<input type="radio" id="HudSubsidyN" name="cur_hudsubsidy" value="No" onchange="CheckButtons();"/><span  class="p3">No</span></fieldset></td></tr>
  <tr><td colspan="2" align="center" class="p2"><hr class="org-hr"></td></tr>
  <tr><td class="button-text" align="left" style="font-weight:bold">TANF
 </td><td width="20%"><fieldset id="TANFset" style="border:0;"><input type="radio" id="TANFY" name="cur_tanf" value="Yes" onchange="CheckButtons();"/><span  class="p3">Yes</span>
																<input type="radio" id="TANFN" name="cur_tanf" value="No" onchange="CheckButtons();"/><span  class="p3">No</span>	</td></tr>
  <tr><td colspan="2" align="center" class="p2"><hr class="org-hr">
  </td></tr>
  <tr><td class="button-text" align="left" style="font-weight:bold">Medical
	</td><td width="20%"><fieldset id="Medicalset" style="border:0;"><input type="radio" id="MedicalY" name="cur_medical" value="Yes" onchange="CheckButtons();"/><span  class="p3">Yes</span>
																<input type="radio" id="MedicalN" name="cur_medical" value="No" onchange="CheckButtons();"/><span  class="p3">No</span></td></tr>
	<tr><td colspan="2" align="center" class="p2"><hr class="org-hr"></td></tr>
	<tr id="DoneButtonRow"><td colspan="2" align="center" ><td>

 <button type="submit" class="donebutton" onclick="" >Continue to Results --></button>
 </td></tr>
	</table>
	</div>
	<?php
			echo "<input type=\"hidden\" name=\"cur_childcare\" value=\"".($_POST["cur_childcare"])."\">";
			echo "<input type=\"hidden\" name=\"cur_foodstamps\" value=\"".($_POST["cur_foodstamps"])."\">";
			echo "<input type=\"hidden\" name=\"cur_hudsubsidy\" value=\"".($_POST["cur_hudsubsidy"])."\">";
			echo "<input type=\"hidden\" name=\"cur_tanf\" value=\"".($_POST["cur_tanf"])."\">";
			echo "<input type=\"hidden\" name=\"cur_medical\" value=\"".($_POST["cur_medical"])."\">";
   ?>
 	<!-- ALLWAYS KEEP NEXT DIV AT THE VERY BOTTOM! This makes sure that the blue border stays bigger than the content. -->
 	<div style="clear: both;">&nbsp;</div>
	
</form>
</div>
<!-- END OUTSIDE FORM -->
<br>
<div align="center"><table align="center" border="0" style="font-size: 11px;">
<tr><td colspan="3"><p align="center"><img alt="Copyleft Yo!" height="10" width="10" src="images/Copyleft.png" /> 2017 CirclesUSA.org</p></td></tr>
<tr><td valign="middle" align="center">Powered with&nbsp;<img src="images/heart.png" height="10" width="10" alt="Love is all that matters">
&nbsp;by VinceCo<br><br></td></tr>
<tr><td colspan="3" align="center">
<a rel="license" href="http://creativecommons.org/licenses/by-nc-sa/4.0/">
<img alt="Creative Commons License" style="border-width:0" src="https://i.creativecommons.org/l/by-nc-sa/4.0/88x31.png" />
</a>
<br />This work is licensed under a
<a rel="license" href="http://creativecommons.org/licenses/by-nc-sa/4.0/">Creative Commons Attribution-NonCommercial-ShareAlike 4.0 International License
</a>.<br><br>
HighCharts graphing is licensed separately from this work and is solely for use by Circles USA within the scope of this work.<br>
Please contact HighCharts directly at <a href="http://www.highcharts.com/contact-email">http://www.highcharts.com/contact-email</a> to obtain your own license.<br><br>
</td></tr>
</table></div><br><br>

       <div id="ShowEverything" style="opacity: 1; float:left; width:100%; text-align: center;">
         <button type="button" onclick="ShowAll()" >Show All (DEV104)</button><br><br>
      </div>

</body>
</html>
