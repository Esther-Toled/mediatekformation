<?php

namespace App\Test;

use App\Entity\Formation;
use DateTime;
use PHPUnit\Framework\TestCase;

/**
 * Description of FormationTest
 *
 * @author toled
 */
class FormationTest extends TestCase {
    
    public function testGetPublisheAtString(){
        $Formation = new Formation();
        $Formation->setTitle("Formation test");
        $Formation->setPublishedAt(new DateTime("2026-01-04"));
        $this->assertEquals("04/01/2026", $Formation->getPublishedAtString());
    }
    
}
