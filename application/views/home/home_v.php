
<!--[if lt IE 8]>
    <p class="browsehappy">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
<![endif]-->    

<!-- Begin Page Loader -->
<div id="page-loader">
    <div class="preload">
        <img src="<?=base_url('dist/img/preloader.gif')?>" alt="Loading"/>
    </div>
    <!-- //.preload -->
</div>
<!-- //End Page Loader -->

<!-- Begin Intro Section -->
<section id="intro">
    <div class="intro-body text-center">
        <div class="container">
            <div class="row">
                <div class="col-md-12 intro-content">
                    <div class="logo">
                        <h1>ETM</h1>
                    </div>
                    <!-- //.logo -->
                    
                    <div class="intro-text text-center">
                        <h2>Eve Trade Master 2.0</h2>

                        <p class="lead">a web based profit tracker, asset manager and much more!</p>
                    </div>
                    <!-- //.intro-text -->

                    <div class="page-scroll">
                        <a href="#etm2" class="btn btn-lg btn-rj">Learn More</a>
                        <br><br>
                        <p class="lead">Login | Register</p>
                    </div>
                    <!-- //.page-scroll -->
                </div>
                <!-- //.intro-content -->
            </div>
            <!-- //.row -->
        </div>
        <!-- //.container -->
    </div>
    <!-- //.intro-body -->
</section>
<!-- //End Intro Section -->


<!-- Begin Navbar -->
<nav id="navigation" class="navbar navbar-inverse navbar-fixed-top" role="navigation">
    <div class="container">
        <div class="navbar-header page-scroll">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-rj-collapse">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            
            <a class="navbar-brand" href="#page-top">Eve Trade Master</a>
        </div>
        <!-- //.navbar-header -->
        
        <div class="navbar-collapse collapse navbar-rj-collapse">
            <ul class="nav navbar-nav navbar-right">
                <li class="page-scroll">
                    <a href="#etm2">ETM 2.0</a>
                </li>

                <li class="page-scroll">
                    <a href="#stats">Stats</a>
                </li>

                <li class="page-scroll">
                    <a href="#features">Features</a>
                </li>

                <li class="page-scroll">
                    <a href="#">Demo</a>
                </li>
                
                <li class="page-scroll">
                    <a href="#gallery">Gallery</a>
                </li>

                <li class="page-scroll">
                    <a href="#tryit">Try it out</a>
                </li>

                <li class="page-scroll">
                    <a href="#coming-soon">Dev Blog</a>
                </li>
                
                <li class="page-scroll">
                    <a href="#contact-us">Contact</a>
                </li>
            </ul>
        </div>
        <!-- //.navbar-collapse -->
    </div>
    <!-- //.container -->
</nav>
<!-- //End Navbar -->

<!-- Begin Who We Are Section -->
<section id="etm2" class="section">
    <div class="section-inner">
        <div class="container section-content">
            <div class="row">
                <div class="col-md-12">
                    <div class="section-title text-center">
                        <h2 class="main-title">ETM 2.0 is finally here!</h2>

                        <h3 class="sub-title">New features, a new image and lots of improvements</h3>

                        <span class="section-line"></span>
                    </div>
                    <!-- //.section-title -->
                </div>
                <!-- //.col-md-12 -->
            </div>
            <!-- //.row -->

            <div class="row">
                <div class="col-md-6">
                    <div id="carousel-who-we-are" class="owl-carousel owl-theme">
                        <div class="item">
                            <img src="<?=base_url('dist/img/who-we-are-image-1.jpg')?>" alt="" class="img-responsive img-rounded"/>
                        </div>
                        <!-- //.item -->

                        <div class="item">
                            <img src="<?=base_url('dist/img/who-we-are-image-2.jpg')?>" alt="" class="img-responsive img-rounded"/>
                        </div>
                        <!-- //.item -->

                        <div class="item">
                            <img src="<?=base_url('dist/img/who-we-are-image-3.jpg')?>" alt="" class="img-responsive img-rounded"/>
                        </div>
                        <!-- //.item -->
                    </div>
                    <!-- //.owl-carousel -->
                </div>
                <!-- //.col-md-6 -->

                <div class="col-md-6">
                    <div class="who-we-are-text">
                        <h4>About</h4>

                        <p class="about-etm">Eve Trade Master is a trading manager application for the popular MMORPG Eve Online. Eve Trade Master first started as personal project back in August 2015. With growing interest for web development and Eve Online's fascinating economy, it has been massively improved overtime, thanks to Eve's vibrant community's support. <br>
                        ETM 2.0 represents a major milestone both in back and front-end improvements, which will hopefully empower your trading activity in New Eden to a whole new level.</p>

                        <div class="page-scroll">
                            <a href="#" class="btn btn-lg btn-rj">Check it out!</a>
                        </div>
                        <!-- //.page-scroll -->
                    </div>
                    <!-- //.who-we-are-text -->
                </div>
                <!-- //.col-md-6 -->
            </div>
            <!-- //.row -->
        </div>
        <!-- //.section-content -->
    </div>
    <!-- //.section-inner -->
