
<link rel="stylesheet" href="pages/home/style.css">
<script src="pages/home/script.js"></script>
<img src="img/banner.jpg" alt="#" class="img-fluid" width="100%" id="banner-img">
<div class="container">
    <div class="search_widget">
        <div class="input-group mb-3 search_input">
            <input type="text" class="form-control searchPlace" placeholder="ค้นหาจากชื่อเรื่อง ชื่อผู้แต่ง และคำสำคัญ" id="serch-input" onfocus="this.placeholder = ''">
            <div class="input-group-append">
                <button class="btn" type="button" id="search"><i class="ti-search"></i></button>
            </div>
        </div>
    </div>
</div>
<section class="feature_part my_section_padding">
    <div class="container">
        <div class="my_single_feature_part">
            <h4>Wellcome</h4>
        </div>
    </div>
</section>

<section class="blog_area my_section_padding2">
    <div class="container">
        <h2 class="contact-title chooseComm">เลือกขอบเขตเนื้อหา</h2>
        <p id="pagination_info"></p>
        <div class="blog_left_sidebar">
            <div class="row community_data">
            </div>

            <nav class="my-blog-pagination justify-content-center d-flex" id="pagination_link">
            </nav>
        </div>
    </div>
</section><hr>

<section class="blog_area my_section_padding2">
    <div class="container">
        <h2 class="contact-title lastItem">รายการล่าสุด</h2>
        <div id="item-data"></div>
        <a href="?p=search" class="genric-btn primary-border viewAll">ดูทั้งหมด</a>
    </div>
</section>