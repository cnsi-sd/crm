<?php

namespace App\Console\Commands\ImportMessages\Beautifier;

class AmazonBeautifierMail
{
    public static function getCustomerMessage($mail_html) {
        $get_from = '<h4 style="color:black"><strong>Message: </strong></h4>';
        $cursor_start_reading = strpos($mail_html, $get_from);
        $new_html = self::getHtmlElement('table', $mail_html, $cursor_start_reading);
        if ($new_html === false)
            $new_html = $mail_html;

        // trying to remove previous response
        $cursor_first_reply = strpos($new_html, '> a écrit :');
        if ($cursor_first_reply !== false) {
            $new_html = substr($new_html, 0, $cursor_first_reply);
        }

        // trying to remove previous response
        $cursor_div_reply = strpos($new_html, 'divRplyFwdMsg');
        if ($cursor_div_reply !== false) {
            $length_first_part = strpos($new_html, '<div', $cursor_div_reply - 30); // getting the position from which cut the string
            $cursor_end_th = self::getPositionClosingTag('th', $new_html, $cursor_div_reply); // getting the position until cut the string
            $new_html = substr($new_html, 0, $length_first_part) . substr($new_html, $cursor_end_th); // extract chunk of a string
        }

        return self::cleanHtml($new_html);
    }

    /**
     * From an HTML text, try to get an element by tag name from specified position
     *
     * @param string $tag
     * @param string $html
     * @param integer $cursor_start_reading
     *
     * @return boolean|string
     */
    protected static function getHtmlElement($tag, $html, $cursor_start_reading = 0) {
        $open_tag = '<' . $tag;

        $cursor_open_tag = strpos($html, $open_tag, $cursor_start_reading);
        $cursor_close_tag = self::getPositionClosingTag($tag, $html, $cursor_open_tag + strlen($open_tag));

        if ($cursor_close_tag === false)
            return false;

        return substr($html, $cursor_open_tag, $cursor_close_tag - $cursor_open_tag);
    }

    /**
     * Get the position of the last closing tag from a initial position
     *
     * @param string $tag
     * @param string $html
     * @param integer $position_start
     * @param integer $depth
     *
     * @return boolean|integer
     */
    protected static function getPositionClosingTag($tag, $html, $position_start, $depth = 0) {
        $open_tag = '<' . $tag;
        $closing_tag = '</' . $tag . '>';

        // get position first closing_tag
        $cursor_closing_tag = strpos($html, $closing_tag, $position_start) + strlen($closing_tag);

        if ($cursor_closing_tag < $position_start)
            return false;

        // get content between position_start and first closing_tag
        $content = substr($html, $position_start, $cursor_closing_tag - $position_start);

        // get position first open_tag
        $hasOpenTag = strpos($content, $open_tag);

        // if not open_tag found
        if ($hasOpenTag === false) {
            if ($depth <= 0)
                return $cursor_closing_tag;
            return self::getPositionClosingTag($tag, $html, $cursor_closing_tag, $depth - 1);
        }
        else
        {
            $depth = $depth + substr_count($content, $open_tag) - 1;
            return self::getPositionClosingTag($tag, $html, $cursor_closing_tag, $depth);
        }
    }

    /**
     * Remove tags and transform break-line with <br/> tag
     *
     * @param string $html
     *
     * @return string
     */
    protected static function cleanHtml($html) {
        $res_strip_tag = preg_replace('/<[^>]+>/', "<br/>", $html);
        $res_str_replace = str_replace("\n", "<br/>", $res_strip_tag);
        $res_str_replace = str_replace("\t", "<br/>", $res_str_replace);
        return preg_replace('/(<br\/> *){2,}/', '<br/>', $res_str_replace);
    }
}
