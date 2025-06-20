<?php 
ini_set('session.cache_limiter','public');
session_cache_limiter(false);
session_start();
include("config.php");
								
?>
<!DOCTYPE html>
<html lang="en">

<head>
    
<!-- Required meta tags -->
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

<!-- Meta Tags -->
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<link rel="shortcut icon" href="images/favicon.ico">

<!--	Fonts
	========================================================-->
<link href="https://fonts.googleapis.com/css?family=Muli:400,400i,500,600,700&amp;display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css?family=Comfortaa:400,700" rel="stylesheet">

<!--	Css Link
	========================================================-->
<link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="css/bootstrap-slider.css">
<link rel="stylesheet" type="text/css" href="css/jquery-ui.css">
<link rel="stylesheet" type="text/css" href="css/layerslider.css">
<link rel="stylesheet" type="text/css" href="css/color.css" id="color-change">
<link rel="stylesheet" type="text/css" href="css/owl.carousel.min.css">
<link rel="stylesheet" type="text/css" href="css/font-awesome.min.css">
<link rel="stylesheet" type="text/css" href="fonts/flaticon/flaticon.css">
<link rel="stylesheet" type="text/css" href="css/style.css">

<!--	Title
	=========================================================-->
<title>Vastu Homes</title>
<style>
     .overlay-black img {
            width: 100%;  /* Ensures the image fills the container width */
            height: 250px; /* Set a fixed height (adjust as needed) */
            object-fit: cover; /* Maintains aspect ratio and crops if needed */
            
        }
    </style>
</head>
<body>

<!--	Page Loader  -->
<!--<div class="page-loader position-fixed z-index-9999 w-100 bg-white vh-100">
	<div class="d-flex justify-content-center y-middle position-relative">
	  <div class="spinner-border" role="status">
		<span class="sr-only">Loading...</span>
	  </div>
	</div>
</div>  -->
<!--	Page Loader  -->

<div id="page-wrapper">
    <div class="row"> 
        <!--	Header start  -->
		<?php include("include/header.php");?>
        <!--	Header end  -->
		
        <!--	Banner Start   -->
          
        <div class="overlay-black w-100 slider-banner1 position-relative" id="banner" style="width: 100%; height: 500px; position: relative; overflow: hidden;">
    <!-- Video Background -->
    <video autoplay loop muted playsinline style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; object-fit: cover;">
        <source src="images/banner/Background video.mp4" type="video/mp4">
        
    </video>
    <!-- RGBA Overlay -->
    <div style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0, 0, 0, 0.5); z-index: 1;"></div>
    <!-- Content Overlay -->
    <div class="container h-100" style="position: relative; z-index: 2;">
        <div class="row h-100 align-items-center">
            <div class="col-lg-12">
                <div class="text-white" >
                    
                <h1 class="mb-4">
    <span style="font-family:Arial; background: linear-gradient(to right, aqua, violet); -webkit-background-clip: text; background-clip: text; color: transparent;">Vastu Homes</span><br>
    <span id="changing-text">आपली संस्कृती, आपली परंपरा</span>
</h1>

                    <form method="post" action="propertygrid.php">
                        <!-- Search Form -->
                        <div class="row">
                            <div class="col-md-6 col-lg-2">
                                <div class="form-group">
                                    <select class="form-control" name="type">
                                        <option value="">Select Type</option>
                                        <option value="apartment">Apartment</option>
                                        <option value="flat">Flat</option>
                                        <option value="building">Building</option>
                                        <option value="house">House</option>
                                        <option value="villa">Villa</option>
                                        <option value="office">Office</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6 col-lg-2">
                                <div class="form-group">
                                    <select class="form-control" name="stype">
                                        <option value="">Select Status</option>
                                        <option value="rent">Rent</option>
                                        <option value="sale">Sale</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-8 col-lg-6">
                                <div class="form-group">
                                    <input type="text" class="form-control" name="city" placeholder="Enter City" required>
                                </div>
                            </div>
                            <div class="col-md-4 col-lg-2">
                                <div class="form-group">
                                    <button type="submit" name="filter" class="btn btn-success w-100">Search Property</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const sentences = ["आपली संस्कृती,आपली परंपरा", "आधुनिक घरांसाठी एकच नाव!","भविष्य घडवा, आजच तुमचं घर निवडा!"];
        let index = 0;
        const changingText = document.getElementById("changing-text");

        setInterval(() => {
            changingText.textContent = sentences[index];
            index = (index + 1) % sentences.length;
        }, 2000);
    });
