<?php
session_start();
class Kriweb
{
    private $apiUrl = "https://kriweb.com/rest/";
    private $test = false;
    public function __construct()
    {
        $this->curl = curl_init();
    }

    public function user($username, $password)
    {
        $this->username = $username;
        $this->password = $password;
        $this->checksum = md5($username . $password);
        if ($_SESSION["KRW-Checksum"] === $this->checksum) {
            $this->KRWLastTokenTime = ($_SESSION["KRW-LastTokenTime"]) ? $_SESSION["KRW-LastTokenTime"] : 0;
        } else {
            $this->KRWLastTokenTime        = 0;
            $_SESSION["KRW-Token"]         = 0;
            $_SESSION["KRW-LastTokenTime"] = false;
            $_SESSION["KRW-Checksum"]      = md5($username . $password);
        }
        $this->token = $this->getToken();
    }

    public function test($condition = false)
    {
        $this->test = $condition;
    }

    public function getToken()
    {
        if ($this->KRWLastTokenTime < time()) {
            $response                      = $this->run("token", array("username" => $this->username, "password" => $this->password));
            $_SESSION["KRW-Token"]         = $response["token"];
            $_SESSION["KRW-LastTokenTime"] = strtotime("+500 seconds");
            return $_SESSION["KRW-Token"];
        } else {
            return $_SESSION["KRW-Token"];
        }
    }

    private function run($operation, $parameters)
    {
        $this->curl = curl_init();
        curl_setopt_array($this->curl, array(
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL            => $this->apiUrl . $operation,
            CURLOPT_POST           => 1,
            CURLOPT_POSTFIELDS     => $parameters,
            CURLOPT_HTTPHEADER     => array('Token: ' . $this->token, ($this->test) ? 'Testmode: yes' : ''),
        ));
        $response = json_decode(curl_exec($this->curl), true);
        if ($response["result"] === "success") {
            return $response;
        } else {
            exit($response["error"]);
        }
    }

    public function isAvailable($domains)
    {
        if (is_array($domains)) {
            $domainList = array();
            foreach ($domains as $domain) {
                $domainList[] = array("domain" => $domain);
            }
        } else {
            $domainList[] = array("domain" => $domains);
        }
        return $this->run("whois", json_encode($domainList));
    }
    public function register($domain, $year = 1)
    {
        $domainInfo = array("domain" => $domain, "type" => "register", "year" => $year);

        return $this->run("domain", json_encode($domainInfo));
    }
    public function transfer($domain, $year = 1, $transfercode)
    {
        $domainInfo = array("domain" => $domain, "type" => "register", "year" => $year, "epp" => $transfercode);

        return $this->run("domain", json_encode($domainInfo));
    }
    public function __destruct()
    {
        curl_close($this->curl);
    }
}
