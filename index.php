 <!DOCTYPE html>
 <html lang="en">
 <head>
  <meta charset="UTF-8">
  <link rel="stylesheet" type="text/css" href="css/flexigrid.pack.css" />
  <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
  <script type="text/javascript" src="js/flex.pack.js"></script>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
  <style type="text/css">
  .flexigrid div.fbutton .add {
		background: url(images/add.png) no-repeat center left;
	}

	.flexigrid div.fbutton .delete {
		background: url(images/close.png) no-repeat center left;
	}

	.flexigrid div.fbutton .edit {
		background: url(images/edit.png) no-repeat center left;
	}
  </style>
  <title>Simple Flexigrid example with crud features using JSON</title>
</head>
<body>
  <div class="container">
    <table id="employees" style="display: none"></table>
  </div>
  <div id="add_model" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Add Employee</h4>
            </div>
            <div class="modal-body">
                <form method="post" id="frm_add">
				<input type="hidden" value="add" name="action" id="action">
                  <div class="form-group">
                    <label for="name" class="control-label">Name:</label>
                    <input type="text" class="form-control" id="name" name="name"/>
                  </div>
                  <div class="form-group">
                    <label for="salary" class="control-label">Salary:</label>
                    <input type="text" class="form-control" id="salary" name="salary"/>
                  </div>
				  <div class="form-group">
                    <label for="salary" class="control-label">Age:</label>
                    <input type="text" class="form-control" id="age" name="age"/>
                  </div>
                
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" id="btn_add" class="btn btn-primary">Save</button>
            </div>
			</form>
        </div>
    </div>
</div>
<div id="edit_model" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Edit Employee</h4>
            </div>
            <div class="modal-body">
                <form method="post" id="frm_edit">
				<input type="hidden" value="edit" name="action" id="action">
				<input type="hidden" value="0" name="edit_id" id="edit_id">
                  <div class="form-group">
                    <label for="name" class="control-label">Name:</label>
                    <input type="text" class="form-control" id="edit_name" name="edit_name"/>
                  </div>
                  <div class="form-group">
                    <label for="salary" class="control-label">Salary:</label>
                    <input type="text" class="form-control" id="edit_salary" name="edit_salary"/>
                  </div>
				  <div class="form-group">
                    <label for="salary" class="control-label">Age:</label>
                    <input type="text" class="form-control" id="edit_age" name="edit_age"/>
                  </div>
                
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" id="btn_edit" class="btn btn-primary">Save</button>
            </div>
			</form>
        </div>
    </div>
</div>
</body>
</html>

<script type="text/javascript">
	   $("#employees").flexigrid({
                url : 'response.php',
                dataType : 'json',
				method: 'POST',
                colModel : [ {
                    display : 'ID',
                    name : 'id',
                    width : 90,
                    sortable : true,
                    align : 'center'
                    }, {
                        display : 'Name',
                        name : 'employee_name',
                        width : 120,
                        sortable : true,
                        align : 'left'
                    }, {
                        display : 'Salary',
                        name : 'employee_salary',
                        width : 120,
                        sortable : true,
                        align : 'left'
                    }, {
                        display : 'Age',
                        name : 'employee_age',
                        width : 80,
                        sortable : true,
                        align : 'left'
                    } ],
                buttons : [ {
                    name : 'Add',
                    bclass : 'add',
                    onpress : gridAction
                    }
                    ,
                    {
                        name : 'Edit',
                        bclass : 'edit',
                        onpress : gridAction
                    }
                    ,
                    {
                        name : 'Delete',
                        bclass : 'delete',
                        onpress : gridAction
                    }
                    ,
                    {
                        separator : true
                    } 
                ],
                searchitems : [ {
                    display : 'ID',
                    name : 'id'
                    }, {
                        display : 'Name',
                        name : 'employee_salary',
                        isdefault : true
                } ],
                sortname : "id",
                sortorder : "asc",
                usepager : true,
                title : 'Employees',
                useRp : true,
                rp : 15,
                showTableToggleBtn : true,
				height:'auto',
				striped:true,
                width : 550
            });
			
			function gridAction(com, grid) {
				if (com == 'Add') {
					$('#add_model').modal('show');		
                }else if(com == 'Delete') {
					var conf = confirm('Delete ' + $('.trSelected', grid).length + ' items?');
					alert(conf);
                    if(conf){
                        $.each($('.trSelected', grid),
                            function(key, value){
                                $.post('response.php', { id: value.firstChild.innerText, action:com.toLowerCase()}
                                    , function(){
                                        // when ajax returns (callback), 
										$("#employees").flexReload();
                                });
                        });    
                    }
				} else if (com == 'Edit') {
					$('#edit_model').modal('show');
					if($('.trSelected', grid).length >0) {
						
                        $.each($('.trSelected', grid),
                            function(key, value){
							
                                // collect the data
                                $('#edit_id').val(value.children[0].innerText); // in case we're changing the key
                                $('#edit_name').val(value.children[1].innerText);
                                $('#edit_salary').val(value.children[2].innerText);
                                $('#edit_age').val(value.children[3].innerText);
                        }); 
					} else {
					 alert('Now row selected! First select row, then click edit button');
					}
					
                }
			}
			
			function ajaxAction(action) {
				data = $("#frm_"+action).serializeArray();
				$.ajax({
				  type: "POST",  
				  url: "response.php",  
				  data: data,
				  dataType: "json",       
				  success: function(response)  
				  {
					$('#'+action+'_model').modal('hide');
					$("#employees").flexReload();
				  }   
				});
			}
			
			$( "#btn_add" ).click(function() {
			  ajaxAction('add');
			});
			$( "#btn_edit" ).click(function() {
			  ajaxAction('edit');
			});

</script>