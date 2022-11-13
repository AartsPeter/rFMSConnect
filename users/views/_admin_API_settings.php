                                <div class="row mt-4">
                                    <div class="col-12">
                                        <div class="nav-tabs-custom"  id="APITabs">
                                            <ul class="nav nav-tabs pl-3" >
                                                <li class="nav-item"> <a class="nav-link active" href="#APITAB1" data-toggle="tab" onclick="ShowAPIScheduler();">Scheduler </a></li>
                                                <li class="nav-item"> <a class="nav-link" href="#APITAB2" data-toggle="tab" onclick="ShowAPITable();">Collector</a></li>
                                                <li class="nav-item"> <a class="nav-link" href="#APITAB3" data-toggle="tab" onclick="ShowAPIScripts();">Script Type</a></li>
                                                <li class="nav-item"> <a class="nav-link" href="#APITAB4" data-toggle="tab" onclick="ShowAPITypes();">API_Type</a></li>                                                                                                
                                            </ul>
                                            <div class="tab-content card  p-3">
                                                <div class="tab-pane active" id="APITAB1" role="tabpanel"> <table class="display table responsive noWrap" id="APIScheduleTable"></table>   </div>
                                                <div class="tab-pane" id="APITAB2" role="tabpanel">  <table class="display table responsive noWrap" id="tableAPI"></table>            </div>
                                                <div class="tab-pane" id="APITAB3" role="tabpanel">  <table class="display table responsive noWrap" id="tableAPIScripts"></table>           </div>
                                                <div class="tab-pane" id="APITAB4" role="tabpanel">  <table class="display table responsive noWrap" id="tableAPITypes"></table>           </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

