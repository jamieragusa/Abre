<?php

	/*
	* Copyright (C) 2016-2018 Abre.io Inc.
	*
	* This program is free software: you can redistribute it and/or modify
    * it under the terms of the Affero General Public License version 3
    * as published by the Free Software Foundation.
	*
    * This program is distributed in the hope that it will be useful,
    * but WITHOUT ANY WARRANTY; without even the implied warranty of
    * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    * GNU Affero General Public License for more details.
	*
    * You should have received a copy of the Affero General Public License
    * version 3 along with this program.  If not, see https://www.gnu.org/licenses/agpl-3.0.en.html.
    */

	//Required configuration files
	require_once(dirname(__FILE__) . '/../../core/abre_verification.php');
	require_once(dirname(__FILE__) . '/../../core/abre_functions.php');
	require_once(dirname(__FILE__) . '/../../core/abre_dbconnect.php');

	//Add the course
	$assessment_id=mysqli_real_escape_string($db, $_POST["assessmentIDduplicateid"]);

	if($assessment_id!="")
	{

		//Duplicate the assessment
		$sqllookup2 = "SELECT ID, Owner, Title, Description, Subject, Grade, Level, Verified FROM assessments WHERE ID='$assessment_id'";
		$result3 = $db->query($sqllookup2);
		while($row2 = $result3->fetch_assoc())
		{
			$Assessment_ID=$row2["ID"];
			$Assessment_Owner=$row2["Owner"];
			$Assessment_Title=$row2["Title"];
			$Assessment_Title="$Assessment_Title";
			$Assessment_Description=$row2["Description"];
			$Assessment_Subject=$row2["Subject"];
			$Assessment_Grade=$row2["Grade"];
			$Assessment_Level=$row2["Level"];
			$Assessment_Verified=$row2["Verified"];

			$stmt = $db->stmt_init();
			$sql = "INSERT INTO assessments (Owner, Title, Description, Subject, Grade, Level, Verified, Active) VALUES ('$Assessment_Owner', '$Assessment_Title', '$Assessment_Description', '$Assessment_Subject', '$Assessment_Grade' , '$Assessment_Level', '$Assessment_Verified', '1');";
			$stmt->prepare($sql);
			$stmt->execute();
			$new_assessmentID = $stmt->insert_id;
			$stmt->close();

			//Duplicate the questions
			$sqllookupres = "SELECT Question_Order, Bank_ID, Points, Vendor_ID, Type, Difficulty, Standard FROM assessments_questions WHERE Assessment_ID='$assessment_id'";
			$resultres = $db->query($sqllookupres);
			while($rowres = $resultres->fetch_assoc())
			{
				$Question_Order=$rowres["Question_Order"];
				$Question_Bank_ID=$rowres["Bank_ID"];
				$Question_Points=$rowres["Points"];
				$Question_Vendor_ID=$rowres["Vendor_ID"];
				$Question_Type=$rowres["Type"];
				$Question_Difficulty=$rowres["Difficulty"];
				$Question_Standard=$rowres["Standard"];

				$stmt = $db->stmt_init();
				$sql2 = "INSERT INTO assessments_questions (Assessment_ID, Question_Order, Bank_ID, Points, Vendor_ID, Type, Difficulty, Standard) VALUES ('$new_assessmentID', '$Question_Order', '$Question_Bank_ID', '$Question_Points', '$Question_Vendor_ID', '$Question_Type', '$Question_Difficulty', '$Question_Standard');";
				$stmt->prepare($sql2);
				$stmt->execute();
				$stmt->close();
			}


		}

		//Create session key
		$timedate=time();
		$string=$timedate.$_SESSION['useremail'];
		$sessionid=sha1($string);

		//Create the assessment session
		$stmt = $db->stmt_init();
		$sql = "INSERT INTO assessments_sessions (Owner, Assessment_ID, Session_ID) VALUES ('".$_SESSION['useremail']."', '$new_assessmentID', '$sessionid');";
		$stmt->prepare($sql);
		$stmt->execute();
		$stmt->close();

		//Close database connection
		$db->close();

	}

	echo "The assessment is now active.";
?>