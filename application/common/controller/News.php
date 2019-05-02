<?php


namespace app\common\controller;


use think\Controller;
use think\Request;

class News extends Controller
{
    /**
     * @param Request $request
     * @return array
     */
    public function index(Request $request)
    {
        if ($request->isPost()) {
            $url = $request->param('url');
            $str = json_decode($this->req_curl($url), true);
            $array = explode("\n", $str["data"]["content"]);
            for ($i = 0; $i < count($array); $i++) {
                if ($newStr = strstr($array[$i], '<img')) {
                    $array[$i] = $this->get_images_from_html($array[$i])[0];
                } else {
                    $array[$i] = strip_tags($array[$i]);
                    $array[$i] = str_replace(" ", "", $array[$i]);
                }
            }
            $count = count($array);
            for ($i = 0; $i < $count; $i++) {
                if ($array[$i] == "") {
                    unset($array[$i]);
                }
            }
            $contents = [];
            $array = array_values($array);
            for ($i = 0; $i < count($array); $i++) {
                if (strstr($array[$i], "http")) {
                    $contents[$i] = ['type' => 'img', 'content' => $array[$i]];
                } else {
                    $contents[$i] = ['type' => 'text', 'content' => $array[$i]];
                }
            }
            return ['status'=>200,'msg'=>'查询成功！',
                'date' => date("Y-m-d H:i:s", $str["data"]["pub_time"]),
                'authorName' => $str["data"]["headpic"],
                'title' => $str["data"]["title"],
                'contents' => $contents];
        }
        return config('PARAMS_ERROR');
    }

    /**
     * @param $content
     * @return null
     *  从HTML文本中提取所有图片
     */
    private function get_images_from_html($content)
    {
        $pattern = "/<img.*?src=[\'|\"](.*?)[\'|\"].*?[\/]?>/";
        preg_match_all($pattern, htmlspecialchars_decode($content), $match);
        if (!empty($match[1])) {
            return $match[1];
        }
        return null;
    }

    private function req_curl($url, &$status = null, $options = array())
    {
        $options = array_merge(array(
            'follow_local' => true,
            'timeout' => 30,
            'max_redirects' => 4,
            'binary_transfer' => false,
            'include_header' => false,
            'no_body' => false,
            'cookie_location' => dirname(__FILE__) . '/cookie',
            'useragent' => 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1',
            'post' => array(),
            'referer' => null,
            'ssl_verifypeer' => 0,
            'ssl_verifyhost' => 0,
            'headers' => array(
                'Expect:'
            ),
            'auth_name' => '',
            'auth_pass' => '',
            'session' => false
        ), $options);
        $options['url'] = $url;

        $s = curl_init();

        if (!$s) return false;

        curl_setopt($s, CURLOPT_URL, $options['url']);
        curl_setopt($s, CURLOPT_HTTPHEADER, $options['headers']);
        curl_setopt($s, CURLOPT_SSL_VERIFYPEER, $options['ssl_verifypeer']);
        curl_setopt($s, CURLOPT_SSL_VERIFYHOST, $options['ssl_verifyhost']);
        curl_setopt($s, CURLOPT_TIMEOUT, $options['timeout']);
        curl_setopt($s, CURLOPT_MAXREDIRS, $options['max_redirects']);
        curl_setopt($s, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($s, CURLOPT_FOLLOWLOCATION, $options['follow_local']);
        curl_setopt($s, CURLOPT_COOKIEJAR, $options['cookie_location']);
        curl_setopt($s, CURLOPT_COOKIEFILE, $options['cookie_location']);
        if (!empty($options['auth_name']) && is_string($options['auth_name'])) {
            curl_setopt($s, CURLOPT_USERPWD, $options['auth_name'] . ':' . $options['auth_pass']);
        }
        if (!empty($options['post'])) {
            curl_setopt($s, CURLOPT_POST, true);
            curl_setopt($s, CURLOPT_POSTFIELDS, $options['post']);
        }
        if ($options['include_header']) {
            curl_setopt($s, CURLOPT_HEADER, true);
        }
        if ($options['no_body']) {

            curl_setopt($s, CURLOPT_NOBODY, true);
        }
        if ($options['session']) {
            curl_setopt($s, CURLOPT_COOKIESESSION, true);
            curl_setopt($s, CURLOPT_COOKIE, $options['session']);
        }
        curl_setopt($s, CURLOPT_USERAGENT, $options['useragent']);
        curl_setopt($s, CURLOPT_REFERER, $options['referer']);
        $res = curl_exec($s);
        $status = curl_getinfo($s, CURLINFO_HTTP_CODE);
        curl_close($s);
        return $res;
    }

}


