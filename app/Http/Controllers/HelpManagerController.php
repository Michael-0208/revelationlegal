<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\HelpManager;
use App\Models\Manageguide;

class HelpManagerController extends Controller
{
    
    /* 
    * This will return view with all help desk data.
    * for All site pages.
    */
    public function index(){

        $data = HelpManager::all();
        // dd($data);
        return view('helpdeskdata.helpdeskadmin')->with(['helpPages' => $data]);
    }

    public function edit_helpdesk($id){
 
        // dd($id);
        $data = HelpManager::find($id);
        
        (!empty($data->helpImages)) ? $HelpImages = unserialize($data->helpImages) : $HelpImages = [];
        // dd($HelpImages); 
        return view('helpdeskdata.helpdeskeditor')->with(['helpEdit' => $data,'helpImages' => $HelpImages]);
    }

    public function update_helpdata(Request $request, $id){
 
      


        $files = [];
        if($request->file()){
            foreach($request->file() as $key => $file)
            {
                $name = time().rand(1,100).'.'.$file->extension();
                //dd($name);
                $file->move(public_path('helpcontent'), $name);  
                $files[$key] = $name;  
            }
                        
        }

                

        $request = $request->toArray();
        unset($request['_token']);
        unset($request['help_image_1']);
        unset($request['help_image_2']);
        

        (count($files) > 0) ? $request['helpImages'] = serialize($files) : $request['helpImages'] = '';
        // dd($request);

        $isUpdated = HelpManager::where('id',$id)->update($request);

        if($isUpdated){
           
            return redirect(route('help-desk'));
        }else{
            dd('something went wrong!');
        }
        // dd($data); 
       
    }

    public function update_helpguide(Request $request){

        $files = [];
       
        if($request->file()){
            foreach($request->file() as $key => $file){
                
                $name = time().rand(1,100).'.'.$file->extension();
                //dd($name);
                $file->move(public_path('guides'), $name);  
                $files[$key] = $name; 

                $isUpdated = Manageguide::where('id',$key)->update(['file_name'=>$name]);
                if($isUpdated){
                    return redirect(route('manage-guide'));
                    // dd('Done!');
                }else{
                    dd('something went wrong!');
                }
            }
                        
        }
    }
}
