<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_Authentication extends Public_Controller {
    
    function __construct(){
        parent::__construct();
        
        // Load google oauth library
        $this->load->library('google');
        // Load fakebook oauth library
        $this->load->library('facebook');
        
        // Load user model
        // $this->load->model('user');
        
        // $this->lang->load('auth');

        /* Title Page :: Common */
        $this->page_title->push('Thông tin người dùng');
        $this->data['pagetitle'] = $this->page_title->show();

        /* Breadcrumbs :: Common */
        $this->breadcrumbs->unshift(1, lang('menu_toolcals'), 'admin/toolcals');
    }
    
    public function index(){
        // Redirect to profile page if the user already logged in
        if(isset($_SESSION['sess_logged_in']) &&  isset($_SESSION['sess_logged_in'])==1){
            //redirect('home', 'refresh');
        }
        

        // Authenticate user with facebook
        // if (isset($_GET['code']) AND !empty($_GET['code'])) {
		// 	$code = $_GET['code'];
        // // parsing the result to getting access token.
        //     parse_str($this->get_fb_contents("https://graph.facebook.com/oauth/access_token?client_id=183736582562443&redirect_uri=" . urlencode(base_url('login')) ."&client_secret=a99b3358b780e59772fc30b72f5f5bb7&code=" . urlencode($code)));
		// 	redirect('user_authentication?access_token='.$access_token);
		// }
		// if(!empty($_GET['access_token'])) {
        //     // getting all user info using access token.
		// 	$fbuser_info = json_decode($this->get_fb_contents("https://graph.facebook.com/me?access_token=".$_GET['access_token']), true);
        //     // you can get all user info from print_r($fbuser_info);
        //     if(!empty($fbuser_info['email'])) {
        //         $_SESSION['first_name']=$fbuser_info['first_name'];
        //         $_SESSION['last_name']=$fbuser_info['last_name'];
        //         $_SESSION['email']=$fbuser_info['email'];

        //         $session_data=array(
        //                 'oauth_provider'=>'facebook',
        //                 'name'=>$userProfile['first_name'].' '.$userProfile['last_name'],
        //                 // 'oauth_uid' => $userProfile['id'],
        //                 'first_name' => $userProfile['first_name'],
        //                 'last_name'  => $userProfile['last_name'],
        //                 'email'=>$userProfile['email'],
        //                 'gender' => !empty($userProfile['gender'])?$userProfile['gender']:'',
        //                 'locale' => !empty($userProfile['locale'])?$userProfile['locale']:'',
        //                 'link' => !empty($userProfile['link'])?$userProfile['link']:'',
        //                 'profile_pic' => !empty($userProfile['picture'])?$userProfile['picture']['data']['url']:'',
        //                 'sess_logged_in'=>1
        //                 );
        //         $this->session->set_userdata($session_data);

        //         // Redirect to profile page
        //         redirect('home', 'refresh');

        //     }
        // }
        // var_dump($this->facebook->is_authenticated());
        // var_dump("<br />");
        // var_dump($this->facebook->request('get', '/me?fields=id,first_name,last_name,email,link,gender,locale,cover,picture'));
        // if($this->facebook->is_authenticated()){
        //     // Get user facebook profile details
        //     $userProfile = $this->facebook->request('get', '/me?fields=id,first_name,last_name,email,link,gender,locale,cover,picture');
        //     // var_dump($userProfile);
        //     if(isset($userProfile) && $userProfile!=null){
        //         $session_data=array(
        //             'oauth_provider'=>'facebook',
        //             'name'=>$userProfile['first_name'].' '.$userProfile['last_name'],
        //             'oauth_uid' => $userProfile['id'],
        //             'first_name' => $userProfile['first_name'],
        //             'last_name'  => $userProfile['last_name'],
        //             'email'=>$userProfile['email'],
        //             'gender' => !empty($userProfile['gender'])?$userProfile['gender']:'',
        //             'locale' => !empty($userProfile['locale'])?$userProfile['locale']:'',
        //             'link' => !empty($userProfile['link'])?$userProfile['link']:'',
        //             'profile_pic' => !empty($userProfile['picture'])?$userProfile['picture']['data']['url']:'',
        //             'sess_logged_in'=>1
        //             );
        //         $this->session->set_userdata($session_data);

        //         // Redirect to profile page
        //         redirect('home', 'refresh');
        //     }
            
        // }

        if ($this->facebook->logged_in())
		{
			$user = $this->facebook->user();

			if ($user['code'] === 200)
			{
				$this->session->set_userdata('login',true);
                // $this->session->set_userdata('user_profile',$user['data']);
                $userProfile=$user['data'];
                $picture=(isset($userProfile['picture']) && !empty($userProfile['picture']))?json_decode(json_encode($userProfile['picture']), True):'';
                // var_dump($picture);
				$session_data=array(
                    'oauth_provider'=>'facebook',
                    'name'=>$userProfile['first_name'].' '.$userProfile['last_name'],
                    'oauth_uid' => $userProfile['id'],
                    'first_name' => $userProfile['first_name'],
                    'last_name'  => $userProfile['last_name'],
                    'email'=>$userProfile['email'],
                    'gender' => !empty($userProfile['gender'])?$userProfile['gender']:'',
                    'locale' => !empty($userProfile['locale'])?$userProfile['locale']:'',
                    'link' => !empty($userProfile['link'])?$userProfile['link']:'',
                    'profile_pic' => !empty($picture)?$picture ['data']['url']:'',    //!empty($userProfile['picture'])?$userProfile['picture']['data']['url']:'',
                    'sess_logged_in'=>1
                    );
                $this->session->set_userdata($session_data);

                // Redirect to profile page
                redirect('home', 'refresh');
			}

		}
        
        // Google authentication url
        // $data['userData'] = $userData;
        // $data['loginURL'] =  $this->google->get_login_url();    //$this->google->loginURL();
        $data['google_login_url']=$this->google->get_login_url();
        $data['facebook_login_url'] =  $this->facebook->login_url();
        
        // Load google login view
        $this->load->view('public/user_authentication/index',$data);
    }

    /**
  * calling facebook api using curl and return response.
  */


function get_fb_contents($url) {
	$curl = curl_init();
	curl_setopt( $curl, CURLOPT_URL, $url );
	curl_setopt( $curl, CURLOPT_RETURNTRANSFER, 1 );
	curl_setopt( $curl, CURLOPT_SSL_VERIFYPEER, false );
	$response = curl_exec( $curl );
	curl_close( $curl );
	return $response;
}

    public function oauth2callback(){
		$google_data=$this->google->validate();
		$session_data=array(
                'oauth_provider'=>'google',
                'name'=>$google_data['name'],
                'oauth_uid' => $google_data['id'],
                // 'first_name' => $google_data['given_name'],
                // 'last_name'  => $google_data['family_name'],
				'email'=>$google_data['email'],
                // 'gender' => !empty($google_data['gender'])?$google_data['gender']:'',
                // 'locale' => !empty($google_data['locale'])?$google_data['locale']:'',
                'link' => !empty($google_data['link'])?$google_data['link']:'',
                'profile_pic' => !empty($google_data['profile_pic'])?$google_data['profile_pic']:'',
				'sess_logged_in'=>1
                );
        $this->session->set_userdata($session_data);
        // // Preparing data for database insertion
        // $userData['oauth_provider'] = 'google';
        // $userData['oauth_uid']         = $google_data['id'];
        // $userData['name']     = $google_data['name'];
        // $userData['email']             = $google_data['email'];
        // // $userData['gender']         = !empty($google_data['gender'])?$google_data['gender']:'';
        // // $userData['locale']         = !empty($google_data['locale'])?$google_data['locale']:'';
        // $userData['link']             = !empty($google_data['link'])?$google_data['link']:'';
        // $userData['picture']         = !empty($google_data['profile_pic'])?$google_data['profile_pic']:'';
        
        // // Insert or update user data to the database
        // // $userID = $this->user->checkUser($userData);
        
        // // Store the status and user profile info into session
        // $this->session->set_userdata('loggedIn', true);
        // $this->session->set_userdata('userData', $userData);
        
        //var_dump($this->session->userdata('userData')['email']);
        redirect('home', 'refresh');
    }
    
    public function oauthfacebookcallback(){
        var_dump("Hello");
    }
    
    public function profile(){
        // Redirect to login page if the user not logged in
        if(!isset($_SESSION['sess_logged_in']) ||  isset($_SESSION['sess_logged_in'])==0){
            redirect('user_authentication', 'refresh');
        }
        
        // Get user info from session
        $userData=array(
            'oauth_provider'=>$_SESSION['oauth_provider'],
            'name'=>$_SESSION['name'],
            'oauth_uid' => $_SESSION['oauth_uid'],
            'email'=>$_SESSION['email'],
            'link' => !empty($_SESSION['link']),
            'profile_pic' => $_SESSION['profile_pic'],
            'sess_logged_in'=>$_SESSION['sess_logged_in']
            );
        $this->data['userData'] = $userData;
        /* Breadcrumbs */
        $this->data['breadcrumb'] = $this->breadcrumbs->show();
        
        // Load user profile view
        // $this->load->view('public/user_authentication/profile',$data);
        $this->template->public_render('public/user_authentication/profile', $this->data);
    }

	public function logout(){
		session_destroy();
		unset($_SESSION['access_token']);
		$session_data=array(
				'sess_logged_in'=>0);
		$this->session->set_userdata($session_data);
        
        redirect('user_authentication', 'refresh');
	}
    
    // public function logout(){
    //     // Delete login status & user info from session
    //     $this->session->unset_userdata('loggedIn');
    //     $this->session->unset_userdata('userData');
    //     $this->session->sess_destroy();
    //     unset($_SESSION['access_token']);
        
    //     // Redirect to login page
    //     redirect('user_authentication', 'refresh');
    // }
    
}