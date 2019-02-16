<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Report;
use App\ReportImage;
use Validator;
use Illuminate\Support\Facades\Hash;
use App\User;
use Auth;

class ReportsController extends Controller
{
    //
    public function index(){
        $reports = Report::select(\DB::raw('reports.type','count(reports.id) as reports'))->groupBy('reports.type')->get();
        return view('home',['reports'=>$reports]);
    }

    public function getReports($type = 0,Request $request){
        if(!empty(isset($request->type))){
            $type = $request->type;
        }
        $reports = Report::where("type",$type);
        if(!empty(isset($request->search))){
            $reports->whereRaw("(reports.location LIKE '%".$request->search."%' OR reports.desc LIKE '%".$request->search."%')");
        }
        return $reports->with("images")->orderBy('created_at',"desc")->paginate(10);
    }

    public function getUsers(Request $request){
        return User::orderBy('name')->paginate(10);
    }

    public function addReport(Request $request){
        $validator = Validator::make($request->all(), [
            'location'  => 'required',
            'desc'      => 'required',
            'user_id'      => 'required',
            'witnessed_at'  => 'required',
        ]);
        if ($validator->fails()) {
            return ['success' => false,'errors' => $validator->errors()];
        }

        $newReport              = new Report;
        $newReport->location    = $request->location;
        $newReport->desc        = $request->desc;
        $newReport->user_id     = $request->user_id;
        $newReport->witnessed_at = date('Y-m-d H:i:s',strtotime($request->witnessed_at));        
        $newReport->type        = 0;
        $newReport->save();
        if($request->images){
            if($request->hasFile('images')) {
                $files = $request->file('images');
                if(is_array($files)){
                    foreach($files as $fileKey => $file){
                        $extension = $file->getClientOriginalExtension();
                        $newFilename = "w-".$newReport->id."-".uniqid().".". $extension;
                        $destinationPath = public_path('/uploads/reports');
                        $file->move($destinationPath, $newFilename);

                        $reportImage = new ReportImage;
                        $reportImage->report_id = $newReport->id;
                        $reportImage->image_name = "uploads/reports/".$newFilename;
                        $reportImage->save();
                    }
                }
            }
        }
        
        // $decoded = base64_decode($request->image);
        // $filename_path = "w-".$newReport->id."-".uniqid().".jpg";
        // $output_file = $this->base64_to_jpeg($request->image,"uploads/".$filename_path);
        // $newReport->image       = $output_file;
        // $newReport->save();
        return ['success'=> true, 'report' => $newReport];
    }

    private function base64_to_jpeg($base64_string, $output_file) {
        $file = fopen($output_file, "w+");
 
        $data = explode(',', $base64_string);
        fwrite($file, base64_decode($data[1]));
        fclose($file);
 
        return $output_file;
    }

    public function actionReport($action,Request $request){
        $report = Report::find($request->id);
        if($report){
            if($action == "delete"){
                $report->type = 4;
                $report->save();
                // $this->mSendNotification();
            } else if ($action == "approve" ){
                $report->type = 2;
                $report->save();
                // $this->mSendNotification();
            } else if ($action == "decline"){
                $report->type = 3; 
                $report->save();
                // $this->mSendNotification();
            } else if ($action == "solve"){
                $report->type = 1; 
                $report->save();
                // $this->mSendNotification();
            } else {
                return ['success' => false];
            }
            $report->updated_by = Auth::user()->id;
            $this->mSendNotification();
            return ['success'=>true,'action'=>$action];
            
        } 
        return ['success' => false];
    }

