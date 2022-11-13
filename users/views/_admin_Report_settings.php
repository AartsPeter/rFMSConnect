                                <form class="col-12" action="admin.php#list-item-8" name="reportsettings" method="post">
                                    <div class="row">
                                        <div class="form-group col-12 col-xl-6 ">
                                            <label class="primary" for="report_description"><?=lang("ADMREP_REP_header","");?></label>
                                            <input type="text" class="form-control" name="report_description" id="report_description" value="<?=$settings->report_description?>">
                                        </div>
                                        <div class="form-group col-12 col-xl-2 ">
                                            <label class="primary" for="report_logo"><?=lang("ADMREP_REP_logo","");?></label>
                                            <input type="text" class="form-control" name="report_logo" id="report_logo" value="<?=$settings->report_logo?>">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-12 col-md-3">
                                            <div class="custom-control custom-switch custom-switch-sm">
                                                <input type='checkbox' class="custom-control-input" id='reporting_enabled' name='reporting_enabled' <?php echo ($settings->reporting_enabled==1 ? 'checked' : '');?>   >
                                                <label class="custom-control-label" for="reporting_enabled"><?=lang("ADMREP_SW_Reporting","");?></label>
                                            </div>
                                            <small><?=lang("ADMREP_SW_Report_info","");?></small>
                                        </div>
                                        <div class="form-group col-12 col-md-3">
                                            <div class="custom-control custom-switch custom-switch-sm">
                                                <input type='checkbox' class="custom-control-input" id='reporting_adaptiveDates' name='reporting_adaptiveDates' <?php echo ($settings->reporting_adaptiveDates==1 ? 'checked' : '');?>   >
                                                <label class="custom-control-label" for="reporting_adaptiveDates"><?=lang("ADMREP_SW_Adaptive","");?></label>
                                            </div>
                                            <small><?=lang("ADMREP_SW_Adapt_info","");?></small>
                                        </div>
                                        <div class="col-12 col-md-3 pt-2 ml-auto">
                                            <input type="hidden" name="csrf" value="<?=Token::generate();?>" />
                                            <input class='btn btn-primary' type='submit' name="reportsettings" value='<?=lang("ADMREP_Report_Save","");?>' />
                                        </div>
                                    </div>
                                </form>
                                <div class="row d-none d-sm-block">
                                    <div class="col-12">
                                        <div class="nav-tabs-custom"  id="LatestTabs">
                                            <ul class="nav nav-tabs pl-3" >
                                                <li class="nav-item"> <a class="nav-link active" href="#TAB1" data-toggle="tab" onclick="ShowReportTypes();"><?=lang("ADMREP_TABTypes","");?></a></li>
                                                <li class="nav-item"> <a class="nav-link" href="#TAB2" data-toggle="tab" onclick="ShowReportQueries();"><?=lang("ADMREP_TABQueries","");?></a></li>
                                                <li class="nav-item"> <a class="nav-link" href="#TAB3" data-toggle="tab" onclick="ShowReportTable();"><?=lang("ADMREP_TABPlanning","");?></a></li>
                                                <li class="nav-item"> <a class="nav-link " href="#TAB4" data-toggle="tab"><?=lang("ADMREP_TABHelp","");?></a></li>
                                            </ul>
                                            <div class="tab-content card border p-3">
                                                <div class="tab-pane active" id="TAB1" role="tabpanel">
                                                    <div class="card-body px-0"><?=lang("ADMREP_Types_info","");?></div>
                                                    <table class="display table noWrap" id="tableReporting"></table>
                                                </div>
                                                <div class="tab-pane" id="TAB2" role="tabpanel">        <?=lang("ADMREP_Queries_info","");?><table class="display table noWrap" id="tableReportQueries"></table>    </div>
                                                <div class="tab-pane" id="TAB3" role="tabpanel">        <?=lang("ADMREP_Planning_info","");?><table class="display table noWrap" id="tableReport"></table>           </div>
                                                <div class="tab-pane" id="TAB4" role="tabpanel">
                                                    <div class="col-12 ">
                                                   This page will contain following settings
                                                    <ul>
                                                        <li>Reporting : standaard periode / static overnemen </li>

                                                        <li><b>Manage report planning</b></li>
                                                        <ul>
                                                            <li>define your personal schedule name and description</li>
                                                            <li>reports are always personal</li>
                                                            <li>reports can have a few CC mailadresses</li>
                                                            <li>set frequency of report creation</li>
                                                            <li>reports are always limited to 1 vehicle group</li>
                                                            <li>reports are always limited to 1 DNS-domain (SAAS)</li>
                                                        </ul>
                                                        <li>all report activities are logged</li>

                                                    </ul>
                                                    </div>

                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                </div>