</script>


            <!-- Banner End -->
    

<!-- Dot Indicators -->
<div class="dots-container text-center mt-3">
    <span class="dot" data-index="0"></span>
    <span class="dot" data-index="1"></span>
    <span class="dot" data-index="2"></span>
    <span class="dot" data-index="3"></span>
</div>

		<!-----  Our Services  ---->
		
        <!--	Recent Properties  -->
        <div class="full-row">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <h2 class="text-secondary double-down-line text-center mb-4" style="font-family:Arial;">Recent Property</h2>
                    </div>
                    <!--- <div class="col-md-6">
                        <ul class="nav property-btn float-right" id="pills-tab" role="tablist">
                            <li class="nav-item"> <a class="nav-link py-3 active" id="pills-home-tab" data-toggle="pill" href="#pills-home" role="tab" aria-controls="pills-home" aria-selected="true">New</a> </li>
                            <li class="nav-item"> <a class="nav-link py-3" id="pills-profile-tab" data-toggle="pill" href="#pills-profile" role="tab" aria-controls="pills-profile" aria-selected="false">Featured</a> </li>
                            <li class="nav-item"> <a class="nav-link py-3" id="pills-contact-tab2" data-toggle="pill" href="#pills-contact" role="tab" aria-controls="pills-contact" aria-selected="false">Top Sale</a> </li>
                            <li class="nav-item"> <a class="nav-link py-3" id="pills-contact-tab3" data-toggle="pill" href="#pills-resturant" role="tab" aria-controls="pills-contact" aria-selected="false">Best Sale</a> </li>
                        </ul>
                    </div> --->
                    <div class="col-md-12">
                        <div class="tab-content mt-4" id="pills-tabContent">
                            <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home">
                                <div class="row">
								
									<?php $query=mysqli_query($con,"SELECT property.*, user.uname,user.utype,user.uimage FROM `property`,`user` WHERE property.uid=user.uid ORDER BY date DESC LIMIT 9");
										while($row=mysqli_fetch_array($query))
										{
									?>
								
                                    <div class="col-md-6 col-lg-4">
                                        <div class="featured-thumb hover-zoomer mb-4">
                                            <div class="overlay-black overflow-hidden position-relative"> <img src="admin/property/<?php echo $row['18'];?>" alt="pimage">
                                                <div class="featured bg-success text-white">New</div>
                                                <div class="sale bg-success text-white text-capitalize">For <?php echo $row['5'];?></div>
                                                <div class="price text-primary"><b>₹<?php echo $row['13'];?> </b><span class="text-white"><?php echo $row['12'];?> Sqft</span></div>
                                            </div>
                                            <div class="featured-thumb-data shadow-one">
                                                <div class="p-3">
                                                    <h5 class="text-secondary hover-text-success mb-2 text-capitalize"><a href="propertydetail.php?pid=<?php echo $row['0'];?>&uid=<?php echo $row['23']; ?>"><?php echo $row['1'];?></a></h5>
                                                    <span class="location text-capitalize"><i class="fas fa-map-marker-alt text-success"></i> <?php echo $row['14'];?></span> </div>
                                                <div class="bg-gray quantity px-4 pt-4">
                                                    <ul>
                                                        <li><span><?php echo $row['12'];?></span> Sqft</li>
                                                        <li><span><?php echo $row['6'];?></span> Beds</li>
                                                        <li><span><?php echo $row['7'];?></span> Baths</li>
                                                        <li><span><?php echo $row['9'];?></span> Kitchen</li>
                                                        <li><span><?php echo $row['8'];?></span> Balcony</li>
                                                        
                                                    </ul>
                                                </div>
                                                <div class="p-4 d-inline-block w-100">
                                                    <div class="float-left text-capitalize"><i class="fas fa-user text-success mr-1"></i>By : <?php echo $row['uname'];?></div>
                                                    <div class="float-right"><i class="far fa-calendar-alt text-success mr-1"></i> <?php echo date('d-m-Y', strtotime($row['date']));?></div> 
                                                </div>
                                            </div>
                                        </div>
                                    </div>
									<?php } ?>

                                </div>
                            </div>
                            
                            
                           
                        </div>
                    </div>
                </div>
            </div>
        </div>
		<!--	Recent Properties  -->
        
        <!--	Why Choose Us -->
        <div class="full-row living bg-one overlay-secondary-half" style="background-image: url('images/05.jpg'); background-size: cover; background-position: center center; background-repeat: no-repeat;">
            <div class="container">
                <div class="row">
                    <div class="col-md-12 col-lg-6">
                        <div class="living-list pr-4">
                            <h3 class="pb-4 mb-3 text-white" style="font-family:Arial;">Why Choose Us</h3>
                            <ul>
                                <li class="mb-4 text-white d-flex"> 
									<i class="flaticon-reward flat-medium float-left d-table mr-4 text-success" aria-hidden="true"></i>
									<div class="pl-2">
										<h5 class="mb-3" style="font-family:Arial;">Top Rated</h5>
										<p>Discover the perfect harmony of modern living and ancient wisdom at Top Rated Vastu Homes. Explore expertly designed homes that bring balance, positivity, and prosperity into your life, following the timeless principles of Vastu Shastra</p>
									</div>
                                </li>
                                <li class="mb-4 text-white d-flex"> 
									<i class="flaticon-real-estate flat-medium float-left d-table mr-4 text-success" aria-hidden="true"></i>
									<div class="pl-2">
										<h5 class="mb-3" style="font-family:Arial;">Experience Quality</h5>
										<p>Experience homes where tradition meets modernity. Vastu Homes offers meticulously crafted living spaces designed to foster harmony, prosperity, and a sense of well-being</p>
									</div>
                                </li>
                                <li class="mb-4 text-white d-flex"> 
									<i class="flaticon-seller flat-medium float-left d-table mr-4 text-success" aria-hidden="true"></i>
									<div class="pl-2">
										<h5 class="mb-3" style="font-family:Arial;">Experienced Agents</h5>
										<p>Helping you find a home that is both beautiful and in harmony with nature. With years of experience, I guide clients towards Vastu-compliant properties that promote peace and prosperity.</p>
									</div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
		<!--	why choose us -->
		
		<!--	How it work -->
        <div class="full-row">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <h2 class="text-secondary double-down-line text-center mb-5" style="font-family:Arial;">How It Work</h2>
                        </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <div class="icon-thumb-one text-center mb-5">
                            <div class="bg-success text-white rounded-circle position-absolute z-index-9">1</div>
                            <div class="left-arrow"><i class="flaticon-investor flat-medium icon-success" aria-hidden="true"></i></div>
                            <h5 class="text-secondary mt-5 mb-4" style="font-family:Arial;">Discussion</h5>
                            <p>Understand the principles of Vastu Shastra and how they are applied to home design</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="icon-thumb-one text-center mb-5">
                            <div class="bg-success text-white rounded-circle position-absolute z-index-9">2</div>
                            <div class="left-arrow"><i class="flaticon-search flat-medium icon-success" aria-hidden="true"></i></div>
                            <h5 class="text-secondary mt-5 mb-4" style="font-family:Arial;">Files Review</h5>
                            <p>Get your home Vastu-verified! Upload your floor plan and receive expert analysis. Our report helps you understand Vastu doshas and optimize your living space for harmony and well-being.</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="icon-thumb-one text-center mb-5">
                            <div class="bg-success text-white rounded-circle position-absolute z-index-9">3</div>
                            <div><i class="flaticon-handshake flat-medium icon-success" aria-hidden="true"></i></div>
                            <h5 class="text-secondary mt-5 mb-4" style="font-family:Arial;">Acquire</h5>
                            <p>Acquire your dream home with vastu in mind. Our website simplifies your search with powerful filters and expert insights.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!--	How It Work -->
        
        <!--	Achievement
        ============================================================-->
        <div class="full-row overlay-secondary" style="background-image: url('images/breadcromb.jpg'); background-size: cover; background-position: center center; background-repeat: no-repeat;">
            <div class="container">
                <div class="fact-counter">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="count wow text-center  mb-sm-50" data-wow-duration="300ms"> <i class="flaticon-house flat-large text-white" aria-hidden="true"></i>
								<?php
										$query=mysqli_query($con,"SELECT count(pid) FROM property");
											while($row=mysqli_fetch_array($query))
												{
										?>
                                <div class="count-num text-success my-4" data-speed="3000" data-stop="<?php 
												$total = $row[0];
												echo $total;?>">0</div>
								<?php } ?>
                                <div class="text-white h5">Property Available</div>
                            </div>
                        </div>
						<div class="col-md-3">
                            <div class="count wow text-center  mb-sm-50" data-wow-duration="300ms"> <i class="flaticon-house flat-large text-white" aria-hidden="true"></i>
								<?php
										$query=mysqli_query($con,"SELECT count(pid) FROM property where stype='sale'");
											while($row=mysqli_fetch_array($query))
												{
										?>
                                <div class="count-num text-success my-4" data-speed="3000" data-stop="<?php 
												$total = $row[0];
												echo $total;?>">0</div>
								<?php } ?>
                                <div class="text-white h5">Sale Property Available</div>
                            </div>
                        </div>
						<div class="col-md-3">
                            <div class="count wow text-center  mb-sm-50" data-wow-duration="300ms"> <i class="flaticon-house flat-large text-white" aria-hidden="true"></i>
								<?php
										$query=mysqli_query($con,"SELECT count(pid) FROM property where stype='rent'");
											while($row=mysqli_fetch_array($query))
												{
										?>
                                <div class="count-num text-success my-4" data-speed="3000" data-stop="<?php 
												$total = $row[0];
												echo $total;?>">0</div>
								<?php } ?>
                                <div class="text-white h5">Rent Property Available</div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="count wow text-center  mb-sm-50" data-wow-duration="300ms"> <i class="flaticon-man flat-large text-white" aria-hidden="true"></i>
                                <?php
										$query=mysqli_query($con,"SELECT count(uid) FROM user");
											while($row=mysqli_fetch_array($query))
												{
										?>
                                <div class="count-num text-success my-4" data-speed="3000" data-stop="<?php 
												$total = $row[0];
												echo $total;?>">0</div>
								<?php } ?>
                                <div class="text-white h5">Registered Users</div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
        
        <!--	Popular Place -->
        <div class="full-row bg-gray" >
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <h2 class="text-secondary double-down-line text-center mb-5" style="font-family:Arial;">Popular Places</h2></div>
                </div>
                <div class="col-lg-12">
                    <div class="row">
                        <div class="col-md-6 col-lg-3 pb-1">
                        <div class="overflow-hidden position-relative overlay-secondary hover-zoomer mx-n13 z-index-9">
    <img src="images/thumbnail4/1.jpg" alt="">
    <div class="text-white xy-center z-index-9 position-absolute text-center w-100">
        <?php
        $query = mysqli_query($con, "SELECT COUNT(state) AS total_properties, state FROM property WHERE city IN ('Pune', 'Mumbai', 'Nagpur','Nashik','Thane') GROUP BY state");
        while ($row = mysqli_fetch_array($query)) {
        ?>
            <h4 class="hover-text-success text-capitalize">
                <a href="stateproperty.php?id=<?php echo $row['state']; ?>"><?php echo $row['state']; ?></a>
            </h4>
            <span><?php echo $row['total_properties']; ?> Properties Listed</span>
        <?php } ?>
    </div>
