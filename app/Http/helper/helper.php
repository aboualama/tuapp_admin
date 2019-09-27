<?php





if(!function_exists('uplode_img')){

	function uplode_img($file , $name , $path , $defualt){
        
        if (request()->hasFile($file)) 
        {    
            $img = request($file);
            $image = time() . '.' . request($file)->getClientOriginalExtension();
            $full_path  = $path . $image; 
            Image::make($img)->resize(200,200)->save($full_path); 
        }else
        { 
            $image = $defualt;  
        }  
        return $image;
    }
    
}




