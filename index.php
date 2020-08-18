<?php
	
	
?>
<!doctype html>
<html class="no-js" lang="en">

<head>
<?php include "header_links.php";?>
<style type="text/css">
.pdt_error_class_validate {
    color:#FF0000;
    font-style:italic;
    font-size:15px;
    text-align:left;
    font-weight: bold;
}
</style>
</head>

<body>
<!-- Start Welcome area -->
    <div class="all-content-wrapper">
        
		
        <div class="contacts-area mg-b-15">
		
		
		<div class="row">
		<div class="col-md-12">
			<div class="container-fluid">	
                            <br>
			
			
                
				
				
				<div class="row">
                                 
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <button class="blue new-btn btn btn-primary">New</button>  <br><br> 
						<div class="hpanel hblue contact-panel contact-panel-cs responsive-mg-b-30 col-md-12">
						<table id='userTable' class='display dataTable'>

						  <thead>
							<tr>
							  <th>Name</th>
							  <th>Email</th>
							  <th>City</th>
							  <th>Phone</th>
							  <th>Action</th>
							</tr>
						  </thead>
						
						</table>
							
						</div>
					</div>
                </div>
                                
                                
    <div id="myModal" class="modal">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
<!--                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>-->
                    <h2 class="modal-title" style="color:#FF0000;">Add / Edit User</h2>
                </div>
                <div class="modal-body">
                    <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        
                        <div class="alert alert-success alert-dismissible" role="alert" style="display:none;">
								  <div class="message">Testing</div>
								  <button type="button" class="close" data-dismiss="alert" aria-label="Close" style="position: relative !important; top: -30px !important; right: -35px !important; color: inherit !important;">
									<span aria-hidden="true">&times;</span>
								  </button>
								</div>
                        
                        <div class="hpanel hblue contact-panel contact-panel-cs responsive-mg-b-30">
                            <div class="panel-body custom-panel-jw">
								<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
									 <div class="form-group text-center">
										<h4 class="blue-text">Add / Edit User</h4>
									  </div>
									  <div class="form-group text-center"><br>
										<i class="fa fa-users" aria-hidden="true" style="font-size: 121px;"></i>
									  </div>
									  
									 
								</div>
								<div class="col-lg-8 col-md-8 col-sm-12 col-xs-12">
								
								<form method="post" id="addUser"> 
									  <input type="hidden" class="user_edit">
									  <div class="form-group col-md-4 pl-pr-0">
										<label for="exampleInputEmail1">User Name</label>
									  </div>
									  <div class="form-group col-md-8 pl-pr-0">
										<input type="text" class="form-control name" placeholder="Enter Name" name="name" id="name">
									  </div>
									  <div class="form-group col-md-4 pl-pr-0">
										<label for="exampleInputEmail1">User Email</label>
									  </div>
									  <div class="form-group col-md-8 pl-pr-0">
										<input type="text" class="form-control email" placeholder="Enter Email" name="email" id="email">
									  </div>
									  
									  <div class="form-group col-md-4 pl-pr-0">
										<label for="exampleInputEmail1">User Phone</label>
									  </div>
									  <div class="form-group col-md-8 pl-pr-0">
										<input type="text" class="form-control phone" placeholder="Enter Phone" name="phone" id="phone">
									  </div>
									   
									   <div class="form-group col-md-4 pl-pr-0">
										<label for="exampleInputEmail1">User City</label>
									  </div>
									  <div class="form-group col-md-8 pl-pr-0">
										<input type="text" class="form-control city" placeholder="Enter City" name="city" id="city">
									  </div>
									  
									  <div class="form-group col-md-12">
										<button class="blue cancel-btn btn btn-primary">Cancel</button>&nbsp;&nbsp;<button class="blue save-btn btn btn-primary">Save</button>
									  </div>
									  
								  </form>
								 </div>
                            </div>
                            
                        </div>
                    </div>
					
			
					
                </div>
                </div>
                
            </div>
        </div>
    </div>
				
				
                
            </div>
		</div>
		
		
            
        </div>
		
		
		
		
		

    </div>
    </div>
<script type="text/javascript" src="js/sweetalert2.min.js"></script>  
<script type="text/javascript" src="js/validate.js"></script>  
<script type="text/javascript" src="js/additionalmethod.js"></script>
<script type="text/javascript" src="js/userData.js"></script>
   
</body>



</html>