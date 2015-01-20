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
     
        echo ('<div class="contentLoad" style="display: block; position: absolute; z-index: 99999999999; backgroud: red"><div id="cargandoLoad"><img src="images/ajax-loader.gif" /></div></div>') . "\r\n"; 
         
        echo ('<script language="javascript"> 
                
                function hide(){ 
                    document.getElementById("loading").style.display = "none"; 
                } 
                window.onload = hide; 
               
                </script>') . "\r\n"; 
         
        ob_flush(); 
         
        flush(); 
     
    } 

} 
?>