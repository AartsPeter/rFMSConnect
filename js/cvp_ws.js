function PrepareLogin(){
	var $AUTH=btoa(document.getElementById("CVPusername").value+':'+document.getElementById("CVPpassword").value);
	ShowCVPUserLogin($AUTH);
}

 function ShowCVPUserLogin($Str){
	var $LInfo = $('#CVPLoginProgress');
	ShowSpinnerSmall("CVPLoginProgress");
	$.ajax({
		headers:{  
			"Authorization":"Basic "+$Str,
		},   
		url:"https://connect.daf.com/cle-portal-ws-rest-impl/auth/realm/login",
		dataType:'JSON',
		success:function(data){
			CVPUserInfo(data);
			ShowCVPCustomersTable(data.token);
		}, 
		error:function(){
			$LInfo.html('');
			$LInfo.append('<span class="notice-danger"><strong>CVP authentication failed </strong></span>');
		}
	});		
}
function CVPUserInfo(data){
	var $LInfo = $('#CVPLoginInfo');
	$LInfo.html('');
	$SubScriberArray=data.serviceSubscribers;
	$Userinfo='<div class="col-12 pb-2">';
	$Userinfo+='	<div class="row">';
	$Userinfo+='		<div class="col-4">Login 		</div><div class="col-8 primary"><b>'+ data.login+'</b></div>';
	$Userinfo+='		<div class="col-4">Name /Lastname	</div><div class="col-8 primary"><b>'+ data.userInfo.name+' '+data.userInfo.lastName+'</b></div>';
	$Userinfo+='		<div class="col-4">SessionExpiration	</div><div class="col-8 primary"><b>'+ dateFormat(data.expirationDate, "dd-mm-yyyy HH:MM ")+'</b></div>';
	$Userinfo+='		<div class="col-4">Roles 		</div><div class="col-8 primary"><b>'+ data.roles+'</b></div>';
	$Userinfo+='	</div>';
	$Userinfo+='</div>';
	$LInfo.append($Userinfo);
}
function ShowCVPCustomersTable($Bearer){
	var $CNInfo = $('#CVPCustomerName');
	$CNInfo.html('');
	var $LInfo = $('#CVPLoadCustomerTable');
	ShowSpinner("CVPLoadCustomerTable");
	$CVPData=[];
	$.ajax({
		headers:{  
			"Authorization":"Bearer "+$Bearer
		},
		url:"https://connect.daf.com/cle-portal-ws-rest-impl/ws-portal/servicesubscribers/names",
		dataType:'JSON',
		success:function(data){
			$.each(data, function(key, val){		
				$CVPData.push(val);
			})
			$LInfo.html('');
			ShowCustomerTable($CVPData,$Bearer);

			return $CVPData;
		}, 
		error:function(){
			$LInfo.html('');
			$LInfo.append('<span class="notice notice-danger">Failed to retrieve data from webservice, please contact your application administrator</span>');
		}
	});		
}
function ShowCVPResources($Bearer){
	var $CNInfo = $('#CVPCustomerResources');
	$CNInfo.html('');
	var $LInfo = $('#CVPLoadResourcesTable');
	ShowSpinner("CVPLoadResourcesTable");
	$CVPData=[];
	$.ajax({
		headers:{  
			"Authorization":"Bearer "+$Bearer
		},
		url:"https://connect.daf.com/cle-portal-ws-rest-impl/ws-portal/undefined/usergroup/b15e16d8-fea9-4c19-b10b-11cfcece567c/resources",
		dataType:'JSON',
		success:function(data){
			$.each(data, function(key, val){		
				$CVPData.push(val);
			})
			$LInfo.html('');
			ShowResourcesTable($CVPData,$Bearer);
		}, 
		error:function(){
			$LInfo.html('');
			$LInfo.append('<span class="notice notice-danger">Failed to retrieve data from webservice, please contact your application administrator</span>');
		}
	});		
}
function RequestCVPObjects(searchid,$Bearer){
	var $CNInfo = $('#CVPCustomerResources');
	$CNInfo.html('');
var $LInfo = $('#CVPLoadResourcesTable');
	ShowSpinner("CVPLoadResourcesTable");
	$CVPData=[];
	$.ajax({
		headers:{  
			"Authorization":"Bearer "+$Bearer
		},
		url:"https://connect.daf.com/cle-portal-ws-rest-impl/ws-livefleet/"+searchid+"/initial/info",
		dataType:'JSON',
		success:function(data){
			$.each(data, function(key, val){		
				$CVPData.push(val);
			})
			$LInfo.html('');
			ShowResourcesTable($CVPData,$Bearer);
		}, 
		error:function(){
			$LInfo.html('');
			$LInfo.append('<span class="notice notice-danger">Failed to retrieve data from webservice, please contact your application administrator</span>');
		}
	});		
}
function RequestCVPLiveFleet(searchid,$Bearer){
	var $CNInfo = $('#CVPCustomerResources');
	$CNInfo.html('');
var $LInfo = $('#CVPLoadResourcesTable');
	ShowSpinner("CVPLoadResourcesTable");
	$CVPData=[];
	$.ajax({
		headers:{  
			"Authorization":"Bearer "+$Bearer
		},
		url:"https://connect.daf.com/cle-portal-ws-rest-impl/ws-livefleet/"+searchid+"/initial/info",
		dataType:'JSON',
		success:function(data){
			$.each(data, function(key, val){		
				$CVPData.push(val);
			})
			$LInfo.html('');
			ShowResourcesTable($CVPData,$Bearer);
		}, 
		error:function(){
			$LInfo.html('');
			$LInfo.append('<span class="notice notice-danger">Failed to retrieve data from webservice, please contact your application administrator</span>');
		}
	});		
}
function ShowCustomerTable($Vehicles,$Bearer){
	var $CNInfo = $('#CVPCustomerName');
	table=$('#CVPCustomerTable').DataTable( {
		data:$Vehicles,
		autoWidth: false,
		destroy:true,
		order:[[1,'asc' ]],
		dom: 'Bfrtip',
 		scrollY: '42vh',
		bInfo: true,
        scrollCollapse: true,
		paging: false,
		scrollX: true,
		buttons: {
			buttons: [
				{ extend: 'excel', text: '<i class="far fa-file-excel"></i>',className: 'btn btn-primary btn-xs',  title: 'rFMS_reader_CVPCustomerOverview' },
				{ extend: 'pdf', text: '<i class="far fa-file-pdf"></i>', className: 'btn btn-primary btn-xs', orientation: 'portrait',  pageSize: 'A4', messageTop: 'Customers',title: 'rFMS_reader_CVPCustomerOverview',
				 customize: function(doc) { doc.defaultStyle.fontSize = 8; } },
				{ extend: 'print', text: '<i class="fas fa-print"></i>', className: 'btn btn-primary btn-xs',orientation: 'landscape',  pageSize: 'A4', messageTop: 'Customers',title: 'rFMS_reader_CVPCustomerOverview'},
			]
		},
		columns: [
			{ "data": "id","title":"Id","className":"dt-left","width":"15%","visible":true},
			{ "data": "label","title":"Customer Name","defaultContent": "" , "className":"dt-left"},
			{ "data": "userTrackingEnabled","title":"tracking","defaultContent": "" ,"className":"dt-right","width":"15%"},
			{ "data": "selected","title":"selected","visible":false,"className":"dt-left"},
			]
	} );
   $('#CVPCustomerTable').on('click', 'tbody tr', function (e) {
		e.preventDefault();
		var row = $(this).find('td:nth-child(1)').text();
		var name = $(this).find('td:nth-child(2)').text();
		$(this).closest('tr')
		LoadCVPSubscriptions(row,$Bearer);
		LoadCVPCustomerAccounts(row,$Bearer);
		$CNInfo.html('');
		$CNInfo.append('<div class="row"><div class="col-4">Subscriber</div><div class="col-8 primary"><b>'+	name+'</b></div></div>');
   } );
}
function LoadCVPSubscriptions(searchid,$Bearer){
	var $LInfo = $('#CVPLoadSubscriptionsTable');
	$LInfo.html('');
	UpdateCounter("","CVPLoadSubscriptionsMessage");
	ShowSpinner("CVPLoadSubscriptionsTable");
	$CVPSubData=[];
	UpdateCounter("","Subscriptiondata");
	$.ajax({
		headers:{  
			"Authorization":"Bearer "+$Bearer
		},   
		url: 'https://connect.daf.com/cle-portal-ws-rest-impl/ws-warehouse/catalog/packs?serviceSubscriberId='+searchid,
		dataType:'JSON',
		success:function(data){
			$.each(data, function(key, val){
				if (val.additionals.subscriptionStatus=='SUBSCRIBED'){
					Localdate='';
					if (val.additionals.numberOfSubscribedVehicles==false) {val.additionals.numberOfSubscribedVehicles='';}
					if (val.default==false) {val.default='';}
					if (!!val.additionals.orderDate){
						LocalDate=new Date(val.additionals.orderDate);
						val.additionals.orderDate=dateFormat(val.additionals.orderDate, "dd-mm-yyyy HH:MM ");
					}
					$CVPSubData.push(val);
					
				}
			});
			$LInfo.html('');
			ShowCVPSubscriptionsTable($CVPSubData,searchid,$Bearer);
		},
		error:function(){
		}
	});	
}
function CVPSubscribedObjects(searchid,$Bearer,$pack){
	var $LInfo = $('#CVPLoadSubscriptionsMessage');
	$LInfo.html('');
	ShowSpinner("CVPLoadSubscriptionsMessage");
	var $CVPSubData={'isOrderBased':false,'selectedIds':[]};
	var $orderID="";
	var $selectedIds=[];
	$.ajax({
		headers:{  
			"Authorization":"Bearer "+$Bearer
		},   
		url: 'https://connect.daf.com/cle-portal-ws-rest-impl/ws-warehouse/subscribers/'+searchid+'/history-subscriptions?packs%5B%5D='+$pack+'&page=1&perPage=10000&sort=order&status%5B%5D=SUBSCRIBED',
		dataType:'JSON',
		success:function(data){
			UpdateCounter("","CVPLoadSubscribedObjects");
			$countedObjects= data.connectedObjects.length;
			$.each(data.orders, function(key, val){
				var $sub={'orderId':''+val.id+'','connectedObjectIds':[]};
				var $connectedObjectIds=[];
				$.each(val.statusOrderByCO, function(key, val1){
					$connectedObjectIds.push(val1.connectedObjectId);
				});
				$sub.connectedObjectIds=$connectedObjectIds;
				$selectedIds.push($sub);
			});
			$CVPSubData.selectedIds=$selectedIds;
			UpdateCounter("ready reading data","CVPLoadSubscriptionsMessage");
			if (confirm('Are you sure you want to de-activate the subscriptionpack '+$pack+' for '+ $countedObjects +' vehicles ?')) {
				CVPUnSubscribedObjects(searchid,$Bearer,$pack,$CVPSubData);
			} else {
				$LInfo.html('');
				UpdateCounter("","CVPLoadSubscribedObjects");
				UpdateCounter("","CVPLoadSubscriptionsMessage");
				UpdateCounter("","Subscriptiondata");
				LoadCVPSubscriptions(searchid,$Bearer);				
			}
		
		},
		error:function(){
		}
	});	
}
function CVPUnSubscribedObjects(searchid,$Bearer,$pack,$data){
	var $LInfo = $('#CVPLoadSubscriptionsMessage');
	$LInfo.html('');
	ShowSpinner("CVPLoadSubscriptionsTable");
	$.ajax({
		headers:{  
			"Authorization":"Bearer "+$Bearer
		},  
		type:'PUT',
		url: 'https://connect.daf.com/cle-portal-ws-rest-impl/ws-warehouse/subscribers/'+searchid+'/subscriptions?action=unsubscribe',
		data: JSON.stringify($data),
		contentType: "application/json; charset=utf-8",
		dataType:'JSON',
		success:function(data){
			$LInfo.html('');
			UpdateCounter("","CVPLoadSubscribedObjects");
			UpdateCounter("Subscriptions de-activated","CVPLoadSubscriptionsMessage");
			UpdateCounter("","Subscriptiondata");
			LoadCVPSubscriptions(searchid,$Bearer);
		},
		error:function(){
			$LInfo.html('');
			UpdateCounter("","CVPLoadSubscribedObjects");
			UpdateCounter("Subscriptions de-activation FAILED","CVPLoadSubscriptionsMessage");
			UpdateCounter("","Subscriptiondata");
//			LoadCVPSubscriptions(searchid,$Bearer);
		}
	});	
}
function ShowCVPSubscriptionsTable($Vehicles,searchid,$Bearer){
	$('#Subscriptiondata').DataTable( {
		data:$Vehicles,
		autoWidth: false,
		destroy:true,
		order:[[5,'desc' ]],
		dom: 'Bfrtip',
 		scrollY: '42vh',
		bInfo: true,
        scrollCollapse: true,
		paging: false,
		scrollX: true,
	    buttons: {buttons: [
			{ 	extend: 'excel', 
				className: 'btn btn-primary btn-xs' ,
				filename: 'rFMSReader_Delayed_Vehicles',
			},
			{	extend: 'pdf', 
				className: 'btn btn-primary btn-xs',
				orientation: 'portrait',
                pageSize: 'A4',
				messageTop: 'Delayed vehicles'
			},
			{	extend: 'print', 
				className: 'btn btn-primary btn-xs',	  
				messageTop: 'Delayed vehicles'
			}]
		},
		columns: [
			{ "data": "externalId","title":"Id","defaultContent": "" , "width":"10%","className":"dt-left", "visible":true },
			{ "data": "name","title":"Name","className":"dt-left"},
			{ "data": "type","title":"Type","defaultContent": "" ,"className":"dt-left", "visible":false },
			{ "data": "serviceSubscriberTypes","title":"serviceSubscriberTypes","defaultContent": "" ,"className":"dt-left", "visible":false },
			{ "data": "services","title":"Subscribed","defaultContent": "" ,"className":"dt-left", "visible":false },
			{ "data": "dependencies","title":"dependencies","defaultContent": "" ,"className":"dt-left", "visible":false },
			{ "data": "duration","title":"duration","defaultContent": "" ,"className":"dt-left", "visible":false },
			{ "data": "isLifetimeDuration","title":"isLifetimeDuration","defaultContent": "" ,"className":"dt-left", "visible":false },
			{ "data": "publicationStatus","title":"Status","defaultContent": "" ,"className":"dt-left"},
			{ "data": "shortDescription","title":"shortDescription","defaultContent": "" ,"className":"dt-left", "visible":false },
			{ "data": "longDescription","title":"longDescription","defaultContent": "" ,"className":"dt-left", "visible":false },
			{ "data": "default","title":"default","defaultContent": "" ,"className":"dt-left" ,"width":"8%"},
			{ "data": "price","title":"€","className":"dt-left","width":"5%", "visible":false  },
			{ "data": "additionals.numberOfVehicles","title":"#","defaultContent": "" ,"className":"dt-center","width":"8%" },
			{ "data": "additionals.numberOfSubscribedVehicles","title":"Sub.","defaultContent": "" ,"className":"dt-center bold primary","width":"8%" },
			{ "data": "additionals.subscriptionStatus","title":"status","defaultContent": "" ,"className":"dt-left", "visible":false },
			{ "data": "additionals.orderDate","title":"Last Order","defaultContent": "" ,"className":"dt-left", "visible":false },
			]
	} );
	$('#Subscriptiondata').off('click');  
	$('#Subscriptiondata').on('click', 'tbody tr', function (e) {
		e.preventDefault();
		var $packId = $(this).find('td:nth-child(1)').text();
		$(this).closest('tr')
		CVPSubscribedObjects(searchid,$Bearer,$packId);
	} );
}
function LoadCVPCustomerAccounts(searchid,$Bearer){
	var $LInfo = $('#CVPLoadUserAccountsTable');
	$LInfo.html('');
	ShowSpinner("CVPLoadUserAccountsTable");
	$CVPUsersData=[];
	UpdateCounter("","UserAccountsdata");
	$.ajax({
		headers:{  
			"Authorization":"Bearer "+$Bearer
		},   
		url: 'https://connect.daf.com/cle-portal-ws-rest-impl/auth/admin/client/DAF/users?page=1&perPage=1000&serviceSubscriber='+searchid,
		dataType:'JSON',
		success:function(data){
			LocalDate=new Date();
			LocalDate=dateFormat(LocalDate, "yyyy-mm-dd HH:MM ");	
			$.each(data, function(key, val){
					if (!!val.creationDate){
						val.creationDate=dateFormat(val.creationDate, "dd-mm-yyyy HH:MM ");	}
					if (!!val.lastModificationDate){
						val.lastModificationDate=dateFormat(val.lastModificationDate, "dd-mm-yyyy HH:MM ");	}
					if (!!val.expirationDate){
						val.expirationDate=dateFormat(val.expirationDate, "yyyy-mm-dd HH:MM ");	
						if (val.expirationDate<LocalDate){ 
							val.notused=true;} else {val.notused=false;}
					}
					if (val.objectUserId==undefined) { 
						val.objectUserId=' ';
					} 
					else {
					 	anyString = val.objectUserId;
						val.objectUserId = anyString.substring(0,16);
					} 
					$CVPUsersData.push(val);				
			});
			$LInfo.html('');
			ShowCVPCustomerAccountsTable($CVPUsersData);
		},
		error:function(){
		}
	});	
}
function ShowCVPCustomerAccountsTable($Users){
	$('#UserAccountsdata').DataTable( {
		createdRow: function( row, data, dataIndex){
            if( data.notused ==  true){
                $(row).addClass('delayedrow');
            }
		},
		data:$Users,
		autoWidth: false,
		destroy:true,
		order:[[8,'desc' ]],
		dom: 'Bfrtip',
 		scrollY: '42vh',
		bInfo: true,
        scrollCollapse: true,
		paging: false,
		scrollX: true,
	    buttons: {buttons: [
			{ 	extend: 'excel', 
				className: 'btn btn-primary btn-xs' ,
				filename: 'rFMSReader_DAFConnect_UserAccountOveview',
			},
			{	extend: 'pdf', 
				className: 'btn btn-primary btn-xs',
				orientation: 'portrait',
                pageSize: 'A4',
				messageTop: 'user accounts overview DAF Connect'
			},
			{	extend: 'print', 
				className: 'btn btn-primary btn-xs',	  
				messageTop: 'user accounts overview DAF Connect'
			}]
		},
		columns: [
			{ "data": "name","title":"Name", "visible":true, "width":"7%","className":"dt-left" },
			{ "data": "lastName","title":"Lastname" , "visible":true, "width":"10%","className":"dt-left" },
			{ "data": "id","title":"User","defaultContent": "" ,"className":"dt-left", "visible":true},
			{ "data": "role.id","title":"Role","className":"dt-left"},
			{ "data": "creationDate","title":"creationDate", "visible":false },
			{ "data": "lastModificationDate","title":"lastModificationDate", "visible":false },
			{ "data": "expirationDate","title":"expirationDate" , "visible":false , "width":"10%"},
			{ "data": "objectUserId","title":"Tacho ID" , "visible":true },
			{ "data": "suspended","title":"suspended" , "visible":false },
			{ "data": "notused","title":"Not Used" , "defaultContent": "" ,"className":"dt-left", "visible":false,"width":"4%" },
			{ "data": "civility","title":"civility", "visible":false },
			{ "data": "userPreferences.language","title":"","defaultContent": "" ,"className":"dt-left", "visible":false,"width":"3%" },
			{ "data": "userPreferences.timezone","title":"timezone","defaultContent": "" ,"className":"dt-left", "visible":false,"width":"10%" },
			{ "data": "userPreferences.currency","title":"currency","defaultContent": "" ,"className":"dt-left", "visible":false,"width":"5%" },
			{ "data": "userPreferences.unit","title":"unit","defaultContent": "" ,"className":"dt-left", "visible":false,"width":"4%" },
			{ "data": "userPreferences.dateFormat","title":"date Format","defaultContent": "" ,"className":"dt-left", "visible":false,"width":"8%" },
			{ "data": "userPreferences.vehicleDisplay","title":"vehicle Display","defaultContent": "" ,"className":"dt-left", "visible":false,"width":"6%" },
			{ "data": "mainServiceSubscriber.userTracking","title":"user Tracking","defaultContent": "" ,"className":"dt-left", "visible":false,"width":"5%" },
			]
	} );
}
