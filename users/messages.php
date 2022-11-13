
<?php
require_once 'init.php';
require_once $abs_us_root.$us_url_root.'users/includes/header.php';
require_once $abs_us_root.$us_url_root.'users/includes/navigation.php';


if (!securePage($_SERVER['PHP_SELF'])){die();}
if($settings->messaging != 1){
  Redirect::to('account.php?err=Messaging+is+disabled');
}
if ($user->data()->avatar==1) {
    $useravatar = '/images/avatars/userid_'.$user->data()->id.'_'.$user->data()->username.'.png';
} else {
    $useravatar = '/images/avatars/avatar.png';
}
?>
	<main role="main">
		<section class="section section-full ">
			<div class="container-fluid">
				<div class="pagina p-0 bg-trans">
					<div class="inner-pagina m--2">
<!--                        <div class="col-12 page-title">Chat messages </div>-->
                        <div class="col-12  p-0">
                            <div class="chat-main-box d-flex">
                                <div class="chat-left-aside col-12 col-md-4 col-xl-3 col-lg-2" id="chat-left-aside">
                                    <div class="open-panel"><i class="ti-angle-right"></i></div>
                                    <div class="chat-left-inner card" >
                                        <div class="chat-header d-flex ">
                                            <div class="px-3"><img src="/images/avatars/avatar_<?=$user->data()->id?>.png" class="img-responsive img-thumbnail " alt=""></div>

                                        </div>
                                        <div class="chat-header-search d-flex">
                                            <select class = "w-100  p-1 px-2" name="searchcontact" onchange="ContactSelected();" placeholder="search contact" id="searchcontact"></select>
                                            <div class="ml-auto">
                                                <button class="ml-2 btn btn-xl btn-primary hide" id="createChatButton" onclick="CreateNewChatThread();"><i class="fas fa-plus"></i></button>
                                            </div>
                                            <div class="btn"><i class="fad fa-search fa-fw"></i></div>
                                        </div>
                                        <div class="slimScrollDiv"  >
                                            <ul class="chatonline style-none pl-0 py-2" id="chatcontactlist"></ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="chat-right-aside col" id="chatcontent">
                                    <div class="row justify-content-center h-100" >
                                        <div class="align-self-center chat_background w-100 h-100 p-5">
                                            <div class="mx-5 px-5 larger">Sent and receive messages with your drivers / planners, fleetmanagers and even workshop</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
			</div>
		</section>

		</main>

    <?php require_once $abs_us_root.$us_url_root.'users/includes/page_footer.php';?>
    <script src="<?=$us_url_root?>js/chat.js"></script>


    <script>
    window.onload=function(){
        ShowChatContacts();
        LoadContacts();
        setInterval(function(){ UpdateChatContacts();},2500);
    }
    new SlimSelect({select: '#searchcontact'	});


    </script>

    <?php require_once $abs_us_root.$us_url_root.'users/includes/html_footer.php'; // currently just the closing /body and /html ?>
