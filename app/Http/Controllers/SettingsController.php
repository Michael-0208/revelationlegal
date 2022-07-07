<?php

namespace App\Http\Controllers;

use App\Gateways\SettingsData;
use App\Models\Location;
use App\Models\Respondent;
use App\Models\Survey;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    /**
     * @var \Illuminate\Http\Request
     */
    protected $request;
    /**
     * @var \App\Gateways\SettingsData
     */
    protected $settingsData;

    public function __construct(Request $request, SettingsData $settingsData)
    {
        $this->request = $request;
        $this->settingsData = $settingsData;

        
    }
    /**
     * 
     * Settings
     * 
     * @param int $survey_id    survey id
     * @param string $status
     * @return \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory
     */
    public function settings_settings($survey_id, $status = '') {
        CommonController::validateUser($survey_id, 'surveySettings');
        //dd($survey_id);
        $survey_data = Survey::where('survey_id', $survey_id)->get()->toArray();
        $survey =(object)[
            'survey_id' => $survey_id,
            'survey_name' => $survey_data[0]['survey_name']];
        $data['survey'] = $survey;
        //dd($this->settingsData->SettingsData(42));
        $data['settings'] = $this->settingsData->SettingsData($survey_id);
        
        if ($status == 'updated') {
            $data['updated'] = 1;
        }
        
        return view('settings.settings', ['data' => $data, 'survey' => Survey::where('survey_id', $survey_id)->first()]);
    }


    public function add_settings(Request $request){
       
        $survey_id = $this->request->input('survey_id');
        CommonController::validateUser($survey_id, 'surveySettings');
        $survey_data = Survey::where('survey_id', $survey_id)->get()->toArray();
        $data = $this->settingsData->addSettingsData($survey_id, $this->request);
        $survey =(object)[
            'survey_id' => $survey_id,
            'survey_name' => $survey_data[0]['survey_name']];
        $data['survey'] = $survey;
        //dd($status);

        if($data){
            return back();
        }else{
            echo "Something went wrong!";
        }
        //dd($request);
    }


    /**
     * 
     * Update the row of settings
     * 
     * @param \Illuminate\Http\Request
     * @return \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory
     */
    public function update_settings () {
        //dd()
        //dd($this->request);
        $survey_id = $this->request->input('survey_id');
        //dd($survey_id);
        CommonController::validateUser($survey_id, 'surveySettings');
        
        $updatedRow = $this->settingsData->updateSettingsData($survey_id, $this->request);
        
        if ($updatedRow) {
            
            return $this->settings_settings($survey_id, 'updated');
        }else{
            echo "not updated";
        }
    }
    /**
     * 
     * Location Settings Index
     * 
     * @param int $survey_id    survey id
     * @return \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory
     */
    public function settings_locations ($survey_id) {
        CommonController::validateUser($survey_id, 'surveySettings');

        $survey_data = Survey::where('survey_id', $survey_id)->get()->toArray();
        $survey =(object)[
            'survey_id' => $survey_id,
            'survey_name' => $survey_data[0]['survey_name']];
        $data['survey'] = $survey;

        $data['locations'] = Location::where('survey_id', $survey_id)->get();
        
        return view('settings.locations', ['data' => $data, 'survey' => Survey::where('survey_id', $survey_id)->firstOrFail()]);
    }
    /**
     * 
     * Insert a new row of settings
     * 
     * @param \Illuminate\Http\Request
     * @return \Illuminate\Http\Response
     */
    public function add_location () {
        $params = $this->request->all();
        $data = array ();


        /* dd($params);

        if($params['location_OTHER']) */

        $duplicated = Location::where('survey_id', $params['survey_id'])
            ->where('location', $params['location'])
            ->get();

        if (count($duplicated) > 0) {
            $data['status'] = 400;
        } else {
            $locationModel = new Location();
    
            $locationModel->survey_id          = $params['survey_id'];
            $locationModel->location           = $params['location'];
            $locationModel->location_other     = $params['location_OTHER'];
            $locationModel->location_regional  = $params['location_Regional'];
            $locationModel->location_adjacent  = $params['location_Adjacent'];
            $locationModel->location_current   = $params['location_Current'];
    
            $locationModel->save();
    
            if ($locationModel->location_id) {
                $data['status'] = 200;
                $data['id'] = $locationModel->location_id;
            }
        }

        return $data;
    }
    /**
     * 
     * Update the row of settings
     * 
     * @param \Illuminate\Http\Request
     * @return \Illuminate\Http\Response
     */
    public function update_location () {
        $params = $this->request->all();

        $duplicate = Location::where('survey_id', $params['survey_id'])
            ->where('location', $params['location'])
            ->where('location_id', '<>', $params['location_id'])
            ->get();
        
        if (count($duplicate) > 0) {
            return 400;
        } else {
            $row = Location::where('location_id', $params['location_id'])
                ->update([
                    'location' => $params['location'],
                    'location_OTHER' => $params['location_OTHER'],
                    'location_Regional' => $params['location_Regional'],
                    'location_Adjacent' => $params['location_Adjacent'],
                    'location_Current' => $params['location_Current'],
                ]);
    
            if ($row) {
                return 200;
            }
        }
    }
    /**
     * 
     * Delete the row of settings
     * 
     * @param \Illuminate\Http\Request
     * @return \Illuminate\Http\Response
     */
    public function delete_location () {
        $params = $this->request->all();

        $error = 0;
        
        $participants = Respondent::where('survey_id', $params['survey_id'])
            ->where('cust_6', $params['location_desc'])
            ->get();
        
        if (count($participants) > 0)
            $error = 1;

        if ($error == 1) {
            return 400;
        } else {
            $deleteRow = Location::where('location_id', $params['location_id'])->delete();
            if ($deleteRow) {
                return 200;
            }
        }

    }
}
