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
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="writer_fname">ชื่อผู้เขียน</label>
                                        <input type="text" class="form-control" name="writer_fname[]" value="<?= $value["writer_fname"];?>">
                                    </div>
                                </div>
                                <div class="col-md-4">
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
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="item_issued_month">เดือน</label>
                                    <select class="form-control" name="item_issued_month">
                                        <option value=""> -- เลือกเดือน -- </option>
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
                            <div class="col-md-6">
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
                        <!--<div class="form-group">
                            <label for="exampleInputEmail1">หมวดหมู่</label>
                            <select class="form-control" name="type_id" style="width: 100%;">
                                <?php
                                    $obj4 = $DATABASE->QueryObj("SELECT * FROM tb_type");
                                    foreach ($obj4 as $key3 => $value3) {
                                        $select3 = $value3["type_id"] == $obj[0]["type_id"] ? " selected" : "";
                                        echo '<option value="'.$value3["type_id"].'"'.$select3.'>'.$value3["type_name"].'</option>';
                                    }
                                ?>
                            </select>
                        </div>-->
                        <div class="form-group">
                            <button type="button" class="btn btn-warning" id="edit-item">แก้ไขรายการ</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>