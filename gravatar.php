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
 *      secure - true or false, return the https version or not
 * 
 */
class gravatar 
{

    protected $default_size                 = 80;
    protected $default_rating               = 'g';
    protected $default_image                = false;
    
    protected $use_secure_url               = false;
    
    protected static $valid_ratings         = array('g','pg','r','x');
    protected static $valid_images          = array('404','mm','identicon','monsterid','wavatar','retro');
    protected static $gravatar_url          = 'http://www.gravatar.com/avatar/';
    protected static $secure_gravatar_url   = 'https://secure.gravatar.com/avatar/';

    /**
     * the public function of the class, returns the url of the gravatar image 
     * associated with the email passed in. 
     * 
     * @param string $email 
     * @param array  $params 
     * @return string 
     */
    public static function url($email, $params = NULL){
        
        $url  = self::select_gravatar_url($params);
        $url .= self::hash_email($email);
        $url .= self::append_params($params);
        
        return $url;
    }

    /**
     * Returns either the secure url or non-secure depending on 
     * the param 'secure'
     * @param array $params 
     * @return string
     */
    private static function select_gravatar_url($params){
        if (isset($params['secure']) && $params['secure'] == true) {
            return self::$secure_gravatar_url;
        }
        return self::$gravatar_url;
    }

    /**
     * Returns the md5 hash of the email supplied
     * @param string $email 
     * @return string
     */
    private static function hash_email($email) {
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
    private static function append_params($params){
        
        $appended = array(  's' => self::valid('size',$params),
                            'r' => self::valid('rating',$params),
                            'd' => self::valid('image',$params)   );

        return '?' . http_build_query($appended);
    }

    /**
     * Evaluates the parameters passed in and, depending on the type 
     * of validation requested, returns the value or the default value
     * 
     * @param string $type 
     * @param array $params 
     * @return string
     */
    private static function valid($type,$params){
        
        switch ($type) {
            case 'size':
                return self::valid_size($params);
                break;
            case 'rating':
                return self::valid_rating($params);
                break;
            case 'image':
                return self::valid_image($params);
                break;
        }

    }

    /**
     * Evaluates the value of the 'size' parameter and 
     * returns the value or the default
     * @param array $params 
     * @return string
     */
    private static function valid_size($params) {
        $size = (int) $params['size'];
        if ($size > 512 || $size < 1) {
            return self::$default_size;
        }
        return $params['size'];
    }

    /**
     * Evaluates the value of the 'rating' parameter and 
     * returns the value or the default
     * @param array $params 
     * @return string
     */
    private static function valid_rating($params){
        if (!isset($params['rating'] || !in_array($params['rating'], self::$valid_ratings)){
            return self::$default_rating;
        }
        return $params['rating'];
    }

    /**
     * Evaluates the value of the 'image' parameter and 
     * returns the value or the default
     * @param array $params 
     * @return string
     */
    private static function valid_image($params) {
        if (!isset($params['image'] || !in_array($params['image'],self::$valid_images)) {
            return self::$default_image;
        }
        return $params['image'];
    }
}