                                    <div class="vehicleList">
                                        <form class="col-12" action="admin.php#list-item-9" name="pdcsettings" method="post">
                                            <div class="row">
                                                <div class="form-group col-12 col-md-3">
                                                    <div class="custom-control custom-switch custom-switch-sm">
                                                        <input type='checkbox' class="custom-control-input" id='pdc_enabled' name='pdc_enabled' <?php echo ($settings->pdc_enabled==1 ? 'checked' : '');?>   >
                                                        <label class="custom-control-label" for="pdc_enabled">PDC policy enforcing </label>
                                                    </div>
                                                    <small>To enable the policy enforcement</small>
                                                </div>
                                                <div class="form-group col-12 col-md-3">
                                                    <div class="custom-control custom-switch custom-switch-sm">
                                                        <input type='checkbox' class="custom-control-input" id='pdc_reporting' name='pdc_reporting' <?php echo ($settings->pdc_reporting==1 ? 'checked' : '');?>   >
                                                        <label class="custom-control-label" for="pdc_reporting">PDC Reporting </label>
                                                    </div>
                                                    <small>When policy is enforced, report on result to Fleetmanager</small>
                                                </div>
                                                <div class="form-group col-12 col-md-3">
                                                    <div class="custom-control custom-switch custom-switch-sm">
                                                        <input type='checkbox' class="custom-control-input disabled" id='pdc_autoprocess' name='pdc_autoprocess' disabled <?php echo ($settings->pdc_reporting==1 ? 'checked' : '');?>   >
                                                        <label class="custom-control-label" for="pdc_autoprocess">Auto Process </label>
                                                    </div>
                                                    <small>Automatically process and register the checks, when disabled Fleetmanager needs to validate all incoming PDC`s</small>
                                                </div>
                                            </div>
                                            <div class="row">
                                                 <div class="col-12 col-md-3 pt-2">
                                                    <input type="hidden" name="csrf" value="<?=Token::generate();?>" />
                                                    <input class='btn btn-primary ' type='submit' name="pdcsettings" value='Save Site Settings' />
                                                 </div>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="mt-4 d-none d-sm-block">
                                        <div class="nav-tabs-custom"  id="PDCTabs">
                                            <ul class="nav nav-tabs pl-3" >
                                                <li class="nav-item"> <a class="nav-link active" href="#PDCTAB1" data-toggle="tab" onclick="ShowPDCTemplates();">Templates </a></li>
                                                <li class="nav-item"> <a class="nav-link" href="#PDCTAB2" data-toggle="tab" onclick="ShowPDCCat();">Categories</a></li>
                                                <li class="nav-item"> <a class="nav-link" href="#PDCTAB3" data-toggle="tab" onclick="ShowPDCSubCat();">SubCategories</a></li>
                                                <li class="nav-item"> <a class="nav-link" href="#PDCTAB4" data-toggle="tab" onclick="ShowPDCCheckItems();">Check Items</a></li>
                                                <li class="nav-item"> <a class="nav-link" href="#PDCTAB5" data-toggle="tab" onclick="ShowPDCDamageItems();">Damage Items </a></li>
                                                <li class="nav-item"> <a class="nav-link" href="#PDCTAB7" data-toggle="tab" onclick="ShowPDCPolicies();">Policies</a></li>
                                            </ul>
                                            <div class="tab-content card border p-3">
                                                <div class="tab-pane active" id="PDCTAB1" role="tabpanel"> <table class="display table noWrap" id="tablePDCTemp"></table>   </div>
                                                <div class="tab-pane" id="PDCTAB2" role="tabpanel">  <table class="display table noWrap" id="tablePDCC"></table>            </div>
                                                <div class="tab-pane" id="PDCTAB3" role="tabpanel">  <table class="display table noWrap" id="tablePDCSC"></table>           </div>                                                
                                                <div class="tab-pane" id="PDCTAB4" role="tabpanel">  <table class="display table noWrap" id="tablePDCCI"></table>           </div>
                                                <div class="tab-pane" id="PDCTAB5" role="tabpanel">  <table class="display table noWrap" id="tablePDCDI"></table>           </div>
                                                <div class="tab-pane" id="PDCTAB7" role="tabpanel">  <table class="display table noWrap" id="tablePDCP"></table>            </div>
                                            </div>
                                        </div>
                                    </div>


