<link rel="stylesheet" href="pages/collection-date/style.css">
<script src="pages/collection-date/script.js"></script>
<div class="breadcrumb_section">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb my_breadcrumb">
                <li class="breadcrumb-item"><a href="./">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">dateissued</li>
            </ol>
        </nav>
    </div>
</div>

<section class="cart_area">
    <div class="container">
        <div class="just-padding">
            <?php
                $sql = "SELECT * FROM tb_collection WHERE MD5(collection_id) = '".$_GET["collection"]."'";
                $obj = $DATABASE->QueryObj($sql);
            ?>
            <input type="hidden" id="collection_id" value="<?php echo $_GET["collection"];?>">
            <h2 class="contact-title" id="collection_name"><?= $obj[0]["collection_name"];?></h2>
            <?php
                if (isset($_GET["community"])) {
                    $link = '&community='.$_GET["community"];
                    $sql = "SELECT * FROM tb_community WHERE MD5(community_id) = '".$_GET["community"]."'";
                    $obj = $DATABASE->QueryObj($sql);
            ?>
            <input type="hidden" id="community_id" value="<?php echo $_GET["community"];?>">
            <h4 id="community_title">ชุมชน : <?= $obj[0]["community_title"];?></h4>
            <?php
                } else {
                    $link = "";
            ?>
            <input type="hidden" id="community_id" value="">
            <?php        
                }
            ?>
            <hr>
            <h3>เรียกดู</h3>
            <ul class="nav nav-pills nav-fill">
                <li class="nav-item my-nav-item">
                    <a class="nav-link my-nav-link" href="?p=collections&collection=<?php echo $_GET["collection"].$link;?>">รายการล่าสุด</a>
                </li>
                <li class="nav-item my-nav-item">
                    <a class="nav-link my-nav-link active">ตามวันที่ออก</a>
                </li>
                <li class="nav-item my-nav-item">
                    <a class="nav-link my-nav-link" href="?p=collection-author&collection=<?php echo $_GET["collection"].$link;?>">โดยผู้เขียน</a>
                </li>
                <li class="nav-item my-nav-item">
                    <a class="nav-link  my-nav-link" href="?p=collection-title&collection=<?php echo $_GET["collection"].$link;?>">ตามชื่อเรื่อง</a>
                </li>
                <li class="nav-item my-nav-item">
                    <a class="nav-link  my-nav-link" href="?p=collection-subject&collection=<?php echo $_GET["collection"].$link;?>">ตามหัวเรื่อง</a>
                </li>
                <li class="nav-item my-nav-item">
                    <a class="nav-link  my-nav-link" href="?p=srsc&collection=<?php echo $_GET["collection"].$link;?>">ตามหมวดหมู่หัวเรื่อง</a>
                </li>
            </ul>
            <br>
            <h4>กรองผลลัพธ์ตามปีหรือเดือน</h4>
            <form class="form-contact contact_form" action="contact_process.php" method="post" id="contactForm"
                novalidate="novalidate">
                <div class="row">
                    <div class="col-sm-3">
                        <div class="form-group">
                            <select class="shipping_select" id="item_issued_year">
                                <option value=""> -- เลือกปี -- </option>
                                <?php
                                    $sql_year = "SELECT item_issued_year FROM tb_item GROUP BY item_issued_year ORDER BY item_issued_year DESC";
                                    $obj_year = $DATABASE->QueryObj($sql_year);
                                    foreach ($obj_year as $key_year => $year) {
                                        echo '<option value="'.$year["item_issued_year"].'">'.$year["item_issued_year"].'</option>';
                                    }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group">
                            <select class="shipping_select" id="item_issued_month">
                                <option value=""> -- เลือกเดือน -- </option>
                                <?php
                                    $monthsThai = [
                                        "มกราคม", "กุมภาพันธ์", "มีนาคม", "เมษายน", "พฤษภาคม", "มิถุนายน",
                                        "กรกฎาคม", "สิงหาคม", "กันยายน", "ตุลาคม", "พฤศจิกายน", "ธันวาคม"
                                    ];
                                    foreach ($monthsThai as $index => $month) {
                                        echo '<option value="'.($index + 1).'">'.$month.'</option>';
                                    }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <div class="search_widget">
                                <div class="input-group mb-3 search_input">
                                    <input type="text" class="form-control" id="item_issued_day" placeholder="กรองผลลัพธ์ตามวันที่..."
                                        onfocus="this.placeholder = ''"
                                        onblur="this.placeholder = 'กรองผลลัพธ์ตามวันที่...'">
                                    <div class="input-group-append">
                                        <button class="btn" type="button" id="search-by-date"><i class="ti-book"></i> เรียกดู</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</section>

<section class="blog_area my_section_padding2">
    <div class="container">
        <p id="pagination_info"></p>
        <div id="item-data"></div>
        <nav class="my-blog-pagination justify-content-center d-flex" id="pagination_link"></nav>
    </div>
</section>