</section>
<!-- //End Who We Are Section -->


<!-- Begin Fun Fact Section -->
<section id="stats" class="section section-bgimage-yes">
    <div class="section-inner">
        <div class="section-overlay"></div>
        <div class="container section-content">
            <div class="row">
                <div class="col-md-12">
                    <div class="section-title text-center">
                        <h2 class="main-title">Usage Stats</h2>
                        <span class="section-line"></span>
                    </div>
                    <!-- //.section-title -->
                </div>
                <!-- //.col-md-12 -->
            </div>
            <!-- //.row -->

            <div class="row">
                <div class="col-xs-6 col-sm-6 col-md-3 text-center">
                    <div class="counter-item not-right-column top-column">
                        <i class="fa fa-list"></i>
                        <div class="inner-content">
                            <span class="number" data-from="0" data-to="<?=$stats['data']['transactions']?>" data-refresh-interval="100">
                                <?=$stats['data']['transactions']?>
                            </span>
                        </div>
                        <!-- //.inner-content -->

                        <p>Transactions</p>
                    </div>
                    <!-- //.counter-item -->
                </div>
                <!-- //.col-xs-6 col-sm-6 col-md-3 -->
                <div class="col-xs-6 col-sm-6 col-md-3 text-center">
                    <div class="counter-item not-right-column top-column">
                        <i class="fa fa-usd"></i>
                        <div class="inner-content">
                            <span class="number" data-from="0" data-to="<?=$stats['data']['profit']?>" 
                            data-refresh-interval="100">
                                <?$stats['data']['profit']?>
                            </span>
                        </div>
                        <!-- //.inner-content -->

                        <p>Total Profit (millions ISK)</p>
                    </div>
                    <!-- //.counter-item -->
                </div>
                <!-- //.col-xs-6 col-sm-6 col-md-3 -->

                <div class="col-xs-6 col-sm-6 col-md-3 text-center">
                    <div class="counter-item not-right-column">
                        <i class="fa fa-key"></i>

                        <div class="inner-content">
                            <span class="number" data-from="0" data-to="<?=$stats['data']['keys']?>" data-refresh-interval="100">
                                <?=$stats['data']['keys']?>
                            </span>
                        </div>
                        <!-- //.inner-content -->

                        <p>API Keys</p>
                    </div>
                    <!-- //.counter-item -->
                </div>
                <!-- //.col-xs-6 col-sm-6 col-md-3 -->

                <div class="col-xs-6 col-sm-6 col-md-3 text-center">
                    <div class="counter-item">
                        <i class="fa fa-smile-o"></i>

                        <div class="inner-content">
                            <span class="number" data-from="0" data-to="<?=$stats['data']['characters']?>" data-refresh-interval="100">
                                <?=$stats['data']['characters']?>
                            </span>
                        </div>
                        <!-- //.inner-content -->

                        <p>Characters</p>
                    </div>
                    <!-- //.counter-item -->
                </div>
                <!-- //.col-xs-6 col-sm-6 col-md-3 -->
            </div>
            <!-- //.row -->
        </div>
        <!-- //.section-content -->
    </div>
    <!-- //.section-inner -->
</section>
<!-- //End Fun Fact Section -->

