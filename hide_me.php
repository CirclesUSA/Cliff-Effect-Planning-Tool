<html>
<head>
    <style>
        /* This could be also part of an css file: */
        .container_hidden
         .hidden_element {
            display: none;
        }
    </style>
<script>

function ShowYes21()
	{
	document.getElementById("showme").style.display="block";
	document.getElementById("showmenot").style.display="none";
	}

function ShowNo21()
	{
	document.getElementById("showmenot").style.display="block";
	document.getElementById("showme").style.display="none";
	}
</script>
</head>
<body>
    <div class="container">
        <script>
            document.getElementsByClassName('container')[0]
                    .className += ' container_hidden';
        </script>

        <div id="showme" class="hidden_element">Yes, there are people 21 years or older.<br><br></div>
        <div id="showmenot" class="hidden_element"><br><br>No, there are not people 21 years or older in the home.<br><br></div>
    </div>

Is there anybody IN there?<br><br>

Or is it OUT there?<br><br>

Hmmm. . .<br><br>
<div id="ArePeople21" class="h2" style="opacity: 1; float:left; width:100%;">
          <p style="font-weight: bold; color: rgb(242, 117, 34);">
            Are there people 21 years or older (Adults) in the home?
			<label id="item58_0_label"><input type="radio" id="item58_0_radio" data-hint="" name="twentyone_or_older_check" value="Yes" onchange="ShowYes21();"/><span style="font-size: 12px; color: black;">Yes</span></label>
			<label id="item58_1_label"><input type="radio" id="item58_1_radio" name="twentyone_or_older_check" value="No" onchange="ShowNo21();" /><span style="font-size: 12px; color: black;">No</span></label>
          </p>
      </div>
</body>
</html>