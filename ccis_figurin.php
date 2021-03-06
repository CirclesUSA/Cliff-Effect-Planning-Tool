<?php
  if ($cl_stateinits=="NM")
  {
    //$passSNAP_resource_test == Spreadsheet CCIS_B19
    //$ThisGrossIncome == Spreadsheet CCIS_B29
    //$people_count == CCIS_21
    //$TotalEarnedIncome == CCIS_22
    //$TotalUnEarnedIncome == CCS_23-28

//LOOKUP MONTHLY MAXIMUM MEDICAL DEDUCTION FOR STATE
    $maxmed_sql = "SELECT income_adjust_childmedicalmax FROM state_data WHERE state_init=\"".$cl_stateinits."\";";
    $maxmed_result = $dbconn->query($maxmed_sql);
      while ($maxmed_row = $maxmed_result->fetch_assoc())
      {
        $CCIS_IncomeAdjust_Medical=$maxmed_row['income_adjust_childmedicalmax'];
      }

//CALCULATE F30 from CCIS worksheet
    $ExpenseDisabledAmount=$_POST['ExpenseDisabledAmount'];
    $MedicalElderlyAmount=$_POST['MedicalElderlyAmount'];
    $CCIS_F30=$ExpenseDisabledAmount+$MedicalElderlyAmount;

//CALCULATE MEDICAL ADJUSTMENT
    $CCIS_Medical_Adjustment_Amount=min($CCIS_F30,$CCIS_IncomeAdjust_Medical);

//LOOKUP WORK RELATED EXPENSES ADJUSTMENT FOR STATE
    $workadj_sql = "SELECT income_adjust_work_related_expenses FROM state_data WHERE state_init=\"".$cl_stateinits."\";";
    $workadj_result = $dbconn->query($workadj_sql);
      while ($workadj_row = $workadj_result->fetch_assoc())
      {
        $CCIS_IncomeAdjust_WorkExpense=$workadj_row['income_adjust_work_related_expenses'];
      }

//CALCULATE THE NUMBER OF FULL AND PARTTIME WORKERS
    if(isset($_POST['cl_adults_FT'])){$Fulltime_Workers=$_POST['cl_adults_FT'];}else{$Fulltime_Workers=0;}
    if(isset($_POST['cl_adults_PT'])){$Parttime_Workers=$_POST['cl_adults_PT'];}else{$Parttime_Workers=0;}
    $CCIS_TotalWorking=$Fulltime_Workers+$Parttime_Workers;

//CALCULATE WORKEXPENSE AMOUNT BASED ON ADJUSTMENT AND NUMBER OF WORKERS
    $CCIS_WorkExpense_Amount=$CCIS_IncomeAdjust_WorkExpense*$CCIS_TotalWorking;

//REFERENCE CHILDSUPPORT RECEIVED FROM TANF_FIGURIN
    $child_support_received_amount=$child_support_received_amount;

//LOOKUP CHILDSUPPORT MAXIMUM DEDUCTION FOR STATE
    $maxcsadj_sql = "SELECT income_adjust_childsupporin FROM state_data WHERE state_init=\"".$cl_stateinits."\";";
    $maxcsadj_result = $dbconn->query($maxcsadj_sql);
      while ($maxcsadj_row = $maxcsadj_result->fetch_assoc())
      {
        $CCIS_ChildSupportReceived_Allowed=$maxcsadj_row['income_adjust_childsupporin'];
      }

//CALCULATE CHILDSUPPORT PAID AMOUNT
      //=IF(monthly_child_support>=VLOOKUP(State_Abrv,Table8,50),VLOOKUP(State_Abrv,Table8,50),monthly_child_support)
      if ($child_support_received_amount>=$CCIS_ChildSupportReceived_Allowed){$CCIS_ChildSupport_Amount=$CCIS_ChildSupportReceived_Allowed;}else{$CCIS_ChildSupport_Amount=$child_support_received_amount;}

//CALCULATE STEP PARENT ADJUSTMENT from CCIS worksheet B33
//=IF(VLOOKUP(State_Abrv,Table8,47)=FALSE,0,
//IF(Step_Parent="y",
//IF(HouseHoldSize<=6,VLOOKUP(CCIS_County_State,#REF!,HouseHoldSize),
//(VLOOKUP(CCIS_County_State,#REF!,6)+(VLOOKUP(CCIS_County_State,#REF!,7)*(HouseHoldSize-6)))),0))
    $StepParentSatus=$_POST['cl_family_stepparent_check'];

    $CCIS_StepParent_Adjustment=0;

//LOOKUP INCOME ADJUSMENT ALLOWED FOR CHILDSUPPORT PAID
    $childsuppadj_sql = "SELECT income_adjust_childsupportpaid FROM state_data WHERE state_init=\"".$cl_stateinits."\";";
    $childsuppadj_result = $dbconn->query($childsuppadj_sql);
      while ($childsuppadj_row = $childsuppadj_result->fetch_assoc())
      {
        $CCIS_ChildSupportPaid_Allowed=$childsuppadj_row['income_adjust_childsupportpaid'];
      }

//REFERENCE CHILDSUPPORT PAID FROM SNAP_FIGURIN
    $ChildSupportPaidAmount=$ChildSupportPaidAmount;

//CALCULATE CHILDSUPPORT PAID ADJUSMENT
      if($CCIS_ChildSupportPaid_Allowed=="True")
      {
        $CCIS_ChildSupportPaid_Amount=$ChildSupportPaidAmount;
      }
      else
      {
      $CCIS_ChildSupportPaid_Amount=0;
      }

//LOOKUP INCOME ADJUSMENT ALLOWED FOR ALIMONY PAID
    $aladj_sql = "SELECT income_adjust_alimonypaid FROM state_data WHERE state_init=\"".$cl_stateinits."\";";
    $aladj_result = $dbconn->query($aladj_sql);
      while ($aladj_row = $aladj_result->fetch_assoc())
      {
        $CCIS_AlimonyPaid_Allowed=$aladj_row['income_adjust_alimonypaid'];
      }

//REFERENCE SPOUSESUPPORT aka alimony PAID FROM TANF_FIGURIN
    $AlimonyPaidAmount=$deduct_spousesupport_amount;

//CALCULATE ALIMONY PAID ADJUSMENT
      if($CCIS_AlimonyPaid_Allowed=="True")
        {
          $CCIS_AlimonyPaid_Amount=$AlimonyPaidAmount;
        }
        else
        {
          $CCIS_AlimonyPaid_Amount=0;
        }

//=(((VLOOKUP(HouseHoldSize,FPIG_Net_Inc_Limit_Table,2)))+(IF(HouseHoldSize>10,FPIG!$E$22*(HouseHoldSize-10),0)))*VLOOKUP(State_Abrv,Table8,52)

//REFERENCE GROSS INCOME MAXIMUM FROM SNAP_FIGURIN
    $Gross_Income_Maximum=$Gross_Income_Maximum;

//LOOKUP CCIS ELIGIBILITY PERCENTAGE FOR TEST 1
    $elip_sql = "SELECT ccis_eligible_test1,ccis_eligible_test2 FROM state_data WHERE state_init=\"".$cl_stateinits."\";";
    $elip_result = $dbconn->query($elip_sql);
      while ($elip_row = $elip_result->fetch_assoc())
        {
          $CCIS_EligibPercent1=(str_replace("%","",$elip_row['ccis_eligible_test1']))*.01;
          $CCIS_EligibPercent2=(str_replace("%","",$elip_row['ccis_eligible_test2']))*.01;
        }

//REFERENCE MOOLA FOR PEOPLE COUNT OVER 10 FROM SNAP_FIGURIN
    $Gross_Add_Dollar=$Gross_Add_Dollar;

//CALCULATE ADDITIONAL AMOUNT TO ADD TO NET INCOME LIMIT aka Gross_Add_Dollar
      if ($people_count>10){$CCIS_AdditionalNet_Amount=$Gross_Add_Dollar*($people_count-10);}else{$CCIS_AdditionalNet_Amount=0;}

//CALCULATE TOTAL ADDITIONAL NET INCOME AMOUNT
    $CCIS_TotalNetIncome_Amount=$fpig100+$CCIS_AdditionalNet_Amount;

    $CCIS_FPIG150Monthly_Amount=$CCIS_TotalNetIncome_Amount*$CCIS_EligibPercent1;
    $CCIS_FPIG150Annual_Amount=$CCIS_FPIG150Monthly_Amount*12;

//CALCULATE THE KIDDOS LESS THAN 13 YEARS OLD AND THEIR AGES
    $CCIS_KidsLessThan13_Check=$_POST['less_than_thirteen_check'];
      if ($CCIS_KidsLessThan13_Check=="Yes")
      {
        $CCIS_TotalAgesLessThan13=0;
        $CCIS_KidsLessThan13_Amount=$_POST['cl_less_than_thirteen'];
        $i=1;
        while ($i<=$CCIS_KidsLessThan13_Amount)
        {
          $which_childyears_post="cl_child".$i."_years";
          $which_childmonths_post="cl_child".$i."_months";
          $this_child_age=$_POST[$which_childyears_post]+$_POST[$which_childmonths_post];
          $CCIS_TotalAgesLessThan13=$CCIS_TotalAgesLessThan13+$this_child_age;
          $i++;
        }
      }
      else
      {
        $CCIS_KidsLessThan13_Amount=0;
      }

//CALCULATE THE KIDDOS FROM 13 TO 18 YEARS OLD THAT ARE DISABLED
      if (isset($_POST['cl_unable']))
      {
        $CCIS_KiddosUnable_Amount=$_POST['cl_unable'];
      }
      else
      {
        $CCIS_KiddosUnable_Amount=0;
      }

//AFTER HOUR WEEKEND HOUR PERCENTAGES FOR NM
      if(strtolower($cl_stateinits)=="nm")
      {
        if (isset($CCIS_MoreHours))
        {
          if (($CCIS_MoreHours<=10)&&($CCIS_MoreHours>0)){$CCIS_AfterHours="5%";}
          if (($CCIS_MoreHours>=11)&&($CCIS_MoreHours<=20)){$CCIS_AfterHours="10%";}
          if ($CCIS_MoreHours>=20){$CCIS_AfterHours="15%";}
        }
      }

//REFERENCE TO CCIS WORKSHEET FOR MAX SUBSIDY PAYMENT FOR 3 KIDS IN TEST
    $CCIS_MaxSubsidy_Amount=1463.05;



//CALCULATE TOTAL VALUE OF ASSETS
      if ($vehicle_equity_allowed==1)
      {
        $CCIS_total_value_assets=$checking_account_bal+$savings_account_bal+$EVvehicle1+$EVothervehicle+$EVpersonal+$EVnonResi+$EVallCountable;
      }
      else
      {
        $CCIS_total_value_assets=$checking_account_bal+$EVothervehicle+$EVpersonal+$EVnonResi+$EVallCountable;
      }

//		$x=1;
    $CCIS_row="";
    $x=1;
      while ($x<=$plotpoints)
      {
        if ($HowManyChildren!=0)
        {
          $CCIS_Income_Counted=${"this_monthly".$x};
          $CCIS_CountedIncome_Adjusted=$CCIS_Income_Counted-($CCIS_Medical_Adjustment_Amount+$CCIS_WorkExpense_Amount+$CCIS_ChildSupport_Amount+$CCIS_StepParent_Adjustment+$CCIS_ChildSupportPaid_Amount+$CCIS_AlimonyPaid_Amount);
          $CCIS_AdjustedIncome_Amount=max(0,$CCIS_CountedIncome_Adjusted);
          $CCIS_AdjustedIncome_Annual=$CCIS_AdjustedIncome_Amount*12;
          if ($CCIS_FPIG150Annual_Amount>=$CCIS_AdjustedIncome_Annual){$CCIS_AnnualIncomeTest="True";}else{$CCIS_AnnualIncomeTest="Fals";}
          if (($CCIS_KidsLessThan13_Amount+$CCIS_KiddosUnable_Amount)>.5){$CCIS_ChildAge_Test="True";}else{$CCIS_ChildAge_Test="False";}
          $passSNAP_resource_test=$passSNAP_resource_test;

          $whichcopay="copay_p".$people_count;
          $whichccis=strtolower($cl_stateinits)."_ccis_copay";
//echo "<br><br>ccp_sql = SELECT ".$whichcopay." FROM ".$whichccis." WHERE mni_from>=".$CCIS_AdjustedIncome_Amount."<br><br>";
          $ccp_sql = "SELECT $whichcopay FROM $whichccis WHERE mni_from>=$CCIS_AdjustedIncome_Amount;";
          $ccp_result = $dbconn->query($ccp_sql);
          $ccp_row = $ccp_result->fetch_assoc();
          $CCIS_Child1_ThisCoPay_Amount=$ccp_row[$whichcopay];

          if ($CCIS_KidsLessThan13_Amount>1){$CCIS_ChildMore_ThisCopay_Amount=($CCIS_KidsLessThan13_Amount-1)*($CCIS_Child1_ThisCoPay_Amount/2);}
          else{$CCIS_ChildMore_ThisCopay_Amount=0;}
          $CCIS_TotalMonthlyCopay=$CCIS_Child1_ThisCoPay_Amount+$CCIS_ChildMore_ThisCopay_Amount;
          $CCIS_SubsidyLessCopay=max(($CCIS_MaxSubsidy_Amount-$CCIS_Child1_ThisCoPay_Amount),0);
        }
        else
        {
        $$CCIS_ChildAge_Test="False";
        }
            //=IF(AND(Z41=TRUE,Z42=TRUE,Z43=TRUE,Z67>0),Z67,0)
        if (($CCIS_AnnualIncomeTest=="True")&&($CCIS_ChildAge_Test=="True")&&($passSNAP_resource_test=="True")&&($CCIS_SubsidyLessCopay>0))
        {
          ${"this_ccis".$x}=$CCIS_SubsidyLessCopay;
          $CCIS_row = $CCIS_row."<td>".number_format($CCIS_SubsidyLessCopay)."</td>";
          $x++;
        }
        else
        {
          ${"this_ccis".$x}="0.00";
          $CCIS_row = $CCIS_row."<td bgcolor=\"lightgray\">".number_format('0')."</td>";
          $x++;
        }
      }
  }
  if ($cl_stateinits=="MI")
  {
    $maxmed_sql = "SELECT income_adjust_childmedicalmax FROM state_data WHERE state_init=\"".$cl_stateinits."\";";
    $maxmed_result = $dbconn->query($maxmed_sql);
      while ($maxmed_row = $maxmed_result->fetch_assoc())
      {
        $CCIS_IncomeAdjust_Medical=$maxmed_row['income_adjust_childmedicalmax'];
      }

//CALCULATE F30 from CCIS worksheet
    $ExpenseDisabledAmount=$_POST['ExpenseDisabledAmount'];
    $MedicalElderlyAmount=$_POST['MedicalElderlyAmount'];
    $CCIS_F30=$ExpenseDisabledAmount+$MedicalElderlyAmount;

//CALCULATE MEDICAL ADJUSTMENT
    $CCIS_Medical_Adjustment_Amount=min($CCIS_F30,$CCIS_IncomeAdjust_Medical);

//LOOKUP WORK RELATED EXPENSES ADJUSTMENT FOR STATE
    $workadj_sql = "SELECT income_adjust_work_related_expenses FROM state_data WHERE state_init=\"".$cl_stateinits."\";";
    $workadj_result = $dbconn->query($workadj_sql);
      while ($workadj_row = $workadj_result->fetch_assoc())
      {
        $CCIS_IncomeAdjust_WorkExpense=$workadj_row['income_adjust_work_related_expenses'];
      }

//CALCULATE THE NUMBER OF FULL AND PARTTIME WORKERS
    if(isset($_POST['cl_adults_FT'])){$Fulltime_Workers=$_POST['cl_adults_FT'];}else{$Fulltime_Workers=0;}
    if(isset($_POST['cl_adults_PT'])){$Parttime_Workers=$_POST['cl_adults_PT'];}else{$Parttime_Workers=0;}
    $CCIS_TotalWorking=$Fulltime_Workers+$Parttime_Workers;

//CALCULATE WORKEXPENSE AMOUNT BASED ON ADJUSTMENT AND NUMBER OF WORKERS
    $CCIS_WorkExpense_Amount=$CCIS_IncomeAdjust_WorkExpense*$CCIS_TotalWorking;

//REFERENCE CHILDSUPPORT RECEIVED FROM TANF_FIGURIN
    $child_support_received_amount=$child_support_received_amount;

//LOOKUP CHILDSUPPORT MAXIMUM DEDUCTION FOR STATE
    $maxcsadj_sql = "SELECT income_adjust_childsupporin FROM state_data WHERE state_init=\"".$cl_stateinits."\";";
    $maxcsadj_result = $dbconn->query($maxcsadj_sql);
      while ($maxcsadj_row = $maxcsadj_result->fetch_assoc())
      {
        $CCIS_ChildSupportReceived_Allowed=$maxcsadj_row['income_adjust_childsupporin'];
      }

//CALCULATE CHILDSUPPORT PAID AMOUNT
      //=IF(monthly_child_support>=VLOOKUP(State_Abrv,Table8,50),VLOOKUP(State_Abrv,Table8,50),monthly_child_support)
      if ($child_support_received_amount>=$CCIS_ChildSupportReceived_Allowed){$CCIS_ChildSupport_Amount=$CCIS_ChildSupportReceived_Allowed;}else{$CCIS_ChildSupport_Amount=$child_support_received_amount;}

//CALCULATE STEP PARENT ADJUSTMENT from CCIS worksheet B33
//=IF(VLOOKUP(State_Abrv,Table8,47)=FALSE,0,
//IF(Step_Parent="y",
//IF(HouseHoldSize<=6,VLOOKUP(CCIS_County_State,#REF!,HouseHoldSize),
//(VLOOKUP(CCIS_County_State,#REF!,6)+(VLOOKUP(CCIS_County_State,#REF!,7)*(HouseHoldSize-6)))),0))
    $StepParentSatus=$_POST['cl_family_stepparent_check'];

    $CCIS_StepParent_Adjustment=0;

//LOOKUP INCOME ADJUSMENT ALLOWED FOR CHILDSUPPORT PAID
    $childsuppadj_sql = "SELECT income_adjust_childsupportpaid FROM state_data WHERE state_init=\"".$cl_stateinits."\";";
    $childsuppadj_result = $dbconn->query($childsuppadj_sql);
      while ($childsuppadj_row = $childsuppadj_result->fetch_assoc())
      {
        $CCIS_ChildSupportPaid_Allowed=$childsuppadj_row['income_adjust_childsupportpaid'];
      }

//REFERENCE CHILDSUPPORT PAID FROM SNAP_FIGURIN
    $ChildSupportPaidAmount=$ChildSupportPaidAmount;

//CALCULATE CHILDSUPPORT PAID ADJUSMENT
      if($CCIS_ChildSupportPaid_Allowed=="True")
      {
        $CCIS_ChildSupportPaid_Amount=$ChildSupportPaidAmount;
      }
      else
      {
      $CCIS_ChildSupportPaid_Amount=0;
      }

//LOOKUP INCOME ADJUSMENT ALLOWED FOR ALIMONY PAID
    $aladj_sql = "SELECT income_adjust_alimonypaid FROM state_data WHERE state_init=\"".$cl_stateinits."\";";
    $aladj_result = $dbconn->query($aladj_sql);
      while ($aladj_row = $aladj_result->fetch_assoc())
      {
        $CCIS_AlimonyPaid_Allowed=$aladj_row['income_adjust_alimonypaid'];
      }

//REFERENCE SPOUSESUPPORT aka alimony PAID FROM TANF_FIGURIN
    $AlimonyPaidAmount=$deduct_spousesupport_amount;

//CALCULATE ALIMONY PAID ADJUSMENT
      if($CCIS_AlimonyPaid_Allowed=="True")
        {
          $CCIS_AlimonyPaid_Amount=$AlimonyPaidAmount;
        }
        else
        {
          $CCIS_AlimonyPaid_Amount=0;
        }

//=(((VLOOKUP(HouseHoldSize,FPIG_Net_Inc_Limit_Table,2)))+(IF(HouseHoldSize>10,FPIG!$E$22*(HouseHoldSize-10),0)))*VLOOKUP(State_Abrv,Table8,52)

//REFERENCE GROSS INCOME MAXIMUM FROM SNAP_FIGURIN
    $Gross_Income_Maximum=$Gross_Income_Maximum;

//LOOKUP CCIS ELIGIBILITY PERCENTAGE FOR TEST 1
    $elip_sql = "SELECT ccis_eligible_test1,ccis_eligible_test2 FROM state_data WHERE state_init=\"".$cl_stateinits."\";";
    $elip_result = $dbconn->query($elip_sql);
      while ($elip_row = $elip_result->fetch_assoc())
        {
          $CCIS_EligibPercent1=(str_replace("%","",$elip_row['ccis_eligible_test1']))*.01;
          $CCIS_EligibPercent2=(str_replace("%","",$elip_row['ccis_eligible_test2']))*.01;
        }

//REFERENCE MOOLA FOR PEOPLE COUNT OVER 10 FROM SNAP_FIGURIN
    $Gross_Add_Dollar=$Gross_Add_Dollar;

//CALCULATE ADDITIONAL AMOUNT TO ADD TO NET INCOME LIMIT aka Gross_Add_Dollar
      if ($people_count>10){$CCIS_AdditionalNet_Amount=$Gross_Add_Dollar*($people_count-10);}else{$CCIS_AdditionalNet_Amount=0;}

//CALCULATE TOTAL ADDITIONAL NET INCOME AMOUNT
    $CCIS_TotalNetIncome_Amount=$fpig100+$CCIS_AdditionalNet_Amount;

    $CCIS_FPIG150Monthly_Amount=$CCIS_TotalNetIncome_Amount*$CCIS_EligibPercent1;
    $CCIS_FPIG150Annual_Amount=$CCIS_FPIG150Monthly_Amount*12;

//CALCULATE THE KIDDOS LESS THAN 13 YEARS OLD AND THEIR AGES
    $CCIS_KidsLessThan13_Check=$_POST['less_than_thirteen_check'];
      if ($CCIS_KidsLessThan13_Check=="Yes")
      {
        $CCIS_TotalAgesLessThan13=0;
        $CCIS_KidsLessThan13_Amount=$_POST['cl_less_than_thirteen'];
        $i=1;
        while ($i<=$CCIS_KidsLessThan13_Amount)
        {
          $which_childyears_post="cl_child".$i."_years";
          $which_childmonths_post="cl_child".$i."_months";
          $this_child_age=$_POST[$which_childyears_post]+$_POST[$which_childmonths_post];
          $CCIS_TotalAgesLessThan13=$CCIS_TotalAgesLessThan13+$this_child_age;
          $i++;
        }
      }
      else
      {
        $CCIS_KidsLessThan13_Amount=0;
      }

//CALCULATE THE KIDDOS FROM 13 TO 18 YEARS OLD THAT ARE DISABLED
      if (isset($_POST['cl_unable']))
      {
        $CCIS_KiddosUnable_Amount=$_POST['cl_unable'];
      }
      else
      {
        $CCIS_KiddosUnable_Amount=0;
      }

//AFTER HOUR WEEKEND HOUR PERCENTAGES FOR NM
      if(strtolower($cl_stateinits)=="mi")
      {
        if (isset($CCIS_MoreHours))
        {
          if (($CCIS_MoreHours<=10)&&($CCIS_MoreHours>0)){$CCIS_AfterHours="5%";}
          if (($CCIS_MoreHours>=11)&&($CCIS_MoreHours<=20)){$CCIS_AfterHours="10%";}
          if ($CCIS_MoreHours>=20){$CCIS_AfterHours="15%";}
        }
      }

//REFERENCE TO CCIS WORKSHEET FOR MAX SUBSIDY PAYMENT FOR 3 KIDS IN TEST
    $CCIS_MaxSubsidy_Amount=1463.05;

//CALCULATE TOTAL VALUE OF ASSETS
      if ($vehicle_equity_allowed==1)
      {
        $CCIS_total_value_assets=$checking_account_bal+$savings_account_bal+$EVvehicle1+$EVothervehicle+$EVpersonal+$EVnonResi+$EVallCountable;
      }
      else
      {
        $CCIS_total_value_assets=$checking_account_bal+$EVothervehicle+$EVpersonal+$EVnonResi+$EVallCountable;
      }

//IGNORE ALL OF THE NM STUFF ABOVE
//THE STUFF BELOW IS REDUNANT BECAUSE I MAY BE DELETING THE STUFF ABOVE TO CLEAN THIS MI SECTION UP
//HOW MANY MICHIGAN CHILDREN UNDER 13 AND WHAT ARE THEIR AGES?
    if (isset($_POST['cl_child_care_location'])){$CCIS_ChildCareLocation=$_POST['cl_child_care_location'];}
    if (isset($_POST['cl_child_care_hours'])){$CCIS_ChildCareHours=$_POST['cl_child_care_hours'];}
    if (isset($_POST['cl_child_care_days'])){$CCIS_ChildCareDays=$_POST['cl_child_care_days'];}

    $CCIS_KidsLessThan13_Check=$_POST['less_than_thirteen_check'];
      if ($CCIS_KidsLessThan13_Check=="Yes")
      {
        $CCIS_KidsLessThan13_Amount=$_POST['cl_less_than_thirteen'];
        //echo "<br>CCIS_KIDSLESSTHAN13_AMOUNT == ".$CCIS_KidsLessThan13_Amount."<br><br>";
        $i=1;
        while ($i<=$CCIS_KidsLessThan13_Amount)
        {
          $which_childyears_post="cl_child".$i."_years";
          $which_childmonths_post="cl_child".$i."_months";
          ${"this_child_age".$i}=$_POST[$which_childyears_post]+($_POST[$which_childmonths_post]/12);
          if (${"this_child_age".$i}<=2.5)
            {
              if ($CCIS_ChildCareLocation=="By licensed center")
              {
                $CCIS_Rate=3.75;
                ${"this_child_subsidy".$i}=$CCIS_Rate*$CCIS_ChildCareHours*$CCIS_ChildCareDays*4;
                //echo "<br>THIS_CHILD_SUBSIDY".$i." == ".${"this_child_subsidy".$i}."<br>";
              }
            }
          if (${"this_child_age".$i}>2.5)
          {
            if ($CCIS_ChildCareLocation=="By licensed center")
            {
              $CCIS_Rate=2.5;
              ${"this_child_subsidy".$i}=$CCIS_Rate*$CCIS_ChildCareHours*$CCIS_ChildCareDays*4;
              //echo "<br>THIS_CHILD_SUBSIDY".$i." == ".${"this_child_subsidy".$i}."<br>";
            }
          }
          $i++;
        }
        $CCIS_SubsidyLessCopay=0;
        $i=1;
        while ($i<=$CCIS_KidsLessThan13_Amount)
        {
          $CCIS_SubsidyLessCopay=$CCIS_SubsidyLessCopay+${"this_child_subsidy".$i};
          $i++;
        }
      }

    $CCIS_row="";
    $x=1;
    while ($x<=$plotpoints)
    {
      ${"this_ccis".$x}=$CCIS_SubsidyLessCopay;
      $CCIS_row = $CCIS_row."<td>".number_format($CCIS_SubsidyLessCopay)."</td>";
      $x++;
/*      else
      {
        ${"this_ccis".$x}="0.00";
        $CCIS_row = $CCIS_row."<td bgcolor=\"lightgray\">".number_format('0')."</td>";
        $x++;
      }*/
    }
  }
?>
