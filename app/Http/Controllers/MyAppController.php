<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\MyApp;
use Image;
use Illuminate\Support\Str;
use Storage;

class MyAppController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $myapps = MyApp::all();    
        return view('myapps.myapps' , compact('myapps'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('myapps.addnewapp'); 
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->all();
        $validator = validator()->make($data, [
            'appname'               => 'required|min:6',  
            'logoapp'               => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048', 
            'splash'                => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048', 
            'appidentificationkey'  => 'required|unique:my_apps',
        ]);     
        
        $data['logoapp']                =  uplode_img('logoapp' , 'logoapp_name' , 'uploads/logo/' , 'logo.png');
        $data['splashscreen']           =  uplode_img('splash' , 'splash_name' , 'uploads/splash/' , 'splash.png'); 
        $data['appidentificationkey']   = $this->generateAppidentificationkey(); 
        $myapp = MyApp::create($data);   

        return redirect('/myapp')->with('success', 'MyApp Created!');
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $myapp = MyApp::find($id); 
        return view('myapps.myapp' , compact('myapp'));

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $myapp   = MyApp::find($id); 
        return view('myapps.editapp', compact('myapp'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $myapp = MyApp::find($id);
        $data = $request->all();
        $validator = validator()->make($data, [
            'appname'               => 'required|min:6',  
            'logoapp'               => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048', 
            'splash'                => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048', 
        ]);    
            if (request()->hasFile('logoapp')) 
            {   
                if($myapp->logoapp !==  'logo.png'){
                    Storage::delete('logo/'. $myapp->logoapp);    
                }   
                $data['logoapp']  =  uplode_img('logoapp' , 'logoapp_name' , 'uploads/logo/' , 'logo.png');
            }  
 
            if (request()->hasFile('splash')) 
            {   
                if($myapp->splashscreen !==  'splash.png'){
                    Storage::delete('splash/'. $myapp->splashscreen);    
                }  
                $data['splashscreen']   =  uplode_img('splash' , 'splash_name' , 'uploads/splash/' , 'splash.png'); 
            }  
          
        $myapp->update($data);
        return redirect('/myapp')->with('success', 'MyApp Created!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $myapp     = MyApp::find($id);
        if($myapp->logoapp !==  'logo.png'){
           Storage::delete('logo/'. $myapp->logoapp);    
        }
        if($myapp->splashscreen !==  'splash.png'){
           Storage::delete('splash/'. $myapp->splashscreen);    
        }

        $myapp->delete(); 
        return back() ;
    }


//////////////// Generate App Id Notification //////////// 
    public function generateAppidentificationkey()
    {  
        $record = MyApp::all()->last();  

        if (empty($record)) {
            $nextAppidentificationkey = 11; 
        } 
        else { 
            $x = $record->appidentificationkey;
            $x = $x + 1;
            $nextAppidentificationkey = $x;
        } 
        return $nextAppidentificationkey;

    } 

}
