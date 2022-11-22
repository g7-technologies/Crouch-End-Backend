<?php 
    include('db/connection.php');
    if(!isset($_SESSION['login_user'])){ 
        header("location: index.php"); // Redirecting To Home Page 
    }
    
    //____________________________________________ROW 1 VALUES_________________________________________________________________//
    $users = mysqli_query($conn,"SELECT COALESCE(COUNT(*),0) AS total_user FROM user WHERE is_deleted = 0 AND status = 1;");
    $today_post = mysqli_query($conn,"SELECT COALESCE(COUNT(*),0) AS today_post FROM posts WHERE is_deleted = 0 AND status = 1 AND DATE_FORMAT(created_at,'%y%m%d') = DATE_FORMAT(now(),'%y%m%d');");
    $pinned_post = mysqli_query($conn,"SELECT COALESCE(COUNT(*),0) AS pinned_post FROM posts WHERE user_id IS NULL;");
    $query_unassigned_user = mysqli_query($conn,"SELECT * FROM user LEFT OUTER JOIN user_role ON user.id = user_role.user_id WHERE user_role.id IS NULL;");
    
    //____________________________________________ROW 2 VALUES_________________________________________________________________//
    $all_users_without_role = mysqli_query($conn,"SELECT user.id, user.name, role.name AS role_name, user.phone, DATE_FORMAT(user.created_at,'%M %d, %Y') AS created_at FROM user LEFT OUTER JOIN user_role ON user.id = user_role.user_id LEFT OUTER JOIN role ON user_role.role_id = role.id ORDER BY user.created_at DESC LIMIT 5 ;");
    $roles_id = mysqli_query($conn,"SELECT c.name AS role_name ,COUNT(a.id) AS role_users FROM user a INNER JOIN user_role b ON a.id = b.user_id INNER JOIN role c ON b.role_id = c.id GROUP BY (c.name)");
    
    
    $total_likes=mysqli_query($conn,"SELECT * FROM liked_posts ");
    $like_count=mysqli_num_rows($total_likes);
    
    $total_cmnts =mysqli_query($conn,"SELECT * FROM post_comments ");
    $cmnt_count=mysqli_num_rows($total_cmnts);

    include_once('includes/header_start.php');
?>


<link rel="stylesheet" href="assets/plugins/morris/morris.css">

