<h2>iFormBuilder api Version 6 (v60)</h2>
<p>PHP framework to interact with iFormBuilder api v60 resources</p>
work in progress :)
<ul>
    <li>Start interacting with api quickly (generates access tokens).</li>
    <li>Provides familiar ORM layer for mapping iFormBuidler resources.</li>
    <li>Utilize v60 features</li>
</ul>

<h2>How to use</h2>
<p>Must add credentials to Creds/Auth.php and Creds/Profile.php</p>
<p>use zerion_autoload.php.  If using composer, include composer autoload.php</p>

<pre>
<div>
require_once 'zerion_autoload.php';
use Iform\Resources\IformResource;
</div>
</pre>
<p>Use container to instantiate resources</p>
<pre>
<div>
$pageResource = IformResource::page();
$optionListResource = IformResource::optionList();
$profileResource = IformResource::profile();
$userResource = IformResource::user();
</div>
</pre>
<p>Following will require a parent identifier</p>
<pre>
<div>
$pageId = 123123;
$recordResource = IformResource::record($pageId);
$elementsResource = IformResource::elements($pageId);

$optionListId = 12312345;
$optionsResource = IformResource::options($optionListId);
</div>
</pre>
