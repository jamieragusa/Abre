<?php
	
	/*
	* Copyright 2015 Hamilton City School District	
	* 		
	* This program is free software: you can redistribute it and/or modify
    * it under the terms of the GNU General Public License as published by
    * the Free Software Foundation, either version 3 of the License, or
    * (at your option) any later version.
	* 
    * This program is distributed in the hope that it will be useful,
    * but WITHOUT ANY WARRANTY; without even the implied warranty of
    * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    * GNU General Public License for more details.
	* 
    * You should have received a copy of the GNU General Public License
    * along with this program.  If not, see <http://www.gnu.org/licenses/>.
    */
	
	//Required configuration files
	require(dirname(__FILE__) . '/../../configuration.php'); 
	require_once(dirname(__FILE__) . '/../../core/abre_verification.php');
	require_once('permissions.php');
	
	//Show the searcg and last 10 updates
	if($pageaccess==1)
	{
		
					
		echo "<div id='archivedemployees'>"; include "archived.php"; echo "</div>";
		
		
		?>
												
			<script>
				
				$(function() {
														
					$("#myTable").tablesorter(); 
														
					//Restore the User
					$('#archivedemployees').on('click','.restoreuser',function(){
						var address = $(this).find("a").attr("href");
						$.ajax({
							type: 'POST',
							url: address,
							data: '',
						})
															
						//Show the notification
						.done(function(response) {
							
							$( "#archivedemployees" ).load( "modules/directory/archived.php", function() {
																	
							//Register MDL Components
							mdlregister();
																	
							var notification = document.querySelector('.mdl-js-snackbar');
							var data = { message: response };
							notification.MaterialSnackbar.showSnackbar(data);	
						});	
						})
					});
														
					//Permanently Delete User
					$('#archivedemployees').on('click','.deleteuser',function()
					{
						var result = confirm("Want to permanently delete this user?");
						if (result) 
						{
							var address = $(this).find("a").attr("href");
							$.ajax({
								type: 'POST',
								url: address,
								data: '',
							})
																
							//Show the notification
							.done(function(response) {
								$( "#archivedemployees" ).load( "modules/directory/archived.php", function() {
																		
								//Register MDL Components
								mdlregister();
																		
								var notification = document.querySelector('.mdl-js-snackbar');
								var data = { message: response };
								notification.MaterialSnackbar.showSnackbar(data);		
							});
						})
						}
					});
														
				});
			</script>
	<?php
	
	}
	
?>