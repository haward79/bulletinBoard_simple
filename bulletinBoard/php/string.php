<?php

    function endsWith($str, $postfix, $caseSensitivity)
    {
        if($caseSensitivity)
        {
            if(strlen($str)>=strlen($postfix) && substr($str, strlen($str)-strlen($postfix))==$postfix)
                return true;
            else
                return false;
        }
        else
        {
            if(strlen($str)>=strlen($postfix) && strtolower(substr($str, strlen($str)-strlen($postfix)))==strtolower($postfix))
                return true;
            else
                return false;
        }
    }

    function isNotEmpty($str)
    {
        if($str != '')
            return true;
        else
            return false;
    }

    function toHtml($str)
    {
        $str = str_replace(' ', '&nbsp;', $str);
        $str = str_replace("\r\n", '<br />', $str);
        $str = str_replace("\n", '<br />', $str);
        $str = str_replace('"', '&quot;', $str);
        $str = str_replace('\'', '&apos;', $str);

        return $str;
    }

    function markAnchor($str)
    {
        $urlEndChars = array(' ', "\n", "\r", '&nbsp;', '<br />');

        for($i=0, $len=strlen($str); $i<$len; ++$i)
        {
            // Set ending flag.
            $endFlag = indexOf($str, $urlEndChars, $i);

            if($endFlag == -1)
                $endFlag = $len;

            // Extract current char to char marked by ending flag(excluded).
            $substr = substr($str, $i, $endFlag-$i);

            // Check if extracted string is a valid url.
            $matchResult = preg_match('/^https{0,1}:\/\/[.\/0-9a-zA-Z_=?&-]+$/', $substr);

            // Url found.
            if($matchResult === 1)
            {
                $url = substr($str, $i, $endFlag-$i);
                $markedStr = $markedStr . '<a href="'.$url.'" target="_blank">'.$url.'</a>';
                $markedStr = $markedStr.' ';

                // Prevent extracted substring from double checking.
                $i = $endFlag - 1;
            }
            // Url not found.
            else
                $markedStr = $markedStr . $str{$i};
        }

        return $markedStr;
    }

    function indexOf($str, $target, $offset)
    {
        for($i=$offset, $len=strlen($str); $i<$len; ++$i)
        {
            for($j=0, $arrlen=count($target); $j<$arrlen; ++$j)
            {
                if(substr($str, $i, strlen($target[$j])) == $target[$j])
                    return $i;
            }
        }

        return -1;
    }

