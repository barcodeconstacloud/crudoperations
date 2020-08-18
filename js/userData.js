function GetXmlHttpObject() {
    var xmlHttp=null;
    try
    {
    // Firefox, Opera 8.0+, Safari
            xmlHttp=new XMLHttpRequest();
    }
    catch (e)
    {
    // Internet Explorer
            try
            {
                    xmlHttp=new ActiveXObject("Msxml2.XMLHTTP");
            }
            catch (e)
            {
                    xmlHttp=new ActiveXObject("Microsoft.XMLHTTP");
            }
    }
    return xmlHttp;
}

$(document).ready(function(){
    
        function alignModal(){
        var modalDialog = $(this).find(".modal-dialog");
        
        // Applying the top margin on modal dialog to align it vertically center
        modalDialog.css("margin-top", Math.max(0, ($(window).height() - modalDialog.height()) / 2));
        }
    // Align modal when it is displayed
        $(".modal").on("shown.bs.modal", alignModal);

        // Align modal when user resize the window
        $(window).on("resize", function(){
            $(".modal:visible").each(alignModal);
        });   
        
        //$("#myModal").modal({backdrop: 'static', keyboard: false}, 'show');
        
        $(".new-btn").click(function(e){
            $("#myModal").modal({backdrop: 'static', keyboard: false}, 'show');
        });
        
        $(".cancel-btn").click(function(e){
            $("#myModal").modal('hide');
        });
        
	getUserList();
	$(".save-btn").click(function(e){
		e.preventDefault();
		if ($('#addUser').valid()) {
			if($(this).text() == "Save") {
				addUser("Add");
			} else {
				addUser("Update");	
			}
		}
	});
	if(document.getElementById("alert")!=null) {
		setTimeout(function(){ $("#alert").fadeOut(); }, 3000);
	}
	
	
	
	$(document).on("click", ".table-edit", function() {
		var userId = $(this).attr("data-id");
		editUser(userId);
	});
	
	$(document).on("click", ".table-delete", function() {
		var userId = $(this).attr("data-id");
		deleteUser(userId);
		
	});
	
	$.validator.addMethod('alphanumericformat', function(value, element, param)
    {
        var _URL = window.URL;
        var  pattern=/^[A-z a-z.]+$/;
        var $el=$(element);
        return $el.val().match(pattern);          
    });
	
});

$("#addUser").validate
({
        rules: 
        {
            name:
            {
                required: true, 
                alphanumericformat: true
            },
            email:
            {        
                required: true,
                email: true
            },
            phone: {
                required: true,
                number: true,
                minlength:10,
                maxlength:10
            },
            city: {
                required: true,
                alphanumericformat: true
            }
        },
        messages: 
        {
            name: 
            {
                required: "Please enter user name",
                alphanumericformat: "Please enter user name in characters only"                        
            },
            email:
            {        
                required: "Please enter email",
                email: "Please enter email in email format"
            }, 
            phone: {
                required: "Please enter contact number",
                number: "Please enter contact number in numbers only",
                minlength: "Please enter contact number in 10 digits only",
                maxlength: "Please enter contact number in 10 digits only"
        },
            city: {
                required: "Please enter city",
                alphanumericformat: "Please enter city name in characters only"
                    
            }
			
        },
        errorElement: 'span',
        errorElementClass: 'pdt_error_class_validate',
        errorClass: 'pdt_error_class_validate',
        errorPlacement: function(error, element) {},
        highlight: function(element, errorClass, validClass) {
                $(element).addClass(this.settings.errorElementClass).removeClass(errorClass);
        },
        unhighlight: function(element, errorClass, validClass) {
                $(element).removeClass(this.settings.errorElementClass).removeClass(errorClass);
        },
        onkeyup: false,
        onclick: false,
        onfocusout: false,
        errorPlacement: function (error, element) {  
			if ( element.is(":radio") ||  element.is(":checkbox")) 
			{
				error.insertAfter( element.parents('.row') );
			} else {
				error.insertAfter(element); 
			}
			error.fadeOut(5000, function() { $(this).remove(); });
       }
    
});