<!-- Begin Our Services Section -->
<section id="features" class="section">
    <div class="section-inner">
        <div class="container section-content">
            <div class="row">
                <div class="col-md-12">
                    <div class="section-title text-center">
                        <h3 class="main-title">Features</h3>

                        <h4 class="sub-title">Keep up with your earnings and increase your trading efficiency</h4>

                        <span class="section-line"></span>
                    </div>
                    <!-- //.section-title -->
                </div>
                <!-- //.col-md-12 -->
            </div>    
            <!-- //.row -->

            <div class="row">
                <div class="col-sm-4 col-md-4">
                    <div class="funny-boxes float-shadow not-right-column text-center">
                        <span class="funny-boxes-icon">
                            <i class="fa fa-calculator"></i>
                        </span>
                        <div class="funny-boxes-text">
                            <h4>Automatic Profit Tracking</h4>

                            <p>Find out every item you've been re-selling, with all taxes and fees calculated, even across different characters!</p>
                        </div>
                        <!-- //.funny-boxes-text -->
                    </div>
                    <!-- //.funny-boxes -->
                </div>
                <!-- //.col-sm-4 col-md-4 -->
                <div class="col-sm-4 col-md-4">
                    <div class="funny-boxes float-shadow not-right-column text-center">
                        <span class="funny-boxes-icon">
                            <i class="fa fa-cog"></i>
                        </span>

                        <div class="funny-boxes-text">
                            <h4>Market Simulator</h4>

                            <p>Instantly compare price differences between stations to help you find new profitable trade routes or restock staging systems.</p>
                        </div>
                        <!-- //.funny-boxes-text -->
                    </div>
                    <!-- //.funny-boxes -->
                </div>
                <!-- //.col-sm-4 col-md-4 -->
                <div class="col-sm-4 col-md-4">
                    <div class="funny-boxes float-shadow text-center">
                        <span class="funny-boxes-icon">
                            <i class="fa fa-newspaper-o"></i>
                        </span>

                        <div class="funny-boxes-text">
                            <h4>Statistics and Reports</h4>

                            <p>Get access to detailed reports about your trading activity such as best items, best ISK/h, and more!</p>
                        </div>
                        <!-- //.funny-boxes-text -->
                    </div>
                    <!-- //.funny-boxes -->
                </div>
                <!-- //.col-sm-4 col-md-4 -->
            </div>
            <div class="row">
                <div class="col-sm-4 col-md-4">
                    <div class="funny-boxes float-shadow not-right-column text-center">
                        <span class="funny-boxes-icon">
                            <i class="fa fa-area-chart"></i>
                        </span>
                        <div class="funny-boxes-text">
                            <h4>Visualize your progress</h4>

                            <p>Find out how your trading strategy or overall wealth is evolving with several interactive charts.</p>
                        </div>
                        <!-- //.funny-boxes-text -->
                    </div>
                    <!-- //.funny-boxes -->
                </div>
                <!-- //.col-sm-4 col-md-4 -->
                <div class="col-sm-4 col-md-4">
                    <div class="funny-boxes float-shadow not-right-column text-center">
                        <span class="funny-boxes-icon">
                            <i class="fa fa-file"></i>
                        </span>

                        <div class="funny-boxes-text">
                            <h4>E-mail reporting</h4>

                            <p>Too busy to use external tools? No problem, we'll send you periodic snapshots by e-mail!</p>
                        </div>
                        <!-- //.funny-boxes-text -->
                    </div>
                    <!-- //.funny-boxes -->
                </div>
                <!-- //.col-sm-4 col-md-4 -->
                <div class="col-sm-4 col-md-4">
                    <div class="funny-boxes float-shadow text-center">
                        <span class="funny-boxes-icon">
                            <i class="fa fa-database"></i>
                        </span>

                        <div class="funny-boxes-text">
                            <h4>Fresh data!</h4>

                            <p>The CREST API allows for up-to date information on the state of your orders or prices from anywhere in New Eden.</p>
                        </div>
                        <!-- //.funny-boxes-text -->
                    </div>
                    <!-- //.funny-boxes -->
                </div>
                <!-- //.col-sm-4 col-md-4 -->
            </div>
            <!-- //.row -->
        </div>
        <!-- //.section-content -->
    </div>
    <!-- //.section-inner -->
</section>
<!-- //End Our Services Section -->