    public function mSendNotification(){
        define( 'API_ACCESS_KEY', 'AAAAj0E56bU:APA91bEpHMHjUgoPcCSVXZpxkBuldgFdwVr1DkfbaG-t5RjoOglwEk3SQVzOolXtLQUSD_whVCX6PIJaF9Ra7xS6EeMXISY07ALEfKIeEeNm58lm2BT5A8un5CbZh6fWUyY-SI29KS8u');
        
        $to = 'cQm_SgTMF7s:APA91bG5hSmbjY1hXD3Ua1MYizn3CURwPNHzVeA9tvcTeKS_yL_xYPh5G66tQVStvqKDM6jWuaK0ehTlJ_mOKR7EsVOulKsEMHNOeHFyopeXA1sp3OuR6YjT-t5yXYA9WLQxKRHUz9tA';
        // $data = array(
        //     "report_id"=> $report->id,
        //     "user_id" => $report->user_id
        // );

        $msg = array(
            // 'body' => $report->desc,
            'body' => 'asdlkaj',
            'title' => 'TrashGuard'
        );

		#prep the bundle
		$fields = array(
	    				'to'		=> $to,
						'notification'	=> $msg
						// 'data' => $data
					);
			
		$headers = array(
						'Authorization: key=' . API_ACCESS_KEY,
						'Content-Type: application/json'
					);
		#Send Reponse To FireBase Server	
		$ch = curl_init();
		curl_setopt( $ch,CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send' );
		curl_setopt( $ch,CURLOPT_POST, true );
		curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
		curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
		curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
		curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fields ) );
		$result = curl_exec($ch );
		echo $result;
		curl_close( $ch );

