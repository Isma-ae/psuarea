<script src="pages/item-add/script.js"></script>
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
                    <h4 class="card-title">รายการ</h4>
                    <form class="mt-4" id="form-item" enctype="multipart/form-data">
                        <input type="hidden" name="fn" value="add_item">
                        <input type="hidden" name="item_id">
                        <div class="form-group">
                            <label for="item_title">ชื่อเรื่อง</label>
                            <input type="text" class="form-control" name="item_title"
                                placeholder="กรุณากรอกชื่อเรื่อง...">
                        </div>
                        <div class="form-group">
                            <label for="item_alternative">ชื่อเรื่อง (ภาษาอื่น ๆ)</label>
                            <input type="text" class="form-control" name="item_alternative"
                                placeholder="กรุณากรอกชื่อเรื่อง (ภาษาอื่น ๆ)...">
                        </div>
                        <div class="form-group">
                            <label for="file_name">ไฟล์</label>
                            <input type="file" class="form-control" name="file_name" placeholder="Enter email">
                        </div>
                        <div class="form-group">
                            <label for="community_id">ชุมชน</label>
                            <select class="form-control" name="community_id">
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="collection_id">คอลเลกชัน</label>
                            <select class="form-control" name="collection_id">
                            </select>
                        </div>
                        <hr>
                        <h4>ผู้เขียน</h4>
                        <div id="authors-list">
                            <div class="row author-main">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="writer_fname">ชื่อผู้เขียน</label>
                                        <input type="text" class="form-control" name="writer_fname[]"
                                            placeholder="กรุณากรอกชื่อผู้เขียน...">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="writer_lname">นามสกุล</label>
                                        <input type="text" class="form-control" name="writer_lname[]"
                                            aria-describedby="emailHelp" placeholder="กรุณากรอกนามสกุลผู้เขียน...">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="writer_main">ประเภทผู้เขียน</label>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="writer_main[0]" value="1"
                                                checked="checked">
                                            <label class="form-check-label" for="writer_main1">
                                                ผู้เขียนหลัก
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-2 pt-4">
                                    <button type="button" class="btn btn-primary" id="add-author">เพิ่มผู้เขียน</button>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <!--<div class="col-md-4">
                                <div class="form-group">
                                    <label for="item_issued_day">วันที่ออก</label>
                                    <select class="form-control" name="item_issued_day"></select>
                                </div>
                            </div>-->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="item_issued_month">เดือน</label>
                                    <select class="form-control" name="item_issued_month"></select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="item_issued_year">ปี</label>
                                    <input type="text" class="form-control" name="item_issued_year"
                                        placeholder="กรุณากรอกปีที่ออก...">
                                </div>
                            </div>
                        </div>
                        <!--<div class="form-group">
                            <label for="item_description">คำอธิบายสั้น ๆ</label>
                            <input type="text" class="form-control" name="item_description" placeholder="กรุณากรอกคำอธิบาย...">
                        </div>-->
                        <div class="form-group">
                            <label for="item_abstract">บทคัดย่อ / บทสรุป</label>
                            <textarea id="item_abstract"></textarea>
                            <input type="hidden" name="item_abstract">
                        </div>
                        <div class="form-group">
                            <label for="item_sponsorship">หน่วยงานสนับสนุน</label>
                            <input type="text" class="form-control" name="item_sponsorship"
                                placeholder="กรุณากรอกหน่วยงานสนับสนุน...">
                        </div>
                        <div class="form-group">
                            <label for="item_citation">การอ้างอิง</label>
                            <textarea type="text" class="form-control" name="item_citation"
                                placeholder="กรุณากรอกการอ้างอิง..."></textarea>
                        </div>
                        <div class="form-group">
                            <label for="item_uri">ลิงค์อ่าน e-book</label>
                            <input type="text" class="form-control" name="item_uri"
                                placeholder="กรุณากรอกสำนักพิมพ์...">
                        </div>
                        <div class="form-group">
                            <label for="item_publisher">สำนักพิมพ์</label>
                            <input type="text" class="form-control" name="item_publisher"
                                placeholder="กรุณากรอกสำนักพิมพ์...">
                        </div>
                        <div class="row subject">
                            <div class="col-md-10">
                                <div class="form-group">
                                    <label for="subject_name">หัวเรื่อง</label>
                                    <input type="text" class="form-control" name="subject_name[]"
                                        placeholder="กรุณากรอกหัวเรื่อง...">
                                </div>
                            </div>
                            <div class="col-md-2 pt-4">
                                <button type="button" class="btn btn-primary" id="add-subject">เพิ่มหัวเรื่อง</button>
                            </div>
                        </div>
                        <!--<div class="form-group">
                            <label for="exampleInputEmail1">หมวดหมู่</label>
                            <select class="form-control" name="type_id" style="width: 100%;">
                                <?php
                                    $obj = $DATABASE->QueryObj("SELECT * FROM tb_type");
                                    foreach ($obj as $i => $value) {
                                ?>
                                <option value="<?php echo $value['type_id'];?>"><?php echo $value['type_name'];?>
                                </option>
                                <?php }?>
                            </select>
                        </div>-->
                        <div class="form-group">
                            <button type="button" class="btn btn-success" id="add-item">เพิ่มรายการ</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>