<!-- Begin Our Latest Work Section -->
<section id="gallery" class="section">
    <div class="section-inner">
        <div class="container section-content">
            <div class="row">
                <div class="col-md-12">
                    <div class="section-title text-center">
                        <h2 class="main-title">Gallery</h2>
                    </div>
                    <!-- //.section-title -->
                </div>
                <!-- //.col-md-12 -->
            </div>
            <!-- //.row -->

            <div class="row">
                <div class="col-md-12">
                    <div class="filter-portfolio">
                        <ul class="list-unstyled">
                            <li class="active">
                                <a href="#" data-filter="*" class="btn btn-rj disabled">All</a>
                            </li>

                            <li>
                                <a href="#" data-filter=".profit" class="btn btn-rj">Profit Tracking</a>
                            </li>

                            <li>
                                <a href="#" data-filter=".asset" class="btn btn-rj">Asset Management</a>
                            </li>

                            <li>
                                <a href="#" data-filter=".market" class="btn btn-rj">Market Data</a>
                            </li>
                        </ul>
                    </div>
                    <!-- //.filter-portfolio -->
                </div>
                <!-- //.col-md-12 -->
            </div>
            <!-- //.row -->

            <div class="row">
                <div class="col-md-12">
                    <div class="popup-portfolio">
                        <div class="portfolio-item grow profit logo">
                            <div class="inner-content">
                                <div class="portfolio-content">
                                    <div class="portfolio-detail">
                                        <a href="<?=base_url('dist/img/portfolio/portfolio-image-1.jpg')?>" title="Project Title 1 - Lorem Ipsum">
                                            <div class="portfolio-text">
                                                <h4>Dashboard</h4>

                                                <p>An overview of your recent activity and trends</p>   
                                            </div>
                                            <!-- //.portfolio-text -->
                                        </a>
                                    </div>
                                    <!-- //.portfolio-detail -->
                                </div>
                                <!-- //.portfolio-content -->

                                <img src="<?=base_url('dist/img/portfolio/portfolio-image-1.jpg')?>" alt="" class="img-responsive"/>
                            </div>
                            <!-- //.inner-content -->
                        </div>
                        <!-- //.portfolio-item -->

                        <div class="portfolio-item grow profit">
                            <div class="inner-content">
                                <div class="portfolio-content">
                                    <div class="portfolio-detail">
                                        <a href="../assets/img/portfolio/portfolio-image-2.jpg" title="Project Title 2 - Lorem Ipsum">
                                            <div class="portfolio-text">
                                                <h4>Profit Tracker</h4>

                                                <p>A comprehensive breakdown on your trading history</p>   
                                            </div>
                                            <!-- //.portfolio-text -->
                                        </a>
                                    </div>
                                    <!-- //.portfolio-detail -->
                                </div>
                                <!-- //.portfolio-content -->

                                <img src="<?=base_url('dist/img/portfolio/portfolio-image-2.jpg')?>" alt="" class="img-responsive"/>
                            </div>
                            <!-- //.inner-content -->
                        </div>
                        <!-- //.portfolio-item -->

                        <div class="portfolio-item grow asset identity">
                            <div class="inner-content">
                                <div class="portfolio-content">
                                    <div class="portfolio-detail">
                                        <a href="<?=base_url('dist/img/portfolio/portfolio-image-3.jpg')?>" title="Project Title 3 - Lorem Ipsum">
                                            <div class="portfolio-text">
                                                <h4>Assets List</h4>

                                                <p>Detailed asset listings and distribution </p>   
                                            </div>
                                            <!-- //.portfolio-text -->
                                        </a>
                                    </div>
                                    <!-- //.portfolio-detail -->
                                </div>
                                <!-- //.portfolio-content -->

                                <img src="<?=base_url('dist/img/portfolio/portfolio-image-3.jpg')?>" alt="" class="img-responsive"/>
                            </div>
                            <!-- //.inner-content -->
                        </div>
                        <!-- //.portfolio-item -->

                        <div class="portfolio-item grow profit">
                            <div class="inner-content">
                                <div class="portfolio-content">
                                    <div class="portfolio-detail">
                                        <a href="<?=base_url('dist/img/portfolio/portfolio-image-4.jpg')?>" title="Project Title 4 - Lorem Ipsum">
                                            <div class="portfolio-text">
                                                <h4>Net worth tracker</h4>

                                                <p>Visualize how your wealth is evolving overtime</p>   
                                            </div>
                                            <!-- //.portfolio-text -->
                                        </a>
                                    </div>
                                    <!-- //.portfolio-detail -->
                                </div>
                                <!-- //.portfolio-content -->

                                <img src="<?=base_url('dist/img/portfolio/portfolio-image-4.jpg')?>" alt="" class="img-responsive"/>
                            </div>
                            <!-- //.inner-content -->
                        </div>
                        <!-- //.portfolio-item -->

                        <div class="portfolio-item grow logo profit">
                            <div class="inner-content">
                                <div class="portfolio-content">
                                    <div class="portfolio-detail">
                                        <a href="<?=base_url('dist/img/portfolio/portfolio-image-5.jpg')?>" title="Project Title 5 - Lorem Ipsum">
                                            <div class="portfolio-text">
                                                <h4>Statistics</h4>

                                                <p>A quick glance over the most profitable or problematic items, customers and stations</p>   
                                            </div>
                                            <!-- //.portfolio-text -->
                                        </a>
                                    </div>
                                    <!-- //.portfolio-detail -->
                                </div>
                                <!-- //.portfolio-content -->

                                <img src="<?=base_url('dist/img/portfolio/portfolio-image-5.jpg')?>" alt="" class="img-responsive"/>
                            </div>
                            <!-- //.inner-content -->
                        </div>
                        <!-- //.portfolio-item -->

                        <div class="portfolio-item grow market">
                            <div class="inner-content">
                                <div class="portfolio-content">
                                    <div class="portfolio-detail">
                                        <a href="<?=base_url('dist/img/portfolio/portfolio-image-6.jpg')?>" title="Project Title 6 - Lorem Ipsum">
                                            <div class="portfolio-text">
                                                <h4>Trade Simulator</h4>

                                                <p>Real-time price comparison between stations anywhere in New Eden</p>
                                            </div>
                                            <!-- //.portfolio-text -->
                                        </a>
                                    </div>
                                    <!-- //.portfolio-detail -->
                                </div>
                                <!-- //.portfolio-content -->

                                <img src="<?=base_url('dist/img/portfolio/portfolio-image-6.jpg')?>" alt="" class="img-responsive"/>
                            </div>
                            <!-- //.inner-content -->
                        </div>
                        <!-- //.portfolio-item -->

                        <div class="portfolio-item grow market">
                            <div class="inner-content">
                                <div class="portfolio-content">
                                    <div class="portfolio-detail">
                                        <a href="<?=base_url('dist/img/portfolio/portfolio-image-7.jpg')?>" title="Project Title 7 - Lorem Ipsum">
                                            <div class="portfolio-text">
                                                <h4>Order Check</h4>

                                                <p>Real-time market order tracking. Have your items been outbid yet?</p>   
                                            </div>
                                            <!-- //.portfolio-text -->
                                        </a>
                                    </div>
                                    <!-- //.portfolio-detail -->
                                </div>
                                <!-- //.portfolio-content -->

                                <img src="<?=base_url('dist/img/portfolio/portfolio-image-7.jpg')?>" alt="" class="img-responsive"/>
                            </div>
                            <!-- //.inner-content -->
                        </div>
                        <!-- //.portfolio-item -->

                        <div class="portfolio-item grow asset">
                            <div class="inner-content">
                                <div class="portfolio-content">
                                    <div class="portfolio-detail">
                                        <a href="<?=base_url('dist/img/portfolio/portfolio-image-8.jpg')?>" title="Project Title 8 - Lorem Ipsum">
                                            <div class="portfolio-text">
                                                <h4>Citadel Taxes</h4>

                                                <p>Setup custom broker fees for any active Citadel</p>   
                                            </div>
                                            <!-- //.portfolio-text -->
                                        </a>
                                    </div>
                                    <!-- //.portfolio-detail -->
                                </div>
                                <!-- //.portfolio-content -->

                                <img src="<?=base_url('dist/img/portfolio/portfolio-image-8.jpg')?>" alt="" class="img-responsive"/>
                            </div>
                            <!-- //.inner-content -->
                        </div>
                        <!-- //.portfolio-item -->

                        <div class="portfolio-item grow asset">
                            <div class="inner-content">
                                <div class="portfolio-content">
                                    <div class="portfolio-detail">
                                        <a href="<?=base_url('dist/img/portfolio/portfolio-image-9.jpg')?>" title="Project Title 9 - Lorem Ipsum">
                                            <div class="portfolio-text">
                                                <h4>Transactions</h4>

                                                <p>Stay up to date with easy to filter and search transactions and contract lists</p>   
                                            </div>
                                            <!-- //.portfolio-text -->
                                        </a>
                                    </div>
                                    <!-- //.portfolio-detail -->
                                </div>
                                <!-- //.portfolio-content -->

                                <img src="<?=base_url('dist/img/portfolio/portfolio-image-9.jpg')?>" alt="" class="img-responsive"/>
                            </div>
                            <!-- //.inner-content -->
                        </div>
                        <!-- //.portfolio-item -->
                    </div>
                    <!-- //.popup-portfolio -->
                </div>
                <!-- //.col-md-12 -->
            </div>
            <!-- //.row -->
        </div>
        <!-- //.section-content -->
    </div>
    <!-- //.section-inner -->
