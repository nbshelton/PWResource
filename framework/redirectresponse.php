<?php

class RedirectResponse extends StatusCodeResponse {
    
    protected $redirectUrl;
    
    public function __construct($path) {
        parent::__construct(303);
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || $_SERVER['SERVER_PORT'] == 443 ? "https" : "http";
        if ($path[0] != "/")
            $path = "/".$path;
        $this->redirectUrl = "$protocol://".$_SERVER['HTTP_HOST'].$path;
    }
    
    public function render() {
        header("Location: ".$this->redirectUrl);
        parent::render();
    }
    
}
