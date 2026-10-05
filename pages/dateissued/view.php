<link rel="stylesheet" href="pages/dateissued/style.css">
<script src="pages/dateissued/script.js"></script>
<div class="breadcrumb_section">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb my_breadcrumb">
                <li class="breadcrumb-item community_title"><a href="./" class="home">หน้าแรก</a></li>
                <li class="breadcrumb-item active year" aria-current="page">ปีที่เผยแพร่</li>
            </ol>
        </nav>
    </div>
</div>

<section class="cart_area">
    <div class="container">
        <div class="just-padding">
            <?php
                if (!isset($_GET["community"])) {
            ?>
            <input type="hidden" id="community_id" value="">
            <h2 class="contact-title">กำลังเรียกดู โดย ปีที่เผยแพร่</h2>
            <?php
                } else {
            ?>
            <input type="hidden" id="community_id" value="<?php echo $_GET["community"];?>">
            <h2 class="contact-title" id="title"></h2>
            <div id="img"></div>
            <hr>
            <h3 class="show">เรียกดู</h3>
            <ul class="nav nav-pills nav-fill">
                <li class="nav-item my-nav-item">
                    <a class="nav-link my-nav-link collection" href="?p=communities&community=<?php echo $_GET["community"];?>">คอลเล็กชัน</a>
                </li>
                <li class="nav-item my-nav-item">
                    <a class="nav-link my-nav-link year active">ปีที่เผยแพร่</a>
                </li>
                <li class="nav-item my-nav-item">
                    <a class="nav-link my-nav-link authorLan" href="?p=author&community=<?php echo $_GET["community"];?>">ผู้แต่ง</a>
                </li>
                <li class="nav-item my-nav-item">
                    <a class="nav-link  my-nav-link titleLan" href="?p=title&community=<?php echo $_GET["community"];?>">ชื่อเรื่อง</a>
                </li>
                <li class="nav-item my-nav-item">
                    <a class="nav-link  my-nav-link subLan" href="?p=subject&community=<?php echo $_GET["community"];?>">คำสำคัญ</a>
                </li>
            </ul>
            <br>
            <?php }?>
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