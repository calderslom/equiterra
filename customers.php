<script>
  function searchTable() {
    var input, filter, table, tr, td, i, txtValue;
    input = document.getElementById("searchInput");
    filter = input.value.toUpperCase();
    table = document.getElementsByClassName("horse-table")[0];
    tr = table.getElementsByTagName("tr");

    for (i = 0; i < tr.length; i++) {
      td = tr[i].getElementsByTagName("td")[0]; // Change the index to the column you want to search
      if (td) {
        txtValue = td.textContent || td.innerText;
        if (txtValue.toUpperCase().indexOf(filter) > -1) {
          tr[i].style.display = "";
        } else {
          tr[i].style.display = "none";
        }
      }       
    }
  }
</script>

<html>
  <head>
    <link rel="stylesheet" href="style.css">
  <head>
  <body>
    <div class="onboarding-overlay">
      <div class="onboarding-overlay-outer">
        <?php include 'navbar.php'; ?>
        <img class="barns-image" src="images/customers.png" alt="Barns">
        <div class="onboarding-overlay-inner table">
          <?php
          // TODO: must be changed to the barns info from the database (using their username)
            if (isset($_SESSION['customers']) && count($_SESSION['customers']) > 0) {
              echo "<div class='action-bar'>";
              echo "<div class='search-container'><input class='search-table' type='text' id='searchInput' onkeyup='searchTable()' placeholder='Search customers..'></div>";
              if ($_SESSION['user_type'] == "Admin") {
                echo "<a href='signup.php'><button class='add-button'>Add Customer +</button></a>";
              }
              echo "</div>";
              echo "<table class='horse-table'>";
              echo "<tr><th>Name</th><th>Action</th></tr>";
              // Output data of each row
              foreach($_SESSION['customers'] as $customer) {
                echo "<tr>";
                echo "<td>" . $customer . "</td>";
                echo "<td><a href='barn.php?customer_name=" . urlencode($customer) . "'><button class='table-button'>View/Edit</button></a></td>";
                echo "</tr>";
              }
              echo "</table>";
            } else {
              echo "<div class='returning__header'>No customers in database <a href='signup.php'><button class='add-button'>Add Customer +</button></a></div>";
            }
          ?>
        </div>
        <p class="overlay-copyright">&copy;2023 Omar, Aidan, Youssef</p>
      </div>
    </div>
  </body>
</html>