<link rel="stylesheet" href="pages/items/style.css">
<script src="pages/items/script.js"></script>
<div class="breadcrumb_section">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb my_breadcrumb">
                <li class="breadcrumb-item"><a href="./">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">items</li>
            </ol>
        </nav>
    </div>
</div>
<section class="blog_area">
    <div class="container">
        <?php
            $sql = "SELECT * FROM tb_item
                    INNER JOIN tb_collection ON tb_item.collection_id = tb_collection.collection_id
                    WHERE MD5(item_id) = '".$_GET["item_id"]."'";
            $obj = $DATABASE->QueryObj($sql);
        ?>
        <input type="hidden" id="item_id" value="<?php echo $_GET["item_id"];?>">
        <h3><?= $obj[0]["item_title"];?></h3>
        <div class="row">
            <div class="col-lg-4">
                <div class="blog_right_sidebar">
                    <aside class="single_sidebar_widget search_widget">
                        <?php
                            $sql2 = "SELECT * FROM tb_file WHERE file_type='cover' ORDER BY file_id DESC LIMIT 1";
                            $cover = $DATABASE->QueryObj($sql2);
                            if (sizeof($cover) > 0) {
                                echo '<img src="files/item/'.$obj[0]["item_id"].'/'.$cover[0]["file_name"].'" alt="Cover Image" width="40%">';
                            } else {
                                echo '<img src="img/930231.png" alt="No Cover" width="40%">';
                            }
                        ?>
                        <br><br>
                        <h4>ไฟล์</h4>
                        <div id="file-data"></div>
                        <br>
                        <h4>วันที่</h4>
                        <p><?= $obj[0]["item_issued_day"].' - '.$obj[0]["item_issued_month"].' - '.$obj[0]["item_issued_year"];?></p>
                        <br>
                        <h4>ผู้เขียน</h4>
                        <?php
                            $sql3 = "SELECT * FROM tb_writer WHERE item_id = '".$obj[0]["item_id"]."'";
                            $obj2 = $DATABASE->QueryObj($sql3);
                            foreach ($obj2 as $key => $author) {
                                echo '<p>'.$author["writer_prefix"].$author["writer_fname"].' '.$author["writer_lname"].'</p>';
                            }
                        ?>
                        <br>
                        <h4>สำนักพิมพ์</h4>
                        <p><?= $obj[0]["item_publisher"];?></p>
                    </aside>
                </div>
            </div>
            <div class="col-lg-8 mb-5 mb-lg-0">
                <div class="blog_left_sidebar">
                    <article class="blog_item">
                        <div class="blog_details">
                            <h2>เชิงนามธรรม</h2>
                            <p><?= $obj[0]["item_abstract"];?></p>
                            <h2>คำอธิบาย</h2>
                            <p><?= $obj[0]["item_description"];?></p>
                            <h2>คำหลัก</h2>
                            <p>
                            <?php
                                $sql4 = "SELECT * FROM tb_subject WHERE item_id = '".$obj[0]["item_id"]."'";
                                $obj3 = $DATABASE->QueryObj($sql4);
                                $subject_name = "";
                                foreach ($obj3 as $row) {
                                    $subject_name .= $row["subject_name"] . ", ";
                                }
                                $subject_name = rtrim($subject_name, ", ");
                                echo $subject_name;
                            ?>
                            </p>
                            <h2>คอลเลคชัน</h2>
                            <p><?= $obj[0]["collection_name"];?></p>

                            <?php
                                if ($obj[0]["item_uri"] != "") {
                            ?>
                            <p>อ่านอีบุ๊ก <a href="<?= $obj[0]["item_uri"];?>">คลิ๊กที่นี่</a></p>
                            <?php
                                }
                            ?>
                        </div>
                    </article>
                </div>
            </div>
        </div>
    </div>
</section>