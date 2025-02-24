<div class="page-breadcrumb">
    <div class="row">
        <div class="col-7 align-self-center">
            <h4 class="page-title text-truncate text-dark font-weight-medium mb-1">แดชบอร์ด</h4>
            <div class="d-flex align-items-center">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb m-0 p-0">
                        <li class="breadcrumb-item"><a href="index.html" class="text-muted">แดชบอร์ด</a></li>
                        <li class="breadcrumb-item text-muted active" aria-current="page">ชุมชน</li>
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
                        <h4 class="card-title">ชุมชน</h4>
                        <div class="ml-auto">
                            <div class="dropdown sub-dropdown">
                                <button class="btn btn-success" type="button" id="add">
                                    เพิ่มชุมชน
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="row" style="margin-bottom: 20px;">
                        <div class="col-md-6"></div>
                        <div class="col-md-3 text-right"><b>ชุมชนรวม - <span id="total_data"></span></b></div>
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
                                    <th width="200px;">ภาพปก</th>
                                    <th>ชื่อชุมชน</th>
                                    <th>คำอธิบายสั้น ๆ</th>
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

<div id="community-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="community-modalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header modal-colored-header bg-warning" id="header-modal">
                <h4 class="modal-title" id="community-header-modalLabel">Modal Heading</h4>
                <button type="button" class="close" data-backdrop="false" data-dismiss="modal"
                    aria-hidden="true">×</button>
            </div>
            <div class="modal-body">
                <form id="form-community">
                    <input type="hidden" id="community_id" name="community_id">
                    <input type="hidden" name="fn" id="fn">
                    <div class="mb-3 text-center">
                        <img id="img" src="../img/3673.jpg" alt="community image" class="img-thumbnail"
                            style="width: 300px;" onerror="errorImage(this,'../img/3673.jpg')">
                    </div>
                    <div class="mb-3">
                        <label for="community_img" class="form-label">ภาพปกชุมชน</label>
                        <input class="form-control" type="file" name="community_img" id="community_img" accept="img/*"
                            onchange="imgf_change(this,'#img','../img/3673.jpg')">
                    </div>
                    <div class="mb-3">
                        <label for="community_title" class="form-label">ชื่อชุมชน</label>
                        <input class="form-control" type="text" name="community_title" id="community_title"
                            placeholder="กรุณากรอกชื่อชุมชน...">
                    </div>
                    <div class="mb-3">
                        <label for="category_id" class="form-label">คำอธิบายสั้น ๆ</label>
                        <input class="form-control" type="text" name="community_description" id="community_description"
                            placeholder="กรุณากรอกคำอธิบายสั้น ๆ...">
                    </div>
                    <button type="submit" class="btn btn-success" id="add-community">เพิ่มชุมชน</button>
                    <button type="submit" class="btn btn-warning" id="edit-community">แก้ไขชุมชน</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="pages/community/script.js"></script>