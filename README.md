<h1>ZF Restful Module Skeleton (BETA)</h1>
<h4></h4>
<p>
  <strong>Based on ZF2 application skeleton</strong>, this module skeleton provides 
  foundation for working with ZF2 Restful and Action controllers.
</p>
<p>
  To learn how to setup Zend Framework 2 click 
  <a href="http://packages.zendframework.com/docs/latest/manual/en/zend.mvc.quick-start.html">here</a>. 
  Once setup, you can clone and 
  place this skeleton under the "module" folder.
</p>
<p>
  This module already implements the following URIs:
  <ul>
    <li>/ - Loads IndexController::indexAction() and renders index/index.phtml</li>
    <li>/info.json - Loads InfoController::getList() and renders through json.phtml</li>
    <li>/category.json - Loads CategoryController::getList() and renders through json.phtml</li>
    <li>/thumb.json/id1 - Loads ThumbController::get('id1') and renders through json.phtml</li>
  </ul>
</p>
<p>
  Currently, RestfulController implementations, when working with the HTTP methods instead of actions, 
  need to wrap the return array as follows:
  <pre>return array('message' => array('foo' => 'bar'));</pre>
  This will get rendered as follows:
  <br />
  <pre>{"foo":"bar"}</pre>
</p>