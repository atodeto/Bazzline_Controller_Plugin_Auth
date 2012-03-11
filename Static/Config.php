<?php
/**
 * Bazzline config class
 * Needs 'bazzline.ini' in application/config
 *
 * @author artodeto
 * @since 2012-03-08
 */
class Bazzline_Static_Config
{
    /**
     * Getter for defined propertie in bazzline.ini
     *
     * @author artodeto
     * @param string $propertie
     * @param mixed $defaultValue
     * @return mixed, null or string
     * @since 2012-03-08
     */
    public static function get($propertie, $defaultValue = null)
    {
        $config = self::_getConfig();
        $value = null;

        if (!is_null($config)) {
            if (!is_array($propertie)) {
                $properties = explode('.', $propertie);
            }

            $value = self::_getPropertieFromConfig($properties, $config);
        }

        if (is_null($value)
            && !is_null($defaultValue)) {
            $value = $defaultValue;
        }

        return $value;
    }

    /**
     * Getter for bazzline.ini
     *
     * @author artodeto
     * @return Zend_Config_Ini 
     * @since 2012-03-08
     */
    protected static function _getConfig()
    {
        $config = null;
        $index = self::_getIndexKey();

        if (Zend_Registry::isRegistered($index)) {
            $config = Zend_Registry::get($index);
        } else {
            $filePath = self::_getFilePath();
            if (file_exists($filePath)) {
                $config = new Zend_Config_Ini($filePath);
                Zend_Registry::set($index, $config);
            }
        }

        return $config;
    }

    /**
     * Recursive getter for properties
     *
     * @author artodeto
     * @param array $properties
     * @param Zend_Config $config
     * @return string
     * @since 2012-03-08
     */
    protected static function _getPropertieFromConfig(array $properties, Zend_Config $config)
    {
        $value = null;

        if (count($properties) > 0) {
            $propertie = array_shift($properties);

            if ($config->__isset($propertie)) {
                if (count($properties) > 0) {
                    $config = $config->$propertie;
                    $value = self::_getPropertieFromConfig($properties, $config);
                } else {
                    $value = $config->$propertie;
                }

            }
        }

        return $value;
    }

    /**
     * Returns file path from config file
     *
     * @author artodeto
     * @return string 
     * @since 2012-03-08
     */
    protected static function _getFilePath()
    {
        $filePath = APPLICATION_PATH.'/configs/bazzline.ini';

        return $filePath;
    }

    /**
     * Returns config index key
     *
     * @author artodeto
     * @return string
     * @since 2012-03-08
     */
    protected static function _getIndexKey()
    {
        $indexKey = 'net_bazzline_config';

        return $indexKey;
    }
}
