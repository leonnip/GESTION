<?php 
#~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ 
# 
# initiate the class: $divLoader = new loadingDiv; 
# call the function after the <body> tag: $divLoader->loader(); 
# 
#~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ 

class loadingDiv { 

    var $width = 800; 
    var $height = 400; 
    var $loadingGif = '../images/ajax-loader.gif'; 
     
    // create a form with the following parameters: 
    // $formAction = html action of the form 
    // $linkText = in browser displayed html link for submitting form 
    // $getString = string with values as: name{=}John{&}surname{=}Joe{&}age{=}20 
    // $yesOrNo = text asked for confirmation (leave it empty for no question) 
    function loader(){ 
     
        echo ('<div style="top:20px; z-index: 9999; position: absolute; width: '.$this->width.'px; left: 50%; top: 50%; margin-left: -'.($this->width/2).'px; margin-top: -'.($this->height/2).'px; height: '.$this->height.'px; background-color: #FFF; opacity: 0.7; vertical-align: center; text-align:center; padding: 15px 0 15px 0; filter:Alpha(opacity=75); -moz-opacity: 0.75; opacity: 0.75; background-image: url(\''.$this->loadingGif.'\'); background-repeat: no-repeat; background-position: center;" id="loading"></div>') . "\r\n"; 
         
        echo ('<script language="javascript"> 
                <!--  
                function hide(){ 
                    document.getElementById("loading").style.display = "none"; 
                } 
                window.onload = hide; 
                --> 
                </script>') . "\r\n"; 
         
        ob_flush(); 
         
        flush(); 
     
    } 

} 
?>