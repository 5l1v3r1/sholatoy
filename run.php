<?php
date_default_timezone_set('Asia/Jakarta');
function randStr($l){
	$word = "ab1cde2fgh3ijk4lmn5op6qr7s8tuv9wxy0z";
	$str = "";
	for($a=0;$a<$l;$a++){
		$str .= $word{rand(0,strlen($word)-1)};
	}
	return $str;
}
function getStr($a,$b,$data){
	$a = @explode($a, $data)[1];
	return @explode($b, $a)[0];
}
function makeId(){
	$str[0] = randStr(8);
	$str[1] = randStr(4);
	$str[2] = randStr(4);
	$str[3] = randStr(4);
	$str[4] = randStr(12);
	return implode("-", $str);
}
function call($no){
	$valid = substr($no,0,1);
	if($valid=="0") $no = "62".substr($no,1);
	$body = "method=CALL&countryCode=id&phoneNumber=$no&templateID=&numDigits=6";
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, 'https://api.grab.com/grabid/v1/phone/otp');
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
	curl_setopt($ch, CURLOPT_POST, 1);

	$headers = array();
	$headers[] = 'X-Request-Id: '.makeId();
	$headers[] = 'Content-Encoding: gzip';
	$headers[] = 'Accept-Language: in-ID;q=1.0, en-us;q=0.9, en;q=0.8';
	$headers[] = 'User-Agent: Grab/5.46.1 (Android 5.1.1; Build 3091653)';
	$headers[] = 'Content-Type: application/x-www-form-urlencoded';
	$headers[] = 'Content-Length: '.strlen($body);
	$headers[] = 'Host: api.grab.com';
	$headers[] = 'Connection: close';
//	$headers[] = 'Accept-Encoding: gzip, deflate';
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	$result = curl_exec($ch);
	curl_close ($ch);
	return $result;
}
function getList(){
	$data = @file_get_contents("https://jadwalsholat.org/adzan/monthly.php");
	$list = getStr('<select name=kota onChange="change_page()" class="inputcity">','</select>',$data);
	$arr = @explode("<option ", $list);
	$lists = array("ID	|	KOTA");
	$secret = array();
	for($a=1;$a<count($arr);$a++){
		$ll = $arr[$a];
		$id = getStr('value="','"',$ll);
		$kota = getStr('">','</option>',$ll);
		$secret[$id] = $kota;
		$lists[$a] = $id."	|	".$kota;
	}
	return array(implode("\n", $lists),$secret);
}
function get(){ return trim(fgets(STDIN)); }
function getTime($id){
	$data = @file_get_contents("https://jadwalsholat.org/adzan/monthly.php?id=$id");
	$get = @getStr('align="center"><td><b>'.date("H", time()).'</b>', '</td></tr>', $data);
	$get = @explode('</td><td>', $get); unset($get[0],$get[1]);
	return $get;
}
echo "#################################\n# SPAM TELEPON PENGINGAT SHOLAT	#\n# CREATED BY : @xptra           #\n# QUOTES     : SHOLAT AJG       #\n#################################\n";
echo "Nomor Handphone		";
$nope = get();
$getList = getList();
echo $getList[0]."\n";
while(true){
	echo "Pilih Id/No. Kota		";
	$id = get();
	echo "Kamu Yakin Memilih Id $id [".$getList[1][$id]."] (y/n)  ";
	if(strtolower(get())=="y"){
		break;
	}else{
		continue;
	}
}
echo "Sudah Berjalan....\n";
while(true){
	$validation = getTime($id);
	$time = date("H:i", time());
	$b = 0;
	if(in_array($time, $validation) AND $b<5){
		echo call($nope)."\n";
		$b += 1;
		sleep(20);
	}else{
		$b = 0;
		continue;
	}
}
