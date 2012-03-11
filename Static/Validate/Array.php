<?php

class Bazzline_Static_Validate_Array implements Bazzline_Interface_Static_Validate
{
    const IS_FILLED = 'isFilled';

    /**
     * Bazzline_Validate::IsInt
     * Validates if int is set and if typecast returns correct value
     *
     * @author artodeto(AT)arcor(DOT)de
     * @access public
     * @param int $int
     * @param array $params, isFilled => true (checks if count > 0),
     * @return boolean
     * @since 2012-03-04
     */
    public static function isValid($array, $params = array())
    {
        $isValid = false;

        if (is_array($array)) 
        {
            $isValid = true;

            if ((isset($params[self::IS_FILLED]))
                && ($params[self::IS_FILLED] === true)) 
            {
                $isValid = false;
                if (count($array) > 0)
            {
                    $isValid = true;
                }
            }
        }

        return $isValid;
    }
}