</section>
<!-- //End Our Latest Work Section -->


<!-- Begin Quote Section -->
<section id="tryit" class="section section-bgimage-yes">
    <div class="section-inner">
        <div class="section-overlay"></div>

        <div class="container section-content">
            <div class="row">
                <div class="col-md-12">
                    <div class="quote-text text-center">
                        <!--<span class="quote-icon">
                            <i class="fa fa-quote-right fa-4x"></i>
                        </span>-->

                        <h2>Try it out! Free to use and install.</h2>
                        <h2>All you need is a working API Key</h2>
                        <a class="btn btn-lg btn-rj">Register</a>
                        
                    </div>
                    <!-- //.quote-text -->
                </div>
                <!-- //.col-md-12 -->
            </div>    
            <!-- //.row -->
        </div>
        <!-- //.section-content -->
    </div>
    <!-- //.section-inner -->
</section>
<!-- //End Quote Section -->


<!-- Begin Testimonials Section -->
<section id="testimonials" class="section section-bgimage-yes">
    <div class="section-inner">
        <div class="section-overlay"></div>

        <div class="container section-content">
            <div class="row">
                <div class="col-md-12">
                    <div class="section-title text-center">
                        <h2 class="main-title">Testimonials</h2>

                        <h3 class="sub-title">What some players have to say:</h3>

                        <span class="section-line"></span>
                    </div>
                    <!-- //.section-title -->
                </div>
                <!-- //.col-md-12 -->
            </div>    
            <!-- //.row -->

            <div class="row">
                <div class="col-md-4">
                    <div class="testimonial-item not-right-column not-bottom-column">
                        <div class="testimonial-content">
                            <p class="testimonial-title">Lorem Ipsum Title</p>

                            <blockquote>
                                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation.</p>
                            </blockquote>
                        </div>
                        <!-- //.testimonial-content -->

                        <div class="testimonial-info clearfix">
                            <img width="100" src="<?=base_url('dist/img/testimonial-image-1.jpg')?>" alt="" class="img-circle img-thumbnail"/>

                            <p class="testimonial-author">
                                @Jane Doe, Jakarta
                                <br>
                                <em>Co-Founder at Cisco</em>
                            </p>
                        </div>
                        <!-- //.testimonial-info -->
                    </div>
                    <!-- //.testimonial-item -->
                </div>
                <!-- //.col-md-4 -->

                <div class="col-md-4">
                    <div class="testimonial-item not-right-column not-bottom-column">
                        <div class="testimonial-content">
                            <p class="testimonial-title">Lorem Ipsum Title</p>

                            <blockquote>
                                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation.</p>
                            </blockquote>
                        </div>
                        <!-- //.testimonial-content -->

                        <div class="testimonial-info clearfix">
                            <img width="100" src="<?=base_url('dist/img/testimonial-image-2.jpg')?>" alt="" class="img-circle img-thumbnail"/>

                            <p class="testimonial-author">
                                @John Doe, Jakarta
                                <br>
                                <em>Creative Director at Apple</em>
                            </p>
                        </div>
                        <!-- //.testimonial-info -->
                    </div>
                    <!-- //.testimonial-item -->
                </div>
                <!-- //.col-md-4 -->

                <div class="col-md-4">
                    <div class="testimonial-item not-bottom-column">
                        <div class="testimonial-content">
                            <p class="testimonial-title">Lorem Ipsum Title</p>

                            <blockquote>
                                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation.</p>
                            </blockquote>
                        </div>
                        <!-- //.testimonial-content -->

                        <div class="testimonial-info clearfix">
                            <img width="100" src="<?=base_url('dist/img/testimonial-image-3.jpg')?>" alt="" class="img-circle img-thumbnail"/>

                            <p class="testimonial-author">
                                @John Doe, Jakarta
                                <br>
                                <em>Managet at Microsoft</em>
                            </p>
                        </div>
                        <!-- //.testimonial-info -->
                    </div>
                    <!-- //.testimonial-item -->
                </div>
                <!-- //.col-md-4 -->
            </div>
            <!-- //.row -->
            
        </div>
        <!-- //.section-container -->
    </div>
    <!-- //.section-inner -->
