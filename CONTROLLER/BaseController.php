<?php

class BaseController {

	private static $cipherMethod = "aes-128-gcm";
	private static $cipherKey = '6v9y$B&E)H@MbQeThWmZq4t7w!z%C*F-JaNdRfUjXn2r5u8x/A?D(G+KbPeShVkY';

    protected $model;
    protected $controller;
    protected $searchView;
    protected $showView;
    protected $editView;
    protected $addView;
	
	function __construct(){

        // Initialize atributes
        $this->controller = get_class($this);
        $base = substr($this->controller, 0, -10);
        $this->model = $base . "Model";
        $this->searchView = $base . "SearchView";
        $this->showView = $base . "ShowView";
        $this->editView = $base . "EditView";
        $this->addView = $base . "AddView";

        // Include what's necessary
        include_once "./MODEL/$this->model.php";
        foreach (glob("./VIEW/entities/$base/*.php") as $filename)
        {
            include_once $filename;
        }

    }

	function redirectToMsg($data){

		// Encode data to JSON
		$jsonString = json_encode($data);

		// Encrypt JSON into token
		$ivlen = openssl_cipher_iv_length(self::$cipherMethod);
		$iv = openssl_random_pseudo_bytes($ivlen);
		$token = openssl_encrypt($jsonString, self::$cipherMethod, self::$cipherKey, $options = 0, $iv, $tag);

		// Store iv and tag for decryption later
		$_SESSION["iv"] = $iv;
		$_SESSION["tag"] = $tag;

		// Redirect
		header("Location: index.php?token=$token");
	}

	function search(){
		$entity = new $this->model();
		$entity->patchEntity();
		$data["atributeNames"] = $this->model::getFormattedAtributeNames();
		$data["result"] = $entity->SEARCH();
        new $this->searchView($data);
	}

	function show(){
		$entity = new $this->model();
		$entity->patchEntity();
        new $this->showView($entity->SHOW());
	}

	function delete(){
		$entity = new $this->model();
		$entity->patchEntity();
		$data["result"] = $entity->DELETE();
		$data["controller"] = $this->controller;
		$data["action"] = "search";
		$this->redirectToMsg($data);
	}

	function addForm(){
		new $this->addView();
	}

	function add(){
		$entity = new $this->model();
		$entity->patchEntity();
		$data["result"] = $entity->ADD();
		$data["controller"] = $this->controller;
		$data["action"] = "search";
		$this->redirectToMsg($data);
	}

	function editForm(){
		$entitySearch = new $this->model();
		$entitySearch->patchEntity();
		$data = $entitySearch->SHOW();
		new $this->editView($data);
	}

	function edit(){
		$entity = new $this->model();
		$entity->patchEntity();
		$data["result"] = $entity->EDIT();
		$data["controller"] = $this->controller;
		$data["action"] = "search";
		$this->redirectToMsg($data);
	}
}
?>