		// return json_encode ($result,true,JSON_PRETTY_PRINT);
    }




    // MOBILE API

    public function mRegister(Request $request){
        $validator = Validator::make($request->all(), [
            'user_email'  => 'required',
            'token'      => 'required',
            'password'      => 'required',
            'phone_num'      => 'required',
            'fname'      => 'required',
            'lname'      => 'required',
        ]);
        if ($validator->fails()) {
            return ['error' => true,'message'=>'Required fields are missing','stack_trace' => $validator->errors()];
        }
        $newUser = new User;
        $newUser->first_name = $request->fname;
        $newUser->last_name  = $request->lname;
        $newUser->token      = $request->token;
        $newUser->phone_number = $request->phone_num;
        $newUser->name       = $request->fname . " " . $request->lname;
        $newUser->email      = $request->user_email;
        $newUser->password   = Hash::make($request->password);
        $newUser->type       = 0;
        $newUser->save();

        return [
            'error' => false,
            'message' => 'User registered sucessfully',
            'id'    => $newUser->id,
            'email' => $newUser->email,
            'fname' => $newUser->first_name,
            'lname' => $newUser->last_name,
        ];
    }

    public function mLogout(Request $request){
        $validator = Validator::make($request->all(),[
            'user_id' => 'required',
        ]);

        if($validator->fails()){
            return ['error' => true,'message'=>'Required fields are missing','stack_trace' => $validator->errors()];
        }

        $user = User::where("id",$request->user_id)->first();
        $user->token = null;
        $user->save();

        return [
            'error' => false,
            'message' => 'Sucessfully logged out',
            'id'    => $user->id,
            'email' => $user->email,
            'fname' => $user->first_name,
            'lname' => $user->last_name,
            'token' => $user->token,    
        ];
        
    }

    public function mLogin(Request $request){
        $validator = Validator::make($request->all(), [
            'user_email'  => 'required',
            'token'      => 'required',
            'password'      => 'required',
        ]);
        if ($validator->fails()) {
            return ['error' => true,'message'=>'Required fields are missing','stack_trace' => $validator->errors()];
        }   
        $user = User::where("email",$request->user_email)->first();
        if($user){
            if(Hash::check($request->password, $user->password)){
                $user->token = $request->token;
                $user->save();
                return [
                    'error' => false,
                    'message' => 'Sucessfully logged in',
                    'id'    => $user->id,
                    'email' => $user->email,
                    'fname' => $user->first_name,
                    'lname' => $user->last_name,
                    'token' => $user->token,    
                ];
            }
        }
        return ['error'=>true,'message'=>'Invalid email or password'];
    }

    public function mReportSend(Request $request){
        $validator = Validator::make($request->all(), [
            'image'    => 'required',
            'user_id'  => 'required',
            'report_date'      => 'required',
            'report_time'      => 'required',
            'report_details'   => 'required',
            'report_location'  => 'required',
            // 'report_date'      => 'required',
            // 'report_time'      => 'required',
        ]);
        if ($validator->fails()) {
            return ['error' => true,'message'=>'Required fields are missing','stack_trace' => $validator->errors()];
        }   
        
        $newReport = new Report;
        $newReport->user_id = $request->user_id;
        $newReport->desc = $request->report_details;
        $newReport->location = $request->report_location;
        $newReport->witnessed_at = date('Y-m-d H:i:s',strtotime($request->report_date." ".$request->report_time));
        $newReport->type = 0;
        $newReport->save();
        
        foreach($request->image as $key => $value){
            $reportImage = new ReportImage;
            $reportImage->report_id = $newReport->id;
            $reportImage->image_name = $this->base64_to_jpeg($value,"uploads/reports/"."w-".$newReport->id."-".uniqid().".jpg");
            $reportImage->save();
        }
        
        return [
            'error' => false,
            'message' => 'Succesfully sent a report',
            'report' => $newReport,
        ];

    }

    public function mUploadProfilePicture(Request $request){
        $validator = Validator::make($request->all(), [
            'image'    => 'required',
            'user_id'  => 'required',
        ]);

        if ($validator->fails()) {
            return ['error' => true,'message'=>'Required fields are missing','stack_trace' => $validator->errors()];
        }
        
        $user = User::find($request->user_id);

        if($user){
            $user->profile_photo = $this->base64_to_jpeg($request->image,"uploads/profile/"."w-".$request->user_id."-".uniqid().".jpg");
            $user->save();
            return [
                'error' => false,
                'message' => 'Sucessfully uploaded profile picture',
                'profile_photo' => $user,
            ];
        }
        return ['error'=>true,'message'=>'Invalid email or password'];
    }

    public function mGetReports(Request $request){
        $validator = Validator::make($request->all(), [
            'user_id'  => 'required',
        ]);

        if ($validator->fails()) {
            return ['error' => true,'message'=>'Required fields are missing','stack_trace' => $validator->errors()];
        }

        $reports = Report::where("user_id",$request->user_id)->with("images")->get();
                   
        foreach($reports as $reportKey => $report) {
            foreach($report->images as $imageKey => $image){
                if($image){
                    $reports[$reportKey]->images[$imageKey]->image_name = url($image->image_name);
                }
            }
        }

        // $temp = array();

        // foreach($reports as $key => $value){
        //     $temp = array();
        //     $reports[$key]['images'] = array();
        //     $reportImage = ReportImage::where("report_id", $reports[$key]['id'])->get()->toArray();

        //     // array_push($temp,$reportImage);    
        //     array_push($reports[$key]['images'],$reportImage); 
        // }
        
        return $reports;    
    }

    public function mGetReportsCount(Request $request){
        $validator = Validator::make($request->all(), [
            'user_id'  => 'required',
        ]);

        if ($validator->fails()) {
            return ['error' => true,'message'=>'Required fields are missing','stack_trace' => $validator->errors()];
        }

        $reportsCount = Report::where("user_id",$request->user_id)->count();
        $pendingCount = Report::where("user_id",$request->user_id)
            ->where("type",0)
            ->count();
        $solvedCount = Report::where("user_id",$request->user_id)
            ->where("type",1)
            ->count();
        $ongoingCount = Report::where("user_id",$request->user_id)
            ->where("type",2)
            ->count();
        $declinedCount = Report::where("user_id",$request->user_id)
            ->where("type",03)
            ->count();
        
        return [
            'error'   => false,
            'message' => 'Success',
            'total'   => $reportsCount,
            'pending' => $pendingCount,
            'solved'  => $solvedCount,
            'ongoing' => $ongoingCount,
            'declined'=> $declinedCount,
        ];    
    }

    public function mGetProfilePicture(Request $request){
        $validator = Validator::make($request->all(),[
            'user_id'  => 'required',
        ]);

        if($validator->fails()){
            return ['error' => true,'message'=>'Required fields are missing','stack_trace' => $validator->errors()];
        }

        $profilePicture = User::find($request->user_id);
        $profilePicture->profile_photo = url($profilePicture->profile_photo);
        return $profilePicture;
    }

    
}
