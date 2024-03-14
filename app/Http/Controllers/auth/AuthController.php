<?php
  
namespace App\Http\Controllers\Auth; 
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use phpseclib3\Crypt\RSA;
use Session;
use App\Models\User;
use Hash;
use App\Helpers\PhpseclibHelper;
  
class AuthController extends Controller
{
    /**
     * Write code on Method
     *
     * @return response()
     */
    public function index()
    {
       // echo "cvgbhnjkm,l.";die;
        return view('auth.login');
    }  
      
    /**
     * Write code on Method
     *
     * @return response()
     */
    public function registration()
    {
        return view('auth.registration');
    }
      
    /**
     * Write code on Method
     *
     * @return response()
     */
   public function postLogin(Request $request)
    {
        $request->validate([
            'email' => 'required',
            'digital_sign' => 'required',
        ]);

        // Get the user based on the provided email
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return redirect("/")->withInput()->withSuccess('Oppes! You have entered invalid credentials');
        }

        // Verify the signature
        $publicKeyString = $user->public_key;
        $publicKey = RSA::loadFormat('PKCS8', $publicKeyString);

        $plaintext = $user->plain_text; // This should be the data you want to verify
        $signature = $request->digital_sign;

        // Perform signature verification
        if ($publicKey->verify($plaintext, base64_decode($signature))) {
            Auth::login($user);
             return redirect('/dashboard')->withSuccess('You have Successfully logged in');
        }else{
             return redirect("/")->withInput()->withSuccess('Invalid login!!');

        }
        //echo "ghjk";die;
        return redirect("/")->withInput()->withSuccess('Oppes! You have entered invalid credentials');
    }

      
    /**
     * Write code on Method
     *
     * @return response()
     */
    public function postRegistration(Request $request)
    {  
        $request->validate([
            'name' => 'required',
            'digital_sign' => 'required',
            'email' => 'required|email|unique:users',
            // 'password' => 'required|min:6',

        ]);

            $phpseclibStatus = PhpseclibHelper::checkPhpseclib();

            $folderPath = public_path('upload/');
        
            $image_parts = explode(";base64,", $request->digital_sign);
                  
            $image_type_aux = explode("image/", $image_parts[0]);
               
            $image_type = $image_type_aux[1];
               
            $image_base64 = base64_decode($image_parts[1]);
               
            $file = $folderPath . uniqid() . '.'.$image_type;
            file_put_contents($file, $image_base64);
            

            $privateKey = \phpseclib3\Crypt\RSA::createKey();
            //echo $privateKey;die;
            $publicKey = $privateKey->getPublicKey();
            //$token = $publicKey;
            //$plaintext = $request->digital_sign;
              //die;
            //echo $publicKey;die;
            $signature = $privateKey->sign($request->digital_sign);
            $new_signature= base64_encode($signature);

            $user = new User();

            // Set the attributes
            $user->name = $request->input('name');
            $user->email = $request->input('email');
            // $user->password = bcrypt($request->input('password'));
            $user->plain_text = $request->digital_sign;
            $user->digital_sign = $new_signature;
            $user->private_key = $privateKey;
            $user->public_key = $publicKey;
            $data = $user->save();
             return $new_signature;        
    }
    
    /**
     * Write code on Method
     *
     * @return response()
     */
    public function dashboard()
    {
        if(Auth::check()){
            return view('dashboard');
        }
  
        return redirect("/")->withSuccess('Opps! You do not have access');
    }
    
    /**
     * Write code on Method
     *
     * @return response()
     */
    public function create(array $data)
    {
      return User::create([
        'name' => $data['name'],
        'email' => $data['email'],
        'password' => Hash::make($data['password'])
      ]);
    }
    
    /**
     * Write code on Method
     *
     * @return response()
     */
    public function logout() {
        Session::flush();
        Auth::logout();
  
        return Redirect('/');
    }

    public function upload(Request $request)
    {

        $folderPath = public_path('upload/');
        
        $image_parts = explode(";base64,", $request->signed);
              
        $image_type_aux = explode("image/", $image_parts[0]);
           
        $image_type = $image_type_aux[1];
           
        $image_base64 = base64_decode($image_parts[1]);
           
        $file = $folderPath . uniqid() . '.'.$image_type;
        file_put_contents($file, $image_base64);
         return back()->with('success', 'success Full upload signature');

    }

    public function adminlogin(){
        return view('auth.adminlogin');
    }

    public function checklogin(Request $request) {
    $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            
            // Authentication passed
            return redirect()->intended('admindashboard'); // Redirect to the intended page after successful login
        } else {
            // Authentication failed
            return redirect()->route('adminlogin')->with('error', 'Invalid email or password');
        }

    }
    public function admindashboard(){
        if(Auth::check()){
            $users = User::whereNull('password')->get(); // Retrieve users with not null passwords
            return view('admin.dashboard', compact('users'));
        }
  
        return redirect("/adminlogin")->withSuccess('Opps! You do not have access');
        

    }

}