</div>

                            
                        </div>
                        <div class="col-md-6 col-lg-3 pb-1">
    <div class="overflow-hidden position-relative overlay-secondary hover-zoomer mx-n13 z-index-9">
        <img src="images/thumbnail4/2.jpg" alt="">
        <div class="text-white xy-center z-index-9 position-absolute text-center w-100">
            <?php
            $query = mysqli_query($con, "SELECT COUNT(state) AS total_properties, state FROM property WHERE city IN ('Saket','Connaught Place','Dwarka','Chandni Chowk','Karol Bagh','Lajpat Nagar') GROUP BY state");
            while ($row = mysqli_fetch_array($query)) {
            ?>
                <h4 class="hover-text-success text-capitalize">
                    <a href="stateproperty.php?id=<?php echo $row['state']; ?>"><?php echo $row['state']; ?></a>
                </h4>
                <span><?php echo $row['total_properties']; ?> Properties Listed</span>
            <?php } ?>
        </div>
    </div>
</div>

<div class="col-md-6 col-lg-3 pb-1">
    <div class="overflow-hidden position-relative overlay-secondary hover-zoomer mx-n13 z-index-9">
        <img src="images/thumbnail4/3.jpg" alt="">
        <div class="text-white xy-center z-index-9 position-absolute text-center w-100">
            <?php
            $query = mysqli_query($con, "SELECT COUNT(state) AS total_properties, state FROM property WHERE city IN ('Bengaluru','Mysore','Udupi','Davangere','Shimoga') GROUP BY state");
            while ($row = mysqli_fetch_array($query)) {
            ?>
                <h4 class="hover-text-success text-capitalize">
                    <a href="stateproperty.php?id=<?php echo $row['state']; ?>"><?php echo $row['state']; ?></a>
                </h4>
                <span><?php echo $row['total_properties']; ?> Properties Listed</span>
            <?php } ?>
        </div>
    </div>