<?php include_once('includes/header_end.php'); ?>

                            <!-- Page title -->
                            <ul class="list-inline menu-left mb-0">
                                <li class="list-inline-item">
                                    <button type="button" class="button-menu-mobile open-left waves-effect">
                                        <i class="ion-navicon"></i>
                                    </button>
                                </li>
                                <li class="hide-phone list-inline-item app-search">
                                    <h3 class="page-title">Dashboard</h3>
                                </li>
                            </ul>

                            <div class="clearfix"></div>
                        </nav>

                    </div>
                    <!-- Top Bar End -->

                    <!-- ==================
                         PAGE CONTENT START
                         ================== -->

                    <div class="page-content-wrapper">

                        <div class="header-bg"> 
                            <div class="container-fluid">
                                <div class="row">
                                    <div class="col-sm-12 text-center mt-4">
                                        <h5>Users Registered Per Month</h5>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12 mb-4 pt-5">
                                        <div id="morris-bar-example" class="dash-chart"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-md-6 col-xl-4">
                                    <div class="card text-center m-b-30">
                                        <div class="mb-2 card-body text-muted">
                                            <h3 class="text-info"><?php $users=mysqli_fetch_assoc($users); echo $users['total_user']; ?></h3>
                                            Total Users
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 col-xl-4">
                                    <div class="card text-center m-b-30">
                                        <div class="mb-2 card-body text-muted">
                                            <h3 class="text-purple"><?php $today_post=mysqli_fetch_assoc($today_post); echo $today_post['today_post']; ?></h3>
                                            Today's Posts
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 col-xl-4">
                                    <div class="card text-center m-b-30">
                                        <div class="mb-2 card-body text-muted">
                                            <h3 class="text-primary"><?php $pinned_post=mysqli_fetch_array($pinned_post); echo $pinned_post['pinned_post']; ?></h3>
                                            Pinned Posts
                                        </div>
                                    </div>
                                </div>
                                
                                <!-------------------------------------------------------------->
                                <!--<div class="col-md-6 col-xl-3">-->
                                <!--    <div class="card text-center m-b-30">-->
                                <!--        <div class="mb-2 card-body text-muted">-->
                                <!--            <h3 class="text-danger"><?php echo mysqli_num_rows($query_unassigned_user); ?></h3>-->
                                <!--            Unassigned Users-->
                                <!--        </div>-->
                                <!--    </div>-->
                                <!--</div>-->
                                <!-------------------------------------------------------------->
                            </div>
                            <!-- end row -->
            
                            <!-------------------------------------------------------------->
                            <div class="row">
                                <div class="col-xl-4" style="display:none;">
                                    <div class="card m-b-30">
                                        <div class="card-body">
                                            <h4 class="mt-0 header-title">Users and Roles</h4>
                                            <div class="row m-t-20">
                                                <?php 
                                                    while($row_roles=mysqli_fetch_array($roles_id)){
                                                ?>
                                                
                                                <div class="col-6 text-center">
                                                    <h5 class=""><?php echo $row_roles['role_users']; ?></h5>
                                                    <p class="text-muted font-14"><?php echo $row_roles['role_name']; ?></p>
                                                </div>
                                                
                                                
                                                <?php       
                                                    }
                                                ?>
                                                
                                            </div>
                                            <div id="morris-donut-example" class="dash-chart"></div>
                                        </div>
                                    </div>
                                </div>
                                <!-------------------------------------------------------------->
            
                                <div class="col-xl-12">
                                    <div class="card m-b-30">
                                        <div class="card-body">
                                            <h4 class="mt-0 header-title">Posts Per Month</h4>
            
                                            <div class="row text-center m-t-20">
                                                <div class="col-6">
                                                    <h5 class=""><?php echo $like_count; ?></h5>
                                                    <p class="text-muted font-14">Total Likes</p>
                                                </div>
                                                <div class="col-6">
                                                    <h5 class=""><?php echo $cmnt_count; ?></h5>
                                                    <p class="text-muted font-14">Total Comments</p>
                                                </div>
                                            </div>
            
                                            <div id="morris-area-example" class="dash-chart"></div>
                                        </div>
                                    </div>
                                </div>
            
                            </div>
                            <!-- end row -->
            
                            <div class="row">
                                <div class="col-xl-12">
                                    <div class="card m-b-30">
                                        <div class="card-body">
                                            <h4 class="mt-0 m-b-30 header-title">Recently Added Users</h4>
            
                                            <div class="table-responsive">
                                                <table class="table m-t-20 mb-0 table-vertical">
            
                                                    <tbody>
                                                        <?php while($row_users=mysqli_fetch_array($all_users_without_role)){ ?>
                                                        <tr>
                                                            <td>
                                                                <?php echo $row_users['name']; ?>
                                                            </td>
                                                            <?php
                                                            if($row_users['role_name'] != null && $row_users['role_name'] != ''){ ?>
                                                                <td><i class="mdi mdi-checkbox-blank-circle text-success"></i> <?php echo $row_users['role_name']; ?></td>
                                                            <?php

                                                            }else{ ?>
                                                                <td><i class="mdi mdi-checkbox-blank-circle text-danger"></i> No Role Assigned</td>
                                                            <?php } ?>
                                                            <td>
                                                                <?php echo $row_users['phone']; ?>
                                                                <p class="m-0 text-muted font-14">Phone</p>
                                                            </td>
                                                            <td>
                                                                <?php echo $row_users['created_at']; ?>
                                                                <p class="m-0 text-muted font-14">Joined On</p>
                                                            </td>
                                                            <!-------------------------------------------------------------->
                                                            <!--<td>-->
                                                            <!--    <a href="single_assign_role.php?id=<?php echo $row_users['id']; ?>" class="btn btn-secondary btn-sm waves-effect">Assign Role</a>-->
                                                            <!--</td>-->
                                                            <!-------------------------------------------------------------->
                                                        </tr>
                                                        <?php } ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- end row -->
            
                         </div>
            

                    </div> <!-- Page content Wrapper -->

                </div> <!-- content -->

