<?php
/**
 * core/MY_Controller.php
 *
 * Default application controller
 *
 * @author		JLP
 * @copyright           2010-2013, James L. Parry
 * ------------------------------------------------------------------------
 */
class Application extends CI_Controller {
	protected $data = array();	  // parameters for view components
	protected $id;				  // identifier for our content
	/**
	 * Constructor.
	 * Establish view parameters & load common helpers
	 */
	function __construct()
	{
		parent::__construct();
		$this->data = array();
		$this->data['title'] = 'Stock Game';	// our default title
		$this->errors = array();
		$this->data['pageTitle'] = 'welcome';   // our default page
		
		 $this->load->library('session');
		 
		 
		 $nav_right = $this->config->item('menu_choices_right');
		 if ($this->session->userdata('usr') !== null) {
             $nav_right['menudata'][0] = array('name' => 'Hello, ' . $this->session->userdata('usr'), 'link' => '#');
			 $nav_right['menudata'][1] 
                 = array('name' => 'Logout', 'link' => '/logout');
			 
         }
		 $this->config->set_item('menu_choices_right', $nav_right);
	}
	/**
	 * Render this page
	 */
	function render()
	{
                $mychoices = array('menudata' => $this->makemenu());
                $this->data['menubar'] = $this->parser->parse('_menubar', $mychoices, true);
		$this->data['content'] = $this->parser->parse($this->data['pagebody'], $this->data, true);
                $this->data['menubar_right'] = $this->parser->parse('_menubar_right', $this->config->item('menu_choices_right'), true);
		// finally, build the browser page!
		$this->data['data'] = &$this->data;
		$this->parser->parse('_template', $this->data);
                
		//$this->data['menubar'] = $this->parser->parse('_menubar', $this->config->item('menu_choices'), true);
		//$this->data['menubar_right'] = $this->parser->parse('_menubar_right', $this->config->item('menu_choices_right'), true);
		
		//$this->data['content'] = $this->parser->parse($this->data['pagebody'], $this->data, true);
		// finally, build the browser page!
		//$this->data['data'] = &$this->data;
		//$this->parser->parse('_template', $this->data);
                 
                 
	}
        
        function restrict($roleNeeded = null) {
            $userRole =
            $this->session->userdata('userRole');
            if ($roleNeeded != null) {
                if (is_array($roleNeeded)) {
                    if (!in_array($userRole, $roleNeeded))
                    {
                        redirect("/");
                        return;
                    }
                } else if ($userRole != $roleNeeded) {
            redirect("/");
                return;
                    }
            }
        }
        
        function makemenu()
	{
            $userRole = $this->session->userdata('userRole');
            $userName = $this->session->userdata('usr');
            $menu = array();
            $menu[] = array('name' => 'Home', 'link' => '/');
            $menu[] = array('name' => 'Stock History', 'link' => '/history');
            $menu[] = array('name' => 'Portfolio', 'link' => '/profile');
            $menu[] = array('name' => 'About', 'link' => '/about');
            if ($userRole != null) {
                if ($userRole == ROLE_ADMIN || $userRole == ROLE_USER) {
                    //admin and user shit in here
                    $menu[] = array('name' => 'Play', 'link' => '/play');
                }
                //$menu[] = array('name' => "".$userName." - Logout", 'link' => '/login/logout');
                if ($userRole == ROLE_ADMIN) {
                        //admin shit in here
                }
                }else{
                    //$menu[] = array('name' => 'Login', 'link' => '/login');
                }
            return $menu;
	}
}