<?php

namespace App\Filters;

use Illuminate\Support\Facades\Auth;


/**
 * Routefilter
 *
 * @package Onvard\Filter\Filters
 */
class Filter
{

    /**
     * Invalidate the Browser Cache
     * Helpful Filter to tell the Browser to dump the Site's Cache.
     * For Example: If a User logs out and hits the 'back' Button of his Browser, he doesn't get served a cached copy.
     */
    public function invalidateBrowserCache() {
        $this->response->headers->set('Cache-Control','nocache, no-store, max-age=0, must-revalidate');
        $this->response->headers->set('Pragma','no-cache');
        $this->response->headers->set('Expires','Fri, 01 Jan 1970 00:00:00 GMT');
    }

    /**
     * Minify the HTML Output
     *
     * @see http://laravel-tricks.com/tricks/minify-html-output
     */
    public function minifyHTML(){
        $buffer = $this->response->getContent();
        if(strpos($buffer,'<pre>') !== false)
        {
            $replace = array(
                '/<!--[^\[](.*?)[^\]]-->/s' => '',
                "/<\?php/"                  => '<?php ',
                "/\r/"                      => '',
                "/>\n</"                    => '><',
                "/>\s+\n</"    				=> '><',
                "/>\n\s+</"					=> '><',
            );
        }
        else
        {
            $replace = array(
                '/<!--[^\[](.*?)[^\]]-->/s' => '',
                "/<\?php/"                  => '<?php ',
                "/\n([\S])/"                => '$1',
                "/\r/"                      => '',
                "/\n/"                      => '',
                "/\t/"                      => '',
                "/ +/"                      => ' ',
                '#^\s*//.+$#m'              => '', // remove single comment in JS
            );
        }
        $buffer = preg_replace(array_keys($replace), array_values($replace), $buffer);
        $this->response->setContent($buffer);
    }
}