--TEST--
Scope on write and read property
--FILE--
<?php
class Hero
{
   public $public = 'public';
   protected $protected = 'protected';
   private $private = 'private';
   static public $spublic = 'spublic';
   static protected $sprotected = 'sprotected';
   static private $sprivate = 'sprivate';

   public function touch () {
        echo "WRITE :\n";
        $temp = $this->public;
        $temp = $this->protected;
        $temp = $this->private;
        echo "\n\nREAD :\n";
        $this->public = null;
        $this->protected = null;
        $this->private = null;
        $this->ninit = null;
   }
/* Not implement
   static function stouch () {
        $temp = self::$spublic;
        $temp = self::$sprotected;
        $temp = self::$sprivate;
        self::$spublic = null;
        self::$sprotected = null;
        self::$sprivate = null;
        
   }
*/
}

aop_add_before('write Hero->*', function (AopTriggeredJoinPoint $tjp) {
    echo "empty ".$tjp->getTriggeringPropertyName()."\n";
});

aop_add_before('public Hero->*', function (AopTriggeredJoinPoint $tjp) {
    echo "public ".$tjp->getTriggeringPropertyName()."\n";
});

aop_add_before('protected Hero->*', function (AopTriggeredJoinPoint $tjp) {
    echo "protected ".$tjp->getTriggeringPropertyName()."\n";
});

aop_add_before('private Hero->*', function (AopTriggeredJoinPoint $tjp) {
    echo "private ".$tjp->getTriggeringPropertyName()."\n";
});

aop_add_before('!public Hero->*', function (AopTriggeredJoinPoint $tjp) {
    echo "!public ".$tjp->getTriggeringPropertyName()."\n";
});

aop_add_before('static Hero->*', function (AopTriggeredJoinPoint $tjp) {
    echo "static ".$tjp->getTriggeringPropertyName()."\n";
});

aop_add_before('i!static Hero->*', function (AopTriggeredJoinPoint $tjp) {
    echo "!static ".$tjp->getTriggeringPropertyName()."\n";
});


aop_add_before('static public|private Hero->*', function (AopTriggeredJoinPoint $tjp) {
    echo "static public|private".$tjp->getTriggeringPropertyName()."\n";
});

$wizard = new Hero();
$wizard->touch();
//Hero::stouch();
--EXPECT--
WRITE :
public public
!static public
protected protected
!public protected
!static protected
private private
!public private
!static private


READ :
empty public
public public
!static public
empty protected
protected protected
!public protected
!static protected
empty private
private private
!public private
!static private
empty ninit
public ninit
!static ninit

