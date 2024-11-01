<?php
/*
Plugin Name: Simple Yt Video Feeds
Plugin URI: https://wordpress.org/support/profile/amybeagh
Description: Simple Yt Video Feeds - An awesome youtube hosted video slider.
Version: 1.0
Author :Amy Beagh
Author URI: https://wordpress.org/support/profile/amybeagh
*/


class syvf_ytvideo_Feeds{
    
    public $options;
    
    public function __construct() {
        
        $this->options = get_option('syvf_plugin_options');
        $this->syvf_register_settings_and_fields();
    }
    
    public static function syvf_add_youtube_tools_options_page(){
        add_options_page('Simple Yt Video Feeds', 'Simple Yt Video Feeds', 'administrator', __FILE__, array('syvf_ytvideo_Feeds','syvf_tools_options'));
    }
    
    public static function syvf_tools_options(){
?>

<div class="wrap">
  <div class="syvf_main_display">
    <h2 class="syvf-heading">Simple Yt Video Feeds Setting</h2>
    <form method="post" action="options.php" enctype="multipart/form-data" class="syvf_frm">
      <?php settings_fields('syvf_plugin_options'); ?>
      <?php do_settings_sections(__FILE__); ?>
      <p class="syvf_submit">
        <input name="submit" type="submit" class="syvf_button_success" value="Save Changes"/>
      </p>
    </form>
  </div>
</div>
<?php
    }
    public function syvf_register_settings_and_fields(){
        register_setting('syvf_plugin_options', 'syvf_plugin_options',array($this,'syvf_validate_settings'));
        add_settings_section('syvf_main_section', '', array($this,'syvf_main_section_cb'), __FILE__);
        //Start Creating Fields and Options
      
        //pageURL
        add_settings_field('syvf_url_id', 'Youtube Video ID', array($this,'syvf_pageURL_settings'), __FILE__,'syvf_main_section');
        //marginTop
        add_settings_field('syvf_top_position', 'Margin Top', array($this,'syvf_marginTop_settings'), __FILE__,'syvf_main_section');
        //width
        add_settings_field('syvf_w', 'Width', array($this,'syvf_width_settings'), __FILE__,'syvf_main_section');
        //height
        add_settings_field('syvf_h', 'Height', array($this,'syvf_height_settings'), __FILE__,'syvf_main_section');
		//alignment option
        add_settings_field('syvf_position', 'Position', array($this,'syvf_position_settings'),__FILE__,'syvf_main_section');
		//show hide option
        add_settings_field('syvf_status', 'Display On Frontend', array($this,'syvf_status_settings'),__FILE__,'syvf_main_section');


    }
    public function syvf_validate_settings($plugin_options){
        return($plugin_options);
    }
    public function syvf_main_section_cb(){
        //optional
    }

     
    
    
    //pageURL_settings
    public function syvf_pageURL_settings() {
        if(empty($this->options['syvf_url_id'])) $this->options['syvf_url_id'] = "";
        echo "<input name='syvf_plugin_options[syvf_url_id]' type='text' value='{$this->options['syvf_url_id']}' />";
    }
    //marginTop_settings
    public function syvf_marginTop_settings() {
        if(empty($this->options['syvf_top_position'])) $this->options['syvf_top_position'] = "100";
        echo "<input name='syvf_plugin_options[syvf_top_position]' type='text' value='{$this->options['syvf_top_position']}' />";
    }
    //alignment_settings
	public function syvf_position_settings(){
        if(empty($this->options['syvf_position'])) $this->options['syvf_position'] = "left";
        $items = array('left','right');
        
	   foreach($items as $item){
            $selected = ($this->options['syvf_position'] === $item) ? 'checked = "checked"' : '';
            
			echo "<input type='radio' name='syvf_plugin_options[syvf_position]' value='$item' $selected> ".ucfirst($item)."&nbsp;";
        }
    }
	
	//alignment_settings
	public function syvf_status_settings(){
        if(empty($this->options['syvf_status'])) $this->options['syvf_status'] = "on";
        $items = array('on','off');
        
	   foreach($items as $item_yt){
            $selected_yt = ($this->options['syvf_status'] === $item_yt) ? 'checked = "checked"' : '';
            
			echo "<input type='radio' name='syvf_plugin_options[syvf_status]' value='$item_yt' $selected_yt> ".ucfirst($item_yt)."&nbsp;";
        }
    }
    //width_settings
    public function syvf_width_settings() {
        if(empty($this->options['syvf_w'])) $this->options['syvf_w'] = "350";
        echo "<input name='syvf_plugin_options[syvf_w]' type='text' value='{$this->options['syvf_w']}' />";
    }
    //height_settings
    public function syvf_height_settings() {
        if(empty($this->options['syvf_h'])) $this->options['syvf_h'] = "400";
        echo "<input name='syvf_plugin_options[syvf_h]' type='text' value='{$this->options['syvf_h']}' />";
    }

}
add_action('admin_menu', 'syvf_trigger_options_function');

function syvf_trigger_options_function(){
    syvf_ytvideo_Feeds::syvf_add_youtube_tools_options_page();
}

