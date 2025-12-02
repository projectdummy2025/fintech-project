<?php
echo "<h1>Available PDO Drivers</h1>";
echo "<pre>";
print_r(PDO::getAvailableDrivers());
echo "</pre>";
phpinfo();
