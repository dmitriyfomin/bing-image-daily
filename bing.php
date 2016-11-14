<?php

/**
 * Bing Image Daily (PHP > 5.3)
 * 
 * Script getting images from bing.com
 * 
 * @author Dmitry Fomin
 */
class BingImage {

    /**
     * Method curl_connect
     * 
     * @param string $url
     * @param int $port
     * @param string $user_agent
     * @param string $proxy
     * @return string URL (path of image for download)
     */
    public function curl_connect($url, $port = 80, $user_agent = 'Mozilla/5.0', $proxy = null) {
        if (function_exists('curl_init')) {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_PORT, $port);
            curl_setopt($ch, CURLOPT_USERAGENT, $user_agent);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            #curl_setopt ($ch, CURLOPT_REFERER, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_TIMEOUT, 10);
            if ($proxy) {
                curl_setopt($ch, CURLOPT_PROXY, $proxy);
            }
            $result = curl_exec($ch);
            curl_close($ch);
            if (empty($result)) {
                die('Couldn\'t connect to' . $url);
                exit;
            }
            return $result;
        }
    }

}

$img = (isset($_GET['img'])) ? htmlspecialchars($_GET['img']) : '';
switch ($img):
    default:      // today image by timezone of location of your server
        $f = BingImage :: curl_connect('http://www.bing.com/', 'Opera/9.80 (Windows NT 5.1; U; ru) Presto/2.8.131 Version/11.10');
        $f = preg_replace('|<!(.*)g_img={url: "|siU', '', $f);
        $f = preg_replace('|\",id(.*)|is', '', $f);
        $f = str_replace('\/', '/', $f);
        exit(header('Location: http://www.bing.com' . $f));
        break;
    case 'tomorrow':    // image of tomorrow by timezone of location of your server
        $f = BingImage :: curl_connect('http://www.bing.com/', 'Opera/9.80 (Windows NT 5.1; U; ru) Presto/2.8.131 Version/11.10');
        $f = preg_replace('|<!(.*)Im\': {url:\'|siU', '', $f);
        $f = preg_replace('|\', hash(.*)|is', '', $f);
        $f = str_replace('\/', '/', $f);
        exit(header('Location: http://www.bing.com' . $f));
        break;
endswitch;
