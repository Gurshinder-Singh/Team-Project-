<!-- navbar.php -->
<div class="navbar" id="navbar">
    <div class="dropdown">
        <button class="dropbtn">
            <img src="asset/menu_icon.png" alt="Menu Icon" class="menu-icon">
        </button>
        <div class="dropdown-content">
            <a href="about.php">About Us</a>
            <a href="contact.php">Contact Us</a>
            <a href="FAQ.php">FAQs</a>
        </div>
    </div>

    <a href="homepage.php">HOME</a>
    <a href="search.php">SEARCH</a>
    <div class="navbar-logo">
        <img src="asset/LUXUS_logo.png" alt="LUXUS_logo" id="luxusLogo">
    </div>
    
    <?php if (!isset($_SESSION['is_admin']) || !$_SESSION['is_admin']): ?>
        <a href="profile.html">PROFILE</a>
    <?php endif; ?>

    <a href="checkout.php">BASKET</a>

    <?php if (isset($_SESSION['is_admin']) && $_SESSION['is_admin']): ?>
        <a href="admin_page.php">ADMIN</a>
    <?php endif; ?>

    <!-- Additional buttons for admin page -->
    <?php if (isset($is_admin_page) && $is_admin_page): ?>
        <a href="manage_users.php">Manage Users</a>
        <a href="admin_settings.php">Settings</a>
    <?php endif; ?>
</div>

<style>
/* Navigation Bar Styles */
.navbar {
    height: 100px; /* Set your desired navbar height */
    display: flex;
    align-items: center;
    position: fixed;
    width: 100%;
    top: 0;
    background-color: #363636;
    transition: top 0.3s ease-in-out;
    will-change: transform; /* Use hardware acceleration */
    z-index: 1000; /* Ensure navbar stays above background shapes */
}

.navbar a {
    padding: 0 15px; /* Add some padding around the links */
    text-decoration: none; /* Remove underline from links */
    color: black; /* Set link color */
    font-weight: bold; /* Optional: Make the text bold */
    text-transform: uppercase; /* Make navbar links uppercase */
}

.navbar a:hover {
    color: gray; /* Change link color on hover */
}

.navbar img {
    height: 100%; /* Adjust this percentage to make the image bigger */
    max-height: 100%;
    margin-left: center; /* Push the logo to the right if needed */
}

/* Image Container Styles */
.image-container {
    display: flex;
    justify-content: center;
    position: absolute;
}

.image-container img {
    width: 650px; /* Adjust the width to make the image smaller */
    height: auto; /* Maintain the aspect ratio */
    position: absolute;
    left: 800px; /* Move the image 320 pixels to the right */
    top: 100px;
}
</style>