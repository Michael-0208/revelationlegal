<?php

namespace App\Gateways;

use App\Models\Setting;
use Illuminate\Support\Facades\File;

class SettingsData {
    /**
     * 
     * Returns the data of settings
     * 
     * @param int $survey_id    survey id
     * @return \App\Models\Setting 
     */
    public function SettingsData($survey_id) {
        $setting = Setting::where('survey_id', $survey_id)->first();

        return $setting;

    }
    /**
     * 
     * Returns the row updated
     * 
     * @param int $survey_id    survey id
     * @param \Illuminate\Http\Request $req    request updated
     * @return \App\Models\Setting 
     */
    public function updateSettingsData ($survey_id, $req) {
        
        $data = $req->all();
        unset($data['_token']);
        
        $data['show_splash_page'] = isset($data['show_splash_page']) && $data['show_splash_page'] == 'on' ? 1 : 0;
        $data['show_progress_bar'] = isset($data['show_progress_bar']) && $data['show_progress_bar'] == 'on' ? 1 : 0;
        $data['show_location_dist'] = isset($data['show_location_dist']) && $data['show_location_dist'] == 'on' ? 1 : 0;
        $data['show_summary'] = isset($data['show_summary']) && $data['show_summary'] == 'on' ? 1 : 0;
        
        if ($req->file()) {
            foreach ($req->file() as $logo_type => $file) {
                $fileName = time() . '_' . $logo_type . '_' . $file->getClientOriginalName();
                $filePath = $file->storeAs('logo', $fileName, 'public');
                $file->move(public_path('logo'),$fileName);
                $data[$logo_type] = '/public/' . $filePath;
            }
        }

        $row = Setting::where('survey_id', $survey_id)->update($data);
        
        return $row;
    }

    public function addSettingsData ($survey_id, $request) {
        $data = $request->all();
        unset($data['_token']);
        
        $data['show_splash_page'] = 0;
        $data['show_progress_bar'] = 0;
        $data['show_location_dist'] = 0;
        $data['show_summary'] = 0;
        //dd($data);
        if ($request->file()) {
            foreach ($request->file() as $logo_type => $file) {
                $fileName = time() . '_' . $logo_type . '_' . $file->getClientOriginalName();
                $filePath = $file->storeAs('logo', $fileName, 'public');
                $file->move(public_path('logo'),$fileName);
                
                $data[$logo_type] = '/public/' . $filePath;
            }
        }

        $row = Setting::create($data);
        return $row;
    }
    /**
     * 
     * Check if the file is existing in the provided path
     * 
     * @param string $url    storage path or url
     * @return boolean
     */
    public static function checkFileExistWithPath ($url = '') {
        if (File::exists(public_path($url))) {
            return true;
        }

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_NOBODY, true);
        curl_exec($ch);
        $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        if ($code == 200) {
            $status = true;
        } else {
            $status = false;
        }
        curl_close($ch);
        return $status;
    }
}