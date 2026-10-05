<link rel="stylesheet" href="pages/items/style.css">
<script src="pages/items/script.js"></script>
<div class="breadcrumb_section">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb my_breadcrumb">
            <li class="breadcrumb-item"><a href="./" class="home">หน้าแรก</a></li>
                <li class="breadcrumb-item item active" aria-current="page">รายการ</li>
            </ol>
        </nav>
    </div>
</div>
<section class="blog_area">
    <div class="container">
        <input type="hidden" id="item_id" value="<?php echo $_GET["item_id"];?>">
        <h3 id="title"></h3>
        <div class="row">
            <div class="col-lg-4">
                <div class="blog_right_sidebar">
                    <aside class="single_sidebar_widget search_widget">
                        <div id="cover"></div>
                        <br><br>
                        <h4 class="file">ไฟล์</h4>
                        <div id="file-data"></div>
                        <br>
                        <h4 class="year">ปีที่เผยแพร่</h4>
                        <p id="year"></p>
                        <br>
                        <h4 class="authorLan">ผู้เขียน</h4>
                        <div id="author"></div>
                        <br>
                        <h4 class="publisher">สำนักพิมพ์</h4>
                        <p id="publisher"></p>
                    </aside>
                </div>
            </div>
            <div class="col-lg-8 mb-5 mb-lg-0">
                <div class="blog_left_sidebar">
                    <article class="blog_item">
                        <div class="blog_details">
                            <h2 class="abstract">บทคัดย่อ / บทสรุป</h2>
                            <p id="abstract"></p>
                            <h2 class="subLan">คำสำคัญ</h2>
                            <p id="sbj"></p>
                            <h2 class="collection">คอลเล็กชัน</h2>
                            <p id="collection"></p>
                            <div id="elink"></div>
                        </div>
                    </article>
                </div>
            </div>
        </div>
    </div>
</section>