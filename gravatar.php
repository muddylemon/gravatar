<?php 
/*
    gravatar 
        A simple and straightforward php class for generating gravatar urls
        Suitable for dropping in to just about anywhere
        
        
        
    usage: <img src="<?php echo gravatar::url('bob@bitcap.com',$params); ?>" />
    params: 
        size - int 0 to 512
        rating - like movie ratings
        image - one of these: '404','mm','identicon','monsterid','wavatar','retro'
        secure - true or false, return the https version or not

*/
class gravatar 
{

    protected $default_size                 = 80;
    protected $default_rating               = 'g';
    protected $default_image                = false;
    
    protected $use_secure_url               = false;
    
    protected static $valid_ratings         = array('g','pg','r','x');
    protected static $valid_defaults        = array('404','mm','identicon','monsterid','wavatar','retro');
    protected static $gravatar_url          = 'http://www.gravatar.com/avatar/';
    protected static $secure_gravatar_url   = 'https://secure.gravatar.com/avatar/';

    public static function url($email, $params = NULL){
        
        $url  = self::select_gravatar_url($params);
        $url .= self::hash_email($email);
        $url .= self::append_params($params);
        
        return $url;
    }

    public static function select_gravatar_url($params){
        if (isset($params['secure']) && $params['secure'] == true) {
            return self::$secure_gravatar_url;
        }
        return self::$gravatar_url;
    }

    public static function hash_email($email) {
        return hash('md5',trim(strtolower($email)));
    }

    public static function append_params($params){
        
        $appended = array();
        
        if (isset($params['size'])){
            $appended['s'] = self::valid_size($params['size']);
        }
        
        if (isset($params['rating'])){
            $appended['r'] = self::valid_rating($params['rating']);
        }
        
        if (isset($params['default'])) {
            $appended['d'] = self::valid_default($params['default']);
        }
        
        if (count($appended) > 0) {
            return '?' . http_build_query($appended);
        }
        return '';
    }
    
    public static function valid_size($size) {
        $size = (int) $size;
        if ($size > 512 || $size < 0) {
            return $default_size;
        }
        return $size;
    }
    
    public static function valid_rating($rating){
        if (!in_array($rating, self::$valid_ratings)){
            return self::$default_rating;
        }
        return $rating;
    }
    
    public static function valid_default($default) {
        if (!in_array($default,self::$valid_defaults)) {
            return self::$default_image;
        }
        return $default;
    }
}