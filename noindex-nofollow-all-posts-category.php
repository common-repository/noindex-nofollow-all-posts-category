<?php
/*
Plugin Name:Noindex Nofollow All Posts
Plugin URI: http://www.digitalchef.in/
Description: This plugin lets you Noindex Nofollow All Posts in a category. <strong>Simply go to Posts > Categories, and click on any category's EDIT link. There you can just "tick" the relevant option you need. The plugin will then add a "noindex" or/and "nofollow" line in all posts in that category.</strong>
Version: 1.0.3
Author: DigitalChef.in - Madhu Ramrakhyani
Author URI: http://www.digitalchef.in/
*/

add_filter('wpseo_robots', function($robots) {
    return '';
});
add_action( 'wp_head', 'dc_nfniapc_noRobots' );
function dc_nfniapc_noRobots() {
	$categories = get_the_category();
	$cat_id = $categories[0]->cat_ID;
	//echo($cat_id);
	//then i get the data from the database
	$cat_data = get_option("category_$cat_id");
	$options="";$Noindex="";$Nofollow="";
	//echo $cat_data['extra1'];
	if($cat_data['extra1']=="on")
	{
		$Noindex="noindex";
	}
	else
	{
		$Noindex="index";
	}
	if($cat_data['extra2']=="on")
	{
		$Nofollow="nofollow";
	}
	else
	{
		$Nofollow="follow";
	}

	$options=$Noindex.",".$Nofollow;
	
    echo( '<meta name="robots" content="'.$options.'" />' );
}
//add extra fields to category edit form hook
add_action ( 'edit_category_form_fields', 'dc_nfniapc_extra_category_fields');
//add extra fields to category edit form callback function
function dc_nfniapc_extra_category_fields( $tag ) {    //check for existing featured ID
    $t_id = $tag->term_id;
    $cat_meta = get_option( "category_$t_id");
?>

<tr class="form-field">
<th scope="row" valign="top"><label for="extra1">No Index</label></th>
<td>
<input type="checkbox" name="Cat_meta[extra1]" id="Cat_meta[extra1]" 
<?php if($cat_meta["extra1"]=="on") {echo "checked";}?>>
<br />
            <span class="description"><b>"NOINDEX" allows the subsidiary links to be explored, even though the page is not indexed</b></span>
</td>
</tr>
<tr class="form-field">
<th scope="row" valign="top"><label for="extra2">No Follow</label></th>
<td>
<input type="checkbox" name="Cat_meta[extra2]" id="Cat_meta[extra2]"  
<?php if($cat_meta["extra2"]=="on") {echo "checked";}?>>
<br />
            <span class="description"><b>"NOFOLLOW" allows the page to be indexed, but no links from the page are explored</b></span>
        </td>
</tr>

<?php
}
add_action ( 'edited_category', 'dc_nfniapc_save_extra_category_fileds');
add_action ( 'create_category', 'dc_nfniapc_save_extra_category_fileds');
   // save extra category extra fields callback function
function dc_nfniapc_save_extra_category_fileds( $term_id ) {
    /*if ( isset( $_POST['Cat_meta'] ) ) {
        $t_id = $term_id;
        $cat_meta = get_option( "category_$t_id");
        $cat_keys = array_keys($_POST['Cat_meta']);
            foreach ($cat_keys as $key){
            if (isset($_POST['Cat_meta'][$key])){
                $cat_meta[$key] = $_POST['Cat_meta'][$key];
            }
        }
        //save the option array
        update_option( "category_$t_id", $cat_meta );
    }*/
	$t_id = $term_id;
    $cat_meta = get_option( "category_$t_id");
	if(!isset($_POST['Cat_meta']['extra1'])){
		$cat_meta['extra1'] = "off";
	}
	if(isset($_POST['Cat_meta']['extra1']))
	{	
		$cat_meta['extra1'] = "on";		
	}
	if(!isset($_POST['Cat_meta']['extra2'])){
		$cat_meta['extra2'] = "off";		
	}
	if(isset($_POST['Cat_meta']['extra2']))
	{
		$cat_meta['extra2'] = "on";		
	}

	
	update_option( "category_$t_id", $cat_meta );
}

?>
<?php
// Add the field to the Add New Category page
add_action( 'category_add_form_fields', 'dc_nfniapc_pt_taxonomy_add_new_meta_field', 10, 2 );
 
function dc_nfniapc_pt_taxonomy_add_new_meta_field() {
    // this will add the custom meta field to the add new term page
    ?>


	 <div class="form-field">
		<label for="extra1">No Index</label>
		<input type="checkbox" name="Cat_meta[extra1]" id="Cat_meta[extra1]" >
		<p class="description">"NOINDEX" allows the subsidiary links to be explored, even though the page is not indexed</p>
	</div>
	 <div class="form-field">
		<label for="extra2">No Follow</label>
		<input type="checkbox" name="Cat_meta[extra2]" id="Cat_meta[extra2]" >
		<p class="description">"NOFOLLOW" allows the page to be indexed, but no links from the page are explored</p>       
	</div>
<?php
}