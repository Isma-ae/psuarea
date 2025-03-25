<link rel="stylesheet" href="pages/search/style.css">
<link rel="stylesheet" href="https://code.jquery.com/ui/1.14.1/themes/base/jquery-ui.css">
<script src="pages/search/script.js"></script>
<script src="https://code.jquery.com/ui/1.14.1/jquery-ui.js"></script>
<div class="breadcrumb_section">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb my_breadcrumb">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">search</li>
            </ol>
        </nav>
    </div>
</div>

<section class="cart_area">
    <div class="container">
        <div class="just-padding">
            <div class="row">
                <div class="col-12 col-md-3">
                    <h2 class="contact-title">ตัวกรอง</h2>
                    <div class="product_sidebar">
                        <div class="single_sedebar">
                            <div class="select_option">
                                <div class="select_option_list">ผู้เขียน <i class="right fas fa-caret-down"></i></div>
                                <div class="select_option_dropdown" style="display: none;">
                                    <div id="authur-data"></div>
                                    <p><a href="#" id="showMore">Show More</a>
                                        <a href="#" id="collapse" style="float:right">collapse</a></p>
                                    <input type="text" class="form-control bg-light" id="search-author">
                                </div>
                            </div>
                        </div>
                        <div class="single_sedebar">
                            <div class="select_option">
                                <div class="select_option_list">เรื่อง <i class="right fas fa-caret-down"></i> </div>
                                <div class="select_option_dropdown" style="display: none;">
                                    <div id="subject-data"></div>
                                    <a href="#" id="showMore2">Show More</button>
                                        <a href="#" id="collapse2" style="float:right">collapse</a>
                                        <input type="text" class="form-control bg-light" id="search-subject">
                                </div>
                            </div>
                        </div>
                        <div class="single_sedebar">
                            <div class="select_option">
                                <div class="select_option_list">วันที่ <i class="right fas fa-caret-down"></i> </div>
                                <div class="select_option_dropdown" style="display: none;">
                                    <div class="inputs">
                                        <div class="range-input-group">
                                            <label for="min-input">เริ่ม:</label>
                                            <input type="text" id="min-input" value="" class="form-control bg-light" style="border-radius: 0px;">
                                        </div>
                                        <div class="range-input-group">
                                            <label for="max-input">สิ้นสุด:</label>
                                            <input type="text" id="max-input" value="" class="form-control bg-light" style="border-radius: 0px;">
                                        </div>
                                    </div>
                                    <br>
                                    <div id="slider-range"></div>
                                </div>
                            </div>
                        </div>
                        <div class="single_sedebar">
                            <div class="select_option">
                                <div class="select_option_list">มีไฟล์ <i class="right fas fa-caret-down"></i> </div>
                                <div class="select_option_dropdown" style="display: none;">
                                    <p>
                                        <input type="checkbox" id="has_file1" name="has_file" value="1"
                                            style="width:20%;">
                                        <label for="has_file1">ใช่</label>
                                    </p>
                                </div>
                            </div>
                        </div>
                        <a href="#" class="genric-btn primary radius"><i class="ti-reload"></i> รีเซ็ตตัวกรอง</a>
                    </div>
                    <hr>
                    <h2 class="contact-title">การตั้งค่า</h2>
                    <div style="margin-bottom: 80px;">
                        <h4>เรียงตาม</h4>
                        <div class="form-group">
                            <select class="shipping_select" id="order_by">
                                <option value="1">รายการล่าสุด</option>
                                <option value="2">รายการแรก</option>
                                <option value="3">ชื่อจากน้อยไปมาก</option>
                                <option value="4">ชื่อจากมากไปน้อย</option>
                                <option value="5">ปีจากน้อยไปมาก</option>
                                <option value="6">ปีจากมากไปน้อย</option>
                            </select>
                        </div>
                    </div>
                    <div style="margin-bottom: 80px;">
                        <h4>ผลลัพธ์ต่อหน้า</h4>
                        <div class="form-group">
                            <select class="shipping_select" id="limit">
                                <option value="1">1</option>
                                <option value="5">5</option>
                                <option value="10" selected>10</option>
                                <option value="20">20</option>
                                <option value="40">40</option>
                                <option value="60">60</option>
                                <option value="80">80</option>
                                <option value="100">100</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-9" style="padding-left: 50px;">
                    <div class="search_widget">
                        <div class="input-group mb-3 search_input">
                            <div class="input-group-append">
                                <button class="btn" type="button">psu area ทั้งหมด</button>
                            </div>
                            <?php $search_term = (isset($_GET["search_term"])) ? $_GET["search_term"] : "" ;?>
                            <input type="text" class="form-control" placeholder="กรองผลลัพธ์โดยพิมพ์ชื่อเรื่อง..."
                                id="search_query" value="<?php echo $search_term;?>" onfocus="this.placeholder = ''"
                                onblur="this.placeholder = 'กรองผลลัพธ์โดยพิมพ์ชื่อเรื่อง...'">
                            <div class="input-group-append">
                                <button class="btn" type="button" id="search-button"><i class="ti-book"></i>
                                    เรียกดู</button>
                            </div>
                        </div>
                    </div>
                    <h2 class="contact-title">ผลการค้นหา</h2>
                    <p id="pagination_info"></p>
                    <div id="item-data"></div>
                    <nav class="my-blog-pagination justify-content-center d-flex" id="pagination_link"></nav>
                </div>
            </div>
        </div>
    </div>
</section>