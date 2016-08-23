<h4><?php echo $parseClassname." List"; ?></h4>
<div id="error-validation" style="display: none"></div>
<section class="admin-content">
    <div style="overflow-x: scroll; " class="form">
    <?php if(isset($datalist)): ?>
    <?php if($parseClassname != "_User"){?>
        <div class="back">
            <a class="cancel" href="javascript:void(0);" onclick="removeThis('<?php echo $parseClassname ?>')">Delete</a>
        </div>
    <?php } ?>
        <div class="raffle-edit">
            <?php echo $this->Html->link("Add New", array("controller"=>"users", "action"=>$addnew_action, $subField), array("class"=>"cancel") ); ?>
        </div>
        <table width="100%">
            <thead>
                <tr>
                    <th width="1%">&nbsp;</th>
                    <th width="10%">Image</th>
                    <th>Name</th>
                <?php if(isset($datalist[0]['parent_cat_name'])){
                    echo "<th width='40%'>Parent Category Name</th>";
                }
                if($parseClassname != "_User") {
                ?>
                    <th width="15%">Action</th>
                <?php } ?>    
                </tr>
            </thead>
            <tbody>
            <?php if (!empty($datalist)): foreach($datalist as $dl): ?>    
                <tr>
                    <td>
                        <input type="checkbox" name="chkb_remove" class="chkb_remove" value="<?php echo $dl['objectId']?>" title="Select any one to delete" />
                    </td>
                    <td>
                        <?php 
                            if(!empty($dl['img'])):
                                echo $this->Html->image($dl['img'], array("alt"=>"Image", "border"=>"0", "title"=>"Image", "style"=>"width: 65px; height: auto")); 
                            else:
                                echo "No Image";
                            endif;
                        ?>
                    </td>
                    <td><?php echo $dl['name']?></td>
                <?php if(isset($dl['parent_cat_name'])){
                    echo "<td>".$dl['parent_cat_name']."</td>";
                } 
                if($parseClassname != "_User") {
                ?>
                    <td>
                    <?php //echo $this->Html->link("Edit", array("controller"=>"users", "action"=>"editListing", $parseClassname, $dl['objectId']) ); ?>
                    <?php echo $this->Html->link("Edit", array("controller"=>"users", "action"=>$addnew_action, $subField, $dl['objectId']))?>
                    </td>
                <?php } ?>    
                </tr>
            <?php endforeach; ?>
            <?php else: echo "<td colspan='5'>No Records Found!</td>"; endif; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No Records found!</p>
    <?php endif; ?>
    </div>
</section>
<script type="text/javascript">
    function removeThis(clsName)
    {
        var objId = "";
        $('.chkb_remove').each(function(){
           if(this.checked == true)
           {
               objId = this.value;
           }
        });
        
        if(objId!=""){
            $.ajax({
               url: webURL+"users/removedata/",
               type: 'post',
               data: {'objId':objId, 'clsName': clsName},
               //dataType: 'json',
               cache: false,
               success: function(resp){
                   alert(resp);
                   window.location.reload();
               },
               error: function(){
                   alert("Unable to remove. Please try later!");
                   return false;
               }
            });
        }
        else
        {
            alert("Please select one item to delete!");
        }
        return;
    }
</script>