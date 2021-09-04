<?php 
    //inclue constants file
    include('../config/constants.php');
    //check whether the id and image_name value is set or not
    if(isset($_GET['id']) AND isset($_GET['image_name']))
    {
        //get the value and delete
        $id = $_GET['id'];
        $image_name = $_GET['image_name'];

        //remove the physical image file if it is available
        if($image_name != "")
        {
            //image name Available. so remove it
            $path = "../images/food/".$image_name;
            
            //remove the image
            $remove = unlink($path);
            //if failed to remove image add an error message and stop the process
            if($remove == false)
            {
                //set the session message
                $_SESSION['remove'] = "<div class='error'>Failed to Remove food Image.</div>";
                //redirect to manage food page
                header('location:'.SITEURL.'admin/manage-food.php');
                //stop the process
                die();
            }
        }
        //remove data from datbase
        $sql = "DELETE FROM tbl_food WHERE id=$id";
        //execute the query
        $res = mysqli_query($conn, $sql);

        //check whether the data is deleted form database or not
        if($res == true)
        {
            //set success message and redirect to manage-food oage
            $_SESSION['delete'] = "<div class='success'>Food Deleted Successfully.</div>";
            header('location:'.SITEURL.'admin/manage-food.php');
        }
        else
        {
            //sete failed message and redirect
            $_SESSION['delete'] = "<div class='error'>Failed to Delete Food.</div>";
            header('location:'.SITEURL.'admin/manage-food.php');
        }
        
    }
    else
    {   //todo: security feature
        $_SESSION['unauthorized'] = "<div class='error'>Unauthorized Access</div>";
        //redirect to manage food page
        header('location:'.SITEURL.'admin/manage-food.php');
    }

?>