function addUser(command) {
	//alert("dologin");
	var promiseObj = new Promise(function(resolve, reject) {
		client_add_update=GetXmlHttpObject();
		if (client_add_update==null) {
			alert ("Your browser does not support AJAX!");
			return;
		}
		var url = "getUserData.php";
		var message = "";
		var formData = new FormData();
		formData.append("cmd",command);
		formData.append("UserID",$(".user_edit").val());
        formData.append("Name",$(".name").val());
        formData.append("Email",$(".email").val());
        formData.append("Phone",$(".phone").val());
        formData.append("City",$(".city").val());
		
        client_add_update.open("POST", url, true);
        client_add_update.setRequestHeader("enctype", "multipart/form-data");
        client_add_update.send(formData);
        $(".save-btn").attr("disabled", true);
        $(".save-btn").text("");
        $(".save-btn").html('<span class="spinner-border spinner-border-sm"></span>&nbsp;Processing...');
        client_add_update.onreadystatechange = function() {
            if (client_add_update.readyState === 4) {
                if (client_add_update.status === 200) {
					
					//alert(client_login.responseText);
                    response_add_update=JSON.parse(client_add_update.responseText);
					//alert(response_login.message);
					if(response_add_update.errors == false && response_add_update.success == true) {
						if(command == "Add") {
							message = "User added successfully";
							
						} else {
							message = "User updated successfully";	
						}
						
						$(".save-btn").html("");
						$(".save-btn").text("Save");
						$(".message").text(message);
						$(".alert").removeClass("alert-danger");
						$(".alert").removeClass("alert-success");
						$(".alert").addClass("alert-success");
						$(".close").hide();
						$(".alert").fadeIn().fadeOut(4000);
						$(".user_edit").val("")
						$(".name").val("");
						$(".email").val("");
						$(".phone").val("");
						$(".city").val("");
						$('html, body').animate({scrollTop: '0px'}, 800);
                                                setTimeout(function(){ $("#myModal").modal('hide'); $('#userTable').DataTable().ajax.reload(null, false); $(".save-btn").removeAttr("disabled"); }, 4000);
                                                
						
					} else if(response_add_update.errors == false && response_add_update.success == false) {
						$(".save-btn").removeAttr("disabled");
						$(".save-btn").html("");
						if(command == "Add") {
							$(".save-btn").text("Save");
						} else {
							$(".save-btn").text("Update");
						}
						
						$(".message").text(response_add_update.message);
						$(".alert").removeClass("alert-danger");
						$(".alert").removeClass("alert-success");
						$(".alert").addClass("alert-danger");
						//$(".close").show();
						$(".alert").fadeIn().fadeOut(4000);
						if(response_add_update.message == "No Changes Done") {
							
                                                        setTimeout(function(){ $("#myModal").modal('hide'); $('#userTable').DataTable().ajax.reload(null, false);
                                                        
                                                        $(".user_edit").val("")
							$(".name").val("");
							$(".email").val("");
							$(".phone").val("");
							$(".city").val("");
							
							$(".save-btn").text("Save");
                                                        
                                                        }, 4000);
							
						}
						$('html, body').animate({scrollTop: '0px'}, 800);
					} else {
						//alert("else");
						//alert(response_login.errorData[0].booking_user_name);
						$(".save-btn").removeAttr("disabled");
						$(".save-btn").html("");
						if(command == "Add") {
							$(".save-btn").text("Save");
						} else {
							$(".save-btn").text("Update");
						}
						$.each(response_add_update.errorData,function(key, value){
							//alert(key);								 
                            var error = '<span id='+key+'-error class="pdt_error_class_validate">'+value+'</span>';
                            $(error).insertAfter("."+key);
                            $("#"+key+"-error").fadeOut(5000, function() { $(this).remove(); $("."+key).parent().removeClass("pdt_error_class_validate"); });
                        });		
					}
				}
			}
		}
	});
}

