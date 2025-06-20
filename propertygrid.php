<?php 
ini_set('session.cache_limiter', 'public');
session_cache_limiter(false);
session_start();
include("config.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Real Estate PHP">
    <meta name="author" content="Unicoder">
    <link rel="shortcut icon" href="images/favicon.ico">
    <title>Vastu Homes</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Muli:400,400i,500,600,700&amp;display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Comfortaa:400,700" rel="stylesheet">

    <!-- CSS Links -->
    <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="css/bootstrap-slider.css">
    <link rel="stylesheet" type="text/css" href="css/jquery-ui.css">
    <link rel="stylesheet" type="text/css" href="css/layerslider.css">
    <link rel="stylesheet" type="text/css" href="css/color.css" id="color-change">
    <link rel="stylesheet" type="text/css" href="css/owl.carousel.min.css">
    <link rel="stylesheet" type="text/css" href="css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="fonts/flaticon/flaticon.css">
    <link rel="stylesheet" type="text/css" href="css/style.css">

    <style>
      .overlay-black img {
            width: 100%;  /* Ensures the image fills the container width */
            height: 250px; /* Set a fixed height (adjust as needed) */
            object-fit: cover; /* Maintains aspect ratio and crops if needed */
            
        }
    </style>
</head>
<body>

<div id="page-wrapper">
    <div class="row"> 
        <!-- Header -->
        <?php include("include/header.php"); ?>

        <!-- Banner -->
        <div class="banner-full-row page-banner" style="background-image:url('images/breadcromb.jpg');">
            <div class="container">
                <div class="row">
                    <div class="col-md-6">
                        <h2 class="page-name float-left text-white text-uppercase mt-1 mb-0"><b>Filter Property</b></h2>
                    </div>
                    <div class="col-md-6">
                        <nav aria-label="breadcrumb" class="float-left float-md-right">
                            <ol class="breadcrumb bg-transparent m-0 p-0">
                                <li class="breadcrumb-item text-white"><a href="#">Home</a></li>
                                <li class="breadcrumb-item active">Filter Property</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>

        <!-- Property Grid -->
        <div class="full-row">
            <div class="container">
                <div class="row">
                    <div class="col-lg-8">
                        <div class="row">
                            <?php 
                            if(isset($_REQUEST['filter'])) {
                                $type = $_REQUEST['type'];
                                $stype = $_REQUEST['stype'];
                                $city = $_REQUEST['city'];

                                $sql = "SELECT property.*, user.uname FROM property, user 
                                        WHERE property.uid = user.uid 
                                        AND type = '{$type}' 
                                        AND stype = '{$stype}' 
                                        AND city = '{$city}'";
                                
                                $result = mysqli_query($con, $sql);

                                if(mysqli_num_rows($result) > 0) {
                                    while($row = mysqli_fetch_array($result)) {
                            ?>
                            <div class="col-md-6">
                                <div class="featured-thumb hover-zoomer mb-4">
                                    <div class="overlay-black overflow-hidden position-relative"><img src="admin/property/<?php echo $row['18']; ?>" alt="pimage">
                                        <div class="sale bg-success text-white">For <?php echo $row['5']; ?></div>
                                        <div class="price text-primary text-capitalize">
                                            $<?php echo $row['13']; ?> <span class="text-white"><?php echo $row['12']; ?> Sqft</span>
                                        </div>
                                    </div>
                                    <div class="featured-thumb-data shadow-one">
                                        <div class="p-4">
                                            <h5 class="text-secondary hover-text-success mb-2 text-capitalize">
                                                <a href="propertydetail.php?pid=<?php echo $row['0']; ?>&uid=<?php echo $row['uid']; ?>">
                                                    <?php echo $row['1']; ?>
                                                </a>
                                            </h5>
                                            <span class="location text-capitalize">
                                                <i class="fas fa-map-marker-alt text-success"></i> <?php echo $row['14']; ?>
                                            </span>
                                        </div>
                                        <div class="px-4 pb-4 d-inline-block w-100">
                                            <div class="float-left text-capitalize">
                                                <i class="fas fa-user text-success mr-1"></i>By : <?php echo $row['uname']; ?>
                                            </div>
                                            <div class="float-right">
                                                <i class="far fa-calendar-alt text-success mr-1"></i> <?php echo date('d-m-Y', strtotime($row['date'])); ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php 
                                    }
                                } else {
                                    echo "<h1 class='mb-5'><center>No Property Available</center></h1>";
                                }
                            }
                            ?>
                        </div>
                    </div>

                    <div class="col-lg-4">
                        <!-- Instalment Calculator -->
                        <div class="sidebar-widget">
                            <h4 class="double-down-line-left text-secondary position-relative pb-4 my-4">Instalment Calculator</h4>
                            <form class="d-inline-block w-100" action="calc.php" method="post">
                                <div class="input-group mb-2">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">$</div>
                                    </div>
                                    <input type="text" class="form-control" name="amount" placeholder="Property Price">
                                </div>
                                <div class="input-group mb-2">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="far fa-calendar-alt"></i></div>
                                    </div>
                                    <input type="text" class="form-control" name="month" placeholder="Duration Year">
                                </div>
                                <div class="input-group mb-2">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">%</div>
                                    </div>
                                    <input type="text" class="form-control" name="interest" placeholder="Interest Rate">
                                </div>
                                <button type="submit" value="submit" name="calc" class="btn btn-danger mt-4">Calculate Instalment</button>
                            </form>
                        </div>

                        <!-- Recently Added Property -->
                        <div class="sidebar-widget mt-5">
                            <h4 class="double-down-line-left text-secondary position-relative pb-4 mb-4">Recently Added Property</h4>
                            <ul class="property_list_widget">
                                <?php 
                                $query = mysqli_query($con, "SELECT * FROM property ORDER BY date DESC LIMIT 6");
                                while($row = mysqli_fetch_array($query)) {
                                ?>
                                <li>
                                    <img src="admin/property/<?php echo $row['18']; ?>" alt="pimage">
                                    <h6 class="text-secondary hover-text-success text-capitalize">
                                        <a href="propertydetail.php?pid=<?php echo $row['0']; ?>&uid=<?php echo $row['23']; ?>">
                                            <?php echo $row['1']; ?>
                                        </a>
                                    </h6>
                                </li>
                                <?php } ?>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?php include("include/footer.php"); ?>
    </div>
</div>

<script src="js/jquery.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/custom.js"></script>
</body>
</html>