</div>

<div class="col-md-6 col-lg-3 pb-1">
    <div class="overflow-hidden position-relative overlay-secondary hover-zoomer mx-n13 z-index-9">
        <img src="images/thumbnail4/4.jpg" alt="">
        <div class="text-white xy-center z-index-9 position-absolute text-center w-100">
            <?php
            $query = mysqli_query($con, "SELECT COUNT(state) AS total_properties, state FROM property WHERE city IN ('Noida','Varanasi','Prayagraj') GROUP BY state");
            while ($row = mysqli_fetch_array($query)) {
            ?>
                <h4 class="hover-text-success text-capitalize">
                    <a href="stateproperty.php?id=<?php echo $row['state']; ?>"><?php echo $row['state']; ?></a>
                </h4>
                <span><?php echo $row['total_properties']; ?> Properties Listed</span>
            <?php } ?>
        </div>
    </div>
</div>
                    </div>
                </div>
            </div>
        </div>
        <!--	Popular Places -->
		
		<!--	Testonomial -->
		<div class="full-row">
            <div class="container">
                <div class="row">
					<div class="col-lg-12">
						<div class="content-sidebar p-4">
							<div class="mb-3 col-lg-12">
								<h4 class="double-down-line-left text-secondary position-relative pb-4 mb-4">Testimonial</h4>
									<div class="recent-review owl-carousel owl-dots-gray owl-dots-hover-success">
									
										<?php
													
												$query=mysqli_query($con,"select feedback.*, user.* from feedback,user where feedback.uid=user.uid and feedback.status='1'");
												while($row=mysqli_fetch_array($query))
													{
										?>
										<div class="item">
											<div class="p-4 bg-success down-angle-white position-relative">
												<p class="text-white"><i class="fas fa-quote-left mr-2 text-white"></i><?php echo $row['2']; ?>. <i class="fas fa-quote-right mr-2 text-white"></i></p>
											</div>
											<div class="p-2 mt-4">
												<span class="text-success d-table text-capitalize"><?php echo $row['uname']; ?></span> <span class="text-capitalize"><?php echo $row['utype']; ?></span>
											</div>
										</div>
										<?php }  ?>
										
									</div>
							</div>
						 </div>
					</div>
				</div>
			</div>
		</div>
		<!--	Testonomial -->
		
		
        <!--	Footer   start-->
		<?php include("include/footer.php");?>
		<!--	Footer   start-->
        
        
        <!-- Scroll to top --> 
        <a href="#" class="bg-success text-white hover-text-secondary" id="scroll"><i class="fas fa-angle-up"></i></a> 
        <!-- End Scroll To top --> 
    </div>