function editUser(userId) {
	//alert(userId);
	var promiseObj = new Promise(function(resolve, reject) {
		client_edit=GetXmlHttpObject();
		if (client_edit==null) {
			alert ("Your browser does not support AJAX!");
			return;
		}
		var url = "getUserData.php";
		var message = "";
		var formData = new FormData();
		formData.append("cmd","Edit");
		formData.append("userId",userId);
        client_edit.open("POST", url, true);
		client_edit.setRequestHeader("enctype", "multipart/form-data");
		client_edit.send(formData);
		client_edit.onreadystatechange = function() {
            if (client_edit.readyState === 4) {
                if (client_edit.status === 200) {
					
					//alert(client_login.responseText);
                    response_edit=JSON.parse(client_edit.responseText);
					//alert(response_login.message);
					if(response_edit.errors == false && response_edit.success == true) {
						$(".save-btn").text("Update");
						$(".name").val(response_edit.data.Name);
						$(".email").val(response_edit.data.Email);
						$(".phone").val(response_edit.data.Phone);
						$(".city").val(response_edit.data.City);
						
						$(".user_edit").val(userId);
						$('html, body').animate({scrollTop: '0px'}, 800);
                                                $("#myModal").modal({backdrop: 'static', keyboard: false}, 'show');
						$('#userTable').DataTable().ajax.reload(null, false);	
						
					} else if(response_edit.errors == false && response_edit.success == false) {
						$(".message").text(response_edit.message);
						$(".alert").removeClass("alert-danger");
						$(".alert").removeClass("alert-success");
						$(".alert").addClass("alert-danger");
						//$(".close").show();
						$(".alert").fadeIn().fadeOut(2000);
					} 
				}
			}
		}
	});	
}


function deleteUser(userId) {
	swal({
    title: '<font style="color:red;font-weight:bold;">Are you sure, you want to delete this user?</font>',
    text: "<font style='font-size:17px;'>You won't be able to revert this!</font>",
    type: 'warning',
    showCancelButton: true,
    confirmButtonClass: "btn-danger",
    cancelButtonColor: "#CCCCCC",
    confirmButtonText: 'Yes, delete it!',
    showLoaderOnConfirm: true,
    allowOutsideClick: false     
    }).then(function()
    {
		var url = "getUserData.php";
		client_delete=GetXmlHttpObject();
        if (client_delete==null)
        {
              alert ("Your browser does not support AJAX!");
              return;
        }
	   var formData = new FormData();
	   formData.append("userId",userId);
	   
	   formData.append("cmd","Delete");
	   client_delete.open("POST", url, true);
	   client_delete.setRequestHeader("enctype", "multipart/form-data");
	   client_delete.send(formData);
	   client_delete.onreadystatechange = function() {
		if (client_delete.readyState === 4) {
        	if (client_delete.status === 200) {
				response_delete=JSON.parse(client_delete.responseText);
				if(response_delete.errors === false) {
					swal
					({
						title: '<font style="color:green;font-weight:bold;">Record deleted successfully</font>',
						text: "<font style='font-size:17px;'>"+response_delete.message+"</font>",
						type: 'success',
						confirmButtonClass: "btn-success",
						showLoaderOnConfirm: true,
						allowOutsideClick: false    
				
					}).then(function()
					{
						$('#userTable').DataTable().ajax.reload(null, false);				
					});
				} else {
					swal
					({
						title: '<font style="color:red;font-weight:bold;">Error</font>',
						text: "<font style='font-size:17px;'>"+response_delete.message+"</font>",
						type: 'error',
						confirmButtonClass: "btn-success",
						showLoaderOnConfirm: true,
						allowOutsideClick: false    
				
					}).then(function()
					{
						$('#userTable').DataTable().ajax.reload(null, false);
					});	
				}
			}
		}
	  }
	}, function(dismiss)
    {
        
    });
}

function getUserList() {
      $('#userTable').DataTable({
      'processing': true,
      'serverSide': true,
      'serverMethod': 'post',
      'ajax': {
          'url':'getUserData.php',
                "data": function ( d ) {
                d.cmd = "List";
                d.UserID = $(".user_edit").val();
                // d.custom = $('#myInput').val();
                // etc
            }
      },
      'columns': [
         { data: 'Name' },
         { data: 'Email' },
         { data: 'City' },
         { data: 'Phone' },
		 /* EDIT */ {
            mRender: function (data, type, row) {
				if(row["UserID"] !== $(".user_edit").val()) {
				//alert(row["UserID"]);
                	return '<a class="table-edit" data-id="' + row["UserID"] + '" style="color:blue; cursor:pointer;">Edit</a>&nbsp;|&nbsp;<a class="table-delete" data-id="' + row["UserID"] + '" style="color:blue; cursor:pointer;">Delete</a>';
				} else {
					return '---';
				}
            }
        },
         
      ]
   });
}


