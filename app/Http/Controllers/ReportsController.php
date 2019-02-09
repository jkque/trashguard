<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Report;
use Validator;
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
        return $reports->orderBy('created_at',"desc")->paginate(10);
    }

    public function getUsers(Request $request){
        return User::orderBy('name')->paginate(10);
    }

    public function addReport(Request $request){
        $validator = Validator::make($request->all(), [
            'location'  => 'required',
            'desc'      => 'required',
            'user_id'      => 'required',
        ]);
        if ($validator->fails()) {
            return ['success' => false,'errors' => $validator->errors()];
        }

        $newReport              = new Report;
        $newReport->location    = $request->location;
        $newReport->desc        = $request->desc;
        $newReport->user_id     = $request->user_id;
        $newReport->type        = 0;
        $newReport->save();
        
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
            } else if ($action == "approve" ){
                $report->type = 2;
                $report->save();
            } else if ($action == "decline"){
                $report->type = 3; 
                $report->save();
            } else if ($action == "solve"){
                $report->type = 1; 
                $report->save();
            } else {
                return ['success' => false];
            }
            $report->updated_by = Auth::user()->id;
            return ['success'=>true,'action'=>$action];
        } 
        return ['success' => false];
    }
}
