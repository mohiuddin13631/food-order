<?php include('partials/menue.php') ?>

<?php

    //check whether id is set or not
    if(isset($_GET['id']))
    {
        //get all details
        $id = $_GET['id'];
        //Sql query to get the selected food
        $sql2 = "SELECT * FROM tbl_food WHERE id = $id";
        //execute the query
        $res2 = mysqli_query($conn,$sql2);

        //count row do some code for authenticaton
        $count2 = mysqli_num_rows($res2);
        if($count2 == 1) //here 1 because we are finding data for only one id
        {
            // data founded
            // Get the value based on query executed
            $row2 = mysqli_fetch_assoc($res2);

            //get the individual value of selected food
            $title = $row2['title'];
            $description = $row2['description'];
            $price = $row2['price'];
            $current_image = $row2['image_name'];
            $current_category = $row2['category_id'];
            $featured = $row2['featured'];
            $active = $row2['active'];
        }
        else
        {
            //todo: Authontication Check
            header('location:'.SITEURL.'admin/manage-food.php');
            $_SESSION['unauthorized'] = "<div class='error'>Unauthorized Access.</div>";
        }
    }
    else
    {
        //redirect ot manage food page
        //todo: Authontication Check
        header('location:'.SITEURL.'admin/manage-food.php');
        //display message
        $_SESSION['no-category-found'] = "<div class='error'>Category Not Found</div>";
    }

?>

