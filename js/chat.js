/*jslint browser: true*/
/*global $, jQuery, alert*/
// definition of Global variables
var $chat=false;
var $contacts=false;
var $activeThread=0;
var $lastThreadChatId=0;
var $activeOnread=[];


function ChatContact(val){
    var $activeth="";
    var $date=ChatDate(val.last_update);
    var $str='';var $new="";var $newBadge="";var $lastMessage='<span class="fal fa-check text-secondary small px-2"></span>';
    var $active="";
    if (parseInt(val.messageUnreadTo)>0){
        $new = 'bold';$newBadge = '<div class="badge badge_on_icon badge-success small"> '+parseInt(val.messageUnreadTo)+' </div>';
        $lastMessage="<div></div>";
    }
    if (parseInt(val.messageUnread) == 0) {
        $lastMessage = '<div class="fas fa-check-double text-primary small px-2"></div>';
    } else {
        $lastMessage = '<span class="fal fa-check text-secondary small px-2"></span>'
    }
    if (val.lastSender!=val.my_id) {$lastMessage="<div></div>";}
    if (val.active == '1' ) { $active=' border-success'; }
    if (val.id == $activeThread ){ $activeth=" active";}
    $str='<li>';
    $str+=' <div onclick="ShowChatContent(`'+val.id+'`,`'+val.chatContactId+'`);ShowChatContacts();" class="chat-contact  '+$activeth+'" id="thread_'+val.id+'">';
    if (val.chatContactAvatar==0){ $str+='     <img src = "https://www.gravatar.com/avatar/39ef0c367e15fcad4bd0a5bc994429e6?s=120&amp;d=mm&amp;r=pg" class = "img-responsive img-thumbnail" id="thread_'+val.id+'_image" alt = "">';}
    else { $str+='     <img src = "/images/avatars/avatar_'+val.chatContactId+'.png" class = "img-responsive img-thumbnail '+$active+'" id="thread_'+val.id+'_image" alt = "">';}
    $str+='     <div class="row">';
    $str+='         <div class="col p-0 '+$new+'">'+val.chatContact+'</div>';
    $str+='         <div class="ml-auto text-right small" id="thread_'+val.id+'_date">'+$date+'</div>';
    $str+='     </div>';
    $str+='     <div class="row">';
    $str+='			<span id="thread_'+val.id+'_messagestatus">'+$lastMessage+'</span>';
    $str+='         <div class="col flow-clip small px-0 '+$new+'" id="thread_'+val.id+'_lastMessage">'+val.lastMessage+'</div>';
    $str+='         <div class= "ml-auto text-right">';
    if (val.pinned=='1'){
        $str+='             <span class="text-primary"><i class="fad fa-fw fa-thumbtack text-primary"></i></span>';
    }
    $str+='             <span class="chat_row_menu onlyhover">';
    $str+='                 <div class="dropdown">';
    $str+='                     <button onmouseover="ShowChatMenu(`'+val.id+'`)"  id="chat-menu-'+val.id+'" class="btn btn-sm " data-toggle="dropdown"><i class="fas fa-chevron-down  text-secondary"></i></button>';
    $str+='                     <ul class="dropdown-menu chat-menu-left" role="menu" aria-labelledby="chat-menu-'+val.id+'" >';
    $str+='                         <li role="presentation"><a class="chat-row-menu-item" tabindex="-1" href="#">Chat archiveren</a></li>';
    $str+='                         <li role="presentation"><a class="chat-row-menu-item" tabindex="-1" href="#">Meldingen dempen</a></li>';
    if (val.pinned=='1'){$str+='                         <li role="presentation"><a class="chat-row-menu-item" tabindex="-1" href="#">Chat losmaken</a></li>';}
    else {$str+='                         <li role="presentation"><a class="chat-row-menu-item" tabindex="-1" href="#">Chat vastmaken</a></li>';}
    $str+='                     </ul>';
    $str+='                 </div>';
    $str+='             </span>';


    $str+='			<span id="thread_'+val.id+'_newbadge">'+$newBadge+'</span>';
    $str+='             </div>';
    $str+='         </div>';
    $str+='     </div>';
    $str+='</div>';
    $str+='</li>';
    return $str;
}
function UpdateChatContact(val){
    var $date=ChatDate(val.last_update);
    var $newBadge="";
    var $lastReadStatus='<span class="fal fa-check text-secondary small px-2"></span>';   
    var $threadImage=document.getElementById("thread_"+val.id+"_image") ;
    var $thread=document.getElementById("thread_"+val.id) ;
    var $threadMessage=document.getElementById("thread_"+val.id+"_lastMessage") ;
    
    if (parseInt(val.messageUnreadTo)>0){
        $newBadge = '<div class="badge badge_on_icon badge-success small">'+parseInt(val.messageUnreadTo)+'</div>';
        $lastMessage="<div></div>";
        $threadMessage.classList.add("bold");}
    else { 
        $threadMessage.classList.remove("bold");}   
    if (parseInt(val.messageUnread) == 0) {
        $lastReadStatus = '<div class="fas fa-check-double text-primary small px-2"></div>';
    } else {
        $lastReadStatus = '<span class="fal fa-check text-secondary small px-2"></span>'
    }
    if (val.lastSender!=val.my_id) {$lastReadStatus="<div></div>";}
    
    if (val.active=='1') { $threadImage.classList.add("border-success");}
    else                 { $threadImage.classList.remove("border-success");}    
    if (val.id==$activeThread)  { $thread.classList.add("active");}
    else                        { $thread.classList.remove("active");}    
    
    UpdateCounter($newBadge,'thread_'+val.id+'_newbadge');
    UpdateCounter(val.lastMessage,'thread_'+val.id+'_lastMessage');
    UpdateCounter($lastReadStatus,'thread_'+val.id+'_messagestatus');
    UpdateCounter($date,'thread_'+val.id+'_date');

    return;
}
function UpdateChatContactOud(val){
    var $activeth="";
    var $date=ChatDate(val.last_update);
    var $str='';var $new="";var $newBadge="";var $lastMessage='<span class="fal fa-check text-secondary small px-1"></span>';
    var $active="";
    if (parseInt(val.messageUnreadTo)>0){
            $new = 'bold';$newBadge = '<div class="badge  badge-pill  badge-success small px-1">'+parseInt(val.messageUnreadTo)+'</div>';
            $lastMessage="<div></div>";
        }
    if (parseInt(val.messageUnread) == 0) {
        $lastMessage = '<div class="fas fa-check-double text-primary small px-1"></div>';
    } else {
        $lastMessage = '<span class="fal fa-check text-secondary small px-1"></span>'
    }
    if (val.lastSender!=val.my_id) {$lastMessage="<div></div>";}

    if (val.active=='1'){$active=' border-success';}
    if (val.id==$activeThread){ $activeth=" active";}
    if (val.chatContactAvatar==0){ $str+='     <img src = "https://www.gravatar.com/avatar/39ef0c367e15fcad4bd0a5bc994429e6?s=120&amp;d=mm&amp;r=pg" class = "img-responsive img-thumbnail" alt = "">';}
    else { $str+='     <img src = "/images/avatars/avatar_'+val.chatContactId+'.png" class = "img-responsive img-thumbnail '+$active+'" alt = "">';}
    $str+='     <div class="row">';
    $str+='         <div class="col p-0 '+$new+'">'+val.chatContact+'</div>';
    $str+='         <div class="ml-auto text-right small">'+$date+'</div>';
    $str+='     </div>';
    $str+='     <div class="row">';
    $str+=$lastMessage;
    $str+='         <div class="col flow-clip small px-0">'+val.lastMessage+'</div>';
    $str+='         <div class= "ml-auto text-right">';
    $str+=                     '                 <span onclick="DeleteChatThread(`'+val.id+'`);" class="pointer small onlyhover" title="delete chat"><i class="fad fa-trash-alt fa-fw " aria-hidden="true"><i></i></i></span>'
    if (val.pinned=='1'){$str+='                 <span class="text-secondary"><i class="fad fa-fw fa-thumbtack small text-primary"></i></span>';}
    $str+=$newBadge;
    $str+='             </div>';
    $str+='         </div>';
    $str+='     </div>';
    return $str;
}
function ShowChatContacts(){
    var $str='';
    $.ajax({
        dataType: "json",
        url:  window.location.origin+'/scripts/GetUserChats.php',
        success: function(data) {
            $.each(data, function(key, val) {
                $str += ChatContact(val);
            });
            $str+='</ul>';
            UpdateCounter($str,'chatcontactlist');
        },
        error:function() {UpdateCounter('<span class="alert alert-danger text-danger">Error reading chats</span>','chatcontactlist');}
    });
}
function UpdateChatContacts(){
    var $newChat = $('#chatcontactlist');
    $.ajax({
        dataType: "json",
        url:  window.location.origin+'/scripts/GetUserChats.php',
        success: function(data) {
            var $str='';
            $.each(data, function(key, val) {
                    if (document.getElementById('thread_'+val.id)) {
//                        UpdateCounter(UpdateChatContact(val),'thread_'+val.id);
                        UpdateChatContact(val);
                        if ($activeThread==val.id){
                            UpdateChatContent(val.chatContactId);
                            UpdateChatRead(val.lastMessageId);
                        }
                    } else { $str += ChatContact(val); }
            });
            $newChat.append($str);
        },
        error:function() {UpdateCounter('<span class="m-5 alert alert-danger text-danger">Error reading chats</span>','chatcontactlist');}
    });
}
function ShowChatContent($messagethread,$contactId){
        $activeThread = $messagethread;
        $lastThreadChatId = 0;
//        var element2 = document.getElementById('thread_' + $messagethread);
 //       element2.classList.add("active");
        var $RefDate = '';
        var $ChatDate = '';
        var $chat = '';
        if(deviceType()=='mobile'){
            var element=document.getElementById("chat-left-aside") ;
            element.classList.add("hide");
        }
        $activeOnread=[];
        $.ajax({
            dataType: "json",
            url: window.location.origin + '/scripts/GetUserChatContent.php?id=' + $messagethread + '&contactId=' + $contactId,
            success: function (data) {
                var $str = '<div class="chat-right-inner">';
                $str += AddChatHeader(data.thread[0]);
                $x = data.thread[0].msg_from;
                UpdateCounter($str, 'chatcontent');
                $.each(data.messages, function (key, val) {
                    $ChatDate = dateFormat(new Date(val.sent_on), "yyyy-mm-dd");
                    if (val.msg_from != $x) { $chat += '<div class="chat-emptyline"></div>';   }
                    if ($ChatDate != $RefDate) {  $RefDate = $ChatDate;  $chat+= AddChatRowDate(val.sent_on); }
                    $chat += AddChatRow(val);
                    $x = val.msg_from;
                    $lastThreadChatId = val.id;
                });
                UpdateCounter($chat, 'chatlist');
                scrollToBottom('chatlist');
            },
            error: function () {
                UpdateCounter('<div class="row col-12"><div class="align-self-center col-3 alert alert-danger text-danger">Error reading chats</div></div>', 'chatcontent');
            }
        });
}
function UpdateChatContent($contactId){
    var $ChatList = $('#chatlist');
    var $RefDate='';var $ChatDate='';$chat='';
    $.ajax({
        dataType: "json",
        url:  window.location.origin+'/scripts/GetUserChatContentNew.php?id='+$activeThread+'&lastMessageId='+$lastThreadChatId+ '&contactId=' + $contactId,
        success: function(data) {
            $.each(data.messages, function(key, val) {
                if (val.msg_from!=$contactId){
                    $chat+='<div class="chat-emptyline"></div>';
                }
                $ChatDate=dateFormat(new Date(val.sent_on), "yyyy-mm-dd");
                $chat += AddChatRow(val);
                $lastThreadChatId=val.id;
                $ChatList.append($chat);
                scrollToBottom('chatlist');
            });
        },
        error:function() {UpdateCounter('<div class="row col-12"><div class="m-5 p-5 col-4 align-self-center col-3 alert alert-danger text-danger">Error reading chats</div></div>','chatcontent');}
    });
}

