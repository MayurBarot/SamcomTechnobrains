<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Company;
use File;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Response;
use App\Mail\MailTrap;

class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $companies=Company::orderBy('id','DESC')->paginate(10);
            return view('home')->with('companies',$companies);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('create_company');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'unique:companies',
            "logo"  => "dimensions:min_width=100,min_height=100"
        ]);
        

        $image_custom_name = "";
        $image_fullpath = "";
        if (isset($request->logo) && !empty($request->logo)) {
            $image_upload_dir  = public_path() . '/uploads/';
            File::isDirectory($image_upload_dir) or File::makeDirectory($image_upload_dir, 0777, true, true);

            $errors     = array();

            $image_temp_path = $request->logo->getRealPath();
            $image_file_name = $request->logo->getClientOriginalName();
            $image_file_ext  = $request->logo->getClientOriginalExtension();
            $image_file_size = $request->file('logo')->getSize();

            $image_custom_name = time() . "." . $image_file_ext;
            if (!empty($image_custom_name) && $image_custom_name != '') {
                $image_fullpath = '/uploads/'. $image_custom_name;
            }
            if (empty($errors) == true) {
                $request->logo->move($image_upload_dir, $image_custom_name);
                $request->merge(['logo' =>  $image_file_name,'logo_path' =>  $image_fullpath]);
            } else {
                return false;
            }
        }
        Company::create($request->all());
        $data = [
            'email'   =>  $request->email,
            'subject' => "Company is created",
            'name' => $request->name,
            'body'    =>  "Hello, ".$request->name." company  is created successfully",
        ];

        // CompanyController::sentMail($data); // using SMTP
        Mail::to($request->email)->send(new MailTrap($data)); //using Mailtrap
        return redirect()->route('companies.index')
                        ->with('success','Company created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $company=Company::find($id);
        return view('create_company',compact('company'));
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
        $request->validate([
            'name' => 'required',
            "logo"  => "dimensions:min_width=100,min_height=100"
        ]);
        
        $companies=Company::find($id);

        $image_custom_name = "";
        $image_fullpath = "";
        if (isset($request->logo) && !empty($request->logo)) {
            $image_upload_dir  = public_path() . '/uploads/';
            File::isDirectory($image_upload_dir) or File::makeDirectory($image_upload_dir, 0777, true, true);

            $errors     = array();

            $image_temp_path = $request->logo->getRealPath();
            $image_file_name = $request->logo->getClientOriginalName();
            $image_file_ext  = $request->logo->getClientOriginalExtension();
            $image_file_size = $request->file('logo')->getSize();

            $image_custom_name = time() . "." . $image_file_ext;
            if (!empty($image_custom_name) && $image_custom_name != '') {
                $image_fullpath = '/uploads/'. $image_custom_name;
            }
            if (empty($errors) == true) {
                $request->logo->move($image_upload_dir, $image_custom_name);
                if(file_exists(public_path($companies->logo_path))){
                    unlink(public_path($companies->logo_path));
                }
                $request->merge(['logo' =>  $image_file_name,'logo_path' =>  $image_fullpath]);
            } else {
                return false;
            }
        }
        $companies->update($request->all());
    
        return redirect()->route('companies.index')
                        ->with('success','Company updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $companies=Company::find($id);
        $companies->delete();
    
        return redirect()->route('companies.index')
                        ->with('success','Company deleted successfully');
    }

    public function sentMail($data){
      return $sentmail = Mail::send([], $data, function($message) use ($data)
        {
            $message->to($data['email'])
                    ->subject($data['subject']) 
                    ->setBody($data['body'], 'text/html');
        });
    }

    public function createCompanyByApi(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'name' => 'required',
            'email' => 'unique:companies',
            "logo"  => "dimensions:min_width=100,min_height=100"
        ]);

        if($validator->fails()){
            $this->output['status'] = "error";
            $this->output['response'] = implode(",", $validator->messages()->all());
            return response()->json($this->output, Response::HTTP_BAD_REQUEST);
        }
        $image_custom_name = "";
        $image_fullpath = "";
        if (isset($request->logo) && !empty($request->logo)) {
            $image_upload_dir  = public_path() . '/uploads/';
            File::isDirectory($image_upload_dir) or File::makeDirectory($image_upload_dir, 0777, true, true);

            $errors     = array();

            $image_temp_path = $request->logo->getRealPath();
            $image_file_name = $request->logo->getClientOriginalName();
            $image_file_ext  = $request->logo->getClientOriginalExtension();
            $image_file_size = $request->file('logo')->getSize();

            $image_custom_name = time() . "." . $image_file_ext;
            if (!empty($image_custom_name) && $image_custom_name != '') {
                $image_fullpath = '/uploads/'. $image_custom_name;
            }
            if (empty($errors) == true) {
                $request->logo->move($image_upload_dir, $image_custom_name);
                $request->merge(['logo' =>  $image_file_name,'logo_path' =>  $image_fullpath]);
            } else {
                return false;
            }
        }
        $storeData = Company::create($request->all());
        $data = [
            'email'   =>  $request->email,
            'subject' => "Company is created",
            'name' => $request->name,
            'body'    =>  "Hello, ".$request->name." company  is created successfully",
        ];

        // CompanyController::sentMail($data); // using SMTP
        Mail::to($request->email)->send(new MailTrap($data)); //using Mailtrap

        $this->output['status'] = "success";
        $this->output['response'] = "Company Add Successfully";
        $this->output['data'] = $storeData;
        return response()->json($this->output, Response::HTTP_CREATED);
    }
}
