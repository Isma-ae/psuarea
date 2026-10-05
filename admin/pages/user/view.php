<div class="page-breadcrumb">
    <div class="row">
        <div class="col-7 align-self-center">
            <h4 class="page-title text-truncate text-dark font-weight-medium mb-1">แดชบอร์ด</h4>
            <div class="d-flex align-items-center">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb m-0 p-0">
                        <li class="breadcrumb-item"><a href="./" class="text-muted">แดชบอร์ด</a></li>
                        <li class="breadcrumb-item text-muted active" aria-current="page">ผู้ดูแลระบบ</li>
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
                        <h4 class="card-title"> ผู้ดูแลระบบ </h4>
                        <div class="ml-auto">
                            <div class="dropdown sub-dropdown">
                                <button class="btn btn-success" type="button" id="add">
                                    เพิ่มผู้ดูแลระบบ
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="row" style="margin-bottom: 20px;">
                        <div class="col-md-6"></div>
                        <div class="col-md-3 text-right"><b>ผู้ดูแลระบบ - <span id="total_data"></span></b></div>
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
                                    <th>บัญชีผู้ใช้</th>
                                    <th>ชื่อ</th>
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

<div id="user-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="user-modalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header modal-colored-header bg-warning" id="header-modal">
                <h4 class="modal-title" id="user-header-modalLabel">Modal Heading</h4>
                <button type="button" class="close" data-backdrop="false" data-dismiss="modal"
                    aria-hidden="true">×</button>
            </div>
            <div class="modal-body">
                <form id="form-user">
                    <input type="hidden" id="user_id" name="user_id">
                    <input type="hidden" name="fn" id="fn">
                    <div class="mb-3">
                        <label for="user_name" class="form-label">บัญชีผู้ใช้</label>
                        <input class="form-control" type="text" name="user_name" id="user_name"
                            placeholder="กรุณากรอกชื่อบัญชีผู้ใช้...">
                    </div>
                    <div class="mb-3">
                        <div class="row">
                            <div class="col-md-6">
                                <label for="user_name" class="form-label">ชื่อ</label>
                                <input class="form-control" type="text" name="user_fname" id="user_fname"
                                    placeholder="กรุณากรอกชื่อบัญชีผู้ใช้...">
                            </div>
                            <div class="col-md-6">
                                <label for="user_name" class="form-label">นามสกุล</label>
                                <input class="form-control" type="text" name="user_lname" id="user_lname"
                                        placeholder="กรุณากรอกชื่อบัญชีผู้ใช้...">
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-success" id="add-user">เพิ่มผู้ดูแลระบบ</button>
                    <button type="submit" class="btn btn-warning" id="edit-user">แก้ไขผู้ดูแลระบบ</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="pages/user/script.js"></script>