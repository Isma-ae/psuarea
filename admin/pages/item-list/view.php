<div class="page-breadcrumb">
    <div class="row">
        <div class="col-7 align-self-center">
            <h4 class="page-title text-truncate text-dark font-weight-medium mb-1">แดชบอร์ด</h4>
            <div class="d-flex align-items-center">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb m-0 p-0">
                        <li class="breadcrumb-item"><a href="./" class="text-muted">แดชบอร์ด</a></li>
                        <li class="breadcrumb-item"><a href="?p=community" class="text-muted">ชุมชน</a></li>
                        <li class="breadcrumb-item"><a href="?p=collection" class="text-muted">คอลเลกชัน</a></li>
                        <li class="breadcrumb-item text-muted active" aria-current="page">รายการ</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-4">
                        <h4 class="card-title">รายการ</h4>
                        <div class="ml-auto d-flex align-items-center">
                            <div class="btn-group mr-2">
                                <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fas fa-download mr-1"></i> ส่งออกข้อมูล (Export)
                                </button>
                                <div class="dropdown-menu dropdown-menu-right shadow">
                                    <h6 class="dropdown-header text-dark font-weight-bold">เลือกรูปแบบการส่งออก</h6>
                                    <a class="dropdown-item py-2 export-link" data-format="excel" href="pages/item-list/export.php?format=excel" target="_blank">
                                        <i class="far fa-file-excel text-success mr-2 fa-lg"></i> ส่งออกเป็น <strong>Excel</strong> (.xls)
                                    </a>
                                    <a class="dropdown-item py-2 export-link" data-format="csv" href="pages/item-list/export.php?format=csv" target="_blank">
                                        <i class="far fa-file-alt text-info mr-2 fa-lg"></i> ส่งออกเป็น <strong>CSV</strong> (.csv)
                                    </a>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item py-2 export-link" data-format="pdf" href="pages/item-list/export.php?format=pdf" target="_blank">
                                        <i class="far fa-file-pdf text-danger mr-2 fa-lg"></i> พิมพ์ / บันทึกเป็น <strong>PDF</strong> (.pdf)
                                    </a>
                                </div>
                            </div>
                            <a class="btn btn-success" href="?p=item-add">
                                <i class="fas fa-plus mr-1"></i> เพิ่มรายการ
                            </a>
                        </div>
                    </div>
                    <div class="row align-items-center mb-3">
                        <div class="col-md-4 mb-2 mb-md-0">
                            <div class="d-inline-flex align-items-center">
                                <label class="mr-2 mb-0 text-muted font-weight-normal">แสดง</label>
                                <select id="limit_select" class="custom-select custom-select-sm" style="width: auto;">
                                    <option value="10" selected>10</option>
                                    <option value="25">25</option>
                                    <option value="50">50</option>
                                    <option value="100">100</option>
                                </select>
                                <label class="ml-2 mb-0 text-muted font-weight-normal">รายการต่อหน้า</label>
                            </div>
                        </div>
                        <div class="col-md-4 text-md-center mb-2 mb-md-0">
                            <span class="text-muted">รายการทั้งหมด:</span> <span id="total_data" class="badge badge-pill badge-primary font-14 font-weight-bold px-3 py-1">0</span> <span class="text-muted">รายการ</span>
                        </div>
                        <div class="col-md-4">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text bg-white"><i class="icon-magnifier"></i></span>
                                </div>
                                <input type="text" name="search" class="form-control" id="search"
                                    placeholder="ค้นหารายการ หรือชื่อผู้แต่ง..." />
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th width="10px">#</th>
                                    <th width="90px">ปี</th>
                                    <th>รายการ</th>
                                    <th>ผู้แต่ง</th>
                                    <th width="90px">อัปโหลด</th>
                                    <th width="150px">จัดการ</th>
                                </tr>
                            </thead>
                            <tbody id="post_data"></tbody>
                        </table>
                    </div>
                    <div id="pagination_link"></div>
                </div>
            </div>
        </div>
    </div>
</div>
<style>
.fa-file-csv:before { content: "\f15c"; }
</style>
<script src="pages/item-list/script.js"></script>