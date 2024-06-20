<?php
$connection = mysqli_connect("localhost", "root", "", "Pharma");
// $connection = mysqli_connect("localhost", "id18666014_pharma1", "tXU!y/6D\EH_{<[6", "id18666014_pharma");
function query($query)
{
    global $connection;
    $run = mysqli_query($connection, $query);
    if($run) {
        while ($row = $run->fetch_assoc()) {
            $data[] = $row;
        }
        return $data;
    } else {
        return 0;
    }
}
function single_query($query)
{
    global $connection;
    $run = mysqli_query($connection, $query);
    if($run) {
        return 1;
    } else {
        return 0;
    }
}

function post_redirect($url)
{
    ob_start();
    header('Location: ' . $url);

    ob_end_flush();
    die();
}
function get_redirect($url)
{
    echo " <script> 
    window.location.href = '$url'; 
    </script>";
    
}

function login()
{
    if (isset($_POST['login'])) {

        $adminEmail = trim(strtolower($_POST['adminEmail']));
        $password = trim($_POST['password']);
        $query = "SELECT  admin_email , admin_id , admin_password FROM admin WHERE admin_email= '$adminEmail' ";
        $data = query($query);
        if (empty($adminEmail) or empty($password)) {
            $_SESSION['message'] = "empty_err";
            post_redirect("login.php");
        }
       
        if (empty($data)) {
            $_SESSION['message'] = "loginErr";
            post_redirect("login.php");
        } else if ($password == $data[0]['admin_password'] and  $adminEmail == $data[0]['admin_email']) {
            $_SESSION['admin_id'] = $data[0]['admin_id'];
            post_redirect("index.php");
        } else {
            $_SESSION['message'] = "loginErr";
            post_redirect("login.php");
        }
    }
}
// messages function (start)
function message()
{
    if(isset($_SESSION['message']) &&  $_SESSION['message'] == "loginErr") {
        echo "   <div class='alert alert-danger' role='alert'>
        There is no account with this email !!!
      </div>";

      unset($_SESSION['message']);
    } else if(isset($_SESSION['message']) && $_SESSION['message'] == "emailErr") {
        echo "   <div class='alert alert-danger' role='alert'>
        The email address is already taken.  Please choose another
      </div>";
        unset($_SESSION['message']);
    } else if(isset($_SESSION['message']) && $_SESSION['message'] == "loginErr1") {
        echo "   <div class='alert alert-danger' role='alert'>
        The email or password is wrong!
      </div>";
        unset($_SESSION['message']);
    } else if(isset($_SESSION['message']) && $_SESSION['message'] == "noResult") {
        echo "   <div class='alert alert-danger' role='alert'>
        There is no user .
      </div>";
        unset($_SESSION['message']);
    } else if(isset($_SESSION['message']) && $_SESSION['message'] == "itemErr") {
        echo "   <div class='alert alert-danger' role='alert'>
        There is a product with the same name .
      </div>";
        unset($_SESSION['message']);
    } else if (isset($_SESSION['message']) && $_SESSION['message'] == "noResultOrder") {
        echo "   <div class='alert alert-danger' role='alert'>
        There is no order with this ID !!!
      </div>";
        unset($_SESSION['message']);
    } else if (isset($_SESSION['message']) && $_SESSION['message'] == "noResultItem") {
        echo "   <div class='alert alert-danger' role='alert'>
        There is no product with this name !!!
      </div>";
        unset($_SESSION['message']);
    } else if(isset($_SESSION['message']) && $_SESSION['message'] == "noResultAdmin") {
        echo "   <div class='alert alert-danger' role='alert'>
        There is no admin with this name !!!
      </div>";
        unset($_SESSION['message']);}
        else if(isset($_SESSION['message']) && $_SESSION['message'] == "noResultSupplier") {
            echo "   <div class='alert alert-danger' role='alert'>
            There is no supplier with this name !!!
          </div>";
            unset($_SESSION['message']);
    } else if(isset($_SESSION['message']) && $_SESSION['message'] == "empty_err") {
        echo "   <div class='alert alert-danger' role='alert'>
    please don't leave anything empty !!!
  </div>";
        unset($_SESSION['message']);
    }
}
// messages function (end)

