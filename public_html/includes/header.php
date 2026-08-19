<?php
$currentPage = basename($_SERVER['SCRIPT_NAME']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= isset($pageTitle) ? htmlspecialchars($pageTitle) . ' | Roop Shree Construction' : 'Roop Shree Construction' ?></title>
<meta name="description" content="<?= isset($pageDescription) ? htmlspecialchars($pageDescription) : 'Roop Shree Construction — trusted real estate developer in Jodhpur.' ?>">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="/assets/css/style.css">
</head>
<body>
<header class="site-header">
  <div class="container site-header__inner">
    <a href="/index.php" class="logo" aria-label="Roop Shree Construction — Home">
      <img src="/assets/logo/logo.png" alt="Roop Shree Construction" class="logo__image">
    </a>

    <button class="nav-toggle" id="navToggle" aria-expanded="false" aria-controls="primaryNav" aria-label="Toggle menu">
      <span></span><span></span><span></span>
    </button>

    <nav class="primary-nav" id="primaryNav">
      <a href="/index.php" class="<?= $currentPage === 'index.php' ? 'is-active' : '' ?>">Home</a>
      <a href="/properties.php" class="<?= $currentPage === 'properties.php' || $currentPage === 'property.php' ? 'is-active' : '' ?>">Properties</a>
      <a href="/about.php" class="<?= $currentPage === 'about.php' ? 'is-active' : '' ?>">About</a>
      <a href="/contact.php" class="<?= $currentPage === 'contact.php' ? 'is-active' : '' ?>">Contact</a>
    </nav>
  </div>
</header>
<main>
