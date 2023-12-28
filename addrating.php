<?php

include('connection.php');

      $review = $_GET['rating'];
      $product_id = $_GET['product_id'];
      $user_id = $_GET['user_id'];

       $sql = "SELECT * FROM REVIEW WHERE FK1_CUST_ID= :user_id AND FK2_PRODUCT_ID = :product_id";
            $stmt = oci_parse($conn, $sql);
            oci_bind_by_name($stmt, ":user_id", $user_id);
            oci_bind_by_name($stmt, ":product_id", $product_id);
            oci_execute($stmt);
            while ($data = oci_fetch_array($stmt)) {
                if (isset($data['REVIEW_ID'])) {
                    $review_id = $data['REVIEW_ID'];
                }
            }

            if (!empty($review_id)) {
                $sql = "UPDATE REVIEW SET RATING = :review WHERE REVIEW_ID = :review_id";
                $stid = oci_parse($conn, $sql);
                oci_bind_by_name($stid, ":review_id", $review_id);
                oci_bind_by_name($stid, ":review", $review);

                if (oci_execute($stid)) {
                    echo "Rating is successfully recorded!!";
                }
            } else {

                $sql = "INSERT INTO REVIEW (FK1_CUST_ID,FK2_PRODUCT_ID, RATING) VALUES (:user_id, :product_id, :review)";
                $stid = oci_parse($conn, $sql);
                oci_bind_by_name($stid, ":user_id", $user_id);
                oci_bind_by_name($stid, ":product_id", $product_id);
                oci_bind_by_name($stid, ":review", $review);

                if (oci_execute($stid)) {
                    echo "Rating is successfully recorded!!";
                }
            }

    

?>