// user functions (start)
function all_users()
{
    $query = "SELECT user_id ,user_fname ,user_lname ,email ,user_phn,user_address,user_state,user_pincode FROM user";
    $data = query($query);
    return $data;
}
function delete_user()
{
    if (isset($_GET['delete'])) {
        $userId = $_GET['delete'];
        $query = "DELETE FROM user WHERE user_id ='$userId'";
        $run = single_query($query);
        get_redirect("customers.php");
    }
}
function edit_user($id)
{
    if (isset($_POST['update'])) {
        $fname = trim($_POST['fname']);
        $lname = trim($_POST['lname']);
        $email = trim(strtolower($_POST['email']));
        $phn = trim($_POST['phn']);
        $address = trim($_POST['address']);
        $state = trim($_POST['state']);
        $pincode = trim($_POST['pincode']);
        if (empty($email) or empty($address) or empty($fname) or empty($lname)or empty($pincode) or empty($state) or empty($phn)) {
            $_SESSION['message'] = "empty_err";
            get_redirect("customers.php");
            return;
        }
        $check = check_email_user($email);
        if ($check == 0) {
            $query = "UPDATE user SET email='$email' ,user_fname='$fname' ,user_lname='$lname' ,user_address='$address' WHERE user_id= '$id'";
            single_query($query);
            get_redirect("customers.php");
        } else {
            $_SESSION['message'] = "emailErr";
            get_redirect("customers.php");
        }
    } else if(isset($_POST['cancel'])) {
        get_redirect("customers.php");
    }
}
function get_user($id)
{
    $query = "SELECT user_id ,user_fname ,user_lname ,email ,user_phn,user_address,user_state,user_pincode FROM user WHERE user_id=$id";
    $data = query($query);
    return $data;
}
function check_email_user($email)
{
    $query = "SELECT email FROM user WHERE email='$email'";
    $data = query($query);
    if($data) {
        return 1;
    } else {
        return 0;
    }
}
/*function search_user()
{
    if(isset($_GET['search_user'])) {
        $email = trim(strtolower($_GET['search_user_email']));
        if(empty($email)) {
            return;
        }
        $query = "SELECT user_id ,user_fname ,user_lname ,email ,user_phn,user_address,user_state,user_pincode FROM user WHERE email='$email'";
        $data = query($query);
        if($data) {
            return $data;
        } else {
            $_SESSION['message'] = "noResult";
            return;
        }
    }
}*/
function search_user()
{
    if (isset($_GET['search_user'])) {
        $fname = trim($_GET['search_user_fname']);
        
        if(empty($fname)) {
            return;
        }
        $query = "SELECT * FROM user WHERE user_fname LIKE '%$fname%'";
        $data = query($query);
        if ($data) {
            return $data;
        } else {
            $_SESSION['message'] = "noResult";
            return;
        }
    }
}

function get_user_details()
{
    if($_GET['id']) {
        $id = $_GET['id'];
        $query = "SELECT * FROM user WHERE user_id=$id";
        $data = query($query);
        return $data;
    }
}
// user functions (end)
//supplier functions (start)
function all_supplier()
{
    $query = "SELECT supplier_id ,supplier_fname ,supplier_lname ,supplier_email ,supplier_place FROM supplier";
    $data = query($query);
    return $data;
}
function delete_supplier()
{
    if (isset($_GET['delete'])) {
        $userId = $_GET['delete'];
        $query = "DELETE FROM supplier WHERE user_id ='$supplierId'";
        $run = single_query($query);
        get_redirect("supplier.php");
    }
}
function edit_supplier($id)
{
    if (isset($_POST['update'])) {
        $fname = trim($_POST['fname']);
        $lname = trim($_POST['lname']);
        $email = trim(strtolower($_POST['email']));
        $place = trim($_POST['place']);
        if (empty($email) or empty($place) or empty($fname) or empty($lname)) {
            $_SESSION['message'] = "empty_err";
            get_redirect("supplier.php");
            return;
        }
        $check = check_email_supplier($email);
        if ($check == 0) {
            $query = "UPDATE supplier SET supplier_email='$email' ,supplier_fname='$fname' ,supplier_lname='$lname' ,supplier_place='$address' WHERE supplier_id= '$id'";
            single_query($query);
            get_redirect("supplier.php");
        } else {
            $_SESSION['message'] = "emailErr";
            get_redirect("supplier.php");
        }
    } else if(isset($_POST['cancel'])) {
        get_redirect("supplier.php");
    }
}
function get_supplier($id)
{
    $query = "SELECT supplier_id ,supplier_fname ,supplier_lname ,supplier_email ,supplier_place FROM user WHERE supplier_id=$id";
    $data = query($query);
    return $data;
}
function check_email_supplier($email)
{
    $query = "SELECT supplier_email FROM supplier WHERE supplier_email='$email'";
    $data = query($query);
    if($data) {
        return 1;
    } else {
        return 0;
    }
}
function search_supplier()
{
    if (isset($_GET['search_supplier'])) {
        $fname = trim($_GET['search_supplier_fname']);
        if (empty($id)) {
            return;
        }
        $query = "SELECT * FROM supplier WHERE supplier_fname LIKE '%$fname%'";
        $data = query($query);
        if ($data) {
            return $data;
        } else {
            $_SESSION['message'] = "noResultSupplier";
            return;
        }
    }
}