function AddChatHeader(val){
    var $str='   <div class = "chat-header" id="chatheader">';
    $str+='     <div class = "d-flex">';
    $str+='         <div class = "px-3 chatonline ">';
    if (val.avatar==0){ $str+='     <img src = "/images/avatars/male_avatar.png" class = "img-responsive img-thumbnail" alt = "">';}
    else { $str+='     <img src = "/images/avatars/avatar_'+val.id+'.png" class = "img-responsive img-thumbnail " alt = "">';}
    $str+='         </div>';
    $str+='         <div class="media-item">'+val.chatContact+'</div>';
    $str+='         <div class="ml-auto px-3"><button class="btn" onclick="CloseChatContent();"><i class="fas fa-chevron-left" aria-hidden="true"></i></button></div>';
    $str+='     </div>';
    $str+=' </div>';
    $str+=' <div class = "chat-rbox chat-list-bg mr-2">';
    $str+='    <div class = "slimScrollDiv">'
    $str+='         <ul class= "chat-list p-2 chat-list-bg-trans" id="chatlist"></ul>';
    $str+='    </div>';
    $str+=' </div>';
    $str+=' <div class = "chat-footer chat-footer-send" id="chatfooter">';
    $str+='    <div class = "d-flex">';
    $str+='        <div class = "w-100"><Input class = "form-control " autofocus id="message" 	onfocusin="UpdateTyping(`'+val.id+'`)" onchange="AddNewMessage();" type = "text" placeholder = "Type your message" ></input></div>';
    $str+='        <div><button class="btn" id="emoji-button"></button></div>';
/*    $str+='        <div><button class="btn"><i class="fad fa-paperclip fa-fw"></i></button></div>';*/
    $str+='        <div><button class="btn btn-primary" onclick="AddNewMessage();" id="send-button"><i class="fad fa-paper-plane"></i></button></div>';
    $str+='    </div>';
    $str+='</div>';
    return $str;
    $chat=true;

}

