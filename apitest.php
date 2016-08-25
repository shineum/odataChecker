<?php

$version = "v0.0.1";

$url = "";
$requestBody = "";
$methodData = Array("GET"=>0, "POST"=>0, "PUT"=>0, "PATCH"=>0, "DELETE"=>0);

if (count($_POST) > 0) {
        $url = $_POST['api'];
//      $proxy = "localhost:9001";
        $requestMethod = $_POST['requestMethod'];
        $methodData[ $requestMethod ] = 1;
        $requestBody = $_POST['requestBody'];
        $requestHeaderRaw = $_POST['requestHeader'];
        $requestHeaders = Array();
        $requestHeaderLines = explode("\n", $requestHeaderRaw);
        foreach($requestHeaderLines as $line) {
                if (strpos($line, ":") !== false) {
                        $requestHeaders[] = trim($line);
                }
        }

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
//      curl_setopt($ch, CURLOPT_PROXY, $proxy);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $requestBody);
//      curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_HEADER, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $requestMethod);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $requestHeaders);

        $response = curl_exec($ch);

        $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
        $rHeader = substr($response, 0, $header_size);
        $rData = substr($response, $header_size);
        $rError = curl_error($ch);

        curl_close($ch);
} else {
        $requestHeaderRaw = "Content-Type: application/json\n";
}

?>
<html>
<body style='background-color:#ccffcc'>
                <div style='display:table;'>
                        <div style='float:left;width:150px;'>Version</div>
                        <div style='float:left;'><? echo $version; ?></div>
                </div>
        <form method='post'>
                <div style='display:table;padding-top:10px;'>
                        <div style='float:left;width:150px;'>API URL</div>
                        <div style='float:left;'><input type='text' name='api' value='<? echo htmlspecialchars($url, ENT_QUOTES, "UTF-8"); ?>' size='100'></div>
                </div>
                <div style='display:table;padding-top:10px;'>
                        <div style='float:left;width:150px;'>Request Method</div>
                        <div style='float:left;padding-right:15px;'>
                                <select name='requestMethod'>
<?
        foreach($methodData as $tName=>$tSelected) {
                printf("<option value='%s' %s>%s</option>", $tName, ($tSelected == 1) ? "selected" : "", $tName);

        }
?>
                                </select>
                        </div>
                </div>
                <div style='display:table;padding-top:10px;'>
                        <div style='float:left;width:150px;'>Request Header</div>
                        <div style='float:left'>
                                <textarea cols='100' rows='5' name='requestHeader'><? echo $requestHeaderRaw; ?></textarea>
                        </div>
                </div>
                <div style='display:table;padding-top:10px;'>
                        <div style='float:left;width:150px;'>Request Body</div>
                        <div style='float:left'>
                                <textarea cols='100' rows='10' name='requestBody'><? echo $requestBody; ?></textarea>
                        </div>
                </div>
                <div style='display:table;padding-top:10px;'>
                        <div style='float:left;width:150px;'>&nbsp;</div>
                        <div style='float:left;padding-left:550px;'><input type='submit'></div>
                </div>
        </form>
                <div style='display:table;padding-top:10px;'>
                        <div style='float:left;width:150px;'>Received Header</div>
                        <div style='float:left'>
                                <textarea cols='100' rows='20' readonly><? echo $rHeader; ?></textarea>
                        </div>
                </div>
                <div style='display:table;'>
                        <div style='float:left;width:150px;'>Received Data</div>
                        <div style='float:left'>
                                <textarea cols='100' rows='20' readonly><? echo $rData; ?></textarea>
                        </div>
                </div>
                <div style='display:table;'>
                        <div style='float:left;width:150px;'>Error Message</div>
                        <div style='float:left'>
                                <textarea cols='100' readonly><? echo $rError; ?></textarea>
                        </div>
                </div>
</body>
</html>
