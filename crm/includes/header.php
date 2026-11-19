<?php
// Base URL Calculation for safe linking
if (!isset($base_url)) {
    $script_path = $_SERVER['SCRIPT_NAME'];
    $crm_pos = strpos($script_path, '/crm');
    $base_url = substr($script_path, 0, $crm_pos + 4); 
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- FontAwesome for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <!-- TinyMCE Editor Full Features -->
    <script src="https://cdn.tiny.cloud/1/v812pi25f429nukqeiu0k9lizz58u91jk9lp9d93i7tdz7o9/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
    <script>
      tinymce.init({
        selector: '.tinymce-editor', 
        plugins: 'preview searchreplace autolink directionality code visualblocks visualchars fullscreen image link media codesample table charmap pagebreak nonbreaking anchor insertdatetime advlist lists wordcount help charmap emoticons',
        menubar: 'file edit view insert format tools table help',
        toolbar: 'undo redo | bold italic underline strikethrough | fontfamily fontsize blocks | alignleft aligncenter alignright alignjustify | outdent indent |  numlist bullist | forecolor backcolor removeformat | pagebreak | charmap emoticons | fullscreen preview print | image media link anchor codesample | ltr rtl',
        height: 400,
        branding: false,
        image_advtab: true,
        toolbar_mode: 'sliding'
      });
    </script>

    <!-- Custom WordPress Style CSS -->
    <style>
        body { 
            background-color: #f0f0f1; 
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Oxygen-Sans, Ubuntu, Cantarell, "Helvetica Neue", sans-serif;
            margin: 0;
            overflow-x: hidden;
        }
        #sidebar { width: 220px; background: #1d2327; position: fixed; top: 0; left: 0; height: 100vh; color: white; padding-top: 20px; z-index: 100; }
        #sidebar a { padding: 10px 15px; text-decoration: none; font-size: 14px; color: #a7aaad; display: block; }
        #sidebar a:hover, #sidebar a.active { color: #fff; background: #2c3338; }
        #sidebar i { width: 20px; text-align: center; margin-right: 8px; }
        .sidebar-title { color: #fff; font-size: 18px; font-weight: bold; text-align: center; margin-bottom: 20px; }
        #topbar { background: #fff; height: 50px; position: fixed; top: 0; left: 220px; right: 0; z-index: 99; box-shadow: 0 1px 3px rgba(0,0,0,0.1); display: flex; align-items: center; justify-content: flex-end; padding: 0 20px; }
        #main-content { margin-left: 220px; margin-top: 50px; padding: 20px; min-height: calc(100vh - 50px); }
        .wp-card { background: #fff; border: 1px solid #c3c4c7; padding: 15px; margin-bottom: 20px; box-shadow: 0 1px 1px rgba(0,0,0,.04); border-radius: 3px; }
        .wp-card-title { font-size: 14px; font-weight: 600; border-bottom: 1px solid #ccd0d4; padding-bottom: 10px; margin-bottom: 15px; }
        .btn-wp { background: #2271b1; color: #fff; border: 1px solid #2271b1; padding: 5px 15px; border-radius: 3px; font-size: 14px; cursor: pointer; }
        .btn-wp:hover { background: #135e96; color: #fff; }
        .btn-wp-danger { background: #d63638; color: #fff; border: 1px solid #d63638; padding: 5px 15px; border-radius: 3px; font-size: 14px; cursor: pointer; }
        .btn-wp-danger:hover { background: #b32d2e; color: #fff; }
        .form-control:focus, .form-select:focus { box-shadow: none; border-color: #2271b1; }
        
        
        .sidebar-dropdown .dropdown-toggle-link {
    position: relative;
    padding-right: 30px;
}

.dropdown-arrow {
    position: absolute;
    right: 12px;
    top: 14px;
    width: auto !important;
    font-size: 11px;
    transition: transform 0.3s ease;
}

.sidebar-dropdown.open .dropdown-arrow {
    transform: rotate(180deg);
}

.sidebar-submenu {
    display: none;
    background: #151a1d;
    padding: 5px 0;
}

.sidebar-dropdown.open .sidebar-submenu {
    display: block;
}

.sidebar-submenu a {
    padding: 9px 12px 9px 43px !important;
    font-size: 12px !important;
    line-height: 1.5;
}
#sidebar {
    overflow-y:scroll;
}

    </style>
</head>
<body>

<!-- Left Sidebar Menu -->
<div id="sidebar">
    <div class="sidebar-title">Admin</div>
    <a href="<?php echo $base_url; ?>/dashboard.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
    <a href="<?php echo $base_url; ?>/our-project/"><i class="fas fa-briefcase"></i> Our Project</a>
    <a href="<?php echo $base_url; ?>/blogs/"><i class="fas fa-blog"></i> Blogs</a>
    <a href="<?php echo $base_url; ?>/media/"><i class="far fa-newspaper"></i> Media</a>
    <a href="<?php echo $base_url; ?>/jobs/"><i class="fas fa-briefcase"></i> Jobs</a>
    
    <!-- Corporate Governance Dropdown -->
<div class="sidebar-dropdown">
    <a href="javascript:void(0)"
       class="dropdown-toggle-link"
       onclick="toggleGovernanceMenu(event)">
        <i class="fas fa-landmark"></i>
        Corporate Governance
        <i class="fas fa-chevron-down dropdown-arrow"></i>
    </a>

    <div id="governanceSubmenu" class="sidebar-submenu">
        <a href="<?php echo $base_url; ?>/committees">
            Composition of Committee
        </a>

        <a href="<?php echo $base_url; ?>/policies">
            Policies
        </a>

        <a href="<?php echo $base_url; ?>/corporate-governance-report">
            Corporate Governance Report
        </a>
    </div>
</div>

<a href="<?php echo $base_url; ?>/financials/"><i class="fas fa-briefcase"></i> Financials</a>
<a href="<?php echo $base_url; ?>/secretarial-compliance/"><i class="fas fa-briefcase"></i> Secretarial Compliance Report</a>
 <a href="<?php echo $base_url; ?>/awards/"><i class="fas fa-briefcase"></i> Awards</a>
 
  <a href="<?php echo $base_url; ?>/annual-reports/"><i class="fas fa-briefcase"></i> Annual Reports</a>
  
  
  <a href="<?php echo $base_url; ?>/in-the-news/"><i class="fas fa-briefcase"></i> In the News</a>
  
  <a href="<?php echo $base_url; ?>/statement-of-deviation/"><i class="fas fa-briefcase"></i> Statement of Deviation</a>
 
 
  <a href="<?php echo $base_url; ?>/shareholding-pattern/"><i class="fas fa-briefcase"></i> Shareholding Pattern</a>
  <a href="<?php echo $base_url; ?>/stock-exchange-filings/"><i class="fas fa-briefcase"></i> Stock Exchange Filings </a>
  <a href="<?php echo $base_url; ?>/dividend/"><i class="fas fa-briefcase"></i> Dividend </a>
  <a href="<?php echo $base_url; ?>/IEPF/"><i class="fas fa-briefcase"></i> IEPF </a>
  <a href="<?php echo $base_url; ?>/postal-ballot/"><i class="fas fa-briefcase"></i> Postal Ballot </a>
  <a href="<?php echo $base_url; ?>/forms-for-physical-shareholders/"><i class="fas fa-briefcase"></i> Forms for Physical Shareholders </a>
  <a href="<?php echo $base_url; ?>/demerger/"><i class="fas fa-briefcase"></i> Demerger </a>
  <a href="<?php echo $base_url; ?>/preferential-allotment/"><i class="fas fa-briefcase"></i> Preferential Allotment </a>
  <a href="<?php echo $base_url; ?>/split-Sub-division"><i class="fas fa-briefcase"></i> Split / Sub-division </a>
  <a href="<?php echo $base_url; ?>/NCDs/"><i class="fas fa-briefcase"></i> Non-Convertible Debentures (NCDs) </a>
  
  
  
  
  
  
  
  
  <a href="<?php echo $base_url; ?>/analyst-meet-intimation/"><i class="fas fa-briefcase"></i> Analyst Meet Intimation</a>
  <a href="<?php echo $base_url; ?>/analyst-notes/"><i class="fas fa-briefcase"></i> Analyst Notes</a>
  <a href="<?php echo $base_url; ?>/analyst-presentation/"><i class="fas fa-briefcase"></i> Analyst Presentation</a>
  
    
    <a href="<?php echo $base_url; ?>/media-press-release/"><i class="fas fa-briefcase"></i> Media / Press Release</a>
    <a href="<?php echo $base_url; ?>/in-the-news/"><i class="fas fa-briefcase"></i> In the News</a>
    <a href="<?php echo $base_url; ?>/newspaper-publication/"><i class="fas fa-briefcase"></i> Newspaper Publication</a>
    
  
   <a href="<?php echo $base_url; ?>/newsletter/"><i class="fas fa-briefcase"></i> Newsletter</a>
   <a href="<?php echo $base_url; ?>/forms/"><i class="fas fa-briefcase"></i> Forms</a>
</div>

<!-- Top Navbar (User Info & Logout) -->
<div id="topbar">
    <span><i class="fas fa-user-circle"></i> Admin User &nbsp;|&nbsp; </span>
    <a href="<?php echo $base_url; ?>/logout.php" style="color: #d63638; text-decoration: none; font-weight: 500; margin-left: 10px;">
        <i class="fas fa-sign-out-alt"></i> Logout
    </a>
</div>

<!-- Main Content Wrapper Starts Here -->
<div id="main-content">