function AddChatRow(val){
    var $str='';var $delete='';
    var $lastMessage='';
    if (val.msg_read==1 ) {
        $lastMessage='<span class = "px-1" id="thread_'+val.id+'_rs"><i class = "fas fa-check-double text-primary small"></i></span>';
    } else {
        $lastMessage='<span class = "px-1" id="thread_'+val.id+'_rs"><i class = "fal fa-check text-secondary small"></i></span>';
    }
    if (val.deleted==1) { val.msg_body='<i><i class="fal fa-ban fa-fw"></i> '+val.msg_body+'</i>';$lastMessage='';}
    if(val.user_id==val.msg_from){
        //place message on left
        $str+=' <li>';
        $str+='     <div class = "chat-content">';
        $str+='         <div class = "speech-bubble-left box bg-default shadow-sm" data-pre-plain-text="['+val.sent_on+'] '+val.chatContact+':">'+val.msg_body
        $str+='             <span class="chat-row-menu onlyhover">';
        $str+='                 <div class="dropdown">';
        $str+='                     <button onclick="ShowChatMenu(`'+val.id+'`)"  id="chat-menu-'+val.id+'" class="btn btn-sm " data-toggle="dropdown"><i class="fas fa-chevron-down  text-secondary"></i></button>';
        $str+='                     <ul class="dropdown-menu chat-menu-left" role="menu" aria-labelledby="chat-menu-'+val.id+'" >';
        $str+='                         <li role="presentation"><a class="chat-row-menu-item" tabindex="-1" href="#">Reply</a></li>';
        $str+='                         <li role="presentation"><a class="chat-row-menu-item" tabindex="-1" href="#">Forward</a></li>';
        $str+='                     </ul>';
        $str+='                 </div>';
        $str+='             </span>';
        $str+='             <div class = "chat-time small">'+dateFormat(new Date(val.sent_on), "HH:MM");
        $str+='             </div>';
        $str+='         </div>';
        $str+='     </div>';
        $str+='</li>';
    } else {
        $str+=' <li class = "reverse" id="chat_'+val.id+'">';
        $str+='     <div class = "chat-content">';
        $str+='         <div class = "speech-bubble-right box text-primary shadow-sm" data-pre-plain-text="['+val.sent_on+'] '+val.chatContact+':">'+val.msg_body;
        $str+='                 <span class="chat-row-menu onlyhover">';
        $str+='                     <div class="dropdown">';
        $str+='                         <button onclick="ShowChatMenu(`'+val.id+'`)"  id="chat-menu-'+val.id+'" class="btn btn-sm " data-toggle="dropdown"><i class="fas fa-chevron-down  text-secondary"></i></button>';
        $str+='                         <ul class="dropdown-menu chat-menu-right" role="menu" aria-labelledby="chat-menu-'+val.id+'" >';
        $str+='                             <li role="presentation"><a class="chat-row-menu-item" tabindex="-1" href="#">Edit/Change</a></li>';
        $str+='                             <li role="presentation"><a class="chat-row-menu-item" tabindex="-1" href="#">Reply</a></li>';
        $str+='                             <li role="presentation"><a class="chat-row-menu-item" tabindex="-1" href="#">Delete</a></li>';
        $str+='                         </ul>';
        $str+='                     </div>';
        $str+='                 </span>';
        $str+='             <div class = "chat-time small">'+dateFormat(new Date(val.sent_on), "HH:MM")+' '+$lastMessage;
        $str+='             </div>';
        $str+='         </div>';
        $str+='     </div>';
        $str+='</li>';
    }
    return $str;
}
function AddNewMessage(){
    var $newChat = $('#chatlist');
    var $str='';
    var $newRow=document.getElementById('message').value;
    if ($newRow!='') {
        document.getElementById('message').value = '';
        $.ajax({
            url: window.location.origin + '/scripts/PostNewChatMessage.php', type: 'POST',
            dataType: 'json', contentType: 'application/json; charset=utf-8',
            data: JSON.stringify({id: $activeThread, message: $newRow}),
            success: function (data) {
                $newChat.append(AddChatRow(data.message[0]));
                $lastThreadChatId = data.message[0].id;
                scrollToBottom('chatlist');
                $activeOnread.push(val.id);
                UpdateChatContacts();
            },
            error: function () {
            }
        });
    }
    return
}
function AddChatRowDate(val){
    var $str='';
    //place message on left
    $str+=' <li class="center">';
    $str+='     <div class = "chat-content-date">';
    $str+='         <div class = "chat-date border"> '+ChatDate(val,'1')+' </div>';
    $str+='     </div>';
    $str+='</li>';

    return $str;
}

