<link rel="stylesheet" href="pages/collection-date/style.css">
<script src="pages/collection-date/script.js"></script>
<div class="breadcrumb_section">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb my_breadcrumb">
                <li class="breadcrumb-item"><a href="./" class="home">หน้าแรก</a></li>
                <li class="breadcrumb-item collection_name" aria-current="page">Data</li>
                <li class="breadcrumb-item year active" aria-current="page">ปีที่เผยแพร่</li>
            </ol>
        </nav>
    </div>
</div>

<section class="cart_area">
    <div class="container">
        <div class="just-padding">
            <input type="hidden" id="collection_id" value="<?php echo $_GET["collection"];?>">
            <h2 class="contact-title collection_name"></h2>
            <?php
                if (isset($_GET["community"])) {
                    $link = '&community='.$_GET["community"];
            ?>
            <input type="hidden" id="community_id" value="<?php echo $_GET["community"];?>">
            <h4><strong class="community">ขอบเขตเนื้อหา </strong><span id="community"> : </span> </h4>
            <?php
                } else {
                    $link = "";
            ?>
            <input type="hidden" id="community_id" value="">
            <?php        
                }
            ?>
            <hr>
            <h3 class="show">เรียกดู</h3>
            <ul class="nav nav-pills nav-fill">
                <li class="nav-item my-nav-item">
                    <a class="nav-link my-nav-link lastItem" href="?p=collections&collection=<?php echo $_GET["collection"].$link;?>">รายการล่าสุด</a>
                </li>
                <li class="nav-item my-nav-item">
                    <a class="nav-link my-nav-link year active">ปีที่เผยแพร่</a>
                </li>
                <li class="nav-item my-nav-item">
                    <a class="nav-link my-nav-link authorLan" href="?p=collection-author&collection=<?php echo $_GET["collection"].$link;?>">ผู้แต่ง</a>
                </li>
                <li class="nav-item my-nav-item">
                    <a class="nav-link  my-nav-link titleLan" href="?p=collection-title&collection=<?php echo $_GET["collection"].$link;?>">ชื่อเรื่อง</a>
                </li>
                <li class="nav-item my-nav-item">
                    <a class="nav-link  my-nav-link subLan" href="?p=collection-subject&collection=<?php echo $_GET["collection"].$link;?>">คำสำคัญ</a>
                </li>
            </ul>
            <br>
            <form class="form-contact contact_form" action="contact_process.php" method="post" id="contactForm"
                novalidate="novalidate">
                <div class="row">
                    <div class="col-sm-5">
                        <h4 class="since">ตั้งแต่ </h4>
                        <div class="form-group">
                            <select class="shipping_select" id="year_start">
                                <option value="" class="selectYear"> -- เลือกปี -- </option>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-5">
                        <h4 class="to">ถึง </h4>
                        <div class="form-group">
                            <select class="shipping_select" id="year_end">
                                <option value="" class="selectYear"> -- เลือกปี -- </option>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <br><br>
                        <button class="btn btn-primary btn-block showButton" id="search-by-date" type="button"><i class="ti-book"></i> เรียกดู</button>
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