<?php include_once('includes/footer_start.php'); ?>

        <!--Morris Chart-->
        <script src="assets/plugins/morris/morris.min.js"></script>
        <script src="assets/plugins/raphael/raphael-min.js"></script>
        <script>
            !function ($) {
                "use strict";

                var Dashboard = function () {};
                
                    //creates Bar chart
                    Dashboard.prototype.createBarChart = function (element, data, xkey, ykeys, labels, lineColors) {
                        Morris.Bar({
                            element: element,
                            data: data,
                            xkey: xkey,
                            ykeys: ykeys,
                            labels: labels,
                            gridLineColor: 'rgba(255,255,255,0.1)',
                            gridTextColor: '#98a6ad',
                            barSizeRatio: 0.2,
                            resize: true,
                            hideHover: 'auto',
                            barColors: lineColors
                        });
                    },

                    //creates area chart
                    Dashboard.prototype.createAreaChart = function (element, pointSize, lineWidth, data, xkey, ykeys, labels, lineColors) {
                        Morris.Area({
                            element: element,
                            pointSize: 0,
                            lineWidth: 0,
                            data: data,
                            xkey: xkey,
                            ykeys: ykeys,
                            labels: labels,
                            resize: true,
                            gridLineColor: '#eee',
                            hideHover: 'auto',
                            lineColors: lineColors,
                            fillOpacity: .6,
                            behaveLikeLine: true
                        });
                    },

                    //creates Donut chart
                    Dashboard.prototype.createDonutChart = function (element, data, colors) {
                        Morris.Donut({
                            element: element,
                            data: data,
                            resize: true,
                            colors: colors,
                        });
                    },

                    Dashboard.prototype.init = function () {

                        //Users registered per month
                        var $barData = [
                            {y: 'Jan 2020', a: 75},
                            {y: 'Feb 2020', a: 100},
                            {y: 'Mar 2020', a: 90},
                            {y: 'Apr 2020', a: 75},
                            {y: 'May 2020', a: 50},
                            {y: 'Jun 2020', a: 75},
                            {y: 'Jul 2020', a: 100},
                            {y: 'Aug 2020', a: 90}
                        ];
                        this.createBarChart('morris-bar-example', $barData, 'y', ['a'], ['Users'], ['#4bbbce']);

                        //posts per month
                        var $areaData = [
                            {y: '2007', a: 120},
                            {y: '2008', a: 150},
                            {y: '2009', a: 60},
                            {y: '2010', a: 180},
                            {y: '2011', a: 90},
                            {y: '2012', a: 75},
                            {y: '2013', a: 30}
                        ];
                        this.createAreaChart('morris-area-example', 0, 0, $areaData, 'y', ['a'], ['Posts'], ['#4bbbce']);

                        //User and roles
                        var $donutData = [
                            {label: "Driver", value: 250},
                            {label: "Cook", value: 430}
                        ];
                        this.createDonutChart('morris-donut-example', $donutData, ['#f0f1f4', '#2f8ee0', '#4bbbce']);

                      
                    },
                    $.Dashboard = new Dashboard, $.Dashboard.Constructor = Dashboard
            }(window.jQuery),

            function ($) {
                "use strict";
                $.Dashboard.init();
            }(window.jQuery);
        </script>

<?php include_once('includes/footer_end.php'); ?>