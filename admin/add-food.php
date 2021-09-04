<?php include('partials/menue.php');?>

<div class="main-content">
    <div class="wrapper">
        <h1>Add Food</h1>

        <br><br>

        <?php       
            if(isset($_SESSION['upload']))
            {
                echo $_SESSION['upload'];
                unset($_SESSION['upload']);
            }       
        ?>

        <form action="" method="POST" enctype="multipart/form-data">
    
            <table class="tbl-30">

                <tr>
                    <td>Title </td>
                    <td>
                        <input type="text" name="title" placeholder="Title of the Food">
                    </td>
                </tr>

                <tr>
                    <td>Description </td>
                    <td>
                        <textarea name="description" cols="30" rows="5" placeholder="description of the Food"></textarea>
                    </td>
                </tr>
                
                <tr>
                    <td>Price </td>
                    <td>
                        <input type="number" name="price">
                    </td>
                </tr>

                <tr>
                    <td>Select_Image</td>
                    <td>
                        <input type="file" name="image">
                    </td>
                </tr>

                <tr>
                    <td>Category </td>
                    <td>
                        <select name="category">
<!-- 
                            //create php code to display categories from database
                            //1. create sql to get all active categories from database -->
                            <?php
                                $sql = "SELECT * FROM tbl_category WHERE active='Yes'";
                                //Execute the query
                                $res = mysqli_query($conn,$sql);
                                //coutn number of rows
                                $count = mysqli_num_rows($res);
                                if($count > 0)
                                {
                                    //we have category
                                    while($row = mysqli_fetch_assoc($res))
                                    {
                                        //get the details of the categories
                                        $id = $row['id'];
                                        $title = $row['title'];

                                        // <!-- it will show title name in categories drop down -->
                                        ?>
                                            <option value="<?php echo $id;?>"><?php echo $title;?></option>                                       
                                        <?php
                                    }
                                }
                                else
                                {
                                    // <!-- we do not have category -->
                                    ?>
                                        <option value="0">No Category Found</option>
                                    <?php
                                }
                            ?>
                        </select>
                    </td>
                </tr>

                <tr>
                    <td>Featured</td>
                    <td>
                        <input type="radio" name="featured" value="Yes">Yes
                        <input type="radio" name="featured" value="No">No
                    </td>
                </tr>

                <tr>
                    <td>Active</td>
                    <td>
                        <input type="radio" name="active" value="Yes">Yes
                        <input type="radio" name="active" value="No">No
                    </td>
                </tr>

                <tr>
                    <td colspan="2">
                        <input type="submit" name="submit" value="Add Food" class="btn-secondary">
                    </td>
                </tr>

            </table>

        </form>

        <!-- //check whether the button is clicked or not -->
        <?php        
        if(isset($_POST['submit']))
        {
            //add the food in databse
            //1. get the data from form
            $title = $_POST['title'];
            $description = $_POST['description'];
            $price = $_POST['price'];
            $category = $_POST['category'];

            //check whether radio button for featured checked or not
            if(isset($_POST['featured']))
            {
                $featured = $_POST['featured'];
            }
            else
            {
                $featured = "No";
            }

            //check whether radio button for active checked or not
            if(isset($_POST['active']))
            {
                $active= $_POST['active'];
            }
            else
            {
                $active = "No";//set default value
            }

            //2. Upload the image if selected
            //check whether the select image button is clicked or not and upload the image if only image is selected
            if(isset($_FILES['image']['name']))
            {
                //get the details of the selected image
                $image_name = $_FILES['image']['name'];

                //check whether the image is selected or not
                if($image_name != "")
                {
                    //image is selected
                    //todo: A. rename the image
                    //get the extention of selected image
                    //! here i was facing big problem finally it is solved thank god :)
                    $separed = explode('.',$image_name);
                    $ext = end($separed);

                    //create new name for image
                    $image_name = "Food-Name-".rand(0000,9999).'.'.$ext; //new image name may be like "Food-name-657.jpg"

                    //todo: B. upload the image
                    //Get the source path and destination path

                    //source path is the current location of the image
                    $src = $_FILES['image']['tmp_name'];
                    
                    //destination path
                    $des = "../images/food/".$image_name;

                    //finally upload the food image
                    $upload = move_uploaded_file($src,$des);

                    //check whether image uploaded or not
                    if($upload == false)
                    {
                        //failed to uploade image and redirect with error message
                        $_SESSION['upload'] = "<div class='error'>Failed to upload Image</div>";
                        header('location:'.SITEURL.'admin/add-food.php');
                        //stop the process
                        die();
                    }
                }
                else
                {
                    $image_name = "";
                }
            }
            else
            {
                $image_name = ""; //setting default value as blank
            }

            //todo: 3. Insert into databse
            //create sql query to save databse
            //price numarical value thats why not need single code
            $sql2 = "INSERT INTO tbl_food SET
                title = '$title',
                description = '$description',
                price = $price,
                image_name = '$image_name',
                category_id = '$category',
                featured = '$featured',
                active = '$active'
            ";

            //execute the query
            $res2 = mysqli_query($conn,$sql2);

            //todo: 4. redirect to manage-food page with message
            //check whether the data is inserted or not
            if($res2)
            {
                //data inserted successfully
                $_SESSION['add'] = "<div class='success'>Food Added Successfully.</div>";
                // redirect to page
                header('location:'.SITEURL.'admin/manage-food.php');
            }
            else
            {
                //failed to insert data
                $_SESSION['add'] = "<div class='Failed to Add Food.</div>";
                // redirect to page
                header ('location:'.SITEURL.'admin/manage-food.php');
                ob_enf_fluch();
            }
        }        
        ?>
    </div>
</div>
<?php include('partials/footer.php');?>