<?php
// Minimal admin header
?><!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title><?php echo htmlspecialchars($title ?? 'Admin'); ?></title>
  <style>body{font-family:Arial,Helvetica,sans-serif;margin:0} .topbar{background:#222;color:#fff;padding:10px} .container{padding:20px} .sidebar{width:200px;float:left;background:#f7f7f7;height:100vh;padding:10px;box-sizing:border-box} .content{margin-left:220px;padding:20px}</style>
</head>
<body>
  <div class="topbar">
    <span><?php echo htmlspecialchars($_SESSION['name'] ?? 'Admin'); ?></span>
    <span style="float:right"><a style="color:#fff" href="<?php echo ADMIN_BASE;?>/logout.php">Logout</a></span>
  </div>
  <div class="sidebar">
    <h3>Menu</h3>
    <ul>
      <li><a href="<?php echo ADMIN_BASE;?>/dashboard.php">Dashboard</a></li>
      <li><a href="<?php echo ADMIN_BASE;?>/page.php?page=index.html">Index</a></li>
      <li><a href="<?php echo ADMIN_BASE;?>/page.php?page=gallery.html">Gallery</a></li>
      <li><a href="<?php echo ADMIN_BASE;?>/page.php?page=form-basic.html">Forms</a></li>
    </ul>
  </div>
  <div class="content">
