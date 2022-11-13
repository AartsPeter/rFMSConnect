

    <div class="row">
        <div class="col-12 col-md-4 col-xl-3 mb-3">
            <div class="card ">
                <div class="card-header">Update Vehicle/Group relation</div>
                <div class="card-body">
                    <div class="import pt-3 ">upload <b> CSV-file</b> to maintain vehicle group relation (max 100.000 vehicles)</div>
                    <form class="row d-flex pt-3" action="<?=$us_url_root?>import/famreport.php" method="post" name="upload_excel" enctype="multipart/form-data">
                        <div class="col-12 form-group ">
                            <input type="file" name="file" id="famreport" class="col-12 p-0">
                        </div>
                        <div class="col-12 form-group ml-auto ">
                            <button type="submit" id="submitVehicles" name="ImportVehicles" class="col-12 btn btn-primary button-loading col-4" data-loading-text="Loading..."> <i class="fad fa-upload fa-fw"></i> Import</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
		<div class="col-12 col-md-4 col-xl-3 mb-3">
            <div class="card ">
                <div class="card-header">Update Trailers</div>
                <div class="card-body">
                    <div class="import pt-3 ">upload <b> CSV-file</b> to mass-update trailer information relation (max 10.000 trailers)</div>
                    <form class="row d-flex pt-3" action="<?=$us_url_root?>import/famreport.php" method="post" name="upload_excel" enctype="multipart/form-data">
                        <div class="col-12 form-group ">
                            <input type="file" name="file" id="trailerlist" class="col-12 p-0">
                        </div>
                        <div class="col-12 form-group ml-auto ">
                            <button type="submit" id="submitTrailers" name="ImportTrailers" class="col-12 btn btn-primary button-loading col-4" data-loading-text="Loading..."> <i class="fad fa-upload fa-fw"></i> Import</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-4 col-xl-3 mb-3">
            <div class="card ">
                <div class="card-header">Update Drivers </div>
                <div class="card-body">
                    <div class="import pt-3">upload Drivers CSV-file  (max 100.000 drivers)</div>
                    <form class="row d-flex pt-3" action="<?=$us_url_root?>import/drivers.php" method="post" name="upload_excel" enctype="multipart/form-data">
                        <div class="col-12 form-group ">
                            <input type="file" name="file" id="drivers" class="col-12 p-0 ">
                        </div>
                        <div class="col-12 form-group ml-auto ">
                            <button type="submit" id="submitDrivers" name="ImportDrivers" class="col-12 btn btn-primary button-loading col-4" data-loading-text="Loading..."><i class="fad fa-upload  fa-fw"></i> Import</button>
                        </div>
                        <div id="importprogress"></div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-4 col-xl-3 mb-3">
            <div class="card ">
                <div class="card-header">Update Group</div>
                <div class="card-body">
                    <div class="import pt-3">upload Group definition CSV-file (max 100.000 entries)</div>
                    <form class="row d-flex pt-3" action="<?=$us_url_root?>import/customers.php" method="post" name="upload_excel" enctype="multipart/form-data">
                        <div class="col-12 form-group ">
                            <input type="file" name="file" id="customers" class="col-12 p-0 ">
                        </div>
                        <div class="col-12 form-group ml-auto ">
                            <button type="submit" id="submitCustomers" name="ImportCustomers" class="col-12 btn btn-primary button-loading col-4" data-loading-text="Loading..."><i class="fad fa-upload fa-fw"></i> Import</button>
                        </div>
                        <div id="importprogress"></div>
                    </form>
                </div>
            </div>
        </div>
		<div class="col-12 col-md-4 col-xl-3 mb-3">
            <div class="card ">
                <div class="card-header">Update Workshops</div>
                <div class="card-body">
                    <div class="import pt-3">upload Workshop CSV-file (max 100.000 entries)</div>
                    <form class="row d-flex pt-3" action="<?=$us_url_root?>import/customers.php" method="post" name="upload_excel" enctype="multipart/form-data">
                        <div class="col-12 form-group ">
                            <input type="file" name="file" id="workshop" class="col-12 p-0 ">
                        </div>
                        <div class="col-12 form-group ml-auto ">
                            <button type="submit" id="submitWorkshop" name="ImportWorkshop" class="col-12 btn btn-primary button-loading col-4" data-loading-text="Loading..."><i class="fad fa-upload fa-fw"></i> Import</button>
                        </div>
                        <div id="importprogress"></div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-4 col-xl-3 mb-3">
            <div class="card ">
                <div class="card-header">Update Historical rFMS API-data </div>
                <div class="card-body">
                    <div class="import pt-3">upload rFMS-API JSON-file  (max 12 Mb)</div>
                    <form class="row d-flex pt-3" action="<?=$us_url_root?>import/importJson.php" method="post" name="upload_excel" enctype="multipart/form-data">
                        <div class="col-12 form-group ">
                            <input type="file" name="file" id="json" class="col-12 p-0">
                        </div>
                        <div class="col-12 form-group ml-auto ">
                            <button type="submit" id="submitJSON" name="ImportJSON" class="col-12 btn btn-primary button-loading col-4" data-loading-text="Loading..."><i class="fad fa-upload fa-fw"></i> Import</button>
                        </div>
                        <div id="importprogress"></div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-6 ">
            <div class="card bg-default">
                <div class="card-body">
                    <div class="card-title">HELP</div>
                        <div class="import">This page will help you update some critical mass data based on CSV-file.
                            <ul class="ml-3">
                                <li>Always use '<span class="info-box-number danger">;</span>' as column seperator </li>
                                <li>First-line will be ignored, assumed to be header</li>
                                <li>Processing of large files can take a few minutes, please only use incremental files</li>
                            </ul><br>
                            <span class="card-title">Examples of the files:</span>
                            <ul class="ml-3">
                                <li><a href="*">Driver CSV-file <i class="fad fa-file-download"></i></a></li>
                                <li><a href="*">Trailer CSV-file <i class="fad fa-file-download"></i></a></li>
                                <li><a href="*">Vehicle Group relation) <i class="fad fa-file-download"></i></a></li>
                                <li><a href="*">Group CSV-file <i class="fad fa-file-download"></i></a></li>
                                <li><a href="*">Workshop CSV-file <i class="fad fa-file-download"></i></a></li>
                            </ul>
                        </div>
                </div>
            </div>
        </div>
    </div>

