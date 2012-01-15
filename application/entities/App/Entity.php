<?php

namespace App;

/**
 * Abstract parent class for entities. It includes some utiliyu methods (e.g.
 * slugify).
 * 
 * @version 0.1.
 * @author Onur Yaman <onuryaman@gmail.com>
 */
abstract class Entity
{
    // Doctrine container instance.
    protected $_doctrineContainer;
    
    /**
     * @Id
     * @Column(type="integer")
     * @generatedValue(strategy="IDENTITY") 
     */
    protected $id;
    
    /**
     * @Column(type="datetime")
     */
    protected $createdAt;
    
    /**
     * @Column(type="datetime")
     */
    protected $updatedAt;
    
    public function __construct() {
        $this->_doctrineContainer = \Zend_Registry::get('doctrine');
        
        // set the creation and update datetime values.
        $this->createdAt = $this->updatedAt = new \DateTime('now');
    }
    
    /**
     * Getter magic method.
     * 
     * @param String $name Name of the object attribute to be fetched.
     * @return mixed
     */
    public function __get($name) {
        return $this->{$name};
    }
    
    /**
     * Setter magic method.
     * 
     * @param String $name Name of the object attribute to be updated.
     * @param mixed $value Value that is to be assigned to the corresponding
     *                     attribute.
     */
    public function __set($name, $value) {
        $this->{$name} = $value;
    }
    
    public function __isset($name) {
        return isset($this->{$name});
    }
    
    public function updated() {
        $this->updatedAt = new \DateTime('now');
    }
    
    public function save() {
        // fetch the entity manager.
        $em = \Zend_Registry::get('doctrine')->getEntityManager();
        
        $em->persist($this);
        $em->flush();
        
        return true;
    }
    
    public function remove() {
        // fetch the entity manager.
        $em = \Zend_Registry::get('doctrine')->getEntityManager();
        
        $em->remove($this);
        $em->flush();
        
        return true;
    }
    
    /**
     * Modifies a string to remove all non ASCII characters and spaces.
     */
    public static function slugify($text) {
        $map = array(
            // Cyrillic.
            '/щ/' => 'sch',
            '/ш/' => 'sh',
            '/ч/' => 'ch',
            '/ц/' => 'c',
            '/ю/' => 'yu',
            '/я/' => 'ya',
            '/ж/' => 'zh',
            '/а/' => 'a',
            '/б/' => 'b',
            '/в/' => 'v',
            '/г|ґ/' => 'g',
            '/д/' => 'd',
            '/е/' => 'e',
            '/ё/' => 'yo',
            '/з/' => 'z',
            '/и|і/' => 'i',
            '/й/' => 'y',
            '/к/' => 'k',
            '/л/' => 'l',
            '/м/' => 'm',
            '/н/' => 'n',
            '/о/' => 'o',
            '/п/' => 'p',
            '/р/' => 'r',
            '/с/' => 's',
            '/т/' => 't',
            '/у/' => 'u',
            '/ф/' => 'f',
            '/х/' => 'h',
            '/ы/' => 'y',
            '/э/' => 'e',
            '/є/' => 'ye',
            '/ї/' => 'yi',
            // Other.
            '/º|°/' => 0,
            '/¹/' => 1,
            '/²/' => 2,
            '/³/' => 3,
            '/à|á|å|â|ã|ä|ą|ă|ā|ª/' => 'a',
            '/@/' => 'at',
            '/æ/' => 'ae',
            '/ḃ/' => 'b',
            '/č|ç|¢|ć|ċ|ĉ|©/' => 'c',
            '/ď|ḋ|đ/' => 'd',
            '/€/' => 'euro',
            '/è|é|ê|ě|ẽ|ë|ę|ē|ė|ĕ/' => 'e',
            '/ƒ|ḟ/' => 'f',
            '/ģ|ğ|ĝ|ġ/' => 'g',
            '/ĥ|ħ/' => 'h',
            '/Ì|Í|Î|Ï/' => 'I',
            '/ì|í|î|ï|ĩ|ī|į|ı/' => 'i',
            '/ĵ/' => 'j',
            '/ķ/' => 'k',
            '/ł|ľ|ĺ|ļ/' => 'l',
            '/Ł/' => 'L',
            '/ṁ/' => 'm',
            '/ñ|ń|ņ|ň/' => 'n',
            '/ò|ó|ô|ø|õ|ö|ō|ð|ơ|ő/' => 'o',
            '/œ/' => 'oe',
            '/ṗ/' => 'p',
            '/ř|ŗ|ŕ|®/' => 'r',
            '/š|ś|ṡ|ş|ș/' => 's',
            '/ť|ț|ŧ|ţ|ṫ/' => 't',
            '/þ/' => 'th',
            '/ß/' => 'ss',
            '/™/' => 'tm',
            '/ù|ú|ů|û|ü|ū|ũ|ű|ŭ|ư|ų|µ/' => 'u',
            '/ẃ|ẅ|ẁ|ŵ/' => 'w',
            '/×/' => 'x',
            '/ÿ|ý|ỳ|ŷ|¥/' => 'y',
            '/Ž|Ż|Ź/' => 'Z',
            '/ž|ż|ź/' => 'z',
            '/\\s+/' => '-'
        );
        
        // modify text regarding to the map array.
        $text = preg_replace(
            array_keys($map),
            array_values($map),
            $text
        );
        
        // replace non letter or digits by "-".
        $text = preg_replace('~[^\\pL\d]+~u', '-', $text);

        // trim.
        $text = trim($text, '-');

        // transliterate.
        if (function_exists('iconv'))
        {
            $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
        }

        // lowercase.
        $text = strtolower($text);

        // remove unwanted characters.
        $text = preg_replace('~[^-\w]+~', '', $text);

        if (empty($text))
        {
            return 'n-a';
        }

        return $text;
    }
    
    public static function valueOrNull($value) {
        if (empty($value)) {
            return null;
        }
        
        return $value;
    }
}