add_action('admin_init','syvf_trigger_create_object');
function syvf_trigger_create_object(){
    new syvf_ytvideo_Feeds();
}
add_action('wp_footer','syvf_add_content_in_footer');
function syvf_add_content_in_footer(){
    
    $option_yt = get_option('syvf_plugin_options');
    extract($option_yt);
    $total_height=$syvf_h-95;
	$mheight = $syvf_h-85;
    $max_height=$total_height+10;
	$syvf_youtube = '';
	if($syvf_url_id == ''){
	$syvf_youtube.='<div class="error_syvf">Please Fill Out The Simple Yt Video Feeds Configuration First</div>';	
	} else {
	$syvf_youtube .= '
	<iframe width="'.trim($syvf_w).'" height="'.trim($syvf_h).'"
	 src="http://www.youtube.com/embed/'.trim($syvf_url_id).'" frameborder="0" allowfullscreen="yes" marginheight="0px"
	 "></iframe>';
	}
	$syvf_img_icon = plugins_url('assets/syvf_icon.png', __FILE__);
	
	?>
<?php
	 if($syvf_status == 'on') {
	 if($syvf_position=='left'){?>
<div id="syvf_outer">
<div id="syvf_ybox1" class="syvf_area_left"> <a class="syvf_open" id="syvf_122" href="javascript:;"><img src="<?php echo $syvf_img_icon;?>" ></a>
  <div id="syvf_inner_left" class="syvf_inner_area_left"> <?php echo $syvf_youtube; ?> </div>
</div>
<style>
 
  div.syvf_area_left{

	left: -<?php echo trim($syvf_w+10);?>px; 

	top: <?php echo $syvf_top_position;?>px; 

	z-index: 10000; 

	height:<?php echo trim($syvf_h+10);?>px;

	}

div.syvf_area_left.syvf_shd{

	left:0;

	}	

div.syvf_inner_area_left{

	text-align: left;

	width:<?php echo trim($syvf_w);?>px;

	height:<?php echo trim($syvf_h);?>px;

	}

</style>
<?php } else { ?>
<div id="syvf_outer">
  <div class="syvf_area_right" id="right_syvf_box1"> <a class="syvf_open" id="syvf_122" href="javascript:;"><img style="top: 0px;left:-46px;" src="<?php echo $syvf_img_icon;?>" ></a>
    <div id="syvf_box2" class="syvf_inner_area_right" > <?php echo $syvf_youtube; ?> </div>
  </div>
</div>
<style type="text/css">

div.syvf_area_right{

	right: -<?php echo trim($syvf_w+10);?>px;

	top: <?php echo $syvf_top_position;?>px;

	z-index: 10000; 

	height:<?php echo trim($syvf_h+10);?>px;

	-webkit-transition: all .5s ease-in-out;

	-moz-transition: all .5s ease-in-out;

	-o-transition: all .5s ease-in-out;

	transition: all .5s ease-in-out;

	}

div.syvf_area_right.syvf_shd{

	right:0;

	}	

div.syvf_inner_area_right{
	text-align: left;
	width:<?php echo trim($syvf_w);?>px;
	height:<?php echo trim($syvf_h);?>px;
	}
</style>
<?php } } ?>
<script type="text/javascript">
    jQuery(document).ready(function() {
    jQuery('#syvf_122').click(function(){
        jQuery(this).parent().toggleClass('syvf_shd');
    });});
    </script>
<?php
    }
    add_action( 'wp_enqueue_scripts', 'register_syvf_slider_styles' );
    add_action( 'admin_enqueue_scripts', 'register_syvf_slider_styles' );
    
    function register_syvf_slider_styles() {
        wp_register_style( 'register_syvf_slider_styles', plugins_url( 'assets/syvf_style.css' , __FILE__ ) );
        wp_enqueue_style( 'register_syvf_slider_styles' );
    }
	/* add Shortcode code*/
	function syvf_youtube_sh_fn()
	{
		$option_yt00 = get_option('syvf_plugin_options');
		extract($option_yt00);
		$total_height=$syvf_h-95;
		$mheight = $syvf_h-85;
		$max_height=$total_height+10;
		$syvf_shotcod_out = '';
		?>
		<style>
		.syvf_shortcode_dis_play{
			width:<?php echo trim($syvf_w);?>px;
			height:<?php echo trim($syvf_h+10);?>px;
		}
		</style>
		<?php 
		if($syvf_url_id == ''){
		$syvf_shotcod_out.='<div class="error_syvf">Please Fill Out The Simple Yt Video Feeds Configuration First</div>';	
		} else {
		$syvf_shotcod_out .= '<div class="syvf_shortcode_dis_play">
		<iframe width="'.trim($syvf_w).'" height="'.trim($syvf_h).'"
		 src="http://www.youtube.com/embed/'.trim($syvf_url_id).'" frameborder="0" allowfullscreen="yes" 
		 "></iframe></div>';
		}
		return $syvf_shotcod_out;	
		
	}
	add_shortcode('syvf_youtube_feeds_sh','syvf_youtube_sh_fn');

