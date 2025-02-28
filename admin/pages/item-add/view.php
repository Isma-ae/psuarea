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
                    <form class="mt-4" id="form-item">
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
                        <hr>
                        <h4>ผู้เขียน</h4>
                        <div id="authors-list">
                            <div class="row author-main">
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="writer_prefix">คำนำหน้า</label>
                                        <select class="form-control" name="writer_prefix[]" style="width: 100%;">
                                            <option value="">-- ไม่มีคำนำหน้า --</option>
                                            <option value="นาย">นาย</option>
                                            <option value="นาง">นาง</option>
                                            <option value="น.ส.">นางสาว</option>
                                            <option value="ดร.">ดร.</option>
                                            <option value="ศ.ดร.">ศ.ดร.</option>
                                            <option value="ศ.">ศ.</option>
                                            <option value="ผศ.ดร.">ผศ.ดร.</option>
                                            <option value="ผศ.">ผศ.</option>
                                            <option value="รศ.ดร.">รศ.ดร.</option>
                                            <option value="รศ.">รศ.</option>
                                            <option value="Mr.">MR</option>
                                            <option value="Mrs.">MRS</option>
                                            <option value="Ms.">MS</option>
                                            <option value="Miss">MISS</option>
                                            <option value="Dr.">Dr.</option>
                                            <option value="พล.ต.อ.">พลตำรวจเอก</option>
                                            <option value="พล.ต.อ. หญิง">พลตำรวจเอก หญิง</option>
                                            <option value="พล.ต.ท">พลตำรวจโท</option>
                                            <option value="พล.ต.ท หญิง">พลตำรวจโท หญิง</option>
                                            <option value="พล.ต.ต">พลตำรวจตรี</option>
                                            <option value="พล.ต.ต หญิง">พลตำรวจตรี หญิง</option>
                                            <option value="พ.ต.อ.">พันตำรวจเอก</option>
                                            <option value="พ.ต.อ. หญิง">พันตำรวจเอก หญิง</option>
                                            <option value="พ.ต.อ.(พิเศษ)">พันตำรวจเอกพิเศษ</option>
                                            <option value="พ.ต.อ.(พิเศษ) หญิง">พันตำรวจเอกพิเศษ หญิง</option>
                                            <option value="พ.ต.ท.">พันตำรวจโท</option>
                                            <option value="พ.ต.ท. หญิง">พันตำรวจโท หญิง</option>
                                            <option value="พ.ต.ต.">พันตำรวจตรี</option>
                                            <option value="พ.ต.ต. หญิง">พันตำรวจตรี หญิง</option>
                                            <option value="ร.ต.อ.">ร้อยตำรวจเอก</option>
                                            <option value="ร.ต.อ. หญิง">ร้อยตำรวจเอก หญิง</option>
                                            <option value="ร.ต.ท.">ร้อยตำรวจโท</option>
                                            <option value="ร.ต.ท. หญิง">ร้อยตำรวจโท หญิง</option>
                                            <option value="ร.ต.ต.">ร้อยตำรวจตรี</option>
                                            <option value="ร.ต.ต. หญิง">ร้อยตำรวจตรี หญิง</option>
                                            <option value="ด.ต.">นายดาบตำรวจ</option>
                                            <option value="ด.ต. หญิง">ดาบตำรวจหญิง</option>
                                            <option value="ส.ต.อ.">สิบตำรวจเอก</option>
                                            <option value="ส.ต.อ. หญิง">สิบตำรวจเอก หญิง</option>
                                            <option value="ส.ต.ท.">สิบตำรวจโท</option>
                                            <option value="ส.ต.ท. หญิง">สิบตำรวจโท หญิง</option>
                                            <option value="ส.ต.ต.">สิบตำรวจตรี</option>
                                            <option value="ส.ต.ต. หญิง">สิบตำรวจตรี หญิง</option>
                                            <option value="จ.ส.ต.">จ่าสิบตำรวจ</option>
                                            <option value="จ.ส.ต. หญิง">จ่าสิบตำรวจ หญิง</option>
                                            <option value="พลฯ">พลตำรวจ</option>
                                            <option value="พลฯ หญิง">พลตำรวจ หญิง</option>
                                            <option value="พล.อ.">พลเอก</option>
                                            <option value="พล.อ. หญิง">พลเอก หญิง</option>
                                            <option value="พล.ท.">พลโท</option>
                                            <option value="พล.ท. หญิง">พลโท หญิง</option>
                                            <option value="พล.ต.">พลตรี</option>
                                            <option value="พล.ต.หญิง">พลตรี หญิง</option>
                                            <option value="พ.อ.">พันเอก</option>
                                            <option value="พ.อ.หญิง">พันเอก หญิง</option>
                                            <option value="พ.อ.(พิเศษ)">พันเอกพิเศษ</option>
                                            <option value="พ.อ.(พิเศษ) หญิง">พันเอกพิเศษ หญิง</option>
                                            <option value="พ.ท.">พันโท</option>
                                            <option value="พ.ท.หญิง">พันโท หญิง</option>
                                            <option value="พ.ต.">พันตรี</option>
                                            <option value="พ.ต.หญิง">พันตรี หญิง</option>
                                            <option value="ร.อ.">ร้อยเอก</option>
                                            <option value="ร.อ.หญิง">ร้อยเอก หญิง</option>
                                            <option value="ร.ท.">ร้อยโท</option>
                                            <option value="ร.ท.หญิง">ร้อยโท หญิง</option>
                                            <option value="ร.ต.">ร้อยตรี</option>
                                            <option value="ร.ต.หญิง">ร้อยตรี หญิง</option>
                                            <option value="ส.อ.">สิบเอก</option>
                                            <option value="ส.อ.หญิง">สิบเอก หญิง</option>
                                            <option value="ส.ท.">สิบโท</option>
                                            <option value="ส.ท.หญิง">สิบโท หญิง</option>
                                            <option value="ส.ต.">สิบตรี</option>
                                            <option value="ส.ต.หญิง">สิบตรี หญิง</option>
                                            <option value="จ.ส.อ.">จ่าสิบเอก</option>
                                            <option value="จ.ส.อ.หญิง">จ่าสิบเอก หญิง</option>
                                            <option value="จ.ส.ท.">จ่าสิบโท</option>
                                            <option value="จ.ส.ท.หญิง">จ่าสิบโท หญิง</option>
                                            <option value="จ.ส.ต.">จ่าสิบตรี</option>
                                            <option value="จ.ส.ต.หญิง">จ่าสิบตรี หญิง</option>
                                            <option value="พลฯ">พลทหารบก</option>
                                            <option value="ว่าที่ พ.ต.">ว่าที่ พ.ต.</option>
                                            <option value="ว่าที่ พ.ต. หญิง">ว่าที่ พ.ต. หญิง</option>
                                            <option value="ว่าที่ ร.อ.">ว่าที่ ร.อ.</option>
                                            <option value="ว่าที่ ร.อ. หญิง">ว่าที่ ร.อ. หญิง</option>
                                            <option value="ว่าที่ ร.ท.">ว่าที่ ร.ท.</option>
                                            <option value="ว่าที่ ร.ท. หญิง">ว่าที่ ร.ท. หญิง</option>
                                            <option value="ว่าที่ ร.ต.">ว่าที่ ร.ต.</option>
                                            <option value="ว่าที่ ร.ต. หญิง">ว่าที่ ร.ต. หญิง</option>
                                            <option value="พล.ร.อ.">พลเรือเอก</option>
                                            <option value="พล.ร.อ.หญิง">พลเรือเอก หญิง</option>
                                            <option value="พล.ร.ท.">พลเรือโท</option>
                                            <option value="พล.ร.ท.หญิง">พลเรือโท หญิง</option>
                                            <option value="พล.ร.ต.">พลเรือตรี</option>
                                            <option value="พล.ร.ต.หญิง">พลเรือตรี หญิง</option>
                                            <option value="น.อ.">นาวาเอก</option>
                                            <option value="น.อ.หญิง">นาวาเอก หญิง</option>
                                            <option value="น.อ.(พิเศษ)">นาวาเอกพิเศษ</option>
                                            <option value="น.อ.(พิเศษ) หญิง">นาวาเอกพิเศษ หญิง</option>
                                            <option value="น.ท.">นาวาโท</option>
                                            <option value="น.ท.หญิง">นาวาโท หญิง</option>
                                            <option value="น.ต.">นาวาตรี</option>
                                            <option value="น.ต.หญิง">นาวาตรี หญิง</option>
                                            <option value="ร.อ.">เรือเอก</option>
                                            <option value="ร.อ.หญิง">เรือเอก หญิง</option>
                                            <option value="ร.ท.">เรือโท</option>
                                            <option value="ร.ท.หญิง">เรือโท หญิง</option>
                                            <option value="ร.ต.">เรือตรี</option>
                                            <option value="ร.ต.หญิง">เรือตรี หญิง</option>
                                            <option value="พ.จ.อ.">พันจ่าเอก</option>
                                            <option value="พ.จ.อ.หญิง">พันจ่าเอก หญิง</option>
                                            <option value="พ.จ.ท.">พันจ่าโท</option>
                                            <option value="พ.จ.ท.หญิง">พันจ่าโท หญิง</option>
                                            <option value="พ.จ.ต.">พันจ่าตรี</option>
                                            <option value="พ.จ.ต.หญิง">พันจ่าตรี หญิง</option>
                                            <option value="จ.อ.">จ่าเอก</option>
                                            <option value="จ.อ.หญิง">จ่าเอก หญิง</option>
                                            <option value="จ.ท.">จ่าโท</option>
                                            <option value="จ.ท.หญิง">จ่าโท หญิง</option>
                                            <option value="จ.ต.">จ่าตรี</option>
                                            <option value="จ.ต.หญิง">จ่าตรี หญิง</option>
                                            <option value="พลฯ">พลทหารเรือ</option>
                                            <option value="พล.อ.อ.">พลอากาศเอก</option>
                                            <option value="พล.อ.อ.หญิง">พลอากาศเอก หญิง</option>
                                            <option value="พล.อ.ท.">พลอากาศโท</option>
                                            <option value="พล.อ.ท.หญิง">พลอากาศโท หญิง</option>
                                            <option value="พล.อ.ต.">พลอากาศตรี</option>
                                            <option value="พล.อ.ต.หญิง">พลอากาศตรี หญิง</option>
                                            <option value="น.อ.">นาวาอากาศเอก</option>
                                            <option value="น.อ.หญิง">นาวาอากาศเอก หญิง</option>
                                            <option value="น.อ.(พิเศษ)">นาวาอากาศเอกพิเศษ</option>
                                            <option value="น.อ.(พิเศษ) หญิง">นาวาอากาศเอกพิเศษ หญิง</option>
                                            <option value="น.ท.">นาวาอากาศโท</option>
                                            <option value="น.ท.หญิง">นาวาอากาศโท หญิง</option>
                                            <option value="น.ต.">นาวาอากาศตรี</option>
                                            <option value="น.ต.หญิง">นาวาอากาศตรี หญิง</option>
                                            <option value="ร.อ.">เรืออากาศเอก</option>
                                            <option value="ร.อ.หญิง">เรืออากาศเอก หญิง</option>
                                            <option value="ร.ท.">เรืออากาศโท</option>
                                            <option value="ร.ท.หญิง">เรืออากาศโท หญิง</option>
                                            <option value="ร.ต.">เรืออากาศตรี</option>
                                            <option value="ร.ต.หญิง">เรืออากาศตรี หญิง</option>
                                            <option value="พ.อ.อ.">พันจ่าอากาศเอก</option>
                                            <option value="พ.อ.อ.หญิง">พันจ่าอากาศเอก หญิง</option>
                                            <option value="พ.อ.ท.">พันจ่าอากาศโท</option>
                                            <option value="พ.อ.ท.หญิง">พันจ่าอากาศโท หญิง</option>
                                            <option value="พ.อ.ต.">พันจ่าอากาศตรี</option>
                                            <option value="พ.อ.ต.หญิง">พันจ่าอากาศตรี หญิง</option>
                                            <option value="จ.อ.">จ่าอากาศเอก</option>
                                            <option value="จ.อ.หญิง">จ่าอากาศเอก หญิง</option>
                                            <option value="จ.ท.">จ่าอากาศโท</option>
                                            <option value="จ.ท.หญิง">จ่าอากาศโท หญิง</option>
                                            <option value="จ.ต.">จ่าอากาศตรี</option>
                                            <option value="จ.ต.หญิง">จ่าอากาศตรี หญิง</option>
                                            <option value="พลฯ">พลทหารอากาศ</option>
                                            <option value="หม่อม">หม่อม</option>
                                            <option value="ม.จ.">หม่อมเจ้า</option>
                                            <option value="ม.ร.ว.">หม่อมราชวงศ์</option>
                                            <option value="ม.ล.">หม่อมหลวง</option>
                                            <option value="นพ.">นพ.</option>
                                            <option value="พญ.">แพทย์หญิง</option>
                                            <option value="นสพ.">สัตวแพทย์</option>
                                            <option value="สพญ.">สพญ.</option>
                                            <option value="ทพ.">ทพ.</option>
                                            <option value="ทพญ.">ทพญ.</option>
                                            <option value="ภก.">เภสัชกร</option>
                                            <option value="ภกญ.">ภกญ.</option>
                                            <option value="พระ">พระ</option>
                                            <option value="พระครู">พระครู</option>
                                            <option value="พระมหา">พระมหา</option>
                                            <option value="พระสมุห์">พระสมุห์</option>
                                            <option value="พระอธิการ">พระอธิการ</option>
                                            <option value="สามเณร">สามเณร</option>
                                            <option value="แม่ชี">แม่ชี</option>
                                            <option value="บาทหลวง">บาทหลวง</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="writer_fname">ชื่อผู้เขียน</label>
                                        <input type="text" class="form-control" name="writer_fname[]"
                                            placeholder="กรุณากรอกชื่อผู้เขียน...">
                                    </div>
                                </div>
                                <div class="col-md-3">
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
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="item_issued">ปีที่ออก</label>
                                    <input type="text" class="form-control" name="item_issued"
                                        placeholder="กรุณากรอกปีที่ออก...">
                                </div>
                            </div>
                            <div class="col-md-9">
                                <div class="form-group">
                                    <label for="item_description">คำอธิบาย</label>
                                    <input type="text" class="form-control" name="item_description"
                                        placeholder="กรุณากรอกคำอธิบาย...">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="item_abstract">บทคัดย่อ</label>
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
                        <div class="form-group">
                            <label for="exampleInputEmail1">ประเภทงาน</label>
                            <select class="form-control" name="type_id" style="width: 100%;">
                                <?php
                                    $obj = $DATABASE->QueryObj("SELECT * FROM tb_type");
                                    foreach ($obj as $i => $value) {
                                ?>
                                <option value="<?php echo $value['type_id'];?>"><?php echo $value['type_name'];?>
                                </option>
                                <?php }?>
                            </select>
                        </div>
                        <div class="form-group">
                            <button type="button" class="btn btn-success" id="add-item">เพิ่มรายการ</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>