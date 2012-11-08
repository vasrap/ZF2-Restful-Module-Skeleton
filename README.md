<h1>ZF2 Restful Module Skeleton</h1>
<h4></h4>
<p>
	This module skeleton provides foundation for working with the ZF2 Restful controller.
</p>
<p>
	To learn how to setup Zend Framework 2 click 
	<a target="_blank" href="http://framework.zend.com/wiki/pages/viewpage.action?pageId=42303506">here</a>. 
	Once setup, you can clone and place this skeleton under the "module/Main" folder.
	You will also have to change your project's config file (application.config.php) to include this module:
	<pre>
		...
		'modules' => array(
			...
			'Main',
			...
		),
		...
	</pre>
</p>
<p>
	Example URI implementations included:
	<ul>
		<li>/info.json - Loads InfoController::getList() and renders through PostProcessor/Json.php</li>
	</ul>
</p>
<h3>Suggestions</h3>
<p>
In an effort to make this module better, I encourage you to submit any API or REST related feature requests 
in the "Issues" section.
</p>