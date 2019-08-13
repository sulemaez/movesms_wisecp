<?php
    if(!defined("CORE_FOLDER")) die();

    $lang           = $module->lang;
    $config         = $module->config;
    
    
    $api_token     = (string)  Filter::init("POST/api-token","hclear");
    $senderId       = (string) Filter::init("POST/senderId","hclear");
    $username       = (string) Filter::init("POST/userName","hclear");
    $sets       = [];

    if($api_token != $config["api-token"]) $sets["api-token"] = $api_token;
    if($senderId != $config["sender-id"]) $sets["sender-id"] = $senderId;
    if($username != $config["user-name"]) $sets["user-name"] = $username;

    if($sets){
        $config_result  = array_replace_recursive($config,$sets);
        $array_export   = Utility::array_export($config_result,['pwith' => true]);
        $file           = dirname(__DIR__).DS."config.php";
        $write          = FileManager::file_write($file,$array_export);

        $adata          = UserManager::LoginData("admin");
        User::addAction($adata["id"],"alteration","changed-sms-module-settings",[
            'module' => $config["meta"]["name"],
            'name'   => $lang["name"],
        ]);
    }

    echo Utility::jencode([
        'status' => "successful",
        'message' => $lang["success1"],
    ]);