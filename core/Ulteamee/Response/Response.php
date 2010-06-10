<?php


 function setHeader($format)
        {
                $arrFormats = array
                (
                        // basic types

                        "javascript" => "Content-type: application/javascript",
                        "json" => "Content-type: application/json",
                        "pdf" => "Content-type: application/pdf",
                        "text" => "Content-type: text/plain",
                        "xml" => "Content-type: text/xml",

                        // complex types

                        "atom" => "Content-type: text/xml",
                        "bibliographic" => "Content-type: application/x-research-info-systems",
                        "embed_html_js" => "Content-type: application/javascript",
                        "ris" => "Content-type: text/plain",
                        "rss" => "Content-type: text/xml",
                        "xerxes" => "Content-type: text/xml",
                        "text-file" => "Content-Disposition: attachment; Content-type: text/plain; filename=download.txt",
                        "ris-file" => "Content-Disposition: attachment; Content-type: text/plain; filename=download.ris"
                );

                if ( array_key_exists( $format, $arrFormats ) )
                {
                        header( $arrFormats[$format] . "; charset=UTF-8" );
                }
        }


        /**
* Controller helper; redirects the user to another page
*/
    public function redirect($url, $httpStatus = 302) {
        header('Location: ' . $url, true, $httpStatus);
        // In case the client does not follow location headers
        die("<a href=\"$url\">You have been redirected</a>");
    }