function get_supplier_details()
{
    if($_GET['supplier_id']) {
        $id = $_GET['supplier_id'];
        $query = "SELECT * FROM supplier WHERE supplier_id=$id";
        $data = query($query);
        return $data;
    }
}
//supplier function(end)
// item functions (start)
function all_items()
{
    $query = "SELECT * FROM item";
    $data = query($query);
    return $data;
}
function delete_item()
{
    if (isset($_GET['delete'])) {
        $itemID = $_GET['delete'];
        $query = "DELETE FROM item WHERE item_id ='$itemID'";
        $run = single_query($query);
        get_redirect("products.php");
    }
}
function edit_item($id)
{
    if (isset($_POST['update'])) {
        $name = trim($_POST['name']);
        $brand = trim($_POST['brand']);
        $cat = trim($_POST['cat']);
        $tags = trim($_POST['tags']);
        $image = trim($_POST['image']);
        $quantity = trim($_POST['quantity']);
        $price = trim($_POST['price']);
        $expiry = trim($_POST['expiry']);
        $details = trim($_POST['details']);
        $check = check_name($name);
        if ($check == 0) {
            $query = "UPDATE item SET item_title='$name' ,item_brand='$brand' ,item_cat='$cat' ,
            item_details='$details',item_tags='$tags' 
            ,item_image='$image' ,item_quantity='$quantity' ,item_price='$price',item_expiry='$expiry'  WHERE item_id= '$id'";
            $run = single_query($query);
            get_redirect("products.php");
        } else {
            $_SESSION['message'] = "itemErr";
            get_redirect("products.php");
        }
    } else if (isset($_POST['cancel'])) {
        get_redirect("products.php");
    }
}
function get_item($id)
{
    $query = "SELECT * FROM item WHERE item_id=$id";
    $data = query($query);
    return $data;
}
function check_name($name)
{
    $query = "SELECT item_title FROM item WHERE item_title='$name'";
    $data = query($query);
    if ($data) {
        return 1;
    } else {
        return 0;
    }
}
function search_item()
{
    if (isset($_GET['search_item'])) {
        $name = trim($_GET['search_item_name']);
        $query = "SELECT * FROM item WHERE item_title LIKE '%$name%'";
        $data = query($query);
        if ($data) {
            return $data;
        } else {
            $_SESSION['message'] = "noResultItem";
            return;
        }
    }
}
function add_item()
{
    if (isset($_POST['add_item'])) {
        $name = trim($_POST['name']);
        $brand = trim($_POST['brand']);
        $cat = trim($_POST['cat']);
        $tags = trim($_POST['tags']);
        $image = trim($_POST['image']);
        $quantity = trim($_POST['quantity']);
        $price = trim($_POST['price']);
        $details = trim($_POST['details']);
        $expiry= trim($_POST['expiry']);
        $check = check_name($name);
        if (
            empty($name) or empty($brand) or empty($cat)  or
            empty($tags) or empty($image) or empty($quantity) or empty($price) or empty($details) or empty($expiry)
        ) {
            $_SESSION['message'] = "empty_err";
            get_redirect("products.php");
            return;
        }
        if ($check == 0) {
            $query = "INSERT INTO item (item_title, item_brand, item_cat, item_details  ,
            item_tags ,item_image ,item_quantity ,item_price,item_expiry) VALUES
            ('$name' ,'$brand' ,'$cat' ,'$details' ,'$tags' ,'$image' ,'$quantity' ,'$price','$expiry')";
            $run = single_query($query);
            get_redirect("products.php");
        } else {
            $_SESSION['message'] = "itemErr";
            get_redirect("products.php");
        }
    } else if (isset($_POST['cancel'])) {
        get_redirect("products.php");
    }
}
function get_item_details()
{
    if ($_GET['id']) {
        $id = $_GET['id'];
        $query = "SELECT * FROM item WHERE item_id=$id";
        $data = query($query);
        return $data;
    }
}
// item functions (end)
// admin functions (start)
function all_admins()
{
    $query = "SELECT admin_id ,admin_fname ,admin_lname ,admin_email  FROM admin";
    $data = query($query);
    return $data;
}
function get_admin($id)
{
    $query = "SELECT admin_id ,admin_fname ,admin_lname ,admin_email  FROM admin WHERE admin_id=$id";
    $data = query($query);
    return $data;
}
function edit_admin($id)
{
    if (isset($_POST['admin_update'])) {
        $fname = trim($_POST['admin_fname']);
        $lname = trim($_POST['admin_lname']);
        $email = trim(strtolower($_POST['admin_email']));
        $password = trim($_POST['admin_password']);
        $check = check_email_admin($email);
        if ($check == 0) {
            $query = "UPDATE admin SET admin_email='$email' ,admin_fname='$fname' ,admin_lname='$lname' ,admin_password='$password'  WHERE admin_id= '$id'";
            single_query($query);
            get_redirect("admin.php");
        } else {
            $_SESSION['message'] = "emailErr";
            get_redirect("admin.php");
        }
    } else if (isset($_POST['admin_cancel'])) {
        get_redirect("admin.php");
    }
}
function check_email_admin($email)
{
    $query = "SELECT admin_email FROM admin WHERE admin_email='$email'";
    $data = query($query);
    if ($data) {
        return $data;
    } else {
        return 0;
    }
}
function add_admin()
{
    if (isset($_POST['add_admin'])) {
        $fname = trim($_POST['admin_fname']);
        $lname = trim($_POST['admin_lname']);
        $email = trim(strtolower($_POST['admin_email']));
        $password = trim($_POST['admin_password']);
        $check = check_email_admin($email);
        if ($check == 0) {
            $query = "INSERT INTO admin (admin_fname, admin_lname, admin_email, admin_password) 
            VALUES ('$fname','$lname','$email','$password')";
            single_query($query);
            get_redirect("admin.php");
        } else {
            $_SESSION['message'] = "emailErr";
            get_redirect("admin.php");
        }
    } else if (isset($_POST['admin_cancel'])) {
        get_redirect("admin.php");
    }
}
function delete_admin()
{
    if (isset($_GET['delete'])) {
        $adminId = $_GET['delete'];
        $query = "DELETE FROM admin WHERE admin_id ='$adminId'";
        $run = single_query($query);
        get_redirect("admin.php");
    }
}
function search_admin()
{
    if (isset($_GET['search_admin'])) {

        $fname= trim($_GET['search_admin_fname']);
        if (empty($id)) {
            return;
        }
        $query = "SELECT * FROM admin WHERE admin_fname LIKE '%$fname%'";
        $data = query($query);
        if ($data) {
            return $data;
        } else {
            $_SESSION['message'] = "noResultAdmin";
            return;
        }
    }
}
function search_order()
{
    if (isset($_GET['search_order'])) {
        $id = trim($_GET['search_order_id']);
        if (empty($id)) {
            return;
        }
        $query = "SELECT * FROM orders WHERE order_id='$id'";
        $data = query($query);
        if ($data) {
            return $data;
        } else {
            $_SESSION['message'] = "noResultOrder";
            return;
        }
    }
}
function check_admin($id)
{
    $query = "SELECT admin_id FROM admin where admin_id='$id'";
    $row = query($query);
    if (empty($row)) {
        return 0;
    } else {
        return 1;
    }
}
// admin functions (end)
// order functions (start)
function all_orders()
{
    $query = "SELECT * FROM orders";
    $data = query($query);
    return $data;
}

