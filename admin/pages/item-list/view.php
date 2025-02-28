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
                        <div class="ml-auto">
                            <div class="dropdown sub-dropdown">
                                <a class="btn btn-success" href="?p=item-add">
                                    เพิ่มรายการ
</a>
                            </div>
                        </div>
                    </div>
                    <div class="row" style="margin-bottom: 20px;">
                        <div class="col-md-6"></div>
                        <div class="col-md-3 text-right"><b>รายการรวม - <span id="total_data"></span></b></div>
                        <div class="col-md-3">
                            <input type="text" name="search" class="form-control" id="search"
                                placeholder="Search Here" />
                        </div>
                    </div>
                    <div class="table-resposive">
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
<script src="pages/item-list/script.js"></script>