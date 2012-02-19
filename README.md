<h1>ZF2 Restful Module Skeleton</h1>
<h4></h4>
<p>
  <strong>Based on ZF2 application skeleton</strong>, this module skeleton provides 
  foundation for working with the ZF2 Restful controller.
</p>
<p>
  To learn how to setup Zend Framework 2 click 
  <a target="_blank" href="http://packages.zendframework.com/docs/latest/manual/en/zend.mvc.quick-start.html">here</a>. 
  Once setup, you can clone and 
  place this skeleton under the "module/Main" folder & change zf2 project config file 
  <strong>/config/application.config.php</strong>:
  <pre>
  ...
    'modules' => array(
        'Main',
    ),
  ...
  </pre>
</p>
<p>
  Example URI implementations included:
  <ul>
    <li>/info.json - Loads InfoController::getList() and renders through PostProcessor/Json.php</li>
    <li>/category.json - Loads CategoryController::getList() and renders through PostProcessor/Json.php</li>
    <li>/thumb.json/id1 - Loads ThumbController::get('id1') and renders through PostProcessor/Json.php</li>
  </ul>
</p>
