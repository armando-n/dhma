<?php
require_once dirname(__FILE__) . '\..\..\WebContent\views\HomeView.class.php';
require_once dirname(__FILE__) . '\..\..\WebContent\views\HeaderView.class.php';
require_once dirname(__FILE__) . '\..\..\WebContent\views\FooterView.class.php';

class HomeViewTest extends PHPUnit_Framework_TestCase {
    
    public function testShow() {
        ob_start();
        HomeView::show();
        $output = ob_get_clean();
        
        $this->assertTrue(stristr($output, '<section id="site-info">') !== false,
            'It should call show and display the home view');
    }
    
}
?>