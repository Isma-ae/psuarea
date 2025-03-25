<script src="pages/item-edit/script.js"></script>
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
                        <?php
                            $obj = $DATABASE->QueryObj("SELECT * FROM tb_item WHERE item_id = '".$_GET["item_id"]."'");
                        ?>
                        <input type="hidden" name="fn" value="edit_item">
                        <input type="hidden" name="item_id" value="<?= $obj[0]["item_id"];?>">
                        <div class="form-group">
                            <label for="item_title">ชื่อเรื่อง</label>
                            <input type="text" class="form-control" name="item_title" value="<?= $obj[0]["item_title"];?>">
                        </div>
                        <div class="form-group">
                            <label for="item_alternative">ชื่อเรื่อง (ภาษาอื่น ๆ)</label>
                            <input type="text" class="form-control" name="item_alternative" value="<?= $obj[0]["item_alternative"];?>">
                        </div>
                        <div class="form-group">
                            <label for="community_id">ชุมชน</label>
                            <select class="form-control" name="community_id">
                                <?php
                                    $obj2 = $DATABASE->QueryObj("SELECT * FROM tb_community");
                                    foreach ($obj2 as $key => $value) {
                                        $select = $value["community_id"] == $obj[0]["community_id"] ? " selected" : "";
                                        echo '<option value="'.$value["community_id"].'"'.$select.'>'.$value["community_title"].'</option>';
                                    }
                                ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="collection_id">คอลเลกชัน</label>
                            <select class="form-control" name="collection_id">
                                <?php
                                    $obj3 = $DATABASE->QueryObj("SELECT * FROM tb_collection");
                                    foreach ($obj3 as $key2 => $value2) {
                                        $select2 = $value2["collection_id"] == $obj[0]["collection_id"] ? " selected" : "";
                                        echo '<option value="'.$value2["collection_id"].'"'.$select2.'>'.$value2["collection_name"].'</option>';
                                    }
                                ?>
                            </select>
                        </div>
                        <?php
                            $file = $DATABASE->QueryObj("SELECT * FROM tb_file WHERE file_type = 'file' AND item_id = '".$_GET["item_id"]."'");
                            if(sizeof($file) > 0) {
                        ?>
                        <div class="form-group file-show">
                            <label for="file_name">ไฟล์</label>
                            <p>
                                <a href="<?= $file[0]["file_name"]?>"><?= $file[0]["file_name"]?></a>&nbsp;&nbsp;&nbsp; 
                                <a href="#" style="color:red;" id="del-file" file-id="<?= $file[0]["file_id"]?>">ลบ</a>
                            </p>
                        </div>
                        <?php }?>
                        <div class="form-group file-add">
                            <label for="file_name">ไฟล์</label>
                            <input type="file" class="form-control" name="file_name" placeholder="Enter email">
                        </div>
                        <hr>
                        <h4>ผู้เขียน</h4>
                        <div id="authors-list">
                            <?php
                                $authors = $DATABASE->QueryObj("SELECT * FROM tb_writer WHERE item_id = '".$_GET["item_id"]."'");
                                foreach ($authors as $key => $value) {
                                    $selectedPrefix = $value["writer_prefix"];
                                    $checkedType = $value["writer_main"];
                            ?>
                            <div class="row author-main" data-action="add_author">
                                <input type="hidden" name="writer_id[]" value="<?= $value['writer_id']; ?>">
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="writer_prefix">คำนำหน้า</label>
                                        <select class="form-control" name="writer_prefix[]" style="width: 100%;">
                                            <option value="" <?= ($selectedPrefix == "") ? 'selected' : '' ?>>-- ไม่มีคำนำหน้า --</option>
                                            <option value="นาย" <?= ($selectedPrefix == "นาย") ? 'selected' : '' ?>>นาย</option>
                                            <option value="นาง" <?= ($selectedPrefix == "นาง") ? 'selected' : '' ?>>นาง</option>
                                            <option value="น.ส." <?= ($selectedPrefix == "น.ส.") ? 'selected' : '' ?>>นางสาว</option>
                                            <option value="ดร." <?= ($selectedPrefix == "ดร.") ? 'selected' : '' ?>>ดร.</option>
                                            <option value="ศ.ดร." <?= ($selectedPrefix == "ศ.ดร.") ? 'selected' : '' ?>>ศ.ดร.</option>
                                            <option value="ศ." <?= ($selectedPrefix == "ศ.") ? 'selected' : '' ?>>ศ.</option>
                                            <option value="ผศ.ดร." <?= ($selectedPrefix == "ผศ.ดร.") ? 'selected' : '' ?>>ผศ.ดร.</option>
                                            <option value="ผศ." <?= ($selectedPrefix == "ผศ.") ? 'selected' : '' ?>>ผศ.</option>
                                            <option value="รศ.ดร." <?= ($selectedPrefix == "รศ.ดร.") ? 'selected' : '' ?>>รศ.ดร.</option>
                                            <option value="รศ." <?= ($selectedPrefix == "รศ.") ? 'selected' : '' ?>>รศ.</option>
                                            <option value="Mr." <?= ($selectedPrefix == "Mr.") ? 'selected' : '' ?>>MR</option>
                                            <option value="Mrs." <?= ($selectedPrefix == "Mrs.") ? 'selected' : '' ?>>MRS</option>
                                            <option value="Ms." <?= ($selectedPrefix == "Ms.") ? 'selected' : '' ?>>MS</option>
                                            <option value="Miss" <?= ($selectedPrefix == "Miss") ? 'selected' : '' ?>>MISS</option>
                                            <option value="Dr." <?= ($selectedPrefix == "Dr.") ? 'selected' : '' ?>>Dr.</option>
                                            <option value="พล.ต.อ." <?= ($selectedPrefix == "พล.ต.อ.") ? 'selected' : '' ?>>พลตำรวจเอก</option>
                                            <option value="พล.ต.อ. หญิง" <?= ($selectedPrefix == "พล.ต.อ. หญิง") ? 'selected' : '' ?>>พลตำรวจเอก หญิง</option>
                                            <option value="พล.ต.ท" <?= ($selectedPrefix == "พล.ต.ท") ? 'selected' : '' ?>>พลตำรวจโท</option>
                                            <option value="พล.ต.ท หญิง" <?= ($selectedPrefix == "พล.ต.ท หญิง") ? 'selected' : '' ?>>พลตำรวจโท หญิง</option>
                                            <option value="พล.ต.ต" <?= ($selectedPrefix == "พล.ต.ต") ? 'selected' : '' ?>>พลตำรวจตรี</option>
                                            <option value="พล.ต.ต หญิง" <?= ($selectedPrefix == "พล.ต.ต หญิง") ? 'selected' : '' ?>>พลตำรวจตรี หญิง</option>
                                            <option value="พ.ต.อ." <?= ($selectedPrefix == "พ.ต.อ.") ? 'selected' : '' ?>>พันตำรวจเอก</option>
                                            <option value="พ.ต.อ. หญิง" <?= ($selectedPrefix == "พ.ต.อ. หญิง") ? 'selected' : '' ?>>พันตำรวจเอก หญิง</option>
                                            <option value="พ.ต.อ.(พิเศษ)" <?= ($selectedPrefix == "พ.ต.อ.(พิเศษ)") ? 'selected' : '' ?>>พันตำรวจเอกพิเศษ</option>
                                            <option value="พ.ต.อ.(พิเศษ) หญิง" <?= ($selectedPrefix == "พ.ต.อ.(พิเศษ) หญิง") ? 'selected' : '' ?>>พันตำรวจเอกพิเศษ หญิง</option>
                                            <option value="พ.ต.ท." <?= ($selectedPrefix == "พ.ต.ท.") ? 'selected' : '' ?>>พันตำรวจโท</option>
                                            <option value="พ.ต.ท. หญิง" <?= ($selectedPrefix == "พ.ต.ท. หญิง") ? 'selected' : '' ?>>พันตำรวจโท หญิง</option>
                                            <option value="พ.ต.ต." <?= ($selectedPrefix == "พ.ต.ต.") ? 'selected' : '' ?>>พันตำรวจตรี</option>
                                            <option value="พ.ต.ต. หญิง" <?= ($selectedPrefix == "พ.ต.ต. หญิง") ? 'selected' : '' ?>>พันตำรวจตรี หญิง</option>
                                            <option value="ร.ต.อ." <?= ($selectedPrefix == "ร.ต.อ.") ? 'selected' : '' ?>>ร้อยตำรวจเอก</option>
                                            <option value="ร.ต.อ. หญิง" <?= ($selectedPrefix == "ร.ต.อ. หญิง") ? 'selected' : '' ?>>ร้อยตำรวจเอก หญิง</option>
                                            <option value="ร.ต.ท." <?= ($selectedPrefix == "ร.ต.ท.") ? 'selected' : '' ?>>ร้อยตำรวจโท</option>
                                            <option value="ร.ต.ท. หญิง" <?= ($selectedPrefix == "ร.ต.ท. หญิง") ? 'selected' : '' ?>>ร้อยตำรวจโท หญิง</option>
                                            <option value="ร.ต.ต." <?= ($selectedPrefix == "ร.ต.ต.") ? 'selected' : '' ?>>ร้อยตำรวจตรี</option>
                                            <option value="ร.ต.ต. หญิง" <?= ($selectedPrefix == "ร.ต.ต. หญิง") ? 'selected' : '' ?>>ร้อยตำรวจตรี หญิง</option>
                                            <option value="ด.ต." <?= ($selectedPrefix == "ด.ต.") ? 'selected' : '' ?>>นายดาบตำรวจ</option>
                                            <option value="ด.ต. หญิง" <?= ($selectedPrefix == "ด.ต. หญิง") ? 'selected' : '' ?>>ดาบตำรวจหญิง</option>
                                            <option value="ส.ต.อ." <?= ($selectedPrefix == "ส.ต.อ.") ? 'selected' : '' ?>>สิบตำรวจเอก</option>
                                            <option value="ส.ต.อ. หญิง" <?= ($selectedPrefix == "ส.ต.อ. หญิง") ? 'selected' : '' ?>>สิบตำรวจเอก หญิง</option>
                                            <option value="ส.ต.ท." <?= ($selectedPrefix == "ส.ต.ท.") ? 'selected' : '' ?>>สิบตำรวจโท</option>
                                            <option value="ส.ต.ท. หญิง" <?= ($selectedPrefix == "ส.ต.ท. หญิง") ? 'selected' : '' ?>>สิบตำรวจโท หญิง</option>
                                            <option value="ส.ต.ต." <?= ($selectedPrefix == "ส.ต.ต.") ? 'selected' : '' ?>>สิบตำรวจตรี</option>
                                            <option value="ส.ต.ต. หญิง" <?= ($selectedPrefix == "ส.ต.ต. หญิง") ? 'selected' : '' ?>>สิบตำรวจตรี หญิง</option>
                                            <option value="จ.ส.ต." <?= ($selectedPrefix == "จ.ส.ต.") ? 'selected' : '' ?>>จ่าสิบตำรวจ</option>
                                            <option value="จ.ส.ต. หญิง" <?= ($selectedPrefix == "จ.ส.ต. หญิง") ? 'selected' : '' ?>>จ่าสิบตำรวจ หญิง</option>
                                            <option value="พลฯ" <?= ($selectedPrefix == "พลฯ") ? 'selected' : '' ?>>พลตำรวจ</option>
                                            <option value="พลฯ หญิง" <?= ($selectedPrefix == "พลฯ หญิง") ? 'selected' : '' ?>>พลตำรวจ หญิง</option>
                                            <option value="พล.อ." <?= ($selectedPrefix == "พล.อ.") ? 'selected' : '' ?>>พลเอก</option>
                                            <option value="พล.อ. หญิง" <?= ($selectedPrefix == "พล.อ. หญิง") ? 'selected' : '' ?>>พลเอก หญิง</option>
                                            <option value="พล.ท." <?= ($selectedPrefix == "พล.ท.") ? 'selected' : '' ?>>พลโท</option>
                                            <option value="พล.ท. หญิง" <?= ($selectedPrefix == "พล.ท. หญิง") ? 'selected' : '' ?>>พลโท หญิง</option>
                                            <option value="พล.ต." <?= ($selectedPrefix == "พล.ต.") ? 'selected' : '' ?>>พลตรี</option>
                                            <option value="พล.ต.หญิง" <?= ($selectedPrefix == "พล.ต.หญิง") ? 'selected' : '' ?>>พลตรี หญิง</option>
                                            <option value="พ.อ." <?= ($selectedPrefix == "พ.อ.") ? 'selected' : '' ?>>พันเอก</option>
                                            <option value="พ.อ.หญิง" <?= ($selectedPrefix == "พ.อ.หญิง") ? 'selected' : '' ?>>พันเอก หญิง</option>
                                            <option value="พ.อ.(พิเศษ)" <?= ($selectedPrefix == "พ.อ.(พิเศษ)") ? 'selected' : '' ?>>พันเอกพิเศษ</option>
                                            <option value="พ.อ.(พิเศษ) หญิง" <?= ($selectedPrefix == "พ.อ.(พิเศษ) หญิง") ? 'selected' : '' ?>>พันเอกพิเศษ หญิง</option>
                                            <option value="พ.ท." <?= ($selectedPrefix == "พ.ท.") ? 'selected' : '' ?>>พันโท</option>
                                            <option value="พ.ท.หญิง" <?= ($selectedPrefix == "พ.ท.หญิง") ? 'selected' : '' ?>>พันโท หญิง</option>
                                            <option value="พ.ต." <?= ($selectedPrefix == "พ.ต.") ? 'selected' : '' ?>>พันตรี</option>
                                            <option value="พ.ต.หญิง" <?= ($selectedPrefix == "พ.ต.หญิง") ? 'selected' : '' ?>>พันตรี หญิง</option>
                                            <option value="ร.อ." <?= ($selectedPrefix == "ร.อ.") ? 'selected' : '' ?>>ร้อยเอก</option>
                                            <option value="ร.อ.หญิง" <?= ($selectedPrefix == "ร.อ.หญิง") ? 'selected' : '' ?>>ร้อยเอก หญิง</option>
                                            <option value="ร.ท." <?= ($selectedPrefix == "ร.ท.") ? 'selected' : '' ?>>ร้อยโท</option>
                                            <option value="ร.ท.หญิง" <?= ($selectedPrefix == "ร.ท.หญิง") ? 'selected' : '' ?>>ร้อยโท หญิง</option>
                                            <option value="ร.ต." <?= ($selectedPrefix == "ร.ต.") ? 'selected' : '' ?>>ร้อยตรี</option>
                                            <option value="ร.ต.หญิง" <?= ($selectedPrefix == "ร.ต.หญิง") ? 'selected' : '' ?>>ร้อยตรี หญิง</option>
                                            <option value="ส.อ." <?= ($selectedPrefix == "ส.อ.") ? 'selected' : '' ?>>สิบเอก</option>
                                            <option value="ส.อ.หญิง" <?= ($selectedPrefix == "ส.อ.หญิง") ? 'selected' : '' ?>>สิบเอก หญิง</option>
                                            <option value="ส.ท." <?= ($selectedPrefix == "ส.ท.") ? 'selected' : '' ?>>สิบโท</option>
                                            <option value="ส.ท.หญิง" <?= ($selectedPrefix == "ส.ท.หญิง") ? 'selected' : '' ?>>สิบโท หญิง</option>
                                            <option value="ส.ต." <?= ($selectedPrefix == "ส.ต.") ? 'selected' : '' ?>>สิบตรี</option>
                                            <option value="ส.ต.หญิง" <?= ($selectedPrefix == "ส.ต.หญิง") ? 'selected' : '' ?>>สิบตรี หญิง</option>
                                            <option value="จ.ส.อ." <?= ($selectedPrefix == "จ.ส.อ.") ? 'selected' : '' ?>>จ่าสิบเอก</option>
                                            <option value="จ.ส.อ.หญิง" <?= ($selectedPrefix == "จ.ส.อ.หญิง") ? 'selected' : '' ?>>จ่าสิบเอก หญิง</option>
                                            <option value="จ.ส.ท." <?= ($selectedPrefix == "จ.ส.ท.") ? 'selected' : '' ?>>จ่าสิบโท</option>
                                            <option value="จ.ส.ท.หญิง" <?= ($selectedPrefix == "จ.ส.ท.หญิง") ? 'selected' : '' ?>>จ่าสิบโท หญิง</option>
                                            <option value="จ.ส.ต." <?= ($selectedPrefix == "จ.ส.ต.") ? 'selected' : '' ?>>จ่าสิบตรี</option>
                                            <option value="จ.ส.ต.หญิง" <?= ($selectedPrefix == "จ.ส.ต.หญิง") ? 'selected' : '' ?>>จ่าสิบตรี หญิง</option>
                                            <option value="ว่าที่ พ.ต." <?= ($selectedPrefix == "ว่าที่ พ.ต.") ? 'selected' : '' ?>>ว่าที่ พ.ต.</option>
                                            <option value="ว่าที่ พ.ต. หญิง" <?= ($selectedPrefix == "ว่าที่ พ.ต. หญิง") ? 'selected' : '' ?>>ว่าที่ พ.ต. หญิง</option>
                                            <option value="ว่าที่ ร.อ." <?= ($selectedPrefix == "ว่าที่ ร.อ.") ? 'selected' : '' ?>>ว่าที่ ร.อ.</option>
                                            <option value="ว่าที่ ร.อ. หญิง" <?= ($selectedPrefix == "ว่าที่ ร.อ. หญิง") ? 'selected' : '' ?>>ว่าที่ ร.อ. หญิง</option>
                                            <option value="ว่าที่ ร.ท." <?= ($selectedPrefix == "ว่าที่ ร.ท.") ? 'selected' : '' ?>>ว่าที่ ร.ท.</option>
                                            <option value="ว่าที่ ร.ท. หญิง" <?= ($selectedPrefix == "ว่าที่ ร.ท. หญิง") ? 'selected' : '' ?>>ว่าที่ ร.ท. หญิง</option>
                                            <option value="ว่าที่ ร.ต." <?= ($selectedPrefix == "ว่าที่ ร.ต.") ? 'selected' : '' ?>>ว่าที่ ร.ต.</option>
                                            <option value="ว่าที่ ร.ต. หญิง" <?= ($selectedPrefix == "ว่าที่ ร.ต. หญิง") ? 'selected' : '' ?>>ว่าที่ ร.ต. หญิง</option>
                                            <option value="พล.ร.อ." <?= ($selectedPrefix == "พล.ร.อ.") ? 'selected' : '' ?>>พลเรือเอก</option>
                                            <option value="พล.ร.อ.หญิง" <?= ($selectedPrefix == "พล.ร.อ.หญิง") ? 'selected' : '' ?>>พลเรือเอก หญิง</option>
                                            <option value="พล.ร.ท." <?= ($selectedPrefix == "พล.ร.ท.") ? 'selected' : '' ?>>พลเรือโท</option>
                                            <option value="พล.ร.ท.หญิง" <?= ($selectedPrefix == "พล.ร.ท.หญิง") ? 'selected' : '' ?>>พลเรือโท หญิง</option>
                                            <option value="พล.ร.ต." <?= ($selectedPrefix == "พล.ร.ต.") ? 'selected' : '' ?>>พลเรือตรี</option>
                                            <option value="พล.ร.ต.หญิง" <?= ($selectedPrefix == "พล.ร.ต.หญิง") ? 'selected' : '' ?>>พลเรือตรี หญิง</option>
                                            <option value="น.อ." <?= ($selectedPrefix == "น.อ.") ? 'selected' : '' ?>>นาวาเอก</option>
                                            <option value="น.อ.หญิง" <?= ($selectedPrefix == "น.อ.หญิง") ? 'selected' : '' ?>>นาวาเอก หญิง</option>
                                            <option value="น.อ.(พิเศษ)" <?= ($selectedPrefix == "น.อ.(พิเศษ)") ? 'selected' : '' ?>>นาวาเอกพิเศษ</option>
                                            <option value="น.อ.(พิเศษ) หญิง" <?= ($selectedPrefix == "น.อ.(พิเศษ) หญิง") ? 'selected' : '' ?>>นาวาเอกพิเศษ หญิง</option>
                                            <option value="น.ท." <?= ($selectedPrefix == "น.ท.") ? 'selected' : '' ?>>นาวาโท</option>
                                            <option value="น.ท.หญิง" <?= ($selectedPrefix == "น.ท.หญิง") ? 'selected' : '' ?>>นาวาโท หญิง</option>
                                            <option value="น.ต." <?= ($selectedPrefix == "น.ต.") ? 'selected' : '' ?>>นาวาตรี</option>
                                            <option value="น.ต.หญิง" <?= ($selectedPrefix == "น.ต.หญิง") ? 'selected' : '' ?>>นาวาตรี หญิง</option>
                                            <option value="ร.อ." <?= ($selectedPrefix == "ร.อ.") ? 'selected' : '' ?>>เรือเอก</option>
                                            <option value="ร.อ.หญิง" <?= ($selectedPrefix == "ร.อ.หญิง") ? 'selected' : '' ?>>เรือเอก หญิง</option>
                                            <option value="ร.ท." <?= ($selectedPrefix == "ร.ท.") ? 'selected' : '' ?>>เรือโท</option>
                                            <option value="ร.ท.หญิง" <?= ($selectedPrefix == "ร.ท.หญิง") ? 'selected' : '' ?>>เรือโท หญิง</option>
                                            <option value="ร.ต." <?= ($selectedPrefix == "ร.ต.") ? 'selected' : '' ?>>เรือตรี</option>
                                            <option value="ร.ต.หญิง" <?= ($selectedPrefix == "ร.ต.หญิง") ? 'selected' : '' ?>>เรือตรี หญิง</option>
                                            <option value="พ.จ.อ." <?= ($selectedPrefix == "พ.จ.อ.") ? 'selected' : '' ?>>พันจ่าเอก</option>
                                            <option value="พ.จ.อ.หญิง" <?= ($selectedPrefix == "พ.จ.อ.หญิง") ? 'selected' : '' ?>>พันจ่าเอก หญิง</option>
                                            <option value="พ.จ.ท." <?= ($selectedPrefix == "พ.จ.ท.") ? 'selected' : '' ?>>พันจ่าโท</option>
                                            <option value="พ.จ.ท.หญิง" <?= ($selectedPrefix == "พ.จ.ท.หญิง") ? 'selected' : '' ?>>พันจ่าโท หญิง</option>
                                            <option value="พ.จ.ต." <?= ($selectedPrefix == "พ.จ.ต.") ? 'selected' : '' ?>>พันจ่าตรี</option>
                                            <option value="พ.จ.ต.หญิง" <?= ($selectedPrefix == "พ.จ.ต.หญิง") ? 'selected' : '' ?>>พันจ่าตรี หญิง</option>
                                            <option value="จ.อ." <?= ($selectedPrefix == "จ.อ.") ? 'selected' : '' ?>>จ่าเอก</option>
                                            <option value="จ.อ.หญิง" <?= ($selectedPrefix == "จ.อ.หญิง") ? 'selected' : '' ?>>จ่าเอก หญิง</option>
                                            <option value="จ.ท." <?= ($selectedPrefix == "จ.ท.") ? 'selected' : '' ?>>จ่าโท</option>
                                            <option value="จ.ท.หญิง" <?= ($selectedPrefix == "จ.ท.หญิง") ? 'selected' : '' ?>>จ่าโท หญิง</option>
                                            <option value="จ.ต." <?= ($selectedPrefix == "จ.ต.") ? 'selected' : '' ?>>จ่าตรี</option>
                                            <option value="จ.ต.หญิง" <?= ($selectedPrefix == "จ.ต.หญิง") ? 'selected' : '' ?>>จ่าตรี หญิง</option>
                                            <option value="พล.อ.อ." <?= ($selectedPrefix == "พล.อ.อ.") ? 'selected' : '' ?>>พลอากาศเอก</option>
                                            <option value="พล.อ.อ.หญิง" <?= ($selectedPrefix == "พล.อ.อ.หญิง") ? 'selected' : '' ?>>พลอากาศเอก หญิง</option>
                                            <option value="พล.อ.ท." <?= ($selectedPrefix == "พล.อ.ท.") ? 'selected' : '' ?>>พลอากาศโท</option>
                                            <option value="พล.อ.ท.หญิง" <?= ($selectedPrefix == "พล.อ.ท.หญิง") ? 'selected' : '' ?>>พลอากาศโท หญิง</option>
                                            <option value="พล.อ.ต." <?= ($selectedPrefix == "พล.อ.ต.") ? 'selected' : '' ?>>พลอากาศตรี</option>
                                            <option value="พล.อ.ต.หญิง" <?= ($selectedPrefix == "พล.อ.ต.หญิง") ? 'selected' : '' ?>>พลอากาศตรี หญิง</option>
                                            <option value="น.อ." <?= ($selectedPrefix == "น.อ.") ? 'selected' : '' ?>>นาวาอากาศเอก</option>
                                            <option value="น.อ.หญิง" <?= ($selectedPrefix == "น.อ.หญิง") ? 'selected' : '' ?>>นาวาอากาศเอก หญิง</option>
                                            <option value="น.อ.(พิเศษ)" <?= ($selectedPrefix == "น.อ.(พิเศษ)") ? 'selected' : '' ?>>นาวาอากาศเอกพิเศษ</option>
                                            <option value="น.อ.(พิเศษ) หญิง" <?= ($selectedPrefix == "น.อ.(พิเศษ) หญิง") ? 'selected' : '' ?>>นาวาอากาศเอกพิเศษ หญิง</option>
                                            <option value="น.ท." <?= ($selectedPrefix == "น.ท.") ? 'selected' : '' ?>>นาวาอากาศโท</option>
                                            <option value="น.ท.หญิง" <?= ($selectedPrefix == "น.ท.หญิง") ? 'selected' : '' ?>>นาวาอากาศโท หญิง</option>
                                            <option value="น.ต." <?= ($selectedPrefix == "น.ต.") ? 'selected' : '' ?>>นาวาอากาศตรี</option>
                                            <option value="น.ต.หญิง" <?= ($selectedPrefix == "น.ต.หญิง") ? 'selected' : '' ?>>นาวาอากาศตรี หญิง</option>
                                            <option value="ร.อ." <?= ($selectedPrefix == "ร.อ.") ? 'selected' : '' ?>>เรืออากาศเอก</option>
                                            <option value="ร.อ.หญิง" <?= ($selectedPrefix == "ร.อ.หญิง") ? 'selected' : '' ?>>เรืออากาศเอก หญิง</option>
                                            <option value="ร.ท." <?= ($selectedPrefix == "ร.ท.") ? 'selected' : '' ?>>เรืออากาศโท</option>
                                            <option value="ร.ท.หญิง" <?= ($selectedPrefix == "ร.ท.หญิง") ? 'selected' : '' ?>>เรืออากาศโท หญิง</option>
                                            <option value="ร.ต." <?= ($selectedPrefix == "ร.ต.") ? 'selected' : '' ?>>เรืออากาศตรี</option>
                                            <option value="ร.ต.หญิง" <?= ($selectedPrefix == "ร.ต.หญิง") ? 'selected' : '' ?>>เรืออากาศตรี หญิง</option>
                                            <option value="พ.อ.อ." <?= ($selectedPrefix == "พ.อ.อ.") ? 'selected' : '' ?>>พันจ่าอากาศเอก</option>
                                            <option value="พ.อ.อ.หญิง" <?= ($selectedPrefix == "พ.อ.อ.หญิง") ? 'selected' : '' ?>>พันจ่าอากาศเอก หญิง</option>
                                            <option value="พ.อ.ท." <?= ($selectedPrefix == "พ.อ.ท.") ? 'selected' : '' ?>>พันจ่าอากาศโท</option>
                                            <option value="พ.อ.ท.หญิง" <?= ($selectedPrefix == "พ.อ.ท.หญิง") ? 'selected' : '' ?>>พันจ่าอากาศโท หญิง</option>
                                            <option value="พ.อ.ต." <?= ($selectedPrefix == "พ.อ.ต.") ? 'selected' : '' ?>>พันจ่าอากาศตรี</option>
                                            <option value="พ.อ.ต.หญิง" <?= ($selectedPrefix == "พ.อ.ต.หญิง") ? 'selected' : '' ?>>พันจ่าอากาศตรี หญิง</option>
                                            <option value="จ.อ." <?= ($selectedPrefix == "จ.อ.") ? 'selected' : '' ?>>จ่าอากาศเอก</option>
                                            <option value="จ.อ.หญิง" <?= ($selectedPrefix == "จ.อ.หญิง") ? 'selected' : '' ?>>จ่าอากาศเอก หญิง</option>
                                            <option value="จ.ท." <?= ($selectedPrefix == "จ.ท.") ? 'selected' : '' ?>>จ่าอากาศโท</option>
                                            <option value="จ.ท.หญิง" <?= ($selectedPrefix == "จ.ท.หญิง") ? 'selected' : '' ?>>จ่าอากาศโท หญิง</option>
                                            <option value="จ.ต." <?= ($selectedPrefix == "จ.ต.") ? 'selected' : '' ?>>จ่าอากาศตรี</option>
                                            <option value="จ.ต.หญิง" <?= ($selectedPrefix == "จ.ต.หญิง") ? 'selected' : '' ?>>จ่าอากาศตรี หญิง</option>
                                            <option value="หม่อม" <?= ($selectedPrefix == "หม่อม") ? 'selected' : '' ?>>หม่อม</option>
                                            <option value="ม.จ." <?= ($selectedPrefix == "ม.จ.") ? 'selected' : '' ?>>หม่อมเจ้า</option>
                                            <option value="ม.ร.ว." <?= ($selectedPrefix == "ม.ร.ว.") ? 'selected' : '' ?>>หม่อมราชวงศ์</option>
                                            <option value="ม.ล." <?= ($selectedPrefix == "ม.ล.") ? 'selected' : '' ?>>หม่อมหลวง</option>
                                            <option value="นพ." <?= ($selectedPrefix == "นพ.") ? 'selected' : '' ?>>นพ.</option>
                                            <option value="พญ." <?= ($selectedPrefix == "พญ.") ? 'selected' : '' ?>>แพทย์หญิง</option>
                                            <option value="นสพ." <?= ($selectedPrefix == "นสพ.") ? 'selected' : '' ?>>สัตวแพทย์</option>
                                            <option value="สพญ." <?= ($selectedPrefix == "สพญ.") ? 'selected' : '' ?>>สพญ.</option>
                                            <option value="ทพ." <?= ($selectedPrefix == "ทพ.") ? 'selected' : '' ?>>ทพ.</option>
                                            <option value="ทพญ." <?= ($selectedPrefix == "ทพญ.") ? 'selected' : '' ?>>ทพญ.</option>
                                            <option value="ภก." <?= ($selectedPrefix == "ภก.") ? 'selected' : '' ?>>เภสัชกร</option>
                                            <option value="ภกญ." <?= ($selectedPrefix == "ภกญ.") ? 'selected' : '' ?>>ภกญ.</option>
                                            <option value="พระ" <?= ($selectedPrefix == "พระ") ? 'selected' : '' ?>>พระ</option>
                                            <option value="พระครู" <?= ($selectedPrefix == "พระครู") ? 'selected' : '' ?>>พระครู</option>
                                            <option value="พระมหา" <?= ($selectedPrefix == "พระมหา") ? 'selected' : '' ?>>พระมหา</option>
                                            <option value="พระสมุห์" <?= ($selectedPrefix == "พระสมุห์") ? 'selected' : '' ?>>พระสมุห์</option>
                                            <option value="พระอธิการ" <?= ($selectedPrefix == "พระอธิการ") ? 'selected' : '' ?>>พระอธิการ</option>
                                            <option value="สามเณร" <?= ($selectedPrefix == "สามเณร") ? 'selected' : '' ?>>สามเณร</option>
                                            <option value="แม่ชี" <?= ($selectedPrefix == "แม่ชี") ? 'selected' : '' ?>>แม่ชี</option>
                                            <option value="บาทหลวง" <?= ($selectedPrefix == "บาทหลวง") ? 'selected' : '' ?>>บาทหลวง</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="writer_fname">ชื่อผู้เขียน</label>
                                        <input type="text" class="form-control" name="writer_fname[]" value="<?= $value["writer_fname"];?>">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="writer_lname">นามสกุล</label>
                                        <input type="text" class="form-control" name="writer_lname[]" value="<?= $value["writer_lname"];?>">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="writer_main">ประเภทผู้เขียน</label>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="writer_main[<?= $key;?>]" value="<?= $checkedType;?>" <?= ($checkedType == 1) ? 'checked' : '' ?>>
                                            <label class="form-check-label" for="writer_main1">
                                                ผู้เขียนหลัก
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-2 pt-4">
                                    <?php
                                        if($key == 0) {
                                            $btnColor = 'btn-primary';
                                            $btnAction = 'add-author';
                                            $btnShow = 'เพิ่มผู้เขียน';
                                        } else {
                                            $btnColor = 'btn-danger';
                                            $btnAction = 'remove-author';
                                            $btnShow = 'ลบผู้เขียน';
                                        }
                                    ?>
                                    <button type="button" class="btn <?= $btnColor.' '.$btnAction;?>" writer-id="<?= $value["writer_id"];?>"><?= $btnShow;?></button>
                                </div>
                            </div>
                            <?php }?>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="item_issued_day">วันที่ออก</label>
                                    <select class="form-control" name="item_issued_day">
                                        <?php
                                            for ($i = 1; $i <= 31; $i++) {
                                                $select5 = $i == $obj[0]["item_issued_day"] ? " selected" : "";
                                                echo '<option value="'.$i.'"'.$select5.'>'.$i.'</option>';
                                            }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="item_issued_month">เดือน</label>
                                    <select class="form-control" name="item_issued_month">
                                        <?php
                                            $monthsThai = [
                                                "มกราคม", "กุมภาพันธ์", "มีนาคม", "เมษายน", "พฤษภาคม", "มิถุนายน",
                                                "กรกฎาคม", "สิงหาคม", "กันยายน", "ตุลาคม", "พฤศจิกายน", "ธันวาคม"
                                            ];
                                            foreach ($monthsThai as $index => $month) {
                                                $select4 = ($index + 1) == $obj[0]["item_issued_month"] ? " selected" : "";
                                                echo '<option value="'.($index + 1).'"'.$select4.'>'.$month.'</option>';
                                            }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="item_issued_year">ปี</label>
                                    <input type="text" class="form-control" name="item_issued_year" value="<?= $obj[0]["item_issued_year"];?>">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="item_description">คำอธิบาย</label>
                            <input type="text" class="form-control" name="item_description" value="<?= $obj[0]["item_description"];?>">
                        </div>
                        <div class="form-group">
                            <label for="item_abstract">บทคัดย่อ</label>
                            <textarea id="item_abstract"><?= $obj[0]["item_abstract"];?></textarea>
                            <input type="hidden" name="item_abstract" value="<?= $obj[0]["item_abstract"];?>">
                        </div>
                        <div class="form-group">
                            <label for="item_sponsorship">หน่วยงานสนับสนุน</label>
                            <input type="text" class="form-control" name="item_sponsorship" value="<?= $obj[0]["item_sponsorship"];?>">
                        </div>
                        <div class="form-group">
                            <label for="item_citation">การอ้างอิง</label>
                            <textarea type="text" class="form-control" name="item_citation"><?= $obj[0]["item_citation"];?></textarea>
                        </div>
                        <div class="form-group">
                            <label for="item_uri">ลิงค์อ่าน e-book</label>
                            <input type="text" class="form-control" name="item_uri" value="<?= $obj[0]["item_uri"];?>">
                        </div>
                        <div class="form-group">
                            <label for="item_publisher">สำนักพิมพ์</label>
                            <input type="text" class="form-control" name="item_publisher" value="<?= $obj[0]["item_publisher"];?>">
                        </div>
                        <?php
                            $subject = $DATABASE->QueryObj("SELECT * FROM tb_subject WHERE item_id = '".$_GET["item_id"]."'");
                            foreach ($subject as $i => $v) {
                        ?>
                        <div class="row subject" data-action="add_subject">
                            <input type="hidden" name="subject_id[]" value="<?= $v['subject_id']; ?>">
                            <div class="col-md-10">
                                <div class="form-group">
                                    <label for="subject_name">หัวเรื่อง</label>
                                    <input type="text" class="form-control" name="subject_name[]" value="<?= $v["subject_name"];?>">
                                </div>
                            </div>
                            <div class="col-md-2 pt-4">
                                <?php
                                    if($i == 0) {
                                        $btnColor2 = 'btn-primary';
                                        $btnAction2 = 'add-subject';
                                        $btnShow2 = 'เพิ่มหัวเรื่อง';
                                    } else {
                                        $btnColor2 = 'btn-danger';
                                        $btnAction2 = 'remove-subject';
                                        $btnShow2 = 'ลบหัวเรื่อง';
                                    }
                                ?>
                                <button type="button" class="btn <?= $btnColor2.' '.$btnAction2;?>" subject-id="<?= $v["subject_id"];?>"><?= $btnShow2;?></button>
                            </div>
                        </div>
                        <?php }?>
                        <div class="form-group">
                            <label for="exampleInputEmail1">ประเภทงาน</label>
                            <select class="form-control" name="type_id" style="width: 100%;">
                                <?php
                                    $obj4 = $DATABASE->QueryObj("SELECT * FROM tb_type");
                                    foreach ($obj4 as $key3 => $value3) {
                                        $select3 = $value3["type_id"] == $obj[0]["type_id"] ? " selected" : "";
                                        echo '<option value="'.$value3["type_id"].'"'.$select3.'>'.$value3["type_name"].'</option>';
                                    }
                                ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <button type="button" class="btn btn-warning" id="edit-item">แก้ไขรายการ</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>