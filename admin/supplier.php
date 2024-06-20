<?php
include "includes/head.php";
?>

<body>
    <?php
    include "includes/header.php"
    ?>
     <?php
    include "includes/sidebar.php"
    ?>
    <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
        <?php
        message();
        ?>
        <div class="container">
            <div class="row align-items-start">
                <div class="col">
                    <br>
                    <h2>Supplier details</h2>
                    <br>
                </div>
                <div class="col">
                </div>
                <div class="col">
                    <br>
                    <form class="d-flex" method="GET" action="supplier.php">
                        <input class="form-control me-2 col" type="search" name="search_supplier_fname" placeholder="Search for supplier (fname)" aria-label="Search">
                        <button class="btn btn-outline-secondary" type="submit" name="search_supplier" value="search">Search</button>
                    </form>
                    <br>
                </div>
            </div>
        </div>
        <?php
        edit_supplier(isset($_SESSION['supplier_id']));
        if (isset($_GET['edit'])) {
            $_SESSION['id'] = $_GET['edit'];
            $data = get_supplier($_SESSION['supplier_id']);

        ?>
            <br>
            <h2>Edit Customer Details</h2>
            <form action="supplier.php" method="POST">
                <div class="form-group">
                    <label>First name</label>
                    <input pattern="[A-Za-z_]{1,15}" type="text" class="form-control" placeholder="<?php echo $data[0]['supplier_fname'] ?>" name="fname">
                    <div class="form-text">please enter the first name in range(1-30) character/s , special character & numbers not allowed !</div>
                </div>
                <br>
                <div class="form-group">
                    <label for="validationTooltip01">Last name</label>
                    <input pattern="[A-Za-z_]{1,15}" id="validationTooltip01" type="text" class="form-control" placeholder="<?php echo $data[0]['supplier_lname'] ?>" name="lname">
                    <div class="form-text">please enter the last name in range(1-30) character/s , special character & numbers not allowed !</div>
                </div>
                <br>
                <div class="form-group">
                    <label for="exampleInputEmail1">Email address</label>
                    <input type="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="<?php echo $data[0]['email'] ?>" name="email">
                    <div class="form-text">please enter the email in format : example@gmail.com.</div>
                </div>
                <br>
                <div class="form-group">
                    <label for="validationTooltip01">place</label>
                    <input pattern="[A-Za-z_]{1,15}" id="validationTooltip01" type="text" class="form-control" placeholder="<?php echo $data[0]['supplier_place'] ?>" name="lname">
                    <div class="form-text">please enter the place in range(1-30) character/s , special character & numbers not allowed !</div>
                </div>
                <br>
                
                <br>
                <button type="submit" class="btn btn-outline-success" value="update" name="update">Submit</button>
                <button type=" submit" class="btn btn-outline-danger" value="cancel" name="cancel">Cancel</button>
                <br> <br>
            </form>

        
            <?php
        }
        add_supplier();
        if (isset($_GET['add'])) {

        ?>
            <h2>Add new supplier </h2>
            <form action="supplier.php" method="POST">
                <div class="form-group">
                    <label>First name</label>
                    <input pattern="[A-Za-z_]{1,15}" type="text" class="form-control" placeholder="First name" name="supplier_fname">
                    <div class="form-text">please enter the first name in range(1-30) character/s , special character & numbers not allowed !</div>
                </div>
                <br>
                <div class="form-group">
                    <label for="validationTooltip01">Last name</label>
                    <input pattern="[A-Za-z_]{1,15}" id="validationTooltip01" type="text" class="form-control" placeholder="Last name" name="supplier_lname">
                    <div class="form-text">please enter the last name in range(1-30) character/s , special character & numbers not allowed !</div>
                </div>
                <br>
                <div class="form-group">
                    <label for="validationTooltip01">place</label>
                    <input pattern="[A-Za-z_]{1,15}" id="validationTooltip01" type="text" class="form-control" placeholder="place" name="supplier_place">
                    <div class="form-text">please enter the place!</div>
                </div>
                <br>
                <div class="form-group">
                    <label for="exampleInputEmail1">Email address</label>
                    <input type="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Email address" name="supplier_email">
                    <div class="form-text">please enter the email in format : example@gmail.com.</div>
                </div><br>
                <div class="form-group">
                    <label for="exampleInputPassword1">Password</label>
                    <input type="password" pattern="^\S*(?=\S{8,})(?=\S*[a-z])(?=\S*[A-Z])(?=\S*[\d])\S*$" class="form-control" placeholder="Password" name="supplier_password">
                    <div class="form-text">
                        <ul>
                            <li>Must be a minimum of 8 characters</li>
                            <li>Must contain at least 1 number</li>
                            <li>Must contain at least one uppercase character</li>
                            <li>Must contain at least one lowercase character</li>
                        </ul>
                    </div>
                </div>
                <br>
                <button type="submit" class="btn btn-outline-primary" value="update" name="add_supplier">Submit</button>
                <button type=" submit" class="btn btn-outline-danger" value="cancel" name="supplier_cancel">Cancel</button>
                <br> <br>
            </form>

        <?php
        }

        ?>
        
        <div class="table-responsive">
            <table class="table table-striped table-sm">
                <thead>
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">First Name</th>
                        <th scope="col">Last Name</th>
                        <th scope="col">Email</th>
                        <th scope="col">Place</th>
                        <th scope="col">
                        <th scope="col">
                            <button type="button" class="btn btn-outline-primary "><a style="text-decoration: none; color:black;" href="supplier.php?add=1"> &nbsp;&nbsp;Add&nbsp;&nbsp;</a></button>
                        </th>
                        </th>
                       
                </thead>

                <tbody>
                    <?php
                    $data = all_supplier();
                    delete_supplier();
                    if (isset($_GET['search_supplier'])) {
                        $query = search_supplier();
                        if (isset($query)) {
                            $data = $query;
                        } else {
                            get_redirect("supplier.php");
                        }
                    } elseif (isset($_GET['id'])) {
                        $data = get_supplier_details();
                    }
                    $num = sizeof($data);
                    for ($i = 0; $i < $num; $i++) {
                    ?>
                        <tr>
                            <td><?php echo $data[$i]['supplier_id'] ?></td>
                            <td><?php echo $data[$i]['supplier_fname'] ?></td>
                            <td><?php echo $data[$i]['supplier_lname'] ?></td>
                            <td><?php echo $data[$i]['supplier_email'] ?></td>
                            <td><?php echo $data[$i]['supplier_place'] ?></td>
                           
                            <!--td>
                                <button type="button" class="btn pull-left btn-outline-warning"><a style="text-decoration: none; color:black;" href="customers.php?edit=<?php echo $data[$i]['user_id'] ?>">Edit</a></button>
                            </!--td-->
                            <td>
                                <button type="button" class="btn pull-left btn-outline-danger"><a style="text-decoration: none; color:black;" href="supplier.php?delete=<?php echo $data[$i]['supplier_id'] ?>">Delete</a></button>
                            </td>
                        </tr>
                    <?php  }
                    ?>
                </tbody>
            </table>
        </div>

    </main>
    </div>
    </div>
    <?php
    include "includes/footer.php"
    ?>
</body>