</section>
<!-- //End Testimonials Section -->

<!-- Begin Coming Soon Section -->
<section id="coming-soon" class="section section-bgimage-yes">
    <div class="section-inner">
        <div class="section-overlay"></div>

        <div class="container section-content">
            <div class="row">
                <div class="col-md-12">
                    <div class="section-title text-center" style="padding-bottom: 0 !important">
                        <h3 class="main-title">Developer's Blog</h3>

                        <h3 class="sub-title">New features & roadmap</h3>

                        <span class="section-line"></span>
                    </div>
                    <!-- //.section-title -->
                </div>
                <!-- //.col-md-12 -->
            </div>
            <!-- //.row -->
            <div class="row">
                <div class="col-md-8 col-md-offset-2">
                    <div class="countdown-text text-center">
                        <p class="lead">Stay up to date with new features and bug fixes and contribute for future development in Eve Trade Master</p>
                        <a href="#" class="btn btn-lg btn-rj">Dev Blog</a>
                    </div>
                    <!-- //.countdown-text -->
                </div>

                <!-- //.col-md-8 col-md-offset-2 -->
            </div>
            <!-- //.row -->
        </div>
        <!-- //.section-content -->
    </div>
    <!-- //.section-inner -->
</section>
<!-- //End Coming Soon Section -->