function delete_order()
{
    if (isset($_GET['delete'])) {
        $order_id = $_GET['delete'];
        $query = "DELETE FROM orders WHERE order_id ='$order_id'";
        $run = single_query($query);
        get_redirect("orders.php");
    } else if (isset($_GET['done'])) {
        $order_id = $_GET['done'];
        $query = "UPDATE orders SET order_status = 1 WHERE order_id='$order_id'";
        single_query($query);
        get_redirect("orders.php");
    } else if (isset($_GET['undo'])) {
        $order_id = $_GET['undo'];
        $query = "UPDATE orders SET order_status = 0 WHERE order_id='$order_id'";
        single_query($query);
        get_redirect("orders.php");
    }
}
// order functions (end)
function add_supplier()
{
    if (isset($_POST['add_supplier'])) {
        $fname = trim($_POST['supplier_fname']);
        $lname = trim($_POST['supplier_lname']);
        $place = trim($_POST['supplier_place']);
        $email = trim(strtolower($_POST['supplier_email']));
        $password = trim($_POST['supplier_password']);
        $check = check_email_supplier($email);
        if ($check == 0) {
            $query = "INSERT INTO supplier (supplier_fname, supplier_lname, supplier_place,supplier_email,supplier_password) 
            VALUES ('$fname','$lname','$place','$email','$password')";
            single_query($query);
            get_redirect("supplier.php");
        } else {
            $_SESSION['message'] = "emailErr";
            get_redirect("supplier.php");
        }
    } else if (isset($_POST['supplier_cancel'])) {
        get_redirect("supplier.php");
    }
}