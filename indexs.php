<?php
include "head.php";
?>

<body>

  <?php
  include "header.php";
  ?>

  <div class=" container-fluid">


    <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
      <br>
      <div class="row">
       
        <div class="card" style="width: 25rem;margin-bottom: 20px ;">
          <a href="products.php">
            <img class="card-img-top" src="images/package.svg" alt="Card image cap" style="width: 5rem;margin-top: 20px ;">
          </a>
          <div class="card-body">
            <h5 class="card-title">Products</h5>
            <p class="card-text">Display and Supply medicine</p>
            <a href="products.php" class="btn btn-primary">Go to products page</a>
          </div>
        </div>
      </div>
      
    </main>
  </div>

  <?php
  include "footer.php"
  ?>
</body>

</html>