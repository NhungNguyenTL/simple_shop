<?php

session_start();
require_once('database.php');
$database = new Database();
echo "<pre>";
print_r($_POST);
echo "</pre>";

if (isset($_POST) && !empty($_POST)) {
    if (isset($_POST['action'])) {
        switch($_POST['action']) {
            case 'add' :
                if (isset($_POST['quantity']) && isset($_POST['product_id'])) {
                    $sql = "SELECT * FROM products WHERE id=". (int)$_POST['product_id'];
                    $product = $database->runQuery($sql);
                    $product = current($product);//lấy dữ liệu trong array
                    $product_id = $product['id'];
                    if (isset($_SESSION['cart_item']) &&!empty($_SESSION['cart_item'])) {
                        if (isset($_SESSION['cart_item'][$product_id])) {
                            //Xem SP mình add đã tồn tại trong giỏ hàng
                            $exits_cart_item = $_SESSION['cart_item'][$product_id];
                            $exits_quantity = abs($exits_cart_item['quantity']);
                            $cart_item = array();
                            $cart_item['id'] = $product['id'];
                            $cart_item['product_name'] = $product['product_name'];
                            $cart_item['product_image'] = $product['product_image'];
                            $cart_item['price'] = $product['price'];
                            $cart_item['quantity'] = abs($exits_quantity) + abs($_POST['quantity']);//update sl trong giỏ hàng= sl cũ+ sl mới
                            $_SESSION['cart_item'][$product_id] = $cart_item;
                        } else {
                            //SP chưa tồn tại trong giỏ hàng
                            $cart_item = array();
                            $cart_item['id'] = $product['id'];
                            $cart_item['product_name'] = $product['product_name'];
                            $cart_item['product_image'] = $product['product_image'];
                            $cart_item['price'] = $product['price'];
                            $cart_item['quantity'] = abs($_POST['quantity']);
                            $_SESSION['cart_item'][$product_id] = $cart_item;
                        }
                    } else {
                        $_SESSION['cart_item'] = array();
                        $product_id = $product['id'];
                        $cart_item = array();
                        $cart_item['id'] = $product['id'];
                        $cart_item['product_name'] = $product['product_name'];
                        $cart_item['product_image'] = $product['product_image'];
                        $cart_item['price'] = $product['price'];
                        $cart_item['quantity'] = abs($_POST['quantity']);
                        $_SESSION['cart_item'][$product_id] = $cart_item;
                    }
                }
                break;
            case 'remove' :
                if (isset($_POST['product_id'])){
                    $product_id = $_POST['product_id'];
                    if (isset($_SESSION['cart_item'][$product_id])) {
                        unset($_SESSION['cart_item'][$product_id]);
                    }
                }
                break;
            default:
                echo "Action không tồn tại";
                die;
        }
    }
}



die;