<script src="pages/item-add/script.js"></script>
<style>
.select2-container--bootstrap4 .select2-selection--multiple .select2-selection__choice {
    background-color: #5f76e8;
    border-color: #5f76e8;
    color: #fff;
    padding: 3px 10px;
    margin-top: 4px;
    margin-right: 4px;
    border-radius: 4px;
    font-size: 13px;
}
.select2-container--bootstrap4 .select2-selection--multiple .select2-selection__choice__remove {
    color: #fff;
    margin-right: 6px;
    font-weight: bold;
}
.select2-container--bootstrap4 .select2-selection--multiple .select2-selection__choice__remove:hover {
    color: #ffcdd2;
}
</style>

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
                        <li class="breadcrumb-item text-muted active" aria-current="page">เพิ่มรายการ</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h4 class="card-title text-dark">เพิ่มรายการผลงาน</h4>
                    <form class="mt-4" id="form-item" enctype="multipart/form-data">
                        <input type="hidden" name="fn" value="add_item">
                        <input type="hidden" name="item_id">

                        <!-- 1. ชุมชน (อยู่บนสุด เลือกได้หลายชุมชนต่อ 1 รายการ) -->
                        <div class="form-group" id="section-community">
                            <label for="community_id" class="font-weight-medium text-dark">
                                ชุมชน <span class="text-danger">* (สามารถเลือกได้หลายชุมชนต่อ 1 รายการ)</span>
                            </label>
                            <select class="form-control select2" id="community_id" name="community_id[]" multiple="multiple" style="width: 100%;">
                            </select>
                            <small class="form-text text-muted">คลิกเพื่อเลือกหรือพิมพ์ค้นหาชื่อชุมชน สามารถเลือกได้มากกว่า 1 ชุมชน</small>
                        </div>

                        <!-- 2. คอลเล็กชัน (ถัดมาเป็นคอลเล็กชัน) -->
                        <div class="form-group" id="section-collection">
                            <label for="collection_id" class="font-weight-medium text-dark">
                                คอลเล็กชัน <span class="text-danger">*</span>
                            </label>
                            <select class="form-control" id="collection_id" name="collection_id" style="width: 100%;">
                                <option value="0">-- กรุณาเลือกคอลเล็กชัน --</option>
                            </select>
                        </div>

                        <hr class="my-4">

                        <!-- ฟิลด์ด้านล่างตามคอลเล็กชันที่เลือก -->
                        <div id="dynamic-fields">
                            <!-- Title -->
                            <div class="form-group" id="section-title">
                                <label for="item_title" class="font-weight-medium text-dark">Title <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="item_title" name="item_title"
                                    placeholder="กรุณากรอก Title...">
                            </div>

                            <!-- Title Alternative -->
                            <div class="form-group" id="section-alternative">
                                <label for="item_alternative" class="font-weight-medium text-dark">Title Alternative</label>
                                <input type="text" class="form-control" id="item_alternative" name="item_alternative"
                                    placeholder="กรุณากรอก Title Alternative...">
                            </div>

                            <!-- Creater -->
                            <div class="form-group" id="section-creator">
                                <label class="font-weight-medium text-dark">Creater <span class="text-danger">*</span></label>
                                <div id="creators-list">
                                    <div class="row creator-item mb-2 align-items-center">
                                        <div class="col-md-6 creator-name-col">
                                            <label class="small text-muted mb-1">Name</label>
                                            <input type="text" class="form-control" name="creator_name[]"
                                                placeholder="กรุณากรอก Name...">
                                        </div>
                                        <div class="col-md-6 creator-org-col">
                                            <label class="small text-muted mb-1">Organization</label>
                                            <input type="text" class="form-control" name="creator_org[]"
                                                placeholder="กรุณากรอก Organization...">
                                        </div>
                                        <div class="col-md-2 creator-action-col pt-4" style="display: none;">
                                            <button type="button" class="btn btn-outline-danger btn-block btn-remove-creator" style="display: none;">
                                                <i class="fas fa-trash-alt mr-1"></i> ลบ
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div class="creator-add-wrapper mt-2" id="creator-add-wrapper" style="display: none;">
                                    <button type="button" class="btn btn-sm btn-outline-primary" id="btn-add-creator">
                                        <i class="fas fa-plus mr-1"></i> เพิ่ม Creater
                                    </button>
                                </div>
                            </div>

                            <!-- contributor (COM00000000001, COM00000000002) -->
                            <div class="form-group" id="section-contributor" style="display: none;">
                                <label class="font-weight-medium text-dark">contributor</label>
                                <div id="contributors-list">
                                    <div class="row contributor-item mb-2 align-items-center">
                                        <div class="col-md-5">
                                            <label class="small text-muted mb-1">Name</label>
                                            <input type="text" class="form-control" name="contributor_name[]"
                                                placeholder="กรุณากรอก Name...">
                                        </div>
                                        <div class="col-md-5">
                                            <label class="small text-muted mb-1">Role</label>
                                            <input type="text" class="form-control" name="contributor_role[]"
                                                placeholder="กรุณากรอก Role (เช่น อาจารย์ที่ปรึกษา, ผู้ร่วมวิจัย)...">
                                        </div>
                                        <div class="col-md-2 pt-4">
                                            <button type="button" class="btn btn-outline-danger btn-block btn-remove-contributor" style="display: none;">
                                                <i class="fas fa-trash-alt mr-1"></i> ลบ
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div class="contributor-add-wrapper mt-2">
                                    <button type="button" class="btn btn-sm btn-outline-primary" id="btn-add-contributor">
                                        <i class="fas fa-plus mr-1"></i> เพิ่ม contributor
                                    </button>
                                </div>
                            </div>

                            <!-- Journal & Volume (COM00000000004) -->
                            <div id="section-journal-volume" class="row" style="display: none;">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="item_journal" class="font-weight-medium text-dark">Journal</label>
                                        <input type="text" class="form-control" id="item_journal" name="item_journal"
                                            placeholder="กรุณากรอก Journal...">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="item_volume" class="font-weight-medium text-dark">Volume</label>
                                        <input type="text" class="form-control" id="item_volume" name="item_volume"
                                            placeholder="กรุณากรอก Volume...">
                                    </div>
                                </div>
                            </div>

                            <!-- ISBN (COM00000000005) -->
                            <div class="form-group" id="section-isbn" style="display: none;">
                                <label for="item_isbn" class="font-weight-medium text-dark">ISBN</label>
                                <input type="text" class="form-control" id="item_isbn" name="item_isbn"
                                    placeholder="กรุณากรอก ISBN...">
                            </div>

                            <!-- year -->
                            <div class="form-group" id="section-year">
                                <label for="item_issued_year" class="font-weight-medium text-dark">year</label>
                                <input type="text" class="form-control" id="item_issued_year" name="item_issued_year"
                                    placeholder="กรุณากรอก year (เช่น 2567)...">
                            </div>

                            <!-- Description -->
                            <div class="form-group" id="section-description">
                                <label for="item_abstract" class="font-weight-medium text-dark">Description</label>
                                <textarea id="item_abstract"></textarea>
                                <input type="hidden" name="item_abstract">
                            </div>

                            <!-- Subject -->
                            <div class="form-group" id="section-subject">
                                <label class="font-weight-medium text-dark">Subject</label>
                                <div id="subjects-list">
                                    <div class="row subject-item mb-2 align-items-center">
                                        <div class="col-md-10">
                                            <input type="text" class="form-control" name="subject_name[]"
                                                placeholder="กรุณากรอก Subject...">
                                        </div>
                                        <div class="col-md-2">
                                            <button type="button" class="btn btn-outline-danger btn-block btn-remove-subject" style="display: none;">
                                                <i class="fas fa-trash-alt mr-1"></i> ลบ
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div class="subject-add-wrapper mt-2">
                                    <button type="button" class="btn btn-sm btn-outline-primary" id="btn-add-subject">
                                        <i class="fas fa-plus mr-1"></i> เพิ่ม Subject
                                    </button>
                                </div>
                            </div>

                            <!-- Click to view -->
                            <div class="form-group" id="section-uri">
                                <label for="item_uri" class="font-weight-medium text-dark">Click to view</label>
                                <input type="text" class="form-control" id="item_uri" name="item_uri"
                                    placeholder="กรุณากรอก Click to view (เช่น https://...)">
                            </div>

                            <!-- file -->
                            <div class="form-group" id="section-file">
                                <label for="file_name" class="font-weight-medium text-dark">file</label>
                                <input type="file" class="form-control-file border p-2 rounded w-100 bg-white" id="file_name" name="file_name">
                                <small class="form-text text-muted">รองรับไฟล์เอกสาร PDF หรือรูปภาพ (แนะนำขนาดไม่เกิน 40 MB)</small>
                            </div>
                        </div>

                        <hr class="my-4">
                        <div class="form-group mb-0">
                            <button type="button" class="btn btn-success px-4" id="add-item">
                                <i class="fas fa-save mr-1"></i> เพิ่มรายการ
                            </button>
                            <a href="?p=item-list" class="btn btn-secondary px-4 ml-2">ยกเลิก</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>