</div>
<!-- Wrapper End --> 

<!--	Js Link
============================================================--> 
<script src="js/jquery.min.js"></script> 
<!--jQuery Layer Slider --> 
<script src="js/greensock.js"></script> 
<script src="js/layerslider.transitions.js"></script> 
<script src="js/layerslider.kreaturamedia.jquery.js"></script> 
<!--jQuery Layer Slider --> 
<script src="js/popper.min.js"></script> 
<script src="js/bootstrap.min.js"></script> 
<script src="js/owl.carousel.min.js"></script> 
<script src="js/tmpl.js"></script> 
<script src="js/jquery.dependClass-0.1.js"></script> 
<script src="js/draggable-0.1.js"></script> 
<script src="js/jquery.slider.js"></script> 
<script src="js/wow.js"></script> 
<script src="js/YouTubePopUp.jquery.js"></script> 
<script src="js/validate.js"></script> 
<script src="js/jquery.cookie.js"></script> 
<script src="js/custom.js"></script>

<script>
    const images = [
        'images/banner/Banner1.jpg',
        'images/banner/Banner2.jpg',
        'images/banner/Banner3.jpg'
    ];
    
    let currentIndex = 0;
    const banner = document.getElementById('banner');

    function changeBackgroundImage() {
        // Corrected syntax for setting backgroundImage
        banner.style.backgroundImage = `url('${images[currentIndex]}')`;
        currentIndex = (currentIndex + 1) % images.length; // Cycle through images
    }

    // Change image every 2 seconds
    setInterval(changeBackgroundImage, 5000);
</script>


</body>

</html>