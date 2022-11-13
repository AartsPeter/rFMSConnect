
<?php if ($settings->FooterActive==1){?>
    <footer class="footer <?=$menu_style?> navbar-dark navbar-nav  fixed-bottom d-none d-md-block" >
        <div class="container-fluid">
            <div class="d-flex">
                    <div class="navbar-nav p-2"><a class="btn btn-secondary" href="<?=$us_url_root?>pages/contact.php"><i class="far fa-comments"></i></a></div>
                    <?php if (strlen($settings->FooterText)>0){?>
                    <div class="navbar-brand mr-auto p-0 normal"><i><?php echo $settings->FooterText;?></i></div>
                    <?php } ?>
                    <div class="navbar-nav ml-auto "><a class="nav-link dropdown-item p-0 pl-2" data-toggle="modal" data-target="#modal-about" href="#"> &copy;  Peter Aarts 2017 - 2022 &nbsp</a></div>

            </div>
        </div>
    </footer>
<?php } ?>
    <footer class="footer <?=$menu_style?> fixed-bottom " id="footer_menu">
        <div class="d-flex btn-primary">
            <a href="<?=$us_url_root?>index.php"                class="col btn text-light"><i class="fad fa-2x fa-th fa-fw"></i></a>
        <?php if ($user->data()->driver_id!=''){ ?>
            <a href="<?=$us_url_root?>pages/drivertrips.php"   class="col btn text-light"><i class="fad fa-2x fa-flag fa-fw"></i></a>
        <?php } ?>
            <?php if($settings->messaging==1 ){ ?>
            <a href="<?=$us_url_root?>users/messages.php"       class="col btn text-light"><i class="fad fa-2x fa-comments fa-fw"></i><span class="badge" id="mail_badge">&nbsp&nbsp</span></a>
            <?php }?>
            <a href="<?=$us_url_root?>pages/driverpage.php"     class="col btn text-light"><i class="fad fa-2x fa-user fa-fw"></i></a>
        </div>
    </footer>
    <div class="modal fade " id="myModal" role="dialog">
        <div class="modal-dialog modal-lg modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
      		    <div class="modal-header "><button type="button" class="close" data-dismiss="modal">&times;</button><h4 class="card-title" id="myModalTitle"></h4></div>
      		    <div class="modal-body" id="myModalBody"></div>
      		</div>
      	</div>
    </div>
	<script>
	    new SlimSelect({select: 'select'});
	    setInterval(function(){ CheckNewChats();},5000);
	</script>
	<script src="<?=$us_url_root?>plugins/jQuery/jquery-latest.min.js"></script>
	<script src="<?=$us_url_root?>plugins/bootstrap4/js/bootstrap.bundle.min.js"></script>
	<script src="<?=$us_url_root?>plugins/notiflix/notiflix-3.2.4.min.js"></script>
	<script src="<?=$us_url_root?>plugins/notiflix/notiflix-confirm-aio-3.2.4.min.js"></script>
	<script src="<?=$us_url_root?>plugins/moment/moment.min.js"></script>
	<script src="<?=$us_url_root?>plugins/moment/moment-timezone-with-data-10-year-range.min.js"></script>
	<script src="<?=$us_url_root?>plugins/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
	<script src="<?=$us_url_root?>plugins/gridmaster/jquery.gridstrap.min.js"></script>
	<script src="<?=$us_url_root?>js/rfms.js"></script>
	<script>ToggleCSSFooter(`<?=$settings->FooterActive?>`);</script>
    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-VQ4YQPXFBF"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());

      gtag('config', 'G-VQ4YQPXFBF');
    </script>


