<?php
namespace App\Http\service;

use App\Http\service\Qrcode;

/**
 * 文件二维码加密方法
 *
 * @author: dogstar <chanzonghuang@gmail.com> 2014-10-04
 */

class FileQRcodeEncry {
	
	/* 加密文件 */
	public function encry ($unique_num, $return_url, $path, $form_str, $flag=true) {
		
		$result = [];
		
		if ($flag === true) {
			
			//文件整体安全加密流程
			$html_filename = $return_url.'&pay_filename='.MD5($unique_num);
			
			$token = MD5($html_filename.'&token='.MD5($html_filename));
			
			$filecontent_base64_2 = base64_encode(base64_encode($form_str));
			
			$filecontent_token = MD5($filecontent_base64_2);
			
			$content = '<input type="hidden" id="'.MD5(MD5($unique_num).'!VERIFY!').'" value="'.$filecontent_token.'" />';
			
			$content .= '<input type="hidden" id="'.MD5(MD5($unique_num).$filecontent_token.'!BASE64!').'" value="'.rand(1,9).$filecontent_base64_2.'" />';
			
			$content .= '<div id="'.MD5(MD5($unique_num).'!HTML!').'" ></div>';
		
			//创建临时支付文件
			@file_put_contents($path.MD5(MD5($unique_num).$html_filename), $content);
			
			$tmp_files['encry_file'] = $path.MD5(MD5($unique_num).$html_filename);
			
			$html_filename = $html_filename.'&token='.$token;
			
			$qrcode_path = $path.MD5($unique_num).'.png';
			
			QRcode::png($html_filename, $qrcode_path, 'H', 5);
			
			$result = ['qr_path'=>$qrcode_path, 'url_path'=>$html_filename];
		
		} else {
			
			$qrcode_path = $path.MD5($unique_num).'.png';
			
			QRcode::png($form_str, $qrcode_path, 'H', 5);
			
			$result = ['qr_path'=>$qrcode_path];
		}
		
		return $result;
	}
	
	/* 解密文件 */
	public function de_encry ($pay_filename, $token, $return_url, $path) {
		
		//配置验证数据
		$token_verify_html  = $return_url.'&pay_filename='.$pay_filename;
		$token_verify_md5 = MD5($return_url.'&pay_filename='.$pay_filename.'&token='.MD5($token_verify_html));
		
		//验证token值
		if ($token == $token_verify_md5) {
			$real_pay_filename = $path . MD5($pay_filename.$token_verify_html);
			
			if (file_exists($real_pay_filename)) {
				
				$verify_str = file_get_contents($real_pay_filename);
				
				//验证文件是否属于一套机制生成
				if (preg_match('/<input .+ id=\"'.MD5($pay_filename.'!HTML!').'\" /', $verify_str) > 0) {
					
					ob_start();
					$js_urldecode = 'function URLdecode(str) { var ret = "";for(var i=0;i<str.length;i++) {	var chr = str.charAt(i);if(chr == "+") {ret += " ";}else if(chr=="%") {var asc = str.substring(i+1,i+3);if(parseInt("0x"+asc)>0x7f) {ret += decodeURI("%"+ str.substring(i+1,i+9));	i += 8;	}else {	ret += String.fromCharCode(parseInt("0x"+asc));	i += 2;	}}else {ret += chr;	}}return ret;}';
					//跳转支付页面
					echo '<script type="text/javascript"> '.$js_urldecode.' var _'.MD5($pay_filename.'!JS!').'=""; setTimeout(function () {document.write(URLdecode(_'.MD5($pay_filename.'!JS!').'));}, 1);</script>';
					
					//验证参数1
					preg_match('/<input .+ id=\"'.MD5($pay_filename.'!VERIFY!').'\" value=\"([^\"\']+)\"/', $verify_str, $verify1);
					$verify1 = $verify1[1];
					
					//验证参数2
					preg_match('/<input .+ id=\"'.MD5($pay_filename.$verify1.'!BASE64!').'\" value=\"([^\"\']+)\"/', $verify_str, $verify2);
					$verify2 = substr($verify2[1],1);
					
					echo '<script type="text/javascript"> _'.MD5($pay_filename.'!JS!').'="'.urlencode(base64_decode(base64_decode($verify2))).'";</script>';
					
					$return_str = ob_get_contents();
					
					ob_end_clean();
					
					return $return_str;
				}
			}
		}
	}
	
}
