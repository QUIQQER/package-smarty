<?php
/**
 * Smarty Plugin
 *
 * @package    com.pcsg.pms.smarty
 * @subpackage plugins
 *
 * @author     PCSG - Henning
 */


/**
 * Smarty {text_passage} function plugin
 *
 * Type:     function<br>
 * Name:     text_passage<br>
 *
 * @param array $params - parameters
 * @param Smarty $smarty
 *
 * @return string
 * @author PCSG
 *
 */
function smarty_function_text_passage($params, $smarty)
{
    if (!isset($params['text'])) {
        return '';
    }

    if (!isset($params['start'])) {
        return '';
    }

    if (!isset($params['end'])) {
        return '';
    }

    $text  = $params['text'];
    $start = (int)$params['start'];
    $end   = (int)$params['end'];

    if (!$start && \strlen($text) < $end) {
        if (isset($params['striphtml'])) {
            $text = \strip_tags($text);
        }

        return $text;
    }


    $text = \htmlspecialchars_decode($text);

    if (isset($params['striphtml'])) {
        $text = \strip_tags($text);
    }

    $last = \mb_substr($text, $end - 1, $end);

    if (\preg_match('/[^a-zA-Z0-9]/i', $last)) {
        // bei ganzen Wörtern abschneiden
        if (isset($params['wholewords'])) {
            $text = preg_replace(
                '/\s+?(\S+)?$/',
                '',
                \mb_substr($text, $start, $end + 3)
            );
        } else {
            $text = \mb_substr($text, $start, $end + 3);
        }
    } else {
        // bei ganzen Wörtern abschneiden
        if (isset($params['wholewords'])) {
            $text = \preg_replace(
                '/\s+?(\S+)?$/',
                '',
                \mb_substr($text, $start, $end)
            );
        } else {
            $text = \mb_substr($text, $start, $end);
        }
    }

    if (isset($params['tidy']) && \class_exists('tidy')) {
        $tidy = new \tidy();

        $config = [
            "char-encoding" => "utf8",
            'output-xhtml'  => true
        ];

        $tidy->parseString($text, $config, 'utf8');
        $tidy->cleanRepair();

        $text = $tidy;
        $text = QUI\Utils\DOM::getInnerBodyFromHTML($text);
    }

    if (isset($params['append'])) {
        if (\mb_strlen($params['text']) > \mb_strlen($text)) {
            $text .= $params['append'];
        }
    }

    return $text;
}
