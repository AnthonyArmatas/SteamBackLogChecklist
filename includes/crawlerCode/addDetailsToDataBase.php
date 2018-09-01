<?php

	function doesEntryExist($db,$tableName,$entryName){
		$tableExistanceCheck = $db->prepare("SELECT * FROM $tableName where name = ?");
		
		try{
				//Sets the query
				$tableExistanceCheck->bindParam(1, $entryName);	
				//Runs the query			
				$tableExistanceCheck->execute();
				if($tableExistanceCheck->rowCount() > 0){
					//print_r('The row ' . $entryName . ' already exists' );
					//echo '<br/>';	
				}else{
					insertIntoDB($db,$tableName,$entryName);
					//print_r($entryName . ' Instered into the table' );					
					//echo '<br/>';
				}
				
			}
			catch(PDOException $e){
				handle_sql_errors($tableExistanceCheck, $e->getMessage());
			}
	}

	function insertIntoDB($db,$tableName,$entryName){

		$insertDetailsToTable = $db->prepare("insert into $tableName values('',?)");
		$insertDetailsToTable->bindParam(1, $entryName);	
		$insertDetailsToTable->execute();					
	}