function DeleteChatThread($id){
    Notiflix.Confirm.show('Delete',' Do wish to delete this chat ?','Yes','No',	() => {
        $.ajax({url: window.location.origin+'/scripts/PostDeleteChatThread.php',type: 'POST',
            dataType: 'json',	contentType: 'application/json; charset=utf-8',
            data: JSON.stringify({id:$id}),
            success: function (result) {
                var element = document.getElementById("thread_"+$id);
                element.remove();
                Notiflix.Notify.success('Chat has been deleted');
            },
        });
    },	() => {});
}

function LoadContacts(){
    var $select = $('#searchcontact');
    $select.html('');
    $.ajax({
        url: window.location.origin+'/scripts/GetUserChatContacts.php',
        dataType:'JSON',
        success:function(data) {
            $select.append('<option class="DeActiveGroup" disabled value="" selected >search contact</option>');
            $.each(data, function (key, val) {
                $select.append('<option class="DeActiveGroup" value="' + val.id + '" data-thread="'+val.thread+'" >' + val.contact + '</option>');
            })
        }
    });
}
function ContactSelected(){
    var element=document.getElementById("createChatButton") ;
    element.classList.remove("hide");
}
function CreateNewChatThread(){
    var id= document.getElementById('searchcontact').value;
    $.ajax({
        url: window.location.origin + '/scripts/CreateNewUserChat.php?id='+id,
        dataType:'JSON',
        success: function (data) {
            $.each(data, function (key, val) {
                $activeThread=val.id;
                ShowChatContent(val.id, id);
                var element = document.getElementById("createChatButton");
                element.classList.add("hide");
            });
        },
        error : function(e){ Notiflix.Notify.failure('new chat creation failed !');}
    });
}
function UpdateChatRead(id){
    var $str='<i class = "fas fa-check-double text-primary small"></i>';
    UpdateCounter($str,'thread_'+id+'_rs');
}
function CloseChatContent(){
    if(deviceType()=='mobile'){
        var element=document.getElementById("chat-left-aside") ;
        element.classList.remove("hide");
    } else {
        var $str='<div class="row justify-content-center h-100"><div class="align-self-center chat_background w-100 h-100 p-5"><div class="mx-5 px-5 larger">Sent and receive messages with your drivers / planners, fleetmanagers and even workshop</div></div></div>'
        UpdateCounter($str,'chatcontent');
    }
    $activeThread=0;
}
$("#message").keyup(function(event) {
    if (event.keyCode === 13) {
        AddNewMessage();
    }
});