<div class="main-content">
    <div class="wrapper">
        <h1>Upadate Food</h1>
        <br><br>
        <?php
        if(isset($_SESSION['update']))
            {
                echo $_SESSION['update'];
                unset($_SESSION['update']);
            }
        ?>

        <form action="" method="POST" enctype="multipart/form-data">

            <table class="tbl-30">

                <tr>
                    <td>Title </td>
                    <td>
                        <input type="text" name="title" value="<?php echo $title; ?>">
                    </td>
                </tr>
                
                <tr>
                    <td>Descriotion </td>
                    <td>
                        <textarea name="description" cols="30" rows="5"><?php echo $description; ?></textarea>
                    </td>
                </tr>

                <tr>
                    <td>Price</td>
                    <td>
                        <input type="number" name="price" value="<?php echo $price; ?>">
                    </td>
                </tr>

                <tr>
                    <td>Current_Image</td>
                    <td>
                        <?php
                            //check whether image is available or not
                            if($current_image != "")
                            {
                                //image available
                                ?>
                                    <img src="<?php echo SITEURL; ?>images/food/<?php echo $current_image; ?>" width=100px>
                                <?php

                            }
                            else
                            {
                                //image is not available
                                echo "<div class='error'>Image Not Available</div>";
                            }
                        ?>
                    </td>
                </tr>

                <tr>
                    <td>New Image </td>
                    <td>
                        <input type="file" name="image">
                    </td>
                </tr>

                <tr>
                    <td>Category </td>
                    <td>
                        <select name="category">

                            <?php
                                //query to get active category
                                $sql = "SELECT * FROM tbl_category WHERE active='Yes'";
                                //execute the query
                                $res = mysqli_query($conn, $sql);
                                $count = mysqli_num_rows($res);
                                // $count = 0; //check else condition that is category not found

                                //check whether category available or not
                                if($count > 0)
                                {
                                    //category available
                                    while($row = mysqli_fetch_assoc($res))
                                    {
                                        $category_title = $row['title'];
                                        $category_id = $row['id'];//from tbl_category
                                        
                                        ?>

                                            <option <?php if($current_category == $category_id){echo "selected";} ?> value="<?php echo $category_id; ?>"><?php echo $category_title; ?></option>

                                        <?php
                                    }
                                    
                                }
                                else
                                {
                                    //category not Available
                                    echo "<option value='0'>Category Not Found.</option>";
                                }
                            ?>

                            
                        </select>
                    </td>
                </tr>

                <tr>
                    <td>Featured </td>
                    <td>
                        <input <?php if($featured == 'Yes'){echo "checked";} ?> type="radio" name="featured" value="Yes"> Yes
                        <input <?php if($featured == 'No'){echo "checked";} ?> type="radio" name="featured" value="No"> No
                    </td>
                </tr>

                <tr>
                    <td>Active </td>
                    <td>
                        <input <?php if($active == 'Yes'){echo "checked";} ?> type="radio" name="active" value="Yes"> Yes
                        <input <?php if($active == 'No'){echo "checked";} ?> type="radio" name="active" value="No"> No
                    </td>
                </tr>

                <tr>
                    <td>
                        <input type="hidden" name="id" value="<?php echo $id; ?>">
                        <input type="hidden" name="current_image" value="<?php echo $current_image; ?>">
                        <input type="submit" name="submit" value="Update Food" class="btn-secondary">
                    </td>
                </tr>

            </table>

        </form>

        <?php
            //check whether the button is clicked or not
    
            
            if(isset($_POST['submit']))
            {
                // echo "clicked";
                //1.get all the details from form
                $id = $_POST['id'];
                $title = $_POST['title'];
                $description = $_POST['description'];
                $price = $_POST['price'];
                $current_image = $_POST['current_image'];
                $category = $_POST['category'];
                $featured = $_POST['featured'];
                $active = $_POST['active'];

                //2. upload the image if selected

                //check whether upload image button is clicked or not
                if(isset($_FILES['image']['name']))
                {
                    //upload button clicked
                    $image_name = $_FILES['image']['name'];//new image name
                    
                    //check whether the file is available or not
                    if($image_name != "")
                    {
                        //?rename the image name
                        
                        //! here i was facing big problem finally it is solved thank god :)
                        $separed = explode('.', $image_name);
                        $ext = end($separed);
                        

                        $image_name = "Food-Name-".rand(0000,9999).'.'.$ext;

                        //?upload the image
                        $src_path = $_FILES['image']['tmp_name'];//source path
                        $dest_path = "../images/food/".$image_name;//destination path
                        $upload = move_uploaded_file($src_path, $dest_path);

                        //?check whether the image is uploaded or not
                        if($upload == false)
                        {
                            //failed to upload 
                            $_SESSION['upload'] = "<div class='error'>Failed to upload image.</div>";
                            //redirect to manage food
                            header('location:'.SITEURL.'admin/manage-food.php');
                            //stop the process
                            die();
                        }
                   
                        //todo: 3. Remove the image if new imgae is uploaded and current image exist
                        if($current_image != "")
                        {
                            //current image available
                            ///remove the image
                            $remove_path = "../images/food/".$current_image;

                            $remove = unlink($remove_path);

                            //check whether the image is removed or not
                            if($remove == false)
                            {
                                $_SESSION['remove-failed'] = "<div class='error'>Failed to remove current image.</div>";
                                //redirect to manage food
                                header('location:'.SITEURL.'admin/manage-food.php');
                                // stop process
                                die();
                            }
                        }
                    }
                    else
                    {
                        $image_name = $current_image; //default image when image is not selected.
                    }
                }
                else
                {
                    $image_name = $current_image; //default Image when button is not clicked.
                }

                //4 update the food in database
                $sql3 = "UPDATE tbl_food SET
                    title = '$title',
                    description = '$description',
                    price = $price,
                    image_name = '$image_name',
                    category_id = '$category',
                    featured = '$featured',
                    active = '$active'
                    WHERE id = $id

                ";
                //execute the query
                $res3 = mysqli_query($conn, $sql3);

                //check whether the query is executed or not
                if($res3 == true)
                {
                    //executed the query
                    $_SESSION['update'] = "<div class='success'>Food updated successfully.</div>";
                    //redirect to manage food
                    header('location:'.SITEURL.'admin/manage-food.php');
                  
                }
                else
                {
                    //failed to update
                    $_SESSION['update'] = "<div class='error'>Failed to update food.</div>";
                    //redirect to manage food
                    header('location:'.SITEURL.'admin/manage-food.php');
                    
                }
            }
    
        ?>

    </div>
</div>

<?php include('partials/footer.php'); ?>