<!-- Begin Contact Us Section -->
<section id="contact-us" class="section section-bgimage-yes">
    <div class="section-inner">
        <div class="section-overlay"></div>
        <div class="container section-content">
            <div class="row">
                <div class="col-md-12">
                    <div class="section-title text-center">
                        <h3 class="main-title">Enjoy this service?</h3>
                        <p class="lead" style="text-transform: initial;">Feel free to donate any ISK to 'Nick Starkey' or to Paypal to help out with server costs. <br>
                        Any help is appreciated, but never mandatory. <br>
                        I'm also interested in your feedback for bug reports or feature requests!</p>
                        <span class="section-line"></span>
                        <div class="row donate">
                            <div class="col-md-4 col-md-offset-4">
                                <div class="text-center">
                                    <h2 class="section-title title-donate"><i class="fa fa-paypal"></i> Donate</h2>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 col-md-offset-2">
                        <div class="text-center">
                            <h2 class="lead"><i class="fa fa-envelope-o"></i></h2>
                            <h3 class="lead">etmdevelopment42@gmail.com</h3>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="text-center">
                            <h2 class="lead"><i class="fa fa-twitter"></i></h2>
                            <h3 class="lead">@nickstarkey</h3>
                        </div>
                    </div>
                    <!-- //.section-title -->
                </div>
                <!-- //.col-md-12 -->
            </div>
            
            <!-- //.row -->
        </div>
        <!-- //.section-content -->
    </div>
    <!-- //.section-inner -->
</section>
<!-- //End Contact Us Section -->

<!-- Begin Footer -->
<footer class="footer">
    <!-- Begin Copyright -->
    <div id="copyright">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <p>Copyright &copy; 2016 by Nick Starkey. <br>
                    EVE Online and the EVE logo are the registered trademarks of CCP hf. All rights are reserved worldwide. All other trademarks are the property of their respective owners. EVE Online, the EVE logo, EVE and all associated logos and designs are the intellectual property of CCP hf. All artwork, screenshots, characters, vehicles, storylines, world facts or other recognizable features of the intellectual property relating to these trademarks are likewise the intellectual property of CCP hf. CCP hf. has granted permission to Eve Trade Master to use EVE Online and all associated logos and designs for promotional and information purposes on its website but does not endorse, and is not in any way affiliated with, Eve Trade Master. CCP is in no way responsible for the content on or functioning of this website, nor can it be liable for any damage arising from the use of this website.</a></p>
                </div>
                <!-- //.col-md-12 -->
            </div>
            <!-- //.row -->
        </div>
        <!-- //.container -->
    </div>
    <!-- //End Copyright -->
</footer>
<!-- //End Footer -->
