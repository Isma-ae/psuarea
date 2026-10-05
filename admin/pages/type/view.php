<div class="page-breadcrumb">
    <div class="row">
        <div class="col-7 align-self-center">
            <h4 class="page-title text-truncate text-dark font-weight-medium mb-1">แดชบอร์ด</h4>
            <div class="d-flex align-items-center">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb m-0 p-0">
                        <li class="breadcrumb-item"><a href="./" class="text-muted">แดชบอร์ด</a></li>
                        <li class="breadcrumb-item"><a href="?p=community" class="text-muted">หมวดหมู่</a></li>
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
                        <h4 class="card-title"> หมวดหมู่ </h4>
                        <div class="ml-auto">
                            <div class="dropdown sub-dropdown">
                                <button class="btn btn-success" type="button" id="add">
                                    เพิ่มหมวดหมู่
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="row" style="margin-bottom: 20px;">
                        <div class="col-md-6"></div>
                        <div class="col-md-3 text-right"><b>หมวดหมู่ - <span id="total_data"></span></b></div>
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
                                    <th>ชื่อหมวดหมู่</th>
                                    <th width="120px">จัดการ</th>
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

<div id="type-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="type-modalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header modal-colored-header bg-warning" id="header-modal">
                <h4 class="modal-title" id="type-header-modalLabel">Modal Heading</h4>
                <button type="button" class="close" data-backdrop="false" data-dismiss="modal"
                    aria-hidden="true">×</button>
            </div>
            <div class="modal-body">
                <form id="form-type">
                    <input type="hidden" id="type_id" name="type_id">
                    <input type="hidden" name="fn" id="fn">
                    <div class="mb-3">
                        <label for="type_name" class="form-label">ชื่อหมวดหมู่</label>
                        <input class="form-control" type="text" name="type_name" id="type_name"
                            placeholder="กรุณากรอกชื่อหมวดหมู่...">
                    </div>
                    <button type="submit" class="btn btn-success" id="add-type">เพิ่มหมวดหมู่</button>
                    <button type="submit" class="btn btn-warning" id="edit-type">แก้ไขหมวดหมู่</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="pages/type/script.js"></script>