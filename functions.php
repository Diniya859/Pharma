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

        $supplierEmail = trim(strtolower($_POST['supplierEmail']));
        $password = trim($_POST['password']);
        $query = "SELECT  supplier_email , supplier_id ,supplier_password FROM supplier WHERE supplier_email= '$supplierEmail' ";
        $data = query($query);
        if (empty($supplierEmail) or empty($password)) {
            $_SESSION['message'] = "empty_err";
            post_redirect("logins.php");
        }
       
        if (empty($data)) {
            $_SESSION['message'] = "loginErr";
            post_redirect("logins.php");
        } else if ($password == $data[0]['supplier_password'] and  $supplierEmail == $data[0]['supplier_email']) {
            $_SESSION['supplier_id'] = $data[0]['supplier_id'];
            post_redirect("indexs.php");
        } else {
            $_SESSION['message'] = "loginErr";
            post_redirect("logins.php");
        }
    }
}
// messages function (start)

function signUp()
{
    if (isset($_POST['signUp'])) {
        $email  = trim(strtolower($_POST['email']));
        $fname  = trim($_POST['Fname']);
        $lname = trim($_POST['Lname']);
        $place = trim($_POST['place']);
    
        $passwd = trim($_POST['passwd']);
        if (empty($email) or empty($passwd) or empty($place)  or empty($fname) or empty($lname)) {
            $_SESSION['message'] = "empty_err";
            post_redirect("signUps.php");
        } else if (!preg_match("/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix", $email)) {
            $_SESSION['message'] = "signup_err_email";
            post_redirect("signUps.php");
        } else if (!preg_match('/^(?=.*\d)(?=.*[A-Za-z])[0-9A-Za-z!@#$%]{8,30}$/', $passwd)) {
            $_SESSION['message'] = "signup_err_password";
            post_redirect("signUps.php");
        }
        $query = "SELECT email FROM supplier ";
        $data = query($query);
        $count = sizeof($data);
        for ($i = 0; $i < $count; $i++) {
            if ($email == $data[$i]['email']) {
                $_SESSION['message'] = "usedEmail";
                post_redirect("signUps.php");
            }
        }
        $query = "INSERT INTO user (supplier_email ,supplier_fname ,supplier_lname ,supplier_place,supplier_password ) VALUES('$email', '$fname' ,'$lname','$place','$passwd')";
        $queryStatus = single_query($query);
        $query = "SELECT supplier_id FROM supplier WHERE supplier_email='$email' ";
        $data = query($query);
        $_SESSION['supplier_id'] = $data[0]['supplier_id'];
        if ($queryStatus == "done") {
            post_redirect("indexs.php");
        } else {
            $_SESSION['message'] = "wentWrong";
            post_redirect("signUps.php");
        }
    }
}
function message()
{
    if (isset($_SESSION['message'])) {
        if ($_SESSION['message'] == "signup_err_password") {
            echo "   <div class='alert alert-danger' role='alert'>
        please enter the password in correct form !!!
      </div>";
            unset($_SESSION['message']);
        } else if ($_SESSION['message'] == "loginErr") {
            echo "   <div class='alert alert-danger' role='alert'>
        The email or the password is incorrect !!!
      </div>";
            unset($_SESSION['message']);
        } else if ($_SESSION['message'] == "usedEmail") {
            echo "   <div class='alert alert-danger' role='alert'>
        This email is already used !!!
      </div>";
            unset($_SESSION['message']);
        } else if ($_SESSION['message'] == "wentWrong") {
            echo "   <div class='alert alert-danger' role='alert'>
        Something went wrong !!!
      </div>";
            unset($_SESSION['message']);
        } else if ($_SESSION['message'] == "empty_err") {
            echo "   <div class='alert alert-danger' role='alert'>
        please don't leave anything empty !!!
      </div>";
            unset($_SESSION['message']);
        } else if ($_SESSION['message'] == "signup_err_email") {
            echo "   <div class='alert alert-danger' role='alert'>
        please enter the email in the correct form !!!
      </div>";
            unset($_SESSION['message']);
        }
    }
}


function all_items()
{
    $query = "SELECT item_title,item_expiry,item_quantity FROM item";
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
    $query = "SELECT item_title,item_expiry,item_quantity FROM item WHERE item_id=$id";
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
function check_supplier($id)
{
    $query = "SELECT supplier_id FROM supplier where supplier_id='$id'";
    $row = query($query);
    if (empty($row)) {
        return 0;
    } else {
        return 1;
    }
}
function check_email_supplier($email)
{
    $query = "SELECT supplier_email FROM supplier WHERE supplier_email='$email'";
    $data = query($query);
    if ($data) {
        return $data;
    } else {
        return 0;
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
function get_supplier($id)
{
    $query = "SELECT * FROM supplier WHERE supplier_id=$id";
    $data = query($query);
    return $data;
}
