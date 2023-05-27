<?php 
require_once(dirname(__FILE__)."/../../vendor/autoload.php");

class RichMenuSetting{
    public function __construct(){
        $env = Dotenv\Dotenv::createImmutable(dirname(__FILE__)."/../../../env");
        $env->load();    
    }

    public function createMenu(){
        $result = "";
        $curl = curl_init("https://api.line.me/v2/bot/richmenu");

        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, file_get_contents("./richmenu.json"));
        curl_setopt($curl, CURLOPT_HTTPHEADER, [
            "Authorization: Bearer ".$_ENV["ACCESSTOKEN"],
            "Content-type: application/json"
        ]);
        curl_exec($curl);

        // エラーがあればエラー内容を表示
        if (curl_errno($curl)){
            $result = curl_error($curl);
            return $result;
        }
        
        $response = curl_multi_getcontent($curl);
        curl_close($curl);
        $response_json = json_decode($response);
        return $response_json->richMenuId;
    }

    public function putMenuImg(string $menuId){
        $curl = curl_init(sprintf("https://api-data.line.me/v2/bot/richmenu/%s/content", $menuId));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, file_get_contents("./image.jpg"));
        curl_setopt($curl, CURLOPT_HTTPHEADER, [
            "Authorization: Bearer ".$_ENV["ACCESSTOKEN"],
            "Content-type: image/jpeg"
        ]);
        curl_exec($curl);

        $sts = curl_getinfo($curl, CURLINFO_RESPONSE_CODE);

        // エラーがあればエラー内容を表示
        if (curl_errno($curl)){
            $result = curl_error($curl);
            return $result;
        }

        curl_close($curl);

        return $sts;

    }

    public function setDefaultMenu(string $menuId){
        $curl = curl_init(sprintf("https://api.line.me/v2/bot/user/all/richmenu/%s", $menuId));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, [
            "Authorization: Bearer ".$_ENV["ACCESSTOKEN"],
        ]);
        curl_exec($curl);

        $sts = curl_getinfo($curl, CURLINFO_RESPONSE_CODE);

        // エラーがあればエラー内容を表示
        if (curl_errno($curl)){
            $result = curl_error($curl);
            return $result;
        }
        curl_close($curl);

        return $sts;
    }

    public function getDefaultMenu(){
        $curl = curl_init("https://api.line.me/v2/bot/user/all/richmenu");
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_HTTPHEADER, [
            "Authorization: Bearer ".$_ENV["ACCESSTOKEN"],
        ]);
        curl_exec($curl);
        $response = curl_multi_getcontent($curl);
        $sts = curl_getinfo($curl, CURLINFO_RESPONSE_CODE);
        curl_close($curl);
        return $sts;
    }

    public function getDefaultMenuObj(){
        $curl = curl_init("https://api.line.me/v2/bot/user/all/richmenu");
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_HTTPHEADER, [
            "Authorization: Bearer ".$_ENV["ACCESSTOKEN"],
        ]);
        curl_exec($curl);
        $response = curl_multi_getcontent($curl);
        curl_close($curl);
        return $response;
   
    }

    public function getMenuDetail(string $menuId){
        $curl = curl_init("https://api.line.me/v2/bot/richmenu/".$menuId);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_HTTPHEADER, [
            "Authorization: Bearer ".$_ENV["ACCESSTOKEN"],
        ]);
        curl_exec($curl);
        $response = curl_multi_getcontent($curl);
        curl_close($curl);
        return $response;   
    }

    public function getEndpointURL(){
        $curl = curl_init("https://api.line.me/v2/bot/channel/webhook/endpoint");
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_HTTPHEADER, [
            "Authorization: Bearer ".$_ENV["ACCESSTOKEN"],
        ]);
        curl_exec($curl);
        $response = curl_multi_getcontent($curl);
        curl_close($curl);
        return $response;   
    }
}
?>
