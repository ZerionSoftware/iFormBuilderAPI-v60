<h2>iFormBuilder api Version 6 (v60)</h2>
<p>PHP framework to interact with iFormBuilder api v60 resources</p>
work in progress :)
<ul>
    <li>Start interacting with api quickly (generates access tokens).</li>
    <li>Provides familiar ORM layer for mapping iFormBuidler resources.</li>
    <li>Utilize v60 features</li>
<ul>

<h2>How to use</h2>
<p>Must add credentials to Creds/Auth.php and Creds/Profile.php</p>
<pre>
<code>

require_once 'zerion_autoload.php';

use Iform\Resources\IformResource;

$pageResource = IformResource::page();


</code>
</pre>

<pre>
<code>
//return an collection of all pages in profile
$allPages = $pageResource->fetchAll();

//filter pages by type
$filteredPages = $pageResource->where('data_type(="7")')->fetchAll();

//require all fields
$allFields = $pageResource->withAllFields()->fetchAll();

//single
$pageId = 123123;
$page = $pageResource->fetch($pageId);

//update
values = ['name' => 'new_test'];
$pageId = 123123;
$pageResource->update($pageId, $values);

//create page
$values = ['name' => 'test', 'label' => 'This is a test'];
$pageId = $pageResource->create($values);

//delete
$pageId = 123123;
$pageResource->delete($pageId);

//fetch email alerts for page
$pageId = 123123;
$pageResource->alerts($pageId)->fetchAll();

//fetch callbacks for page
$pageId = 123123;
$pageResource->http($pageId)->fetchAll();

//fetch localizations for page
$pageId = 123123;
$pageResource->localizations($pageId)->fetchAll();

//fetch assignments for page
$pageId = 123123;
$pageResource->assignments($pageId)->fetchAll();

//all methods consist for attributes
$values = [
        "language_code"=> "es",
        "label"=> "inspección de la construcción"
];

$pageResource->localizations($pageId)->update($values);



</code>
</pre>