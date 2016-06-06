<?
class BitrixConnect
{
	private $host;
	private $port = 443; //стандартное значение для облака bitrix24
	private $path = "/crm/configs/import/lead.php"; //стандартное значение для облака bitrix24
	private $login;
	private $password;
	public $response;
	public $error;

	//
	public function __construct($host, $login, $password, $port = "", $path = ""){
		$this->host = $host;
		$this->login = $login;
		$this->password = $password;
		if ($port) $this->port = $port;
		if ($path) $this->port = $path;
	}

	public function send($data=array())
	{
		if (!$this->host){
			$this->error = "Error! Host cannot be empty!";
			return false;
		}
		elseif (!$this->login || !$this->password){
			$this->error = "Error! Login or password cannot be empty!";
			return false;
		} else {
			$dataSet['LOGIN'] = $this->login;
			$dataSet['PASSWORD'] = $this->password;
			$dataSet = array_merge($dataSet, $data);

			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, "https://" . $this->host . $this->path);
			curl_setopt($ch, CURLOPT_POST, 1);
			$strPostData = '';
			foreach ($dataSet as $key => $value)
				$strPostData .= ($strPostData == '' ? '' : '&') . $key . '=' . urlencode($value);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $strPostData);

//			на локальных серверах CURL может выдавать ошибку при подключении. В этому случае для тестирования подключения можно
// 			раскомментировать следующую строчку. Внимание! На боевых серверах в целях безопасности этого делать не рекомендуется!

//			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

			$server_output = curl_exec($ch);
			$this->response = $server_output;
			$this->error = curl_error($ch);
			curl_close($ch);
		}
	}
}