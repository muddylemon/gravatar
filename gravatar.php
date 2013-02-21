<?php 
/**
 * 
 * @package php-gravatar
 * 
 * A simple and straightforward php class for generating gravatar urls
 * Suitable for dropping in just about anywhere
 * 
 * Usage:
 *  
 *    <img src="<?php echo gravatar::url('bob@bitcap.com',$params); ?>" />
 * 
 * Valid Params: 
 * 
 *      size - int 1 to 512
 *      rating - like movie ratings
 *      image - one of these: '404','mm','identicon','monsterid','wavatar','retro'
 * 
 */
class gravatar 
{

    /**
     * the public function of the class, returns the url of the gravatar image 
     * associated with the email passed in. 
     * 
     * @param string $email 
     * @param array  $params 
     * @return string 
     */
    public static function url($email, $params = NULL){
        
        return sprintf('%s.gravatar.com/avatar/%s?%s',
                        self::url_prefix(),
                        self::hashed($email),
                        self::params($params)
                       );
    }
        private static function url_prefix(){
            return ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? 'https://secure': 'http://www');
        }

        /**
         * Returns the md5 hash of the email supplied
         * @param string $email 
         * @return string
         */
        private static function hashed($email) {
            return hash('md5',trim(strtolower($email)));
        }

    /**
     * Creates an array of parameters used in the gravatar query string
     * if the params passed in are not available or invalid it returns the
     * default value set in the static attributes above. 
     * Returns a string to be appeneded to the gravatar url.
     * 
     * @param array $params 
     * @return string
     */

    private static function params($params){
        $valid = array();
        foreach ($params as $key => $value) {
            $valid[substr($key,0,1)] => self::validate($key,$value);
        }
        return $valid;
    }
    
    private static function validate($key,$value){
        if ($key == 'size') {
            return ($value > 512 || $value < 1) ? 80 : $value;
        }
        
        if ($key == 'rating') {
            return (in_array($value, array('g','pg','r','x'))) ? $value : 'g';
        }
        
        if ($key == 'image') {
            return (in_array($value,array('404','mm','identicon','monsterid','wavatar','retro'))) ? $value : '404';
        }
    }
}
